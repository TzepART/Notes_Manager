<?php

namespace Tzepart\NotesManagerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Tzepart\NotesManagerBundle\Entity\Circle;
use Tzepart\NotesManagerBundle\Entity\Labels;
use Tzepart\NotesManagerBundle\Entity\Notes;
use Tzepart\NotesManagerBundle\Controller\LabelsController;

/**
 * Notes controller.
 *
 */
class NotesController extends Controller
{

    /**
     * Lists all Notes entities.
     *
     */
    public function indexAction()
    {
        $em    = $this->getDoctrine()->getManager();
        $notes = $em->getRepository('NotesManagerBundle:Notes')->findAll();

        return $this->render(
            'notes/index.html.twig',
            array(
                'notes' => $notes,
            )
        );
    }

    /**
     * Get user id
     * @return integer $userId
     */
    protected function getCurrentUserObject()
    {
        $user = $this->get('security.context')->getToken()->getUser();

        return $user;
    }

    /**
     * Creates a new Notes entity.
     *
     */
    public function newAction(Request $request)
    {
        $note             = new Notes();
        $arSectors        = [];
        $arCircles        = [];
        $arSectorsObjects = [];
        $arLayersObjects  = [];
        $arLayersId       = [];
        $countLayers      = 5;
        $arSelectCirclesId = 0;
        $form             = $this->createForm('Tzepart\NotesManagerBundle\Form\NotesType', $note);
        $form->handleRequest($request);

        $user             = $this->getCurrentUserObject();
        $arCirclesObjects = $user->getCircles();

        if(!empty($request->get("select_circle"))){
            $arSelectCirclesId = $request->get("select_circle");
        }

        foreach ($arCirclesObjects as $key => $circlesObject) {
            $arCircles[$key]["id"]   = $circlesObject->getId();
            $arCircles[$key]["name"] = $circlesObject->getName();
            if ($circlesObject->getId() == $arSelectCirclesId) {
                $arLayersObjects  = $circlesObject->getLayers();
                $arSectorsObjects = $circlesObject->getSectors();
            }
        }


        if (!empty($arLayersObjects) && !empty($arSectorsObjects)) {
            $countLayers = count($arLayersObjects);
            foreach ($arLayersObjects as $keyLayer => $arLayersObject) {
                $arLayersId[$keyLayer] = $arLayersObject->getId();
            }

            foreach ($arSectorsObjects as $key => $arSectorObj) {
                $arSectors[$key]["id"]   = $arSectorObj->getId();
                $arSectors[$key]["name"] = $arSectorObj->getName();
            }
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if (!empty($request->get("select_circle")) && !empty($request->get(
                    "layers_number"
                )) && !empty($request->get("select_sector"))
            ) {
                $numberLayer = $request->get("layers_number");
                $sectorId    = $request->get("select_sector");
                $layerId     = $arLayersId[$numberLayer];
                $label       = $this->createLabel($sectorId, $layerId);
                $note->setLabels($label);
            }

            $note->setUser($user);
            $note->setDateCreate(new \DateTime('now'));
            $note->setDateUpdate(new \DateTime('now'));
            $note->setName($request->get("name"));
            $note->setText($request->get("text"));
            $em->persist($note);
            $em->flush();

            return $this->redirectToRoute('notes_show', array('id' => $note->getId()));
        }

        return $this->render(
            'notes/new.html.twig',
            array(
                'note' => $note,
                'arSectors' => $arSectors,
                'countLayers' => $countLayers,
                'arCircles' => $arCircles,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Displays a form to edit an existing Notes entity.
     *
     */
    public function editAction(Request $request, Notes $note)
    {
        $deleteForm = $this->createDeleteForm($note);
        $editForm   = $this->createForm('Tzepart\NotesManagerBundle\Form\NotesType', $note);
        $editForm->handleRequest($request);

        $arSectorsObjects = [];
        $arLayersObjects  = [];
        $arSectors        = [];
        $arCircles        = [];
        $arLayersId       = [];
        $countLayers      = 5;
        $arSelectCirclesId = 0;
        $selectLayerId = 0;
        $selectSectorId = 0;
        $numberLayer = "";


        $name = $note->getName();
        $text = $note->getText();
        $label = $note->getLabels();

        if($label != null){
            $arSelectCirclesId = $label->getSectors()->getCircle()->getId();
            $selectLayerId = $label->getLayers()->getId();
            $selectSectorId = $label->getSectors()->getId();
        }
        
        if(!empty($request->get("select_circle"))){
            $arSelectCirclesId = $request->get("select_circle");
        }

        $user             = $this->getCurrentUserObject();
        $arCirclesObjects = $user->getCircles();

        foreach ($arCirclesObjects as $key => $circlesObject) {
            $arCircles[$key]["id"]   = $circlesObject->getId();
            $arCircles[$key]["name"] = $circlesObject->getName();
            if ($circlesObject->getId() == $arSelectCirclesId) {
                $this->array_swap($arCircles,0,$key);
                $arLayersObjects  = $circlesObject->getLayers();
                $arSectorsObjects = $circlesObject->getSectors();
            }
        }

        if (!empty($arLayersObjects) && !empty($arSectorsObjects)) {
            $countLayers = count($arLayersObjects);
            foreach ($arLayersObjects as $keyLayer => $arLayersObject) {
                $arLayersId[$keyLayer] = $arLayersObject->getId();
                if($arLayersObject->getId() == $selectLayerId){
                    $numberLayer = $keyLayer;
                }
            }

            foreach ($arSectorsObjects as $key => $arSectorObj) {
                $arSectors[$key]["id"]   = $arSectorObj->getId();
                $arSectors[$key]["name"] = $arSectorObj->getName();
                if($arSectorObj->getId() == $selectSectorId){
                    $this->array_swap($arSectors,0,$key);
                }
            }
        }


        if ($editForm->isSubmitted() && $editForm->isValid()) {

            if (!empty($request->get("select_circle")) && !empty($request->get(
                    "layers_number"
                )) && !empty($request->get("select_sector"))
            ) {
                $numberLayer = $request->get("layers_number");
                $sectorId    = $request->get("select_sector");
                $layerId     = $arLayersId[$numberLayer];
                if($label != null){
                    $updateLabel = $this->updateLabel($label,$sectorId,$layerId);
                }else{
                    $updateLabel = $this->createLabel($sectorId,$layerId);
                }
                $note->setLabels($updateLabel);
            }

            $note->setDateUpdate(new \DateTime('now'));
            $note->setName($request->get("name"));
            $note->setText($request->get("text"));

            $em = $this->getDoctrine()->getManager();
            $em->persist($note);
            $em->flush();

            return $this->redirectToRoute('notes_edit', array('id' => $note->getId()));
        }

        return $this->render(
            'notes/edit.html.twig',
            array(
                "name" => $name,
                "text" => $text,
                "numberLayer" => $numberLayer,
                'note' => $note,
                'arSectors' => $arSectors,
                'countLayers' => $countLayers,
                'arCircles' => $arCircles,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }


    /**
     * Finds and displays a Notes entity.
     *
     */
    public function showAction(Notes $note)
    {
        $deleteForm = $this->createDeleteForm($note);

        return $this->render(
            'notes/show.html.twig',
            array(
                'note' => $note,
                'delete_form' => $deleteForm->createView(),
            )
        );
    }


    /**
     * Deletes a Notes entity.
     *
     */
    public function deleteAction(Request $request, Notes $note)
    {
        $form = $this->createDeleteForm($note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($note);
            $em->flush();
        }

        return $this->redirectToRoute('notes_index');
    }


    /**
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function editAjaxAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {
            $arResult  = [];
            $user      = $this->getCurrentUserObject();
            $arCircles = $user->getCircles();
            foreach ($arCircles as $index => $arCircle) {
                if ($arCircle->getId() == $request->get("circleId")) {
                    $arLayers                = $arCircle->getLayers();
                    $arSectors               = $arCircle->getSectors();
                    $arResult["countLayers"] = count($arLayers);
                    foreach ($arSectors as $key => $arSector) {
                        $arResult["sectors"][$key]["id"]   = $arSector->getId();
                        $arResult["sectors"][$key]["name"] = $arSector->getName();
                    }
                }
            }

            return new JsonResponse($arResult);
        }

        return new Response('This is not ajax!', 400);
    }

    /**
     * Create labels
     * @param int $sectorId
     * @param int $layerId
     * @return Labels
     */
    protected function createLabel($sectorId, $layerId)
    {
        $arParams        = [];

        $em     = $this->getDoctrine()->getManager();
        $sector = $em->getRepository('NotesManagerBundle:Sectors')->find($sectorId);
        $layer  = $em->getRepository('NotesManagerBundle:Layers')->find($layerId);


        $angel  = rand($sector->getBeginAngle(), $sector->getEndAngle());
        $radius = $this->f_rand($layer->getBeginRadius(), $layer->getEndRadius());

        $arParams["angle"]  = $angel;
        $arParams["radius"] = $radius;
        $arParams["layer"]  = $layer;
        $arParams["sector"] = $sector;
        
        $label = $this->newLabel($arParams);

        return $label;
    }

    /**
     * Create labels
     * @param Labels $label
     * @param int $sectorId
     * @param int $layerId
     * @return Labels
     */
    protected function updateLabel(Labels $label,$sectorId, $layerId)
    {
        $arParams        = [];

        $em     = $this->getDoctrine()->getManager();
        $sector = $em->getRepository('NotesManagerBundle:Sectors')->find($sectorId);
        $layer  = $em->getRepository('NotesManagerBundle:Layers')->find($layerId);


        $angel  = rand($sector->getBeginAngle(), $sector->getEndAngle());
        $radius = $this->f_rand($layer->getBeginRadius(), $layer->getEndRadius());

        $arParams["angle"]  = $angel;
        $arParams["radius"] = $radius;
        $arParams["layer"]  = $layer;
        $arParams["sector"] = $sector;

        $label = $this->editLabel($label,$arParams);

        return $label;
    }

    /**
     * Creates a form to delete a Notes entity.
     *
     * @param Notes $note The Notes entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Notes $note)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('notes_delete', array('id' => $note->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @param array $arParams
     * @return int
     */
    public function newLabel($arParams)
    {
        $label = new Labels();
        $em = $this->getDoctrine()->getManager();
        
        $label->setAngle($arParams["angle"]);
        $label->setRadius($arParams["radius"]);
        $label->setLayers($arParams["layer"]);
        $label->setSectors($arParams["sector"]);
        $label->setDateCreate(new \DateTime('now'));
        $label->setDateUpdate(new \DateTime('now'));
        $em->persist($label);
        $em->flush();

        return $label;
    }


    /**
     * @param Labels $label
     * @param array $arParams
     * @return int
     */
    public function editLabel(Labels $label,$arParams)
    {
        $em = $this->getDoctrine()->getManager();
        if(!empty($arParams["angle"])){
            $label->setAngle($arParams["angle"]);
        }
        if(!empty($arParams["radius"])){
            $label->setRadius($arParams["radius"]);
        }
        if(!empty($arParams["layer"])){
            $label->setLayers($arParams["layer"]);
        }
        if(!empty($arParams["sector"])){
            $label->setSectors($arParams["sector"]);
        }
        $label->setDateUpdate(new \DateTime('now'));
        $em->persist($label);
        $em->flush();

        return $label;
    }


    /**
     * Deletes a Labels entity.
     *
     * @param Labels $label
     * @return bool
     */
    public function deleteLabel(Labels $label)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($label);
        $em->flush();

        return true;
    }

    protected function varsAndMethodsObject($object)
    {
        $arResult               = array();
        $arResult["CLASS_NAME"] = get_class($object);
        $arResult["VARS"]       = get_class_vars(get_class($object));
        $arResult["METHODS"]    = get_class_methods(get_class($object));

        return $arResult;
    }

    /**
     * function replace two element array with keys - $key1 и $key2
     * @param array $array original array
     * @param string $key1
     * @param string $key2
     * @return bool true if replace successful or false if fail
     */
    protected function array_swap(array &$array, $key1, $key2)
    {
        if (isset($array[$key1]) && isset($array[$key2])) {
            list($array[$key1], $array[$key2]) = array($array[$key2], $array[$key1]);
            return true;
        }
        return false;
    }

    protected function f_rand($min=0,$max=1,$mul=1000000){
        if ($min>$max) return false;
        return mt_rand($min*$mul,$max*$mul)/$mul;
    }

}

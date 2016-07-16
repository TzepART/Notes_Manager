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

    function varsAndMethodsObject($object)
    {
        $arResult               = array();
        $arResult["CLASS_NAME"] = get_class($object);
        $arResult["VARS"]       = get_class_vars(get_class($object));
        $arResult["METHODS"]    = get_class_methods(get_class($object));

        return $arResult;
    }

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
        
        $label = $note->getLabels();
   
        if(!$label != null){
            $arSelectCirclesId = $label->getSectors()->getCircle()->getId();
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


        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($note);
            $em->flush();

            return $this->redirectToRoute('notes_edit', array('id' => $note->getId()));
        }

        return $this->render(
            'notes/edit.html.twig',
            array(
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

    function f_rand($min=0,$max=1,$mul=1000000){
        if ($min>$max) return false;
        return mt_rand($min*$mul,$max*$mul)/$mul;
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

        return $label->getId();
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
}

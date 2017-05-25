<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Labels;
use AppBundle\Entity\Notes;

/**
 * Notes controller.
 *
 */
class NotesController extends Controller
{

    /**
     * Lists all Notes entities.
     * @param null $circleId
     * @param null $labelId
     * @return Response
     * @Route('/',name="notes_index")
     * @Route('/list/{circleId}/',name="select_notes_list_by_circle")
     * @Route('/list/{circleId}/{labelId}/',name="select_note_by_circle")
     * @Method('GET')
     */
    public function indexAction($circleId = null,$labelId = null)
    {
        $this->checkAuthorize();
        $arSectors = [];
        $arNotes = [];
        $userObj = $this->getCurrentUserObject();
        $em    = $this->getDoctrine()->getManager();


        //if exists select circle, then create array with notes link
        // by select circle.
        //Else - create list with haven't linked notes (inbox notes)
        if($circleId != null && $circleId >0){
            $circleObj = $em->getRepository('AppBundle:Circle')->find($circleId);
            $arLayerByLabel = [];
            $arSectorsObj = $circleObj->getSectors();
            $arLayersObj = $circleObj->getLayers();
            $countLayer = count($arLayersObj);
            foreach ($arLayersObj as $indexLayer => $arLayerObj) {
                $arLabelsObjByLayer = $arLayerObj->getLabels();
                foreach ($arLabelsObjByLayer as $key => $labelObj) {
                    $arLayerByLabel[$labelObj->getId()] = $indexLayer;
                }
            }
            foreach ($arSectorsObj as $index => $sectorObj) {
                $arSectors[$index]["id"] = $sectorObj->getId();
                $arSectors[$index]["name"] = $sectorObj->getName();
                $arSectors[$index]["inFlag"] = "";
                $arLabelsObj = $sectorObj->getLabels();
                $arColorBySector = $this->returnArColorLayers($sectorObj->getColor(),$countLayer);

                foreach ($arLabelsObj as $key => $labelObj) {
                    $notesObj = $labelObj->getNotes();
                    $notes[] = $notesObj;
                    $arSectors[$index]["notes"][$key]["noteId"] = $notesObj->getId();
                    $arSectors[$index]["notes"][$key]["labelId"] = $labelObj->getId();
                    $arSectors[$index]["notes"][$key]["noteName"] = $notesObj->getName();
                    $arSectors[$index]["notes"][$key]["text"] = $notesObj->getText();
                    $arSectors[$index]["notes"][$key]["color"] = $arColorBySector[$arLayerByLabel[$labelObj->getId()]];
                    if($labelObj->getId() == $labelId){
                        $arSectors[$index]["notes"][$key]["collapsed"] = "";
                        $arSectors[$index]["notes"][$key]["ariaExpanded"] = "true";
                        $arSectors[$index]["notes"][$key]["inFlag"] = "in";
                        $arSectors[$index]["inFlag"] = "in";
                    }else{
                        $arSectors[$index]["notes"][$key]["collapsed"] = "collapsed";
                        $arSectors[$index]["notes"][$key]["ariaExpanded"] = "false";
                        $arSectors[$index]["notes"][$key]["inFlag"] = "";
                    }
                }
            }

            return $this->render(
                'notes/index_circle.html.twig',
                array(
                    'circleId' => $circleId,
                    'arSectors' => $arSectors,
                )
            );
            
        }else{
            $arNotesObj = $userObj->getNotes();
            foreach ($arNotesObj as $index => $notesObj) {
                if($notesObj->getLabels() == null){
                    $arNotes[$index]["noteId"] = $notesObj->getId();
                    $arNotes[$index]["noteName"] = $notesObj->getName();
                    $arNotes[$index]["text"] = $notesObj->getText();
                }
            }

            return $this->render(
                'notes/index.html.twig',
                array(
                    'arNotes' => $arNotes,
                )
            );
        }
        
    }


    /**
     * Creates a new Notes entity.
     * @param Request $request
     * @param int $select_circle
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route('/new/{select_circle}',name="notes_new_by_circle")
     * @Route('/new',name="notes_new")
     * @Method({'GET', 'POST'})
     */
    public function newAction(Request $request,$select_circle = null)
    {
        $this->checkAuthorize();
        $note             = new Notes();
        $arSectors        = [];
        $arCircles        = [];
        $arSectorsObjects = [];
        $arLayersObjects  = [];
        $arLayersId       = [];
        $countLayers      = 5;
        $selectCirclesId = 0;
        $form             = $this->createForm('AppBundle\Form\NotesType', $note);
        $form->handleRequest($request);


        // Get all user's circle entities
        $user             = $this->getCurrentUserObject();
        $arCirclesObjects = $user->getCircles();

        //if exists select circle in request, then selectCirclesId her id
        //if doesn't exists select circle in request, then selectCirclesId = $select_circle from URL
        //else selectCirclesId = 0
        if(!empty($request->get("select_circle"))){
            $selectCirclesId = $request->get("select_circle");
        }elseif($select_circle != null){
            $selectCirclesId = $select_circle;
        }

        //get all sectors and layers by circle
        foreach ($arCirclesObjects as $key => $circlesObject) {
            $arCircles[$key]["id"]   = $circlesObject->getId();
            $arCircles[$key]["name"] = $circlesObject->getName();
            if ($circlesObject->getId() == $selectCirclesId) {
                $this->array_swap($arCircles,0,$key);
                $arLayersObjects  = $circlesObject->getLayers();
                $arSectorsObjects = $circlesObject->getSectors();
            }
        }

        $arDefaultCircle = ["id" => "", "name" => "Во входящие"];
        if($selectCirclesId  == 0){
            array_unshift($arCircles,$arDefaultCircle);
        }else{
            array_push($arCircles,$arDefaultCircle);
        }


        //if exists sectors and layers by circle,
        // then create array with information about them
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


        //if form submit then create new Note and label, which links with new note
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if (!empty($request->get("select_circle")) && !empty($request->get(
                    "layers_number"
                )) && !empty($request->get("select_sector"))
            ) {
                $numberLayer = $request->get("layers_number");
                $sectorId    = $request->get("select_sector");
                $layerId     = $arLayersId[$numberLayer-1];
                $label       = $this->createLabel($sectorId, $layerId);
                $note->setLabels($label);
            }else{
                $note->setLabels(null);
            }

            $note->setUser($user);
            $note->setName($request->get("name"));
            $note->setText($request->get("text"));
            $em->persist($note);
            $em->flush();

            //@TODO redirect in notes page
            if($selectCirclesId  != 0){
                return $this->redirectToRoute('circle_show', array('id' => $selectCirclesId));
            }else{
                return $this->redirectToRoute('notes_index');
            }
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
     * @param Request $request
     * @param Notes $note
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route('/{id}/edit',name="notes_edit")
     * @Method({'GET', 'POST'})
     */
    //@TODO change number layer (now first layer - 0)
    public function editAction(Request $request, Notes $note)
    {
        $this->checkAuthorize();
        $deleteForm = $this->createDeleteForm($note);
        $editForm   = $this->createForm('AppBundle\Form\NotesType', $note);
        $editForm->handleRequest($request);

        $arSectorsObjects = [];
        $arLayersObjects  = [];
        $arSectors        = [];
        $arCircles        = [];
        $arLayersId       = [];
        $countLayers      = 5;
        $selectCirclesId = 0;
        $selectLayerId = 0;
        $selectSectorId = 0;
        $numberLayer = "";


        $name = $note->getName();
        $text = $note->getText();
        $label = $note->getLabels();

        // if note have label, get layer and sector entities where is label
        if($label != null){
            $selectCirclesId = $label->getSectors()->getCircle()->getId();
            $selectLayerId = $label->getLayers()->getId();
            $selectSectorId = $label->getSectors()->getId();
        }
        
        if(!empty($request->get("select_circle"))){
            $selectCirclesId = $request->get("select_circle");
        }

        //get current user object
        $user             = $this->getCurrentUserObject();
        $arCirclesObjects = $user->getCircles();

        //get list with user's circles
        //if exists select circle then move it into first place in list
        foreach ($arCirclesObjects as $key => $circlesObject) {
            $arCircles[$key]["id"]   = $circlesObject->getId();
            $arCircles[$key]["name"] = $circlesObject->getName();
            if ($circlesObject->getId() == $selectCirclesId) {
                $this->array_swap($arCircles,0,$key);
                $arLayersObjects  = $circlesObject->getLayers();
                $arSectorsObjects = $circlesObject->getSectors();
            }
        }


        //if exists sectors and layers by select circle,
        // then create array with information about them
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


        //if form submit then update Note and label, which links with new note
        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $labelDelete = false;
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
            }elseif(empty($request->get("layers_number")) && empty($request->get("select_sector")) && ($label != null)){
                $note->setLabels();
                $labelDelete = true;
            }

            $note->setName($request->get("name"));
            $note->setText($request->get("text"));

            $em = $this->getDoctrine()->getManager();
            $em->persist($note);
            $em->flush();

            if($labelDelete){
                $this->deleteLabel($label);
            }

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
     * @param Notes $note
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route('/{id}/show',name="notes_show")
     * @Method('GET')
     */
    public function showAction(Notes $note)
    {
        $this->checkAuthorize();
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
     * @param Request $request
     * @param Notes $note
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route('/{id}/delete',name="notes_delete")
     * @Method('DELETE')
     */
    public function deleteAction(Request $request, Notes $note)
    {
        $this->checkAuthorize();
        $form = $this->createDeleteForm($note);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $label = $note ->getLabels();
            $em = $this->getDoctrine()->getManager();
            $em->remove($note);
            $em->flush();
            if($label != null){
                $this->deleteLabel($label);
            }
        }

        return $this->redirectToRoute('notes_index');
    }


    /**
     * @param Request $request
     * @return JsonResponse|Response
     * @Route('/editAjax',name="notes_edit_ajax")
     * @Method({'GET', 'POST'})
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
     * Get user object
     * @return integer $userId
     */
    protected function getCurrentUserObject()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        return $user;
    }

    /**
     * Method for checking user's Authorize
     */
    protected function checkAuthorize(){
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
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
        $sector = $em->getRepository('AppBundle:Sectors')->find($sectorId);
        $layer  = $em->getRepository('AppBundle:Layers')->find($layerId);


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
     * Update labels
     * @param Labels $label
     * @param int $sectorId
     * @param int $layerId
     * @return Labels
     */
    protected function updateLabel(Labels $label,$sectorId, $layerId)
    {
        $arParams        = [];

        $em     = $this->getDoctrine()->getManager();
        $sector = $em->getRepository('AppBundle:Sectors')->find($sectorId);
        $layer  = $em->getRepository('AppBundle:Layers')->find($layerId);


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
     * @param Notes $note The Notes entity
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
     * Create a Labels entity.
     * @param array $arParams
     * @return Labels
     */
    protected function newLabel($arParams)
    {
        $label = new Labels();
        $em = $this->getDoctrine()->getManager();
        
        $label->setAngle($arParams["angle"]);
        $label->setRadius($arParams["radius"]);
        $label->setLayers($arParams["layer"]);
        $label->setSectors($arParams["sector"]);
        $em->persist($label);
        $em->flush();

        return $label;
    }


    /**
     * Change a Labels entity.
     * @param Labels $label
     * @param array $arParams
     * @return Labels
     */
    protected function editLabel(Labels $label,$arParams)
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
        $em->persist($label);
        $em->flush();

        return $label;
    }


    /**
     * Deletes a Labels entity.
     * @param Labels $label
     * @return bool
     */
    protected function deleteLabel(Labels $label)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($label);
        $em->flush();

        return true;
    }


    /**
     * Method replace two element array with keys - $key1 и $key2
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

    /**
     * Method for return random float number
     * @param int $min
     * @param int $max
     * @param int $mul
     * @return bool|float
     */
    protected function f_rand($min=0,$max=1,$mul=1000000){
        if ($min>$max) return false;
        return mt_rand($min*$mul,$max*$mul)/$mul;
    }

    /**
     * Method return array colors by layer, where change any color in red
     * @param string $color
     * @param int $numLayers
     * @return array
     */
    protected function returnArColorLayers($color,$numLayers) {
        $arColor = $this->hextorgb($color);
        $tempColor = $arColor;
        $arRBA = [];
        $i = 0;
        $difColorRed = (256-$arColor[0])/$numLayers;
        $difColorGreen = ($arColor[1])/($numLayers-1);
        $difColorBlue = ($arColor[2])/($numLayers-1);
        $red = $arColor[0] + $difColorRed;
        $green = $arColor[1];
        $blue = $arColor[2];
        for($red; $red <= 256.01; $red = $red + $difColorRed){
            $tempColor[0] = floor($red);
            $tempColor[1] = floor($green);
            $tempColor[2] = floor($blue);
            $arRBA[$i] = $this->hexArrayInRgbString($tempColor);
            $green = $green - $difColorGreen;
            $blue = $blue - $difColorBlue;
            $i++;
        }
        return array_reverse($arRBA);
    }


    /**
     * Method for converting RgbString in array by colors
     * @param string $hex
     * @return array
     */
    protected function hextorgb($hex) {
        $hex = str_replace('#', '', $hex);
        if ( strlen($hex) == 6 ) {
            $rgb[0] = hexdec(substr($hex, 0, 2));
            $rgb[1] = hexdec(substr($hex, 2, 2));
            $rgb[2] = hexdec(substr($hex, 4, 2));
        }
        else if ( strlen($hex) == 3 ) {
            $rgb[0] = hexdec(str_repeat(substr($hex, 0, 1), 2));
            $rgb[1] = hexdec(str_repeat(substr($hex, 1, 1), 2));
            $rgb[2] = hexdec(str_repeat(substr($hex, 2, 1), 2));
        }
        else {
            $rgb[0] = '0';
            $rgb[1] = '0';
            $rgb[2] = '0';
        }
        return $rgb;
    }

    /**
     * Method for converting array by colors in RgbString
     * @param array $tempColor
     * @return string
     */
    protected function hexArrayInRgbString($tempColor) {
        $rgb = 'rgb('.$tempColor[0].', '.$tempColor[1].', '.$tempColor[2].')';
        return $rgb;
    }

}

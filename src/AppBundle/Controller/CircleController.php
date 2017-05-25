<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Event\CreateCircleEvent;
use AppBundle\Entity\Circle;
use AppBundle\Entity\Sectors;
use AppBundle\Entity\Layers;
use AppBundle\Entity\Labels;


/**
 * Circle controller.
 * @Route("circle")
 */
class CircleController extends Controller
{
    /**
     * Lists all Circle entities.
     * @Route("/", name="circle_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $this->checkAuthorize();
        $user = $this->getCurrentUserObject();

        $em = $this->getDoctrine()->getManager();
        $circles = $user->getCircles();

        return $this->render(
            'SectorManagercircle/index.html.twig',
            array(
                'circles' => $circles,
                'user' => "",
            )
        );
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homepageAction()
    {
        return $this->indexAction();
    }


    /**
     * Creates a new Circle entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/new",name="circle_new")
     * @Method({"GET","POST"})
     */
    public function newAction(Request $request)
    {
        $this->checkAuthorize();
        $circle = new Circle();

        $form   = $this->createForm('AppBundle\Form\CircleType', $circle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && !empty($request->get("layers_number"))) {
            $user = $this->getCurrentUserObject();
            $circle->setUser($user);
            $circle->setName($request->get("name"));
            $em = $this->getDoctrine()->getManager();

            // create the CreateCircleEvent and dispatch it
            $event = new CreateCircleEvent($circle);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(CreateCircleEvent::NAME, $event);

            $em->persist($circle);
            $em->flush();

            $arSectorName = $request->get("sector_name");

            //  @TODO Change create sectors like create layers
            $sectorsNumber = count($arSectorName);
            $arAngles      = $this->anglesBySectors($sectorsNumber);
            $arBeginAngle  = $arAngles['begin'];
            $arEndAngle    = $arAngles['end'];
            $arColor       = $request->get("sector_color");
            foreach ($arSectorName as $key => $sectorName) {
                $this->createSector($circle, $sectorName, $arBeginAngle[$key], $arEndAngle[$key], $arColor[$key]);
            }

            $this->createLayers($circle, $request->get("layers_number"));

            return $this->redirectToRoute('circle_show', array('id' => $circle->getId()));
        }

        return $this->render(
            'circle/new.html.twig',
            array(
                'circle' => $circle,
                'form' => $form->createView(),
            )
        );
    }


    /**
     * Finds and displays a Circle entity.
     * @param Circle $circle
     * @param null $labelId
     * @return Response
     * @Route("/{id}/{labelId}/show",name="circle_label_show")
     * @Route("/{id}/show", name="circle_show")
     * @Method("GET")
     */
    public function showAction(Circle $circle,$labelId = null)
    {

        $this->checkAuthorize();
        $selectLabel = $labelId;
        $arSectors = [];
        $arLayers = [];
        $arLabels = [];
        $deleteForm = $this->createDeleteForm($circle);
        $arLayersObj = $circle->getLayers();
        $countLayers = count($arLayersObj);

        $arSectorsObj = $circle->getSectors();
        $i = 0;
        $em     = $this->getDoctrine()->getManager();

        foreach ($arSectorsObj as $key=>$sectorObj) {
            $arSectors[$key]["beginAngle"] = $sectorObj->getBeginAngle();
            $arSectors[$key]["endAngle"] = $sectorObj->getEndAngle();
            $arSectors[$key]["name"] = $sectorObj->getName();
            $arSectors[$key]["color"] = $sectorObj->getColor();
            $arSectors[$key]["id"] = $sectorObj->getId();
            $arLabelsObj = $sectorObj->getLabels();
            foreach ($arLabelsObj as $index => $labelObj) {
                if($labelObj->getNotes() != null){
                    $arLabels[$i]["id"] = $labelObj->getId();
                    $arLabels[$i]["radius"] = $labelObj->getRadius();
                    $arLabels[$i]["degr"] = $labelObj->getAngle();
                    $arLabels[$i]["name"] = $labelObj->getNotes()->getName();
                    $i++;
                }
            }
        }

        foreach ($arLayersObj as $i => $arLayerObj) {
            $arLayers[$i]["beginRadius"] = $arLayerObj->getBeginRadius();
            $arLayers[$i]["endRadius"] = $arLayerObj->getEndRadius();
            $arLayers[$i]["id"] = $arLayerObj->getId();
        }

        $redis = $this->container->get('snc_redis.default');
        $redis->set($circle->getId().'_sectors',serialize($arSectors));
        $redis->set($circle->getId().'_layers',serialize($arLayers));


        return $this->render(
            'circle/show.html.twig',
            array(
                'arSectors'=>$arSectors,
                'arLabels'=>$arLabels,
                'countLayers'=>$countLayers,
                'circle' => $circle,
                'selectLabel' => $selectLabel,
                'delete_form' => $deleteForm->createView(),
            )
        );
    }


    /**
     * Displays a form to edit an existing Circle entity.
     * @param Request $request
     * @param Circle $circle
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/{id}/edit", name="circle_edit")
     * @Method({"POST","GET"})
     */
    public function editAction(Request $request, Circle $circle)
    {
        $this->checkAuthorize();
        $deleteForm = $this->createDeleteForm($circle);
        $editForm   = $this->createForm('AppBundle\Form\CircleType', $circle);
        $editForm->handleRequest($request);

        $layers  = $circle->getLayers();
        $countCurrentLayers = count($layers);
        $arLabelsByCircleObj = [];

        $sectors = $circle->getSectors();

        /*
         * get All sectors by circle
         * */
        $arSectors = [];
        $i = 0;
        foreach ($sectors as $index => $sector) {
            $arSectors[$index]["id"]        = $sector->getId();
            $arSectors[$index]["name"]      = $sector->getName();
            $arSectors[$index]["color"]     = $sector->getColor();
            $arSectorsObj[$sector->getId()] = $sector;
            $arCurrentSectorId[$index]      = $sector->getId();
            $arLabelsObj = $sector->getLabels();
            foreach ($arLabelsObj as $index => $labelObj) {
                if($labelObj->getNotes() != null){
                    $arLabelsByCircleObj[$i] = $labelObj;
                    $i++;
                }
            }
        }


        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $circle->setName($request->get("name"));
            $em->persist($circle);
            $em->flush();

            /*
            * Block logic for sectors
            * Update sectors is in 3 steps
             * 1 Step - if quantity sectors decreased - delete select sectors
             * 2 Step - update exists sectors
             * 3 Step - if quantity sectors increase - create new sectors
            * */

            $arSectorName  = $request->get("sector_name");
            $arSectorColor = $request->get("sector_color");
            $arSectorId    = $request->get("sector_id");


            if (count($arCurrentSectorId) > count($arSectorId)) {
                /*
                 * Delete sectors
                 * */
                $arSectorIdDelete = array_diff($arCurrentSectorId, $arSectorId);
                if (!empty($arSectorIdDelete)) {
                    foreach ($arSectorIdDelete as $index => $sectorId) {
                        $this->deleteSector($arSectorsObj[$sectorId]);
                    }
                }
            }

            /*
              * Update sectors
            * */

            $sectorsNumber = count($arSectorName);
            $arAngles      = $this->anglesBySectors($sectorsNumber);
            $arBeginAngle  = $arAngles['begin'];
            $arEndAngle    = $arAngles['end'];


            $keyBegin = 0;
            foreach ($arSectorId as $key => $sectorId) {
                $arSectorParams               = [];
                $arSectorParams["name"]       = $arSectorName[$key];
                $arSectorParams["color"]      = $arSectorColor[$key];
                $arSectorParams["beginAngle"] = $arBeginAngle[$key];
                $arSectorParams["endAngle"]   = $arEndAngle[$key];
                $this->updateSector($arSectorsObj[$sectorId], $arSectorParams);
                $keyBegin = $key+1;
            }

            /*
             * Create new sectors
             * */
            if($keyBegin < count($arSectorName)){
                for ($i = $keyBegin; $i < count($arSectorName); $i++) {
                $this->createSector($circle, $arSectorName[$i], $arBeginAngle[$i], $arEndAngle[$i], $arSectorColor[$i]);
                }
            }

            /*
              * End block logic for sectors
              *
             * */

            /*
             * Block logic for layers
             * */
            $newCountLayers = $request->get("layers_number");

            if($newCountLayers < $countCurrentLayers){
                //recount
                //update
                $arRadius = $this->radiusByLayers($newCountLayers);
                for ($i = 0; $i < $newCountLayers; $i++) {
                    $arLayerParams = [];
                    $arLayerParams["beginRadius"] = $arRadius['begin'][$i];
                    $arLayerParams["endRadius"] = $arRadius['end'][$i];
                    $this->updateLayer($layers[$i],$arLayerParams);
                }
                //delete
                //@TODO add check radius by layers
                $beginDelete = $newCountLayers;
                for($i = $beginDelete;$i<$countCurrentLayers;$i++){
                    $this->deleteLayer($layers[$i]);
                }

            }elseif($newCountLayers > $countCurrentLayers){
                //recount
                //update
                $arRadius = $this->radiusByLayers($newCountLayers);
                for ($i = 0; $i < $countCurrentLayers; $i++) {
                    $arLayerParams = [];
                    $arLayerParams["beginRadius"] = $arRadius['begin'][$i];
                    $arLayerParams["endRadius"] = $arRadius['end'][$i];
                    $this->updateLayer($layers[$i],$arLayerParams);
                }
                //create
                $numberBegin = $countCurrentLayers;
                for ($i = $numberBegin; $i < $newCountLayers; $i++) {
                    $this->createLayer($circle,$arRadius['begin'][$i],$arRadius['end'][$i]);
                }
            }
            /*
              * End block logic for layers
              * */

            /*
             * Update labels by circle
             * */
            $this->updateLabelsByLayer($arLabelsByCircleObj,$circle);

            return $this->redirectToRoute('circle_edit', array('id' => $circle->getId()));
        }

        return $this->render(
            'circle/edit.html.twig',
            array(
                'countLayers' => $countCurrentLayers,
                'sectors' => $arSectors,
                'circle' => $circle,
                'circleName' => $circle->getName(),
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }


    /**
     * Deletes a Circle entity.
     * @param Request $request
     * @param Circle $circle
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("{id}/delete",name="circle_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Circle $circle)
    {
        $this->checkAuthorize();
        $form = $this->createDeleteForm($circle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($circle);
            $em->flush();
        }

        return $this->redirectToRoute('circle_index');
    }



    /**
     * @param Request $request
     * @return JsonResponse|Response
     * @Route("/editLabelAjax", name="circle_edit_label_ajax")
     * @Method({"POST","GET"})
     */
    public function updateCoordinateLabelAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {
            $arResult = [];
            $sectorId = 0;
            $layerId = 0;
            $em = $this->getDoctrine()->getManager();
            $circleId = $request->get("circleId");
            $labelId = $request->get("labelId");
            $radius = $request->get("radius");
            $angle = $request->get("angle");
            if($angle < 0 ){
                $angle = 360 + $angle;
            }

            $redis = $this->container->get('snc_redis.default');

            $arSectors = unserialize($redis->get($circleId.'_sectors'));
            $arLayers = unserialize($redis->get($circleId.'_layers'));

            foreach ($arSectors as $index => $arSector) {
                if($angle > $arSector['beginAngle'] && $angle < $arSector['endAngle']){
                    $sectorId = $arSector['id'];
                    break;
                }
            }

            foreach ($arLayers as $index => $arLayer) {
                if($radius > $arLayer['beginRadius'] && $radius < $arLayer['endRadius']){
                    $layerId = $arLayer['id'];
                    break;
                }
            }

            $sector = $em->getRepository('AppBundle:Sectors')->find($sectorId);
            $layer  = $em->getRepository('AppBundle:Layers')->find($layerId);
            $labelObj = $em->getRepository('AppBundle:Labels')->find($labelId);

            $arParams["angle"]  = $angle;
            $arParams["radius"] = $radius;
            $arParams["layer"]  = $layer;
            $arParams["sector"] = $sector;

            $label = $this->editLabel($labelObj,$arParams);

            $arResult = array("status" => "Y");

            return new JsonResponse($arResult);

        }

        return new Response('This is not ajax!', 400);

    }


    /**
     * Creates a form to delete a Circle entity.
     * @param Circle $circle The Circle entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Circle $circle)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('circle_delete', array('id' => $circle->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Check user's authorize
     */
    protected function checkAuthorize(){
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * Get user object
     * @return User $userId
     */
    protected function getCurrentUserObject()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        return $user;
    }

    /**
     * create N layers in circle
     * @param mixed $circle
     * @param int $n - count layers
     * @return integer $userId
     */
    protected function createLayers($circle, $n = 1)
    {
        $arRadius = $this->radiusByLayers($n);
        for ($i = 0; $i < $n; $i++) {
            $this->createLayer($circle,$arRadius['begin'][$i],$arRadius['end'][$i]);
        }
        return true;
    }

    /**
     * Create layer
     * @param Circle $circle
     * @param $radiusBegin
     * @param $radiusEnd
     * @return bool
     */
    protected function createLayer(Circle $circle, $radiusBegin,$radiusEnd){
        $layers = new Layers();
        $layers->setCircle($circle);
        $layers->setBeginRadius($radiusBegin);
        $layers->setEndRadius($radiusEnd);
        $layers->setColor("#FFF");
        $em = $this->getDoctrine()->getManager();
        $em->persist($layers);
        $em->flush();

        return true;
    }

    /**
     * Update select layer
     * @param mixed $layer
     * @param array $arParams
     * @return integer $userId
     */
    protected function updateLayer(Layers $layer, $arParams)
    {
        if (!empty($arParams["beginRadius"])) {
            $layer->setBeginRadius($arParams["beginRadius"]);
        }
        if (!empty($arParams["endRadius"])) {
            $layer->setEndRadius($arParams["endRadius"]);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($layer);
        $em->flush();

        return true;
    }

    /**
     * Delete select layer
     * @param mixed $layer
     * @return bool
     */
    protected function deleteLayer(Layers $layer)
    {
        $em = $this->getDoctrine()->getManager();

        $arLabelsObj = $layer->getLabels();
        $arParams = ["layer" => "Y"];
        foreach ($arLabelsObj as $index => $arLabelObj) {
            $this->unlinkLabel($arLabelObj,$arParams);
        }

        $em->remove($layer);
        $em->flush();

        return true;
    }

    /**
     * Method return array with Begin and End radius by layers on the assumption of layer's quantity
     * @param int $layersNumber
     * @return array
     */
    protected function radiusByLayers($layersNumber = 1)
    {
        $arRadius = [];
        $layerRad = 1 / $layersNumber;
        $beginRad = 0;
        $endRad   = $layerRad;

        for ($i = 0; $i < $layersNumber; $i++) {
            $arRadius["begin"][$i] = $beginRad;
            $arRadius["end"][$i]   = $endRad;
            $beginRad += $layerRad;
            $endRad += $layerRad;
        }

        return $arRadius;
    }

    /**
     * Create sector
     * @param mixed $circle
     * @param string $name
     * @param float $beginAngle
     * @param float $endAngle
     * @param string $color
     * @param int $parentSector
     * @return bool
     */
    protected function createSector($circle, $name, $beginAngle, $endAngle, $color, $parentSector = 0)
    {
        $sector = new Sectors();
        $sector->setCircle($circle);

        $sector->setName($name);
        $sector->setBeginAngle($beginAngle);
        $sector->setEndAngle($endAngle);
        $sector->setParentSectorId($parentSector);
        $sector->setColor($color);
        $em = $this->getDoctrine()->getManager();
        $em->persist($sector);
        $em->flush();

        return true;
    }

    /**
     * Update select sector
     * @param mixed $sector
     * @param array $arParams
     * @return bool
     */
    protected function updateSector(Sectors $sector, $arParams)
    {
        $arOldSectorParams = [];
        $arNewSectorParams = [];
        if (!empty($arParams["name"])) {
            $sector->setName($arParams["name"]);
        }
        if (!empty($arParams["beginAngle"])) {
            $arOldSectorParams["beginAngle"] = $sector->getBeginAngle();
            $arNewSectorParams["beginAngle"] = $arParams["beginAngle"];
            $sector->setBeginAngle($arParams["beginAngle"]);
        }
        if (!empty($arParams["endAngle"])) {
            $arOldSectorParams["endAngle"] = $sector->getEndAngle();
            $arNewSectorParams["endAngle"] = $arParams["endAngle"];
            $sector->setEndAngle($arParams["endAngle"]);
        }
        if (!empty($arParams["parentSector"])) {
            $sector->setParentSectorId($arParams["parentSector"]);
        }
        if (!empty($arParams["color"])) {
            $sector->setColor($arParams["color"]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($sector);
        $em->flush();
        if(!empty($arOldSectorParams["beginAngle"]) && $arOldSectorParams["endAngle"]){
            $this->updateLabelsBySector($sector,$arOldSectorParams,$arNewSectorParams);
        }

        return true;
    }


    /**
     * Delete select sector
     * @param mixed $sector
     * @return bool
     */
    protected function deleteSector(Sectors $sector)
    {
        $em = $this->getDoctrine()->getManager();

        $arLabelsObj = $sector->getLabels();
        $arParams = ["sector" => "Y"];
        foreach ($arLabelsObj as $index => $arLabelObj) {
            $this->unlinkLabel($arLabelObj,$arParams);
        }

        $em->remove($sector);
        $em->flush();

        return true;
    }


    /**
     * Method return array with Begin and End angles by sectors on the assumption of sector's quantity
     * @param int $sectorsNumber
     * @return array
     */
    protected function anglesBySectors($sectorsNumber = 1)
    {
        $arAngles    = [];
        $sectorAngle = 360 / $sectorsNumber;
        $beginAngle  = 0;
        $endAngle    = $sectorAngle;

        for ($i = 0; $i < $sectorsNumber; $i++) {
            $arAngles["begin"][$i] = $beginAngle;
            $arAngles["end"][$i]   = $endAngle;
            $beginAngle += $sectorAngle;
            $endAngle += $sectorAngle;
        }

        return $arAngles;
    }


    /**
     * Update labels by Layer
     * @param array $arLabelsObj
     * @param Circle $circle
     */
    protected function updateLabelsByLayer($arLabelsObj,Circle $circle)
    {
        $arLayers = [];
        $arLayersObj = $circle->getLayers();

        //create array, with layer's radius
        foreach ($arLayersObj as $index => $layerObj) {
            $arLayers[$index]["id"] = $layerObj->getId();
            $arLayers[$index]["beginRadius"] = $layerObj->getBeginRadius();
            $arLayers[$index]["endRadius"] = $layerObj->getEndRadius();
            $arLayers[$index]["object"] = $layerObj;
        }

        //cycle, where labels update layers_id, if his radius in new layers
        foreach ($arLabelsObj as $indexLabel => $labelObj) {
            if($labelObj->getLayers() != null){
                $layerId = $labelObj->getLayers()->getId();
            }else{
                $layerId = 0;
            }
            $radius = $labelObj->getRadius();
            foreach ($arLayers as $indexLayer => $arLayer) {
                if($radius > $arLayer['beginRadius'] && $radius < $arLayer['endRadius']){
                    if($layerId != $arLayer['id']){
                        $arParams["layer"]  = $arLayer['object'];
                        $this->editLabel($labelObj,$arParams);
                    }
                    break;
                }
            }
        }

    }

    /**
     * Update labels by sectors
     * @param Sectors $sectorObj
     * @param array $arOldSectorParams
     * @param array $arNewSectorParams
     */
    protected function updateLabelsBySector(Sectors $sectorObj, $arOldSectorParams,$arNewSectorParams)
    {
        $arLabelsObj = $sectorObj->getLabels();
        //create array, with sectors's angles
        $arParams = [];
        foreach ($arLabelsObj as $index => $labelObj) {
            $oldLabelAngle = $labelObj->getAngle();
            $arParams["angle"] = $this->newLabelAngle($oldLabelAngle,$arOldSectorParams,$arNewSectorParams);
            $this->editLabel($labelObj,$arParams);
        }

    }

    /**
     * Method return update label's angle from info about new and old angles by sector
     * @param $oldLabelAngle
     * @param array $arOldSectorAngles
     * @param array $arNewSectorAngles
     * @return float|int
     */
    protected function newLabelAngle($oldLabelAngle,$arOldSectorAngles,$arNewSectorAngles)
    {
        $newAngle = 0;
        $newAngle = ($oldLabelAngle - $arOldSectorAngles["beginAngle"])*($arNewSectorAngles["endAngle"] - $arNewSectorAngles["beginAngle"])/($arOldSectorAngles["endAngle"] - $arOldSectorAngles["beginAngle"])+$arNewSectorAngles["beginAngle"];
        return $newAngle;

    }

    /**
     * Update select label's params
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
     * Method for unlink label entity from layer or sector
     * @param Labels $label
     * @param array $arParams
     * @return bool
     */
    protected function unlinkLabel(Labels $label,$arParams)
    {
        $em = $this->getDoctrine()->getManager();

        if(isset($arParams["sector"]) && $arParams["sector"] == "Y"){
            $label->setSectors();
            $noteObj = $label->getNotes();
            $noteObj->setLabels();

            $em->remove($label);
            $em->persist($noteObj);

            $em->flush();

        }
        if(isset($arParams["layer"]) && $arParams["layer"] == "Y"){
            $label->setLayers();
            $em->persist($label);
            $em->flush();
        }

        return true;
    }
}

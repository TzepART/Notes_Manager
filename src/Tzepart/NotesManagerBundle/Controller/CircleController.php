<?php

namespace Tzepart\NotesManagerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Tzepart\NotesManagerBundle\Entity\Circle;
use Tzepart\NotesManagerBundle\Entity\Sectors;
use Tzepart\NotesManagerBundle\Entity\Layers;
use Tzepart\NotesManagerBundle\Form\CircleType;
use \Tzepart\NotesManagerBundle\Entity\User;
use Tzepart\NotesManagerBundle\Form\SectorsType;

/**
 * Circle controller.
 *
 */
class CircleController extends Controller
{
    /**
     * Lists all Circle entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        } else {
            echo "You authorize!";
        }

        $circles = $em->getRepository('NotesManagerBundle:Circle')->findAll();

        return $this->render(
            'circle/index.html.twig',
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
     * Get user id
     * @return integer $userId
     */
    protected function getCurrentUserObject()
    {
        $user = $this->get('security.context')->getToken()->getUser();

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
        for ($i = 1; $i <= $n; $i++) {
            $layers = new Layers();
            $layers->setCircle($circle);
            $layers->setBeginRadius($arRadius['begin'][$i]);
            $layers->setEndRadius($arRadius['end'][$i]);
            $layers->setColor("#FFF");
            $layers->setDateCreate(new \DateTime('now'));
            $layers->setDateUpdate(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($layers);
            $em->flush();
        }

        return true;
    }

    /**
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
        $sector->setName($name);
        $sector->setCircle($circle);
        $sector->setBeginAngle($beginAngle);
        $sector->setEndAngle($endAngle);
        $sector->setParentSectorId($parentSector);
        $sector->setColor($color);
        $sector->setDateCreate(new \DateTime('now'));
        $sector->setDateUpdate(new \DateTime('now'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($sector);
        $em->flush();

        return true;
    }

    /**
     * Creates a new Circle entity.
     *
     */
    public function newAction(Request $request)
    {
        $circle = new Circle();
        $form   = $this->createForm('Tzepart\NotesManagerBundle\Form\CircleType', $circle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && !empty($request->get("layers_number"))) {
            $user = $this->getCurrentUserObject();
            $circle->setUser($user);
            $circle->setDateCreate(new \DateTime('now'));
            $circle->setDateUpdate(new \DateTime('now'));
            $circle->setName($request->get("name"));
            $em = $this->getDoctrine()->getManager();
            $em->persist($circle);
            $em->flush();

            $arSectorName = $request->get("sector_name");

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
     * @param int $sectorsNumber
     * @return array
     */
    protected function anglesBySectors($sectorsNumber = 1)
    {
        $arAngles    = [];
        $sectorAngle = 360 / $sectorsNumber;
        $beginAngle  = 0;
        $endAngle    = $sectorAngle;

        for ($i = 1; $i <= $sectorsNumber; $i++) {
            $arAngles["begin"][$i] = $beginAngle;
            $arAngles["end"][$i]   = $endAngle;
            $beginAngle += $sectorAngle;
            $endAngle += $sectorAngle;
        }

        return $arAngles;
    }

    /**
     * @param int $layersNumber
     * @return array
     */
    protected function radiusByLayers($layersNumber = 1)
    {
        $arRadius = [];
        $layerRad = 1 / $layersNumber;
        $beginRad = 0;
        $endRad   = $layerRad;

        for ($i = 1; $i <= $layersNumber; $i++) {
            $arRadius["begin"][$i] = $beginRad;
            $arRadius["end"][$i]   = $endRad;
            $beginRad += $layerRad;
            $endRad += $layerRad;
        }

        return $arRadius;
    }

    /**
     * Finds and displays a Circle entity.
     *
     */
    public function showAction(Circle $circle)
    {
        $deleteForm = $this->createDeleteForm($circle);

        return $this->render(
            'circle/show.html.twig',
            array(
                'circle' => $circle,
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Displays a form to edit an existing Circle entity.
     *
     */
    public function editAction(Request $request, Circle $circle)
    {
        $deleteForm = $this->createDeleteForm($circle);
        $editForm   = $this->createForm('Tzepart\NotesManagerBundle\Form\CircleType', $circle);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($circle);
            $em->flush();

            return $this->redirectToRoute('circle_edit', array('id' => $circle->getId()));
        }

        return $this->render(
            'circle/edit.html.twig',
            array(
                'circle' => $circle,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Deletes a Circle entity.
     *
     */
    public function deleteAction(Request $request, Circle $circle)
    {
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
     * Creates a form to delete a Circle entity.
     *
     * @param Circle $circle The Circle entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Circle $circle)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('circle_delete', array('id' => $circle->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}

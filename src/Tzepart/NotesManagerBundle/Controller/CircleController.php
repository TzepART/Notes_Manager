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
        }else{
            echo "You authorize!";
        }

        $circles = $em->getRepository('NotesManagerBundle:Circle')->findAll();
        return $this->render('circle/index.html.twig', array(
            'circles' => $circles,
            'user'=>"",
        ));
    }


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
    protected function createLayers($circle,$n = 1)
    {
        for($i = 0;$i<$n;$i++){
            $layers = new Layers();
            $layers->setCircle($circle);
            $layers->setBeginRadius(0);
            $layers->setEndRadius(180);
            $layers->setColor("#FFDEAD");
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
    protected function createSector($circle,$name,$beginAngle,$endAngle,$color,$parentSector = 0)
    {
        $sector = new Sectors();
        $sector->setName($name);
        $sector->setCircle($circle);
        $sector->setBeginAngle($beginAngle);
        $sector->setEndAngle($endAngle);
        $sector->setParentSectorId($parentSector);
        $sector->setColor($color);
        $sector ->setDateCreate(new \DateTime('now'));
        $sector ->setDateUpdate(new \DateTime('now'));
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
        $form = $this->createForm('Tzepart\NotesManagerBundle\Form\CircleType', $circle);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid() && !empty($request->get("layers_number"))){
            $user = $this->getCurrentUserObject();
            $circle->setUser($user);
            $circle ->setDateCreate(new \DateTime('now'));
            $circle ->setDateUpdate(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($circle);
            $em->flush();

            $arSectorName = $request->get("sector_name");
            $arBeginAngle = $request->get("begin_angle");
            $arEndAngle = $request->get("end_angle");
            $arColor = $request->get("sector_color");
            foreach ($arSectorName as $key => $sectorName) {
                $this->createSector($circle,$sectorName,$arBeginAngle[$key],$arEndAngle[$key],$arColor[$key]);
            }

            $this->createLayers($circle,$request->get("layers_number"));

            return $this->redirectToRoute('circle_show', array('id' => $circle->getId()));
        }

        return $this->render('circle/new.html.twig', array(
            'circle' => $circle,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Circle entity.
     *
     */
    public function showAction(Circle $circle)
    {
        $deleteForm = $this->createDeleteForm($circle);

        return $this->render('circle/show.html.twig', array(
            'circle' => $circle,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Circle entity.
     *
     */
    public function editAction(Request $request, Circle $circle)
    {
        $deleteForm = $this->createDeleteForm($circle);
        $editForm = $this->createForm('Tzepart\NotesManagerBundle\Form\CircleType', $circle);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($circle);
            $em->flush();

            return $this->redirectToRoute('circle_edit', array('id' => $circle->getId()));
        }

        return $this->render('circle/edit.html.twig', array(
            'circle' => $circle,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
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
            ->getForm()
        ;
    }
}

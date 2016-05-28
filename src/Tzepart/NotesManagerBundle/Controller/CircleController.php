<?php

namespace Tzepart\NotesManagerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Tzepart\NotesManagerBundle\Entity\Circle;
use Tzepart\NotesManagerBundle\Form\CircleType;

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
     * Creates a new Circle entity.
     *
     */
    public function newAction(Request $request)
    {
        $circle = new Circle();
        $form = $this->createForm('Tzepart\NotesManagerBundle\Form\CircleType', $circle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($circle);
            $em->flush();

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

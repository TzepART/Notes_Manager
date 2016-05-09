<?php

namespace Tzepart\NotesManagerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Tzepart\NotesManagerBundle\Entity\Sectors;
use Tzepart\NotesManagerBundle\Form\SectorsType;

/**
 * Sectors controller.
 *
 */
class SectorsController extends Controller
{
    /**
     * Lists all Sectors entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $sectors = $em->getRepository('NotesManagerBundle:Sectors')->findAll();

        return $this->render('sectors/index.html.twig', array(
            'sectors' => $sectors,
        ));
    }

    /**
     * Creates a new Sectors entity.
     *
     */
    public function newAction(Request $request)
    {
        $sector = new Sectors();
        $form = $this->createForm('Tzepart\NotesManagerBundle\Form\SectorsType', $sector);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sector);
            $em->flush();

            return $this->redirectToRoute('sectors_show', array('id' => $sector->getId()));
        }

        return $this->render('sectors/new.html.twig', array(
            'sector' => $sector,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Sectors entity.
     *
     */
    public function showAction(Sectors $sector)
    {
        $deleteForm = $this->createDeleteForm($sector);

        return $this->render('sectors/show.html.twig', array(
            'sector' => $sector,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Sectors entity.
     *
     */
    public function editAction(Request $request, Sectors $sector)
    {
        $deleteForm = $this->createDeleteForm($sector);
        $editForm = $this->createForm('Tzepart\NotesManagerBundle\Form\SectorsType', $sector);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sector);
            $em->flush();

            return $this->redirectToRoute('sectors_edit', array('id' => $sector->getId()));
        }

        return $this->render('sectors/edit.html.twig', array(
            'sector' => $sector,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Sectors entity.
     *
     */
    public function deleteAction(Request $request, Sectors $sector)
    {
        $form = $this->createDeleteForm($sector);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sector);
            $em->flush();
        }

        return $this->redirectToRoute('sectors_index');
    }

    /**
     * Creates a form to delete a Sectors entity.
     *
     * @param Sectors $sector The Sectors entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Sectors $sector)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sectors_delete', array('id' => $sector->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

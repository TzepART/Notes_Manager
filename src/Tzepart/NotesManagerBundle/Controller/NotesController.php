<?php

namespace Tzepart\NotesManagerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Tzepart\NotesManagerBundle\Entity\Notes;
use Tzepart\NotesManagerBundle\Form\NotesType;

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
        $em = $this->getDoctrine()->getManager();

        $notes = $em->getRepository('NotesManagerBundle:Notes')->findAll();

        return $this->render('notes/index.html.twig', array(
            'notes' => $notes,
        ));
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
        $note = new Notes();
        $arSectors = [];
        $arCircles = [];
        $arSectorsObjects = [];
        $arLayersObjects = [];
        $countLayers = 5;
        $form = $this->createForm('Tzepart\NotesManagerBundle\Form\NotesType', $note);
        $form->handleRequest($request);

        $user = $this->getCurrentUserObject();
        $arCirclesObjects = $user->getCircles();

        foreach ($arCirclesObjects as $key => $circlesObject) {
            $arCircles[$key]["id"] = $circlesObject->getId();
            $arCircles[$key]["name"] = $circlesObject->getName();
            if($key == 0){
                $arLayersObjects = $circlesObject->getLayers();
                $arSectorsObjects = $circlesObject->getSectors();
            }
        }

     
        if(!empty($arLayersObjects) && !empty($arSectorsObjects)){
            $countLayers = count($arLayersObjects);
            foreach ($arSectorsObjects as $key => $arSectorObj) {
                $arSectors[$key]["id"] = $arSectorObj->getId();
                $arSectors[$key]["name"] = $arSectorObj->getName();
            } 
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $note->setUser($user);
            $note->setDateCreate(new \DateTime('now'));
            $note->setDateUpdate(new \DateTime('now'));
            $note->setName($request->get("name"));
            $note->setText($request->get("text"));
            $em->persist($note);
            $em->flush();

            return $this->redirectToRoute('notes_show', array('id' => $note->getId()));
        }

        return $this->render('notes/new.html.twig', array(
            'note' => $note,
            'arSectors' => $arSectors,
            'countLayers' => $countLayers,
            'arCircles' => $arCircles,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Notes entity.
     *
     */
    public function showAction(Notes $note)
    {
        $deleteForm = $this->createDeleteForm($note);

        return $this->render('notes/show.html.twig', array(
            'note' => $note,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Notes entity.
     *
     */
    public function editAction(Request $request, Notes $note)
    {
        $deleteForm = $this->createDeleteForm($note);
        $editForm = $this->createForm('Tzepart\NotesManagerBundle\Form\NotesType', $note);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($note);
            $em->flush();

            return $this->redirectToRoute('notes_edit', array('id' => $note->getId()));
        }

        return $this->render('notes/edit.html.twig', array(
            'note' => $note,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
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

    

    public function editAjaxAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {
            $arResult = [];
            $user = $this->getCurrentUserObject();
            $arCircles = $user->getCircles();
            foreach ($arCircles as $index => $arCircle) {
                if($arCircle->getId() == $request->get("circleId")){
                    $arLayers = $arCircle->getLayers();
                    $arSectors = $arCircle->getSectors();
                    $arResult["countLayers"] = count($arLayers);
                    foreach ($arSectors as $key => $arSector) {
                        $arResult["sectors"][$key]["id"] = $arSector->getId();
                        $arResult["sectors"][$key]["name"] = $arSector->getName();
                    }
                }
            }

            return new JsonResponse($arResult);
        }

        return new Response('This is not ajax!', 400);
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
            ->getForm()
        ;
    }
}

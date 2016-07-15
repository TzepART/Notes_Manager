<?php

namespace Tzepart\NotesManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Tzepart\NotesManagerBundle\Entity\Labels;

class LabelsController extends Controller
{

    /**
     * @param array $arParams
     * @return int
     */
    public function newAction($arParams)
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
    public function editAction(Labels $label,$arParams)
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
    public function deleteAction(Labels $label)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($label);
        $em->flush();

        return true;
    }
    
}

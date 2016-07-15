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
        $label->setAngle($arParams["Angle"]);
        $label->setRadius($arParams["Radius"]);
        $label->setLayers($arParams["Layer"]);
        $label->setSectors($arParams["Sector"]);
        $label->setDateCreate(new \DateTime('now'));
        $label->setDateUpdate(new \DateTime('now'));
        $em->persist($label);
        $em->flush();

        return $label->getId();
    }


    /**
     * @param Labels $label
     * @param array $arParams
     * @return int
     */
    public function editAction(Labels $label,$arParams)
    {
        $em = $this->getDoctrine()->getManager();
        if(!empty($arParams["Angle"])){
            $label->setAngle($arParams["Angle"]);
        }
        if(!empty($arParams["Radius"])){
            $label->setRadius($arParams["Radius"]);
        }
        if(!empty($arParams["Layer"])){
            $label->setLayers($arParams["Layer"]);
        }
        if(!empty($arParams["Sector"])){
            $label->setSectors($arParams["Sector"]);
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

<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 29.11.16
 * Time: 21:08
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Labels;

class LoadLabelsData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
            $label = new Labels();
            $label->setLayers($this->getReference('layer_2'));
            $label->setSectors($this->getReference('sector_1'));
            $label->setRadius(0.7);
            $label->setAngle(200);

            $this->addReference('label_0', $label);

            $manager->persist($label);
            $manager->flush();

    }

    public function getOrder()
    {
        return 5;
    }
}
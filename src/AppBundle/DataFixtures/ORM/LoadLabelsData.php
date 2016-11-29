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
            $sector = new Labels();
            $sector->setLayers($this->getReference('layer_2'));
            $sector->setSectors($this->getReference('sector_1'));
            $sector->setRadius(0.7);
            $sector->setAngle(100);

            $manager->persist($sector);
            $manager->flush();

            $this->addReference('label_0', $sector);
    }

    public function getOrder()
    {
        return 5;
    }
}
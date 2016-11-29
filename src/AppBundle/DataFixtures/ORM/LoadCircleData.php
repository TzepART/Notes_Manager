<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 28.11.16
 * Time: 21:08
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Circle;

class LoadCircleData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $circle = new Circle();
        $circle->setUser($this->getReference('example_user'));
        $circle->setName('Common circle');

        $manager->persist($circle);
        $manager->flush();

        $this->addReference('example_circle', $circle);
    }

    public function getOrder()
    {
        return 2;
    }
}
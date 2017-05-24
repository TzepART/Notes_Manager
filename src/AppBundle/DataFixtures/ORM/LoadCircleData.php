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
use Faker\Factory as FakerFactory;

class LoadCircleData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = FakerFactory::create('ru_RU');
        for($i=0; $i < 4; $i++){
            $key = $i%2;
            $circle = new Circle();
            $circle->setUser($this->getReference('example_user_'.$key));
            $circle->setName($faker->title);

            $manager->persist($circle);
            $this->addReference('example_circle_'.$i, $circle);
        }

        $manager->flush();

    }

    public function getOrder()
    {
        return 2;
    }
}
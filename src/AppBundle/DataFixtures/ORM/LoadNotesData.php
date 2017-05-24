<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 29.11.16
 * Time: 22:08
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Notes;
use Faker\Factory as FakerFactory;

class LoadNotesData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = FakerFactory::create('ru_RU');

        for ($i = 0; $i < 20; $i++){
            $userKey = $i%2;
            $note = new Notes();
            $note->setLabels($this->getReference('label_'.$i));
            $note->setUser($this->getReference('example_user_'.$userKey));
            $note->setName($faker->name);
            $note->setText($faker->realText(500));
            $manager->persist($note);
        }


        for ($i = 0; $i < 5; $i++){
            $note = new Notes();
            $note->setLabels(null);
            $note->setUser($this->getReference('example_user_'.rand(0,1)));
            $note->setName($faker->name);
            $note->setText($faker->realText(500));
            $manager->persist($note);
        }

        $manager->flush();

    }

    public function getOrder()
    {
        return 6;
    }
}
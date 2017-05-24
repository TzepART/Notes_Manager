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
        $note1 = new Notes();
        $note1->setLabels($this->getReference('label_0'));

        $note1->setUser($this->getReference('example_user'));
        $note1->setName($faker->word);
        $note1->setText($faker->text(50));
        $manager->persist($note1);

        $note2 = new Notes();
        $note2->setLabels(null);
        $note2->setUser($this->getReference('example_user'));
        $note1->setName($faker->word);
        $note1->setText($faker->text(50));

        $manager->persist($note2);
        $manager->flush();

    }

    public function getOrder()
    {
        return 6;
    }
}
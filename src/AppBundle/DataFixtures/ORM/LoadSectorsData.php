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
use AppBundle\Entity\Sectors;
use Faker\Factory as FakerFactory;


class LoadSectorsData extends AbstractFixture implements OrderedFixtureInterface
{
    protected $data = [
        [
          'begin_angle' => 0,
          'end_angle' => 120,
          'color' => '#7fff00'],
        [
          'begin_angle' => 120,
          'end_angle' => 240,
          'color' => '#6495ed'],
        [
           'begin_angle' => 240,
           'end_angle' => 360,
           'color' => '#ff8c00'
        ],
    ];

    public function load(ObjectManager $manager)
    {
        $faker = FakerFactory::create('ru_RU');

        for($i = 0; $i < 2; $i++){
            foreach ($this->data as $index => $item) {
                $sector = new Sectors();
                $sector->setCircle($this->getReference('example_circle_'.$i));
                $sector->setName($faker->title);
                $sector->setBeginAngle($item['begin_angle']);
                $sector->setEndAngle($item['end_angle']);
                $sector->setColor($item['color']);

                $manager->persist($sector);

                $this->addReference('circle_'.$i.'_sector_'.$index, $sector);
            }
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}
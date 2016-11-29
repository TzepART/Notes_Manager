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
use AppBundle\Entity\Sectors;

class LoadSectorsData extends AbstractFixture implements OrderedFixtureInterface
{
    protected $data = [
        ['name' => 'Sector_1',
          'begin_angle' => 0,
          'end_angle' => 60,
          'color' => '#7fff00'],
        ['name' => 'Sector_2',
          'begin_angle' => 60,
          'end_angle' => 120,
          'color' => '#6495ed'],
        ['name' => 'Sector_3',
            'begin_angle' => 0,
            'end_angle' => 180,
            'color' => '#ff8c00'],
    ];

    public function load(ObjectManager $manager)
    {

        foreach ($this->data as $index => $item) {
            $sector = new Sectors();
            $sector->setCircle($this->getReference('example_circle'));
            $sector->setName($item['name']);
            $sector->setBeginAngle($item['begin_angle']);
            $sector->setEndAngle($item['end_angle']);
            $sector->setColor($item['color']);

            $manager->persist($sector);
            $manager->flush();

            $this->addReference('sector_'.$index, $sector);
        }
    }

    public function getOrder()
    {
        return 3;
    }
}
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
    protected $sectorData = [
        [
            'begin_angle' => 0,
            'end_angle' => 120,
        ],
        [
            'begin_angle' => 120,
            'end_angle' => 240,
        ],
        [
            'begin_angle' => 240,
            'end_angle' => 360,
        ],
    ];

    protected $layerData = [
        [
            'begin_radius' => 0,
            'end_radius' => 25,
        ],
        [
            'begin_radius' => 25,
            'end_radius' => 50,
        ],
        [
            'begin_radius' => 50,
            'end_radius' => 75,
        ],
        [
            'begin_radius' => 75,
            'end_radius' => 100,
        ],
    ];

    public function load(ObjectManager $manager)
    {
            for ($i = 0; $i < 20; $i++){
                $circleKey = $i%4;
                $sectorKey = array_rand($this->sectorData);
                $sector = $this->sectorData[$sectorKey];
                $layerKey = array_rand($this->layerData);
                $layer = $this->layerData[$layerKey];

                $label = new Labels();
                $label->setLayers($this->getReference('circle_' . $circleKey . '_layer_' . $layerKey));
                $label->setSectors($this->getReference('circle_' . $circleKey . '_sector_' . $sectorKey));
                $label->setRadius(rand($layer['begin_radius'],$layer['end_radius'])/100);
                $label->setAngle(rand($sector['begin_angle'],$sector['end_angle']));
                $this->addReference('label_'.$i, $label);
                $manager->persist($label);
            }

            $manager->flush();

    }

    public function getOrder()
    {
        return 5;
    }
}
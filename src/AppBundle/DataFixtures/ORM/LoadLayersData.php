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
use AppBundle\Entity\Layers;

class LoadLayersData extends AbstractFixture implements OrderedFixtureInterface
{
    protected $data = [
        [
            'begin_radius' => 0,
            'end_radius' => 0.25,
            'color' => '#FFF'],
        [
            'begin_radius' => 0.25,
            'end_radius' => 0.5,
            'color' => '#FFF'],
        [
            'begin_radius' => 0.5,
            'end_radius' => 0.75,
            'color' => '#FFF'],
        [
            'begin_radius' => 0.75,
            'end_radius' => 1,
            'color' => '#FFF'
        ],
    ];

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 2; $i++) {
            foreach ($this->data as $index => $item) {
                $layer = new Layers();
                $layer->setCircle($this->getReference('example_circle_' . $i));
                $layer->setBeginRadius($item['begin_radius']);
                $layer->setEndRadius($item['end_radius']);
                $layer->setColor($item['color']);

                $manager->persist($layer);

                $this->addReference('circle_' . $i . '_layer_' . $index, $layer);
            }
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}
<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Circle;
use AppBundle\Tests\CommonApp;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;


class CircleControllerTest extends WebTestCase
{
    public function testCheckCirclePage()
    {
        $client = $this->loadDefaultData();

        $circle = $this->returnEntityByUser($client,Circle::class);

        $client->request('GET',  '/circle/'.$circle->getId().'/show');
        $this->assertTrue(
            $client->getResponse()->isSuccessful()
        );
    }

    /**
     * @param Client $client
     * @param string $class
     * @return mixed
     */
    private function returnEntityByUser($client,$class){

        $container = static::$kernel->getContainer();
        $user = $container->get('security.token_storage')->getToken()->getUser();

        /**
         * @var mixed $entity
         */
        $entity = $client->getContainer()->get('doctrine')->getRepository($class)->findOneBy(['user' => $user]);

        return $entity;
    }

    /**
     * @return mixed
     */
    private function loadDefaultData()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadCircleData',
            'AppBundle\DataFixtures\ORM\LoadLabelsData',
            'AppBundle\DataFixtures\ORM\LoadLayersData',
            'AppBundle\DataFixtures\ORM\LoadNotesData',
            'AppBundle\DataFixtures\ORM\LoadSectorsData',
            'AppBundle\DataFixtures\ORM\LoadUserData',
        ));

        $client = CommonApp::loginUser();
        return $client;
    }
}

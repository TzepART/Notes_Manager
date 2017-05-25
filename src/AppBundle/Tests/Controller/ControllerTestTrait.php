<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.05.17
 * Time: 10:25
 */

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\CommonApp;
use Symfony\Bundle\FrameworkBundle\Client;


trait ControllerTestTrait
{
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
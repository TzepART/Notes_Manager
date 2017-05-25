<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Circle;
use AppBundle\Entity\Notes;
use AppBundle\Tests\CommonApp;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;


class NotesControllerTest extends WebTestCase
{
    public function testCheckNewNotePageByCircle()
    {
        $client = $this->loadDefaultData();
        $circle = $this->returnEntityByUser($client,Circle::class);

        $client->request('GET',  '/notes/new/'.$circle->getId());
        $this->assertTrue(
            $client->getResponse()->isSuccessful()
        );
    }

    public function testCheckNotesPageByCircle()
    {
        $client = $this->loadDefaultData();
        $circle = $this->returnEntityByUser($client,Circle::class);

        $client->request('GET',  '/notes/list/'.$circle->getId().'/');
        $this->assertTrue(
            $client->getResponse()->isSuccessful()
        );
    }

    public function testCheckEditNotePage()
    {
        $client = $this->loadDefaultData();
        $note = $this->returnEntityByUser($client,Notes::class);

        $client->request('GET',  '/notes/'.$note->getId().'/edit');
        $this->assertTrue(
            $client->getResponse()->isSuccessful()
        );
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

}

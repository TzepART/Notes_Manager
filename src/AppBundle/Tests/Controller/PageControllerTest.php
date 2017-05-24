<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Circle;
use AppBundle\Entity\Notes;
use AppBundle\Tests\CommonApp;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class PageControllerTest extends WebTestCase
{
    /**
     * @dataProvider getUrlsProvider
     */
    public function testCheckRedirectStatusMainPages($url)
    {
        $client = static::createClient();

        $client->request('GET',  $url);
        $this->assertTrue(
            $client->getResponse()->isRedirect()
        );
    }

    /**
     * @dataProvider getUrlsProvider
     */
    public function testCheckSuccessStatusMainPages($url)
    {
        $client = $this->loadDefaultData();

        $client->request('GET',  $url);
        $this->assertTrue(
            $client->getResponse()->isSuccessful()
        );
    }

    public function testCheckCirclePage()
    {
        $client = $this->loadDefaultData();

        $circle = $this->returnEntityByUser($client,Circle::class);

        $client->request('GET',  '/circle/'.$circle->getId().'/show');
        $this->assertTrue(
            $client->getResponse()->isSuccessful()
        );
    }

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

    public function getUrlsProvider()
    {
        return [
            ['/'],
            ['/circle/'],
            ['/circle/new'],
            ['/notes/'],
            ['/notes/new'],
        ];
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

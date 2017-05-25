<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\CommonApp;
use Liip\FunctionalTestBundle\Test\WebTestCase;

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



}

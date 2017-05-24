<?php

namespace AppBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class PageControllerTest extends WebTestCase
{
    public function testCompleteScenario()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadCircleData',
            'AppBundle\DataFixtures\ORM\LoadLabelsData',
            'AppBundle\DataFixtures\ORM\LoadLayersData',
            'AppBundle\DataFixtures\ORM\LoadNotesData',
            'AppBundle\DataFixtures\ORM\LoadSectorsData',
            'AppBundle\DataFixtures\ORM\LoadUserData',
        ));

        $client = static::createClient();
    }

    /**
     * @dataProvider getUrlsProvider
     */
    public function testPagesResponseStatus($url)
    {

        $client = static::createClient();

        $client->request('GET',  $url);
        $this->assertTrue(
            $client->getResponse()->isRedirect()
        );
    }

    public function getUrlsProvider()
    {
        return [
            ['/'],
        ];
    }
}

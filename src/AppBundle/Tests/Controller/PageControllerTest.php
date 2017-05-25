<?php

namespace AppBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class PageControllerTest extends WebTestCase
{
    use ControllerTestTrait;

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
}

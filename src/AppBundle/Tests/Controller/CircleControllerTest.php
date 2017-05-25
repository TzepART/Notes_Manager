<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Circle;
use Liip\FunctionalTestBundle\Test\WebTestCase;


class CircleControllerTest extends WebTestCase
{
    use ControllerTestTrait;

    public function testCheckCirclePage()
    {
        $client = $this->loadDefaultData();

        $circle = $this->returnEntityByUser($client,Circle::class);

        $client->request('GET',  '/circle/'.$circle->getId().'/show');
        $this->assertTrue(
            $client->getResponse()->isSuccessful()
        );
    }
}

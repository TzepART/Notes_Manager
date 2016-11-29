<?php

namespace AppBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class CircleControllerTest extends WebTestCase
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
}

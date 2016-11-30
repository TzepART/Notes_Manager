<?php

namespace AppBundle\Tests\Common;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Tests\TestClient;

class AuthTest extends WebTestCase
{

    const TEST_LOGIN = 'autotest@xtest.ru';
    const TEST_PASSWORD = '123456';

    public function testRegistration()
    {

        /** @var TestClient $client */
        $client = static::createClient();

        $client->request('POST', '/registration/', [
            'customer[email]' => self::TEST_LOGIN,
            'customer[password][first]' => self::TEST_PASSWORD,
            'customer[password][second]' => self::TEST_PASSWORD,
            'agree' => 'on',
            'customer[inviterId]' => '',
            'g-recaptcha-response' => ''
        ]);

        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"status":"error"', $client->getResponse()->getContent());

    }

    public function testLogin()
    {
        /** @var TestClient $client */
        $client = static::createClient();

        $client->request('POST', '/form_login_check?_animal=cat', [
            'login' => self::TEST_LOGIN,
            'password' => self::TEST_PASSWORD
        ]);


        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('success', $client->getResponse()->getContent());

        return $client;
    }


}
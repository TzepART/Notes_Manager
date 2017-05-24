<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\User;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    const EXAMPLE_EMAIL = 'user@mail.com';
    const EXAMPLE_LOGIN = 'user2';
    const EXAMPLE_PASSWORD = 'qweqwe';
    const REGISTER_URL = '/register/';
    const LOGIN_URL = '/login';


    public function testRegister()
    {

        $client = static::createClient();

        $crawler = $client->request('GET', self::REGISTER_URL);

        // Get the form.
        $form = $crawler->filter('form')->form();


        $values = array(
            'fos_user_registration_form[email]'  => self::EXAMPLE_EMAIL,
            'fos_user_registration_form[username]'  => self::EXAMPLE_LOGIN,
            'fos_user_registration_form[plainPassword][first]'  => self::EXAMPLE_PASSWORD,
            'fos_user_registration_form[plainPassword][second]'  => self::EXAMPLE_PASSWORD,
            'fos_user_registration_form[_token]' => $form->getValues()["fos_user_registration_form[_token]"]
        );

        // Submit the data.
        $client->request($form->getMethod(), $form->getUri(),
            $values);

        $this->assertTrue(
            $client->getResponse()->isSuccessful()
        );

    }


    public function testLogin()
    {
        $this->loadFixtures(array(
            'AppBundle\DataFixtures\ORM\LoadUserData',
        ));

        $client = static::createClient();
        $crawler = $client->request('GET', self::LOGIN_URL);

        // Get the form.
        $form = $crawler->filter('form')->form();

        $values = array(
            '_username' => self::EXAMPLE_LOGIN,
            '_password' => self::EXAMPLE_PASSWORD,
            '_csrf_token' => $form->getValues()['_csrf_token']
        );

        // Submit the data.
        $client->request($form->getMethod(), $form->getUri(),
            $values);

        $container = static::$kernel->getContainer();
        $user = $container->get('security.token_storage')->getToken()->getUser();

        $this->assertTrue(
            $user instanceof User
        );
    }
}

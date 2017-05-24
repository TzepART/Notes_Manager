<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 25.05.17
 * Time: 0:39
 */

namespace AppBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class CommonApp extends WebTestCase
{

    const EXAMPLE_LOGIN = 'user2';
    const EXAMPLE_PASSWORD = 'qweqwe';
    const LOGIN_URL = '/login';

    /**
     * @return mixed
     */
    static function loginUser()
    {
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

        return $client;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 27.11.16
 * Time: 21:08
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;


class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{
    protected $users = [
      [
          'login' => 'user1',
          'password' => 'qweqwe',
          'email' => 'user1@mail.com',
      ],
      [
          'login' => 'user2',
          'password' => 'qweqwe',
          'email' => 'user2@mail.com',
      ],
    ];
    public function load(ObjectManager $manager)
    {

        foreach ($this->users as $index => $arUser) {
            $user = new User();
            $user->setUsername($arUser['login']);
            $user->setPlainPassword($arUser['password']);
            $user->setEmail($arUser['email']);
            $user->setEnabled(true);
            $user->setLogged(new \DateTime('now'));
            $manager->persist($user);
            $this->addReference('example_user_'.$index, $user);
        }

        $manager->flush();

    }

    public function getOrder()
    {
        return 1;
    }
}
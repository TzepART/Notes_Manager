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
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('user2');
        $user->setPassword('123123');
        $user->setEmail('user@mail.com');
        $user->setRoles('USER');

        $manager->persist($user);
        $manager->flush();

        $this->addReference('example_user', $user);
    }

    public function getOrder()
    {
        return 1;
    }
}
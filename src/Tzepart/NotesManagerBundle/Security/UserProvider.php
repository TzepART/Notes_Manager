<?php

/**
 * Created by PhpStorm.
 * User: Ri
 * Date: 14.05.2016
 * Time: 21:48
 */
namespace Tzepart\NotesManagerBundle\Security;

/**
 * По мотивам: http://symfony.com/doc/current/cookbook/security/custom_authentication_provider.html
 * Провайдер пользователя - находит пользователя по уникальному полю username.
 */

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityManager;

use Tzepart\NotesManagerBundle\Entity\Users;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class UserProvider implements UserProviderInterface
{
    /**
     * находит и возвращает экземпляр класса User или выбрасывает Exception
     * @param string $username - мыло пользователя
     *
     * @return false|Users|\Symfony\Component\Security\Core\User\UserInterface
     * @throws \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     */
    public function loadUserByUsername( $username = '' )
    {
        if (empty($username)) {
            throw new UsernameNotFoundException('Username is empty.');
        }

//  @TODO сделать выборку users по условию 	username=$username

        $r['username'] = "artem";
        $r['password'] = '$2y$13$PPFBavHa55dsmWQJxCRUFuPKlWe1SAyJL1qsPw';
        $r['role'] = 'USER';
//        if(empty($r)){
//            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
//        }

        $user = new Users();
        $user->setUsername($r['username']);
        $user->setRoles($r['role']);
        $user->setPassword($r['password']);// напомню, это хэш пароля (как его создать - читай ниже)

        return $user;
    }

    /**
     * метод проверяет вид сущности пользователя (ведь их может быть много)
     *
     * @param UserInterface $user
     *
     * @return Users|UserInterface
     * @throws \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     */
    public function refreshUser( UserInterface $user )
    {
        if (!$user instanceof Users) {
            throw new UnsupportedUserException( sprintf( 'Instances of "%s" are not supported.', get_class( $user ) ) );
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Метод проверки класса пользователя
     * нужен чтобы Symfony использовал правильный класс Пользователя для получения объекта пользователя
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass( $class )
    {
        return $class === 'Tzepart\\NotesManagerBundle\\Entity\\Users';
    }
}
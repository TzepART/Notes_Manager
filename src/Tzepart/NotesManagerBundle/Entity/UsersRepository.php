<?php
/**
 * Created by PhpStorm.
 * User: Ri
 * Date: 14.05.2016
 * Time: 22:06
 */

namespace Tzepart\NotesManagerBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UsersRepository extends EntityRepository
{
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
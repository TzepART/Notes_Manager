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
    public function findAllUsersByName($username)
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT u FROM NotesManagerBundle:Users u WHERE username = $username"
            )
            ->getResult();
    }
}
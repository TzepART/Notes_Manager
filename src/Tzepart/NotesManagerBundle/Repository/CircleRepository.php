<?php

namespace Tzepart\NotesManagerBundle\Repository;

use Tzepart\NotesManagerBundle\Entity\Circle;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;



class CircleRepository extends EntityRepository
{
    public function findByIdSectorsByIdToCircle($circleId)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT * FROM NotesManagerBundle:Sectors s
                WHERE s.circle_id = :id'
                )->setParameter('id', $circleId);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}

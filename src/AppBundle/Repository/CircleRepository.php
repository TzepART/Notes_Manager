<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Circle;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;



class CircleRepository extends EntityRepository
{
    public function findByIdSectorsByIdToCircle($circleId)
    {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT * FROM AppBundle:Sectors s
                WHERE s.circle_id = :id'
                )->setParameter('id', $circleId);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }
}

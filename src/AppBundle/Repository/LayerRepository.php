<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.05.17
 * Time: 16:52
 */

namespace AppBundle\Repository;


use AppBundle\Entity\Circle;
use Doctrine\ORM\EntityRepository;

class LayerRepository extends EntityRepository
{

    public function getLayersByCircleAndRadius(Circle $circle, $radius)
    {
        return $this->createQueryBuilder('l')
            ->where('l.beginRadius < :radius')
            ->andWhere('l.endRadius > :radius')
            ->andWhere('l.circle = :circle')
            ->setParameter('circle',$circle)
            ->setParameter('radius', $radius)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
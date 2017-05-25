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

class SectorRepository extends EntityRepository
{

    public function getSectorsByCircleAndRadius(Circle $circle, $angle)
    {

        return $this->createQueryBuilder('sector')
            ->where('sector.beginAngle < :angle AND sector.endAngle > :angle')
            ->andWhere('sector.circle = :circle')
            ->setParameter('angle', $angle)
            ->setParameter('circle',$circle)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
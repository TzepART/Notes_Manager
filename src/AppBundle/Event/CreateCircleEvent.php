<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 07.12.16
 * Time: 2:38
 */

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\Circle;

class CreateCircleEvent extends Event
{
    const NAME = 'circle.create';

    protected $circle;

    public function __construct(Circle $circle)
    {
        $this->circle = $circle;
    }

    public function getCircle()
    {
        return $this->circle;
    }
}
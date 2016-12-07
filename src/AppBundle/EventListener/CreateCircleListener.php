<?php
/**
 * Created by PhpStorm.
 * User: tzepart
 * Date: 07.12.16
 * Time: 2:46
 */

namespace AppBundle\EventListener;

use AppBundle\Event\CreateCircleEvent;

class CreateCircleListener
{
    public function onCreateCircle(CreateCircleEvent $event)
    {
        $circle = $event->getCircle();
        $circle->setName("eeeeeeeeee");
        return $circle;
    }
}
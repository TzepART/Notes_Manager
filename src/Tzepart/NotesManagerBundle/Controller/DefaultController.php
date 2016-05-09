<?php

namespace Tzepart\NotesManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('NotesManagerBundle:Default:index.html.twig');
    }
}

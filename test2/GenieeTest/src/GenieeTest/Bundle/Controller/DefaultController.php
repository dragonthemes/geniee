<?php

namespace GenieeTest\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GenieeTestBundle:Default:index.html.twig');
    }
}

<?php

namespace LA\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends Controller
{
    public function indexAction()
    {
        return $this->render('LAAppBundle:App:index.html.twig');
    }

    public function contactAction()
    {
    	return $this->render('LAAppBundle:App:contact.html.twig');
    }
}

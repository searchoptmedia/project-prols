<?php

namespace SmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SmsBundle:Default:index.html.twig', array('name' => $name));
    }
    
}

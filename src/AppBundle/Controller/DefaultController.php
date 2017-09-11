<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
  /**
  * @Route("/")
  */
  public function indexAction(Request $request)
  {
    $object = new \stdClass();
    $object->hello = "world";
    return new Response(json_encode($object));
  }
}

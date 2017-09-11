<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HazardousController extends Controller
{
  /**
  * @Route("/neo/hazardous")
  */
  public function indexAction(Request $request)
  {
    $dbconn = pg_connect("host=localhost port=5432 dbname=neo_data");
    $tablename = "public.neo_table";
    $result = pg_exec($dbconn, "SELECT * FROM $tablename WHERE is_neo_hazardous = TRUE");
    $array = pg_fetch_all($result);

    return new Response(json_encode($array));
  }
}

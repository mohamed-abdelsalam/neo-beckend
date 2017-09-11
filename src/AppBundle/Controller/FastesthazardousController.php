<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FastesthazardousController extends Controller
{
  /**
  * @Route("/neo/fastest")
  */
  public function indexAction(Request $request)
  {
    $is_neo_hazardous = $request->query->get('hazardous');
    if ($is_neo_hazardous == 'true' || $is_neo_hazardous == 'false') {
      $dbconn = pg_connect("host=localhost port=5432 dbname=neo_data");
      $tablename = "public.neo_table";
      $get_fastest_query = pg_exec($dbconn,
        "SELECT MAX(neo_speed) as neo_speed FROM $tablename WHERE is_neo_hazardous = $is_neo_hazardous");
      $fastest_result = pg_fetch_all($get_fastest_query);
      $fastest = $fastest_result[0]['neo_speed'];
      $result = pg_exec($dbconn,
        "SELECT * FROM $tablename WHERE (is_neo_hazardous = $is_neo_hazardous) and (neo_speed = $fastest)");
      $array = pg_fetch_all($result);
      return new Response(json_encode($array));
    } else {
      return new Response("Invalid GET parameter $is_neo_hazardous");
    }
  }
}

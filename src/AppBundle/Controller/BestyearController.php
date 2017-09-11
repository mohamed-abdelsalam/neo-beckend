<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BestyearController extends Controller
{
  /**
  * @Route("/neo/best-year")
  */
  public function indexAction(Request $request)
  {
    $is_neo_hazardous = $request->query->get('hazardous');
    if ($is_neo_hazardous == 'true' || $is_neo_hazardous == 'false') {
      $dbconn = pg_connect("host=localhost port=5432 dbname=neo_data");
      $tablename = "public.neo_table";
      $get_maxyear_query = pg_exec($dbconn,
        "SELECT count(*) as count, extract(year from neo_date) as year FROM $tablename WHERE is_neo_hazardous = $is_neo_hazardous group by year");
      $maxyear_result = pg_fetch_all($get_maxyear_query);
      return new Response(json_encode($this -> get_maxyear($maxyear_result)));
    } else {
      return new Response("Invalid GET parameter $is_neo_hazardous");
    }
  }

  function get_maxyear($years_count)
  {
    $count = 0;
    $bestyear = 0;
    for ($index = 0; $index < count($years_count); $index ++){
      if ($years_count[$index]['count'] > $count) {
        $count = $years_count[$index]['count'];
        $bestyear = $years_count[$index]['year'];
      }
    }
    return $bestyear;
  }
}

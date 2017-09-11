<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BestmonthController extends Controller
{
  /**
  * @Route("/neo/best-month")
  */
  public function indexAction(Request $request)
  {
    $is_neo_hazardous = $request->query->get('hazardous');
    if ($is_neo_hazardous == 'true' || $is_neo_hazardous == 'false') {
      $dbconn = pg_connect("host=localhost port=5432 dbname=neo_data");
      $tablename = "public.neo_table";
      $get_maxmonth_query = pg_exec($dbconn,
        "SELECT count(*) as count, extract(month from neo_date) as month FROM $tablename WHERE is_neo_hazardous = $is_neo_hazardous group by month");
      $maxmonth_result = pg_fetch_all($get_maxmonth_query);
      return new Response(json_encode($this -> get_maxyear($maxmonth_result)));
    } else {
      return new Response("Invalid GET parameter $is_neo_hazardous");
    }
  }

  function get_maxyear($month_count)
  {
    $count = 0;
    $bestmonth = 0;
    for ($index = 0; $index < count($month_count); $index ++){
      if ($month_count[$index]['count'] > $count) {
        $count = $month_count[$index]['count'];
        $bestmonth = $month_count[$index]['month'];
      }
    }
    return $bestmonth;
  }
}

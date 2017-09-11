<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoadCommand extends ContainerAwareCommand {
    protected function configure() {
        $this ->setName('app:loaddata')
          ->addArgument('name', InputArgument::OPTIONAL);
    }

    function getURL()
    {
      $api_key = "N7LkblDsc5aen05FJqBQ8wU4qSdmsftwJagVK7UD";
      $end_date = date("Y-m-d");
      $start_date = date("Y-m-d", strtotime("$end_date - 3 days"));
      $base_url = "https://api.nasa.gov/neo/rest/v1/feed";
      $neo_url = $base_url.
              "?start_date=".$start_date.
              "&end_date=".$end_date.
              "&detailed=true".
              "&api_key=".$api_key;
      return $neo_url;
    }

    function parseJson($dataJson)
    {
      $today_date = date("Y-m-d");
      $result = array();
      for ($day = 0; $day < 4; $day ++) {
        $date = date("Y-m-d", strtotime("$today_date - $day days"));
        $array = $dataJson->{'near_earth_objects'} -> {$date};
        $size = count($array);
        for ($index = 0; $index < $size; $index ++) {
          $result[] = $array[$index]-> {'neo_reference_id'} . "\t" .
                      $array[$index]-> {'name'} . "\t" .
                      $date . "\t" .
                      $array[$index]-> {'close_approach_data'}[0] -> {'relative_velocity'} -> {'kilometers_per_hour'} . "\t" .
                      ($array[$index]-> {'is_potentially_hazardous_asteroid'} ? 'true' : 'false');
        }
      }
      return $result;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
      $neo_url = $this -> getURL();
      $data = file_get_contents($neo_url);
      $dataJson = json_decode($data);
      $array = $this -> parseJson($dataJson);
      $dbconn = pg_connect("host=localhost port=5432 dbname=neo_data");
      $tablename = "public.neo_table";
      $status = pg_copy_from($dbconn, $tablename, $array);
      pg_close($dbconn);
      if ($status) {
        $output->writeln('Elements : ' . $dataJson->{'element_count'});
      } else {
        $output->writeln('Failed to insert into $tablename');
      }
    }
}

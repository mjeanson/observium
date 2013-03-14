<?php

include("includes/graphs/common.inc.php");

$scale_min    = 0;
$colours      = "mixed";
$nototal      = 1;
$unit_text    = "Queries/sec";

$thread = 0;

$i = 0;

while (1)
{
  $rrd_filename = $config['rrd_dir'] . "/" . $device['hostname'] . "/app-unbound-".$app['app_id']."-thread$thread.rrd";
  if (file_exists($rrd_filename))
  {
    $rrd_list[$i]['filename'] = $rrd_filename;
    $rrd_list[$i]['descr']    = "Queries handled by thread$thread";
    $rrd_list[$i]['ds']       = "numQueries";
    $rrd_list[$i]['colour']   = $config['graph_colours'][$colours][$i % count($config['graph_colours'][$colours])];
    $i++;

    $thread++;
  }
  else
  {
    break;
  }
}

$rrd_filename = $config['rrd_dir'] . "/" . $device['hostname'] . "/app-unbound-".$app['app_id']."-total.rrd";
    
$array        = array(
                      'numQueries' => array('descr' => 'Total queries', 'colour' => '00FF00FF'), /// FIXME better colours
                      'cacheHits' => array('descr' => 'Cache hits', 'colour' => '0000FFFF'),
                      'prefetch' => array('descr' => 'Cache prefetch', 'colour' => 'FF0000FF'),
                     );

if (is_file($rrd_filename))
{
  foreach ($array as $ds => $vars)
  {
    $rrd_list[$i]['filename'] = $rrd_filename;
    $rrd_list[$i]['descr']    = $vars['descr'];
    $rrd_list[$i]['ds']       = $ds;
    $rrd_list[$i]['colour']   = $vars['colour'];
    $i++;
  }
} else {
  echo("file missing: $file");
}

$rrd_filename = $config['rrd_dir'] . "/" . $device['hostname'] . "/app-unbound-".$app['app_id']."-queries.rrd";
    
$array        = array(
                      'numQueryTCP' => array('descr' => 'TCP queries', 'colour' => '00FFFFFF'),
                      'numQueryIPv6' => array('descr' => 'IPv6 queries', 'colour' => '00FFFFFF'),
                      'numQueryUnwanted' => array('descr' => 'Unwanted queries', 'colour' => '00FFFFFF'),
                      'numReplyUnwanted' => array('descr' => 'Unwanted replies', 'colour' => '00FFFFFF'), /// FIXME better colours
                     );

if (is_file($rrd_filename))
{
  foreach ($array as $ds => $vars)
  {
    $rrd_list[$i]['filename'] = $rrd_filename;
    $rrd_list[$i]['descr']    = $vars['descr'];
    $rrd_list[$i]['ds']       = $ds;
    $rrd_list[$i]['colour']   = $vars['colour'];
    $i++;
  }
} else {
  echo("file missing: $file");
}

include("includes/graphs/generic_multi_line.inc.php");

?>
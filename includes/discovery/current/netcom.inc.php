<?php

// NETCOM UPS
if ($device['os'] == 'netcom')
{
  echo("NETCOM-UPS-MIB ");

  $oids = snmp_walk($device, "upsBatteryCurrent", "-Osqn", "NETCOM-UPS-MIB");
  if ($debug) { echo($oids."\n"); }
  $oids = trim($oids);
  foreach (explode("\n", $oids) as $data)
  {
    $data = trim($data);
    if ($data)
    {
      list($oid,$descr) = explode(" ", $data,2);
      $split_oid = explode('.',$oid);
      $current_id = $split_oid[count($split_oid)-1];
      $current_oid  = ".1.3.6.1.4.1.13891.101.2.6.$current_id";
      $divisor = 10;
      $current = snmp_get($device, $current_oid, "-O vq") / $divisor;
      $descr = "Battery" . (count(explode("\n",$oids)) == 1 ? '' : ' ' . ($current_id+1));
      $type = "netcom";
      $index = 500+$current_id;

      discover_sensor($valid['sensor'], 'current', $device, $current_oid, $index, $type, $descr, $divisor, '1', NULL, NULL, NULL, NULL, $current);
    }
  }

  $oids = trim(snmp_walk($device, "upsOutputNumLines", "-OsqnU", "NETCOM-UPS-MIB"));
  if ($debug) { echo($oids."\n"); }
  list($unused,$numLine) = explode(' ',$oids);
  for($i = 1; $i <= $numLine;$i++)
  {
    $current_oid   = ".1.3.6.1.4.1.13891.101.4.4.1.3.$i";
    $descr      = "Output"; if ($numLine > 1) $descr .= " Phase $i";
    $divisor    = 10;
    $current    = snmp_get($device, $current_oid, "-Oqv") / $divisor;
    $type       = "netcom";
    $index      = $i;

    discover_sensor($valid['sensor'], 'current', $device, $current_oid, $index, $type, $descr, $divisor, '1', NULL, NULL, NULL, NULL, $current);
  }

}

?>

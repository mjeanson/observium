<?php

// NETCOM UPS
if ($device['os'] == 'netcom')
{
  echo("NETCOM-UPS-MIB ");

  $oids = trim(snmp_walk($device, "upsOutputNumLines", "-OsqnU", "NETCOM-UPS-MIB"));
  if ($debug) { echo($oids."\n"); }
  list($unused,$numLine) = explode(' ',$oids);
  for($i = 1; $i <= $numLine;$i++)
  {
    $load_oid   = ".1.3.6.1.4.1.13891.101.4.4.1.5.$i";
    $descr      = "Output"; if ($numLine > 1) $descr .= " Phase $i";
    $load    = snmp_get($device, $load_oid, "-Oqv");
    $type       = "netcom";
    $index      = $i;

    discover_sensor($valid['sensor'], 'load', $device, $load_oid, $index, $type, $descr, '1', '1', NULL, NULL, NULL, NULL, $load);
  }

}

?>

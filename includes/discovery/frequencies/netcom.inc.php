<?php

// NETCOM UPS
if ($device['os'] == 'netcom')
{
  echo("NETCOM ");

  $oids = trim(snmp_walk($device, ".1.3.6.1.4.1.13891.101.3.2.0", "-OsqnU"));
  if ($debug) { echo($oids."\n"); }
  list($unused,$numPhase) = explode(' ',$oids);
  for($i = 1; $i <= $numPhase;$i++)
  {
    $freq_oid   = ".1.3.6.1.4.1.13891.101.3.3.1.2.$i";
    $descr      = "Input"; if ($numPhase > 1) $descr .= " Phase $i";
    $current    = snmp_get($device, $freq_oid, "-Oqv") / 10;
    $type       = "netcom";
    $divisor  = 10;
    $index      = '3.2.0.'.$i;
    discover_sensor($valid['sensor'], 'frequency', $device, $freq_oid, $index, $type, $descr, $divisor, '1', NULL, NULL, NULL, NULL, $current);
  }

  $freq_oid   = ".1.3.6.1.4.1.13891.101.4.2.0";
  $descr      = "Output";
  $current    = snmp_get($device, $freq_oid, "-Oqv") / 10;
  $type       = "netcom";
  $divisor  = 10;
  $index      = '4.2.0';
  discover_sensor($valid['sensor'], 'frequency', $device, $freq_oid, $index, $type, $descr, $divisor, '1', NULL, NULL, NULL, NULL, $current);

  $freq_oid   = ".1.3.6.1.4.1.13891.101.5.1.0";
  $descr      = "Bypass";
  $current    = snmp_get($device, $freq_oid, "-Oqv") / 10;
  $type       = "netcom";
  $divisor  = 10;
  $index      = '5.1.0';
  discover_sensor($valid['sensor'], 'frequency', $device, $freq_oid, $index, $type, $descr, $divisor, '1', NULL, NULL, NULL, NULL, $current);
}

?>

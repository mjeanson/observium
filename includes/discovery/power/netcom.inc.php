<?php

// NETCOM UPS
if ($device['os'] == 'netcom')
{
  echo("NETCOM-UPS-MIB ");

  // The only relevent output power is on phase 1
  $freq_oid   = ".1.3.6.1.4.1.13891.101.4.4.1.4.1";
  $descr      = "Output";
  $current    = snmp_get($device, $freq_oid, "-Oqv");
  $type       = "netcom";
  $divisor    = 1;
  $index      = '4.4.1.4.1';
  discover_sensor($valid['sensor'], 'power', $device, $freq_oid, $index, $type, $descr, $divisor, '1', NULL, NULL, NULL, NULL, $current);
}

?>

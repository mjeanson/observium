<?php

// NETCOM UPS
if ($device['os'] == 'netcom')
{
  echo("NETCOM-UPS-MIB ");

  $divisor = 10;

  $input_freq  = snmp_get($device, "upsConfigInputFreq.0", "-Oqv", "NETCOM-UPS-MIB") / $divisor;
  $output_freq = snmp_get($device, "upsConfigOutputFreq.0", "-Oqv", "NETCOM-UPS-MIB") / $divisor;

  // The only relevent input frenquency is on phase 1
  $freq_oid   = ".1.3.6.1.4.1.13891.101.3.3.1.2.1";
  $descr      = "Input";
  $current    = snmp_get($device, $freq_oid, "-Oqv") / $divisor;
  $limit      = $input_freq * 1.05;
  $llimit     = $input_freq - ($input_freq * 0.05);
  $type       = "netcom";
  $index      = '3.3.1.2.1';
  discover_sensor($valid['sensor'], 'frequency', $device, $freq_oid, $index, $type, $descr, $divisor, '1', $llimit, NULL, NULL, $limit, $current);

  $freq_oid   = ".1.3.6.1.4.1.13891.101.4.2.0";
  $descr      = "Output";
  $current    = snmp_get($device, $freq_oid, "-Oqv") / $divisor;
  $limit      = $output_freq * 1.05;
  $llimit     = $output_freq - ($output_freq * 0.05);
  $type       = "netcom";
  $index      = '4.2.0';
  discover_sensor($valid['sensor'], 'frequency', $device, $freq_oid, $index, $type, $descr, $divisor, '1', $llimit, NULL, NULL, $limit, $current);

  $freq_oid   = ".1.3.6.1.4.1.13891.101.5.1.0";
  $descr      = "Bypass";
  $current    = snmp_get($device, $freq_oid, "-Oqv") / $divisor;
  $limit      = $output_freq * 1.05;
  $llimit     = $output_freq - ($output_freq * 0.05);
  $type       = "netcom";
  $index      = '5.1.0';
  discover_sensor($valid['sensor'], 'frequency', $device, $freq_oid, $index, $type, $descr, $divisor, '1', $llimit, NULL, NULL, $limit, $current);
}

?>

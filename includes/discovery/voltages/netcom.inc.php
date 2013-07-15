<?php

// NETCOM UPS Voltages
if ($device['os'] == 'netcom')
{
  echo("NETCOM-UPS-MIB ");

  $oids = snmp_walk($device, "upsBatteryVoltage", "-Osqn", "NETCOM-UPS-MIB");
  if ($debug) { echo($oids."\n"); }
  $oids = trim($oids);
  foreach (explode("\n", $oids) as $data)
  {
    $data = trim($data);
    if ($data)
    {
      list($oid,$descr) = explode(" ", $data,2);
      $split_oid = explode('.',$oid);
      $volt_id   = $split_oid[count($split_oid)-1];
      $volt_oid  = ".1.3.6.1.4.1.13891.101.2.5.$volt_id";
      $divisor   = 10;
      $volt      = snmp_get($device, $volt_oid, "-O vq") / $divisor;
      $descr     = "Battery" . (count(explode("\n",$oids)) == 1 ? '' : ' ' . ($volt_id+1));
      $type      = "netcom";
      $index     = "1.2.5.".$volt_id;

      discover_sensor($valid['sensor'], 'voltage', $device, $volt_oid, $index, $type, $descr, $divisor, '1', NULL, NULL, NULL, NULL, $volt);
    }
  }

  $divisor  = 1;
  $input_volt  = snmp_get($device, "upsConfigInputVoltage.0", "-Oqv", "NETCOM-UPS-MIB") / $divisor;
  $output_volt = snmp_get($device, "upsConfigOutputVoltage.0", "-Oqv", "NETCOM-UPS-MIB") / $divisor;

  $oids = trim(snmp_walk($device, "upsOutputNumLines", "-OsqnU", "NETCOM-UPS-MIB"));
  if ($debug) { echo($oids."\n"); }
  list($unused,$numPhase) = explode(' ',$oids);
  for($i = 1; $i <= $numPhase;$i++)
  {
    $volt_oid = ".1.3.6.1.4.1.13891.101.4.4.1.2.$i";
    $descr    = "Output"; if ($numPhase > 1) $descr .= " Phase $i";
    $type     = "netcom";
    $current  = snmp_get($device, $volt_oid, "-Oqv") / $divisor;
    $limit    = $output_volt * 1.05;
    $llimit   = $output_volt - ($output_volt * 0.05);
    $index    = $i;

    discover_sensor($valid['sensor'], 'voltage', $device, $volt_oid, $index, $type, $descr, $divisor, '1', $llimit, NULL, NULL, $limit, $current);
  }

  $oids = trim(snmp_walk($device, "upsInputNumLines", "-OsqnU", "NETCOM-UPS-MIB"));
  if ($debug) { echo($oids."\n"); }
  list($unused,$numPhase) = explode(' ',$oids);
  for($i = 1; $i <= $numPhase;$i++)
  {
    $volt_oid = ".1.3.6.1.4.1.13891.101.3.3.1.3.$i";
    $descr    = "Input"; if ($numPhase > 1) $descr .= " Phase $i";
    $type     = "netcom";
    $current  = snmp_get($device, $volt_oid, "-Oqv") / $divisor;
    $limit    = $input_volt * 1.05;
    $llimit   = $input_volt - ($input_volt * 0.05);
    $index    = 100+$i;

    discover_sensor($valid['sensor'], 'voltage', $device, $volt_oid, $index, $type, $descr, $divisor, '1', $llimit, NULL, NULL, $limit, $current);
  }

  $oids = trim(snmp_walk($device, "upsBypassNumLines", "-OsqnU", "NETCOM-UPS-MIB"));
  if ($debug) { echo($oids."\n"); }
  list($unused,$numPhase) = explode(' ',$oids);
  for($i = 1; $i <= $numPhase;$i++)
  {
    $volt_oid = ".1.3.6.1.4.1.13891.101.5.3.1.2.$i";
    $descr    = "Bypass"; if ($numPhase > 1) $descr .= " Phase $i";
    $type     = "netcom";
    $current  = snmp_get($device, $volt_oid, "-Oqv") / $divisor;
    $limit    = $output_volt * 1.05;
    $llimit   = $output_volt - ($output_volt * 0.05);
    $index    = 200+$i;

    discover_sensor($valid['sensor'], 'voltage', $device, $volt_oid, $index, $type, $descr, $divisor, '1', $llimit, NULL, NULL, $limit, $current);
  }
}

?>

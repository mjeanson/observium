<?php

// NETCOM UPS Voltages
if ($device['os'] == 'netcom')
{
  echo("NETCOM ");

  $oids = snmp_walk($device, ".1.3.6.1.4.1.13891.101.2.5", "-Osqn", "NETCOM-UPS-MIB");
  if ($debug) { echo($oids."\n"); }
  $oids = trim($oids);
  foreach (explode("\n", $oids) as $data)
  {
    $data = trim($data);
    if ($data)
    {
      list($oid,$descr) = explode(" ", $data,2);
      $split_oid = explode('.',$oid);
      $volt_id = $split_oid[count($split_oid)-1];
      $volt_oid  = ".1.3.6.1.4.1.13891.101.2.5.$volt_id";
      $divisor = 10;
      $volt = snmp_get($device, $volt_oid, "-O vq") / $divisor;
      $descr = "Battery" . (count(explode("\n",$oids)) == 1 ? '' : ' ' . ($volt_id+1));
      $type = "netcom";
      $index = "1.2.5.".$volt_id;

      discover_sensor($valid['sensor'], 'voltage', $device, $volt_oid, $index, $type, $descr, $divisor, '1', NULL, NULL, NULL, NULL, $volt);
    }
  }

  $oids = trim(snmp_walk($device, ".1.3.6.1.4.1.13891.101.4.3.0", "-OsqnU"));
  if ($debug) { echo($oids."\n"); }
  list($unused,$numPhase) = explode(' ',$oids);
  for($i = 1; $i <= $numPhase;$i++)
  {
    $volt_oid = ".1.3.6.1.4.1.13891.101.4.4.1.2.$i";
    $descr    = "Output"; if ($numPhase > 1) $descr .= " Phase $i";
    $type     = "netcom";
    $divisor  = 1;
    $current  = snmp_get($device, $volt_oid, "-Oqv") / $divisor;
    $index    = $i;

    discover_sensor($valid['sensor'], 'voltage', $device, $volt_oid, $index, $type, $descr, $divisor, '1', NULL, NULL, NULL, NULL, $current);
  }

  $oids = trim(snmp_walk($device, ".1.3.6.1.4.1.13891.101.3.2.0", "-OsqnU"));
  if ($debug) { echo($oids."\n"); }
  list($unused,$numPhase) = explode(' ',$oids);
  for($i = 1; $i <= $numPhase;$i++)
  {
    $volt_oid = ".1.3.6.1.4.1.13891.101.3.3.1.3.$i";
    $descr    = "Input"; if ($numPhase > 1) $descr .= " Phase $i";
    $type     = "netcom";
    $divisor  = 1;
    $current  = snmp_get($device, $volt_oid, "-Oqv") / $divisor;
    $index    = 100+$i;

    discover_sensor($valid['sensor'], 'voltage', $device, $volt_oid, $index, $type, $descr, $divisor, '1', NULL, NULL, NULL, NULL, $current);
  }

  $oids = trim(snmp_walk($device, ".1.3.6.1.4.1.13891.101.5.2.0", "-OsqnU"));
  if ($debug) { echo($oids."\n"); }
  list($unused,$numPhase) = explode(' ',$oids);
  for($i = 1; $i <= $numPhase;$i++)
  {
    $volt_oid = ".1.3.6.1.4.1.13891.101.5.3.1.2.$i";
    $descr    = "Bypass"; if ($numPhase > 1) $descr .= " Phase $i";
    $type     = "netcom";
    $divisor  = 1;
    $current  = snmp_get($device, $volt_oid, "-Oqv") / $divisor;
    $index    = 200+$i;

    discover_sensor($valid['sensor'], 'voltage', $device, $volt_oid, $index, $type, $descr, $divisor, '1', NULL, NULL, NULL, NULL, $current);
  }
}

?>

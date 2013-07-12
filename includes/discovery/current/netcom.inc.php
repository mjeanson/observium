<?php

// NETCOM UPS
if ($device['os'] == 'netcom')
{
  echo("NETCOM ");

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
      $precision = 10;
      $current = snmp_get($device, $current_oid, "-O vq") / $precision;
      $descr = "Battery" . (count(explode("\n",$oids)) == 1 ? '' : ' ' . ($current_id+1));
      $type = "netcom";
      $index = 500+$current_id;

      discover_sensor($valid['sensor'], 'current', $device, $current_oid, $index, $type, $descr, $precision, '1', NULL, NULL, NULL, NULL, $current);
    }
  }

/// FIXME: array-walk upsOutputTable and use those values
  $oids = trim(snmp_walk($device, "upsOutputNumLines", "-OsqnU"));
  if ($debug) { echo($oids."\n"); }
  list($unused,$numPhase) = explode(' ',$oids);
  for($i = 1; $i <= $numPhase;$i++)
  {
    $current_oid   = ".1.3.6.1.4.1.13891.101.4.4.1.3.$i";
    $descr      = "Output"; if ($numPhase > 1) $descr .= " Phase $i";
    $precision  = 10;
    $current    = snmp_get($device, $current_oid, "-Oqv") / $precision;
    $type       = "netcom";
    $index      = $i;

    discover_sensor($valid['sensor'], 'current', $device, $current_oid, $index, $type, $descr, $precision, '1', NULL, NULL, NULL, NULL, $current);
  }

/// FIXME: array-walk upsInputTable and use those values
  $oids = trim(snmp_walk($device, "upsInputNumLines", "-OsqnU"));
  if ($debug) { echo($oids."\n"); }
  list($unused,$numPhase) = explode(' ',$oids);
  for($i = 1; $i <= $numPhase;$i++)
  {
    $current_oid   = ".1.3.6.1.4.1.13891.101.3.3.1.4.$i";
    $descr      = "Input"; if ($numPhase > 1) $descr .= " Phase $i";
    $precision  = 10;
    $current    = snmp_get($device, $current_oid, "-Oqv") / $precision;
    $type       = "netcom";
    $index      = 100+$i;

    discover_sensor($valid['sensor'], 'current', $device, $current_oid, $index, $type, $descr, $precision, '1', NULL, NULL, NULL, NULL, $current);
  }

/// FIXME: array-walk upsBypassTable and use those values
  $oids = trim(snmp_walk($device, "upsBypassNumLines", "-OsqnU"));
  if ($debug) { echo($oids."\n"); }
  list($unused,$numPhase) = explode(' ',$oids);
  for($i = 1; $i <= $numPhase;$i++)
  {
    $current_oid   = ".1.3.6.1.4.1.13891.101.5.3.1.3.$i";
    $descr      = "Bypass"; if ($numPhase > 1) $descr .= " Phase $i";
    $precision  = 10;
    $current    = snmp_get($device, $current_oid, "-Oqv") / $precision;
    $type       = "netcom";
    $index      = 200+$i;

    discover_sensor($valid['sensor'], 'current', $device, $current_oid, $index, $type, $descr, $precision, '1', NULL, NULL, NULL, NULL, $current);
  }
}

?>

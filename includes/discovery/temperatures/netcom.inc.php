<?php
// NETCOM UPS
if ($device['os'] == 'netcom')
{
  echo("NETCOM-UPS-MIB ");

  $oids = snmp_walk($device, "upsBatteryTemperature", "-Osqn", "NETCOM-UPS-MIB");
  if ($debug) { echo($oids."\n"); }
  $oids = trim($oids);
  if ($oids) echo("NETCOM Battery Temperature ");
  foreach (explode("\n", $oids) as $data)
  {
    $data = trim($data);
    if ($data)
    {
      list($oid,$descr) = explode(" ", $data,2);
      $split_oid = explode('.',$oid);
      $temperature_id = $split_oid[count($split_oid)-1];
      $temperature_oid  = ".1.3.6.1.4.1.13891.101.2.7.$temperature_id";
      $temperature = snmp_get($device, $temperature_oid, "-Ovq");
      $descr = "Battery" . (count(explode("\n",$oids)) == 1 ? '' : ' ' . ($temperature_id+1));

      discover_sensor($valid['sensor'], 'temperature', $device, $temperature_oid, $temperature_id, 'netcom', $descr, '1', '1', NULL, NULL, NULL, NULL, $temperature);
    }
  }
}

?>

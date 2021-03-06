<?php

if ($device['os'] == "asyncos")
{
  echo("ASYNCOS-MAIL-MIB ");
  $oids = snmpwalk_cache_oid($device, "fanTable", array(), "ASYNCOS-MAIL-MIB");

  if (is_array($oids))
  {
    foreach ($oids as $index => $entry)
    {
      $entry['descr']   = $entry['fanName'];
      $entry['oid']     = ".1.3.6.1.4.1.15497.1.1.1.10.1.2.".$index;
      $entry['current'] = $entry['fanRPMs'];

      discover_sensor($valid['sensor'], 'fanspeed', $device, $entry['oid'], $index, 'asyncos-fan', $entry['descr'], '1', '1', NULL, NULL, NULL, NULL, $entry['current']);
    }
  }
}

// EOF

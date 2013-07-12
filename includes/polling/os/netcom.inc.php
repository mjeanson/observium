<?php

$version = trim(snmp_get($device, "upsIdentAgentSoftwareVersion.0", "-OQv", "NETCOM-UPS-MIB"),'"');
$hardware = $poll_device['sysDescr'];

?>

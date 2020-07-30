#!/usr/bin/php
<?php

/*
# https://openwrt.org/docs/techref/ubus
*/


$i=0;
$cfg['hosts'][$i]['host']="OpenWRT IP ADDRESS";
$cfg['hosts'][$i]['user']="root";
$cfg['hosts'][$i]['pass']="PASSWORD";


$debug=false;

// --------------------------------------------------------------------------

require_once(dirname(__FILE__)."/src/owa.php");

$host=$cfg['hosts'][0];

$owa=new OpenWrtApi("http://".$host['host'] , $debug);
$owa->UbusLogin($host['user'], $host['pass']);

TestCallUbus('system','board');
TestCallUbus('system','info');

if($devices=TestCallUbus('luci-rpc','getWirelessDevices')){
	foreach($devices as $dev => $info){
		foreach($info['interfaces'] as $interface ){
			$ifname=$interface['ifname'];
			$stations=$owa->CallUbus('iwinfo','assoclist',array('device'=>$ifname));
			foreach($stations['results'] as $s){
				echo "$ifname	{$s['mac']}\n";

			}
		}
	}
}

// -------------------------------------------------------------------
function TestCallUbus($path,$meth, $arg=array()){
	global $owa;
	echo "## $path - $meth ########################################\n";
	$r=$owa->CallUbus($path,$meth, $arg);
	if($r){
		print_r($r);
		return $r;
	}
	else{
		echo " -> ERROR: ";
		print_r($owa->GetErrors());
	}
	echo "\n";
}

?>
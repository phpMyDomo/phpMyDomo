<?php
/*
##################################################################################################
## OpenWRT Preferences  ##########################################################################
##################################################################################################

THIS FEATURE IS CURRENTLY EXPERIMENTAL, NAD SUBJECT TO CHANGE....
PLEASE READ the /phpMyDomo/doc/howto/openwrt.md for more informations (NOT YES AVAILABLE)

## Description  ----------------------------------------------------------------------------------
Print multiple OpenWRT driven routers information on a single page, list all connected staions, resolving their name and IP address...

## Requirements  ----------------------------------------------------------------------------------

*/


// # ROUTERS (running OpenWrt) ####################################################################

/*
$prefs['hosts'] : a list of routeur cerdentials, build like :

$prefs['hosts'][INDEX]['host'];	    // (REQUIRED) IP address or host_name (FQDN)
$prefs['hosts'][INDEX]['user'];	    // (REQUIRED) user name
$prefs['hosts'][INDEX]['pass'];     // (REQUIRED) password
$prefs['hosts'][INDEX]['ssl'];     // (OPTIONNAL) set to 1, to use https:// (instead of http://). Required for OpenWRT > v21.0

Example:

$i=0;
$prefs['hosts'][$i]['host']	='102.168.1.1';		// IP address or host_name (FQDN)
$prefs['hosts'][$i]['user']	='root';			// user name
$prefs['hosts'][$i]['pass']	='MY_PASSWORD';		// password

$i++;
$prefs['hosts'][$i]['host']	='wifi2.local';		// IP address or host_name (FQDN)
$prefs['hosts'][$i]['user']	='root';			// user name
$prefs['hosts'][$i]['pass']	='MY_PASSWORD_2';				// password

$i++;
$prefs['hosts'][$i]['host']	='wifi3.local';		// IP address or host_name (FQDN)
$prefs['hosts'][$i]['user']	='root';			// user name
$prefs['hosts'][$i]['pass']	='MY_PASSWORD_3';	// password
*/

$i=0;
$prefs['hosts'][$i]['host']	='192.168.1.1';		// IP address or host_name (FQDN)
$prefs['hosts'][$i]['user']	='root';			// user name
$prefs['hosts'][$i]['pass']	='openwrt';			// password



// ## PREFERENCES #################################################################################

// Show IP address, Hostanme, Friendly Name (from the file defined in $prefs['mac_file']) ?
$prefs['mac_to_ip']		=true;		

// A file listing all known "Stations" (Wifi Client  Devices), 
// in the format: "MACadddress IPaddress Hostname FriendlyName" (separated by TABS)
$prefs['mac_file']		=dirname(dirname(__FILE__)).'/cache/openwrt_macs.csv';

// Resolve MAC addresses to Manufacturers ?
$prefs['mac_to_vendor']	=true;

// Signal levels thresholds (used in CSS for Wifi strength colors)
$prefs['levels']['good']	= -0;
$prefs['levels']['mid']		= -30;
$prefs['levels']['poor']	= -65;
$prefs['levels']['bad']		= -80;

// Default stations filers 
$prefs['stations_show_ip']			= false;
$prefs['stations_show_stats']		= true;


?>
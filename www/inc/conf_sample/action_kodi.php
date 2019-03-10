<?php
/*

PLEASE READ the /phpMyDomo/www/inc/doc/howto/actions.md for more informations

## Description  ----------------------------------------------------------------------------------
This action sends a command to an Kodi (formely know as XBMC) media-center client.


## Fields  ----------------------------------------------------------------------------------
- 'type'	=> (mandatory) 'kodi'
- 'server' 	=> (required) hostname or ip (with optionnal :port), ie "kodi.local", "192.168.0.12", "kodi.local:8080"
- 'mode' 	=> (required) The command to send. Two modes are supported: 'notify' (display a notification) | 'pause' (pause a playing movies)

Fields needed in 'notify' mode:
- 'title'   => (required) The title of the notification.
- 'message'	=> (required) The content of the notification.
- 'time'	=> (optional) The time in seconds the notification will be visible, minimum 1.5 (else default to 5 seconds)
- 'icon'	=> (optional) The icon to show: "info" | "warning" | "error" | a_custom_url
- 'custom'	=> (optional) replaces "{custom}" in the  message field

Fields required in 'pause' mode:
none!

## URLs examples ----------------------------------------------------------------------------------
	/action?type=kodi&preset=door_ring
	/action?type=kodi&preset=door_ring&custom=Portal
	/action?type=kodi&preset=door_ring&custom=Main+Door&time=10&icon=warning
	/action?type=kodi&mode=pause

*/


// ##############################################################################
// Global Configuration  #######################################################
// ##############################################################################
//$action['globals']['debounce']	='3';	//minimum seconds to wait between identical action calls

$action['globals']['server']	="192.168.1.2:8080"; // change to your kodi Host:port
$action['globals']['mode']		="notify";	// 'notify' | 'pause'
$action['globals']['title']		="phpMyDomo Notification";
$action['globals']['message']	="No Content Set";
$action['globals']['time']		="5";		// in seconds
$action['globals']['icon']		="warning"; //"info" | "warning" | "error" | "http://a_custom_url"


// ##############################################################################
// Presets  #####################################################################
// ##############################################################################

// Make your own preset like this:
//$action['presets']['PRESET_NAME']['FIELD']="your value here";

//examples ---------------------
$action['presets']['door_ring']['mode']		="notify";
$action['presets']['door_ring']['title']	="Ringing at door";
$action['presets']['door_ring']['message']	="Someone is at door: {custom}";

?>
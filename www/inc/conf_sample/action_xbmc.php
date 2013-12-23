<?php
/*

PLEASE READ the /phpMyDomo/doc/howto/actions.md for more informations

## Description  ----------------------------------------------------------------------------------
This action send a command to an XBMC (media-center) client.


## Fields  ----------------------------------------------------------------------------------
- 'type'	=> (mandatory) 'xbmc'
- 'server' 	=> (required) hostname or ip (with optionnal :port), ie "xbmc.local", "192.168.0.12", "xbmc.local:8080"
- 'mode'  	=> (required) The command to send. At this time, 2 are supported: 'notify' (display a notification) | 'pause' (pause a playing movies)

Fields required in 'notify' mode:
- 'title'   => (required) The title of the notification.
- 'message'	=> (required) The content of the notification.
- 'time'	=> (optionnal) The time in seconds the notification will be visible, minimum 1.5 (else default to 5 seconds)
- 'image'	=> (optionnal) The icon to show: "info" | "warning" | "error" | a_custom_url
- 'custom'	=> (optionnal) replaces "{custom}" in the  message field

Fields required in 'pause' mode:
none!


## URLS examples ----------------------------------------------------------------------------------
/action?type=xbmc&preset=door_ring  
/action?type=xbmc&preset=door_ring&custom=Portal  
/action?type=xbmc&preset=door_ring&custom=Main+Door&time=10&image=warning  
/action?type=xbmc&mode=pause  

*/

// ##############################################################################
// Global Configuration  #######################################################
// ##############################################################################

$action['globals']['server']	="192.168.1.2:8080"; // change to your xbmc Host:port
$action['globals']['mode']		="notify";	// 'notify' | 'pause'
$action['globals']['title']		="phpMyDomo Notification";
$action['globals']['message']	="No Content Set";
$action['globals']['time']		="5";		// in seconds
$action['globals']['image']		="warning"; //"info" | "warning" | "error" | "http://a_custom_url"


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
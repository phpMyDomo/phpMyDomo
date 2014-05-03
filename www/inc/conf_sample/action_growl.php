<?php
/*

PLEASE READ the /phpMyDomo/www/inc/doc/howto/actions.md for more informations

## Description  ----------------------------------------------------------------------------------
This action send a Growl notification over the network.

## Requirements  ----------------------------------------------------------------------------------
A Growl client must be installed on each notified workstation:
- OSX		: http://growl.info
- Windows	: http://www.growlforwindows.com
- Linux		: http://mattn.github.io/growl-for-linux/

## Fields  ---------------------------------------------------------------------------------------
- 'type'		=> (mandatory) 'growl'
- 'hosts' 		=> (required) a list of host to notify, separated by comma. ie: "192.168.0.1,192.168.0.2,workstation.local".
- 'protocol'	=> (required) Growl protocol to use : 'udp' | 'gntp' | 'both'
	- 'gntp'	is intended for MacOSX >= 10.7, Windows and Linux It supports custom icons.
	- 'udp'	is intended for MacOSX < 10.7 (with Growl 1.xx). It does not support custom icons.
	- 'both' 	try both protocols for each host. Usefull only if you want to notify some Mac (< 10.7) as well as some PC. But it will be really slower because each host might be called 2 time.
- 'title' 		=> (required) Notification Title
- 'message' 	=> (required) Notification Message
- 'pass' 		=> (optionnal) Password (only  if set in the workstations)
- 'icon' 		=> (optionnal) URL to a custom Icon, ie : http://www.mysite/my_custom_icon.gif. Works ONLY in GNTP mode!
- 'priority' 	=> (optionnal) Notification priority : 'low' | 'moderate' | 'normal' | 'high' | 'emergency'
- 'sticky' 	=> (optionnal) "Sticky" Notification : true | false
- 'custom'	=> (optionnal) replaces "{custom}" in the  message field

## URLS examples ----------------------------------------------------------------------------
/action?type=growl&preset=door_ring  
/action?type=growl&preset=door_ring&custom=Portal  
/action?type=growl&preset=door_ring&priority=emergency  

*/

// ##############################################################################
// Global Configuration  #######################################################
// ##############################################################################
//$action['globals']['debounce']	='3';	//minimum seconds to wait between identical action calls

$action['globals']['hosts']		="192.168.0.1,192.168.0.2"; //put your host(s) here
$action['globals']['protocol']	="gntp";		// 'gntp' | 'udp' | 'both'
$action['globals']['priority']	="normal"; 		//'low' | 'moderate' | 'normal' | 'high' | 'emergency'
$action['globals']['sticky']	=false;			// true | false
//$action['globals']['pass']	="my_password";
//$action['globals']['icon']	="http://www.mysite/my_custom_icon.png";


// ##############################################################################
// Presets  #####################################################################
// ##############################################################################

// Make your own preset like this:
//$action['presets']['PRESET_NAME']['FIELD']="your value here";

//examples ---------------------

$action['presets']['door_ring']['title']	="Someone is ringing at the door";
$action['presets']['door_ring']['message']	="I've just realized that someone is ringing at door: {custom}";

?>
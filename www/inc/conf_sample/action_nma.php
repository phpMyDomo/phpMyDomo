<?php
/*

PLEASE READ the /phpMyDomo/doc/howto/actions.md for more informations

## Description  ----------------------------------------------------------------------------------
This action send a notification to an Android device using the NotifyMyAndroid application

## Requirements  ----------------------------------------------------------------------------------
You must have the NMA application installed on the Android devices, and generate a (free) API keys from:
http://www.notifymyandroid.com

## Fields  ---------------------------------------------------------------------------------------
- 'type'	=> (mandatory) 'nma'
- 'key' 	=> (required) your NMA API key
- 'title' 	=> (required) Notification Title
- 'message' => (required) Notification Content
- 'priority'=> (optionnal) Priority : '-2' (Very Low) | '-1' (Moderate)  | '0' (Normal)  | '1' (High)  | '2' (Emergency)
- 'custom'	=> (optionnal) replaces "{custom}" in the  message field

## Some URLS examples ----------------------------------------------------------------------------
/action?type=nma&preset=door_ring

*/

// ##############################################################################
// Global Configuration  #######################################################
// ##############################################################################
//$action['globals']['debounce']	='3';	//minimum seconds to wait between identical action calls

$action['globals']['key']		="your_api_key";
$action['globals']['title']		="phpMyDomo Notification";
//$action['globals']['message']	="Hello World";
$action['globals']['priority']	="0";

//compte : wxop


// ##############################################################################
// Presets  #####################################################################
// ##############################################################################

// Make your own preset like this:
//$action['presets']['PRESET_NAME']['FIELD']="your value here";

//examples ---------------------
$action['presets']['door_ring']['message']	="Someone rings at the door";

?>
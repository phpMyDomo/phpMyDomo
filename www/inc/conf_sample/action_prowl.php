<?php
/*

PLEASE READ the /phpMyDomo/doc/howto/actions.md for more informations

## Description  ----------------------------------------------------------------------------------
This action send a notification to an IOS device using Prowl

## Requirements  ----------------------------------------------------------------------------------
You must have the Prowl application installed on the IOS devices, and generate a (free) API keys from:
https://www.prowlapp.com/api_settings.php

## Fields  ---------------------------------------------------------------------------------------
- 'type'	=> (mandatory) 'prowl'
- 'key' 	=> (required) your prowl API key
- 'title' 	=> (required) Notification Title
- 'message' => (required) Notification Content
- 'url'		=> (optionnal) an URL,
- 'priority'=> (optionnal) Priority : '-2' (Very Low) | '-1' (Moderate)  | '0' (Normal)  | '1' (High)  | '2' (Emergency)
- 'custom'	=> (optionnal) replaces "{custom}" in the  message field

## Some URLS examples ----------------------------------------------------------------------------
/action?type=prowl&preset=door_ring

*/

// ##############################################################################
// Global Configuration  #######################################################
// ##############################################################################

$action['globals']['key']		="your_api_key";
$action['globals']['title']		="phpMyDomo Notification";
//$action['globals']['message']	="Hello World";
$action['globals']['url']		="http://www.phpmydomo.org";
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
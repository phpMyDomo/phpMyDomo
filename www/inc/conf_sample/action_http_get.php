<?php
/*

PLEASE READ the /phpMyDomo/doc/howto/actions.md for more informations

## Description  ----------------------------------------------------------------------------------
This action fetch an url.

## Fields  ---------------------------------------------------------------------------------------
- 'type'	=> (mandatory) 'http_get'
- 'url' 	=> (required) the url to fetch: ie "http://www.myserver.com/myurl?param=xx"
- 'timeout' => (required) the maximum time to spend asking the remote server
- 'custom'	=> (optionnal) replaces "{custom}" in the  url field

## Some URLS examples ----------------------------------------------------------------------------
/action?type=http_get&preset=domoticz_sensor&custom=12.3;45;2

*/

// ##############################################################################
// Global Configuration  #######################################################
// ##############################################################################

//$action['globals']['url']	="";
$action['globals']['timeout']	=3;


// ##############################################################################
// Presets  #####################################################################
// ##############################################################################

// Make your own preset like this:
//$action['presets']['PRESET_NAME']['FIELD']="your value here";

//examples ---------------------
$url_api_domoticz="http://localhost:8080/json.htm?";

$action['presets']['domoticz_sensor']['url']	=$url_api_domoticz."type=command&param=udevice&idx=10&nvalue=0&svalue={$custom}";

?>
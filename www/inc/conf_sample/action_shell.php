<?php
/*

PLEASE READ the /phpMyDomo/doc/howto/actions.md for more informations
## Description  ----------------------------------------------------------------------------------
This action launch any shell command.

## Requirements  ----------------------------------------------------------------------------------
Your command must be allowed to run under the web server user: ie on debian, the user 'www-data' must be allowed to execute the command

## Fields  ---------------------------------------------------------------------------------------
- 'type'	=> (mandatory) 'shell'
- 'command' => (required) the command to execute: ie "/bin/ls -la /home"

## Some URLS examples ----------------------------------------------------------------------------
/action?type=shell&preset=list_home

*/

// ##############################################################################
// Global Configuration  #######################################################
// ##############################################################################

//$action['globals']['command']	="";


// ##############################################################################
// Presets  #####################################################################
// ##############################################################################

// Make your own preset like this:
//$action['presets']['PRESET_NAME']['FIELD']="your value here";

//examples ---------------------

$action['presets']['list_home']['command']	="/bin/ls -la /home";

?>
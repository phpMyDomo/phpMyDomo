<?php
/*

PLEASE READ the /phpMyDomo/doc/howto/actions.md for more informations

## Description  ----------------------------------------------------------------------------------
This action send a MySensors message to your MySensors Gateway

## Requirements  ----------------------------------------------------------------------------------
You must have a MySensors Gateway installed

## Fields  ---------------------------------------------------------------------------------------
Either send a raw MySensors message ie:
- 'msg'		=> (required)  Mysensors raw message, ie : "1;3;0;4;5"

OR send a message like this :

- 'node' 	=> (required) Node ID
- 'child' 	=> (required) Child ID
- 'type' 	=> (required) message type (presentation|set|req|internal|stream) or corresponding num values (0|1|2|3|4)
- 'sub'		=> (required) message sub-type (use either the numerical sub-type or the 'X_TYPE') depending on the 'type' used (see MySensors documentation)
- 'payload' => the payload to send, when needed. (see MySensors documentation)


## Some URLS examples ----------------------------------------------------------------------------
/action?type=mysensors&msg=12;0;2;1;1
/action?type=mysensors&node=12&child=0&type=2&sub=1&payload=1
/action?type=mysensors&preset=set_relay1

*/

// ##############################################################################
// Global Configuration  #######################################################
// ##############################################################################

$action['globals']['gw_type']	="ethernet";	// gateway type : 'ethernet' or 'serial' (serial gateway must be on the same host as PMD)

//set this for an Ethernet gateway
$action['globals']['eth_ip']	="10.1.7.40";	// gateway IP address
$action['globals']['eth_port']	="5003";		// gateway TCP port (default to 5003)

//set this for a Serial gateway (The Serial gateway client has not been tested, and may not work at all)
//$action['globals']['serial_port']	="COM1";		// gateway Serial port name


// ##############################################################################
// Presets  #####################################################################
// ##############################################################################

// Make your own preset like this:
//$action['presets']['PRESET_NAME']['FIELD']="your value here";

//examples ---------------------
// $action['presets']['set_relay1']['msg']	="12;0;2;1;1";

// or 

// $action['presets']['set_relay2']['node']		=12;
// $action['presets']['set_relay2']['child']	=0;
// $action['presets']['set_relay2']['type']		=2;
// $action['presets']['set_relay2']['sub']		=1;
// $action['presets']['set_relay2']['payload']	=1;

?>
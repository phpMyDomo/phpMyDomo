<?php
/*

PLEASE READ the /phpMyDomo/www/inc/doc/howto/actions.md for more informations

## Description  ------------------------------------------------------------------------------------------------
This action send a command to a Nabaztag using an OJN server

## Requirements  ------------------------------------------------------------------------------------------------
You must have a working OpenJabNab server:  
https://github.com/OpenJabNab/OpenJabNab  
http://openjabnab.fr/ojn_admin/

## Fields  -----------------------------------------------------------------------------------------------------
- 'type'	=> (mandatory) 'nabaztag'
- 'server' 	=> (required) the server were is installed OJN: ie "myserver.com:81/ojn"
- 'mac' 	=> (required) MAC address of your bunny, ie "0019db9e1234"
- 'token' 	=> (required in 'ears' mode) Violet API Token (grab it from the OJN Admin)
- 'mode' 	=> (required) 'tts' (Text To Speech) | 'ears' (Move ears)

- 'text' 	=> (required in 'tts' mode) Text to be spoken by the Nabaztag
- 'voice' 	=> (optionnal in 'tts' mode) voice to use (see the list bellow)

- 'left' 	=> (required in 'ears' mode) position of the left ear (0 to 16)
- 'right' 	=> (required in 'ears' mode) position of the right ear (0 to 16)

- 'timeout' => (optionnal) the maximum time to spend asking the remote server
- 'custom'	=> (optionnal) replaces "{custom}" in the  text field

## Possible voices (Acapella tts mode) -----------------------------------------------------------
- Arabic			: Leila, Mehdi, Nizar, Salma, 
- English (AU)		: Liam, Lisa, Olivia, Tyler, 
- English (India)	: Deepa, 
- English (UK)		: Graham, Harry, Lucy, Nizareng, Peter, PeterHappy, PeterSad, QueenElizabeth, Rachel, Rosie, 
- French (Belgium)	: Justine, 
- French (Canada)	: Louise, 
- French (France)	: Alice, Antoine, AntoineFromAfar, AntoineHappy, AntoineSad, AntoineUpClose, Bruno, Claire, Julie, Manon, Margaux, MargauxHappy, MargauxSad, 
- German			: Andreas, Jonas, Julia, Klaus, Lea, Sarah, 
- Italian			: chiara, fabiana, vittorio, 
- Russian			: Alyona, 
- Spanish (Spain)	: Antonio, Ines, Maria, 
- Spanish (US)		: Rodrigo, Rosa

## URLS examples --------------------------------------------------------------------------------------------
/action?type=nabaztag&preset=door_ring  
/action?type=nabaztag&preset=ears_0  
/action?type=nabaztag&preset=ears_8
/action?type=nabaztag&mode=tts&voice=Lucy&text=Hello
/action?type=nabaztag&mode=ears&left=7

*/


// ##############################################################################
// Global Configuration  #######################################################
// ##############################################################################
//$action['globals']['debounce']	='3';	//minimum seconds to wait between identical action calls

//$action['globals']['url']	="";
$action['globals']['server']	='10.1.10.1';
$action['globals']['mac']		='your_nabaztag_mac';
$action['globals']['token']		='your_violet_api_token';
$action['globals']['mode']		='tts';
//$action['globals']['text']		='hello';


// ##############################################################################
// Presets  #####################################################################
// ##############################################################################

// Make your own preset like this:
//$action['presets']['PRESET_NAME']['FIELD']="your value here";

//examples ---------------------
$action['presets']['door_ring']['text']	="Someone is ringing at the door!";

$action['presets']['ears_0']['mode']	="ears";
$action['presets']['ears_0']['left']	="0";
$action['presets']['ears_0']['right']	="0";

$action['presets']['ears_8']['mode']	="ears";
$action['presets']['ears_8']['left']	="8";
$action['presets']['ears_8']['right']	="8";


?>
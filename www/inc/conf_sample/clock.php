<?php
/*

PLEASE READ the /phpMyDomo/doc/howto/clock.md for more informations

## Description  ----------------------------------------------------------------------------------
Display the clock,  selected sensors in realtime and allow setting an alarm/timer time.

## Requirements  ----------------------------------------------------------------------------------

*/

// ##############################################################################
// Clock Preferences  ##########################################################
// ##############################################################################

$prefs['type']								='analog';	// 'digital' | 'analog' : clock type

// analog clock colors: can be defined as hex, colorstring, or rgb, rgba (https://github.com/thooyork/thooClock)
$prefs['analog']['dialColor']				='#333333';	// dial color
$prefs['analog']['dialBackgroundColor']		='transparent';	// background-color of dial
$prefs['analog']['secondHandColor']			='cyan';	// color of second hand
$prefs['analog']['minuteHandColor']			='cyan';	// color of minute hand
$prefs['analog']['hourHandColor']			='cyan';	// color of hour hand
$prefs['analog']['alarmHandColor']			='#FFFFFF';	// color of alarm hand (alarm hand only visible if alarmTime is set to 'hh:mm')
$prefs['analog']['alarmHandTipColor']		='#026729';	// color of tip of alarm hand


// ##############################################################################
// Sensors Configuration  ########################################################
// ##############################################################################
$prefs['refresh_time']			=30;	//refresh the sensors every x seconds (don't set it too low)

//$prefs['sensors']['1']['name']	="Ext";
//$prefs['sensors']['1']['uid'][]	="sensor_temp_53";
//$prefs['sensors']['1']['uid'][]	="sensor_hygro_53";

//$prefs['sensors']['2']['name']	="Int";
//$prefs['sensors']['2']['uid'][]	="sensor_temp_52";
//$prefs['sensors']['2']['uid'][]	="sensor_hygro_52";

//$prefs['sensors']['3']['name']	="Office";
//$prefs['sensors']['3']['uid'][]	="sensor_temp_58";
//$prefs['sensors']['3']['uid'][]	="sensor_hygro_58";

?>
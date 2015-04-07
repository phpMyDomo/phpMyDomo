<?php
/*

PLEASE READ the /phpMyDomo/doc/howto/clock.md for more informations

## Description  ----------------------------------------------------------------------------------
Display the clock.

## Requirements  ----------------------------------------------------------------------------------

*/

// ##############################################################################
// Clock Preferences  ##########################################################
// ##############################################################################

$clock['type']								='digital';	// 'digital' | 'analog' : clock type

// analog clock colors: can be defined as hex, colorstring, or rgb, rgba (https://github.com/thooyork/thooClock)
$clock['analog']['dialColor']				='#333333';	// dial color
$clock['analog']['dialBackgroundColor']	='transparent';	// background-color of dial
$clock['analog']['secondHandColor']		='cyan';	// color of second hand
$clock['analog']['minuteHandColor']		='cyan';	// color of minute hand
$clock['analog']['hourHandColor']			='cyan';	// color of hour hand
$clock['analog']['alarmHandColor']			='#FFFFFF';	// color of alarm hand (alarm hand only visible if alarmTime is set to 'hh:mm')
$clock['analog']['alarmHandTipColor']		='#026729';	// color of tip of alarm hand


// ##############################################################################
// Calendars Configuration  ########################################################
// ##############################################################################

?>
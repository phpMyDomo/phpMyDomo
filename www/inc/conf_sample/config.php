<?php
//	phpMyDomo main config File
//	http://www.phpmydomo.org


// ##############################################################################
// Minimal Configuration  #######################################################
// ##############################################################################
//api to use : domoticz | domogik | domotiga | openhab
$conf['app']['api']			="openhab";

// Personalize the name of your Home
$conf['app']['home_name']	="Home Sweet Home";

// language file to use : (name of the folder in /www/inc/lang/)
$conf['app']['lang']		="en";	// en | fr | nl | de

//choose a photoframe method : directory | iphoto
$conf['app']['photoframe']		="directory"; 

// Skin : Uncomment to use another skin (defaults to the 'default' skin, if not set)
//$conf['app']['skin']		="black"; // default | black | metal

// SubDirectory: Uncomment ONLY if you want to install phpMydomo in a sub directory of your web server root (not recommended at this time)
//$conf['app']['dir']		="/my_directory";

//Locale: uncomment to override the locale defined in the language file
//$conf['app']['locale']		="en_US.utf8"; 



//	Urls  #####################################################################
// Change these, if your domotic server is NOT on the same server than phpMyDomo, or if you are not using default server ports.

// Remote API Server. ---------------------------------------------------
// Format : http://hostname_or_ip:port

$conf['urls']['server_openhab']	="http://demo.openhab.org:8080";
//$conf['urls']['server_domoticz']	="http://10.1.100.151:8080";
//$conf['urls']['server_domogik']	="http://10.1.100.152:40405";
//$conf['urls']['server_domotiga']	="http://10.1.100.153:9090";
//$conf['urls']['server_pilight']	="http://10.1.100.153:5000";

// Server Website, the normal GUI of your domotic server -----------------
// Format : http://hostname_or_ip:port

$conf['urls']['www_openhab']		="http://demo.openhab.org:8080/openhab.app?sitemap=demo";
//$conf['urls']['www_domoticz']		="http://10.1.100.151:8080";
//$conf['urls']['www_domogik']		="http://10.1.100.152:40404";
//$conf['urls']['www_domotiga']		="http://10.1.100.153:9090";
//$conf['urls']['www_pilight']		="http://10.1.100.153:5000";



// ##############################################################################
// Sensors Types Units ##########################################################
// ##############################################################################
// Personalize your local units, according to your server settings
$conf['units']['temp']			="°C";
$conf['units']['hum']			="%";
$conf['units']['wind']			="m/s";
$conf['units']['rain']			="mm";
$conf['units']['baro']			="hPa";
$conf['units']['hygro']			="%";

$conf['units']['wind_speed']	="m/s";
$conf['units']['wind_gust']		="m/s";

$conf['units']['wind_temp']		="°C";
$conf['units']['wind_chill']	="°C";



// ##############################################################################
// Groups #######################################################################
// ##############################################################################
/*
Define Groups shown on the home page.
Groups are displayed in the order they are created below.

Definitions:
	- GROUP_KEY: a key to identify the group (use a-z 0-1 _ or - chars) , ie "living", "room_1", "external", etc...
	- DEVICE_IDs: Unique Id of the Device (tips: grab it from the phpMyDomo Devices page)
	- GROUP_NAME: Group Name Displayed, ie: "Living Room"
	- 'command' for switches shown in the group
	- 'sensor' for sensors displayed in the Group Title

Example 1:
	$conf['groups_names']['GROUP_KEY']			="GROUP_NAME";
	$conf['groups']['GROUP_KEY']['command'][]	="DEVICE_ID_1";	
	$conf['groups']['GROUP_KEY']['command'][]	="DEVICE_ID_2";
	$conf['groups']['GROUP_KEY']['sensor'][]	="DEVICE_ID_3";
will create a group named "GROUP_NAME" (with ID "GROUP_KEY") displaying switches for DEVICE_ID_1 and DEVICE_ID_2, and showing DEVICE_ID_3 sensor values in the group title.

Example 2: (working with openHab demo API)

	// Groups Names ----------------------------------------------------
	$conf['groups_names']['living']		="Living Room";
	$conf['groups_names']['outside']	="OutDoor";
	$conf['groups_names']['heating']	="Heating";

	// Groups (unique ids) ----------------------------------------------

	$conf['groups']['outside']['command'][]	="command_switch_light_outdoor_terrace";
	$conf['groups']['outside']['command'][]	="command_switch_light_outdoor_frontdoor";
	$conf['groups']['outside']['command'][]	="command_switch_light_outdoor_garage";
	$conf['groups']['outside']['sensor'][]	="sensor_temp_weather_temperature";

	$conf['groups']['living']['command'][]	="scene_group_gf_living";
	$conf['groups']['living']['command'][]	="command_switch_heating_gf_living";
	$conf['groups']['living']['command'][]	="command_shutter_shutter_gf_living";
	$conf['groups']['living']['command'][]	="command_dimmer_light_gf_living_table";
	$conf['groups']['living']['sensor'][]	="sensor_bool_window_gf_living";
	$conf['groups']['living']['sensor'][]	="sensor_temp_temperature_gf_living";

	$conf['groups']['heating']['command'][]	="scene_group_heating";
	$conf['groups']['heating']['command'][]	="command_switch_heating_gf_toilet";
	$conf['groups']['heating']['command'][]	="command_switch_heating_gf_living";
	$conf['groups']['heating']['command'][]	="command_switch_heating_gf_kitchen";
	$conf['groups']['heating']['command'][]	="command_switch_heating_gf_corridor";
	$conf['groups']['heating']['command'][]	="command_switch_heating_ff_office";
	$conf['groups']['heating']['command'][]	="command_switch_heating_ff_child";
	$conf['groups']['heating']['command'][]	="command_switch_heating_ff_bed";
	$conf['groups']['heating']['command'][]	="command_switch_heating_ff_bath";

*/



// ##############################################################################
// Blocks #######################################################################
// ##############################################################################
/*
Blocks are shown in the right column of the HomePage.
They are 2 user defined blocks : "weather" and "sensors".
Example 1:
	$conf['blocks']['weather'][]="DEVICE_ID_4";
	$conf['blocks']['weather'][]="DEVICE_ID_5";

	$conf['blocks']['sensors'][]="DEVICE_ID_7";
	$conf['blocks']['sensors'][]="DEVICE_ID_8";
will show DEVICE_ID_4 and DEVICE_ID_5 in the 'weather' block, and DEVICE_ID_4 and DEVICE_ID_5 in the 'sensors' block

Example 2: (working with openHab demo API)

	// weather Block (unique ids) ---------------------------------------
	$conf['blocks']['weather'][]="sensor_temp_weather_temperature";

	// Sensor Block (unique ids) ---------------------------------------
	$conf['blocks']['sensors'][]="sensor_temp_temperature_setpoint";
	$conf['blocks']['sensors'][]="sensor_temp_temperature_gf_living";
	$conf['blocks']['sensors'][]="sensor_temp_temperature_gf_toilet";
	$conf['blocks']['sensors'][]="sensor_temp_temperature_gf_corridor";
	$conf['blocks']['sensors'][]="sensor_temp_temperature_ff_office";
	$conf['blocks']['sensors'][]="sensor_temp_temperature_ff_child";
	$conf['blocks']['sensors'][]="sensor_temp_temperature_ff_bed";
	$conf['blocks']['sensors'][]="sensor_temp_temperature_ff_bath";
*/



// ##############################################################################
// Cameras (if not already defined in the server devices) #######################
// ##############################################################################
/*
Define IP camera streams to be displayed in the Camera Page. if cameras_sizes is not defined, it defaults to 320x240
Example:
$conf['cameras_names']['room1']	="Room 1";
$conf['cameras_urls']['room1']	="http://user:pass@10.0.0.1/videostream.cgi";
$conf['cameras_sizes']['room1']	="640x480";

$conf['cameras_names']['room2']	="Room 2";
$conf['cameras_urls']['room2']	="http://user:pass@10.0.0.2/videostream.cgi";

*/



// ##############################################################################
// Menus To Show ################################################################
// ##############################################################################
/*
Defines menus displayed on the header or footer of the page.
If not set, all menus are shown.
*/
$conf['menu_head']=array('home','cameras','photos','devices');
//$conf['menu_foot']=array('home','cameras','photos','commands','sensors','devices');




// ##############################################################################
// DEBUG : you don't need to change this #########################################
// ##############################################################################
$conf['debug']['allow']=1;					//allow debug from url (add ?debug in the url)
$conf['debug']['force']=0;					//always show debug
$conf['debug']['level']=E_ALL ^E_NOTICE;	//php error level reporting

?>
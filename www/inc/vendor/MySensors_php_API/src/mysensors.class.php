<?
/*
	MySensors PHP API : Php interface to Mysensors Gateway
	https://github.com/soif/MySensors_Php_API
	http://www.mysensors.org
    ----------------------------------------------
	Copyright (C) 2016  Francois Dechery

	LICENCE: ###########################################################
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>
	#####################################################################
*/



// #######################################################################################
class MySensors{

	// http://www.mysensors.org/download/serial_api_15
	// http://ci.mysensors.org/job/MySensorsArduino/branch/development/Doxygen_HTML/group__MyMessagegrp.html
	// https://github.com/mysensors/Arduino/blob/development/libraries/MySensors/core/MyMessage.h
	
	protected $message_types=array(
		'presentation'	=> 0,	// Sent by a node when they present attached sensors. This is usually done in setup() at startup.
		'set'			=> 1,	// This message is sent from or to a sensor when a sensor value should be updated
		'req'			=> 2,	// Requests a variable value (usually from an actuator destined for controller).
		'internal'		=> 3,	// This is a special internal message. See table below for the details
		'stream'		=> 4	// Used for OTA firmware updates
	);

	protected $presentation_types=array(
		'S_DOOR'			=> 0,	// Door and window sensors (V_TRIPPED, V_ARMED)
		'S_MOTION'			=> 1,	// Motion sensors (V_TRIPPED, V_ARMED)
		'S_SMOKE'			=> 2,	// Smoke sensor (V_TRIPPED, V_ARMED)
		'S_LIGHT'			=> 3,	// Light Actuator (on/off) (V_STATUS (or V_LIGHT), V_WATT)
		'S_BINARY'			=> 3,	// Binary device (on/off), Alias for S_LIGHT  (V_STATUS (or V_LIGHT), V_WATT)
		'S_DIMMER'			=> 4,	// Dimmable device of some kind (V_STATUS (on/off), V_DIMMER (dimmer level 0-100), V_WATT)
		'S_COVER'			=> 5,	// Window covers or shades (V_UP, V_DOWN, V_STOP, V_PERCENTAGE)
		'S_TEMP'			=> 6,	// Temperature sensor (V_TEMP, V_ID)
		'S_HUM'				=> 7,	// Humidity sensor (V_HUM)
		'S_BARO'			=> 8,	// Barometer sensor (Pressure) (V_PRESSURE, V_FORECAST)
		'S_WIND'			=> 9,	// Wind sensor (V_WIND, V_GUST)
		'S_RAIN'			=> 10,	// Rain sensor (V_RAIN, V_RAINRATE)
		'S_UV'				=> 11,	// UV sensor (V_UV)
		'S_WEIGHT'			=> 12,	// Weight sensor for scales etc. (V_WEIGHT, V_IMPEDANCE)
		'S_POWER'			=> 13,	// Power measuring device, like power meters (V_WATT, V_KWH)
		'S_HEATER'			=> 14,	// Heater device (V_HVAC_SETPOINT_HEAT, V_HVAC_FLOW_STATE, V_TEMP)
		'S_DISTANCE'		=> 15,	// Distance sensor (V_DISTANCE, V_UNIT_PREFIX)
		'S_LIGHT_LEVEL'		=> 16,	// Light sensor (V_LIGHT_LEVEL (uncalibrated percentage), V_LEVEL (light level in lux))
		'S_ARDUINO_NODE'	=> 17,	// Arduino node device ( )
		'S_ARDUINO_REPEATER_NODE'	=> 18,	// Arduino repeating node device ( )
		'S_LOCK'			=> 19,	// Lock device (V_LOCK_STATUS)
		'S_IR'				=> 20,	// Ir sender/receiver device (V_IR_SEND, V_IR_RECEIVE)
		'S_WATER'			=> 21,	// Water meter (V_FLOW, V_VOLUME)
		'S_AIR_QUALITY'		=> 22,	// Air quality sensor e.g. MQ-2 (V_LEVEL, V_UNIT_PREFIX)
		'S_CUSTOM'			=> 23,	// Use this for custom sensors where no other fits. ( )
		'S_DUST'			=> 24,	// Dust level sensor (V_LEVEL, V_UNIT_PREFIX)
		'S_SCENE_CONTROLLER'=> 25,	// Scene controller device (V_SCENE_ON, V_SCENE_OFF)
		'S_RGB_LIGHT'		=> 26,	// RGB light (V_RGB, V_WATT)
		'S_RGBW_LIGHT'		=> 27,	// RGBW light (with separate white component) (V_RGBW, V_WATT)
		'S_COLOR_SENSOR'	=> 28,	// Color sensor (V_RGB)
		'S_HVAC'			=> 29,	// Thermostat/HVAC device (V_HVAC_SETPOINT_HEAT, V_HVAC_SETPOINT_COLD, V_HVAC_FLOW_STATE, V_HVAC_FLOW_MODE, V_HVAC_SPEED)
		'S_MULTIMETER'		=> 30,	// Multimeter device (V_VOLTAGE, V_CURRENT, V_IMPEDANCE)
		'S_SPRINKLER'		=> 31,	// Sprinkler device (V_STATUS (turn on/off), V_TRIPPED (if fire detecting device))
		'S_WATER_LEAK'		=> 32,	// Water leak sensor (V_TRIPPED, V_ARMED)
		'S_SOUND'			=> 33,	// Sound sensor (V_LEVEL (in dB), V_TRIPPED, V_ARMED)
		'S_VIBRATION'		=> 34,	// Vibration sensor (V_LEVEL (vibration in Hz), V_TRIPPED, V_ARMED)
		'S_MOISTURE'		=> 35,	// Moisture sensor (V_LEVEL (water content or moisture in percentage?), V_TRIPPED, V_ARMED),
		'S_INFO'			=> 36,	// LCD text device / Simple information device on controller, V_TEXT
		'S_GAS'				=> 37,	// Gas meter, V_FLOW, V_VOLUME
		'S_GPS'				=> 38,	// GPS Sensor, V_POSITION
		'S_WATER_QUALITY'	=> 39,	// V_TEMP, V_PH, V_ORP, V_EC, V_STATUS 
	);

	protected $setreq_types=array(
		'V_TEMP'		=> 0,	// Temperature  (S_TEMP, S_HEATER, S_HVAC)
		'V_HUM'			=> 1,	// Humidity (S_HUM)
		'V_STATUS'		=> 2,	// Binary status. 0=off 1=on (S_LIGHT, S_DIMMER, S_SPRINKLER, S_HVAC, S_HEATER)
		'V_LIGHT'		=> 2,	// Deprecated. Alias for V_STATUS. Light status. 0=off 1=on (S_LIGHT, S_DIMMER, S_SPRINKLER)
		'V_PERCENTAGE'	=> 3,	// Percentage value. 0-100 (%) (S_DIMMER)
		'V_DIMMER'		=> 3,	// Deprecated. Alias for V_PERCENTAGE. Dimmer value. 0-100 (%) (S_DIMMER)
		'V_PRESSURE'	=> 4,	// Atmospheric Pressure (S_BARO)
		'V_FORECAST'	=> 5,	// Whether forecast. One of "stable", "sunny", "cloudy", "unstable", "thunderstorm" or "unknown" (S_BARO)
		'V_RAIN'		=> 6,	// Amount of rain (S_RAIN)
		'V_RAINRATE'	=> 7,	// Rate of rain (S_RAIN)
		'V_WIND'		=> 8,	// Windspeed (S_WIND)
		'V_GUST'		=> 9,	// Gust (S_WIND)
		'V_DIRECTION'	=> 10,	// Wind direction (S_WIND)
		'V_UV'			=> 11,	// UV light level (S_UV)
		'V_WEIGHT'		=> 12,	// Weight (for scales etc) (S_WEIGHT)
		'V_DISTANCE'	=> 13,	// Distance (S_DISTANCE)
		'V_IMPEDANCE'	=> 14,	// Impedance value (S_MULTIMETER, S_WEIGHT)
		'V_ARMED'		=> 15,	// Armed status of a security sensor. 1=Armed, 0=Bypassed (S_DOOR, S_MOTION, S_SMOKE, S_SPRINKLER, S_WATER_LEAK, S_SOUND, S_VIBRATION, S_MOISTURE)
		'V_TRIPPED'		=> 16,	// Tripped status of a security sensor. 1=Tripped, 0=Untripped (S_DOOR, S_MOTION, S_SMOKE, S_SPRINKLER, S_WATER_LEAK, S_SOUND, S_VIBRATION, S_MOISTURE)
		'V_WATT'		=> 17,	// Watt value for power meters (S_POWER, S_LIGHT, S_DIMMER, S_RGB, S_RGBW)
		'V_KWH'			=> 18,	// Accumulated number of KWH for a power meter (S_POWER)
		'V_SCENE_ON'	=> 19,	// Turn on a scene (S_SCENE_CONTROLLER)
		'V_SCENE_OFF'	=> 20,	// Turn of a scene (S_SCENE_CONTROLLER)
		'V_HVAC_FLOW_STATE'	=> 21,	// Mode of header. One of "Off", "HeatOn", "CoolOn", or "AutoChangeOver" (S_HVAC, S_HEATER)
		'V_HVAC_SPEED'	=> 22,	// HVAC/Heater fan speed ("Min", "Normal", "Max", "Auto") (S_HVAC, S_HEATER)
		'V_LIGHT_LEVEL'	=> 23,	// Uncalibrated light level. 0-100%. Use V_LEVEL for light level in lux. (S_LIGHT_LEVEL)
		'V_VAR1'		=> 24,	// Custom value (Any device)
		'V_VAR2'		=> 25,	// Custom value (Any device)
		'V_VAR3'		=> 26,	// Custom value (Any device)
		'V_VAR4'		=> 27,	// Custom value (Any device)
		'V_VAR5'		=> 28,	// Custom value (Any device)
		'V_UP'			=> 29,	// Window covering. Up. (S_COVER)
		'V_DOWN'		=> 30,	// Window covering. Down. (S_COVER)
		'V_STOP'		=> 31,	// Window covering. Stop. (S_COVER)
		'V_IR_SEND'		=> 32,	// Send out an IR-command (S_IR)
		'V_IR_RECEIVE'	=> 33,	// This message contains a received IR-command (S_IR)
		'V_FLOW'		=> 34,	// Flow of water (in meter) (S_WATER)
		'V_VOLUME'		=> 35,	// Water volume (S_WATER)
		'V_LOCK_STATUS'	=> 36,	// Set or get lock status. 1=Locked, 0=Unlocked (S_LOCK)
		'V_LEVEL'		=> 37,	// Used for sending level-value (S_DUST, S_AIR_QUALITY, S_SOUND (dB), S_VIBRATION (hz), S_LIGHT_LEVEL (lux))
		'V_VOLTAGE'		=> 38,	// Voltage level (S_MULTIMETER)
		'V_CURRENT'		=> 39,	// Current level (S_MULTIMETER)
		'V_RGB'			=> 40,	// RGB value transmitted as ASCII hex string (I.e "ff0000" for red) (S_RGB_LIGHT, S_COLOR_SENSOR)
		'V_RGBW'		=> 41,	// RGBW value transmitted as ASCII hex string (I.e "ff0000ff" for red + full white) (S_RGBW_LIGHT)
		'V_ID'			=> 42,	// Optional unique sensor id (e.g. OneWire DS1820b ids) (S_TEMP)
		'V_UNIT_PREFIX'	=> 43,	// Allows sensors to send in a string representing the unit prefix to be displayed in GUI. This is not parsed by controller! E.g. cm, m, km, inch. (S_DISTANCE, S_DUST, S_AIR_QUALITY)
		'V_HVAC_SETPOINT_COOL'	=> 44,	// HVAC cold setpoint (S_HVAC)
		'V_HVAC_SETPOINT_HEAT'	=> 45,	// HVAC/Heater setpoint (S_HVAC, S_HEATER)
		'V_HVAC_FLOW_MODE'		=> 46,	// Flow mode for HVAC ("Auto", "ContinuousOn", "PeriodicOn") (S_HVAC)

		'V_TEXT'		=> 47,	// S_INFO. Text message to display on LCD or controller device
		'V_CUSTOM'		=> 48,	// Custom messages used for controller/inter node specific commands, preferably using S_CUSTOM device type.
		'V_POSITION'	=> 49,	// GPS position and altitude. Payload: latitude;longitude;altitude(m). E.g. "55.722526;13.017972;18"
		'V_IR_RECORD'	=> 50,	// Record IR codes S_IR for playback
		'V_PH'			=> 51,	// S_WATER_QUALITY, water PH
		'V_ORP'			=> 52,	// S_WATER_QUALITY, water ORP : redox potential in mV
		'V_EC'			=> 53,	// S_WATER_QUALITY, water electric conductivity μS/cm (microSiemens/cm)
	);

	protected $internal_types=array(
		'I_BATTERY_LEVEL'		=> 0,	// Use this to report the battery level (in percent 0-100).
		'I_TIME'				=> 1,	// Sensors can request the current time from the Controller using this message. The time will be reported as the seconds since 1970
		'I_VERSION'				=> 2,	// Used to request gateway version from controller.
		'I_ID_REQUEST'			=> 3,	// Use this to request a unique node id from the controller.
		'I_ID_RESPONSE'			=> 4,	// Id response back to sensor. Payload contains sensor id.
		'I_INCLUSION_MODE'		=> 5,	// Start/stop inclusion mode of the Controller (1=start, 0=stop).
		'I_CONFIG'				=> 6,	// Config request from node. Reply with (M)etric or (I)mperal back to sensor.
		'I_FIND_PARENT'			=> 7,	// When a sensor starts up, it broadcast a search request to all neighbor nodes. They reply with a I_FIND_PARENT_RESPONSE.
		'I_FIND_PARENT_RESPONSE'=> 8,	// Reply message type to I_FIND_PARENT request.
		'I_LOG_MESSAGE'			=> 9,	// Sent by the gateway to the Controller to trace-log a message
		'I_CHILDREN'			=> 10,	// A message that can be used to transfer child sensors (from EEPROM routing table) of a repeating node.
		'I_SKETCH_NAME'			=> 11,	// Optional sketch name that can be used to identify sensor in the Controller GUI
		'I_SKETCH_VERSION'		=> 12,	// Optional sketch version that can be reported to keep track of the version of sensor in the Controller GUI.
		'I_REBOOT'				=> 13,	// Used by OTA firmware updates. Request for node to reboot.
		'I_GATEWAY_READY'		=> 14,	// Send by gateway to controller when startup is complete.
		'I_REQUEST_SIGNING'		=> 15,	// Used between sensors when initialting signing.
		'I_GET_NONCE'			=> 16,	// Used between sensors when requesting nonce.
		'I_GET_NONCE_RESPONSE'	=> 17,	// Used between sensors for nonce response.

		'I_SIGNING_PRESENTATION'=> 15,	// same as I_REQUEST_SIGNING ?	//!< Provides signing related preferences (first byte is preference version)
		'I_NONCE_REQUEST'		=> 16,	// same as I_GET_NONCE ?	//!< Request for a nonce
		'I_NONCE_RESPONSE'		=> 17,	// same as I_GET_NONCE_RESPONSE ?	// //!< Payload is nonce data

		'I_HEARTBEAT'			=> 18,	//
		'I_PRESENTATION'		=> 19,	//
		'I_DISCOVER'			=> 20,	//
		'I_DISCOVER_RESPONSE'	=> 21,	//
		'I_HEARTBEAT_RESPONSE'	=> 22,	//
		'I_LOCKED'				=> 23,	//!< Node is locked (reason in string-payload)
	);

	protected $stream_types=array(
		'ST_FIRMWARE_CONFIG_REQUEST'	=> 0,	//
		'ST_FIRMWARE_CONFIG_RESPONSE'	=> 1,	//
		'ST_FIRMWARE_REQUEST'			=> 2,	//
		'ST_FIRMWARE_RESPONSE'			=> 3,	//
		'ST_SOUND'						=> 4,	//
		'ST_IMAGE'						=> 5,	//
	);
	
	protected $types =array();


	// ---------------------------------------------------------
	function __construct(){
		$this->types=array(
			0	=> $this->presentation_types,
			1	=> $this->setreq_types,
			2	=> $this->setreq_types,
			3	=> $this->internal_types,
			4	=> $this->stream_types
		);
	}

	// ---------------------------------------------------------
	public function getMessageTypes(){
		return $this->message_types;
	}

	// ---------------------------------------------------------
	public function getSubTypes(){
		return $this->types;
	}

}



// #######################################################################################
class MySensorSend extends MySensors{

	protected $last_message 		=array();
	protected $last_answer			=array();
	protected $last_raw_message 	='';
	protected $last_raw_answer	='';

	// ---------------------------------------------------------
	function __construct(){
		parent::__construct();
	}

	// ---------------------------------------------------------
	public function sendMessage($node_id, $child_id, $type, $ack, $sub_type, $payload, $return_answer=false){
		$type		=$this->_convertType($type);
		$sub_type	=$this->_convertSubType($type, $sub_type);
		if($type !== false && $sub_type !== false ){
			$message=$this->_message2array($node_id, $child_id, $type, $ack, $sub_type, $payload);
			return $this->_transmit($message,$return_answer);
		}		
	}

	// ---------------------------------------------------------
	public function presentation($node_id,$child_id,$sub_type,$ack=false){
		$type=$this->message_types['presentation'];
		//return $this->sendMessage($node_id, $child_id, $type, $ack, $sub_type, $payload, $return_answer);
		return $this->sendMessage($node_id, $child_id, $type, $ack, $sub_type, '');
	}
	
	// ---------------------------------------------------------
	public function set($node_id,$child_id,$sub_type,$payload,$ack=false){
		$type=$this->message_types['set'];
		return $this->sendMessage($node_id, $child_id, $type, $ack, $sub_type, $payload);
	}

	// ---------------------------------------------------------
	public function req($node_id,$child_id,$sub_type,$ack=false){
		$type=$this->message_types['req'];
		return $this->sendMessage($node_id, $child_id, $type, $ack, $sub_type, '', true);
	}

	// ---------------------------------------------------------
	public function internal($node_id,$child_id,$sub_type,$ack=false,$return_answer=false){
		$type=$this->message_types['internal'];
		return $this->sendMessage($node_id, $child_id, $type, $ack, $sub_type, '',$return_answer);
	}

/*
	// ---------------------------------------------------------
	public function stream($node_id,$child_id,$sub_type,$ack=false){
		$type=$this->message_types['stream'];
		return $this->sendMessage($node_id, $child_id, $type, $ack, $sub_type, ???'', ?);
	}
*/

	// ---------------------------------------------------------
	public function encodeMessage($node_id, $child_id, $type, $ack, $sub_type, $payload=''){
		$message	= $this->_message2array($node_id, $child_id, $type, $ack, $sub_type, $payload);
		return $this->_buildRawMessage($message);
	}

	// ---------------------------------------------------------
	public function decodeMessage($raw_message){
		$message = $this->_parseMessage($raw_message);
		return $message;
	}

	// ---------------------------------------------------------
	protected function _buildRawMessage($message){		
		$raw  ='';
		$raw .=$message['node']	. ';';
		$raw .=$message['child']	. ';';
		$raw .=$message['type']	. ';';
		$raw .=$message['ack']	. ';';
		$raw .=$message['sub']	. ';';
		$raw .=$message['payload'];
		return $raw;
	}

	// ---------------------------------------------------------
	protected function _message2array($node_id, $child_id, $type, $ack, $sub_type, $payload=''){
		$out['node']		=$node_id;
		$out['child']		=$child_id;
		$out['type']		=$type;
		$out['ack']			=$ack ? 1 : 0;;
		$out['sub']			=$sub_type;
		$out['payload']		=$payload;
		return $out;
	}

	// ---------------------------------------------------------
	public function getRawMessage(){
		return $this->last_raw_message;	
	}

	// ---------------------------------------------------------
	public function getRawAnswer(){
		return $this->last_raw_answer;
	}

	// ---------------------------------------------------------
	protected function _convertSubType($type, $sub_type){
		if(!is_numeric($sub_type)){
			$sub_type=$this->types[$type][$sub_type];
			if($sub_type == ''){return false;}
		}
		return $sub_type;
	}

	// ---------------------------------------------------------
	protected function _convertType($type){
		if(!is_numeric($type)){
			$type=$this->message_types[$type];
			if($type == ''){return false;}
		}
		return $type;
	}

	// ---------------------------------------------------------
	protected function _parseMessage($line){
		$line=trim($line);
		list($l['node'],$l['child'],$l['type'],$l['ack'],$l['sub'],$l['payload'])=explode(";",$line);
		return $l;
	}

	// ---------------------------------------------------------
	protected function _filterAnswer($line){
		$a=$this->_parseMessage($line);
		if(	$a['node']	==$this->last_message['node'] and 
			($a['child']==$this->last_message['child'] or ($a['node']==0 and $a['child']==255)  ) and
			$a['type']	==$this->last_message['type'] and 
			$a['sub']	==$this->last_message['sub']
		){
			return $a;
		}
	}

	// ---------------------------------------------------------
	protected function _transmit($message,$fetch_answer=false){
		trigger_error("No '_transmit' method available. You must use MySensorSendEthernet or MySensorSendSerial.",E_USER_ERROR);
	}

}



// #######################################################################################
class MySensorSendEthernet extends MySensorSend{

	protected $socket_timeout_message	=2;
	protected $socket_timeout_answer	=4;

	protected $gateway_ip	='';
	protected $gateway_port=5003;

	// ---------------------------------------------------------
	function __construct($gw_ip,$gw_port=""){
		parent::__construct();
		$this->gateway_ip = $gw_ip;
		$gw_port and $this->gateway_port=$gw_port;
	}

	// ---------------------------------------------------------
	protected function _transmit($message,$fetch_answer=false){
		$this->last_message		=$message;
		$this->last_raw_message	=$this->_buildRawMessage($this->last_message);
		$this->last_answer		='';
		$this->last_raw_answer	='';
		
		if($fetch_answer){
			$timeout=$this->socket_timeout_answer;
		}
		else{
			$timeout=$this->socket_timeout_message;			
		}

		$socket = @fsockopen($this->gateway_ip, $this->gateway_port, $errno, $errstr, $timeout);
		if(!$socket){
			return false;
		}

		if(!fputs($socket, $this->last_raw_message."\n")){
			@fclose($socket);
			return false;
		}

		if($fetch_answer){
			$line='';
			$until_t = time() + $timeout;
			while(true){
				if(!$socket or time() > $until_t){
					break;
				}
			
		        $char =fread($socket, 1);
		        $line .= $char;
		        if($char=="\n"){
					if($this->last_answer 		= $this->_filterAnswer($line)){
						$this->last_raw_answer	= trim($line);
						$out = $this->last_answer['payload'];
						break;
					}
					$line='';
				}
		    }
			
		}
		else{
			$out=true;
		}

		@fclose($socket);
		return $out;
	}
}



// #######################################################################################
/*
	This class is absolutely NOT tested. This is just a skeleton, for someone willing to implement it right!
	BTW this require the PhpSerial.php class from https://github.com/Xowap/PHP-Serial/

	if you find some bugs in the PhpSerial class, please also send a PR to the original author to submit your fixes, 
	rather than creating a detached fork of his class in this project.
	
*/
class MySensorSendSerial extends MySensorSend{
	
	protected $socket_timeout_message	=2;
	protected $socket_timeout_answer	=4;

	protected $serial_port;

	//protected $arduino_boot_time; 

	// ---------------------------------------------------------
	function __construct($port="/dev/ttyS0"){
		parent::__construct();
		$this->serial_port = $port;
		
		require(dirname(__FILE__).'/PhpSerial.php');
	}
	
	// ---------------------------------------------------------
	protected function _transmit($message,$fetch_answer=false){
		$this->last_message		=$message;
		$this->last_raw_message	=$this->_buildRawMessage($this->last_message);
		$this->last_answer		='';
		$this->last_raw_answer	='';


		// start the serial connection
		$serial =$serial = new PhpSerial;
		
		// First we must specify the device. This works on both linux and windows (if
		// your linux serial device is /dev/ttyS0 for COM1, etc)
		$serial->deviceSet($this->serial_port);

		// We can change the baud rate, parity, length, stop bits, flow control
		// http://forum.mysensors.org/topic/340/pidome-domotica-home-automation
		$serial->confBaudRate(115000);
		$serial->confParity("none");
		$serial->confCharacterLength(8);
		$serial->confStopBits(1);
		$serial->confFlowControl("none"); // ?

		// Then we need to open it
		$serial->deviceOpen();
		
		// we may have to sleep before arduino restart ?
		// http://stackoverflow.com/questions/13114275/php-serial-port-data-return-from-arduino
		//sleep($this->arduino_boot_time);

		// We may need to return if nothing happens for $timeout seconds
		if($fetch_answer){
			$timeout=$this->socket_timeout_answer;
		}
		else{
			$timeout=$this->socket_timeout_message;			
		}
		
		stream_set_timeout($serial->_dHandle, $timeout);
		
		// send message
		$serial->sendMessage($this->last_raw_message."\n");
		
		if($fetch_answer){
			$line='';
			$until_t = time() + $timeout;
			while(true){
				if(!$serial->_dHandle or time() > $until_t){
					break;
				}
			
		        $line = $serial->readPort();
				if($this->last_answer 		= $this->_filterAnswer($line)){
					$this->last_raw_answer	= trim($line);
					$out = $this->last_answer['payload'];
					break;
				}
		    }
		}
		else{
			$out=true;
		}

		$serial->deviceClose();
		return $out;
	}
}






/*

// #######################################################################################
class MySensorReceive  extends MySensors{	
	// Somebody willing to implement it ?
}

*/


?>
<?php
/*
	phpMyDomo : Home Automation Web Interface
	http://www.phpmydomo.org
    ----------------------------------------------
	Copyright (C) 2013  Francois Dechery

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
/* 
#########################################################################################
Domotiga API ############################################################################
#########################################################################################
Tested on v1.0.013
*/

class PMD_ApiClient extends PMD_Root_ApiClient{


	//----------------------------------------------------------------------------------
	function ApiListInfos(){
		if($this->ApiFetch('get','astro')){
			$info = $this->api_response;
			$this->infos['sunset_time']		=strtotime(date('m/d/Y ').$info['sunset']);
			$this->infos['sunrise_time']	=strtotime(date('m/d/Y ').$info['sunrise']);
			//$this->infos['server_time']		=strtotime($info['ServerTime']);
		}
		//$this->Debug('Infos',$this->infos);
	}


	//----------------------------------------------------------------------------------
	function ApiListDevices(){

		if($this->ApiFetch('list','device_enabled')){
			$devices = $this->GetResult();
			$i=0;
			foreach($devices as $d){
				$raw=$d['raw'];
				$d['raw_value1']=$raw['values'][0]['value'];
				if($raw['switchable']=='1'){
					$d['class']	="command";
					$d['type']	="switch";
				}
				if($raw['dimable']=='1'){
					$d['class']	="command";
					$d['type']	="dimmer";
					$d=$this->FormatState($d);
					$d['state'] or $d['value']	=$raw['value1'];
				}
				if(!$d['class']){
					$d =$this->_guessFromIcon($d);
				}

				$this->RegisterDevice($d);					
				
			}
			//$this->Debug('JSON',$this->api_response);
			//$this->Debug('Devices',$this->devices);
		}
		else{
			if($this->debug){
				$this->o_kernel->PageError('500',"Failed to contact server at {$this->api_url} ");
			}
		}
	}

	//----------------------------------------------------------------------------------
	
	//----------------------------------------------------------------------------------
	private function _guessFromIcon($d){
		$raw=$d['raw'];

		$d['class']		='undef';
		$d['type']		='undef';

		$names=array();
		$names['^sun\.']	=array('sensor','uv',1);
		$names['^motion']	=array('sensor','pir');
		$names['^smoke']	=array('sensor','gas',1);
		$names['^ups']		=array('sensor','bool');
		$names['^temp']		=array('sensor','temp',1);
		$names['^energy']	=array('sensor','consum',1);
		$names['^mail']		=array('sensor','bool');
		$names['^door']		=array('security','door');
		foreach($names as $reg => $class_type_values){
			if(preg_match("#{$reg}#",$raw['icon'])){
				$d['class']	=$class_type_values[0];
				$d['type']	=$class_type_values[1];
				$class_type_values[2] and $d['value']	=$d['raw_value1'];
				if($d['type']=='temp' and $raw['label2']=='%'){
					$d2=$d;
					$d2['type']	='hygro';
					$d2['value']=$d['raw_value2'];
					$this->RegisterDevice($d2,'hum');	
				}
			}
		}
		return $d;
	}

} 
?>
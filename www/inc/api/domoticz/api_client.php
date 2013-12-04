<?php
/*
	phpMyDomo : Home Automation Web Interface
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

class PMD_ApiClient extends PMD_Root_ApiClient{

/* 
Domoticz API

*/


	//----------------------------------------------------------------------------------
	function ApiListInfos(){
		if($this->ApiFetch('list','info')){
			$info = $this->api_response;
			$this->infos['sunset_time']		=strtotime(date('m/d/Y ').$info['Sunset']);
			$this->infos['sunrise_time']	=strtotime(date('m/d/Y ').$info['Sunrise']);
			$this->infos['server_time']		=strtotime($info['ServerTime']);
		}
		//$this->Debug('Info',$this->infos);
	}


	//----------------------------------------------------------------------------------
	function ApiListDevices(){
		if($this->ApiFetch('list','device')){
			$devices = $this->GetResult();
			$i=0;
			foreach($devices as $d){
				$raw=$d['raw'];
				if($raw['Type']=='Group'){
					$this->RegisterDevice('scene','group',	$d,	'Status');
				}
				elseif($raw['Type']=='Scene'){
					$this->RegisterDevice('scene','scene',	$d,	'Status');
				}
				elseif($raw['Type']=='Temp + Humidity + Baro'){
					$this->RegisterDevice('sensor','temp',	$d,	'Temp');
					$this->RegisterDevice('sensor','hum',	$d,	'Humidity');
					$this->RegisterDevice('sensor','baro',	$d,	'Barometer');
				}
				elseif($raw['Type']=='Wind'){
					$this->RegisterDevice('sensor','wind_temp',		$d,	'Temp');
					$this->RegisterDevice('sensor','wind_chill',	$d,	'Chill');
					$this->RegisterDevice('sensor','wind_speed',	$d,	'Speed');
					$this->RegisterDevice('sensor','wind_gust',		$d,	'Gust');
				}
				elseif($raw['Type']=='Rain'){
					$this->RegisterDevice('sensor','rain',		$d,	'Rain');
				}
				elseif($raw['Type']=='Temp'){
					$this->RegisterDevice('sensor','temp',		$d,	'Temp');
				}
				elseif($raw['SwitchType']=='On/Off'){
					$this->RegisterDevice('command','switch',		$d,	'Status');
				}
				else{
					$this->RegisterDevice('undef','undef',		$d,	'Status');					
				}
			}
			//$this->Debug('Devices',$devices);
		}
		else{
			if($this->debug){
				$this->o_fn->PageError('500',"Failed to contact server at {$this->api_url} ");
			}
		}
		
		// get Cameras ------------------------
		if($this->ApiFetch('list','camera')){
			$cameras = $this->GetResult();
			foreach($cameras as $cam){
				$raw=$cam['raw'];
				if($raw['Enabled'] !='true'){continue;}
				$cam['url']	=$this->_MakeCameraUrl($raw);
				$this->RegisterDevice('camera','cam_ip',$cam,	'Enabled');
			}
		}
		else{
			if($this->debug){
				$this->o_fn->PageError('500',"Failed to contact server at {$this->api_url} ");
			}
		}		
	}

	//----------------------------------------------------------------------------------
	private function _MakeCameraUrl($raw){
		//make url
		$url='http://';
		if($raw['Username']){
			$url .="{$raw['Username']}";
			$raw['Password']	and $url.=":{$raw['Password']}";
			$url.="@";
		}
		$url.="{$raw['Address']}";
		if($raw['Port'] !=80){
			$url .=":{$raw['Port']}";
		}
		$url .="/{$raw['VideoURL']}";
		return $url;
	}

	
} 
?>
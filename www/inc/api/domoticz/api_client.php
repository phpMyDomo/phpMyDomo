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
Domoticz API ############################################################################
#########################################################################################
No dimmer supported at this time
*/

class PMD_ApiClient extends PMD_Root_ApiClient{



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
					$d['class']	='scene';
					$d['type']	='group';
				}
				elseif($raw['Type']=='Scene'){
					$d['class']	='scene';
					$d['type']	='scene';
				}
				elseif($raw['Type']=='Temp + Humidity + Baro'){
					$d['class']	='sensor';

					$d['type']	='temp';
					$d['value']	=$raw['Temp'];
					$this->RegisterDevice($d);
					
					$d['type']	='hygro';
					$d['value']	=$raw['Humidity'];
					$this->RegisterDevice($d);

					$d['type']	='baro';
					$d['value']	=$raw['Barometer'];

				}
				elseif($raw['Type']=='Wind'){
					$d['class']	='sensor';

					$d['type']	='wind_temp';
					$d['value']	=$raw['Temp'];
					$this->RegisterDevice($d);

					$d['type']	='wind_chill';
					$d['value']	=$raw['Chill'];
					$this->RegisterDevice($d);

					$d['type']	='wind_speed';
					$d['value']	=$raw['Speed'];
					$this->RegisterDevice($d);

					$d['type']	='wind_gust';
					$d['value']	=$raw['Gust'];

				}
				elseif($raw['Type']=='Rain'){
					$d['class']	='sensor';
					$d['type']	='rain';
					$d['value']	=$raw['Rain'];
				}
				elseif($raw['Type']=='Temp'){
					$d['class']	='sensor';
					$d['type']	='temp';
					$d['value']	=$raw['Temp'];
				}
				elseif($raw['Type']=='Temp + Humidity'){
					$d['class']	='sensor';
					
					$d['type']	='temp';
					$d['value']	=$raw['Temp'];
					$this->RegisterDevice($d);

					$d['type']	='hygro';
					$d['value']	=$raw['Humidity'];

				}
				elseif($raw['Type']=='YouLess Meter'){
					$d['class']	='sensor';
					
					$d['type']	='current';
					$d['value']	=(float) preg_replace('#[^0-9\.]+#','',$raw['CounterToday']);
					$d['unit']	="kWh";
					$this->RegisterDevice($d,'today');

					$d['type']	='current';
					$d['value']	=(float) preg_replace('#[^0-9\.]+#','',$raw['Usage']);
					$d['unit']	="W";
					$this->RegisterDevice($d,'now');

					$d['type']	='current';
					$d['value']	=(float) $raw['Counter'];
					$d['unit']	="kWh";
				}
				elseif($raw['SwitchType']=='Motion Sensor'){
					$d['class']	='sensor';
					$d['type']	='pir';
				}
				elseif($raw['SwitchType']=='On/Off'){
					$d['class']	='command';
					$d['type']	='switch';
				}
				elseif($raw['SwitchType']=='Dimmer'){
					$d['class']	='command';
					$d['type']	='dimmer';
					$d['value']	=$raw['LevelInt'];
				}
				$this->RegisterDevice($d);
			}
			//$this->Debug('Devices',$this->devices);
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
				$cam['class']	="camera";
				$cam['type']	="cam_ip";
				$this->RegisterDevice($cam);
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
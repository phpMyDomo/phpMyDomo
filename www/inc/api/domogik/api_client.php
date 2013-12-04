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
Domogiz 0.3 API

WorkInProgress:
Missing : Get values and format values for each devices

*/

	//----------------------------------------------------------------------------------
	function ApiListInfos(){
	}


	//----------------------------------------------------------------------------------
	function ApiListDevices(){
		if($this->ApiFetch('list','device')){
			$devices = $this->GetResult();
			$i=0;
			foreach($devices as $d){
				$raw=$d['raw'];
				//id is user as the address to send commands to
				$d['id']=$raw['device_type']['device_technology_id'].'/'.$raw['address'];

				// by master device usages
				if($raw['device_usage_id']=='air_conditionning'){
					$this->RegisterDevice('command','therm',	$d, $raw['id']);
				}
				elseif($raw['device_usage_id']=='ventilation'){
					$this->RegisterDevice('command','fan',	$d, $raw['id']);
				}
				elseif($raw['device_usage_id']=='heating'){
					$this->RegisterDevice('command','heating',	$d, $raw['id']);
				}
				elseif($raw['device_usage_id']=='shutter'){
					$this->RegisterDevice('command','shutter',	$d, $raw['id']);
				}

				// by device_type_id (RFXCOM) ---------
				elseif($raw['device_type_id']=='online_service.weather'){
					$this->RegisterDevice('sensor','temp',	$d, $raw['id']);					
				}
				elseif($raw['device_type_id']=='rfxcom.thb'){
					$d['sub_id']='temp';
					$this->RegisterDevice('sensor','temp',	$d, $raw['id'].'_'.$d['sub_id']);
					$d['sub_id']='baro';
					$this->RegisterDevice('sensor','baro',	$d, $raw['id'].'_'.$d['sub_id']);					
				}
				elseif($raw['device_type_id']=='rfxcom.th'){
					$this->RegisterDevice('sensor','temp',	$d, $raw['id']);					
				}
				elseif($raw['device_type_id']=='rfxcom.wind'){
					$d['sub_id']='speed';
					$this->RegisterDevice('sensor','wind_speed',	$d, $raw['id'].'_'.$d['sub_id']);					
					$d['sub_id']='gust';
					$this->RegisterDevice('sensor','wind_gust',	$d, $raw['id'].'_'.$d['sub_id']);					
				}
				elseif($raw['device_type_id']=='rfxcom.rain'){
					$this->RegisterDevice('sensor','rain',	$d, $raw['id']);					
				}
				elseif($raw['device_type_id']=='rfxcom.humidity'){
					$this->RegisterDevice('sensor','hum',	$d, $raw['id']);					
				}
				elseif($raw['device_type_id']=='rfxcom.uv'){
					$this->RegisterDevice('sensor','uv',	$d, $raw['id']);					
				}
				elseif($raw['device_type_id']=='rfxcom.rfxmeter'){
					$this->RegisterDevice('sensor','current',	$d, $raw['id']);					
				}
				elseif($raw['device_type_id']=='rfxcom.elec1'){
					$this->RegisterDevice('sensor','current',	$d, $raw['id']);					
				}
				elseif($raw['device_type_id']=='rfxcom.elec2'){
					$this->RegisterDevice('sensor','current',	$d, $raw['id']);					
				}
				elseif($raw['device_type_id']=='rfxcom.rfxsensor'){
					$this->RegisterDevice('sensor','temp',	$d, $raw['id']);					
				}
				
				elseif($raw['device_type_id']=='rfxcom.curtain1_harrison'){
					$this->RegisterDevice('command','switch',	$d, $raw['id']);					
				}

				elseif($raw['device_type_id']=='rfxcom.lighting1_arc_switch'){
					$this->RegisterDevice('command','switch',	$d, $raw['id']);					
				}
				elseif($raw['device_type_id']=='rfxcom.lighting1_chacon_switch'){
					$this->RegisterDevice('command','switch',	$d, $raw['id']);					
				}
				elseif($raw['device_type_id']=='rfxcom.lighting1_elro_switch'){
					$this->RegisterDevice('command','switch',	$d, $raw['id']);					
				}
				elseif($raw['device_type_id']=='rfxcom.lighting1_impuls_switch'){
					$this->RegisterDevice('command','switch',	$d, $raw['id']);					
				}
				elseif($raw['device_type_id']=='rfxcom.lighting1_waveman_switch'){
					$this->RegisterDevice('command','switch',	$d, $raw['id']);					
				}
				/*
				elseif($raw['device_type_id']=='rfxcom.digimax'){
					$this->RegisterDevice('command','switch',	$d, $raw['id']);					
				}
				*/
				
				elseif($raw['device_type_id']=='rfxcom.lighting2_ac_dimmer'){
					$this->RegisterDevice('command','dimmer',	$d, $raw['id']);					
				}
				elseif($raw['device_type_id']=='rfxcom.lighting1_x10_dimmer'){
					$this->RegisterDevice('command','dimmer',	$d, $raw['id']);					
				}
				elseif($raw['device_type_id']=='rfxcom.lighting2_homeeasy_eu_dimmer'){
					$this->RegisterDevice('command','dimmer',	$d, $raw['id']);					
				}
				elseif($raw['device_type_id']=='rfxcom.lighting3_koppla_dimmer'){
					$this->RegisterDevice('command','dimmer',	$d, $raw['id']);					
				}

				elseif($raw['device_type_id']=='dawndusk.status'){
					//$this->RegisterDevice('info','dawndusk',	$d, $raw['id']);
				}

				// by (fallback) device_usage_id ---------

				elseif($raw['device_usage_id']=='appliance' or $raw['device_usage_id']=='light' or $raw['device_usage_id']=='christmas_tree' or $raw['device_usage_id']=='portal'){
					$this->RegisterDevice('command','switch',	$d, $raw['id']);
				}

				elseif($raw['device_usage_id']=='water_tank'){
					$this->RegisterDevice('sensor','level',	$d, $raw['id']);
				}
				elseif($raw['device_usage_id']=='temperature'){
					$this->RegisterDevice('sensor','temp',	$d, $raw['id']);
				}
				elseif($raw['device_usage_id']=='door'){
					$this->RegisterDevice('security','door',	$d, $raw['id']);
				}
				elseif($raw['device_usage_id']=='window'){
					$this->RegisterDevice('security','window',	$d, $raw['id']);
				}

				elseif($raw['device_usage_id']=='security_camera'){
					$this->RegisterDevice('camera','cam_ip',	$d, $raw['id']);
				}

				elseif($raw['device_usage_id']=='scene'){
					$this->RegisterDevice('scene','scene',	$d, $raw['id']);
				}

				else{
					$this->RegisterDevice('undef','undef',	$d, $raw['id']);					
				}
			}
			$this->_FetchValues();
			//$this->Debug('Devices',$this->devices);
			//$this->Debug('JSON',$this->api_response);
		}
		else{
			if($this->debug){
				$this->o_fn->PageError('500',"Failed to contact server at {$this->api_url} ");
			}
		}
	}

	// grab devices Values -------------------------------------------------------------
	// not finished, need some work
	
	private function _FetchValues(){
		if($this->ApiFetch('list','stats')){
			$stats=$this->api_response['stats'];
			foreach($this->devices as $uid => $d){
				foreach($stats as $s){
					if($d['raw']['id']==$s['device_id']){
						//assign value
						//value for commands should be ( 'on' | 'off' | 'int');
						$d['value']=$s['value'];
						if($d['value']=='none' or $d['value']=='0') $d['value']='off';
					}
				}
				
				//assign img_type
				$d['img_type']=$d['type'];
				if($d['class'] !='sensor' and $d['class'] !='camera'){
					$d['value'] or $d['value']='on'; //just to test
					$d['img_type'] .="_".$d['value'];
				}
				// replace
				$this->devices[$uid]=$d;
			}
			//$this->Debug('Stats',$stats);
		}
		else{
			if($this->debug){
				$this->o_fn->PageError('500',"Failed to contact server at {$this->api_url} ");
			}
		}		
	}
} 
?>
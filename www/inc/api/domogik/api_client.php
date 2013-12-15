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
Domogiz 0.3 API #########################################################################
#########################################################################################
only Device listing is supported.
WorkInProgress: Missing values and states for each devices. Domogiz 0.4 should be better

*/

class PMD_ApiClient extends PMD_Root_ApiClient{


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
				//address to send commands to
				// Api Doc is not very usefull : What is the correct address?
				//$d['address']=$raw['device_type']['device_technology_id'].'/'.$raw['address'];
				$d['address']=$raw['device_type']['id'].'/'.$raw['address'];

				// by master device usages
				if($raw['device_usage_id']=='air_conditionning'){
					$d['class']	='command';
					$d['type']	='therm';
				}
				elseif($raw['device_usage_id']=='ventilation'){
					$d['class']	='command';
					$d['type']	='fan';
				}
				elseif($raw['device_usage_id']=='heating'){
					$d['class']	='command';
					$d['type']	='heating';
				}
				elseif($raw['device_usage_id']=='shutter'){
					$d['class']	='command';
					$d['type']	='shutter';
				}

				// by device_type_id (RFXCOM) ---------
				elseif($raw['device_type_id']=='online_service.weather'){
					$d['class']	='sensor';
					$d['type']	='temp';
				}
				elseif($raw['device_type_id']=='rfxcom.thb'){
					$d['class']	='sensor';

					$d['type']	='temp';
					$this->RegisterDevice($d);

					$d['type']	='baro';
				}
				elseif($raw['device_type_id']=='rfxcom.th'){
					$d['class']	='sensor';
					$d['type']	='temp';
				}
				elseif($raw['device_type_id']=='rfxcom.wind'){
					$d['class']	='sensor';
					
					$d['type']	='wind_speed';
					$this->RegisterDevice($d);

					$d['type']	='wind_gust';
				}
				elseif($raw['device_type_id']=='rfxcom.rain'){
					$d['class']	='sensor';
					$d['type']	='rain';
				}
				elseif($raw['device_type_id']=='rfxcom.humidity'){
					$d['class']	='sensor';
					$d['type']	='hygro';
				}
				elseif($raw['device_type_id']=='rfxcom.uv'){
					$d['class']	='sensor';
					$d['type']	='uv';
				}
				elseif($raw['device_type_id']=='rfxcom.rfxmeter'){
					$d['class']	='sensor';
					$d['type']	='consum';
				}
				elseif($raw['device_type_id']=='rfxcom.elec1'){
					$d['class']	='sensor';
					$d['type']	='consum';
				}
				elseif($raw['device_type_id']=='rfxcom.elec2'){
					$d['class']	='sensor';
					$d['type']	='consum';
				}
				elseif($raw['device_type_id']=='rfxcom.rfxsensor'){
					$d['class']	='sensor';
					$d['type']	='temp';
				}
				
				elseif($raw['device_type_id']=='rfxcom.curtain1_harrison'){
					$d['class']	='command';
					$d['type']	='switch';
				}

				elseif($raw['device_type_id']=='rfxcom.lighting1_arc_switch'){
					$d['class']	='command';
					$d['type']	='switch';
				}
				elseif($raw['device_type_id']=='rfxcom.lighting1_chacon_switch'){
					$d['class']	='command';
					$d['type']	='switch';
				}
				elseif($raw['device_type_id']=='rfxcom.lighting1_elro_switch'){
					$d['class']	='command';
					$d['type']	='switch';
				}
				elseif($raw['device_type_id']=='rfxcom.lighting1_impuls_switch'){
					$d['class']	='command';
					$d['type']	='switch';
				}
				elseif($raw['device_type_id']=='rfxcom.lighting1_waveman_switch'){
					$d['class']	='command';
					$d['type']	='switch';
				}
				/*
				elseif($raw['device_type_id']=='rfxcom.digimax'){
					$this->RegisterDevice('command','switch',	$d, $raw['id']);					
				}
				*/
				
				elseif($raw['device_type_id']=='rfxcom.lighting2_ac_dimmer'){
					$d['class']	='command';
					$d['type']	='dimmer';
				}
				elseif($raw['device_type_id']=='rfxcom.lighting1_x10_dimmer'){
					$d['class']	='command';
					$d['type']	='dimmer';
				}
				elseif($raw['device_type_id']=='rfxcom.lighting2_homeeasy_eu_dimmer'){
					$d['class']	='command';
					$d['type']	='dimmer';
				}
				elseif($raw['device_type_id']=='rfxcom.lighting3_koppla_dimmer'){
					$d['class']	='command';
					$d['type']	='dimmer';
				}

				elseif($raw['device_type_id']=='dawndusk.status'){
					continue;
				}

				// by (fallback) device_usage_id ---------

				elseif($raw['device_usage_id']=='appliance' or $raw['device_usage_id']=='light' or $raw['device_usage_id']=='christmas_tree' or $raw['device_usage_id']=='portal'){
					$d['class']	='command';
					$d['type']	='switch';
				}

				elseif($raw['device_usage_id']=='water_tank'){
					$d['class']	='sensor';
					$d['type']	='level';
				}
				elseif($raw['device_usage_id']=='temperature'){
					$d['class']	='sensor';
					$d['type']	='temp';
				}
				elseif($raw['device_usage_id']=='door'){
					$d['class']	='security';
					$d['type']	='door';
				}
				elseif($raw['device_usage_id']=='window'){
					$d['class']	='security';
					$d['type']	='window';
				}

				elseif($raw['device_usage_id']=='security_camera'){
					$d['class']	='camera';
					$d['type']	='cam_ip';
				}

				elseif($raw['device_usage_id']=='scene'){
					$d['class']	='scene';
					$d['type']	='scene';
				}
				else{
					
				}
				$this->RegisterDevice($d);

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
						$d['raw_stats']=$s;
						//assign value
						//value for commands should be ( 'on' | 'off' | 'int');
						$d['value']=$s['value'];
						if($d['value']=='none' or $d['value']=='0') $d['state']='off';
					}
				}
				
				//assign state to switches
				if($d['class'] !='sensor' and $d['class'] !='camera'){
					$d['state'] or $d['state']='on'; //just to test
				}
				// replace
				$this->RegisterDevice($d);
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
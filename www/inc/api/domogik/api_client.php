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

	// todo: use wget -qO- http://www.gaisma.com/en/location/antibes.html | grep 'class="sunshine"' to get astronomy
		
		$this->infos['server_time'] = time();
	}


	//----------------------------------------------------------------------------------
	function ApiListDevices(){

		$time_start = microtime(true);

		if($this->ApiFetch('list','device')){
			$devices = $this->GetResult();
			$i=0;
			
			foreach($devices as $d){
				$raw=$d['raw'];
				$tech = explode(".", $raw['device_feature_model_id']);
				$techno=$tech[0];
				$device_feature=$tech[1];
				$device_usage_id=$raw['device']['device_usage_id'];
				
				// device name
				$d['name']=$raw['device']['name'];
				
				// address to send commands to
				$d['address']=$techno.'/'.$raw['device']['address'];
				
				// first eliminate duplicates (dimmer declared as both change and switch)
				if ($tech[2]=='change'){
					continue;
				}
				
				// eliminate elements without representation
				if ($device_usage_id=='server' or $raw['device']['device_type_id']=='dawndusk.status'){
					continue;
				}
				
				// by master device usages
				if($device_usage_id=='air_conditionning'){
					$d['class']	='command';
					$d['type']	='therm';
				}
				elseif($device_usage_id=='ventilation'){
					$d['class']	='command';
					$d['type']	='fan';
				}
				elseif($device_usage_id=='heating'){
					$d['class']	='command';
					$d['type']	='heating';
				}
				elseif($device_usage_id=='shutter'){
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
				
				// by device type
				
				elseif($device_feature=='dimmer'){
					$d['class']	='command';
					$d['type']	='dimmer';
				}
				
				elseif($device_feature=='switch'){
					$d['class']	='command';
					$d['type']	='switch';
				}
				
				// by (fallback) device_usage_id ---------

				elseif($device_usage_id=='light' or $device_usage_id=='christmas_tree'){
					$d['class']	='command';
					$d['type']	='switch';
				}

				elseif($device_usage_id=='appliance'){
					$d['class']	='command';
					$d['type']	='alarm';
				}

				elseif($device_usage_id=='water_tank'){
					$d['class']	='sensor';
					$d['type']	='level';
				}
				elseif($device_usage_id=='temperature'){
					$d['class']	='sensor';
					$d['type']	='temp';
				}
				elseif($device_usage_id=='door' or $device_usage_id=='portal'){
					$d['class']	='security';
					$d['type']	='door';
				}
				elseif($device_usage_id=='window'){
					$d['class']	='security';
					$d['type']	='window';
				}

				elseif($device_usage_id=='security_camera'){
					$d['class']	='camera';
					$d['type']	='cam_ip';
				}

				elseif($device_usage_id=='scene'){
					$d['class']	='scene';
					$d['type']	='scene';
				}
				else{
					
				}

				// fetch current value of the device
				if (!isset($this->vars['db']['database'])){
					if($this->ApiFetch('list','stats', $d['raw']['device_id'] , $d['raw']['device_feature_model']['stat_key'])){

						$stats=$this->api_response['stats'];
						//if ($raw['device_id']=="45") $this->Debug('Stats',$stats);
					
						// case of boolean
						if ($d['raw']['device_feature_model']['value_type']=="binary" or $d['raw']['device_feature_model']['value_type']=="boolean") {
						
							//compare with comparison json string
							$stat_values=json_decode(html_entity_decode($d['raw']['device_feature_model']['parameters']), true);
							$value = strtolower($stats[0]['value']);
						
							if ($value == strtolower($stat_values['value1']) || $value == 'preset_dim') {
								$d['state'] = 'on';
							} 
							elseif ($value == strtolower($stat_values['value0']))
								$d['state'] = 'off';
							} 
							elseif ($d['raw']['device_feature_model']['value_type']=="number") {
								$d['value'] = (float) $stats[0]['value'];
							}
						} 
					elseif($this->debug){
						$this->o_kernel->PageError('500',"Failed to contact server at {$this->api_url} ");
					}
				}
				// register the full device
				$this->RegisterDevice($d);
			}
			
			if (isset($this->vars['db']['database']))
				$this->_FetchValues();
			
			if($this->debug){
			// additional device to measure query time
				$time_end = microtime(true);
				$time = $time_end - $time_start;
				$tdev['name'] = 'Mysql Query time';
				$tdev['class'] = 'sensor';
				$tdev['type'] = 'level';
				$tdev['unit'] = 's';
				$tdev['value'] = (float) $time;
				$this->RegisterDevice($tdev);
			}
			//$this->Debug('Devices',$this->devices);
			//$this->Debug('JSON',$this->api_response);
		}
		else{
			if($this->debug){
				$this->o_kernel->PageError('500',"Failed to contact server at {$this->api_url} ");
			}
		}
	}

	// grab device values
	private function _FetchValues(){
		
		// build multiple query
		$query = "";
		foreach($this->devices as $uid => $d){
			if ($query != "") $query .= " union all ";
			$query .= "(SELECT * FROM core_device_stats WHERE device_id = '" . $d['raw']['device_id'] . "' AND skey = '" . $d['raw']['device_feature_model']['stat_key'] . "' ORDER BY date DESC LIMIT 1)";
		}
		
		// fetch data in db in one go
		$mysqli = @new mysqli($this->vars['db']['host'], $this->vars['db']['user'], $this->vars['db']['password'], $this->vars['db']['database'],$this->vars['db']['port']);
		if ($mysqli->connect_errno) {
			$this->o_kernel->PageError('500', "Failed to connect to database : {$mysqli->connect_error} at {$this->vars['db']['host']}:{$this->vars['db']['port']} ");
		}
		$res = $mysqli->query($query);
		
		// loop to read the results (assuming that both $this->devices and $rows are in the same order
		// due to way data have been fetched in the db)
		$row = $res->fetch_assoc();
		foreach($this->devices as $uid => $d){
		
			//if ($d[raw]['device_id']=="45") $this->Debug('Row',$row);
		
			// check that result corresponds to current device (may happen that no result has been returned for a given device)
			if ($row['device_id'] == $d['raw']['device_id']) {
			
				// case of boolean
				if ($d['raw']['device_feature_model']['value_type']=="binary" or $d['raw']['device_feature_model']['value_type']=="boolean") {
					
					//compare with comparison json string
					$stat_values=json_decode(html_entity_decode($d['raw']['device_feature_model']['parameters']), true);
					$value = strtolower($row['value_str']);
					
					if ($value == strtolower($stat_values['value1']) || $value == 'preset_dim') {
						$d['state'] = 'on';
					} elseif ($value == strtolower($stat_values['value0']))
						$d['state'] = 'off';
				
				// case of numeric value
				} elseif ($d['raw']['device_feature_model']['value_type']=="number") {
				
					// take into account only if value not older than thirty minutes
					if ((time() - $row['timestamp'])/60 < 30)
						$d['value'] = (float) $row['value_num'];
					
					//$this->Debug('Row',time() . " " . $row['timestamp'] . " " . $row['date'] . " " . (time() - $row['timestamp'])/60);
				}

				// address specific case of dimmers
				if ($d['type'] == 'dimmer') {
				
					$dres = $mysqli->query("SELECT * FROM core_device_stats WHERE device_id = '" . $d['raw']['device_id'] . "' AND skey = 'level' ORDER BY date DESC LIMIT 1");
					
					if ($row = $dres->fetch_assoc()) {
					
						$level = (float) $row['value_num'];;

						// for unknown level domogik may return 255 !!!
						if ($level >= 0 && $level <= 100) {
							$d['value'] = $level;
						}

						// a 0 level indicates a device which is actually off
						if ($level == 0) {
							$d['state'] = 'off';
						}
					}
				}
				
				$this->RegisterDevice($d);
				
				// read next row of result
				$row = $res->fetch_assoc();
			}
		}
	}
} 
?>

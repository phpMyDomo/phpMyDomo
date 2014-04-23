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
Only Devices listing is supported.
WorkInProgress: Missing values and states for each devices. (Domogiz 0.4 should be better)

//TODO :
- Move  value parsing via API or via Db to a single common method, for both ways, ie : $d=$this->_ParseValue($d,$row,'sql') 
- Check that SET command and DIM command work, Might need extending the ApiFetch method


*/

class PMD_ApiClient extends PMD_Root_ApiClient{

	//----------------------------------------------------------------------------------
	function ApiListInfos(){
	}


	//----------------------------------------------------------------------------------
	function ApiListDevices(){

		$time_start = microtime(true);

		if($this->ApiFetch('list','device')){
			$devices = $this->GetResult();
			$i=0;

			$sensors_devices_types_ids	=$this->vars['sensors_devices_types_ids'];
			$switches_devices_types_ids	=$this->vars['switches_devices_types_ids'];
			$dimmers_devices_types_ids	=$this->vars['dimmers_devices_types_ids'];
		
			
			foreach($devices as $d){
				$raw=$d['raw'];
				$tech = explode(".", $raw['device_feature_model_id']);
				$techno=$tech[0];
				$device_feature=$tech[1];
				$device_usage_id=$raw['device']['device_usage_id'];
				$device_type_id	=$raw['device']['device_type_id'];
				
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
				

				//by device_type_id ----------------
				if(in_array($device_type_id, $dimmers_devices_types_ids)){
					$d['class']	='command';
					$d['type']	='dimmer';					
				}
				elseif(in_array($device_type_id, $switches_devices_types_ids)){
					$d['class']	='command';
					$d['type']	='switch';					
				}
				elseif($type=$sensors_devices_types_ids[$device_type_id]){
					$d['class']	='sensor';
					$d['type']	=$type;					
				}
				elseif($device_type_id=='rfxcom.thb'){
					$d['class']	='sensor';

					$d['type']	='temp';
					$this->RegisterDevice($d);

					$d['type']	='baro';
				}
				elseif($device_type_id=='rfxcom.wind'){
					$d['class']	='sensor';
					
					$d['type']	='wind_speed';
					$this->RegisterDevice($d);

					$d['type']	='wind_gust';
				}
				/*
				elseif($device_type_id=='rfxcom.digimax'){
					$this->RegisterDevice('command','switch',	$d, $raw['id']);					
				}
				*/

				// by device type -----------------
				elseif($device_feature=='dimmer'){
					$d['class']	='command';
					$d['type']	='dimmer';
				}
				elseif($device_feature=='switch'){
					$d['class']	='command';
					$d['type']	='switch';
				}


				// by (fallback) device_usage_id ---------
				elseif($device_usage_id=='air_conditionning'){
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
				elseif($device_usage_id=='light' or $device_usage_id=='christmas_tree'){
					$d['class']	='command';
					$d['type']	='switch';
				}
				elseif($device_usage_id=='appliance'){
					$d['class']	='command';
					$d['type']	='switch';
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

				//commands must have a default STATE
				if ($d['class'] == 'command' ) {
					strlen($d['state']) or $d['state']='off';
				}

				// fetch current value of the device
				if(!isset($this->vars['db']['database'])){
					if($this->ApiFetch('list','stats', $d['raw']['device_id'] , $d['raw']['device_feature_model']['stat_key'])){

						$stats=$this->api_response['stats'];
						//if ($raw['device_id']=="45") $this->Debug('Stats',$stats);
					

//start_tomove1 ; move this to a method ++++++++++++++++++++++++++++
//$d=$this->_ParseValues($d, $stats[0],'api');
					
						// case of boolean
						if ($d['raw']['device_feature_model']['value_type']=="binary" or $d['raw']['device_feature_model']['value_type']=="boolean") {
						
							//compare with comparison json string
							$model=json_decode(html_entity_decode($d['raw']['device_feature_model']['parameters']), true);
							$value = strtolower($stats[0]['value']);
						
							if ($value == strtolower($model['value1']) || $value == 'preset_dim') {
								$d['state'] = 'on';
							} 
							elseif ($value == strtolower($model['value0'])){
								$d['state'] = 'off';
							} 
						}
						elseif ($d['raw']['device_feature_model']['value_type']=="number") {
								$d['value'] = (int) $stats[0]['value'];
						}
// end_tomove1 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++


					}
					elseif($this->debug){
						$this->o_kernel->PageError('500',"Failed to contact server at {$this->api_url} ");
					}
				}
				// register the full device
				$this->RegisterDevice($d);
			}

			if(isset($this->vars['db']['database'])){
				$this->_FetchValues();
			}

			
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

	//----------------------------------------------------------------------------------
	// grab all devices values
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

				
				
//start_tomove2 ; mode this to a method ++++++++++++++++++++++++++++
//$d=$this->_ParseValues($d, $row,'sql');

				$model=json_decode(html_entity_decode($d['raw']['device_feature_model']['parameters']), true);
			
				// case of boolean
				if ($d['raw']['device_feature_model']['value_type']=="binary" or $d['raw']['device_feature_model']['value_type']=="boolean") {
					
					//compare with comparison json string
					$value = strtolower($row['value_str']);
					
					if ($value == strtolower($model['value1']) || $value == 'preset_dim') {
						$d['state'] = 'on';
					} 
					elseif ($value == strtolower($model['value0'])){
						$d['state'] = 'off';
					}
				
				} 
				// case of numeric value
				elseif ($d['raw']['device_feature_model']['value_type']=="number") {
				
					/* 
					WHY ????
					// take into account only if value not older than thirty minutes
					if ((time() - $row['timestamp'])/60 < 30){
						$d['value'] = (float) $row['value_num']; // float really ???????
					}
					*/
					$d['value'] = (int) $row['value_num'];
					//$this->Debug('Row',time() . " " . $row['timestamp'] . " " . $row['date'] . " " . (time() - $row['timestamp'])/60);
				}

				// address specific case of dimmers
				if ($d['type'] == 'dimmer') {
				
					$dres = $mysqli->query("SELECT * FROM core_device_stats WHERE device_id = '" . $d['raw']['device_id'] . "' AND skey = 'level' ORDER BY date DESC LIMIT 1");
					
					if ($row = $dres->fetch_assoc()) {
					
						$level = (int) $row['value_num'];

						// for unknown level domogik may return 255 !!!
						if ($level >= 0 && $level <= 100) {
							$d['value'] = $level;
							$d['state'] = 'on';
						}

						// a 0 level indicates a device which is actually off
						if ($level == 0) {
							$d['state'] = 'off';
						}
						// min and max could change depnding on the dimmer model (grrrrrr)...
						//strlen($model['valueMin']) and $this->vars['set']['dimmer']['min']=(int) $model['valueMin'];
						//strlen($model['valueMax']) and $this->vars['set']['dimmer']['max']=(int) $model['valueMax'];
					}
				}
				//for debug
				//$d['raw_sql']=$row;
// end_tomove2 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++


				
				$this->RegisterDevice($d);
				
				// read next row of result
				$row = $res->fetch_assoc();
			}
		}
	}

/*
	//----------------------------------------------------------------------------------
	private function _ParseValues($d,$row,$mode='api'){
		if($mode=='api'){
			$value_num=strtolower($row['value']);
			$value_str=strtolower($row['value']);
		}
		elseif($mode=='sql'){
			$value_num=strtolower($row['value_num']);
			$value_str=strtolower($row['value_str']);
		}
		else{
			$this->Debug("Unknown mode");
			return $d;
		}
		// parse values and scale min/max
		
		
		return $d;
	}


	//----------------------------------------------------------------------------------
	function ApiFetch($command, $type='', $address='',$state='',$invert_set=false){
		if($command=='set'){			
			if($d=$this->_GetDeviceByAddress($address)){
				$model=json_decode(html_entity_decode($d['raw']['device_feature_model']['parameters']), true);

				//assign corrects states depending on the device_feature_model ??? : grrrrrrrrr !
				if($type=="switch"){
					// si c'est pas toujour on ou off, il faut le gerer
					//if($d['raw']['value_type']=='binary'){
					//	$my_states['off']	=$model['value0'];
					//	$my_states['on']	=$model['value1'];
					//}
					//strlen($my_states[$state]) and $state=$my_states[$state];
				}
				
				// min and max could change depending on the dimmer model (grrrrrr)...
				elseif($type=="dim_level"){
						strlen($model['valueMin']) and $this->vars['set']['dimmer']['min']=(int) $model['valueMin'];
						strlen($model['valueMax']) and $this->vars['set']['dimmer']['max']=(int) $model['valueMax'];
				}
			}
		}
		return parent::ApiFetch($command, $type, $address,$state,$invert_set);
	}

	//----------------------------------------------------------------------------------
	private function _GetDeviceByAddress($address){
		foreach($this->devices as $d){
			if($d['address']==$address){
				return $d;
			}
		}
	}
*/

} 
?>

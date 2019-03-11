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
OpenHab API v2 #############################################################################
#########################################################################################

https://docs.openhab.org/v2.1/concepts/items.html
http://demo.openhab.org:8080/doc/index.html
*/

class PMD_ApiClient extends PMD_Root_ApiClient{


	//----------------------------------------------------------------------------------
	function ApiListInfos(){
		//if($this->ApiFetch('list','info')){
			//$info = $this->api_response;
			//$this->infos['server_time']		=strtotime($info['state']);
			//$this->infos['sunset_time']		=strtotime(date('m/d/Y ').$info['Sunset']);
			//$this->infos['sunrise_time']	=strtotime(date('m/d/Y ').$info['Sunrise']);
		//}
		//$this->Debug('Info',$this->infos);
	}

	//----------------------------------------------------------------------------------
	function ApiListDevices(){
		if($this->ApiFetch('list','device')){
			$devices = $this->GetResult();
			
			$i=0;
			foreach($devices as $d){
				$raw=$d['raw'];
				$d['address']=$raw['name'];
				$d=$this->_makePrettyName($d);
				//$d['address']=='Temperature' or $d['address']=='Status' or $d['address']=='Weather' or $d['address']=='Weather_Chart'
				if($d['aaaa']){
					// Dont know what they are! So skippeng registraion at the moment
					continue;
				}
				elseif($raw['type']=='Color'){
					$d['class']	="command";
					$d['type']	="rgb";
				}
				elseif($raw['type']=='Contact'){
					$d['class']	="sensor";
					$d['type']	="bool";
					$d=$this->FormatState($d);
					if($raw['category']=='door'){
						$d['type']	="door";					
					}
					elseif($raw['category']=='window'){
						$d['type']	="door";	// should we create a 'window' type ?				
					}
				}
				elseif($raw['type']=='DateTime'){
					//$this->infos['server_time']		=strtotime($raw['state']);
					//continue;
				}
				elseif($raw['type']=='Dimmer'){
					$d['class']	="command";
					$d['type']	="dimmer";
					$d['value']	=$raw['state'];
					$d=$this->FormatState($d);
					//isset($d['state']) or $d['value']	=$raw['value1'];
				}
				elseif($raw['type']=='Group'){
					$d['class']	="scene";
					$d['type']	="group";
					$d=$this->_makePrettyName($d,1);

					if($raw['category']=='rollershutter'){
						$d['class']	="sensor";
						$d['type']	="shutter";	// 
						$d['raw_value1']=$raw['function']['params'][$raw['state']];
						$d=$this->FormatState($d);
					}
					
					if($raw['state']=='NULL'){
						// Dont know what they are! So skippeng registraion at the moment
						continue;
					}
					
					
				}
				elseif($raw['type']=='Number'){
					$d['class']	="sensor";
					if($raw['category']=='humidity'){
						$d['type']	="hygro";
						$d['value']	=(float) $raw['state'];
					}
					elseif($raw['category']=='temperature'){
						$d['type']	="temp";
						$d['value']	=(float) $raw['state'];
					}
					elseif($raw['category']=='signal'){
						$d['type']	="custom";		// should we create a 'level' type ?
						$d['value']	=$raw['state'];
					}
					elseif($raw['category']=='network'){
						$d['type']	="custom";
						$d['value']	=$raw['state'];
					}
					elseif($raw['category']=='heating'){
						$d['type']	="custom";
						$d['value']	=str_replace('NULL','',$raw['state']);
					}
				}
				elseif($raw['type']=='Player'){
					$d['class']	="sensor";
					$d['type']	="mediaplayer";
				}
				elseif($raw['type']=='Rollershutter'){
					$d['class']	="command";
					$d['type']	="shutter";
					$d['value']	=$raw['state'];
				}
				elseif($raw['type']=='String'){
					$d['class']	="sensor";
					$d['type']	="text";
					$d['value']	=str_replace('NULL','',$raw['state']);
					if($raw['category']=='moon'){
						$d=$this->_StateLabelToValue($d);
					}
				}
				elseif($raw['type']=='Switch'){
					$d['class']	="command";
					$d['type']	="switch";
					if($raw['category']=='heating'){
						$d['type']	="heating";
					}
					elseif($raw['category']=='motion'){
						$d['class']	="sensor";
						$d['type']	="bool";
						$d=$this->FormatState($d);	//use bool state
						$d['type']	="pir";

//				$this->Debug('Devices',$d);
					}
				}
				/*
				elseif($d['address']=='Shutter_all'){
					$d['class']	="scene";
					$d['type']	="group";					
				}
				*/

				$d['name']=str_replace('_',' ',$d['name']);
				$this->RegisterDevice($d);					
				
			}
			//$this->Debug('JSON',$this->api_response);
			//$this->Debug('Devices',$this->devices);
			//$this->Debug('Devices',$devices);
		}
		else{
			if($this->debug){
				$this->o_kernel->PageError('500',"Failed to contact server at {$this->api_url} ");
			}
		}
	}

	//----------------------------------------------------------------------------------

	function _StateLabelToValue($d){
		$states=$d['raw']['stateDescription']['options'];
		if(is_array($states) and $d['raw']['state']){
			foreach($states as $row){
				if($row['value']==$d['raw']['state']){
					$d['value']=$row['label'];
					break;
				}
			}
		}
		return $d;
	}
	//----------------------------------------------------------------------------------

	function _makePrettyName($d,$group_mode=0){
		if($group_mode){
			list($place,$name)=explode('_',$d['raw']['name']);
			$name=str_replace('_',' ',$name);
			$name and $d['name']=$name;
		}
		else{
			list($type,$place,$name,$name2)=explode('_',$d['raw']['name']);
			$name=str_replace('_',' ',$name.$name2);
			$name and $d['name']=$name." / ".$type;
		}
		return $d;
	}


} 
?>
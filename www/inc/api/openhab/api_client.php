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

class PMD_ApiClient extends PMD_Root_ApiClient{

/* 
OpenHab API

WorkInProgress:

*/

	//----------------------------------------------------------------------------------
	function ApiListInfos(){
		//$this->Debug('Infos',$this->infos);
	}


	//----------------------------------------------------------------------------------
	function ApiListDevices(){
		if($this->ApiFetch('list','device')){
			$devices = $this->GetResult();
			$i=0;
			foreach($devices as $d){
				$raw=$d['raw'];
				$d['address']=$raw['name'];
				if($d['address']=='Temperature' or $d['address']=='Status' or $d['address']=='Weather' or $d['address']=='Weather_Chart'){
					
				}
				elseif($d['address']=='Shutter_all'){
					$d['class']	="scene";
					$d['type']	="group";					
				}
				elseif($raw['type']=='SwitchItem'){
					$d['class']	="command";
					$d['type']	="switch";
				}
				elseif($raw['type']=='RollershutterItem'){
					$d['class']	="command";
					$d['type']	="shutter";
					$d['value']	=$raw['state'];
				}
				elseif($raw['type']=='ColorItem'){
					$d['class']	="command";
					$d['type']	="rgb";
				}
				elseif($raw['type']=='DimmerItem'){
					$d['class']	="command";
					$d['type']	="dimmer";
					$d['value']	=$raw['state'];
					//$d=$this->FormatState($d);
					//isset($d['state']) or $d['value']	=$raw['value1'];
				}
				elseif($raw['type']=='NumberItem'){
					$d['class']	="sensor";
					$d['type']	="temp"; // we need to dertermine what type of sensor it is
					$d['value']	=(float) $raw['state'];
				}
				elseif($raw['type']=='ContactItem'){
					$d['class']	="sensor";
					$d['type']	="bool"; // we need to dertermine what type of sensor it is
				}
				elseif($raw['type']=='GroupItem'){
					$d['class']	="scene";
					$d['type']	="group";
				}

				$this->RegisterDevice($d);					
				
			}
			//$this->Debug('JSON',$this->api_response);
			//$this->Debug('Devices',$this->devices);
			//$this->Debug('Devices',$devices);
		}
		else{
			if($this->debug){
				$this->o_fn->PageError('500',"Failed to contact server at {$this->api_url} ");
			}
		}
	}

	//----------------------------------------------------------------------------------

} 
?>
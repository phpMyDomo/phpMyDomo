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
FreeDomotic API ############################################################################
#########################################################################################
Proof of Concept : Only Switches and Temperatures sensor MIGHT work

*/

class PMD_ApiClient extends PMD_Root_ApiClient{



	//----------------------------------------------------------------------------------
	function ApiListInfos(){
//TODO
		/*
		if($this->ApiFetch('list','info')){
			$info = $this->api_response;
			//$this->infos['sunset_time']		=strtotime(date('m/d/Y ').$info['Sunset']);
			//$this->infos['sunrise_time']	=strtotime(date('m/d/Y ').$info['Sunrise']);
			//$this->infos['server_time']		=strtotime($info['ServerTime']);
		}
		*/
		//$this->Debug('Info',$this->infos);
	}


	//----------------------------------------------------------------------------------
	function ApiListDevices(){
		if($this->ApiFetch('list','device')){
			$devices = $this->GetResult();

			$i=0;
			foreach($devices as $d){
				$raw=$d['raw'];
				if($raw['type']=='EnvObject.ElectricDevice.Light'){
					$d['class']	='command';
					$d['type']	='switch';
					if($behavior=$this->_extractBehaviorbyName($raw['behaviors'],'powered')){
						$d['raw_value1']=$behavior['value'];
					}
				}
				/*
				elseif($raw['type']=='EnvObject.ElectricDevice'){
					$d['class']	='scene';
					$d['type']	='scene';
					if($behavior=$this->_extractBehaviorbyName($raw['behaviors'],'powered')){
						$d['raw_value1']=$behavior['value'];
					}
				}
				*/
				elseif($raw['type']=='EnvObject.Gate'){
					$d['class']	='sensor';
					$d['type']	='door';
					if($behavior=$this->_extractBehaviorbyName($raw['behaviors'],'open')){
						$d['raw_value1']=$behavior['value'];
					}
				}
				elseif($raw['type']=='EnvObject.Thermometer'){
					$d['class']	='sensor';
					$d['type']	='temp';
					if($behavior=$this->_extractBehaviorbyName($raw['behaviors'],'temperature')){
						$d['value']=$behavior['value'] / $behavior['scale'] ;
					}
				}
//TODO other types


				$this->RegisterDevice($d);
			}

			//uncomment this to show the whole devices array
			//$this->Debug('Devices',$this->devices);

		}
		else{
			if($this->debug){
				$this->o_kernel->PageError('500',"Failed to contact server at {$this->api_url} ");
			}
		}
		
		/*
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
				$this->o_kernel->PageError('500',"Failed to contact server at {$this->api_url} ");
			}
		}
		*/		
	}

	// ---------------------------------------------------------------
	private function _extractBehaviorbyName($behaviors,$name){
		if(is_array($behaviors)){
			foreach($behaviors as $b){
				if($b['name']==$name){
					return $b;
				}
			}
		}
		return false;
	}

	
} 
?>
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
Pilight API #############################################################################
#########################################################################################

Draft pilight API (absolutely not tested,  certainly needs some fixes)

*/

class PMD_ApiClient extends PMD_Root_ApiClient{


	//----------------------------------------------------------------------------------
	function ApiListInfos(){
		// (optionnal) used to get system infos, ie server date, etc...
		//$this->Debug('Infos',$this->infos);
	}


	//----------------------------------------------------------------------------------
	function ApiListDevices(){
		if($this->ApiFetch('list','device')){
			$rooms_list = $this->GetResult(false);
			foreach($rooms_list as $room_k 	=> $devices){
				foreach($devices as $dev_k => $dev){
					if(!is_array($dev)){continue;}
					$d=array();
					$d['raw']		=$dev;	
					$d['name']		=$dev['name'];
					$d['address']	="{$dev['location']}/{$dev['device']}";
					
					//guess types
					if(isset($dev['temperature']) and isset($dev['humidity'])){
						$d['class']	="sensor";
						
						$d['type']	="hygro";
						$d['value']	=(float) $dev['humidity'];
						$this->RegisterDevice($d);
						
						//we register a second device 
						$d['type']	="temp";
						$d['value']	=(float) $dev['temperature'];
					}
					elseif(isset($dev['temperature'])){
						$d['class']	="sensor";
						$d['type']	="temp";
						$d['value']	=(float) $dev['temperature'];
					}
					elseif(isset($dev['dimlevel'])){
						$d['class']	="command";
						$d['type']	="dim_level";
						$d['value']	=(float) $dev['dimlevel'];
						$d['raw_value1']= $dev['state']; //raw_value1 with be transformed to clean 'state'
					}
					elseif(isset($dev['state'])){
						$d['class']	="command";
						$d['type']	="switch";
						$d['raw_value1']= $dev['state']; //raw_value1 with be transformed to clean 'state'
					}
					$this->RegisterDevice($d);
				}
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
	//commands	: 'set' | 'list' (list is used only in your own ApiListDevices)
	//Set types : 'switch' | 'dimmer' | 'dim_level'
	//address 	: (unique address of device, in your case this is "location/device")
	//states 	: 'on' | 'off' | number (for dim_level)
	function ApiFetchCustom($command, $type='', $address='',$state=''){
		$mess='';
		if(isset($this->vars['messages'][$command][$type])){
			$arr=$this->vars['messages'][$command][$type];
			
			if($address){
				//translate address back into $location,$device
				list($location,$device)=explode('/',$address);
				$arr['code']['location']=$location;
				$arr['code']['device']	=$device;
				
				if($type=='switch'){
					$arr['code']['state']=$state;
				}
				elseif($type=='dim_level'){
					//$arr['code']['state']=$state; //how should it be? unset? empty ? (off if 0, else on) ? anything else?
					$arr['code']['values']['dimlevel']=$state;
				}
			}
			$mess=urlencode(json_encode($arr));
		}
		$url=$this->vars['urls']['api'].$this->vars['actions'][$command].$mess;
		$this->api_url=urldecode($url); // just for debug

		$out=file_get_contents($url);			
	
		//status -------------
		if($command=='list'){
			$out=json_decode($out,true);
			if(is_array($out)){
				$this->api_status=true;
			}
			else{
				$this->api_status=false;
			}
		}
		elseif($command=='set'){
			//here you should check if the answer is true or false using : json answer? http status ? text answer ?
			$this->api_status=true;	
		}
		return $out;	
	}
}
?>
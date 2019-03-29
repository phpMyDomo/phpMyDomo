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
				elseif($raw['Type']=='UV'){
					$d['class']	='sensor';
					$d['type']	='uv';
					$d['value']	=$raw['UVI'];
					$d['unit']	="UVI";
				}
				elseif($raw['Type']=='General' and $raw['SubType'] =='Text' ){
					$d['class']	='sensor';
					$d['type']	='text';
					$d['value']	=$raw['Data'];
				}
				elseif($raw['Type']=='General' and $raw['SubType'] =='Alert' ){
					$d['class']	='sensor';
					$d['type']	='text';
					$d['value']	=$raw['Data'];
					$d['state']	=$raw['Level'];
				}
				elseif($raw['Type']=='General' and $raw['SubType'] =='Solar Radiation' ){
					$d['class']	='sensor';
					$d['type']	='radiation';
					$d['value']	=$raw['Radiation'];
					$d['unit']	="W/m2";
				}
				elseif($raw['Type']=='General' and $raw['SubType'] =='Visibility' ){
					$d['class']	='sensor';
					$d['type']	='visibility';
					$d['value']	=$raw['Visibility'];
					$d['unit']	="Km";
				}
				elseif($raw['Type']=='General' and $raw['SubType'] =='Distance' ){
					$d['class']	='sensor';
					$d['type']	='distance';
					list($v,$u)=explode(" ",$raw['Data']);
					$d['value']	=trim($v);
					$d['unit']	=trim($u);
				}
				elseif($raw['Type']=='General' and $raw['SubType'] =='Custom Sensor' ){
					$d['class']	='sensor';
					$d['type']	='custom';
					list($v,$u)=explode(" ",$raw['Data']);
					$d['value']	=trim($v);
					$d['unit']	=trim($u);
				}
				elseif($raw['Type']=='Lux'){
					$d['class']	='sensor';
					$d['type']	='lux';
					list($v,$u)=explode(" ",$raw['Data']);
					$d['value']	=trim($v);
					$d['unit']	=trim(strtolower($u));
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
					
					$d['type']	='counter';
					$d['value']	=(float) preg_replace('#[^0-9\.]+#','',$raw['CounterToday']);
					$d['unit']	="kWh";
					$this->RegisterDevice($d,'today');

					$d['type']	='consum';
					$d['value']	=(float) preg_replace('#[^0-9\.]+#','',$raw['Usage']);
					$d['unit']	="W";
					$this->RegisterDevice($d,'now');

					$d['type']	='counter';
					$d['value']	=(float) $raw['Counter'];
					$d['unit']	="kWh";
				}
				elseif($raw['Type']=='P1 Smart Meter' AND $raw['SubType']=='Energy'){
					$d['class']	='sensor';
					
					$d['type']	='counter';
					$d['value']	=(float) preg_replace('#[^0-9\.]+#','',$raw['CounterToday']);
					$d['unit']	="kWh";
					$this->RegisterDevice($d,'today');

					$d['type']	='consum';
					$d['value']	=(float) preg_replace('#[^0-9\.]+#','',$raw['Usage']);
					$d['unit']	="W";
					$this->RegisterDevice($d,'now');

					$d['type']	='counter';
					$d['value']	=(float) $raw['Counter'];
					$d['unit']	="kWh";
				}
				elseif($raw['Type']=='P1 Smart Meter' AND $raw['SubType']=='Gas'){
					$d['class']	='sensor';
					
					$d['type']	='counter';
					$d['value']	=(float) preg_replace('#[^0-9\.]+#','',$raw['CounterToday']);
					$d['unit']	="m3";
					$this->RegisterDevice($d,'today');

					//$d['type']	='consum';
					//$d['value']	=(float) preg_replace('#[^0-9\.]+#','',$raw['Usage']);
					//$d['unit']	="m3";
					//$this->RegisterDevice($d,'now');

					$d['type']	='counter';
					$d['value']	=(float) $raw['Counter'];
					$d['unit']	="m3";
				}
				
				elseif($raw['SwitchType']=='Motion Sensor'){
					$d['class']	='sensor';
					$d['type']	='pir';
				}
				elseif($raw['SwitchType']=='On/Off' and $raw['SubType']=='RGB'){
					//$d['class']	='unknown';
					//$d['type']	='unknown';
					//$d['value']	=$raw['LevelInt'];

				}
				elseif($raw['SwitchType']=='On/Off'){
					$d['class']	='command';
					$d['type']	='switch';
				}
				elseif($raw['SwitchType']=='Push On Button'){
					$d['class']	='command';
					$d['type']	='push';
					$d['state']	='off';
				}
				elseif($raw['SwitchType']=='Push Off Button'){
					$d['class']	='command';
					$d['type']	='push';
					$d['state']	='on';
				}
				elseif($raw['SwitchType']=='Dimmer'){
					$d['class']	='command';
					$d['type']	='dimmer';
					$d['value']	=$raw['LevelInt'];

					if($raw['SubType']=='RGB'){	//$raw['Type']=='Color Switch'
						$d['type']		='rgb';
						$raw_color		=json_decode($raw['Color'],true);
						//$d['raw_color']	=$raw_color;
						$d['color_rgb']=$this->_MaxHexColor($raw_color, $raw['LevelInt']);
					}
					elseif($raw['SubType']=='RGBW'){
						$d['type']		='rgbw';
						$raw_color		=json_decode($raw['Color'],true);
						//$d['raw_color']	=$raw_color;
						$d['color_rgb']=$this->_MaxHexColor($raw_color, $raw['LevelInt']);
						
					}

				}
				elseif($raw['SwitchType']=='Contact'){
					$d['class']	='sensor';
					$d['type']	='bool';
				}
				elseif($raw['SwitchType']=='Door Contact'){
					$d['class']	='sensor';
					$d['type']	='door';
				}
				elseif($raw['SwitchType']=='Media Player'){
					$d['class']	='sensor';
					$d['type']	='mediaplayer';
					$d['value']	=$raw['Data'];
					$d['state']	='off';
					$d['value'] and $d['state']	='on';
				}
				elseif($raw['SwitchType']=='Blinds'){
					$d['class']	='command';
					$d['type']	='blinds';
					$d['value']	=$raw['LevelInt'];
				}
				elseif($raw['SwitchType']=='Blinds Inverted'){
					$d['class']		='command';
					$d['type']		='blinds';
					$d['value']		=$raw['LevelInt'];
					$d['invert_set']=true;
				}
				elseif($raw['SwitchType']=='Selector'){
					$d['class']	='command';
					$d['type']	='selector';
					$d['value']	=$raw['LevelInt'];
					//set (pseudo) state
					$d['state']='off';

					//create choice
					if(!preg_match('#\|#',$raw['LevelNames'])){
						$raw['LevelActions']=base64_decode($raw['LevelActions']);
						$raw['LevelNames']	=base64_decode($raw['LevelNames']);
					}					
					$choices=explode('|',$raw['LevelNames']);

					if(is_array($choices)){
						$i=0;
						foreach($choices as $choice){
							$d['choices'][$i]=$choice;
							$i +=10;
						}
					}
				}

				$this->RegisterDevice($d);
			}
			//$this->Debug('Devices',$this->devices);
		}
		else{
			if($this->debug){
				$this->o_kernel->PageError('500',"Failed to contact server at {$this->api_url} ");
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
				$this->o_kernel->PageError('500',"Failed to contact server at {$this->api_url} ");
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

	//----------------------------------------------------------------------------------
	private function _MaxHexColor($color, $perc) {
		$color['r']=round($color['r'] * $perc /100);
		$color['g']=round($color['g'] * $perc /100);
		$color['b']=round($color['b'] * $perc /100);
 		//return dechex(($color['r']<<16)|($color['g']<<8)|$color['b']);
		return sprintf("%02x%02x%02x", $color['r'], $color['g'], $color['b']);
	}


	
} 
?>
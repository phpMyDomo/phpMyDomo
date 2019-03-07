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

class PMD_Action extends PMD_Root_Action{

	private $timeout	=1;
	private $sticky		=false;
	private $priority	='normal';
	private $group		='Notifications';
	private $gapp_icon	='';
	private $o_growl;
	private $log=array();
	private $do_register=true;

	//----------------------------------------------------------------------------------
	private function _paramToArray($str,$count=0,$default=''){
		strlen($str) or $str= $default;
		$arr=explode(',',$str);
		$out=array();
		for ($i = 0; $i < $count; $i++) {
			$value=$arr[$i];
			if(!strlen($value)){
				$value=$default;
			}
			$out[$i]=$value;
		} 
		return $out;
	}

	//----------------------------------------------------------------------------------
	function Run(){
		require_once($this->conf['libs']['growl_atoload']);
		//set_include_path(get_include_path() . PATH_SEPARATOR . $this->conf['paths']['vendors'].'PEAR_Net_Growl');

		$this->app_icon	=$this->conf['urls']['static'].'/global/img/app_icon128.png';
		$this->app_icon	='http://domo.lo.lo/static/global/img/app_icon128.png';

		$p['app_icon']=$this->GetParam('app_icon'	,'str')	or $p['app_icon']	=$this->app_icon;
		$p['app_name']=$this->GetParam('app_name'	,'raw')	or $p['app_name']  	= $this->conf['app']['name'];
		
		$p['hosts']			=$this->GetParam('hosts'		,'raw');
		$p['protocol']		=$this->GetParam('protocol'		,'str');
		$p['pass']			=$this->GetParam('pass'			,'raw');

		$groups			= $this->GetParam('groups'		,'raw');
		$a_groups 		= explode(',', $groups);
		$count_groups	= count($a_groups);
		$p['groups']	=$this->_paramToArray($groups, $count_groups, $this->group);
		
		$p['titles']	=$this->_paramToArray($this->GetParam('title',		'raw') , $count_groups,		'');
		$p['messages']	=$this->_paramToArray($this->GetParam('message',	'raw') , $count_groups,		'');
		$p['icons']		=$this->_paramToArray($this->GetParam('icon',		'str') , $count_groups,		$this->icon);
		$p['prioritys']	=$this->_paramToArray($this->GetParam('priority',	'str') , $count_groups,		$this->priority);
		$p['stickys']	=$this->_paramToArray($this->GetParam('sticky',		'bool') , $count_groups,	$this->sticky);

		$p['icon_url']		=$this->GetParam('icon_url'		,'str');
		$p['custom']		=$this->GetParam('custom'		,'raw');
		$p['message']		=str_replace('{custom}',$p['custom,'], $p['message']);
		

		//$this->do_register=$this->GetParam('register'	,'bool');

		$result='';
		if($p['hosts'] and $p['protocol']  and count($p['titles'])){
			$hosts_arr=explode(',',$p['hosts']);
			foreach($hosts_arr as $k => $host){
				$p['host']=trim($host);
				if($p['protocol']=='both'){
					if($this->_Register($p,'udp')){
						$this->_PublishGroups($p);
					}
					elseif($this->_Register($p,'gntp')){
						$this->_PublishGroups($p);
					}
				}
				else{
					if($this->_Register($p,$p['protocol'])){
						$this->_PublishGroups($p);
					}
				}
			}
			
			// make answer ------------------------------------------
			$out=false;
			$count_publish	=count($this->log['ok_publish']);
			$count_hosts	=count($hosts_arr);
			
			if($count_publish==0){
				$data=array('code'=>404, 'message'=> 'Impossible to reach any host!');
				$out=false;
			}
			else{
				if( $count_publish == $count_hosts){
					$data=array('code'=>200, 'message'=> "Notified ALL $count_hosts hosts.");
					$out=true;
				}
				else{
					$data=array('code'=>201, 'message'=> "Notified ONLY $count_publish from $count_hosts total hosts.");
					$out=true;
				}			
			}
			
			//make logs --------------------------------------------------
			if(isset($this->log['ok_register'])){
				$logs=array();
				foreach($this->log['ok_register'] as $k => $l){
					$logs[$k]['registration']=$l;
					$this->log['ok_publish'][$k] and $logs[$k]['publications']=$this->log['ok_publish'][$k];
				}
				$data['logs_ok']=$logs;
			}
			
			if(isset($this->log['err_register'])){
				$logs=array();
				foreach($this->log['err_register'] as $k => $l){
					$logs[$k]['registration']=$l;
					//$this->log['err_publish'][$k] and $logs[$k]['publications']=$this->log['err_publish'][$k];
				}
				$data['logs_errors']=$logs;
			}
			$this->DisplayJson($out,$data);
		}
		else{
			$this->DisplayJson(false, array('code'=>500, 'message'=>"Missing some parameters!"));
		}
	}
	
	//----------------------------------------------------------------------------------
	private function _Register($p, $protocol='udp'){

		foreach($p['groups'] as $k => $group){
			$group 			or	$group=$this->group;
			$g_name	=$group	or	$g_name=$this->group;
			$notifications[$group] = array(
				'display'		=> $g_name,
				'enabled'		=> 1
			);
		}
		
		$options  = array(
		    'host'		=> $p['host'],
		    'protocol'	=> $protocol,
			'AppIcon'	=> $p['app_icon'],
		    'timeout'	=> $this->timeout,
//			'debug'		=> '/tmp/netgrowl.log',
		);

		try {
		    $this->o_growl = Net_Growl::singleton($p['app_name'], $notifications, $p['pass'], $options);
			if($this->do_register){
		    	$this->o_growl->register();
				$this->log['ok_register'][$p['host']]['log']="Registered $protocol {$p['host']}";
				$this->log['ok_register'][$p['host']]['options']		=$options;
				$this->log['ok_register'][$p['host']]['groups'] =$notifications;
			}
			else{
				$this->log['ok_register'][$p['host']]['log']="Instancied $protocol {$p['host']}";
			}
			return true;
		} 
		catch (Net_Growl_Exception $e) {
		    $this->o_growl->reset();
			$this->log['err_register'][$p['host']]['log']=" - ERROR while registering $protocol {$p['host']} : ".$e->getMessage() ;
		}
		$this->log['err_register'][$p['host']]['options']		=$options;
		$this->log['err_register'][$p['host']]['notifications'] =$notifications;
	}

	//----------------------------------------------------------------------------------
	private function _PublishGroups($p){
		$groups=$p['groups'];
		if(is_array($groups)){
			foreach($groups as $k => $group){
				$this->_Publish($p,$k);
			}
		}
	}

	
	//----------------------------------------------------------------------------------
	private function _Publish($p,$k=0){
		$p['message']	=$p['messages'][$k];
		$p['title']		=$p['titles'][$k];
		$p['sticky']	=$p['stickys'][$k]					or $p['sticky']		=$this->sticky;
		$p['icon']		=$p['icon_url'] . $p['icons'][$k]	or $p['icon']		=$this->icon;
		$p['priority']	=$p['prioritys'][$k]				or $p['priority']	=$this->priority;
		
		$priority 	=constant('Net_Growl::PRIORITY_'.strtoupper($p['priority']));
		$options     = array(
			'priority'	=> $priority,
			'sticky'	=> $p['sticky'],
			'icon'		=> $p['icon']
		);
		$group=$p['groups'][$k]	or $group=$this->group;

		if($p['title']==''){
			$this->log['ok_publish'][$p['host']][$k]['log']=" - Cancel group '{$group}' : title is empty !";
			return false;
		}
		
		try {
		    $this->o_growl->publish($group, $p['title'], $p['message'], $options);
		    $this->o_growl->reset();
			$this->log['ok_publish'][$p['host']][$k]['log']=" - Published to '{$group}': \"{$p['title']}\"";
			$options['title']	=$p['title'];
			$options['message']=$p['message'];
			$this->log['ok_publish'][$p['host']][$k]['sent']=$options;

			return true;
		} 
		catch (Net_Growl_Exception $e) {
		    $this->o_growl->reset();
			$this->log['ok_publish'][$p['host']][$k]['log']=" - Error while Publishing to '{$group}' : ".$e->getMessage() ;
		}
	}


	
}
?>
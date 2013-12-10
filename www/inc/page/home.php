<?php
class PMD_Page extends PMD_Root_Page{

	//----------------------------------------------------------------------------------
	function Run(){
		$data=array();
		$data['devices']	=$this->o_api->GetDevices();
		$data['commands']	=$this->o_api->GetCommands();
		$data['infos']		=$this->o_api->GetInfos();
		//$this->Debug('',$data);
		$this->Assign('data',$data);
		$this->_checkNewVersion();
		$this->Display();
	}
	
	//----------------------------------------------------------------------------------
	private function _checkNewVersion(){
		$cache_last_version=$this->conf['paths']['caches'].'last_version';
		$cache_duration=3600*8;
		if(!$this->conf['app']['last_version'] or filemtime($cache_last_version) < ( time() - $cache_duration) ){
			$url="{$this->conf['urls']['pmd_api']}version&version={$this->conf['app']['version']}&api={$this->conf['app']['api']}";
			if($json=@file_get_contents($url)){
				$arr=json_decode($json,true);
				if(is_array($arr) and $arr['result'] and $arr['version']){
					file_put_contents($cache_last_version,$arr['version']);
				}
			}
		}
	}

} 
?>
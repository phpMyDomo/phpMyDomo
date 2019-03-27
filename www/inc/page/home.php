<?php
class PMD_Page extends PMD_Root_Page{

	//----------------------------------------------------------------------------------
	function Run(){
		$this->LoadApiClient();

		$data=array();
		$data['devices']	=$this->o_api->GetDevices();
		$data['commands']	=$this->o_api->GetCommands();
		$data['infos']		=$this->o_api->GetInfos();
		//$this->Debug('',$data);
		
		//screensaver
		if($this->conf['app']['screensaver_mode']=='clock'){
			$this->conf['app']['screensaver_url']=$this->conf['urls']['ss_clock'].'?back=1';
		}
		elseif($this->conf['app']['screensaver_mode']=='photoframe'){
			if($this->conf['app']['screensaver_pf_album']){
				$this->conf['app']['screensaver_url']=$this->conf['urls']['ss_pf_album'].$this->conf['app']['screensaver_pf_album'].'&back=1';
			}
		}

		$this->Assign('data',$data);
		$this->_checkNewVersion();
		$this->Display();
	}
	
	//----------------------------------------------------------------------------------
	private function _checkNewVersion(){
		$cache_last_version=$this->conf['paths']['caches'].'last_version';
		$cache_duration=3600*8;
		if(!$this->conf['app']['last_version'] or filemtime($cache_last_version) < ( time() - $cache_duration) or isset($_GET['update']) ){
			$os		=urlencode(php_uname('s'));
			$arch	=urlencode(php_uname('m'));
			$url="{$this->conf['urls']['pmd_api']}version&version={$this->conf['app']['version']}&api={$this->conf['app']['api']}&os=$os&arch=$arch";
			if($json=@file_get_contents($url)){
				$arr=json_decode($json,true);
				if(is_array($arr) and $arr['result'] and $arr['version']){
					file_put_contents($cache_last_version,$arr['version']);
					if( $this->conf['app']['version'] != $arr['version'] and isset($_GET['update'])){
						header("location: {$this->conf['urls']['www']}/utils/update");
						exit;
					}
					// custom api
					if($arr['custom_client_api']){
						$this->conf['app']['custom_client_api']=1;
					}					
				}
			}
		}
	}

} 
?>
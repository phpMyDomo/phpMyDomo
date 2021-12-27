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

		// build page 
		$cur_page=$this->conf['app']['page'] ;
		$page=array();
		$page['groups_shown']=$this->conf['pages'][$cur_page]['groups'];
		$page['blocks_shown']=$this->conf['pages'][$cur_page]['blocks'];
		if($cur_page == 'home'){
			$page['groups_shown'] or $page['groups_shown']= array_keys($this->conf['groups']);
			$page['blocks_shown'] or $page['blocks_shown']= array_keys($this->conf['blocks']);
		}
		else{
			$this->conf['pages'][$cur_page]['title'] and $page['title'] = $this->conf['pages'][$cur_page]['title'];
			$page['template']='home';
		}

		$this->_checkNewVersion();
		$this->Display($page);
	}

	//----------------------------------------------------------------------------------
	private function _fetchUrlContent($url){
		//return @file_get_contents($url);
		//usefull, when no internet connection, to prevent waiting for the long default timeout
		$timeout=5;	
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$result=curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	//----------------------------------------------------------------------------------
	private function _checkNewVersion(){
		$cache_last_version=$this->conf['paths']['caches'].'last_version';
		$cache_duration=3600*8;
		if(!$this->conf['app']['last_version'] or filemtime($cache_last_version) < ( time() - $cache_duration) or isset($_GET['update']) ){
			$os		=urlencode(php_uname('s'));
			$arch	=urlencode(php_uname('m'));
			$url="{$this->conf['urls']['pmd_api']}version&version={$this->conf['app']['version']}&api={$this->conf['app']['api']}&os=$os&arch=$arch";
			if($json=$this->_fetchUrlContent($url)){
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
			else{
				//do not recheck before 4h (usefull, when no internet connection)
				touch($cache_last_version, time() - 3600*4); 
			}
		}
	}

} 
?>
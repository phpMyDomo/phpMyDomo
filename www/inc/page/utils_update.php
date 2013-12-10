<?php
class PMD_Page extends PMD_Root_Page{


	//----------------------------------------------------------------------------------
	function Run(){
		$data=array();
		$data['old_version']	=file_get_contents($this->conf['paths']['caches'].'version');
		$data['new_version']	=$this->conf['app']['version'];
		$data['dl_version']		=$this->conf['app']['dl_version'];
		$data['paths']			=$this->conf['paths'];
		$data['step']			=$_GET['step'];
		$data['refresh']		=6;
		$step=$data['step'];
		if($step==1){
			//first reset cache
			header("location: {$this->conf['urls']['www']}/utils/reset?redirect");
		}
		elseif($step==2){
			//then here we will make update depending on version, ie mysql update
			
			$data['title']="Saving Version";
			file_put_contents($this->conf['paths']['caches'].'version',$this->conf['app']['version']);
			$data['text']="Saved version {$data['new_version']} !";
		}
		elseif($step==3){
			// this is finished
			$data['step']='done';
		}
		elseif($data['old_version'] == $data['new_version'] and ! $data['dl_version'] ){
			$data['step']='done';
			$data['text']="You've already performed the update";
		}
		else{
			$file='';
			// step 0 : Ask to update
			if($data['dl_version']){
				$file=$this->conf['urls']['pmd_changelog'];
			}
			$data['changelog']=$this->_GetChangelog($data['old_version'],$file);
		}
		$this->Assign('data',$data);
		$this->Display();
	}

	//----------------------------------------------------------------------------------
	private function _GetChangelog($last_version,$file=''){
		$last_version=(float) $last_version;
		$out=array();
		$file or $file=$this->conf['paths']['confs'].'changelog.md';
		$lines=file($file);
		foreach($lines as $line){
			if(preg_match('/^[#]+[^0-9]*([0-9\.]+)(.*)/',$line,$m)){
				if($last_version < $m[1]){
					$version=$m[1];
					$out[$version]['title']=$m[2];
				}
				else{
					break;
				}
				continue;
			}
			if(preg_match('/^-.*(new|fix|dev)\w*:\w*(.*)/',$line,$m)){
				$out[$version]['lines'][$m[1]][]=$m[2];
			}
			
		}
		return $out;
	}

} 
?>
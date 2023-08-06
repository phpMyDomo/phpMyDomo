<?php
class PMD_Page extends PMD_Root_Page{

	//----------------------------------------------------------------------------------
	function Run(){
		$missing_lib=1;
		if($this->conf['app']['photoframe']){
			$lib=$this->conf['libs']['photoframe'];
			if(file_exists($lib)){
				require_once($this->conf['libs']['root_photoframe']);
				require_once($lib);
				$o=new PMD_PhotoFrame($this->o_kernel);
				$missing_lib=0;
			}
		}
		if($missing_lib){
			$this->o_kernel->PageError('500',"Undefined of missing PhotoFrame library!");
		}

		$data=array();
		//photoframe mode -------------------------------
		if(isset($_GET['id'])){
			$id=$_GET['id'];

			$albums				=$o->ListAlbums();
			$data['albums']		=$this->PopupFromTitles($albums);
			$data['selected']	=urlencode($id);
			$data['prefs']		=$o->GetPreferences();

			$cur_album=$albums[$data['selected']];
			$page['title']		=ucfirst($cur_album['title'])." - ".$this->lang['global']['menu_head']['photos'];
						
			if(isset($_GET['back'])){
				$page['url_back']=$this->conf['urls']['home'];
			}
			else{
				$page['url_back']="?selected={$data['selected']}#a_{$data['selected']}";
			}

			$photos			=$o->ListPhotos($id);

			if($data['prefs']['random']){
				$this->shuffle_assoc($photos);
			}
			$data['photos']		=$photos;

			$this->Assign('data',$data);

			$this->SetHeader('css/supersized.css','css_global');
			$this->SetHeader('js/jquery.easing.min.js',		'js_global');
			$this->SetHeader('js/supersized.3.2.7.js',	'js_global');
			$this->SetHeader('js/supersized.shutter.js','js_global');
			$this->SetHeadJavascript("var pmd_dirs_supersized_img='{$this->conf['urls']['static']}/global/img/supersized/';");
			
			$this->conf['app']['page']='photoframe';
		}
		//album mode -------------------------------
		else{
			$albums=$o->ListAlbums();
			$data=$this->_paginate($albums);
			if(isset($_GET['selected'])){
				$data['selected']=$_GET['selected'];
			}
			$this->Assign('data',$data);
		}
		$this->Display($page);
	}

	//----------------------------------------------------------------------------------
	private function _paginate($array,$max=50) {
		$p=1; 
		if(isset($_GET['p'])){
			$p=intval($_GET['p']);
		}
		$x=($p - 1)*$max;
		$out['rows']			=array_slice($array,$x,$max,true);
		$out['current_page']	=$p;
		$out['pages']			=ceil(count($array) / $max);
		return $out;
	}

	//----------------------------------------------------------------------------------
	private function shuffle_assoc(&$array) {
		$keys = array_keys($array);

		shuffle($keys);

		foreach($keys as $key) {
			$new[$key] = $array[$key];
		}
		$array = $new;
		return true;
	}

	//----------------------------------------------------------------------------------
	private function PopupFromTitles($array) {
		foreach($array as $id=>$arr){
			$out[$id]=$arr['title'];
		}
		return $out;
	}



} 
?>
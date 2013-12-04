<?php
class PMD_Page extends PMD_Root_Page{

	//----------------------------------------------------------------------------------
	function Run(){
		isset($_GET['mode'])	and $mode	=$_GET['mode'];
		
		if($mode=='set'){
			$this->_modeSet();
		}
		
		$this->_jsonError();
	}

	//----------------------------------------------------------------------------------
	private function _modeSet(){
		isset($_GET['i'])	and $id			=$_GET['i'];
		isset($_GET['v'])	and $val		=$_GET['v'];
		isset($_GET['t'])	and $type		=$_GET['t'];
		if($id and strlen($val)){
			$type or $type='device'; // or scene
			if($this->o_api->ApiFetch('set', $type, $id , $val)){
				$this->_jsonOk();
				exit;
			}
			$this->_jsonError();
		}
	}

	//----------------------------------------------------------------------------------
	private function _jsonOk($arr=array()){
		$arr['status'] or $arr['status']='ok';
		echo json_encode($arr);
	}

	//----------------------------------------------------------------------------------
	private function _jsonError($exit=1){
		$arr=array();
		$arr['status']='err';
		echo json_encode($arr);
		if($exit){
			exit;
		}
	}	
} 
?>
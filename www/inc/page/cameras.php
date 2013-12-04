<?php
class PMD_Page extends PMD_Root_Page{

	//----------------------------------------------------------------------------------
	function Run(){
		$data=array();
		$data['cameras']	=$this->o_api->GetCameras();
		//$this->Debug('',$data);
		$this->Assign('data',$data);
		$this->Display();
	}

} 
?>
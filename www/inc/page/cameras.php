<?php
class PMD_Page extends PMD_Root_Page{

	private $height=240;

	//----------------------------------------------------------------------------------
	function Run(){
		$data=array();

		$cameras	=$this->o_api->GetCameras();
		$data['cameras']=$cameras;

		if(isset($_GET['id']) and $id=$_GET['id']){
			$data['selected']=$cameras[$id];
		}

		$this->Assign('data',$data);
		$this->Display();
	}

} 
?>
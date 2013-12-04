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
		$this->Display();
	}

} 
?>
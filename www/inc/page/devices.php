<?php
class PMD_Page extends PMD_Root_Page{

	//----------------------------------------------------------------------------------
	function Run(){
		$data=array();
		$data['rows']	=$this->o_api->GetDevices('','',array('lang_class' => SORT_ASC, 'name' => SORT_ASC , 'lang_type' => SORT_ASC  ));
		if(isset($_GET['dump'])){
			$this->Debug('Devices Dump',$data['rows']);
		}
		$this->Assign('data',$data);
		$this->Display();
	}

} 
?>
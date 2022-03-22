<?php
class PMD_Page extends PMD_Root_Page{

	//----------------------------------------------------------------------------------
	function Run(){
		$this->LoadApiClient();

		$data=array();
		if($data['rows']	=$this->o_api->GetDevices('','',array('lang_class' => SORT_ASC, 'name' => SORT_ASC , 'lang_type' => SORT_ASC  ))){
			foreach($data['rows'] as $k => $row){
				// --- html printable objet
				$data['rows'][$k]['f_object']=htmlspecialchars(print_r($row,true), ENT_QUOTES, 'UTF-8' ) ;
			}
		}

		if(isset($_GET['dump'])){
			$debug_arr['request_url']	=$this->o_api->api_url;
			$debug_arr['devices']		=$data['rows'];
			$this->Debug('Devices Dump',$debug_arr);
		}
		$this->Assign('data',$data);
		$this->DisplayAdmin();
	}

} 
?>
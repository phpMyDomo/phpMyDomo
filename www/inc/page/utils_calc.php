<?php
class PMD_Page extends PMD_Root_Page{

	//----------------------------------------------------------------------------------
	function Run(){
/*
		$data=array();
		$api=$this->o_api->GetDevices('','',array('lang_class' => SORT_ASC, 'name' => SORT_ASC , 'lang_type' => SORT_ASC  ));
		if(is_array($api)){
			foreach($api as $name => $row){

				if($row['type']=='switch' and $row['raw']['HardwareName']=="RFX usb"){
					$data['rows'][$name]				=$row;
					$data['rows'][$name]['house_code']	=chr($row['raw']['ID']);
					
				}
			}
			//$this->Debug('Devices Dump',$api);
		}
		//$data['rows']=$api;
		if(isset($_GET['dump'])){
			$this->Debug('Devices Dump',$data['rows']);
		}
		$this->Assign('data',$data);
*/
		$this->Display();
	}

} 
?>
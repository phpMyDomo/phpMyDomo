<?php
class PMD_Page extends PMD_Root_Page{

	//----------------------------------------------------------------------------------
	function Run(){
		$error=0;
		$message='';
		require_once($this->conf['paths']['libs'].'pmd_root_action.php');

		if(isset($_GET['type'])){
			$my_action=$_GET['type'];
			$my_file=$this->conf['paths']['actions']."$my_action/action.php";
			if(file_exists($my_file)){
				require_once($my_file);
				$object= new PMD_action($this->o_kernel,$my_action);
				$object->Run();
			}
			else{
				$error	=404;
				$message="Invalid '$my_action' Action Type : No file found at $my_file";
			}
		}
		else{
			$error=500;
			$message="Type is not set";
		}
		if($error){
			$object= new PMD_root_action($this->o_kernel,false);
			$data=array('code'=>$error,'message'=>$message);
			$object->DisplayJson(false,$data);
		}
	}
} 
?>
<?php
class PMD_Page extends PMD_Root_Page{

	//----------------------------------------------------------------------------------
	function Run(){
		isset($_GET['i'])	and $id			=$_GET['i'];
		isset($_GET['v'])	and $val		=$_GET['v'];
		isset($_GET['c'])	and $command	=$_GET['c'];
		isset($_GET['t'])	and $type		=$_GET['t'];
		
		if($id and strlen($val)){
			isset($command)		or $command="set";
			isset($type)		or $type="device";
			$out=$this->o_api->ApiFetch($command, $type, $id , $val);

			//toDO json answer
			
			if($out['status']=='OK'){
				echo "<h1>OK</h1>";
			}
			else{
				echo "<h1 style='color:red'>Error</h1>";
			}
			echo "$command / $type - ID:$id => $val <pre>";
			print_r($out);
		}

	}

	
} 
?>
<?php
class PMD_Page extends PMD_Root_Page{

	//----------------------------------------------------------------------------------
	function Run(){
		echo "<h3>Removing Cache Files</h3>";
		echo "<pre>";
		$this->_resetCache();
		echo "</pre>";
		echo "<hr>DONE!";
		sleep(2);
		if(isset($_GET['redirect'])){
			header("location: {$this->conf['urls']['www']}/utils/update?step=2");
		}
	}

	//----------------------------------------------------------------------------------
	private function _resetCache(){
		$this->rmdir_recurse($this->conf['paths']['caches'].'minify/');
		$this->rmdir_recurse($this->conf['paths']['caches'].'smarty_comp/');
		$this->rmdir_recurse($this->conf['paths']['caches'].'smarty_cache/');
	}

	//----------------------------------------------------------------------------------
	private function rmdir_recurse($path) {
	    $path = rtrim($path, '/').'/';
	    $handle = @opendir($path);
	    while(false !== ($file = @readdir($handle))) {
	        if($file != '.' and $file != '..' and $file != '.gitkeep' and $file !=".AppleDouble" ) {
	            $fullpath = $path.$file;
	            if(@is_dir($fullpath)) {
					$this->rmdir_recurse($fullpath); 
				}
				else{
					echo "$fullpath\n";
					unlink($fullpath);
				}
	        }
	    }
	    closedir($handle);
		//echo "rm $path\n";
	    //rmdir($path);
	}

} 
?>
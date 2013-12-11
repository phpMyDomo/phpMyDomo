<?
$dir=dirname(__FILE__);
if(!file_exists($dir.'/.htaccess')){
	echo "
	<h2>There is no .htaccess file in this diretory</h2>
	Please copy it from the phpMyDomo/www/.htaccess archive to <i>$dir/.htaccess</i>:
	<pre style='color:grey'>mv [ARCHIVE_PATH_OF_PMD]/phpMyDomo/www/.htaccess $dir/</pre>
	";
}
$current_dir=dirname($_SERVER['PHP_SELF']);
if($current_dir=='/'){$current_dir='';}
header("location: $current_dir/home");
?>

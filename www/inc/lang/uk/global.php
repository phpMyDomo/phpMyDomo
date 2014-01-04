<?php
//require en (us) file -------------------------------------------------
require_once(dirname(dirname(__FILE__)).'/en/'.basename(__FILE__));

//override some formats ------------------------------------------------
$lang['locale']="en_UK.utf8";

// dates formats ########################################################
//  date format according to : http://php.net/strftime
$lang['dates']['day']		="%A %b %e";
$lang['dates']['time']		="%H : %M";

?>
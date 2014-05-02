<?php
//#############################################################################
// Domogik API Configuration #######################################################
//#############################################################################

//Domogik DATABASE -----------------------------------------------------------------

// Mysql Server IP and host where Domogik Database is installed
/*
1) if PMD is located on the same host, you can leave this to 'lacalhost', and use the "domogik" user with has right to connect to the "domogik" database
2) if PMD is not located on the same host, you will need to make sure that:
- the mysql server authorizes external connections : in /etc/mysql/my.cnf , "bind-address" should be commented
- you have created a user who has the rights to connect to the domogik db from an external host, ie:
# mysql -u root -p
> GRANT ALL PRIVILEGES ON domogik.* TO 'domogik'@'%'  IDENTIFIED BY 'domopass'  WITH GRANT OPTION;

*/
$api['db']['host'] 	= 'localhost';

// port of the mysql server, default to 3306
$api['db']['port'] 	= '3306';

// user having access to the domogik database, default to "domogik"
$api['db']['user'] 	= 'domogik';

// user password , default to the one set during install. You can check this in /etc/domogik/domogik.cfg 
$api['db']['password'] = 'domopass';

// the domogik database, default to : domogik
$api['db']['database'] = 'domogik';

//Domogik Settings -----------------------------------------------------------------
// retrict sensors value to only show values if they where updated before x minutes.
// in some case, when a plugin crashes, sensor values are no longer updated. Setting this to ie 60, would show nothing in affected sensors, intead of showing a too old value.
$api['sensors_timeout']=0;

?>

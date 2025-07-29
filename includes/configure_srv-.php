<?php
define('HTTP_SERVER_PATH', 'http://10.164.213.91/');
define('DIR_PAGE', 'prod/manage_fgtag/');
define('DIR_VIEWS', 'views/');
define('DIR_MENU', 'menu/');
define('DIR_UPLOAD', 'uploads/');
define('DIR_BTW', 'btw/');
define('DIR_MPIC', 'picmodel/');

 
define('DB_SERVER', '10.164.213.27');
define('DB_SERVER_USERNAME', 'admin');
define('DB_SERVER_PASSWORD', 'password');

/*
define('DB_SERVER', 'localhost');
define('DB_SERVER_USERNAME', 'root');
define('DB_SERVER_PASSWORD', '1234');
*/

define('DB_DATABASE4', 'prod_fg_tag_line'); // prod_fg_tag

define('DB_DATABASE5', 'sign_on');
  
$con2 = mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD) or die( "Can't connect Database server, Please contacr Administrator.");
	
mysql_query("SET NAMES UTF8");
mysql_select_db(DB_DATABASE4, $con2)or die( mysql_error() );
mysql_select_db(DB_DATABASE5, $con2)or die( mysql_error() );
?>





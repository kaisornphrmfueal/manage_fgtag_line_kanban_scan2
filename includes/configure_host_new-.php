<?php

//define('HTTP_SERVER_TRUE', 'http://linuxapps.fttl.ten.fujitsu.com/');
//define('HTTP_SERVER', 'http://linuxapps.fttl.ten.fujitsu.com/');

//define('HTTP_SERVER_TRUE', 'http://10.164.213.91/');
//define('HTTP_SERVER', 'http://10.164.213.91/');


define('HTTP_SERVER_TRUE', '172.16.130.121/');//must be 10.164.213.51
define('HTTP_SERVER', '172.16.130.121/');

  define('SSO', 'sso/');
  define('DIR_PAGE', 'prod/manage_fgtag/');
  define('DIR_PARTH', '/htdocs/prod/manage_fgtag/');
  

  define('DIR_IMAGES', 'images/');
  define('DIR_CSS', 'css/');
  define('DIR_INCLUDES', 'includes/');
  define('DIR_FUNCTIONS', 'functions/');
  define('DIR_JAVA', 'javascript/');
  define('DIR_VIEWS', 'views/');
  define('DIR_MENU', 'menu/');
  define('DIR_UPLOAD', 'uploads/');
  define('DIR_BTW', 'btw/');
  define('DIR_MPIC', 'picmodel/');
  define('DIR_LINE', 'lines/');
  
 

  
  define('DB_DATABASE1', 'prod_fg_tag');
  define('DB_DATABASE3', 'target_board');
  define('DB_DATABASE2', 'rfid_project');
  define('DB_DATABASESSO', 'sign_on');
  define('USE_PCONNECT', 'false');
  define('STORE_SESSIONS', 'mysql');  
/*
  $ip = "10.164.213.27";
  $usermame = "admin";
  $password = "password";

*/
$ip = "172.16.130.121";
  $usermame = "root";
  $password = "1234";
$con = mysql_connect($ip, $usermame, $password) or die( mysql_error() );	 	
mysql_query("SET NAMES UTF8");
mysql_select_db(DB_DATABASE1, $con)or die( mysql_error() );
mysql_select_db(DB_DATABASE2, $con)or die( mysql_error() );
mysql_select_db(DB_DATABASE3, $con)or die( mysql_error() );
mysql_select_db(DB_DATABASESSO, $con)or die( mysql_error() );


$con_rfid = mysqli_connect($ip, $usermame, $password, "rfid_project") or die( mysqli_error() );
$con_fg_tag = mysqli_connect($ip, $usermame, $password, "prod_fg_tag") or die( mysqli_error() );


?>




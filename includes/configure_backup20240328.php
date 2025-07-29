<?php
/* SERVER HOST FOR BSI
define('HTTP_SERVER_TRUE', 'http://linuxapps.fttl.ten.fujitsu.com/');
define('HTTP_SERVER', 'http://linuxapps.fttl.ten.fujitsu.com/');

*/
define('HTTP_SERVER_TRUE', 'http://172.16.130.121/');//must be localhost
define('HTTP_SERVER', 'http://172.16.130.121/');



  define('SSO', 'sso/');
  define('DIR_PAGE', 'prod/manage_fgtag_line_kanban_scan/'); //manage_fgtag
  define('DIR_SRV_PAGE', 'prod/manage_fgtag_line_kanban_scan/'); //manage_fgtag
  define('DIR_PARTH', '/htdocs/prod/manage_fgtag_line_kanban_scan/');  //www/prod/manage_fgtag/


   define('SRV_SHARED', '10.164.213.32'); //SERVER SHARED FILE FOR BARTENDER
   define('SRV_TEMPLATE', 'eticket_template'); //SERVER SHARED FILE FOR BARTENDER
   

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
  define('DIR_PRINT', 'print/'); 
 

  
  define('DB_DATABASE1', 'prod_fg_tag');// prod_fg_tag_line
  define('DB_DATABASE2', 'rfid_project');
  define('DB_DATABASE4', 'ewi_packing');
  define('DB_DATABASESSO', 'sign_on');
  define('DB_DATABASE3', 'target_board');
  define('USE_PCONNECT', 'false');
  define('STORE_SESSIONS', 'mysql');   

/* FOR TEST SERVER
$con = mysql_connect("localhost", "root", "1234") or die( mysql_error() );	
*/	
/* FOR SERVER  */
$host = "10.164.213.51";
$user = "test121";
$password = "test121";

$con = mysql_connect($host, $user, $password) or die( mysql_error() );		

mysql_query("SET NAMES UTF8");
mysql_select_db(DB_DATABASE1, $con)or die( mysql_error() );
mysql_select_db(DB_DATABASE2, $con)or die( mysql_error() );
mysql_select_db(DB_DATABASESSO, $con)or die( mysql_error() );
//mysql_select_db(DB_DATABASE3, $con)or die( mysql_error() );


///Set Time Zone
date_default_timezone_set("Asia/Bangkok");

?>




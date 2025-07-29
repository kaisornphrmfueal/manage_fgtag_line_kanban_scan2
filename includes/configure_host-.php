<?php

define('HTTP_SERVER_TRUE', 'http://localhost/');
define('HTTP_SERVER', 'http://localhost/');

  define('SSO', 'sso/');
  define('DIR_PAGE', 'ticket_project/manage_fgtag_line/'); //ticket_project/manage_fgtag
  define('DIR_PARTH', '/htdocs/ticket_project/manage_fgtag_line/');  //www/prod/manage_fgtag/

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
  define('DB_DATABASESSO', 'sign_on');
  define('DB_DATABASE3', 'target_board');
  define('USE_PCONNECT', 'false');
  define('STORE_SESSIONS', 'mysql');   


$con = mysql_connect("localhost", "root", "1234") or die( mysql_error() );		
mysql_query("SET NAMES UTF8");
mysql_select_db(DB_DATABASE1, $con)or die( mysql_error() );
mysql_select_db(DB_DATABASESSO, $con)or die( mysql_error() );
mysql_select_db(DB_DATABASE3, $con)or die( mysql_error() );


?>




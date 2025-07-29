<?php
define('HTTP_SERVER_EWI', 'http://10.164.213.10/');//For link WEI
define('DIR_PAGE_EWI', 'qa/ewi-demo/public/imgs_wi/');
  
define('DB_SERVER_ORCL', '10.164.213.30'); 
define('DB_SERVER_ORCL_USERNAME', 'glodb');
define('DB_SERVER_ORCL_PASSWORD', 'glodb');

define('DB_DATABASE6', 'glodb');


$con3='(DESCRIPTION = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.164.213.30)(PORT = 1521))(CONNECT_DATA =(SERVER = DEDICATED) (SERVICE_NAME =ORCL )))';
$conn = oci_connect(DB_SERVER_ORCL_PASSWORD, DB_SERVER_ORCL_PASSWORD, $con3);
if (!$conn) {
    $e = oci_error();
   // trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
?>





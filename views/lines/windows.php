<?php 
error_reporting(0);
session_start();
date_default_timezone_set('Asia/Bangkok');
include('../../includes/configure.php');
include('../../includes/chk_session_line.php');
include('../../functions/function.php');
?>

<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Windown FG Tag</title>
<link rel="stylesheet" href="<?=HTTP_SERVER.DIR_PAGE.DIR_INCLUDES.DIR_CSS?>/windows.css" type="text/css"  charset="utf-8"/>
<link rel="stylesheet" href="<?=HTTP_SERVER.DIR_PAGE.DIR_INCLUDES.DIR_CSS?>/txt.css" type="text/css"  charset="utf-8"/>


<script type="text/javascript" src="<?=HTTP_SERVER.DIR_PAGE.DIR_JAVA?>all.js"></script>
<script type="text/javascript" src="<?=HTTP_SERVER.DIR_PAGE.DIR_JAVA?>java.js"></script>

</head>
<body>
<?php
		if($_GET['win']=="print"){
				require('win_printtag.php');
			}else if($_GET['win']=="reprint"){
				require('win_reprint.php');
			}else if($_GET['win']=="view_op"){
				 $gid=$_GET['idm'];
				require('windown_view_operator.php');
			}else if($_GET['win']=="view_opb"){
				 $gid=$_GET['idm'];
				require('../windown_view_operator.php');
				}else if($_GET['win']=="edit"){
				 $gid=$_GET['idm'];
				require('windown_edit_tag.php');
				}else if($_GET['win']=="confirm"){
				 $gid=$_GET['idm'];
				require('windows_confirm_adjust.php');
				}
			
			
	?>
    

</body>
</html>
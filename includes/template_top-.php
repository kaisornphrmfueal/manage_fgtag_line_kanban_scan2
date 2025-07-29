<?php session_start();
date_default_timezone_set('Asia/Bangkok');
include('../includes/configure_host.php');
include('../includes/chk_session.php');
include('../functions/function.php');
?>

<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>FG Transfer Tag RealTime Printing </title>
<? //HTTP_SERVER.DIR_PAGE ?>

<link href="<?=HTTP_SERVER.DIR_PAGE.DIR_INCLUDES.DIR_CSS?>style.css" rel="stylesheet" type="text/css" /> <!-- WEB -->
<link href="<?=HTTP_SERVER.DIR_PAGE.DIR_INCLUDES.DIR_CSS?>txt.css" rel="stylesheet" type="text/css" /> <!-- WEB -->
<!--  start menu -->
<link href="<?=HTTP_SERVER.DIR_PAGE.DIR_INCLUDES.DIR_CSS?>dropdown/dropdown.css" media="screen" rel="stylesheet" type="text/css" />
<link href="<?=HTTP_SERVER.DIR_PAGE.DIR_INCLUDES.DIR_CSS?>dropdown/dropdown.vertical.rtl.css" media="screen" rel="stylesheet" type="text/css" />
<link href="<?=HTTP_SERVER.DIR_PAGE.DIR_INCLUDES.DIR_CSS?>dropdown/default/default.ultimate.css" media="screen" rel="stylesheet" type="text/css" />
<!--  end menu -->

<script type="text/javascript" src="<?=HTTP_SERVER.DIR_PAGE.DIR_JAVA?>all.js"></script>
<script type="text/javascript" src="<?=HTTP_SERVER.DIR_PAGE.DIR_JAVA?>java.js"></script>
<script type="text/javascript" src="<?=HTTP_SERVER.DIR_PAGE.DIR_JAVA?>date-picker2.js"></script>

</head>
<body id="page1">
<div id="main">
<!-- HEADER -->
<div id="header">
		<div class="row-1">
			<div class="fleft"><a href="#"><img src="../<?=DIR_IMAGES?>logo.png" alt="" /></a></div>
			  
				       <!-- MENU -->
                       <div class="menu">
								<?  	require_once(DIR_MENU."menu.php");  ?>
                        </div>
            			<!-- MENU -->
            
				<div class="wellcome">Welcome, <? echo selectName($user_login) ;?> ||  <a href="logout.php">Logout</a> </div>

			
		</div>  

		<div class="row-3"></div>
	
	</div>
<!-- CONTENT -->
<div id="content">

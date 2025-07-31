<?php
session_start();
date_default_timezone_set('Asia/Bangkok');

include('../../configure.php');
include('../../chk_session_line.php');
include('../../../functions/function.php');
require_once 'function.php';
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>FG Transfer Tag RealTime Printing</title>

    <!-- CSS -->
    <link href="<?= HTTP_SERVER . DIR_PAGE . DIR_INCLUDES . DIR_CSS ?>style.css" rel="stylesheet" type="text/css" />
    <link href="<?= HTTP_SERVER . DIR_PAGE . DIR_INCLUDES . DIR_CSS ?>txt.css" rel="stylesheet" type="text/css" />
    <!-- Menu CSS -->
    <link href="<?= HTTP_SERVER . DIR_PAGE . DIR_INCLUDES . DIR_CSS ?>dropdown/dropdown.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="<?= HTTP_SERVER . DIR_PAGE . DIR_INCLUDES . DIR_CSS ?>dropdown/dropdown.vertical.rtl.css" media="screen" rel="stylesheet" type="text/css" />
    <link href="<?= HTTP_SERVER . DIR_PAGE . DIR_INCLUDES . DIR_CSS ?>dropdown/default/default.ultimate.css" media="screen" rel="stylesheet" type="text/css" />

    <!-- JS -->
    <script type="text/javascript" src="<?= HTTP_SERVER . DIR_PAGE . DIR_INCLUDES . DIR_JAVA ?>all.js"></script>
    <script type="text/javascript" src="<?= HTTP_SERVER . DIR_PAGE . DIR_INCLUDES . DIR_JAVA ?>java.js"></script>
    <script type="text/javascript" src="<?= HTTP_SERVER . DIR_PAGE . DIR_INCLUDES . DIR_JAVA ?>date-picker2.js"></script>

    <!-- Bootstrap -->
    <link href="bootstrap-5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
</head>
<body id="page1">
    <div id="main">
        <!-- HEADER -->
        <div id="header">
            <div class="row-1">
                <div class="fleft">
                    <a href="#"><img src="../../<?= DIR_IMAGES ?>logoline.png" alt="" /></a>
                </div>
                <!-- MENU -->
                <div class="menu">
                    <?php require_once('../../menu/menu_line.php'); ?>
                </div>
                <!-- MENU -->
                <div class="wellcome">
                    Welcome, <?php echo $top_name = selectLineName($user_login); ?> ||
                    <a href="logout.php">Logout</a>
                </div>
            </div>
            <div class="row-3"></div>
        </div>
        <h4 style="color: red;">=====> SERVER TEST <======</h4>
        <!-- CONTENT -->
        <div id="content">
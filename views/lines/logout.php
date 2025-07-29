<?php session_start();


unset( $_SESSION['user_ip']);
unset( $_SESSION['user_login']);


session_destroy();
$pathloguot=$_GET['gpath'];
	echo "<META HTTP-EQUIV=refresh CONTENT=\"1; URL=index_line.php?gbsi=$pathloguot\">";
	exit();

?>
<?php //session_start();


$user_ip=session_id();
$user_login=$_SESSION['user_login'];

if($user_ip<>session_id() or $user_login == "" ){
			
		echo "<META HTTP-EQUIV=refresh CONTENT=\"1; URL=".HTTP_SERVER.DIR_PAGE.DIR_VIEWS.DIR_LINE."index_line.php?gbsi=YnNpMjE4MjM=\">";
				exit();
}

?>

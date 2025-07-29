<?php session_start();
 include("includes/configure.php");

		if(!empty($_GET['log_i'])){
			$id=$_GET['log_i'];
 			$user_log=base64_decode($id);
		}
		$user_ip=session_id();
		@session_register('user_login');
		$_SESSION["user_login"] =$user_log;
		session_write_close();
		$user_login = $_SESSION["user_login"];
 
if(!empty($user_login)){	
				echo "<META HTTP-EQUIV=refresh CONTENT=\"1; URL=".HTTP_SERVER.DIR_PAGE.DIR_VIEWS."\">";
}else {
				echo "<META HTTP-EQUIV=refresh CONTENT=\"1; URL=".HTTP_SERVER.SSO."\">";
 		exit();
	}
?>
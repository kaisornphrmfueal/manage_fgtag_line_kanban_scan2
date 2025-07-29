<?php 
session_start();
 include("../../includes/configure.php");

		if(!empty($_GET['lid'])){
			$id=$_GET['lid'];
			$idp=$_GET['idm'];
			@$gidtg=$_GET['idgtg'];
 			$user_log=$id;
			$gline=$_GET['ln'];
		}

		//$_SESSION[user_ip]=session_id();
		$_SESSION['user_login']=$id; 
		
		//session_write_close();
		$user_login = $_SESSION["user_login"];

if(!empty($user_login)){	
				if($gline=='b'){
						 echo "<META HTTP-EQUIV=refresh CONTENT=\"1; URL=".HTTP_SERVER.DIR_PAGE.DIR_VIEWS.DIR_LINE."index_bsi.php?id=$idp&idtg=$gidtg\">";
					}else{
						 echo "<META HTTP-EQUIV=refresh CONTENT=\"1; URL=".HTTP_SERVER.DIR_PAGE.DIR_VIEWS.DIR_LINE."?id=$idp&idtg=$gidtg\">";
						}
				
}else {
				echo "<META HTTP-EQUIV=refresh CONTENT=\"1; URL=".HTTP_SERVER.DIR_PAGE.DIR_VIEWS.DIR_LINE."index_line.php\">";
 		exit();
	}
?>
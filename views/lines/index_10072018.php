<?php require('../../includes/template_topl.php'); ?>


<div class="body_resize"> 

<?php 
//echo "==".$user_login;

	if(!empty($_GET['id']) ){
		require(base64_decode($_GET['id']).".php"); 
	}else{
		 gotopage("index_line.php");
	}
		
		
			//require("imports.php"); 
		
	

?>
</div>

<?php require('../../includes/template_bottom.php'); ?>
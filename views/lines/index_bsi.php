<?php require('../../includes/template_top_bsi.php'); ?>
<?php
/*
function chkBrowser($nameBroser){
    return preg_match("/".$nameBroser."/",$_SERVER['HTTP_USER_AGENT']);
}
if(chkBrowser("Firefox")==1){
    echo "Browser Firefox.";// Firefox
}
*/
?>

<div class="body_resize"> 

<?php 
//echo "==".$user_login;

	if(!empty($_GET['id']) ){
		require(base64_decode($_GET['id']).".php"); 
	}else{

		 gotopage("index_bsi.php?id=".base64_encode('bsi_scan')." ");
	}
	
	

?>
</div>

<?php require('../../includes/template_bottom.php'); ?>
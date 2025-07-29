<?php require('../../includes/template_top_line.php'); ?>
<?php 
/*
// function for delete tag data scan serial = 0 
$sql_reset="DELETE FROM ".DB_DATABASE1.".fgt_srv_tag WHERE (sn_start = '' AND sn_end = '') OR (sn_start IS NULL AND sn_end IS NULL) AND  line_id = '$user_login' ";
$qr_reset=mysql_query($sql_reset);
if($qr_reset){
	//alert("Reset Tag");
	}
	
	*/

?>

<div class="body_resize"> 

<?php 
//echo "==".selectStatus($user_login);

		require("line.php"); 


?>
</div>

<?php require('../../includes/template_bottom.php'); ?>
<?
include('../../includes/configure.php');
include('../../includes/configure_srv.php');
	
		$sqltg = "SELECT MAX(a.id_tag)  AS mxtag
				FROM ".DB_DATABASE1.".fgt_tag  a
				WHERE   a.status_print in ( 'Printed','Reprinted') 
				AND upload_status = '0'";
 		$qrtg=mysql_query($sqltg,$con);
		$rstg=mysql_fetch_array($qrtg);
		$maxtag= $rstg['mxtag'];
		$today=date('Y-m-d H:i:s');

		 	$sqlck="SELECT id_tag,line_id,tag_no,id_model,model_kanban,shift,sn_start,sn_end,
					tag_qty,fg_tag_barcode,status_print,date_insert,date_print,who_reprint,date_reprint
					FROM ".DB_DATABASE1.".fgt_tag  a
					WHERE a.status_print in ('Printed','Reprinted') 
					AND a.upload_status = '0'
					AND  a.id_tag<='$maxtag' ";
			$qrck=mysql_query($sqlck,$con);
			if(mysql_num_rows($qrck)<>0){
				

			while($rsck=mysql_fetch_array($qrck)){ 
				$idtag=$rsck['id_tag'];
				$sqlint="INSERT INTO ".DB_DATABASE4.".fgt_srv_tag (line_id, tag_no,id_model,model_kanban,shift,sn_start,sn_end,tag_qty,fg_tag_barcode,
				status_print,date_insert,date_print,who_reprint,date_reprint,who_upload,date_upload)  
				VALUES	('".$rsck['line_id']."', '".$rsck['tag_no']."','".$rsck['id_model']."','".$rsck['model_kanban']."','".$rsck['shift']."',
				'".$rsck['sn_start']."','".$rsck['sn_end']."','".$rsck['tag_qty']."','".$rsck['fg_tag_barcode']."',
				'".$rsck['status_print']."','".$rsck['date_insert']."','".$rsck['date_print']."','".$rsck['who_reprint']."',
				'".$rsck['date_reprint']."','System','".$today."')
				ON DUPLICATE KEY UPDATE    line_id='".$rsck['line_id']."',	id_model='".$rsck['id_model']."', model_kanban='".$rsck['model_kanban']."', 
				shift='".$rsck['shift']."',	sn_start='".$rsck['sn_start']."', sn_end='".$rsck['sn_end']."', tag_qty='".$rsck['tag_qty']."',
					 fg_tag_barcode='".$rsck['fg_tag_barcode']."', status_print='".$rsck['status_print']."', date_insert='".$rsck['date_insert']."',
					date_print='".$rsck['date_print']."', who_reprint='".$rsck['who_reprint']."', date_reprint='".$rsck['date_reprint']."',
					 who_upload='System', date_upload='".$today."'";
				$qrint=mysql_query($sqlint,$con2);		
				if (!$qrint) {
					alert("Can't Upload data, Please try again");
					exit;
				}else{
					$sqlus="UPDATE ".DB_DATABASE1.".fgt_tag SET upload_status=1, who_upload='System', 	
					date_upload='$today',mxid_upload='$maxtag' WHERE id_tag=$idtag";
					mysql_query($sqlus,$con);
				}				
			}//while($rs=mysql_fetch_array($qrqrck)){ 
			
				mysql_query("INSERT INTO ".DB_DATABASE1.".fgt_update_tag SET last_tag_id='$maxtag', line_id='',
						  status_update='System', date_update='".$today."'",$con);
				 mysql_query("INSERT INTO ".DB_DATABASE4.".fgt_log_local_update_tag SET last_tag_id='$maxtag', line_id='',
						  status_update='System', date_update='".$today."'",$con2);
			
				}//		if(mysql_num_rows($qrck)<>0){

?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

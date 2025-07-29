<?php
include('../../includes/configure.php');
if(!empty($_GET["modelsr"]) && $_GET["modelsr"]<>"undefined"){
	$gserial=substr($_GET["modelsr"], -5); 
	$gtagno=$_GET["tgno"];
	$tagmodel=$_GET["trmodel"];
	
	//-----------------------Start Check Only A-Z,a-z,0-9 (เจออักขระพิเศษแล้วตัดทิ้ง แล้วไปเช็คต่อ) --------------
		$gserial8=substr($_GET["modelsr"], -8);
		$str_serial = preg_replace('/[^A-Za-z0-9-]/', '', $gserial8);
		$chk_8=strlen($str_serial);
	//----------------------End Check Only A-Z,a-z,0-9 ------------------------------------------------
	
	
	/*  $sqlcht="SELECT IFNULL(RIGHT(MAX(serial_scan_label), 6),'Null')  AS mxtag
				FROM  ".DB_DATABASE1.".fgt_serial 
				WHERE tag_no = '$gtagno' ";*/
				
		$sqlcht="SELECT IFNULL(RIGHT(MAX(b.serial_scan_label), 5),0)  AS mxtag
			FROM  ".DB_DATABASE1.".fgt_srv_tag  a  
            LEFT JOIN ".DB_DATABASE1.".fgt_srv_serial b  ON a.tag_no=b.tag_no
			WHERE a.model_kanban = '$tagmodel'
			AND DATE_FORMAT(a.date_insert,'%m%Y') = DATE_FORMAT(NOW(),'%m%Y')";//	MONTH(a.date_insert) = MONTH(NOW()) 
				
	$qrcht=mysqli_query($con, $sqlcht);
	$rscht = mysqli_fetch_array($qrcht); 
	$mxsrl=$rscht['mxtag'];
	
		if($gserial-$mxsrl==1 AND $chk_8==8){
			//echo $gserial."-".$mxsrl;
			echo "1";
			}else{
			echo "No";
			}//if($gserial-$rscht['mxtag']==1){
	

 }//  if(!empty($_GET["code"]) && $_GET["code"]<>"undefined"){


?>
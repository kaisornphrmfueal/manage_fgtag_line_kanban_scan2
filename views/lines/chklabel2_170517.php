<?
include('../../includes/configure.php');
if(!empty($_GET["modelsr"]) && $_GET["modelsr"]<>"undefined"){
	$gserial=substr($_GET["modelsr"], -5); 
	$gtagno=$_GET["tgno"];
	$tagmodel=$_GET["trmodel"];
	/*  $sqlcht="SELECT IFNULL(RIGHT(MAX(serial_scan_label), 6),'Null')  AS mxtag
				FROM  ".DB_DATABASE1.".fgt_serial 
				WHERE tag_no = '$gtagno' ";*/
				
		$sqlcht="SELECT IFNULL(RIGHT(MAX(b.serial_scan_label), 5),0)  AS mxtag
			FROM  ".DB_DATABASE1.".fgt_tag  a  
            LEFT JOIN ".DB_DATABASE1.".fgt_serial b  ON a.tag_no=b.tag_no
			WHERE a.model_kanban = '$tagmodel'
			AND MONTH(a.date_insert) = MONTH(NOW()) ";	
				
	$qrcht=mysql_query($sqlcht);
	$rscht = mysql_fetch_array($qrcht); 
	$mxsrl=$rscht['mxtag'];
	
		if($gserial-$mxsrl==1){
			// echo $gserial."-".$mxsrl;
			echo "1";
			}else{
				echo "No";
			}//if($gserial-$rscht['mxtag']==1){
	

 }//  if(!empty($_GET["code"]) && $_GET["code"]<>"undefined"){


?>
<?
include('../../includes/configure.php');
$gm="114000-2850B101BUC00001";
$gm2="114000-2850B101";
$gr="114000-2850B101 BUC00001";
//if(!empty($_GET["qcmodel"]) && $_GET["qcmodel"]<>"undefined"){
if(!empty($gm) && $gm<>"undefined"){
	//$gserial=substr($_GET["modelsr"], -5); 
	////$exp= explode(' ' , $_GET["modelsr"]);
	  $exp= explode(' ' , "114000-2850B101 BUC00001");
		foreach( $exp as $expps ):if(substr($expps,0, 1)=="B"){	 echo "====". $frdata =  $expps;	}else{ echo"Y_Y";}
	
	endforeach ;
	 	$gserial=substr($frdata, -5);
		
	//-----------------------Start Check Only A-Z,a-z,0-9 (เจออักขระพิเศษแล้วตัดทิ้ง แล้วไปเช็คต่อ) --------------
		$gserial8=substr($frdata, -8);
		$str_serial = preg_replace('/[^A-Za-z0-9-]/', '', $gserial8);
		$chk_8=strlen($str_serial);
	//----------------------End Check Only A-Z,a-z,0-9 ------------------------------------------------
	
	$gtagno="300073018";
	$tagmodel="114000-2850B101";
	
//	$gtagno=$_GET["tgno"];
	// $tagmodel=$_GET["trmodel"];
	
		   $sqlg="SELECT id_model
					FROM ".DB_DATABASE1.".fgt_model 
					WHERE label_model_no = '$gm2'
					AND  tag_model_no  ='".$tagmodel."' ";
		  $resultg = mysql_query($sqlg);

if(mysql_num_rows($resultg)<>0){
	$rowg = mysql_fetch_array($resultg);
	 /* $sqlcht="SELECT IFNULL(RIGHT(MAX(serial_scan_label), 5),'Null')  AS mxtag
				FROM  ".DB_DATABASE1.".fgt_serial 
				WHERE tag_no = '$gtagno' ";*/
	 	$sqlcht="SELECT IFNULL(RIGHT(MAX(b.serial_scan_label), 5),0)  AS mxtag
				FROM  ".DB_DATABASE1.".fgt_srv_tag  a  
                LEFT JOIN ".DB_DATABASE1.".fgt_srv_serial b  ON a.tag_no=b.tag_no
				WHERE a.model_kanban = '$tagmodel'
				AND DATE_FORMAT(a.date_insert,'%m%Y') = DATE_FORMAT(NOW(),'%m%Y')";//MONTH(a.date_insert)  = MONTH(NOW())	
	$qrcht=mysql_query($sqlcht);
	$rscht = mysql_fetch_array($qrcht); 
	echo $mxsrl=$rscht['mxtag'];

		if($gserial-$mxsrl==1 and $chk_8==8){//Check Tag no. and Serial.length == 8 Only is TRUE 
				// echo $gserial."-".$mxsrl;
			 echo "1";
			}else{
				echo "Wrong";
				// echo $mxsrl ;
			}//if($gserial-$rscht['mxtag']==1){

		
}else{
	 echo "No";
	 // echo $sqlg;
	}
 }//  if(!empty($_GET["code"]) && $_GET["code"]<>"undefined"){


?>
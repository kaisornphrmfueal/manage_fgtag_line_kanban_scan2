<?

		//echo "=". $_POST['htagno'];	
	if(!empty($_POST['button2']) && $_POST['button2']=="Submit" && !empty($_POST['htagno']) ){ 
		$pkbmodel=$_POST['hkbmodel'];
		//$ptagid=$_POST['htid'];
		$ptagno=$_POST['htagno'];
		$phtkno=$_POST['htkno']; 
		$phtagbc=$_POST['htagbc']; 
		$poserial=$_POST['hserial'];
		
		
		
		//$plineid=$_POST['hslid'];
	 	$pscanmodel=$_POST['model'];
			
	 	  $sqlct="SELECT a.tag_no,c.tag_model_no,c.label_model_no,a.line_id,a.fg_tag_barcode ,
				b.serial_scan_label, COUNT(b.tag_no)+1 AS sr_count,a.ticket_qty AS std_qty,c.model_destination
						FROM ".DB_DATABASE1.".fgt_srv_tag a
						LEFT JOIN ".DB_DATABASE1.".fgt_srv_serial b ON a.tag_no=b.tag_no
						LEFT JOIN ".DB_DATABASE1.".fgt_model c ON a.id_model=c.id_model
						WHERE a.status_print = 'Not yet'
						AND b.tag_no='$ptagno'
						GROUP BY a.tag_no"; //c.std_qty,
		$qrct=mysql_query($sqlct);
		$numct=mysql_num_rows($qrct);
		
		
		$chkdata=substr($pscanmodel,0, 2);
			if($chkdata  == "EW" || $chkdata  == "GB" || $chkdata   == "GC"  || $chkdata   == "GD" ){
				//echo $chkdata ;
				$psserial=substr($pscanmodel,3, 8);
				$modelscan=$chkdata;
			}else{
				$exp= explode(' ' , $pscanmodel);
				foreach( $exp as $expps ):
					if(substr($expps,0, 1)=="B"){
						$frdata =  $expps;
					}
				endforeach ;
				//$psserial=$frdata;
				$psserial=substr($frdata,0,8);
				$modelscan=substr($pscanmodel,0, 15);
					
			}//if($chkdata  == "EW" || $chkdata  == "GB" || $chkdata   == "GC"  || $chkdata   == "GD" ){
		//alert($psserial." - ".$modelscan." | ".substr($psserial,0,3));
		$txtserial=substr($psserial,0,3);
		//--------- Check pattern serial ----------------

			$sql_pat="SELECT pattern_serial FROM ".DB_DATABASE1.".fgt_pattern_serial WHERE pattern_serial = '$txtserial' ";
			$qr_pat=mysql_query($sql_pat);
			$num_pat=mysql_num_rows($qr_pat);	
		//---------- end check pattern serial ----------
		
		if($num_pat <> 0 AND $num_pat <> ""){ //Check Pattern Serial exe BPB,BPC
		$str_hdnlast_serial=substr($poserial, -5);
		$str_postnew_serial=substr($psserial, -5);
			//alert($str_hdnlast_serial);
			//alert($str_postnew_serial);
			
		if( $str_postnew_serial-$str_hdnlast_serial == 1 ){  //if($psserial <> ""){
			if($numct==0){
				//echo "numct==0";
					//---START Check Qty if 1 print -----------
					/* $sqlckn="SELECT c.std_qty
								FROM ".DB_DATABASE1.".fgt_srv_tag a
								LEFT JOIN ".DB_DATABASE1.".fgt_model c ON a.id_model=c.id_model
								WHERE a.status_print = 'Not yet'
								AND a.tag_no='$ptagno' 
								GROUP BY a.tag_no";*/
					 $sqlckn="SELECT a.ticket_qty  AS std_qty,c.model_destination
								FROM ".DB_DATABASE1.".fgt_srv_tag a
								LEFT JOIN ".DB_DATABASE1.".fgt_model c ON a.id_model=c.id_model
								WHERE a.status_print = 'Not yet'
								AND a.tag_no='$ptagno' 
								GROUP BY a.tag_no"; /// query nodata
					$qrckn=mysql_query($sqlckn);
					$rsckn=mysql_fetch_array($qrckn);
					$model_dest1 = $rsckn['model_destination'];
					if($rsckn['std_qty']==1){
						$sqlup_tag="UPDATE ".DB_DATABASE1.".fgt_srv_tag SET  sn_start='$psserial' , sn_end='$psserial' ,
										status_print='Printed', tag_qty='1', date_print='".date('Y-m-d H:i:s')."',
										tag_location='1'
										WHERE tag_no='$ptagno' " ;
							$qrup_tag=mysql_query($sqlup_tag);
							
							$sqlsr="INSERT INTO ".DB_DATABASE1.".fgt_srv_serial SET  tag_no='$ptagno', 
									model_scan_label='$modelscan', serial_scan_label='$psserial', 
									date_scan='".date('Y-m-d H:i:s')."'";
							$qrsr=mysql_query($sqlsr);
							log_hist($user_login,"Insert",$psserial,"fgt_srv_serial",$sqlsr);
							$sqlutk="UPDATE  rfid_project.rf_kanban_ticket SET
												 status_write=6, last_status='Print Tag'
												 WHERE ticket_ref='$phtkno'";
							$qrtk=mysql_query($sqlutk); //Print Tag
						
						// START INSERT FOR MATCHING
						//echo $model_dest;
						
						if($model_dest1=="E"){
						$ip = $_SERVER['REMOTE_ADDR']; 
						$tkno9=sprintf("%09d",$phtkno);
						 $sqlmc = "INSERT INTO ewi_packing.record_mathing_kanban SET 
							record_code='".date("YmdHis")."', ticket_no='$tkno9',	model_no_scan='$modelscan', 
							fg_tag_no='$phtagbc', model_no_auto='$modelscan', judge='OK', 
							operator_id='$top_name', record_time='".date('Y-m-d H:i:s')."', 
							error_code='-', line_leader_id='-',ip='$ip',kanban_group='".date("ymdH")."' ,working_location='1',status_scan = '7'"; 
							mysql_query($sqlmc); 
							
							
							$sqlmc = "INSERT INTO ewi_packing.record_mathing_kanban_log SET 
							record_code='".date("YmdHis")."', ticket_no='$tkno9', model_no_scan='$modelscan', fg_tag_no='$phtagbc', 
							model_no_auto='$modelscan', judge='ok', operator_id='$top_name', record_time='$today',
							error_code='-', line_leader_id='-',ip='$ip'";
							mysql_query($sqlmc);
							
							log_record_packing($top_name,'Line insert to record_mathing_kanban',$sql);
							}//if($model_dest==""){
						// END  INSERT FOR MATCHING 
						
						//sprintTag
						 printTag($ptagno);
						//eprinTag
							log_hist($user_login,"Printed Tag",$ptagno,"fgt_srv_tag","");
					//---END  Checking Qty if 1 print -----------		
					sleep(3);
					gotopage("index.php?id=".base64_encode('print')."&lid=".$user_login);
						
					}else{ //Qty > 1
						$sqlup_tag="UPDATE ".DB_DATABASE1.".fgt_srv_tag SET sn_start='$psserial' 
								WHERE tag_no='$ptagno' " ;
						$qrup_tag=mysql_query($sqlup_tag);
						
						$sqlsr="INSERT INTO ".DB_DATABASE1.".fgt_srv_serial SET  tag_no='$ptagno', 
								model_scan_label='$modelscan', serial_scan_label='$psserial', 
								date_scan='".date('Y-m-d H:i:s')."'";
						$qrsr=mysql_query($sqlsr);
						log_hist($user_login,"Insert",$psserial,"fgt_srv_serial",$sqlsr);	
						gotopage("index.php?id=".base64_encode('printtag')."&lid=".$user_login."&idtg=".base64_encode($ptagno));	
					}//f($rsckn['std_qty']==1){
					//---END  Checking  Qty if 1 print -----------

				}else{
					$rsct = mysql_fetch_array($qrct);
					//$rsidtag = $rsct['id_tag'];
					$rsstqty = $rsct['std_qty'];
					$rsscqty = $rsct['sr_count'];
					$model_dest = $rsct['model_destination'];
					
	
					if($rsstqty==$rsscqty){
							$sqlup_tag="UPDATE ".DB_DATABASE1.".fgt_srv_tag SET sn_end='$psserial' ,
										status_print='Printed', tag_qty='$rsscqty', date_print='".date('Y-m-d H:i:s')."',
										tag_location='1'
										WHERE tag_no='$ptagno' " ;
							$qrup_tag=mysql_query($sqlup_tag);
							
							$sqlsr="INSERT INTO ".DB_DATABASE1.".fgt_srv_serial SET  tag_no='$ptagno', 
									model_scan_label='$modelscan', serial_scan_label='$psserial', 
									date_scan='".date('Y-m-d H:i:s')."'";
							$qrsr=mysql_query($sqlsr);
							log_hist($user_login,"Insert",$psserial,"fgt_srv_serial",$sqlsr);
							$sqlutk="UPDATE  rfid_project.rf_kanban_ticket SET
												 status_write=6, last_status='Print Tag'
												 WHERE ticket_ref='$phtkno'";
							$qrtk=mysql_query($sqlutk); //Print Tag
						
						// START INSERT FOR MATCHING
						if($model_dest=="E"){
						$ip = $_SERVER['REMOTE_ADDR'];
						$tkno9=sprintf("%09d",$phtkno);
						$sqlmc = "INSERT INTO ewi_packing.record_mathing_kanban SET 
							record_code='".date("YmdHis")."', ticket_no='$tkno9',	model_no_scan='$modelscan', 
							fg_tag_no='$phtagbc', model_no_auto='$modelscan', judge='OK', 
							operator_id='$top_name', record_time='".date('Y-m-d H:i:s')."', 
							error_code='-', line_leader_id='-',ip='$ip',kanban_group='".date("ymdH")."' ,working_location='1',status_scan = '7'"; 
							mysql_query($sqlmc); 
							
							
							$sqlmc = "INSERT INTO ewi_packing.record_mathing_kanban_log SET 
							record_code='".date("YmdHis")."', ticket_no='$tkno9', model_no_scan='$modelscan', fg_tag_no='$phtagbc', 
							model_no_auto='$modelscan', judge='ok', operator_id='$top_name', record_time='$today',
							error_code='-', line_leader_id='-',ip='$ip'";
							mysql_query($sqlmc);
							
							log_record_packing($top_name,'Line insert to record_mathing_kanban',$sql);
							
						}//if($model_dest==""){
					
						// END  INSERT FOR MATCHING 
						
						//sprintTag
						 printTag($ptagno);
						//eprinTag
							log_hist($user_login,"Printed Tag",$ptagno,"fgt_srv_tag","");
							
						sleep(3);	
						gotopage("index.php?id=".base64_encode('print')."&lid=".$user_login);
						
						}else{
							//scan again
							$sqlsr="INSERT INTO ".DB_DATABASE1.".fgt_srv_serial SET  tag_no='$ptagno', 
								model_scan_label='$modelscan', serial_scan_label='$psserial', 
								date_scan='".date('Y-m-d H:i:s')."'";
							$qrsr=mysql_query($sqlsr);
							log_hist($user_login,"Insert",$psserial,"fgt_srv_serial",$sqlsr);
						gotopage("index.php?id=".base64_encode('printtag')."&lid=".$user_login."&idtg=".base64_encode($ptagno));
						
					}//if($rsstqty==$rsscqty){
	
			}//if($numct==0){
				
		}else{
			alert("Serial No. ไม่ถูกต้อง กรุณาแสกนข้อมูลใหม่อีกครั้ง");
			gotopage("index.php?id=".base64_encode('printtag')."&idtg=".base64_encode($ptagno));
		}//if($psserial <> ""){
		}//if($num_pat <> 0 AND $num_pat <> ""){
		else{
				alert("รูปแบบ Pattern Serial No. ไม่ถูกต้อง กรุณาแสกนข้อมูลใหม่อีกครั้ง");
				gotopage("index.php?id=".base64_encode('printtag')."&idtg=".base64_encode($ptagno));
				}// else if($num_pat <> 0 AND $num_pat <> ""){	
			
			
	}//if(!empty($_POST['button2']) || $_POST['button2']=="Submit"){


?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript">
window.onload = function() {
  document.getElementById("model").focus();
}

/*function validateTag2(my_request) {
document.getElementById("scan").submit();
}*/
</script>


  <div class="rightPane" align="center">
  <?
  	$lineid= $_GET['lid'];

	
  	if (!empty($_GET['idwait'])){
		$gtgno = base64_decode($_GET['idwait']);
		  $sqluw="UPDATE ".DB_DATABASE1.".fgt_srv_tag SET status_print='Not yet' WHERE tag_no='".$gtgno."'";
			$qruw=mysql_query($sqluw);
			$sql_st="SELECT b.id_model,a.tag_no,b.tag_model_no AS modelm,model_name,
					a.line_id,a.fg_tag_barcode,b.std_qty ,a.matching_ticket_no,a.ticket_qty
						FROM ".DB_DATABASE1.".fgt_srv_tag a 
						LEFT JOIN ".DB_DATABASE1.".fgt_model b ON a.id_model=b.id_model 
						WHERE a.line_id='$user_login'
						AND a.tag_no='$gtgno'
						AND a.status_print = 'Not yet' ";
						
	}else{
		$gtgno = base64_decode($_GET['idtg']);
  		  $sql_st="SELECT b.id_model,a.tag_no,b.tag_model_no AS modelm,model_name,
		  			a.line_id,a.fg_tag_barcode,b.std_qty,a.matching_ticket_no,a.ticket_qty
						FROM ".DB_DATABASE1.".fgt_srv_tag a 
						LEFT JOIN ".DB_DATABASE1.".fgt_model b ON a.id_model=b.id_model 
						WHERE a.line_id='$user_login'
						AND a.tag_no='".$gtgno."'
						AND a.status_print = 'Not yet' ";
						
	}//	if (!empty($_GET['idwait'])){
	  //echo $sql_st;
		$qr_st=mysql_query($sql_st);
		if($num_st=mysql_num_rows($qr_st)<>0){
			$rsst=mysql_fetch_array($qr_st);
			$modelid=$rsst['id_model'];
			
			//$id_tag=$rsst['id_tag'];
			$stmodel=$rsst['modelm'];
			$st_stdqty=$rsst['std_qty'];
			$sttag=$rsst['tag_no'];
			$stticket=$rsst['matching_ticket_no'];
			$stfg_tag_barcode=$rsst['fg_tag_barcode'];
			$stticket_qty=$rsst['ticket_qty'];
			
  ?>
  

<form name="scan"  id="scan" method="post" action="index.php?id=<?=base64_encode('printtag')?>"   onsubmit="return false;"  autocomplete="off" >
            
    <table width="952" height="310" border="1" align="center" class="table01">
              <tr>
                <th height="31" colspan="2"><span class="text_black">
				<?
				 
				 echo $top_name." Real Time Printing";
				 ?>
                </span>  </th>
              </tr>
              <tr>
                <th width="375" height="45"><div class="tmagin_left"><span class="text_black">Produces Model (โมเดลที่กำลังผลิต) :<br />
                </span></div></th>
                <td width="561" height="45">  <div class="tmagin_right">
                <span class="text_black"><?  echo "<span id=txtModel>$stmodel</span> ";?> </span>
                <span class="txt-blue-big"><? echo "[".$rsst['model_name']."]"; ?></span>
               	  <input type="button" name="button3" id="button3" value="Change" class="button3"  
            onclick="window.location.href='index.php?id=<?=base64_encode('print')?>&wtag=<?=$sttag?>';"/>
       		  
                </div>
                </td>
              </tr>
              <tr>
                <th height="45"><div class="tmagin_left"><span class="text_black">STD Qty. (จำนวนมาตรฐาน) : <br />
                </span></div></th>
                <td height="45">  <div class="tmagin_right"> <span class="txt-black-big">
				<?  echo $st_stdqty; ?> </span>
               
                </div>
                </td>
              </tr>
				   <tr>
                <th height="45"><div class="tmagin_left"><span class="text_black">Ticket No. : <br />
                </span></div></th>
                <td height="45">  <div class="tmagin_right"> <span class="txt-black-big">
				<?php  echo $stticket; ?>  || Ticket Qty. : </span>
               
               <span class="txt-black-big"> 	<?php  echo $stticket_qty; ?> </span> </div>
                </td>
              </tr>
              <tr>
                <th height="45"><div class="tmagin_left"><span class="text_black">Tag no. (หมายเลขแท็ก) :
                </span> <br />
                </div></th>
                <td height="45">  <div class="tmagin_right"> 
                <span class="txt-black-big"><?php  echo "<span id=txtTag>$sttag</span> ";?> || </span>
                <span class="text_black">  ซีเรียลล่าสุด : </span>
                <span class="txt-blue-big">
                <?
                 	  $sql_sr="SELECT b.id_serial, b.serial_scan_label  
								FROM ".DB_DATABASE1.".fgt_srv_tag a 
								LEFT JOIN ".DB_DATABASE1.".fgt_srv_serial b ON a.tag_no=b.tag_no 
								WHERE a.model_kanban = '$stmodel' 
								AND DATE_FORMAT(b.date_scan,'%m%Y') = DATE_FORMAT(NOW(),'%m%Y')
								ORDER BY id_serial DESC LIMIT 1";//MONTH(b.date_scan) = MONTH(NOW())
					$qr_sr=mysql_query($sql_sr);
						if($num_st=mysql_num_rows($qr_sr)<>0){
							$rssr=mysql_fetch_array($qr_sr);
							echo  $rerialck= $rssr['serial_scan_label'];
						}else{
								echo $rerialck="00000000";
							}
				?>
                </span>
                </div>
                </td>
              </tr>
                <tr>
                <td height="45"><div class="tmagin_left"> <span class="text_black">Scan Label barcode (แสกนซีเรียลลาเบล) : <br />
                </span></div></td>
                <td >
                <div class="tmagin_right">
               
                  <input type="text" name="model" id="model"  class="bigtxtbox" style="width:290px;"  onkeypress="return ckKeyPresse(event);" />
                  <input type="button" name="btnscan" id="btnscan" value="Submit" class="myButton" onclick="validateTag(this.value)" />
                  <input type="hidden" name="button2" id="button2"  value="Submit" /> 
                  <input type="hidden" name="hkbmodel" id="hkbmodel" value="<?=$stmodel?>" />
					<input type="hidden" name="htkno" id="htkno" value="<?=$stticket?>" />
					 <input type="hidden" name="htagbc" id="htagbc" value="<?=$stfg_tag_barcode?>" />
					  <input type="hidden" name="htagno" id="htagno" value="<?=$sttag?>" />  
					<input type="hidden" name="hserial" id="hserial" value="<?=$rerialck?>" /> 
              <tr>
                <td colspan="2" height="75px"  align="center">
             <div id="txtStatus"> </div>

                </td>
              </tr>
    </table>
  
</form>

<?

		  $sql ="SELECT id_serial,tag_no,model_scan_label,serial_scan_label,
					 DATE_FORMAT(date_scan, '%d-%b-%Y %H:%i') AS dateprint 
					 FROM ".DB_DATABASE1.".fgt_srv_serial
					 WHERE tag_no='$sttag'
					 ORDER BY id_serial DESC ";
		$qr=mysql_query($sql);
		$nums=mysql_num_rows($qr);
		if($nums<>0){				
			$i=1;
?>
<!-- <form id="form1" name="form1" method="post" action="index.php?id=<?=base64_encode('printtag')?>" autocomplete="off" >!-->

<table width="863" border="1" class="table01" align="center" >
     <tr height="31">
       <th colspan="4" align="left" >
         <span class="text_black_bold">Model No.(หมายเลขโมเดล) : </span><? echo $stmodel;?> <br />
 
        <span class="text_black_bold">  STD Qty. (จำนวน): </span><? echo $st_stdqty;?></th>
       <th >
		<!---   <input type="button" name="button5" id="button5" value="Print"  class="button1" onclick="javascript:openWins('windows.php?win=print&idm=<?=$sttag?>', '_blank',650, 530, 1, 1, 0, 0, 0);return false;"/>!-->
		 </th>
     </tr>
     <tr height="31">
       <th width="77" ><span class="text_black_bold">No. <br/>(ลำดับ)</span></th>
       <th width="129" ><span class="text_black_bold">Tag No.<br />
         (หมายเลขแท็ก) </span><br />
      </th> 
       <th width="230" ><span class="text_black_bold">Model on label<br />
       (หมายเลขโมลเดลของลาเบล)</span></th>
       <th width="235"><span class="text_black_bold">Serial on label<br />
(หมายเลขซีเรียลของลาเบล)       </span></th>
       <th width="158"><span class="text_black_bold">Date scan<br />
         (วันที่แสกน)
       </span></th>
    </tr>
   		<?
        	while($rs=mysql_fetch_array($qr)){
		?>
     <tr <?php echo icolor($v); $v = $v + 1; ?> onMouseOver="className=&quot;over&quot;"  onMouseOut="className=&quot;&quot;" height="31px" align="center">
       <td><span class="text_black_normal02"><?=$i?></span></td>
       <td><span class="text_black_normal02"><?=$rs['tag_no'];?></span></td>
       <td><span class="text_black_normal02"><?=$rs['model_scan_label'];?></span></td>
       <td><span class="text_green_normal02"><?=$rs['serial_scan_label'];?></span></td>
       <td><span class="text_black_normal02"><?=$rs['dateprint'];?></span></td>
    </tr>
     	<?php
			$i++;
			}//while($rs=mysql_fetch_array()){
		?>
     <tr align="center">
       <td colspan="5"><input type="hidden" name="htag" id="htag" value="<?=$sttag?>" /> 
            <input type="hidden" name="hqtys" id="hqtys" value="<?=$nums?>" />
		   
		   
			<!-- <input type="hidden" name="hidtag" id="hidtag" value="<?=$id_tag?>" />!-->
   
     </td>
     </tr>

   </table>
<!--</form>!-->
		  <?
                }else{
					echo "<center><div class='table_comment' >No have data...</div></center>";
					}//if($nums=mysql_num_rows($qr)){		
	
}else{
	echo "<center><div class='table_comment' >
	<a href='index.php?id=".base64_encode('line')."'>ไม่มีข้อมูลการตั้งต้นของโมเดล คลิกที่นี่ เพื่อแสกน Kanban อีกครั้ง</a></div></center>";
	}//if($num_st=mysql_num_rows($qr_st)<>0){
          ?>
</div> 

  
  
  

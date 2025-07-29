<?

				
	if(!empty($_POST['button2']) && $_POST['button2']=="Submit" && !empty($_POST['htagno']) ){ 
		$pkbmodel=$_POST['hkbmodel'];
		//$ptagid=$_POST['htid'];
		$ptagno=$_POST['htagno'];
		$phtkno=$_POST['htkno']; 
		$phtagbc=$_POST['htagbc']; 
		
		//$plineid=$_POST['hslid'];
	 	$pscanmodel=$_POST['model'];
			
	 	 $sqlct="SELECT a.tag_no,c.tag_model_no,c.label_model_no,a.line_id,a.fg_tag_barcode ,
				b.serial_scan_label, COUNT(b.tag_no)+1 AS sr_count,a.ticket_qty AS std_qty
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
		
		if($psserial <> ""){
			if($numct==0){
				//echo "numct==0";
					//---START Check Qty if 1 print -----------
					/* $sqlckn="SELECT c.std_qty
								FROM ".DB_DATABASE1.".fgt_srv_tag a
								LEFT JOIN ".DB_DATABASE1.".fgt_model c ON a.id_model=c.id_model
								WHERE a.status_print = 'Not yet'
								AND a.tag_no='$ptagno' 
								GROUP BY a.tag_no";*/
					 $sqlckn="SELECT a.ticket_qty  AS std_qty
								FROM ".DB_DATABASE1.".fgt_srv_tag a
								WHERE a.status_print = 'Not yet'
								AND a.tag_no='$ptagno' 
								GROUP BY a.tag_no";
					$qrckn=mysql_query($sqlckn);
					$rsckn=mysql_fetch_array($qrckn);
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
												 WHERE ticket_ref='$ticket_no'";
							$qrtk=mysql_query($sqlutk); //Print Tag
						
						// START INSERT FOR MATCHING
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
												 WHERE ticket_ref='$ticket_no'";
							$qrtk=mysql_query($sqlutk); //Print Tag
						
						// START INSERT FOR MATCHING
						$ip = $_SERVER['REMOTE_ADDR'];
						$tkno9=sprintf("%09d",$phtkno);
						//echo "-------->>>>";
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
  
<?php
		
			 if( date("H:i:s") >= "07:50:00" AND date("H:i:s") <= "20:19:59"){
							$chshift="Day";
							$datework=date('Y-m-d');
				 			$fieldcap="capacity_type_day";
				 			$fieldqty="all_qty_day"; 
							$field_capaqty="capacity_qty_day"; 
						}else{
							$chshift="Night";
				 			$fieldcap="capacity_type_night";
				 			$fieldqty="all_qty_night";
				 			$field_capaqty="capacity_qty_night"; 
							if( date("H:i:s") > "20:19:59" AND date("H:i:s") <= "23:59:59"){
								$datework=date('Y-m-d');
							}else{
								$datework=date( "Y-m-d",  strtotime("-1 day") );
							}
						}
			
	      $sql_tk="SELECT a.id_plan,a.all_qty, c.day_qty,c.day_ot_qty,
	  	c.night_qty, a.$fieldcap AS capa_type,a.$field_capaqty AS capaqty,a.$fieldqty AS shiftqty,
		
		DATE_FORMAT(MAX(c.capacity_date), '%d-%b-%Y') AS cap_date, DATE_FORMAT(a.plan_date, '%d-%b-%Y') AS plandate, 
		IFNULL( a.emp_modify, a.emp_insert)AS empins, IFNULL( DATE_FORMAT( a.date_modify, '%d-%b-%Y %H:%i'), 
		DATE_FORMAT( a.date_insert, '%d-%b-%Y %H:%i'))AS dateins 
		FROM ".DB_DATABASE1.".fgt_pc_plan a 
		
		LEFT JOIN ".DB_DATABASE1.".fgt_capacity c ON a.line_id=c.line_id 
		WHERE plan_date ='".$datework."'
		AND a.line_id = '$user_login' 
		 GROUP BY a.id_plan"; //,IF(b.matching_ticket_no IS NOT NULL ,COUNT(*),0)  AS tk_produce
				$qrctk=mysql_query($sql_tk);
				$rsctk=mysql_fetch_array($qrctk);
				$numstk=mysql_num_rows($qrctk);
			    $all_qty=$rsctk['all_qty'];
				$all_shif_tqty=$rsctk['shiftqty'];
			    $lineid=$rsctk['line_id'];
				$capn=$rsctk['capa_type'];
				$capqt=$rsctk['capaqty'];
				
			
		if($numstk<> 0){
			
	?>
		
		  <table width="650px"  border="1" class="table01" align="center">
			  <tr >
			    <th height="34" colspan="4"><span class="text_black">  Plan Date: <span class="txt-blue-b"><?php echo $rsctk['plandate'];?> </span>
			
			|| Total Plan = <span class="txt-blue-b"><?php echo $all_qty;?> </span>Unit </th>
		      </tr>
			  <tr align="center">
			    <td width="135" height="31"><span class="txt-black-b">  Capacity (Unit) </span>
		        </td>
			    <td width="149"><span class="txt-black-b"> <?php echo $capn." Plan (Unit)";?></span></td>
			    <td width="164"><span class="txt-black-b">Actual (Unit)</span></td>
			    <td width="174" height="31"><span class="txt-black-b">Remain  (Unit)</span></td>
			  
		      </tr>
			  <tr align="center">
			    <td height="31"><span class="txt-sky-bl"> <?php echo $capqt ;
					?> </span> </td>
			    <td><span class="txt-sky-bl"> <?php echo $all_shif_tqty;
					?></span></td>
			    <td><span class="txt-green-b-s"> 
					<?php 
					 	$sqlcrp = "  SELECT SUM(b.tag_qty) AS sum_current
									  FROM ".DB_DATABASE1.".fgt_srv_tag b
									  WHERE b.date_work ='$datework' AND shift='$chshift'
									  AND b.line_id = '$user_login'
									  GROUP BY  b.id_model ";
						$qrncrp=mysql_query($sqlcrp);
						$rsncrp=mysql_fetch_array($qrncrp);
						if($rsncrp['sum_current']<>""){
							$current_prod= $rsncrp['sum_current'];
						}else{
							$current_prod= 0;
						}
					echo	$current_prod;
		?></span>  </td>
			    <td height="31"><span class="txt-red-bl">  
					<?php echo $all_shif_tqty-$current_prod;
		?> </span></td>
			 
		    </tr>
		  </table>
			<?php }else{
				echo "<center><div class='table_comment'>No hava Plan from Production Control...</div> </center><br/>";
		}//if($numstk<>0){ ?>
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
							echo  $rssr['serial_scan_label'];
						}else{
								echo "00000000";
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
       <th ><input type="button" name="button5" id="button5" value="Print"  class="button1" onclick="javascript:openWins('windows.php?win=print&idm=<?=$sttag?>', '_blank',650, 530, 1, 1, 0, 0, 0);return false;"/></th>
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

  
  
  

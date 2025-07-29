<?
	if(!empty($_POST['button2']) && $_POST['button2']=="Submit" && !empty($_POST['htid']) ){ 
		$pkbmodel=$_POST['hkbmodel'];
		$ptagid=$_POST['htid'];
		$ptagno=$_POST['htagno'];
	
		//$plineid=$_POST['hslid'];
	 	$pscanmodel=$_POST['model'];
			
	 	 $sqlct="SELECT a.id_tag,a.tag_no,c.tag_model_no,c.label_model_no,a.line_id,a.fg_tag_barcode ,
				b.serial_scan_label, COUNT(b.tag_no)+1 AS sr_count,c.std_qty
						FROM ".DB_DATABASE1.".fgt_tag a
						LEFT JOIN ".DB_DATABASE1.".fgt_serial b ON a.tag_no=b.tag_no
						LEFT JOIN ".DB_DATABASE1.".fgt_model c ON a.id_model=c.id_model
						WHERE a.status_print = 'Not yet'
						AND b.tag_no='$ptagno'
						GROUP BY a.tag_no";
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
	
		if($psserial <> ""){
			if($numct==0){
				//echo "numct==0";
					//---START Check Qty if 1 print -----------
					 $sqlckn="SELECT c.std_qty
								FROM ".DB_DATABASE1.".fgt_tag a
								LEFT JOIN ".DB_DATABASE1.".fgt_model c ON a.id_model=c.id_model
								WHERE a.status_print = 'Not yet'
								AND a.tag_no='$ptagno' 
								GROUP BY a.tag_no";
					$qrckn=mysql_query($sqlckn);
					$rsckn=mysql_fetch_array($qrckn);
					if($rsckn['std_qty']==1){
						$sqlup_tag="UPDATE ".DB_DATABASE1.".fgt_tag SET  sn_start='$psserial' , sn_end='$psserial' ,
										status_print='Printed', tag_qty='1', date_print='".date('Y-m-d H:i:s')."'
										WHERE tag_no='$ptagno' " ;
							$qrup_tag=mysql_query($sqlup_tag);
							
							$sqlsr="INSERT INTO ".DB_DATABASE1.".fgt_serial SET  tag_no='$ptagno', 
									model_scan_label='$modelscan', serial_scan_label='$psserial', 
									date_scan='".date('Y-m-d H:i:s')."'";
							$qrsr=mysql_query($sqlsr);
							log_hist($user_login,"Insert",$psserial,"fgt_serial",$sqlsr);
						//sprintTag
						 printTag($ptagid);
						//eprinTag
							log_hist($user_login,"Printed Tag",$ptagno,"fgt_tag","");
					//---END  Checking Qty if 1 print -----------		
					sleep(3);
					gotopage("index.php?id=".base64_encode('print')."&lid=".$user_login);
						
					}else{ //Qty > 1
						$sqlup_tag="UPDATE ".DB_DATABASE1.".fgt_tag SET sn_start='$psserial' 
								WHERE tag_no='$ptagno' " ;
						$qrup_tag=mysql_query($sqlup_tag);
						
						$sqlsr="INSERT INTO ".DB_DATABASE1.".fgt_serial SET  tag_no='$ptagno', 
								model_scan_label='$modelscan', serial_scan_label='$psserial', 
								date_scan='".date('Y-m-d H:i:s')."'";
						$qrsr=mysql_query($sqlsr);
						log_hist($user_login,"Insert",$psserial,"fgt_serial",$sqlsr);	
						gotopage("index.php?id=".base64_encode('printtag')."&lid=".$user_login."&idtg=".base64_encode($ptagno));	
					}//f($rsckn['std_qty']==1){
					//---END  Checking  Qty if 1 print -----------

				}else{
					
					$rsct = mysql_fetch_array($qrct);
					$rsidtag = $rsct['id_tag'];
					$rsstqty = $rsct['std_qty'];
					$rsscqty = $rsct['sr_count'];
	
					if($rsstqty==$rsscqty){
							$sqlup_tag="UPDATE ".DB_DATABASE1.".fgt_tag SET sn_end='$psserial' ,
										status_print='Printed', tag_qty='$rsscqty', date_print='".date('Y-m-d H:i:s')."'
										WHERE tag_no='$ptagno' " ;
							$qrup_tag=mysql_query($sqlup_tag);
							
							$sqlsr="INSERT INTO ".DB_DATABASE1.".fgt_serial SET  tag_no='$ptagno', 
									model_scan_label='$modelscan', serial_scan_label='$psserial', 
									date_scan='".date('Y-m-d H:i:s')."'";
							$qrsr=mysql_query($sqlsr);
							log_hist($user_login,"Insert",$psserial,"fgt_serial",$sqlsr);
						//sprintTag
						 printTag($rsidtag);
						//eprinTag
							log_hist($user_login,"Printed Tag",$ptagno,"fgt_tag","");
							
						sleep(3);	
						gotopage("index.php?id=".base64_encode('print')."&lid=".$user_login);
						
						}else{
							//scan again
							$sqlsr="INSERT INTO ".DB_DATABASE1.".fgt_serial SET  tag_no='$ptagno', 
								model_scan_label='$modelscan', serial_scan_label='$psserial', 
								date_scan='".date('Y-m-d H:i:s')."'";
							$qrsr=mysql_query($sqlsr);
							log_hist($user_login,"Insert",$psserial,"fgt_serial",$sqlsr);
						gotopage("index.php?id=".base64_encode('printtag')."&lid=".$user_login."&idtg=".base64_encode($ptagno));
						
					}//if($rsstqty==$rsscqty){
	
			}//if($numct==0){
				
		}else{
			alert("Serial No. ไม่ถูกต้อง กรุณาแสกนข้อมูลใหม่อีกครั้ง");
			gotopage("index.php?id=".base64_encode('printtag')."&idtg=".base64_encode($ptagno));
		}//if($psserial <> ""){
			
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
		  $sqluw="UPDATE ".DB_DATABASE1.".fgt_tag SET status_print='Not yet' WHERE tag_no='".$gtgno."'";
			$qruw=mysql_query($sqluw);
			$sql_st="SELECT a.id_tag,b.id_model,a.tag_no,b.tag_model_no AS modelm,model_name,a.line_id,a.fg_tag_barcode,b.std_qty 
						FROM ".DB_DATABASE1.".fgt_tag a 
						LEFT JOIN ".DB_DATABASE1.".fgt_model b ON a.id_model=b.id_model 
						WHERE a.line_id='$user_login'
						AND a.tag_no='$gtgno'
						AND a.status_print = 'Not yet' ";
						
	}else{
		$gtgno = base64_decode($_GET['idtg']);
  		 $sql_st="SELECT a.id_tag,b.id_model,a.tag_no,b.tag_model_no AS modelm,model_name,a.line_id,a.fg_tag_barcode,b.std_qty 
						FROM ".DB_DATABASE1.".fgt_tag a 
						LEFT JOIN ".DB_DATABASE1.".fgt_model b ON a.id_model=b.id_model 
						WHERE a.line_id='$user_login'
						AND a.tag_no='".$gtgno."'
						AND a.status_print = 'Not yet' ";
						
	}//	if (!empty($_GET['idwait'])){
		$qr_st=mysql_query($sql_st);
		if($num_st=mysql_num_rows($qr_st)<>0){
			$rsst=mysql_fetch_array($qr_st);
			$modelid=$rsst['id_model'];
			$id_tag=$rsst['id_tag'];
			$stmodel=$rsst['modelm'];
			$st_stdqty=$rsst['std_qty'];
			$sttag=$rsst['tag_no'];
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
                <th height="45"><div class="tmagin_left"><span class="text_black">Tag no. (หมายเลขแท็ก) :
                </span> <br />
                </div></th>
                <td height="45">  <div class="tmagin_right"> 
                <span class="txt-black-big"><?  echo "<span id=txtTag>$sttag</span> ";?> || </span>
                <span class="text_black">  ซีเรียลล่าสุด : </span>
                <span class="txt-blue-big">
                <?
                 	  $sql_sr="SELECT b.id_serial, b.serial_scan_label  
								FROM ".DB_DATABASE1.".fgt_tag a 
								LEFT JOIN ".DB_DATABASE1.".fgt_serial b ON a.tag_no=b.tag_no 
								WHERE a.model_kanban = '$stmodel' 
								AND MONTH(b.date_scan) = MONTH(NOW())
								ORDER BY id_serial DESC LIMIT 1";
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
                  <input type="hidden" name="htid" id="htid" value="<?=$id_tag?>" /> 
                  <input type="hidden" name="htagno" id="htagno" value="<?=$sttag?>" />  			
                </div> </td>
                </tr>

              <tr>
                <td colspan="2" height="75px"  align="center">
             <div id="txtStatus"> </div>

                </td>
              </tr>
            </table>
  
</form>

<?

		  $sql =" SELECT id_serial,tag_no,model_scan_label,serial_scan_label,
					 DATE_FORMAT(date_scan, '%d-%b-%Y %H:%i') AS dateprint 
					 FROM ".DB_DATABASE1.".fgt_serial
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
       <th ><input type="button" name="button5" id="button5" value="Print"  class="button1" onclick="javascript:openWins('windows.php?win=print&idm=<?=$sttag?>', '_blank',650, 430, 1, 1, 0, 0, 0);return false;"/></th>
     </tr>
     <tr height="31">
       <th width="77" ><span class="text_black_bold">No. <br/>(ลำดับ)</span></th>
       <th width="129" ><span class="text_black_bold">Tag No.<br />
         (หมายเลขแท็ก) </span><br />
       </span></th> 
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
     	<?
			$i++;
			}//while($rs=mysql_fetch_array()){
		?>
     <tr align="center">
       <td colspan="5"><input type="hidden" name="htag" id="htag" value="<?=$sttag?>" /> 
            <input type="hidden" name="hqtys" id="hqtys" value="<?=$nums?>" />
			 <input type="hidden" name="hidtag" id="hidtag" value="<?=$id_tag?>" />
   
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

  
  
  

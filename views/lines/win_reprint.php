<?php
if(!empty($_POST['hbutton'])){
		$preprint=$_GET['idrp'];
		
			$sqlck="SELECT emp_id,emp_pass,emp_name,permission,active 
					FROM ".DB_DATABASE1.".view_permission 
					WHERE emp_id = '". substr($_POST['user'],2)."' 
					AND emp_pass='".$_POST['passw']."'
					AND  permission in ('sup','floater') ";
			$qrck=mysqli_query($con, $sqlck);
			if(mysqli_num_rows($qrck)<>0){
			$rsck=mysqli_fetch_array($qrck);
			$usprint=$rsck['emp_id'];
		  	$sqlu="UPDATE  ".DB_DATABASE1.".fgt_srv_tag SET status_print='Reprinted',who_reprint='".$rsck['emp_name']."', 
			 		date_reprint='".date('Y-m-d H:i:s')."' WHERE tag_no=$preprint";  //upload_status = '0',
			mysqli_query($con, $sqlu);
			//sprintTag
			 printTag($preprint);
			//eprinTag
			log_hist($usprint,"Reprinted Tag",$preprint,"fgt_tag","");
			sleep(2);	
				go_page_opener("?msg=true");		
				}else{
					 alert("ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง");
					 gotopage("windows.php?win=reprint&idrp=$preprint");
					}//if(mysql_num_rows($qrck)<>0){	
		}//if(!empty($_POST['hbutton'])){
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" type="text/JavaScript">
window.onload = function() {
  document.getElementById("user").focus();
}

function validate(form2) {   
	if(document.form2.user.value == "" || document.getElementById("user").value.length!= 6){
		alert("กรุณาใส่รหัสพนักงานผู้ยืนยัน 6 หลัก (tl1234)");
		document.form2.user.focus();
		return (false);
	}else if(document.form2.passw.value == "" ){
		alert("Please input Password.");
		document.form2.passw.focus();
		return (false);
	}else{
		form2.button.disabled = true;  
		form2.button.value = 'Re-Pringting...'; 
		return (true) ;
	}
}///function validate(form) {

</script>

<?php
 	if(!empty($_GET['idrp'])){
		$gidm=$_GET['idrp'];
 	 $sql_qt="SELECT a.tag_no,b.tag_model_no,a.line_id,a.fg_tag_barcode, a.tag_qty,b.model_name,
				DATE_FORMAT(a.date_print, '%d-%b-%Y %H:%i') AS date_prod
				FROM ".DB_DATABASE1.".fgt_srv_tag a 
				LEFT JOIN ".DB_DATABASE1.".fgt_model b ON a.id_model=b.id_model 
				WHERE a.tag_no ='$gidm' 
				GROUP BY  a.tag_no";
		$qrqt=mysqli_query($con, $sql_qt);
		$nums=mysqli_num_rows($qrqt);
		if($nums<>0){	
		$rsq=mysqli_fetch_array($qrqt);
		$tidtg=$rsq['tag_no'];
		$tno=$rsq['tag_no'];
		$tmodel=$rsq['tag_model_no'];  
		$tqty=$rsq['tag_qty'];
?>
 <form action="" method="post"name="form2" id="form2"  onsubmit='return validate(this)' autocomplete="off">


<table width="640"  border="1" class="table01" align="center">
  <tr>
    <th height="39" colspan="2"><span class="text_black">
      <p>
      Reprinting Confirmation (ยืนยันการพิมพ์แท็กซ้ำ)</p></span></th>
  </tr>
   <tr>
      <td height="32" ><p class="text_black_bold">Model (โมเดล) : 
                
      </p></td>
      <td width="306" height="32" ><div class="tmagin_right"><span class="text_black_bold">  <?=$tmodel?>(<?=$rsq['model_name']?>)</span> </div></td>  
    </tr>
   <tr>
     <td height="29" ><span class="text_black_bold">Tag No. (หมายเลขแท็ก) : </span></td>
     <td height="29" ><div class="tmagin_right"><span class="text_black_bold"><?=$tno?></span></div></td>
   </tr>
   <tr>
     <td height="29" ><span class="text_black_bold">Serial No. (หมายเลขซีเรียล) : </span></td>
     <td height="29" ><div class="tmagin_right"><span class="text_black_bold">
	 <?php
	  	$sqls="SELECT  CASE  $tqty WHEN 1 THEN  MIN(serial_scan_label) ELSE  CONCAT(MIN(serial_scan_label) ,'-' ,MAX(serial_scan_label)) END AS serialp
				FROM ".DB_DATABASE1.".fgt_srv_serial WHERE tag_no = '$tno'";
		$qrs=mysqli_query($con, $sqls);
		$rss=mysqli_fetch_array($qrs);
		echo $rss['serialp'];
	 ?></span></div></td>
   </tr>
   <tr>
     <td height="27" ><span class="text_black_bold">Qty. (จำนวน) </span></td>
     <td height="27" ><div class="tmagin_right"><span class="text_black_bold"><?=$tqty?></span></div></td>
   </tr>
    <tr>
     <td height="27" ><span class="text_black_bold">Production Date. (วันที่ผลิต) </span></td>
     <td height="27" ><div class="tmagin_right"><span class="text_black_bold"><?=$rsq['date_prod']?></span></div></td>
   </tr>
    <tr>
      <td width="301" height="40"><span class="text_black_bold">Username  (รหัสพนักงานผู้ยืนยัน 6 หลัก) :</span></td>  
      <td><div class="tmagin_right">
      <input type="text" name="user" id="user" />
      *Ex.tl1972</div></td>
    </tr>
     <tr>
     <td height="37" ><span class="text_black_bold">Password (รหัสผ่าน) : </span></td>
     <td height="37" ><div class="tmagin_right"><input type="password" name="passw" id="passw" /></div></td>
   </tr>
    <td height="25" colspan="2" align="center">
        <input id='button'  name='button' type='submit' value='Re-Print'  class="buttonb" />
        <input type="hidden" name="hbutton" id="hbutton" value="Print" />
        <input type="hidden" name="hidtag" id="hidtag" value="<?=$tidtg?>" />
      <input type=button value="Close" onclick="javascript:window.close();"  class="buttonr" /></td>
  </tr>
</table>
 </form> 

 
 <?php
		}//if($nums<>0){	
 	}else{
		echo "<br/><br/><br/><center><div class='table_comment' >No hava data, please try again ";
	}//if(!empty($_GET['idm'])){
			
 ?>
 
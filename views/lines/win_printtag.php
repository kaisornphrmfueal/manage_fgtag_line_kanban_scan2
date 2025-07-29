<?php

if(!empty($_POST['hbutton'])){
		$pqtyp=$_POST['hqtys'];
		//$plineidp=$_POST['hlid'];
		$ptagn=$_POST['htno'];
		$pidtag=$_POST['hidtag'];
		$phmodel=$_POST['hmd'];
	  	$phtk=$_POST['htk'];
		$phtagbc=$_POST['htagbc'];
	
		$sqlck="SELECT emp_id,emp_pass,emp_name,permission,active 
					FROM ".DB_DATABASE1.".view_permission 
					WHERE emp_id = '". substr($_POST['user'],2)."' 
					AND emp_pass='".$_POST['passw']."'
					AND  permission in ('sup','floater') ";
			$qrck=mysqli_query($con, $sqlck);
			if(mysqli_num_rows($qrck)<>0){
			$rsck=mysqli_fetch_array($qrck);
			$usprint=$rsck['emp_id'];
			
			 	  $sqlup_tag="UPDATE ".DB_DATABASE1.".fgt_srv_tag SET sn_end=(SELECT serial_scan_label FROM ".DB_DATABASE1.".fgt_srv_serial 
							WHERE tag_no = '$ptagn' ORDER BY id_serial DESC LIMIT 1) , 
							status_print='Printed',tag_qty='$pqtyp' , date_print='".date('Y-m-d H:i:s')."',emp_confirm_print='".$usprint."'
							WHERE tag_no='$pidtag' " ;
						$qrup_tag=mysqli_query($con, $sqlup_tag);
						
						log_hist($user_login,"Printed Tag",$ptagn,"fgt_tag",$sqlup_tag);
				
				
						// START INSERT FOR MATCHING
						$ip = $_SERVER['REMOTE_ADDR'];
						$tkno9=sprintf("%09d",$phtk);
							 $sqln = "SELECT line_name FROM ".DB_DATABASE1.".view_line WHERE line_id =  '$user_login' ";
							$qrn=mysqli_query($con, $sqln);
							$rsn=mysqli_fetch_array($qrn);
							$lname= $rsn['line_name'];
						
						$sqlmc = "INSERT INTO ewi_packing.record_mathing_kanban SET 
							record_code='".date("YmdHis")."', ticket_no='$tkno9',	model_no_scan='$phmodel', 
							fg_tag_no='$phtagbc', model_no_auto='$phmodel', judge='OK', 
							operator_id='$lname', record_time='".date('Y-m-d H:i:s')."', 
							error_code='-', line_leader_id='-',ip='$ip',kanban_group='".date("ymdH")."' ,working_location='1',status_scan = '7'"; 
							mysqli_query($con, $sqlmc); 
							
							
							$sqlmc = "INSERT INTO ewi_packing.record_mathing_kanban_log SET 
							record_code='".date("YmdHis")."', ticket_no='$tkno9', model_no_scan='$phmodel', fg_tag_no='$phtagbc', 
							model_no_auto='$phmodel', judge='ok', operator_id='$lname', record_time='$today',
							error_code='-', line_leader_id='-',ip='$ip'";
							mysqli_query($con, $sqlmc);
							
							log_record_packing($lname,'Line insert to record_mathing_kanban',$sql);
						// END  INSERT FOR MATCHING 
						//printTag
						printTag($pidtag);
						sleep(2);	
						//go_page_parent("?msg=true");		
			
				}else{
					 alert("ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง");
					 gotopage("windows.php?win=print&idm=$ptagn");
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
		form2.button.value = 'Pringting...'; 
		
		return (true) ;
	}
}///function validate(form) {

</script>

<?php
 	if(!empty($_GET['idm'])){
		$gidm=$_GET['idm'];
	   	$sql_qt="SELECT a.tag_no,b.tag_model_no,a.line_id,a.fg_tag_barcode,
					a.tag_qty,b.model_name,b.std_qty ,COUNT(c.tag_no) AS ctag,a.matching_ticket_no
					FROM ".DB_DATABASE1.".fgt_srv_tag a 
					LEFT JOIN ".DB_DATABASE1.".fgt_model b ON a.id_model=b.id_model 
					LEFT JOIN ".DB_DATABASE1.".fgt_srv_serial c ON a.tag_no=c.tag_no
					WHERE a.tag_no ='$gidm' 
					GROUP BY  a.tag_no";
		$qrqt=mysqli_query($con, $sql_qt);
		$nums=mysqli_num_rows($qrqt);
		if($nums<>0){	
		$rsq=mysqli_fetch_array($qrqt);
		$tidtg=$rsq['tag_no'];
		$tno=$rsq['tag_no'];
		$tmodel=$rsq['tag_model_no'];
		$tqty=$rsq['ctag'];
		$sqty=$rsq['std_qty'];
		$sqtk=$rsq['matching_ticket_no'];
		$sqfg_tag_barcode=$rsq['fg_tag_barcode'];
?>
 <form action="" method="post" enctype="multipart/form-data" name="form2" id="form2"  onsubmit='return validate(this)' autocomplete="off">


<table width="623"  border="1" class="table01" align="center">
  <tr>
    <th height="39" colspan="2"><span class="text_black">
      <p>
      Confirm (ยืนยันการปริ้นแท็ก กรณีงานไม่เต็มกล่อง)</p></span></th>
  </tr>
   <tr>
      <td height="34" colspan="2" ><p class="text_black_bold">Model No.(หมายเลขโมเดล) :  <?=$tmodel?>   </p>
        <p class="text_black_bold">Tag No. (หมายเลขแท็ก) :  <?=$tno?>  </p>
      <p class="text_black_bold">Serial No. (หมายเลขซีเรียล) :   <?php 
	 	 $sqls="SELECT CASE  $tqty WHEN 1 THEN  MIN(serial_scan_label) ELSE   CONCAT(MIN(serial_scan_label) ,'-' ,MAX(serial_scan_label)) END AS serialp
				FROM ".DB_DATABASE1.".fgt_srv_serial WHERE tag_no = '$tno'";
		$qrs=mysqli_query($con, $sqls);
		$rss=mysqli_fetch_array($qrs);
		echo $rss['serialp'];
	 ?></p>
	   <p class="text_black_bold">Ticket No. :  <?=$sqtk?>  </p>
	   </td>  
    </tr>
    <tr  align="center"> 
      <td height="61" bgcolor="#66CCCC"><span class="text_black">Standard Qty.
          <p>(จำนวนมาตรฐาน) </p></span></td>
      <td width="302" bgcolor="#FF8080" ><span class="text_black">Current Qty. 
      <p>(จำนวนที่แสกนไปแล้ว) </p></span></td>
    </tr>
    <tr align="center">
      <td height="49"><span class="txt-black-big"><?=$sqty?></span> </td>
      <td><span class="txt-black-big"><?=$tqty?></span></td>
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
        <input id='button'  name='button' type='submit' value='Print'  class="buttonb" />
        <input type="hidden" name="hbutton" id="hbutton" value="Print" />
        <input type="hidden" name="htno" id="htno" value="<?=$gidm?>" />
        <input type="hidden" name="hidtag" id="hidtag" value="<?=$tidtg?>" />
        <input type="hidden" name="hqtys" id="hqtys" value="<?=$tqty?>" />
		<input type="hidden" name="hmd" id="hmd" value="<?=$tmodel?>" /> 
		<input type="hidden" name="htk" id="htk" value="<?=$sqtk?>" />
		<input type="hidden" name="htagbc" id="htagbc" value="<?=$sqfg_tag_barcode?>" />
	
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
 
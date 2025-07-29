<?
if(!empty($_POST['hbutton'])){
		$pqtyp=$_POST['hqtys'];
		$ptagn=$_POST['htno'];
		$pidtag=$_POST['hidtag'];
		$emp_confirm=base64_decode($_POST['hemp']);
		for($k=1;$k<=$pqtyp;$k++){
			
			$pserial=$_POST['edit_'.$k];
			$pidserial=$_POST['hidserial_'.$k];
			
			if(!empty($pserial) AND !empty($pidserial)){
			$sqlup="UPDATE ".DB_DATABASE1.".fgt_serial 
			SET serial_scan_label = '$pserial',
			emp_modify = '$emp_confirm',
			date_modify = '".date('Y-m-d H:i:s')."' 
			WHERE id_serial='$pidserial' ";
			$qrup=mysql_query($sqlup);
			log_hist($emp_confirm,"Adjustment Serial",$pidserial,"fgt_serial",$sqlup);
			//if($qrup){
				
				if($k==$pqtyp){
					//updat sn_start in fgt_tag
					//alert($k."=".$pqtyp);
					$sqlup_st="UPDATE ".DB_DATABASE1.".fgt_tag 
					SET sn_start = '".$_POST['edit_1']."',sn_end = '".$_POST['edit_'.$pqtyp]."'
					WHERE tag_no='$ptagn' ";
					$qrup_st=mysql_query($sqlup_st);
					log_hist($emp_confirm,"Adjustment Tag sn_start,sn_end",$ptagn,"fgt_tag",$sqlup_st);
						}
					//}//if($qrup){
				}//if(!empty($pserial) AND !empty($pidserial)){
				else{
						alert("กรุณาตรวจสอบข้อมูล ระบบไม่อนุญาติให้บันทึกข้อมูลว่าง");
						gotopage("windows.php?win=edit&idm=$ptagn");
					}
			}
		sleep(2);
		go_page_parent("?msg=true");

					
		}//if(!empty($_POST['hbutton'])){
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" type="text/JavaScript">
window.onload = function() {
  document.getElementById("user").focus();
}

function validate(form2) {   

		form2.button.disabled = true;  
		form2.button.value = 'Editing...'; 
		return (true) ;

}///function validate(form) {

</script>

<?
 	if(!empty($_GET['idm'])){
		$gidm=$_GET['idm'];
	  	$sql_qt="SELECT a.id_tag, a.tag_no,b.tag_model_no,a.line_id,a.fg_tag_barcode,
					a.tag_qty,b.model_name,b.std_qty ,COUNT(c.tag_no) AS ctag
					FROM ".DB_DATABASE1.".fgt_tag a 
					LEFT JOIN ".DB_DATABASE1.".fgt_model b ON a.id_model=b.id_model 
					LEFT JOIN ".DB_DATABASE1.".fgt_serial c ON a.tag_no=c.tag_no
					WHERE a.tag_no ='$gidm' 
					GROUP BY  a.tag_no";
		$qrqt=mysql_query($sql_qt);
		$nums=mysql_num_rows($qrqt);
		if($nums<>0){	
		$rsq=mysql_fetch_array($qrqt);
		$tidtg=$rsq['id_tag'];
		$tno=$rsq['tag_no'];
		$tmodel=$rsq['tag_model_no'];
		$tqty=$rsq['ctag'];
		$sqty=$rsq['std_qty'];
?>
 <form action="" method="post" enctype="multipart/form-data" name="form2" id="form2"  onsubmit='return validate(this)' autocomplete="off">


<table width="623"  border="1" class="table01" align="center">
  <tr>
    <th height="39" colspan="<?=$sqty?>"><span class="text_black">
      <p>Adjustment Tag Data (แก้ไขข้อมูลแท็ก)</p></span>
      </th>
  </tr>
   <tr>
      <td height="34" colspan="<?=$sqty?>" ><p class="text_black_bold">
      <p align="center"><span style="color:#F00;font-size:12px;">*** กรุณาตรวจสอบความถูกต้องของข้อมูลก่อนการแก้ไข เพราะการแก้ไขส่งผลต่อความถูกต้องของข้อมูล FG Tag ***</span></p>
     <p class="text_black_bold"> Model No. (หมายเลขโมเดล) :  <?=$tmodel?>   </p>
        <p class="text_black_bold">Tag No. (หมายเลขแท็ก) :  <?=$tno?>  </p>
        <p class="text_black_bold">Standard Qty. (จำนวนมาตรฐาน) :  <?=$sqty?> </p>
      <p class="text_black_bold">Serial No. (หมายเลขซีเรียล) :   <? 
	 	$sqls="SELECT CASE  $tqty WHEN 1 THEN  MIN(serial_scan_label) ELSE   CONCAT(MIN(serial_scan_label) ,'-' ,MAX(serial_scan_label)) END AS serialp
				FROM prod_fg_tag.fgt_serial WHERE tag_no = '$tno'";
		$qrs=mysql_query($sqls);
		$rss=mysql_fetch_array($qrs);
		echo $rss['serialp'];
	 ?></p></td>  
    </tr>
    <tr  align="center">
    <? for($i=1;$i<=$sqty;$i++){?> 
      <td height="61" bgcolor="#66CCCC"><span class="text_black">Serial <?=$i;?></span></td>
          <? }?>
    </tr>
    <tr align="center">
    <? //for($i=1;$i<=$sqty;$i++){
		$sql="SELECT serial_scan_label,id_serial FROM prod_fg_tag.fgt_serial WHERE tag_no = '$tno' ";
		$qr=mysql_query($sql);
		$j=1;
		while($re=mysql_fetch_array($qr)){
		
	?> 
      <td height="49"><span class="txt-black-big">
      <input type="text" name="edit_<?=$j?>" id="edit_<?=$j?>" class="bigtxtbox" value="<?=$re['serial_scan_label']?>">
      <input type="hidden" name="hidserial_<?=$j?>" id="hidserial_<?=$j?>" value="<?=$re['id_serial']?>" />
      </span> </td>
      <? $j++;}//while($re=mysql_fetch_array()){
	  //} ?>
    </tr>

    <td height="25" colspan="<?=$sqty?>" align="center">
        <input id='button'  name='button' type='submit' value='Edit'  class="buttonb" />
        <input type="hidden" name="hbutton" id="hbutton" value="Edit" />
        <input type="hidden" name="htno" id="htno" value="<?=$tno?>" />
        <input type="hidden" name="hidtag" id="hidtag" value="<?=$tidtg?>" />
        <input type="hidden" name="hqtys" id="hqtys" value="<?=$sqty?>" />
        <input type="hidden" name="hemp" id="hemp" value="<?=$_GET['emp']?>" />
      <input type=button value="Close" onClick="javascript:window.close();"  class="buttonr" /></td>
  </tr>
</table>
 </form>  
 <?
		}//if($nums<>0){	
 	}else{
			
		echo "<br/><br/><br/><center><div class='table_comment' >No hava data, please try again ";
	}//if(!empty($_GET['idm'])){
			
 ?>
 
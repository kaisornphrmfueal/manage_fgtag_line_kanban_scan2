
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
	 	if(!empty($_POST['tsearch'])|| !empty($_GET['serh'])){
				if(!empty($_POST['tsearch'])){$txtsearsh=$_POST['tsearch'];}else{$txtsearsh=$_GET['serh']; }
					$x="AND  ((c.model_name LIKE '%$txtsearsh%') or (a.model_kanban LIKE '%$txtsearsh%')
						  or (c.model_code LIKE '%$txtsearsh%') )";
				}else{ $x=""; }
         	  	  
?>
 <form id="form1" name="form1" method="post" action="">
   <table width="769" border="1" align="center" class="table01" >
     <tr>
       <td width="405" height="37"><div class="tmagin_right">Search : Model No., Model Name,Model Code</div> </td>
       <td width="348">
              <input type="text" name="tsearch" id="tsearch" style=" width:180px;"  value="<?php echo @$txtsearsh?>"/>
               <!-- <img src="../../images/calendar-icon2.png"  align="absmiddle" style="cursor:pointer " onClick="displayDatePicker('tsearch')" />-->
       <input type="submit" name="button" id="button" value="Search" /></td>
     </tr>
   </table>
</form>   
 
 <div class="rightPane">
 <table width="98%" height="105" border="1" bordercolor="#CC9966"class="table01" align="center">
    <tr >
      <th height="28" colspan="11">
      <div align="center">Max Tag Report of <i><?=date("M-Y")?></i> <br />(รายงานซีเรียลล่าสุด)</div>  </th>
      </tr> 
    <tr>
      <th width="3%" height="23">No.<br/> ลำดับ</th>
      <th width="5%">Line<br/>ไลน์</th>
      <th width="8%">Tag No<br/>หมายเลขแท็ก</th>
      <th width="7%">Model Code<br/>รหัสโมเดล</th>
      <th width="11%"><span class="tmagin_right">Model No. (Tag)<br/>
      หมายเลขโมเดล</span></th>
      <th width="10%">Model Name<br/>ชื่อโมเดล</th>
      <th width="14%">Serial<br/>หมายเลขซีเรียล</th>
      <th width="7%">Tag Qty.<br/>จำนวน</th>
      <th width="7%">Status Print<br/>สถานะการพิมพ์</th>
      <th width="12%">Print Date<br/>วันที่พิมพ์</th>
      <th width="14%">Re-Print Date<br/>
      วันที่พิมพ์ซ้ำ</th>
    </tr>

<?php
	 $sql_model="SELECT label_model_no FROM ".DB_DATABASE1.".fgt_model ORDER BY label_model_no ";
	$qr_model=mysqli_query($con, $sql_model);
	$total=mysqli_num_rows($qr_model);
		$i=1;
		if($total<>0){	
	
	while($model=mysqli_fetch_array($qr_model)){

	$q="SELECT a.line_id,d.line_name,a.tag_no,c.model_code,a.id_model,b.id_serial,a.date_work,c.model_name,a.model_kanban,b.serial_scan_label ,
	a.date_print ,a.tag_qty,a.status_print,IFNULL(DATE_FORMAT(a.date_reprint, '%d-%b-%Y %H:%i'),'-') AS datereprint,
	DATE_FORMAT(a.date_print,'%d-%b-%Y %H:%i') AS dateprint ,a.tag_qty,
				IFNULL(DATE_FORMAT(a.date_reprint, '%d-%b-%Y %H:%i'),'-') AS datereprint,
				CASE a.status_print WHEN 'Reprinted' THEN CONCAT(DATE_FORMAT(a.date_reprint, '%d-%b-%Y %H:%i'),
				CONCAT(' [',a.who_reprint,']') ) ELSE '-' END AS rp	
		FROM ".DB_DATABASE1.".fgt_srv_tag a 
		LEFT JOIN ".DB_DATABASE1.".fgt_srv_serial b ON a.tag_no=b.tag_no 
		LEFT JOIN ".DB_DATABASE1.".fgt_model c ON a.id_model=c.id_model
		LEFT JOIN ".DB_DATABASE1.".view_line d ON a.line_id=d.line_id 
		WHERE a.model_kanban = '".$model['label_model_no']."' 
		and MONTH(b.date_scan) = MONTH(NOW()) and a.line_id ='$user_login'
		$x
		ORDER BY id_serial DESC Limit 1 ";	
	$qr=mysqli_query($con, $q);
	$rs=mysqli_fetch_array($qr);
	//echo $q;
	if(!empty($rs['serial_scan_label']) and $rs['serial_scan_label']<>""){
			?>
			<tr  <?php $v =0; $v = $v + 1; echo  icolor($v); ?>height="28" 
            onMouseOver="className=&quot;over&quot;"  onMouseOut="className=&quot;&quot;" align="center">
                  <td><?=$i?></td>
                  <td><?=$rs['line_name']?></td>
                  <td><?=$rs['tag_no']?></td>
                  <td><?=$rs['model_code']?></td>
                  <td><?=$rs['model_kanban']?></td>
                  <td><?=$rs['model_name']?></td>
                  <td><?=$rs['serial_scan_label']?></td>
                  <td><?=$rs['tag_qty']?></td>
                  <td><?=$rs['status_print']?></td>
                  <td><?=$rs['date_print']?></td>
                  <td><?php echo $rs['rp'] ?></td>
              </tr>

			<?php
			$i++;
				}//if(!empty($rs['serial_scan_label']) and $rs['serial_scan_label']<>""){
		}//while($model=mysql_fetch_array($qr_model)){
		  ?>

  </table>
<?php 
				
			}else{
					echo "<br/><br/><br/><center><div class='table_comment' >No have Data  ";
		

			}//if(rows($qr)<>0){
		 ?>
</div>
  

<?php
include('../../includes/configure_srv.php');
	if(!empty($_POST['button']) && $_POST['button']=="Upload"){
		$sqltg = "SELECT MAX(a.id_tag)  AS mxtag
				FROM ".DB_DATABASE1.".fgt_tag  a
				WHERE a.line_id='$user_login'
				AND  a.status_print in ( 'Printed','Reprinted') 
				AND upload_status = '0'";
 		$qrtg=mysqli_query($con, $sqltg);
		$rstg=mysqli_fetch_array($qrtg);
		$maxtag= $rstg['mxtag'];
		$today=date('Y-m-d H:i:s');

		  	$sqlck="SELECT id_tag,line_id,tag_no,id_model,model_kanban,shift,sn_start,sn_end,
					tag_qty,fg_tag_barcode,status_print,date_insert,date_print,who_reprint,date_reprint,date_work
					FROM ".DB_DATABASE1.".fgt_tag  a
					WHERE a.line_id='$user_login'
					AND a.status_print in ('Printed','Reprinted') 
					AND a.upload_status = '0'
					AND  a.id_tag<='$maxtag' ";
			$qrck=mysqli_query($con, $sqlck);
			while($rsck=mysqli_fetch_array($qrck)){ 
				$idtag=$rsck['id_tag'];
				 $sqlint="INSERT INTO ".DB_DATABASE4.".fgt_srv_tag (line_id, tag_no,id_model,model_kanban,shift,sn_start,sn_end,tag_qty,fg_tag_barcode,
				status_print,date_insert,date_print,who_reprint,date_reprint,who_upload,date_upload,date_work)  
				VALUES	('".$rsck['line_id']."', '".$rsck['tag_no']."','".$rsck['id_model']."','".$rsck['model_kanban']."','".$rsck['shift']."',
				'".$rsck['sn_start']."','".$rsck['sn_end']."','".$rsck['tag_qty']."','".$rsck['fg_tag_barcode']."',
				'".$rsck['status_print']."','".$rsck['date_insert']."','".$rsck['date_print']."','".$rsck['who_reprint']."',
				'".$rsck['date_reprint']."','".$top_name."','".$today."','".$rsck['date_work']."')
				ON DUPLICATE KEY UPDATE    line_id='".$rsck['line_id']."',	id_model='".$rsck['id_model']."', model_kanban='".$rsck['model_kanban']."', 
				shift='".$rsck['shift']."',	sn_start='".$rsck['sn_start']."', sn_end='".$rsck['sn_end']."', tag_qty='".$rsck['tag_qty']."',
					 fg_tag_barcode='".$rsck['fg_tag_barcode']."', status_print='".$rsck['status_print']."', date_insert='".$rsck['date_insert']."',
					date_print='".$rsck['date_print']."', who_reprint='".$rsck['who_reprint']."', date_reprint='".$rsck['date_reprint']."',
					 who_upload='".$top_name."', date_upload='".$today."',date_work='".$rsck['date_work']."'";
				$qrint=mysqli_query($con, $sqlint);		
				if (!$qrint) {
					alert("Can't Upload data, Please try again");
					exit;
				}else{
					$sqlus="UPDATE ".DB_DATABASE1.".fgt_tag SET upload_status=1, who_upload='".$top_name."', 	
					date_upload='$today',mxid_upload='$maxtag' WHERE id_tag=$idtag";
					mysqli_query($con, $sqlus);
				}				
			}//while($rs=mysql_fetch_array($qrqrck)){ 
			
				mysqli_query($con, "INSERT INTO ".DB_DATABASE1.".fgt_update_tag SET last_tag_id='$maxtag', line_id='$user_login',
						  status_update='$top_name', date_update='".$today."'");
				 mysqli_query("INSERT INTO ".DB_DATABASE4.".fgt_log_local_update_tag SET last_tag_id='$maxtag', line_id='$user_login',
						  status_update='$top_name', date_update='".$today."'");
				gotopage("index.php?id=".base64_encode('upload_tag_report'));
		}//if(!empty($_POST['button']) && $_POST['button']=="Upload"){

?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

 <div class="rightPane" align="center">
  <?php
  		
 	 	 	   $sqlup="SELECT a.lastid,a.allqtyup
					FROM 
					(SELECT IFNULL(a.id_tag,0) AS lastid,count(*) AS allqtyup
					FROM ".DB_DATABASE1.".fgt_tag a
					WHERE a.line_id = '$user_login'
					AND a.status_print in ( 'Printed','Reprinted')
					AND a.id_tag > (SELECT IFNULL(MAX(last_tag_id),0) AS mxupid FROM ".DB_DATABASE1.".fgt_update_tag WHERE  type_status = 'Tag' )
					AND a.upload_status = '0'
					GROUP BY a.line_id) a";
		$qrup=mysqli_query($con, $sqlup);
		$numup=mysqli_num_rows($qrup);  
		if($numup<>0){
			$i=1;
			
 ?>
 <form id="form1" name="form1" method="post" action="">
 <table width="663px" border="1" class="table01" >
   <tr>
     <th height="27" colspan="5">Manual upload data to server<br />
       การส่งข้อมูลไปที่ระบบหลัก</th>
    </tr>
   <tr>
     <th width="62" height="28">No.<br/>ลำดับ</th>
     <th width="107">Last ID upload <br/> 
       ไอดีล่าสุด</th>
     <th width="139">Last Time upload <br/>
       เวลาส่งข้อมูลล่าสุด</th>
     <th width="225">All record Waiting to upload<br/>
       จำนวนที่รอส่งข้อมูล</th>
     <th width="96">Update Data<br/>
     ส่งข้อมูล</th>
   </tr>
    <?php while($rsup=mysql_fetch_array($qrup)){ ?>
   <tr align="center">
     <td><?=$i?></td>
     <td><?=$rsup['lastid']?></td>
     <td><?php
     		 $sqldt="SELECT IFNULL(DATE_FORMAT(date_update, '%d-%b-%Y %H:%i'),'-') AS dateupdate
					FROM ".DB_DATABASE1.".fgt_update_tag 
					WHERE type_status = 'Tag' 
					ORDER BY date_update DESC LIMIT 1";
			$qrdt=mysqli_query($con, $sqldt);
			$numup=mysqli_num_rows($qrdt);  
			if($numup<>0){
				$rsdt=mysqli_fetch_array($qrdt);
				echo  $rsdt['dateupdate'];
			}
		
			
	 ?></td>
     <td><?=$rsup['allqtyup']?></td>
     <td>
       <input type="submit" name="button" id="button" value="Upload" />
     </td>
   </tr>
   <?php
	}//while($rsup=mysql_fetch_array($qrup)){
   ?>
 </table>
 </form>
 <?php
		}//if($numup<>0){
 ?>

  <?php
	
		   	$q="SELECT a.id_tag,b.id_model,b.model_code,a.tag_no,a.shift,b.model_name,b.tag_model_no,
				DATE_FORMAT(a.date_print, '%d-%b-%Y %H:%i') AS dateprint ,a.tag_qty,
				IFNULL(DATE_FORMAT(a.date_reprint, '%d-%b-%Y %H:%i'),'-') AS datereprint,
				CONCAT('  [',who_reprint,']') AS whoreprint,
				CONCAT(a.sn_start,'-',a.sn_end) AS allserial,a.sn_start,a.sn_end,a.line_id,a.fg_tag_barcode,
				b.customer_part_no,b.customer_part_name,b.model_picture,a.status_print,b.status_tag_printing,c.line_name
				FROM ".DB_DATABASE1.".fgt_tag  a
				LEFT JOIN ".DB_DATABASE1.".fgt_model b ON a.id_model=b.id_model
				LEFT JOIN ".DB_DATABASE1.".view_line c ON a.line_id=c.line_id
				WHERE a.line_id='$user_login'
				AND  a.status_print in ( 'Printed','Reprinted') 
				AND upload_status = '0' ";
				
				$qr=mysqli_query($con, $q);
				$total=mysqli_num_rows($qr);  
					$i=1;
					if($total<>0)			
				{	
								$e_page=15; // ????? ???????????????????????????
								
								if(!isset($_GET['s_page']) or !empty($txtsearsh)){     
									$_GET['s_page']=0;     
								}else{     
									$chk_page=$_GET['s_page'];       
									$_GET['s_page']=$_GET['s_page']*$e_page;     
								}     
								$q.=" LIMIT ".$_GET['s_page'].",$e_page";  
								$qr=mysqli_query($con, $q);  
								if(mysqli_num_rows($qr)>=1){     
									@$plus_p=($chk_page*$e_page)+mysqli_num_rows($qr);     
								}else{     
									@$plus_p=($chk_page*$e_page);         
								}     
								$total_p=ceil($total/$e_page);     
								@$before_p=($chk_page*$e_page)+1; 
								
							?>
   
   
   
  <table width="98%" border="1" bordercolor="#CC9966"class="table01" align="center">
    <tr >
      <th height="23" colspan="10">
      <div align="center">Tag Report </div>  </th>
      </tr>
    <tr>
      <th width="3%" height="23">No.<br/>ลำดับ</th>
      <th width="5%">Line<br/>ไลน์</th>
      <th width="8%">Tag No<br/>หมายเลขแท็ก</th>
      <th width="10%">Model Code<br/>รหัสโมเดล</th>
      <th width="13%"><span class="tmagin_right">Model No. (Tag)<br/>หมายเลขโมดล</span></th>
      <th width="11%">Model Name<br/>ชื่อโมเดล</th>
      <th width="16%">Serial <br/>หมายเลขซีเรียล</th>
      <th width="9%">Tag Qty.<br/>จำนวน</th> 
      <th width="13%">Print Date<br/>วันที่พิมพ์</th>
      <th width="12%">Re-Print Date<br/>
      วันที่พิมพ์ซ้ำ</th>
    </tr>
       <?php while($rs=mysqli_fetch_array($qr)){ 
	   		$rtag=$rs['tag_no'];
	   	?>
      <tr  <?php $v =0; $v = $v + 1; echo  icolor($v); ?>height="28" onMouseOver="className=&quot;over&quot;"  onMouseOut="className=&quot;&quot;" align="center">
      <td ><?=$i?></td>
      <td><?=$rs['line_name']?></td>
      <td ><?=$rs['tag_no']?></td>
      <td><?=$rs['model_code']?></td>
      <td><?=$rs['tag_model_no']?></td>
      <td><?=$rs['model_name']?></td>
      <td><?=$rs['allserial']?></td>
      <td><?=$rs['tag_qty']?></td>
      <td><?=$rs['dateprint']?></td>
      <td><?php echo $rs['datereprint']." ".$rs['whoreprint']; ?></td>
    </tr>
	 <?php 
        $i++;
     
        }//	while($rsp=mysql_fetch_array($qrp)){
    ?>
  
  </table>
  
  <?php
								
		 if($total>0){ ?>  
<div class="browse_page" >
			  <?php    @page_navigator_user($before_p,$plus_p,$total,$total_p,$chk_page,base64_encode('upload'),$txtsearsh);  	  ?>
  </div>  
<?php }
				
			}else{
					echo "<br/><br/><br/><center><div class='table_comment' >No hava data waiting to upload  ";
		
			}//if(rows($qr)<>0){
		 ?>
</div>
  

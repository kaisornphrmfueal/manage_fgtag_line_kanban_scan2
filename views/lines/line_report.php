
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
	 	if(!empty($_POST['tsearch'])|| !empty($_GET['serh'])){
				if(!empty($_POST['tsearch'])){$txtsearsh=$_POST['tsearch'];}else{$txtsearsh=$_GET['serh']; }
					$x="AND   ((a.tag_no LIKE '%$txtsearsh%') or (a.date_print LIKE '%$txtsearsh%')
						  or (b.tag_model_no LIKE '%$txtsearsh%') or (b.model_name LIKE '%$txtsearsh%')   )";
				}else{ $x=""; }
         	  	  
?>
 <form id="form1" name="form1" method="post" action="">
   <table width="769" border="1" align="center" class="table01" >
     <tr>
       <td width="479" height="37"><div class="tmagin_right">Search : Tag No., Model No., Model Name,
Print Date (Exp.yyyy-mm-dd)</div> </td>
       <td width="253">
              <input type="text" name="tsearch" id="tsearch" style=" width:180px;"  value="<?php echo @$txtsearsh?>"/>
                <img src="../../images/calendar-icon2.png"  align="absmiddle" style="cursor:pointer " onClick="displayDatePicker('tsearch')" />
       <input type="submit" name="button" id="button" value="Search" /></td>
     </tr>
   </table>
</form>   
 
 <div class="rightPane">
<?php
		  
		   	$q="SELECT b.id_model,b.model_code,a.tag_no,a.shift,b.model_name,b.tag_model_no,
				DATE_FORMAT(a.date_print, '%d-%b-%Y %H:%i') AS dateprint ,a.tag_qty,
				IFNULL(DATE_FORMAT(a.date_reprint, '%d-%b-%Y %H:%i'),'-') AS datereprint,
				CASE status_print WHEN 'Reprinted' THEN CONCAT(DATE_FORMAT(a.date_reprint, '%d-%b-%Y %H:%i'),CONCAT(' [',who_reprint,']') ) ELSE '-' END AS rp,
				CONCAT('  [',who_reprint,']') AS whoreprint,status_print,
				CASE a.tag_qty WHEN 1 THEN  a.sn_start ELSE  CONCAT(a.sn_start,'-',a.sn_end) END AS allserial,
				a.sn_start,a.sn_end,a.line_id,a.fg_tag_barcode,
				b.customer_part_no,b.customer_part_name,b.model_picture,a.status_print,b.status_tag_printing,c.line_name
				FROM ".DB_DATABASE1.".fgt_srv_tag  a
				LEFT JOIN ".DB_DATABASE1.".fgt_model b ON a.id_model=b.id_model
				LEFT JOIN ".DB_DATABASE1.".view_line c ON a.line_id=c.line_id
				WHERE a.line_id='$user_login'
				$x 
				ORDER BY a.tag_no DESC";
			//echo $q;	
			$qr=mysqli_query($con, $q);
			$total=mysqli_num_rows($qr);  
					$i=1;
					if($total<>0)			
				{	
								$e_page=15; // ????? ???????????????????????????
								
								if(!isset($_GET['s_page']) ){      //or !empty($txtsearsh)
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
	


<table width="98%" height="105" border="1" bordercolor="#CC9966"class="table01" align="center">
    <tr >
      <th height="28" colspan="11">
      <div align="center">Tag Report (รายงาน)</div>  </th>
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
      <th width="9%">Printing Status<br/>สถานะ</th>
      <th width="12%">Print Date<br/>วันที่พิมพ์</th>
      <th width="14%">Re-Print Date<br/>
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
      <td><?=$rs['status_print']?></td>
      <td><?=$rs['dateprint']?></td>
      <td><?php echo $rs['rp'] ?></td>
    </tr>
	 <?php 
        $i++;
     
        }//	while($rsp=mysql_fetch_array($qrp)){
    ?>
  
  </table>
  
  <?php
								
		 if($total>0){ ?>  
<div class="browse_page" >
			  <?php       @page_navigator_user($before_p,$plus_p,$total,$total_p,$chk_page,base64_encode('line_report'),$txtsearsh);  	  ?>
  </div>  
<?php }
				
			}else{
					echo "<br/><br/><br/><center><div class='table_comment' >No have Data  ";
		

			}//if(rows($qr)<>0){
		 ?>
</div>
  

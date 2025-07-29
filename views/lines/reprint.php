<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<form id="form1" name="form1" method="post" action="">
   <table width="710" border="1" align="center" class="table01" >
     <tr>
       <td width="366" height="37"><div class="tmagin_right">Search : Tag No., Model Name, Model No.</div> </td>
       <td width="328">
              <input type="text" name="tsearch" id="tsearch" style=" width:180px;"  value="<?php echo @$_POST['tsearch']?>"/>
       <input type="submit" name="button" id="button" value="Search" /></td>
     </tr>
   </table>
</form>   

<div class="rightPane">
<?php
		   	if(!empty($_POST['tsearch'])){
				$txtsearsh= $_POST['tsearch'];
					$x="AND   ((a.tag_no LIKE '%$txtsearsh%') or (b.model_name LIKE '%$txtsearsh%')  or (b.tag_model_no LIKE '%$txtsearsh%')    )";
				}else{ $x=""; }
         	  	  
		 	$q="SELECT b.id_model,b.model_code,a.tag_no,a.shift,b.model_name,b.tag_model_no,
				DATE_FORMAT(a.date_print, '%d-%b-%Y %H:%i') AS dateprint ,	a.tag_qty,
				IFNULL(DATE_FORMAT(a.date_reprint, '%d-%b-%Y %H:%i'),'-') AS datereprint,
				CONCAT('  [',who_reprint,']') AS whoreprint,
				CASE a.tag_qty WHEN 1 THEN  a.sn_start ELSE  CONCAT(a.sn_start,'-',a.sn_end) END AS allserial,
				a.sn_start,a.sn_end,a.line_id,a.fg_tag_barcode,
				CASE a.status_print WHEN 'Reprinted' THEN CONCAT(DATE_FORMAT(a.date_reprint, '%d-%b-%Y %H:%i'),
				CONCAT(' [',who_reprint,']') ) ELSE '-' END AS rp,
				b.customer_part_no,b.customer_part_name,b.model_picture,a.status_print,b.status_tag_printing,c.line_name
				FROM ".DB_DATABASE1.".fgt_srv_tag  a
				LEFT JOIN ".DB_DATABASE1.".fgt_model b ON a.id_model=b.id_model
				LEFT JOIN ".DB_DATABASE1.".view_line c ON a.line_id=c.line_id
				WHERE status_print in ( 'Printed','Reprinted') 
				AND a.line_id='$user_login'
				$x 
				ORDER BY  a.date_print DESC";
			// echo "--".$q;
					$i=1;	
			$qr=mysqli_query($con, $q);
								$total=mysqli_num_rows($qr);  
					
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
	


<table width="98%" height="104" border="1" bordercolor="#CC9966"class="table01" align="center">
    <tr >
      <th height="27" colspan="11">
      <div align="center">Tag Reprinting</div>  </th>
      </tr>
      
    <tr>
      <th width="3%" height="27">No.<br/>ลำดับ</th>
      <th width="9%">Tag No<br/>หมายเลขแท็ก</th>
      <th width="5%">Model Code<br/>รหัสโมเดล</th>
      <th width="14%"><span class="tmagin_right">Model No. (Tag)<br/>
      หมายเลขโมเดล</span></th>
      <th width="8%">Model Name<br/>ชื่อโมเดล</th>
      <th width="12%">Serial <br/>หมายเลขซีเรียล</th>
      <th width="7%">Tag Qty.<br/>จำนวน</th>
      <th width="6%">Line<br/>ไลน์</th>
      <th width="10%">Print Date<br/>
      วันที่พิมพ์</th>
      <th width="14%">Re-Print Date<br/>        
      วันที่พิมพ์ซ้ำ</th>
      <th width="12%">Re-Printing<br/>
      พิมพ์ซ้ำ</th>
    </tr>
       <?php while($rs=mysqli_fetch_array($qr)){ 
	   		$rtag=$rs['tag_no'];
	   		  ?>
      <tr  <?php $v =0; $v = $v + 1; echo  icolor($v); ?>height="28" onMouseOver="className=&quot;over&quot;"  onMouseOut="className=&quot;&quot;" align="center">
      <td ><?=$i?></td>
      <td ><?=$rs['tag_no']?></td>
      <td><?=$rs['model_code']?></td>
      <td><?=$rs['tag_model_no']?></td>
      <td><?=$rs['model_name']?></td>
      <td><?=$rs['allserial']?></td>
      <td><?=$rs['tag_qty']?></td>
      <td><?=$rs['line_name']?></td>
      <td height="40"><?=$rs['dateprint']?></td>
      <td><?php echo $rs['rp']?></td>
      <td> 
         <img src="../../images/reprinting.png"  onclick="javascript:openWins('windows.php?win=reprint&idrp=<?=$rtag?>', '_blank',650, 380, 1, 1, 0, 0, 0);return false;"/>
        </td>
    </tr>
	 <?php 
        $i++;
     
        }//	while($rsp=mysql_fetch_array($qrp)){
    ?>
  
  </table>
  
  <?php
								
		 if($total>0){ ?>  
<div class="browse_page" >
			  <?php       @page_navigator_user($before_p,$plus_p,$total,$total_p,$chk_page,base64_encode('reprint'),$txtsearsh);  	  ?>
  </div>  
<?php }
				
			}else{
					echo "<br/><br/><br/><center><div class='table_comment' >No hava Data  ";
		

			}//if(rows($qr)<>0){
		 ?>
</div>
  

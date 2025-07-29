
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
error_reporting(0);

	 	if(!empty($_POST['tsearch'])|| !empty($_GET['serh'])){
				if(!empty($_POST['tsearch'])){$txtsearsh=$_POST['tsearch'];}else{$txtsearsh=$_GET['serh']; }
					$x="AND    ((a.model_kanban LIKE '%$txtsearsh%') or (c.model_name LIKE '%$txtsearsh%')
						  or (a.date_print LIKE '%$txtsearsh%')    )";
				}else{ $x=""; }
         	  	  
?>
<div align="center">
 <form id="form1" name="form1" method="post" action="">
   <table width="769" border="1" align="center" class="table01" >
     <tr>
       <td width="479" height="37"><div class="tmagin_right">Search :  Model No., Model Name,
Print Date (Exp.yyyy-mm-dd)</div> </td>
       <td width="253">
              <input type="text" name="tsearch" id="tsearch" style=" width:180px;"  value="<?php echo @$txtsearsh?>"/>
                <img src="../../images/calendar-icon2.png"  align="absmiddle" style="cursor:pointer " onClick="displayDatePicker('tsearch')" />
       <input type="submit" name="button" id="button" value="Search" /></td>
     </tr>
   </table>
</form>   
 </div>
 <div class="rightPane"   align="center">
<?php
		  
	 	    	$q="SELECT a.model_kanban ,c.model_name,a.shift ,DATE_FORMAT(a.date_print, '%d-%b-%Y') AS dates,
				MIN(b.serial_scan_label) AS min_serial ,DATE_FORMAT(MIN(b.date_scan) , '%H:%i') AS min_time ,
				MAX(b.serial_scan_label)AS max_serial ,DATE_FORMAT(MAX(b.date_scan) , '%H:%i') AS max_time,
				MAX(SUBSTR(b.serial_scan_label,4,5)),MIN(SUBSTR( b.serial_scan_label,4,5)),
				ROUND(MAX(SUBSTR(b.serial_scan_label,4,5))- MIN(SUBSTR( b.serial_scan_label,4,5))+1,0) AS sumserial
				FROM prod_fg_tag.fgt_srv_tag a
				LEFT JOIN  prod_fg_tag.fgt_srv_serial b ON a.tag_no=b.tag_no
				LEFT JOIN prod_fg_tag.fgt_model c ON a.model_kanban=c.tag_model_no
				WHERE a.line_id = '$user_login'
				$x
				GROUP BY a.model_kanban,a.shift,date_work,SUBSTR(b.serial_scan_label,1,3)
				ORDER BY date_work DESC, c.model_name";
			//echo $q;
			$qr=mysqli_query($con, $q);
			$total=mysqli_num_rows($qr);  
					$i=1;
					if($total<>0)			
				{	
								$e_page=15; // ????? ???????????????????????????
								
								if(!isset($_GET['s_page']) ){ //or !empty($txtsearsh)
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
	


<table width="904" height="105" border="1" bordercolor="#CC9966"class="table01" align="center">
    <tr >
      <th height="28" colspan="10">
      <div align="center">Tag Report (รายงาน)</div>  </th>
      </tr>
      
    <tr>
      <th width="5%" height="23">No.<br/> ลำดับ</th>
      <th width="8%">Model Name<br/>
      ชื่อโมเดล์</th>
      <th width="19%"><span class="tmagin_right">Model No. (Tag)<br/>
หมายเลขโมเดล</span></th>
      <th width="12%">Shift<br/>
กะการทำงาน</th>
      <th width="8%">Date<br/>
        วันที่</th>
      <th width="11%">Serial Start<br/>
        ซีเรียลเริ่มต้น</th>
      <th width="9%">Start Time<br />
      เวลาเริ่มต้น<br/></th>
      <th width="11%">Serial Latest<br/>
ซีเรียลสุดท้าย</th>
      <th width="9%">Stop Time<br />
        เวลาสิ้นสุด</th>
      <th width="8%">Summary<br />
        ผลรวม</th>
    </tr>
       <?php while($rs=mysqli_fetch_array($qr)){ 
	   		$rtag=$rs['tag_no'];
	   		  ?>
      <tr  <?php $v =0; $v = $v + 1; echo  icolor($v); ?>height="28" onMouseOver="className=&quot;over&quot;"  onMouseOut="className=&quot;&quot;" align="center">
      <td ><?=$i?></td>
      <td><?=$rs['model_name']?></td>
      <td ><?=$rs['model_kanban']?></td>
      <td><?=$rs['shift']?></td>
      <td><?=$rs['dates']?></td>
      <td><?=$rs['min_serial']?></td>
      <td><?=$rs['min_time']?></td>
      <td><?=$rs['max_serial']?></td>
      <td><?=$rs['max_time']?></td>
      <td><div class="tmagin_left"><?=$rs['sumserial']?></div></td>
    </tr>
	 <?php 
        $i++;
     
        }//	while($rsp=mysql_fetch_array($qrp)){
    ?>
  
  </table>
  
  <?php
								
		 if($total>0){ ?>  
<div class="browse_page" >
			  <?php       @page_navigator_user($before_p,$plus_p,$total,$total_p,$chk_page,base64_encode('tag_sum'),$txtsearsh);  	  ?>
  </div>  
<?php }
				
			}else{
					echo "<br/><br/><br/><center><div class='table_comment' >No have Data  ";
		

			}//if(rows($qr)<>0){
		 ?>
</div>
  

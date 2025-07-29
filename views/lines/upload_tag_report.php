

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

 <div class="rightPane" align="center">

<?php

	 		   $q="SELECT a.id_update,a.last_tag_id,a.status_update, DATE_FORMAT(a.date_update, '%d-%b-%Y %H:%i') AS date_upload
					FROM ".DB_DATABASE1.".fgt_update_tag a 
					WHERE a.type_status = 'Tag' 
					ORDER BY a.date_update DESC  ";
				$qr=mysqli_query($con, $q);
				$total=mysqli_num_rows($qr);  
				if($total<>0){	
								$e_page=15; // ????? ?????????????????????????????     
								if(!isset($_GET['s_page']) ){     //or !empty($txtsearsh)
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
					$i=1;
								
				?>
	
<table width="576" border="1" bordercolor="#CC9966"class="table01" align="center">
    <tr >
      <th height="26" colspan="4">
      <div align="center">Upload Tag Report<br />
        รายงานการส่งข้อมูลไปที่ระบบหลัก </div>  </th>
      </tr>  
    <tr>
      <th width="5%" height="26">No.<br/>ลำดับ</th>
      <th width="25%">Last Tag no.<br/>ไอดีล่าสุด</th>
      <th width="26%">Upload by<br/>ผู้ส่งข้อมูล</th>
      <th width="44%">Date Upload<br/>วันที่ส่งข้อมูล</th>
    </tr>
       <?php while($rs=mysqli_fetch_array($qr)){  ?>
      <tr  <?php $v =0; $v = $v + 1; echo  icolor($v); ?>height="28" onMouseOver="className=&quot;over&quot;"  onMouseOut="className=&quot;&quot;" align="center">
      <td><?=$i?></td>
      <td><?=$rs['last_tag_id']?></td>
      <td><?=$rs['status_update']?></td>
      <td><?=$rs['date_upload']?></td>
    </tr> 
	 <?php 
        $i++;
        }//	while($rsp=mysql_fetch_array($qrp)){
    ?>
  </table>
    <?php
								
		 if($total>0){ ?>  
<div class="browse_page" >
			  <?php       @page_navigator_user($before_p,$plus_p,$total,$total_p,$chk_page,base64_encode('upload_tag_report'),$txtsearsh);  	  ?>
  </div>  
<?php }
				
			}else{
					echo "<br/><br/><br/><center><div class='table_comment' >No hava History Update Master Data  ";
		

			}//if(rows($qr)<>0){
		 ?>
</div>
  
</div>
  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

 
 <div class="rightPane" align="center">

<?php

	 		   $q="SELECT id_update,last_id_srvupdate,type_data,user_update,date_update ,b.line_name,
					 DATE_FORMAT(date_update, '%d-%b-%Y %H:%i') AS date_modify
					FROM ".DB_DATABASE1.".fgt_update_master a 
					LEFT JOIN ".DB_DATABASE1.".view_line b ON a.user_update=b.line_id
					WHERE b.line_id = '$user_login'
					ORDER BY date_update DESC  ";
				$qr=mysqli_query($con, $q);
				$total=mysqli_num_rows($qr);  
				
		
					if($total<>0)			
				{	
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
	
<table width="651" border="1" bordercolor="#CC9966"class="table01" align="center">
    <tr >
      <th height="26" colspan="5">
      <div align="center">Update Master Data Report <br />
      (รายงานการอัพเดตข้อมูลจากระบบหลัก)</div>  </th>
      </tr>  
    <tr>
      <th width="5%" height="26">No.<br/>ลำดับ</th>
      <th width="20%">Type Data<br/>ประเภทข้อมูล</th>
      <th width="28%">Last ID Server Updated<br/>รหัสล่าสุดที่อัพเดต</th>
      <th width="18%">User Update<br/>ผู้อัพเดต</th>
      <th width="29%">Date Updated<br/>วันที่อัพเดต</th>
    </tr>
       <?php while($rs=mysqli_fetch_array($qr)){  ?>
      <tr  <?php $v =0; $v = $v + 1; echo  icolor($v); ?>height="28" onMouseOver="className=&quot;over&quot;"  onMouseOut="className=&quot;&quot;" align="center">
      <td><?=$i?></td>
      <td><?=$rs['type_data']?></td>
      <td><?=$rs['last_id_srvupdate']?></td>
      <td><?=$rs['line_name']?></td>
      <td><?=$rs['date_modify']?></td>
    </tr>
	 <?php 
        $i++;
        }//	while($rsp=mysql_fetch_array($qrp)){
    ?>
  </table>
    <?php
								
		 if($total>0){ ?>  
<div class="browse_page" >
			  <?php       @page_navigator_user($before_p,$plus_p,$total,$total_p,$chk_page,base64_encode('update_master_data_report'),$txtsearsh);  	  ?>
  </div>  
<?php }
				
			}else{
					echo "<br/><br/><br/><center><div class='table_comment' >No hava History Update Master Data  ";
		

			}//if(rows($qr)<>0){
		 ?>
</div>
  
</div>
  

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<div class="body_resize"  align="center"> 

 <form id="form1" name="form1" method="post" action="">
   <table width="710" border="1" align="center" class="table01" >
     <tr>
       <td width="366" height="37"><div class="tmagin_right">Search : Model Code or Model No. or Model Name</div> </td>
       <td width="328">
              <input type="text" name="tsearch" id="tsearch" style=" width:180px;"  value="<?php echo @$_POST['tsearch']?>"/>
       <input type="submit" name="button" id="button" value="Submit" /></td>
     </tr>
   </table>
</form>   
    <?php
        	if(!empty($_POST['tsearch'])|| !empty($_GET['serh'])){
				if(!empty($_POST['tsearch'])){$txtsearsh=$_POST['tsearch'];}else{$txtsearsh=$_GET['serh']; }
					$x="WHERE  ((model_code LIKE '%$txtsearsh%')  OR  (tag_model_no LIKE '%$txtsearsh%') OR  (model_name LIKE '%$txtsearsh%') )";
				}else{ $x=""; }
         	  	  $q="SELECT id_model,model_code,tag_model_no,label_model_no,model_name,std_qty,
						customer,customer_part_no,customer_part_name,model_picture,
						CASE status_tag_printing WHEN 0 THEN 'FG Tag, Supplier Tag' WHEN 1 THEN 'Only FG Tag'  ELSE 'No Tag' END AS sptag
						FROM ".DB_DATABASE1.".fgt_model 
						$x
						GROUP BY id_model
						ORDER BY tag_model_no  ";
				//echo  $q;
					$qr = mysqli_query($con, $q);
					$total=mysqli_num_rows($qr);  
					
					if($total<>0)	{	
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
<div class="rightPane">  

  <table width="98%" height="123" border="1" bordercolor="#CC9966"class="table01" align="center">
    <tr >
      <th height="28" colspan="11">
        <div align="center">Model Master Report (รายงานโมเดล)</div>      </th>
      </tr>
      
    <tr>
      <th width="3%" height="28">No.<br/>ลำดับ</th>
      <th width="6%">Model Code<br/>รหัสโมเดล</th>
       <th width="11%"><span class="tmagin_right">Model No. (Tag)<br/>
       หมายเลขโมเดล</span></th>
      <th width="12%">Model No.(Label)<br/>
      หมายเลขโมเดล</th>
      <th width="7%">Model Name<br/>ชื่อโมเดล</th>
      <th width="7%">Standard Qty.<br/>จำนวนมาตรฐาน</th>
      <th width="9%">Customer<br/>ลูกค้า</th>
      <th width="13%">  Customer Part  No. <br/> หมายเลขสิ้นค้า</th>
      <th width="9%">Customer Part  name<br/>ชื่อสินค้า</th>
      <th width="13%">Type Tag <br/>ประเภทแท็ก</th>
      <th width="10%">Model Image<br/>ภาพ</th>
    </tr>
       <?php while($rs=mysqli_fetch_array($qr)){   ?>
      <tr  <?php $v =0; $v = $v + 1; echo  icolor($v); ?> onMouseOver="className=&quot;over&quot;"  onMouseOut="className=&quot;&quot;" align="center">
      <td height="30px"><?=$i?></td>
      <td height="30px"> <?=$rs['model_code']?> </td> 
      <td><?=$rs['tag_model_no']?></td>
      <td><?=$rs['label_model_no']?></td>
      <td><?=$rs['model_name']?></td>
      <td><?=$rs['std_qty']?></td>
      <td><?=$rs['customer']?></td>
      <td><?=$rs['customer_part_no']?></td>
      <td height="30px"><?=$rs['customer_part_name']?></td>
      <td height="30px"><Div class="tmagin_right"><?=$rs['sptag']?></Div></td>
      <td height="30px"><img src="<?=HTTP_SERVER.DIR_SRV_PAGE.DIR_VIEWS.DIR_UPLOAD.DIR_MPIC.$rs['model_picture']?>" /></td>
    </tr>
	 <?php 
        $i++;
     
        }//	while($rsp=mysql_fetch_array($qrp)){
    ?>
  
  </table>
   <?php
								
		 if($total>0){ ?>  
<div class="browse_page" >
			  <?php       @page_navigator_user($before_p,$plus_p,$total,$total_p,$chk_page,base64_encode('model_list'),$txtsearsh);  	  ?>
  </div>  
<?php }
				
			}else{
					echo "<br/><br/><br/><center><div class='table_comment' >No hava Data ";

			}//if(rows($qr)<>0){
		 ?>

</div>

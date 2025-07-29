
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 <div class="rightPane" align="center">
 <form id="form1" name="form1" method="post" action="">
   <table width="750" border="1" align="center" class="table01" >
     <tr>
       <td width="397" height="37"><div class="tmagin_right"><b>Search by :</b> Tag No., FG Tag Model, Print Date (Exp.yyyy-mm-dd)</div> </td>
       <td width="337">
              <input type="text" name="tsearch" id="tsearch" style=" width:180px;"  value="<? echo @$_POST['tsearch']?>"/>
       <input type="submit" name="button" id="button" value="Search" /></td>
     </tr>
   </table>
</form>   
 

<?php
			if(!empty($_POST['tsearch'])|| !empty($_GET['serh'])){
				if(!empty($_POST['tsearch'])){$txtsearsh=$_POST['tsearch'];}else{$txtsearsh=$_GET['serh']; }
					$x="AND   ((a.tag_no LIKE '%$txtsearsh%') or (a.bsi_date LIKE '%$txtsearsh%') or (a.model_kanban LIKE '%$txtsearsh%'))";
				}else{ $x=""; }
         	  	  
		  $q="SELECT a.tag_no,IFNULL(a.model_kanban,'-') AS modeln,a.bsi_line_id,
				DATE_FORMAT(a.bsi_date, '%d-%b-%Y %H:%i')  AS bsidate,b.line_name,a.bsi_model,a.bsi_tag_scan
				 FROM ".DB_DATABASE1.".fgt_srv_tag a 
				 LEFT JOIN ".DB_DATABASE1.".view_line b ON a.bsi_line_id=b.line_id
				 WHERE  a.bsi_line_id='$user_login'
			 	$x 
				ORDER BY a.bsi_date DESC";
				
			$qr=mysqli_query($con, $q);
			$total=mysqli_num_rows($qr);
			//echo   $q;
					$i=1;
					if($total<>0)			
				{	
								$e_page= 15; // ????? ???????????????????????????
								
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
								
							?>
	


<table width="770px" border="1" bordercolor="#CC9966"class="table01" align="center">
    <tr>
      <th height="30" colspan="7"><span class="Arial_14_black">BSI Scaning</span> today report</th>
      </tr>
    <tr>
      <th width="6%" height="30">No.</th>
      <th width="17%"><span class="tmagin_left">FG Tag</span></th>
      <th width="17%">FG Tag Model</th>
      <th width="17%">BSI Scan</th>
      <th width="13%">Line</th>
      <th width="17%">Date scan </th>
      <th width="13%">Status </th>
      </tr>
   
    <?php while($rs=mysql_fetch_array($qr)){  ?>
   	   <tr  <?php  $v = 0; echo icolor($v); $v = $v + 1; ?> onMouseOver="className=&quot;over&quot;"  onMouseOut="className=&quot;&quot;" height="25px" >
   	     <td height="34" ><div align="center">
   	       <?=$i?>
 	       </div></td>
   	     <td><div  class="tmagin_right"><?php 
		 if($rs['modeln']=='-'){
		  echo $rs['bsi_tag_scan'];//TAG OLD V.
		  }else{
			  echo $rs['tag_no'];//New TAG from fgtag system
			  }

		 //echo $rs['tag_no'];?></div></td>
   	     <td><div align="center"><?php echo $rs['modeln'];?></div></td>
   	     <td><div align="center"><?php echo $rs['bsi_model'];?></div></td>
         <td ><div  class="tmagin_right"><?php echo $rs['line_name'];?></div></td>
   	     <td align="center"><div  class="tmagin_right"><?php echo $rs['bsidate'];?></div></td>
            <?php if(strtolower($rs['modeln'])==strtolower($rs['bsi_model'])){ $st="<b>Match</b>";$bg="#009900";$tt="[FG Tag Model] equal [BSI Scan]";}
		  		else{ $st="<b>NG</b>";$bg="#FF0000";$tt="[FG Tag Model] not equal [BSI Scan]";}?>
         <td bgcolor="<?=$bg;?>" title="<?=$tt;?>" align="center" ><div><?=$st;?></div></td>
   	     </tr>
    <?php						
		$i++;	
			}
		
    ?>
  </table>

  <?php
								
		 if($total>0){ ?>  
<div class="browse_page" >
			  <?php       @page_navigator_user($before_p,$plus_p,$total,$total_p,$chk_page,base64_encode('bsi_report'),$txtsearsh);  	  ?>
  </div>  
<?php }
				
			}else{
					echo "<br/><br/><br/><center><div class='table_comment' >No hava Data  ";
		

			}//if(rows($qr)<>0){
		 ?> </center> 



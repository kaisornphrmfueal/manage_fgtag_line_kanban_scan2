
<?php
//include('../../includes/configure_srv.php');
/*function sumem_work($work_id){
	$sqls="SELECT COUNT(operater_id) AS sum_em FROM ".DB_DATABASE1.".fgt_operator WHERE work_id = '$work_id'";		
	$qrs=mysql_query($sqls);
	$rss=mysql_fetch_array($qrs);
	return $rss['sum_em'];
	}*/

?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<div class="body_resize"  align="center"> 

 <form id="form1" name="form1" method="post" action="index.php?id=<?=base64_encode('operator')?>" >
   <table width="50%" border="1" align="center" class="table01"  >
     <tr>
       <td width="225" height="37"><div class="tmagin_right">Search : Work Date</div> </td>
       <td width="323">

<!--<input type="text" name="dateInput" id="dateInput" value="" />--> 
              <input type="text" name="tsearch" id="tsearch" value="<?php echo @$_POST['tsearch']?>"/>
                 <img src="../../images/calendar-icon2.png"  align="absmiddle" style="cursor:pointer " onClick="displayDatePicker('tsearch')" />  
       <input type="submit" name="button" id="button" value="Submit" /></td>
     </tr>
   </table>
</form>   
    <?php	
        	if(!empty($_POST['tsearch'])|| !empty($_GET['serh'])){
				if(!empty($_POST['tsearch'])){$txtsearsh=$_POST['tsearch'];}else{$txtsearsh=$_GET['serh']; }
					$x="AND ( work_date LIKE '%$txtsearsh%' )  ";

				}else{ $x=""; }
				
	
			
			  	 $q="SELECT work_id,line_id,work_date,shift,leader_day,leader_name_day,
				 leader_night,leader_name_night,floater_day,floater_name_day,floater_night,
				 floater_name_night,emp_print_tag_day,emp_print_tag_name_day,emp_print_tag_night,
				 emp_print_tag_name_night,
				 	DATE_FORMAT(work_date, '%d-%b-%Y') AS wdate 
						FROM  ".DB_DATABASE1.".fgt_leader 
						
						WHERE line_id='$user_login'  AND leader_name_day <> '' 
						$x
						GROUP BY work_id
						ORDER BY work_id DESC";	
				//echo  $q."<BR>";
					
					$qr = mysqli_query($con, $q);
					$total=mysqli_num_rows($qr);  
					
					if($total<>0)	{	
								$e_page=10; // ????? ?????????????????????????????     
								
								
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
<div class="rightPane" align="center"  >

  <table width="97%" height="111" border="1" bordercolor="#CC9966"class="table01">
    <tr >
      <th height="36" colspan="7">
      <div align="center">Operator</div> </th>
    </tr>
      
    <tr>
      <th width="15%" height="28">Work Date</th>
      <th width="14%">Leader Day</th>
      <th width="15%">Floater Day</th>
      <th width="14%">Printer Day</th>
      <th width="14%">Leader Night</th>
      <th width="14%">Floater Night</th>
      <th width="14%">Printer Night</th>
    </tr>
       <?php while($rsp=mysqli_fetch_array($qr)){  
			$workid=$rsp['work_id'];
	 	 	/* $qp="SELECT work_id,line_id,work_date,shift,leader_day,leader_name_day,leader_night,leader_name_night,floater_day,floater_name_day,floater_night,floater_name_night,emp_print_tag_day,emp_print_tag_name_day,emp_print_tag_night,emp_print_tag_name_night,emp_insert,date_insert,emp_modify,date_modify
			FROM ".DB_DATABASE1.".fgt_leader 

			WHERE ( line_id ='".$rs['line_id']."'  AND work_date ='".$rs['work_date']."')
			GROUP BY shift,b.line_id,b.work_id 
			ORDER BY shift,b.work_id ";*/
	
		//echo '<BR>'.$qp;
			
		
	   ?>
       
      <tr  <?php $v =0; $v = $v + 1; echo  icolor($v); ?> onMouseOver="className=&quot;over&quot;"  onMouseOut="className=&quot;&quot;">
      <td height="30px" align="center"  > <?php echo $rsp['wdate'];?></td>
      <td ><div class="tmagin_right"><?php echo $rsp['leader_name_day']; ?></div></td>
      <td ><div class="tmagin_right"> <?php echo $rsp['floater_name_day']; ?></div></td>
    
          <td ><div class="tmagin_right"> <?php echo $rsp['emp_print_tag_name_day']; ?></div></td>
          <td ><div class="tmagin_right"><?php echo $rsp['floater_name_night']; ?></div></td>
          <td ><div class="tmagin_right"> <?php echo $rsp['floater_name_night']; ?></div></td>
          <td ><div class="tmagin_right"> <?php echo $rsp['emp_print_tag_name_night']; ?></div></td>
    </tr>
        
		
     
    <?php 

			}//while($rs=mysql_fetch_array($qr)){
	?>
  
  </table>
    <?php						
		 if($total>0){ ?>  
<div class="browse_page" >
			  <?php       @page_navigator_user($before_p,$plus_p,$total,$total_p,$chk_page,base64_encode('operator'),$txtsearsh);  	  ?>
  </div>  
<?php }
				
			}else{
					echo "<br/><br/><br/><center><div class='table_comment' >No have data...  ";

			}//if(rows($qr)<>0){
		 ?>

</div>
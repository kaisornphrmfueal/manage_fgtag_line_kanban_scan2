
<?
include('../../includes/configure_srv.php');
function sumem_work($work_id){
	$sqls="SELECT COUNT(operater_id) AS sum_em FROM ".DB_DATABASE1.".fgt_operator WHERE work_id = '$work_id'";		
	$qrs=mysql_query($sqls);
	$rss=mysql_fetch_array($qrs);
	return $rss['sum_em'];
	}

?>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<div class="body_resize"  align="center"> 

 <form id="form1" name="form1" method="post" action="index.php?id=<?=base64_encode('operator')?>" >
   <table width="50%" border="1" align="center" class="table01"  >
     <tr>
       <td width="225" height="37"><div class="tmagin_right">Search : Work Date</div> </td>
       <td width="323">

<!--<input type="text" name="dateInput" id="dateInput" value="" />--> 
              <input type="text" name="tsearch" id="tsearch" value="<? echo @$_POST['tsearch']?>"/>
                 <img src="../../images/calendar-icon2.png"  align="absmiddle" style="cursor:pointer " onClick="displayDatePicker('tsearch')" />  
       <input type="submit" name="button" id="button" value="Submit" /></td>
     </tr>
   </table>
</form>   
    <?	
        	if(!empty($_POST['tsearch'])|| !empty($_GET['serh'])){
				if(!empty($_POST['tsearch'])){$txtsearsh=$_POST['tsearch'];}else{$txtsearsh=$_GET['serh']; }
					$x="AND ( b.work_date LIKE '%$txtsearsh%' )  ";

				}else{ $x=""; }
				
	
			
			  	 $q="SELECT b.work_date,b.shift,b.line_id,b.work_id,a.line_name,
				 	DATE_FORMAT(b.work_date, '%d-%b-%Y') AS wdate 
						FROM  ".DB_DATABASE1.".fgt_leader b
						LEFT JOIN ".DB_DATABASE1.".view_line  a ON b.line_id=a.line_id
						WHERE a.line_id='$user_login'
						$x
						GROUP BY b.work_date,b.line_id
						ORDER BY b.work_date DESC";	
					//echo  $q."<BR>";
					
					$qr = mysql_query($q,$con2);
					$total=mysql_num_rows($qr);  
					
					if($total<>0)	{	
								$e_page=10; // ????? ?????????????????????????????     
								
								
								if(!isset($_GET['s_page']) ){     //or !empty($txtsearsh)
									$_GET['s_page']=0;     
								}else{     
									$chk_page=$_GET['s_page'];       
									$_GET['s_page']=$_GET['s_page']*$e_page;     
								}     
								$q.=" LIMIT ".$_GET['s_page'].",$e_page";  
								$qr=mysql_query($q);  
								if(mysql_num_rows($qr)>=1){     
									@$plus_p=($chk_page*$e_page)+mysql_num_rows($qr);     
								}else{     
									@$plus_p=($chk_page*$e_page);         
								}     
								$total_p=ceil($total/$e_page);     
								@$before_p=($chk_page*$e_page)+1; 
							
					
							?>
<div class="rightPane" align="center"  >

  <table width="97%" height="206" border="1" bordercolor="#CC9966"class="table01">
    <tr >
      <th height="30" colspan="9">
      <div align="center">Operator</div> </th>
    </tr>
      
    <tr>
      <th height="28">Work Date</th>
      <th>Line</th>
      <th>Shift</th>
      <th>Leader</th>
      <th>Floater</th>
      <th>Sum operater</th>
      <th>Update</th>
      <th>View</th>
      <th>Add</th>
    </tr>
       <?php while(@extract($rs=mysql_fetch_array($qr))){  
			$workid=$rs['work_id'];
	 	 	 $qp="SELECT b.work_id,b.line_id,b.work_date,b.shift,
			IF(LOWER(b.shift)='day',b.leader_day,b.leader_night)AS leader,
			IF(LOWER(b.shift)='day',CONCAT(d.emp_id,' ',d.name_en,' ',d.lastname_en),CONCAT(e.emp_id,' ',e.name_en,' ',e.lastname_en))AS sname_leader,
			IF(LOWER(b.shift)='day',b.floater_day,b.floater_night)AS floater,
			IF(LOWER(b.shift)='day',CONCAT(f.emp_id,' ',f.name_en,' ',f.lastname_en),CONCAT(g.emp_id,' ',g.name_en,' ',g.lastname_en))AS sname_floater,
			b.leader_day,b.leader_night,b.floater_day,b.floater_night
			FROM ".DB_DATABASE1.".fgt_leader b 
			LEFT JOIN ".DB_DATABASE5.".so_employee_data d ON d.emp_id=b.leader_day
			LEFT JOIN ".DB_DATABASE5.".so_employee_data e ON e.emp_id=b.leader_night
			LEFT JOIN ".DB_DATABASE5.".so_employee_data f ON f.emp_id=b.floater_day
			LEFT JOIN ".DB_DATABASE5.".so_employee_data g ON g.emp_id=b.floater_night
			WHERE ( b.line_id ='".$rs['line_id']."'  AND b.work_date ='".$rs['work_date']."')
			GROUP BY b.shift,b.line_id,b.work_id 
			ORDER BY b.shift,b.work_id ";
	
		//echo '<BR>'.$qp;
			$qrp=mysql_query($qp,$con2);
			$np=mysql_num_rows($qrp);	
		
	   ?>
       
      <tr  <?php $v =0; $v = $v + 1; echo  icolor($v); ?> onMouseOver="className=&quot;over&quot;"  onMouseOut="className=&quot;&quot;">
      <td align="center" height="30px" <? if($np>1){ $nps = $np+1;echo "rowspan='".$nps."'";}?> > <?php echo $rs['wdate'];?></td>
      <td align="center" <? if($np>1){ $nps = $np+1;echo "rowspan='".$nps."'";}?>>
      <?=$rs['line_name']?>
      </td>
     

      <?
		 //Start multi row&column
		  if($np==1){ 
         	while($rsp=mysql_fetch_array($qrp)){

		 ?>
          <td align="center"><?=$rsp['shift'];?></td>
          <td ><div class="tmagin_right"><?php echo $rsp['sname_leader']; ?></div></td>
          <td ><div class="tmagin_right"> <?php echo $rsp['sname_floater']; ?></div></td>
          <td align="center">  
          <?
           $ssumo= sumem_work($rsp['work_id']);
	 		echo $ssumo;
		  ?>
          </td>
         
          <td align="center"> 
           <? if($ssumo<>'0'){?>
          <a href="index.php?id=<?=base64_encode('setup_operetor_emp')?>&wid=<?=$rsp['work_id'];?>&st=edit"  >
            <img src="../../images/001_45.gif" alt="Update" /><? $rsp['work_id'];?></a>
            <? }?>
          </td>
          <td align="center"><a href="#" onClick="javascript:openWins('windows.php?win=view_op&idm=<?=$rsp['work_id']?>', '_blank',1100,750,1,1, 0, 0, 0);return false;" >
              
              <img src="../../images/view2.png" width="24" height="24"  border="0" alt="View"/></a> 
        </td>
          <td align="center"><a href="index.php?id=<?=base64_encode('setup_operetor_emp')?>&wid=<?=$rsp['work_id'];?>&st=add"  >
        <img src="../../images/001_01.gif" alt="Add"/><? $rsp['work_id'];?></a>
        </td>
             <?
		 	}//	while($rsp=mysql_fetch_array($qrp)){
		}//if($np>1){ 
		?>
</tr>
        <?
		 //Start multi row&column
		  if($np>1){ 
         	while($rsp=mysql_fetch_array($qrp)){
					
		 ?>
            <tr  <?php $v =0; $v = $v + 1; echo  icolor($v); ?> onMouseOver="className=&quot;over&quot;"  onMouseOut="className=&quot;&quot;">
          <td align="center"> <?=$rsp['shift'];?></td>
          <td ><div class="tmagin_right"><?php echo  $rsp['sname_leader']; ?></div></td>
          <td ><div class="tmagin_right"> <?php echo $rsp['sname_floater']; ?></div></td>
          <td align="center" >  
          <?
           $ssumo= sumem_work($rsp['work_id']);
	 		echo $ssumo;
		  ?>
          </td>
         
          <td align="center" > 
           <? if($ssumo<>'0'){?>
          <a href="index.php?id=<?=base64_encode('setup_operetor_emp')?>&wid=<?=$rsp['work_id'];?>&st=edit"  >
            <img src="../../images/001_45.gif" alt="Update" /><? $rsp['work_id'];?></a>
            <? }?>
          </td>
          <td align="center">  <a href="#" onClick="javascript:openWins('windows.php?win=view_op&idm=<?=$rsp['work_id']?>', '_blank',1100,750,1,1, 0, 0, 0);return false;" >
              
              <img src="../../images/view2.png" width="24" height="24"  border="0" alt="View"/></a> 
            </td>
          <td align="center"><a href="index.php?id=<?=base64_encode('setup_operetor_emp')?>&wid=<?=$rsp['work_id'];?>&st=add"  >
        <img src="../../images/001_01.gif" alt="Add"/><? $rsp['work_id'];?></a>
        </td>
            </tr>
             <?
		 	}//	while($rsp=mysql_fetch_array($qrp)){
		}//if($np>1){ 
          //END  multi row&column
		  
		 ?>
     
    <? 

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
<?php
include('../../includes/configure.php');
error_reporting( error_reporting() & ~E_NOTICE );

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
nnn
<?php

	 $work_id=$_GET['idm'];
	
	echo "==". $sqlm="SELECT a.work_date,a.shift,a.line_id,b.line_name,a.work_id,
			IF(LOWER(a.shift)='day',CONCAT(d.emp_id,' ',d.name_en,' ',d.lastname_en),
			CONCAT(e.emp_id,' ',e.name_en,' ',e.lastname_en))AS sname_leader,
			IF(LOWER(a.shift)='day',CONCAT(f.emp_id,' ',f.name_en,' ',f.lastname_en),
			CONCAT(g.emp_id,' ',g.name_en,' ',g.lastname_en))AS sname_floater  
					FROM ".DB_DATABASE1.".fgt_leader a
					LEFT JOIN ".DB_DATABASE1.".view_line b ON a.line_id = b.line_id
					LEFT JOIN ".DB_DATABASESSO.".so_employee_data d ON d.emp_id=a.leader_day
					LEFT JOIN ".DB_DATABASESSO.".so_employee_data e ON e.emp_id=a.leader_night
					LEFT JOIN ".DB_DATABASESSO.".so_employee_data f ON f.emp_id=a.floater_day
					LEFT JOIN ".DB_DATABASESSO.".so_employee_data g ON g.emp_id=a.floater_night
					WHERE a.line_id <> 0 AND b.line_name <> '' 
					AND a.work_id = '$work_id' 
					GROUP BY a.line_id   ";
	$qrm=mysqli_query($con, $sqlm);
	$rsm=mysqli_fetch_array($qrm);
	$work_date =$rsm['work_date'];
	$shift =$rsm['shift'];
	$line_id =$rsm['line_id'];
	$line_name =$rsm['line_name'];
	$work_id =$rsm['work_id'];
	   
	?>
    
	<table width="1000px" border="0" align="center" class="table01"  >
     <tr>
    <th height="27" colspan="7">Operator Setup Report</th>
    </tr>
  <tr>
	<td width="24%" height="40" ><div align="center" ><b>Work Date: <?=$rsm['work_date']?></b></div></td>
	<td width="20%" ><div  align="center" ><b>Line: <?=$rsm['line_name']?></b></div></td>
	<td width="22%"><div  align="center" ><b>Shift : <?=$rsm['shift']?></b></div></td>
	<td width="34%">
    <div align="left" style="margin-left:30px;margin-top:5px;"><b>Leader : <?=$rsm['sname_leader']?></b></div>
    <br />
    <div align="left" style="margin-left:30px;margin-bottom:5px;"><b>Floater : <?=$rsm['sname_floater']?></b></div>
    
    </td>
  </tr>
</table>


<!-- ------------------------- SHOW EMP DATA ---------------------------------------!-->

   <?php
   		
			$sql = "SELECT c.emp_id,c.name_em,c.posit,c.dept_id,c.emp_shift,a.line_name,c.type_shift,a.process_id,c.dept_em
							FROM ".DB_DATABASE1.".fgt_operator a 
							LEFT JOIN ".DB_DATABASE1.".fgt_leader b ON a.work_id = b.work_id 
							LEFT JOIN ".DB_DATABASE1.".view_line_emp_prod c ON c.emp_id=a.operater_id
							WHERE a.work_id = '".$rsm['work_id']."'
							ORDER BY c.emp_id";
			
		echo "*-". $sql;
		$qr = mysqli_query($con, $sql);
		

		if(mysqli_num_rows($qr)<>0){
			$i=1;
	?>
<center>
  <form id="form3" name="form3" method="post" action="" >
  <table width="99%" height="55" border="1" class="table01" align="center">    
    <tr>
      <th width="5%" height="28" align="center">No.</th>
      <th width="7%" align="center">ID</th>
      <th width="17%" align="center">Name</th>
      <th width="13%" align="center">Line Name</th>
      <th width="15%" align="center">Department</th>
      <th width="15%" align="center">Position</th>
      <th width="9%" align="center">Type Shift</th>
      <th width="19%" align="center">Process Work</th>
    </tr>
  
    <?php $v =0; while($rs=mysqli_fetch_array($qr)){ 	?>
   	   <tr  <?php  echo icolor($v);  $v = $v + 1; ?> onMouseOver="className=&quot;over&quot;"  onMouseOut="className=&quot;&quot;" height="25px" align="center">
   	     <td ><div align="center">
   	       <?=$i?>
 	       </div></td>
         
   	     <td><div align="center"><?=$rs['emp_id']?></div></td> 
   	     <td><div  class="tmagin_right"><?=$rs['name_em']?></div></td>
   	     <td><div  class="tmagin_right" ><?=$rs['line_name']?></div> </td>
   	     <td><div  class="tmagin_right" ><?=$rs['dept_em']?></div> </td>
   	     <td><div  class="tmagin_right" ><?=$rs['posit']; ?></div></td>
         <td><div  align="center" ><?=$rs['type_shift']?></div></td>
         <td>
       <?php 


			$sql_dept= "SELECT process_id,process_name,deleted FROM ".DB_DATABASE1.".fgt_process 
			WHERE deleted = 'N' AND process_id = '".$rs['process_id']."' ";
        	$qr_dept = mysqli_query($con, $sql_dept);
  			while ($re_dept = mysqli_fetch_array($qr_dept)) {
				echo $re_dept['process_name'];
		
				}
			?>
         </td>
   	     
    </tr>

       
        <?php		$i++;
				}
				
				
		 ?>	
   	 
 
  </table>
  </form>
  </center>

<?php
		}else{ ?>
        		<br/><br/><br/><br/><br/>
				<center>
				  <div class="table_comment" >No have data...</div> </center>
		<?php	}//if(rows($qr)){
			
			
  ?>
 <center> 
<input type=button value="Close Window" onClick="javascript:window.close();">
 </center>

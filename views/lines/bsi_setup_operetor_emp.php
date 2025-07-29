<?php
error_reporting( error_reporting() & ~E_NOTICE );

function chk_data($work,$id){
	$con;
	$sql="SELECT * FROM ".DB_DATABASE1.".fgt_operator WHERE work_id = '$work' AND operater_id = '$id'";
	$num=mysqli_num_rows(mysqli_query($con, $sql));
	return $num;
	}	
if($_POST['button']=="Submit"){
	
			$idemp= $_POST['id_emp'];
			$idpro=$_POST['pro_work'];
			$pwork_id=$_POST['hidwork'];
			//echo "--->".$_POST['hidLinen_0067']."--->".$_POST['pro_work_0067'];
			 		//$j=0;
					foreach( $idemp as $emps ):
					
							if($emps!="" AND $emps!="Array"){
							//echo $emps;
							 //echo "<br/>Array Index [$j] : ".$emps." || ".$_POST["hidLinen_$emps"]." || ".$_POST["pro_work_$emps"];
							 $process_id=$_POST["pro_work_$emps"];
							 $line_name=$_POST["hidLinen_$emps"];
							 //$j++;
							 
							 if(chk_data($pwork_id,$idemp)=='0'){
							$sql_int="INSERT INTO ".DB_DATABASE1.".fgt_operator SET
											work_id = '$pwork_id',
											operater_id = '$emps',
											process_id = '$process_id',
											line_name = '$line_name',
											emp_insert = '$user_login',
											date_insert = '".date('Y-m-d H:i:s')."' ";	//
											log_hist($user_login,"Insert",$emps,"fgt_operator",$sql_int);		
							 }else{
								 
									$sql_int="UPDATE ".DB_DATABASE1.".fgt_operator SET
											process_id = '$process_id',
											emp_modify = '$user_login',
											date_modify = '".date('Y-m-d H:i:s')."' 
											WHERE work_id = '$pwork_id' AND operater_id = '$emps' ";
											log_hist($user_login,"Update",$emps,"fgt_operator",$sql_int);		 
								 
								 }
												
									$qr_int=mysqli_query($con, $sql_int);
									
									
								}//if($emps!="" AND $emps!="Array"){			
					endforeach ;

			gotopage("index_bsi.php?id=".base64_encode('bsi_setup_operetor_emp')."&wid=".$pwork_id."&st=add");
			 
	}else if($_POST['button']=="Update"){
		
			
			$pwork=$_POST['hidwork_1'];
			$pmax=$_POST['max_row'];
			
			for($j=0;$j<$pmax;$j++){
				
				//echo $_POST['pro_work1'][$j];
				//echo '=='.$_POST['emp'][$j];echo'<BR>';
				$sql_up="UPDATE ".DB_DATABASE1.".fgt_operator SET
							process_id = '".$_POST['pro_work1'][$j]."'
							WHERE operater_id = '".$_POST['emp'][$j]."' AND work_id = '$pwork' ";			
							$qr_up=mysqli_query($con, $sql_up);
							log_hist($user_login,"Update",$_POST['emp'][$j],"fgt_operator",$sql_up);
				}
		
					
			gotopage("index_bsi.php?id=".base64_encode('bsi_setup_operetor_emp')."&wid=".$pwork."&st=edit");
			 
	}
	
	
		
	if(!empty($_GET['wid']) AND !empty($_GET['del'])){
	
	$del="DELETE FROM prod_fg_tag.fgt_operator 
			WHERE work_id='".$_GET['wid']."' 
			and operater_id = '".$_GET['del']."'";
	$qr_del=mysqli_query($con, $del);		
	if($qr_del){
		//log_hist($user_login,"Delete",$_GET['del'],"fgt_operator",$del);
		gotopage("index_bsi.php?id=".base64_encode('bsi_setup_operetor_emp')."&wid=".$_GET['wid']."&st=".$_GET['st']);
		}
	
	}	
	
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" type="text/JavaScript">
function validate() {
			var id_emp = document.getElementById("id_emp").value;
			
					var total=""
					
					//alert(document.form2.id_emp.length);
					alert(document.form2.hdnMaxLine.value)
					for(var i=0; i < document.form2.id_emp.length; i++){

					total +=document.form2.id_emp[i].value + "\n"
					
					}
					
					if(total==""){
					alert("Please select employee");
					}  else{
					//alert (total);
						return true;
					}
					/*return false;
					}/* else{	alert (total); }*/
						/*	else{
						
						form2.appr_ok.disabled = true;   
						form2.appr_no.disabled = true; 
						form2.app_status.value = 'กรุณารอสักครู่...หน้าจอจะปีดเองอัตโนมัติหลังจากทำงานเสร็จ';  
						return true;  
  
						}*/
					
					

}///function validate(form) {

	<!-- Begin
function Check(chk)
{
	if(document.form2.Check_ctr.checked==true){
	for (i = 0; i < chk.length; i++)
	chk[i].checked = true ;
	}else{

		for (i = 0; i < chk.length; i++)
		chk[i].checked = false ;
		}
}

// End -->
  </script>
  
<?php // echo "==".$_GET['tid']; ?>
<?php

	$work_id=$_GET['wid'];
	
	$sqlm="SELECT a.work_date,a.shift,a.line_id,b.line_name,a.work_id,
IF(LOWER(a.shift)='day',CONCAT(d.emp_id,' ',d.name_en,' ',d.lastname_en),CONCAT(e.emp_id,' ',e.name_en,' ',e.lastname_en))AS sname_leader,
IF(LOWER(a.shift)='day',CONCAT(f.emp_id,' ',f.name_en,' ',f.lastname_en),CONCAT(g.emp_id,' ',g.name_en,' ',g.lastname_en))AS sname_floater  
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
    <th height="27" colspan="7">Operator Setup Selection</th>
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
<div style="width:99%" align="center">
<?php if($_GET['st']=='add'){?>  
<form id="form1" name="form1" method="post" action="">
<table width="623" border="1" class="table01" align="center">
          <tr>
                <td width="35%" height="28">
              	Search by : Line, Emp ID, Name
                </td>
            <td width="65%" colspan="2"><div class='tmagin_right' >
  
            <select name="ssearch" id="ssearch">
             <option value="">----Please select line----</option>
            <?php
            		$sqls="SELECT line_name 
							FROM ".DB_DATABASE1.".view_line_emp_prod
							WHERE line_name <>''
							GROUP BY line_name
							ORDER BY line_name ";
					$qrs=mysqli_query($con, $sqls);
					while($rss=mysqli_fetch_array($qrs)){
			?>
              <option value="<?=$rss['line_name']?>" <?php if($_POST['ssearch']==$rss['line_name']){ echo "selected='selected'";}?> >
			  <?=$rss['line_name']?></option>
              <?php
					}//	while($rss=fetch($qrs)){
			  ?>
            </select>
              <input type="text" name="txtsearch" value="<?=$_POST['txtsearch']?>" > 
            <input type="submit" name="button" id="button" value="Search" />

            </div></td>
          </tr>
       
</table>
<br />
</form>
</div>
   <?php
   		
		 if(!empty($_POST['txtsearch']) ){
				$x=" AND ((emp_id LIKE '%".$_POST['txtsearch']."%') 
				OR (name_em LIKE '%".$_POST['txtsearch']."%'))";	
			}elseif (!empty($_POST['ssearch'])){
				$x="AND (line_name LIKE '%".$_POST['ssearch']."%')  ";
			}elseif (!empty($_POST['txtsearch']) || !empty($_POST['ssearch'])){
				$x=" AND ((line_name LIKE '%".$_POST['ssearch']."%')  OR (emp_id LIKE '%".$_POST['txtsearch']."%') 
				OR (name_em LIKE '%".$_POST['txtsearch']."%'))";	
			}else{
				$x="";
			}
		
		
			/*if($_GET['st']=='add'){*/
			$sql = "SELECT emp_id,name_em,posit,dept_id,emp_shift,line_name,type_shift,dept_em 
					FROM ".DB_DATABASE1.".view_line_emp_prod 
					WHERE emp_id NOT IN (SELECT a.operater_id
							FROM ".DB_DATABASE1.".fgt_operator a
							LEFT JOIN ".DB_DATABASE1.".fgt_leader b ON a.work_id = b.work_id
							WHERE a.work_id = '".$rsm['work_id']."'  )
							$x
					 GROUP BY emp_id";
				//echo $sql;	 
		/*	}else if($_GET['st']=='edit'){
				
					$sql = "SELECT c.emp_id,c.name_em,c.posit,c.dept_id,c.emp_shift,c.line_name,c.type_shift,a.process_id,
							CONCAT('','edit') AS st
							FROM prod_fg_tag.fgt_operator a 
							LEFT JOIN prod_fg_tag.fgt_leader b ON a.work_id = b.work_id 
							LEFT JOIN prod_fg_tag.view_line_emp_prod c ON c.emp_id=a.operater_id
							WHERE b.work_date = '".$rsm['work_date']."' AND b.line_id = '".$rsm['line_id']."' $x
							
							UNION 
							
							SELECT emp_id,name_em,posit,dept_id,emp_shift,line_name,type_shift,'' as process_id,
							CONCAT('','add') AS st
							FROM prod_fg_tag.view_line_emp_prod 
							WHERE emp_id NOT IN (SELECT a.operater_id FROM prod_fg_tag.fgt_operator a 
										 LEFT JOIN prod_fg_tag.fgt_leader b ON a.work_id = b.work_id 
										 WHERE b.work_id = '".$_GET['wid']."' ) 
										 $x
										 GROUP BY emp_id ";
				
				}*/
			
		//echo  $sql;
		$qr = mysqli_query($con, $sql);
		

		if(mysqli_num_rows($qr)<>0){
			$i=1;
	?>
  
<form id="form2" name="form2" method="post" action="" onsubmit='return validate(this)' style="height:200px;
overflow:auto;" >
  <table width="85%" height="55" border="1" class="table01" align="center">

    <tr>
      <th height="30"><input type="submit" name="button" id="button" value="Submit" />
     
     
      <input type="hidden" name="hidwork" id="hidwork" value="<?=$_GET['wid']?>" />
      
      </th>

       <th height="30" colspan="8">Add Operator</th>
    </tr>
    
    <tr>
      <th width="8%" height="28" align="center">No.</th>
      <th width="4%" align="center">
       <input type="checkbox" name="Check_ctr" value="yes"  onClick="Check(document.form2.id_emp)"/>
     </th>
      <th width="4%" align="center">ID</th>
      <th width="18%" align="center">Name</th>
      <th width="11%" align="center">Line Name</th>
      <th width="11%" align="center">Department</th>
      <th width="18%" align="center">Position</th>
      <th width="7%" align="center">Type Shift</th>
      <th width="25%" align="center">Process Work</th>
      
    </tr>
  
    <?php $v =0; while($rs=fetch($qr)){ 	?>
   	   <tr <?php echo icolor($v); $v = $v + 1; ?> onMouseOver="className=&quot;over&quot;" onMouseOut="className=&quot;&quot;" align="center">
   	     <td ><div align="center">
   	       <?=$i?>
 	       </div></td>
         <td> 

        
      	<input name="id_emp[]<?=$i;?>"  id="id_emp" type="checkbox" value="<?=$rs['emp_id']?>"  />
        <input type="hidden" name="id_emp[]<?=$i;?>" id="id_emp" value="<?=$id_emp?>"/>
        

        </td>
   	     <td><div align="center"><?=$rs['emp_id']?></div></td> 
   	     <td><div  class="tmagin_right"><?=$rs['name_em']?></div></td>
   	     <td>
          <input type="hidden" name="hidLinen_<?=$rs['emp_id']?>" value="<?=$rs['line_name']?>">  
         <div  class="tmagin_right" ><b><?=$rs['line_name']?></b></div> </td>
   	     <td><div  class="tmagin_right" ><?=$rs['dept_em']?></div> </td>
   	     <td><div  class="tmagin_right" ><?=$rs['posit']; ?></div></td>
         <td><div  align="center" ><b><?=$rs['type_shift']?><b></div></td>
         <td>
         
         <select name="pro_work_<?=$rs['emp_id']?>"  id="pro_work"   style="width:100%"  >
         <option  value="">---- Please Select Process Work ---- </option>
        <?php
        $sql_dept= "SELECT * FROM ".DB_DATABASE1.".fgt_process WHERE deleted = 'N'  ";
        $qr_dept = mysqli_query($con, $sql_dept);
        
			while ($re_dept = mysqli_fetch_array($qr_dept)) {
				//echo "process=".$rs['process_id'];
				$dept=$re_dept['process_id'];
				$dept_name=$re_dept['process_name'];
				if($rs['process_id']==$re_dept['process_id']){
					$sel="selected ";
					//$bg="style='background-color:#F93'";
					}else{
						$sel="";
						}
			echo "<option $sel value='$dept' >$dept_name</option>";
			}
        ?>
        	</select>
		
         </td>
   	     
    </tr>

       
        <?php		$i++;
				}
		 ?>	
   	 
 
  </table>    

<input type="hidden" name="hdnLine" value="<?=$i;?>">    
   </form>

<?php
		}else{ ?>
        		<br/><br/><br/><br/><br/>
				<center>
				  <div class="table_comment" >No have data...</div> </center>
		<?php	}//if(rows($qr)){
  ?>
 <?php }?>
  <?php
			$sql = "SELECT c.emp_id,c.name_em,c.posit,c.dept_id,c.emp_shift,a.line_name,c.type_shift,a.process_id,c.dept_em
							FROM prod_fg_tag.fgt_operator a 
							LEFT JOIN prod_fg_tag.fgt_leader b ON a.work_id = b.work_id 
							LEFT JOIN prod_fg_tag.view_line_emp_prod c ON c.emp_id=a.operater_id
							WHERE a.work_id = '".$rsm['work_id']."'
							ORDER BY c.emp_id ";
		
		//echo  $sql;
		$qr = mysqli_query($con, $sql);
		$num_rows=mysqli_num_rows($qr);

		if($num_rows<>0){
			$i=1;
	?>
<br/>
<hr />
<center>
  <form id="form3" name="form3" method="post" action="" >
  <table width="90%" height="55" border="1" class="table01" align="center">

    <tr>
      <th height="30"><div align="center" >
      <?php if($_GET['st']=='edit'){?>
      <input type="submit" name="button" id="button" value="Update" 
      />
      <?php }?>
      </div><!-- onclick="return confirm('Are you sure you want to update?')";-->
     
     
      <input type="hidden" name="hidwork_1" id="hidwork_1" value="<?=$_GET['wid']?>" />
      <input type="hidden" name="max_row" id="max_row" value="<?=$num_rows?>" />
      
      
      </th>
     
       <th height="30" colspan="9">Operator Report</th>
    </tr>
    
    <tr>
      <th width="8%" height="28" align="center">No.</th>
      <th width="4%" align="center">ID</th>
      <th width="16%" align="center">Name</th>
      <th width="8%" align="center">Line Name</th>
      <th width="13%" align="center">Department</th>
      <th width="15%" align="center">Position</th>
      <th width="11%" align="center">Type Shift</th>
      <th width="17%" align="center">Process Work</th>
      <th width="8%" align="center">Delete</th>
      
    </tr>
  
    <?php $v =0; while($rs=fetch($qr)){ 	?>
   	   <tr  <?php  echo icolor($v);  $v = $v + 1; ?> onMouseOver="className=&quot;over&quot;"  onMouseOut="className=&quot;&quot;" height="25px" align="center">
   	     <td ><div align="center">
   	       <?=$i?>
 	       </div></td>
         
   	     <td><div align="center"><?=$rs['emp_id']?></div></td> 
   	     <td><div  class="tmagin_right"><?=$rs['name_em']?></div></td>
   	     <td><div  class="tmagin_right" ><?=$rs['line_name']?></div> </td>
   	     <td><div  class="tmagin_right" ><?=$rs['dept_em']?></div> </td>
   	     <td><div  class="tmagin_right" ><?=$rs['posit']; ?></div></td>
         <td><div  align="center" ><b><?=$rs['type_shift']?><b></div></td>
         <td>
       <?php 
	   
	   if($_GET['st']=='edit'){?>
       <select name="pro_work1[]<?=$i?>"  id="pro_work1"   style="width:100%"   >
  
        <?php
        $sql_dept= "SELECT * FROM ".DB_DATABASE1.".fgt_process WHERE deleted = 'N'  ";
        $qr_dept = mysqli_query($con, $sql_dept);
        echo "<option  value=''>---- Please Select Process Work ---- </option>";	
			while ($re_dept = mysqli_fetch_array($qr_dept)) {
			
				$dept=$re_dept['process_id'];
				$dept_name=$re_dept['process_name'];
				if($rs['process_id']==$re_dept['process_id']){

					$sel="selected ";
					}else{
						$sel="";
						}
				
			echo "<option $sel value='$dept'>$dept_name</option>";
			
			
			}
        ?>
        </select>
        <?php }else if($_GET['st']=='add'){
			
			 $sql_dept= "SELECT * FROM ".DB_DATABASE1.".fgt_process WHERE deleted = 'N' 
			 AND process_id = '".$rs['process_id']."' ";
        	$qr_dept = mysqli_query($con, $sql_dept);
        
			while ($re_dept = mysqli_fetch_array($qr_dept)) {

				echo $re_dept['process_name'];
			
			}
			
			}?>
		<input type="hidden" name="emp[]<?=$i?>" id="emp" value="<?=$rs['emp_id']?>" />
         </td>
         <td>
         <a href="index_bsi.php?id=<?=base64_encode('bsi_setup_operetor_emp')?>&wid=<?=$rsm['work_id']?>&del=<?=$rs['emp_id']?>&st=<?=$_GET['st']?>" 
         onclick="return confirm('Are you sure you want to Delete?')"><img src="../../images/001_29.gif" width="25" height="25" border="0"/></a>
         </td>
      </tr>

       
        <?php		$i++;
				}
				
				}
		 ?>	
   	 
 
  </table>
  </form>
  </center>
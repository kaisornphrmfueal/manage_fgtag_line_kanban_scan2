<?php
	//echo "-->".$emp_con=base64_decode($_GET['emp']);
	//echo "===".$_POST['scan2'];
	if(!empty($_POST['scan2']) &&  $_POST['scan2']=="Submit"){
				//Should be checking old tag or waiting tag!!!
				//Create Tag 
				$emp=$_POST['emp'];
				$mxtag=selectMxTag($user_login);
				$post_scan=$_POST['model'];
				$pmodel=substr($post_scan,0, 15);  
				$ptkstatus=substr($post_scan,15, 1);  
			//	$pline=$_POST['hslid'];
				$linesp=sprintf("%02d",$user_login);
				$mxtagsp=sprintf("%07d",$mxtag);
				$tagnon=$linesp.$mxtagsp;
		 	 	 $sqlckm="SELECT tag_no FROM  ".DB_DATABASE1.".fgt_srv_tag 
						 WHERE model_kanban = '$pmodel'
						 AND status_print in ('Wait','Not yet')
						 AND line_id ='$user_login'";
				$qrckm=mysqli_query($con, $sqlckm);
				if(mysqli_num_rows($qrckm)<>0){
					$rsckm=mysqli_fetch_array($qrckm);
					gotopage("index.php?id=".base64_encode('adjust_serial_scan')."&idwait=".base64_encode($rsckm['tag_no'])."&emp=$emp");
						
					}else{
				
					if( date("H:i:s") >= "07:50:00" AND date("H:i:s") <= "20:19:59"){
							$chshift="Day";
							$datework=date('Y-m-d');
						}else{
							$chshift="Night";
							if( date("H:i:s") > "20:19:59" AND date("H:i:s") <= "23:59:59"){
								$datework=date('Y-m-d');
							}else{
								$datework=date( "Y-m-d",  strtotime("-1 day") );
							}
						}
				$tagb=date("Ymd").$tagnon;
				
				if(strlen($post_scan)=="9"){
				$sql_tk="SELECT b.ticket_ref,b.ticket_qty ,a.std_qty,b.status_write ,b.model_no,a.id_model
						FROM  ".DB_DATABASE1.".fgt_model a
                        LEFT JOIN ".DB_DATABASE2.".rf_kanban_ticket b ON a.tag_model_no = b.model_no
						WHERE b.ticket_ref = '".$post_scan."' 
						AND b.status_write = '0'
						GROUP BY b.ticket_ref ";
				$qrctk=mysqli_query($con, $sql_tk);
				if(mysqli_num_rows($qrctk)<>0){
					$rsctk=mysqli_fetch_array($qrctk);	
						$ticket_no=	$rsctk['ticket_ref'];
						$tk_qty=$rsctk['ticket_qty'];
						$rspmodel=$rsctk['model_no'];
						$rsp_idmodel=$rsctk['id_model'];
					
						 $sqltg="INSERT INTO ".DB_DATABASE1.".fgt_srv_tag 
									SET line_id='$linesp', 
									tag_no= '$tagnon', 
									id_model='$rsp_idmodel', 
									model_kanban='$rspmodel',
									shift='$chshift', 
									fg_tag_barcode='$tagb',
									status_print='Not yet' ,
									matching_ticket_no='$ticket_no', ticket_qty='$tk_qty',kanban_status='N',
									date_insert ='".date('Y-m-d H:i:s')."',
									date_work ='".$datework."' ,
									work_id = (SELECT work_id FROM ".DB_DATABASE1.".fgt_leader  
											WHERE line_id = '".$linesp."' ORDER BY work_id DESC  LIMIT 1)"; 
							$qrtg=mysqli_query($con, $sqltg);
							log_hist($user_login,"New Tag",$mxtag,"fgt_srv_tag",$sqltg);
										
										$sqlutk="UPDATE  ".DB_DATABASE2.".rf_kanban_ticket SET
												 status_write=5, last_status='Reserved'
												 WHERE ticket_ref='$ticket_no'";
										$qrtk=mysqli_query($con, $sqlutk); //Reserved
					
						//gotopage("index.php?id=".base64_encode('printtag')."&idtg=".base64_encode($tagnon));
						gotopage("index.php?id=".base64_encode('adjust_serial_scan')."&idtg=".base64_encode($tagnon)."&emp=$emp");	
					}else{
						alert("ไม่มีข้อมูลในระบบ กรุณาติดต่อหัวหน้างาน");
						gotopage("index.php?id=".base64_encode('print'));
					} // END check tiket if(mysql_num_rows($qrctk)<>0){
		}else{
					
				$sql_tk="SELECT b.ticket_ref,b.ticket_qty ,a.std_qty,b.status_write,b.model_no
						FROM  ".DB_DATABASE1.".fgt_model a
                        LEFT JOIN ".DB_DATABASE2.".rf_kanban_ticket b ON a.tag_model_no = b.model_no
																			AND  a.std_qty = b.ticket_qty 
						WHERE b.model_no ='$pmodel'
						AND b.status_write = '0'
						ORDER BY b.ticket_ref  ASC LIMIT 1";
				$qrctk=mysqli_query($con, $sql_tk);
				if(mysqli_num_rows($qrctk)<>0){
					$rsctk=mysqli_fetch_array($qrctk);	
						$ticket_no=	$rsctk['ticket_ref'];
						$tk_qty=$rsctk['ticket_qty'];
						 $sqltg="INSERT INTO ".DB_DATABASE1.".fgt_srv_tag 
									SET line_id='$linesp', 
									tag_no= '$tagnon', 
									id_model=(SELECT id_model FROM ".DB_DATABASE1.".fgt_model  WHERE tag_model_no = '".$pmodel."'), 
									model_kanban='$pmodel',
									shift='$chshift', 
									fg_tag_barcode='$tagb',
									status_print='Not yet' ,
									matching_ticket_no='$ticket_no', ticket_qty='$tk_qty',kanban_status='$ptkstatus',
									date_insert ='".date('Y-m-d H:i:s')."',
									date_work ='".$datework."' ,
									work_id = (SELECT work_id FROM ".DB_DATABASE1.".fgt_leader  
											WHERE line_id = '".$linesp."' ORDER BY work_id DESC  LIMIT 1)"; 
							$qrtg=mysqli_query($con, $sqltg);
							log_hist($user_login,"New Tag",$mxtag,"fgt_srv_tag",$sqltg);
										
										$sqlutk="UPDATE  ".DB_DATABASE2.".rf_kanban_ticket SET
												 status_write=5, last_status='Reserved'
												 WHERE ticket_ref='$ticket_no'";
										$qrtk=mysqli_query($con, $sqlutk); //Reserved
					
						//gotopage("index.php?id=".base64_encode('printtag')."&idtg=".base64_encode($tagnon));
						gotopage("index.php?id=".base64_encode('adjust_serial_scan')."&idtg=".base64_encode($tagnon)."&emp=$emp");

					}else{
						alert("ไม่มีข้อมูลในระบบ กรุณาติดต่อหัวหน้างาน");
						gotopage("index.php?id=".base64_encode('print'));
					} // END check tiket if(mysql_num_rows($qrctk)<>0){
				
					
		}//END if(strlen($post_scan)=="9"){  // ELSE IF SCAN TICKET
			
					
					
					
					
			/*	$sqltg="INSERT INTO ".DB_DATABASE1.".fgt_srv_tag 
							SET line_id='$linesp', 
							tag_no= '$tagnon', 
							id_model=(SELECT id_model FROM ".DB_DATABASE1.".fgt_model  WHERE tag_model_no = '".$pmodel."'), 
							model_kanban='$pmodel',
							shift='$chshift', 
							fg_tag_barcode='$tagb',
							status_print='Not yet' ,
							date_insert ='".date('Y-m-d H:i:s')."',
							date_work ='".$datework."' "; 
					$qrtg=mysql_query($sqltg);
					log_hist($user_login,"Adjust New Tag",$mxtag,"fgt_srv_tag",$sqltg);
					gotopage("index.php?id=".base64_encode('adjust_serial_scan')."&idtg=".base64_encode($tagnon)."&emp=$emp");
*/
					
					
					
					
				}//if(mysql_num_rows($qrckm)<>0){		
					
	}//if(!empty($_POST['button2'])){

	if(!empty($_GET['wtag'])){
			$gtagw=$_GET['wtag'];
			$sqlwt="UPDATE ".DB_DATABASE1.".fgt_srv_tag SET status_print='Wait'
					 WHERE tag_no='".$gtagw."' ";
			$qrwt=mysqli_query($con, $sqlwt);
			//$plineid = $_GET['lid'];
			log_hist($user_login,"Waiting",$gtagw,"fgt_srv_tag","");
			gotopage("index.php?id=".base64_encode('print'));
			
		}//if(!empty($_GET['wtag'])){
		
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript">
window.onload = function() {
  document.getElementById("model").focus();
}
</script>
  
  <div class="rightPane" align="center">
<form name="scan"  id="scan" method="post" action=""   onsubmit="return false;"  autocomplete="off" > <!--  onsubmit='return validate(this)'  -->
            <table width="749" border="1" align="center" class="table01">
              <tr>
                <th height="31" colspan="2"><span class="text_black"><?php echo $top_name;?> Real Time Printing (Adjustment Data)</span></th>
              </tr>
                <tr>
                <td width="335" height="32"><div class="tmagin_left"><span class="text_black">Kanban - Model No. Or Ticket No.: <br />
(แสกนหมายเลขโมเดลจากกันบัง หรือ Ticket No. งานเศษ)</span></div></td>
                <td width="398">
                <div class="tmagin_right">
                  <input type="text" name="model" id="model"  class="bigtxtbox" style="width:240px; size:18px;"  onkeypress="return ckKeyPresse(event);" />
                  <input type="button" name="btnscan" id="btnscan" value="Submit" class="buttonb" onclick="validate(this.value)" />
                  <input type="hidden" name="scan2" id="scan2"  value="Submit" /> 
                  <input type="hidden" name="emp" id="emp"  value="<?=$_GET['emp']?>" />      
                </div> </td>
                </tr>
              <tr>
                <td colspan="2" height="38"  align="center">
             <div id="txtStatus"></div>
                </td>
              </tr>
            </table>
</form>

</div> 

<?php //echo "<h1 style='color:red;'>DB Server : ".$host."<br>".
			//"DB USer : ".$user."<br>".
			//"Enviroment : TEST</h1>";?>

  
  
  

<?
	//echo "-->".$emp_con=base64_decode($_GET['emp']);
	//echo "===".$_POST['scan2'];
	if(!empty($_POST['scan2']) &&  $_POST['scan2']=="Submit"){
				//Should be checking old tag or waiting tag!!!
				//Create Tag 
				$emp=$_POST['emp'];
				$mxtag=selectMxTag();
				$pmodel=substr($_POST['model'],0, 15);  
			//	$pline=$_POST['hslid'];
				$linesp=sprintf("%02d",$user_login);
				$mxtagsp=sprintf("%07d",$mxtag);
				$tagnon=$linesp.$mxtagsp;
		 	 	$sqlckm="SELECT tag_no FROM prod_fg_tag.fgt_tag
						 WHERE model_kanban = '$pmodel'
						 AND status_print in ('Wait','Not yet')
						 AND line_id ='$user_login'";
				$qrckm=mysql_query($sqlckm);
				if(mysql_num_rows($qrckm)<>0){
					$rsckm=mysql_fetch_array($qrckm);
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
				$sqltg="INSERT INTO ".DB_DATABASE1.".fgt_tag 
							SET id_tag='$mxtag', 
							line_id='$linesp', 
							tag_no= '$tagnon', 
							id_model=(SELECT id_model FROM ".DB_DATABASE1.".fgt_model  WHERE tag_model_no = '".$pmodel."'), 
							model_kanban='$pmodel',
							shift='$chshift', 
							fg_tag_barcode='$tagb',
							status_print='Not yet' ,
							date_insert ='".date('Y-m-d H:i:s')."',
							date_work ='".$datework."' "; 
					$qrtg=mysql_query($sqltg);
					log_hist($user_login,"Adjust New Tag",$mxtag,"fgt_tag",$sqltg);
					gotopage("index.php?id=".base64_encode('adjust_serial_scan')."&idtg=".base64_encode($tagnon)."&emp=$emp");

				}//if(mysql_num_rows($qrckm)<>0){		
					
	}//if(!empty($_POST['button2'])){

	if(!empty($_GET['wtag'])){
			$gtagw=$_GET['wtag'];
			$sqlwt="UPDATE ".DB_DATABASE1.".fgt_tag SET status_print='Wait'
					 WHERE tag_no='".$gtagw."' ";
			$qrwt=mysql_query($sqlwt);
			//$plineid = $_GET['lid'];
			log_hist($user_login,"Waiting",$gtagw,"fgt_tag","");
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
                <th height="31" colspan="2"><span class="text_black"><? echo $top_name;?> Real Time Printing (Adjustment Data)</span></th>
              </tr>
                <tr>
                <td width="335" height="32"><div class="tmagin_left"> <span class="text_black">Kanban - Model No.: <br />
					(แสกนหมายเลขโมเดลจากกันบัง)</span>
				</div></td>
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

  
  
  

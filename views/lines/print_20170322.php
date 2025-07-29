<?
	//echo "===".$_POST['scan2'];
	if(!empty($_POST['scan2']) &&  $_POST['scan2']=="Submit"){
				//Should be checking old tag or waiting tag!!!
				//Create Tag 
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
					gotopage("index.php?id=".base64_encode('printtag')."&idwait=".base64_encode($rsckm['tag_no']));
						
					}else{
				
					if( date("H:i:s") >= "07:50:00" AND date("H:i:s") <= "20:19:59"){
							$chshift="Day";
						}else{
							$chshift="Night";
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
							date_insert ='".date('Y-m-d H:i:s')."'"; 
					$qrtg=mysql_query($sqltg);
					log_hist($user_login,"New Tag",$mxtag,"fgt_tag",$sqltg);
					gotopage("index.php?id=".base64_encode('printtag')."&idtg=".base64_encode($tagnon));

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

		<?
	
	    $sql_emp="SELECT a.tag_no,b.tag_model_no,a.line_id,a.fg_tag_barcode,
					a.tag_qty,b.model_name,b.std_qty ,COUNT(c.tag_no) AS ctag
					FROM ".DB_DATABASE1.".fgt_tag a 
					LEFT JOIN ".DB_DATABASE1.".fgt_model b ON a.id_model=b.id_model 
					LEFT JOIN ".DB_DATABASE1.".fgt_serial c ON a.tag_no=c.tag_no
					WHERE a.line_id ='$user_login' 
					AND a.status_print  in ('Wait','Not yet')
					GROUP BY  a.tag_no";
		$qrem=mysql_query($sql_emp);
		$nums=mysql_num_rows($qrem);
		 ?>


 <div class="col-2">
   <table width="380 px" border="1" class="table01" >
     <tr height="31">
       <th colspan="4" > Wating for Print (รายการวิทยุที่รอพิมพ์ FG Tag)</th>
     </tr>
     <tr height="31">
       <th width="81" ><span class="text_black_bold">Model Name<br />
         (ชื่อโมเดล)
       </span></th>
       <th width="91" ><span class="text_black_bold">Tag No.<br />
         (หมายเลขแท็ก)
       </span></th>
       <th width="128"><span class="text_black_bold">STD Qty.<br />
       (จำนวนมาตรฐาน)</span></th>
       <th width="100"><span class="text_black_bold">Current Qty<br />
         จำนวนที่แสกนไปแล้ว
       </span></th>
     </tr>
     <?
     	if($nums<>0){
			while($rsem=mysql_fetch_array($qrem)){
				$tagnowait=$rsem['tag_no'];
	 ?>
     <tr   <?php echo icolor($v); $v = $v + 1; ?> onMouseOver="className=&quot;over&quot;"  onMouseOut="className=&quot;&quot;" height="31px" align="center">
       <td><span class="text_black_normal02"><? echo $rsem['model_name'];?> </span></td>
       <td><span class="text_black_normal02"><a href="index.php?id=<?=base64_encode('printtag')?>&idwait=<?=base64_encode($tagnowait)?>"><? echo $tagnowait;?></a> </span></td>
       <td><span class="text_black_normal02"><? echo $rsem['std_qty'];?> </span></td>
       <td><span class="text_black_normal02"><? echo $rsem['ctag'];?> </span></td>
     </tr>

     <?
			}//while($rs(mysql_fetch_array())){
		}else{
			    echo  "<tr align='center'>";
      			echo  " <td colspan='4'><center><div class='table_comment_small' >No have data...</div></center></td>";
    			echo " </tr>";
			}
	 ?>
   </table>
   
</div>
  
  <div class="rightPane" align="center">

  

<form name="scan"  id="scan" method="post" action=""   onsubmit="return false;"  autocomplete="off" > <!--  onsubmit='return validate(this)'  -->
            <table width="749" border="1" align="center" class="table01">
              <tr>
                <th height="31" colspan="2"><span class="text_black"><? echo $top_name;?> Real Time Printing</span></th>
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
                </div> </td>
                </tr>
              <tr>
                <td colspan="2" height="38"  align="center">
             <div id="txtStatus"> </div>
    
                </td>
              </tr>
              
              
            </table>
  
</form>

</div> 

  
  
  

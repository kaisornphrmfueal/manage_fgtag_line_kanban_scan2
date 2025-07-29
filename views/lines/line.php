<?php
if(!empty($_POST['hbtn']) && $_POST['hbtn']=="Submit"){
 	$sql="SELECT a.line_id,a.line_name
		FROM ".DB_DATABASE1.".view_line a
		WHERE active = '0'
		AND a.line_name ='".$_POST['line']."' ";
	
	$qr=mysqli_query($con, $sql);
	if(mysqli_num_rows($qr)<>0){   
		$rs=mysqli_fetch_array($qr);
		$linename= $rs['line_name'];
		$lineid=$rs['line_id'];
			if (strpos($linename, 'BSI') !== false) {
				$mid=base64_encode('bsi_scan')."&ln=b&lid=".$lineid;
				gotopage("index_chk.php?idm=$mid");
			}else{
				/*
				// START function for delete tag data scan serial = 0 
			 	$sql_reset="DELETE FROM ".DB_DATABASE1.".fgt_srv_tag WHERE (sn_start = '' AND sn_end = '') OR (sn_start IS NULL AND sn_end IS NULL) AND  line_id = '$lineid' ";
				$qr_reset=mysql_query($sql_reset);
				if($qr_reset){
					//alert("Reset Tag");
					}
				//END  function for delete tag data scan serial = 0 
				*/
				 $sqlck="SELECT a.line_id,a.tag_no
						FROM ".DB_DATABASE1.".fgt_srv_tag a 
						WHERE a.line_id='".$lineid."'
						AND a.status_print = 'Not yet'";
				$qrck=mysqli_query($con, $sqlck);
				if(mysqli_num_rows($qrck)<>0){
					$rsck=mysqli_fetch_array($qrck);
					$mid=base64_encode("printtag")."&lid=".$lineid."&ln=f&idgtg=".base64_encode($rsck['tag_no']);
				}else{
					$mid=base64_encode("print")."&lid=".$lineid."&ln=f&idgtg=";
				}//if(mysql_num_rows($qrck)<>0){
					gotopage("index_chk.php?idm=$mid");
					
			}//if (strpos($a, 'BSI') !== false) {
					
	}//if(mysql_num_rows($qr)<>0){   
			
}//if(!empty($_POST['hbtn']) && $_POST['hbtn']=="Submit"){
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script type="text/javascript">
window.onload = function() {
  document.getElementById("line").focus();
}
</script>

<div align="center" style="margin-top:80px; margin-bottom:150px;">
   <form id="scan" name="scan" method="post" action=""  onsubmit="return false;"  autocomplete="off" >
<table width="626" border="1" class="table01"  align="center">
      <tr>
        <th height="47" colspan="2" align="center"  ><span class="text_black">FG Trnsfer Tag Real-Time Printing </span>
       
     </th>
      </tr>
      <tr>
        <td width="260" height="90"  align="center"><span class="text_black">Scan Line Name : <br />
        แสกนขื่อไลน์ :</span>
          <div class="txt-red-m" >   Ex. FA-6</div>
       </td>
        <td width="624"  > 
        	<input type="text" name="line" id="line"  class="bigtxtbox" style="width:240px; size:18px;"  onkeypress="return ckKeyPresse(event);" />
            <input type="button" name="btnscan" id="btnscan" value="Login" class="buttonb" onclick="validateLine(this.value)" />
         	<input type="hidden" name="hbtn" id="hbtn"  value="Submit" /> 
         <br />
            <div id="txtStatus"> </div>
         </td>
      </tr>
   </table>
   </form>

   
</div>
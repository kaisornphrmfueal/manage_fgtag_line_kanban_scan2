<? include('../../includes/configure_orcl.php');

	if(!empty($_POST['button2']) AND $_POST['button2']=="Confirm"){
		$pbtag=substr($_POST['fgtag'], -9);
		$pbtagno=$_POST['fgtag'];
		
		  $sqlb="INSERT INTO  ".DB_DATABASE1.".fgt_srv_tag (tag_no, bsi_line_id, bsi_date, bsi_model,bsi_tag_scan) 
		  VALUES('".$pbtag."', '$user_login', '".date('Y-m-d H:i:s')."', '".$_POST['mdel']."', '".$pbtagno."') 
			ON DUPLICATE KEY UPDATE   bsi_line_id='$user_login', bsi_date= '".date('Y-m-d H:i:s')."',
			bsi_model= '".$_POST['mdel']."',bsi_tag_scan='".$pbtagno."' ";
		$qrb=mysql_query($sqlb);	
		if (!$qrb) {
			alert("Can't add data, Please try again");
			exit;
		}else{
			log_hist($user_login,"BSI Scan",$pbtagno,"fgt_srv_tag","");
			//gotopage("index_bsi.php?id=".base64_encode('bsi_scan'));
			}
	

}//	if($_POST['button2']=="Confirm"){
	
?>

<script type="text/javascript">
window.onload = function() {
	 document.getElementById("mdel").focus();
	 document.getElementById("txtStatus").innerHTML  ='<span class="txt-blue-m" >Please scan Model No.</span>';
}
</script>

<script type="text/javascript">
function validate(my_request) {
	  var str =  document.getElementById("fgtag").value.length;
	  var strm =  document.getElementById("mdel").value.length;
	//  alert(str);
	if (document.getElementById("mdel").value == "" || strm < 15){  
		 document.getElementById("txtStatus").innerHTML  ='<span class="txt-blue-m" >Please scan Model No.</span>';
		 document.getElementById("mdel").focus();
		 document.getElementById("mdel").select();
		return (false);
		}else if (document.getElementById("fgtag").value == "" ||  str < 12){
		 document.getElementById("txtStatus").innerHTML  ='<span class="txt-blue-m" >Please scan FG Transfer Tag</span>';
		 document.getElementById("fgtag").focus();
		 document.getElementById("fgtag").select();
		return (false);
		}else{
			return true; 
		}
}

function clear_tag()
{
	document.getElementById("fgtag").value="";
	document.getElementById('fgtag').focus();
}

function clear_model()
{
	document.getElementById("mdel").value="";
	document.getElementById('mdel').focus();
}
	
//-------------------------
function checkEnter(e){ //e is event object passed from function invocation
var characterCode //literal character code will be stored in this variable

if(e && e.which){ //if which property of event object is supported (NN4)
	e = e
	characterCode = e.which //character code is contained in NN4's which property
}else{
	e = event
	characterCode = e.keyCode //character code is contained in IE's keyCode property
}


if(characterCode == 0x0d){ //if generated character code is equal to ascii 13 (if enter key)
	e.keyCode=0x09;	
	
}
return;
}

</script>
<style>
#bg {
	text-align: center;
	width: 100%;
	height:20%;


}
</style>

</div>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 <div class="rightPane" align="center">
 <form id="form1" name="form1" method="post"  action="" onsubmit='return validate(this)' autocomplete="off" >
<table width="100%" border="1" class="table01" align="center">
          <tr>
            <th colspan="8" align="left"><span class="Arial_14_black">
           BSI Scaning : </span><span id="txtStatus"> </span>  </th>
          </tr>
          <tr>
            <td width="8%" ><div  class="tmagin_left">Model No. :</div></td>
            <td colspan="2"><div  class="tmagin_right"> 
             <input type="text" name="mdel" id="mdel" class="bigtxtbox"  onclick="clear_model()" 
             style="width:350px; background-color:#FCC;" onKeyDown="checkEnter()" /></div></td>
             <td width="11%" ><div  class="tmagin_left">FG Transfer Tag :</div></td>
            <td colspan="2"><div  class="tmagin_right"> 
             <input type="text" name="fgtag" id="fgtag" class="bigtxtbox"  onclick="clear_tag()" style="width:350px; background-color:#FCC;" /></div>				</td>
            <td width="16%" height="28" colspan="3" align="center">
                    <input type="Submit" name="button1" id="button1" value="Confirm"   class="myButton"/>
                <input type="hidden" name="button2" id="button2" value="Confirm" />
                <input type="button" name="clear" id="clear" value="Clear" onclick="javascript:formClear();"  class="myButton"   />
                  </td>  
          </tr>
          
</table>
</form>  
  

<?
				if(!empty($_POST['mdel'])){

						 $strSQL = "SELECT NAME, TYPE, MODEL_NO 
							FROM QA_WI_PICTURE
							WHERE MODEL_NO = '".$_POST['mdel']."'";
						$objParse = oci_parse($conn, $strSQL);
						oci_execute ($objParse,OCI_DEFAULT); 
						$objResult = oci_fetch_array($objParse,OCI_BOTH);	
								  
						 if($objResult['NAME'] == ""){  $pict="not-fonud.jpg"; } else { $pict= $objResult['NAME']; }
						echo "<img style='height:90%;' src='".HTTP_SERVER_EWI.DIR_PAGE_EWI.$pict."'   />";	
				}//if(!empty($_POST['mdel'])){
						
?>
</div>
 
<?

if(!empty($_POST['hbutton']) AND $_POST['hbutton']=="Confirm"){
		$ppage=$_POST['hgoto'];
		$bcadep=base64_encode($ppage);
		$bcadeem=base64_encode(substr($_POST['user'],2));
		
		$sqlck="SELECT emp_id,emp_pass,emp_name,permission,active 
					FROM ".DB_DATABASE1.".view_permission 
					WHERE emp_id = '". substr($_POST['user'],2)."' 
					AND emp_pass='".$_POST['passw']."'
					AND  permission in ('sup','floater','leader') ";
					
			$qrck=mysql_query($sqlck);
			if(mysql_num_rows($qrck)<>0){
			$rsck=mysql_fetch_array($qrck);
			
						sleep(2);	
						go_page_parent("?msg=true");		
						gotopage("index.php?id=$bcadep&emp=$bcadeem");
				}else{
					 alert("ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง");
					 gotopage("windows.php?win=confirm&idagnm=");
					}//if(mysql_num_rows($qrck)<>0){
					
		}//if(!empty($_POST['hbutton'])){
		
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" type="text/JavaScript">
window.onload = function() {
  document.getElementById("user").focus();
}

function validate(form2) {   
	if(document.form2.user.value == "" || document.getElementById("user").value.length!= 6){
		alert("กรุณาใส่รหัสพนักงานผู้ยืนยัน 6 หลัก (tl1234)");
		document.form2.user.focus();
		return (false);
	}else if(document.form2.passw.value == "" ){
		alert("Please input Password.");
		document.form2.passw.focus();
		return (false);
	}else{
		form2.button.disabled = true;  
		form2.button.value = 'Waiting...'; 
		return (true) ;
	}
	
}///function validate(form) {
	
function gotoClick(){
	window.location.replace('index.php?id=cHJpbnQ=');
    }

</script>


 <form action="" method="post" enctype="multipart/form-data" name="form2" id="form2"  onsubmit='return validate(this)' autocomplete="off">


<table width="623"  border="1" class="table01" align="center">
  <tr>
    <th height="39" colspan="2"><span class="text_black">
      <p>
      Confirm For Adjustment Data (ยืนยันการแก้ไขข้อมูล)</p></span></th>

   <tr>
      <td width="301" height="40"><span class="text_black_bold">Username  (รหัสพนักงานผู้ยืนยัน 6 หลัก) :</span></td>  
      <td><div class="tmagin_right">
      <input type="text" name="user" id="user" />
      *Ex.tl1972</div></td>
    </tr>
     <tr>
     <td height="37" ><span class="text_black_bold">Password (รหัสผ่าน) : </span></td>
     <td height="37" ><div class="tmagin_right"><input type="password" name="passw" id="passw" /></div></td>
   </tr>
    <td height="25" colspan="2" align="center">
        <input id='button'  name='button' type='submit' value='Confirm'  class="buttonb" />
        <input type="hidden" name="hbutton" id="hbutton" value="Confirm" />
        
        <input type="hidden" name="hgoto" id="hgoto" value="<?=$_GET['page']?>" />
        
      	<input type='button' id='buttonc'  name='buttonc' value="Close"  class="buttonr" onclick="gotoClick()" />
        <input type='hidden' id='hbuttonc' name='hbuttonc' value="Close" />
    </td>
  </tr>
</table>
 </form>  
 
 
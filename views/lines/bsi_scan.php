<?php 
error_reporting( error_reporting() & ~E_NOTICE );  //V3 25052022
include('../../includes/configure_orcl.php');
include('../../includes/configure_host_new.php');

$sql_ng = "SELECT model_no, fg_tag, fg_tag_model, operator, record_date, status
FROM fgt_bsi_scan_confirm
WHERE status = 'NG' AND operator = '$user_login' ";
$query_ng = mysqli_query($con_fg_tag, $sql_ng);
$row_ng = mysqli_num_rows($query_ng);

if($row_ng != 0)
{
	echo "<script>
	window.location.href = 'ng_confirm.php';
	</script>";
}

if(!empty($_POST["total_box_return"]))
{
	$return_box = $_POST["total_box_return"];
}
else
{
	$return_box = '1';

	echo "<script>
	function deploy()
	{
		document.getElementById('mdel').focus();

	};
	</script>";

}


?>

<script type="text/javascript">
	window.onload = function() {
		document.getElementById("mdel").focus();
		document.getElementById("txtStatus").innerHTML  ='<span class="txt-blue-m" >Please scan Model No.</span>';
	}
</script>

<script type="text/javascript">
	function validate(my_request)
	{
		var str =  document.getElementById("fgtag").value.length;
		var strm =  document.getElementById("mdel").value.length;

		if (document.getElementById("mdel").value == "" || strm < 15)
		{  
			document.getElementById("txtStatus").innerHTML  ='<span class="txt-blue-m" >Please scan Model No.</span>';
			document.getElementById("mdel").focus();
			document.getElementById("mdel").select();
			return (false);
		}
		else if (document.getElementById("fgtag").value == "" ||  str < 12)
		{
			document.getElementById("txtStatus").innerHTML  ='<span class="txt-blue-m" >Please scan FG Transfer Tag</span>';
			document.getElementById("fgtag").focus();
			document.getElementById("fgtag").select();
			return (false);
		}
		else
		{
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

	function clear_sptag()
	{
		document.getElementById("sptag").value="";
		document.getElementById('sptag').focus();
	}
	function clear_name_plate()
	{
		document.getElementById("name_plate").value="";
		document.getElementById('name_plate').focus();
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
<body onload="deploy();">
	<div class="rightPane" align="center">
		<form id="form1" name="form1" method="post"  action="" onsubmit='return validate(this)' autocomplete="off" >
			<table width="100%" border="1" class="table01" align="center">
				<tr>
					<th colspan="8" align="left">
						<span class="Arial_14_black">
						BSI Scaning : </span><span id="txtStatus"> </span>
					</th>
				</tr>
				<tr>
					<td width="8%" >
						<div  class="tmagin_left">Model No. :</div>
					</td>
					<td colspan="2">
						<div  class="tmagin_right"> 
							<input type="text" name="mdel" id="mdel" class="bigtxtbox"  onclick="clear_model()"  
							style="width:160px; background-color:#FCC;" onKeyDown="checkEnter()" />
						</div>
					</td>
					<td width="11%">
						<div  class="tmagin_left">FG Transfer Tag :</div>
					</td>
					<td colspan="2">
						<div  class="tmagin_right"> 
							<input type="text" name="fgtag" id="fgtag" class="bigtxtbox"  onclick="clear_tag()" style="width:170px; background-color:#FCC;"/></div>
						</td>
						<td width="11%">
							<div  class="tmagin_left">Suppiler Tag :</div>
						</td>
						<td colspan="2">
							<div  class="tmagin_right"> 
								<input type="text" name="sptag" id="sptag" class="bigtxtbox"  onclick="clear_sptag()" readOnly="true" style="width:180px; background-color:#FCC;" /></div>
							</td>
							<td width="11%">
								<div  class="tmagin_left">Name Plate :</div>
							</td>
							<td colspan="2">
								<div  class="tmagin_right"> 
									<input type="text" name="name_plate" id="name_plate" maxlength="15" class="bigtxtbox"  onclick="clear_name_plate()" style="width:180px; background-color:#FCC;" /></div>
								</td>


								<td width="16%" height="28" colspan="3" align="center">
									<input type="Submit" name="button1" id="button1" value="Confirm"   class="myButton"/>
									<input type="hidden" name="total_box_return" id="total_box_return" value="<?php echo $return_box; ?>" />
									<input type="hidden" name="button2" id="button2" value="Confirm" />
									<input type="button" name="clear" id="clear" value="Clear" onclick="javascript:formClear();"  class="myButton"/>
								</td>  
							</tr>

						</table>
					</form>  

					<?php
					$p_model = substr($_POST['mdel'],0, 15); 
					$full_model = $_POST['mdel'];
					
					if(!empty($p_model))
					{
					//GET PICTURE
						$strSQL = "SELECT NAME, TYPE, MODEL_NO 
						FROM QA_WI_PICTURE
						WHERE MODEL_NO = '".$p_model."'";
						$objParse = oci_parse($conn, $strSQL);
						oci_execute ($objParse,OCI_DEFAULT); 
						$objResult = oci_fetch_array($objParse,OCI_BOTH);	

						if($objResult['NAME'] == "")
						{  
							$pict="not-fonud.jpg"; 
						} 
						else 
						{
							$pict= $objResult['NAME']; 
						}


					//CHECK Domestic and Export
						$check_domectic = "SELECT model_no, model_status, status_sale, model_name
						FROM rf_model_name
						WHERE model_no = '".$p_model."' ";
						$query_cn = mysqli_query($con_rfid, $check_domectic);
						$rs_ci = mysqli_fetch_array($query_cn);
						$model_salse = substr($rs_ci["model_name"], -2);

						$get_model_form_fgtag = "SELECT model_kanban FROM fgt_srv_tag WHERE fg_tag_barcode = '".$_POST['fgtag']."' ";
						$query_model_form_fgtag = mysqli_query($con_fg_tag, $get_model_form_fgtag);
						$rs_getmo_tag = mysqli_fetch_array($query_model_form_fgtag);



					//CHECK CN, CI 
						$get_sn = "SELECT sn_start, sn_end, line_id, tag_qty, matching_ticket_no
						FROM fgt_srv_tag
						WHERE model_kanban = '".$p_model."' AND fg_tag_barcode = '".$_POST['fgtag']."' ";
						$query_sn = mysqli_query($con_fg_tag, $get_sn);
						$rs_sn = mysqli_fetch_array($query_sn);

						$cus_sn_start = substr($rs_sn["sn_start"], 2);
						$cus_sn_end = substr($rs_sn["sn_end"], 2);
						$cut_title = substr($rs_sn["sn_start"],0,2);
						//$total_box = ($cus_sn_end - $cus_sn_start) + 1;
						$total_box = $rs_sn["tag_qty"];
						$ticket_no = $rs_sn["matching_ticket_no"];
						
						if($rs_getmo_tag["model_kanban"] == $p_model && $_POST['sptag'] == "")
						{

					//if($rs_ci["model_status"] == 0 && $rs_ci["status_sale"] == 1)///Check export
					if($model_salse != "TH")
					{

						if($_POST['name_plate'] == "")
						{

							echo "<script>
							function deploy(){

								var model_no = '".$full_model."';
								var fg_tag = '".$_POST['fgtag']."';

								document.getElementById('mdel').value= model_no;
								document.getElementById('fgtag').value= fg_tag;
								document.getElementById('sptag').readOnly = true;
								document.getElementById('name_plate').focus();
								document.getElementById('txtStatus').innerHTML  ='<span>Please scan Name plate.</span>';

							};
							</script>";

						}
						elseif($_POST['name_plate'] == $p_model)
						{

							echo '<br><h2 style="color:blue;">TOTAL SCAN : '.$return_box.'/'.$total_box.' </h2><table width="100%" align="center"><tr>';
							echo "<td style='border: 0px solid #CCC; text-align: center; width:75%'><img style='height:100%;' src='".HTTP_SERVER_EWI.DIR_PAGE_EWI.$pict."'   /></td>
							";
							echo "</tr></table>";

							if($return_box < $total_box)
							{
								$return_box++;

								echo "<script>
								function deploy(){

									var model_no = '".$full_model."';
									var fg_tag = '".$_POST['fgtag']."';
									var return_box = '".$return_box."'

									document.getElementById('mdel').value= model_no;
									document.getElementById('fgtag').value= fg_tag;
									document.getElementById('sptag').readOnly = true;
									document.getElementById('total_box_return').value= return_box;
									document.getElementById('name_plate').focus();
									document.getElementById('txtStatus').innerHTML  ='<span>Please scan Name plate.</span>';

								};
								</script>";
								
							}
							else
							{

								//$return_box = '0';
								//echo ">>>>".$total_box.">>>".$return_box;

								echo "<script>
								function deploy(){

									document.getElementById('mdel').focus();
									document.getElementById('total_box_return').value= 0;
								};
								</script>";

							}

							

							$pbtag=substr($_POST['fgtag'], -9);
							$pbtagno=$_POST['fgtag'];

							$sqlb="INSERT INTO  ".DB_DATABASE1.".fgt_srv_tag (tag_no, bsi_line_id, bsi_date, bsi_model,bsi_tag_scan) 
							VALUES('".$pbtag."', '$user_login', '".date('Y-m-d H:i:s')."', '".$full_model."', '".$pbtagno."') 
							ON DUPLICATE KEY UPDATE   bsi_line_id='$user_login', bsi_date= '".date('Y-m-d H:i:s')."',
							bsi_model= '".$full_model."',bsi_tag_scan='".$pbtagno."', tag_location = '2' ";
							$qrb=mysql_query($sqlb);	
							

							
							if (!$qrb)
							{
								alert("Can't add data, Please try again");
								exit;
							}else
							{
							
							$update_rfid = "UPDATE ".DB_DATABASE2.".rf_kanban_ticket SET  status_write=7, last_status='BSI'  WHERE ticket_ref='$ticket_no'";
							$query_rifd = mysql_query($update_rfid);
							
								log_hist($user_login,"BSI Scan",$pbtagno,"fgt_srv_tag","");
							}

						}
						else
						{

							$sql_insert_ng = "INSERT INTO fgt_bsi_scan_confirm (model_no, fg_tag, fg_tag_model, suppiler_tag_model, operator, status, name_plate)
							VALUES ('".$p_model."', '".$_POST['fgtag']."', '".$rs_getmo_tag["bsi_model"]."', '".$_POST["sptag"]."', '$user_login', 'NG', '".$_POST['name_plate']."')";
								$query_ng = mysqli_query($con_fg_tag, $sql_insert_ng);
								if($query_ng)
								{

									echo "<script>
									window.location.href = 'ng_confirm.php?line_id=".$user_login."';
									</script>";

								}

							}

						}
					else // Domestic
					{
						echo "<script>
						function deploy(){
							var model_no = '".$full_model."';
							var fg_tag = '".$_POST['fgtag']."';

							document.getElementById('mdel').value= model_no;
							document.getElementById('fgtag').value= fg_tag;
							document.getElementById('sptag').readOnly = false;
							document.getElementById('sptag').focus();
							document.getElementById('txtStatus').innerHTML  ='<span>Please scan Suppiler Tag.</span>';

						};
						</script>";
					}

				}
				else if($rs_getmo_tag["model_kanban"] == $p_model && $_POST['sptag'] == $p_model)
				{
					
					if($_POST['name_plate'] == "")
					{

						echo "<script>
						function deploy(){

							var model_no = '".$full_model."';
							var fg_tag = '".$_POST['fgtag']."';
							var sp_tag = '".$_POST['sptag']."'

							document.getElementById('mdel').value= model_no;
							document.getElementById('fgtag').value= fg_tag;
							document.getElementById('sptag').value = sp_tag;
							document.getElementById('name_plate').focus();
							document.getElementById('txtStatus').innerHTML  ='<span>Please scan Name plate.</span>';

						};
						</script>";

					}
					elseif($_POST['name_plate'] == $p_model)
					{

						echo '<br><h2 style="color:blue;">TOTAL SCAN : '.$return_box.'/'.$total_box.' </h2><table width="100%" align="center"><tr>';
						echo "<td style='border: 0px solid #CCC; text-align: center; width:75%'><img style='height:100%;' src='".HTTP_SERVER_EWI.DIR_PAGE_EWI.$pict."'   /></td>
						";
						echo "</tr></table>";

						if($return_box < $total_box)
						{
							$return_box++;

							echo "<script>
							function deploy(){

								var model_no = '".$full_model."';
								var fg_tag = '".$_POST['fgtag']."';
								var sp_tag = '".$_POST['sptag']."'
								var return_box = '".$return_box."'

								document.getElementById('mdel').value= model_no;
								document.getElementById('fgtag').value= fg_tag;
								document.getElementById('sptag').value = sp_tag;
								document.getElementById('total_box_return').value= return_box;
								document.getElementById('name_plate').focus();
								document.getElementById('txtStatus').innerHTML  ='<span>Please scan Name plate.</span>';

							};
							</script>";

						}
						else
						{
							//echo ">>>>".$total_box.">>>".$return_box;

							echo "<script>
							function deploy(){

								document.getElementById('mdel').focus();
								document.getElementById('total_box_return').value= 0;

							};
							</script>";

						}

						$pbtag=substr($_POST['fgtag'], -9);
						$pbtagno=$_POST['fgtag'];

						$sqlb="INSERT INTO  ".DB_DATABASE1.".fgt_srv_tag (tag_no, bsi_line_id, bsi_date, bsi_model,bsi_tag_scan) 
						VALUES('".$pbtag."', '$user_login', '".date('Y-m-d H:i:s')."', '".$full_model."', '".$pbtagno."') 
						ON DUPLICATE KEY UPDATE   bsi_line_id='$user_login', bsi_date= '".date('Y-m-d H:i:s')."',
						bsi_model= '".$full_model."',bsi_tag_scan='".$pbtagno."', tag_location = '2' ";
						$qrb=mysql_query($sqlb);	
						if (!$qrb)
						{
							alert("Can't add data, Please try again");
							exit;
						}else
						{
							log_hist($user_login,"BSI Scan",$pbtagno,"fgt_srv_tag","");
						}

					}
					else
					{

						$sql_insert_ng = "INSERT INTO fgt_bsi_scan_confirm (model_no, fg_tag, fg_tag_model, suppiler_tag_model, operator, status, name_plate)
						VALUES ('".$p_model."', '".$_POST['fgtag']."', '".$rs_getmo_tag["bsi_model"]."', '".$_POST["sptag"]."', '$user_login', 'NG', '".$_POST['name_plate']."')";
							$query_ng = mysqli_query($con_fg_tag, $sql_insert_ng);
							if($query_ng)
							{

								echo "<script>
								window.location.href = 'ng_confirm.php?line_id=".$user_login."';
								</script>";

							}

						}


					}
					else
					{

						$sql_insert_ng = "INSERT INTO fgt_bsi_scan_confirm (model_no, fg_tag, fg_tag_model, suppiler_tag_model, operator, status)
						VALUES ('".$p_model."', '".$_POST['fgtag']."', '".$rs_getmo_tag["bsi_model"]."', '".$_POST["sptag"]."', '$user_login', 'NG')";
							$query_ng = mysqli_query($con_fg_tag, $sql_insert_ng);
							if($query_ng)
							{

								echo "<script>
								window.location.href = 'ng_confirm.php?line_id=".$user_login."';
								</script>";

							}

						}

					}

					?>
					<td style="text-align= center; "></td>
				</div>
			</body>

<?
include('../../includes/configure_srv.php');
	//	Ccheck Model, Line, Bartender 
	if(!empty($_GET['idup']) && $_GET['idup']=="Model"){
			$gtype= $_GET['idup']; //Model
			$timenow=date('Y-m-d H:i:s');
			if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];} else {$ip = $_SERVER["REMOTE_ADDR"];}//else {
			$sqltg = "SELECT IFNULL(MAX(last_id_srvupdate),0) AS mxup FROM ".DB_DATABASE1.".fgt_update_master 
						 WHERE type_data = '$gtype' AND user_update = '$user_login'";
			$qrtg=mysql_query($sqltg,$con);
			$rstg=mysql_fetch_array($qrtg);
			$mxhist = $rstg['mxup'];
			
			$sqlmxs="SELECT MAX(id_update) AS mxsidrvup FROM ".DB_DATABASE4.".fgt_srv_update WHERE type_data = '$gtype' AND date_insert <'$timenow'";
			$qrmxs=mysql_query($sqlmxs,$con2);
			$rsmxs=mysql_fetch_array($qrmxs);
			$mxsvup = $rsmxs['mxsidrvup'];

		 	 	$sqlup="SELECT b.* 
					FROM (
						SELECT a.id_update,a.id_action,a.action_name,a.path,a.version_up,a.emp_insert, a.type_data,
						CASE a.fname WHEN '' THEN '-' ELSE a.fname END AS nfname,
						DATE_FORMAT(a.date_insert, '%d-%b-%Y %H:%i') AS date_modify 
						FROM ".DB_DATABASE4.".fgt_srv_update a 
						WHERE a.type_data = '$gtype' 
						AND a.id_update >'$mxhist'
						AND a.date_insert <'$timenow'
						ORDER BY a.id_update DESC 
					)b
					GROUP BY b.action_name,b.id_action,b.path ";
				$qrup=mysql_query($sqlup,$con2);
				while($rsup=mysql_fetch_array($qrup)){
					
					$sqlq="SELECT id_model,model_code,tag_model_no,label_model_no,model_name,std_qty,customer,
									customer_part_no,customer_part_name,model_picture,status_tag_printing
									FROM ".DB_DATABASE4.".fgt_model WHERE id_model = '".$rsup['id_action']."'";
					$qrq=mysql_query($sqlq,$con2);		
					if(mysql_num_rows($qrq)<>0){
						$rsq=mysql_fetch_array($qrq);
						$upmodelid=$rsq['id_model'];
						$modelpic=$rsq['model_picture'];
						$actionn=$rsup['action_name'];
						if($actionn=="Insert"){
								 $sqlqrup="INSERT INTO ".DB_DATABASE1.".fgt_model SET  id_model='".$rsq['id_model']."',model_code='".$rsq['model_code']."', 
								 tag_model_no='".$rsq['tag_model_no']."', label_model_no='".$rsq['label_model_no']."', model_name='".$rsq['model_name']."', 
									std_qty='".$rsq['std_qty']."', customer='".$rsq['customer']."', customer_part_no='".$rsq['customer_part_no']."', 
									customer_part_name='".$rsq['customer_part_name']."', model_picture='".$modelpic."',
									emp_insert='$user_login', date_insert='".$timenow."'";
								mysql_query($sqlqrup,$con);
								
							 $srvfile=HTTP_SERVER_PATH.DIR_PAGE.DIR_VIEWS.DIR_UPLOAD.DIR_MPIC.$modelpic;
							if(copy($srvfile,"../".DIR_UPLOAD.DIR_MPIC.$modelpic)){ 
								
							}else{
								alert("can't copy file");
								}//if(copy($tmp_name,DIR_UPLOAD.DIR_BTW."$btnname")){

						}else{// =="Update"
							if($rsup['path']=="picmodel"){
								$sqlqrup="UPDATE  ".DB_DATABASE1.".fgt_model SET   model_code='".$rsq['model_code']."', 
								tag_model_no='".$rsq['tag_model_no']."', label_model_no='".$rsq['label_model_no']."', model_name='".$rsq['model_name']."', 
								std_qty='".$rsq['std_qty']."', customer='".$rsq['customer']."', customer_part_no='".$rsq['customer_part_no']."', 
								customer_part_name='".$rsq['customer_part_name']."', model_picture='".$modelpic."', 
								status_tag_printing='".$rsq['status_tag_printing']."',emp_modify='$user_login', date_modify='".$timenow."'
								WHERE id_model='".$upmodelid."'";
								mysql_query($sqlqrup,$con);
								
								$srvfile=HTTP_SERVER_PATH.DIR_PAGE.DIR_VIEWS.DIR_UPLOAD.DIR_MPIC.$modelpic;
								if(copy($srvfile,"../".DIR_UPLOAD.DIR_MPIC.$modelpic)){ 
									
								}else{
									alert("can't copy file");
									}//if(copy($tmp_name,DIR_UPLOAD.DIR_BTW."$btnname")){
							}else{
									$sqlqrup="UPDATE  ".DB_DATABASE1.".fgt_model SET   model_code='".$rsq['model_code']."', 
								tag_model_no='".$rsq['tag_model_no']."', label_model_no='".$rsq['label_model_no']."', model_name='".$rsq['model_name']."', 
								std_qty='".$rsq['std_qty']."', customer='".$rsq['customer']."', customer_part_no='".$rsq['customer_part_no']."', 
								customer_part_name='".$rsq['customer_part_name']."',status_tag_printing='".$rsq['status_tag_printing']."',
								emp_modify='$user_login', date_modify='".$timenow."'
								WHERE id_model='".$upmodelid."'";
								mysql_query($sqlqrup,$con);
								
								}//if($rsup['action_name']=="picmodel"){
							}//if($rsup['action_name']=="insert"){			
									
					}//if(mysql_num_rows($qrq)<>0){
						//Update Log
						$sqll = "INSERT INTO ".DB_DATABASE1.".fgt_log_history SET  emp_id='$top_name', action_name='$actionn',
						table_id_action='$upmodelid',table_name='fgt_model', sql_code='',record_date='$timenow', ip_address='$ip' ";
						mysql_query($sqll,$con)	;
				}//while($rsup=mysql_fetch_array()){
					
				//Start Update local
				$sqlupl="INSERT INTO ".DB_DATABASE1.".fgt_update_master SET last_id_srvupdate='$mxsvup', 
						type_data='$gtype', user_update='$user_login', date_update='$timenow'";
				mysql_query($sqlupl,$con);
				//End  Update local
				//Start Update Server Log
				$sql = "INSERT INTO ".DB_DATABASE4.".fgt_log_download SET  line_id='$user_login', action_name='$actionn', type_data='$gtype',
						 last_id_update='$mxsvup', record_date='$timenow', ip_address='$ip' ";
				mysql_query($sql,$con2);
				//END Update Server Log			
				gotopage("index.php?id=".base64_encode('update_master_data'));
		
		}//if(!empty($_GET['idup']) && $_GET['idup']=="Model"){
			
			
		if(!empty($_GET['idup']) && $_GET['idup']=="Bartender"){
			$gtype= $_GET['idup']; //Bartender
			$timenow=date('Y-m-d H:i:s');
			if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];} else {$ip = $_SERVER["REMOTE_ADDR"];}//else {
				
			$sqltg = "SELECT IFNULL(MAX(last_id_srvupdate),0) AS mxup FROM ".DB_DATABASE1.".fgt_update_master  
					WHERE type_data = '$gtype' AND user_update = '$user_login'";
			$qrtg=mysql_query($sqltg,$con);
			$rstg=mysql_fetch_array($qrtg);
			$mxhist = $rstg['mxup'];
			
			$sqlmxs="SELECT MAX(id_update) AS mxsidrvup FROM ".DB_DATABASE4.".fgt_srv_update WHERE type_data = '$gtype' AND date_insert <'$timenow'";
			$qrmxs=mysql_query($sqlmxs,$con2);
			$rsmxs=mysql_fetch_array($qrmxs);
			$mxsvup = $rsmxs['mxsidrvup'];
			
		 	 	$sqlup="SELECT b.* 
						FROM (
							SELECT a.id_update,a.id_action,a.action_name,a.path,a.version_up,a.emp_insert, a.type_data,
							CASE a.fname WHEN '' THEN '-' ELSE a.fname END AS nfname,
							DATE_FORMAT(a.date_insert, '%d-%b-%Y %H:%i') AS date_modify 
							FROM ".DB_DATABASE4.".fgt_srv_update a 
							WHERE a.type_data = '$gtype' 
							AND a.id_update >'$mxhist'
							AND a.date_insert <'$timenow'
							ORDER BY a.id_update DESC 
						)b
						GROUP BY b.id_action,b.path ,nfname ";
				$qrup=mysql_query($sqlup);
				while($rsup=mysql_fetch_array($qrup)){
						$btwname=$rsup['nfname'].".btw";
						// BTW File Only update
						$newbtnname=$btwname.date('YmdHis').".btw";
						
						copy("../".DIR_UPLOAD.DIR_BTW.$btwname ,"../".DIR_UPLOAD.DIR_BTW."backup_file/$newbtnname") ;//Backup
						$srvfile=HTTP_SERVER_PATH.DIR_PAGE.DIR_VIEWS.DIR_UPLOAD.DIR_BTW.$btwname;
						copy($srvfile,"../".DIR_UPLOAD.DIR_BTW."$btwname");
						
						//Update Log
						$sqll = "INSERT INTO ".DB_DATABASE1.".fgt_log_history SET  emp_id='$top_name', action_name='Download',
						table_id_action='$btwname',table_name='btw', sql_code='',record_date='$timenow', ip_address='$ip' ";
						mysql_query($sqll,$con);
				}//while($rsup=mysql_fetch_array($qrup)){
					
				//Start  Update local
				$sqlupl="INSERT INTO ".DB_DATABASE1.".fgt_update_master SET last_id_srvupdate='$mxsvup', 
						type_data='$gtype', user_update='$user_login', date_update='$timenow'";
				mysql_query($sqlupl,$con);//Update local
				//End  Update local
				//Start Update Server Log
					$sql = "INSERT INTO ".DB_DATABASE4.".fgt_log_download SET  line_id='$user_login', action_name='Update', type_data='$gtype',
						 last_id_update='$mxsvup', record_date='$timenow', ip_address='$ip' ";
					mysql_query($sql,$con2);
					
				//END Update Server Log			
				gotopage("index.php?id=".base64_encode('update_master_data'));
		}//if(!empty($_GET['idup']) && $_GET['idup']=="Bartender"){
			
		//	echo "--".$_GET['idup']."--";
	if($_GET['idup']=="Line"  || $_GET['idup']=="Permission"){
			$gtype=$_GET['idup']; //Line ot Permission
			$timenow=date('Y-m-d H:i:s');
			if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];} else {$ip = $_SERVER["REMOTE_ADDR"];}//else {
			$sqltg = "SELECT IFNULL(MAX(last_id_srvupdate),0) AS mxup FROM ".DB_DATABASE1.".fgt_update_master  
					WHERE type_data = '$gtype' AND user_update = '$user_login' ";
			$qrtg=mysql_query($sqltg,$con);
			$rstg=mysql_fetch_array($qrtg);
			$mxhist = $rstg['mxup'];
			
			$sqlmxs="SELECT MAX(id_update) AS mxsidrvup FROM ".DB_DATABASE4.".fgt_srv_update WHERE type_data = '$gtype' AND date_insert <'$timenow'";
			$qrmxs=mysql_query($sqlmxs,$con2);
			$rsmxs=mysql_fetch_array($qrmxs);
			$mxsvup = $rsmxs['mxsidrvup'];
			
		 	   	$sqlup="SELECT b.* 
						FROM (
							SELECT a.id_update,a.id_action,a.action_name,a.path,a.version_up,a.emp_insert, a.type_data,
							CASE a.fname WHEN '' THEN '-' ELSE a.fname END AS nfname,
							DATE_FORMAT(a.date_insert, '%d-%b-%Y %H:%i') AS date_modify 
							FROM ".DB_DATABASE4.".fgt_srv_update a 
							WHERE a.type_data = '$gtype' 
							AND a.id_update > '$mxhist'
							AND a.date_insert <'$timenow'
							ORDER BY a.id_update DESC 
						)b
						GROUP BY b.id_action,b.path  ";
				$qrup=mysql_query($sqlup,$con2);
				
				while($rsup=mysql_fetch_array($qrup)){
					$idaction=$rsup['id_action'];
					$actionn=$rsup['action_name'];
					if($gtype=="Line"){
						$tbaction="view_line";
						 $sqlq="SELECT a.line_id,a.line_name,a.active
							FROM ".DB_DATABASE4.".view_line a
							WHERE a.line_id = ".$idaction."
							GROUP BY a.line_id";
					$qrq=mysql_query($sqlq,$con2);		
					if(mysql_num_rows($qrq)<>0){
						$rsq=mysql_fetch_array($qrq);
						$lineid=$rsq['line_id'];
						
							if($actionn=="Insert"){
								 $sqlup="INSERT INTO ".DB_DATABASE1.".view_line SET line_id='".$lineid."', line_name='".$rsq['line_name']."', 
									  active='".$rsq['active']."' ,emp_modify='$user_login', date_modify='".$timenow."' ";
								mysql_query($sqlup,$con);
						
							}else{ // update
							
								$sqlup="UPDATE ".DB_DATABASE1.".view_line SET line_name='".$rsq['line_name']."', 
										emp_modify='$user_login', date_modify='".$timenow."' ,active='".$rsq['active']."'
										WHERE line_id='".$lineid."' ";
								mysql_query($sqlup,$con);
							}//if($rsup['action_name']=="insert"){
						}//if(mysql_num_rows($qrq)<>0){	
					}else if($gtype=="Permission"){
						$tbaction="Permission";
					 		$sqlq="SELECT emp_id,emp_pass,emp_name,permission,active 
									FROM ".DB_DATABASE4.".view_permission 
									WHERE emp_id = ".$idaction." ";
						$qrq=mysql_query($sqlq,$con2);		
						if(mysql_num_rows($qrq)<>0){
							$rsq=mysql_fetch_array($qrq);
							if($actionn=="Insert"){
							 $sqlup="INSERT INTO ".DB_DATABASE1.".view_permission SET emp_id='".$rsq['emp_id']."', 
										emp_pass='".$rsq['emp_pass']."',emp_name='".$rsq['emp_name']."', permission='".$rsq['permission']."' ,
										emp_modify='$user_login', date_modify='".$timenow."' ";
								mysql_query($sqlup,$con);
							}else{ // update
								$sqlup="UPDATE ".DB_DATABASE1.".view_permission SET emp_pass='".$rsq['emp_pass']."',emp_name='".$rsq['emp_name']."',
										 permission='".$rsq['permission']."' ,emp_modify='$user_login', date_modify='".$timenow."' 
										 WHERE emp_id='".$rsq['emp_id']."' ";
								mysql_query($sqlup,$con);
							}//if($rsup['action_name']=="insert"){
						}//if(mysql_num_rows($qrq)<>0){
					}//if($gtype=="Line"){
					
							
						
						//Update Log	
						$sqll = "INSERT INTO ".DB_DATABASE1.".fgt_log_history SET  emp_id='$top_name', action_name='$actionn',
						table_id_action='$lineid',table_name='$tbaction', sql_code='',record_date='$timenow', ip_address='$ip' ";
						mysql_query($sqll,$con)	;
				
						
						
				}//while($rsup=mysql_fetch_array($qrup)){
				//Start  Update local
			 	$sqlupl="INSERT INTO ".DB_DATABASE1.".fgt_update_master SET last_id_srvupdate='$mxsvup', 
						type_data='$gtype', user_update='$user_login', date_update='$timenow'";
				mysql_query($sqlupl,$con);//Update local
				//End  Update local
				//Start Update Server Log
					
				$sql = "INSERT INTO ".DB_DATABASE4.".fgt_log_download SET  line_id='$user_login', action_name='$actionn', type_data='$gtype',
						 last_id_update='$mxsvup', record_date='$timenow', ip_address='$ip' ";
				mysql_query($sql,$con2);
				//END Update Server Log				
			gotopage("index.php?id=".base64_encode('update_master_data'));
	}//if(!empty($_GET['idup']) && $_GET['idup']=="Line"){			
			
			
			
?>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="rightPane" align="center">
<?

			$arr = array("Model", "Line", "Bartender","Permission");
			$i=0;
		
			
				?>
                            
<form id="form1" name="form1" method="post" action="">
 
<table width="780px"  border="1" bordercolor="#CC9966"class="table01">
    <tr >
      <th height="29" colspan="7">
        <div align="center">Manage Customer Data<br />
(การจัดการข้อมูลจากระบบหลัก)</div> </th>
      </tr>
    <tr>
      <th width="10%" height="28">Type data<br/>ลำดับ</th>
      <th width="8%">Update<br />
        อัพเดต</th>
      <th width="12%">Action Name<br />สิ่งที่ทำ</th>
      <th width="13%">Last Version<br />รุ่นล่าสุด</th>
      <th width="22%">File<br /> ไฟล์</th>
      <th width="20%">Date modified<br />วันที่แก้ไข</th>
      <th width="15%">User modified<br />
      ผู้แก้ไข</th>
    </tr>
       <?php while ($i < 4) {   
	   		$rstype=$arr[$i];
					
		 	$sqltg = "SELECT IFNULL(MAX(last_id_srvupdate),0) AS mxup FROM ".DB_DATABASE1.".fgt_update_master   
					WHERE type_data = '$rstype' AND user_update = '$user_login'";
			$qrtg=mysql_query($sqltg,$con);
			$rstg=mysql_fetch_array($qrtg);
			$mxhist = $rstg['mxup'];
	
		     $qp="SELECT b.* 
					FROM (
						SELECT a.id_update,a.id_action,a.action_name,a.path,a.version_up,a.emp_insert, a.type_data,
						CASE a.fname WHEN '' THEN '-' ELSE a.fname END AS nfname,
						DATE_FORMAT(a.date_insert, '%d-%b-%Y %H:%i') AS date_modify 
						FROM ".DB_DATABASE4.".fgt_srv_update a 
						WHERE a.type_data = '$rstype' 
						AND a.id_update > '$mxhist'
						ORDER BY a.id_update DESC 
					)b
					GROUP BY b.action_name,b.id_action,b.path
					ORDER BY b.date_modify DESC ";		
			$qrp=mysql_query($qp,$con2);
			$np=mysql_num_rows($qrp);			
			if($np<>0){	
	   ?>
       
       <tr  <?php $v =0; $v = $v + 1; echo  icolor($v); ?> onMouseOver="className=&quot;over&quot;"  onMouseOut="className=&quot;&quot;" align="center">
          <td height="28" <? if($np>1){ $nps = $np+1;echo "rowspan='".$nps."'";}?> ><strong> <?=$rstype?>
          </strong></td>
          <td <? if($np>1){ $nps = $np+1;echo "rowspan='".$nps."'";}?>>
          <a href="index.php?id=<?=base64_encode('update_master_data')?>&idup=<?=$rstype?>" onclick="return confirm('Do you need to update Master data?');" >
            <img src="../../images/update2.png" /></a>
          </td>
          <?
             //Start multi row&column
              if($np==1){ 
                while($rsp=mysql_fetch_array($qrp)){
    
             ?>
                  <td align="center"><?=$rsp['action_name']?></td>
                  <td align="center"><?=$rsp['version_up']?></td>
                  <td align="center"><?=$rsp['nfname']?></td>
                  <td align="center"><?=$rsp['date_modify']?></td>
                  <td align="center"><?=$rsp['emp_insert']?></td>
             
                 <?
                }//	while($rsp=mysql_fetch_array($qrp)){
            }//if($np>1){ 
            ?>
    </tr>
          <?
             //Start multi row&column
              if($np==1){ 
                while($rsp=mysql_fetch_array($qrp)){
    
             ?>
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
                  <td height="26" align="center"><?=$rsp['action_name']?></td>
                  <td align="center"><?=$rsp['version_up']?></td>
                  <td align="center"><?=$rsp['nfname']?></td>
                  <td align="center"><?=$rsp['date_modify']?></td>
                  <td align="center"><?=$rsp['emp_insert']?></td>
                </tr>
                 <?
                }//	while($rsp=mysql_fetch_array($qrp)){
            }//if($np>1){ 
              //END  multi row&column

		}//if($np<>0){	
			
		$i++;
        } // while ($i < 3) {
	?>
  
  </table>
  </form>
  <br/>
<input type="button" value="Refresh Page" onClick="window.location.reload()">
</div>
  

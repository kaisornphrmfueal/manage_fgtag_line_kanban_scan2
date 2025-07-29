<?php


//*---------------------------------SUb time----------------------------//
function subtime($t_time){
		 $rest = substr($t_time, 0, -3);
		 return $rest; 
		}
//----------------------------- LOG HOSTORY -------------------------------------------------//

	
	/////////-----------------///////////////

function log_hist($user_id,$action,$id_action,$tatblen,$sql_log)
 {
  $rec_date = date('Y-m-d H:i:s');
  $sql_ex = addslashes($sql_log);
  $database_log =DB_DATABASE1;
  if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
								$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
								} else {
								$ip = $_SERVER["REMOTE_ADDR"];
						}//else {
	 	$sql = "INSERT INTO $database_log.fgt_log_history SET  emp_id='$user_id', action_name='$action',
				 table_id_action='$id_action',table_name='$tatblen',  sql_code='$sql_ex',record_date='$rec_date', ip_address='$ip' ";

 		mysql_query($sql);
//echo "==".$sql ;
 }	


 function log_servup($action,$id_action,$type,$detail,$path,$user_id)
 {
 // $database_log =DB_DATABASE1;
	 	$sql = "INSERT INTO ".DB_DATABASE1.".fgt_srv_update SET  action_name='$action', 
							id_action='$id_action', type_data='$type', path='$detail', fname='$path', 
							version_up=(SELECT IFNULL(MAX(tptb.version_up),0) AS mxver  
									FROM  ".DB_DATABASE1.".fgt_srv_update AS tptb  WHERE type_data = 'Model')+1, 
							emp_insert='$user_id', date_insert='".date('Y-m-d H:i:s')."' ";

 		mysql_query($sql);
 	}
 
function log_record_packing($user_id,$action,$sql_log)
	{
		$rec_date = date('Y-m-d H:i:s');
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$sql_ex = addslashes($sql_log);
	
		$sql = "INSERT INTO ".DB_DATABASE4.".ewi_log SET user_id='$user_id',action_name='$action',action_detail = '$sql_ex',record_date='$rec_date',log_ip='$ip'";
		mysql_query($sql);
	}
	
	

	function selectName($ilog){
		$database = DB_DATABASESSO;
		 $sqln = "SELECT CONCAT(name_en,' ',lastname_en) AS sname FROM $database.so_employee_data WHERE emp_id = '$ilog' ";
 		$qrn=mysql_query($sqln);
		$rsn=mysql_fetch_array($qrn);
		return $rsn['sname'];
	}
	
function selectLineName($lsid){
		$database = DB_DATABASE1;
	 	 $sqln = "SELECT line_name FROM $database.view_line WHERE line_id =  '$lsid' ";
 		$qrn=mysql_query($sqln);
		$rsn=mysql_fetch_array($qrn);
		return $rsn['line_name'];
	
	}
	
function selectMxModel(){
		$sqltg = "SELECT IFNULL(max(id_model),0)+1  AS mxmodel from ".DB_DATABASE1.".fgt_model ";
 		$qrtg=mysql_query($sqltg);
		$rstg=mysql_fetch_array($qrtg);
		return $rstg['mxmodel'];
	
}

function selectMxTag($lsid){
	 	//$sqltg = "SELECT IFNULL(max(id_tag),0)+1  AS mxtag from ".DB_DATABASE1.".fgt_srv_tag ";
		$sqltg = "SELECT ROUND( IFNULL(SUBSTRING(MAX(tag_no) ,3,7),0)+1  ,0)  AS mxtag
					from ".DB_DATABASE1.".fgt_srv_tag 
					WHERE  line_id = '$lsid' ";
 		$qrtg=mysql_query($sqltg);
		$rstg=mysql_fetch_array($qrtg);
		return $rstg['mxtag'];
	/*
	SELECT ROUND( IFNULL(SUBSTRING(MAX(tag_no) ,3,7),0)+1  ,0)
		from  fgt_srv_tag  
		WHERE  `line_id` = '07' 
		
	*/
	
	
	}


function selectMxSupp(){
		$sqltg = "SELECT IFNULL(max(id_print_tag),0)+1  AS mxid from ".DB_DATABASE1.".fgt_supplier_tag ";
 		$qrtg=mysql_query($sqltg);
		$rstg=mysql_fetch_array($qrtg);
		return $rstg['mxid'];
	
}

/*
function selectMxupload(){
		$sqltg = "SELECT MAX(a.id_tag)  AS mxtag
					FROM ".DB_DATABASE1.".fgt_tag  a
					WHERE a.line_id='$user_login'
					AND  a.status_print in ( 'Printed','Reprinted') 
					AND upload_status = '0'";
 		$qrtg=mysql_query($sqltg);
		$rstg=mysql_fetch_array($qrtg);
		return $rstg['mxtag'];
}
*/


function whileprt($st,$ed,$qty){
		while($st <= $ed) {
			$x=$x.",".$st;
			$st++;
		} 
		$cqty =(16-$qty)+1;
		$runing = 1;
		while($runing <= $cqty) {
			$y=$y.",";
			$runing++;
		} 
	 return $x.$y; 
	 //echo whileprt(substr('BN700021', 2),substr('BN700025', 2),5);
}




//----------------------------------END PATH---------------------------------------//
////////////////////////////////////////// Date show////////////////////////////////////////////////////////////	
function echodate($dshow){
		$a = $dshow;
		list($d,$m,$y) = explode('-',$a);
		$ndate= date("d M Y", strtotime($y.'-'.$m.'-'.$d));
		 return $ndate; // Êè§¤èÒ¡ÅÑº
		}
		//echo echodate("2012-11-11");
		

/*-----------------------------------------------------------*/
 ///////////////////// ip ////////////////////

	function getValeInput($input,$typ){
		$instr = "";
		if($typ=="int"){ $instr="".(int)$_POST[$input].",";	
		}else if($typ=="float"){ $instr = "".(float)$_POST[$input].",";			
		}else if($typ=="vchar"){ $instr = "'".$_POST[$input]."',";		
		}else if($typ=="file_name"){ $instr = "'".$_FILES[$input]['name']."',";	
		}else if($typ=="date"){ $instr = "'".date("Y-m-d")."',";			
		}else if($typ=="dtime"){ $instr = "'".date("Y-m-d H:i:s")."',";			
		}else if($typ=="ip"){ $instr = "'".$_SERVER['REMOTE_ADDR']."',";		
		}else if($typ=="imgbyte"){
			 $instr = "'".addslashes(fread(fopen($_FILES[$input]['tmp_name'],"r"),filesize($_FILES[$input]['tmp_name'])))."',";			
		}
		return $instr;
	}
////////////////////////////////////////////////////////////////////////////////////////////////////


function insertPOST($tb){
			$strN = ""; $strVal = "";
			foreach ($_POST as $key => $value){ 
			list($a) = explode("*", $key);
			list($b) = explode("*", $value);
			if( $b != ' ' ){  $strN .= $a.",";  $strVal .="'". $b."',"; 	}
			}
			$strN = substr($strN,4,-1); 
			$strVal = substr($strVal,6,-1);
			$sql = " insert into $tb ($strN) values ($strVal) "; //echo $sql; exit;
			return q( $sql ) or die( mysql_error()." No : ".mysql_errno() );
}


function updatePOST($tb,$wh=''){ 
			$strVal = "";
				foreach ($_POST as $key => $value){ 
			list($a) = explode("*", $key);
			list($b) = explode("*", $value);
			if( $b != ' ' ){  $strVal .= $a." = " ."'". $b."',"; 	}
			}
			$strVal = substr($strVal,12,-1);
			$sql = " update $tb set $strVal $wh ";  //echo $sql; exit;
			return q( $sql ) or die( mysql_error()." No : ".mysql_errno() );
	}
	
	#55555555555555555555555555
	
	function data_visible($table,$hfile,$status,$hid,$hvalue){

	if ($status=="Y")
		$xstatus="N";
	else
		$xstatus="Y";
	$sql ="UPDATE $table SET $hfile='$xstatus' WHERE $hid=$hvalue";
	return q( $sql ) or die( mysql_error()." No : ".mysql_errno() );
}


######################################
function delete_data($table,$did,$dvalue){
	$sql ="DELETE FROM $table WHERE $did=$dvalue";
	return q( $sql ) or die( mysql_error()." No : ".mysql_errno() );
}
	
# 55555555555555555555555555555555555555555555555555555555555555555555555555555555555555555 #
	function delete($table_sql){
        return mysql_query(" delete from $table_sql ") or die( mysql_error()." No : ".mysql_errno() );	
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////
function query($sql){
		return mysql_query($sql);
}
function rows($re)
{
	$i = mysql_num_rows($re);
	return $i;
}
function fetch($re)
{
	return mysql_fetch_array($re);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function alert($t)
	{
		echo "<script language='javascript'>";
		echo "alert('$t');";
		echo "</script>";
	}
	
function go_page_opener($url){
		//echo "<font size=2 color=green>processing...</font>";
		echo "<script type=\"text/javascript\">window.opener.location.reload(); window.close();</script>";
	}	
function go_page_parent($url){
		//echo "<font size=2 color=green>processing...</font>";
		$urls= "index.php?id=".base64_encode('print');
		echo "<script type=\"text/javascript\"> window.opener.location.replace('$urls'); window.close();</script>";
}											

function gotopage($t)
	{
		echo "<script language='javascript'>";
		echo "window.location='$t';";
		echo "</script>";
	}
	
	
	function icolor($i)
{
			if ($i%2 == 0)
			{
				echo "bgcolor='#EDF5FC' ";
			}else{
			
				echo  "bgcolor='#FFFFFF' ";
			}
}
////////////////////////////////////áºè§Ë¹éÒ
function pnavigator($before_p,$plus_p,$total,$total_p,$chk_page,$upage){      
    global $urlquery_str;   
    $pPrev=$chk_page-1;   
    $pPrev=($pPrev>=0)?$pPrev:0;   
    $pNext=$chk_page+1;   
    $pNext=($pNext>=$total_p)?$total_p-1:$pNext;        
    $lt_page=$total_p-4;  
	$nClass=""; 
    if($chk_page>0){     
        echo "<a  href='?id=".$upage."&s_page=$pPrev&urlquery_str=".$urlquery_str."' class='naviPN'>Prev</a>";   
    }   
    if($total_p>=11){   
        if($chk_page>=4){   
            echo "<a $nClass href='?id=".$upage."&s_page=0&urlquery_str=".$urlquery_str."'>1</a><a class='SpaceC'>. . .</a>";      
        }   
        if($chk_page<4){   
            for($i=0;$i<$total_p;$i++){     
                $nClass=($chk_page==$i)?"class='selectPage'":"";   
                if($i<=4){   
                echo "<a $nClass href='?id=".$upage."&s_page=$i&urlquery_str=".$urlquery_str."'>".intval($i+1)."</a> ";      
                }   
                if($i==$total_p-1 ){    
                echo "<a class='SpaceC'>. . .</a><a $nClass href='?id=".$upage."&s_page=$i&urlquery_str=".$urlquery_str."'>".intval($i+1)."</a> ";      
                }          
            }   
        }   
        if($chk_page>=4 && $chk_page<$lt_page){   
            $st_page=$chk_page-3;   
            for($i=1;$i<=5;$i++){   
                $nClass=($chk_page==($st_page+$i))?"class='selectPage'":"";   
                echo "<a $nClass href='?id=".$upage."&s_page=".intval($st_page+$i).@$_SESSION['ses_qCurProvince']."'>".intval($st_page+$i+1)."</a> ";         
            }   
            for($i=0;$i<$total_p;$i++){     
                if($i==$total_p-1 ){    
                $nClass=($chk_page==$i)?"class='selectPage'":"";   
                echo "<a class='SpaceC'>. . .</a><a $nClass href='?id=".$upage."&s_page=$i&urlquery_str=".$urlquery_str."'>".intval($i+1)."</a> ";      
                }          
            }                                      
        }      
        if($chk_page>=$lt_page){   
            for($i=0;$i<=4;$i++){   
                $nClass=($chk_page==($lt_page+$i-1))?"class='selectPage'":"";   
                echo "<a $nClass href='?id=".$upage."&s_page=".intval($lt_page+$i-1).@$_SESSION['ses_qCurProvince']."'>".intval($lt_page+$i)."</a> ";      
            }   
        }           
    }else{   
        for($i=0;$i<$total_p;$i++){     
            $nClass=($chk_page==$i)?"class='selectPage'":"";   
            echo "<a href='?id=".$upage."&s_page=$i&urlquery_str=".$urlquery_str."' $nClass  >".intval($i+1)."</a> ";      
        }          
    }      
    if($chk_page<$total_p-1){   
        echo "<a href='?id=".$upage."&s_page=$pNext&urlquery_str=".$urlquery_str."'  class='naviPN'>Next</a>";   
    }   
}      
/// PAGE USER  -------

////////////////////////////////////áºè§Ë¹éÒ
function page_navigator($before_p,$plus_p,$total,$total_p,$chk_page){     
    global $urlquery_str;  
    $pPrev=$chk_page-1;  
    $pPrev=($pPrev>=0)?$pPrev:0;  
    $pNext=$chk_page+1;  
    $pNext=($pNext>=$total_p)?$total_p-1:$pNext;       
    $lt_page=$total_p-4;  
    if($chk_page>0){    
        echo "<a  href='?s_page=$pPrev&urlquery_str=".$urlquery_str."' class='naviPN'>Prev</a>";  
    }  
    if($total_p>=11){  
        if($chk_page>=4){  
            echo "<a $nClass href='?s_page=0&urlquery_str=".$urlquery_str."'>1</a><a class='SpaceC'>. . .</a>";     
        }  
        if($chk_page<4){  
            for($i=0;$i<$total_p;$i++){    
                $nClass=($chk_page==$i)?"class='selectPage'":"";  
                if($i<=4){  
                echo "<a $nClass href='?s_page=$i&urlquery_str=".$urlquery_str."'>".intval($i+1)."</a> ";     
                }  
                if($i==$total_p-1 ){   
                echo "<a class='SpaceC'>. . .</a><a $nClass href='?s_page=$i&urlquery_str=".$urlquery_str."'>".intval($i+1)."</a> ";     
                }         
            }  
        }  
        if($chk_page>=4 && $chk_page<$lt_page){  
            $st_page=$chk_page-3;  
            for($i=1;$i<=5;$i++){  
                $nClass=($chk_page==($st_page+$i))?"class='selectPage'":"";  
                echo "<a $nClass href='?s_page=".intval($st_page+$i)."'>".intval($st_page+$i+1)."</a> ";      
            }  
            for($i=0;$i<$total_p;$i++){    
                if($i==$total_p-1 ){   
                $nClass=($chk_page==$i)?"class='selectPage'":"";  
                echo "<a class='SpaceC'>. . .</a><a $nClass href='?s_page=$i&urlquery_str=".$urlquery_str."'>".intval($i+1)."</a> ";     
                }         
            }                                     
        }     
        if($chk_page>=$lt_page){  
            for($i=0;$i<=4;$i++){  
                $nClass=($chk_page==($lt_page+$i-1))?"class='selectPage'":"";  
                echo "<a $nClass href='?s_page=".intval($lt_page+$i-1)."'>".intval($lt_page+$i)."</a> ";     
            }  
        }          
    }else{  
        for($i=0;$i<$total_p;$i++){    
            $nClass=($chk_page==$i)?"class='selectPage'":"";  
            echo "<a href='?s_page=$i&urlquery_str=".$urlquery_str."' $nClass  >".intval($i+1)."</a> ";     
        }         
    }     
    if($chk_page<$total_p-1){  
        echo "<a href='?s_page=$pNext&urlquery_str=".$urlquery_str."'  class='naviPN'>Next</a>";  
    }  
}      /// PAGE USER  -------


function page_navigator_user($before_p,$plus_p,$total,$total_p,$chk_page,$gpage,$gtxt){      
    global $urlquery_str;   
    $pPrev=$chk_page-1;   
    $pPrev=($pPrev>=0)?$pPrev:0;   
    $pNext=$chk_page+1;   
    $pNext=($pNext>=$total_p)?$total_p-1:$pNext;        
    $lt_page=$total_p-4;   
    if($chk_page>0){     
        echo "<a  href='?id=".$gpage."&serh=".$gtxt."&s_page=$pPrev&urlquery_str=".$urlquery_str."' class='naviPN'>Prev</a>";   
    }   
    if($total_p>=11){   
        if($chk_page>=4){   
            echo "<a $nClass href='?id=".$gpage."&serh=".$gtxt."&s_page=0&urlquery_str=".$urlquery_str."'>1</a><a class='SpaceC'>. . .</a>";      
        }   
        if($chk_page<4){   
            for($i=0;$i<$total_p;$i++){     
                $nClass=($chk_page==$i)?"class='selectPage'":"";   
                if($i<=4){   
                echo "<a $nClass href='?id=".$gpage."&serh=".$gtxt."&s_page=$i&urlquery_str=".$urlquery_str."'>".intval($i+1)."</a> ";      
                }   
                if($i==$total_p-1 ){    
                echo "<a class='SpaceC'>. . .</a><a $nClass href='?id=".$gpage."&serh=".$dates."&s_page=$i&urlquery_str=".$urlquery_str."'>".intval($i+1)."</a> ";      
                }          
            }   
        }   
        if($chk_page>=4 && $chk_page<$lt_page){   
            $st_page=$chk_page-3;   
            for($i=1;$i<=5;$i++){   
                $nClass=($chk_page==($st_page+$i))?"class='selectPage'":"";   
                echo "<a $nClass href='?id=".$gpage."&serh=".$gtxt."&s_page=".intval($st_page+$i).@$_SESSION['ses_qCurProvince']."'>".intval($st_page+$i+1)."</a> ";         
            }   
            for($i=0;$i<$total_p;$i++){     
                if($i==$total_p-1 ){    
                $nClass=($chk_page==$i)?"class='selectPage'":"";   
                echo "<a class='SpaceC'>. . .</a><a $nClass href='?id=".$gpage."&serh=".$dates."&s_page=$i&urlquery_str=".$urlquery_str."'>".intval($i+1)."</a> ";      
                }          
            }                                      
        }      
        if($chk_page>=$lt_page){   
            for($i=0;$i<=4;$i++){   
                $nClass=($chk_page==($lt_page+$i-1))?"class='selectPage'":"";   
                echo "<a $nClass href='?id=".$gpage."&serh=".$gtxt."&s_page=".intval($lt_page+$i-1).@$_SESSION['ses_qCurProvince']."'>".intval($lt_page+$i)."</a> ";      
            }   
        }           
    }else{   
        for($i=0;$i<$total_p;$i++){     
            $nClass=($chk_page==$i)?"class='selectPage'":"";   
            echo "<a href='?id=".$gpage."&serh=".$gtxt."&s_page=$i&urlquery_str=".$urlquery_str."' $nClass  >".intval($i+1)."</a> ";      
        }          
    }      
    if($chk_page<$total_p-1){   
        echo "<a href='?id=".$gpage."&serh=".$gtxt."&s_page=$pNext&urlquery_str=".$urlquery_str."'  class='naviPN'>Next</a>";   
    }   
}      



///------- PAGE USER ------------
////////////////////////////////////»Ô´áºè§Ë¹éÒ
function cutstring($str, $len) {
  if (strlen($str)<=$len) return $str;
  else return sprintf("%.".$len."s..", $str);
}


      
////////////////////////////////// function string date
	function DateThai($strDate)
	{
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strMonthCut = Array("","มกราคม","กุมภาพันธ์"," มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม"," กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strDay $strMonthThai $strYear";
	}
	
	
	////////////////////////  function num to string
function bathformat($number) {
  $numberstr = array('ศูนย์','หนึ่ง','สอง','สาม','สี่','ห้า','หก','เจ็ด','แปด','เก้า','สิบ');
  $digitstr = array('','สิบ','ร้อย','พัน','หมื่น','แสน','ล้าน');

  $number = str_replace(",","",$number); //ลบ comma
  $number = explode(".",$number); //แยกจุดทศนิยมออก

  //เลขจำนวนเต็ม
  $strlen = strlen($number[0]);
  $result = '';
  for($i=0;$i<$strlen;$i++) {
    $n = substr($number[0], $i,1);
    if($n!=0) {
      if($i==($strlen-1) AND $n==1){ $result .= 'เอ็ด'; }
      elseif($i==($strlen-2) AND $n==2){ $result .= 'ยี่'; }
      elseif($i==($strlen-2) AND $n==1){ $result .= ''; }
      else{ $result .= $numberstr[$n]; }
      $result .= $digitstr[$strlen-$i-1];
    }
  }
  
  //จุดทศนิยม
  $strlen = strlen($number[1]);
  if ($strlen>2) { //ทศนิยมมากกว่า 2 ตำแหน่ง คืนค่าเป็นตัวเลข
    $result .= 'จุด';
    for($i=0;$i<$strlen;$i++) {
      $result .= $numberstr[(int)$number[1][$i]];
    }
  } else { //คืนค่าเป็นจำนวนเงิน (บาท)
    $result .= 'บาท';
    if ($number[1]=='0' OR $number[1]=='00' OR $number[1]=='') {
      $result .= 'ถ้วน';
    } else {
      //จุดทศนิยม (สตางค์)
      for($i=0;$i<$strlen;$i++) {
        $n = substr($number[1], $i,1);
        if($n!=0){
          if($i==($strlen-1) AND $n==1){$result .= 'เอ็ด';}
          elseif($i==($strlen-2) AND $n==2){$result .= 'ยี่';}
          elseif($i==($strlen-2) AND $n==1){$result .= '';}
          else{ $result .= $numberstr[$n];}
          $result .= $digitstr[$strlen-$i-1];
        }
      }
      $result .= 'สตางค์';
    }
  }
  return $result;
}

//--------- str_replace

function nl2br2($string) {
$string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
return $string;
} 



function ConfirmCancel(){
			if 	 (confirm ("Do you want to cancel data?")==true){ 
				return true;
			}
				return false;
		}
		
function convDate($dDate) {
$datecon = date_create_from_format('d/m/Y', $dDate);
$daten=date_format($datecon, 'm/d/Y');
return $daten;
} 
	
//-------START FG TAG ----------------------------------------------
function printTag($tagn){
		  $sqltg="SELECT b.id_model,b.model_code,a.tag_no,a.shift,b.model_name,b.tag_model_no,a.date_print,a.date_reprint,
		 			 DATE_FORMAT(a.date_print, '%d-%b-%Y %H:%i')  AS dateprint,
					CASE  a.status_print WHEN 'Reprinted' THEN  CONCAT('Reprintd : ',who_reprint, ' ',
					DATE_FORMAT(a.date_reprint, '%d-%b-%Y %H:%i')) 	 ELSE  '' END AS datereprint,
					 a.tag_qty,
					CASE  a.tag_qty WHEN 1 THEN  a.sn_start ELSE  CONCAT(a.sn_start,'-',a.sn_end) END AS allserial,
					a.sn_start,a.sn_end,a.line_id,a.fg_tag_barcode,b.customer_part_no,b.customer_part_name,
					b.model_picture,a.status_print,b.status_tag_printing,c.line_name,b.std_qty,a.matching_ticket_no,
						IF(a.shift ='Day', 	CONCAT( d.leader_name_day,'(',d.leader_day, ')' ) , 
                               CONCAT( d.leader_name_night,'(',d.leader_night, ')' )) AS leadern,
                   IF(a.shift ='Day', 	CONCAT( d.floater_name_day,'(',d.floater_day, ')' ), 
                              	CONCAT( d.floater_name_night,'(',d.floater_night, ')' ))  AS floatern ,    
                   IF(a.shift ='Day',	CONCAT( d.emp_print_tag_name_day,'(',d.emp_print_tag_day, ')' ), 
                              		CONCAT( d.emp_print_tag_name_night,'(',d.emp_print_tag_night,')' )) AS printern 
				FROM ".DB_DATABASE1.".fgt_srv_tag  a
				LEFT JOIN ".DB_DATABASE1.".fgt_model b ON a.id_model=b.id_model
				LEFT JOIN ".DB_DATABASE1.".view_line c ON a.line_id=c.line_id
				LEFT JOIN ".DB_DATABASE1.".fgt_leader d ON a.work_id=d.work_id
				WHERE a.tag_no = '$tagn'";
							$qrtg=mysql_query($sqltg);
							if(mysql_num_rows($qrtg)<>0){
								$rstg = mysql_fetch_array($qrtg);   
								$idline=$rstg['line_id'];
								$srv=SRV_SHARED;
								$srv_template = SRV_TEMPLATE;
								$serialtxt=whileprt($rstg['sn_start'],$rstg['sn_end'],$rstg['tag_qty']);
								$mt_ticket=$rstg['matching_ticket_no'];
								$mtkno9=sprintf("%09d",$mt_ticket);
								$tk_bcode=$mtkno9." pline";
								//$fp = fopen("../".DIR_UPLOAD.DIR_BTW."fgtag.txt", "w");
								$fp = fopen("../".DIR_UPLOAD.DIR_PRINT.$idline.".txt", "w");
							
								//$fp = fopen("\\\\$srv\\test_tag\\uploads\\$idline\\fgtag.txt","w") or die("Can't Create file, Please contact Administrator.");
									$i=1;
							fwrite($fp,"Model,Tag No,Shift,Model Name,Model No,Produce Date ,Qty ,Serial No 1-n,Printed by,Printed date,Serial 1,Serial 2,Serial 3,Serial 4,Serial 5,Serial 6,Serial 7,Serial 8,Serial 9,Serial 10,Serial 11,Serial 12,Serial 13,Serial 14,Serial 15,Serial 16,FgTag,Part No,Part Name,image,status print,fg print,stdQty,datereprint,ticket_no,ticket_bcode,Leader,Floater,Printer\r\n");
							fwrite($fp,$rstg['model_code'].",".$rstg['tag_no'].",".$rstg['shift'].",".$rstg['model_name'].",".$rstg['tag_model_no'].",".$rstg['dateprint'].",".$rstg['tag_qty'].",".$rstg['allserial'].",".$rstg['line_name'].",".$rstg['dateprint'].$serialtxt.$rstg['fg_tag_barcode'].",".$rstg['customer_part_no'].",".$rstg['customer_part_name'].",".$rstg['model_picture'].",".$rstg['status_print'].",,".$rstg['std_qty'].",".$rstg['datereprint'].",".$mtkno9.",".$tk_bcode.",".$rstg['leadern'].",".$rstg['floatern'].",".$rstg['printern']."\r\n");
							fclose($fp);
										  				
							/* START BACKUP DATA TO FILE*/
							$strFileName = "tag_backup/".date('Ymd').".txt";
							$objFopen = fopen($strFileName, 'a');
							fwrite($objFopen,$rstg['model_code'].",".$rstg['tag_no'].",".$rstg['line_id'].",".$rstg['shift'].",".$rstg['model_name'].",".$rstg['tag_model_no'].",".$rstg['date_print'].",".$rstg['tag_qty'].",".$rstg['sn_start'].",".$rstg['sn_end'].",".$rstg['line_name'].",".$rstg['fg_tag_barcode'].",".$rstg['customer_part_no'].",".$rstg['customer_part_name'].",".$rstg['model_picture'].",".$rstg['status_print'].",,".$rstg['std_qty'].",".$rstg['date_reprint'].",".$mtkno9.",".$tk_bcode.",".$rstg['leadern'].",".$rstg['floatern'].",".$rstg['printern']."\r\n");
							fclose($objFopen);
							/*END BACKUP DATA TO FILE */						
															
								if($rstg['status_tag_printing']==0){		//0=both,1=only fg Tag
										$flgCopyDT = copy("../".DIR_UPLOAD.DIR_PRINT.$idline.".txt", "\\\\$srv\\$srv_template\\print\\$idline\\fgtag.txt");
									
										$flgCopy1 = copy("\\\\$srv\\$srv_template\\print\\$idline\\cmd.txt", "\\\\$srv\\$srv_template\\print\\$idline\\cmdb\\cmd.txt");
									
										$flgCopy2 = copy("\\\\$srv\\$srv_template\\print\\$idline\\cmd.txt", "\\\\$srv\\$srv_template\\print\\$idline\\cmds\\cmd.txt");
										
										//New
										//$flgCopyDT = copy("../".DIR_UPLOAD.DIR_PRINT.$idline.".txt", "\\..\\..\\..\\..\\..\\test_tag\\print\\$idline\\fgtag.txt");
 										//$flgCopy1 = copy("\\..\\..\\..\\..\\..\\test_tag\\print\\$idline\\cmd.txt", "\\..\\..\\..\\..\\..\\test_tag\\print\\$idline\\cmdb\\cmd.txt");
 										//$flgCopy2 = copy("\\..\\..\\..\\..\\..\\test_tag\\print\\$idline\\cmd.txt", "\\..\\..\\..\\..\\..\\test_tag\\print\\$idline\\cmds\\cmd.txt");
									
							// START Old
									//	$flgCopy1 = copy("../uploads/btw/cmd.txt", "../uploads/btw/cmdb/cmd.txt");
									//	$flgCopy2 = copy("../uploads/btw/cmd.txt", "../uploads/btw/cmds/cmd.txt");
							// END Old			
										
										
									}else{
										$flgCopyDT = copy("../".DIR_UPLOAD.DIR_PRINT.$idline.".txt", "\\\\$srv\\$srv_template\\print\\$idline\\fgtag.txt");
										$flgCopy1 = copy("\\\\$srv\\$srv_template\\print\\$idline\\cmd.txt", "\\\\$srv\\$srv_template\\print\\$idline\\cmdo\\cmd.txt");
										
										//New
										//$flgCopyDT = copy("../".DIR_UPLOAD.DIR_PRINT.$idline.".txt", "\\..\\..\\..\\..\\..\\test_tag\\print\\$idline\\fgtag.txt");
 										//$flgCopy1 = copy("\\..\\..\\..\\..\\..\\test_tag\\print\\$idline\\cmd.txt", "\\..\\..\\..\\..\\..\\test_tag\\print\\$idline\\cmdb\\cmd.txt");

									
									// OLD 	$flgCopy1 = copy("../uploads/btw/cmd.txt", "../uploads/btw/cmdo/cmd.txt");
									
										}//if($rstg['status_tag_printing']==0){
						//eprintTag	
								}//if(mysql_num_rows($qrtg)<>0){
	}//function printTag($tagn){

//-------END FG TAG ----------------------------------------------
		
//-------START SUPPLIER TAG ----------------------------------------------
	
function printSPTag($tagn){
		  $sqltg="SELECT a.id_split,b.id_model,b.model_code,a.tag_no,d.shift,b.model_name,b.tag_model_no,
					 DATE_FORMAT(d.date_print, '%d-%b-%Y %H:%i') AS dateprint,	a.stag_qty,
					 CASE  a.stag_qty WHEN 1 THEN  a.sn_start ELSE  CONCAT(a.sn_start,'-',a.sn_end) END AS allserial,
					a.sn_start,a.sn_end,d.line_id,d.fg_tag_barcode,
					b.customer_part_no,b.customer_part_name,b.model_picture,d.status_print,b.status_tag_printing,c.line_name,b.std_qty
					FROM ".DB_DATABASE1.".fgt_supplier_tag_split a
					LEFT JOIN  ".DB_DATABASE1.".fgt_srv_tag  d ON a.tag_no=d.tag_no
					LEFT JOIN ".DB_DATABASE1.".fgt_model b ON d.id_model=b.id_model
					LEFT JOIN ".DB_DATABASE1.".view_line c ON d.line_id=c.line_id
					WHERE a.id_print_tag = '$tagn' ";
							$qrtg=mysql_query($sqltg);
							if(mysql_num_rows($qrtg)<>0){
								$fp = fopen(DIR_UPLOAD.DIR_BTW."fgtag.txt", "w");
									$i=1;
							fwrite($fp,"Model,Tag No,Shift,Model Name,Model No,Produce Date ,Qty ,Serial No 1-n,Printed by,Printed date,Serial 1,Serial 2,Serial 3,Serial 4,Serial 5,Serial 6,Serial 7,Serial 8,Serial 9,Serial 10,Serial 11,Serial 12,Serial 13,Serial 14,Serial 15,Serial 16,FgTag,Part No,Part Name,image,status print,fg print,stdQty,datereprint\r\n");
								while($rstg = mysql_fetch_array($qrtg)){
								mysql_query("UPDATE ".DB_DATABASE1.".fgt_srv_tag SET status_fg_reprint=1, 
											date_fg_reprint='".date('Y-m-d H:i:s')."' 
											WHERE tag_no='".$rstg['tag_no']."'");
								$serialtxt=whileprt($rstg['sn_start'],$rstg['sn_end'],$rstg['stag_qty']);
								fwrite($fp,$rstg['model_code'].",".$rstg['tag_no'].",".$rstg['shift'].",".$rstg['model_name'].",".$rstg['tag_model_no'].",".$rstg['dateprint'].",".$rstg['stag_qty'].",".$rstg['allserial'].",".$rstg['line_name'].",".$rstg['dateprint'].$serialtxt.$rstg['fg_tag_barcode'].",".$rstg['customer_part_no'].",".$rstg['customer_part_name'].",".$rstg['model_picture'].",".$rstg['status_print'].",-,".$rstg['std_qty'].",\r\n");
								}
							fclose($fp);
										$flgCopy1 = copy("uploads/btw/cmd.txt", "uploads/btw/cmds/cmd.txt");
									
						//eprintTag	
									
						
								}//if(mysql_num_rows($qrtg)<>0){
	}//function printTag($tagn){

?>

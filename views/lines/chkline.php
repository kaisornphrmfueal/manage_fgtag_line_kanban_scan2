<?php
include('../../includes/configure.php');
	  if(!empty($_GET["gline"]) && $_GET["gline"]<>"undefined"){

		    $sqlg="	SELECT a.line_id,a.line_name
					FROM ".DB_DATABASE1.".view_line a
					WHERE active = '0'
					AND a.line_name ='".$_GET['gline']."'
					 "; 
			// AND line_name NOT LIKE 'BSI%'   NOW BSI CAN LOGIN		
		  // OLD COMAND CODE FOR LOCAL NO HAVE THIS LINE, only BSI can login on server and only Final line can login Localhost. 
		  $resultg = mysqli_query($con, $sqlg);
		  
if(mysqli_num_rows($resultg)<>0){
$rowg = mysqli_fetch_array($resultg);
	  echo $rowg['line_id'];
}else{
	  echo "No";
	}
 }//  if(!empty($_GET["code"]) && $_GET["code"]<>"undefined"){


?>
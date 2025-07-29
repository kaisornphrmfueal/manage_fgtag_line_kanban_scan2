<?php
include('../../includes/configure.php');
	  if(!empty($_GET["qcmodel"]) && $_GET["qcmodel"]<>"undefined"){

 
		  $pmodel=substr($_GET['qcmodel'],0, 15);
		    $sqlg="	 SELECT b.ticket_ref,b.ticket_qty ,a.std_qty,b.status_write,b.model_no
						FROM  ".DB_DATABASE1.".fgt_model a
                        LEFT JOIN ".DB_DATABASE2.".rf_kanban_ticket b ON a.tag_model_no = b.model_no
																			AND  a.std_qty = b.ticket_qty 
						WHERE b.model_no ='".$pmodel."'
						AND b.status_write in ('0','10')
						ORDER BY b.ticket_ref  ASC LIMIT 1";
		  
		  /*  $sqlg="	SELECT id_model
					FROM ".DB_DATABASE1.".fgt_model 
					WHERE tag_model_no = '".$pmodel."'";*/
		 
		  $resultg = mysqli_query($con, $sqlg);
		  
if(mysqli_num_rows($resultg)<>0){
$rowg = mysqli_fetch_array($resultg);
	  echo $rowg['id_model'];
}else{
	  echo "No";
	}
 }//  if(!empty($_GET["code"]) && $_GET["code"]<>"undefined"){


?>
<?php
include('../../includes/configure.php');
	  if(!empty($_GET["qcmodel"]) && $_GET["qcmodel"]<>"undefined"){

 
		  $pticket=substr($_GET['qcmodel'],0, 9);
		    $sqlg="	SELECT b.ticket_ref,b.ticket_qty ,a.std_qty,b.status_write 
						FROM  ".DB_DATABASE1.".fgt_model a
                        LEFT JOIN ".DB_DATABASE2.".rf_kanban_ticket b ON a.tag_model_no = b.model_no
						WHERE b.ticket_ref = '".$pticket."' 
						AND b.status_write = '0'
						GROUP BY b.ticket_ref  ";
		  $resultg = mysqli_query($con, $sqlg);
		  
if(mysqli_num_rows($resultg)<>0){
$rowg = mysqli_fetch_array($resultg);
	  echo $rowg['ticket_ref'];
}else{
	  echo "No";
	}
 }//  if(!empty($_GET["code"]) && $_GET["code"]<>"undefined"){


?>
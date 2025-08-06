<?php

function checkshift() {
    if( date("H:i:s") >= "07:50:00" AND date("H:i:s") <= "20:19:59"){
		$chshift="Day";
		$datework=date('Y-m-d');
	}else{
		$chshift="Night";
			if( date("H:i:s") > "20:19:59" AND date("H:i:s") <= "23:59:59"){
				$datework=date('Y-m-d');
			}else{
				$datework=date( "Y-m-d",  strtotime("-1 day") );
			}
	}
    return ['shift' => $chshift, 'datework' => $datework];
}

// ฟังก์ชันสำหรับตัด "0E" ออกจาก Model No.
function clean_model_no($model_no) {
    // ตัด "0E" ออกจาก Model No.
    return str_replace('0E', '', $model_no);
}

function check_transferslip($transferslip) {
    global $con;
    $transferslip = trim($transferslip);
    $query = "SELECT b.ticket_ref,b.ticket_qty ,b.status_write ,b.model_no
              FROM   ".DB_DATABASE2.".rf_kanban_ticket  b
              WHERE b.ticket_ref = '$transferslip' 
              AND b.status_write IN ('0','5')
              AND b.ticket_special_status ='1'
              GROUP BY b.ticket_ref";
    $result = $con->query($query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return [
            'ticket_ref' => $row['ticket_ref'],
            'ticket_qty' => $row['ticket_qty'],
            'status_write' => $row['status_write'],
            'model_no' => $row['model_no'],
            'model_no_clean' => clean_model_no($row['model_no']) // เพิ่ม model_no ที่ตัด 0E แล้ว
        ];
    }else {
        return false;
    }
}


function get_ticket_info($transferslip) {
    global $con;
    $transferslip = trim($transferslip);
    $query = "SELECT b.ticket_ref, b.ticket_qty, b.status_write, b.model_no, c.model_name
              FROM ".DB_DATABASE2.".rf_kanban_ticket b
              LEFT JOIN ".DB_DATABASE2.".rf_model_name c on b.model_no = c.model_no
              WHERE b.ticket_ref = '$transferslip'";
    $result = $con->query($query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $row['model_no_clean'] = clean_model_no($row['model_no']); // เพิ่ม model_no ที่ตัด 0E แล้ว
        return $row;
    } else {
        return false;
    }
}

function get_fgtag_info($ticket_no) {
    global $con;
    $ticket_no = trim($ticket_no);
    $query = "SELECT fst.tag_no , fst.matching_ticket_no , fst.ticket_qty , fm.tag_model_no , fm.model_name , fss.serial_scan_label, fst.fg_tag_barcode 
              FROM ".DB_DATABASE1.".fgt_srv_tag fst 
              LEFT JOIN ".DB_DATABASE1.".fgt_model fm ON fst.model_kanban = fm.tag_model_no 
              LEFT JOIN ".DB_DATABASE1.".fgt_srv_serial fss ON fst.tag_no = fss.tag_no
              WHERE fst.matching_ticket_no = '$ticket_no'
              ORDER BY fss.date_scan DESC
              LIMIT 1";
    $result = $con->query($query);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

function get_serial_info($fgtag_no) {
    global $con;
    $fgtag_no = trim($fgtag_no);
    $query = "SELECT model_scan_label, serial_scan_label, date_scan 
              FROM ".DB_DATABASE1.".fgt_srv_serial 
              WHERE tag_no = '$fgtag_no' ORDER BY date_scan DESC";
    $result = $con->query($query);
    if ($result && $result->num_rows > 0) {
        return $result;
    } else {
        return false;
    }
}

function check_fgtag_no($fgtag_no, $transferslip) {
    global $con;
    $fgtag_no = trim($fgtag_no);

    $query = "
        SELECT 
            a.id_pc_request,  
            b.model_kanban,
            a.request_status,
            c.fg_tag_barcode,
            b.tag_qty
        FROM " . DB_DATABASE1 . ".fgt_split_pc_request a
        LEFT JOIN " . DB_DATABASE1 . ".fgt_split_pc_request_detail c ON a.id_pc_request = c.id_pc_request
        LEFT JOIN " . DB_DATABASE1 . ".fgt_srv_tag b ON c.tag_no = b.tag_no 
        WHERE c.fg_tag_barcode = ?
        GROUP BY a.id_pc_request
    ";

    $stmt = $con->prepare($query);
    $stmt->bind_param('s', $fgtag_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $result_fg = $result->fetch_assoc();

        switch ($result_fg['request_status']) {
            case '0':
                return "FGTAG No. อยู่ในสถานะรอการยืนยันจากผู้ใช้งาน";
                break;
            case '1':
                return "FGTAG No. อยู่ในสถานะรอการอนุมัติจากผู้อนุมัติ";
                break;
            case '2':
                $check_transferslip = check_transferslip($transferslip);
                if ($check_transferslip && $check_transferslip['ticket_qty'] == $result_fg['tag_qty']) {
                    if($result_fg['model_kanban'] === $check_transferslip['model_no']) {
                        return true;
                    }else{
                        return "Model No. ของ FGTAG ไม่ตรงกับ Model No. ใน Transfer Slip";
                    }
                } else {
                    return "จำนวน FGTAG ที่สแกนไม่ตรงกับจำนวนที่ระบุใน Transfer Slip หรือ Transfer Slip ถูกใช้ไปแล้ว";
                }
                break;
            case '3':
                return "FGTAG No. นี้ถูกยกเลิกไปแล้ว";
                break;
            default:
                return "สถานะ FGTAG No. ไม่ถูกต้อง";
                break;
        }    
    } else {
        return "ไม่พบ FGTAG No. นี้ในระบบ หรือ FGTAG นี้ถูกยกเลิกไปแล้ว";
    }
}

function check_fgtag_no_special($fgtag_no, $transferslip) {
    global $con;
    $fgtag_no = trim($fgtag_no);

    $query = "
        SELECT 
            a.id_pc_request,  
            b.model_kanban,
            a.request_status,
            c.fg_tag_barcode,
            b.tag_qty
        FROM " . DB_DATABASE1 . ".fgt_split_pc_request a
        LEFT JOIN " . DB_DATABASE1 . ".fgt_split_pc_request_detail c ON a.id_pc_request = c.id_pc_request
        LEFT JOIN " . DB_DATABASE1 . ".fgt_srv_tag b ON c.tag_no = b.tag_no 
        WHERE c.fg_tag_barcode = ?
        GROUP BY a.id_pc_request
    ";

    $stmt = $con->prepare($query);
    $stmt->bind_param('s', $fgtag_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $result_fg = $result->fetch_assoc();

        switch ($result_fg['request_status']) {
            case '0':
                return "FGTAG No. อยู่ในสถานะรอการยืนยันจากผู้ใช้งาน";
                break;
            case '1':
                return "FGTAG No. อยู่ในสถานะรอการอนุมัติจากผู้อนุมัติ";
                break;
            case '2':
                $check_transferslip = check_transferslip($transferslip);
                if ($check_transferslip && $check_transferslip['ticket_qty'] == $result_fg['tag_qty']) {

                    $model_a_cut = substr_replace($result_fg['model_kanban'], "", 10, 2);
                    $model_b_cut = substr_replace($check_transferslip['model_no'], "", 10, 2);

                    if($model_a_cut === $model_b_cut) {
                        return true;
                    }else{
                        return "Model No. ของ FGTAG ไม่ตรงกับ Model No. ใน Transfer Slip";
                    }
                } else {
                    return "จำนวน FGTAG ที่สแกนไม่ตรงกับจำนวนที่ระบุใน Transfer Slip หรือ Transfer Slip ถูกใช้ไปแล้ว";
                }
                break;
            case '3':
                return "FGTAG No. นี้ถูกยกเลิกไปแล้ว";
                break;
            default:
                return "สถานะ FGTAG No. ไม่ถูกต้อง";
                break;
        }    
    } else {
        return "ไม่พบ FGTAG No. นี้ในระบบ หรือ FGTAG นี้ถูกยกเลิกไปแล้ว";
    }
}

function update_ticket_status($fgtag_no, $transferslip) {
    global $con;
    $transferslip = trim($transferslip);
    $fgtag_no = trim($fgtag_no);
    $check_transferslip = check_transferslip($transferslip);

    //  Get Max ID for conversion
    $query_max_id = "SELECT IFNULL(max(id_conversion),0)+1 AS mxsp 
                     FROM " . DB_DATABASE1 . ".fgt_split_line_conversion";
    $result_max_id = $con->query($query_max_id);
    if (!$result_max_id) {
        return "Error in query_max_id: " . $con->error;
    }
    $max_id = $result_max_id->fetch_assoc()['mxsp'];

    // Get tag original data
    $query_tag_original = "SELECT a.tag_no, a.id_model, a.sn_start, a.sn_end, a.tag_qty, a.fg_tag_barcode, a.matching_ticket_no, b.id_pc_request_detail
                           FROM " . DB_DATABASE1 . ".fgt_srv_tag a
                           LEFT JOIN " . DB_DATABASE1 . ".fgt_split_pc_request_detail b ON a.tag_no = b.tag_no
                           WHERE a.fg_tag_barcode = '$fgtag_no'";
    $result_tag_original = $con->query($query_tag_original);
    if (!$result_tag_original) {
        return "Error in query_tag_original: " . $con->error;
    }
    $tag_data = $result_tag_original->fetch_assoc();

    //Insert new tag
    $mxtag=selectMxTag($_SESSION['user_login']);
    $linesp=sprintf("%02d",$_SESSION['user_login']);
    $mxtagsp=sprintf("%07d",$mxtag);
    $tagnon=$linesp.$mxtagsp;
    $tagb=date("Ymd").$tagnon;
    $pmodel = $check_transferslip['model_no'];
    $check_shift = checkshift();
    $ticket_no = $transferslip;
    $tk_qty = $check_transferslip['ticket_qty'];
    $ptkstatus = 'A'; // Assuming 'A' is the status for active tickets

    $query_insert_new_tag = "INSERT INTO ".DB_DATABASE1.".fgt_srv_tag 
                                    SET line_id='" . $_SESSION['user_login'] . "', 
                                    tag_no= '$tagnon', 
                                    id_model=(SELECT id_model FROM ".DB_DATABASE1.".fgt_model  WHERE tag_model_no = '".$pmodel."'), 
                                    model_kanban='$pmodel',
                                    shift='".$check_shift['shift']."', 
                                    fg_tag_barcode='$tagb',
                                    status_print='Not yet' ,
                                    matching_ticket_no='$ticket_no', ticket_qty='$tk_qty',kanban_status='$ptkstatus',
                                    date_insert ='".date('Y-m-d H:i:s')."',
                                    date_work ='".$check_shift['datework']."',
                                    bsi_model='$pmodel',
                                    work_id = (SELECT work_id FROM ".DB_DATABASE1.".fgt_leader  
                                    WHERE line_id = '".$linesp."' ORDER BY work_id DESC  LIMIT 1)";
    $stmt_insert_new_tag = $con->prepare($query_insert_new_tag);
    if (!$stmt_insert_new_tag) {
        return "Error in query_insert_new_tag: " . $con->error;
    }
    if (!$stmt_insert_new_tag->execute()) {
        return "Error executing query_insert_new_tag: " . $stmt_insert_new_tag->error;
    }

    // Get the tag_no from the previous insert
    $query_get_tag_no = "SELECT tag_no FROM ".DB_DATABASE1.".fgt_srv_tag WHERE fg_tag_barcode = '$tagb' ORDER BY date_insert DESC LIMIT 1";
    $result_get_tag_no = $con->query($query_get_tag_no);
    if (!$result_get_tag_no) {
        return "Error in query_get_tag_no: " . $con->error;
    }
    $new_tag_row = $result_get_tag_no->fetch_assoc();
    $new_tag_no = $new_tag_row['tag_no'];

    $query_insert_conversion = "INSERT INTO " . DB_DATABASE1 . ".fgt_split_line_conversion 
                                (id_model,id_pc_request_detail,fg_tag_barcode_original,transfer_slip,
                                tag_no_original,tag_qty,process_status,tag_no_new,emp_id_insert,date_insert) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt_insert_conversion = $con->prepare($query_insert_conversion);
    if (!$stmt_insert_conversion) {
        return "Error in query_insert_conversion: " . $con->error;
    }
    $tag_no_original = substr($fgtag_no, 8);
    $process_status = '0';
    $stmt_insert_conversion->bind_param(
        'iisssisis',
        $tag_data['id_model'],
        $tag_data['id_pc_request_detail'],
        $fgtag_no,
        $transferslip,
        $tag_no_original,
        $check_transferslip['ticket_qty'],
        $process_status,
        $new_tag_no,
        $_SESSION['user_login']
    );
    if (!$stmt_insert_conversion->execute()) {
        return "Error executing query_insert_conversion: " . $stmt_insert_conversion->error;
    }

    // Update the original tag status to '9' (canceled)
    $fgtag_no_short = substr($fgtag_no, 8); // Extract the tag number without the prefix
    $query_update_tag_original = "UPDATE " . DB_DATABASE1 . ".fgt_srv_tag 
                        SET tag_location='9'
                        WHERE tag_no = '$fgtag_no_short'";
    $stmt_update_tag_original = $con->prepare($query_update_tag_original);
    if (!$stmt_update_tag_original) {
        return "Error in query_update_tag_original: " . $con->error;
    }
    if (!$stmt_update_tag_original->execute()) {
        return "Error executing query_update_tag_original: " . $stmt_update_tag_original->error;
    }

    // Update the ticket Reserve status to '5' (Reserved)
    $query_update_ticket = "UPDATE ".DB_DATABASE2.".rf_kanban_ticket 
                            SET status_write='5', last_status='Reserved'
                            WHERE ticket_ref = '$transferslip'";
    $stmt_update_ticket = $con->prepare($query_update_ticket);
    if (!$stmt_update_ticket) {
        return "Error in query_update_ticket: " . $con->error;
    }
    if (!$stmt_update_ticket->execute()) {
        return "Error executing query_update_ticket: " . $stmt_update_ticket->error;
    }

    return true;
}

function check_converstion_status($fgtag_no) {
    global $con;
    $fgtag_no = trim($fgtag_no);
    $query = "SELECT c.item_status,c.id_conversion,c.process_status
          FROM ".DB_DATABASE1.".fgt_split_line_conversion c 
          WHERE c.fg_tag_barcode_original ='$fgtag_no' 
		  AND c.item_status in (0,1) 
		  ORDER BY c.id_conversion DESC";
    $result = $con->query($query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['item_status'];
    }else{
        return false;
    }
}

//-------START FG TAG ----------------------------------------------
function printTagSpecial($tagn){
	global $con;
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
							$qrtg=mysqli_query($con, $sqltg);
							if(mysqli_num_rows($qrtg)<>0){
								$rstg = mysqli_fetch_array($qrtg);   
								$idline=$rstg['line_id'];
								$srv=SRV_SHARED;
								$srv_template = SRV_TEMPLATE;
								$serialtxt=whileprt($rstg['sn_start'],$rstg['sn_end'],$rstg['tag_qty']);
								$mt_ticket=$rstg['matching_ticket_no'];
								$mtkno9=sprintf("%09d",$mt_ticket);
								$tk_bcode=$mtkno9." pline";
								$fp = fopen("../../../views/".DIR_UPLOAD.DIR_PRINT.$idline.".txt", "w");
							
							$i=1;
							fwrite($fp,"Model,Tag No,Shift,Model Name,Model No,Produce Date ,Qty ,Serial No 1-n,Printed by,Printed date,Serial 1,Serial 2,Serial 3,Serial 4,Serial 5,Serial 6,Serial 7,Serial 8,Serial 9,Serial 10,Serial 11,Serial 12,Serial 13,Serial 14,Serial 15,Serial 16,FgTag,Part No,Part Name,image,status print,fg print,stdQty,datereprint,ticket_no,ticket_bcode,Leader,Floater,Printer\r\n");
							fwrite($fp,$rstg['model_code'].",".$rstg['tag_no'].",".$rstg['shift'].",".$rstg['model_name'].",".$rstg['tag_model_no'].",".$rstg['dateprint'].",".$rstg['tag_qty'].",".$rstg['allserial'].",".$rstg['line_name'].",".$rstg['dateprint'].$serialtxt.$rstg['fg_tag_barcode'].",".$rstg['customer_part_no'].",".$rstg['customer_part_name'].",".$rstg['model_picture'].",".$rstg['status_print'].",,".$rstg['std_qty'].",".$rstg['datereprint'].",".$mtkno9.",".$tk_bcode.",".$rstg['leadern'].",".$rstg['floatern'].",".$rstg['printern']."\r\n");
							fclose($fp);
										  				
							/* START BACKUP DATA TO FILE*/
							$strFileName = "../../../views/lines/tag_backup/".date('Ymd').".txt";
							$objFopen = fopen($strFileName, 'a');
							fwrite($objFopen,$rstg['model_code'].",".$rstg['tag_no'].",".$rstg['line_id'].",".$rstg['shift'].",".$rstg['model_name'].",".$rstg['tag_model_no'].",".$rstg['date_print'].",".$rstg['tag_qty'].",".$rstg['sn_start'].",".$rstg['sn_end'].",".$rstg['line_name'].",".$rstg['fg_tag_barcode'].",".$rstg['customer_part_no'].",".$rstg['customer_part_name'].",".$rstg['model_picture'].",".$rstg['status_print'].",,".$rstg['std_qty'].",".$rstg['date_reprint'].",".$mtkno9.",".$tk_bcode.",".$rstg['leadern'].",".$rstg['floatern'].",".$rstg['printern']."\r\n");
							fclose($objFopen);
							/*END BACKUP DATA TO FILE */						
															
								if($rstg['status_tag_printing']==0){		//0=both,1=only fg Tag
										$flgCopyDT = copy("../../../views/".DIR_UPLOAD.DIR_PRINT.$idline.".txt", "\\\\$srv\\$srv_template\\print\\$idline\\fgtag.txt");
									
										$flgCopy1 = copy("\\\\$srv\\$srv_template\\print\\$idline\\cmd.txt", "\\\\$srv\\$srv_template\\print\\$idline\\cmdb\\cmd.txt");
										
									}else{
										$flgCopyDT = copy("../../../views/".DIR_UPLOAD.DIR_PRINT.$idline.".txt", "\\\\$srv\\$srv_template\\print\\$idline\\fgtag.txt");
										$flgCopy1 = copy("\\\\$srv\\$srv_template\\print\\$idline\\cmd.txt", "\\\\$srv\\$srv_template\\print\\$idline\\cmdo\\cmd.txt");
										
										}
								}
	}

//-------END FG TAG ----------------------------------------------

function check_serial_scan($label_scan, $kanban_model, $fgtag_no, $ticket_no, $fgtag_barcode, $last_serial) {
    global $con;
    $pscanmodel = trim($label_scan);
    $pkbmodel = trim($kanban_model);
    $ptagno = trim($fgtag_no);
    $phtkno = trim($ticket_no);
    $phtagbc = trim($fgtag_barcode);
    $poserial = trim($last_serial);
    $user_login = $_SESSION['user_login'];

    // Check if the label scan matches the expected pattern
    $sqlct="SELECT a.tag_no,c.tag_model_no,c.label_model_no,a.line_id,a.fg_tag_barcode ,
				b.serial_scan_label, COUNT(b.tag_no)+1 AS sr_count,a.ticket_qty AS std_qty,c.model_destination, d.id_conversion, e.id_pc_request_detail 
						FROM ".DB_DATABASE1.".fgt_srv_tag a
						LEFT JOIN ".DB_DATABASE1.".fgt_srv_serial b ON a.tag_no=b.tag_no
						LEFT JOIN ".DB_DATABASE1.".fgt_model c ON a.id_model=c.id_model
						LEFT JOIN ".DB_DATABASE1.".fgt_split_line_conversion d ON a.tag_no = d.tag_no_new
						LEFT JOIN ".DB_DATABASE1.".fgt_split_pc_request_detail e ON d.tag_no_original = e.tag_no
						WHERE a.status_print = 'Not yet'
						AND b.tag_no='$ptagno'
						GROUP BY a.tag_no";
	$qrct=mysqli_query($con, $sqlct);
	$numct=mysqli_num_rows($qrct);
    $chkdata=substr($pscanmodel,0, 2);

    if($chkdata  == "EW" || $chkdata  == "GB" || $chkdata   == "GC"  || $chkdata   == "GD" ){
		$psserial=substr($pscanmodel,3, 8);
		$modelscan=$chkdata;
	}else{
		$exp= explode(' ' , $pscanmodel);
			foreach( $exp as $expps ):
				if(substr($expps,0, 1)=="B"){
					$frdata =  $expps;
				}
			endforeach ;
		$psserial=substr($frdata,0,8);
		$modelscan=substr($pscanmodel,0, 15);			
	}
    $txtserial=substr($psserial,0,3);

    // Check Pattern Serial
    $sql_pat="SELECT pattern_serial FROM ".DB_DATABASE1.".fgt_pattern_serial WHERE pattern_serial = '$txtserial' ";
	$qr_pat=mysqli_query($con, $sql_pat);
	$num_pat=mysqli_num_rows($qr_pat);

    //Check Pattern Serial exe BPB,BPC
    if($num_pat <> 0 AND $num_pat <> "") {
		$str_hdnlast_serial=substr($poserial, -5);
		$str_postnew_serial=substr($psserial, -5);

        if($str_postnew_serial-$str_hdnlast_serial == 1) {
            if($numct == 0) {
                $sqlckn="SELECT a.ticket_qty  AS std_qty,c.model_destination, d.id_conversion, e.id_pc_request_detail 
								FROM ".DB_DATABASE1.".fgt_srv_tag a
								LEFT JOIN ".DB_DATABASE1.".fgt_model c ON a.id_model=c.id_model
                                LEFT JOIN ".DB_DATABASE1.".fgt_split_line_conversion d on a.tag_no = d.tag_no_new
								LEFT JOIN ".DB_DATABASE1.".fgt_split_pc_request_detail e on d.tag_no_original = e.tag_no 
								WHERE a.status_print = 'Not yet'
								AND a.tag_no='$ptagno' 
								GROUP BY a.tag_no";
				$qrckn=mysqli_query($con, $sqlckn);
				$rsckn=mysqli_fetch_array($qrckn);
				$model_dest1 = $rsckn['model_destination'];
                $id_conversion = $rsckn['id_conversion'];
                $id_pc_request_detail = $rsckn['id_pc_request_detail'];

                if($rsckn['std_qty'] == 1) {
                    $sqlup_tag="UPDATE ".DB_DATABASE1.".fgt_srv_tag SET  sn_start='$psserial' , sn_end='$psserial' ,
										status_print='Printed', tag_qty='1', date_print='".date('Y-m-d H:i:s')."',
										tag_location='1'
										WHERE tag_no='$ptagno' " ;
					$qrup_tag=mysqli_query($con, $sqlup_tag);
							
					$sqlsr="INSERT INTO ".DB_DATABASE1.".fgt_srv_serial SET  tag_no='$ptagno', 
									model_scan_label='$modelscan', serial_scan_label='$psserial', 
									date_scan='".date('Y-m-d H:i:s')."'";
					$qrsr=mysqli_query($con, $sqlsr);
					log_hist($user_login,"Insert",$psserial,"fgt_srv_serial",$sqlsr);
					$sqlutk="UPDATE  ".DB_DATABASE2.".rf_kanban_ticket SET
												 status_write=6, last_status='Print Tag'
												 WHERE ticket_ref='$phtkno'";
                        $qrtk=mysqli_query($con, $sqlutk);

                        $update_conversion = "UPDATE ".DB_DATABASE1.".fgt_split_line_conversion SET
												item_status = '2'
												WHERE id_conversion='$id_conversion'";
                        $qruptk = mysqli_query($con, $update_conversion);

                        $update_pc_request = "UPDATE ".DB_DATABASE1.".fgt_split_pc_request_detail SET
                                                item_status = '2'
                                                WHERE id_pc_request_detail='$id_pc_request_detail'";
                        $qrpc = mysqli_query($con, $update_pc_request);

                        $update_tag_original = "UPDATE ".DB_DATABASE1.".fgt_split_pc_request_detail SET
                                                item_status = '3', date_print = '".date('Y-m-d H:i:s')."',
                                                line_id = '".$_SESSION['user_login']."'
                                                WHERE id_pc_request_detail='$id_pc_request_detail'";
                        $qrtag = mysqli_query($con, $update_tag_original);


                    // Check if model destination is 'E' for special processing
                    if($model_dest1 == 'E') { 
                        $ip = $_SERVER['REMOTE_ADDR']; 
						$tkno9=sprintf("%09d",$phtkno);
						    $sqlmc = "INSERT INTO ".DB_DATABASE4.".record_mathing_kanban SET 
							record_code='".date("YmdHis")."', ticket_no='$tkno9',	model_no_scan='$modelscan', 
							fg_tag_no='$phtagbc', model_no_auto='$modelscan', judge='OK', 
							operator_id='$top_name', record_time='".date('Y-m-d H:i:s')."', 
							error_code='-', line_leader_id='-',ip='$ip',kanban_group='".date("ymdH")."' ,working_location='1',status_scan = '7'"; 
							mysqli_query($con, $sqlmc); 
							
							
							$sqlmc = "INSERT INTO ".DB_DATABASE4.".record_mathing_kanban_log SET 
							record_code='".date("YmdHis")."', ticket_no='$tkno9', model_no_scan='$modelscan', fg_tag_no='$phtagbc', 
							model_no_auto='$modelscan', judge='ok', operator_id='$top_name', record_time='$today',
							error_code='-', line_leader_id='-',ip='$ip'";
							mysqli_query($con, $sqlmc);
							
							log_record_packing($top_name,'Line insert to record_mathing_kanban',$sqlmc);
                    }

                    // Print Tag
                    printTagSpecial($ptagno);
					log_hist($user_login,"Printed Tag",$ptagno,"fgt_srv_tag","");
                    sleep(3);
                    return "success";

                }else{ // If more than one serial
                    $sqlup_tag="UPDATE ".DB_DATABASE1.".fgt_srv_tag SET sn_start='$psserial' 
								WHERE tag_no='$ptagno' " ;
					$qrup_tag=mysqli_query($con, $sqlup_tag);
						
					$sqlsr="INSERT INTO ".DB_DATABASE1.".fgt_srv_serial SET  tag_no='$ptagno', 
								model_scan_label='$modelscan', serial_scan_label='$psserial', 
								date_scan='".date('Y-m-d H:i:s')."'";
					$qrsr=mysqli_query($con, $sqlsr);
					log_hist($user_login,"Insert",$psserial,"fgt_srv_serial",$sqlsr);	

                    //For split line conversion
                    $update_conversion = "UPDATE ".DB_DATABASE1.".fgt_split_line_conversion SET
												item_status = '1'
												WHERE id_conversion='$id_conversion'";
                    $qruptk = mysqli_query($con, $update_conversion);

                    $update_pc_request = "UPDATE ".DB_DATABASE1.".fgt_split_pc_request_detail SET
                                                item_status = '2'
                                                WHERE id_pc_request_detail='$id_pc_request_detail'";
                    $qrpc = mysqli_query($con, $update_pc_request);

					return "continue";
                }
            }else{ // If there are existing serials
                $rsct = mysqli_fetch_array($qrct);
                $rsstqty = $rsct['std_qty'];
				$rsscqty = $rsct['sr_count'];
				$model_dest = $rsct['model_destination'];
                $id_conversion = $rsct['id_conversion'];
                $id_pc_request_detail = $rsct['id_pc_request_detail'];


                if($rsstqty == $rsscqty) {
                    $sqlup_tag="UPDATE ".DB_DATABASE1.".fgt_srv_tag SET sn_end='$psserial' ,
										status_print='Printed', tag_qty='$rsscqty', date_print='".date('Y-m-d H:i:s')."',
										tag_location='1'
										WHERE tag_no='$ptagno' " ;
					$qrup_tag=mysqli_query($con, $sqlup_tag);
							
					$sqlsr="INSERT INTO ".DB_DATABASE1.".fgt_srv_serial SET  tag_no='$ptagno', 
									model_scan_label='$modelscan', serial_scan_label='$psserial', 
									date_scan='".date('Y-m-d H:i:s')."'";
					$qrsr=mysqli_query($con, $sqlsr);
					log_hist($user_login,"Insert",$psserial,"fgt_srv_serial",$sqlsr);
					$sqlutk="UPDATE  rfid_project.rf_kanban_ticket SET
												 status_write=6, last_status='Print Tag'
												 WHERE ticket_ref='$phtkno'";
					$qrtk=mysqli_query($con, $sqlutk); //Print Tag

                    //For split line conversion
                    $update_conversion = "UPDATE ".DB_DATABASE1.".fgt_split_line_conversion SET
												item_status = '2'
												WHERE id_conversion='$id_conversion'";
                    $qruptk = mysqli_query($con, $update_conversion);

                    $update_pc_request = "UPDATE ".DB_DATABASE1.".fgt_split_pc_request_detail SET
                                                item_status = '2'
                                                WHERE id_pc_request_detail='$id_pc_request_detail'";
                    $qrpc = mysqli_query($con, $update_pc_request);

                    $update_tag_original = "UPDATE ".DB_DATABASE1.".fgt_split_pc_request_detail SET
                                                item_status = '3', date_print = '".date('Y-m-d H:i:s')."',
                                                line_id = '".$_SESSION['user_login']."'
                                                WHERE id_pc_request_detail='$id_pc_request_detail'";
                    $qrtag = mysqli_query($con, $update_tag_original);

                    if($model_dest == 'E') {
                        $ip = $_SERVER['REMOTE_ADDR'];
						$tkno9=sprintf("%09d",$phtkno);
						    $sqlmc = "INSERT INTO ".DB_DATABASE4.".record_mathing_kanban SET 
							record_code='".date("YmdHis")."', ticket_no='$tkno9',	model_no_scan='$modelscan', 
							fg_tag_no='$phtagbc', model_no_auto='$modelscan', judge='OK', 
							operator_id='$top_name', record_time='".date('Y-m-d H:i:s')."', 
							error_code='-', line_leader_id='-',ip='$ip',kanban_group='".date("ymdH")."' ,working_location='1',status_scan = '7'"; 
							mysqli_query($con, $sqlmc); 
							
							
							$sqlmc = "INSERT INTO ".DB_DATABASE4.".record_mathing_kanban_log SET 
							record_code='".date("YmdHis")."', ticket_no='$tkno9', model_no_scan='$modelscan', fg_tag_no='$phtagbc', 
							model_no_auto='$modelscan', judge='ok', operator_id='$top_name', record_time='$today',
							error_code='-', line_leader_id='-',ip='$ip'";
							mysqli_query($con, $sqlmc);
							
							log_record_packing($top_name,'Line insert to record_mathing_kanban',$sqlmc);
                    }

                    // Print Tag
                    printTagSpecial($ptagno);
                    log_hist($user_login,"Printed Tag",$ptagno,"fgt_srv_tag","");
                    sleep(3);
                    return "success";

                }else{// Scan again
                    $sqlsr="INSERT INTO ".DB_DATABASE1.".fgt_srv_serial SET  tag_no='$ptagno', 
								model_scan_label='$modelscan', serial_scan_label='$psserial', 
								date_scan='".date('Y-m-d H:i:s')."'";
					$qrsr=mysqli_query($con, $sqlsr);
					log_hist($user_login,"Insert",$psserial,"fgt_srv_serial",$sqlsr);

                    //For split line conversion
                    $update_conversion = "UPDATE ".DB_DATABASE1.".fgt_split_line_conversion SET
												item_status = '1'
												WHERE id_conversion='$id_conversion'";
                    $qruptk = mysqli_query($con, $update_conversion);

                    $update_pc_request = "UPDATE ".DB_DATABASE1.".fgt_split_pc_request_detail SET
                                                item_status = '2'
                                                WHERE id_pc_request_detail='$id_pc_request_detail'";
                    $qrpc = mysqli_query($con, $update_pc_request);
                    
                    return "continue";
                }
            }
        }else{
            return "Serial Scan ไม่ถูกต้อง กรุณาตรวจสอบ Serial Scan ใหม่อีกครั้ง";
        }
	}else{
        return "รูปแบบ Pattern Serial No. ไม่ถูกต้อง กรุณาตรวจสอบ Serial Scan ใหม่อีกครั้ง";
    }

}




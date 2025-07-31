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

function check_transferslip($transferslip) {
    global $con;
    $transferslip = trim($transferslip);
    $query = "SELECT b.ticket_ref,b.ticket_qty ,b.status_write ,b.model_no
              FROM   ".DB_DATABASE2.".rf_kanban_ticket  b
              WHERE b.ticket_ref = '$transferslip' 
              AND b.status_write = '0'
              AND b.ticket_special_status ='1'
              GROUP BY b.ticket_ref";
    $result = $con->query($query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return [
            'ticket_ref' => $row['ticket_ref'],
            'ticket_qty' => $row['ticket_qty'],
            'status_write' => $row['status_write'],
            'model_no' => $row['model_no']
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
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

function get_fgtag_info($ticket_no) {
    global $con;
    $ticket_no = trim($ticket_no);
    $query = "SELECT fst.tag_no , fst.matching_ticket_no , fst.ticket_qty , fm.tag_model_no , fm.model_name , fss.serial_scan_label 
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
        return $result->fetch_assoc();
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
                    if($result_fg['model_kanban'] == $check_transferslip['model_no']) {
                        return true;
                    }else{
                        return "Model No. ของ FGTAG ไม่ตรงกับ Model No. ใน Transfer Slip";
                    }
                } else {
                    return "จำนวน FGTAG ที่สแกนไม่ตรงกับจำนวนที่ระบุใน Transfer Slip";
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
?>
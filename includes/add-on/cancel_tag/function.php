<?php

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
          AND a.request_status = 2
        GROUP BY a.id_pc_request
    ";

    $stmt = $con->prepare($query);
    $stmt->bind_param('s', $fgtag_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $result_fg = $result->fetch_assoc();
        $check_transferslip = check_transferslip($transferslip);

        if ($check_transferslip && $check_transferslip['ticket_qty'] == $result_fg['tag_qty']) {
            return true;
        } else {
            return "จำนวน FGTAG ที่สแกนไม่ตรงกับจำนวนที่ระบุใน Transfer Slip";
        }
    } else {
        return "ไม่พบ FGTAG No. นี้ในระบบ หรือ FGTAG นี้ถูกยกเลิกไปแล้ว";
    }
}
?>
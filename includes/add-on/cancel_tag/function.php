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

?>
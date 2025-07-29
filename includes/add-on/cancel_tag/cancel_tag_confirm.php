<?php
require_once 'function.php';


  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transferslip = trim($_POST['transferslip'] ?? '');

    if ($transferslip === '') {
      $message = 'กรุณากรอก Transfer Slip';
    } else {
      $chck_transferslip = check_transferslip($transferslip);
      if ($chck_transferslip === false) {
        $message = 'ไม่พบข้อมูล Trasfer Slip นี้';
      } else {
        $message = 'ยกเลิกใบโอนสินค้าเรียบร้อยแล้ว: ' . htmlspecialchars($chck_transferslip['ticket_ref']);
      }
    }

    echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
      var resultDiv = document.getElementById('cancelTagResult');
      resultDiv.innerHTML = '<div class=\"alert alert-warning\" role=\"alert\">' + " . json_encode($message) . " + '</div>';
      });
    </script>";
  }
?>
  
  
  <link href="../../includes/add-on/cancel_tag/bootstrap-5.3.3/css/bootstrap.min.css" rel="stylesheet">
  <script src="../../includes/add-on/cancel_tag/bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>

  <!-- Main Content: FGTAG Cancel Form -->
  <div class="container mt-5" id="cancelTagSection">
    <div class="card">
      <div class="card-header">
        <h4>Cancel FGTAG</h4>
      </div>
      <div class="card-body">
        <form id="cancelTagForm" method="POST" action="">
          <div class="mb-3">
            <label for="transferslip" class="form-label"><b>Scan transfer slip no.</b></label>
            <input type="text" class="form-control" id="transferslip" name="transferslip" required autofocus>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-success">Submit</button>
          </div>
        </form>
        <div id="cancelTagResult" class="mt-3"></div>
      </div>
    </div>
  </div>


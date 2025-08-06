<?php
require_once 'head.php';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transferslip = trim($_POST['transferslip'] ?? '');

    if ($transferslip === '') {
      $message = 'กรุณากรอก Transfer Slip';
    } else {
      $chck_transferslip = check_transferslip($transferslip);
      if ($chck_transferslip === false) {
        $message = 'ไม่พบข้อมูล Trasfer Slip นี้';
      } else {
        gotopage('cancel_tag_confirm_special.php?transferslip=' . base64_encode($transferslip));
        exit;
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

  <!-- Main Content: FGTAG Cancel Form -->
  <div class="container mt-5" id="cancelTagSection">
    <div class="card">
      <div class="card-header">
        <h4 class="text-danger">Cancel FGTAG Special</h4>
      </div>
      <div class="card-body">
        <form id="cancelTagForm" method="POST" action="">
          <div class="mb-3">
            <label for="transferslip" class="form-label"><b>Scan transfer slip no.</b></label>
            <input type="text" class="form-control" id="transferslip" name="transferslip" autocomplete="off" required autofocus>
          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-success">Submit</button>
          </div>
        </form>
        <div id="cancelTagResult" class="mt-3"></div>
      </div>
    </div>
  </div>

  <?php require_once 'footer.php'; ?>


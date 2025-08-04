<?php
require_once 'head.php';

if (!empty($_POST['fgtagno'])) {
  $get_fgtag = check_fgtag_no($_POST['fgtagno'], $_POST['transferslip']);

  if ($get_fgtag === true) {
    $check_fgtag_conversion = check_converstion_status($_POST['fgtagno']);

    if ($check_fgtag_conversion !== false) {
      gotopage('cancel_tag_confirm_serial.php?transferslip=' . base64_encode($_POST['transferslip']) . '&fgtagno=' . base64_encode($_POST['fgtagno']));
    } else {
      $update_result = update_ticket_status($_POST['fgtagno'], $_POST['transferslip']);

      if ($update_result === true) {
        gotopage('cancel_tag_confirm_serial.php?transferslip=' . base64_encode($_POST['transferslip']) . '&fgtagno=' . base64_encode($_POST['fgtagno']));
      } else {
        $message = $update_result;
        echo $message;
      }
    }
  } else {
    $message = $get_fgtag;
  }

  $_GET['transferslip'] = base64_encode($_POST['transferslip'] ?? '');

  echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
      var resultDiv = document.getElementById('checktagresult');
      resultDiv.innerHTML = '<div class=\"alert alert-warning\" role=\"alert\">".$message."</div>';
    });
  </script>";
}

    

$transfer = base64_decode($_GET['transferslip'] ?? '');
$get_ticket_info = get_ticket_info($transfer);

?>
  <!-- Main Content: FGTAG Cancel Form -->
  <div class="container mt-5" id="cancelTagSection">
    <div class="row justify-content-center">
      <div class="col-lg-7 col-md-9">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-receipt-cutoff me-2"></i>ยืนยันการยกเลิก Transfer Slip</h5>
          </div>
          <div class="card-body">
              <div class="mb-3">
                <label for="transferslip" class="form-label fw-semibold">Transfer Slip no.</label>
                <input type="text" class="form-control bg-light" id="transferslip" name="transferslip" value="<?= htmlspecialchars($transfer); ?>" readonly>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="ticket_qty" class="form-label fw-semibold">Model No.</label>
                  <input type="text" class="form-control bg-light" id="ticket_qty" name="ticket_qty" value="<?= htmlspecialchars($get_ticket_info['model_no'] ?? '') ?>" readonly>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="model_no" class="form-label fw-semibold">Model Name</label>
                  <input type="text" class="form-control bg-light" id="model_no" name="model_no" value="<?= htmlspecialchars($get_ticket_info['model_name'] ?? '') ?>" readonly>
                </div>
              </div>
              <hr>
              <form method="post" action="">
              <div class="row mb-3">
                <div class="col-8">
                  <label for="fgtagno" class="form-label fw-semibold text-center">สแกน FGTAG Original no.</label>
                    <input type="text" class="form-control bg-light" id="fgtagno" name="fgtagno" autocomplete="off" required autofocus>
                    <input type="hidden" name="transferslip" value="<?= htmlspecialchars($transfer); ?>">
                </div>
                <div class="col-4 d-flex align-items-end">
                  <button type="submit" class="btn btn-success w-100">ยืนยัน</button>
                </div>
              </div>
              </form>
              <div id="checktagresult" class="mt-3"></div>
              <br>
              <hr>
              <div class="alert alert-info mt-3" role="alert">
                <strong>หมายเหตุ:</strong> กรุณาสแกน FGTAG Original no. ที่ต้องการยกเลิก Transfer Slip นี้ หากไม่พบ FGTAG หรือ FGTAG นี้ถูกยกเลิกไปแล้ว ระบบจะแจ้งเตือนให้ทราบ
          </div>
        </div>
      </div>
    </div>
  </div>

    <?php require_once 'footer.php'; ?>


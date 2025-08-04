<?php
require_once 'head.php';

$transfer = base64_decode($_GET['transferslip'] ?? '');
$fgtag_no = base64_decode($_GET['fgtagno'] ?? '');
$get_fgtag_info = get_fgtag_info($transfer);
$fgtag = substr($fgtag_no, 0, 8);

if($get_fgtag_info['serial_scan_label'] == null || $get_fgtag_info['serial_scan_label'] == ''){
    $last_serial = "00000000";
}else{
    $last_serial = $get_fgtag_info['serial_scan_label'];
}

$get_serial_info = get_serial_info($fgtag);
 
?>
  <!-- Main Content: FGTAG Cancel Form -->
  <div class="container mt-3" id="cancelTagSection">
    <div class="row justify-content-center">
      <div class="col-12 d-flex justify-content-center">
        <div class="card shadow-sm border-0" style="width: 80%;">
          <div class="card-header bg-danger text-white text-center">
            <h5 class="mb-0"><i class="bi bi-receipt-cutoff me-2"></i>FG Transfer TAG Special Printing</h5>
          </div>
          <div class="card-body">
              <div class="row mb-3">
                <div class="col-md-5 mb-3 text-end">
                    <label for="model" class="form-label fw-semibold" style="font-size: 1.5rem;">Model : </label><br>
                    <label for="fgtag" class="form-label fw-semibold" style="font-size: 1.5rem;">FGTAG No. : </label><br>
                    <label for="ticket" class="form-label fw-semibold" style="font-size: 1.5rem;">Ticket No. : </label><br>
                    <label for="ticket" class="form-label fw-semibold" style="font-size: 1.5rem;">Last Serial : </label>
                  </div>
                <div class="col-md-7 mb-3">
                  <label for="model" class="form-label fw-semibold" style="font-size: 1.5rem;"><?= $get_fgtag_info['tag_model_no'].' <span style="color:blue;">['.$get_fgtag_info['model_name'].']</span> '; ?></label><br>
                  <label for="fgtag" class="form-label fw-semibold" style="font-size: 1.5rem;"><?= $get_fgtag_info['tag_no']; ?></label><br>
                  <label for="ticket" class="form-label fw-semibold" style="font-size: 1.5rem;"><?= $get_fgtag_info['matching_ticket_no']; ?> || Ticket QTY. <?= $get_fgtag_info['ticket_qty']; ?></label><br>
                  <label for="ticket" class="form-label fw-semibold" style="font-size: 1.5rem;"><?= $last_serial; ?></label>
                </div>
              </div>
              <form method="post" action="">
              <div class="row">
                <div class="col-8">
                  <label for="model" class="form-label fw-semibold text-center" style="font-size: 1rem;">สแกน Label barcode no.</label>
                    <input type="text" class="form-control bg-light" id="model" name="model" autocomplete="off" required autofocus>
                    <input type="hidden" name="hkbmodel" value="<?= $get_fgtag_info['tag_model_no']; ?>">
                    <input type="hidden" name="htagno" value="<?= htmlspecialchars($fgtag); ?>">
                    <input type="hidden" name="htkno" value="<?= htmlspecialchars($transfer); ?>">
                    <input type="hidden" name="htagbc" value="<?= $get_fgtag_info['fg_tag_barcode']; ?>">
                    <input type="hidden" name="hserial" value="<?= $last_serial; ?>">
                </div>
                <div class="col-4 d-flex align-items-end">
                  <button type="submit" class="btn btn-success w-100">ยืนยัน</button>
                </div>
              </div>
              </form>
              <div id="checktagresult" class="mt-3"></div>
              <hr>
                <table class="table table-bordered mt-3">
                <thead class="table-light">
                  <tr></tr>
                  <th colspan="5" class="text-center">FGTAG Spliting Serial Confirmed</th>
                  </tr>
                  <tr>
                  <th>No.</th>
                  <th>Serial No.</th>
                  <th>Serial label confirm</th>
                  <th>Scan by</th>
                  <th>Date scan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $no = 1;
                    while($get_serial_info && $get_serial_info->fetch_assoc()) {
                      $serial_no = $get_serial_info['serial_no'];
                      $serial_label_confirm = $get_serial_info['serial_label_confirm'];
                      $scan_by = $_SESSION['user_login'];
                      $date_scan = $get_serial_info['date_scan'];
                      $no++;

                    echo "<tr>
                      <td>{$no}</td>
                      <td>" . htmlspecialchars($serial_no) . "</td>
                      <td>" . htmlspecialchars($serial_label_confirm) . "</td>
                      <td>" . htmlspecialchars($scan_by) . "</td>
                      <td>" . htmlspecialchars($date_scan) . "</td>
                    </tr>";
                    }
                  if($no == 1) {
                    echo "<tr><td colspan='5' class='text-center'>ยังไม่พบข้อมูลการสแกน Serial</td></tr>";
                  }
                  ?>
                </tbody>
                </table>
        </div>
      </div>
    </div>
  </div>

    <?php require_once 'footer.php'; ?>


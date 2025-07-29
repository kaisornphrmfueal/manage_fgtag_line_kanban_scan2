<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel FGTAG</title>
    <link href="bootstrap-5.3.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
</head>
<body>
  <!-- Main Content: FGTAG Cancel Form -->
  <div class="container mt-5" id="cancelTagSection">
    <div class="card">
      <div class="card-header">
        <h4>Cancel FGTAG</h4>
      </div>
      <div class="card-body">
        <form id="cancelTagForm">
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

  <script>
  document.getElementById('cancelTagForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const transferslip = document.getElementById('transferslip').value.trim();
    
    if (!transferslip) {
      document.getElementById('cancelTagResult').innerHTML = '<div class="alert alert-warning">กรุณาใส่หมายเลข Transfer Slip</div>';
      return;
    }

    // แสดง loading
    document.getElementById('cancelTagResult').innerHTML = '<div class="alert alert-info"><div class="d-flex align-items-center"><div class="spinner-border spinner-border-sm me-2" role="status"></div>กำลังตรวจสอบข้อมูล...</div></div>';

    fetch('function.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: 'action=check_transferslip&transferslip=' + encodeURIComponent(transferslip)
    })
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
      }
      return response.json();
    })
    .then(data => {
      const resultDiv = document.getElementById('cancelTagResult');
      if (data.success && data.found) {
        resultDiv.innerHTML = `
          <div class="alert alert-success">
            <h5>✅ พบข้อมูล Transfer Slip</h5>
            <hr>
            <div class="row">
              <div class="col-md-6">
                <strong>📋 Ticket Ref:</strong> ${data.data.ticket_ref}<br>
                <strong>📦 Quantity:</strong> ${data.data.ticket_qty}
              </div>
              <div class="col-md-6">
                <strong>🏷️ Model No:</strong> ${data.data.model_no}<br>
                <strong>📊 Status Write:</strong> ${data.data.status_write}
              </div>
            </div>
            <hr>
            <div class="text-center">
              <button class="btn btn-danger" onclick="cancelTag('${data.data.ticket_ref}')">
                🚫 Cancel Tag
              </button>
            </div>
          </div>
        `;
      } else if (data.success && !data.found) {
        resultDiv.innerHTML = `
          <div class="alert alert-danger">
            <h5>❌ ไม่พบข้อมูล Transfer Slip</h5>
            <hr>
            <strong>Input:</strong> ${transferslip}<br>
            <small>กรุณาตรวจสอบหมายเลขอีกครั้ง หรือข้อมูลอาจไม่ตรงตามเงื่อนไข</small>
          </div>
        `;
      } else {
        resultDiv.innerHTML = `
          <div class="alert alert-danger">
            <h5>⚠️ เกิดข้อผิดพลาด</h5>
            <hr>
            <strong>ข้อความ:</strong> ${data.message || 'ไม่ทราบสาเหตุ'}
          </div>
        `;
      }
    })
    .catch(error => {
      console.error('Error:', error);
      document.getElementById('cancelTagResult').innerHTML = `
        <div class="alert alert-danger">
          <h5>💥 เกิดข้อผิดพลาดในการเชื่อมต่อ</h5>
          <hr>
          <strong>Error:</strong> ${error.message}<br>
          <small>โปรดติดต่อฝ่าย IT หรือลองใหม่อีกครั้ง</small>
        </div>
      `;
    });
  });

  // ฟังก์ชัน Cancel Tag (ยังไม่ได้ implement)
  function cancelTag(ticketRef) {
    if (confirm(`คุณต้องการ Cancel Tag: ${ticketRef} หรือไม่?`)) {
      alert('ฟังก์ชัน Cancel Tag ยังไม่ได้ implement');
      // TODO: implement cancel tag functionality
    }
  }
  </script>
</body>
</html>


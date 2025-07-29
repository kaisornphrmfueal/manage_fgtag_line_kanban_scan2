
  <link href="../../includes/add-on/cancel_tag/bootstrap-5.3.3/css/bootstrap.min.css" rel="stylesheet">
  <script src="../../includes/add-on/cancel_tag/bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>

  <!-- Modal for Login -->
  <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
      <form class="modal-content" id="loginForm">
        <div class="modal-header">
          <h5 class="modal-title" id="loginModalLabel">Login Required</h5>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" required autocomplete="username">
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" required autocomplete="current-password">
          </div>
          <div id="loginError" class="text-danger d-none">Invalid username or password.</div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Login</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Main Content: FGTAG Cancel Form -->
  <div class="container mt-5" id="cancelTagSection" style="display:none;">
    <div class="card">
      <div class="card-header">
        <h4>ลบ FGTAG</h4>
      </div>
      <div class="card-body">
        <form id="cancelTagForm">
          <div class="mb-3">
            <label for="oldFgTag" class="form-label">FGTAG เก่า</label>
            <input type="text" class="form-control" id="oldFgTag" name="oldFgTag" required>
          </div>
          <div class="mb-3">
            <label for="newFgTag" class="form-label">FGTAG ใหม่</label>
            <input type="text" class="form-control" id="newFgTag" name="newFgTag" required>
          </div>
          <button type="submit" class="btn btn-danger">Submit</button>
        </form>
        <div id="cancelTagResult" class="mt-3"></div>
      </div>
    </div>
  </div>

  <script>
  // Show login modal on page load
  document.addEventListener('DOMContentLoaded', function() {
    var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
    loginModal.show();

    // Handle login form submit
    document.getElementById('loginForm').addEventListener('submit', function(e) {
      e.preventDefault();
      // Dummy authentication for demo; replace with real authentication
      var username = document.getElementById('username').value.trim();
      var password = document.getElementById('password').value.trim();
      if(username === 'admin' && password === 'admin') {
        loginModal.hide();
        document.getElementById('cancelTagSection').style.display = '';
      } else {
        document.getElementById('loginError').classList.remove('d-none');
      }
    });

    // Handle FGTAG cancel form submit
    document.getElementById('cancelTagForm').addEventListener('submit', function(e) {
      e.preventDefault();
      var oldFgTag = document.getElementById('oldFgTag').value.trim();
      var newFgTag = document.getElementById('newFgTag').value.trim();
      // TODO: Replace with AJAX call to backend for actual processing
      document.getElementById('cancelTagResult').innerHTML =
        '<div class="alert alert-success">FGTAG ' + oldFgTag + ' ถูกลบและแทนที่ด้วย ' + newFgTag + ' เรียบร้อยแล้ว</div>';
      this.reset();
    });
  });
  </script>

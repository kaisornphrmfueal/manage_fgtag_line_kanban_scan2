<?php 
include('../../includes/configure_orcl.php');
include('../../includes/configure_host_new.php');

$i=1;
$date_new = date("Y-m-d H:i:s");



if(!empty($_POST["emp_confirm"]))
{
  $sql_chk_per = "SELECT emp_id, emp_pass, emp_name FROM view_permission WHERE emp_id = '".$_POST["emp_confirm"]."' AND emp_pass = '".$_POST["emp_confirm_pass"]."' ";
  $query_chk_emp = mysqli_query($con_fg_tag, $sql_chk_per);
  $row_chk = mysqli_num_rows($query_chk_emp);

  if($row_chk >= 1)
  {
    $update_ng = "UPDATE fgt_bsi_scan_confirm 
    SET ng_confirm = '".$_POST["emp_confirm"]."', ng_detail = '".$_POST["detail"]."', date_confirm = '$date_new', status = 'OK'
    WHERE Id = '".$_POST["item_id"]."' ";
    $query_update_ng = mysqli_query($con_fg_tag, $update_ng);

    echo "<script>

    function alert_fade()
    {
      document.getElementById('fade_ok').style.display = 'block';
    }

    </script>";
    
  }
  else
  {

    echo "<script>

    function alert_fade()
    {
      document.getElementById('fade_ng').style.display = 'block';
    }

    </script>";

  }
}


$sql_ng = "SELECT Id, model_no, fg_tag, fg_tag_model, operator, record_date, status, suppiler_tag_model, name_plate
FROM fgt_bsi_scan_confirm
WHERE status = 'NG' ";
$query_ng = mysqli_query($con_fg_tag, $sql_ng);
$row_ng = mysqli_num_rows($query_ng);

if($row_ng == 0)
{
  echo "<script>
  window.location.href = 'index_bsi.php?id=YnNpX3NjYW4=&idtg=';
  </script>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>NG Confirm</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="new_css/bootstrap.min.css" rel="stylesheet">
  <script src="new_css/bootstrap.bundle.min.js"></script>
</head>
<body onload="alert_fade();" style="background-color:#E5EBEE ">

  <div class="container mt-3">

    <div class="alert alert-success alert-dismissible fade show" id="fade_ok" style="display: none;">
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      <strong>Success!</strong> NG confirm done!
    </div>
    <div class="alert alert-warning alert-dismissible" id="fade_ng" style="display: none;">
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      <strong>Warning!</strong> NG confirm false, please check username and pass or check permission.
    </div>

    <div class="card bg-success text-white">
      <div class="card-body">
        <h2>BSI NG Records</h2>
        <p>การยืนยัน NG ต้องยืนยันโดยหัวหน้างานเท่านั้น:</p> 
      </div>
    </div>
    <br>
    <table class="table table-bordered table-hover">
      <thead>
        <tr class="table-warning">
          <th>ID</th>
          <th>Model no (input1)</th>
          <th>FG Tranfer tag no</th>
          <th>Supplier tag model no</th>
          <th>Name Plate</th>
          <th>Line</th>
          <th>Record date</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        while($rs_data = mysqli_fetch_array($query_ng))
        {

          $get_line = "SELECT line_name FROM view_line WHERE line_id = '".$rs_data["operator"]."' ";
          $query_line = mysqli_query($con_fg_tag, $get_line);
          $rs_line = mysqli_fetch_array($query_line);
          echo "
          <tr class='text-center'>
          <td>".$i."</td>
          <td>".$rs_data['model_no']."</td>
          <td>".$rs_data['fg_tag']."</td>
          <td>".$rs_data['suppiler_tag_model']."</td>
          <td>".$rs_data['name_plate']."</td>
          <td>".$rs_line['line_name']."</td>
          <td>".$rs_data['record_date']."</td>
          <td><span class='badge bg-danger'>NG</span></td>
          <td>
          <button type='button' class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#confirm".$rs_data['Id']."'>Confirm
          </button>
          </td>
          </tr>

          <div class='modal fade' id='confirm".$rs_data['Id']."'>
          <div class='modal-dialog modal-dialog-centered'>
          <div class='modal-content'>

          <div class='modal-body'>
          <div class='text-center'>
          <h3>Confirm NG Record</h3>

          <form method='POST'>

          <div class='row'>
          <div class='col-sm-4' style='font-size= 16px; '>
          Employee ID ::
          </div>
          <div class='col-sm-8'>
          <input type='text' class='form-control form-control-sm' placeholder='exp. 2298' name='emp_confirm' required>
          </div>
          </div>

          <span style='font-size:5px; color:white;'>dasd</span>

          <div class='row'>
          <div class='col-sm-4' style='font-size= 16px;'>
          Employee ID ::
          </div>
          <div class='col-sm-8'>
          <input type='password' class='form-control form-control-sm' name='emp_confirm_pass' required>
          </div>
          </div>

          <span style='font-size:5px; color:white;'>dasd</span>

          <div class='row'>
          <div class='col-sm-4' style='font-size= 16px;'>
          NG Detail ::
          </div>
          <div class='col-sm-8'>
          <textarea class='form-control form-control-sm' name='detail' row='3' required ></textarea>
          </div>
          </div>
          <br>
          <input type='hidden' name='item_id' value='".$rs_data['Id']."' >
          <button class='btn btn-info btn-sm' type='submit'>Confrim</button>

          <form>

          </div>
          
          
          
          </div>

          </div>
          </div>
          </div>


          ";
          $i++;
        }
        ?>
      </tbody>
    </table>
  </div>

</body>
</html>

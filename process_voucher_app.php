
<?php
include 'connection.php';
include 'connectionpdo.php';

session_start();
$voucher_no = mysqli_real_escape_string($conn, $_POST['voucher_no']);
if(isset($_POST['endorse'])){
$query = "UPDATE voucher SET ed = '1', status = 'ED Endorsed' WHERE voucher_no = '".$voucher_no."'";
$result1=mysqli_query($conn, $query);
echo "<script type='text/javascript'>alert('Approved Successfully');
  window.location='view_voucher.php';
  </script>";
}


if(isset($_POST['approve'])){
    $query = "UPDATE voucher SET md = '1', status = 'MD Approved' WHERE voucher_no = '".$voucher_no."'";
    $result1=mysqli_query($conn, $query);
    //exit();
    echo "<script type='text/javascript'>alert('Approved Successfully');
  window.location='view_voucher.php';
  </script>";

    }

?>
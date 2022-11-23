<?php
include 'connection.php';
include 'connectionpdo.php';

session_start();

if(isset($_POST['edit'])){

$voucher_no = mysqli_real_escape_string($conn, $_POST['voucher_no']);
$username = $_SESSION['user']['id'];
$client_name = mysqli_real_escape_string($conn, $_POST['client_name']);
$paying_bank = mysqli_real_escape_string($conn, $_POST['paying_bank']);
$cheque_no = mysqli_real_escape_string($conn, $_POST['cheque_no']);
$currency = mysqli_real_escape_string($conn, $_POST['currency']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$amount = mysqli_real_escape_string($conn, $_POST['amount']);
$number = $amount;
$locale = 'en_US';
$fmt = numfmt_create($locale, NumberFormatter::SPELLOUT);
$amount_words = numfmt_format($fmt, $number);

$query = "UPDATE voucher SET client_name = '".$client_name."', paying_bank = '".$paying_bank."', 
cheque_no ='".$cheque_no."', currency = '".$currency."', description = '".$description."',
 amount = '".$amount."', amount_words = '".$amount_words."', created_at = '".date('d-m-y')."'
WHERE voucher_no = '".$voucher_no."'";
$result1=mysqli_query($conn, $query );

echo "<script type='text/javascript'>alert('Voucher Edited Successfully, Okay');
  window.location='view_voucher.php';
  </script>";
    
    
  } else {
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
  

}

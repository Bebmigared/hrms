<?php
include "connection.php";
include ('fpdf/fpdf.php');
session_start();
require 'class.phpmailer.php'; // path to the PHPMailer class
require 'class.smtp.php';
$pdf_name = 'leave_request'.time().'.pdf';
$dest = 'leave_request/'.$pdf_name;
$start_month = '';
$start_year = '';
$start_day = '';
$end_month = '';
$end_year = '';
$end_day = '';
$company = [];
if(!isset($_GET['leave_id'])){
  header("Location: view_leave_flow");
  return false;
}

 $month = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August','September', 'October', 'November', 'December'];
$query = "SELECT * FROM leaves WHERE id = '".$_SESSION['leave_id']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $leaves[] = $row;
        $t =  (int)(trim(explode('-',$row['start_date'])[1]));
         $start_month = $month[$t];
         $start_year = explode('-',$row['start_date'])[0];
         $start_day = explode('-',$row['start_date'])[2];
         $end_month = $month[$t];
         $end_year = explode('-',$row['end_date'])[0];
         $end_day = explode('-',$row['end_date'])[2];
      }
  }
  if($_SESSION['user']['category'] == 'admin' && count($leaves) > 0){
    $query = "SELECT * FROM users WHERE id = '".$leaves[0]['staff_id']."'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result)> 0){
        while($row = mysqli_fetch_assoc($result)) {
          $user[] = $row;
        }
    }
  }else if($_SESSION['user']['leave_processing_permission'] == '1' && count($leaves) > 0){
    $query = "SELECT * FROM users WHERE id = '".$leaves[0]['staff_id']."'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result)> 0){
        while($row = mysqli_fetch_assoc($result)) {
          $user[] = $row;
        }
    }
  }else {
    $user[0]['name'] = '';
    $user[0]['employee_ID'] = '';
  }
  if($_SESSION['user']['category'] == 'staff' && $_SESSION['user']['leave_processing_permission'] == '1') $admin_id = $_SESSION['user']['admin_id'];
  else if($_SESSION['user']['category'] == 'admin') $admin_id = $_SESSION['user']['id'];
  $query = "SELECT * FROM company WHERE admin_id = '".$admin_id."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $company[] = $row;
      }
  }
  print_r($start_day);
$pdf = new FPDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Image('images/header.jpg', 0, 0, 210, 297);
$pdf->Ln(30);
$pdf->SetFont('Times','',12);
$date_now = $month[(int)date('m')].' '.date('d').' '.date('Y');
$pdf->Cell(0,10,$date_now,0,1);
$pdf->Ln(10);
$pdf->SetFont('Times','B',12);
$pdf->Cell(0,10,isset($user[0]['name']) ? $user[0]['name'] : '',0,0);
$pdf->SetFont('Times','',12);
$pdf->Cell(0,10,isset($company[0]['name']) ? $company[0]['name'] : '',0,1);
$pdf->Cell(0,10,isset($company[0]['address']) ? $company[0]['address'] : '',0,1);
$pdf->Ln(20);
$salute = isset($user[0]['name']) ? 'Dear '.$user[0]['name'].',' : 'Dear,';
$pdf->Cell(0,10,$salute,0,1);
$pdf->Ln(5);
$pdf->SetFont('Times','BU',12);
$pdf->Cell(0,10,'Confirmation of Leave Application',0,1);
$pdf->SetFont('Times','',12);
$leave_type = isset($leaves[0]['leave_type']) ? $leaves[0]['leave_type'] : '';
$pdf->Cell(0,10,'We received your leave request which has already been approved by your unit head/branch manager.',0,1);
$pdf->Cell(0,10,'Therefore, you are to proceed on your '.$leave_type.' Leave effective '.$start_month.' '.$start_day.', '.$start_year.' and expected to resume' ,0,1); 
$pdf->SetFont('Arial','',10);
$boldCell = "on ";
$pdf->Cell($pdf->GetStringWidth($boldCell),3,$boldCell, 0, 'L');



$pdf->SetFont('Times','B',12);
$cell = ''.$end_month.' '.$end_day.', '.$end_year.'.';
$pdf->Cell($pdf->GetStringWidth($cell),3,$cell, 0, 'L');


$pdf->Ln(5);



$pdf->SetFont('Times','',12);
$pdf->Cell(0,10,'Thank you',0,1);
$pdf->Ln(5);

$pdf->Cell(0,10,'Yours Faithfully,',0,1);
$boldCell = "For: ";
$pdf->Cell($pdf->GetStringWidth($boldCell),3,$boldCell, 0, 'L');


$pdf->SetFont('Times','B',12);
$cell = isset($company[0]['company_name']) ? $company[0]['company_name']:'';
$pdf->Cell($pdf->GetStringWidth($cell),3,$cell, 0, 'L');

$pdf->Output('F', $dest);
updateLeave($conn,$leaves);
header("Location: /selfservice/downloadpdf.php/?file=leave_request&filename=".$pdf_name."");
function updateLeave($conn,$leaves){
     $sql = "UPDATE leaves SET processed = 'true' WHERE id = '".$leaves[0]['id']."'";
        if (mysqli_query($conn, $sql)) {
        } else {
          echo "Error updating record: " . mysqli_error($conn);
        }
  }
header("Location: /selfservice/downloadpdf.php/?file=leave_request&filename=".$pdf_name."")
?>
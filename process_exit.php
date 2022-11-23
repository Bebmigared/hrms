<?php
include 'connection.php';
include 'connectionpdo.php';
include "process_email.php";
session_start();
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
 $address = mysqli_real_escape_string($conn, $_POST['address']);
    $date_exited = mysqli_real_escape_string($conn, $_POST['date_exited']);
    $date_employed = mysqli_real_escape_string($conn, $_POST['date_employed']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $outstandingallowance = mysqli_real_escape_string($conn, $_POST['outstandingallowance']);
    $obligation = mysqli_real_escape_string($conn, $_POST['obligation']);
    $yes_obligation = mysqli_real_escape_string($conn, $_POST['yes_obligation']);
    $extentissuesolved = mysqli_real_escape_string($conn, $_POST['extentissuesolved']);
    $repaymentplan = mysqli_real_escape_string($conn, $_POST['repaymentplan']);
    $outstandingissues = mysqli_real_escape_string($conn, $_POST['outstandingissues']);
    $if_outstanding_issues = mysqli_real_escape_string($conn, $_POST['if_outstanding_issues']);
    $propertiescare = mysqli_real_escape_string($conn, $_POST['propertiescare']);
    $planaboutresolvingissue = mysqli_real_escape_string($conn, $_POST['planaboutresolvingissue']);
 $month = date('m');
 $year = date('y');
 $string_company_name = strtoupper(substr($company_name, 0, 3));
 $admin_id = $_SESSION['user']['admin_id'];



 //$new_item_id = $total + 1;
 $item_id = $string_company_name .'/'. $month. '/' . $year . '/' . $new_item_id;
 $flow = mysqli_real_escape_string($conn, $_POST['flow']);
 $flow = $flow == '' ? $_SESSION['user']['exit_flow'] : $flow;

 if(isset($_POST['submit'])){
  /* if($_SESSION['user']['exit_flow'] == '' && $flow == ''){
     $_SESSION['msg'] = "You can not process requisition, because you don't have approvals. Contact your admin";
     header("Location: newexit.php");
     //header("Location: staff_settings.php");
     return false;
   }*/
if($flow != '')
   {
      $sql = "UPDATE users SET exit_flow = ? WHERE id = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$flow, $_SESSION['user']['id']]);
      $_SESSION['user']['exit_flow'] = $flow;

   }



  $pdo->beginTransaction();
  
  try {


    $sql = "UPDATE users SET staff_exit = 'yes', exit_date = '".$date_exited."', correspondence_address = '".$address."', date_employed = '".$date_employed."',reason_exit = '".$reason."', how_outstanding_to_be_paid = '".$outstandingallowance."', do_you_have_outstanding_obligation = '".$obligation."', yes_obligation='".$yes_obligation."', repayment_plan = '".$repaymentplan."', outstanding_issue = '".$outstandingissues."', if_outstanding_issue = '".$if_outstanding_issues."', extent_issue_resolved = '".$extentissuesolved."', plan_about_resolving_issue = '".$planaboutresolvingissue."', have_you_submitted_company_ppties = '".$propertiescare."' WHERE id = '".$_SESSION['user']['id']."'";
    $stmt = $pdo->prepare($sql);

    $requestId = $pdo->lastInsertId();

    $requestflow =  explode(";",$_SESSION['user']['exit_flow']);
    $firstapproval = '';
    for($r = 0; $r < count($requestflow); $r++)
    {
      $title = explode(":", $requestflow[$r])[0];
      $id = explode(":", $requestflow[$r])[1];
      if($r == 0) $firstapproval = $id;
      $sql = "INSERT INTO approval_flows (title,type,approvalId, level,requestId,date_created) VALUES (?, ?,?, ?,?,?)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$title,'Item Request',$id,($r+1),$requestId,date('Y-m-d')]);

    }


    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$firstapproval]); 
    $user = $stmt->fetch();

    if(isset($user['id']))
    {
      $msg = "<div><p>Good Day,</p><p>".$_SESSION['user']['name']." has requested an exit, kindly log in to account to view  more detail about the request.</p><p><a  style ='padding:7px;color:#fff;background-color:#4e73df;margin:10px;border-radius: 3px;text-decoration:none' href = 'http://www.ics.hrcore.ng'>Log In to view</a></p></div>";
      process_data($conn,$user['email'],$msg);

    }

    $pdo->commit();
    $_SESSION['msg'] = "Your request is being processed";
    header("Location: newexit.php");
  } catch(Exception $e)
  {
      $pdo->rollBack();
      //$_SESSION['msg'] = "Error whiling processng data";
      
      
      
      throw $e;
      header("Location: newexit.php");
  } 
}
?>
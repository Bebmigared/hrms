<?php
  include "connection.php";
  //include "connection.php";
  include "process_email.php";
  session_start();
   if(!isset($_SESSION['user']['id'])) header("Location: login.php");


  if(isset($_POST['remove_permission']))
  {
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $access = mysqli_real_escape_string($conn, $_POST['access']);
    if($department != ""){
      $sql = "UPDATE users SET $access = '0' WHERE department = '".$department."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Permission denied to Department";
        } else {
            $_SESSION['msg'] = "Error while granting permission to $department";
            
        }
    }
    header("Location: access.php");
  }

  if(isset($_GET['access']))
  {
    $access = $_GET['access'];
      $sql = "UPDATE users SET $access = '0' WHERE id = '".$_GET['id']."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Access removed from user";
        } else {
            $_SESSION['msg'] = "Error while granting permission to user";
            
        }
    header("Location: access.php");
  }

   if(isset($_POST['manage_talent']))
  {
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $staff_email = mysqli_real_escape_string($conn, $_POST['staff']);
    if($department != ""){
      $sql = "UPDATE users SET add_talent_management = '1' WHERE department = '".$department."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Talent management Module granted to department";
        } else {
            $_SESSION['msg'] = "Error while granting permission to department";
            
        }
    }
    if($staff_email != "") {
      $sql = "UPDATE users SET add_talent_management = '1' WHERE email = '".$staff_email."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Talent management Module granted to user";
            $msg = "The admin has granted you permission to manage Talent Module.";
            process_data($conn,$staff_email,$msg,'Talent Management');
        } else {
            $_SESSION['msg'] = "Error while granting permission to user";
            
        }
        //header("Location: permission.php");
    }
    header("Location: permission.php");
  }

  if(isset($_POST['manage_permission']))
  {
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $staff_email = mysqli_real_escape_string($conn, $_POST['staff']);
    if($department != ""){
      $sql = "UPDATE users SET add_permission_management = '1' WHERE department = '".$department."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Permission management granted to department";
        } else {
            $_SESSION['msg'] = "Error while granting permission to department";
            
        }
    }
    if($staff_email != "") {
      $sql = "UPDATE users SET add_permission_management = '1' WHERE email = '".$staff_email."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Permission management granted to user";
            $msg = "The admin has granted you permission to manage Employee.";
            process_data($conn,$staff_email,$msg,'Permission Management');
        } else {
            $_SESSION['msg'] = "Error while granting permission to user";
            
        }
        //header("Location: permission.php");
    }
    header("Location: permission.php");
  }

  if(isset($_POST['create_voucher']))
  {
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $staff_email = mysqli_real_escape_string($conn, $_POST['staff']);
    if($department != ""){
      $sql = "UPDATE users SET create_voucher = '1' WHERE department = '".$department."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Voucher management granted to department";
        } else {
            $_SESSION['msg'] = "Error while granting permission to department";
            
        }
    }
    if($staff_email != "") {
      $sql = "UPDATE users SET create_voucher = '1' WHERE email = '".$staff_email."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Voucher management granted to user";
            $msg = "The admin has granted you permission to manage Employee.";
            process_data($conn,$staff_email,$msg,'Permission Management');
        } else {
            $_SESSION['msg'] = "Error while granting permission to user";
            
        }
        //header("Location: permission.php");
    }
    header("Location: permission.php");
  }

  if(isset($_POST['manage_employee']))
  {
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $staff_email = mysqli_real_escape_string($conn, $_POST['staff']);
    if($department != ""){
      $sql = "UPDATE users SET add_employee_management = '1' WHERE department = '".$department."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Employee management permission granted to department";
        } else {
            $_SESSION['msg'] = "Error while granting permission to department";
            
        }
    }
    if($staff_email != "") {
      $sql = "UPDATE users SET add_employee_management = '1' WHERE email = '".$staff_email."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Employee management permission granted to user";
            $msg = "The admin has granted you permission to manage Employee.";
            process_data($conn,$staff_email,$msg,'Employee Management');
        } else {
            $_SESSION['msg'] = "Error while granting permission to user";
            
        }
        //header("Location: permission.php");
    }
    header("Location: permission.php");
  }

  if(isset($_POST['manage_request']))
  {
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $staff_email = mysqli_real_escape_string($conn, $_POST['staff']);
    if($department != ""){
      $sql = "UPDATE users SET add_item_permission = '1' WHERE department = '".$department."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Request management permission granted to department";
        } else {
            $_SESSION['msg'] = "Error while granting permission to department";
            
        }
    }
    if($staff_email != "") {
      $sql = "UPDATE users SET add_item_permission = '1' WHERE email = '".$staff_email."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Request management permission granted to user";
            $msg = "The admin has granted you permission to manage Requisition.";
            process_data($conn,$staff_email,$msg,'Request Management');
        } else {
            $_SESSION['msg'] = "Error while granting permission to user";
            
        }
        //header("Location: permission.php");
    }
    header("Location: permission.php");
  }
  if(isset($_POST['id_request'])){
  	$department = mysqli_real_escape_string($conn, $_POST['department']);
  	$staff_email = mysqli_real_escape_string($conn, $_POST['staff']);
  	if($department != ""){
  		$sql = "UPDATE users SET id_card_permission = '1' WHERE department = '".$department."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "ID Card Processing permission granted to department";
        } else {
            $_SESSION['msg'] = "Error while granting permission to department";
            
        }
  	}
  	if($staff_email != "") {
  		$sql = "UPDATE users SET id_card_permission = '1' WHERE email = '".$staff_email."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "ID Card Processing permission granted to user";
            $msg = "The admin has granted you permission to process ID Card request.";
            process_data($conn,$staff_email,$msg,'ID Processing Privilege');
        } else {
            $_SESSION['msg'] = "Error while granting permission to user";
            
        }
        //header("Location: permission.php");
  	}
  	header("Location: permission.php");
  }else if(isset($_POST['upload_appraisal'])){
  	$department = mysqli_real_escape_string($conn, $_POST['department']);
  	$staff_email = mysqli_real_escape_string($conn, $_POST['staff']);
  	if($department != ""){
  		$sql = "UPDATE users SET upload_appraisal = '1' WHERE department = '".$department."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Permission granted to department";
        } else {
            $_SESSION['msg'] = "Error while granting permission to department";
            
        }
  	}
  	if($staff_email != "") {
  		$sql = "UPDATE users SET upload_appraisal = '1' WHERE email = '".$staff_email."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "permission granted to user";
        } else {
            $_SESSION['msg'] = "Error while granting permission to user";
            
        }
        //header("Location: permission.php");
  	}
  	header("Location: permission.php");
  }else if(isset($_POST['payroll'])){
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $staff_email = mysqli_real_escape_string($conn, $_POST['staff']);
    if($department != ""){
      $sql = "UPDATE users SET payroll_permission = '1' WHERE department = '".$department."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Payroll Processing permission granted to department";
        } else {
            $_SESSION['msg'] = "Error while granting permission to department";
            
        }
    }
    if($staff_email != "") {
      $sql = "UPDATE users SET payroll_permission = '1' WHERE email = '".$staff_email."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Payroll Processing permission granted to user";
            $msg = "The admin has granted you permission to manage employee payroll.";
            process_data($conn,$staff_email,$msg,'ID Processing Privilege');
        } else {
            $_SESSION['msg'] = "Error while granting permission to user";
            
        }
        //header("Location: permission.php");
    }
    header("Location: permission.php");
  }else if(isset($_POST['leave'])){
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $staff_email = mysqli_real_escape_string($conn, $_POST['staff']);
    if($department != ""){
      $sql = "UPDATE users SET leave_processing_permission = '1' WHERE department = '".$department."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "ID Card Processing permission granted to department";
        } else {
            $_SESSION['msg'] = "Error while granting permission to department";
            
        }
    }
    if($staff_email != "") {
      $sql = "UPDATE users SET leave_processing_permission = '1' WHERE email = '".$staff_email."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Leave Processing privilege has been granted to user";
            $msg = "The admin has granted you permission to process Leave Request.";
            process_data($conn,$staff_email,$msg,'Leave Processing Privilege');
        } else {
            $_SESSION['msg'] = "Error while granting permission to user";
            
        }
        //header("Location: permission.php");
    }
    header("Location: permission.php");
  }else if(isset($_POST['cash_request'])){
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $staff_email = mysqli_real_escape_string($conn, $_POST['staff']);
    if($department != ""){
      $sql = "UPDATE users SET cash_processing_permission = '1' WHERE department = '".$department."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Cash Request Processing permission granted to department";
        } else {
            $_SESSION['msg'] = "Error while granting permission to department";
            
        }
    }
    if($staff_email != "") {
      $sql = "UPDATE users SET cash_processing_permission = '1' WHERE email = '".$staff_email."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Cash Request Processing privilege has been granted to user";
            $msg = "The admin has granted you permission to process cash request.";
            process_data($conn,$staff_email,$msg,'Cash Processing Privilege');
        } else {
            $_SESSION['msg'] = "Error while granting permission to user";
            
        }
        //header("Location: permission.php");
    }
    header("Location: permission.php");
  }

?>
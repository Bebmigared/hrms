<?php
  include 'connection.php';
  require_once "connectionpdo.php";
  session_start(); 
   if(!isset($_SESSION['user']['id'])) header("Location: login.php");
  $msg = '';
  $data = [];
  $admin_id = '';
  //echo $_POST['appraisalflow'];
  if(!isset($_SESSION['user']['id'])) 
  {
    header("Location: login");
  }
  $query = "SELECT * FROM company WHERE uniqueID = '".$_SESSION['user']['uniqueID']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
      }
  }
  if(isset($_POST['submit'])){
       $branch = mysqli_real_escape_string($conn, $_POST['branch']);
       $department = mysqli_real_escape_string($conn, $_POST['department']);
       $leaveflow = mysqli_real_escape_string($conn, $_POST['leaveflow']);
       $appraisalflow = mysqli_real_escape_string($conn, $_POST['appraisalflow']);
       $requisitionflow = mysqli_real_escape_string($conn, $_POST['requisitionflow']);
       $exitflow = mysqli_real_escape_string($conn, $_POST['exitflow']);
       $cashflow = mysqli_real_escape_string($conn, $_POST['cashflow']);
       $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
       $address = mysqli_real_escape_string($conn, $_POST['address']);
       $companyId = $_SESSION['user']['companyId'];
       $roleflow = $_POST['role'];
       //echo $roleflow;
      
          
        $sql = "UPDATE users SET first_time_loggin = ? WHERE id = ?";
        $query = $pdo->prepare($sql);
        $query->execute(['0', $_SESSION['user']['id']]);
        if ($query->rowCount() == 1) { $msg =  true; } else { }
       
        
       //echo $branch;
    if(count($data) > 0){
          // $dept = implode(";", $department);
          // $branch = implode(";", $branch);
          // $requisitionFlow = implode(";",$requisitionflow);
          // $appraisalFlow = implode(";",$appraisalflow);
          // $leaveFlow = implode(";",$leaveflow);
          // $cashFlow = implode(";",$cashflow);
          // $role = implode(";", $roleflow);
       updatesettings($pdo, trim($roleflow), trim($department),trim($branch), trim($requisitionflow),$appraisalflow,$leaveflow,$cashflow,$companyId);    
       $sql = "UPDATE company SET branch = '".trim($branch)."', department = '".trim($department)."',leave_flow = '".trim($leaveflow)."', appraisal_flow = '".trim($appraisalflow)."',requisition_flow = '".trim($requisitionflow)."' , company_name = '".trim($company_name)."',cash_flow = '".trim($cashflow)."', address = '".$address."', exit_flow = '".$exitflow."' WHERE id = '".$data[0]['id']."'";
        if (mysqli_query($conn, $sql)) {
            $msg =  true;
        } else {
           $msg = false;
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }else {
      $sql = "INSERT INTO company (branch, department, leave_flow, appraisal_flow, requisition_flow, company_name, address,admin_id, date_created,cash_flow, exit_flow)
          VALUES ('".$branch."', '".$department."', '".$leaveflow."','".$appraisalflow."', '".$requisitionflow."' ,'".$company_name."','".$address."','".$_SESSION['user']['id']."','".date('Y-m-d')."', '".$cashflow."', '".$exitflow."')";
          if (mysqli_query($conn,$sql ) === TRUE) {
              $msg = true;
              updatesettings($pdo, $roleflow, $dept,$branch, $requisitionFlow,$appraisalFlow,$leaveFlow,$cashFlow,$conn->insertId);
              //header("Location: login.php");
          } else {
              //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
              //$_SESSION['msg'] = "Error: " . $sql . "<br>" . mysqli_error($conn);
             $_SESSION['msg'] = "Error updating data, kindly try again later";
              $msg = false;
          }
          
    }
    if(isset($_FILES['image'])){
       if($_FILES['image']['name'] == null) {
            echo $msg;
       }else {
       $error = array(); 
       $file_ext = explode('.',$_FILES['image']['name'])[1];
       $img = explode('.',$_FILES['image']['name'])[0].'_'.strtotime(date('Y-m-d'));
       $file_name = $img.'.'.$file_ext;
       $file_size = $_FILES['image']['size'];
       $file_tmp = $_FILES['image']['tmp_name'];
       $file_type = $_FILES['image']['type'];
       //$file_ext = explode('.',$_FILES['image']['name'])[1];
       $extensions = array('jpeg','jpg','png');
       if(in_array($file_ext,$extensions) === false){
        $errors[] = "extension not allowed, please select a JPEG or PNG file.";
       }
       if($file_size > 209752){
        $errors[] = "File size too large";
       }
       if(empty($errors)==true){
         move_uploaded_file($file_tmp,"images/".$file_name);
          $sql = "UPDATE company SET image = '".$file_name."' Where admin_id = '".$_SESSION['user']['id']."'";
          if (mysqli_query($conn, $sql)) {
            $_SESSION['logo'] = $file_name;
            $msg = true;
          }else {$_SESSION['msg'] = 'Error updating data'; $msg = false;}

       }else {
         $msg = $errors[0];
         $_SESSION['msg'] = $msg;
         $msg = false;
       }
       }
    }
    
    echo $msg;
    return false;
  }  
   function updatesettings($pdo, $role, $dept,$branch, $requisitionflow,$appraisalflow,$leaveflow,$cashflow,$companyId)
  {
             $dept = explode(";", $dept);
             $branch = explode(";", $branch);
             $requisitionFlow = explode(";",$requisitionflow);
             $appraisalFlow = explode(";",$appraisalflow);
             $leaveFlow = explode(";",$leaveflow);
             $cashFlow = explode(";",$cashflow);
             $role = explode(";", $role);
            for($t = 0; $t < count($role); $t++)
          {
              $companyId = $_SESSION['user']['companyId'];
              $sql = "INSERT INTO roles (rolename,admin_id,insert_by,date_created,company_id) VALUES (?,?,?,?,?)";
              $query = $pdo->prepare($sql);
              $query->execute([$role[$t], $_SESSION['user']['id'], $_SESSION['user']['id'], date('Y-m-d'), $companyId]);
              if ($query->rowCount() == 1) { $msg =  true; } else { }
          }
          for($t = 0; $t < count($dept); $t++)
          {
              $sql = "INSERT INTO departments (dept,admin_id,insert_by,date_created,company_id) VALUES (?,?,?,?,?)";
              $query = $pdo->prepare($sql);
              $query->execute([$dept[$t], $_SESSION['user']['id'], $_SESSION['user']['id'], date('Y-m-d'), $companyId]);
              if ($query->rowCount() == 1) { $msg =  true; } else { }
          }
          for($t = 0; $t < count($branch); $t++)
          {
              $sql = "INSERT INTO branches (name,admin_id,insert_by,date_created,company_id,email,address) VALUES (?,?,?,?,?,?,?)";
              $query = $pdo->prepare($sql);
              $query->execute([$branch[$t], $_SESSION['user']['id'], $_SESSION['user']['id'], date('Y-m-d'), $companyId,'','']);
              if ($query->rowCount() == 1) { $msg =  true; } else { }
          }    
          
          for($t = 0; $t < count($requisitionFlow); $t++)
          {
              $sql = "INSERT INTO flows (flowname,approval,level,admin_id,insert_by,date_created,company_id) VALUES (?,?,?,?,?,?,?)";
              $query = $pdo->prepare($sql);
              $query->execute(['Requisition',$requisitionFlow[$t],($t+1), $_SESSION['user']['id'], $_SESSION['user']['id'], date('Y-m-d'), $companyId]);
              if ($query->rowCount() == 1) { $msg =  true; } else { }
          }
          for($t = 0; $t < count($appraisalFlow); $t++)
          {
              $sql = "INSERT INTO flows (flowname, approval,level,admin_id,insert_by,date_created,company_id) VALUES (?,?,?,?,?,?,?)";
              $query = $pdo->prepare($sql);
              $query->execute(['Appraisal',$appraisalFlow[$t],($t+1), $_SESSION['user']['id'], $_SESSION['user']['id'], date('Y-m-d'), $companyId]);
              if ($query->rowCount() == 1) { $msg =  true; } else { }
          }
          for($t = 0; $t < count($leaveFlow); $t++)
          {
              $sql = "INSERT INTO flows (flowname, approval,level,admin_id,insert_by,date_created,company_id) VALUES (?,?,?,?,?,?,?)";
              $query = $pdo->prepare($sql);
              $query->execute(['Leave',$leaveFlow[$t],($t+1), $_SESSION['user']['id'], $_SESSION['user']['id'], date('Y-m-d'), $companyId]);
              if ($query->rowCount() == 1) { $msg =  true; } else { }
          }
          for($t = 0; $t < count($cashFlow); $t++)
          {
              $sql = "INSERT INTO flows (flowname, approval,level,admin_id,insert_by,date_created,company_id) VALUES (?,?,?,?,?,?,?)";
              $query = $pdo->prepare($sql);
              $query->execute(['Cash',$cashFlow[$t],($t+1), $_SESSION['user']['id'], $_SESSION['user']['id'], date('Y-m-d'), $companyId]);
              if ($query->rowCount() == 1) { $msg =  true; } else { }
          }
        }
?>
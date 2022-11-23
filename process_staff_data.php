<?php
  include "connection.php";
  session_start();
   if(!isset($_SESSION['user']['id'])) header("Location: login.php");
  $final_update = 0;
  $msg = '';
  $query = "SELECT * FROM users WHERE id = '".$_SESSION['user']['id']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
      }
  }
  if(isset($_POST['submit'])){
  	if($_SESSION['user']['admin_id'] == ''){
  		$name = mysqli_real_escape_string($conn, $_POST['name']);
	    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
	    $employee_ID = mysqli_real_escape_string($conn, $_POST['employee_ID']);
	    $admin_email = mysqli_real_escape_string($conn, $_POST['admin_email']);
	    $role = mysqli_real_escape_string($conn, $_POST['role']);
	    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
	    //$branch = $branch != '' ? $branch : $data[0]['branch'];
        $department = '';
        $leaveflow = '';
        $appraisalflow = '';
        $requisitionflow = '';
        $branch = '';
	    $admin_id = getAdmin($conn,$admin_email);
	    updateUser($conn,$name, $phone_number, $employee_ID, $admin_id,$role,$department,$leaveflow,$appraisalflow,$requisitionflow,$branch);
	    if(isset($_FILES['image'])) processImage($conn,$_FILES['image'],$admin_id);
  	}else {
  		$name = mysqli_real_escape_string($conn, $_POST['name']);
	    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
	    $employee_ID = mysqli_real_escape_string($conn, $_POST['employee_ID']);
	    $leaveflow = mysqli_real_escape_string($conn, $_POST['all_leave_approvals']);
	    $appraisalflow = mysqli_real_escape_string($conn, $_POST['all_appraisal_approvals']);
      $requisitionflow = mysqli_real_escape_string($conn, $_POST['all_requisition_approvals']);
      $cashflow = mysqli_real_escape_string($conn, $_POST['all_cash_approvals']);
	    $role = mysqli_real_escape_string($conn, $_POST['role']);
      $position = mysqli_real_escape_string($conn, $_POST['position']);
	    $department = mysqli_real_escape_string($conn, $_POST['department']);
	    $branch = mysqli_real_escape_string($conn, $_POST['branch']);
	    $role = $role != '' ? $role : $data[0]['role'];
	    $branch = $branch != '' ? $branch : $data[0]['branch'];
      $position = $position != '' ? $position : $data[0]['position'];
      $grade = $grade != '' ? $grade : $data[0]['grade'];
        $department = $department != '' ? $department : $data[0]['department'];
        $leaveflow = $leaveflow != '' ? $leaveflow : $data[0]['leave_flow'];
        $appraisalflow = $appraisalflow != '' ? $appraisalflow : $data[0]['appraisal_flow'];
        $requisitionflow = $requisitionflow != '' ? $requisitionflow : $data[0]['requisition_flow'];
        $cashflow = $cashflow != '' ? $cashflow : $data[0]['cash_flow'];
        $admin_id = $_SESSION['user']['admin_id'];
	    $msg = finalupdateUser($conn,$name, $phone_number, $employee_ID, $admin_id,$role,$position,$department,$leaveflow,$appraisalflow, $requisitionflow, $branch, $cashflow);
	     if(isset($_FILES['image'])) processImage($conn,$_FILES['image'],$admin_id);
	     else echo $msg;
  	}
  }
  function getAdmin($conn,$admin_email){
        if($admin_email  == ''){$admin_id = '';}
        else {
        	$query = "SELECT * from users WHERE email = '$admin_email'";
		  	$result = mysqli_query($conn, $query);
		  	if(mysqli_num_rows($result) > 0){
		  		$row = mysqli_fetch_assoc($result);
		  		$admin_id = $row['id'];
            }	
        return $admin_id;
        }
  }      
  function updateUser($conn, $name, $phone_number,$employee_ID,$admin_id,$role,$department,$leaveflow,$appraisalflow,$requisitionflow,$branch){
        $sql = "UPDATE users SET name = '".$name."',phone_number = '".$phone_number."', employee_ID = '".$employee_ID."',admin_id = '".$admin_id."', role = '".$role."', branch = '".$branch."',department ='".$department."', leave_flow = '".$leaveflow."', appraisal_flow = '".$appraisalflow."', requisition_flow = '".$requisitionflow."'   WHERE email = '".$_SESSION['user']['email']."'";
        if (mysqli_query($conn, $sql)) {
        	if($admin_id != ''){
        		if($_SESSION['user']['department'] == '' || $_SESSION['branch'] == ''){
        			$_SESSION['msg'] = 'Updated noted, kindly input other details';
        		}
        	}else {
        		$_SESSION['msg'] = 'Updated noted, please input the admin email to continue with the update.';
        	}
            //$_SESSION['msg'] = "Record updated successfully";
            $_SESSION['user']['phone_number'] = $phone_number;
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['role'] = $role;
            $_SESSION['user']['employee_ID'] = $employee_ID;
            $_SESSION['user']['admin_id'] = $admin_id;

        } else {
            echo "Error updating record: " . mysqli_error($conn);
            //header("Location: settings.php");
        }
  }
  function finalupdateUser($conn, $name, $phone_number,$employee_ID,$admin_id,$role,$position,$department,$leaveflow,$appraisalflow,$requisitionflow,$branch,$cashflow){
        $sql = "UPDATE users SET name = '".$name."',phone_number = '".$phone_number."', employee_ID = '".$employee_ID."',admin_id = '".$admin_id."',position = '".$position."', role = '".$role."', branch = '".$branch."',department ='".$department."',leave_flow = '".$leaveflow."', first_time_loggin = '0', requisition_flow = '".$requisitionflow."', cash_flow = '".$cashflow."', appraisal_flow = '".$appraisalflow."', profile_image = 'user_profile.png'   WHERE email = '".$_SESSION['user']['email']."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['user']['phone_number'] = $phone_number;
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['role'] = $role;
            $_SESSION['user']['position'] = $position;
            $_SESSION['user']['employee_ID'] = $employee_ID;
            $_SESSION['user']['admin_id'] = $admin_id;
            $_SESSION['user']['branch'] = $branch;
            $_SESSION['user']['department'] = $department;
            $_SESSION['user']['leave_flow'] = $leaveflow;
            $_SESSION['user']['appraisal_flow'] = $appraisalflow;
            $_SESSION['user']['requisition_flow'] = $requisitionflow;
            $_SESSION['user']['cash_flow'] = $cashflow;
            return true;

        } else {
        	//return false;
            //echo "Error updating record: " . mysqli_error($conn);
            return false;
            //header("Location: settings.php");
        }
        $final_update = 1;
  }
  function processImage($conn,$image,$admin_id){
  	if(isset($image)){
       if($image['name'] == null) {
       	    if($final_update == 1) echo $msg;
       	    else
       		  header("Location: staff_settings.php");
       }else {

       $error = array(); 
       $file_ext = explode('.',$image['name'])[1];
       $img = explode('.',$image['name'])[0].'_'.strtotime(date('Y-m-d'));
       $file_name = $img.'.'.$file_ext;
       $file_size = $image['size'];
       $file_tmp = $image['tmp_name'];
       $file_type = $image['type'];
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
          $sql = "UPDATE users SET profile_image = '".$file_name."' Where id = '".$_SESSION['user']['id']."'";
          if (mysqli_query($conn, $sql)) {
            $_SESSION['user']['profile_image'] = $file_name;
          }else {$_SESSION['msg'] = 'Error updating data'; return false;}
          if($final_update == 1) echo $msg;
          else
           header("Location: staff_settings.php");
       }else {
         $msg = $errors[0];
         $_SESSION['msg'] = $msg;
         if($final_update == 1) echo $msg;
         else
           header("Location: staff_settings.php");
       }
       }
    }
  }
?>
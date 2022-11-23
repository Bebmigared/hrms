<?php
include 'connection.php';
include "process_email.php";
session_start();
if(!isset($_SESSION['user']['id'])) header('location: login.php');

 function isAccountCreated($conn, $email){
    $query = "SELECT * from users WHERE email = '".trim($email)."'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
      return 0;
    }
    return 1;
  }

  function isIDExist($conn, $employee_ID){
    $query = "SELECT * from users WHERE employee_ID = '".trim($employee_ID)."'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
      return 0;
    }
    return 1;
  }
if(isset($_POST['submit'])){
    $surname = mysqli_real_escape_string($conn, $_POST['name']);
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $mname = mysqli_real_escape_string($conn, $_POST['mname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $marital_status = mysqli_real_escape_string($conn, $_POST['marital_status']);
    $employee_ID = mysqli_real_escape_string($conn, $_POST['employee_ID']);
    $branch = mysqli_real_escape_string($conn, $_POST['branch']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $lga = mysqli_real_escape_string($conn, $_POST['lga']);
    $sorigin = mysqli_real_escape_string($conn, $_POST['sorigin']);
    $town = mysqli_real_escape_string($conn, $_POST['town']);
    
    $sresidence = mysqli_real_escape_string($conn, $_POST['sresidence']);
    $on_hmo = mysqli_real_escape_string($conn, $_POST['on_hmo']);
    $hmo = mysqli_real_escape_string($conn, $_POST['hmo']);
    $hmo_number = mysqli_real_escape_string($conn, $_POST['hmo_number']);
    $hmo_plan = mysqli_real_escape_string($conn, $_POST['hmo_plan']);
    $hmo_hospital = mysqli_real_escape_string($conn, $_POST['hmo_hospital']);
    
    $hmo_status = mysqli_real_escape_string($conn, $_POST['hmo_status']);
    $hmo_remarks = mysqli_real_escape_string($conn, $_POST['hmo_remarks']);
    $pension = mysqli_real_escape_string($conn, $_POST['pension']);
    $pension_number = mysqli_real_escape_string($conn, $_POST['pension_number']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    //$hmo_hospital = mysqli_real_escape_string($conn, $_POST['hmo_hospital']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    
    $leaveflow = mysqli_real_escape_string($conn, $_POST['leaveflow']);
    $appraisalflow = mysqli_real_escape_string($conn, $_POST['appraisalflow']);
    $reqflow = mysqli_real_escape_string($conn, $_POST['reqflow']);
    $cashflow = mysqli_real_escape_string($conn, $_POST['cashflow']);
    //$exitflow = mysqli_real_escape_string($conn, $_POST['exitflow']);
    $admin_id = $_SESSION['user']['id'];
    $date = date('Y-m-d');
    $category = 'staff';


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['msg'] = "Invalid Email";
        echo 'Invalid Email';
        exit();
    }
    if ($surname == '') {
        $_SESSION['msg'] = "Surname is required";
        echo 'Surname is required';
        exit();
    }
    if ($fname == '') {
        $_SESSION['msg'] = "Firstname is required";
        echo 'Firstname is required';
        exit();
    }

    if ($phone == '') {
        $_SESSION['msg'] = "Phone is required";
        echo 'Phone is required';
        exit();
    }

    if ($employee_ID == '') {
        $_SESSION['msg'] = "Employee ID is required";
        echo 'Employee ID is required';
        exit();
    }

    $status = isAccountCreated($conn, $email);
    $status2 = isIDExist($conn, $employee_ID);

    if($status == 0)
    {
        $_SESSION['msg'] = "User Already Exist";
        echo 'User Already Exist';
        exit();
    }

    if($status2 == 0)
    {
        $_SESSION['msg'] = "User Employee ID Exist";
        echo "User Employee ID Exist";
        exit();
    }
    
    //$company = getcompany($conn);
    $uniqueID = $_SESSION['user']['uniqueID'];
    $companyId = $_SESSION['user']['companyId'];
    $imagename = isset($_FILES['image']) ? processImage($_FILES['image']) : '';
    $password = password_hash('selfservice', PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (uniqueID,name, fname, mname, email, phone_number, branch,employee_ID,category,company_name,first_time_loggin,date_created,position,grade,leave_flow,appraisal_flow,requisition_flow,cash_flow,dob,lga,sorigin,town,sresidence,on_hmo,hmo,hmo_number,hmo_plan,hmo_hospital,hmo_status,hmo_remarks,pension,pension_number,gender,admin_id,role,profile_image,marital_status,password,department, companyId, active)
      VALUES ('".$uniqueID."','".trim($surname)."', '".trim($fname)."', '".$mname."','".trim($email)."','".trim($phone)."','".$branch."','".trim($employee_ID)."','".$category."','".$_SESSION['user']['company_name']."','0','".date('Y-m-d')."','".$position."','".$grade."','".$leaveflow."','".$appraisalflow."','".$reqflow."','".$cashflow."','".$dob."','".$lga."','".$sorigin."','".$town."','".$sresidence."','".$on_hmo."','".$hmo."','".$hmo_number."','".$hmo_plan."','".$hmo_hospital."','".$hmo_status."','".$hmo_remarks."','".$pension."','".$pension_number."','".$gender."','".$admin_id."','".$role."','".$imagename."','".$marital_status."','".$password."','".$department."', '".$companyId."','1')";
      if (mysqli_query($conn, $sql)) {
          echo '1';
          $msg = "<div><p>Good Day,</p><p>Your Company Admin (".$_SESSION['user']['email'].") has created you on hrcore.ng. To participate in company affairs, kindly log In</p><p>Your login details is username : $email and Password : selfservice</p><a  style ='padding:7px;color:#fff;background-color:#4e73df;margin:10px;border-radius: 3px;text-decoration:none' href = 'http://www.hrcore.ng'>Log In to view</a></p></div>";
               if (filter_var($email, FILTER_VALIDATE_EMAIL) && $_SERVER['SERVER_NAME'] != 'localhost') {
                  process_data($conn,$email,$msg,'Account Created');
                }
          
      }
      else{
          echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      }
      
}
function getcompany($conn){
        $company = [];  
        $sql = "Select * from company where id = '".$_SESSION['user']['companyId']."'";
          $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result)> 0){
            while($row = mysqli_fetch_assoc($result)) {
              $company[] = $row;
               return $company[0]['company_name'];
        }


           
            
}
        
}
function processImage($image){
    $imagename = 'user_profile.png';
  	if(isset($image)){
       if($image['name'] == null) {
       	    $imagename = 'user_profile.png';
       }
       else {
           //$image = $_FILES['image'];
           $error = array(); 
           $file_ext = explode('.',$image['name'])[1];
           $img = uniqid().'_'.strtotime(date('H:m:s'));
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
             $imagename = $file_name;
           }else {
             $imagename = 'user_profile.png';
           }
       }
    }
    return $imagename;
  }
?>
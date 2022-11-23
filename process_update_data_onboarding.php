<?php
include 'connection.php';
session_start();
if(!isset($_SESSION['user']['id'])) header('location: login.php');
if(isset($_POST['submit'])){
    $staff_id = mysqli_real_escape_string($conn, $_POST['staff_id']);
    $surname = mysqli_real_escape_string($conn, $_POST['name']);
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $mname = mysqli_real_escape_string($conn, $_POST['mname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
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
    $exitflow = mysqli_real_escape_string($conn, $_POST['exitflow']);
    $admin_id = $_SESSION['user']['id'];
    $date = date('Y-m-d');
    //$category = 'staff';
    $company = getcompany($conn);
    
    
    $password = password_hash('selfservice', PASSWORD_DEFAULT);
    $sql = "UPDATE users SET name = '".$surname."', fname = '".$fname."', mname = '".$mname."', email = '".$email."', phone_number = '".$phone."', branch = '".$branch."',employee_ID='".$employee_ID."',company_name='".$company."',date_created='".date('Y-m-d')."',position='".$position."',leave_flow='".$leaveflow."',appraisal_flow='".$appraisalflow."',requisition_flow='".$reqflow."',cash_flow='".$cashflow."',dob='".$dob."',lga='".$lga."',sorigin='".$sorigin."',town='".$town."',sresidence='".$sresidence."',on_hmo='".$on_hmo."',hmo='".$hmo."',hmo_number='".$hmo_number."',hmo_plan='".$hmo_plan."',hmo_hospital='".$hmo_hospital."',hmo_status='".$hmo_status."',hmo_remarks='".$hmo_remarks."',pension='".$pension."',pension_number='".$pension_number."',gender='".$gender."',admin_id='".$admin_id."',role='".$role."',marital_status='".$marital_status."', exit_flow = '".$exitflow."',department = '".$department."'  where id = '".$staff_id."'";
      if (mysqli_query($conn, $sql)) {
          echo '1';
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
            }
          return $company[0]['company_name'];  
         }
         return '';
}
?>
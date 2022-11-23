<?php
 include "connection.php";
 session_start();
  if(!isset($_SESSION['user']['id'])) header("Location: login.php");
 $is_exist = 0;
 if(isset($_POST['submit'])){
 $branch_id = mysqli_real_escape_string($conn, $_POST['branch_id']);
 $department_id = mysqli_real_escape_string($conn, $_POST['department_id']);
 $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
 $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
 $middle_name = mysqli_real_escape_string($conn, $_POST['middle_name']);
 $employee_ID = mysqli_real_escape_string($conn, $_POST['employee_ID']);
 $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
 $status = mysqli_real_escape_string($conn, $_POST['status']);
 $gender = mysqli_real_escape_string($conn, $_POST['gender']);
 $blood_type = mysqli_real_escape_string($conn, $_POST['blood_type']);
 $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth']);
 $citizenship = mysqli_real_escape_string($conn, $_POST['citizenship']);
 $religion = mysqli_real_escape_string($conn, $_POST['religion']);
 $email = mysqli_real_escape_string($conn, $_POST['email']);
 if($branch_id == '') {
 	$_SESSION['msg'] = "Kindly input the employee branch";
 	header("Location: employee.php");
  return false;
 }
 if($department_id == '') {
 	$_SESSION['msg'] = "Kindly input the employee department";
 	header("Location: employee.php");
  return false;
 }
 if($first_name == '' || $last_name == '' || $employee_ID == '') {
 	$_SESSION['msg'] = "Be sure to input the employee first name, last name and employee ID";
 	header("Location: employee.php");
 }
 $query = "SELECT * from employee_info WHERE employee_ID = '$employee_ID'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
      $is_exist = 1;
    }
    if($is_exist == 1){
      $_SESSION['msg'] = "Employee already exist in the system";
       header("Location: employee.php");
       return false;
    }
    if($_SESSION['user']['category'] == 'admin') $admin_id = $_SESSION['user']['id'];
    else $admin_id = $_SESSION['user']['admin_id'];
    $sql = "INSERT INTO employee_info (branch_id, department_id, first_name, last_name, middle_name,date_of_birth,status,gender,blood_type,citizenship,place_of_birth,religion,employee_ID,date_created,admin_id,insert_by,date_updated,email)
          VALUES ('".$branch_id."', '".$department_id."', '".$first_name."','".$last_name."','".$middle_name."',
          '".$date_of_birth."','".$status."','".$gender."','".$blood_type."','".$citizenship."','".$place_of_birth."','".$religion."','".$employee_ID."','".date('Y-m-d')."','".$admin_id."','".$_SESSION['user']['id']."', '".date('Y-m-d')."','".$email."')";
            if (mysqli_query($conn, $sql)) {
              $_SESSION['msg'] = "New Employee Added";
              header("Location: employee.php");
            }else {
            	//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
               $_SESSION['msg'] = "Error while update account, please try again later";
               header("Location: employee.php");
          }
}
if(isset($_POST['update'])){
 $branch_id = mysqli_real_escape_string($conn, $_POST['branch_id']);
 $department_id = mysqli_real_escape_string($conn, $_POST['department_id']);
 $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
 $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
 $middle_name = mysqli_real_escape_string($conn, $_POST['middle_name']);
 $employee_ID = mysqli_real_escape_string($conn, $_POST['employee_ID']);
 $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);
 $status = mysqli_real_escape_string($conn, $_POST['status']);
 $gender = mysqli_real_escape_string($conn, $_POST['gender']);
 $blood_type = mysqli_real_escape_string($conn, $_POST['blood_type']);
 $place_of_birth = mysqli_real_escape_string($conn, $_POST['place_of_birth']);
 $citizenship = mysqli_real_escape_string($conn, $_POST['citizenship']);
 $religion = mysqli_real_escape_string($conn, $_POST['religion']);
 $email = mysqli_real_escape_string($conn, $_POST['email']);
 if($branch_id == '') {
  $_SESSION['msg'] = "Kindly input the employee branch";
  header("Location: employee.php");
 }
 if($department_id == '') {
  $_SESSION['msg'] = "Kindly input the employee department";
  header("Location: employee.php");
 }
 if($first_name == '' || $last_name == '' || $employee_ID == '') {
  $_SESSION['msg'] = "Be sure to input the employee first name, last name and employee ID";
  header("Location: employee.php");
 }
if($_SESSION['user']['category'] == 'admin') $admin_id = $_SESSION['user']['id'];
else $admin_id = $_SESSION['user']['admin_id'];
$sql = "UPDATE employee_info SET branch_id = '".$branch."', department_id = '".$department_id."', first_name = '".$first_name."', last_name = '".$last_name."', middle_name = '".$middle_name."', date_of_birth = '".$date_of_birth."', status = '".$status."', gender = '".$gender."', blood_type = '".$blood_type."',$email = '".$email."', citizenship = '".$citizenship."', place_of_birth = '".$place_of_birth."', religion = '".$religion."', updated_by = '".$_SESSION['user']['id']."',date_updated = '".date('Y-m-d')."' WHERE employee_ID = '".$employee_ID."'";
        if (mysqli_query($conn, $sql)) {
          $_SESSION['msg'] = "Employee Information Updated";
          header("Location: employee.php");
        }else {
          echo "Error: " . $sql . "<br>" . mysqli_error($conn);
           //$_SESSION['msg'] = "Error while update account, please try again later";
           //header("Location: /selfservice/employee.php");
      }
}

?>
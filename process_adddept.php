<?php
include "connection.php";
session_start();
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
if(isset($_POST['submit'])){
  $admin_id;
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $id = mysqli_real_escape_string($conn, $_POST['branch_id']);
  if($_SESSION['user']['category'] == 'staff') $admin_id = $_SESSION['user']['admin_id'];
  if($_SESSION['user']['category'] == 'admin') $admin_id = $_SESSION['user']['id'];
  $query = "SELECT * from departments WHERE dept = '$name' AND company_id = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
      $_SESSION['msg'] = "This department is already added";
      header("Location: adddepartment.php");
      return false;
    }
  $sql = "INSERT INTO departments (branch_id, dept, date_created, admin_id, insert_by, company_id)
  VALUES ('', '".$name."', '".date('Y-m-d')."', '".$admin_id."','".$_SESSION['user']['id']."', '".$_SESSION['user']['companyId']."')";
  if (mysqli_query($conn,$sql ) === TRUE) {
      $_SESSION['msg'] = "New department added";
      header("Location: adddepartment.php");
  } else {
      //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
     $_SESSION['msg'] = "Error updating data, kindly try again later";
     header("Location: adddepartment.php");
  }
}
if(isset($_POST['update'])){
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  //$id = mysqli_real_escape_string($conn, $_POST['branch_id']);
  $dept_id = mysqli_real_escape_string($conn, $_POST['id']);
   $sql = "UPDATE departments SET dept = '".$name."', date_created = '".date('Y-m-d')."' WHERE id = '".$dept_id."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Record updated successfully";
            header("Location: department.php");
        } else {
            //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $_SESSION['msg'] = "Error updating data, kindly try again later";
            header("Location: department.php");
        }
}
?>
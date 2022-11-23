<?php
include "connection.php";
session_start();
 if(!isset($_SESSION['user']['id']) || $_SESSION['user']['id'] == '') header("Location: login.php");
if(isset($_POST['submit'])){
  $admin_id;
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $query = "SELECT * from levels WHERE name = '$name' AND company_id = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
      $_SESSION['msg'] = "This Level is already added";
      header("Location: addlevel.php");
      return false;
    }

  $sql = "INSERT INTO levels (name, date_created, company_id, insert_by)
  VALUES ('".$name."','".date('Y-m-d')."', '".$_SESSION['user']['companyId']."','".$_SESSION['user']['id']."')";
  if (mysqli_query($conn,$sql ) === TRUE) {
      $_SESSION['msg'] = "New Level added";
      header("Location: addlevel.php");
  } else {
      //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
     $_SESSION['msg'] = "Error updating data, kindly try again later";
     header("Location: addlevel.php");
  }
}
if(isset($_POST['update'])){
  $name = mysqli_real_escape_string($conn, $_POST['name']);
   $sql = "UPDATE levels SET name = '".$name."' WHERE id = '".$_POST['level_id']."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Record updated successfully";
            header("Location: level.php");
        } else {
            $_SESSION['msg'] = "Error updating data, kindly try again later";
            header("Location: level.php");
        }
}
?>
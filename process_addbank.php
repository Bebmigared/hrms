<?php
include "connection.php";
session_start();
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
if(isset($_POST['submit'])){
$name = mysqli_real_escape_string($conn, $_POST['name']);
  $sql = "INSERT INTO bank (bank, staff_id)
  VALUES ('".$name."',  '".$_SESSION['user']['id']."')";
  if (mysqli_query($conn,$sql ) === TRUE) {
      $_SESSION['msg'] = "New Bank added";
      header("Location: add_bank.php");
  } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
     $_SESSION['msg'] = "Error updating data, kindly try again later";
     header("Location: add_bank.php");
  }
}
if(isset($_POST['update'])){
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  //$id = mysqli_real_escape_string($conn, $_POST['branch_id']);
  $id = mysqli_real_escape_string($conn, $_POST['id']);
   $sql = "UPDATE bank SET dept = '".$name."' WHERE id = '".$id."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Record updated successfully";
            header("Location: bank.php");
        } else {
            //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $_SESSION['msg'] = "Error updating data, kindly try again later";
            header("Location: bank.php");
        }
}
?>
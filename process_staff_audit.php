<?php
include "connection.php";
session_start();
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
if(isset($_POST['submit'])){
	$month = mysqli_real_escape_string($conn, $_POST['month']);
	$year = mysqli_real_escape_string($conn, $_POST['year']);
	$staff_id = $_SESSION['user']['id'];
	 $sql = "INSERT INTO staff_audit (month, year,staff_id, admin_id)
      VALUES ('".$month."', '".$year."','".$staff_id."','".$_SESSION['user']['id']."')";
        if (mysqli_query($conn, $sql)) {
          $_SESSION['msg'] = "Your record has been updated";
          $last_id = $conn->insert_id;
        }else {
           $_SESSION['msg'] = "Error while update account, please try again later";
       }
       header("Location: begin_audit");
}
if(isset($_POST['audit'])){
	$audit_id = mysqli_real_escape_string($conn, $_POST['audit_id']);
	$sql = "INSERT INTO staff_audit_replies (audit_id,staff_id, admin_id)
      VALUES ('".$audit_id."', '".$_SESSION['user']['id']."','".$_SESSION['user']['admin_id']."')";
        if (mysqli_query($conn, $sql)) {
          $_SESSION['msg'] = "Your record has been updated";
          $last_id = $conn->insert_id;
        }else {
           $_SESSION['msg'] = "Error while update account, please try again later";
       }
       header("Location: staff_audit");
}

?>
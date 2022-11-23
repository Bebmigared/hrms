<?php
 include 'connection.php';
 session_start();
  if(!isset($_SESSION['user']['id'])) header("Location: login.php");
 if(isset($_GET['leave_id']) && isset($_GET['staff_id'])){
 	if($_GET['leave_id'] != '' && $_GET['staff_id'] != ''){
 		$_SESSION['leave_id'] = base64_decode($_GET['leave_id']);
 		$_SESSION['staff_id'] = base64_decode($_GET['staff_id']);
 		//echo $_SESSION['staff_id'];
 		if($_SERVER['SERVER_NAME'] != 'localhost')
 		  header("Location: /approval_leave_view.php");
 		else
 		  header("Location: /newhrcore/approval_leave_view.php");	

 	}
 }
?>
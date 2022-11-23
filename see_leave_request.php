<?php
  include 'connection.php';
  session_start();
  if(isset($_GET['leave_id'])){
  	$leave_id = mysqli_real_escape_string($conn, $_GET['leave_id']);
  	$_SESSION['leave_id'] = base64_decode($leave_id);
  	//echo $_SESSION['leave_id'];
  	
  	if($_SERVER['SERVER_NAME'] == 'localhost')
  	  header("Location: /newhrcore/view_leave_flow.php");
  	else
  	  header("Location: /view_leave_flow.php");	
  }
?>
<?php
 include 'connection.php';
 session_start();
  if(!isset($_SESSION['user']['id'])) header("Location: login.php");
 //echo $_GET['staff_id'];
 if(isset($_GET['requestitem_id']) && isset($_GET['staff_id'])){
 	if($_GET['requestitem_id'] != '' && $_GET['staff_id'] != ''){
 		$_SESSION['requestitem_id'] = base64_decode($_GET['requestitem_id']);
 		$_SESSION['staff_id'] = base64_decode($_GET['staff_id']);
 		//echo $_SESSION['staff_id'];
 		if($_SERVER['SERVER_NAME'] != 'localhost')
 		  header("Location: /approval_requisition_view.php");
 		else
 		  header("Location: /newhrcore/approval_requisition_view.php");	 
 	}
 }
?>
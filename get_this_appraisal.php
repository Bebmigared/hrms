<?php
  include 'connection.php';
  session_start();
   if(!isset($_SESSION['user']['id'])) header("Location: login.php");
  if(isset($_GET['appraisal_id']) && isset($_GET['staff_id'])){
  	if($_GET['appraisal_id'] != '' && $_GET['staff_id'] != ''){
  		$_SESSION['appraisal_id'] = base64_decode($_GET['appraisal_id']);
  		$_SESSION['staff_id'] = base64_decode($_GET['staff_id']);

      if($_SERVER['SERVER_NAME'] != 'localhost')
  		   header("Location: /staff_appraisal_view.php");
      else
         header("Location: /newhrcore/staff_appraisal_view.php"); 
  	}
  }
  ?>
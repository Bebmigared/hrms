<?php
 session_start();
  if(!isset($_SESSION['user']['id'])) header("Location: login.php");
 if(isset($_GET['appraisal_id']) && $_GET['appraisal_id'] != ''){
    $appraisal_id = base64_decode($_GET['appraisal_id']);
    $_SESSION['appraisal_id'] = $appraisal_id;
    //echo $appraisal_id;
    if($_SERVER['SERVER_NAME'] != 'localhost')
       header("Location: /staff_appraisal.php");
    else
       header("Location: /newhrcore/staff_appraisal.php");
 }
?>
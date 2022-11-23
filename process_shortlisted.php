<?php
include "connection.php";
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';
require 'class.phpmailer.php';
require 'class.smtp.php';
if(isset($_FILES['file'])){
	if($_FILES['file']['name'] == '') {
      $_SESSION['msg'] = "No file uploaded";  
      header("location: replyrequest.php"); 
      return false;   
    }else{
       $ro_name = mysqli_real_escape_string($conn, $_POST['ro_name']);
       $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
       $requestID = mysqli_real_escape_string($conn, $_POST['requestID']);
       $fileuploadedbefore = mysqli_real_escape_string($conn, $_POST['filename']);
       if($ro_name == ''){
   		  $_SESSION['msg'] = "Recruitment Officer Name is required";  
	      header("location: replyrequest.php"); 
	      return false; 
       }
       $errors = ''; 
       $file_ext = explode('.',$_FILES['file']['name'])[1];
       $extName = explode('.',$_FILES['file']['name'])[0];
       $filename = $extName.'_'.time();
       $file_name = $filename.'.'.$file_ext;
       $file_size = $_FILES['file']['size'];
       $file_tmp = $_FILES['file']['tmp_name'];
       $file_type = $_FILES['file']['type'];
       $extensions = array('xlsx','xls');
       if(in_array($file_ext,$extensions) === false){
        $errors .= "<p>extension not allowed, please select an Excel file Only.</p>";
       }
       if($file_size > 209752){
        $errors .= "<p>File size too large</p>";
       }
       if(empty($errors)==true){
       	 move_uploaded_file($file_tmp,"shortlist/".$file_name);
       	 if($fileuploadedbefore == ''){
            $sql = "INSERT INTO shortlists (RequestID, filename, date_created, ro_name,admin_id) VALUES ('".$requestID."','".$file_name."','".date('Y-m-d')."','".$ro_name."','".$admin_id."')";
	          if (mysqli_query($conn, $sql)) {
	            $_SESSION['msg'] = "File Uploaded Successfully...";
	            updateRequest($conn,$requestID);
	            $user = getPM($conn,$admin_id);
	            $msg = "<p>Hi ".$user[0]['name'].", The Recruitment Officer has Shortlisted some candidates as Requested. Kindly login to your Account to view.</p><p><a href ='https://hrcore.ng/outsourcing/login'>Click on this Link to Login</a></p>";
	            sendmail($msg,'Shortlisted Candidates',$email);

	          }else {
	          	$_SESSION['msg'] = 'Error updating data';
	          }
	      }else {
	      	$sql = "UPDATE shortlists SET filename = '".$file_name."' WHERE requestID = '".$requestID."'";
	          if (mysqli_query($conn, $sql)) {
	            $_SESSION['msg'] = "File Uploaded Successfully...";
	            $user = getPM($conn,$admin_id);
	            $msg = "<p>Hi ".$user[0]['name'].", The Recruitment Officer has Shortlisted some candidates as Requested. Kindly login to your Account to view.</p><p><a href ='https://hrcore.ng/outsourcing/login'>Click on this Link to Login</a></p>";
	            sendmail($msg,'Shortlisted Candidates',$user[0]['email']);
	          }else {
	          	$_SESSION['msg'] = 'Error updating data';
	          }
	      }
         

       }else {
            $_SESSION['msg'] = $errors;
       }
       header("location: replyrequest.php");
    }
}else{
	$_SESSION['msg'] = "No file uploaded";  
      header("location: replyrequest.php"); 
}
function updateRequest($conn,$requestID){
	$sql = "UPDATE recruitment SET stage = 'Candidate Shortlisted' WHERE requestID = '".$requestID."'";
	          if (mysqli_query($conn, $sql)) {
	            //$_SESSION['msg'] = "File Uploaded Successfully...";
	          }else {
	          	//$_SESSION['msg'] = 'Error updating data';
	          }
	return true;          
}
function getPM($conn,$admin_id){
	$sql ="SELECT * from users WHERE id = '".$admin_id."'";
	$result = mysqli_query($conn, $sql);
	  if(mysqli_num_rows($result)> 0){
	      while($row = mysqli_fetch_assoc($result)) {
	        $user[] = $row;
	      }
	  }
	return $user;
}
function sendmail($msg,$subject,$pm_email){
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 0;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host = 'hrcore.ng';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                                   // Enable SMTP authentication
    $mail->Username = 'ess@hrcore.ng';                     // SMTP username
    $mail->Password = 'wROS+cb63zQ(';                               // SMTP password
    $mail->SMTPSecure = 'tls';  
    $mail->SMTPAutoTLS = false;   
    $mail->SMTPOptions = array(
	    'ssl' => array(
	        'verify_peer' => false,
	        'verify_peer_name' => false,
	        'allow_self_signed' => true
	    )
	);                             // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('ess@hrcore.ng', 'Recruitment');
    //$mail->addAddress($to, 'ESS');     // Add a recipient
    for($r = 0; $r < count($emails); $r++){
        $mail->addAddress($emails[$r]);
    }
    $mail->addCC($pm_email);
    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $msg;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    return true;
	} 
catch (Exception $e) { return false; }
  }	
?>
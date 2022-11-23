<?php
include "connection.php";
 include "process_email.php";
 //include "e_mail.php";
 $msg = "234";
 $email = "omotayo@gmail.com";
 //sendmail('omotayoogunrinde@gmail.com', $msg,'Appraisal')
 //process_data($conn,'omotayoogunrinde@gmail.com',$msg,'Appraisal');
 if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo("$email is a valid email address");
}
?>
<?php
require_once('mailer/class.smtp.php');
require_once('mailer/class.phpmailer.php');
//----------------------------------------------
// Send an e-mail. Returns true if successful 
//
//   $to - destination
//   $nameto - destination name
//   $subject - e-mail subject
//   $message - HTML e-mail body
//   altmess - text alternative for HTML.
//----------------------------------------------
//function sendmail($to,$nameto,$subject,$message,$altmess)  {
  $subject = "mesaage";
  $altmess = "oay";
  $to = "omotayoogunrinde@gmail.com";
  $message = "okay boy";
  $from  = "yourcontact@yourdomain.com";
  $namefrom = "yourname";
  $nameto = "sert";
  $mail = new PHPMailer();  
  $mail->CharSet = 'UTF-8';
  $mail->isSMTP();   // by SMTP
  $mail->SMTPAuth   = true;   // user and password
  $mail->Host       = "stmp.gmail.com";
  $mail->Port       = 465;
  $mail->Username   = 'ogunrindeomotayo@gmail.com';  
  $mail->Password   = "christianlife";
  $mail->SMTPSecure = "ssl";    // options: 'ssl', 'tls' , ''  
  $mail->setFrom($from,$namefrom);   // From (origin)
  $mail->addCC($from,$namefrom);      // There is also addBCC
  $mail->Subject  = $subject;
  $mail->AltBody  = $altmess;
  $mail->Body = $message;
  $mail->isHTML();   // Set HTML type
//$mail->addAttachment("attachment");  
  $mail->addAddress($to, $nameto);
  if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
//}
?>
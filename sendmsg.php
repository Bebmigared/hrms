<?php
//include "connection.php";
include ('fpdf/fpdf.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';
session_start();
require 'class.phpmailer.php'; // path to the PHPMailer class
require 'class.smtp.php';

$msg = "<p>Dear Patrick,</p><p style='margin-top:10px;'>Please find attached the approval for your <b>Annual Leave</b> application.</p><p style='margin-top:10px;'>Kindly contact your People Manager at ICS Outsourcing Limited if you have any concern with respect to this approval.</p><p style='margin-top:10px;'>Regards,</p><p style='margin-top:5px;'>Leave Management Desk,</p><p>ICS Outsourcing Limited.</p>";
//sendmail('ogunrindeomotayo@gmail.com',$msg,'Request Demo');

function sendmail($to,$msg,$subject){
// Instantiation and passing `true` enables exceptions
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
    $mail->setFrom('ess@hrcore.ng', 'Request a Demo');
    $mail->addAddress($to, 'ESS');     // Add a recipient
    for($r = 0; $r < count($approvals); $r++){
        $mail->addCC($approvals[$r]);
    }
    //$mail->addCC('leave@icsoutsourcing.com');
    //$mail->addCC('ogunrindeomotayo@gmail.com');
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    // Attachments
    //$mail->addAttachment($dest);         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $msg;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    /*echo 'Message has been sent';
    if($subject == 'Leave Request'){
        echo 'Message has been sent';
        $_SESSION['msg'] = "Your leave request is under processing";
        header("Location: staff_leave_request.php");
    }*/
    return true;
} catch (Exception $e) {
    /*echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    if($subject == 'Leave Request'){
        $_SESSION['msg'] = "Your leave request is under processing";
        header("Location: staff_leave_request.php");
    }*/
    return false;
}
}  
//header("Location: /outsourcing/downloadpdf.php/?file=leave_request&filename=".$pdf_name."")
?>
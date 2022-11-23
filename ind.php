<?php
include "connection.php";


if(isset($_POST["email"]) && (!empty($_POST["email"]))){
$email = $_POST["email"];
$email = filter_var($email, FILTER_SANITIZE_EMAIL);
$email = filter_var($email, FILTER_VALIDATE_EMAIL);
if (!$email) {
   $error .="<p>Invalid email address please type a valid email address!</p>";
   }else{
   $sel_query = "SELECT * FROM `users` WHERE email='".$email."'";
   $results = mysqli_query($conn,$sel_query);
   $row = mysqli_num_rows($results);
   if ($row==""){
   $error .= "<p>No user is registered with this email address!</p>";
   }
  }
   if($error!=""){
   echo "<div class='error'>".$error."</div>
   <br /><a href='javascript:history.go(-2)'>Go Back</a>";
   }else{
       
  $expFormat = mktime(
  date("H"), date("i"), date("s"), date("m") ,date("d")+1, date("Y")
  );
  $expDate = date("Y-m-d H:i:s",$expFormat);
  
  $key = md5(2418*2+$email);
  $addKey = substr(md5(uniqid(rand(),1)),3,10);
  $keys = $key . $addKey;
  
  
//     echo $keys, $expDate, $email;
//   exit();


// Insert Temp Table



$sql = "INSERT INTO `password_reset_temp`( `email`, `key`, `expDate`) VALUES ('$email', '$keys', '$expDate')";
    $results = mysqli_query($conn, $sql);

$output='<p>Dear user,</p>';
$output.='<p>Please click on the following link to reset your password.</p>';
$output.='<p>-------------------------------------------------------------</p>';
$output.='<p><a href="https://ics.hrcore.ng/paa.php?
key='.$keys.'&email='.$email.'&action=reset" target="_blank">
https://ics.hrcore.ng/paa.php
?key='.$keys.'&email='.$email.'&action=reset</a></p>';		
$output.='<p>-------------------------------------------------------------</p>';
$output.='<p>Please be sure to copy the entire link into your browser.
The link will expire after 1 day for security reason.</p>';
$output.='<p>If you did not request this forgotten password email, no action 
is needed, your password will not be reset. However, you may want to log into 
your account and change your security password as someone may have guessed it.</p>';   	
$output.='<p>Thanks,</p>';
$output.='<p>BCT Team</p>';
$body = $output; 
$subject = "Password Recovery ";

$email_to = $email;
$fromserver = "noreply@ics.hrcore.ng"; 
require("PHPMailer/src/PHPMailer.php");

require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer\PHPMailer\PHPMailer();
$mail->IsSMTP();
$mail->Host = "hrcore.ng"; // Enter your host here
$mail->SMTPAuth = true;
$mail->Username = "info@hrcore.ng"; // Enter your email here
$mail->Password = "5Fv8ySSMSBx)"; //Enter your password here
$mail->Port = 587;
$mail->IsHTML(true);
$mail->From = "info@hrcore.ng";
$mail->FromName = "info@hrcore.ng";
$mail->Sender = $fromserver; // indicates ReturnPath header
$mail->Subject = $subject;
$mail->Body = $body;
$mail->AddAddress($email_to);
if(!$mail->Send()){
echo "Mailer Error: " . $mail->ErrorInfo;
}else{
echo "<div class='error'>
<p>An email has been sent to you with instructions on how to reset your password.</p>
</div><br /><br /><br />";
	}
   }
}else{
?>
<div style='display:flex; justify-content:center

	;background: #87defa;
	;font-size: 1.1em;
	;font-family: sans-serif;



h2.form-title: 
	text-align: center;

input:
	display: block;
	;box-sizing: border-box;
	;width: 100%;
	padding: 8px;


form button:
	width: 100%;
	;border: none;
	;color: white;
	;background: #3b5998;
	;padding: 15px;
	border-radius: 5px;


'>
<form method="post" action="" name="reset"><br /><br />
<label><strong>Enter Your Email Address:</strong></label><br /><br />
<input type="email" name="email" placeholder="username@email.com" />
<br /><br />
<input type="submit" value="Reset Password"/>
</form>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php } ?>
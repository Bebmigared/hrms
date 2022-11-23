 <?php
include "connection.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';
require 'class.phpmailer.php';
require 'class.smtp.php';
$url = 'http://localhost';
//$url = 'https://hrcore.ng';
function notify_ro_doc($conn,$pm_email,$requestID){
    $sql = "SELECT * from recruitment where requestID = '".$requestID."'";
    $result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($result)> 0){
          while($row = mysqli_fetch_assoc($result)) {
                $request[] = $row;
            }
      }
    $sql = "SELECT * from ro where location = '".$request[0]['location']."'";
    $result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($result)> 0){
          while($row = mysqli_fetch_assoc($result)) {
                $emails[] = $row['email'];
            }
      } 
    $msg = "<div style='margin:10;font-size:14px;'><p>Good day,</p><p>Kindly begin documentation for the following candidates.</p><p>To view these candidates <a href = '".$GLOBALS['url']."/outsourcing/begin_doc.php/?q=".base64_encode($requestID)."' style='color:red'>Click on this Link.</a></p></div>";
    $sql = "UPDATE recruitment SET stage = 'Begin Documentation', doc_phase ='Starting', begin_date = '".date('Y-m-d')."' where requestID = '".$requestID."'";
     if (mysqli_query($conn, $sql)) {
            return sendmail($emails,$msg,'Begin Documentation',$pm_email);
        }else {
          
       }    
}
function sendmail_lagosRO($conn,$requestID){
    $sql = "SELECT * from recruitment where requestID = '".$requestID."'";
    $result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($result)> 0){
          while($row = mysqli_fetch_assoc($result)) {
                $request[] = $row;
            }
      }
    $sql = "SELECT * from users where id = '".$request[0]['admin_id']."'";
    $result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($result)> 0){
          while($row = mysqli_fetch_assoc($result)) {
                $pm_email = $row['email'];
            }
      }  
    $sql = "SELECT * from ro where location = 'lagos'";
    $result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($result)> 0){
          while($row = mysqli_fetch_assoc($result)) {
                $emails[] = $row['email'];
            }
      } 
    $msg = "<div style='margin:10;font-size:14px;'><p>Good day,</p><p>The Recruitment Officer for ".$request[0]['location']." has uploaded some documentation forms.</p><p>To view these documents <a href = '".$GLOBALS['url']."/outsourcing/view_doc.php/?q=".base64_encode($requestID)."' style='color:red'>Click on this Link.</a></p></div>";
    //return print_r($msg);
    return sendmail($emails,$msg,'Documentation',$pm_email);
}
function notify_ro($conn,$location, $job_title, $job_description, $priority_level,$qualification,$pm_email,$requestID){
   $emails = [];	
   $sql = "SELECT * from ro where location = '".$location."'";
	$result = mysqli_query($conn, $sql);
	  if(mysqli_num_rows($result)> 0){
	      while($row = mysqli_fetch_assoc($result)) {
	            $emails[] = $row['email'];
	  }
	  $msg = "<div style='margin:10;font-size:14px;'><p>Good day,</p><p>A request has been made by the People Manager to shortlist qualified candidate for a Job Request. The details of the request is given below: </p><p><span style='font-weight:'800'>Job Title</span>: ".$job_title."<p><p><span style='font-weight:'800'>Job Description</span>: ".$job_description."</p><p><span style='font-weight:'800'>Qualification Required</span>: ".$qualification."</p><p><span style='font-weight:'800'>Priority Level</span>: ".$priority_level."</p><p>To respond to this request <a href = '".$GLOBALS['url']."/outsourcing/uploadlist/?q=".base64_encode($requestID)."' style='color:red'>Click on this Link.</a></p></div>";
     //return $emails;
	 return sendmail($emails,$msg,'Job Request',$pm_email); 
	}
} 
function notify_client($conn,$email,$pm_email,$filename){
    $msg = "<div style='margin:10;font-size:14px;'><p>Good day,</p><p>Please find the list of shortlist candidate as requested.</p></div>";
     return sendmail($email,$pm_email,'Shortlisted Candidate',$filename,$msg);
}
function share_link_with_employee($conn,$email){
    $msg = "<div style='margin:10;font-size:14px;'><p>Good day,</p><p>Please click on the link to complete your HMO form.</p><p><a href = '".$GLOBALS['url']."/outsourcing/hmoform.php' style='color:red'>Click on this Link.</a></p></div>";
     return sendlinkmail($email,'Link to HMO form',$msg);
}
function notify_ro_interview($conn,$location, $date, $time,$information,$pm_email,$requestID,$venue,$contact_person){
    $emails = [];   
    $sql = "SELECT * from ro where location = '".$location."'";
    $result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($result)> 0){
        while($row = mysqli_fetch_assoc($result)) {
            $emails[] = $row['email'];
        }
    }
    $msg = "<div style='margin:10;font-size:14px;'><p>Good day,</p><p>A request has been made by the People Manager to invite the candidate in the link below for an interview. The details of the interview is given below: </p><p><span style='font-weight:'800'>Interview Date</span>: ".$date."<p><p><span style='font-weight:'800'>Interview Time</span>: ".$time."</p><p><span style='font-weight:'800'>Other Information</span>: ".$information."</p><p><span style='font-weight:'800'>Venue</span>: ".$venue."</p><p><span style='font-weight:'800'>Contact Person</span>: ".$contact_person."</p><p>To respond to this request <a href = '".$GLOBALS['url']."/outsourcing/invite/?q=".base64_encode($requestID)."' style='color:red'>Click on this Link.</a></p></div>";
    return sendmail($emails,$msg,'Invite Candidate for Interview',$pm_email); 
}
function sendclientmail($email,$pm_email,$subject,$filename,$msg){
    $mail = new PHPMailer(true);

    try {
        $dest = 'shortlist/'.$filename;
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
        $mail->addAddress($email);
        $mail->addCC($pm_email);
        $mail->addAttachment($dest);
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
function sendmail($emails,$msg,$subject,$pm_email){
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
        $mail->setFrom('ess@hrcore.ng', 'HRCORE');
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
function sendlinkmail($email,$subject,$msg){
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
        $mail->setFrom('ess@hrcore.ng', 'HRCORE');
        //$mail->addAddress($to, 'ESS');     // Add a recipient
        $mail->addAddress($email);
        //$mail->addCC($pm_email);
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
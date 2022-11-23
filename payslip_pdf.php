<?php
include "connection.php";
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


class PDF extends FPDF
{
// Load data
function LoadData($file)
{
    // Read file lines
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
        $data[] = explode(';',trim($line));
    return $data;
}

// Simple table
function BasicTable($header, $data)
{
    // Header
    foreach($header as $col)
        $this->Cell(40,7,$col,1);
    $this->Ln();
    // Data
    foreach($data as $row)
    {
        foreach($row as $col)
            $this->Cell(40,6,$col,1);
        $this->Ln();
    }
}

// Better table
function ImprovedTable($header, $data)
{
    // Column widths
    $w = array(40, 35, 40, 45);
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Data
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR');
        $this->Cell($w[1],6,$row[1],'LR');
        $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
        $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
        $this->Ln();
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}

// Colored table
function FancyTable($conn,$header, $data,$dest)
{
    $query = "SELECT * from branches WHERE id = '".$data['branch_id']."'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $branch[] = $row;
      }
    }
    $query = "SELECT * from users WHERE employee_ID = '".$data['employee_ID']."'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
      }
    }
    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Header
    $w = array(70, 70);
    $p = array(70, 45,55,25);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],10,$header[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Data
    $fill = false;
    $r = 0;
    $branchname = isset($branch[0]['name']) ? $branch[0]['name'] : '';
        $this->Cell($w[0],10,'EMP. CODE: '.$data['employee_ID'].'','LR',0,'L',$fill);
        $this->Cell($w[1],10,'PAYMODE',1,1,'L',$fill);
        
        $this->Cell($w[0],10,'EMP. NAME: '.$data['first_name'].' '.$data['last_name'].'',1,0,'L',$fill);
        $this->Cell($w[1],10,'Grade',1,1,'L',$fill);
        
        $this->Cell($w[0],10,'DEPARTMENT: '.$data['first_name'].'',1,0,'L',$fill);
        $this->Cell($w[1],10,'CATEGORY: EMPLOYEE',1,1,'L',$fill);
        
        $this->Cell($w[0],10,'LOCATION: '.$branchname.'',1,0,'L',$fill);
        $this->Cell($w[1],10,'POSITION: EMPLOYEE',1,1,'L',$fill);
         $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont('');
        
        $this->Cell($p[0],10,'ALLOWANCES/EARNING',1,0,'L',$fill);
        $this->Cell($p[1],10,'NGN',1,0,'L',$fill);
        $this->Cell($p[2],10,'DEDUCTION',1,0,'L',$fill);
        $this->Cell($p[3],10,'NGN',1,1,'L',$fill);
        
        
        $this->Cell($p[0],10,'BASIC SALARY',1,0,'L',$fill);
        $this->Cell($p[1],10,''.number_format($data['basic_salary']).'',1,0,'L',$fill);
        $this->Cell($p[2],10,'PENSION(EMPLOYER)',1,0,'L',$fill);
        $this->Cell($p[3],10,''.number_format($data['pension_company']).'',1,1,'L',$fill);
        
        $this->Cell($p[0],10,'HOUSING',1,0,'L',$fill);
        $this->Cell($p[1],10,''.number_format($data['housing']).'',1,0,'L',$fill);
        $this->Cell($p[2],10,'NTF',1,0,'L',$fill);
        $this->Cell($p[3],10,''.number_format($data['NTF']).'',1,1,'L',$fill);
        
        $this->Cell($p[0],10,'TRANSPORT',1,0,'L',$fill);
        $this->Cell($p[1],10,''.number_format($data['transport']).'',1,0,'L',$fill);
        $this->Cell($p[2],10,'ECA',1,0,'L',$fill);
        $this->Cell($p[3],10,''.number_format($data['ECA']).'',1,1,'L',$fill);
        
        $this->Cell($p[0],10,'UTILITY',1,0,'L',$fill);
        $this->Cell($p[1],10,''.number_format($data['utility']).'',1,0,'L',$fill);
        $this->Cell($p[2],10,'ITF',1,0,'L',$fill);
        $this->Cell($p[3],10,''.number_format($data['ITF']).'',1,1,'L',$fill);
        
        $this->Cell($p[0],10,'EDUCATION',1,0,'L',$fill);
        $this->Cell($p[1],10,''.number_format($data['education']).'',1,0,'L',$fill);
        $this->Cell($p[2],10,'GLI',1,0,'L',$fill);
        $this->Cell($p[3],10,''.number_format($data['GLI']).'',1,1,'L',$fill);
        
        $this->Cell($p[0],10,'ENTERTAINMENT',1,0,'L',$fill);
        $this->Cell($p[1],10,''.number_format($data['entertainment']).'',1,0,'L',$fill);
        $this->Cell($p[2],10,'TAX',1,0,'L',$fill);
        $this->Cell($p[3],10,''.number_format($data['tax']).'',1,1,'L',$fill);
        
        
        $this->Cell($p[0],10,'ITF',1,0,'L',$fill);
        $this->Cell($p[1],10,''.number_format($data['ITF']).'',1,0,'L',$fill);
        $this->Cell($p[2],10,'TOTAL DEDUCTION',1,0,'L',$fill);
        $this->Cell($p[3],10,'',1,1,'L',$fill);
        
        $this->Cell($p[0],10,'FURNITURE',1,0,'L',$fill);
        $this->Cell($p[1],10,''.number_format($data['furniture']).'',1,0,'L',$fill);
        $this->Cell($p[2],10,'',1,0,'L',$fill);
        $this->Cell($p[3],10,'',1,1,'L',$fill);
        
        $this->Cell($p[0],10,'QUARTERLY ALLOWANCE',1,0,'L',$fill);
        $this->Cell($p[1],10,''.number_format($data['q_allowance']).'',1,0,'L',$fill);
        $this->Cell($p[2],10,'',1,0,'L',$fill);
        $this->Cell($p[3],10,'',1,1,'L',$fill);
        
        
        $this->Cell($p[0],10,'LEAVE',1,0,'L',$fill);
        $this->Cell($p[1],10,''.number_format($data['leave_bonus']).'',1,0,'L',$fill);
        $this->Cell($p[2],10,'',1,0,'L',$fill);
        $this->Cell($p[3],10,'',1,1,'L',$fill);
        
        $this->Cell($p[0],10,'GROSS SALARY',1,0,'L',$fill);
        $this->Cell($p[1],10,''.number_format($data['gross']).'',1,0,'L',$fill);
        $this->Cell($p[2],10,'',1,0,'L',$fill);
        $this->Cell($p[3],10,'',1,1,'L',$fill);
        
        $this->Cell($p[0],10,'NET SALARY',1,0,'L',$fill);
        $this->Cell($p[1],10,''.number_format($data['NET']).'',1,0,'L',$fill);
        $this->Cell($p[2],10,'',1,0,'L',$fill);
        $this->Cell($p[3],10,'',1,0,'LR',$fill);
        
        
        
        
        
        //$this->Cell($p[3],10,'','LR',0,'R',$fill);
    
        $this->Ln();
        $fill = !$fill;
    //}
    // Closing line
    //$data['email'] = 'ogunrindeomotayo@gmail.com';
    $this->Cell(array_sum($w),0,'','T');
    $this->Output('F', $dest);
    if (filter_var($users[0]['email'], FILTER_VALIDATE_EMAIL)) {
        $msg = 'Please find attached your payslip for the month of  '.date('F').' '.date('Y').'';
        //if(isset($users[0]['email']) && $users[0]['email'] != '')
            sendmail($users[0]['email'],$msg,'Payslip',$dest,$copyadmin);
    }
    
}
}
//get data
if(isset($_POST['submit'])){
    $employee = [];
    $sendto = $_POST['slipbranch'];
    $copyadmin = $_POST['radios'];
    $admin_id = $admin_id = $_SESSION['user']['id'];
    if($sendto == 'all'){
        $query = "SELECT employee_info.branch_id, employee_info.email,employee_info.first_name, employee_info.last_name, employee_info.employee_ID, employee_payroll_data.basic_salary,employee_payroll_data.housing, employee_payroll_data.transport,employee_payroll_data.lunch,employee_payroll_data.utility,employee_payroll_data.education,employee_payroll_data.entertainment,employee_payroll_data.HMO,employee_payroll_data.leave_bonus,employee_payroll_data.xmas,employee_payroll_data.pension_company,employee_payroll_data.pension_earning,employee_payroll_data.tax,employee_payroll_data.service_charge,employee_payroll_data.VAT,employee_payroll_data.gross,employee_payroll_data.NET FROM employee_info LEFT JOIN employee_payroll_data ON(employee_payroll_data.admin_id = employee_info.admin_id AND employee_payroll_data.employee_info_id = employee_info.id)  WHERE employee_info.admin_id = '".$admin_id."'";
    }else {
         $query = "SELECT employee_info.first_name, employee_info.last_name, employee_info.employee_ID, employee_payroll_data.basic_salary,employee_payroll_data.housing, employee_payroll_data.transport,employee_payroll_data.lunch,employee_payroll_data.utility,employee_payroll_data.education,employee_payroll_data.entertainment,employee_payroll_data.HMO,employee_payroll_data.leave_bonus,employee_payroll_data.xmas,employee_payroll_data.pension_company,employee_payroll_data.pension_earning,employee_payroll_data.tax,employee_payroll_data.service_charge,employee_payroll_data.VAT,employee_payroll_data.gross,employee_payroll_data.NET FROM employee_info LEFT JOIN employee_payroll_data ON (employee_payroll_data.admin_id = employee_info.admin_id AND employee_payroll_data.employee_info_id = employee_info.id)  WHERE employee_info.admin_id = '".$admin_id."' AND employee_info.branch_id = '".$sendto."'";
    }
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $employee[] = $row;
      }
    }
$pdf = new PDF();
// Column headings
$date =  "PAYSLIP for the Month";
$query = "SELECT * FROM company WHERE admin_id = '".$_SESSION['user']['id']."'";
      $result = mysqli_query($conn, $query);
      if(mysqli_num_rows($result)> 0){
          while($row = mysqli_fetch_assoc($result)) {
            $company = $row;
          }
      }
$header = array($company[0]['company_name'], $date);
// Data loading
//$data = $pdf->LoadData('countries.txt');
$pdf->SetFont('Arial','',14);
/*$pdf->AddPage();
$pdf->BasicTable($header,$data);
$pdf->AddPage();
$pdf->ImprovedTable($header,$data);*/
$pdf->AddPage();
for($r = 0; $r < count($employee); $r++){
    $pdf_name = 'leave_request'.time().'.pdf';
    $dest = 'leave_request/'.$pdf_name;
    $pdf->FancyTable($conn,$header,$employee[$r],$dest);
}
$_SESSION['msg'] = 'Message Sent';
header("Location: masterlist.php");

//$pdf->Output();    
}
function sendmail($to,$msg,$subject,$dest,$copyadmin){
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
    $mail->setFrom('ess@hrcore.ng', 'Leave Confirmation');
    $mail->addAddress($to, 'ESS');     // Add a recipient
    //$mail->addCC('leave@icsoutsourcing.com');
    if($copyadmin == 'Yes') $mail->addCC('ogunrindeomotayo@gmail.com');
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    // Attachments
    $mail->addAttachment($dest);         // Add attachments
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
?>
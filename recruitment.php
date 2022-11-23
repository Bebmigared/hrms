<?php 
include 'connection.php';
include 'notify_msg.php';
require 'phpoffice/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use phpOffice\PhpSpreadsheet\Writer\Xlsx;
session_start();
$user_company = [];
$recruitment = [];
$shortlisted = [];
$interviews = [];
$keepfinal_filename = '';
$error = 0;
$show_msg = 0;
$show_list = 0;
$excel_size = 0;
$lastrow = '';
$body = [];
$header = [];
  $inputFileType = 'Xlsx';
  $inputFileName = './shortlist/input.xlsx';
  $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
  $reader->setReadDataOnly(true);
   
  $worksheetData = $reader->listWorksheetInfo($inputFileName);
  $spreadsheet = $reader->load($inputFileName);
  $worksheet = $spreadsheet->getActiveSheet();
 //echo  "http://localhost/outsourcing/uploaddocumentation/?q=".base64_encode('request05')."";
if($_SESSION['user']['id'] == '' || $_SESSION['user']['id'] == null) header("location:login.php");
if(isset($_POST['submitfile'])){
  if(isset($_FILES['file'])) {
    if($_FILES['file']['name'] == null) {
      $_SESSION['show'] = "No file uploaded";  
      $show_msg = 1; 
    }else{
       $requestID = mysqli_real_escape_string($conn, $_POST['requestID']);
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
            $sql = "UPDATE shortlists SET final_filename = '".$file_name."', satisfied = 'Yes' WHERE requestID = '".$requestID."'";
            $show_msg = 1;
          if (mysqli_query($conn, $sql)) {
            $_SESSION['show'] = "File Uploaded Successfully...";
          }else {
            $_SESSION['show'] = 'Error updating data';
          }
       }else {
            $_SESSION['show'] = $errors;
            $show_msg = 1;
       }
    }
  }
} 
if(isset($_POST['begin_doc'])){
  $requestID = mysqli_real_escape_string($conn, $_POST['requestID']);
  if($requestID == ''){
    $_SESSION['msg'] = "Please Specify the request ID";
  }else{
    $value = notify_ro_doc($conn,$_SESSION['user']['email'],$requestID);
  }
}
if(isset($_POST['submit'])){
  $title = mysqli_real_escape_string($conn, $_POST['title']);
  $requestID = count($recruitment) + 1;
  $qualification = mysqli_real_escape_string($conn, $_POST['qualification']);
  $job_description = mysqli_real_escape_string($conn, $_POST['job_description']);
  $priority_level = mysqli_real_escape_string($conn, $_POST['priority_level']);
  $location = mysqli_real_escape_string($conn, $_POST['location']);
  if($title == '' || $qualification == '' || $location == ''){
     $_SESSION['msg'] = "Job Title, Qualification, Location fields are Required";
     $_SESSION['title'] = $title;
     $_SESSION['qualification'] = $qualification;
     $_SESSION['job_description'] = $job_description;
     $_SESSION['priority_level'] = $priority_level;
     $_SESSION['location'] = $location;
     $error = 1;
  }else{
     $query = "INSERT INTO recruitment (job_title,job_description,location,qualification,admin_id,priority_level,stage,request_date_created,requestID) values ('".$title."','".$job_description."','".$location."','".$qualification."','".$_SESSION['user']['id']."','".$priority_level."','Request for Shortlist','".date('Y-m-d')."','request0".$requestID."')";
      if(mysqli_query($conn,$query)){
          $value = notify_ro($conn,$location, $title, $job_description, $priority_level,$qualification,$_SESSION['user']['email'],$requestID);
          //print_r($value);
            //$error = 1;
          //echo $value;
          $_SESSION['msg'] = "Job Request Notification sent to concerned RO";
      }
      else {
        //$error = 1;
        //echo "Error: ".mysqli_error($conn);
        $_SESSION['msg'] = mysqli_error($conn);
      }
  }
  
}  
if(isset($_POST['updatesubmit'])){
  $title = mysqli_real_escape_string($conn, $_POST['job_title']);
  $qualification = mysqli_real_escape_string($conn, $_POST['qualification']);
  $job_description = mysqli_real_escape_string($conn, $_POST['job_description']);
  $location = mysqli_real_escape_string($conn, $_POST['location']);
  $requestID = mysqli_real_escape_string($conn, $_POST['requestID']);
  $priority_level = mysqli_real_escape_string($conn, $_POST['priority_level']);
  if($title == '' || $qualification == ''){
     $_SESSION['show'] = "Job Title, Qualification fields are Required";
     $_SESSION['title'] = $title;
     $_SESSION['qualification'] = $qualification;
     $show_msg = 1;
  }else{
     $query = "UPDATE recruitment SET job_title = '".$title."',job_description = '".$job_description."',qualification = '".$qualification."' WHERE requestID = '".$requestID."'";
      if(mysqli_query($conn,$query)){
          $value = notify_ro($conn,$location,$title, $job_description,$priority_level, $qualification,$_SESSION['user']['email'],$requestID);
          $show_msg = 1;
          $_SESSION['show'] = "Job Request Notification sent to concerned RO";
      }
      else {}
  }
}
if(isset($_POST['clientsubmit'])){
  $email = mysqli_real_escape_string($conn, $_POST['client_email']);
  $requestID = mysqli_real_escape_string($conn, $_POST['requestID']);
  if($email == ''){
     $_SESSION['show'] = "Email field is empty";
     $show_msg = 1;
  }else{
     $pm_email = $_SESSION['user']['email'];
     $sql = "SELECT * from shortlists where requestID = '".$requestID."'";
     $result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($result)> 0){
          while($row = mysqli_fetch_assoc($result)) {
              $thisshortlisted[] = $row;
          }
      }
      if(isset($thisshortlisted[0]['final_filename']) && $thisshortlisted[0]['final_filename'] != ''){
         $value = notify_client($conn,$email,$pm_email,$thisshortlisted[0]['final_filename']);
         $_SESSION['show'] = "Email Sent to Client";
         $show_msg = 1;
      }
      else {
         $_SESSION['show'] = "Confirm your satisfaction on the file received from Recruitment Officer";
         $show_msg = 1;
      }
           
  }
}
if(isset($_POST['interview_submit'])){
  $date = mysqli_real_escape_string($conn, $_POST['date']);
  $time = mysqli_real_escape_string($conn, $_POST['time']);
  $information = mysqli_real_escape_string($conn, $_POST['information']);
  $requestID = mysqli_real_escape_string($conn, $_POST['requestID']);
  $venue = mysqli_real_escape_string($conn, $_POST['venue']);
  $contact_person = mysqli_real_escape_string($conn, $_POST['contact_person']);
  //get location 
  $query = "SELECT * from recruitment where requestID = '".$requestID."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
      $location = $row['location'];
    }
  }
  $sql = "SELECT * from interviews where requestID = '".$requestID."'";
  $result = mysqli_query($conn, $sql);
  if(mysqli_num_rows($result)> 0){
        $query = "UPDATE interviews SET interview_date='".$date."', interview_time='".$time."', information='".$information."',contact_person = '".$contact_person."', venue = '".$venue."' WHERE requestID = '".$requestID."'";
        if(mysqli_query($conn,$query)){
          $value = notify_ro_interview($conn,$location, $date, $time,$information,$_SESSION['user']['email'],$requestID,$venue,$contact_person);
          //echo $value;
          $_SESSION['msg'] = "Interview Notification sent to RO";

      }else{
          $_SESSION['msg'] = "Error saving Record, please try again later";
          echo "Error: ".mysqli_error($conn);
      }
  }else{
     $sql = "INSERT INTO interviews (interview_date, interview_time, information,date_created,requestID,contact_person,venue,admin_id) VALUES ('".$date."', '".$time."','".$information."','".date('Y-m-d')."', '".$requestID."','".$contact_person."','".$venue."','".$_SESSION['user']['id']."')";
      if(mysqli_query($conn,$sql)){
          $value = notify_ro_interview($conn,$location, $date, $time,$information,$_SESSION['user']['email'],$requestID,$venue,$contact_person);
          //echo $value;
          $_SESSION['msg'] = "Interview Notification sent to RO";
      }
      else {
         echo "Error: ".mysqli_error($conn);
        //$show_msg = 1;
      }
  }
}
if(isset($_POST['process_submit'])){
  $requestID = mysqli_real_escape_string($conn, $_POST['requestID']);
  $sql = "SELECT * from shortlists where requestID = '".$requestID."'";
  $result = mysqli_query($conn, $sql);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
            $dataRows[] = $row;
      }
  }
  if(isset($dataRows[0]['final_filename']) && $dataRows[0]['final_filename'] != ''){
  $show_list = 1;
  $keepfinal_filename = $dataRows[0]['final_filename'];
  $spreadsheet = new Spreadsheet();
  $inputFileType = 'Xlsx';
  $inputFileName = './shortlist/'.$dataRows[0]['final_filename'].'';
  $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
  $reader->setReadDataOnly(true);
   
  $worksheetData = $reader->listWorksheetInfo($inputFileName);
  $header = []; 
  $spreadsheet = $reader->load($inputFileName);
  $worksheet = $spreadsheet->getActiveSheet();
  $e = 1;
  $stop = false;
  $value = [];
  $h = 0;
  $header = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P'];
  if($worksheet->getCell('A1')->getValue() != '') $h++; 
  if($worksheet->getCell('B1')->getValue() != '') $h++; 
  if($worksheet->getCell('C1')->getValue() != '') $h++; 
  if($worksheet->getCell('D1')->getValue() != '') $h++; 
  if($worksheet->getCell('E1')->getValue() != '') $h++; 
  if($worksheet->getCell('F1')->getValue() != '') $h++; 
  if($worksheet->getCell('G1')->getValue() != '') $h++; 
  if($worksheet->getCell('H1')->getValue() != '') $h++; 
  if($worksheet->getCell('I1')->getValue() != '') $h++; 
  $excel_size = $h + 1;
  //echo $excel_size;
  while($stop == false){
    $body[$e][''.$worksheet->getCell('A1')->getValue().''] = trim($worksheet->getCell('A'.$e.'')->getValue());
    $body[$e][''.$worksheet->getCell('B1')->getValue().''] = trim($worksheet->getCell('B'.$e.'')->getValue());
    $body[$e][''.$worksheet->getCell('C1')->getValue().''] = trim($worksheet->getCell('C'.$e.'')->getValue());
    $body[$e][''.$worksheet->getCell('D1')->getValue().''] = trim($worksheet->getCell('D'.$e.'')->getValue());
    $body[$e][''.$worksheet->getCell('E1')->getValue().''] = trim($worksheet->getCell('E'.$e.'')->getValue());
    $body[$e][''.$worksheet->getCell('F1')->getValue().''] = trim($worksheet->getCell('F'.$e.'')->getValue());
    $body[$e][''.$worksheet->getCell('G1')->getValue().''] = trim($worksheet->getCell('G'.$e.'')->getValue());
    $body[$e][''.$worksheet->getCell('H1')->getValue().''] = trim($worksheet->getCell('H'.$e.'')->getValue());
    $body[$e][''.$worksheet->getCell('I1')->getValue().''] = trim($worksheet->getCell('I'.$e.'')->getValue());
    if(trim($worksheet->getCell('A'.$e.'')->getValue()) == "" && trim($worksheet->getCell('B'.$e.'')->getValue()) =="" && trim($worksheet->getCell('C'.$e.'')->getValue()) == "" && trim($worksheet->getCell('D'.$e.'')->getValue()) == "" && trim($worksheet->getCell('E'.$e.'')->getValue()) == "" && trim($worksheet->getCell('F'.$e.'')->getValue()) == "" && trim($worksheet->getCell('G'.$e.'')->getValue()) == "" && trim($worksheet->getCell('H'.$e.'')->getValue()) == "" && trim($worksheet->getCell('I'.$e.'')->getValue()) == ""){
      $stop = true;
    }
    $e++;
  }
 }
}
if(isset($_POST['update_file_submit'])){
  $filename = mysqli_real_escape_string($conn, $_POST['filename']);
  $allstatus = mysqli_real_escape_string($conn, $_POST['allstatus']);
  $allstatusnumber = mysqli_real_escape_string($conn, $_POST['allstatusnumber']);
  $requestID = mysqli_real_escape_string($conn, $_POST['requestID']);
  $keepfinal_filename = $filename;
  $keepstatus = explode('###',$allstatus);
  $keepstatusnumber = explode("###",$allstatusnumber);
  if($filename != ''){
  $show_list = 1;
  $spreadsheet = new Spreadsheet();
  $inputFileType = 'Xlsx';
  $inputFileName = './shortlist/'.$filename.'';
  $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
  $reader->setReadDataOnly(true);
   
  $worksheetData = $reader->listWorksheetInfo($inputFileName);
  $header = []; 
  $spreadsheet = $reader->load($inputFileName);
  $worksheet = $spreadsheet->getActiveSheet();
  $e = 1;
  $stop = false;
  $value = [];
  $h = 0;
  $header = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P'];
  if($worksheet->getCell('A1')->getValue() != '') {
    $h++;
    $lastrow = $worksheet->getCell('A1')->getValue();
  } 
  if($worksheet->getCell('B1')->getValue() != '') {$h++;$lastrow = $worksheet->getCell('B1')->getValue();}
  if($worksheet->getCell('C1')->getValue() != '') {$h++;$lastrow = $worksheet->getCell('C1')->getValue();} 
  if($worksheet->getCell('D1')->getValue() != '') {$h++;$lastrow = $worksheet->getCell('D1')->getValue();} 
  if($worksheet->getCell('E1')->getValue() != '') {$h++;$lastrow = $worksheet->getCell('E1')->getValue();} 
  if($worksheet->getCell('F1')->getValue() != '') {$h++;$lastrow = $worksheet->getCell('F1')->getValue();} 
  if($worksheet->getCell('G1')->getValue() != '') {$h++;$lastrow = $worksheet->getCell('G1')->getValue();}
  if($worksheet->getCell('H1')->getValue() != '') {$h++;$lastrow = $worksheet->getCell('H1')->getValue();} 
  if($worksheet->getCell('I1')->getValue() != '') {$h++;$lastrow = $worksheet->getCell('I1')->getValue();} 
  $excel_size = $h;
  while($stop == false){
    $body[$e][''.$worksheet->getCell('A1')->getValue().''] = trim($worksheet->getCell('A'.$e.'')->getValue());
    $body[$e][''.$worksheet->getCell('B1')->getValue().''] = trim($worksheet->getCell('B'.$e.'')->getValue());
    $body[$e][''.$worksheet->getCell('C1')->getValue().''] = trim($worksheet->getCell('C'.$e.'')->getValue());
    $body[$e][''.$worksheet->getCell('D1')->getValue().''] = trim($worksheet->getCell('D'.$e.'')->getValue());
    $body[$e][''.$worksheet->getCell('E1')->getValue().''] = trim($worksheet->getCell('E'.$e.'')->getValue());
    $body[$e][''.$worksheet->getCell('F1')->getValue().''] = trim($worksheet->getCell('F'.$e.'')->getValue());
    $body[$e][''.$worksheet->getCell('G1')->getValue().''] = trim($worksheet->getCell('G'.$e.'')->getValue());
    $body[$e][''.$worksheet->getCell('H1')->getValue().''] = trim($worksheet->getCell('H'.$e.'')->getValue());
    $body[$e][''.$worksheet->getCell('I1')->getValue().''] = trim($worksheet->getCell('I'.$e.'')->getValue());
    if(trim($worksheet->getCell('A'.$e.'')->getValue()) == "" && trim($worksheet->getCell('B'.$e.'')->getValue()) =="" && trim($worksheet->getCell('C'.$e.'')->getValue()) == "" && trim($worksheet->getCell('D'.$e.'')->getValue()) == "" && trim($worksheet->getCell('E'.$e.'')->getValue()) == "" && trim($worksheet->getCell('F'.$e.'')->getValue()) == "" && trim($worksheet->getCell('G'.$e.'')->getValue()) == "" && trim($worksheet->getCell('H'.$e.'')->getValue()) == "" && trim($worksheet->getCell('I'.$e.'')->getValue()) == ""){
      $stop = true;
    }
    $e++;
  }
 }
  $show_list = 1;
  if($_FILES['file']['name'] == '') {
      
      $_SESSION['show_list'] = "No file uploaded";     
  }else{
       $errors = ''; 
       $file_ext = explode('.',$_FILES['file']['name'])[1];
       $extName = explode('.',$_FILES['file']['name'])[0];
       $filename = $extName.'_'.time();
       $file_name = $filename.'.'.$file_ext;
       $file_size = $_FILES['file']['size'];
       $file_tmp = $_FILES['file']['tmp_name'];
       $file_type = $_FILES['file']['type'];
       $extensions = array('xlsx','xls','pdf','doc','docx','txt');
         move_uploaded_file($file_tmp,"interviewsheet/".$file_name);
          $sql = "UPDATE shortlists SET interviewsheet = '".$file_name."', date_doc_uploaded = '".date('Y-m-d')."' WHERE requestID = '".$requestID."'";
            if (mysqli_query($conn, $sql)) {

                $spreadsheet = new Spreadsheet();
                $inputFileType = 'Xlsx';
                $inputFileName = './shortlist/'.$keepfinal_filename.'';
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                $reader->setReadDataOnly(true);
                 
                $worksheetData = $reader->listWorksheetInfo($inputFileName);
                $spreadsheet = $reader->load($inputFileName);
                if($lastrow != 'Status'){
                  $spreadsheet->getActiveSheet()->setCellValue(''.$header[$excel_size].'1', 'Status');
                  for($r = 0; $r < count($keepstatus); $r++){
                    $spreadsheet->getActiveSheet()->setCellValue(''.$header[$excel_size].''.$keepstatusnumber[$r].'', $keepstatus[$r]);
                  }
                }else {
                  $spreadsheet->getActiveSheet()->setCellValue(''.$header[$excel_size - 1].'1', 'Status');
                  for($r = 0; $r < count($keepstatus); $r++){
                    $spreadsheet->getActiveSheet()->setCellValue(''.$header[$excel_size - 1].''.$keepstatusnumber[$r].'', $keepstatus[$r]);
                  }
                }
                
                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save($inputFileName);
                
              
              $_SESSION['show_list'] = "File Uploaded Successfully...";
            }else {
              $_SESSION['show_list'] = 'Error updating data';
            }

  }
}
$rolocation = [];
$sql = "SELECT * from ro where company_id = '".$_SESSION['user']['company_id']."'";
$result = mysqli_query($conn, $sql);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        if(!in_array($row['location'], $rolocation))
            $rolocation[] = $row['location'];
      }
  }
$sql = "SELECT * from interviews where admin_id = '".$_SESSION['user']['id']."'";
$result = mysqli_query($conn, $sql);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
            $interviews[] = $row;
      }
  }
  $sql = "SELECT * from recruitment where admin_id = '".$_SESSION['user']['id']."'";
  $result = mysqli_query($conn, $sql);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
          $recruitment[] = $row;
      }
  }
  $sql = "SELECT * from shortlists LEFT JOIN recruitment ON recruitment.requestID = shortlists.requestID where recruitment.admin_id = '".$_SESSION['user']['id']."'";
  $result = mysqli_query($conn, $sql);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
          $shortlisted[] = $row;
      }
  }
if(isset($_POST['satisfiedsubmit'])){
  $satisfied = mysqli_real_escape_string($conn, $_POST['satisfied']);
  $requestID = mysqli_real_escape_string($conn, $_POST['requestID']);
  $filename = mysqli_real_escape_string($conn, $_POST['filename']);
  if($satisfied == 'Yes'){
     $sql = "UPDATE shortlists SET satisfied = 'Yes', final_filename = '".$filename."' where requestID = '".$requestID."'";
     if (mysqli_query($conn, $sql)) {
            $_SESSION['show'] = "Update is Noted";
            $show_msg = 1;
        }else {
           $_SESSION['show'] = "Error while update account, please try again later";
           $show_msg = 1;
       }   
  }else{
    $sql = "UPDATE shortlists SET satisfied = 'No' where requestID = '".$requestID."'";
     if (mysqli_query($conn, $sql)) {
            $_SESSION['show'] = "Update is Noted";
            $show_msg = 1;
        }else {
           $_SESSION['show'] = "Error while update account, please try again later";
           $show_msg = 1;
       } 
  }
} 

?>
<?php include "header.php"?>

<style type="text/css">
  .w3-content, .w3-auto {
    margin-left: auto;
    margin-right: auto;
  }
  .w3-light-grey, .w3-hover-light-grey:hover, .w3-light-gray, .w3-hover-light-gray:hover {
    color: #000!important;
    background-color: #f1f1f1!important;
  }
  .w3-red, .w3-hover-red:hover {
    color: #fff!important;
    background-color: #f44336!important;
  }
  .w3-btn, .w3-button {
    border: none;
    display: inline-block;
    padding: 8px 16px;
    vertical-align: middle;
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    background-color: inherit;
    text-align: center;
    cursor: pointer;
    white-space: nowrap;
 }
 .w3-center {
    text-align: center!important;
 }
</style>
<div class="right_col" role="main">
<div class="">
        <div class="page-title">
          <div class="title_left">
            <h3>Recruitment</h3>
          </div>

          <div class="title_right">
            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Search for...">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button">Go!</button>
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
         <?php if(isset($_SESSION['msg'])) {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                  <?php } ?>
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#home">Request</a></li>
            <li><a data-toggle="tab" href="#menu2">Shortlist</a></li>
            <li><a data-toggle="tab" href="#menu3">Interview</a></li>
            <li><a data-toggle="tab" href="#menu4">Interview Sheet</a></li>
            <li><a data-toggle="tab" href="#menu5">Begin Documentationing</a></li>
          </ul>
        
          <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
                <div class="row">
                     <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                          <div class="x_title">
                            <h2>Request</h2>
                            <button type="submit" id="btnExport" style="margin-left:10px;"
                                class="btn btn-info request" data-toggle="modal" data-target="#exampleModal">Add New Request</button>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <br />
                            <div class="table-responsive" style='margin-top:20px;'>
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N </th>
                            <th class="column-title">RequestID </th>
                            <th class="column-title">Job Title </th>
                            <th class="column-title">Job Description </th>
                            <th class="column-title">Location </th>
                            <th class="column-title">Qualification </th>
                            <th class="column-title">Priority Level </th>
                            <th class="column-title">Stage </th>
                            <th class="column-title">Date Created </th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($recruitment); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$recruitment[$h]['requestID']?></td>
                            <td class=" "><?=$recruitment[$h]['job_title']?></td>
                            <td class=" "><?=$recruitment[$h]['job_description']?></td>
                            <td class=" "><?=$recruitment[$h]['location']?></td>
                            <td class=" "><?=$recruitment[$h]['qualification']?></td>
                            <td class=" "><?=$recruitment[$h]['priority_level']?></td>
                            <td class=" " style="text-transform:capitalize"><?=$recruitment[$h]['stage']?></td>
                            <td class=" " style="text-transform:capitalize"><?=$recruitment[$h]['request_date_created']?></td>
                          </tr>
                           <?php }?>
                        </tbody>
                      </table>
                    </div>
                          </div>
                        </div>
                     </div>
                </div>
            </div>
            <div id="menu2" class="tab-pane fade">
             <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                      <div class="x_title">
                        <h2>Shortlisted</h2>
                        <div class="clearfix"></div>
                      </div>

                      <div class="x_content">
                        <br />
                         <table class="table table-striped jambo_table bulk_action">
                            <thead>
                              <tr class="headings">
                                <th class="column-title">S/N </th>
                                <th class="column-title">RequestID </th>
                                <th class="column-title">Recruitment Officer Name </th>
                                <th class="column-title">Shortlisted </th>
                                <th class="column-title">Satisfied with List?</th>
                                <th class="column-title">Update / Share </th>
                                <th class="column-title">Date Created </th>
                              </tr>
                            </thead>

                            <tbody>
                              <?php for ($h = 0; $h < count($shortlisted); $h++) {?>
                              <tr class="pointer">
                                <td class="a-center ">
                                  <?=$h + 1?>
                                </td>
                                <td class=""><?=$shortlisted[$h]['requestID']?></td>
                                <td class=" "><?=$shortlisted[$h]['ro_name']?></td>
                                <td class="">
                                  <a href = "downloadshortlist.php/?to=a&filename=<?=$shortlisted[$h]['filename']?>" class="btn btn-info btn-sm">Download file</a>  
                                </td>
                                <td class=" "><?=$shortlisted[$h]['satisfied'] == '' ? 'Not Known' : 'Yes'?></td>
                                <td class="">
                                  <button type="button" id="request<?=$shortlisted[$h]['requestID']?>"  requestID ="<?=$shortlisted[$h]['requestID']?>" class="btn btn-success btn-sm requests" job_description = "<?=$shortlisted[$h]['job_description']?>" job_title = "<?=$shortlisted[$h]['job_title']?>" location = "<?=$shortlisted[$h]['location']?>" priority_level = "<?=$shortlisted[$h]['priority_level']?>"   filename = "<?=$shortlisted[$h]['filename']?>" qualification = "<?=$shortlisted[$h]['qualification']?>" data-toggle="modal" data-target="#shortModal">Update</button>  
                                </td>
                                <td class=" " style="text-transform:capitalize"><?=$shortlisted[$h]['date_created']?></td>
                              </tr>
                               <?php }?>
                            </tbody>
                          </table> 
                      </div>
                    </div>
                </div> 
              </div>
            </div>
            <div id="menu3" class="tab-pane fade">
             <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                      <div class="x_title">
                        <h2>Interview</h2>
                        <button type="submit" id="btnExport" style="margin-left:10px;"
                                class="btn btn-info" data-toggle="modal" data-target="#interModal">Add Interview Date</button>
                        <div class="clearfix"></div>
                      </div>

                      <div class="x_content">
                        <br />
                          <table class="table table-striped jambo_table bulk_action">
                            <thead>
                              <tr class="headings">
                                <th class="column-title">S/N </th>
                                <th class="column-title">RequestID </th>
                                <th class="column-title">Interview Date </th>
                                <th class="column-title">Interview Time </th>
                                <th class="column-title">Contact Person </th>
                                <th class="column-title">Venue </th>
                                <th class="column-title">Additional Information </th>
                                <th class="column-title">Date Shared with RO </th>
                              </tr>
                            </thead>

                            <tbody>
                              <?php for ($h = 0; $h < count($interviews); $h++) {?>
                              <tr class="pointer">
                                <td class="a-center ">
                                  <?=$h + 1?>
                                </td>
                                <td class=""><?=$interviews[$h]['requestID']?></td>
                                <td class=" "><?=$interviews[$h]['interview_date']?></td>
                                <td class=" "><?=$interviews[$h]['interview_time']?></td>
                                <td class=" "><?=$interviews[$h]['contact_person']?></td>
                                <td class=" "><?=$interviews[$h]['venue']?></td>
                                <td class=" "><?=$interviews[$h]['information']?></td>
                                <td class=" "><?=$interviews[$h]['date_created']?></td>
                                
                              </tr>
                               <?php }?>
                            </tbody>
                          </table> 
                      </div>
                    </div>
                </div> 
              </div>
            </div>
            <div id="menu4" class="tab-pane fade">
             <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                      <div class="x_title">
                        <h2>Interview Document</h2>
                        <button type="submit" id="btnExport" style="margin-left:10px;"
                                class="btn btn-info" data-toggle="modal" data-target="#sheetModal">Upload Document / Successful Candidates</button>
                        <button type="submit" id="btnExport" style="display: none"
                                class="btn btn-info processData" data-toggle="modal" data-target="#showDataModal">Process</button>
                        <button type="submit" id=""
                                class="btn btn-success" data-toggle="modal" data-target="#docModal">Begin Documentation</button>        
                        <div class="clearfix"></div>
                      </div>

                      <div class="x_content">
                        <br />
                         <table class="table table-striped jambo_table bulk_action">
                            <thead>
                              <tr class="headings">
                                <th class="column-title">S/N </th>
                                <th class="column-title">RequestID </th>
                                <th class="column-title">Shortlisted (RO) </th>
                                <th class="column-title">Final Shortlist </th>
                                <th class="column-title">Interview Document </th>
                                <th class="column-title">Date Interview Document Uploaded </th>
                              </tr>
                            </thead>

                            <tbody>
                              <?php for ($h = 0; $h < count($shortlisted); $h++) {?>
                              <tr class="pointer">
                                <td class="a-center ">
                                  <?=$h + 1?>
                                </td>
                                <td class=""><?=$shortlisted[$h]['requestID']?></td>
                                <td class="">
                                  <a href = "downloadshortlist.php/?to=a&filename=<?=$shortlisted[$h]['filename']?>" class="btn btn-info btn-sm">Download file</a>  
                                </td>
                                <td class="">
                                  <a href = "downloadshortlist.php/?to=a&filename=<?=$shortlisted[$h]['final_filename']?>" class="btn btn-success btn-sm">Download</a>  
                                </td>
                                <td class="">
                                  <?php if($shortlisted[$h]['interviewsheet'] == '') {?>
                                    No Document Uploaded yet 
                                  <?php }else { ?> 
                                    <a href = "downloadinterviewsheet.php/?to=a&filename=<?=$shortlisted[$h]['interviewsheet']?>" class="btn btn-success btn-sm">Download</a>
                                  <?php } ?>  
                                </td>
                                <td class="a-center ">
                                  <?=$shortlisted[$h]['date_doc_uploaded']?>
                                </td>
                                
                              </tr>
                               <?php }?>
                            </tbody>
                          </table> 
                      </div>
                    </div>
                </div> 
              </div>
            </div>
            <div id="menu5" class="tab-pane fade">
             <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                      <div class="x_title">
                        <h2>Begin Documentation for Successful Candidates</h2>
                        <button type="submit" id=""
                                class="btn btn-success" style="margin-left: 5px;" data-toggle="modal" data-target="#docModal">Begin Documentation</button>        
                        <div class="clearfix"></div>
                      </div>

                      <div class="x_content">
                        <br />
                         <table class="table table-striped jambo_table bulk_action">
                            <thead>
                              <tr class="headings">
                                <th class="column-title">S/N </th>
                                <th class="column-title">RequestID </th>
                                <th class="column-title">Documentation Phase </th>
                                <th class="column-title">Begin Date </th>
                                <th class="column-title">End Date </th>
                                
                              </tr>
                            </thead>

                            <tbody>
                              <?php $a = 1;?>
                              <?php for ($h = 0; $h < count($recruitment); $h++) {?>
                              <?php if($recruitment[$h]['stage'] == 'Begin Documentation' || $recruitment[$h]['stage'] == 'Documentation'){?>  
                              <tr class="pointer">
                                <td class="a-center ">
                                  <?=$a?>
                                </td>
                                <td class=""><?=$recruitment[$h]['requestID']?></td>
                                <td class=""><?=$recruitment[$h]['doc_phase']?></td>
                                <td class=""><?=$recruitment[$h]['begin_date']?></td>
                                <td class=""><?=$recruitment[$h]['end_date']?></td>
                                
                                
                              </tr>
                              <?php $a++;?>
                               <?php } } ?>
                            </tbody>
                          </table> 
                      </div>
                    </div>
                </div> 
              </div>
            </div>

          </div>
        </div>
</div>
</div>
<div class="modal fade" id="sheetModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Job Request</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
         <form action="recruitment.php" method="POST" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
        
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Request ID <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="requestID" class="form-control">
                       <option value=""></option>
                       <?php for($r = 0; $r < count($recruitment); $r++) { ?>
                        <option value="<?=$recruitment[$r]['requestID']?>"><?=$recruitment[$r]['requestID']?></option>
                       <?php } ?>                     

                     </select>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-12">
                 <button type="submit" name="process_submit" class="btn btn-success">Submit</button>
              </div>
            </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="showDataModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="width: 90%;">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Job Request</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
          <?php if(isset($_SESSION['show_list'])) {?>
                        <div class="alert alert-primary" style="background-color:blue;font-size: 14px;color:#fff" role="alert">
                            <?=$_SESSION['show_list']?>
                        </div>
                        <?php unset($_SESSION['show_list']); ?>
                  <?php } ?>
          <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N </th>
                            <?php if($worksheet->getCell('A1')->getValue() != "") { ?>
                            <th class="column-title"><?=$body[1][''.$worksheet->getCell('A1')->getValue().'']?> </th>
                            <?php } ?>
                            <?php if($worksheet->getCell('B1')->getValue() != "") { ?>
                             <th class="column-title"><?=$body[1][''.$worksheet->getCell('B1')->getValue().'']?> </th>
                             <?php } ?>
                             <?php if($worksheet->getCell('C1')->getValue() != "") { ?>
                             <th class="column-title"><?=$body[1][''.$worksheet->getCell('C1')->getValue().'']?> </th>
                             <?php } ?>
                             <?php if($worksheet->getCell('D1')->getValue() != "") { ?>
                             <th class="column-title"><?=$body[1][''.$worksheet->getCell('D1')->getValue().'']?> </th>
                             <?php } ?>
                             <?php if($worksheet->getCell('E1')->getValue() != "") { ?>
                             <th class="column-title"><?=$body[1][''.$worksheet->getCell('E1')->getValue().'']?> </th>
                             <?php } ?>
                             <?php if($worksheet->getCell('F1')->getValue() != "") { ?>
                             <th class="column-title"><?=$body[1][''.$worksheet->getCell('F1')->getValue().'']?> </th>
                             <?php } ?>
                             <?php if($worksheet->getCell('G1')->getValue() != "") { ?>
                             <th class="column-title"><?=$body[1][''.$worksheet->getCell('G1')->getValue().'']?> </th>
                             <?php } ?>
                             <?php if($worksheet->getCell('H1')->getValue() != "") { ?>
                             <th class="column-title"><?=$body[1][''.$worksheet->getCell('H1')->getValue().'']?> </th>
                             <?php } ?>
                             <?php if($worksheet->getCell('I1')->getValue() != "") { ?>
                             <th class="column-title"><?=$body[1][''.$worksheet->getCell('I1')->getValue().'']?> </th>
                             <?php } ?>
                             <th class="column-title">Status </th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 2; $h < count($body); $h++) {?>
                          <?php if($h == 1){?>

                          <?php } ?>  
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h - 1?>
                            </td>
                            <?php if($worksheet->getCell('A1')->getValue() != "") { ?>
                              <td class=""><?=$body[$h][''.$worksheet->getCell('A1')->getValue().'']?></td>
                            <?php } ?>
                            <?php if($worksheet->getCell('B1')->getValue() != "") { ?>
                            <td class=" "><?=$body[$h][''.$worksheet->getCell('B1')->getValue().'']?></td>
                            <?php } ?>
                            <?php if($worksheet->getCell('C1')->getValue() != "") { ?>
                            <td class=" "><?=$body[$h][''.$worksheet->getCell('C1')->getValue().'']?></td>
                            <?php } ?>
                            <?php if($worksheet->getCell('D1')->getValue() != "") { ?>
                            <td class=" "><?=$body[$h][''.$worksheet->getCell('D1')->getValue().'']?></td>
                            <?php } ?>
                            <?php if($worksheet->getCell('E1')->getValue() != "") { ?>
                            <td class=" "><?=$body[$h][''.$worksheet->getCell('E1')->getValue().'']?></td>
                            <?php } ?>
                            <?php if($worksheet->getCell('F1')->getValue() != "") { ?>
                            <td class=" "><?=$body[$h][''.$worksheet->getCell('F1')->getValue().'']?></td>
                            <?php } ?>
                            <?php if($worksheet->getCell('G1')->getValue() != "") { ?>
                            <td class=" " style="text-transform:capitalize"><?=$body[$h][''.$worksheet->getCell('G1')->getValue().'']?></td>
                            <?php } ?>
                            <?php if($worksheet->getCell('H1')->getValue() != "") { ?>
                            <td class=" "><?=$body[$h][''.$worksheet->getCell('H1')->getValue().'']?></td>
                            <?php } ?>
                            <?php if($worksheet->getCell('I1')->getValue() != "") { ?>
                            <td class=" " style="text-transform:capitalize"><?=$body[$h][''.$worksheet->getCell('I1')->getValue().'']?></td>
                            <?php } ?>
                            <td class=" " style="text-transform:capitalize">
                              <select name="state" id="status<?=$h?>" class="form-control status" position ="<?=$h?>">
                                 <option value=""></option>
                                 <option value="Successful">Successful</option>
                                 <option value="Unsuccessful">Unsuccessful</option>
                              </select>
                            </td>
                            
                          </tr>
                           <?php }?>
                        </tbody>
          </table>
          <form action="recruitment.php" method="POST" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
        
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Upload Interview Document <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="text" name="file" class="form-control col-md-7 col-xs-12" type="file">
                  <input id="allstatus" name="allstatus" style="display: none;" class="form-control col-md-7 col-xs-12" type="text">
                  <input id="allstatusnumber" name="allstatusnumber" style="display: none;" class="form-control col-md-7 col-xs-12" type="text">
                  <input id="file_requestID" name="requestID" style="display: none" class="form-control col-md-7 col-xs-12" type="text" value="<?=isset($dataRows[0]['requestID']) ? $dataRows[0]['requestID'] : ''?>">
                  <input id="toeditfile" style="display: none" value = "<?=isset($dataRows[0]['final_filename']) ? $dataRows[0]['final_filename'] : ''?>" name="filename" class="form-control col-md-7 col-xs-12" required="required" type="text">
              </div>
            </div>
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="submit" name="update_file_submit" class="btn btn-success">Submit</button>
                </div>
              </div>

        </form>            
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="interModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Job Request</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form action="recruitment.php" method="POST" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
        
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Interview Date<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="interview_date" name="date" required="true" class="form-control col-md-7 col-xs-12" type="date" value="<?=isset($_SESSION['date']) ? $_SESSION['date'] : ''?>">
              </div>
            </div>
            <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Interview Time <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="interview_time" name="time" required="true" class="form-control col-md-7 col-xs-12" type="time" value="<?=isset($_SESSION['time']) ? $_SESSION['time'] : ''?>">
                      
                    </div>
              </div>
              <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Contact Person <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="" name="contact_person" required="true" class="form-control col-md-7 col-xs-12" type="text" value="<?=isset($_SESSION['contact_person']) ? $_SESSION['contact_person'] : ''?>">
                      
                    </div>
              </div>
              <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Request ID <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <select name="requestID" required="true" class="form-control">
                         <option value=""></option>
                         <?php for($r = 0; $r < count($recruitment); $r++) {?>
                           <option value="<?=$recruitment[$r]['requestID']?>"><?=$recruitment[$r]['requestID']?></option>
                         <?php }?>
                      </select>
                    </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="admin_email">Venue
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                 <textarea class="form-control" required="true" name='venue'><?=isset($_SESSION['venue']) ? $_SESSION['venue'] : ''?></textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="admin_email">Other Information <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                 <textarea class="form-control" name='information'><?=isset($_SESSION['information']) ? $_SESSION['information'] : ''?></textarea>
                </div>
              </div>
              
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="submit" name="interview_submit" class="btn btn-success">Submit</button>
                </div>
              </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Job Request</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form action="recruitment.php" method="POST" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
        
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Job Title <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="text" name="title" class="form-control col-md-7 col-xs-12" required="required" type="text" value="<?=isset($_SESSION['title']) ? $_SESSION['title'] : ''?>">
              </div>
            </div>
            <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Location <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                     <select name="location" class="form-control" value="<?=isset($_SESSION['location']) ? $_SESSION['location'] : ''?>">
                       <option value=""></option>
                       <?php for($r = 0; $r < count($location); $r++) { ?>
                        <option value="<?=$location[$r]?>"><?=$location[$r]?></option>
                       <?php } ?>                     

                     </select>
                    </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-3" for="admin_email">Job Description <span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                 <textarea class="form-control" name='job_description'><?=isset($_SESSION['job_description']) ? $_SESSION['job_description'] : ''?></textarea>
                </div>
              </div>
              <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Qualification Required <span class="required">*</span>
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea class="form-control" name="qualification"><?=isset($_SESSION['qualification']) ? $_SESSION['qualification'] : ''?></textarea>
                      </div>                                
              </div>
              <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Priority Level <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                     <select name="priority_level" class="form-control" <?=isset($_SESSION['priority_level']) ? $_SESSION['priority_level'] : ''?>>
                       <option value=""></option>
                       <option value="low">Low</option>
                       <option value="medium">Medium</option>
                       <option value="high">High</option>

                     </select>
                    </div>
              </div>
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="submit" name="submit" class="btn btn-success">Submit</button>
                </div>
              </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="shortModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Job Request</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php if(isset($_SESSION['show'])) {?>
                        <div class="alert alert-primary" style="background-color: blue;color:#fff;font-size: 15px;" role="alert">
                            <?=$_SESSION['show']?>
                        </div>
                        <?php unset($_SESSION['show']); ?>
                  <?php } ?>
      <div class="modal-body">
          <ul class="nav nav-tabs">
            <li  class="active"><a data-toggle="tab" href="#home1">Satisfied</a></li>
            <li><a data-toggle="tab" href="#home2">Request Shortlist Again</a></li>
            <li><a data-toggle="tab" href="#home3">Upload Edited Shortlist</a></li>
            <li><a data-toggle="tab" href="#home4">Share Shortlisted with Client</a></li>
          </ul>
          <div class="tab-content">
            <div id="home1" class="tab-pane fade in active">
                <div class="row">
                     <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                          <div class="x_title">
                            <h2>Satisfy with Shortlisted Candidate from Recruitment Officer ?</h2>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <br />
                            <form action="recruitment.php" method="POST" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                          
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                                 Satisfied?
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                  <select name="satisfied" class="form-control">
                                     <option value=""></option>
                                     <option value="Yes">Yes</option>
                                     <option value="No">No</option>
                                  </select>
                              </div>
                              <input type="text" style="display: none;" name="requestID" id="thisrequestID" >
                              <input type="text" style="display: none;" name="filename" id="thisfilename" >
                            </div>
                            <div class="form-group" style="text-align: center;">
                              <button type="submit" name="satisfiedsubmit" class="btn btn-success ">Submit</button>
                            </div>
                          </form>
                          </div>
                        </div>
                     </div>
                </div>
            </div>
            <div id="home2" class="tab-pane fade">
             <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                      <div class="x_title">
                        <h2>Request for new Shortlist</h2>
                        <div class="clearfix"></div>
                      </div>

                      <div class="x_content">
                        <br />
                          <form action="recruitment.php" method="POST" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                            <div class="form-group" style="display: none;">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Request ID 
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input id="requestNo" style="display: none;" name="requestID" class="form-control col-md-7 col-xs-12" required="required" type="text"/>
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Job Title 
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input id="job_title" name="job_title" class="form-control col-md-7 col-xs-12" required="required" type="text">
                                  <input id="thislocation" style="display: none;" name="location" class="form-control col-md-7 col-xs-12" required="required" type="text">
                                  <input id="thisprioritylevel" style="display: none;" name="priority_level" class="form-control col-md-7 col-xs-12" required="required" type="text">
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-3">Job Description <span class="required">*</span>
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                               <textarea class="form-control" name='job_description' id ="job_description"></textarea>
                              </div>
                            </div>
                            <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Qualification Required
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <textarea class="form-control" name="qualification" id="qualification"></textarea>
                                    </div>                                
                            </div>
                            <div class="form-group">
                              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button type="submit" name="updatesubmit" class="btn btn-success">Submit</button>
                              </div>
                            </div>

                        </form>
                      </div>
                    </div>
                </div> 
              </div>
            </div>
            <div id="home3" class="tab-pane fade">
             <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                      <div class="x_title">
                        <h2>Upload Editted List</h2>
                        <div class="clearfix"></div>
                      </div>

                      <div class="x_content">
                        <br />
                          <form action="recruitment.php" method="POST" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                          
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                                 Upload File (Excel sheet Only)
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input type="file" name ="file" id="upload" class="form-control col-md-7 col-xs-12">
                                  <input type="text" style="display: none;" name ="requestID" id="uploadRequestID" class="form-control col-md-7 col-xs-12">
                              </div>
                            </div>
                            <div class="form-group" style="text-align: center;">
                              <button type="submit" name="submitfile" class="btn btn-success">Submit</button>
                            </div>
                          </form>
                      </div>
                    </div>
                </div> 
              </div>
            </div>
            <div id="home4" class="tab-pane fade">
             <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                      <div class="x_title">
                        <h2>Share shortlisted List with Client</h2>
                        <div class="clearfix"></div>
                      </div>

                      <div class="x_content">
                        <br />
                          <form action="recruitment.php" method="POST" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                          
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                                 Client Email
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input type="email" name ="client_email" class="form-control col-md-7 col-xs-12">
                                  <input type="text" style="display: none;" name ="requestID" id='clientID' class="form-control col-md-7 col-xs-12">
                              </div>
                            </div>
                            <div class="form-group" style="text-align: center;">
                              <button type="submit" name="clientsubmit" class="btn btn-success">Submit</button>
                            </div>
                          </form>
                      </div>
                    </div>
                </div> 
              </div>
            </div>


          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="docModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Begin Decumentation</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <div style="padding: 10px;">
          <h4>Share list of Successful candidate with Recruitment Officer to begin Documentation</h4>
        </div>
        <form action="recruitment.php" method="POST" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
        
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Request ID<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="requestID" class="form-control">
                     <option value=""></option>
                     <?php for ($r = 0; $r < count($recruitment);$r++) {?>
                        <option value="<?=$recruitment[$r]['requestID']?>"><?=$recruitment[$r]['requestID']?></option>
                     <?php } ?>
                  </select>
              </div>
            </div>              
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="submit" name="begin_doc" class="btn btn-success">Share</button>
                </div>
              </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php include "footer.php"?>
<script type="text/javascript">
  let request = "<?php echo $error ?>";
  let show_msg = "<?php echo $show_msg ?>";
  let show_list = "<?php echo $show_list ?>";
  let selected = [];
  let selectedrow = [];
  if(request == 1){
     $('.request').trigger('click');
  }
  if(show_list == 1){
    $('.processData').trigger('click');
    if($("#file_requestID").val() != '')
       localStorage.setItem('file_requestID',$("#file_requestID").val());
    if($("#toeditfile").val() != '')
       localStorage.setItem('final_filename',$("#toeditfile").val()); 
    if($("#file_requestID").val() == '')
       $("#file_requestID").val(localStorage.getItem('file_requestID'));
    if($("#toeditfile").val() == '')
       $("#toeditfile").val(localStorage.getItem('final_filename')); 
  }
  if(show_msg == 1){
     $('.requests').trigger('click');
     let job_title = localStorage.getItem('job_title');
     let job_description = localStorage.getItem('job_description');
    let qualification = localStorage.getItem('qualification');
    let requestID = localStorage.getItem('requestID');
    let filename = localStorage.getItem('filename');
    $("#job_title").val(job_title);
    $("#job_description").val(job_description);
    $("#qualification").val(qualification);
    $('#requestNo').val(requestID);
    $('#thisrequestID').val(requestID);
    $('#thisfilename').val(filename);
    $('#uploadRequestID').val(requestID);
    $('#clientID').val(requestID);
    $('#interviewrequestID').val(requestID);
  }
  $('.requests').on("click", function(e){
    e.preventDefault();
    let job_title = $('#'+this.id+"").attr('job_title');
    let job_description = $('#'+this.id+"").attr('job_description');
    let qualification = $('#'+this.id+"").attr('qualification');
    let requestID = $('#'+this.id+"").attr('requestID');
    let filename = $('#'+this.id+"").attr('filename');
    let location = $('#'+this.id+'').attr('location');
    let priority_level = $('#'+this.id+'').attr('priority_level');
    localStorage.setItem('job_title',job_title);
    localStorage.setItem('job_description',job_description);
    localStorage.setItem('qualification',qualification);
    localStorage.setItem('requestID',requestID);
    localStorage.setItem('filename',filename);
    localStorage.setItem('location',location);
    localStorage.setItem('priority_level',priority_level);
    //localStorage.setItem('location',location);
    $("#job_title").val(job_title);
    $("#job_description").val(job_description);
    $("#qualification").val(qualification);
    $('#requestNo').val(requestID);
    $('#thisrequestID').val(requestID);
    $('#thisfilename').val(filename);
    $('#thislocation').val(location);
    $('#thisprioritylevel').val(priority_level);
    $('#uploadRequestID').val(requestID);
    $('#clientID').val(requestID);
    $('#interviewrequestID').val(requestID);
    //alert(filename);
  });
  $(".status").on('change', function(e){
    e.preventDefault();
    let v = $("#"+this.id+"").attr('position');
    selected.push($("#"+this.id+"").val());
    selectedrow.push(v);
    $("#allstatus").val(selected.join("###"));
    $("#allstatusnumber").val(selectedrow.join("###"));

  });
</script>

        

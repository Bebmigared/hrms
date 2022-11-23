<?php
 include "connection.php";
 include "process_email.php";
 session_start();
  if(!isset($_SESSION['user']['id'])) header("Location: login.php");

  if(!isset($_SESSION['user'])) {
    if($_SERVER['SERVER_NAME'] == 'localhost')
        header("location: /newhrcore/login");
    else
        header("location: /login");
    exit();
  }

 if(isset($_POST['submit'])){
  $flow = mysqli_real_escape_string($conn, $_POST['flow']);
  if($_SESSION['user']['appraisal_flow'] == '' && $flow == ''){
     $_SESSION['msg'] = "You can not process appraisal, because you don't have approvals. Kindly contact the admin";
     header("Location: staff_appraisal.php");
     return false;
  }
  if($flow != ''){
      $sql = "UPDATE users SET appraisal_flow = '".$flow."' WHERE id = '".$_SESSION['user']['id']."'";
      if (mysqli_query($conn, $sql)) {
        $_SESSION['user']['appraisal_flow'] = $flow;
      } else {
        header("Location: staff_appraisal.php");
        exit();
      }
     //return false;
   }
 	$appraisal_id = mysqli_real_escape_string($conn, $_POST['appraisal_id']);
 	$all_remark = mysqli_real_escape_string($conn, $_POST['all_remark']);
 	$all_justification = mysqli_real_escape_string($conn, $_POST['all_justification']);
  $appraisal = mysqli_real_escape_string($conn, $_POST['appraisallist']);
  $appraisal_flow = getappraisal_flow($conn);
 	 $sql = "INSERT INTO appraisal_replies (appraisal_id, responses, staff_id, admin_id, staff_remarks, staff_justifications, date_created, comments_flow, companyId,appraisal_replies_flow)
          VALUES ('".$appraisal_id."','".$appraisal."', '".$_SESSION['user']['id']."', '".$_SESSION['user']['admin_id']."','".$all_remark."','".$all_justification."','".date('Y-m-d')."','".$appraisal_flow."', '".$_SESSION['user']['companyId']."','".$_SESSION['user']['appraisal_flow']."')";
            if (mysqli_query($conn, $sql)) {
               if($_SESSION['user']['appraisal_flow'] == ""){ 
               $_SESSION['msg'] = "Appraisal under processing";
               $_SESSION['is_just_filled'] = true;
               header("Location: staff_appraisal.php");return false;}
               $approvals = explode(";",$_SESSION['user']['appraisal_flow']);
               if(count($approvals) == 0) { 
               $_SESSION['msg'] = "Appraisal under processing";
               $_SESSION['is_just_filled'] = true;
               header("Location: staff_appraisal.php");return false;}
               $get_first_approval_details = explode(":",$approvals[0]);
               if(count($get_first_approval_details) > 1) $get_first_approval_email = $get_first_approval_details[1];
               $msg = "<div><p>Good Day,</p><p>".$_SESSION['user']['name']." has completed the appraisal for the period. As the ".$get_first_approval_details[0].", kindly log In and add your remark to this staff appraisal.</p><p><a  style ='padding:7px;color:#fff;background-color:#4e73df;margin:10px;border-radius: 3px;text-decoration:none' href = 'http://www.hrcore.ng'>Log In to view</a></p></div>";
               if (filter_var($get_first_approval_email, FILTER_VALIDATE_EMAIL)) {
                  process_data($conn,$get_first_approval_email,$msg,'Appraisal');
                   $_SESSION['msg'] = "Appraisal under processing";
                   $_SESSION['is_just_filled'] = true;
                   header("Location: staff_appraisal.php");
                }
              /*$_SESSION['msg'] = "Appraisal under processing";
              $_SESSION['is_just_filled'] = true;
              header("Location: /selfservice/staff_appraisal.php");*/
            }else {
              //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
               $_SESSION['msg'] = "Error whiling saving appraisal, please try again later";
               //exit();
               header("Location: staff_appraisal.php");
           }
 }

 if(isset($_POST['submit_data_manager']))
 {
    $appraisal_id = mysqli_real_escape_string($conn, $_POST['appraisal_id']);
    $staff_id = mysqli_real_escape_string($conn, $_POST['staff_id']);
    $appraisal = mysqli_real_escape_string($conn, $_POST['managerappraisallist']);
    $staff = staff_flow($conn,$staff_id);
     $sql = "update appraisal_replies SET responses = '".$appraisal."' WHERE (appraisal_id = '".$appraisal_id."' AND staff_id = '".$staff_id."')";
              if (mysqli_query($conn, $sql)) {
                 $approvals = explode(";",$staff['appraisal_flow']);
                 $there_is_next_approval = 0;
                 $send_to_next_approval = 0;
                 for($s = 0; $s < count($approvals); $s++){
                  $data = explode(":", $approvals[$s]);
                  if(count($data) > 0) $who = $data[0];
                  
                  if($send_to_next_approval == 1) {
                    $send_to_next_approval = 0;
                    $there_is_next_approval = 1;
                    $next_approval = $data;
                    if(count($next_approval) > 1){
                      $email = $next_approval[1];
                      
                      $msg = "<div><p>Good Day,</p><p>".$staff['name']." has completed the appraisal for the period. As the ".$next_approval[0].", kindly log In and add your remark to this staff appraisal.</p><p><a  style ='padding:7px;color:#fff;background-color:#4e73df;margin:10px;border-radius: 3px;text-decoration:none' href = 'https://www.hrcore.ng'>Log In to view</a></p></div>";
                      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        process_data($conn,$email,$msg,'Appraisal');
                         $_SESSION['msg'] = "Thank you for the feedback";
                         //$_SESSION['is_just_filled'] = true;
                      }
                    }
                  }
                  if(strtolower($who) == strtolower($_SESSION['user']['position'])) $send_to_next_approval = 1;
                }
                if($there_is_next_approval == 0)
                {
                   
                    $msg = "<div><p>Good Day,</p><p>All your Managers have appraised you, kindly log In and see what was said about you</p><p><a  style ='padding:7px;color:#fff;background-color:#4e73df;margin:10px;border-radius: 3px;text-decoration:none' href = 'https://www.hrcore.ng'>Log In to view</a></p></div>";
                      if (filter_var($staff['email'], FILTER_VALIDATE_EMAIL)) {
                        process_data($conn,$staff['email'],$msg,'Appraisal');
                         $_SESSION['msg'] = "Thank you for the feedback";
                         //$_SESSION['is_just_filled'] = true;
                      }
                }
                 if($_SERVER['SERVER_NAME'] == 'localhost')
                    header("Location: /newhrcore/approval_appraisal_view.php");
                 else
                    header("Location: /approval_appraisal_view.php");
                
              }else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                 $_SESSION['msg'] = "Error whiling saving appraisal, please try again later";
                 exit();
                 header("Location: staff_appraisal.php");
             }
 }
 function getappraisal_flow($conn){
  $data = [];
  $app_query = "SELECT * FROM company WHERE id = '".$_SESSION['user']['companyId']."'";
    $result = mysqli_query($conn, $app_query);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data[0]['appraisal_flow'];
    }
    return '';
}

 function staff_flow($conn, $staff_id){
  $data = [];
  $app_query = "SELECT * FROM users WHERE id = '".$staff_id."'";
    $result = mysqli_query($conn, $app_query);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data[0];
    }
    return '';
}
?>
<?php
  include "connection.php";
  include "connectionpdo.php";
  include "process_email.php";
  session_start();
   if(!isset($_SESSION['user']['id'])) header("Location: login.php");
  $msg = '';
  if(!isset($_SESSION['user'])) {
    if($_SERVER['SERVER_NAME'] == 'localhost')
        header("location: /newhrcore/login");
    else
        header("location: /login");
    exit();
  }
  if(isset($_POST['submit'])){
    $flow = mysqli_real_escape_string($conn, $_POST['flow']);

    if($_SESSION['user']['leave_flow'] == '' && $flow == ''){
     $_SESSION['msg'] = "You can not process leave request, because you don't have approvals. Contact your Administrator";
     header("Location: leave_request.php");
     return false;
    }

   //  if($flow != ''){
   //    $sql = "UPDATE users SET leave_flow = '".$flow."' WHERE id = '".$_SESSION['user']['id']."'";
   //    if (mysqli_query($conn, $sql)) {
   //      $_SESSION['user']['leave_flow'] = $flow;
   //    } else {
   //      header("Location: leave_request.php");
   //      exit();
   //    }
   //   //return false;
   // }

   if($flow != '')
   {
      $sql = "UPDATE users SET leave_flow = ? WHERE id = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$flow, $_SESSION['user']['id']]);
      $_SESSION['user']['leave_flow'] = $flow;

   }else 
   {
      $flow = $_SESSION['user']['leave_flow'];
   }
    
     $leave_day = mysqli_real_escape_string($conn, $_POST['leave_day']); 
     $leave_type = mysqli_real_escape_string($conn, $_POST['leave_type']);
     $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
     $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
     $justification = mysqli_real_escape_string($conn, $_POST['justification']);
     $reliever_required = mysqli_real_escape_string($conn, $_POST['reliever_required']);
     $reliever_source = mysqli_real_escape_string($conn, $_POST['reliever_source']);
     $reliever_name = mysqli_real_escape_string($conn, $_POST['reliever_name']);
     $reliever_email = mysqli_real_escape_string($conn, $_POST['reliever_email']);
     $reliever_phone = mysqli_real_escape_string($conn, $_POST['reliever_phone']);

     if($leave_type == ''){
        $_SESSION['msg'] = 'kindly specify the type of leave';
        header("Location: leave_request.php");
        return false;
     }
     if($start_date == ''){
        $_SESSION['msg'] = 'kindly specify the starting date';
        header("Location: leave_request.php");
        return false;
     }
     if($end_date == ''){
        $_SESSION['msg'] = 'kindly specify the end date';
        header("Location: leave_request.php");
        return false;
     }
     if(strtotime($start_date) < strtotime(date('Y-m-d'))){
        $_SESSION['msg'] = 'Please select the appropriate leave Start Date';
        header("Location: leave_request.php");
        return false;
      }
      if(strtotime($end_date) < strtotime($start_date)){
        $_SESSION['msg'] = 'Please select the appropriate leave End Date';
        header("Location: leave_request.php");
        return false;
      }
      if(date("N",strtotime($start_date))>5){
          $_SESSION['msg'] = 'You can not start your leave on Saturday or Sunday, kindly select the appropriate start Date';
          header("Location: leave_request.php");
          return false;
      }
      if(date("N",strtotime($end_date))>5){
          $_SESSION['msg'] = 'You can not end your leave on Saturday or Sunday, kindly select the appropriate End Date';
          header("Location: leave_request.php");
          return false;
      }
      $total_leaves_gones = 0;
      $assigneddate = 0;


      $stmt = $pdo->prepare("SELECT sum(number_of_days) as ndays FROM leaves WHERE staff_id = ? AND leave_type = ? AND year = ? AND stage !='decline'");
      $stmt->execute([$_SESSION['user']['id'],$leave_type, date('Y')]); 
      $t_leaves_gones = $stmt->fetchAll();

      $total_leaves_gones = isset($t_leaves_gones[0]['ndays']) ? $t_leaves_gones[0]['ndays'] : $total_leaves_gones;

      $stmt = $pdo->prepare("SELECT sum(days) as days FROM leave_type WHERE grade = ? AND leave_kind = ?");
      $stmt->execute([$_SESSION['user']['grade'],$leave_type]); 
      $adate = $stmt->fetchAll();
      
      $assigneddate = isset($adate[0]['days']) ? $adate[0]['days'] : $assigneddate;


      if($assigneddate == 0)
      {
         $_SESSION['msg'] = "No Leave Type Set, Contact the Admin";
         header("Location: leave_request.php");
         return false;
      }
        $gone = $total_leaves_gones;
        $total_leaves = $total_leaves_gones + (int)$leave_day;
        if($total_leaves > (int)$assigneddate){
            $left = (int)$assigneddate - $gone;
            $_SESSION['msg'] = "You cannot apply for ".$leave_day." days Leave, You have ".$left." days left for ".$leave_type."  Leave";
            header("Location: leave_request.php");
            return false;
        }

     $pdo->beginTransaction();
     
     try{

            $sql = "INSERT INTO leaves (leave_type, start_date, end_date, justification, require_reliever, reliever_source,reliever_name,reliever_email,reliever_phone,stage,status,date_created,staff_id,admin_id,flow,year,number_of_days, flowstatus, leave_flow, companyId) VALUES (?, ?,?, ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$leave_type, $start_date, $end_date,$justification,$reliever_required,$reliever_source,$reliever_name,$reliever_email,$reliever_phone,'','',date('Y-m-d'),$_SESSION['user']['id'],$_SESSION['user']['admin_id'], $_SESSION['user']['leave_flow'],date('Y'),$leave_day, '',$_SESSION['user']['leave_flow'], $_SESSION['user']['companyId']]);

            $requestId = $pdo->lastInsertId();

            $requestflow =  explode(";",$_SESSION['user']['leave_flow']);
            $firstapproval = '';
           
            for($r = 0; $r < count($requestflow); $r++)
            {
              $title = explode(":", $requestflow[$r])[0];
              $id = explode(":", $requestflow[$r])[1];
              if($r == 0) $firstapproval = $id;
              $sql = "INSERT INTO approval_flows (title,type,approvalId, level,requestId,date_created) VALUES (?, ?,?, ?,?,?)";
              $stmt = $pdo->prepare($sql);
              $stmt->execute([$title,'Leave Request',$id,($r+1),$requestId,date('Y-m-d')]);

            }


            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$firstapproval]); 
            $user = $stmt->fetch();

            if(isset($user['id']))
            {
               $msg = "<div><p>Good Day,</p><p>".$_SESSION['user']['name']." has requested for ".$leave_type." leave.  kindly log In and add your remark to this staff Request.</p><p><a  style ='padding:7px;color:#fff;background-color:#4e73df;margin:10px;border-radius: 3px;text-decoration:none' href = 'http://www.ics.hrcore.ng'>Log In to view</a></p></div>";
              process_data($conn,$user['email'],$msg,'Leave Request');

            }

              $pdo->commit();
              $_SESSION['msg'] = "Your request is being processed";
              header("Location: leave_request.php");
     }catch(Exception $e)
     {
          $pdo->rollBack();
          $_SESSION['msg'] = "Error whiling processng data";
      throw $e;
          //header("Location: leave_request.php");
     }    
   
  }
  //echo "<script> window.location.href = 'leave_request.php'</script>";
?>
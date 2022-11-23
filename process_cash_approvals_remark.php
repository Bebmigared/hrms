<?php
  include "connectionpdo.php";
  include "process_email.php";
  session_start();
   if(!isset($_SESSION['user']['id'])) header("Location: login.php");
  $stage = "pending";
  $user = [];
  $item = [];
    if($_GET['status'] == '' || $_GET['item_id'] == '' || $_GET['approvalflowId'] == '') 
    {
        $_SESSION['msg'] = "Aproval Status is required";
        if($_SERVER['SERVER_NAME'] != 'localhost')
            header("Location: /cash_remark.php");
        else
          header("Location: /newhrcore/cash_remark.php");  
    }
      $status = base64_decode(mysqli_real_escape_string($conn, $_GET['status']));
      $approvalflowId = base64_decode(mysqli_real_escape_string($conn, $_GET['approvalflowId']));
      $item_id = base64_decode(mysqli_real_escape_string($conn, $_GET['item_id']));
      $remark = base64_decode(mysqli_real_escape_string($conn, $_GET['remark']));




      $sql = "UPDATE approval_flows SET status = ?, remark = ?, date_accessed = ? WHERE id = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$status,trim($remark), date('Y-m-d'), $approvalflowId]);



     $stmt = $pdo->prepare("SELECT * FROM approval_flows INNER JOIN cash_request ON cash_request.id = approval_flows.requestId INNER JOIN users ON users.id = cash_request.staff_id WHERE approval_flows.id = ?");
      $stmt->execute([$approvalflowId]); 
      $requests = $stmt->fetch();
 
      
      if(isset($requests))
      {

        $nextlevel = (int)$requests['level'] + 1;
        $stmt = $pdo->prepare("SELECT * FROM approval_flows INNER JOIN users ON users.id = approval_flows.approvalId WHERE requestId = ? AND level = ?");
        $stmt->execute([$item_id, $nextlevel]); 
        $nextapproval = $stmt->fetch();
        
     

        if(isset($nextapproval['id']) && $status == 'approved')
        {
          $sql = "UPDATE cash_request SET status = ?, stage = ? WHERE id = ?";
          $stmt = $pdo->prepare($sql);
          $stmt->execute(["Pending with ".$nextapproval['name'],$nextapproval['title'], $item_id]);

            $msg = "<div><p>Good Day,</p><p>".$requests['name']."  ".$requests['fname']." has requested for Cash, kindly log in to account to view  more detail about the request.</p><p><a  style ='padding:7px;color:#fff;background-color:#4e73df;margin:10px;border-radius: 3px;text-decoration:none' href = 'https://www.hrcore.ng'>Log In to view</a></p></div>";

            $msg2 = "<div><p>Good Day,</p><p>Your request is Pending with ".$nextapproval['name'].". kindly log in to account to view  more detail about the request.</p><p><a  style ='padding:7px;color:#fff;background-color:#4e73df;margin:10px;border-radius: 3px;text-decoration:none' href = 'https://www.hrcore.ng'>Log In to view</a></p></div>";
                  if (filter_var($nextapproval['email'], FILTER_VALIDATE_EMAIL)) {
                    process_data($conn,$nextapproval['email'],$msg,'Item Request');
                    process_data($conn,$requests['email'],$msg2,'Item Request');
                  }
        }else {
          $sql = "UPDATE cash_request SET status = ?, stage = ? WHERE id = ?";
          $stmt = $pdo->prepare($sql);
          $stmt->execute([$status,$status, $item_id]);
           $msg2 = "<div><p>Good Day,</p><p>Your request with ID (".$requests['item_id'].") is ".$status.". kindly log in to account to view  more detail about the request.</p><p><a  style ='padding:7px;color:#fff;background-color:#4e73df;margin:10px;border-radius: 3px;text-decoration:none' href = 'https://www.hrcore.ng'>Log In to view</a></p></div>";
                  if (filter_var($requests['email'], FILTER_VALIDATE_EMAIL)) {
                    process_data($conn,$requests['email'],$msg2,'Item Request');
                  }
        }
      }

      $_SESSION['msg'] = "Request Information Updated Successfully";
    
      if($_SERVER['SERVER_NAME'] != 'localhost')
        header("Location: /cash_remark.php");
      else
        header("Location: /newhrcore/cash_remark.php");  
      
?>
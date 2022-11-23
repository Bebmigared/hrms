<?php
include 'connection.php';
include 'connectionpdo.php';
include "process_email.php";
session_start();
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
 $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
 $justification = mysqli_real_escape_string($conn, $_POST['justification']);
 $item_quantity = mysqli_real_escape_string($conn, $_POST['item_quantity']);
 $item_cost = mysqli_real_escape_string($conn, $_POST['item_cost']);

 $company_name = $_SESSION['user']['company_name'];
 $month = date('m');
 $year = date('y');
 $string_company_name = strtoupper(substr($company_name, 0, 3));
 $admin_id = $_SESSION['user']['admin_id'];

 
 //Do row count for total company items
 
 $query ="SELECT item_name FROM items WHERE id = '$item_name'";
 $result = mysqli_query($conn, $query);
  while($row = mysqli_fetch_assoc($result)) {

     $itemname[] = $row;
  //print_r($sumitem);
  }

 $stmt = $pdo->prepare("SELECT item_id FROM requesteditem WHERE companyId = ?");
 $stmt->execute([$_SESSION['user']['companyId']]); 
 $result = $stmt->fetchAll();
 $result = count($result) + 1;
 $new_item_id = sprintf("%05d", $result);

 //$new_item_id = $total + 1;
 $item_id = $string_company_name .'/'. $month. '/' . $year . '/' . $new_item_id;
 $flow = mysqli_real_escape_string($conn, $_POST['flow']);
 $flow = $flow == '' ? $_SESSION['user']['requisition_flow'] : $flow;

 if(isset($_POST['submit'])){
   if($_SESSION['user']['requisition_flow'] == '' && $flow == ''){
     $_SESSION['msg'] = "You can not process requisition, because you don't have approvals. Contact your admin";
     header("Location: requestitems.php");
     //header("Location: staff_settings.php");
     return false;
   }

   if($item_name == ''){
     $_SESSION['msg'] = 'No item selected';
      header("Location: requestitems.php");
      return false;
   }
   if($item_quantity == ''){
     $_SESSION['msg'] = 'Please select the required quantity';
     header("Location: requestitems.php");
     return false;
   }


   if($flow != '')
   {
      $sql = "UPDATE users SET requisition_flow = ? WHERE id = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$flow, $_SESSION['user']['id']]);
      $_SESSION['user']['requisition_flow'] = $flow;

   }


   $stmt = $pdo->prepare("SELECT * FROM items WHERE companyId = ? AND id = ?");
   $stmt->execute([$_SESSION['user']['companyId'], $item_name]); 
   $item = $stmt->fetch();
   
    if((int)$item['item_quantity'] < (int)$item_quantity){
        $_SESSION['msg'] = "We don't have enough ".$item['item_name']." in the inventory. There are only ".$item['item_quantity']." left.";
        header("Location: requestitems.php");
        return false;
    }

  $pdo->beginTransaction();
  
  try {


    $sql = "INSERT INTO requesteditem (item,item_id, justification, quantity, cost, date_created,staff_id, status,flow,admin_id,companyId, requisition_flow) VALUES (?, ?,?, ?,?,?,?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$item_name,$item_id,$justification,$item_quantity,$item_cost,date('Y-m-d'),$_SESSION['user']['id'],'pending',$flow, $admin_id, $_SESSION['user']['companyId'], $flow]);
    $requestId = $pdo->lastInsertId();

    $requestflow =  explode(";",$_SESSION['user']['requisition_flow']);
    $firstapproval = '';
    for($r = 0; $r < count($requestflow); $r++)
    {
      $title = explode(":", $requestflow[$r])[0];
      $id = explode(":", $requestflow[$r])[1];
      if($r == 0) $firstapproval = $id;
      $sql = "INSERT INTO approval_flows (title,type,approvalId, level,requestId,date_created) VALUES (?, ?,?, ?,?,?)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$title,'Item Request',$id,($r+1),$requestId,date('Y-m-d')]);

    }


    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$firstapproval]); 
    $user = $stmt->fetch();

    if(isset($user['id']))
    {
      $msg = "<div><p>Good Day,</p><p>".$_SESSION['user']['name']." has requested for ".$itemname.", kindly log in to account to view  more detail about the request.</p><p><a  style ='padding:7px;color:#fff;background-color:#4e73df;margin:10px;border-radius: 3px;text-decoration:none' href = 'http://www.ics.hrcore.ng'>Log In to view</a></p></div>";
      process_data($conn,$user['email'],$msg,'Request Item');

    }

    $pdo->commit();
    $_SESSION['msg'] = "Your request is being processed";
    header("Location: requestitems.php");
  } catch(Exception $e)
  {
      $pdo->rollBack();
      //$_SESSION['msg'] = "Error whiling processng data";
      
      
      
      throw $e;
      header("Location: requestitems.php");
  } 
}


?>
<?php
include "connection.php";
include "connectionpdo.php";
include "process_email.php";
session_start();

$company_name = $_SESSION['user']['company_name'];
$month = date('m');
$year = date('y');
$string_company_name = strtoupper(substr($company_name, 0, 3));
$admin_id = $_SESSION['user']['admin_id'];



//Do row count for total company items


$stmt = $pdo->prepare("SELECT cash_id FROM cash_request WHERE companyId = ?");
$stmt->execute([$_SESSION['user']['companyId']]); 
$result = $stmt->fetchAll();
$result = count($result) + 1;
$new_item_id = sprintf("%05d", $result);

//$new_item_id = $total + 1;
$cash_id = $string_company_name .'/'. $month. '/' . $year . '/' . $new_item_id;

 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
if(isset($_POST['submit'])){
	$purpose = mysqli_real_escape_string($conn, $_POST['purpose']);
	$justification = mysqli_real_escape_string($conn, $_POST['justification']);
	$amount = mysqli_real_escape_string($conn, $_POST['amount']);
	$admin_id = $_SESSION['user']['category'] == 'staff' ? $_SESSION['user']['admin_id'] : $_SESSION['user']['id'];
	$staff_id = $_SESSION['user']['id'];
  $flow = mysqli_real_escape_string($conn, $_POST['flow']);


   if($purpose == ''){
     $_SESSION['msg'] = 'No Category selected';
      header("Location: make_request.php");
      return false;
   }

   if($flow != '')
   {
      $sql = "UPDATE users SET cash_flow = ? WHERE id = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$flow, $_SESSION['user']['id']]);
      $_SESSION['user']['cash_flow'] = $flow;

   }


  $pdo->beginTransaction();
  
  try {
      $sql = "INSERT INTO cash_request (cash_id, purpose, justification,amount,staff_id, admin_id,flow,date_created, companyId, cash_flow, status) VALUES (?, ?,?, ?,?,?,?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cash_id, $purpose, $justification,$amount,$staff_id,$admin_id,$_SESSION['user']['cash_flow'],date('Y-m-d'), $_SESSION['user']['companyId'],$_SESSION['user']['cash_flow'],'pending']);
    $requestId = $pdo->lastInsertId();

    $requestflow =  explode(";",$_SESSION['user']['cash_flow']);
    $firstapproval = '';
    for($r = 0; $r < count($requestflow); $r++)
    {
      $title = explode(":", $requestflow[$r])[0];
      $id = explode(":", $requestflow[$r])[1];
      if($r == 0) $firstapproval = $id;
      $sql = "INSERT INTO approval_flows (title,type,approvalId, level,requestId,date_created) VALUES (?, ?,?, ?,?,?)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$title,'Cash Request',$id,($r+1),$requestId,date('Y-m-d')]);

    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$firstapproval]); 
    $user = $stmt->fetch();

    if(isset($user['id']))
    {
       $msg = "<div><p>Good Day,</p><p>".$_SESSION['user']['name']." has requested for Cash, kindly log in to account to view  more detail about the request.</p><p><a  style ='padding:7px;color:#fff;background-color:#4e73df;margin:10px;border-radius: 3px;text-decoration:none' href = 'http://www.hrcore.ng'>Log In to view</a></p></div>";
      process_data($conn,$user['email'],$msg,'Cash Item');

    }

    processattach_document($conn, $_FILES['attach_document'],$admin_id,$requestId);

    $pdo->commit();
    $_SESSION['msg'] = "Your request is being processed";
    header("Location: make_request.php");

  }catch(Exception $e)
  {
      $pdo->rollBack();
      $_SESSION['msg'] = "Error whiling processng data";
      //throw $e;
      header("Location: make_request.php");
  }

	// $last_id = 0;

	// $sql = "INSERT INTO cash_request (cash_id, purpose, justification,amount,staff_id, admin_id,flow,date_created, companyId, cash_flow)
 //      VALUES ('".$cash_id."', '".$purpose."', '".$justification."','".$amount."','".$staff_id."','".$admin_id."','".$_SESSION['user']['cash_flow']."','".date('Y-m-d')."', '".$_SESSION['user']['companyId']."','".$_SESSION['user']['cash_flow']."')";
 //  $flow = mysqli_real_escape_string($conn, $_POST['flow']);

 //  if($_SESSION['user']['cash_flow'] == '' && $flow == ''){
 //     $_SESSION['msg'] = "You can not process cash, because you don't have approvals. Contact your admin";
 //     header("Location: make_request.php");
 //     //header("Location: staff_settings.php");
 //     return false;
 //   }

 //        if (mysqli_query($conn, $sql)) {
 //          $_SESSION['msg'] = "Your Cash request is under processing";
 //          $last_id = $conn->insert_id;
 //        }else {
 //        	//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
 //        	//return false;
 //           $_SESSION['msg'] = "Error while saving data, please try again later";
 //       }
 //  	   $approvals = explode(";",$_SESSION['user']['cash_flow']);
 //  	   if(count($approvals) == 0) { 
 //       $_SESSION['msg'] = "Your Cash request is being processed";
 //       //header("Location: requestitems.php");return false;
 //       }else {
 //          $get_first_approval_details = explode(":",$approvals[0]);
          
 //          if(count($get_first_approval_details) > 1) $get_first_approval_email = $get_first_approval_details[1];
 //           //include "process_email.php";
 //           $msg = "<div><p>Good Day,</p><p>".$_SESSION['user']['name']." has requested for Cash, kindly log in to account to view  more detail about the request.</p><p><a  style ='padding:7px;color:#fff;background-color:#4e73df;margin:10px;border-radius: 3px;text-decoration:none' href = 'http://www.hrcore.ng'>Log In to view</a></p></div>";
            
 //           if (filter_var($get_first_approval_email, FILTER_VALIDATE_EMAIL)) {
 //              process_data($conn,$get_first_approval_email,$msg,'Cash Request');
               
 //               $_SESSION['msg'] = "Your Cash request is being processed";
 //               //header("Location: requestitems.php");
 //            } 
 //       }

 //       if($_FILES['attach_document']['name'] != null)
 //          processattach_document($conn, $_FILES['attach_document'],$admin_id,$last_id);
 //       else 
 //       {
 //          header("Location: make_request");
 //       }
}
function processattach_document($conn,$attach_document,$admin_id,$last_id){
  
  	if(isset($attach_document)){
       if(!isset($attach_document['name'])) {

       		  header("Location: make_request");
       }else {

       $error = array(); 
       $file_ext = explode('.',$attach_document['name'])[1];
       $img = explode('.',$attach_document['name'])[0].'_'.strtotime(date('Y-m-d'));
       $file_name = $img.'.'.$file_ext;
       $file_size = $attach_document['size'];
       $file_tmp = $attach_document['tmp_name'];
       $file_type = $attach_document['type'];
       //$file_ext = explode('.',$_FILES['attach_document']['name'])[1];
       $extensions = array('jpeg','jpg','png','doc','docx','pdf','txt','csv','xlsx');
       if(in_array($file_ext,$extensions) === false){
        $errors[] = "extension not allowed, please select a JPEG, JPG, DOC, DOCX,PDF,XLSX,CSV or PNG file.";
       }
       if($file_size > 2009752){
        $errors[] = "File size too large";
       }
       if(empty($errors)==true){
         move_uploaded_file($file_tmp,"document/".$file_name);
         //$last_id = $conn->insert_id;
          $sql = "UPDATE cash_request SET document = ? WHERE id = ?";
          $stmt = $pdo->prepare($sql);
          $stmt->execute([$file_name, $last_id]);
          header("Location: make_request");
          // $sql = "UPDATE cash_request SET document = '".$file_name."' Where id = '".$last_id."'";
          // if (mysqli_query($conn, $sql)) {
          //     //$_SESSION['msg'] = $last_id;
          // 	header("Location: make_request");
          // }else {$_SESSION['msg'] = 'Error updating data'; return false;}
       }else {
         $msg = $errors[0];
         $_SESSION['msg'] = $msg;
         header("Location: make_request");
       }
       }
    }else {
        header("Location: make_request");
    }
  }

?>
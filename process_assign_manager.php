<?php
include "connection.php";
session_start();
if(!isset($_SESSION['user'])) header('location: login.php');

$appraisal = [];
$leave = [];
$cash = [];
$requisition = []; 

$query = "SELECT * from company WHERE id = '".$_SESSION['user']['companyId']."'";
$result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
        $appraisal = explode(";", $row['appraisal_flow']);
        $leave = explode(";", $row['leave_flow']);
        $cash = explode(";", $row['cash_flow']);
        $requisition = explode(";", $row['requisition_flow']);
    }
}

if(isset($_POST['submit']))
{
	 //$position = mysqli_real_escape_string($conn, $_POST['position']);
	 $createleaveflow = mysqli_real_escape_string($conn, $_POST['createleaveflow']);
	 $createreqflow = mysqli_real_escape_string($conn, $_POST['createreqflow']);
	 $createappraisalflow = mysqli_real_escape_string($conn, $_POST['createappraisalflow']);
	 $createcashflow = mysqli_real_escape_string($conn, $_POST['createcashflow']);
	 $department = mysqli_real_escape_string($conn, $_POST['department']);
	 $flow = mysqli_real_escape_string($conn, $_POST['flow']);


	 if($flow == '')
	 {
	 	$_SESSION['msg'] = 'Kindly select a flow';
	 	header("Location: assign_manager.php");
	 	exit();
	 }

	 if($department == '')
	 {
	 	$_SESSION['msg'] = 'Kindly select a flow';
	 	header("Location: assign_manager.php");
	 	exit();
	 }

	 if($flow == 'Requisition flow')
	 {
	 	if($createreqflow == '' || $createreqflow == null || $createreqflow == undefined)
	 	{
	 		$_SESSION['msg'] = 'Unknown Requisition Approval flow';
	 		header("Location: assign_manager.php");
	 		exit();
	 	}
	 	$sql = "UPDATE users SET requisition_flow = '".$createreqflow."' WHERE department = '".$department."' AND companyId = '".$_SESSION['user']['companyId']."'";
	 }
	 else if($flow == 'Cash flow')
	 {
	 	if($createcashflow == '' || $createcashflow == null || $createcashflow == undefined)
	 	{
	 		$_SESSION['msg'] = 'Unknown Cash Approval flow';
	 		header("Location: assign_manager.php");
	 		exit();
	 	}
	 	$sql = "UPDATE users SET cash_flow = '".$createcashflow."' WHERE department = '".$department."' AND companyId = '".$_SESSION['user']['companyId']."'";
	 }
	 else if($flow == 'Appraisal flow')
	 {
	 	if($createappraisalflow == '' || $createappraisalflow == null || $createappraisalflow == undefined)
	 	{
	 		$_SESSION['msg'] = 'Unknown Appraisal Approval flow';
	 		header("Location: assign_manager.php");
	 		exit();
	 	}
	 	$sql = "UPDATE users SET appraisal_flow = '".$createappraisalflow."' WHERE department = '".$department."' AND companyId = '".$_SESSION['user']['companyId']."'";
	 }
	 else if($flow == 'Leave flow')
	 {
	 	if($createleaveflow == '' || $createleaveflow == null || $createleaveflow == undefined)
	 	{
	 		$_SESSION['msg'] = 'Unknown Leave Approval flow';
	 		header("Location: assign_manager.php");
	 		exit();
	 	}
	 	$sql = "UPDATE users SET leave_flow = '".$createleaveflow."' WHERE department = '".$department."' AND companyId = '".$_SESSION['user']['companyId']."'";
	 }


	 if (mysqli_query($conn,$sql ) === TRUE) {
        $_SESSION['msg'] = "Approval flow Updated Successfully";
        header("Location: assign_manager.php");
	 } else {
	      //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	     $_SESSION['msg'] = "Error updating data, kindly try again later";
	    header("Location: assign_manager.php");
	  }
	// print_r($employee);
}



?>
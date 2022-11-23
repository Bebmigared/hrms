<?php
include "connection.php";
//echo 'aaa';
session_start();
$found = 0;
if(isset($_GET['q'])){
	 $requestID = base64_decode($_GET['q']);
	 $_SESSION['requestID'] = $requestID;
	 $sql = "SELECT * from recruitment where requestID = '".$requestID."'";
	 $result = mysqli_query($conn,$sql);
	 if(mysqli_num_rows($result) > 0){
	 	$found = 1;
	 }
	 if($found == 1) header("location: /outsourcing/replyrequest.php");
	 else echo "Invalid Request";
}else {
	echo base64_encode('request010');
	echo "Invalid Request";
}
?>
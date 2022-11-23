<?php
 include "connection.php";
 session_start();
  if(!isset($_SESSION['user']['id'])) header("Location: login.php");
 //echo $_GET['id'];
 if(isset($_GET['id'])){
 	if($_GET['id'] != ''){
 		$_SESSION['dept_id'] = base64_decode($_GET['id']);

 		$query = "SELECT * FROM departments WHERE  id = '".$_SESSION['dept_id']."'";
		  $result = mysqli_query($conn, $query);
		  if(mysqli_num_rows($result)> 0){
		      while($row = mysqli_fetch_assoc($result)) {
		        $_SESSION['department'][] = $row;
		      }
		  }
		  //print_r($_SESSION['department']);
		  //unset($_SESSION['department']);

		if($_SERVER['SERVER_NAME'] != 'localhost')  
 			header("Location: /adddepartment.php");
 		else 
 			header("Location: /newhrcore/adddepartment.php");
 	}
 }
?>
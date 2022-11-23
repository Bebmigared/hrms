<?php
include 'connection.php';
session_start();
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
if(!isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == '') header("Location: login.php");
 if($_SESSION['user']['category'] == 'staff') $admin_id = $_SESSION['user']['admin_id'];
 else if ($_SESSION['user']['category'] == 'admin') $admin_id = $_SESSION['user']['id'];
if(isset($_POST['submit'])){

	  $category = mysqli_real_escape_string($conn, $_POST['category']);
	  $query = "SELECT * FROM cash_category where category ='".$category."' AND companyId = '".$_SESSION['user']['companyId']."'";
	  $result = mysqli_query($conn, $query);
	  if(mysqli_num_rows($result)> 0){
	  	 $_SESSION['msg'] = "Category Already Exist";
	    if($_SERVER['SERVER_NAME'] == 'localhost')
        	header("location: /newhrcore/cash_request_category");
        else
            header("location: /cash_request_category");	
        exit();
	  }

      
      $sql = "INSERT INTO cash_category (category,admin_id, date_created, companyId)
          VALUES ('".$category."','".$admin_id."', '".date('Y-m-d')."', '".$_SESSION['user']['companyId']."')";
            if (mysqli_query($conn, $sql)) {
               $_SESSION['msg'] = "New Category of Item is Added";
            }else {
               $_SESSION['msg'] = "Error while update account, please try again later";
               echo "Error updating record: " . mysqli_error($conn);
          }
       
      if($_SERVER['SERVER_NAME'] == 'localhost')
      	header("location: /newhrcore/cash_request_category");
      else
        header("location: /cash_request_category");	  
  }

  if(isset($_POST['update'])){

    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $query = "SELECT * FROM cash_category where category ='".$category."'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result)> 0){
       $_SESSION['msg'] = "Category Already Exist";
      if($_SERVER['SERVER_NAME'] == 'localhost')
          header("location: /newhrcore/cash_request_category");
        else
            header("location: /cash_request_category"); 
        exit();
    }

      
      $sql = "UPDATE cash_category SET category = '".$category."' WHERE id = '".$_POST['id']."'";
            if (mysqli_query($conn, $sql)) {
               $_SESSION['msg'] = "Category Updated successfully";
            }else {
               $_SESSION['msg'] = "Error while update account, please try again later";
               echo "Error updating record: " . mysqli_error($conn);
          }
       
      if($_SERVER['SERVER_NAME'] == 'localhost')
        header("location: /newhrcore/cash_request_category");
      else
        header("location: /cash_request_category");   
  }

  if(isset($_GET['id']))
  {
    $id = base64_decode($_GET['id']);
    //echo $id;
    //exit();
    $query = "SELECT * FROM cash_request where purpose ='".$id."'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0) {
       echo $_SESSION['msg'] =  "Cash Category can not be Deleted";
        if($_SERVER['SERVER_NAME'] == 'localhost')
            header("location: /newhrcore/cash_request_category");
        else
            header("location: /cash_request_category");

        exit();  
    }
  	
  	$sql = "DELETE FROM cash_category WHERE id=$id";

  	if (mysqli_query($conn, $sql)) {
  	   $_SESSION['msg'] =  "Record deleted successfully";
  	} else {
  	   $_SESSION['msg'] = "Error deleting record";
  	}

  	 if($_SERVER['SERVER_NAME'] == 'localhost')
          	header("location: /newhrcore/cash_request_category");
      else
          header("location: /cash_request_category");	
  }

?>
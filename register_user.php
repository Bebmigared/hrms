<?php
  include "connection.php";
  session_start();
  $exist = 1;
  if(isset($_POST['submit'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    if($password != $cpassword){
    	 $_SESSION['msg'] = 'Password does not match';
    	 header("Location: register.php");
    	 return false;
    }
    /*if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         $_SESSION['msg'] = 'Input is not a valid email';
         header("Location: register.php");
         return false;
    }*/
    $password = password_hash($password, PASSWORD_DEFAULT);
    $exist = isAccountCreated($conn, $email);
    if ($exist == 0) {
    	$_SESSION['msg'] = "User already exist, proceed to log In";
        header("Location: login.php");
    }else{
    	$sql = "INSERT INTO users (name, email, password, role, company_name, category,first_time_loggin,department,employee_ID,profile_image,admin_id,lManager,bManager)
          VALUES ('', '".$email."', '".$password."','','','staff','1','','','user_profile.png','','','')";
          if (mysqli_query($conn,$sql ) === TRUE) {
              $_SESSION['msg'] = "Account created successfully, kindly login";
              header("Location: login.php");
          } else {
              //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
              $_SESSION['msg'] = "Error creating account";
              header("Location: register.php");
          }
    }
  }
  function isAccountCreated($conn, $email){
  	$query = "SELECT * from users WHERE email = '".$email."'";
  	$result = mysqli_query($conn, $query);
  	if(mysqli_num_rows($result) > 0){
  		return 0;
  	}
  	return 1;
  }
?>
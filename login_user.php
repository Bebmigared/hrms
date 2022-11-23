<?php
  include "connection.php";
  session_start();
  $user = [];
  $errors = [];
  $user_id = "";
  
  if(isset($_POST['submit'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    // die($email); exit;
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    isUserExist($conn,$email, $password);
  }
  function isUserExist($conn,$email, $password){
    //echo $email;
    $query = "SELECT * from users WHERE email = '$email' AND active = '1'";
  	$result = mysqli_query($conn, $query);
  	if(mysqli_num_rows($result) > 0){
  		$row = mysqli_fetch_assoc($result);
      //print_r($email);
  		$user[] = $row;
  		//print_r($row);
  		//exit();
  		 $verify = password_verify($password,$row['password']);
         if($verify){
           if($user[0]['first_time_loggin'] == '1'){
           	    $_SESSION['user'] = $user[0];
           	    //print_r($user[0]);
                updateUser($conn, $_SESSION['user']['category']);
                
                $query = "SELECT * from payments WHERE uniqueID = '".$user[0]['uniqueID']."' ORDER BY id DESC LIMIT 1";
              	$result = mysqli_query($conn, $query);
              	if(mysqli_num_rows($result) > 0){
              		$row = mysqli_fetch_assoc($result);
              		//print_r($row);
              		if(strtotime($row['expiration_date']) < strtotime(date('d-m-Y')) && $user[0]['category'] == 'staff'){
              		    $_SESSION['msg'] = "Subscription Expired, please contact your administrator";
                        header("Location: login");
                        return false;
              		}else{
              		    $_SESSION['user'] = $user[0];
              		    $package[] = $row;
              		    $_SESSION['payment'] = $package[0];
                      updatelastseen($conn,$email);
              		    if($_SESSION['user']['category'] == 'staff') header("Location: staff_settings");
                        if($_SESSION['user']['category'] == 'admin') header("Location: admin_settings");
              		}
              		
              		
              		
              	}
                //header("Location: basic_settings");
            }else {
            	//echo 'sssss';
            	$query = "SELECT * from payments WHERE uniqueID = '".$user[0]['uniqueID']."' ORDER BY id DESC LIMIT 1";
              	$result = mysqli_query($conn, $query);
              	if(mysqli_num_rows($result) > 0){
              		$row = mysqli_fetch_assoc($result);
              		//print_r($row);
              		if(strtotime($row['expiration_date']) < strtotime(date('d-m-Y')) && $user[0]['category'] == 'staff'){
              		    $_SESSION['msg'] = "Subscription Expired, please contact your administrator";
              		    //echo 'sssss';
                        header("Location: login");
                        return false;
              		}else{
              		    //echo 'sssssdddddd';
              		    $_SESSION['user'] = $user[0];
              		    $package[] = $row;
              		    $_SESSION['payment'] = $package[0];
              		    updatelastseen($conn,$email);
                      approval_settings($conn,$_SESSION['user']['companyId']);
                        header("Location: dashboard");
              		}
              		
              		
              		
              	}
                //function has_admin_changed_approvals();
                
            }
         }else {
              $_SESSION['msg'] = "Username and password do not match";
              header("Location: login");
         }
  	}else {
       $_SESSION['msg'] = "No username with such email, kindly create an account";
       header("Location: login");
    }
  }
  function updatelastseen($conn,$email){
      $sql = "UPDATE users SET last_loggin = '".date('Y-m-d')." ".date('H:m:s')."' WHERE email = '".$email."'";
        if (mysqli_query($conn, $sql)) {
        } else {
        }
        return true;
  }

  function approval_settings($conn,$companyId){
      $sql = "SELECT * FROM approval_settings WHERE companyId = '".$companyId."'";
      $result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result); 
        $_SESSION['approval_settings'] = $row['status'];
      }else {}
        return true;
  }
  function updateUser($conn, $category){
     $sql = "UPDATE users SET category = '".$category."', first_time_loggin = '1' WHERE email = '".$_SESSION['user']['email']."'";
        if (mysqli_query($conn, $sql)) {
            //echo "Record updated successfully";
            $_SESSION['user']['category'] = $category;
        } else {
            echo "Error updating record: " . mysqli_error($conn);
            //header("Location: settings.php");
        }
        return true;
  }
  
  
  
  if (isset($_POST['reset-password'])) {
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  // ensure that the user exists on our system
  $query = "SELECT email FROM users WHERE email='$email'";
  $results = mysqli_query($conn, $query);

  if (empty($email)) {
    array_push($errors, "Your email is required!");
  }else if(mysqli_num_rows($results) <= 0) {
    array_push($errors, "Sorry, no user exists on our system with that email");
  }
  // generate a unique random token of length 100
  $token = bin2hex(random_bytes(50));

  if (count($errors) == 0) {
    // store token in the password-reset database table against the user's email
    $sql = "INSERT INTO password_reset(email, token) VALUES ('$email', '$token')";
    $results = mysqli_query($conn, $sql);

    // Send email to user with the token in a link they can click on
    $to = $email;
    $subject = "Reset your password on hrcore";
    $msg = "Hi there, click on this <a href=\"new_password.php?token=" . $token . "\">link</a> to reset your password on our site";
    $msg = wordwrap($msg,70);
    $headers = "From: info@icshrcore.com";
    mail($to, $subject, $msg, $headers);
    header('location: pending.php?email=' . $email);
  }
}

// ENTER A NEW PASSWORD
if (isset($_POST['new_password'])) {
  $new_pass = mysqli_real_escape_string($conn, $_POST['new_pass']);
  $new_pass_c = mysqli_real_escape_string($conn, $_POST['new_pass_c']);

  // Grab to token that came from the email link
  $token = $_SESSION['token'];
  if (empty($new_pass) || empty($new_pass_c)) array_push($errors, "Password is required");
  if ($new_pass !== $new_pass_c) array_push($errors, "Password do not match");
  if (count($errors) == 0) {
    // select email address of user from the password_reset table 
    $sql = "SELECT email FROM password_reset WHERE token='$token' LIMIT 1";
    $results = mysqli_query($conn, $sql);
    $email = mysqli_fetch_assoc($results)['email'];

    if ($email) {
      $new_pass = md5($new_pass);
      $sql = "UPDATE users SET password='$new_pass' WHERE email='$email'";
      $results = mysqli_query($conn, $sql);
      header('location: index.php');
    }
  }
}
  
  
  
  
  
  
  
  
  
  
  
  
  
  

?>
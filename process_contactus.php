<?php 
  include "connection.php";
  include "process_email.php";
  session_start();
  if(isset($_POST['submit'])){
      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $name = mysqli_real_escape_string($conn, $_POST['name']);
      $message = mysqli_real_escape_string($conn, $_POST['message']);
      $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
      if($email != "" && filter_var($email, FILTER_VALIDATE_EMAIL) && $name != '' && $message != '' && is_numeric($phone_number)){
        $server_provider = explode("@", $email)[1];
        $provider = explode(".", $server_provider)[0];
          if($provider == 'gmail' || $provider == 'yahoo' || $provider == 'hotmail' || $provider == 'rocketmail' || $provider == 'googlemail' || $provider == 'aol' || $provider == 'outlook' || $provider == 'mail' || $provider == 'icloud'){
          $_SESSION['msg'] = 'Kindly use your company Email';
          $_SESSION['name'] = $name;
          $_SESSION['message'] = $message;
          $_SESSION['phone_number'] = $phone_number;
          $_SESSION['email'] = '';
          header("Location:thankyou.php"); 
          }else {
          $send_email = 'enquiries@icsoutsourcing.com';
          //$send_email = 'ogunrindeomotayo@gmail.com';
          $msg = "<div><p>Hello,</p><p> A potential Client has requested for a Demo on HRCORE.</p><p>Below are the information submitted:</p>
              <p>Name : $name</p>
              <p>Email: $email</p>
              <p>Phone Number: $phone_number</p>
              <p>Message: $message</p>";
           if (filter_var($send_email, FILTER_VALIDATE_EMAIL)) {
                process_data($conn,$send_email,$msg,'Request a Demo');
               $_SESSION['msg'] = "Thank you for your Interest in HRCORE, you will get a responses from us shortly";
                  $_SESSION['name'] = '';
                  $_SESSION['message'] = '';
                  $_SESSION['phone_number'] = '';
                  $_SESSION['email'] = '';
                  header("Location:thankyou.php");   
            }
      }
    }else {
      $_SESSION['msg'] = "Company Name, Company Email and Message fields are required";
      $_SESSION['name'] = $name;
      $_SESSION['message'] = $message;
      $_SESSION['phone_number'] = $phone_number;
      $_SESSION['email'] = $email;
      header("Location:thankyou.php"); 
      //echo $message;
    }      
         
    
  }
?>
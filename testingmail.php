<?php
include "connection.php";
include "process_email.php";
//$email = "ogunrindeomotayo@gmail.com";
$email = "oogunrinde@icsoutsourcing.com";
$msg = "<div><p>Good Day,</p><p>Your Company Admin has created you on hrcore.ng. To participate in company affairs, kindly log In</p><p>Your login details is username : $email and Password : selfservice</p><a  style ='padding:7px;color:#fff;background-color:#4e73df;margin:10px;border-radius: 3px;text-decoration:none' href = 'http://www.hrcore.ng'>Log In to view</a></p></div>";
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      process_data($conn,$email,$msg,'Account Created');
      echo 'aaaa';
    }

?>
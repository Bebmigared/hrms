<?php
session_start();
include "connection.php";
$email = $_GET['email'];
if($email != ''){
    $email = base64_decode($email);
    $_SESSION['email'] = $email;
    header("Location: resetpassword.php");
    //echo $email;
}

?>
<?php 
include "connection.php";
include "connectionPDO.php";
session_start();
//$_SESSION["ticket_id"] = mysqli_real_escape_string($conn, $_POST['id']);

//$ticket_id =  $_SESSION["ticket_id"];
    //$id = mysqli_real_escape_string($conn, $GET['ticket_id']);
    if(isset($_POST['reply_btn']) || isset($_SESSION['ticketid'])){
    //$ticket_id = mysqli_real_escape_string($conn, $_POST['ticket_id']);
    $ticket_id = mysqli_real_escape_string($conn, $_POST['ticket_id']);
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $handler_id = mysqli_real_escape_string($conn, $_POST['handler_id']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $created_at = date("Y-m-d");
    $sql ="INSERT into tickets_message (user_id, ticket_id, handler_id, messages, created_at) VALUES ('".$user_id."', '".$ticket_id."', '".$handler_id."', '".$message."', '".$created_at."')";
    if (mysqli_query($conn, $sql)) {
        
      echo "Reply sent";
      
      
    } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
      
    //$ticket_id = $_SESSION['ticket_id'];
    header( "refresh:3;url=process_ticket2.php" );
  }
      ?>
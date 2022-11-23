<?php
include "connection.php";
session_start();
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
if(isset($_POST['submit'])){
	$comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $request_id = mysqli_real_escape_string($conn, $_POST['id_card_request_id']);
    //echo $request_id;
    $sql = "UPDATE id_card SET comment = '".$comment."', status = 'Comment Added' WHERE IID = '".$request_id."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Comment updated";
            header("Location: id_card_details.php");
            
        } else {
            //echo "Error updating record: " . mysqli_error($conn);
            $_SESSION['msg'] = "Error updating comment, kindly try again later";
            header("Location: id_card_details.php");
        }
}
if(isset($_POST['justification_update'])){
	$justification = mysqli_real_escape_string($conn, $_POST['justification']);
    $request_id = mysqli_real_escape_string($conn, $_POST['id_card_request_id']);
    //echo $request_id;
    $sql = "UPDATE id_card SET justification = '".$justification."', status = 'Staff updated Justification' WHERE IID = '".$request_id."'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Justification updated";
            header("Location: view_id_request_status.php");
            
        } else {
            //echo "Error updating record: " . mysqli_error($conn);
            $_SESSION['msg'] = "Error updating comment, kindly try again later";
            header("Location: view_id_request_status.php");
        }
}
?>
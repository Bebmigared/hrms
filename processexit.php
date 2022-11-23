<?php
session_start();
if(isset($_GET['id'])){
    $id = base64_decode($_GET['id']);
    $_SESSION['exit_id'] = $id;
    //echo $_SESSION['exit_id'];
    
    header('Location: /newexit.php');
}

?>
<?php
 include 'connection.php';
 session_start();
  if(!isset($_SESSION['user']['id'])) header("Location: login.php");
 if(isset($_FILES['signature'])){
       if($_FILES['signature']['name'] == null) {
         $justification = mysqli_real_escape_string($conn, $_POST['justification']);
         getsignature($conn,$justification);
       }else {
       $error = array();
       $file_ext = explode('.',$_FILES['signature']['name'])[1];
       $img = rand(1,9) * 9999999;
       $file_name = $img.'.'.$file_ext;
       $file_size = $_FILES['signature']['size'];
       $file_tmp = $_FILES['signature']['tmp_name'];
       $file_type = $_FILES['signature']['type'];
       //$file_ext = explode('.',$_FILES['signature']['name'])[1];
       $extensions = array('jpeg','jpg','png');
       if(in_array($file_ext,$extensions) === false){
        $errors[] = "extension not allowed, please select a JPEG or PNG file.";
       }
       if($file_size > 209752){
        $errors[] = "File size too large";
       }
       if(empty($errors)==true){
       
         move_uploaded_file($file_tmp,"document/signature/".$file_name);
           $sql = "INSERT INTO id_card (staff_id, admin_id, date_created, signature,status, justification)
          VALUES ('".$_SESSION['user']['id']."', '".$_SESSION['user']['admin_id']."', '".date('Y-m-d')."','".$file_name."', 'pending','".$justification."')";
          if (mysqli_query($conn,$sql ) === TRUE) {
            $_SESSION['msg'] = 'Request under processing';
            header("Location: request_idcard.php");
          } else {
          	$_SESSION['msg'] = 'Error while saving information, please try again later';
          	header("Location: request_idcard.php");
          }

       }else {
         $_SESSION['msg'] = $errors[0];
         header("Location: request_idcard.php");

       }
       }
 }
 function getsignature($conn,$justification){
   $query = "SELECT * FROM id_card WHERE staff_id = '".$_SESSION['user']['id']."'";
   $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result)> 0){
        while($row = mysqli_fetch_assoc($result)) {
          $data[] = $row;
        }
    }
    if(count($data) > 0){
       $sql = "INSERT INTO id_card (staff_id, admin_id, date_created, signature,status,justification)
          VALUES ('".$_SESSION['user']['id']."', '".$_SESSION['user']['admin_id']."', '".date('Y-m-d')."','".$data[0]['signature']."', 'pending','".$justification."')";
          if (mysqli_query($conn,$sql ) === TRUE) {
            $_SESSION['msg'] = 'Request under processing';
            header("Location: request_idcard.php");
          } else {
            $_SESSION['msg'] = 'Error while saving information, please try again later';
            header("Location: request_idcard.php");
          }
    }else {
            $_SESSION['msg'] = 'Kindly upload your signature to process your request';
            header("Location: request_idcard.php");
    }
 }
?>
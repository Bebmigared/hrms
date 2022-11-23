<?php
include "connection.php";
session_start();
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
$msg = '';
$item_category = mysqli_real_escape_string($conn, $_POST['item_category']);
$item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
$item_quantity = mysqli_real_escape_string($conn, $_POST['item_quantity']);
$item_cost = mysqli_real_escape_string($conn, $_POST['item_cost']);
$admin_id = $_SESSION['user']['category'] == 'staff' ? $_SESSION['user']['admin_id'] : $_SESSION['user']['id'];
 if(isset($_POST['submit'])){
   if($item_category == ''){
     $_SESSION['msg'] = 'Please select category of Item';
   }
   if($item_name == ''){
     $_SESSION['msg'] = 'Please select the item name';
   }

  $query = "SELECT * from items WHERE item_name = '".$item_name."' AND companyId = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){

     $_SESSION['msg'] = 'Item Already Exist';
      header("Location: addItems.php");
     exit();
  }
   if($item_name){
     $sql = "INSERT INTO items (item_category, item_cost, item_name, item_quantity, admin_id, companyId)
          VALUES ('".$_POST['item_category']."', '".$_POST['item_cost']."', '".$_POST['item_name']."','".$_POST['item_quantity']."','".$admin_id."', '".$_SESSION['user']['companyId']."')";
            if (mysqli_query($conn, $sql)) {
              $_SESSION['msg'] = "New Item added to record";
              $last_id = $conn->insert_id;
            }else {
               $_SESSION['msg'] = "Error while update account, please try again later";
          }
          header("Location: addItems.php");
   }
 }
?>
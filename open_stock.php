<?php
include 'connection.php';
include 'connectionpdo.php';

session_start();

$query = "SELECT item_quantity FROM items ORDER BY item_quantity ASC";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result)> 0){
  while($row = mysqli_fetch_assoc($result)) {
    $data_item[] = $row;
  }
}
//for ($h = 0; $h < count($data_item); $h++)
foreach ($data_item as $value) 
    { 
      //$it[] = $data_item[$h]['item_quantity'];
      print_r($value);
    
      exit();
     $query = "UPDATE items SET opening_quantity = $value'";
    
if (mysqli_query($conn, $query)) {
    echo "Record updated successfully";
  } else {
    echo "Error updating record: " . mysqli_error($conn);
  }
}
    
?>
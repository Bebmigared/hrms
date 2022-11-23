<?php
include 'connection.php';
include 'connectionpdo.php';

session_start();


     $query = "UPDATE leaves SET stage = 'approved', status = 'approved'";
    
if (mysqli_query($conn, $query)) {
    echo "Record updated successfully";
  } else {
    echo "Error updating record: " . mysqli_error($conn);
  }
    
?>
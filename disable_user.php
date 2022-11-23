<?php

include 'connection.php';
session_start();

if(isset($_POST['disable'])){
    $user= mysqli_real_escape_string($conn, $_POST['userid']);
    $query = "UPDATE users SET active = '3'
    WHERE id = $user";
    $result = mysqli_query($conn, $query );
    
    echo "<script type='text/javascript'>alert('User Has Been Disabled');
      window.location='staff_directory.php';
      </script>";
        
        
      } else {
        echo "" . $query . "<br>" . mysqli_error($conn);
      
    
}

if(isset($_POST['enable'])){
    $user= mysqli_real_escape_string($conn, $_POST['userid']);
    $query = "UPDATE users SET active = '1'
    WHERE id = $user";
    $result = mysqli_query($conn, $query );
    
    echo "<script type='text/javascript'>alert('User Has Been Enabled');
      window.location='staff_directory.php';
      </script>";
        
        
      } else {
        echo "" . $query . "<br>" . mysqli_error($conn);
      
    
}



?>
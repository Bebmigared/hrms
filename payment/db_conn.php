<?php
    //$pass = 'Z57ppWeT75njs3yl';
    //$username = 'selfservice_user';
    //$db = 'selfservicedb';
    /*$pass = ')FjqHa0zh7hg';
    $username = 'smoothtr_service';
    $db = 'smoothtr_selfservicedb';
    $host = 'localhost';*/
  /*  $pass = 'XvW@gap[h@M=';
    $db = 'hrcoreng_selfservicedb';
    $host = 'localhost';
    $username = 'hrcoreng_serviceDB';
    $conn = mysqli_connect($host, $username, $pass,$db);
*/

/*
    $pass = 'root';
    $db = 'payment';
    $host = 'localhost';
    $username = 'admin';
    $conn = mysqli_connect($host, $username, $pass,$db);
    
    */
    
    
     $pass = 'XvW@gap[h@M=';
    $db = 'hrcoreng_selfservicedb';
    $host = 'localhost';
    $username = 'hrcoreng_serviceDB';
    $conn = mysqli_connect($host, $username, $pass,$db);
    if($conn){
    	//echo 'connected';
    }
    else {
    	echo "failed";
    }
    //db: smoothtr_selfservicedb
    //smoothtr_service
    //)FjqHa0zh7hg
?>

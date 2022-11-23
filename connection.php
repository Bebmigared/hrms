<?php

include 'vendor/autoload.php';
$json = (string)((new josegonzalez\Dotenv\Loader(__DIR__.'/.env'))->parse());
if($_SERVER['SERVER_NAME'] != 'localhost')
{
    $pass = json_decode($json)->DB_LIVE_PASSWORD;
    $db = json_decode($json)->DB_LIVE_DATABASE;
    $host = json_decode($json)->DB_LIVE_HOST;
    $username = json_decode($json)->DB_LIVE_USER;   
}else {
    $pass = json_decode($json)->DB_LOCAL_PASSWORD;
    $db = json_decode($json)->DB_LOCAL_DATABASE;
    $host = json_decode($json)->DB_LOCAL_HOST;
    $username = json_decode($json)->DB_LOCAL_USER; 
}
    

    $conn = mysqli_connect($host, $username, $pass,  $db);
    if($conn){
    	
    }
    else {
    	
    }
?>

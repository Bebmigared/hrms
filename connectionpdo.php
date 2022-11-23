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
    
    $charset = 'utf8mb4';
    $ds = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        $pdo = new PDO($ds, $username, $pass, $options);
    }
    catch(PDOException $e){
        exit ("Error:".$e->getMessage());
    }
?>
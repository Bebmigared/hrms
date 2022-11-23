<?php
session_start();
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");

//unset($_SESSION['coursestestid']);

if(isset($_GET['coursestestid']))
{
	$id = base64_decode($_GET['coursestestid']);
	$_SESSION['coursestestid'] = $id;
}

if($_SERVER['SERVER_NAME'] == 'localhost')
{
	header("location: /newhrcore/participantlist.php");
}else {
    header("location: /participantlist.php");	
}

?>
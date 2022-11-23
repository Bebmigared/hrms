<?php

include "connectionpdo.php";
$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute([]); 
$users = $stmt->fetchAll();
//print_r($users);

for($r = 0; $r < count($users); $r++)
{
	$requisitionflow = $users[$r]['requisition_flow'];
	$cashflow = $users[$r]['cash_flow'];
	$leaveflow = $users[$r]['leave_flow'];

	if($requisitionflow != null && $requisitionflow != '')
	{
		$value = '';
		$flow = explode(';', $requisitionflow);
		for ($i=0; $i < count($flow); $i++) { 
			$eachflow = explode(':',$flow[$i]);
			$id = getid($pdo, $eachflow[1]);
			if($value != '') $value .= ';';  
			$value .= $eachflow[0].':'.$id;
			
		}

		$sql = "UPDATE users SET requisition_flow = ? WHERE id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$value,$users[$r]['id']]);

	}

	if($cashflow != null && $cashflow != '')
	{
		$value = '';
		$flow = explode(';', $cashflow);
		for ($i=0; $i < count($flow); $i++) { 
			$eachflow = explode(':',$flow[$i]);
			$id = getid($pdo, $eachflow[1]);
			if($value != '') $value .= ';';  
			$value .= $eachflow[0].':'.$id;
			
		}

		$sql = "UPDATE users SET cash_flow = ? where id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$value,$users[$r]['id']]);
	}

	if($leaveflow != null && $leaveflow != '')
	{
		$value = '';
		$flow = explode(';', $leaveflow);
		for ($i=0; $i < count($flow); $i++) { 
			$eachflow = explode(':',$flow[$i]);
			$id = getid($pdo, $eachflow[1]);
			if($value != '') $value .= ';';  
			$value .= $eachflow[0].':'.$id;
			
		}

		$sql = "UPDATE users SET leave_flow = ? where id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$value,$users[$r]['id']]);
	}
}

function getid($pdo, $email)
{
	$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR id = ?");
	$stmt->execute([$email, $email]); 
	$user = $stmt->fetch();
	// echo $user['id'];
	// echo "<br>";
	return $user['id'];
}
?>
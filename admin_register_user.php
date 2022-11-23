<?php
  include "connection.php";
  include "connectionpdo.php";
  session_start();
  $exist = 1;
  if(isset($_POST['submit'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['msg'] = "Invalid Email";
        header("Location: admin_register.php");
        exit();
    }
    if($password != $cpassword){
    	 $_SESSION['msg'] = 'Password does not match';
    	 header("Location: admin_register.php");
    	 exit();
    }
    if(strlen($password) < 8)
    {
       $_SESSION['msg'] = 'Minimum of 8 character is required for Password';
       header("Location: admin_register.php");
      exit();
    }
    if(strlen($company_name) < 3)
    {
       $_SESSION['msg'] = 'Company Name must not be less than 3 character';
       header("Location: admin_register.php");
       exit();
    }
    $password = password_hash($password, PASSWORD_DEFAULT);
    $exist = isAccountCreated($conn, $email);
    if ($exist == 0) {
    	$_SESSION['msg'] = "User already exist, proceed to log In";
        header("Location: admin_register.php");
    }else{
        $unique_id = uniqid();
        
    	  try {
            $pdo->beginTransaction();

            $sql = "INSERT INTO company (company_name, uniqueID, date_created) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$company_name, $unique_id,date('Y-m-d')]);
            $companyId = $pdo->lastInsertId();

            $query = "INSERT INTO users (name, email, password, role,uniqueID, company_name, category,first_time_loggin,department,employee_ID,profile_image,admin_id, companyId, date_created) VALUES (?, ?, ?,?,?,?,?,?,?,?,?,?,?,?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['Admin', trim($email),trim($password), '', $unique_id,trim($company_name),'admin','1','',trim($email),'user_profile.png',null,$companyId, date('Y-m-d')]);



            $sql = "INSERT INTO payments (uniqueID, package_type, number_of_users, expiration_date, date_created) VALUES (?, ?, ?, ?,?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$unique_id, 'Basic', '5', '31-12-2025', date('Y-m-d')]);
           
            $pdo->commit();

            $_SESSION['msg'] = "Account created successfully, kindly login";
            header("Location: login.php");

        } catch (PDOException $e) {

            $pdo->rollBack();
            $_SESSION['msg'] = "Error Whiling Creating Account";
            header("Location: admin_register.php");
        }
    }
  }
  function isAccountCreated($conn, $email){
  	$query = "SELECT * from users WHERE email = '".trim($email)."'";
  	$result = mysqli_query($conn, $query);
  	if(mysqli_num_rows($result) > 0){
  		return 0;
  	}
  	return 1;
  }
?>
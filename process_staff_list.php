<?php
ini_set('display_errors', 1);
include 'connectionpdo.php';
require_once "SimpleXLSX.php";
ini_set("MAX_EXECUTION_TIME", "10000000");
session_start();
if(!isset($_SESSION['user']))
{
  header("Location:login");
}


$exexcuteRows = 50;
$filename = '';
$updated = 0;
$inserted = 0;
if(isset($_POST['submit']))
{
       if(!is_uploaded_file($_FILES['list']['tmp_name']))
       {
       	  $_SESSION['invalid'] = "Upload a Excel(Xlsx) file";
       	  //echo $_SESSION['msg'];
       	  header("Location:onboarding");
       	  //print_r($_FILES['list']);
       	  exit();
       }
       $_SESSION['invalid'] = '';
       $file_ext = explode('.',$_FILES['list']['name'])[1];
       $_name = uniqid();
       $file_name = $_name.''.time().'.'.$file_ext;
       $file_size = $_FILES['list']['size'];
       $file_tmp = $_FILES['list']['tmp_name'];
       $file_type = $_FILES['list']['type'];
       $filename = $file_name;
       
       $extensions = array('xlsx');
       if(strtolower($file_ext) != 'xlsx'){
           $_SESSION['invalid'] = "Extension not allowed, please select a XLSX file.";
           //echo $_SESSION['msg'];
           header("Location:onboarding");
           exit();
       }
       if($file_size > 2005097){
          $_SESSION['invalid']  = "File size too large";
         // echo $_SESSION['msg'];
          header("Location:onboarding");
          exit();
       }	
       $status = move_uploaded_file($file_tmp,"document/list/".$file_name); 
       if($status == true)
       {

       		            
  			if ( $xlsx = SimpleXLSX::parse('./document/list/'.$file_name.''))
		    {
					  
					   $rows = $xlsx->rows();
					   $sheets = $xlsx->sheetNames();

					   if(count($sheets) > 1)
					   {
					   	    $_SESSION['invalid'] = "Only One Sheet is allowed... ".count($sheets)." sheet uploaded";
					     // echo $_SESSION['msg'];
					         header("Location:onboarding");
					          exit();
					   }
					   
					   //print_r($rows[0][2]);
					   if(trim(strtolower($rows[0][0])) !== 'firstname')
					    {
					      $_SESSION['invalid'] = "Firstname Column is Invalid....Kindly Download the Accepted Excel format ".$rows[0][0]."";
					     // echo $_SESSION['msg'];
					      header("Location:onboarding");
					      exit();
					    }
					    if(trim(strtolower($rows[0][1])) !== "lastname")
					    {
					      $_SESSION['invalid'] = "lastname Column is Invalid....Kindly Download the Accepted Excel format";
					       //echo $_SESSION['msg'];
					       header("Location:onboarding");
					      exit();
					    }  
					    if(trim(strtolower($rows[0][2])) !== "employee id")
					    {
					      $_SESSION['invalid'] = "Employee ID Column is Invalid....Kindly Download the Accepted Excel format";
					       //echo $_SESSION['msg'];
					       header("Location:onboarding");
					      exit();
					    }
					    if(trim(strtolower($rows[0][3])) !== "email")
					    {
					      $_SESSION['invalid'] = "Email Column is Invalid....Kindly Download the Accepted Excel format";
					       //echo $_SESSION['msg'];
					       header("Location:onboarding");
					      exit();
					    }
					    if(trim(strtolower($rows[0][4])) !== "phone number")
					    {
					      $_SESSION['invalid'] = "Phone Number Column is Invalid....Kindly Download the Accepted Excel format";
					       //echo $_SESSION['msg'];
					       header("Location:onboarding");
					      exit();
					    }
					    if(trim(strtolower($rows[0][5])) !== "gender")
					    {
					      $_SESSION['invalid'] = "Gender Column is Invalid....Kindly Download the Accepted Excel format";
					       //echo $_SESSION['msg'];
					       header("Location:onboarding");
					      exit();
					    }
					    if(trim(strtolower($rows[0][6])) !== "marital status")
					    {
					      $_SESSION['invalid'] = "Marital Status Column is Invalid....Kindly Download the Accepted Excel format";
					       //echo $_SESSION['msg'];
					       header("Location:onboarding");
					      exit();
					    }

					    if(trim(strtolower($rows[0][7])) !== "date of birth")
					    {
					      $_SESSION['invalid'] = "Marital Status Column is Invalid....Kindly Download the Accepted Excel format";
					       //echo $_SESSION['msg'];
					       header("Location:onboarding");
					      exit();
					    }
 
 
					  
					   $res = loadData($pdo,$rows,$filename);

					      $_SESSION['msg'] = $res." Rows Added";
					      //echo $_SESSION['msg'];
					      header("Location:onboarding");
					      exit();
			}
       }
 
}


function loadData($pdo,$rows,$filename)
{			$inserted = 0;
	        for($e = 1; $e < count($rows); $e++)
					   {
					   	  

					      $stmt = $pdo->prepare("SELECT * FROM users WHERE employee_ID = ?");
					      $stmt->execute([trim($rows[$e][2])]); //$rows[0][2]
					      $user = $stmt->fetch();

					      $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
					      $stmt->execute([trim($rows[$e][3])]); //$rows[0][2]
					      $useremail = $stmt->fetch();

					      if(isset($user['id']) && $rows[$e][2] != "")
					      {
					          
					      }else if(isset($useremail['id']) && $rows[$e][3] != "")
					      {

					      }
					      else if(!isset($user['id']) && $rows[$e][1] != '' && $rows[$e][2] != "" && filter_var($rows[$e][3], FILTER_VALIDATE_EMAIL))
					      {

					              $password = password_hash(strtolower('selfservice'), PASSWORD_DEFAULT);
					              $cpassword = 'selfservice';
					              //$admin_id = $_SESSION['user']['id'];
					              $category = 'staff';
					              $company = $_SESSION['user']['companyId'];
					              $gender = '';
					              $marital_status = '';
					              if($rows[$e][5] != '' && trim((strtolower($rows[$e][5])) == 'male' || trim(strtolower($rows[$e][5])) == 'female'))
					              {
					              	$gender = ucfirst(trim($rows[$e][5]));
					              }

					              if($rows[$e][6] != '' && (trim(strtolower($rows[$e][6])) == 'single' || trim(strtolower($rows[$e][6])) == 'married'))
					              {
					              	$marital_status = trim(strtolower($rows[$e][6]));
					              }
					              

    					              $sql = "INSERT INTO users (email, name,role,department,phone_number,branch,employee_ID,password,companyId,category,company_name,profile_image,first_time_loggin,date_created,leave_flow,appraisal_flow,requisition_flow,cash_flow,position,fname,mname,active,gender,marital_status,dob,uniqueID, admin_id, grade)
					      VALUES (?, ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
					             
					             $stmt = $pdo->prepare($sql);
					             $stmt->execute([trim($rows[$e][3]),trim($rows[$e][1]),'',trim($rows[$e][8]),trim($rows[$e][4]),trim($rows[$e][9]),trim($rows[$e][2]),$password,$company,$category,$_SESSION['user']['company_name'],'user_profile.png','0',date('Y-m-d'),'','','','','',$rows[$e][0],'','1', $gender,$marital_status,$rows[$e][7], $_SESSION['user']['uniqueID'], $_SESSION['user']['id'], trim($rows[$e][10])]);
					             $inserted = $inserted + 1;
					            

					      }else if(!filter_var($rows[$e][3], FILTER_VALIDATE_EMAIL))
					      {
					      	$_SESSION['invalid'] .= "Invalid Email Address(".$rows[$e][0].")";
					      }
					      else if($rows[$e][2] == "")
					      {
					      	$_SESSION['invalid'] .= " Invalid Employee ID for (".$rows[$e][0].")";
					      }

					      

					   }

    return $inserted;
}


?>
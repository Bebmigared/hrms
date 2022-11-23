<?php
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
       	  header("Location:additems");
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
           header("Location:additems");
           exit();
       }
       if($file_size > 2005097){
          $_SESSION['invalid']  = "File size too large";
         // echo $_SESSION['msg'];
          header("Location:additems");
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
					         header("Location:additems");
					          exit();
					   }
					   
					   //print_r($rows[0][2]);
					   if(strtolower($rows[0][0]) !== 'item category')
					    {
					      $_SESSION['invalid'] = "Item Category Column is Invalid....Kindly Download the Accepted Excel format ".$rows[0][0]."";
					     // echo $_SESSION['msg'];
					      header("Location:additems");
					      exit();
					    }
					    if(strtolower($rows[0][1]) !== "item cost")
					    {
					      $_SESSION['invalid'] = "Item Cost Column is Invalid....Kindly Download the Accepted Excel format";
					       //echo $_SESSION['msg'];
					       header("Location:additems");
					      exit();
					    }  
					    if(strtolower($rows[0][2]) !== "item name")
					    {
					      $_SESSION['invalid'] = "Item Name Column is Invalid....Kindly Download the Accepted Excel format";
					       //echo $_SESSION['msg'];
					       header("Location:additems");
					      exit();
					    }
					    if(strtolower($rows[0][3]) !== "quantity")
					    {
					      $_SESSION['invalid'] = "Quantity Column is Invalid....Kindly Download the Accepted Excel format";
					       //echo $_SESSION['msg'];
					       header("Location:additems");
					      exit();
					    }
					    
					   $res = loadData($pdo,$rows,$filename);

					      $_SESSION['msg'] = $res." Rows Added";
					      //echo $_SESSION['msg'];
					      header("Location:additems");
					      exit();
			}
       }
 
}


function loadData($pdo,$rows,$filename)
{			$inserted = 0;
	        for($e = 1; $e < count($rows); $e++)
		   {
		   	  

		      $stmt = $pdo->prepare("SELECT * FROM items WHERE item_name = ? AND companyId = ?");
		      $stmt->execute([trim($rows[$e][2]), $_SESSION['user']['companyId']]); //$rows[0][2]
		      $item = $stmt->fetch();
		      if(isset($item['id']) && $rows[$e][2] != "")
		      {
		          
		      }else if(!isset($item['id']) && $rows[$e][2] != '')
		      {
		              $sql = "INSERT INTO items (item_category, item_cost, item_name, item_quantity, companyId, date_created)
		      VALUES (?, ?,?,?,?)";
		             
		             $stmt = $pdo->prepare($sql);
		             $stmt->execute([trim($rows[$e][0]),trim($rows[$e][1]),$rows[$e][2],$rows[$e][3],$_SESSION['user']['companyId'], date('Y-m-d')]);
		             $inserted = $inserted + 1;
		            

		      }

		    
		   }

    return $inserted;
}


?>
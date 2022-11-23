<?php
 include "connection.php";
 include "connectionPDO.php";
 session_start();
 if(!isset($_SESSION['user'])) header("Location: login.php");
if (isset($_POST['submit'])) { // if save button on the form is clicked
$subject=mysqli_real_escape_string($conn, $_POST['subject']);
     
     $file = rand(1000,100000)."-".$_FILES['file']['name'];
    $file_loc = $_FILES['file']['tmp_name'];
 $file_size = $_FILES['file']['size'];
 $file_type = $_FILES['file']['type'];
 $folder="uploads/";
 
 /* new file size in KB */
 $new_size = $file_size/1024;  
 /* new file size in KB */
 
 /* make file name in lower case */
 $new_file_name = strtolower($file);
 /* make file name in lower case */
 
 $final_file=str_replace(' ','-',$new_file_name);
 
 if(move_uploaded_file($file_loc,$folder.$final_file))
 {
 $sql="INSERT INTO images(subject, filename,type,size,user,department)
  VALUES('$subject', '$final_file','$file_type','$new_size','".$_SESSION['user']['id']."','".$_SESSION['user']['department']."')";
  if (mysqli_query($conn,$sql)){
       header('location:kss.php');
 $_SESSION['msg'] = 'File sucessfully uploaded!';
  //echo "File sucessfully upload";
  }
   else
 {
  $_SESSION['msg'] = 'An error Occurred';
  header('location:kss.php');
  //echo "Error.Please try again";
		
		}
	}
  
 }

   /*if($_SESSION['user']['category'] == 'admin') {
       $admin_id = $_SESSION['user']['id']; 
       $staff_id = $_SESSION['user']['id']; 
   }else if($_SESSION['user']['category'] == 'staff'){
       $admin_id = $_SESSION['user']['admin_id']; 
       $staff_id = $_SESSION['user']['id'];
   }
   $department = $_SESSION['user']['department'];
   $knowledge = mysqli_real_escape_string($conn, $_POST['editor1']);
   $sql = "INSERT INTO kss (information, staff_id, department, admin_id, companyId, date_created)
          VALUES ('".$knowledge."', '".$staff_id."', '".$department."' '".$admin_id."', '".$_SESSION['user']['companyId']."', '".date('Y-m-d')."')";
          if (mysqli_query($conn,$sql ) === TRUE) {
              $_SESSION['msg'] = "Knowledge shared has been dispatched";
              if($_SERVER['SERVER_NAME'] != 'localhost')
                 header("Location: /share_knowledge.php");
              else 
                header("Location: /hrenterprise/share_knowledge.php");
          } else {
              echo "Error: " . $sql . "<br>" . mysqli_error($conn);
             // $_SESSION['msg'] = "Error saving information";
              if($_SERVER['SERVER_NAME'] != 'localhost')
                 header("Location: /share_knowledge.php");
              else 
                header("Location: /hrenterprise/share_knowledge.php");
          }*/
 
?>
 <?php
 include "connection.php";
 session_start();
  if(!isset($_SESSION['user']['id'])) header("Location: login.php");
 $is_exist = 0;
 if(isset($_POST['submit'])){
 $period = mysqli_real_escape_string($conn, $_POST['period']);
 $year = mysqli_real_escape_string($conn, $_POST['year']);
 $department = mysqli_real_escape_string($conn, $_POST['department']); 
 //$appraisal_flow = getappraisal_flow($conn);
  $query = "SELECT * from appraisal WHERE year = '$year' AND period = '$period' AND department = '$department'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
      $is_exist = 1;
    }
    if($is_exist == 1){
      $_SESSION['msg'] = "You have already uploaded appraisal for year $year with an appraisal period of $period";
       echo "<script> window.location.href = 'create_appraisal.php';</script>";
       //header("Location: create_appraisal.php");
       return false;
    }
  if($period != '' && $year != ''){
     if(isset($_FILES['document'])){
     if($_FILES['document']['name'] == null){
        $_SESSION['msg'] = "Kindly attached appraisal file to upload";
        echo "<script> window.location.href = 'create_appraisal.php';</script>";
        return false;
     }else {

       $error = array();
       $file_ext = explode('.',$_FILES['document']['name'])[1];
       $name = time();
       $file_name = strtotime(date('Y-m-d')).'.'.$file_ext;
       $file_size = $_FILES['document']['size'];
       $file_tmp = $_FILES['document']['tmp_name'];
       $file_type = $_FILES['document']['type'];
       $extensions = array('docx','doc','csv','xlsx');
       if(in_array($file_ext,$extensions) === false){
        $errors[] = "extension not allowed, please select a docx, doc, csv or xlsx file.";
       }
       if($file_size > 209752){
        $errors[] = "File size too large";
       }
       if(empty($errors)==true){
         move_uploaded_file($file_tmp,"document/".$file_name);
          $sql = "INSERT INTO appraisal (period, year, document, department, document_name, admin_id, date_created)
          VALUES ('".$period."', '".$year."','".$file_name."', '".$department."','".$_FILES['document']['name']."', '".$_SESSION['user']['id']."', '".date('Y-m-d')."')";
          if (mysqli_query($conn,$sql ) === TRUE) {
              $_SESSION['msg'] = "Appraisal successfully Uploaded";
              echo "<script> window.location.href = 'create_appraisal.php';</script>";
              //header("Location: create_appraisal.php");
          } else {
              echo "Error: " . $sql . "<br>" . mysqli_error($conn);
              $_SESSION['msg'] = "Error uploading Appraisal";
              //header("Location: create_appraisal.php");
          }

       }else {
         $msg = $errors[0];
         $_SESSION['msg'] = $msg;
         echo "<script> window.location.href = 'create_appraisal.php';</script>";
         //header("Location: create_appraisal.php");
       }
     }
   }
  }else {
    $_SESSION['msg'] = "Kindly complete all input field";
    header("Location: create_appraisal.php");
  }
 }

?>
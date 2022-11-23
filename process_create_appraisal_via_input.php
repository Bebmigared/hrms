 <?php
 include "connection.php";
 session_start();
  if(!isset($_SESSION['user']['id'])) header("Location: login.php");
 $is_exist = 0;
 if(isset($_POST['submit'])){
 $period = mysqli_real_escape_string($conn, $_POST['app_period']);
 $year = mysqli_real_escape_string($conn, $_POST['app_year']);
 $department = mysqli_real_escape_string($conn, $_POST['app_department']); 
 $appraisal_data = mysqli_real_escape_string($conn, $_POST['appraisal_data']);

 if($_SESSION['user']['category'] == 'staff') $admin_id = $_SESSION['user']['id'];
 else $admin_id = $_SESSION['user']['id'];
    $query = "SELECT * from appraisal WHERE year = '$year' AND period = '$period' AND department = '$department'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
      $is_exist = 1;
    }
    if($is_exist == 1){
      $_SESSION['msg'] = "You have already uploaded appraisal for year $year with an appraisal period of $period";
       echo "<script> window.location.href = 'create_appraisal.php';</script>";
       return false;
    }
    $sql = "INSERT INTO appraisal (period, year, document, department, document_name, appraisal_data, admin_id, date_created, companyId)
    VALUES ('".$period."', '".$year."','', '".$department."','input Question','".$appraisal_data."', '".$admin_id."', '".date('Y-m-d')."', '".$_SESSION['user']['companyId']."')";
    if (mysqli_query($conn,$sql ) === TRUE) {
        $_SESSION['msg'] = "Appraisal successfully Uploaded";
        echo "<script> window.location.href = 'create_appraisal.php';</script>";
    } else {
        //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        $_SESSION['msg'] = "Error uploading Appraisal";
        echo "<script> window.location.href = 'create_appraisal.php';</script>";
    }


}

?>
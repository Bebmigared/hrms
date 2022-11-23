 <?php 
 
 include 'connection.php';
require_once "connectionpdo.php";
 
 if(isset($_POST['submit'])){
$name = mysqli_real_escape_string($conn, $_POST['company_name']);
  $sql = "INSERT INTO companies (company_name)
  VALUES ('".$name."')";
  if (mysqli_query($conn,$sql ) === TRUE) {
    
      $_SESSION['msg'] = "New Bank added";
       $script = "<script> window.location = '../create_voucher';</script>";
       echo "Redirecting...";
      echo $script;
  } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
     $_SESSION['msg'] = "Error updating data, kindly try again later";
     header("Location: create_voucher.php");
  }
}
 
  ?>
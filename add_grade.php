<?php

session_start();
include 'connection.php';
include 'connectionpdo.php';

if(isset($_POST['insert_grade'])){
$grade_name= mysqli_real_escape_string($conn, $_POST['grade_name']);
$grade= mysqli_real_escape_string($conn, $_POST['grade']);
$checkGrade ="SELECT * FROM grade WHERE grade_name = '".$grade_name."'";
$result = mysqli_query($conn, $checkGrade);
$numrows=mysqli_num_rows($result);

if($numrows > 0){
    
     $_SESSION['msg'] = "Grade Name already exist";
     
}

else {
$query = "INSERT INTO grade (grade_name) VALUES ('".$grade_name."')";

if (mysqli_query($conn, $query ) === TRUE) {
    //exit();
    $_SESSION['msg'] = "New Grade added";
   // header("Location: add_grade.php");
} else {
    //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
   $_SESSION['msg'] = "Error inserting data, kindly try again later";
   header("Location: add_grade.php");
}

/*if (mysqli_query($conn, $query)) {
    //mail("oluwasegunjimoh@gmail.com","My subject",sss);
    echo "<script type='text/javascript'>alert('Grade Added Successfully');
    window.location='add_grade.php';
    </script>";
    //sendmail('oluwasegunjimoh@gmail.com','new ticket','new ticket');
  } else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }*/
}
}

$getGrade="SELECT * FROM grade";
$result = mysqli_query($conn, $getGrade);
//if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)) {
    $grades[] = $row;
   // print_r($grades);
}
?>
<?php include 'header.php'?>
<div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Add Employee Grade</h3>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg">Add Grade</button>

                  <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel">Add Grade</h4>
                        </div>
                        <div class="modal-body">
                          
                 <form action = 'add_grade.php' method = "POST">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Grade Name</label>
                    <input type="text" name ="grade_name" class="form-control" id="gradeName" aria-describedby="emailHelp" placeholder="">
                  </div>
                   <button type="submit" name="insert_grade" class="btn btn-success">Submit</button>
                </form>

                        </div>
                      </div>

                    </form>
                  
               
                  
                     
                        </div>

                      </div>
                    </div>
                  </div>
              </div>
                 <div class="x_content">
 <?php if(isset($_SESSION['msg'])) {?>
                    <div class="alert alert-success alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <?=$_SESSION['msg']?>
                  </div>
                        
                  <?php } ?>
                  <div class="x_content">
                    <br />
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                               <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N</th>
                            <th class="column-title">Grade Name</th>
                            
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($grades); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$grades[$h]['grade_name']?> </td>
                           
                                <!--<button type="button" leave_kind = "<?=$leave_type[$h]['leave_kind']?>" leave_id = "<?=$leave_type[$h]['id']?>" id = "<?=$leave_type[$h]['id']?>" days = "<?=$leave_type[$h]['days']?>" level = "<?=$leave_type[$h]['level_id']?>" class="leave_btn btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                                  Edit
                                </button>-->
                            </td>
                            
                           <?php }?>
                        </tbody>
                      </table>
                    </div></div>
                       

                      </div>
                    </div>
                    </div>
                </div>
                  </div>
                
                          
             
<?php include 'footer.php'?>
<?php 
include 'connection.php';
session_start();
$kss = [];
$admin_id;
$leave_type = [];
$levels = [];
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
 if($_SESSION['user']['category'] == 'staff' && $_SESSION['user']['leave_processing_permission'] != '1') header("Location: dashboard.php");
 $query = "SELECT * FROM grade";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result)> 0){
  while($row = mysqli_fetch_assoc($result)) {
    $levels[] = $row;
  }
}
 if(isset($_POST['submit'])){
      
      
      $leaveType = mysqli_real_escape_string($conn, $_POST['leaveType']);
      $_id = mysqli_real_escape_string($conn, $_POST['leave_id']);
      $leaveDays = mysqli_real_escape_string($conn, $_POST['leaveDays']);
      if($leaveType == ''){
          $_SESSION['msg'] = 'Please specify the leave Type';
      }

      $status = exist($conn, $leaveType,$_POST['level_id']);

      if($status == false)
      {
        $_SESSION['msg'] = 'Leave Type Already Exist';
      }
      else {
           $sql = "UPDATE leave_type SET leave_kind = '".$leaveType."', days = '".$leaveDays."', grade = '".$_POST['level_id']."' WHERE id = '".$_id."'";
          if (mysqli_query($conn, $sql)) {
              $_SESSION['msg'] = 'Leave Plan has been updated';
              //header("location: leave_types.php");
          }else{
             // echo "Error: " . $sql . "<br>" . mysqli_error($conn);
              $_SESSION['msg'] = 'Error updating leave plan';
          }
      }
      
 }

  function grade($conn, $grade_name, $grade)
  {
    $query="SELECT * FROM grade";
    $result = mysqli_query($conn, $query);
    //if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)) {
        $grades[] = $row;
       // print_r($grades);
    }
  }

  if(isset($_POST['newadd'])){
      
      
      $leaveType = mysqli_real_escape_string($conn, $_POST['leaveType']);
      $_id = mysqli_real_escape_string($conn, $_POST['leave_id']);
      $leaveDays = mysqli_real_escape_string($conn, $_POST['leaveDays']);
      /*$admin_id = $_SESSION['user']['id'];
      $companyId = $_SESSION['user']['id'];
      if($_SESSION['user']['category'] == 'staff') $admin_id = $_SESSION['user']['id'];
      else $admin_id = $_SESSION['user']['id'];
      $status = exist($conn, $leaveType,$_POST['level_id']);
      if($leaveType == '' || $leaveDays == ''){
          $_SESSION['msg'] = 'Please specify the leave Type and Leave days';
          //header("location: leave_types.php");
      }
      if($status == false)
      {
        $_SESSION['msg'] = 'Leave Type Already Exist';
      }*/
       $checkType ="SELECT * FROM leave_type WHERE leave_kind = '".$leaveType."' AND grade = '".$_POST['level_id']."'";
        $result = mysqli_query($conn, $checkType);
            $numrows=mysqli_num_rows($result);

            if($numrows > 0){
    
     $_SESSION['msg'] = "Leave Type already exist";
     //print_r($result);
     //exit();
}
      else {
          $sql = "INSERT INTO leave_type (leave_kind,days, grade, admin_id, companyId, date_created) VALUES ('".$leaveType."', '".$leaveDays."','".$_POST['level_id']."','".$admin_id."', '".$companyId."', '".date('Y-m-d')."')";
         if (mysqli_query($conn, $sql)) {
              $_SESSION['msg'] = 'New Leave Plan Added';
              //header("location: leave_types.php");
          }else{
              $_SESSION['msg'] = 'Error adding leave plan';
          }
      }
      
      
 }
  
  
  $query = "SELECT * FROM leave_type";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $leave_type[] = $row;
      }
  }
 
 function exist ($conn,$type, $level_id)
 {
      $query = "SELECT * FROM leave_type WHERE leave_kind ='$type' AND grade='leave_id' ";
      $result = mysqli_query($conn, $query);
      if(mysqli_num_rows($result)> 0){
          return false;
      }
      return true;
 }

  

  //print_r($users);
  //return false;
?>
<?php include "header.php"?>
<style type="text/css">
 .table-striped>tbody>tr:nth-of-type(odd){
    background-color: #f8fafc;
  }
  .table>tbody>tr>th{
    border-top: none;
  }
  .table>tbody>tr>td{
    border-top: none;
  }
</style>
<div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>LEAVE TYPE</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    <form method="" action="">
                    <div class="input-group">
                      <input type="text" class="form-control" name = "emp_ID" placeholder="Search by ID">
                      <span class="input-group-btn">
                        <button class="btn btn-default" type="submit" name="search">Go!</button>
                      </span>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
               <?php if(isset($_SESSION['msg']) && $_SESSION['msg'] != '') {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                  <?php } ?>   
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Leave Type</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li>
                      <!--form action="process_file.php" method="post"-->
                            
                            <button type="submit" id="btnExport"
                                name='export' value=""
                                class="btn btn-info" data-toggle="modal" data-target="#addModal">Add Leave Type</button>
                        <!--/form-->
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="table-responsive">
                      <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N</th>
                            <th class="column-title">Leave Type</th>
                            <th class="column-title">Leave Type Description </th>
                           
                            <th class="column-title">Days </th>
                            <th class="column-title">Grade </th>
                            <th class="column-title">Modify</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($leave_type); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$leave_type[$h]['leave_kind']?> </td>
                            <td class=" "><?=$leave_type[$h]['leave_kind']?> Leave</td>
                             
                              <td class=" "><?=$leave_type[$h]['days']?> </td>
                               <td class=" "><?=$leave_type[$h]['grade']?> </td>
                            <td class=" ">
                                <button type="button" leave_kind = "<?=$leave_type[$h]['leave_kind']?>" id = "<?=$leave_type[$h]['id']?>" days = "<?=$leave_type[$h]['days']?>" level = "<?=$leave_type[$h]['level_id']?>" class="leave_btn btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal">
                                  Edit
                                </button>
                            </td>
                            
                           <?php }?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-12 col-xs-12">
                
              </div>
            </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
        <!-- Button trigger modal -->
        <!--button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
          Launch demo modal
        </button-->
        
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Leave Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                 <form action = 'leave_type.php' method = "POST">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Leave Type</label>
                    <input type="text" name ="leaveType" class="form-control" id="leaveType" aria-describedby="emailHelp" placeholder="">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Leave Description</label>
                    <input type="text" name = "leaveDesc" class="form-control" id="leave_desc" placeholder="">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Leave Days</label>
                    <input type="text" name = "leaveDays" class="form-control" id="leaveDays" placeholder="">
                  </div>
                   <div class="form-group">
                    <label for="exampleInputPassword1">Level</label>
                    <select class="form-control" name="level_id" required="true" id ="editlevel_id">
                       <option value=""></option>
                      
                        <?php for($r = 0; $r < count($levels); $r++) { ?>
                         <option value="<?=$levels[$r]['grade_name']?>"><?=$levels[$r]['grade_name']?></option>
                       <?php } ?> 
                    </select>
                  </div>
                  <div class="form-group" hidden>
                    <label for="exampleInputPassword1">Id</label>
                    <input type="text" name = "leave_id" class="form-control" id="leave_id" placeholder="">
                  </div>
                  <input type="submit" name = "submit" class="btn btn-primary"/>
                </form>
              </div>
            </div>
          </div>
        </div>
        
        
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Leave Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                 <form action = 'leave_type.php' method = "POST">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Leave Type</label>
                    <input type="text" name ="leaveType" class="form-control" aria-describedby="emailHelp" placeholder="">
                  </div>
                 <!-- <div class="form-group">
                    <label for="exampleInputPassword1">Leave Description</label>
                    <input type="text" name = "leaveDesc" class="form-control" placeholder="">
                  </div>-->
                  <div class="form-group">
                    <label for="exampleInputPassword1">Leave Days</label>
                    <input type="text" name = "leaveDays" class="form-control" placeholder="">
                  </div>
                   <div class="form-group">
                    <label for="exampleInputPassword1">Level</label>
                    <select class="form-control" name="level_id" required="true">
                       <option value=""></option>
                       <?php for($r = 0; $r < count($levels); $r++) { ?>
                         <option value="<?=$levels[$r]['grade_name']?>"><?=$levels[$r]['grade_name']?></option>
                       <?php } ?> 
                    </select>
                  </div>
                 <!--   <div class="form-group">
                    <label for="exampleInputPassword1">Leave Days</label>
                    <input type="text" name = "leaveDays" class="form-control" placeholder="">
                  </div> -->
                  <div class="form-group" style = "display:none">
                    <!--<label for="exampleInputPassword1">Id</label>-->
                    <input type="text" name = "leave_id" class="form-control" hidden>
                  </div>
                  <input type="submit" name = "newadd" class="btn btn-primary"/>
                </form>
              </div>
            </div>
          </div>
        </div>
<?php include "footer.php"?>
<script type="text/javascript" src="js/appraisal.js"></script>
<script>
    $(function(){
        $(".leave_btn").on('click', function(e){
            e.preventDefault();
            let kind = $('#'+this.id+'').attr('leave_kind');
            let _id = $('#'+this.id+'').attr('id');
            let days = $('#'+this.id+'').attr('days');
            let level = $('#'+this.id+'').attr('level');
            //alert(_id);
            $("#leaveType").val(kind);
            $("#leave_desc").val(kind+" Leave");
            $("#leave_id").val(_id);
            $("#leaveDays").val(days);
            $("#editlevel_id").val(level);
        })
    })
</script>
        

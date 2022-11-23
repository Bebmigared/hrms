<?php 
include 'connection.php';
session_start();
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
$courses = [];
$categories = [];
$data = [];
$users = [];
$alldepartments = [];
if(!isset($_SESSION['user']['id'])) header("Location: login.php");

$query = "SELECT coursecategory.id, coursecategory.category,courses.title,courses.link,courses.filename,courses.categoryId,courses.id as courseid, courses.date_created, courses.companyId FROM courses INNER JOIN coursecategory ON courses.categoryId = coursecategory.id WHERE courses.companyId = '".$_SESSION['user']['companyId']."' ORDER BY courses.id DESC";
 
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $courses[] = $row;
      }
  }

  $query = "SELECT * FROM coursecategory where companyId ='".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
      }
  }


$query = "SELECT * from departments WHERE company_id = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $alldepartments[] = $row;
        //$dept[] = $row;
      }
  }


if($_SESSION['user']['category'] == 'staff' && $_SESSION['user']['add_permission_management'] != '1') header("location: dashboard");
 $query = "SELECT * FROM company WHERE id = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
      }
  }

  $query = "SELECT * FROM users WHERE companyId = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
      }
  }

   //print_r($data_cash);
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
            <h3>Courses</h3>
          </div>

          <div class="title_right">
            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Search for...">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button">Go!</button>
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <?php if(isset($_SESSION['msg'])) {?>
              <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                  <?=$_SESSION['msg']?>
              </div>
              <?php unset($_SESSION['msg']); ?>
            <?php } ?>
            <?php if(count($courses) > 0) {?>      
            <div class="col-md-12 col-sm-12 col-xs-12">
                 <div class="x_panel">
                      <div class="x_title">
                        <h2>Assign Course to Department</h2>
                        <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                        <br />

                        <form action="process_course.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                           <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Select Course 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                               <select class="form-control" name ="courseId">
                                <option value = ""></option>
                               <?php if(count($courses) > 0) {?>
                                    <?php for ($r = 0; $r < count($courses); $r++){ ?>
                                      <option value = "<?=$courses[$r]['id']?>"><?=$courses[$r]['title']?></option>
                                <?php } }?> 
                                </select>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Select Department 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                               <select class="form-control" name ="department">
                                 <option value=""></option>
                                <?php for($r = 0; $r < count($alldepartments); $r++){?>
                                  <option value = "<?=$alldepartments[$r]['id'];?>"> <?=$alldepartments[$r]['dept'];?></option>
                                <?php } ?>
                                </select>
                            </div>
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"> 
                            <button type="submit" name="assign_course_to_department" class="btn btn-success">Submit</button>
                          </div>
                        </form>
                      </div>
                      </div>
                 <div class="x_panel">
                      <div class="x_title">
                        <h2>Assign Course to Employee</h2>
                        <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                        <br />

                        <form action="process_course.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Select Course 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                               <select class="form-control" name ="courseId">
                                <option value = ""></option>
                               <?php if(count($courses) > 0) {?>
                                    <?php for ($r = 0; $r < count($courses); $r++){ ?>
                                      <option value = "<?=$courses[$r]['id']?>"><?=$courses[$r]['title']?></option>
                                <?php } }?> 
                                </select>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Select Staff <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                               <select class="form-control" name="userId">
                                <option value = ""></option>
                               <?php if(count($data) > 0) {?>
                                    <?php for ($r = 0; $r < count($users); $r++){ ?>
                                      
                                      <option value = "<?=$users[$r]['id']?>"><?=$users[$r]['name']?> <?=$users[$r]['fname']?> - <?=$users[$r]['department']?></option>
                                <?php } } ?> 
                                </select>
                            </div>
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"> 
                            <button type="submit" name="assign_course_to_user" class="btn btn-success">Submit</button>
                          </div>
                        </form>
                      </div>
                      </div>      
            </div> 
            <?php } ?> 
            <?php if(count($courses) == 0) { ?>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Courses</h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                    <?php if(count($courses) == 0 ){ ?>
                       No Course Created
                    <?php } ?>
                  </div>
                </div>
            </div> 
            <?php } ?>    
        </div>
</div>
</div>
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">Edit Course</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                  <form action="process_course.php" method="POST" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
  
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Course Category <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <select name ="categoryId" id="categoryId" class="form-control col-md-7 col-xs-12" required="required">
                                  <option value =""></option>
                                  <?php for($e = 0; $e < count($categories); $e++) { ?>
                                    <option value="<?=$categories[$e]['id']?>"><?=$categories[$e]['category']?></option>
                                  <?php } ?>
                                  
                              </select>
                              
                          </div>
                        </div>
                        <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Course Title<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input name="title" id="title" class="form-control col-md-7 col-xs-12" required="required" type="text">
                                </div>
                        </div>
                        <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Course Link<span></span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input name="link" id="link" class="form-control col-md-7 col-xs-12" placeholder="https://www.example.com/eduxation.pdf" type="text">
                                     <input name="id" style="display: none" id="id" class="form-control col-md-7 col-xs-12" placeholder="" type="text">
                                </div>
                        </div>
                      
                        <div class="form-group">
                                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Upload Course <small style='color:red'>maximum file size 2MB (jpeg,pdf,doc,txt)</small>
                                      </label>
                                      <div class="col-md-6 col-sm-6 col-xs-12">
                                          <input type="file" name ="attach_document" class="custom-file-input" id="">
                                      </div>                                
                              </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="submit" name="update_course" class="btn btn-success">Submit</button>
                          </div>
                        </div>
  
                      </form>
              </div>
            </div>
          </div>
        </div>
<?php include "footer.php"?>
<script type="text/javascript">
  $(function(e){
    $('.edit').on('click', function(e){
      e.preventDefault();
      // alert($("#"+this.id+"").attr('categoryId'));
      $('#title').val($("#"+this.id+"").attr('title'));
      $('#categoryId').val($("#"+this.id+"").attr('categoryId'));
      $('#link').val($("#"+this.id+"").attr('link'));
      $('#id').val($("#"+this.id+"").attr('courseid'));

    })
  })
</script>
        

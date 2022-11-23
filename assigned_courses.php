<?php 
include 'connection.php';
session_start();
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
$courses = [];
$categories = [];
if(!isset($_SESSION['user']['id'])) header("Location: login.php");

$query = "SELECT courses.link,assigncourse.date_created, courses.title,assigncourse.department,assigncourse.userId, assigncourse.id as assign_id, courses.filename FROM assigncourse INNER JOIN courses ON courses.id = assigncourse.courseId INNER JOIN coursecategory ON coursecategory.id = courses.id  WHERE assigncourse.companyId = '".$_SESSION['user']['companyId']."' ORDER BY courses.id DESC";
 
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

  // print_r($courses);

  function user($conn,$userId)
  {
    //return $userId;
      $query = "SELECT * FROM users WHERE id = '".$userId."'";
      //echo $userId;
      $result = mysqli_query($conn, $query);
      if(mysqli_num_rows($result)> 0){
          while($row = mysqli_fetch_assoc($result)) {
            $user[] = $row;
          }
           return $user[0]['fname'].' '.$user[0]['name'];
      }
       return '';

  }
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
                    <h2>Assigned Courses</h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                  
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N </th>
                            <th class="column-title">Title </th>
                            <th class="column-title">Download </th>
                            <th class="column-title">Link </th>
                            <th class="column-title">Department </th>
                             <th class="column-title">User </th>
                            <th class="column-title">Date Assigned </th>
                            <th class="column-title">Option </th>
                           
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($courses); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$courses[$h]['title']?></td>
                            <td class=" ">
                              <?php if($courses[$h]['filename'] != '') { ?>
                              <a href ="document/<?=$courses[$h]['filename']?>" id=""
                                name='' value=""
                                class="btn btn-success btn-sm" style = "color: #fff;">Download</a>
                              <?php }else { ?>
                                <a>No file Uploaded</a>
                              <?php } ?>  
                              </td>
                            <td class=" "><?=$courses[$h]['link']?></td>
                            <td class=" ">
                             <!--  <?=$courses[$h]['department']?> -->
                                 <?php
                               $query = "SELECT * FROM departments WHERE id = '".$courses[$h]['department']."'";
                                $result = mysqli_query($conn, $query);
                                if(mysqli_num_rows($result)> 0){
                                    while($row = mysqli_fetch_assoc($result)) {
                                      echo $row['dept'];
                                    }
                                }else{
                                   echo $courses[$h]['department'];
                                }  
                                ?>  
                              </td>
                            <td class=" ">
                            <?php if($courses[$h]['userId'] != '') {?>
                               <?=user($conn, $courses[$h]['userId'])?>
                            <?php } ?>  
                            </td>
                            <td class=" "><?=$courses[$h]['date_created']?></td>
                            <td class="edit" id="edit<?=$h?>" onclick = "Deleteitem(<?=$courses[$h]['assign_id']?>)" courseid="<?=$courses[$h]['courseid']?>" categoryId = "<?=$courses[$h]['categoryId']?>" title = "<?=$courses[$h]['title']?>" link = "<?=$courses[$h]['link']?>"><span class="fa fa-close"></span></td>
                           <!--  <th class="column-title"><a href="process_request_cash_details.php/?cash_id=<?=base64_encode($data_cash[$h]['id'])?>&staff_id=<?=base64_encode($data_cash[$h]['staff_id'])?>" class="btn btn-sm btn-success">Details</a> </th> -->
                          </tr>
                           <?php }?>
                        </tbody>
                      </table>
                    </div>
                    <?php if(count($courses) == 0 ){ ?>
                       No request made
                    <?php } ?>
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

  function Deleteitem(id)
  {
    if(confirm('Do you want to Remove the Assigned Course?'))
    {
      window.location.href = `process_course.php?deleteassign=${btoa(id)}`;
    }else {

    }
  }
  $(function(e){
    $('.edit').on('click', function(e){
      e.preventDefault();
      // alert($("#"+this.id+"").attr('categoryId'));
      $('#title').val($("#"+this.id+"").attr('title'));
      $('#categoryId').val($("#"+this.id+"").attr('categoryId'));
      $('#link').val($("#"+this.id+"").attr('link'));
      $('#id').val($("#"+this.id+"").attr('courseid'));

    });
  })
</script>
        

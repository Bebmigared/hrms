<?php 
include 'connection.php';
session_start();
$courses = [];
$categories = [];
$coursestest = [];
if(!isset($_SESSION['user']['id'])) header("Location: login.php");
if(!isset($_SESSION['courseid'])) header("Location: view_courses");



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



  $query = "SELECT coursestest.id, coursestest.testexpirationdate,coursestest.date_created, coursestest.testname,coursestest.questions, users.name, users.fname  FROM coursestest INNER JOIN users ON coursestest.createdBy = users.id WHERE coursestest.courseid = '".$_SESSION['courseid']."' ORDER BY coursestest.id DESC";
 
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $coursestest[] = $row;
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
             <h3> <a href = 'view_courses'><i class="fa fa-arrow-left" style = "font-size:30px;"></i></a> Course Details</h3>
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
            <div class="col-md-12 col-sm-12 col-xs-12">
                 <div class="x_panel">
                  <div class="x_title">
                    <h2>Course Tests</h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                  
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N </th>
                            <th class="column-title">Test Name </th>
                            <th class="column-title">Test Expiration Date </th>
                            <th class="column-title">Created By </th>
                            <th class="column-title">View Question </th>
                            <th class="column-title">Option </th>
                            <th class="column-title">Participant </th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($coursestest); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$coursestest[$h]['testname']?></td>
                            <td class=""><?=$coursestest[$h]['testexpirationdate']?></td>
                            <td class=" ">
                               <?=$coursestest[$h]['name']?>
                              </td>
                             <td class="column-title"><a href="" style="cursor: pointer" data = '<?=$coursestest[$h]['questions']?>' id="<?=$coursestest[$h]['id']?>" class="btn btn-sm btn-success view_courses" data-toggle="modal" data-target="#editModal">View</a> </td>  
                            <td class="edit" id="edit<?=$h?>" onclick = "Deletetest(<?=$coursestest[$h]['id']?>)"><span class="fa fa-close"></span></td>  
                           
                            <td><a href="get_participantlist.php/?coursestestid=<?=base64_encode($coursestest[$h]['id'])?>" style="cursor: pointer" class="btn btn-sm btn-success">View List</a></td>  
                          </tr>
                           <?php }?>
                        </tbody>
                      </table>
                    </div>
                    <?php if(count($coursestest) == 0 ){ ?>
                       No Schedule Test
                    <?php } ?>
                  </div>
                </div>
                 <div class="x_panel">
                      <div class="x_title">
                        <h2>Set Question On Course</h2>
                        <div class="clearfix"></div>
                      </div>

                      <div class="x_content">
                        <br />
                         
                        <div class="question_page">   
                          <div class="row">
                            <div class="col-md-8 col-sm-12 col-xs-12 col-md-offset-2 all_questions"> 
                            </div>
                          </div>  
                          <div class="row">
                            <div class="col-md-8 col-sm-12 col-xs-12 col-md-offset-2"> 
                             <textarea id="question" class="form-control question" rows="4" placeholder="Question"></textarea> 
                             <input style="margin-top: 20px" class="form-control" type="text" placeholder="Option A" id="a" value="" name="">
                             <input style="margin-top: 20px" class="form-control" type="text" placeholder="Option B" id="b" value="" name="">
                             <input style="margin-top: 20px" class="form-control" type="text" placeholder="Option C" id="c" value="" name="">
                             <input style="margin-top: 20px" class="form-control" type="text" placeholder="Option D" id="d" value="" name="">
                             <input style="margin-top: 20px" class="form-control" type="text" placeholder="Option E" id="e" value="" name="">
                             <select class="form-control" id="answer" style="margin-top: 20px" >
                               <option value="">Correct Option (A, B, C, D, E)</option>
                               <option value="A">A</option>
                               <option value="B">B</option>
                               <option value="C">C</option>
                               <option value="D">D</option>
                               <option value="E">E</option>
                             </select>
                                             
                            </div>
                          </div>
                        </div>
                        <div class="show_questions hide">
                          <div class="row">
                             <div class="col-md-8 col-sm-12 col-xs-12 col-md-offset-2 each_questions">
                                
                             </div>
                              <div style="text-align: center;">
                                  <a href="#" id = "editdocument" class="btn btn-warning">Edit</a>
                              </div>
                          </div>
                          <div class="row">
                             <div class="col-md-8 col-sm-12 col-xs-12 col-md-offset-2">

                                <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                  
                                  
                                </ul>
                              </nav>   
                             </div>
                          </div>
                        </div>
                         <div class="process_form hide">
                        <div class="row">
                          <div class="col-md-8 col-sm-12 col-xs-12 col-md-offset-2">
                            <form action="process_course.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
        
                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Test Name <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="" name="testname" class="form-control col-md-7 col-xs-12" required="required" type="text">
                                </div>
                            
                              </div>

                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Test Expiration Date <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="" name="testexpirationdate" class="form-control col-md-7 col-xs-12" required="required" type="date">
                                </div>
                                 <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea style="display: none;" name = "course_test" id = "course_test"></textarea>
                                </div>
                               
                              </div>
                             
                             
                              <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                  <button type="submit" name="testsubmit" class="btn btn-success">Submit</button>
                                </div>
                              </div>
        
                            </form>
                          </div>
                        </div>
                        </div>
                       
                        <div class="row">
                           <div class="col-md-8 col-sm-12 col-xs-12 col-md-offset-2">
                             <div class="btn-group" role="group" aria-label="Basic example" style="margin-top: 10px;">
                              <button type="button" class="btn btn-primary" style="margin: 4px;" id="add_ques">Add Question</button>
                              <button type="button" class="btn btn-success hide" style="margin: 4px;" id="main_question_page">Question</button>
                              <button type="button" class="btn btn-info" style="margin: 4px;" id="course_review">Review Questions</button>
                              <button type="button" class="btn btn-warning" style="margin: 4px;" id = 'course_continue'>Continue</button>
                            </div> 
                           </div>
                        </div>
                        
                      </div>
                    </div>
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
                            <td class=" "><?=$courses[$h]['department']?></td>
                            <td class=" ">
                            <?php if($courses[$h]['userId'] != '') {?>
                               <?=user($conn, $courses[$h]['userId'])?>
                            <?php } ?>  
                            </td>
                            <td class=" "><?=$courses[$h]['date_created']?></td>
                            <td class="edit" id="edit<?=$h?>" onclick = "Deleteitem(<?=$courses[$h]['assign_id']?>)" courseid="<?=$courses[$h]['courseid']?>" categoryId = "<?=$courses[$h]['categoryId']?>" title = "<?=$courses[$h]['title']?>" link = "<?=$courses[$h]['link']?>"><span class="fa fa-close"></span></td>
                            
                          </tr>
                           <?php }?>
                        </tbody>
                      </table>
                    </div>
                    <?php if(count($courses) == 0 ){ ?>
                       Course not Assigned Yet
                    <?php } ?>
                  </div>
                </div>
            </div> 
            
        </div>
</div>
</div>
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">View Questions</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div style="overflow: scroll;height: 400px;overflow-x: hidden; ">
                       <div class="row" id = "all_reply">
                             <div class="col-md-8 col-sm-12 col-xs-12 each_questions">
                              
                               <div id="view_questions" style="text-align: justify;">
                                
                               </div>
                              
                               
                        </div>
                  </div>
                </div>
                  
              </div>
            </div>
          </div>
        </div>
<?php include "footer.php"?>
<script type="text/javascript" src="js/course_details.js?version=1.2"></script>
<script type="text/javascript">

  function Deleteitem(id)
  {
    if(confirm('Do you want to Remove the Assigned Course?'))
    {
      window.location.href = `process_course.php?deleteassign=${btoa(id)}`;
    }else {

    }
  }

  function Deletetest(id)
  {
    if(confirm('Do you want to Delete this Test?'))
    {
      window.location.href = `process_course.php?deletetest=${btoa(id)}`;
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
        

<?php 
include 'connection.php';
session_start();
$test_participant = [];
$categories = [];
$coursestest = [];
if(!isset($_SESSION['user']['id'])) header("Location: login.php");
if(!isset($_SESSION['coursestestid'])) header("Location: view_courses");



$query = "SELECT coursestest.id, coursestest.testname, test_participant.testdata,test_participant.coursetestid, users.name,users.fname, users.id as user_id, test_participant.date_created FROM test_participant INNER JOIN users ON users.id = test_participant.userId INNER JOIN coursestest ON coursestest.id = test_participant.coursetestid  WHERE test_participant.coursetestid = '".$_SESSION['coursestestid']."' ORDER BY test_participant.id DESC";
 
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

  function scores($conn,$coursetestid, $userId)
  {
    //return $userId;
      $query = "SELECT * FROM test_participant WHERE userId = '".$userId."' AND coursetestid = '".$coursetestid."'";
      //echo $userId;
      $result = mysqli_query($conn, $query);
      if(mysqli_num_rows($result)> 0){
          while($row = mysqli_fetch_assoc($result)) {
            $scores[] = $row;
          }
           return $scores[0]['scores'];
      }
       return 'Not Available';

  }
  function dateCreated($conn,$coursetestid, $userId)
  {
    //return $userId;
      $query = "SELECT * FROM test_participant WHERE userId = '".$userId."' AND coursetestid = '".$coursetestid."'";
      //echo $userId;
      $result = mysqli_query($conn, $query);
      if(mysqli_num_rows($result)> 0){
          while($row = mysqli_fetch_assoc($result)) {
            $scores[] = $row;
          }
           return $scores[0]['date_created'];
      }
       return 'Not Available';

  }

  function attempts($conn,$coursetestid, $userId)
  {
    //return $userId;
      $query = "SELECT * FROM test_participant WHERE userId = '".$userId."' AND coursetestid = '".$coursetestid."'";
      //echo $userId;
      $result = mysqli_query($conn, $query);
      if(mysqli_num_rows($result)> 0){
          while($row = mysqli_fetch_assoc($result)) {
            $scores[] = $row;
          }
           return $scores[0]['attempts'];
      }
       return 'Not Available';

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
            <h3> <a href = 'course_details'><i class="fa fa-arrow-left" style = "font-size:30px;"></i></a> Test Participant</h3>
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
                    <h2>Test Complete By the following Employee</h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                  
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N </th>
                            <th class="column-title">Test Name </th>
                            <th class="column-title">Employee Name </th>
                            <th class="column-title">Date Completed </th>
                            <th class="column-title">Scores (In %) </th>
                            <th class="column-title">Attempts </th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($coursestest); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$coursestest[$h]['testname']?></td>
                            <td class=""><?=$coursestest[$h]['name']?> <?=$coursestest[$h]['fname']?></td>
                            <td class=" ">
                               <?=dateCreated($conn, $coursestest[$h]['id'],$coursestest[$h]['user_id'])?>
                              </td>
                             <td class=" ">
                               <?=scores($conn, $coursestest[$h]['id'],$coursestest[$h]['user_id'])?>
                              </td>   
                           
                            <td class="edit">
                              <?=attempts($conn, $coursestest[$h]['id'], $coursestest[$h]['user_id'])?>
                            </td>  
                          </tr>
                           <?php }?>
                        </tbody>
                      </table>
                    </div>
                    <?php if(count($coursestest) == 0 ){ ?>
                      No Completed Test Yet
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
                             <div class="col-md-8 col-sm-12 col-xs-12 col-md-offset-2 each_questions">
                              
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
        

<?php 
include 'connection.php';
session_start();
$courses = [];
$categories = [];
$coursestest = [];
if(!isset($_SESSION['user']['id'])) header("Location: login.php");
if(!isset($_SESSION['courseid'])) header("Location: view_courses");




  $query = "SELECT coursestest.id, coursestest.testexpirationdate,coursestest.date_created, coursestest.testname,coursestest.questions, users.name, users.fname  FROM coursestest INNER JOIN users ON coursestest.createdBy = users.id WHERE coursestest.courseid = '".$_SESSION['courseid']."' ORDER BY coursestest.id DESC";
 
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $coursestest[] = $row;
      }
  }

  // print_r($courses);

  function scores($conn,$coursetestid)
  {
    //return $userId;
      $query = "SELECT * FROM test_participant WHERE userId = '".$_SESSION['user']['id']."' AND coursetestid = '".$coursetestid."'";
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

  function attempts($conn,$coursetestid)
  {
    //return $userId;
      $query = "SELECT * FROM test_participant WHERE userId = '".$_SESSION['user']['id']."' AND coursetestid = '".$coursetestid."'";
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
             <h3> <a href = 'view_mycourses'><i class="fa fa-arrow-left" style = "font-size:30px;"></i></a> Course Details</h3>
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
                          
                            <th class="column-title">Score (%)</th>
                            <th class="column-title">Attempts</th>
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
                             <td class="column-title"><a href="" style="cursor: pointer" data = '<?=$coursestest[$h]['questions']?>' id="<?=$coursestest[$h]['id']?>" class="btn btn-sm btn-success myview_courses" data-toggle="modal" data-target="#editModal">View</a> </td>  
                           
                            <td><?=scores($conn, $coursestest[$h]['id'])?></td> 
                            <td><?=attempts($conn, $coursestest[$h]['id'])?></td>  
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
                <div style="margin: 7px;margin-right: 20px;">
                       <div class="row" id = "all_reply">
                             <div class="col-md-12 col-sm-12 col-xs-12 each_questions">
                               <div id="view_questions" style="text-align: justify;margin-bottom: 30px">
                                
                               </div>
                            </div>
                           
                  </div>
                </div>
                 <div style="margin-top:30px;">
                  <form action="process_course.php" method="POST" >
                    <textarea id="responsetoquestions" name="responses" style="display: none"></textarea>
                    <input type="text" name="mycoursetestid" style="display: none" id="mycoursetestid">
                    <div class="form-group" >
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <button type="submit" name="submitAnswer" class="btn btn-success">Submit</button>
                      </div>
                    </div>
                  </form>  
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
        

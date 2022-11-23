<?php 
include 'connection.php';
 session_start();
 $msg = '';
 $data = [];
 $user = [];
 $to_remark = [];
 $leave_approval_details = [];
 $leaves = [];
 $to_show_leave = [];
 if(!isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == '') header("Location:login");
 $query = "SELECT * FROM users WHERE admin_id = '".$_SESSION['user']['admin_id']."'";
 $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result) > 0){
     while($row = mysqli_fetch_assoc($result)) {
          $user[] = $row;
     }
  }
  //print_r($user);
$leaveID = [];  
for ($i=0; $i < count($user); $i++) { 
  if($user[$i]['exit_flow'] != ''){
    //print_r($user[$i]['exit_flow']);  
  $exit_approval_details = explode(";", $user[$i]['exit_flow']);
  if(count($exit_approval_details) > 0){
    for($r = 0; $r < count($exit_approval_details); $r++){
      $email = explode(":", $exit_approval_details[$r])[1];//email of approval
      //echo $email."<br>";
      if(strtolower(trim($email)) == strtolower(trim($_SESSION['user']['email']))){
        foreach ($user as $value) {
          if($value['staff_exit'] == 'yes'){
            $exit[] = $value;
          }
        }
      }
    }
    
    
  }
 }
}
//print_r($exit);
?>
<?php include "header.php"?>
<div class="right_col" role="main">
<div class="">
        <div class="page-title">
          <div class="title_left">
            <h3>Exit Request</h3>
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
                    <h2>Exit Request</h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                   <?php if(count($exit) > 0 ){ ?>
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N </th>
                            <th class="column-title">Name </th>
                            <th class="column-title">Department </th>
                            <th class="column-title">Role </th>
                            <th class="column-title">Branch </th>
                            <th class="column-title">Phone Number </th>
                            <th class="column-title">Employee ID </th>
                            <th class="column-title">Exit Date </th>
                            <th class="column-title">View Form </th>
                          </tr>
                        </thead>


                        <tbody>
                          <?php for ($h = 0; $h < count($exit); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$exit[$h]['name']?></td>
                            <td class=" "><?=$exit[$h]['department']?></td>
                            <td class=" "><?=$exit[$h]['role']?></td>
                            <td class=" "><?=$exit[$h]['branch']?></td>
                            <td class=" "><?=$exit[$h]['phone_number']?></td>
                            <td class=" "><?=$exit[$h]['employee_ID']?></td>
                            <td class=" "><?=$exit[$h]['exit_date']?></td>
                            <td class=" "> <a href ="/processexit.php/?id=<?=base64_encode($exit[$h]['id'])?>" class ='btn btn-danger btn-sm'>View Form</a></td>
                          </tr>
                           <?php }?>
                        </tbody>
                      </table>
                    </div>
                    <?php } else { ?>
                       You have no exit request
                    <?php } ?>
                  </div>
                </div>
            </div> 
        </div>
</div>
</div>
<?php include "footer.php"?>
<script type="text/javascript">
    $('.upload_qual_file').on('click', function(e){
     $('#qual_file').trigger('click');
    });
    function readURL(input) {
      if (input.files && input.files[0]) {
        $('#doc').text('1 doc added-'+input.files[0].name);
        $('.upload_qual_file')
            .attr('src', 'images/document.png')
            .width(100)
            .height(100);
      }
    }
</script>

        

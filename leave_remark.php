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
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
 $query = "SELECT * FROM users WHERE admin_id = '".$_SESSION['user']['admin_id']."'";
 $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result) > 0){
     while($row = mysqli_fetch_assoc($result)) {
          $user[] = $row;
     }
  }
 $query = "SELECT users.name,users.department, users.fname,approval_flows.status, leaves.id as leave_id,leaves.remarks, leaves.flowstatus, leaves.leave_type,leaves.status,leaves.flow,leaves.justification,leaves.require_reliever, leaves.staff_id,users.id as user_id FROM leaves INNER JOIN users ON users.id = leaves.staff_id INNER JOIN approval_flows ON approval_flows.requestId = leaves.id WHERE approval_flows.approvalId = '".$_SESSION['user']['id']."'";
 $app_result = mysqli_query($conn, $query);
  if(mysqli_num_rows($app_result) > 0){
     while($row = mysqli_fetch_assoc($app_result)) {
          $leaves[] = $row;
     }
  }
 // print_r($leaves);
//$leaveID = [];  
// for ($i=0; $i < count($user); $i++) { 
//   if($user[$i]['leave_flow'] != ''){
//     //echo $user[$i]['appraisal_flow'];
//   $leave_approval_details = explode(";", $user[$i]['leave_flow']);
//   if(count($leave_approval_details) > 0){
//     for($r = 0; $r < count($leave_approval_details); $r++){
//       $email = explode(":", $leave_approval_details[$r])[1];//email of approval
//       //echo $email."<br>";
//       if(strtolower(trim($email)) == strtolower(trim($_SESSION['user']['email']))){
//         foreach ($leaves as $value) {
//           //print_r($value);

//           if($value['staff_id'] == $user[$i]['id'] && !in_array($value['leave_id'],$leaveID)){
//               $leaveID[] = $value['leave_id'];
//             $to_remark[]  = $user[$i];
//             $to_show_leave[] = $value;
//           }
//         }
//       }
//     }
    
    
//   }
//  }
// }
//print_r($to_show_leave);
?>
<?php include "header.php"?>
<div class="right_col" role="main">
<div class="">
        <div class="page-title">
          <div class="title_left">
            <h3>Leave Remark</h3>
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
                    <h2>Leave to Remark</h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                   <?php if(count($leaves) > 0 ){ ?>
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N</th>
                            <th class="column-title text-center">Name</th>
                            <th class="column-title text-center">SBU </th>
                            <th class="column-title text-center">Leave Type </th>
                            <th class="column-title text-center">Status </th>
                           
                            <th class="column-title text-center">More </th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($leaves); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class="text-center"><?=$leaves[$h]['fname']?> <?=$leaves[$h]['name']?> </td>
                            <td class="text-center">
                              <?=$leaves[$h]['department']?>
                              <?php
                                $query = "SELECT * FROM users WHERE id = '".$leaves[$h]['department']."'";
                                $result = mysqli_query($conn, $query);
                                if(mysqli_num_rows($result)> 0){
                                    while($row = mysqli_fetch_assoc($result)) {
                                          //echo $row['name'];
                                      //$dept = $row['department'];
                                    }
                                }else{
                                   //echo $leaves[$h]['department'];
                                }  
                              ?>
                              </td>
                            <td class="text-center"><?=$leaves[$h]['leave_type']?></td>
                            <td class="text-center" style='text-transform:Capitalize;'>
                             <?=$leaves[$h]['status'] == null ? 'Pending' : $leaves[$h]['status']?>
                            </td>
                          
                            <td class="text-center"><a href="get_this_staff_leave.php/?leave_id=<?=base64_encode($leaves[$h]['leave_id'])?>&staff_id=<?=base64_encode($leaves[$h]['staff_id'])?>" class="btn btn-sm btn-success">View</a></td>
                           <?php }?>
                        </tbody>
                      </table>
                    </div>
                    <?php } else { ?>
                       You have no leave to approve
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

        

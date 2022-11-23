<?php 
include 'connection.php';
session_start();
$leaves = [];
$user = [];
$leave_flow = [];
$curr = '';
$full_leave_flow = [];
$manager = [];
if(!isset($_SESSION['leave_id'])) header("Location: login.php");
if(!isset($_SESSION['leave_id']) && $_SESSION['leave_id'] != '') header("Location: dashboard.php");
  $query = "SELECT * FROM leaves WHERE id = '".$_SESSION['leave_id']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $leaves[] = $row;
      }
      $full_leave_flow = $leaves[0]['leave_flow'];
      $manager = explode(";",$leaves[0]['leave_flow']);
  }

   $query = "SELECT * FROM approval_flows WHERE requestId = '".$_SESSION['leave_id']."' AND type = 'Leave Request' ORDER BY id ASC";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $approvallist[] = $row;
      }
      
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
            <h3>Leave Flow</h3>
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
            <?php if($_SESSION['user']['category'] == 'staff' && $leaves[0]['status'] == 'approved') {?>
                        <div class="alert" style="background-color: #d4edda;" role="alert">
                            Congratulations! Your Leave request has been Approved
                        </div>
                  <?php } ?>
            <?php if($_SESSION['user']['category'] == 'admin' && $leaves[0]['status'] == 'approved') {?>
                        <div class="alert" style="background-color: #d4edda;" role="alert">
                            This Leave request has been Approved
                        </div>
                  <?php } ?> 
               <div class="col-md-7 col-sm-12 col-xs-12">
              <?php for($e = 0; $e < count($approvallist); $e++) {?>
              
                <div class="x_panel">
                  <div class="x_title">
                    <h2 style="text-transform: uppercase;"><?=$approvallist[$e]['title']?>
                      <?php
                      $email = explode(':',$manager[$e])[1];
                      $Req_sql = "SELECT * FROM users WHERE id = '".$approvallist[$e]['approvalId']."'";
                      $res = mysqli_query($conn, $Req_sql);
                      if(mysqli_num_rows($res)> 0){
                          while($row = mysqli_fetch_assoc($res)) {
                            echo $row['name'].' '.$row['fname'];
                          }
                      }

                      ?>

                    </h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                   <div style="width: 100%;margin-left: auto;margin-right: auto;">
                     <ul class="list-group">
                      <li class="list-group-item d-flex justify-content-between align-items-center">Approve
                       
                         <span class="badge badge-pill badge_request"  attr_id = "<?=$e?>" id= "approve<?=$e?>" status = "approved" item_id = "<?=$items[0]['id']?>"  style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
                               <?php if($approvallist[$e]['status'] != null && $approvallist[$e]['status'] == 'approved') {?>
                                  <i class="fas fa-check" style="padding: 0px;color: #4e73df;font-size: 12px;"></i>
                              <?php } ?>  
                            
                         </span>
                       </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">Decline 
                        <span class="badge badge-pill badge_request" style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
                              <?php if($approvallist[$e]['status'] != null && $approvallist[$e]['status'] == 'decline') {?>
                                  <i class="fas fa-check" style="padding: 0px;color: #4e73df;font-size: 12px;"></i>
                              <?php } ?> 
                         </span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">Pending 
                        <span class="badge badge-pill badge_request" style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
                          <?php if($approvallist[$e]['status'] != null && $approvallist[$e]['status'] == 'pend') {?>
                                  <i class="fas fa-check" style="padding: 0px;color: #4e73df;font-size: 12px;"></i>
                              <?php } ?>  
                         </span>
                      </li>
                    </ul>
                  </div>
                  <?php if($approvallist[$e]['date_accessed'] != null) { ?>
                  <div style="width: 100%;margin-left: auto;margin-right: auto;">
                      <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-0">
                            <div class="form-group">
                              <label for="exampleInputEmail1">Remark Date</label>     
                              <input disabled="" class="form-control" type="date" value="<?=$approvallist[$e]['date_accessed']?>" name="">   
                            </div>
                        </div>
                  </div>
                  <?php } ?>
                  <div style="width: 100%;margin-left: auto;margin-right: auto;">
                      <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-0">
                      <div class="form-group">
                        <label for="exampleInputEmail1"> Remark</label>        
                        <textarea style='width:100%' disabled=""  class = "form-control"><?=$approvallist[$e]['remark']?></textarea>
                      </div>
                  </div>
                  </div>
                  </div>
                </div>
                
              <?php } ?> 
            
              </div>      
              
              <div class="col-md-5 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>New Request</h2>
                  
                    <ul class="nav navbar-right panel_toolbox">
                      
                    </ul>
                    
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                   <?php if(count($leaves) > 0 ){ ?>
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <tbody>
                         <tr>
                           <td style="width: 60%">Request By</td>
                           <td style="width: 40%">
                              <?php
                              $eID = '';
                              $Req_sql = "SELECT * FROM users WHERE id = '".$leaves[0]['staff_id']."'";
                              $res = mysqli_query($conn, $Req_sql);
                              if(mysqli_num_rows($res)> 0){
                                  while($row = mysqli_fetch_assoc($res)) {
                                    echo $row['name'].' '.$row['fname'];
                                    $eID = $row['employee_ID'];
                                  }
                              }

                              ?>
                           </td>
                         </tr>
                         <tr>
                           <td style="width: 60%">Employee ID</td>
                           <td style="width: 40%"><?=$eID?></td>
                         </tr>
                         <tr>
                           <td style="width: 60%">Leave Type</td>
                           <td style="width: 40%"><?=$leaves[0]['leave_type']?></td>
                         </tr>
                         <tr>
                           <td style="width: 60%">Start Date</td>
                           <td style="width: 40%"><?=$leaves[0]['start_date']?></td>
                         </tr>
                         <tr>
                           <td style="width: 60%">End Date</td>
                           <td style="width: 40%"><?=$leaves[0]['end_date']?></td>
                         </tr>
                         <tr>
                           <td style="width: 60%">Number of Days</td>
                           <td style="width: 40%"><?=$leaves[0]['number_of_days']?></td>
                         </tr>
                          <tr>
                           <td style="width: 60%">Required Reliever</td>
                           <td style="width: 40%"><?=$leaves[0]['require_reliever']?></td>
                         </tr>
                          <tr>
                           <td style="width: 60%">Reliever Source</td>
                           <td style="width: 40%"><?=$leaves[0]['reliever_source']?></td>
                         </tr>
                          <tr>
                           <td style="width: 60%">Reliever Name</td>
                           <td style="width: 40%"><?=$leaves[0]['reliever_name']?></td>
                         </tr>
                          <tr>
                           <td style="width: 60%">Reliever Email</td>
                           <td style="width: 40%"><?=$leaves[0]['reliever_email']?></td>
                         </tr>
                         <tr>
                           <td style="width: 60%">Reliever Phone</td>
                           <td style="width: 40%"><?=$leaves[0]['reliever_phone']?></td>
                         </tr>
                         <tr>
                           <td style="width: 60%">Request Date</td>
                           <td style="width: 40%"><?=$leaves[0]['date_created']?></td>
                         </tr>
                      </table>
                    </div>
                    <?php } ?> 
                  </div>
                </div>
            </div>
              <?php if($_SESSION['user']['category'] == 'admin' || $_SESSION['user']['category'] == 'staff') {?>
              
              <?php }?>  
        </div>
</div>
</div>
<?php include "footer.php"?>
        

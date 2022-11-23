<?php 
include 'connection.php';
session_start();
if(!isset($_SESSION['user']['id'])) header("Location: login.php");
$items = [];
$user = [];
$company = [];
$leave_flow = [];
$full_leave_flow;
$manager = [];
$curr = '';
$processflow= [];
$approvallist = [];
//echo $_SESSION['requestitem_id'];
//echo $_SESSION['leave_id'];
if(!isset($_SESSION['leave_id'])) header("Location: requesteditems.php");

  $query = "SELECT * FROM leaves WHERE id = '".$_SESSION['leave_id']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $leave[] = $row;
      }
      $full_leave_flow = $leave[0]['leave_flow'];
      $manager = explode(";",$leave[0]['leave_flow']);
      $processflow = explode(';',$leave[0]['flow']);
      //$full_leave_flow = $user[0]['leave_flow'];
  }

  $query = "SELECT * FROM approval_flows WHERE requestId = '".$_SESSION['leave_id']."' AND type = 'Leave Request' ORDER BY id ASC";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $approvallist[] = $row;
      }
      
  }
  $sql = "SELECT * FROM users WHERE id = '".$_SESSION['staff_id']."'";
  $res = mysqli_query($conn, $sql);
      if(mysqli_num_rows($res)> 0){
          while($row = mysqli_fetch_assoc($res)) {
            $user[] = $row;
          }
          //$full_leave_flow = $user[0]['leave_flow'];
          //$manager = explode(";",$user[0]['leave_flow']);
          
  }
   if($full_leave_flow != '')
  {
    $flow = explode(';', $full_leave_flow);

    $req_flow = "";
    for($r = 0; $r < count($flow); $r++)
    {
      $f = explode(":", $flow[$r])[0]; 
      if($req_flow != '') $req_flow .= ";";
      $req_flow .= $f;
    }
    //echo $req_flow;
    $leave_flow = explode(";",$req_flow);
    //print_r($cash_flow);
  }
  $Req_sql = "SELECT * FROM company WHERE id = '".$_SESSION['user']['companyId']."'";
      $res = mysqli_query($conn, $Req_sql);
      if(mysqli_num_rows($res)> 0){
          while($row = mysqli_fetch_assoc($res)) {
            $company[] = $row;
          }
          //$leave_flow = explode(";",$company[0]['leave_flow']);
      }

  
  //print_r($items);
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
            <h3>Approve Leave</h3>
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
           
              <div class="col-md-7 col-sm-12 col-xs-12">
              <?php for($e = 0; $e < count($approvallist); $e++) {?> 
              
                <div class="x_panel">
                  <div class="x_title">
                    <h2 style="text-transform: uppercase;"><?=$approvallist[$e]['title']?>

                      <?php
                      //$email = explode(':',$manager[$e])[1];
                      $Req_sql = "SELECT * FROM users WHERE id = '".$approvallist[$e]['approvalId']."'";
                      $res = mysqli_query($conn, $Req_sql);
                      if(mysqli_num_rows($res)> 0){
                          while($row = mysqli_fetch_assoc($res)) {
                            echo $row['name'].' '.$row['fname'];
                          }
                         // $requisition_flow = explode(";",$company[0]['requisition_flow']);
                      }

                      ?>

                    </h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                   <div style="width: 100%;margin-left: auto;margin-right: auto;">
                     <ul class="list-group">
                      <?php
                       //$canapproval = $approvallist[$e+1]['status'];
                      $canapproval = true;
                       if(isset($approvallist[$e+1]['status']))
                       {
                          if($approvallist[$e+1]['status'] == 'approved' || $approvallist[$e+1]['status'] == 'decline')
                              $canapproval = false;
                       }
                       if(isset($approvallist[$e-1]['status']) && $canapproval == true)
                       {
                           if($approvallist[$e-1]['status'] == 'approved')
                              $canapproval = true;
                           else
                              $canapproval = false; 
                       }
                      // i $canapproval = true;
                        //echo $canapproval;
                      ?>
                      <li class="list-group-item d-flex justify-content-between align-items-center">Approve
                         <span class="badge badge-pill badge_leave" approvalId = "<?=$approvallist[$e]['approvalId']?>" canapproval = "<?= $canapproval?>" myId = "<?=$_SESSION['user']['id']?>" status = "approved" item_id = "<?=$leave[0]['id']?>" attr_id = "<?=$e?>" id = "approve<?=$e?>" style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
                            <?php if($approvallist[$e]['status'] != null && $approvallist[$e]['status'] == 'approved') {?>
                                  <i class="fas fa-check" style="padding: 0px;color: #4e73df;font-size: 12px;"></i>
                            <?php } ?> 
                            
                         </span>
                       </li> 
                      <li class="list-group-item d-flex justify-content-between align-items-center">Decline 
                        <span class="badge badge-pill badge_leave" approvalId = "<?=$approvallist[$e]['approvalId']?>" canapproval = "<?= $canapproval?>" myId = "<?=$_SESSION['user']['id']?>" status = "decline" item_id = "<?=$leave[0]['id']?>" attr_id = "<?=$e?>" id = "decline<?=$e?>" style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
                          <?php if($approvallist[$e]['status'] != null && $approvallist[$e]['status'] == 'decline') {?>
                                  <i class="fas fa-check" style="padding: 0px;color: #4e73df;font-size: 12px;"></i>
                              <?php } ?> 
                         </span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">Pending 
                        <span class="badge badge-pill badge_leave" approvalId = "<?=$approvallist[$e]['approvalId']?>" canapproval = "<?= $canapproval?>" myId = "<?=$_SESSION['user']['id']?>" status = "pend" item_id = "<?=$leave[0]['id']?>" attr_id = "<?=$e?>" id = "pend<?=$e?>" style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
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
                  <div class="form-group">
                        <label for="exampleInputEmail1"><?=$approvallist[$e]['title']?> Remark</label>
                        
                        <textarea style='width:100%' <?=$approvallist[$e]['approvalId'] == $_SESSION['user']['id'] ? '' : 'disabled'?> canapproval = "<?= $canapproval?>" approvalId = "<?=$approvallist[$e]['approvalId']?>" myId = "<?=$_SESSION['user']['id']?>" status = "pend" item_id = "<?=$leave[0]['id']?>" attr_id = "<?=$e?>" id="remark<?=$e?>"  class = "form-control remark"><?=$approvallist[$e]['remark']?></textarea>
                      </div>
                  <?php if($approvallist[$e]['approvalId'] == $_SESSION['user']['id'] && $canapproval == true) {?>    
                  <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <button type="submit" id = 'submit_btn_leave' approvallistId = "<?=$approvallist[$e]['id']?>" status = "<?=$approvallist[$e]['status']?>" remark = "<?=$approvallist[$e]['remark']?>"  item_id = "<?=$leave[0]['id']?>" class="btn btn-success" style="width:200px">Update</button>
                  </div> 
                  <?php }?> 
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
                   <?php if(count($leave) > 0 ){ ?>
                    <div class="table-responsive">
                      <table class="table table-striped">
                      <tbody>
                        <tr>
                             <td scope="row">Request By</td>
                             <td><?=$user[0]['name']?></td>
                        </tr>
                        <tr>
                             <td scope="row">Employee ID</td>
                             <td><?=$user[0]['employee_ID']?></td>
                        </tr>
                        <?php for ($y = 0; $y < count($leave); $y++) {?>
                        <tr>
                             <td scope="row">Leave Type</td>
                             <td><?=$leave[$y]['leave_type']?></td>
                        </tr>
                        <tr>
                             <td scope="row">Start Date</td>
                             <td><?=$leave[$y]['start_date']?></td>
                        </tr>
                        <tr>
                             <td scope="row">End Date</td>
                             <td><?=$leave[$y]['end_date']?></td>
                        </tr>
                        <tr>
                           <td style="width: 60%">Number of Days</td>
                           <td style="width: 40%"><?=$leave[$y]['number_of_days']?></td>
                         </tr>
                        <tr>
                             <td scope="row">Required Reliever</td>
                             <td><?=$leave[$y]['require_reliever']?></td>
                        </tr>
                        <tr>
                             <td scope="row">Reliever Source</td>
                             <td><?=$leave[$y]['reliever_source']?></td>
                        </tr>
                        <tr>
                             <td scope="row">Reliever Name</td>
                             <td><?=$leave[$y]['reliever_name']?></td>
                        </tr>
                        <tr>
                             <td scope="row">Reliever Email</td>
                             <td><?=$leave[$y]['reliever_email']?></td>
                        </tr>
                        <tr>
                             <td scope="row">Reliever Phone</td>
                             <td><?=$leave[$y]['reliever_phone']?></td>
                        </tr>
                        <tr>
                             <td scope="row">Request Date</td>
                             <td><?=$leave[$y]['date_created']?></td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table> 
                    </div>
                    <?php } ?> 
                  </div>
                </div>
            </div>
             
              
        </div>
</div>
</div>
<?php include "footer.php"?>
        

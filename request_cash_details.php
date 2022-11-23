<?php 
include 'connection.php';
session_start();
$cash = [];
$user = [];
$company = [];
$cash_flow = [];
$num_of_approved = 0;
$approvals = 0;
$manager = [];
$curr = '';
$full_cash_flow = '';
if(!isset($_SESSION['cash_id']) && $_SESSION['cash_id'] != '') header("Location: requestedcash.php");
 //echo $_SESSION['cash_id'];
  $query = "SELECT * FROM cash_request WHERE id = '".$_SESSION['cash_id']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $cash[] = $row;
        $full_cash_flow = $cash[0]['cash_flow'];
        $manager = explode(";",$cash[0]['cash_flow']); 
      }
  }

  $query = "SELECT * FROM approval_flows WHERE requestId = '".$_SESSION['cash_id']."' AND type = 'Cash Request' ORDER BY id ASC";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $approvallist[] = $row;
      }
      
  }
  if($_SESSION['user']['category'] == 'admin'){
      $sql = "SELECT * FROM users WHERE id = '".$cash[0]['staff_id']."'";
      $res = mysqli_query($conn, $sql);
      if(mysqli_num_rows($res)> 0){
          while($row = mysqli_fetch_assoc($res)) {
            $user[] = $row;
          }
      }
  }

 

  // $sql = "SELECT * FROM users WHERE id = '".$cash[0]['staff_id']."'";
  // $res = mysqli_query($conn, $sql);
  // if(mysqli_num_rows($res)> 0){
  //         while($row = mysqli_fetch_assoc($res)) {
  //           $user[] = $row;
  //         }
  //         $full_cash_flow = $user[0]['cash_flow'];
  //         $manager = explode(";",$user[0]['cash_flow']);          
  // }

  if($full_cash_flow != '')
  {
    $flow = explode(';', $full_cash_flow);

    $cash_flow = "";
    for($r = 0; $r < count($flow); $r++)
    {
      $f = explode(":", $flow[$r])[0]; 
      if($cash_flow != '') $cash_flow .= ";";
      $cash_flow .= $f;
    }
    //echo $req_flow;
    $cash_flow = explode(";",$cash_flow);
    //print_r($requisition_flow);
  }
  // if($_SESSION['user']['category'] == 'admin') $admin_id = $_SESSION['user']['id'];
  // else $admin_id = $_SESSION['user']['admin_id'];
  // $Req_sql = "SELECT * FROM company WHERE id = '".$_SESSION['user']['companyId']."'";
  //     $res = mysqli_query($conn, $Req_sql);
  //     if(mysqli_num_rows($res)> 0){
  //         while($row = mysqli_fetch_assoc($res)) {
  //           $company[] = $row;
  //         }
  //         $cash_flow = explode(";",$company[0]['cash_flow']);
  //     }
  if($cash[0]['flow'] != ''){
    $flow = explode(";",$cash[0]['flow']);
    for($r = 0; $r < count($flow); $r++){
      $approvals++;
      if($flow[$r] != ""){
        $eachflow = explode(":",$flow[$r]);
        if(count($eachflow) > 1){
          //echo $eachflow[1]."<br>";
          if($eachflow[1] == 'approved') $num_of_approved++;
        }
      }
    }
  }
  if($_SESSION['user']['category'] == 'staff') 
    $filename = base64_encode($_SESSION['user']['employee_ID']."_".time());
  else
   $filename = base64_encode($user[0]['employee_ID']."_".time());
//echo $approvals;
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
            <h3>Cash Request</h3>
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
              <!-- <div class="col-md-7 col-sm-12 col-xs-12">
              <?php for($e = 0; $e < count($manager); $e++) {?>
              
                <div class="x_panel">
                  <div class="x_title">
                    <h2 style="text-transform: uppercase;"><?=explode(':',$manager[$e])[0]?>
                      <?php
                      $email = explode(':',$manager[$e])[1];
                      $Req_sql = "SELECT * FROM users WHERE email = '".$email."'";
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
                      <li class="list-group-item d-flex justify-content-between align-items-center">Approve
                       
                         <span class="badge badge-pill badge_request" cash_flow = "<?=$cash_flow[$e]?>" category = "<?=$_SESSION['user']['category']?>" flow = "<?=$cash[0]['flow']?>" attr_id = "<?=$e?>" id= "approve<?=$e?>" status = "approved" item_id = "<?=$cash[0]['id']?>" approval = "<?=$cash_flow[$e]?>" style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
                            <?php $flow = explode(";", $cash[0]['flow']) ?>
                            <?php for ($t = 0; $t < count($flow); $t++) {?>
                               <?php $each_flow = explode(":", $flow[$t])[0]; ?>
                               <?php if($each_flow == $cash_flow[$e] && explode(":", $flow[$t])[1] == 'approved') {?>
                                  <i class="fas fa-check" style="padding: 0px;color: #4e73df;font-size: 12px;"></i>
                               <?php } ?> 
                            <?php } ?>  
                            
                         </span>
                       </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">Decline 
                        <span class="badge badge-pill badge_request" cash_flow = "<?=$cash_flow[$e]?>"" category = "<?=$_SESSION['user']['category']?>" flow = "<?=$cash[0]['flow']?>" attr_id = "<?=$e?>" id= "decline<?=$e?>" status = "Decline" item_id = "<?=$cash[0]['id']?>" approval = "<?=$cash_flow[$e]?>" style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
                          <?php $flow = explode(";", $cash[0]['flow']) ?>
                            <?php for ($t = 0; $t < count($flow); $t++) {?>
                               <?php $each_flow = explode(":", $flow[$t])[0]; ?>
                               <?php if($each_flow == $cash_flow[$e] && explode(":", $flow[$t])[1] == 'decline') {?>
                                  <i class="fas fa-check" style="padding: 0px;color: #4e73df; font-size: 12px;"></i>
                               <?php } ?> 
                            <?php } ?> 
                         </span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">Pending 
                        <span class="badge badge-pill badge_request" cash_flow = "<?=$cash_flow[$e]?>"" category = "<?=$_SESSION['user']['category']?>" flow = "<?=$cash[0]['flow']?>" attr_id = "<?=$e?>" id= "pend<?=$e?>" status = "pend" item_id = "<?=$cash[0]['id']?>" approval = "<?=$cash_flow[$e]?>" style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
                          <?php $flow = explode(";", $cash[0]['flow']) ?>
                            <?php for ($t = 0; $t < count($flow); $t++) {?>
                               <?php $each_flow = explode(":", $flow[$t])[0]; ?>
                               <?php if($each_flow == $cash_flow[$e] && explode(":", $flow[$t])[1] == 'pend') {?>
                                  <i class="fas fa-check" style="padding: 0px;color: #4e73df;font-size: 12px;"></i>
                               <?php } ?> 
                            <?php } ?> 
                         </span>
                      </li>
                    </ul>
                  </div>
                  </div>
                  <div style="width: 100%;margin-left: auto;margin-right: auto;">
                      <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-0">
                      <div class="form-group">
                        <label for="exampleInputEmail1"> Remark</label>
                       
                        <?php if($cash[0]['remarks'] != '') { ?>
                           
                           <?php $remarks = explode(";", $cash[0]['remarks']) ?> 
                           <?php for ($t = 0; $t < count($remarks); $t++) {?>
                               <?php $each_remark = explode(":", $remarks[$t])[0]; ?>
                               <?php if($each_remark == explode(":",$cash_flow[$e])[0]) {?>
                                <?php $curr = explode(":", $remarks[$t])[1]; ?>
                                <?php if (filter_var($curr, FILTER_VALIDATE_EMAIL)) {?>
                                    <?php  $curr = ''?>
                                  
                                <?php }?>
                               <?php } ?>
                              
                            <?php } ?>
                        <?php } ?>
                        
                        <textarea style='width:100%' disabled="true"  class = "form-control"><?=$curr?></textarea>
                      </div>
                  </div>
                  </div>
                </div>
                
              <?php } ?> 
      
              </div> -->
              <div class="col-md-5 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Cash Request</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <?php if(count($cash) > 0) {?>
                      <?php if($cash[0]['document'] != '') {?>  
                      <li><a href ="downloadfile.php/?to=view&filename=<?=$cash[0]['document']?>" id=""
                                name='' value=""
                                class="btn btn-success" style = "color: #fff;">Download</a>
                      </li>
                      <?php } }?>
                      <?php if($num_of_approved == $approvals) {?>
                     <!--  <li>
                        <a style="color: #fff;" href = "process_print_cash_doc.php/?cash_id=<?=base64_encode($_SESSION['cash_id'])?>&filename=<?=$filename?>"
                            class="btn btn-info">Print</a>
                      </li> -->
                    <?php } ?>
                    </ul>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                    <?php if(count($cash) > 0 ){ ?>
                   <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                      

                        <tbody>
                         <tr>
                           <td style="width: 60%">Request By</td>
                           <td style="width: 40%"><?=$_SESSION['user']['category'] == 'staff' ? $_SESSION['user']['name'] : $user[0]['name']?></td>
                         </tr>
                         <tr>
                           <td style="width: 60%">Employee ID</td>
                           <td style="width: 40%"><?=$_SESSION['user']['category'] == 'staff' ? $_SESSION['user']['employee_ID'] : $user[0]['employee_ID']?></td>
                         </tr>
                         <tr>
                           <td style="width: 60%">Request ID</td>
                           <td style="width: 40%"><?=$cash[0]['cash_id']?></td>
                         </tr>
                         <tr>
                           <td style="width: 60%">Purpose</td>
                           <td style="width: 40%">
                          
                               <?php
                               $query = "SELECT * FROM cash_category WHERE id = '".$cash[0]['purpose']."'";
                                $result = mysqli_query($conn, $query);
                                if(mysqli_num_rows($result)> 0){
                                    while($row = mysqli_fetch_assoc($result)) {
                                      echo $row['category'];
                                    }
                                }else{
                                   echo $cash[0]['purpose'];
                                }
                                ?>
                            </td>
                         </tr>
                         <tr>
                           <td style="width: 60%">Justification</td>
                           <td style="width: 40%"><?=$cash[0]['justification']?></td>
                         </tr>
                         <tr>
                           <td style="width: 60%">Amount</td>
                           <td style="width: 40%"><?=$cash[0]['amount']?></td>
                         </tr>
                          <tr>
                           <td style="width: 60%">Request Date</td>
                           <td style="width: 40%"><?=$cash[0]['date_created']?></td>
                         </tr>
                      </table>
                    </div> 
                    <?php } ?>
                  </div>
                </div>
              </div>
              <!-- <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"> 
                    <button type="submit" id = 'submit_btn' class="btn btn-success">Update</button>
              </div>    -->
        </div>
</div>
</div>
<?php include "footer.php"?>
        

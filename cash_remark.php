<?php 
include 'connection.php';
session_start();
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
$cash = [];
$user = [];
$company = [];
$cash_flow = [];
$full_cash_flow;
$approvals = 0;
$num_of_approved = 0;
$manager =[];
$curr = '';
$processflow = [];
//echo $_SESSION['requestitem_id'];
if(!isset($_SESSION['cash_id']) && $_SESSION['cash_id'] != '') header("Location: cash_request.php");

  $query = "SELECT * FROM cash_request WHERE id = '".$_SESSION['cash_id']."' ORDER BY id DESC";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $cash[] = $row;
        $full_cash_flow = $cash[0]['cash_flow'];
        $manager = explode(";",$cash[0]['cash_flow']);
        $processflow = explode(';',$cash[0]['flow']);
      }
  }

   $query = "SELECT * FROM approval_flows WHERE requestId = '".$_SESSION['cash_id']."' AND type = 'Cash Request' ORDER BY id ASC";
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
          //  $full_cash_flow = $user[0]['cash_flow'];
//          $manager = explode(";",$user[0]['cash_flow']);
          
  }
   if($full_cash_flow != '')
  {
    $flow = explode(';', $full_cash_flow);

    $req_flow = "";
    for($r = 0; $r < count($flow); $r++)
    {
      $f = explode(":", $flow[$r])[0]; 
      if($req_flow != '') $req_flow .= ";";
      $req_flow .= $f;
    }
    //echo $req_flow;
    $cash_flow = explode(";",$req_flow);
    //print_r($cash_flow);
  }

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
  //print_r($items);
?>
<?php include "header.php"?>
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
                         <span class="badge badge-pill badge_cash" approvalId = "<?=$approvallist[$e]['approvalId']?>" canapproval = "<?= $canapproval?>" myId = "<?=$_SESSION['user']['id']?>" status = "approved" item_id = "<?=$cash[0]['id']?>" attr_id = "<?=$e?>" id = "approve<?=$e?>" style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
                            <?php if($approvallist[$e]['status'] != null && $approvallist[$e]['status'] == 'approved') {?>
                                  <i class="fas fa-check" style="padding: 0px;color: #4e73df;font-size: 12px;"></i>
                            <?php } ?> 
                            
                         </span>
                       </li> 
                      <li class="list-group-item d-flex justify-content-between align-items-center">Decline 
                        <span class="badge badge-pill badge_cash" approvalId = "<?=$approvallist[$e]['approvalId']?>" canapproval = "<?= $canapproval?>" myId = "<?=$_SESSION['user']['id']?>" status = "decline" item_id = "<?=$cash[0]['id']?>" attr_id = "<?=$e?>" id = "decline<?=$e?>" style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
                          <?php if($approvallist[$e]['status'] != null && $approvallist[$e]['status'] == 'decline') {?>
                                  <i class="fas fa-check" style="padding: 0px;color: #4e73df;font-size: 12px;"></i>
                              <?php } ?> 
                         </span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">Pending 
                        <span class="badge badge-pill badge_cash" approvalId = "<?=$approvallist[$e]['approvalId']?>" canapproval = "<?= $canapproval?>" myId = "<?=$_SESSION['user']['id']?>" status = "pend" item_id = "<?=$cash[0]['id']?>" attr_id = "<?=$e?>" id = "pend<?=$e?>" style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
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
                        
                        <textarea style='width:100%' <?=$approvallist[$e]['approvalId'] == $_SESSION['user']['id'] ? '' : 'disabled'?> canapproval = "<?= $canapproval?>" approvalId = "<?=$approvallist[$e]['approvalId']?>" myId = "<?=$_SESSION['user']['id']?>" status = "pend" item_id = "<?=$cash[0]['id']?>" attr_id = "<?=$e?>" id="remark<?=$e?>"  class = "form-control cash_remark"><?=$approvallist[$e]['remark']?></textarea>
                      </div>
                  <?php if($approvallist[$e]['approvalId'] == $_SESSION['user']['id'] && $canapproval == true) {?>    
                  <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <button type="submit" id = 'submit_btn_cash' approvallistId = "<?=$approvallist[$e]['id']?>" status = "<?=$approvallist[$e]['status']?>" remark = "<?=$approvallist[$e]['remark']?>"  item_id = "<?=$cash[0]['id']?>" class="btn btn-success" style="width:200px">Update</button>
                  </div> 
                  <?php }?> 
                  </div>
                </div>
              
              <?php } ?> 
              </div>
            <!-- <div class="col-md-7 col-sm-12 col-xs-12">
            
               <?php for($e = 0; $e < count($manager); $e++) {?>
              <?php $each_manager = explode(":", $manager[$e])[0]; ?> 
               <?php $email = explode(":", $manager[$e])[1]; ?> 
               <?php if($e > 0) {?>
               <?php $each_process = explode(":", $processflow[$e-1])[1]; ?>

               <?php }else { 
                $each_process = 'approved';
               } ?> 
               <?php if(strtolower(trim($email)) == strtolower(trim($_SESSION['user']['email'])) && $each_process == 'approved') {?>
               <?php $link = 'badge_leave';?>   
               <?php $disabled = '';?>      
               <?php }else {?>
               <?php $link = 'general_leave';?>   
               <?php $disabled = 'disabled';?> 
               <?php } ?>  
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
                         <span class="badge badge-pill badge_cash" full_cash_flow = "<?=$cash[0]['cash_flow']?>" each_process_status = "<?=$each_process?>" email = "<?=$_SESSION['user']['email']?>" cash_flow = "<?=$cash_flow[$e]?>" category = "<?=$_SESSION['user']['category']?>" flow = "<?=$cash[0]['flow']?>" attr_id = "<?=$e?>" id= "approve<?=$e?>" status = "approved" cash_id = "<?=$cash[0]['id']?>" approval = "<?=$cash_flow[$e]?>" style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
                         
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
                        <span class="badge badge-pill badge_cash" full_cash_flow = "<?=$cash[0]['cash_flow']?>" each_process_status = "<?=$each_process?>" email = "<?=$_SESSION['user']['email']?>" cash_flow = "<?=$cash_flow[$e]?>"" category = "<?=$_SESSION['user']['category']?>" flow = "<?=$cash[0]['flow']?>" attr_id = "<?=$e?>" id= "decline<?=$e?>" status = "decline" cash_id = "<?=$cash[0]['id']?>" approval = "<?=$cash_flow[$e]?>" style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
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
                        <span class="badge badge-pill badge_cash" full_cash_flow = "<?=$cash[0]['cash_flow']?>" each_process_status = "<?=$each_process?>" email = "<?=$_SESSION['user']['email']?>" cash_flow = "<?=$cash_flow[$e]?>" category = "<?=$_SESSION['user']['category']?>" flow = "<?=$cash[0]['flow']?>" attr_id = "<?=$e?>" id= "pend<?=$e?>" status = "pend" cash_id = "<?=$cash[0]['id']?>" approval = "<?=$cash_flow[$e]?>" style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
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
                   <div class="form-group">
                        <label for="exampleInputEmail1"><?=$each_manager?> Remark</label>
                        
                        <?php if($cash[0]['remarks'] != '') { ?>
                           <?php $remarks = explode(";", $cash[0]['remarks']) ?>
                           <?php for ($t = 0; $t < count($remarks); $t++) {?>
                               <?php $each_remark = explode(":", $remarks[$t])[0]; ?>
                               <?php if($each_remark == $each_manager) {?>
                                <?php $curr = explode(":", $remarks[$t])[1]; ?>
                                <?php if (filter_var($curr, FILTER_VALIDATE_EMAIL)) {?>
                                    <?php  $curr = ''?>
                                  
                                <?php }?>
                               <?php } ?>
                              
                            <?php } ?>
                        <?php } ?>
                        
                        <textarea style='width:100%' <?=$disabled?> full_cash_flow = "<?=$cash[0]['cash_flow']?>" email = "<?=$_SESSION['user']['email']?>" cash_flow = "<?=$cash_flow[$e]?>" category = "<?=$_SESSION['user']['category']?>" flow = "<?=$cash[0]['flow']?>" attr_id = "<?=$e?>" id= "cash_approve<?=$e?>" status = "approved" cash_id = "<?=$cash[0]['id']?>" approval = "<?=$cash_flow[$e]?>"  class = "form-control cash_remark"><?=$curr?></textarea>
                      </div>
                      <?php if($disabled != 'disabled') {?>    
                  <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <button type="submit" id = 'submit_btn_cash' full_cash_flow = "<?=$cash[0]['cash_flow']?>" email = "<?=$_SESSION['user']['email']?>" cash_flow = "<?=$cash_flow[$e]?>" category = "<?=$_SESSION['user']['category']?>" flow = "<?=$cash[0]['flow']?>" attr_id = "<?=$e?>" id= "approve<?=$e?>" status = "approved" cash_id = "<?=$cash[0]['id']?>" approval = "<?=$cash_flow[$e]?>" remarks = "<?=$cash[0]['remarks']?>" class="btn btn-success" style="width:200px">Update</button>
                  </div> 
                  <?php }?> 
                  </div>
                </div> 
              <?php } ?> 
            </div> -->
            <div class="col-md-5 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Request</h2>
                     <ul class="nav navbar-right panel_toolbox">
                      <?php if(count($cash) > 0) {?>
                      <?php if($cash[0]['document'] != '') {?>  
                      <li><a href ="downloadfile.php/?to=view&filename=<?=$cash[0]['document']?>" id=""
                                name='' value=""
                                class="btn btn-success" style = "color: #fff;">Download</a>
                      </li>
                      <?php } }?>
                      <?php if($num_of_approved == $approvals) {?>
                      <li>
                        <!-- <a style="color: #fff;" href = "process_print_cash_doc.php/?cash_id=<?=base64_encode($_SESSION['cash_id'])?>&filename=<?=base64_encode('cash_request_'.time())?>"
                            class="btn btn-info">Print</a> -->
                      </li>
                    <?php } ?>
                    </ul>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                   <div style="width: 100%;margin-left: auto;margin-right: auto;">
                     <ul class="list-group">
                      <li class="list-group-item d-flex justify-content-between align-items-center">Request By
                       
                         <span class="badge badge-pill" style="font-size: 15px;font-weight: 300; background-color: #fff;color:#73879C;text-transform:capitalize"><?=$user[0]['name']?></span>
                       </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">Employee ID: 
                        <span class="badge badge-pill" style="font-size: 15px;font-weight: 300;background-color: #fff;color:#73879C"><?=$user[0]['employee_ID']?></span>
                      </li>
                      <?php for ($y = 0; $y < count($cash); $y++) {?>
                      <li class="list-group-item d-flex justify-content-between align-items-center">Purpose <span class="badge badge-primary badge-pill" style="font-size: 15px;font-weight: 300;background-color: #fff;color:#73879C;text-transform:capitalize">
                      <!--   <?=$cash[$y]['purpose']?> -->
                             <?php
                               $query = "SELECT * FROM cash_category WHERE id = '".$cash[$y]['purpose']."'";
                                $result = mysqli_query($conn, $query);
                                if(mysqli_num_rows($result)> 0){
                                    while($row = mysqli_fetch_assoc($result)) {
                                      echo $row['category'];
                                    }
                                }else{
                                   echo $cash[$y]['purpose'];
                                }
                                ?>
                        </span>
                      </li>
                      <li class="list-group-item">Justification : <?=$cash[$y]['justification']?></li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">Amount 
                        <span class="badge badge-primary badge-pill" style="font-size: 15px;font-weight: 300;background-color: #fff;color:#73879C;text-transform:capitalize"><?=$cash[$y]['amount']?></span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">Amount 
                        <span class="badge badge-primary badge-pill" style="font-size: 15px;font-weight: 300;background-color: #fff;color:#73879C;text-transform:capitalize"><?=$cash[$y]['cash_id']?></span>
                      </li>
                       <li class="list-group-item d-flex justify-content-between align-items-center">
                        Request Date 
                        <span class="badge badge-primary badge-pill" style="font-size: 15px;font-weight: 300;background-color: #fff;color:#73879C;text-transform:capitalize"><?=$cash[$y]['date_created']?></span>
                      </li>
                      <?php } ?>
                    </ul>
                  </div>
                  </div>
                </div>
            </div>
             <!--  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"> 
                    <button type="submit" id = 'submit_btn_cash' class="btn btn-success">Update</button>
              </div>  -->  
        </div>
</div>
</div>
<?php include "footer.php"?>
<script type="text/javascript" src="js/cash_request.js?version=1.26"></script>
        

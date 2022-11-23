<?php 
include 'connection.php';
session_start();
$items = [];
$user = [];
$company = [];
$curr = '';
$requisition_flow = [];
$approvallist = [];
if($_SESSION['user']['id'] == '') header("Location: login.php");
if(!isset($_SESSION['item_id']) && $_SESSION['item_id'] != '') header("Location: requesteditems.php");



if(isset($_POST['submit']))
{
  
  $id = mysqli_real_escape_string($conn, $_POST['id']);
  $item = mysqli_real_escape_string($conn, $_POST['item']);
  $update = '';

  if(isset($_POST['datedisbursed']))
  {
    $datedisbursed = mysqli_real_escape_string($conn, $_POST['datedisbursed']);
    $update .= "disbursed_date = '".$datedisbursed."'";
    //echo $datedisbursed;
  }
  if(isset($_POST['datereceived']))
  {
    $datereceived = mysqli_real_escape_string($conn, $_POST['datereceived']);
     if($update != '') $update .= ',';
     $update .= "received_date = '".$datereceived."'";
     //echo $datereceived;
  }
  if(isset($_POST['quantity']))
  {
     $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
     if($update != '') $update .= ',';
     $update .= "quantity = '".$quantity."'";
  }

  $sql = "UPDATE requesteditem SET $update WHERE id = '".$id."' ";
      if (mysqli_query($conn, $sql)) {
          $_SESSION['msg'] = "Record has been updated";
           $query = "SELECT * FROM items WHERE item_name = '".$item."' AND companyId = '".$_SESSION['user']['companyId']."'";
            $result = mysqli_query($conn, $query);
            if(mysqli_num_rows($result)> 0){
                while($row = mysqli_fetch_assoc($result)) {
                  $quanInInventory = $row['item_quantity'];
                  $item_id = $row['id'];
                 // print_r($row);
                 // exit();
                }

                  $left = (float)$quanInInventory - (float)$_POST['quantity'];
                  if($left > 0 && isset($_POST['quantity']) && $_POST['disbursed_info'] == '')
                  {
                    $sql = "UPDATE items SET item_quantity = '".$left."' WHERE id = '".$item_id."' ";
                        if (mysqli_query($conn, $sql)) {}
                  }
            }
            
      }
}
  $full_requisition_flow = '';

  $query = "SELECT * FROM requesteditem WHERE id = '".$_SESSION['item_id']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
      }
      $full_requisition_flow = $items[0]['requisition_flow'];
      $manager = explode(";",$items[0]['requisition_flow']);   
  }

  $query = "SELECT * FROM approval_flows WHERE requestId = '".$_SESSION['item_id']."' AND type = 'Item Request' ORDER BY id ASC";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $approvallist[] = $row;
      }
      
  }
    $sql = "SELECT * FROM users WHERE id = '".$items[0]['staff_id']."'";
    $res = mysqli_query($conn, $sql);
    if(mysqli_num_rows($res)> 0){
        while($row = mysqli_fetch_assoc($res)) {
          $user[] = $row;
        }
    }


  if($full_requisition_flow != '')
  {
    $flow = explode(';', $full_requisition_flow);

    $req_flow = "";
    for($r = 0; $r < count($flow); $r++)
    {
      $f = explode(":", $flow[$r])[0]; 
      if($req_flow != '') $req_flow .= ";";
      $req_flow .= $f;
    }
    //echo $req_flow;
    $requisition_flow = explode(";",$req_flow);
    //print_r($requisition_flow);
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
            <h3>Request Item</h3>
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
             <!--  <div class="col-md-7 col-sm-12 col-xs-12">
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
                       
                         <span class="badge badge-pill badge_request" requisition_flow = "<?=$requisition_flow[$e]?>"" category = "<?=$_SESSION['user']['category']?>" flow = "<?=$items[0]['flow']?>" attr_id = "<?=$e?>" id= "approve<?=$e?>" status = "approved" item_id = "<?=$items[0]['id']?>" approval = "<?=$requisition_flow[$e]?>" style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
                            <?php $flow = explode(";", $items[0]['flow']) ?>
                            <?php for ($t = 0; $t < count($flow); $t++) {?>
                               <?php $each_flow = explode(":", $flow[$t])[0]; ?>
                               <?php if($each_flow == $requisition_flow[$e] && explode(":", $flow[$t])[1] == 'approved') {?>
                                  <i class="fas fa-check" style="padding: 0px;color: #4e73df;font-size: 12px;"></i>
                               <?php } ?> 
                            <?php } ?>  
                            
                         </span>
                       </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">Decline 
                        <span class="badge badge-pill badge_request" requisition_flow = "<?=$requisition_flow[$e]?>"" category = "<?=$_SESSION['user']['category']?>" flow = "<?=$items[0]['flow']?>" attr_id = "<?=$e?>" id= "decline<?=$e?>" status = "Decline" item_id = "<?=$items[0]['id']?>" approval = "<?=$requisition_flow[$e]?>" style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
                          <?php $flow = explode(";", $items[0]['flow']) ?>
                            <?php for ($t = 0; $t < count($flow); $t++) {?>
                               <?php $each_flow = explode(":", $flow[$t])[0]; ?>
                               <?php if($each_flow == $requisition_flow[$e] && explode(":", $flow[$t])[1] == 'decline') {?>
                                  <i class="fas fa-check" style="padding: 0px;color: #4e73df; font-size: 12px;"></i>
                               <?php } ?> 
                            <?php } ?> 
                         </span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">Pending 
                        <span class="badge badge-pill badge_request" requisition_flow = "<?=$requisition_flow[$e]?>"" category = "<?=$_SESSION['user']['category']?>" flow = "<?=$items[0]['flow']?>" attr_id = "<?=$e?>" id= "pend<?=$e?>" status = "pend" item_id = "<?=$items[0]['id']?>" approval = "<?=$requisition_flow[$e]?>" style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
                          <?php $flow = explode(";", $items[0]['flow']) ?>
                            <?php for ($t = 0; $t < count($flow); $t++) {?>
                               <?php $each_flow = explode(":", $flow[$t])[0]; ?>
                               <?php if($each_flow == $requisition_flow[$e] && explode(":", $flow[$t])[1] == 'pend') {?>
                                  <i class="fas fa-check" style="padding: 0px;color: #4e73df;font-size: 12px;"></i>
                               <?php } ?> 
                            <?php } ?> 
                         </span>
                      </li>
                    </ul>
                  </div>
                  <div style="width: 100%;margin-left: auto;margin-right: auto;">
                      <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-0">
                      <div class="form-group">
                        <label for="exampleInputEmail1"> Remark</label>
                       
                        <?php if($items[0]['remarks'] != '') { ?>
                           
                           <?php $remarks = explode(";", $items[0]['remarks']) ?> 
                           <?php for ($t = 0; $t < count($remarks); $t++) {?>
                               <?php $each_remark = explode(":", $remarks[$t])[0]; ?>
                               <?php if($each_remark == explode(":",$requisition_flow[$e])[0]) {?>
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
                </div>
                
              <?php } ?> 
            
              </div> -->
              <div class="col-md-5 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>New Request</h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                    <?php if(count($items) > 0 ){ ?>
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                       
                        <tbody>
                         <tr>
                           <td style="width: 60%">Request By</td>
                           <td style="width: 40%;text-transform:capitalize"><?=$_SESSION['user']['category'] == 'staff' ? $_SESSION['user']['name'] : $user[0]['name']?></td>
                         </tr>
                         <tr>
                           <td style="width: 60%">Employee ID</td>
                           <td style="width: 40%;"><?=$_SESSION['user']['category'] == 'staff' ? $_SESSION['user']['employee_ID'] : $user[0]['employee_ID']?></td>
                         </tr>
                          <tr>
                           <td style="width: 60%">Request ID</td>
                           <td style="width: 40%;"><?=$items[0]['item_id']?></td>
                         </tr>
                         <tr>
                           <td style="width: 60%">Item Name</td>
                           <td style="width: 40%;text-transform:capitalize">
                          
                           <?php 
                              $query = "SELECT * FROM items WHERE id = '".$items[0]['item']."'";
                              $result = mysqli_query($conn, $query);
                              if(mysqli_num_rows($result)> 0){
                                  while($row = mysqli_fetch_assoc($result)) {
                                    echo $row['item_name'];
                                  }
                              }else {
                                echo $items[0]['item'];
                              }
                              ?>   
                          </td>
                         </tr>
                         <tr>
                           <td style="width: 60%">Justification</td>
                           <td style="width: 40%;text-transform:capitalize"><?=$items[0]['justification']?></td>
                         </tr>
                         <tr>
                           <td style="width: 60%">Quantity</td>
                           <td style="width: 40%"><?=$items[0]['quantity']?></td>
                         </tr>
                          <tr>
                           <td style="width: 60%">Request Date</td>
                           <td style="width: 40%"><?=$items[0]['date_created']?></td>
                         </tr>
                         <tr>
                           <td style="width: 60%">Received Date</td>
                           <td style="width: 40%"><?=$items[0]['received_date']?></td>
                         </tr>
                         <tr>
                           <td style="width: 60%">Disbursed Date</td>
                           <td style="width: 40%"><?=$items[0]['disbursed_date']?></td>
                         </tr>
                      </table>
                      <button type="submit" id="btnExport"
                                    name='export' value=""
                                    class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal"> Update Request Status</button>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <!-- <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"> 
                    <button type="submit" item_id = "<?=$items[0]['id']?>" id = 'submit_btn' class="btn btn-success">Update</button>
              </div>   --> 
        </div>
</div>
 <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">Update</h2>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <div class="modal-body">
                 <form action = 'request_details.php' method = "POST" enctype="multipart/form-data">
                  <?php if($_SESSION['user']['category'] == 'admin' || $_SESSION['user']['add_item_permission'] == '1') { ?>

                  <div class="form-group" style="">
                    <label for="exampleInputEmail1">Date Disbursed</label>
                    <input type="date" value="<?=$items[0]['disbursed_date']?>" name ="datedisbursed" class="form-control" aria-describedby="emailHelp" placeholder="">
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1">Quanitity Disbursed</label>
                    <input type="number" value="<?=$items[0]['quantity']?>" name="quantity" value="<?=$items[0]['quantity']?>" class="form-control" aria-describedby="emailHelp" placeholder="">
                  </div>
                  <?php } ?>
                  <?php if($items[0]['staff_id'] == $_SESSION['user']['id']) { ?>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Date Received</label>
                    <input type="date" value="<?=$items[0]['received_date']?>" name ="datereceived" class="form-control" aria-describedby="emailHelp" placeholder="">
                   
                  </div>
                  <?php } ?>
                   <input style="display: none" type="text" name ="id" value="<?=$items[0]['id']?>" class="form-control" aria-describedby="emailHelp" placeholder="">
                    <input style="display: none" type="text" name ="item" value="<?=$items[0]['item']?>" class="form-control" aria-describedby="emailHelp" placeholder="">
                     <input style="display: none" type="text" name ="disbursed_info" value="<?=$items[0]['disbursed_date']?>" class="form-control" aria-describedby="emailHelp" placeholder="">
                  <input type="submit" name = "submit" class="btn btn-primary"/>
                </form>
              </div>
            </div>
          </div>
        </div>
</div>
<?php include "footer.php"?>
        

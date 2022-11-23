<?php 
include 'connection.php';
include 'connectionpdo.php';
session_start();


// $stmt = $pdo->prepare("SELECT * FROM approval_flows INNER JOIN requesteditem ON requesteditem.id = approval_flows.requestId INNER JOIN users ON users.id = requesteditem.staff_id INNER JOIN items ON items.id = requesteditem.item WHERE approvalId = ?");
//       $stmt->execute(['193']); 
//       $requests = $stmt->fetch();
//       print_r($requests);
if(!isset($_SESSION['user']['id'])) header("Location: login.php");
$items = [];
$user = [];
$company = [];
$requisition_flow = [];
$full_requisition_flow;
$manager = [];
$curr = '';
$processflow= [];
$approvallist = [];
//echo $_SESSION['requestitem_id'];
if(!isset($_SESSION['requestitem_id']) && $_SESSION['requestitem_id'] != '') header("Location: requesteditems.php");

  $query = "SELECT * FROM requesteditem WHERE id = '".$_SESSION['requestitem_id']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
      }
      $full_requisition_flow = $items[0]['requisition_flow'];
      $manager = explode(";",$items[0]['requisition_flow']);   
      $processflow = explode(';',$items[0]['flow']);
  }


  $query = "SELECT * FROM approval_flows WHERE requestId = '".$_SESSION['requestitem_id']."' AND type = 'Item Request' ORDER BY id ASC";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $approvallist[] = $row;
      }
      
  }
//print_r($approvallist);


  $sql = "SELECT * FROM users WHERE id = '".$_SESSION['staff_id']."'";
  $res = mysqli_query($conn, $sql);
  if(mysqli_num_rows($res)> 0){
          while($row = mysqli_fetch_assoc($res)) {
            $user[] = $row;
          }
          //$full_requisition_flow = $user[0]['requisition_flow'];
          //$manager = explode(";",$user[0]['requisition_flow']);          
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

  if($_SESSION['user']['category'] == 'admin') $admin_id = $_SESSION['user']['id'];
  else $admin_id = $_SESSION['user']['admin_id'];
  $Req_sql = "SELECT * FROM company WHERE id = '".$_SESSION['user']['companyId']."'";
      $res = mysqli_query($conn, $Req_sql);
      if(mysqli_num_rows($res)> 0){
          while($row = mysqli_fetch_assoc($res)) {
            $company[] = $row;
          }
          //$requisition_flow = explode(";",$company[0]['requisition_flow']);
      }
  //print_r($items);
?>
<?php include "header.php"?>
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
                         <span class="badge badge-pill badge_request" approvalId = "<?=$approvallist[$e]['approvalId']?>" canapproval = "<?= $canapproval?>" myId = "<?=$_SESSION['user']['id']?>" status = "approved" item_id = "<?=$items[0]['id']?>" attr_id = "<?=$e?>" id = "approve<?=$e?>" style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
                            <?php if($approvallist[$e]['status'] != null && $approvallist[$e]['status'] == 'approved') {?>
                                  <i class="fas fa-check" style="padding: 0px;color: #4e73df;font-size: 12px;"></i>
                            <?php } ?> 
                            
                         </span>
                       </li> 
                      <li class="list-group-item d-flex justify-content-between align-items-center">Decline 
                        <span class="badge badge-pill badge_request" approvalId = "<?=$approvallist[$e]['approvalId']?>" canapproval = "<?= $canapproval?>" myId = "<?=$_SESSION['user']['id']?>" status = "decline" item_id = "<?=$items[0]['id']?>" attr_id = "<?=$e?>" id = "decline<?=$e?>" style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
                          <?php if($approvallist[$e]['status'] != null && $approvallist[$e]['status'] == 'decline') {?>
                                  <i class="fas fa-check" style="padding: 0px;color: #4e73df;font-size: 12px;"></i>
                              <?php } ?> 
                         </span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">Pending 
                        <span class="badge badge-pill badge_request" approvalId = "<?=$approvallist[$e]['approvalId']?>" canapproval = "<?= $canapproval?>" myId = "<?=$_SESSION['user']['id']?>" status = "pend" item_id = "<?=$items[0]['id']?>" attr_id = "<?=$e?>" id = "pend<?=$e?>" style="font-size: 15px;background-color: #fff;color:#73879C;width: 25px;height: 25px;border-radius: 14px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;">
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
                        
                        <textarea style='width:100%' <?=$approvallist[$e]['approvalId'] == $_SESSION['user']['id'] ? '' : 'disabled'?> canapproval = "<?= $canapproval?>" approvalId = "<?=$approvallist[$e]['approvalId']?>" myId = "<?=$_SESSION['user']['id']?>" status = "pend" item_id = "<?=$items[0]['id']?>" attr_id = "<?=$e?>" id="remark<?=$e?>"  class = "form-control req_remark"><?=$approvallist[$e]['remark']?></textarea>
                      </div>
                  <?php if($approvallist[$e]['approvalId'] == $_SESSION['user']['id'] && $canapproval == true) {?>    
                  <div class="col-md-6 col-sm-6 col-xs-12"> 
                    <button type="submit" id = 'submit_btn' approvallistId = "<?=$approvallist[$e]['id']?>" status = "<?=$approvallist[$e]['status']?>" remark = "<?=$approvallist[$e]['remark']?>"  item_id = "<?=$items[0]['id']?>" class="btn btn-success" style="width:200px">Update</button>
                  </div> 
                  <?php }?> 
                  </div>
                </div>
              
              <?php } ?> 
              </div>
           
               <div class="col-md-5 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Request</h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                   <div style="width: 90%;margin-left: auto;margin-right: auto;">
                     <ul class="list-group">
                      <li class="list-group-item d-flex justify-content-between align-items-center">Request By
                       
                         <span class="badge badge-pill" style="font-size: 15px;font-weight: 300; background-color: #fff;color:#73879C;text-transform:capitalize"><?=$user[0]['name']?></span>
                       </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">Employee ID: 
                        <span class="badge badge-pill" style="font-size: 15px;font-weight: 300;background-color: #fff;color:#73879C"><?=$user[0]['employee_ID']?></span>
                      </li>
                      <?php for ($y = 0; $y < count($items); $y++) {?>
                      <li class="list-group-item d-flex justify-content-between align-items-center">Item Name <span class="badge badge-primary badge-pill" style="font-size: 15px;font-weight: 300;background-color: #fff;color:#73879C;;text-transform:capitalize">
                       
                         <?php 
                            $query = "SELECT * FROM items WHERE id = '".$items[$y]['item']."'";
                            $result = mysqli_query($conn, $query);
                            if(mysqli_num_rows($result)> 0){
                                while($row = mysqli_fetch_assoc($result)) {
                                  echo $row['item_name'];
                                }
                            }else {
                              echo $items[$y]['item'];
                            }
                            ?> 
                        </span></li>
                      <li class="list-group-item" style='text-transform:capitalize'>Justification : <?=$items[$y]['justification']?></li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">Quantity 
                        <span class="badge badge-primary badge-pill" style="font-size: 15px;font-weight: 300;background-color: #fff;color:#73879C"><?=$items[$y]['quantity']?></span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        Request ID
                        <span class="badge badge-primary badge-pill" style="font-size: 15px;font-weight: 300;background-color: #fff;color:#73879C"><?=$items[$y]['item_id']?></span>
                      </li>
                       <li class="list-group-item d-flex justify-content-between align-items-center">
                        Request Date 
                        <span class="badge badge-primary badge-pill" style="font-size: 15px;font-weight: 300;background-color: #fff;color:#73879C"><?=$items[$y]['date_created']?></span>
                      </li>
                      <?php } ?>
                    </ul>
                  </div>
                  </div>
                </div>
                 <div class="x_panel">
                  <div class="x_title">
                    <h2>Other Information</h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                   <div style="width: 90%;margin-left: auto;margin-right: auto;">
                     <ul class="list-group">
                      
                      <li class="list-group-item d-flex justify-content-between align-items-center">Date received 
                        <span class="badge badge-pill badge_request" requisition_flow = "<?=$requisition_flow[$e]?>"" category = "<?=$_SESSION['user']['category']?>" flow = "<?=$items[0]['flow']?>" attr_id = "<?=$e?>" id= "decline<?=$e?>" status = "" item_id = "<?=$items[0]['id']?>" approval = "<?=$requisition_flow[$e]?>" style="font-size: 15px;font-weight: 300; background-color: #fff;color:#73879C">
                            <?=$items[0]['received_date']?>
                         </span>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center" style='display:none'>Returned 
                       
                         <span class="" style='background-color:transparent'> 
                              <select id = 'returned' value='' class='form-control'>
                                 <option value = ''></option>
                                 <option value = 'Yes'>Yes</option>
                              </select>
                         </span>
                       </li> 
                       <li class="list-group-item d-flex justify-content-between align-items-center" style='display:none'>Received 
                       
                         <span class="" style='background-color:transparent'> 
                              <select id = 'received' value='' class='form-control'>
                                 <option value = ''></option>
                                 <option value = 'Yes'>Yes</option>
                              </select>
                         </span>
                       </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">Return date
                        <span class="badge badge-pill badge_request" requisition_flow = "<?=$requisition_flow[$e]?>"" category = "<?=$_SESSION['user']['category']?>" flow = "<?=$items[0]['flow']?>" attr_id = "<?=$e?>" id= "pend<?=$e?>" status = "" item_id = "<?=$items[0]['id']?>" approval = "<?=$requisition_flow[$e]?>" style="font-size: 15px;font-weight: 300; background-color: #fff;color:#73879C">
                         
                         </span>
                      </li>
                    </ul>
                  </div>
                  </div>
                </div>
              </div>
              <div class="col-md-8 col-sm-12 col-xs-12 col-md-offset-2">
               
              </div>
              <!-- <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"> 
                    <button type="submit" id = 'submit_btn' item_id = "<?=$items[0]['id']?>" class="btn btn-success">Update</button>
              </div>    -->
        </div>
</div>
</div>
<?php include "footer.php"?>
        

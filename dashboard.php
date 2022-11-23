<?php 
include 'connection.php';
include 'connectionpdo.php';
session_start();
$appraisal = [];
$leaves = [];
$requisition = [];
$id_card = [];
$show = 'CLOSED';
$company = [];
$id_card_processor = [];
$appraisal_uploader = [];
$data_item = [];
$kss = [];
$items = [];
$staff = [];
$all_months = [];
$courses = [];
$leave_type = [];

//print_r($_SESSION['approval_settings']);
$month = ["January", "February", "March", "April", "May", "June", "July", "August", "September","October","November", "December"];
//print_r($_SESSION['user']['category']);
  if(!isset($_SESSION['user']['id'])) header("Location: login.php");


  $query = "SELECT * FROM courses WHERE companyId = '".$_SESSION['user']['companyId']."'";   
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $courses[] = $row;
      }
  }
  
   $query1 = "SELECT * FROM users WHERE grade = 'Intern'";   
  $result = mysqli_query($conn, $query1);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $interns[] = $row;
      }
  }
  

  $queryy = "SELECT * FROM users WHERE active = 4";   
  $result = mysqli_query($conn, $queryy);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $suspend[] = $row;
      }
  }
  
    $queryy = "SELECT * FROM users WHERE active = 3";   
  $result = mysqli_query($conn, $queryy);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $suspend[] = $row;
      }
  }

     $query2 = "SELECT * FROM users WHERE date_created between DATE_FORMAT(CURDATE() ,'%Y-%m-01') AND CURDATE() ";    
  $result = mysqli_query($conn, $query2);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $created_at[] = $row;
      }
      //print_r($created_at);
  }
  
  //print_r($interns);
  if($_SESSION['user']['category'] == 'staff'){
    $query = "SELECT * FROM appraisal_replies WHERE staff_id = '".$_SESSION['user']['id']."'";
    $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $appraisal[] = $row;
      }
  }
  $query = "SELECT * FROM leaves WHERE staff_id = '".$_SESSION['user']['id']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $leaves[] = $row;
      }
  }
  $query = "SELECT * FROM requesteditem WHERE staff_id = '".$_SESSION['user']['id']."' AND status = 'pending'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $requisition[] = $row;
      }
  }
  $query = "SELECT * FROM id_card WHERE staff_id = '".$_SESSION['user']['id']."'";   
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $id_card[] = $row;
      }
  }

  

    $query = "SELECT * FROM open_information_portal WHERE admin_id = '".$_SESSION['user']['admin_id']."' AND open_for = '".$_SESSION['user']['department']."'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) == 1){
      $row = mysqli_fetch_assoc($result);
       $data[] = $row;
       //print_r($data);
        if(strtotime(date('Y-m-d')) >= strtotime($data[0]['opening_date']) && strtotime($data[0]['closing_date']) > strtotime(date('Y-m-d'))){
          $show = 'OPENED';
       }else {
          $show = 'CLOSED';
          //$_SESSION['msg'] = 'You don\'t have permission to edit this document, wait still permission is granted by the admin';
       }
    }
}else if($_SESSION['user']['category'] == 'admin'){
    
  $query = "SELECT * FROM id_card WHERE admin_id = '".$_SESSION['user']['id']."'";   
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $id_card[] = $row;
      }
  }
    
  $query = "SELECT * FROM users WHERE admin_id = '".$_SESSION['user']['id']."' AND id_card_permission ='1' LIMIT 3";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $id_card_processor[] = $row;
      }
  }
  $query = "SELECT * FROM requesteditem WHERE admin_id = '".$_SESSION['user']['id']."' AND status = 'pending'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $requisition[] = $row;
      }
  }
  $query = "SELECT * FROM users WHERE admin_id = '".$_SESSION['user']['id']."' AND upload_appraisal ='1' LIMIT 3";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $appraisal_uploader[] = $row;
      }
  }
  $query = "SELECT * FROM leaves WHERE admin_id = '".$_SESSION['user']['id']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $leaves[] = $row;
      }
  }
  $query = "SELECT * FROM open_information_portal WHERE admin_id = '".$_SESSION['user']['id']."'";
  $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) == 1){
      $row = mysqli_fetch_assoc($result);
       $data[] = $row;
        if(strtotime(date('Y-m-d')) >= strtotime($data[0]['opening_date']) && strtotime($data[0]['closing_date']) > strtotime(date('Y-m-d'))){
          $show = 'OPENED';
       }else {
          $show = 'CLOSED';
       }
    }
  $query = "SELECT * FROM company WHERE admin_id = '".$_SESSION['user']['id']."'";
  $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
      $row = mysqli_fetch_assoc($result);
       $company[] = $row;
        
    }
}
  if($_SESSION['user']['category'] == 'admin'){
    $query = "SELECT * FROM requesteditem WHERE admin_id = '".$_SESSION['user']['id']."' ORDER BY id DESC LIMIT 3";
  }else {
    $query = "SELECT * FROM requesteditem WHERE staff_id = '".$_SESSION['user']['id']."' ORDER BY id DESC LIMIT 3";
  }
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $data_item[] = $row;
      }
  }
 if($_SESSION['user']['category'] == 'staff') $admin_id = $_SESSION['user']['admin_id'];
 else if ($_SESSION['user']['category'] == 'admin') $admin_id = $_SESSION['user']['id'];
  $query = "SELECT kss.information,users.name,users.employee_ID FROM kss INNER JOIN users ON kss.staff_id = users.id WHERE kss.companyId = '".$_SESSION['user']['companyId']."' ORDER BY kss.id DESC LIMIT 2";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $kss[] = $row;
      }
  }
  $items = [];
  if($_SESSION['user']['category'] == 'staff') $admin_id = $_SESSION['user']['admin_id'];
  else if ($_SESSION['user']['category'] == 'admin') $admin_id = $_SESSION['user']['id'];
  $query = "SELECT * FROM items WHERE companyId = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result) > 0){
      //$row = mysqli_fetch_assoc($result);
      while($row = mysqli_fetch_assoc($result)) {
       $items[] = $row;
      }
    }
    //print_r($items);
$leave_request = [];
  if($_SESSION['user']['category'] == 'staff'){
       $query = "SELECT users.name,users.employee_ID,users.department,leaves.start_date, leaves.end_date, leaves.justification, leaves.id as leave_id, leaves.leave_type, leaves.date_created, leaves.stage,leaves.processed FROM leaves INNER JOIN users ON users.id = leaves.staff_id AND users.id = leaves.staff_id WHERE leaves.staff_id = '".$_SESSION['user']['id']."' AND year = '".date('Y')."' ORDER BY leaves.id DESC LIMIT 5";
  }else if($_SESSION['user']['category'] == 'admin'){
      $query = "SELECT users.name,users.employee_ID,users.department,leaves.start_date, leaves.end_date, leaves.justification, leaves.id as leave_id, leaves.leave_type, leaves.date_created,leaves.stage, leaves.processed FROM leaves INNER JOIN users ON users.admin_id = leaves.admin_id AND users.id = leaves.staff_id WHERE leaves.admin_id = '".$_SESSION['user']['id']."' AND year = '".date('Y')."' ORDER BY leaves.id DESC LIMIT 5";
  }      
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $leave_request[] = $row;
         $t =  (int)(trim(explode('-',$row['date_created'])[1]));
         $all_months[] = $month[$t-1];
         $year[] = explode('-',$row['date_created'])[0];
         $day[] = explode('-',$row['date_created'])[2];
      }
   } 

  $query = "SELECT * FROM users WHERE companyId = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $staff[] = $row;
      }
  }

  $query = "SELECT * FROM leave_type WHERE grade = '".$_SESSION['user']['grade']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $leave_type[] = $row;
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
          <!-- top tiles -->
          <div class="row tile_count">
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Staff</span>
              <div class="count"><?=count($staff)?></div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-book"></i> Total Courses</span>
              <div class="count"><?=count($courses)?></div>
            </div>
            <!--<div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-book"></i> Talent Management</span>
              <?php if($_SESSION['user']['category'] == 'staff') { ?>
              <div class="count green"><?=$_SESSION['user']['add_talent_management'] == '1' ? 'TRUE' : 'FALSE'?></div>
              <?php }else { ?>
                 <div class="count green">TRUE</div>
              <?php } ?> 
            </div>-->
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Pending Requisition</span>
              <div class="count"><?=count($requisition)?></div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Employee Portal</span>
              <div class="count green"><h1><?=$show?></h1></div>
            </div>
            <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
              <span class="count_top"><i class="fa fa-user"></i> Inventory</span>
              <div class="count"><?=count($items)?></div>
            </div>
          </div>
          <?php if($_SESSION['user']['category'] == 'admin' ||  $_SESSION['user']['leave_processing_permission'] == '1') {?>
             
            <div class="row tile_count">
          <div class="tile_stats_count">
              <span class="count_bottom"><i class="green"> <i class="fa fa-sort-asc"></i><?=count($staff)-count($interns) ?></i> Full Staff</span>
          <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i><?=count($interns) ?> </i>Interns</span>
          <!--<span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>0 </i> Exited Interns</span>-->
          
          <span class="count_bottom"><i class="green"> <i class="fa fa-sort-asc"></i><?=count($created_at) ?></i> New Staff</span>
          <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i><?=count($exited) ?> </i> Exited Staff</span>
          <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i><?=count($suspend) ?>  </i> Suspension/Termination</span>

          

          </div></div>
          <?php  } ?>
        
          
          <!-- /top tiles -->
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
                    <h2>Leave Request in <?=date('Y')?></h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                      <?php if(count($leave_request) > 0){ ?>
                         <div class="table-responsive">

                          <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N </th>
                            <th class="column-title">Name </th>
                            <th class="column-title">Employee ID </th>
                            <th class="column-title">Leave Type </th>
                            <th class="column-title">Applied Date </th>
                            <th class="column-title">Stage </th>
                            <th class="column-title">More </th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($leave_request); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class="" style='text-transform:capitalize'><?=$leave_request[$h]['name']?></td>
                            <td class=" "><?=$leave_request[$h]['employee_ID']?></td>
                           <!--  <td class=" " style='text-transform:capitalize'><?=$leave_request[$h]['department']?></td> -->
                            <td class=" "><?=$leave_request[$h]['leave_type']?></td>
                            <td class=" "><?=$day[$h]?> <?=$all_months[$h]?> <?=$year[$h]?></td>
                            <td class=" " style="text-transform:capitalize"><?=$leave_request[$h]['stage']?></td>
                            <th class="column-title"><a href="see_leave_request.php/?leave_id=<?=base64_encode($leave_request[$h]['leave_id'])?>" class="btn btn-sm btn-success">Details</a> </th>
                          </tr>
                           <?php }?>
                        </tbody>
                      </table>
                        </div>
                      <?php }else { ?>
                         <h3>No leave Request yet.</h3>
                      <?php } ?>
                    <!--canvas id="myChart" width="400" height="100"></canvas-->
                  </div>
                </div>
            </div>
             <div class="col-md-6 col-sm-12 col-xs-12">
                  <div class="x_panel">
                  <div class="x_title">
                    <h2>KSS shared by (<span style="font-size: 13px;"><?=isset($kss[0]['name']) ? $kss[0]['name'] : ''?></span>)</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php if(count($kss) > 0) {?>
                    <p style="text-align: justify;"><?=$kss[0]['information']?></p>
                    <?php } else { ?>
                      <p style="text-align: justify;">No Information shared</p>
                    <?php } ?> 
                  </div>
                </div>
              </div>   
        
          
          <?php if($_SESSION['user']['category'] == 'staff') {?>
        
             <div class="col-md-6 col-sm-12 col-xs-12">
               <!--  <div class="x_panel" style="">
                   <div class="x_title">
                    <h2>Special Access or Permission<small></small></h2>
                  
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table class="table table-striped">
                      <tbody>
                        <tr>
                             <td scope="row">ID Processing Permission </td>
                             <td><?=$_SESSION['user']['id_card_permission'] == '1' ? 'Access Granted' : 'Restricted'?> </td>
                        </tr>
                        <tr>
                             <td scope="row">Appraisal Upload Permission </td>
                             <td><?=$_SESSION['user']['upload_appraisal'] == '1' ? 'Access Granted' : 'Restricted'?> </td>
                        </tr>
                        <tr>
                             <td scope="row">Talent Management Permission </td>
                             <td><?=$_SESSION['user']['add_talent_management'] == '1' ? 'Access Granted' : 'Restricted'?> </td>
                        </tr>
                        <tr>
                             <td scope="row">Leave Management Permission </td>
                             <td><?=$_SESSION['user']['payroll_permission'] == '1' ? 'Access Granted' : 'Restricted'?> </td>
                        </tr>
                         <tr>
                             <td scope="row">Employee Management Permission </td>
                             <td><?=$_SESSION['user']['add_employee_management'] == '1' ? 'Access Granted' : 'Restricted'?> </td>
                        </tr>
                        <tr>
                             <td scope="row">Permission Management </td>
                             <td><?=$_SESSION['user']['add_permission_management'] == '1' ? 'Access Granted' : 'Restricted'?> </td>
                        </tr>
                        <tr>
                             <td scope="row">Inventory Management Permission</td>
                             <td><?=$_SESSION['user']['add_item_permission'] == '1' ? 'Access Granted' : 'Restricted'?> </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>-->
            </div>
             <div class="col-md-6 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Leave Application in <?=date('Y')?></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                   <?php if(count($leave_type) > 0){ ?>
                   <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N </th>
                            <th class="column-title">Leave Type </th>
                            <th class="column-title">Number of Days </th>
                            <th class="column-title">Days Left </th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($leave_type); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$leave_type[$h]['leave_kind']?></td>
                             <td class=""><?=$leave_type[$h]['days']?></td>
                            <td class=" ">
                              <?php 
                                $sumleave = 0;
                                $query = "SELECT * FROM leaves WHERE (staff_id = '".$_SESSION['user']['id']."' AND leave_type = '".$leave_type[$h]['leave_kind']."' AND year = '".date('Y')."' AND status = 'approved')";
                                $result = mysqli_query($conn, $query);
                                if(mysqli_num_rows($result)> 0){
                                    while($row = mysqli_fetch_assoc($result)) {
                                      $sumleave = $sumleave + $row['number_of_days'];
                                    }
                                }
                                
                                //echo $sumleave
                                echo $leave_type[$h]['days'] - $sumleave;
                              ?>
                            
                            
                            </td>
                          </tr>
                          <tr>
                              <?php 
                                //   if($h == 1)
                                //   {
                                //       echo '
                                //       <td class="">3</td>
                                //       <td class="">Maternity</td>
                                //       <td class="">5</td>
                                //       <td class="">14</td>';
                                //   }
                              ?>
                          </tr>
                           <?php }?>
                        </tbody>
                      </table>
                    </div>
                    <?php  } ?>  
                    <?php if(count($leave_type) == 0){ ?>
                       No Leave Parameter Set
                    <?php  } ?>  
                </div>
              </div>
            </div>
          </div>  
         <!--  <div class="row">


            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="x_panel" style="height: 250px;">
                  <div class="x_title">
                    <h2>Appraisal Flow<small></small></h2>
                  
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table class="table table-striped">
                      <tbody>
                        <?php if($_SESSION['user']['appraisal_flow']) {?>
                          <?php $appraisal = explode(";",$_SESSION['user']['appraisal_flow'])?>
                          <?php for($r = 0; $r < count($appraisal); $r++) {?>
                        <tr>
                          <?php if($appraisal[$r] != '') {?>
                            <?php $app_details = explode(":",$appraisal[$r]) ?>
                            <?php if(count($app_details) > 0) {?>
                             <td scope="row"><?=$app_details[0]?></td>
                             <td><?=$app_details[1]?> </td>
                        </tr>
                        <?php } } } }?>
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="x_panel" style="height: 250px;">
                  <div class="x_title">
                    <h2>Requisition Flow<small></small></h2>
                  
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table class="table table-striped">
                      <tbody>
                        <?php if($_SESSION['user']['requisition_flow']) {?>
                          <?php $requisition = explode(";",$_SESSION['user']['requisition_flow'])?>
                          <?php for($r = 0; $r < count($requisition); $r++) {?>
                        <tr>
                          <?php if($requisition[$r] != '') {?>
                            <?php $req_details = explode(":",$requisition[$r]) ?>
                            <?php if(count($req_details) > 0) {?>
                             <td scope="row"><?=$req_details[0]?></td>
                             <td><?=$req_details[1]?> </td>
                        </tr>
                        <?php } } } }?>
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>


            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="x_panel" style="height: 250px;">
                  <div class="x_title">
                    <h2>Leave Flow<small></small></h2>
                  
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table class="table table-striped">
                      <tbody>
                        <?php if($_SESSION['user']['leave_flow']) {?>
                          <?php $leave = explode(";",$_SESSION['user']['leave_flow'])?>
                          <?php for($r = 0; $r < count($leave); $r++) {?>
                        <tr>
                          <?php if($leave[$r] != '') {?>
                            <?php $leave_details = explode(":",$leave[$r]) ?>
                            <?php if(count($leave_details) > 0) {?>
                             <td scope="row"><?=$leave_details[0]?></td>
                             <td><?=$leave_details[1]?> </td>
                        </tr>
                        <?php } } } }?>
                      </tbody>
                    </table>
                  </div>
                </div>
            </div>

          </div> -->
          <?php  } ?>
          <?php if($_SESSION['user']['category'] == 'admin' || $_SESSION['user']['add_item_permission'] == '1') {?>
             
          
          <?php  } ?>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>REQUESTED ITEMS</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                   <?php if(count($data_item) > 0){ ?>
                   <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N </th>
                            <th class="column-title">Item Name </th>
                            <th class="column-title">Quantity </th>
                            <th class="column-title">Cost </th>
                            <th class="column-title">Status </th>
                            <th class="column-title">More </th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($data_item); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class="">
                             <!--  <?=$data_item[$h]['item']?> -->
                                 <?php 
                              $query = "SELECT * FROM items WHERE id = '".$data_item[$h]['item']."'";
                              $result = mysqli_query($conn, $query);
                              if(mysqli_num_rows($result)> 0){
                                  while($row = mysqli_fetch_assoc($result)) {
                                    echo $row['item_name'];
                                  }
                              }else {
                                echo $data_item[$h]['item'];
                              }
                              ?>
                              </td>
                            <td class=" "><?=$data_item[$h]['quantity']?></td>
                            <td class=" "><?=$data_item[$h]['cost']?></td>
                            <td class=" "><?=$data_item[$h]['status']?></td>
                            <th class="column-title"><a href="process_request_details.php/?item_id=<?=base64_encode($data_item[$h]['id'])?>" class="btn btn-sm btn-success">Details</a> </th>
                          </tr>
                           <?php }?>
                        </tbody>
                      </table>
                    </div>
                    <?php  } ?>  
                    <?php if(count($data_item) == 0){ ?>
                       No Item request
                    <?php  } ?>  
                </div>
              </div>
            </div>
            
            </div>

            <div class="row">
           
            
            </div>
          </div>
        </div>
<?php include "footer.php"?>
<script type="text/javascript" src="js/appraisal.js"></script>
<script>
  data = [];
  $(function(e){
    $.ajax({
      method: "get",
      url: "get_all_leave_request.php",
      success: function(resp){
        //alert(resp);
        data = resp.split(";");
        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
    data: {
        labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September","October","November", "December"],
        datasets: [{
            label: '# of Request',
            data: data,
            backgroundColor: [
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)'
            ],
            borderColor: [
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)',
                'rgba(115, 135, 156, 0.7)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                },gridLines: {
                  display: false ,
                  color: "#FFFFFF"
                },
            }],
            xAxes: [{
                ticks: {
                    beginAtZero:true
                },gridLines: {
                  display: false ,
                  color: "#f8fafc"
                },
            }]
        }
    }
});
      }
    })
  });
console.log(data);  
</script>
        

<?php 
include 'connection.php';
session_start();
$leaves = [];
 $month = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUNE', 'JULY', 'AUG','SEPT', 'OCT', 'NOV', 'DEC'];
 $year = [];
 $all_months = [];
 $day = [];
 $filled_appraisal = [];
 //echo $_SESSION['user']['id'];
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
  if($_SESSION['user']['category'] == 'staff'){
      //echo 'aaaaa';
    $query = "SELECT users.name,users.employee_ID,users.department,leaves.start_date, leaves.end_date, leaves.justification,leaves.status, leaves.id as leave_id, leaves.leave_type, leaves.date_created, leaves.stage,leaves.processed FROM leaves INNER JOIN users ON (users.id = leaves.staff_id AND users.id = leaves.staff_id) WHERE leaves.staff_id = '".$_SESSION['user']['id']."' ORDER BY leaves.id DESC";
  }
  else if($_SESSION['user']['category'] == 'admin'){
    $query = "SELECT users.name,users.employee_ID,users.department,leaves.start_date, leaves.end_date, leaves.justification,leaves.status, leaves.id as leave_id, leaves.leave_type, leaves.date_created,leaves.stage, leaves.processed FROM leaves INNER JOIN users ON users.admin_id = leaves.admin_id AND users.id = leaves.staff_id WHERE leaves.admin_id = '".$_SESSION['user']['id']."' ORDER BY leaves.id DESC";
  }
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $leaves[] = $row;
         $t =  (int)(trim(explode('-',$row['date_created'])[1]));
         $all_months[] = $month[$t-1];
         $year[] = explode('-',$row['date_created'])[0];
         $day[] = explode('-',$row['date_created'])[2];
      }
  }
  //print_r($leaves);
?>
<?php include "header.php"?>
 <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>View Request</h3>
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
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_content">
                    <div class="table-responsive">
                      <?php if(count($leaves) > 0){?>    
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N </th>
                            <th class="column-title">Name </th>
                            <th class="column-title">Employee ID </th>
                            <th class="column-title">Department </th>
                            <th class="column-title">Leave Type </th>
                            <th class="column-title">Applied Date </th>
                            <th class="column-title">stage </th>
                            <th class="column-title">Status </th>
                            <th class="column-title">More </th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($leaves); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$leaves[$h]['name']?></td>
                            <td class=" "><?=$leaves[$h]['employee_ID']?></td>
                            <td class=" "><?=$leaves[$h]['department']?></td>
                            <td class=" "><?=$leaves[$h]['leave_type']?></td>
                            <td class=" "><?=$day[$h]?> <?=$all_months[$h]?> <?=$year[$h]?></td>
                            <td class=" " style="text-transform:capitalize"><?=$leaves[$h]['stage']?></td>
                            <td class=" " style="text-transform:capitalize"><?=$leaves[$h]['status']?></td>
                            <th class="column-title"><a href="see_leave_request.php/?leave_id=<?=base64_encode($leaves[$h]['leave_id'])?>" class="btn btn-sm btn-success">Details</a> </th>
                          </tr>
                           <?php }?>
                        </tbody>
                      </table>
                      <?php }else {?>
                        <h4>No Request yet</h4>
                      <?php }?>
                    </div>
                
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
<?php include "footer.php"?>
        

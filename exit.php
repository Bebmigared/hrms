<?php 
include 'connection.php';
session_start();
$exit = [];
if(!isset($_SESSION['user']['id'])) header("Location: login.php");
if(!isset($_SESSION['user']['category'])) header("Location: dashboard.php");
$query = "SELECT * from users WHERE staff_exit = 'yes' ORDER BY id DESC";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $exit[] = $row;
      }
  }
   //print_r($data);
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
            <h3>Exit</h3>
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
            <?php if(count($exit) > 0) {?>      
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Staff Exit</h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                  
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
                    <?php if(count($exit) == 0 ){ ?>
                       No Staff Exit 
                    <?php } ?>
                  </div>
                </div>
            </div> 
            <?php } ?> 
            <?php if(count($exit) == 0) { ?>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Staff Exit</h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                    <?php if(count($exit) == 0 ){ ?>
                       No Staff Exit 
                    <?php } ?>
                  </div>
                </div>
            </div> 
            <?php } ?>    
        </div>
</div>
</div>
<?php include "footer.php"?>
        

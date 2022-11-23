<?php 
include 'connection.php';
session_start();
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
 $managers = [];
 $query = "SELECT * from users";
 $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result) > 0){
           while($row = mysqli_fetch_assoc($result)) {
                $managers[] = $row;
           }
        }
 print_r($managers);        
?>
<?php include "header.php"?>
 <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Managers</h3>
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
        

<?php 
include 'connection.php';
session_start();
$msg = '';
$id_request = [];
$kss = [];
if(!isset($_SESSION['user']['id'])) header("Location: login.php");
if($_SESSION['user']['category'] == 'staff' && $_SESSION['user']['id-card_permission'] == '1') $admin_id = $_SESSION['user']['admin_id'];
else if($_SESSION['user']['category'] == 'admin') $admin_id = $_SESSION['user']['id'];
else header("Location: dashboard.php");
$query = "SELECT users.name, users.employee_ID,id_card.signature,id_card.status,id_card.date_created,id_card.staff_id,id_card.IID as request_id FROM id_card INNER JOIN users ON users.id = id_card.staff_id WHERE id_card.admin_id = '".$_SESSION['user']['id']."'";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) > 0){
   while($row = mysqli_fetch_assoc($result)) {
        $id_request[] = $row;
   }
}
 if($_SESSION['user']['category'] == 'staff') $admin_id = $_SESSION['user']['admin_id'];
 else if ($_SESSION['user']['category'] == 'admin') $admin_id = $_SESSION['user']['id'];
  $query = "SELECT kss.information,users.name,users.employee_ID FROM kss INNER JOIN users ON kss.staff_id = users.id WHERE kss.admin_id = '".$admin_id."'ORDER BY kss.id DESC LIMIT 1";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $kss[] = $row;
      }
  }
?>
<?php include "header.php"?>
<div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>ID Card Requests</h3>
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
              <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>View Status</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php if(count($id_request) > 0 ){ ?>
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title text-center">S/N</th>
                            <th class="column-title text-center">Name</th>
                            <th class="column-title text-center">Employee ID </th>
                            <th class="column-title text-center">Status</th>
                            <th class="column-title text-center">More</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($id_request); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center text-center">
                              <?=$h + 1?>
                            </td>
                            <td class="text-center" style="text-transform:capitalize"><?=$id_request[$h]['name']?></td>
                            <td class="text-center"  ><?=$id_request[$h]['employee_ID']?></td>
                            <td class="text-center" style="text-transform:capitalize"><?=$id_request[$h]['status']?></td>
                            <td class="text-center">
                              <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="see_more_information_on_idrequest.php/?staff_id=<?=base64_encode($id_request[$h]['staff_id'])?>&request_id=<?=base64_encode($id_request[$h]['request_id'])?>" class="btn btn-warning btn-sm">Details</a>
                              </div>
                            </td>
                           <?php }?>
                        </tbody>
                      </table>
                    </div>
                    <?php } else { ?>
                       You have pending request
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-12 col-xs-12">
                  <div class="x_panel">
                  <div class="x_title">
                    <h2>KSS shared by (<span style="font-size: 13px;"><?=isset($kss[0]['name']) ? $kss[0]['name'] :''?></span>)</h2>
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
              <div class="col-md-4 col-sm-12 col-xs-12">
                
              </div>
            </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
<?php include "footer.php"?>
<script type="text/javascript" src="js/appraisal.js"></script>

        

<?php 
include 'connection.php';
session_start();
$kss = [];
$admin_id;
$users = [];
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
 if($_SESSION['user']['category'] == 'staff') $admin_id = $_SESSION['user']['admin_id'];
 else if ($_SESSION['user']['category'] == 'admin') $admin_id = $_SESSION['user']['id'];
  $query = "SELECT kss.information,users.name,users.employee_ID FROM kss INNER JOIN users ON kss.staff_id = users.id WHERE kss.admin_id = '".$admin_id."'ORDER BY kss.id DESC LIMIT 1";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $kss[] = $row;
      }
  }
  $query = "SELECT * FROM staff_audit_replies INNER JOIN users ON users.id = staff_audit_replies.staff_id INNER JOIN staff_audit ON staff_audit.admin_id = staff_audit_replies.admin_id WHERE staff_audit_replies.admin_id = '".$_SESSION['user']['id']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
      }
  }
  if(isset($_POST['submit'])){
     $year = $_POST['year'];
     $month = $_POST['month'];
     $_SESSION['year'] = $year;
     $_SESSION['month'] = $month;
     if($year == '' || $month == '') {
      $_SESSION['msg'] = 'Please select the audit month and year';
     }else{
       $users = [];
       $query = "SELECT * FROM staff_audit_replies INNER JOIN users ON users.id = staff_audit_replies.staff_id INNER JOIN staff_audit ON staff_audit.admin_id = staff_audit_replies.admin_id WHERE staff_audit_replies.admin_id = '".$_SESSION['user']['id']."' AND staff_audit.month = '".$month."' AND staff_audit.year = '".$year."'";
       $result = mysqli_query($conn, $query);
      if(mysqli_num_rows($result)> 0){
          while($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
          }
      }
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
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>AUDIT</h3>
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
               <?php if(isset($_SESSION['msg']) && $_SESSION['user']['category'] == 'staff') {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                  <?php } ?>   
              <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Staffs Information</h2>
                    <?php if(count($users) > 0) { ?>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="btn btn-success btn-sm" style="color: #fff;" data-toggle="modal" data-target="#exampleModal">Filter Page</a>
                      </li>
                      <li><a href ="print_audit" class="btn btn-success btn-sm" style="color: #fff;">Print</a>
                      </li>
                      <li>
                      <form action="process_file.php" method="post">
                            <input type="text" name="which" value = "audit" style="display: none;">
                            <input type="text" name="month" value = "" style="display: none;">
                            <input type="text" name="year" value = "" style="display: none;">
                            <button type="submit" id="btnExport"
                                name='export' value="Export to Excel"
                                class="btn btn-info">Export to Excel</button>
                        </form>
                      </li>
                    </ul>
                    <?php } ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php if(count($users) > 0) { ?>
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N</th>
                            <th class="column-title">Name</th>
                            <th class="column-title">Employee ID </th>
                            <th class="column-title">Department (Audit Period)</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($users); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$users[$h]['name']?></td>
                            <td class=" "><?=$users[$h]['employee_ID']?></td>
                            <td class=" "><?=$users[$h]['department']?> (<?=$users[$h]['month']?> <?=$users[$h]['year']?>)</td>
                           <?php }?>
                        </tbody>
                      </table>
                    </div>
                    <?php }else { ?>
                      No Record
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

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title" id="exampleModalLabel">Filter Data</h2>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="audit.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">

           <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Audit Month <span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <select name="month" class="form-control">
                       <option value=""></option>
                       <option value="JAN">JAN</option>
                       <option value="FEB">FEB</option>
                       <option value="MAR">MAR</option>
                       <option value="APR">APR</option>
                       <option value="MAY">MAY</option>
                       <option value="JUN">JUN</option>
                       <option value="JUL">JUL</option>
                       <option value="AUG">AUG</option>
                       <option value="SEP">SEPT</option>
                       <option value="OCT">OCT</option>
                       <option value="NOV">NOV</option>
                       <option value="DEC">DEC</option>
                     </select>
              </div>
            </div>
            <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Audit year <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                     <select name="year" class="form-control">
                       <option value=""></option>
                       <option value="2019">2019</option>
                       <option value="2020">2020</option>
                       <option value="2021">2021</option>
                       <option value="2022">2022</option>
                       <option value="2023">2023</option>
                       <option value="2024">2024</option>

                     </select>
                    </div>
            </div>
            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                <button type="submit" name="submit" class="btn btn-success">Submit</button>
              </div>
            </div>      
          </form>
        </div>
      </div>
    </div>
  </div>
<?php include "footer.php"?>
<script type="text/javascript" src="js/appraisal.js"></script>
        

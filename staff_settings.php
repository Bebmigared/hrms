<?php 
 include "connection.php";
 session_start();
 $data = [];
 $dept = [];
 $branch = [];
 $appraisal_level = [];
 $appraisal_flow_details = [];
 $requisition_approval_details = [];
 $leave_approval_details = [];
 $appraisal_approval_details = [];
 $requisition_flow_details = [];
 $leave_flow_details = [];
 $cash_flow_details = [];
 $cash_approval_details = [];
 $leave_level = [];
 $requisition_level = [];
 $all_approval = [];
 $comp_appraisal_level = [];
 $comp_requisition_level = [];
 $comp_cash_level = [];
 $comp_leave_level = []; 
 if(!isset($_SESSION['user'])) header("Location: login");
  $query = "SELECT company.appraisal_flow as c_appraisal_flow, company.cash_flow as c_cash_flow, company.requisition_flow as c_requisition_flow, company.leave_flow as c_leave_flow, users.department,users.phone_number, company.branch as c_branch,users.name as user_name, company.address as c_address,users.department as user_department, company.department as c_dept, users.appraisal_flow as user_appraisal_flow, users.leave_flow as user_leave_flow, users.requisition_flow as user_requisition_flow, users.cash_flow as user_cash_flow FROM users INNER JOIN company ON company.admin_id = '".$_SESSION['user']['admin_id']."' WHERE users.id = '".$_SESSION['user']['id']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
        $dept = explode(";",$row['c_dept']);
        $branch = explode(";", $row['c_branch']);
        $appraisal_level = explode(";", $row['c_appraisal_flow']);
        $leave_level = explode(";", $row['c_leave_flow']);
        $cash_level = explode(";", $row['c_cash_flow']);
        $requisition_level = explode(";", $row['c_requisition_flow']);
        if($row['user_appraisal_flow'] != "") $comp_appraisal_level = explode(";", $row['user_appraisal_flow']);
        if($row['user_requisition_flow'] != "") $comp_requisition_level = explode(";", $row['user_requisition_flow']);
        if($row['user_leave_flow'] != "") $comp_leave_level = explode(";", $row['user_leave_flow']);
        if($row['user_cash_flow'] != "") $comp_cash_level = explode(";", $row['user_cash_flow']);
      }
  }
 //print_r($leave_level);
 $appraisal_approval_details = $comp_appraisal_level;
 $leave_approval_details = $comp_leave_level;
 $requisition_approval_details = $comp_requisition_level;
 $cash_approval_details = $comp_cash_level;
 //print_r($appraisal_approval_details);
 for($e = 0; $e < count($comp_appraisal_level); $e++){
  if(count($appraisal_approval_details) > 0){
    $val = explode(":", $appraisal_approval_details[$e])[0];
    if(!in_array(strtolower($val), $all_approval)){ $all_approval[] = strtolower($val);}
    if($val == $appraisal_level[$e]){
      $t = explode(":", $appraisal_approval_details[$e]);
      if(count($t) > 1) $appraisal_flow_details[]  = explode(":", $appraisal_approval_details[$e])[1];
      else $appraisal_flow_details[] = '';
    }
  }
 }
 
for($e = 0; $e < count($comp_leave_level); $e++){
  if(count($leave_approval_details) > 0){
      $val = explode(":", $leave_approval_details[$e])[0];
      if(!in_array(strtolower($val), $all_approval)){ $all_approval[] = strtolower($val);}
      if($val == $leave_level[$e]){
        $t = explode(":", $leave_approval_details[$e]);
        if(count($t) > 1) $leave_flow_details[]  = explode(":", $leave_approval_details[$e])[1];
        else $leave_flow_details[] = '';
      }
  }
 }
//print_r($requisition_approval_details);
//print_r($_SESSION['user']);
 for($e = 0; $e < count($comp_requisition_level); $e++){
  if(count($requisition_approval_details) > 0){
      $val = explode(":", $requisition_approval_details[$e])[0];
      if(!in_array(strtolower($val), $all_approval)){ $all_approval[] = strtolower($val);}
      if($val == $requisition_level[$e]){
        $t = explode(":", $requisition_approval_details[$e]);
        if(count($t) > 1) $requisition_flow_details[]  = explode(":", $requisition_approval_details[$e])[1];
        else $requisition_flow_details[] = "";
      }
  }
    
 }
 for($e = 0; $e < count($comp_cash_level); $e++){
  if(count($cash_approval_details) > 0){
      $val = explode(":", $cash_approval_details[$e])[0];
      if(!in_array(strtolower($val), $all_approval)){ $all_approval[] = strtolower($val);}
      if($val == $cash_level[$e]){
        $t = explode(":", $cash_approval_details[$e]);
        if(count($t) > 1) $cash_flow_details[]  = explode(":", $cash_approval_details[$e])[1];
        else $cash_flow_details[] = "";
      }
  }
    
 }
 //print_r($all_approval);
  //print_r($appraisal_flow_details);
 /*for($e = 0; $e < count($leave_level); $e++){
  $val = explode(":", $leave_flow_details)[0];
  if($val == $leave_level[$e]){
    $leave_flow_details  = $val;
  }
 }*/
//print_r($data);

  $query = "SELECT grade_name FROM grade WHERE id = '".$_SESSION['user']['grade']."'";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result)> 0){
  while($row = mysqli_fetch_assoc($result)) {
    $levels[] = $row;
  }
}

//print_r($levels);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="icon" href="asset/img/hr.png" type="image/ico" />

    <title>HR CORE </title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
	
    <!-- bootstrap-progressbar -->
    <link href="vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css">



    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
  </head>

  <body class="nav-md">
    <div class="container body" style="background-color: #f8fafc;overflow-x:hidden;">
      <div class="">
        <!-- top navigation -->
        <?php include 'top.php' ?>
        <!-- /top navigation -->
        <!-- page content -->
        <div class="right_col" role="main" style="width:100%;margin-left:0px;">
            <div class="">
                <div class="page-title">
                  <div class="title_left">
                    <h3>Staff Settings</h3>
                  </div>
    
                  <div class="title_right">
                    <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                      <div class="input-group">
                        <!--input type="text" class="form-control" placeholder="Search for...">
                        <span class="input-group-btn">
                          <button class="btn btn-default" type="button">Go!</button>
                        </span-->
                      </div>
                    </div>
                  </div>
                </div>
                <div class="clearfix"></div> 
                <div class="row" style="width:60%;margin-left:auto;margin-right:auto;">
                   <?php if(isset($_SESSION['msg'])) {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                  <?php } ?>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                      <div class="x_title">
                        <h2>Staff Details</h2>
                        <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                        <br />
                        <form id="" action="process_staff_data.php" method="POST" enctype="multipart/form-data" data-parsley-validate class="form-horizontal form-label-left">
                          <div class="form-group">
                             <div style="text-align: center;margin-bottom: 10px;" id = "uploadimg"><img style="width: 100px;height: 100px;" class="uploadimg" src="images/<?=$_SESSION['user']['profile_image']?>" alt=""></div>
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_email">Administrator Email <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" id="admin_email" value="<?=isset($data[0]['company_name']) ? $data[0]['company_name'] : ''?>" name = "admin_email" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" id="name" value="<?=isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : ''?>" name = "name" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Phone Number <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" id="phone_number" value="<?=isset($_SESSION['user']['phone_number']) ? $_SESSION['user']['phone_number'] : ''?>" name="phone_number" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Employee ID <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" id="employee_ID" value="<?=isset($_SESSION['user']['employee_ID']) ? $_SESSION['user']['employee_ID'] : ''?>" name = "employee_ID" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>
                           <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">Grade <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                               
                               <input type="text" id="role" value="<?= $levels[0]['grade_name'] ?>" name = "role" class="form-control col-md-7 col-xs-12" disabled>
                               <!--<input type="text" id="role" value="<?=isset($_SESSION['user']['grade']) ? $_SESSION['user']['grade'] : ''?>" name = "role" class="form-control col-md-7 col-xs-12" disabled>-->
                            </div>
                          </div>
                          <?php if(count($data) > 0) {?>
                           <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">Position <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <select name="position" class="form-control" id = "position">
                               <option value="<?=isset($_SESSION['user']['position']) ? $_SESSION['user']['position'] : ''?>"><?=isset($_SESSION['user']['position']) ? $_SESSION['user']['position'] : ''?></option>
                              <?php for($r = 0; $r < count($all_approval); $r++){?>
                                <option value = "<?=$all_approval[$r];?>"> <?=strtoupper($all_approval[$r]);?></option>
                              <?php } ?>
                            </select>
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Department <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="department" class="form-control" id = "department">
                               <option value="<?=isset($_SESSION['user']['department']) ? $_SESSION['user']['department'] : ''?>"><?=isset($_SESSION['user']['department']) ? $_SESSION['user']['department'] : ''?></option>
                              <?php for($r = 0; $r < count($dept); $r++){?>
                                <option value = "<?=$dept[$r];?>"> <?=$dept[$r];?></option>
                              <?php } ?>
                            </select>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Branch <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="branch" class="form-control" id = "branch">
                              <option value = "<?=isset($_SESSION['user']['branch']) ? $_SESSION['user']['branch'] : ''?>"><?=isset($_SESSION['user']['branch']) ? $_SESSION['user']['branch'] : ''?></option>
                              <?php for($r = 0; $r < count($branch); $r++){?>
                                <option value = "<?=$branch[$r];?>"> <?=$branch[$r];?></option>
                              <?php } ?>
                            </select>
                            </div>
                          </div>
                          <?php } ?>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Upload profile Image <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="file" name="image" onchange="readURL(this)" id="loadimg" style="display: none;">
                              <button type="button" id="showfile" class="btn btn-info">Upload Image</button>
                            </div>
                          </div>
                          <?php if(count($data) == 0) {?>
                          <div class="ln_solid"></div>
                          <div class="form-group">
                          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button class="btn btn-primary" type="button">Cancel</button>
                            <button class="btn btn-primary" type="reset">Reset</button>
                            <button type="submit" name = "submit" class="btn btn-success" id = 'basic_data'>Submit</button>
                          </div>
                        </div>
                        <?php } ?>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                <?php if(count($data) > 0) {?>
                  <div class="row" style="width:60%;margin-left:auto;margin-right:auto;">
                   <?php if(isset($_SESSION['msg'])) {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                  <?php } ?>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                      <div class="x_title">
                        <h2>Leave Approvals</h2>
                        <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                        <br />
                        <form id="" data-parsley-validate class="form-horizontal form-label-left">
                          <?php for ($k = 0; $k < count($leave_level); $k++) {?>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?=$leave_level[$k]?> Email <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="email" id="l_approval<?=$k?>" data = "<?=$leave_level[$k]?>" name = "l_approval<?=$k?>" value = "<?=isset($leave_flow_details[$k]) ? $leave_flow_details[$k] : ''?>" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>
                          <?php } ?>
                        </form>
                      </div>
                    </div>
                   </div>
                  </div>
                  <div class="row" style="width:60%;margin-left:auto;margin-right:auto;">
                   <?php if(isset($_SESSION['msg'])) {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                  <?php } ?>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="x_panel">
                        <div class="x_title">
                          <h2>Appraisal Approvals</h2>

                          <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                          <br />
                          <form id="" data-parsley-validate class="form-horizontal form-label-left">
                            <?php for ($k = 0; $k < count($appraisal_level); $k++) {?>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?=$appraisal_level[$k]?> Email <span class="required">*</span>
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="email" data = "<?=$appraisal_level[$k]?>" id="appraisal_approval<?=$k?>" value="<?=isset($appraisal_flow_details[$k]) ? $appraisal_flow_details[$k] : ''?>" name = "appraisal_approval<?=$k?>" class="form-control col-md-7 col-xs-12">
                              </div>
                            </div>
                            <?php } ?>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row" style="width:60%;margin-left:auto;margin-right:auto;">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="x_panel">
                        <div class="x_title">
                          <h2>Requisition Approvals</h2>
                          <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                          <br />
                          <form id="" data-parsley-validate class="form-horizontal form-label-left">
                            <?php for ($k = 0; $k < count($requisition_level); $k++) {?>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?=$requisition_level[$k]?> Email <span class="required">*</span>
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="email" data = "<?=$requisition_level[$k]?>" id="requisition_approval<?=$k?>" value="<?=isset($requisition_flow_details[$k]) ? $requisition_flow_details[$k] : ''?>" name = "requisition_approval<?=$k?>" class="form-control col-md-7 col-xs-12">
                              </div>
                            </div>
                            <?php } ?>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row" style="width:60%;margin-left:auto;margin-right:auto;">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="x_panel">
                        <div class="x_title">
                          <h2>Cash Approvals</h2>
                          <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                          <br />
                          <form id="" data-parsley-validate class="form-horizontal form-label-left">
                            <?php for ($k = 0; $k < count($cash_level); $k++) {?>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?=$cash_level[$k]?> Email <span class="required">*</span>
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="email" data = "<?=$cash_level[$k]?>" id="cash_approval<?=$k?>" value="<?=isset($cash_flow_details[$k]) ? $cash_flow_details[$k] : ''?>" name = "cash_approval<?=$k?>" class="form-control col-md-7 col-xs-12">
                              </div>
                            </div>
                            <?php } ?>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row" style="width:60%;margin-left:auto;margin-right:auto;">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="x_panel">
                        <div class="x_content">
                          <br />
                          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"> 
                            <button class="btn btn-primary" type="button">Cancel</button>
                            <button class="btn btn-primary" type="reset">Reset</button>
                            <button type="button" branch = "<?=$_SESSION['user']['branch']?>" department = "<?=$_SESSION['user']['department']?>" id = 'staff_btn' appraisal_approvals = "<?=isset($data[0]['c_appraisal_flow']) ? $data[0]['c_appraisal_flow'] : '' ?>" requisition_approvals = "<?=isset($data[0]['c_requisition_flow']) ? $data[0]['c_requisition_flow'] : ''?>" cash_approvals = "<?=isset($data[0]['c_cash_flow']) ? $data[0]['c_cash_flow'] : ''?>" leave_approvals = "<?=isset($data[0]['c_leave_flow']) ? $data[0]['c_leave_flow'] : ''?>" class="btn btn-success">Submit</button>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
                <?php } ?>
              </div>
          </div>
        <!-- /page content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="vendors/Flot/jquery.flot.js"></script>
    <script src="vendors/Flot/jquery.flot.pie.js"></script>
    <script src="vendors/Flot/jquery.flot.time.js"></script>
    <script src="vendors/Flot/jquery.flot.stack.js"></script>
    <script src="vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="vendors/DateJS/build/date.js"></script>
    <!-- JQVMap -->
    <script src="vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="vendors/moment/min/moment.min.js"></script>
    <script src="vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="/build/js/custom.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.all.min.js"></script>
    <script type="text/javascript" src="js/staff_settings.js"></script>
  </body>
</html>

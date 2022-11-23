<?php 
 include "connection.php";
 session_start();
  if(!isset($_SESSION['user']['id'])) header("Location: login.php");
 error_reporting(E_ALL);
ini_set('display_errors', 'On');
 if(!isset($_SESSION['user']['id']) || $_SESSION['user']['id'] == '') header("Location:login.php");
 $data = [];
 $query = "SELECT * FROM company WHERE id = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
      }
  }
  
 $role = [];
 $query = "SELECT * FROM roles WHERE admin_id = '".$_SESSION['user']['id']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $role[] = $row;
      }
  }
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
    <div class="container body" style="background-color: #fff;overflow-x:hidden;">
      <div class="">
        <!-- top navigation -->
        <?php include 'top.php' ?>
        <!-- /top navigation -->
        <!-- page content -->
        <div class="right_col" role="main" style="width:100%;margin-left:5px;">
            <div class="">
                <div class="page-title">
                  <div class="title_left">
                    <h3>Admin Settings</h3>
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
                 <?php if(isset($_SESSION['msg'])) {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                  <?php } ?>
                <div class="row" style="">
                  <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-3">
                      <div class="x_panel">
                      <div class="x_title">
                        <h2>Company Details</h2>
                        <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                        <br />
                        <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
    
                          <div class="form-group">
                             <div style="text-align: center;margin-bottom: 10px;" id = "uploadimg"><img style="width: 120px;height: 120px;" class="uploadimg" src="images/logo.png?>" alt=""></div>
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="company-name">Company Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" id="company_name" value="<?=isset($data[0]['company_name']) ? $data[0]['company_name'] : ''?>" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Address <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <textarea required="required" id = "address" value="" class="form-control col-md-7 col-xs-12"><?=isset($data[0]['address']) ? $data[0]['address'] : ''?></textarea>
                            </div>
                          </div>
                          <div class="form-group" style="display: none">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Upload company Image <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="file" name="image" onchange="readURL(this)" id="loadfile" style="display: none;">
                              <button type="button" id="openfile" class="btn btn-info">Upload Image</button>
                            </div>
                          </div>
                        </form>
                      </div>
                      </div>
                      
                     <!--  <div class="x_panel">
                        <div class="x_title">
                          <h2>Branch</h2>
                          <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                          <br />
                          <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                            <ul class="list-group" id = 'listbranch'>
                              <?php if(count($data) > 0) { ?>
                                    <?php $branch = explode(";",$data[0]['branch']) ?>
                                    <?php for ($r = 0; $r < count($branch); $r++){ ?>
                                    <?php if($branch[$r] != "") {?>
                                      <li class='list-group-item'><?=$branch[$r]?></li>
                                    <?php } } }?> 
                            </ul> 
                           <div class="form-group" style="margin-top:20px;">  
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center form_center">
                                <input type="text" id="branch" name="branch" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                           </div>
                            <div class="form-group btn_center" style="margin-top:20px;">
                              <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-5">
                                <button class="btn btn-primary" branch = "<?=isset($data[0]['branch']) ? $data[0]['branch'] : ''?>" id = 'add_branch' type="button">Add Branch</button>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div> -->
                     <!--  <div class="x_panel">
                            <div class="x_title">
                              <h2>Department</h2>
                              <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                              <br />
                              <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">  
                                <ul class="list-group" id = 'listdept'>
                                  <?php if(count($data) > 0) {?>
                                    <?php $dept = explode(";",$data[0]['department']) ?>
                                    <?php for ($r = 0; $r < count($dept); $r++){ ?>
                                      <?php if($dept[$r] != "") {?>
                                      <li class='list-group-item'><?=$dept[$r]?></li>
                                    <?php } } }?> 
                                </ul> 
                               <div class="form-group" style="margin-top:20px;">  
                                <div class="col-md-12 col-sm-12 col-xs-12 text-center form_center">
                                    <input type="text" id="dept" name="" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                               </div>
                                <div class="form-group btn_center" style="margin-top:20px;">
                                  <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-5">
                                    <button class="btn btn-primary" dept = "<?=isset($data[0]['department']) ? $data[0]['department'] : ''?>" id ='add_dept' type="button">Add Department</button>
                                  </div>
                                </div>
                              </form>
                            </div>
                      </div> -->
                     <!--  <div class="x_panel">
                            <div class="x_title">
                              <h2>Role</h2>
                              <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                              <br />
                              <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">  
                                <ul class="list-group" id = 'listrole'>
                                    <?php for ($r = 0; $r < count($role); $r++){ ?>
                                      <?php if($role[$r]["rolename"] != "") {?>
                                      <li class='list-group-item'><?=$role[$r]["rolename"]?></li>
                                    <?php } } ?> 
                                </ul> 
                               <div class="form-group" style="margin-top:20px;">  
                                <div class="col-md-12 col-sm-12 col-xs-12 text-center form_center">
                                    <input type="text" id="role" name="" required="required" class="form-control col-md-7 col-xs-12">
                                </div>
                               </div>
                                <div class="form-group btn_center" style="margin-top:20px;">
                                  <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-5">
                                    <button class="btn btn-primary" dept = "<?=isset($role[0]['rolename']) ? $role[0]['rolename'] : ''?>" id ='add_role' type="button">Add Role</button>
                                  </div>
                                </div>
                              </form>
                            </div>
                      </div> -->
                       <div class="x_panel">
                          <div class="x_title">
                            <h2>Appraisal Approval Levels</h2>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <br />
                            <ul class ="flow app_flow">
                                  <li style=""><a class="active">Staff </a></li>
                                  <?php if(count($data) > 0) {?>
                                      <?php $app = explode(";",$data[0]['appraisal_flow']) ?>
                                      <?php for ($r = 0; $r < count($app); $r++){ ?>
                                        <?php if($app[$r] != '') {?>
                                        <li><a class=''><span style='margin-left:2px;margin-right:7px;'>&#x27F6;</span><?=$app[$r]?></a></li>
                                      <?php } } }?> 
                            </ul>
                                <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                                      <div class="form-group" style="margin-top:20px;">  
                                       <div class="col-md-12 col-sm-12 col-xs-12 text-center form_center">
                                           <input type="text" id="approval" name="approval" required="required" class="form-control col-md-7 col-xs-12">
                                       </div>
                                      </div>
                                       <div class="form-group btn_center" style="margin-top:20px;">
                                         <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-4">
                                          <?php $app = isset($data[0]['appraisal_flow']) ? $data[0]['appraisal_flow'] : '' ?>
                                           <button class="btn btn-success" id='appraisal_aprroval' appraisal_flow = "<?=$app?>"  type="button">Add Approvals</button>
                                           <button class="btn btn-danger" id = 'reset_approval' type="reset">Reset</button>
                                         </div>
                                       </div>
                                     </form>    
                          </div>
                      </div>
                      <div class="x_panel">
                            <div class="x_title">
                              <h2>Leave Management Approval Levels</h2>
                              <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                              <br />
                              <ul class ="flow leave_flow">
                                    <li><a class="active">Staff </a></li>
                                    <?php if(count($data) > 0) {?>
                                    <?php $leave = explode(";",$data[0]['leave_flow']) ?>
                                    <?php for ($r = 0; $r < count($leave); $r++){ ?>
                                      <?php if($leave[$r] != '') {?>
                                      <li><a class=''><span style='margin-left:2px;margin-right:7px;'>&#x27F6;</span><?=$leave[$r]?></a></li>
                                    <?php } } }?> 
                                  </ul>
                                  <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                                        <div class="form-group" style="margin-top:20px;">  
                                         <div class="col-md-12 col-sm-12 col-xs-12 text-center form_center">
                                             <input type="text" id="leave_level" name="last-name" required="required" class="form-control col-md-7 col-xs-12">
                                         </div>
                                        </div>
                                         <div class="form-group btn_center" style="margin-top:20px;">
                                           <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-4">
                                            <?php $leave = isset($data[0]['leave_flow']) ? $data[0]['leave_flow'] : '' ?>
                                             <button class="btn btn-success" leave_flow ="<?=$leave?>" id = "leave_approval" type="button">Add Approvals</button>
                                             <button class="btn btn-danger" id = 'reset_leave_approval' type="reset">Reset</button>
                                           </div>
                                         </div>
                                       </form>    
                            </div>
                      </div>
                      <div class="x_panel">
                            <div class="x_title">
                              <h2>Requisition Approval Levels</h2>
                              <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                              <br />
                              <ul class ="flow req_flow">
                                    <li><a class="active">Staff </a></li>
                                    <?php if(count($data) > 0) {?>
                                    <?php $requisition = explode(";",$data[0]['requisition_flow']) ?>
                                    <?php for ($r = 0; $r < count($requisition); $r++){ ?>
                                      <?php if($requisition[$r] != '') {?>
                                      <li><a class=''><span style='margin-left:2px;margin-right:7px;'>&#x27F6;</span><?=$requisition[$r]?></a></li>
                                    <?php } } }?> 
                                  </ul>
                                  <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                                        <div class="form-group" style="margin-top:20px;">  
                                         <div class="col-md-12 col-sm-12 col-xs-12 text-center form_center">
                                             <input type="text" id="requisition_level" name="requisition_level" required="required" class="form-control col-md-7 col-xs-12">
                                         </div>
                                        </div>
                                         <div class="form-group btn_center" style="margin-top:20px;">
                                           <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-4">
                                            <?php $requisition = isset($data[0]['requisition_flow']) ? $data[0]['requisition_flow'] : '' ?>
                                             <button class="btn btn-success" requisition_flow ="<?=$requisition?>" id = "requistion_approval" type="button">Add Approvals</button>
                                             <button class="btn btn-danger" id = 'reset_req' type="reset">Reset</button>
                                           </div>
                                         </div>
                                       </form>    
                            </div>
                      </div>
                      <div class="x_panel">
                            <div class="x_title">
                              <h2>Cash Approval Levels</h2>
                              <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                              <br />
                              <ul class ="flow c_flow">
                                    <li><a class="active">Staff </a></li>
                                    <?php if(count($data) > 0) {?>
                                    <?php $cash = explode(";",$data[0]['cash_flow']) ?>
                                    <?php for ($r = 0; $r < count($cash); $r++){ ?>
                                      <?php if($cash[$r] != '') {?>
                                      <li><a class=''><span style='margin-left:2px;margin-right:7px;'>&#x27F6;</span><?=$cash[$r]?></a></li>
                                    <?php } } }?> 
                                  </ul>
                                  <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                                        <div class="form-group" style="margin-top:20px;">  
                                         <div class="col-md-12 col-sm-12 col-xs-12 text-center form_center">
                                             <input type="text" id="cash_level" name="cash_level" required="required" class="form-control col-md-7 col-xs-12">
                                         </div>
                                        </div>
                                         <div class="form-group btn_center" style="margin-top:20px;">
                                           <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-4">
                                            <?php $cash = isset($data[0]['cash_flow']) ? $data[0]['cash_flow'] : '' ?>
                                             <button class="btn btn-success" cash_flow ="<?=$cash?>" id = "cash_approval" type="button">Add Approvals</button>
                                             <button class="btn btn-danger" id = 'reset_cash' type="reset">Reset</button>
                                           </div>
                                         </div>
                                       </form>    
                            </div>
                      </div>
                     <!--  <div class="x_panel">
                            <div class="x_title">
                              <h2>Exit Process</h2>
                              <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                              <br />
                              <ul class ="flow e_flow">
                                    <li><a class="active">Staff </a></li>
                                    <?php if(count($data) > 0) {?>
                                    <?php $exit = explode(";",$data[0]['exit_flow']) ?>
                                    <?php for ($r = 0; $r < count($exit); $r++){ ?>
                                      <?php if($exit[$r] != '') {?>
                                      <li><a class=''><span style='margin-left:2px;margin-right:7px;'>&#x27F6;</span><?=$exit[$r]?></a></li>
                                    <?php } } }?> 
                                  </ul>
                                  <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                                        <div class="form-group" style="margin-top:20px;">  
                                         <div class="col-md-12 col-sm-12 col-xs-12 text-center form_center">
                                             <input type="text" id="exit_level" name="exit_level" required="required" class="form-control col-md-7 col-xs-12">
                                         </div>
                                        </div>
                                         <div class="form-group btn_center" style="margin-top:20px;">
                                           <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-4">
                                            <?php $exit = isset($data[0]['exit_flow']) ? $data[0]['exit_flow'] : '' ?>
                                             <button class="btn btn-success" exit_flow ="<?=$exit?>" id = "exit_approval" type="button">Add Approvals</button>
                                             <button class="btn btn-danger" id = 'reset_exit' type="reset">Reset</button>
                                           </div>
                                         </div>
                                       </form>    
                            </div>
                      </div> -->
                      <div class="x_panel">
                        <div class="x_content">
                          <br />
                          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"> 
                           
                            <button type="submit" id = 'submit_btn' class="btn btn-success">Submit</button>
                          </div>
                        </div>
                      </div>
                  </div>
                  <!--div class="col-md-4 col-sm-12 col-xs-12">
                     <div class="x_panel">
                        <div class="x_title">
                          <h2>Share Access </h2>
                          <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                              <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Settings 1</a>
                                </li>
                                <li><a href="#">Settings 2</a>
                                </li>
                              </ul>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                          </ul>
                          <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                          <br />
                          <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                            <div class="form-group">
                              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <div>
                                   <select name = "user" class="form-control">
                                     <option value = "">share privilege</option>
                                   </select>
                                </div>
                              </div>
                              <div class="col-md-12 col-sm-12 col-xs-12">
                                 <h2 style="margin-top: 20px;">Share Based On Department</h2><hr>
                              </div>
                              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <div>
                                   <ul class="list-group">
                                      <li class="list-group-item d-flex justify-content-between align-items-center">
                                        Fleet
                                        <span class="badge  badge-pill" style="width: 25px;height: 25px;border: 1px solid #ccc;position: absolute;right: 0;margin-right: 20px;background-color: #fff;border-radius: 14px;">
                                          <i class="fas fa-check text-primary" color = ""></i>
                                      </span>
                                      </li>
                                    </ul>
                                </div>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                  </div-->
                </div>
                <div class="row" style="width:60%;margin-left:auto;margin-right:auto;">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                     
                    </div>
                </div>
                <div class="row" style="width:60%;margin-left:auto;margin-right:auto;">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                         
                        </div>
                    </div>
                <div class="row" style="width:60%;margin-left:auto;margin-right:auto;">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                     
                    </div>
                </div>
                <div class="row" style="width:60%;margin-left:auto;margin-right:auto;">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          
                        </div>
                </div>
                <div class="row" style="width:60%;margin-left:auto;margin-right:auto;">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          
                        </div>
                </div>
                <div class="row" style="width:60%;margin-left:auto;margin-right:auto;">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                     
                    </div>
                </div>
                 <div class="row" style="width:60%;margin-left:auto;margin-right:auto;">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <!--div class="x_panel">
                        <div class="x_content">
                          <br />
                          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"> 
                            <button class="btn btn-primary" type="button">Cancel</button>
                            <button class="btn btn-primary" type="reset">Reset</button>
                            <button type="submit" id = 'submit_btn' class="btn btn-success">Submit</button>
                          </div>
                        </div>
                      </div-->
                    </div>
                </div>
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
    <script type="text/javascript" src="js/admin_settings.js?version=5.23"></script>
	
  </body>
</html>

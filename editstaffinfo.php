<?php 
 include "connection.php";
 session_start();
  if(!isset($_SESSION['user']['id'])) header("Location: login.php");
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
 $manager = [];
 $alldepartments = [];
 $allbranches = [];
 if(!isset($_SESSION['user'])) header("Location: login");
  $query = "SELECT * from company WHERE id = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
        $dept = explode(";",$row['department']);
        $branch = explode(";", $row['branch']);
        $appraisal_level = explode(";", $row['appraisal_flow']);
        $leave_level = explode(";", $row['leave_flow']);
        $cash_level = explode(";", $row['cash_flow']);
        $exit_level = explode(";", $row['exit_flow']);
        $requisition_level = explode(";", $row['requisition_flow']);
      }
  }
  $query = "SELECT * from users WHERE position != ''";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $manager[] = $row;
        //print_r($row['fname']);
      }
  }
  
  
  for($e = 0; $e < count($appraisal_level); $e++){
    if(!in_array(strtolower($appraisal_level[$e]), $all_approval)){ $all_approval[] = strtolower($appraisal_level[$e]);
        
    }
  } 
  for($e = 0; $e < count($leave_level); $e++){
    if(!in_array(strtolower($leave_level[$e]), $all_approval)){ $all_approval[] = strtolower($leave_level[$e]);
        
    }
  } 
  for($e = 0; $e < count($cash_level); $e++){
    if(!in_array(strtolower($cash_level[$e]), $all_approval)){ $all_approval[] = strtolower($cash_level[$e]);
        
    }
  }
  for($e = 0; $e < count($requisition_level); $e++){
    if(!in_array(strtolower($requisition_level[$e]), $all_approval)){ $all_approval[] = strtolower($requisition_level[$e]);
        
    }
  }
  $allstaff =[];
  $query = "SELECT * from users WHERE companyId = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $allstaff[] = $row;       
      }
  }  

  $query = "SELECT * from departments WHERE company_id = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $alldepartments[] = $row;
        //$dept[] = $row;
      }
  }

  $query = "SELECT * from branches WHERE company_id = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $allbranches[] = $row;
        //$dept[] = $row;
      }
  }
  
  $query = "SELECT * from grade";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
          $all_grades[]= $row;
        //$all_approval[] = $row['rolename'];
      }
  }
  
  //print_r($all_approval);
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
  <style>
      .displayview{
          width:60%;margin-left:auto;margin-right:auto;
      }
      @media screen and (max-width: 768px){
          .displayview{
              width:98%;
          }
      }
  </style>

  <body class="nav-md">
    <div class="container body" style="background-color: #f8fafc;overflow-x:hidden;">
      <div class="">
        <!-- top navigation -->
        <?php include 'top.php' ?>
        <!-- /top navigation -->
        <!-- page content -->
        <div class="right_col" role="main" style="width:100%;margin-left:0px;">
            <div class="">
                <a href="dashboard" class="btn btn-info">Return to Dashboard</a>
                <div class="page-title">
                  <div class="title_left">
                    <h3>Edit Staff Details</h3>
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
                <div class="row displayview" style="">
                   <?php if(isset($_SESSION['msg'])) {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                  <?php } ?>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_content">
                          <br />
                          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                              <p>Select Staff</p>
                             <select name='allstaff' class='form-control getdetails'>
                                 <option value=''></option>
                                 <?php for($e = 0; $e < count($allstaff); $e++){ ?>
                                 <option value='<?=$allstaff[$e]['id']?>'><?=$allstaff[$e]['name']?> <?=$allstaff[$e]['fname']?> <?=$allstaff[$e]['mname']?></option>
                                 <?php }?>
                             </select>
                          </div>
                        </div>
                      </div>  
                    <div class="x_panel">
                      <div class="x_title">
                        <h2>Personal Information</h2>
                        <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                        <br />
                         <div style="text-align: center;margin-bottom: 10px;" id = "uploadimg"><img id="uploadimage" style="width: 120px;height: 120px;" class="uploadimg" src="images/user_profile.png?>" alt=""></div>
                        <form id="" action="process_staff_data.php" method="POST" enctype="multipart/form-data" data-parsley-validate class="form-horizontal form-label-left">
                          <div class="form-group" style='display:none;'>
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">ID <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" id="staff_id" value="" name = "staff_id" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>    
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Surname <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" id="name" value="" name = "name" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">First Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" id="fname" value="" name = "name" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Middle Name <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" id="mname" value="" name = "name" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Phone Number <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="number" id="phone_number" value="" name="phone_number" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Email <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="email" id="email" value="" name="phone_number" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Employee ID <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" id="employee_ID" value="" name = "employee_ID" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>
                           <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">Role <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" id="role" value="" name = "role" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">Grade <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="grade" class="form-control" id = "grade">
                              <option value = ""></option>
                              <?php for($r = 0; $r < count($all_grades); $r++){?>
                                <option value = "<?=$all_grades[$r]['id'];?>"> <?=$all_grades[$r]['grade_name'];?></option>
                              <?php } ?>
                            </select>
                            </div>
                          </div>
                         
                           <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">Position <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <select name="position" class="form-control position" id = "position">
                               <option value=""></option>
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
                               <option value=""></option>
                              <?php for($r = 0; $r < count($alldepartments); $r++){?>
                                <option value = "<?=$alldepartments[$r]['dept'];?>"> <?=$alldepartments[$r]['dept'];?></option>
                              <?php } ?>
                            </select>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Branch <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="branch" class="form-control" id = "branch">
                              <option value = ""></option>
                              <?php for($r = 0; $r < count($allbranches); $r++){?>
                                <option value = "<?=$allbranches[$r]['name'];?>"> <?=$allbranches[$r]['name'];?></option>
                              <?php } ?>
                            </select>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Gender <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="gender" class="form-control" id = "gender">
                              <option value = ""></option>
                              <option value = 'Male'>Male</option>
                              <option value ='Female'>Female</option>
                            </select>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Marital Status <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="marital_status" class="form-control" id = "marital_status">
                              <option value = ""></option>
                              <option value='single'>Single</option>
                              <option value ='married'>Married</option>
                            </select>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Date of Birth <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type='date' class='form-control' name ='dob'id='dob'/>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Town 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="" value="" class="form-control" id = "town"/>
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">LGA of Origin<span class="required" style='color:red'>*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="" value="" class="form-control" id = "lga"/>
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">State of Origin<span class="required" style='color:red'>*</span> 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="" value="" class="form-control" id = "sorigin"/>
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">State of Residence<span class="required"></span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="" value="" class="form-control" id = "sresidence"/>
                            </div>
                          </div>
                          
                         <!--  <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Upload profile Image <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="file" name="image" onchange="readURL(this)" id="loadimg" style="display: none;">
                              <button type="button" id="showfile" class="btn btn-info">Upload Image</button>
                            </div>
                          </div> -->
                        </form>
                      </div>
                    </div>
                    <div class="x_panel">
                      <div class="x_title">
                        <h2>HMO</h2>
                        <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                        <br />
                          <form id="" data-parsley-validate class="form-horizontal form-label-left">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">On HMO?
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <select name="" id="on_hmo" class="form-control col-md-7 col-xs-12">
                                    <option value=""></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">HMO
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input type="text" name="pm_name" class="form-control col-md-7 col-xs-12" id = 'hmo' value="">
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">HMO Number
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input type="number" name="pm_number" class="form-control col-md-7 col-xs-12" id = 'hmo_number' value="">
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">HMO Plan
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <textarea type="text" name="" class="form-control col-md-7 col-xs-12"  id = 'hmo_plan' value=""></textarea>
                                </div>
                              </div>
                              
                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">HMO Hospital
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input type="text" name="" class="form-control col-md-7 col-xs-12"  id = 'hmo_hospital' value=""/>
                                </div>
                              </div>
                              
                              
                              
                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">HMO status
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input type="text" name="" class="form-control col-md-7 col-xs-12"  id = 'hmo_status' value=""/>
                                </div>
                              </div>
                              
                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">HMO remarks
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input type="text" name="" class="form-control col-md-7 col-xs-12"  id = 'hmo_remarks' value=""/>
                                </div>
                              </div>
                              
                              
                              
                              
                              
                              
                          </form>
                      </div>
                    </div>

                    <div class="x_panel">
                      <div class="x_title">
                        <h2>Pension</h2>
                        <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                        <br />
                          <form id="" data-parsley-validate class="form-horizontal form-label-left">
                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">Pension
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input type="text" name="pm_name" class="form-control col-md-7 col-xs-12" id = 'pension' value="">
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">Pension Number
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input type="text" name="pm_number" class="form-control col-md-7 col-xs-12" id = 'pension_number' value ="">
                                </div>
                              </div>
                              
                              
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
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Approval <?=$k+1?><!-- <?=$leave_level[$k]?> --> Name
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <select name = 'leave_manager' class='lmanager form-control ' id ='<?=$k?>'>
                                  <option value = ''></option>
                                  <?php for($r = 0; $r < count($manager); $r++) { ?>
                                  <option value="<?=$leave_level[$k]?>:<?=$manager[$r]['email']?>"><?=$manager[$r]['name']?> <?=$manager[$r]['fname']?> <?=$manager[$r]['mname']?></option>
                                  <?php } ?>
                              </select>        
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
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Approval <?=$k+1?> <!-- <?=$appraisal_level[$k]?> --> Name
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <select name = 'appraisal_manager' class='form-control amanager' id ='app<?=$k?>' attr ='<?=$k?>'>
                                  <option value = ''></option>
                                  <?php for($r = 0; $r < count($manager); $r++) { ?>
                                  <option value="<?=$appraisal_level[$k]?>:<?=$manager[$r]['email']?>"><?=$manager[$r]['name']?> <?=$manager[$r]['fname']?> <?=$manager[$r]['mname']?></option>
                                  <?php } ?>
                              </select>        
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
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Approval <?=$k+1?> <!-- <?=$requisition_level[$k]?> --> Name
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <select name = 'req_manager' class='form-control rmanager' id ='req<?=$k?>' attr ='<?=$k?>'>
                                  <option value = ''></option>
                                  <?php for($r = 0; $r < count($manager); $r++) { ?>
                                  <option value="<?=$requisition_level[$k]?>:<?=$manager[$r]['email']?>"><?=$manager[$r]['name']?> <?=$manager[$r]['fname']?> <?=$manager[$r]['mname']?></option>
                                  <?php } ?>
                              </select>        
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
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Approval <?=$k+1?> <!-- <?=$cash_level[$k]?> --> Name 
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <select name = 'cash_manager' class='form-control cmanager' id ='cash<?=$k?>' attr ='<?=$k?>'>
                                  <option value = ''></option>
                                  <?php for($r = 0; $r < count($manager); $r++) { ?>
                                  <option value="<?=$cash_level[$k]?>:<?=$manager[$r]['email']?>"><?=$manager[$r]['name']?> <?=$manager[$r]['fname']?> <?=$manager[$r]['mname']?></option>
                                  <?php } ?>
                              </select>        
                            </div>
                            </div>
                            <?php } ?>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="row" style="width:60%;margin-left:auto;margin-right:auto;">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="x_panel">
                        <div class="x_title">
                          <h2>Exit Approvals</h2>
                          <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                          <br />
                          <form id="" data-parsley-validate class="form-horizontal form-label-left">
                            <?php for ($k = 0; $k < count($exit_level); $k++) {?>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name"><?=$exit_level[$k]?> Email <span class="required">*</span>
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <select name = 'exit_manager' class='form-control emanager' id ='<?=$k?>'>
                                  <option value = ''></option>
                                  <?php for($r = 0; $r < count($manager); $r++) { ?>
                                  <option value="<?=$exit_level[$k]?>:<?=$manager[$r]['email']?>"><?=$manager[$r]['name']?> <?=$manager[$r]['fname']?> <?=$manager[$r]['mname']?></option>
                                  <?php } ?>
                              </select>        
                            </div>
                            </div>
                            <?php } ?>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div> -->
                  <div class="row" style="width:60%;margin-left:auto;margin-right:auto;">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="x_panel">
                        <div class="x_content">
                          <br />
                          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"> 
                            <button type="button" id ='onboard' class="btn btn-success">Update Staff Record</button>
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
    <!--script type="text/javascript" src="js/staff_settings.js"></script-->
    <script>
         $(function(){
                var allstaff = <?php echo json_encode($allstaff); ?>;
                console.log(allstaff);
                let leaveflow = [];
                let appraisalflow = [];
                let reqflow = [];
                let cashflow = [];
                let exitflow = [];
             $('.getdetails').on('change', function(e){
                 e.preventDefault();
                 let id = $(this).val();
                 for(let y =0; y < allstaff.length;y++){
                     if(allstaff[y]['id'] == id){
                            $('#staff_id').val(id);
                            document.getElementById("uploadimage").src="./images/"+allstaff[y]['profile_image']+"";
                            $('#name').val(allstaff[y]['name']);
                        	$('#fname').val(allstaff[y]['fname']);
                        	$('#mname').val(allstaff[y]['mname']);
                        	$('#phone_number').val(allstaff[y]['phone_number']);
                        	$('#email').val(allstaff[y]['email']);
                        	$('.position').val(allstaff[y]['position'].toLowerCase());
                        	$('#department').val(allstaff[y]['department']);
                        	$('#branch').val(allstaff[y]['branch']);
                        	$('#gender').val(allstaff[y]['gender']);
                        	$('#marital_status').val(allstaff[y]['marital_status']);
                        	$('#dob').val(allstaff[y]['dob']);
                        	$('#town').val(allstaff[y]['town']);
                        	$('#lga').val(allstaff[y]['lga']);
                        	$('#role').val(allstaff[y]['role']);
                        	$('#sorigin').val(allstaff[y]['sorigin']);
                        	$('#sresidence').val(allstaff[y]['sresidence']);
                        	$('#on_hmo').val(allstaff[y]['on_hmo']);
                        	$('#hmo').val(allstaff[y]['hmo']);
                        	$('#hmo_number').val(allstaff[y]['hmo_number']);
                        	$('#hmo_plan').val(allstaff[y]['hmo_plan']);
                        	$('#hmo_hospital').val(allstaff[y]['hmo_hospital']);
                        	$('#hmo_status').val(allstaff[y]['hmo_status']);
                        	$('#hmo_remarks').val(allstaff[y]['hmo_remarks']);
                        	$('#pension').val(allstaff[y]['pension']);
                        	$('#pension_number').val(allstaff[y]['pension_number']);
                        	$("#employee_ID").val(allstaff[y]['employee_ID']);
                        	let leave = allstaff[y]['leave_flow'].split(';');
                        	let appraisal = allstaff[y]['appraisal_flow'].split(';');
                        	let req = allstaff[y]['requisition_flow'].split(';');
                        	let cash = allstaff[y]['cash_flow'].split(';');
                        	for(let r = 0; r < leave.length;r++){
                        	    $('#'+r+'').val(leave[r]);
                        	    leaveflow[r] = leave[r].trim();
                        	}
                        	for(let r = 0; r < appraisal.length;r++){
                        	    $('#app'+r+'').val(appraisal[r]);
                        	    appraisalflow[r] = appraisal[r].trim();
                        	}
                        	for(let r = 0; r < req.length;r++){
                        	    $('#req'+r+'').val(req[r]);
                        	    reqflow[r] = req[r].trim();
                        	}
                        	for(let r = 0; r < cash.length;r++){
                        	    $('#cash'+r+'').val(cash[r]);
                        	    cashflow[r] = cash[r].trim();
                        	}
                     }
                 }
             })    
             $('.lmanager').on('change', function(e){
                 e.preventDefault();
                let id = this.id;
                let val = $(this).val();
                leaveflow[id] = val;
                //alert(leaveflow);
            });
            $('.amanager').on('change', function(e){
                let id = this.id;
                let a = $(this).attr('attr');
                let val = $(this).val();
                appraisalflow[a] = val;
                //alert(appraisalflow);
            });
            $('.rmanager').on('change', function(e){
                let id = this.id;
                let a = $(this).attr('attr');
                let val = $(this).val();
                reqflow[a] = val;
            });
            $('.cmanager').on('change', function(e){
                let id = this.id;
                let a = $(this).attr('attr');
                let val = $(this).val();
                cashflow[a] = val;
            });
            $('.emanager').on('change', function(e){
                let id = this.id;
                let a = $(this).attr('attr');
                let val = $(this).val();
                exitflow[a] = val;
            });
            $('#onboard').on('click',function(e){
            	e.preventDefault();
            	let approvals = [];
            	let l_approvals = [];
            	let r_approvals = [];
            	let c_approvals = [];
            	let appraisal_approvals = [];
            	let request_approvals = [];
            	let cashs_approvals = [];
            	let leave_approvals = [];
            	let name = $('#name').val();
            	let fname = $('#fname').val();
            	let mname = $('#mname').val();
            	let phone_number = $('#phone_number').val();
            	let email = $('#email').val();
            	let position = $('#position').val();
            	let department = $('#department').val();
            	let branch = $('#branch').val();
            	let gender = $('#gender').val();
            	let marital_status = $('#marital_status').val();
            	let dob = $('#dob').val();
            	let town = $('#town').val();
            	let lga = $('#lga').val();
            	let role = $('#role').val();
            	let sorigin = $('#sorigin').val();
            	let sresidence = $('#sresidence').val();
            	let on_hmo = $('#on_hmo').val();
            	let hmo = $('#hmo').val();
            	let hmo_number = $('#hmo_number').val();
            	let hmo_plan = $('#hmo_plan').val();
            	let hmo_hospital = $('#hmo_hospital').val();
            	let hmo_status = $('#hmo_status').val();
            	let hmo_remarks = $('#hmo_remarks').val();
            	let pension = $('#pension').val();
            	let pension_number = $('#pension_number').val();
            	if(leaveflow.length > 0) 
            	 leaveflow = leaveflow.join(';');
            	else leaveflow = ''; 
            	if(appraisalflow.length > 0) 
            	 appraisalflow = appraisalflow.join(';');
            	else appraisalflow = ''; 
            	if(reqflow.length > 0) 
            	 reqflow = reqflow.join(';');
            	else reqflow = ''; 
            	if(cashflow.length > 0) 
            	 cashflow = cashflow.join(';');
            	else cashflow = ''; 
            	let formdata =  new FormData();
            	formdata.append('fname',fname);
            	formdata.append('mname',mname);
            	formdata.append('staff_id',$("#staff_id").val());
            	formdata.append('email',$("#email").val());
            	formdata.append('phone_number',$("#phone_number").val());
            	formdata.append('position', $("#position").val());
            	formdata.append('gender', $("#gender").val());
            	formdata.append('marital_status', marital_status);
            	formdata.append('dob', $("#dob").val());
            	formdata.append('town', $("#town").val());
            	formdata.append('lga', $("#lga").val());
            	
            	formdata.append('sorigin', $("#sorigin").val());
            	formdata.append('sresidence', $("#sresidence").val());
            	formdata.append('on_hmo', $("#on_hmo").val());
            	
            	formdata.append('hmo', $("#hmo").val());
            	formdata.append('hmo_number', $("#hmo_number").val());
            	formdata.append('hmo_plan', $("#hmo_plan").val());
            	
            	formdata.append('hmo_hospital', $("#hmo_hospital").val());
            	formdata.append('hmo_status', $("#hmo_status").val());
            	formdata.append('hmo_remarks', $("#hmo_remarks").val());
            	
            	formdata.append('pension', $("#pension").val());
            	formdata.append('pension_number', $("#pension_number").val());
            	
            	formdata.append('department',department);
            	formdata.append('branch',branch);
            	formdata.append('employee_ID',$("#employee_ID").val());
            	formdata.append('name', $("#name").val());
            	formdata.append('role',$("#role").val());
            	formdata.append('submit','true');
                formdata.append('leaveflow',leaveflow);
            	formdata.append('appraisalflow',appraisalflow);
            	formdata.append('reqflow', reqflow);
            	formdata.append('cashflow', cashflow);
            	formdata.append('exitflow', exitflow);
            	//alert('aaaaa');
            	$('#onboard').text('Please Wait....');
            	//$(this).prop("disabled",true);
            	$.ajax({
            		type: 'POST',
            		url : 'process_update_data_onboarding.php',
                    data: formdata,
                    processData:false,
            	    contentType:false,
            	    success:function(data){
            	     $('#onboard').text('Update Staff Record');
            	     $('#onboard').prop("disabled",false);     
            	     //alert(data);
            	     //console.log(data);
                      if(data == '1'){
            					Swal.fire({
            					  position: 'top-end',
            					  type: 'success',
            					  title: 'Your update is Noted',
            					  showConfirmButton: false,
            					  timer: 1500
            					});
                      setTimeout(function(){ reload(); }, 3000);
            					//var myVar = setInterval(reload, 1000);

            		   }else{
            					//window.location.href = '/selfservice/staff_settings.php';
            		 }
            	    }
            	});
            	//alert(all_appraisal_approvals);
            	//alert(all_leave_approvals);
            })
         });
         function reload() {
            var host = window.location.host;
            window.location.href = host == 'localhost' ? '/newhrcore/editstaffinfo.php' : '/editstaffinfo';
        }
    </script>
  </body>
</html>

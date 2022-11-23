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
 $manager = [];
 $allflow = [];
 $alldepartments = [];
 $allbranches = [];
 if(!isset($_SESSION['user'])) header("Location: login");
 if($_SESSION['user']['category'] == 'staff' && $_SESSION['user']['add_employee_management'] != '1') header("Location: dashboard");
  $query = "SELECT * from company WHERE id = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
        //print_r($data);
        $dept = explode(";",$row['department']);
        $branch = explode(";", $row['branch']);
        $app = explode(";", $row['appraisal_flow']);
        $leavef = explode(";", $row['leave_flow']);
        $cashf = explode(";", $row['cash_flow']);
        $req = explode(";", $row['requisition_flow']);
        $requisition_level = explode(";", $row['requisition_flow']);
        $leave_level = explode(";", $row['leave_flow']);
        $appraisal_level = explode(";", $row['appraisal_flow']);
        $cash_level = explode(";", $row['cash_flow']);
        //print_r($leave_level[0]);
        
        for($r = 0; $r < count($req); $r++)
        {
            if(!in_array($req[$r],$allflow)) $allflow[] = $req[$r];   
        }
        
        for($r = 0; $r < count($app); $r++)
        {
            if(!in_array($app[$r],$allflow)) $allflow[] = $app[$r];   
        }
        
        
        for($r = 0; $r < count($leavef); $r++)
        {
            if(!in_array($leavef[$r],$allflow)) $allflow[] = $leavef[$r];   
        }
        
        for($r = 0; $r < count($cashf); $r++)
        {
           if(!in_array($cashf[$r],$allflow)) $allflow[] = $cashf[$r];   
        }
        

      }
      //print_r($allflow);
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
  //print_r($alldepartments);
  $query = "SELECT * from flows WHERE admin_id = '".$_SESSION['user']['id']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        //   if($row['flowname'] == 'Requisition') $requisition_level[] = $row;
        //   if($row['flowname'] == 'Leave') $leave_level[] = $row;
        //   if($row['flowname'] == 'Appraisal') $appraisal_level[] = $row;
        //   if($row['flowname'] == 'Cash') $cash_level[] = $row;
        //   if(!in_array($row['approval'],$all_approval) && $row['approval'] != '') $all_approval[] = $row['approval'];
          
      }
      //print_r($requisition_level);
  }
  
  $query = "SELECT * from users WHERE position != '' and admin_id = '".$_SESSION['user']['id']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $manager[] = $row;
      }
  }
  
  $query = "SELECT * from roles WHERE admin_id = '".$_SESSION['user']['id']."' AND rolename != ''";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        //$all_approval[] = $row['rolename'];
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
  
?>
<!DOCTYPE html>
<html lang="en">
  <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <!-- Meta, title, CSS, favicons, etc. -->
    
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
                    <h3>Onboard Staff</h3>
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
                  <?php } ?>
                   <?php if(isset($_SESSION['invalid']) && $_SESSION['invalid'] != '') {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            <?=$_SESSION['invalid']?>
                            <?php unset($_SESSION['invalid']);?>
                        </div>
                  <?php } ?>
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                      <div class="x_title">
                        <h2>Personal Information</h2>
                         <ul class="nav navbar-right panel_toolbox">
                          <li>
                          <!--form action="process_file.php" method="post"-->
                                
                                <button type="submit" id="btnExport"
                                    name='export' value=""
                                    class="btn btn-primary" data-toggle="modal" data-target="#addModal">Upload Bulk Staff</button>
                            <!--/form-->
                          </li>
                        </ul>
                        <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                        <br />
                        <form id="myform" action="process_staff_data.php" method="POST" enctype="multipart/form-data" data-parsley-validate class="form-horizontal form-label-left">
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
                                <option value = "<?=$all_grades[$r]['grade_name'];?>"> <?=$all_grades[$r]['grade_name'];?></option>
                              <?php } ?>
                            </select>
                            </div>
                          </div>
                         
                           <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="role">Position <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <select name="position" class="form-control" id = "position">
                               <option value=""></option>
                              <?php for($r = 0; $r < count($allflow); $r++){?>
                               <?php if($allflow[$r] != '') { ?>
                                <option value = "<?=$allflow[$r];?>"> <?=strtoupper($allflow[$r]);?></option>
                              <?php } } ?>
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
                          
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Upload profile Image <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <div style="text-align: center;margin-bottom: 10px;" id = "uploadimg"><img style="width: 100px;height: 100px;" class="uploadimg" src="images/<?=$_SESSION['user']['profile_image']?>" alt=""></div>    
                              <input type="file" name="image" onchange="readURL(this)" id="loadimg" style="">
                              <!--button type="button" id="showfile" class="btn btn-info">Upload Image</button-->
                            </div>
                            
                          </div>
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
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Approval <?=$k+1?><!-- <?=$leave_level[$k]?> --> Name <span class="required">*</span>
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
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Approval <?=$k+1?><!-- <?=$appraisal_level[$k]?> --> Name <span class="required">*</span>
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <select name = 'appraisal_manager' class='form-control amanager' id ='<?=$k?>'>
                                  <option value = ''></option>
                                  <?php for($r = 0; $r < count($manager); $r++) { ?>
                                  <option value="<?=$appraisal_level[$k]["approval"]?>:<?=$manager[$r]['email']?>"><?=$manager[$r]['name']?> <?=$manager[$r]['fname']?> <?=$manager[$r]['mname']?></option>
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
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Approval <?=$k+1?> <!-- <?=$requisition_level[$k]?> --> Name <span class="required">*</span>
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <select name = 'req_manager' class='form-control rmanager' id ='<?=$k?>'>
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
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Approval <?=$k+1?> <!-- <?=$cash_level[$k]?> --> Name <span class="required">*</span>
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <select name = 'cash_manager' class='form-control cmanager' id ='<?=$k?>'>
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
                     <div>
                     <?php if(isset($_SESSION['msg'])) {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                     <?php } ?>
                  </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                      <div class="x_panel">
                        <div class="x_content">
                          <br />
                          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"> 
                            <button type="button" id ='onboard' class="btn btn-success">Submit</button>
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
     <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">Upload Bulk List</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div style="margin: 10px">
                
                <a class="btn btn-primary" href ="document/dataformat.xlsx">Download Sample</a>
              </div>
              <div class="modal-body">
                 <form action = 'process_staff_list.php' method = "POST" enctype="multipart/form-data">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Upload Document</label>
                    <input type="file" name ="list" class="form-control" aria-describedby="emailHelp" placeholder="">
                  </div>
                 
                  <input type="submit" name = "submit" class="btn btn-primary"/>
                </form>
              </div>
            </div>
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
        function readURL(input) {
          if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
            $('.uploadimg')
                .attr('src', e.target.result)
                .width(100)
                .height(100);
          };
          reader.readAsDataURL(input.files[0]);
         }
        }
    </script>
    <script>
         $(function(){
                let leaveflow = [];
                let appraisalflow = [];
                let reqflow = [];
                let cashflow = [];
                let exitflow = [];
             $('.lmanager').on('change', function(e){
                 e.preventDefault();
                let id = this.id;
                let val = $(this).val();
                leaveflow[id] = val;
                //alert(leaveflow);
            });
            $('.amanager').on('change', function(e){
                let id = this.id;
                let val = $(this).val();
                appraisalflow[id] = val;
            });
            $('.rmanager').on('change', function(e){
                let id = this.id;
                let val = $(this).val();
                reqflow[id] = val;
            });
            $('.cmanager').on('change', function(e){
                let id = this.id;
                let val = $(this).val();
                cashflow[id] = val;
            });
            $('.emanager').on('change', function(e){
                let id = this.id;
                let val = $(this).val();
                exitflow[id] = val;
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
            	let exit_aprovals = [];
            	let name = $('#name').val();
            	let fname = $('#fname').val();
            	let mname = $('#mname').val();
            	let phone_number = $('#phone_number').val();
            	let email = $('#email').val();
            	let position = $('#position').val();
            	let grade = $('#grade').val();
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
            	if(exitflow.length > 0) 
            	 exitflow = exitflow.join(';');
            	else exitflow = ''; 
            	//alert('aaasdff');
            	let formdata =  new FormData();
            	if($('input[type=file]')[0].files[0] != undefined) formdata.append('image',$('input[type=file]')[0].files[0]);
            	formdata.append('fname',fname);
            	formdata.append('mname',mname);
            	formdata.append('email',$("#email").val());
            	formdata.append('phone_number',$("#phone_number").val());
            	formdata.append('position', $("#position").val());
            	formdata.append('grade', $("#grade").val());
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
            	//formdata.append('exitflow', exitflow);
            	//alert('aaaaa
            	$('#onboard').text('Processing....');
            	$('#onboard').prop('disabled','true');
            	$.ajax({
            		type: 'POST',
            		url : 'process_data_onboarding.php',
                    data: formdata,
                    processData:false,
            	    contentType:false,
            	    success:function(data){
            	     //alert(data);
            	     $('#onboard').text('Submit');
            	     $('#onboard').removeAttr('disabled');
            	     //console.log(data);
                      if(data == '1'){
            					Swal.fire({
            					  position: 'top-end',
            					  type: 'success',
            					  title: 'New Staff Added',
            					  showConfirmButton: false,
            					  timer: 1500
            					});
            					$('#name').val('');
                            	$('#fname').val('');
                            	$('#mname').val('');
                            	$('#phone_number').val('');
                            	$('#email').val('');
                            	$('#position').val('');
                            	$('#grade').val('');
                            	$('#department').val('');
                            	$('#branch').val('');
                            	$('#gender').val('');
                            	$('#marital_status').val('');
                            	$('#dob').val('');
                            	$('#town').val('');
                            	$('#lga').val('');
                            	$('#role').val('');
                            	$('#sorigin').val('');
                            	$('#sresidence').val('');
                            	$('#on_hmo').val('');
                            	$('#hmo').val('');
                            	$('#hmo_number').val('');
                            	$('#hmo_plan').val('');
                            	$('#hmo_hospital').val('');
                            	$('#hmo_status').val('');
                            	$('#hmo_remarks').val('');
                            	$('#pension').val('');
                            	$('#pension_number').val('');
                            	$("#employee_ID").val('');
            					//$("#myform")[0].reset();
            		   }else{
            					alert(data);
            		 }
            	    }
            	})
            	//alert(all_appraisal_approvals);
            	//alert(all_leave_approvals);
            })
         });
    </script>
  </body>
</html>

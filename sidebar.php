<?php
 include 'connection.php';
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
 $leave_level = [];
 $requisition_level = [];
 $all_approval = [];
  $cash_level = [];
 //print_r($_SESSION['user']);
  $query = "SELECT company.requisition_flow,company.cash_flow,company.leave_flow,company.appraisal_flow,company.branch, company.department FROM users INNER JOIN company ON company.id = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
        $dept = explode(";",$row['department']);
        $branch = explode(";", $row['branch']);
        $appraisal_level = explode(";", $row['appraisal_flow']);
        $leave_level = explode(";", $row['leave_flow']);
        $requisition_level = explode(";", $row['requisition_flow']);
        $cash_level = explode(";", $row['cash_flow']);
      }
  }
 $appraisal_approval_details = $appraisal_level;
 $leave_approval_details = $leave_level;
 $requisition_approval_details = $requisition_level;
 for($e = 0; $e < count($appraisal_level); $e++){
  if(count($appraisal_approval_details) > 0){

    $val = $appraisal_approval_details[$e];
    if(!in_array(strtolower($val), $all_approval)){ $all_approval[] = strtolower($val);}
  }
 }

 //print_r($leave_approval_details);
for($e = 0; $e < count($leave_level); $e++){
  if(count($leave_approval_details) > 0){
      $val = $leave_approval_details[$e];
      if(!in_array(strtolower($val), $all_approval)){ $all_approval[] = strtolower($val);}
  }
 }
 //print_r($_SESSION['user']['requisition_flow']);
 for($e = 0; $e < count($requisition_level); $e++){
  if(count($requisition_approval_details) > 0){
      $val = $requisition_approval_details[$e];
      if(!in_array(strtolower($val), $all_approval)){ $all_approval[] = strtolower($val);}
  }
}

  for($e = 0; $e < count($cash_level); $e++){
  if(count($cash_level) > 0){
      $val = $cash_level[$e];
      if(!in_array(strtolower($val), $all_approval)){ $all_approval[] = strtolower($val);}
  }
    
 }
//print_r($all_approval);
?>
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu"> 
                  <li><a href="dashboard"><i class="fa fa-home"></i> Dashboard <span class="fa fa-chevron-down"></span></a>
                    
                  </li>
                   <li><a href="/document/HRCORE_Documentation.pdf"><i class="fa fa-book"></i> User Guide</a>
                   <li><a href="/document/ICSHRPolicy.pdf"><i class="fa fa-book"></i> HR Policy</a>
                    
                  </li>
                  <li><a><i class="fa fa-desktop"></i>Requisition<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <?php if($_SESSION['user']['category'] == 'admin' || $_SESSION['user']['add_item_permission'] == '1') { ?>
                        <li><a href="addItems">Add Item</a></li>
                        <li><a href="store">Store</a></li>
                         <li><a href="allrequesteditems">Company Requests</a></li>
                      <?php } ?>
                      <li><a href="requestitems">Make Request</a></li>
                      <li><a href="requesteditems">My Request</a></li>
                      <li><a href="inventory">Inventory</a></li>
                      <li><a href="requisition_remark">Approval</a></li> 
                    </ul>
                  </li>
                  <li><a><i class="fa fa-money"></i>Cash Request<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="make_request">Make Cash Request</a></li>
                      <li><a href="loan_request">Make Loan Request</a></li>
                      <li><a href="requestedcash">My Request</a></li>
                      
                      <?php if($_SESSION['user']['category'] == 'admin' || $_SESSION['user']['cash_processing_permission'] == '1') { ?>
                        <li><a href="all_cash_request">Company Requests</a></li>
                        <li><a href="cash_request_category">Cash Category</a></li>
                      <?php  } ?>
                     
                      <li><a href="manager_remark">Approval</a></li>
                     
                    </ul>
                  </li>
                  <?php if($_SESSION['user']['category'] == '' || $_SESSION['user']['create_voucher'] == '1') { ?>
                  <li><a><i class="fa fa-money"></i>Voucher<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                     <?php if($_SESSION['user']['role'] == 'md') {?> 
                      <li><a href="voucher_approval">View Voucher</a></li>
                      <?php } elseif ($_SESSION['user']['role'] == 'ed'){?>
                      <li><a href="view_voucher">View Voucher</a></li>
                      <?php }else{?>
                      <li><a href="create_voucher">Create Voucher</a></li>
                      <li><a href="view_voucher">View Voucher</a></li>
                      <li><a href="add_bank">Add Bank</a></li>
                      <li><a href="create_company">Add Company</a></li>
                      <?php }?>

                      
                     
                     
                      
                     
                    </ul>
                  </li>
                  <?php }?>
                  <li><a><i class="fa fa-edit"></i> Leave Management<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                     
                      <li><a href="leave_request.php">New Leave Request</a></li>
                  
                      <?php if($_SESSION['user']['category'] == 'admin' || $_SESSION['user']['leave_processing_permission'] == '1') { ?>
                      <li><a href="leave_type">Leave Tool</a></li>
                      <?php } ?>
                      <?php if($_SESSION['user']['category'] == 'admin') { ?>
                      <!--<li><a href="view_leave">View Leave</a></li>-->
                      <li><a href="view_proposed_leave">View Proposed Leave</a></li>
                      <?php } ?>
                      <li><a href="view_leave">Leave Request</a></li>
                      <li><a href="leave_remark">Approval</a></li>
                       <?php if($_SESSION['user']['category'] == 'staff' && $_SESSION['user']['position'] =='') { ?>
                       <li><a href="proposed_leave">Leave Planer</a></li>
                       <?php } ?>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-book"></i>Appraisal Management<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <?php if($_SESSION['user']['category'] == 'admin' || $_SESSION['user']['upload_appraisal'] == '1') {?>
                       <li><a href="create_appraisal">Create Appraisal</a></li>
                      <?php } ?>
                      <li><a href="appraisals">Appraisals</a></li>
                      <?php if($_SESSION['user']['category'] == 'staff') {?>
                       <li><a href="monitor_appraisal">My Completed Appraisals</a></li>
                      <?php } ?>
                      <?php if($_SESSION['user']['category'] == 'admin' || $_SESSION['user']['upload_appraisal'] == '1') {?>
                       <li><a href="staff_filled_appraisal">Company Appraisals</a></li>
                      <?php } ?>
                     
                      <li><a href="appraisal_remark">Approval</a></li>
                     
                    </ul>
                  </li>
                   <?php if($_SESSION['user']['category'] == 'staff') {?>
                   <li><a><i class="fa fa-book"></i>Talent Management<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                     
                       <li><a href="view_mycourses">View Course</a></li>
                    </ul>
                  </li>
                  <?php } ?>
                  <li><a><i class="fa fa-users"></i> Employee Information <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <?php if($_SESSION['user']['category'] == 'staff') {?>
                      <li><a href="qualification">Qualification</a></li>
                      <?php } ?>
                      <?php if($_SESSION['user']['category'] == 'admin' || $_SESSION['user']['add_employee_management'] == '1') {?>
                      <li><a href="open_portal">Open Portal</a></li>
                      <li><a href="employee_information">Employee Information</a></li>
                      <?php }?>
                      <?php if($_SESSION['user']['category'] == 'staff') {?>
                     <!--  <li><a href="personal_information">Personal Information</a></li>  --> 
                      <li><a href="workexperience">Work Experience</a></li>
                      <li><a href="certifications">Professional Certification</a></li>
                    <?php }?>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-desktop"></i>ID Request<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <?php if($_SESSION['user']['category'] == 'staff') {?>
                      <li><a href="request_idcard">New Request</a></li>
                      <?php } ?>
                      <li><a href="view_id_request_status">View Status</a></li>
                      <?php if($_SESSION['user']['category'] == 'admin' || $_SESSION['user']['id_card_permission'] == '1'){ ?>
                      <li><a href="view_all_id_request">View Request</a></li>
                     <?php } ?>
                    </ul>
                  </li>
                  <?php if($_SESSION['user']['category'] == 'staff'){?>
                  <li><a><i class="fa fa-reply"></i>Staff Exit<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="newexit.php">New Exit</a></li>
                      <?php if(in_array(strtolower($_SESSION['user']['position']), $all_approval)) {?>
                          <li><a href="exit_remark">Approval</a></li>
                      <?php } ?>
                    </ul>
                  </li>
                  <?php } ?>
                 
                  <li><a><i class="fa fa-exchange"></i>Approval flow<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <?php if($_SESSION['user']['category'] == 'admin' || $_SESSION['user']['add_employee_management'] == '1'){?>
                      <li><a href="flexible_approvals">Create flexible Approvals</a></li>  
                      <li><a href="assign_manager">Assign Approvals</a></li>
                      <li><a href="view_approvals">View Approvals</a></li>
                      <?php } ?>
                     
                    </ul>
                  </li>
                  <?php if($_SESSION['user']['category'] == 'admin' || $_SESSION['user']['add_employee_management'] == '1'){?>
                 

                  <li><a><i class="fa fa-user"></i>Staff Exit<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="exit.php">Exit</a></li>
                    </ul>
                  </li>
                  <?php } ?>
                  <li><a><i class="fa fa-user"></i>Staff Directory<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="staff_directory">Staff Directory</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-user"></i>Staff Audit<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="staff_audit">Staff Audit</a></li>
                      <?php if($_SESSION['user']['category'] == 'admin') {?>
                      <li><a href="audit">Audit</a></li>  
                      <li><a href="begin_audit">Begin Audit</a></li>
                      <?php  } ?>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-info"></i>KSS Dashboard<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="kss">KSS</a></li>
                      <li><a href="share_knowledge">Share knowledge</a></li>
                    </ul>
                  </li>
                </ul>
              </div>

               <?php if($_SESSION['user']['category'] == 'admin' || $_SESSION['user']['add_employee_management'] == '1'){?>
              <div class="menu_section">
                <h3>Company Settings</h3>
                <ul class="nav side-menu">
                   <li><a><i class="fa fa-user"></i>Staff Onboarding<span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="onboarding.php">On-board Employee</a></li>
                      <li><a href="editstaffinfo">Edit Employee</a></li>
                      <li><a href="add_grade">Grade</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-building"></i> Department <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="department.php">Department List</a></li>
                      <li><a href="adddepartment.php">Add Department</a></li>
                    </ul>
                  </li>
                <!--  <li><a><i class="fa fa-book"></i> Branch <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="branch.php">Branch List</a></li>
                      <li><a href="addbranch.php">Add Branch</a></li>
                    </ul>
                  </li>
                   <li><a><i class="fa fa-book"></i> Level <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="level.php">Level List</a></li>
                      <li><a href="addlevel.php">Add Level</a></li>
                    </ul>
                  </li>-->
                </ul>
              </div>
             <?php } ?>
               <?php if($_SESSION['user']['category'] == 'admin' || $_SESSION['user']['add_talent_management'] == '1') {?>
              <div class="menu_section">
                <h3>Talent Management</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-book"></i> Courses <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                       <li><a href="create_course">Create Course</a></li>
                       <li><a href="view_courses">View Course</a></li>
                       <li><a href="assign_courses">Assign Course</a></li>
                       <li><a href="assigned_courses">View Assigned Courses</a></li>
                    </ul>
                  </li>
                </ul>
              </div>
             <?php } ?>

              <?php if($_SESSION['user']['category'] == 'admin' || $_SESSION['user']['add_permission_management'] == '1') {?>
              <div class="menu_section">
                <h3>Permission</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-key"></i> Permission <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="permission">Grant Permission</a></li>
                      <li><a href="access">Access</a></li>
                    </ul>
                  </li>
                </ul>
              </div>
             <?php } ?>
              <!-- <?php if($_SESSION['user']['category'] == 'admin' || $_SESSION['user']['payroll_permission'] =='1') {?>
              <div class="menu_section">
                <h3>Payroll</h3>
                <ul class="nav side-menu">
                  <li><a><i class="fa fa-bug"></i> Administration <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="branch">Manage Branch</a></li>
                      <li><a href="department">Manage Department</a></li>
                     
                    </ul>
                  </li>
                  <li><a><i class="fa fa-user"></i> Employee <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="employee">Add Employee</a></li>
                      <li><a href="masterlist">MasterList</a></li>
                    </ul>
                  </li>
                </ul>
              </div>
             <?php } ?> -->
            </div>
            <!-- /sidebar menu -->                     
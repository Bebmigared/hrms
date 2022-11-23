<?php 
include 'connection.php';
session_start();
$kss = [];
$admin_id;
$users = [];
$show_month = "";
$show_year = "";
$ID = "";
$audited = 'false';
$audit_begin = 'false';
$id_permission = [];
$leave_permission = [];
$payroll_permission = [];
$requisition = [];
$appraisal = [];
$permission = [];
$talent = [];
$employeemanagment = [];
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
 if($_SESSION['user']['category'] == 'staff') $admin_id = $_SESSION['user']['admin_id'];
 else if ($_SESSION['user']['category'] == 'admin') $admin_id = $_SESSION['user']['id'];
  $query = "SELECT kss.information,users.name,users.employee_ID FROM kss INNER JOIN users ON kss.staff_id = users.id WHERE kss.admin_id = '".$admin_id."'ORDER BY kss.id LIMIT 1";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $kss[] = $row;
      }
  }
  $query = "SELECT * FROM staff_audit WHERE admin_id = '".$_SESSION['user']['admin_id']."' ORDER BY id DESC LIMIT 1";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0) {
      while($row = mysqli_fetch_assoc($result)) {
        $month = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEPT','OCT','NOV','DEC'];
        $now_month = (int)date('m') - 1;
        $this_month = $month[$now_month];
        echo $row['month'];
        if($this_month == $row['month'] && $row['year'] == date('Y')){
          $audit_begin = 'true'; $show_month = $row['month'];
          $show_year = $row['year'];
          $ID = $row['id'];
        } 
        $users[] = $row;
      }
  }
  $query = "SELECT * FROM users WHERE companyId = '".$_SESSION['user']['companyId']."' AND id_card_permission ='1'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0) {
      while($row = mysqli_fetch_assoc($result)) {
          $id_permission[] = $row;
        } 
  }
  $query = "SELECT * FROM users WHERE companyId = '".$_SESSION['user']['companyId']."' AND leave_processing_permission ='1'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0) {
      while($row = mysqli_fetch_assoc($result)) {
          $leave_permission[] = $row;
        } 
  }
  $query = "SELECT * FROM users WHERE companyId = '".$_SESSION['user']['companyId']."' AND payroll_permission ='1'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0) {
      while($row = mysqli_fetch_assoc($result)) {
          $payroll_permission[] = $row;
        } 
  }

  $query = "SELECT * FROM users WHERE companyId = '".$_SESSION['user']['companyId']."' AND add_item_permission ='1'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0) {
      while($row = mysqli_fetch_assoc($result)) {
          $requisition[] = $row;
        } 
  }
  
  $query = "SELECT * FROM users WHERE companyId = '".$_SESSION['user']['companyId']."' AND cash_processing_permission ='1'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0) {
      while($row = mysqli_fetch_assoc($result)) {
          $cashreq[] = $row;
        } 
  }

  $query = "SELECT * FROM users WHERE companyId = '".$_SESSION['user']['companyId']."' AND upload_appraisal ='1'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0) {
      while($row = mysqli_fetch_assoc($result)) {
          $appraisal[] = $row;
        } 
  }

  $query = "SELECT * FROM users WHERE companyId = '".$_SESSION['user']['companyId']."' AND add_employee_management ='1'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0) {
      while($row = mysqli_fetch_assoc($result)) {
          $employeemanagment[] = $row;
        } 
  }
  $query = "SELECT * FROM users WHERE companyId = '".$_SESSION['user']['companyId']."' AND add_permission_management ='1'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0) {
      while($row = mysqli_fetch_assoc($result)) {
          $permission[] = $row;
        } 
  }

  $query = "SELECT * FROM company WHERE id = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
      }
  }

  $query = "SELECT * FROM users WHERE companyId = '".$_SESSION['user']['companyId']."' AND add_talent_management ='1'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0) {
      while($row = mysqli_fetch_assoc($result)) {
          $talent[] = $row;
        } 
  }


  //print_r($audit_begin);
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
                <h3>GRANTED PERMISSION</h3>
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
            <ul class="nav navbar-left panel_toolbox">
                      <li>
                      <!--form action="process_file.php" method="post"-->
                            
                            <button type="submit" id="btnExport"
                                name='export' value=""
                                class="btn btn-danger" data-toggle="modal" data-target="#addModal">Remove Access</button>
                        <!--/form-->
                      </li>
                    </ul>
            <div class="clearfix"></div>

            <div class="row">
               <?php if(isset($_SESSION['msg'])) {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                  <?php } ?>           
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Manage Inventory</h2>
                     
                    <div class="clearfix"></div>
                    <p>Access to Add Item or fill Items in Inventory</p>
                  </div>
                  <div class="x_content">
                    <div class="table-responsive">
                      <?php if(count($requisition) > 0) {?>
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N</th>
                            <th class="column-title">Name</th>
                            <th class="column-title">Employee ID </th>
                            <th class="column-title">Department</th>
                            <th class="column-title">Option</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($requisition); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$requisition[$h]['name']?> <?=$requisition[$h]['fname']?></td>
                            <td class=" "><?=$requisition[$h]['employee_ID']?></td>
                            <td class=" "><?=$requisition[$h]['department']?></td>
                            <td onclick = "deleteitem(<?=$requisition[$h]['id']?>,'add_item_permission')"><span style="color: red; font-size:20px" class="fa fa-close"></span></td>
                           <?php }?>
                        </tbody>
                      </table>
                      <?php }else {?>
                         No user with this permission
                      <?php } ?>  
                    </div>
                  </div>
                </div>
                
                 <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Manage Cash Requests</h2>
                     
                    <div class="clearfix"></div>
                    <p>Access Cash Requests</p>
                  </div>
                  <div class="x_content">
                    <div class="table-responsive">
                      <?php if(count($requisition) > 0) {?>
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N</th>
                            <th class="column-title">Name</th>
                            <th class="column-title">Employee ID </th>
                            <th class="column-title">Department</th>
                            <th class="column-title">Option</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($cashreq); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$cashreq[$h]['name']?> <?=$cashreq[$h]['fname']?></td>
                            <td class=" "><?=$cashreq[$h]['employee_ID']?></td>
                            <td class=" "><?=$cashreq[$h]['department']?></td>
                            <td onclick = "deleteitem(<?=$cashreq[$h]['id']?>,'cash_processing_permission')"><span style="color: red; font-size:20px" class="fa fa-close"></span></td>
                           <?php }?>
                        </tbody>
                      </table>
                      <?php }else {?>
                         No user with this permission
                      <?php } ?>  
                    </div>
                  </div>
                </div>
                
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Talent Management</h2>
                     
                    <div class="clearfix"></div>
                    <p>Access to Create Courses, Set Exams and Assign Courses to Employees</p>
                  </div>
                  <div class="x_content">
                    <div class="table-responsive">
                      <?php if(count($talent) > 0) {?>
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N</th>
                            <th class="column-title">Name</th>
                            <th class="column-title">Employee ID </th>
                            <th class="column-title">Department</th>
                            <th class="column-title">Option</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($talent); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$talent[$h]['name']?> <?=$talent[$h]['fname']?></td>
                            <td class=" "><?=$talent[$h]['employee_ID']?></td>
                            <td class=" "><?=$talent[$h]['department']?></td>
                            <td onclick = "deleteitem(<?=$talent[$h]['id']?>,'add_item_permission')"><span style="color: red; font-size:20px" class="fa fa-close"></span></td>
                           <?php }?>
                        </tbody>
                      </table>
                      <?php }else {?>
                         No user with this permission
                      <?php } ?>  
                    </div>
                  </div>
                </div>
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Manage Permission</h2>
                    <div class="clearfix"></div>
                    <p>Ability to Grant access to other Employees on HRCORE </p>
                  </div>
                  <div class="x_content">
                    <div class="table-responsive">
                      <?php if(count($permission) > 0) {?>
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N</th>
                            <th class="column-title">Name</th>
                            <th class="column-title">Employee ID </th>
                            <th class="column-title">Department</th>
                            <td class="column-title">Option</td>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($permission); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$permission[$h]['name']?></td>
                            <td class=" "><?=$permission[$h]['employee_ID']?></td>
                            <td class=" "><?=$permission[$h]['department']?></td>
                            <td onclick = "deleteitem(<?=$permission[$h]['id']?>,'add_permission_management')"><span style="color: red; font-size:20px" class="fa fa-close"></span></td>
                           <?php }?>
                        </tbody>
                      </table>
                      <?php }else {?>
                         No user with this permission
                      <?php } ?>  
                    </div>
                  </div>
                </div>
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Manage Employee Information</h2>
                    <div class="clearfix"></div>
                    <p>Ability to on board new user or manage employe information </p>
                  </div>
                  <div class="x_content">
                    <div class="table-responsive">
                      <?php if(count($employeemanagment) > 0) {?>
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N</th>
                            <th class="column-title">Name</th>
                            <th class="column-title">Employee ID </th>
                            <th class="column-title">Department</th>
                            <td class="column-title">Option</td>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($employeemanagment); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$employeemanagment[$h]['name']?></td>
                            <td class=" "><?=$employeemanagment[$h]['employee_ID']?></td>
                            <td class=" "><?=$employeemanagment[$h]['department']?></td>
                            <td onclick = "deleteitem(<?=$employeemanagment[$h]['id']?>,'add_employee_management')"><span style="color: red; font-size:20px" class="fa fa-close"></span></td>
                           <?php }?>
                        </tbody>
                      </table>
                      <?php }else {?>
                         No user with this permission
                      <?php } ?>  
                    </div>
                  </div>
                </div>
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Upload Appraisal</h2>
                    <div class="clearfix"></div>
                    <p>Ability to Create Appraisal on the Platform</p>
                  </div>
                  <div class="x_content">
                    <div class="table-responsive">
                      <?php if(count($appraisal) > 0) {?>
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N</th>
                            <th class="column-title">Name</th>
                            <th class="column-title">Employee ID </th>
                            <th class="column-title">Department</th>
                            <td class="column-title">Option</td>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($appraisal); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$appraisal[$h]['name']?></td>
                            <td class=" "><?=$appraisal[$h]['employee_ID']?></td>
                            <td class=" "><?=$appraisal[$h]['department']?></td>
                            <td onclick = "deleteitem(<?=$appraisal[$h]['id']?>,'upload_appraisal')"><span style="color: red; font-size:20px" class="fa fa-close"></span></td>
                           <?php }?>
                        </tbody>
                      </table>
                      <?php }else {?>
                         No user with this permission
                      <?php } ?>  
                    </div>
                  </div>
                </div>
                <div class="x_panel">
                  <div class="x_title">
                    <h2>ID Card Processing</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="table-responsive">
                      <?php if(count($id_permission) > 0) {?>
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N</th>
                            <th class="column-title">Name</th>
                            <th class="column-title">Employee ID </th>
                            <th class="column-title">Department</th>
                            <td class="column-title">Option</td>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($id_permission); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$id_permission[$h]['name']?></td>
                            <td class=" "><?=$id_permission[$h]['employee_ID']?></td>
                            <td class=" "><?=$id_permission[$h]['department']?></td>
                            <td onclick = "deleteitem(<?=$id_permission[$h]['id']?>,'id_card_permission')"><span style="color: red; font-size:20px" class="fa fa-close"></span></td>
                           <?php }?>
                        </tbody>
                      </table>
                      <?php }else {?>
                         No user with this permission
                      <?php } ?>  
                    </div>
                  </div>
                </div>
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Leave Management Processing</h2>

                    <div class="clearfix"></div>
                    <p>Access to set leave type (such as annual leave, sick leave etc)</p>
                  </div>
                  <div class="x_content">
                    <div class="table-responsive">
                      <?php if(count($leave_permission) > 0) {?>
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N</th>
                            <th class="column-title">Name</th>
                            <th class="column-title">Employee ID </th>
                            <th class="column-title">Department</th>
                            <td class="column-title">Option</td>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($leave_permission); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$leave_permission[$h]['name']?></td>
                            <td class=" "><?=$leave_permission[$h]['employee_ID']?></td>
                            <td class=" "><?=$leave_permission[$h]['department']?></td>
                            <td onclick = "deleteitem(<?=$leave_permission[$h]['id']?>,'leave_processing_permission')"><span style="color: red; font-size:20px" class="fa fa-close"></span></td>
                           <?php }?>
                        </tbody>
                      </table>
                      <?php }else {?>
                         No user with this permission
                      <?php } ?>  
                    </div>
                  </div>
                </div>
               <!--  <div class="x_panel">
                  <div class="x_title">
                    <h2>Payroll Processing</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="table-responsive">
                      <?php if(count($payroll_permission) > 0) {?>
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N</th>
                            <th class="column-title">Name</th>
                            <th class="column-title">Employee ID </th>
                            <th class="column-title">Department</th>
                            <td class="column-title">Option</td>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($payroll_permission); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$payroll_permission[$h]['name']?></td>
                            <td class=" "><?=$payroll_permission[$h]['employee_ID']?></td>
                            <td class=" "><?=$payroll_permission[$h]['department']?></td>
                           <?php }?>
                        </tbody>
                      </table>
                      <?php }else {?>
                         No user with this permission
                      <?php } ?>  
                    </div>
                  </div>
                </div> -->
              </div>
             <!--  <div class="col-md-4 col-sm-12 col-xs-12">
                  <div class="x_panel">
                  <div class="x_title">
                    <h2>KSS shared by (<span style="font-size: 13px;"><?=isset($kss[0]['name']) ? $kss[0]['name'] : ''?></span>)</h2>
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
              </div> -->
              <div class="col-md-4 col-sm-12 col-xs-12">
                
              </div>
            </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h2 class="modal-title" id="exampleModalLabel">Remove Access from Department</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                 <form action="process_permission.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Select Department 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                               <select class="form-control" name ="department">
                                <option value = ""></option>
                               <?php if(count($data) > 0) {?>
                                    <?php $dept = explode(";",$data[0]['department']) ?>
                                    <?php for ($r = 0; $r < count($dept); $r++){ ?>
                                      <?php if($dept[$r] != "") {?>
                                      <option value = "<?=$dept[$r]?>"><?=$dept[$r]?></option>
                                <?php } } }?> 
                                </select>
                            </div>
                            
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="address">Select Access 
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                               <select class="form-control" name ="access">
                                <option value = ""></option>
                                <option value="add_item_permission"> Manage Inventory</option>
                                <option value="add_permission_management"> Manage Permission</option>
                                <option value="add_employee_management"> Manage Employee Information</option>
                                <option value="upload_appraisal">Upload Appraisal</option>
                                <option value="leave_processing_permission">Leave Management</option>
                                <option value="id_card_permission">ID Card Processing</option>
                                <option value="add_talent_management">Talent Management</option>
                                </select>
                            </div>
                            
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3"> 
                            <button type="submit" name="remove_permission" class="btn btn-success">Submit</button>
                          </div>
                        </form>
              </div>
            </div>
          </div>
        </div>
<?php include "footer.php"?>
<script type="text/javascript" src="js/appraisal.js"></script>
<script type="text/javascript">
   function deleteitem(id, access){
      if(confirm(`Do you really want to withdraw this Permission from user`))
      {
          window.location.href = `process_permission.php?id=${id}&access=${access}`;
      }else{

      }
   }

</script>
        
 
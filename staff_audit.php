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
   $query = "SELECT * FROM staff_audit_replies WHERE admin_id = '".$_SESSION['user']['admin_id']."' AND audit_id = '".$ID."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
        $audited = 'true';
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
                <h3>STAFF AUDIT</h3>
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
              <?php if($audit_begin == 'true') {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            Staff audit has begun, kindly audit yourself.
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                  <?php } ?> 
               <?php if($audited == 'true') {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            You have audited yourself.
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                  <?php } ?>           
              <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Audit</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form action="process_staff_audit.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
        
                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Audit Month <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" class="form-control" name="month" value="<?=$show_month?>" readonly="true">
                                </div>
                              </div>
                              <div class="form-group">
                                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Audit year <span class="required">*</span>
                                      </label>
                                      <div class="col-md-6 col-sm-6 col-xs-12">
                                          <input type="text" class="form-control" name="year" value="<?=$show_year?>" readonly="true">
                                      </div>
                              </div>
                              <div class="form-group" style="display: none;">
                                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">ID
                                      </label>
                                      <div class="col-md-6 col-sm-6 col-xs-12">
                                          <input type="text" class="form-control" name="audit_id" value="<?=$ID?>" readonly="true">
                                      </div>
                              </div>
                              <?php if ($audit_begin == true && $audited == false)  { ?>
                              <div class="ln_solid"></div>
                              <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                  <!--button class="btn btn-primary" type="button">Cancel</button>
                                  <button class="btn btn-primary" type="reset">Reset</button-->
                                  <button type="submit" name="audit" class="btn btn-success">Submit</button>
                                </div>
                              </div>
                              <?php  } ?>
                            </form>
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
        

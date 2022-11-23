<?php 
include 'connection.php';
session_start();
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
if(isset($_POST['change_password'])){
    $passwords = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    $oldpassword = mysqli_real_escape_string($conn, $_POST['oldpassword']);
    $verify = password_verify($oldpassword,$_SESSION['user']['password']);
    if($verify){
        if($passwords != $cpassword){
           $_SESSION['msg'] = 'Password does not Match';
        }else{
            $password = password_hash($passwords,PASSWORD_DEFAULT);
            $ccpassword = $passwords;
            $sql = "Update users set password = '".$password."', cpassword = '".$ccpassword."'  WHERE employee_ID = '".$_SESSION['user']['employee_ID']."'";
            if(mysqli_query($conn,$sql)){
                $_SESSION['msg'] = 'Password Changed Successfully';
            }else{
                echo "Error updating record: " .mysqli_error($conn);
            }
        }
    }else {
        $_SESSION['msg'] = "Old password is Invalid, Please enter the correct information";
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
                <h3>RESET PASSWORD</h3>
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
                        <div class="alert alert-primary" style="background-color: #007bff;font-size:16px;color:#fff" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                  <?php } ?> 
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>RESET PASSWORD</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form action="change_password.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
        
        
                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Old Password <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="password" class="form-control" name="oldpassword" value="">
                                </div>
                              </div>
                              
                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">New Password <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="password" class="form-control" name="password" value="">
                                </div>
                              </div>
                              
                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Confirm Password <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="password" class="form-control" name="cpassword" value="">
                                </div>
                              </div>
                              <div class="ln_solid"></div>
                              <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                  <button type="submit" name="change_password" class="btn btn-success">Submit</button>
                                </div>
                              </div>
                            </form>
                  </div>
                </div>
              </div>
              <!--div class="col-md-4 col-sm-12 col-xs-12">
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
              </div-->
              <div class="col-md-4 col-sm-12 col-xs-12">
                
              </div>
            </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
<?php include "footer.php"?>
<script type="text/javascript" src="js/appraisal.js"></script>
        

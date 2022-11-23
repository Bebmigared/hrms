<?php 
include 'connection.php';
include "process_email.php";
session_start();
if(isset($_POST['update_request'])){
    $hr_outstandingissues = mysqli_real_escape_string($conn, $_POST['hr_outstandingissues']);
    $hr_yes_outstandingissues = mysqli_real_escape_string($conn, $_POST['hr_yes_outstandingissues']);
    $hr_allowance = mysqli_real_escape_string($conn, $_POST['hr_allowance']);
    $sql = "UPDATE users SET hr_outstandingissues = '".$hr_outstandingissues."', hr_yes_outstandingissues = '".$hr_yes_outstandingissues."', hr_allowance = '".$hr_allowance."'";
    if(mysqli_query($conn,$sql)){
        $_SESSION['msg'] = 'Exit Request Updated';
    }
}
if(isset($_POST['submit'])){
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $date_exited = mysqli_real_escape_string($conn, $_POST['date_exited']);
    $date_employed = mysqli_real_escape_string($conn, $_POST['date_employed']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $outstandingallowance = mysqli_real_escape_string($conn, $_POST['outstandingallowance']);
    $obligation = mysqli_real_escape_string($conn, $_POST['obligation']);
    $yes_obligation = mysqli_real_escape_string($conn, $_POST['yes_obligation']);
    $extentissuesolved = mysqli_real_escape_string($conn, $_POST['extentissuesolved']);
    $repaymentplan = mysqli_real_escape_string($conn, $_POST['repaymentplan']);
    $outstandingissues = mysqli_real_escape_string($conn, $_POST['outstandingissues']);
    $if_outstanding_issues = mysqli_real_escape_string($conn, $_POST['if_outstanding_issues']);
    $propertiescare = mysqli_real_escape_string($conn, $_POST['propertiescare']);
    $planaboutresolvingissue = mysqli_real_escape_string($conn, $_POST['planaboutresolvingissue']);
    $approvals = explode(";",$_SESSION['user']['exit_flow']);
    $get_first_approval_details = explode(":",$approvals[0]);
    if(count($get_first_approval_details) > 1) $get_first_approval_email = $get_first_approval_details[1];
    $sql = "UPDATE users SET staff_exit = 'yes', exit_date = '".$date_exited."', correspondence_address = '".$address."', date_employed = '".$date_employed."',reason_exit = '".$reason."', how_outstanding_to_be_paid = '".$outstandingallowance."', do_you_have_outstanding_obligation = '".$obligation."', yes_obligation='".$yes_obligation."', repayment_plan = '".$repaymentplan."', outstanding_issue = '".$outstandingissues."', if_outstanding_issue = '".$if_outstanding_issues."', extent_issue_resolved = '".$extentissuesolved."', plan_about_resolving_issue = '".$planaboutresolvingissue."', have_you_submitted_company_ppties = '".$propertiescare."' WHERE id = '".$_SESSION['user']['id']."'";
    if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = 'Exit Report is Sent to your Managers';
            //echo $msg; 
            /*$sendmsg = "<div><p>Good Day,</p><p>".$_SESSION['user']['name']." has completed the exit form. As the ".$get_first_approval_details[0].", kindly log In and take the neccessary action.</p><p><a  style ='padding:7px;color:#fff;background-color:#4e73df;margin:10px;border-radius: 3px;text-decoration:none' href = 'http://www.hrcore.ng'>Log In to view</a></p></div>";
               if (filter_var($get_first_approval_email, FILTER_VALIDATE_EMAIL)) {
                  process_data($conn,$get_first_approval_email,$sendmsg,'Exit Request');
                }*/
          }else {
              $_SESSION['msg'] = 'Error updating data';
              echo mysqli_error($conn);
          }
}
$users = [];
//echo 'aaaaa';
//echo $_SESSION['exit_id'];
if(isset($_SESSION['exit_id'])){
    $sql = "SELECT * from users WHERE id = '".$_SESSION['exit_id']."'";
    $result = mysqli_query($conn,$sql);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $users[] = $row;
        }
    }
}

?>
<?php include "header.php"?>
<div class="right_col" role="main">
<div class="">
        <div class="page-title">
          <div class="title_left">
            <h3>New Exit</h3>
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
                        <div class="alert alert-primary" style="background-color: blue;font-size:14px;color:#fff" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                  <?php } ?>
            <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Exit</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <br />
                      <form action="newexit.php" method="POST" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
  
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Correspondence Address After Exit <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <textarea class="form-control col-md-7 col-xs-12" name="address" rows="4"><?=isset($users[0]['correspondence_address']) ?$users[0]['correspondence_address']:'' ?></textarea>
                          </div>
                        </div>
                        <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Date Employed<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="date" name="date_employed" class="form-control col-md-7 col-xs-12" required="required" value="<?=isset($users[0]['date_employed']) ?$users[0]['date_employed']:''?>" type="date">
                                </div>
                        </div>
                        <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Date Exited<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type='date' value="<?=isset($users[0]['exit_date']) ?$users[0]['exit_date']:'' ?>" name='date_exited' class='form-control'/>
                                </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Reason for Exit <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <textarea class="form-control col-md-7 col-xs-12" name="reason" rows="4"><?=isset($users[0]['reason_exit']) ?$users[0]['reason_exit']:'' ?></textarea>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">How do you want your outstanding allowance to be paid? <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <textarea class="form-control col-md-7 col-xs-12" name="outstandingallowance" rows="4"><?=isset($users[0]['how_outstanding_to_be_paid']) ?$users[0]['how_outstanding_to_be_paid']:'' ?></textarea>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Do you have any outstanding Obligation <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <select name="obligation" class='form-control'>
                                  <option value="<?=isset($users[0]['do_you_have_outstanding_obligation']) ?$users[0]['do_you_have_outstanding_obligation']:'' ?>"><?=isset($users[0]['do_you_have_outstanding_obligation']) ?$users[0]['do_you_have_outstanding_obligation']:'' ?></option>
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                              </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">If Yes, state <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <textarea class="form-control col-md-7 col-xs-12" name="yes_obligation" rows="4"><?=isset($users[0]['yes_obligation']) ?$users[0]['yes_obligation']:'' ?></textarea>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">When is your repayment plan <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <textarea class="form-control col-md-7 col-xs-12" name="repaymentplan" rows="4"><?=isset($users[0]['repayment_plan']) ?$users[0]['repayment_plan']:'' ?></textarea>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Do you have any outstanding Issues? <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <select name="outstandingissues" class='form-control'>
                                  <option value="<?=isset($users[0]['outstanding_issue']) ?$users[0]['outstanding_issue']:'' ?>"><?=isset($users[0]['outstanding_issue']) ?$users[0]['outstanding_issue']:'' ?></option>
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                              </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">If Yes, state <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <textarea class="form-control col-md-7 col-xs-12" name="if_outstanding_issues" rows="4"><?=isset($users[0]['if_outstanding_issue']) ?$users[0]['if_outstanding_issue']:'' ?></textarea>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">To what extent has the issue been solved? <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <textarea class="form-control col-md-7 col-xs-12" name="extentissuesolved" rows="4"><?=isset($users[0]['extent_issue_resolved']) ?$users[0]['extent_issue_resolved']:'' ?></textarea>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">What is your plan about resolving the issue? <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <textarea class="form-control col-md-7 col-xs-12" name="planaboutresolvingissue" rows="4"><?=isset($users[0]['plan_about_resolving_issue']) ?$users[0]['plan_about_resolving_issue']:'' ?></textarea>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Have you submitted all the Company's properties in your Care? <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <select name="propertiescare" class='form-control'>
                                  <option value="<?=isset($users[0]['have_you_submitted_company_ppties']) ?$users[0]['have_you_submitted_company_ppties']:'' ?>"><?=isset($users[0]['have_you_submitted_company_ppties']) ?$users[0]['have_you_submitted_company_ppties']:'' ?></option>
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                              </select>
                          </div>
                        </div>
                        <!--div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Upload Signature <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type='file' name='employee_signature' class ="form-control"/>
                          </div>
                        </div-->
                        <?php if($_SESSION['user']['category'] == 'admin') { ?>
                        <div style='margin-top:30px;font-size:16px;font-weight:bold'>Line Manager</div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Does he/she have any outstanding Issues  <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <select name="hr_outstandingissues" class='form-control'>
                                  <option value="<?=isset($users[0]['hr_outstandingissues']) ?$users[0]['hr_outstandingissues']:'' ?>"><?=isset($users[0]['hr_outstandingissues']) ?$users[0]['hr_outstandingissues']:'' ?></option>
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                              </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">If Yes, State <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <textarea class="form-control col-md-7 col-xs-12" name="hr_yes_outstandingissues" rows="4"><?=isset($users[0]['hr_yes_outstandingissues']) ?$users[0]['hr_yes_outstandingissues']:'' ?></textarea>
                          </div>
                        </div>
                         <div style='margin-top:30px;font-size:16px;font-weight:bold'>Human Resources Manager</div>
                         <div class="ln_solid"></div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">The Staff can be paid his/her outstanding allowance <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <select name="hr_allowance" class='form-control'>
                                  <option value="<?=isset($users[0]['hr_allowance']) ?$users[0]['hr_allowance']:'' ?>"><?=isset($users[0]['hr_allowance']) ?$users[0]['hr_allowance']:'' ?></option>
                                  <option value="Yes">Yes</option>
                                  <option value="No">No</option>
                              </select>
                          </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="submit" name="update_request" class="btn btn-success">Update Exit Request</button>
                          </div>
                        </div>
                         <?php } ?>
                        <?php if(!isset($_SESSION['exit_id'])) { ?>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="submit" name="submit" class="btn btn-success">Submit</button>
                          </div>
                        </div>
                        <?php } ?>
                      </form>
                    </div>
                  </div>
                </div>
        </div>
</div>
</div>
<?php include "footer.php"?>
        

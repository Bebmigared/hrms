<?php 
include 'connection.php';
session_start();

$flexible = '';
 $query = "SELECT * FROM approval_settings WHERE companyId = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $flexible = $row['status'];
      }
  }

if(isset($_POST['submit']))
{

  $query = "DELETE FROm approval_settings WHERE companyId = '".$_SESSION['user']['companyId']."'";
  mysqli_query($conn, $query);


  $status = $_POST['flexible'];
   $sql = "INSERT INTO approval_settings (status,createdby, companyId, date_created)
          VALUES ('".$status."', '".$_SESSION['user']['id']."',  '".$_SESSION['user']['companyId']."', '".date('Y-m-d')."')";
    if (mysqli_query($conn, $sql)) {
      $_SESSION['msg'] = "Approval Settings Updated";
      $_SESSION['approval_settings'] = $status;
    }else {
       $_SESSION['msg'] = "Error while update account, please try again later";
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
                <h3>FLEXIBLE APPROVAL FLOW</h3>
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
                    <h2>APPROVAL FLOW STATUS</h2>

                   
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div>Flexible approval Status: <?=($flexible == '' || $flexible == '0') ? 'Rigid Approval flow' : 'User Can select request approvals' ?></div>
                  </div>
                </div>
                <div class="x_panel">
                  <div class="x_title">
                    <h2>ASSIGN APPROVAL FLOW</h2>

                   
                    <div class="clearfix"></div>
                     <div>Flexible approval allows employee to set approval flow for a request(such as leave request, requisition request etc)</div>
                  </div>
                  <div class="x_content">
                    <form action="flexible_approvals.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                              
                             
                               <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Allow flexible approvals <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="flexible" class="form-control" required="true">
                                       <option value=""></option>
                                       <option value="1">Yes</option>
                                       <option value="0">No</option>
                                    </select>
                                </div>
                              </div>
                             
                              <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                  <button type="submit" name="submit" class="btn btn-success">Submit</button>
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
        

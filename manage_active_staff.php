<?php 
include 'connectionpdo.php';
session_start();

$stmt = $pdo->prepare("SELECT * FROM staff_list WHERE admin_id = ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$_SESSION['user']['id']]); 
$done = $stmt->fetch();

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
                <h3>MANAGE STAFF LIST</h3>
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
              <?php if(isset($done['id']) && $done['done'] == '1') {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            Your Last request is Done
                        </div>
                  <?php unset($_SESSION['msg']); ?>     
                  <?php  } ?> 
                <?php if(isset($_SESSION['invalid'])) {?>
                        <div class="alert alert-primary" style="background-color: #d9534f;color: #fff" role="alert">
                            <?=$_SESSION['invalid']?>
                        </div>
                  <?php unset($_SESSION['invalid']); ?>     
                  <?php  } ?>    
               <?php if(isset($done['id']) && $done['done'] != '1') {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            <?=isset($_SESSION['msg']) ? $_SESSION['msg'] : 'Continue Update of Staff List. '.$done['currentNumber'].' Staff Updated thus far'?>
                            <?php if(isset($done['id']) && $done['done'] != '1') {?>
                              <form action = "process_staff_list.php" method="POST">
                               <div><button type="submit" name = "continue" class="btn btn-success">Continue Process</button></div>
                              </form>  
                            <?php } ?>
                        </div>
                  <?php unset($_SESSION['msg']); ?>     
                  <?php  } ?> 
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            This request might take a while even hours before completion....While this process is On All your staff will be deactivated. Any format outside the allowed excel file format will not be processed.
                            <div><a href = "downloadfile.php/?to=long&filename=format.xlsx" class="btn btn-success">Download Excel Format</a></div>
                        </div>
                       
              <?php if((isset($done['id']) && $done['done'] == '1') || !isset($done['id'])) {?>  
              <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Manage Staff</h2></h2>
                    <div class="clearfix"></div>
                  </div>
                  
                  <div class="x_content">
                    <form action = "process_staff_list.php" method="POST" id="demo-form2" enctype="multipart/form-data" data-parsley-validate class="form-horizontal form-label-left">
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">Upload Staff List <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="file" id="first-name" name = "list" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>
                          
                         
                         
                          <div class="ln_solid"></div>
                             <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="submit" name = "submit" class="btn btn-success">Submit</button>
                            
                          </div>
                         
                        </form>
                         </div>
                   
                  </div>
                </div>
              </div>
              <?php  } ?>
              <div class="col-md-4 col-sm-12 col-xs-12">
                
              </div>
            </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
<?php include "footer.php"?>
<script type="text/javascript" src="js/appraisal.js"></script>
        

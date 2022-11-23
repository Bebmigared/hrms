<?php 
include 'connection.php';
session_start();
if(!isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == '')
  header("Location:login");
$dept = [];
$info_id = "";
  //$query = "SELECT * FROM company WHERE id = '".$_SESSION['user']['companyId']."'";
  $query = "SELECT * FROM departments";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        //$dept = explode(";",$row['department']);
        $dept = $row['dept'];
        
      }
  }
  $sql = "SELECT * FROM open_information_portal WHERE companyId = '".$_SESSION['user']['companyId']."'";
  $sql_result = mysqli_query($conn, $sql);
  if(mysqli_num_rows($sql_result)> 0){
      $row = mysqli_fetch_assoc($sql_result);
      $info_id = $row['id'];
  }
?>
<?php include "header.php"?>
<div class="right_col" role="main">
<div class="">
        <div class="page-title">
          <div class="title_left">
            <h3>Employee Portal</h3>
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
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Open Employee Information System</h2>
                      <ul class="nav navbar-right panel_toolbox">
                        
                        
                      </ul>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <br />
                      <form action="process_open_portal.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
  
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Opening Date <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" name="opening_date" class="form-control col-md-7 col-xs-12" id=""/>
                          </div>
                        </div>
                        <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Closing Date <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input id="" class="date-picker form-control col-md-7 col-xs-12" name="closing_date" required="required" type="date">
                                </div>
                        </div>
                        <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Open for <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="open_for" class="form-control col-md-7 col-xs-12" id="">
                                        <option value="all">All</option> 
                                        <?php for ($f = 0; $f < count($dept); $f++) {?>
                                          <option value="<?=$dept[$f]?>"><?=$dept[$f]?></option> 
                                        <?php } ?>   
                                    </select>
                                </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                          <input type="text" value="<?=$info_id?>" name="info_id" style="display: none;">
                          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <button type="submit" name="submit" class="btn btn-success">Submit</button>
                          </div>
                        </div>
  
                      </form>
                    </div>
                  </div>
                </div>
              </div>
</div>
</div>
<?php include "footer.php"?>
        

        

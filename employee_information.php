<?php 
include 'connection.php';
 session_start();
 $msg = '';
 $info = [];
if(!isset($_SESSION['user']['id']) || $_SESSION['user']['id'] == '') header("Location:login.php");
$query = "SELECT * FROM users where companyId = '".$_SESSION['user']['companyId']."' ";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) > 0){
   while($row = mysqli_fetch_assoc($result)) {
        $info[] = $row;
   }
}
?>
<?php include "header.php"?>
<div class="right_col" role="main">
<div class="">
        <div class="page-title">
          <div class="title_left">
            <h3>Employee Information System</h3>
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
                    <h2>Information System</h2>
                    
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                    <div class="row">
                      <?php if(count($info) == 0) { ?>
                        No information saved
                      <?php } ?>  
                      <?php for ($e = 0; $e < count($info); $e++) {?>
                      <div class="col-md-3 col-xs-12 widget widget_tally_box employee_info" val ="<?=$e?>" id = "employee_info<?=$e?>" staff_id = "<?=$info[$e]['staff_id']?>">
                        <div class="x_panel" style="height: 260px;">
                          <div class="x_content">

                            <div class="flex">
                              <ul class="list-inline widget_profile_box">
                                <li>
                                  <img src="images/<?=$info[$e]['profile_image']?>" alt="..." class="img-circle profile_img">
                                </li>
                              </ul>
                            </div>

                            <h3 class="name" style="font-size: 13px;font-weight: 600;"><?=$info[$e]['fname']?> <?=$info[$e]['name']?></h3>
                            <p class="name" style="margin-top: -20px;font-size: 13px;font-weight: 600;"><?=$info[$e]['employee_ID']?></p>
                            <div class="flex">
                              <ul class="list-inline count2" style="border-bottom: none;">
                                <li style="width: 50%;">
                                  
                                  <span>Qualifications</span>
                                </li>
                                <li>
                                 
                                  <span>Certifications</span>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                      <?php }  ?>
                    </div>
                  </div>
                </div>
            </div>      
        </div>
</div>
</div>
<?php include "footer.php"?>
        

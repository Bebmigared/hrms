<?php 
include 'connection.php';
session_start();
$kss = [];
$msg = '';
if($_SESSION['user']['name'] == ''){
   $_SESSION['msg'] = "";
  $_SESSION['msg'] .= "<p>Name field is empty</p>";
}
if($_SESSION['user']['employee_ID'] == ''){
  $_SESSION['msg'] .= "<p>Employee ID field is empty</p>";
  $msg = "To update your profile, <a href = 'staff_settings.php'>click here</a>";
  $_SESSION['msg'] .= $msg;
}
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
?>
<?php include "header.php"?>
<div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>ID Card Request</h3>
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
               <?php if(isset($_SESSION['msg']) && $_SESSION['user']['category'] == 'staff') {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                  <?php } ?>
              <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Request for ID Card</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form action="process_idcard_request.php" method="POST" enctype="multipart/form-data">
                     <input type="file" name="signature" onchange="readURL(this)" id = "signature" style="display: none;">
                     <div class="text-center" style="margin-bottom: 20px;">
                       <img class="uploadimg" src="images/signature.png" alt="" style="width: 100px;height: 40px;">
                     </div>
                     <div class="form-group" >
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3" style="margin-bottom: 10px;">
                              <textarea class="form-control" placeholder="justification" name="justification"></textarea>
                            </div>
                    </div>
                     <div class="form-group" style="margin-top: 10px;">
                          <div class="col-md-8 col-sm-6 col-xs-12 col-md-offset-3">
                            <button class="btn btn-warning" type="button" id = "open_file">Upload Signature</button>
                            <button type="submit" name="submit" class="btn btn-success">Request</button>
                          </div>
                     </div>
                   </form>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-12 col-xs-12">
                  <div class="x_panel">
                  <div class="x_title">
                    <h2>KSS shared by (<span style="font-size: 13px;"><?=isset($kss[0]['name']) ? $kss[0]['name'] : 'None'?></span>)</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php if(count($kss) > 0) {?>
                    <p style="text-align: justify;"><?=$kss[0]['information']?></p>
                    <?php }else { ?>
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
<script type="text/javascript">
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
  $(function(){
    $("#open_file").on("click", function(e){
      $("#signature").trigger("click");
    });
  })
</script>
        

<?php 
include 'connection.php';
session_start();
$msg = '';
$id_request = [];
$kss = [];
$query = "SELECT * FROM id_card WHERE staff_id = '".$_SESSION['user']['id']."'";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) > 0){
   while($row = mysqli_fetch_assoc($result)) {
        $id_request[] = $row;
   }
}
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
?>
<?php include "header.php"?>
<div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>ID Card Request Status</h3>
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
              <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>View Status</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php if(count($id_request) > 0 ){ ?>
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title text-center">S/N</th>
                            <th class="column-title text-center">Request Date</th>
                            <th class="column-title text-center">Status </th>
                            <th class="column-title text-center">Justification </th>
                            <th class="column-title text-center">Remark </th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($id_request); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center text-center">
                              <?=$h + 1?>
                            </td>
                            <td class="text-center"><?=$id_request[$h]['date_created']?></td>
                            <td class="text-center"><?=$id_request[$h]['status']?></td>
                            <td class="text-center" style="text-align: justify;"><?=$id_request[$h]['justification']?></td>
                            <?php if($id_request[$h]['comment'] == '') {?>
                            <td class="text-center">
                               No Comment
                            </td>
                          <?php }else { ?>
                            <td class="text-center">
                               <div class="btn-group" role="group" aria-label="Basic example">
                                <button class="btn btn-warning btn-sm comment" justification ="<?=$id_request[$h]['justification']?>" request_id = "<?=$id_request[$h]['IID']?>" id = "comment<?=$h?>" comment = "<?=$id_request[$h]['comment']?>" data-toggle="modal" data-target="#myComment">Show Comment</button>
                              </div>
                            </td>
                           <?php } ?>
                           <?php }?>
                        </tbody>
                      </table>
                    </div>
                    <?php } else { ?>
                       You have pending request
                    <?php } ?>
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
        <div id="myComment" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-success" >Add Comment</h4>
      </div>
      <div style="padding: 20px;">
        <h4>Justification</h4>
        <p style="text-align: justify;margin-bottom: 5px" id ="justify"></p>
      </div>
      <div style="padding: 20px;">
        <h4>Comment</h4>
        <p style="text-align: justify;margin-top: 7px;margin-bottom: 5px" id ="admin_comment"></p>
      </div>
      <div class="modal-body" style="padding: 20px;">
        <form action="process_id_request.php" method="POST">
           <textarea class="form-control" rows="4" id ="save_justification" name = "justification" style="margin-bottom: 10px;"></textarea>
           <input type="text" name="id_card_request_id" id = "request_id" style="display: none;" />
           <button class="btn btn-warning btn-sm" name="justification_update" type="submit">Update Justification</button>
        </form>
      </div>
    </div>

  </div>
</div>
<?php include "footer.php"?>
<script type="text/javascript" src="js/appraisal.js"></script>
<script type="text/javascript">
  $(function(){
    $(".comment").on('click', function(e){
      let comment = $("#"+this.id+"").attr("comment");
      let request_id = $("#"+this.id+"").attr("request_id");
      let justification = $("#"+this.id+"").attr("justification");
      $("#request_id").val(request_id);
      $("#justify").text(justification);
      $("#admin_comment").text(comment);
      $("#save_comment").val(justification);
    });
  })
</script>

        

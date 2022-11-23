<?php 
include 'connection.php';
session_start();
$users = [];
if(!isset($_SESSION['user']['id'])) header("Location: login.php");
$query = "SELECT * FROM users WHERE companyId = '".$_SESSION['user']['companyId']."'";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) > 0){
   while($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
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
            <h3>Approval flow</h3>
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
                        <div class="alert alert-primary" style="background-color: blue;font-size:14px;color:#fff;" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                  <?php } ?>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Process Approvals</h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                   <?php if (count($users) > 0) {?>      
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N </th>
                            <th class="column-title">User Name </th>
                            <th class="column-title">Department </th>
                            <th class="column-title">Leave flow </th>
                            <th class="column-title">Appraisal flow </th>
                            <th class="column-title">Requisition flow </th>
                            <th class="column-title">Cash flow </th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($users); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$users[$h]['name']?> <?=$users[$h]['fname']?></td>
                            <td class=" ">
                              <?=$users[$h]['department']?></td>
                            <td class=" ">
                            <?php
                              if($users[$h]['leave_flow'] != '')
                              {
                                $flow = explode(';',$users[$h]['leave_flow']);
                                $value = '';
                                for($r = 0; $r < count($flow); $r++){
                                  $eachflow = explode(':',$flow[$r]);
                                  //echo $eachflow[1];
                                  
                                  $query = "SELECT * FROM users WHERE email = '".$eachflow[1]."' OR id = '".$eachflow[1]."' LIMIT 1";
                                  $result = mysqli_query($conn, $query);
                                  if(mysqli_num_rows($result) > 0){
                                     while($row = mysqli_fetch_assoc($result)) {
                                          if($value != '') $value .= '<span style="margin-left:2px;margin-right:7px;"">&#x27F6;</span>';
                                          $value .= $row['name'].' '.$row['fname'];
                                     }
                                  }
                                }
                                echo $value;
                              }
                            ?>
                          </td>
                          <td class=" ">
                            <?php
                              if($users[$h]['appraisal_flow'] != '')
                              {
                                $flow = explode(';',$users[$h]['appraisal_flow']);
                                $value = '';
                                for($r = 0; $r < count($flow); $r++){
                                  $eachflow = explode(':',$flow[$r]);
                                  //echo $eachflow[1];
                                  
                                  $query = "SELECT * FROM users WHERE email = '".$eachflow[1]."' OR id = '".$eachflow[1]."' LIMIT 1";
                                  $result = mysqli_query($conn, $query);
                                  if(mysqli_num_rows($result) > 0){
                                     while($row = mysqli_fetch_assoc($result)) {
                                          if($value != '') $value .= '<span style="margin-left:2px;margin-right:7px;"">&#x27F6;</span>';
                                          $value .= $row['name'].' '.$row['fname'];
                                     }
                                  }
                                }
                                echo $value;
                              }
                            ?>
                          </td>
                          <td class=" ">
                            <?php
                              if($users[$h]['requisition_flow'] != '')
                              {
                                $flow = explode(';',$users[$h]['requisition_flow']);
                                $value = '';
                                for($r = 0; $r < count($flow); $r++){
                                  $eachflow = explode(':',$flow[$r]);
                                  //echo $eachflow[1];
                                  
                                  $query = "SELECT * FROM users WHERE email = '".$eachflow[1]."' OR id = '".$eachflow[1]."' LIMIT 1";
                                  $result = mysqli_query($conn, $query);
                                  if(mysqli_num_rows($result) > 0){
                                     while($row = mysqli_fetch_assoc($result)) {
                                          if($value != '') $value .= '<span style="margin-left:2px;margin-right:7px;"">&#x27F6;</span>';
                                          $value .= $row['name'].' '.$row['fname'];
                                     }
                                  }
                                }
                                echo $value;
                              }
                            ?>
                          </td>
                           <td class=" ">
                            <?php
                              if($users[$h]['cash_flow'] != '')
                              {
                                $flow = explode(';',$users[$h]['cash_flow']);
                                $value = '';
                                for($r = 0; $r < count($flow); $r++){
                                  $eachflow = explode(':',$flow[$r]);
                                  //echo $eachflow[1];
                                  
                                  $query = "SELECT * FROM users WHERE email = '".$eachflow[1]."' OR id = '".$eachflow[1]."' LIMIT 1";
                                  $result = mysqli_query($conn, $query);
                                  if(mysqli_num_rows($result) > 0){
                                     while($row = mysqli_fetch_assoc($result)) {
                                          if($value != '') $value .= '<span style="margin-left:2px;margin-right:7px;"">&#x27F6;</span>';
                                          $value .= $row['name'].' '.$row['fname'];
                                     }
                                  }
                                }
                                echo $value;
                              }
                            ?>
                          </td>
                          </tr>
                          <?php }?>
                        </tbody>
                      </table>
                    </div>
                    <?php } ?>
                    <?php if(count($users) == 0) {?>
                      <h3>No User Found</h3>
                    <?php } ?>
                  </div>
                </div>
              </div>      
        </div>
</div>
</div>
<div class="modal fade" id="interModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Edit</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form action="inventory.php" method="POST" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
        
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Item Name<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="item_name" name="name" required="true" class="form-control col-md-7 col-xs-12" type="text">
              </div>
            </div>
            <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Category <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="category" name="category" required="true" class="form-control col-md-7 col-xs-12" type="text" >
                      
                    </div>
              </div>
              <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Quantity <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="quantity" name="quantity" required="true" class="form-control col-md-7 col-xs-12" type="number">
                      
                    </div>
              </div>
              <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Cost <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="cost" name="cost" required="true" class="form-control col-md-7 col-xs-12" type="number">
                      <input id="item_id" style="display:none;" name="itemid" required="true" class="form-control col-md-7 col-xs-12" type="text">
                    </div>
              </div>

              
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="submit" name="edit" class="btn btn-success">Submit</button>
                </div>
              </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php include "footer.php"?>
<script>
    $(function(e){
        $('.edit').on('click', function(e){
            e.preventDefault();
            let val = $('#'+this.id+'').attr("val");
            let name = $('#'+this.id+'').attr("name");
            let category = $('#'+this.id+'').attr("category");
            let quantity = $('#'+this.id+'').attr("quantity");
            let cost = $('#'+this.id+'').attr("cost");
            let id = $('#'+this.id+'').attr("item_id");
            $('#item_name').val(name);
            $('#category').val(category);
            $('#quantity').val(quantity);
            $('#cost').val(cost);
            $('#item_id').val(id);
                    
        });
        $('.delete').on('click', function(e){
            e.preventDefault();
            let id = $('#'+this.id+'').attr("item_id");
            $('#deleteitem_id').val(id);
            $('#deleteItem').trigger('click');
        })
    })
</script>
        

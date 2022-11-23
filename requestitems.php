<?php 
include 'connection.php';
session_start();
$data_item = [];
$items = [];

$users = [];
//print_r($_SESSION['user']);
if(!isset($_SESSION['user']['id'])) header("Location: login.php");
  if($_SESSION['user']['category'] == 'admin')
    $query = "SELECT * FROM items WHERE companyId = '".$_SESSION['user']['companyId']."'";
  else if($_SESSION['user']['category'] == 'staff')
  $query = "SELECT * FROM items WHERE companyId = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $items[] = $row;
      }
  }
  //print_r($items);
  $q = "SELECT * FROM requesteditem WHERE staff_id = '".$_SESSION['user']['id']."' AND date_created = '".date('Y-m-d')."' ORDER BY id DESC";
   $res = mysqli_query($conn, $q);
   if(mysqli_num_rows($res) > 0){
   while($r = mysqli_fetch_assoc($res)) {
     $data_item[] = $r;
     }
     //print_r($data);
   }

    $q = "SELECT * FROM users WHERE companyId = '".$_SESSION['user']['companyId']."' AND id != '".$_SESSION['user']['id']."'";
   $res = mysqli_query($conn, $q);
   if(mysqli_num_rows($res) > 0){
   while($r = mysqli_fetch_assoc($res)) {
     $users[] = $r;
     }
     //print_r($data);
   }
  //print_r($data);
?>
<?php include "header.php"?>
<div class="right_col" role="main">
<div class="">
        <div class="page-title">
          <div class="title_left">
            <h3>Request Item</h3>
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
            <?php if(isset($_SESSION['user']) && $_SESSION['user']['requisition_flow'] == '') {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            You can not process requisition, because you don't have approvals. Contact your admin
                        </div>
                  <?php } ?>        
            <?php if(count($data_item) > 0) {?>      
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>New Request</h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                   
                    <div class="table-responsive">
                      
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N </th>
                            <th class="column-title">Item ID </th>
                            <th class="column-title">Item Name </th>
                            <th class="column-title">Justification </th>
                            <th class="column-title">Quantity </th>
                            <th class="column-title">Request Date </th>
                            <th class="column-title">Status</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php if(count($data_item) > 0) {?>
                          <?php for ($h = 0; $h < count($data_item); $h++) {?>
                            
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class="" style="text-transform: capitalize;"><?=$data_item[$h]['item_id']?></td>
                            <td class="" style="text-transform: capitalize;">
                            
                              <?php 
                              $query = "SELECT * FROM items WHERE id = '".$data_item[$h]['item']."'";
                              $result = mysqli_query($conn, $query);
                              if(mysqli_num_rows($result)> 0){
                                  while($row = mysqli_fetch_assoc($result)) {
                                    echo $row['item_name'];
                                  }
                              }
                              ?>
                            </td>
                            <td class=" " style="text-transform: capitalize;"><?=$data_item[$h]['justification']?></td>
                            <td class=" "><?=$data_item[$h]['quantity']?></td>
                            <td class=" "><?=$data_item[$h]['date_created']?></td>
                            <td class="" style="text-transform: capitalize;"><?=$data_item[$h]['status']?></i></td>
                            </td>
                          </tr>
                          <?php } } ?>
                        </tbody>
                      </table>
                    </div>
            
                  </div>
                </div>
              </div>    
            <?php } ?>  
            <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Item<small>request new items</small></h2>
                      
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <br />
                      <form action="process_requesteditem.php" method="POST" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
  
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Item Name <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <select class="form-control col-md-7 col-xs-12" name="item_name">
                                <option value=""></option>
                                <?php for ($g = 0; $g < count($items); $g++) {?>
                                 <option value="<?=$items[$g]['id']?>"><?=$items[$g]['item_name']?></option>
                                <?php } ?>
                              </select>
                          </div>
                        </div>
                        <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Justification<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="text" name="justification" class="form-control col-md-7 col-xs-12" required="required" type="text">
                                </div>
                        </div>
                        <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Quantity<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="text" name="item_quantity" class="form-control col-md-7 col-xs-12" required="required" type="number">
                                </div>
                        </div>
                        <div class="form-group" style='display: none'>
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Cost<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="number" value='0' name="item_cost" class="form-control col-md-7 col-xs-12" type="number">
                                    <input id="flow" name="flow" class="form-control col-md-7 col-xs-12" type="text">
                                </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                           <!--  <button  class="btn btn-success hide" id="submitdata" name="submit">Submit</button>
                            <button  class="btn btn-success" data-toggle="modal" data-target="#addModal" id="flowplan">Continue</button> -->
                            <?php if(isset($_SESSION['approval_settings']) && $_SESSION['approval_settings'] == '1') {?>
                               <button  class="btn btn-success" data-toggle="modal" data-target="#addModal" id="flowplan">Continue</button>
                               <button style="display: none" class="btn btn-success" id="submitdata" name="submit">Submit</button>
                             <?php }else { ?>
                              <button  class="btn btn-success" id="submitdata" name="submit">Submit</button>
                             <?php  } ?> 
                          </div>
                        </div>
  
                      </form>
                    </div>
                  </div>
                </div>
        </div>
</div>
</div>
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Process Approval Flow</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="width: 100%">
         <form  method = "POST" enctype="multipart/form-data">
           <?php
              if($_SESSION['user']['requisition_flow'] != '')
              {?>
               <h3>Your Current flow</h3> 
            <?php 
                
                $flow = explode(';',$_SESSION['user']['requisition_flow']);
                $value = 'Staff';
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
              ?>
              <div class="row" style="margin-top: 10px;">
                <div class="col-sm-3">
                  <button  class="btn btn-success" id ="usethisflow">Use this flow</button>
                </div>
                <div class="col-sm-3">
                  <button  class="btn btn-danger" id ="useanotherflow">Use Another flow</button>
                </div>
              </div>
              <hr />
              <?php
              }else if($_SESSION['user']['requisition_flow'] == '' && isset($_SESSION['approval_settings']) && $_SESSION['approval_settings'] == '1'){?>
               <p>You don't have requisition flow, Create a flow for this request</p>
               <button  class="btn btn-danger" id ="useanotherflow">Create a flow</button>
             <?php } ?>

              
           
          <div class="form-group selectapproval" style="margin-top: 10px;">
                 <h2>Create a Flow</h2>
                 <button  class="btn btn-danger" id ="resetflowagain">Reset Flow</button>
                 <div class ="flow req_flow">
                  <div style="text-align: center;border:1px solid #ccc;padding: 7px;width: 200px;margin: auto"><a class="active">Staff </a></div>
                  <div style = 'text-align: center;padding: 7px;width: 200px;margin: auto;font-size: 30px'><span >&#8595;</span></div>
                </div>
            <label for="exampleInputEmail1">Select Approval</label>
            <select name="employee" id="employee" class="form-control">
               <option value=""></option>
               <?php for($r = 0; $r < count($users); $r++) { ?>
                 <option value="<?=$users[$r]['position'] == '' ? $users[$r]['role'] : $users[$r]['position']?>:<?=$users[$r]['id']?>:<?=$users[$r]['name']?> <?=$users[$r]['fname']?>"><?=$users[$r]['name']?> <?=$users[$r]['fname']?></option>
               <?php } ?> 
            </select>
          <input type="submit" id="usenewflow" value="Use Just Created Flow" class="btn btn-primary" style="margin-top: 10px;"/>
          </div>
         
         
        </form>
      </div>
    </div>
  </div>
</div>
<?php include "footer.php"?>
<script type="text/javascript">
  $(function(e){
    let combine = [];
    $('#resetflowagain').hide();
    $('.selectapproval').hide();
    $('#usenewflow').hide();
    $('#employee').change(function(e){
      e.preventDefault();
      let value = $(this).val();
      let data = value.split(":");
      let role = data[0] == '' ? 'Approval '+(combine.length+1)+'' : data[0];
      //alert(role);
      $("#resetflowagain").show();
       $('#usenewflow').show();
      combine[combine.length] = role+':'+data[1];
      $('.flow').append("<div style='text-align: center;border:1px solid #ccc;padding: 7px;width: 200px;margin: auto'><a class='active'>"+data[2]+" </a></div>");
      $('.flow').append("<div style = 'text-align: center;padding: 7px;width: 200px;margin: auto;font-size: 30px'><span >&#8595;</span></div>");
      $('#ok').val(combine.join(';'));
      $(this).val('');
    });

    $("#resetflowagain").click(function(e){
      e.preventDefault();
      combine = [];
      $('#resetflowagain').hide();
      $('.flow').html('');
      $('.flow').append('<div style="text-align: center;border:1px solid #ccc;padding: 7px;width: 200px;margin: auto"><a class="active">Staff </a></div>');
      $('.flow').append('<div style = "text-align: center;padding: 7px;width: 200px;margin: auto;font-size: 30px"><span >&#8595;</span></div>');
    });

    $('#useanotherflow').click(function(e){
      e.preventDefault();
      $('.selectapproval').show();
      $('#useanotherflow').hide();

    });

    $('#usenewflow').click(function(e){
      e.preventDefault();
      $('#flow').val(combine.join(';'));
       $('#submitdata').trigger('click');
    });

    $('#usethisflow').click(function(e){
      e.preventDefault();
      $('#submitdata').trigger('click');

    });
    $('#flowplan').click(function(e){
      e.preventDefault();
    })
  })

</script>
        

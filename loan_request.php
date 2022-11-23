<?php 
include 'connection.php';
session_start();
$kss = [];
$admin_id;
$users = [];
$categories = [];
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
 if($_SESSION['user']['category'] == 'staff') $admin_id = $_SESSION['user']['admin_id'];
 else if ($_SESSION['user']['category'] == 'admin') $admin_id = $_SESSION['user']['id'];
  $query = "SELECT kss.information,users.name,users.employee_ID FROM kss INNER JOIN users ON kss.staff_id = users.id WHERE kss.admin_id = '".$admin_id."'ORDER BY kss.id desc LIMIT 1";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $kss[] = $row;
      }
  }
   $query = "SELECT * FROM cash_category where companyId ='".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
      }
      //print_r($categories);
  }

   $q = "SELECT * FROM users WHERE companyId = '".$_SESSION['user']['companyId']."' AND id != '".$_SESSION['user']['id']."'";
   $res = mysqli_query($conn, $q);
   if(mysqli_num_rows($res) > 0){
   while($r = mysqli_fetch_assoc($res)) {
     $users[] = $r;
     }
     //print_r($data);
   }
  //print_r($_SESSION['user']);
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
                <h3>LOAN REQUEST</h3>
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
                      <h2>LOAN REQUEST<small></small></h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <br />
                      <form action="process_cash_request.php" method="POST" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
  
                        <div class="form-group" hidden>
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="">Loan Category <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                              <select name ="purpose" class="form-control col-md-7 col-xs-12" readonly>
                                  <option value ="20">Loan</option>
                                   
                                    <option value="20">Loan</option>
                                  
                                  
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
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Amount<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="number" name="amount" class="form-control col-md-7 col-xs-12" required="required" type="number">
                                </div>
                        </div>
                        <div class="form-group" hidden>
                                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Upload document <small style='color:red'>maximum file size 2MB (jpeg,pdf,doc,txt)</small>
                                      </label>
                                      <div class="col-md-6 col-sm-6 col-xs-12">
                                          <input type="file" name ="attach_document" class="custom-file-input" id="" hidden>
                                          <input style="display: none" id="flow" name="flow" class="form-control col-md-7 col-xs-12" type="text">
                                      </div>                                
                              </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                          <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
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
              <div class="col-md-4 col-sm-12 col-xs-12">
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
              </div>
              <div class="col-md-4 col-sm-12 col-xs-12">
                
              </div>
            </div>
              <div class="clearfix"></div>
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
              if($_SESSION['user']['cash_flow'] != '')
              {?>
               <h3>Your Current flow</h3> 
            <?php 
                
                $flow = explode(';',$_SESSION['user']['cash_flow']);
                $value = 'Staff';
                for($r = 0; $r < count($flow); $r++) {
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
              }else if($_SESSION['user']['cash_flow'] == '' && isset($_SESSION['approval_settings']) && $_SESSION['approval_settings'] == '1'){?>
               <p>You don't have Cash flow, Create a flow for this request</p>
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
          <input type="submit" id="usenewflow" value="Use Just Create Flow" class="btn btn-primary" style="margin-top: 10px;"/>
          </div>
         
         
        </form>
      </div>
    </div>
  </div>
</div>        
<?php include "footer.php"?>
<script type="text/javascript" src="js/appraisal.js"></script>
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
      //alert($('#flow').val());LOAN REQUEST

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
        

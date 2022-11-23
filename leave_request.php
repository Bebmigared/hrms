<?php 
include 'connection.php';
include 'connectionpdo.php';
session_start();
$dept = [];
$leave_type = [];
$users = [];

//print_r($_SESSION['user']);
  $query = "SELECT * FROM company WHERE id = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $dept = explode(";",$row['department']);
      }
  }
  $query = "SELECT * FROM leave_type WHERE grade = '".$_SESSION['user']['grade']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $leave_type[] = $row;
        // print_r($row);
//     exit();
    
      }
  }

   $q = "SELECT * FROM users WHERE companyId = '".$_SESSION['user']['companyId']."' AND id != '".$_SESSION['user']['id']."'";
 
  $res = mysqli_query($conn, $q);
    
  if(mysqli_num_rows($res) > 0){
  while($r = mysqli_fetch_assoc($res)) {
     $users[] = $r;
     }
    
   }
   
   
?>
<?php include "header.php"?>
<div class="right_col" role="main">
<div class="">
        <div class="page-title">
          <div class="title_left">
            <h3>Leave Request</h3>
          </div>

          <!-- <div class="title_right">
            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
              <div class="input-group">
                <input type="text" class="form-control" placeholder="Search for...">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button">Go!</button>
                </span>
              </div>
            </div>
          </div> -->
        </div>
        <div class="clearfix"></div>
        <div class="row">
                <?php if(isset($_SESSION['msg'])) {?>
                    <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                        <?=$_SESSION['msg']?>
                    </div>
                <?php unset($_SESSION['msg']); ?>
                <?php } ?>
                <?php if(isset($_SESSION['user']) && $_SESSION['user']['leave_flow'] == '') {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            You can not process leave request, because you don't have approvals. Kindly add leave approvals on the setting page
                        </div>
                  <?php } ?> 

                   <?php if(count($leave_type) == 0) {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                           You can not process leave request, kindly Contact the Admin to add to Leave Types (Such as Annual, Casual, Sick etc).
                        </div>
                  <?php } ?>     
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Leave Applicant<small>Complete the form below</small></h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <br />
                      <form action="process_leave_request.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
  
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="leave_type">Leave Type <span class="required">*</span>
                          </label>
                           <div class="col-md-6 col-sm-6 col-xs-12">
                            <select name="leave_type" class="form-control col-md-7 col-xs-12">
                              <option value=""></option>
                               <?php if(count($leave_type) > 0){ ?>
                                <?php for($r = 0; $r < count($leave_type);$r++){ ?>
                                <?php if($leave_type[$r]['leave_kind'] != 'Maternity' ) {?>
                                <option value="<?=$leave_type[$r]['leave_kind']?>"><?=$leave_type[$r]['leave_kind']?></option>
                                <?php  } ?>
                                <?php //if($r == 1){ echo '<option value="Maternity">Maternity</option>'; } ?>
                               <?php if($_SESSION['user']['gender'] == 'Female' &&  $leave_type[$r]['leave_kind'] == 'Maternity') {?>
                               <option value="Maternity">Maternity</option>
                               <?php  } ?>
                               <?php  } }else { ?>
                                <option value="Sick">Sick</option>
                               <option value="Annual">Annual</option>
                               <option value="Casual">Casual</option>
                               <option value="Compassionate">Compassionate</option>
                               <?php if($_SESSION['user']['gender'] == 'Female') {?>
                               <option value="Maternity">Maternity</option>
                               <?php  } ?>
                               
                               <?php } ?>
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Start Date <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="date" id="start_date" name="start_date"  class="form-control col-md-7 col-xs-12">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="end_date" class="control-label col-md-3 col-sm-3 col-xs-12">End Date</label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="end_date" class="form-control col-md-7 col-xs-12" type="date" name="end_date">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="leave_days" class="control-label col-md-3 col-sm-3 col-xs-12">Leave Days</label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="leave_day" class="form-control col-md-7 col-xs-12" type="number" readonly = 'true' name="leave_day">
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="resumption_date" class="control-label col-md-3 col-sm-3 col-xs-12">Resumption Date</label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <p class="form-control col-md-7 col-xs-12" id="resumptionDate"></p>
                            
                          </div>
                        </div>
                        <div class="form-group">
                                <label for="" class="control-label col-md-3 col-sm-3 col-xs-12">Justification</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input id="" class="form-control col-md-7 col-xs-12" type="text" name="justification">
                                </div>
                        </div>
                        <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Reliever Required</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <select name="reliever_required" class="form-control col-md-7 col-xs-12">
                                    <option value=""></option>
                                     <option value="Yes">Yes</option>
                                     <option value="No">No</option>
                                  </select>
                                </div>
                        </div>
                        <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Reliever Source</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input id="" class="form-control col-md-7 col-xs-12" type="text" name="reliever_source">
                                </div>
                        </div>
                        <div class="form-group">
                                <label for="" class="control-label col-md-3 col-sm-3 col-xs-12">Reliever Name</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input id="" class="form-control col-md-7 col-xs-12" type="text" name="reliever_name">
                                </div>
                        </div>
                        <div class="form-group">
                                <label for="" class="control-label col-md-3 col-sm-3 col-xs-12">Reliever Email</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input id="" class="form-control col-md-7 col-xs-12" type="email" name="reliever_email">
                                </div>
                        </div>
                        <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Reliever Phone</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input id="middle-name" class="form-control col-md-7 col-xs-12" type="number" name="reliever_phone">
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
              if($_SESSION['user']['leave_flow'] != '')
              {?>
               <h3>Your Current flow</h3> 
            <?php 
                
                $flow = explode(';',$_SESSION['user']['leave_flow']);
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
              }else if($_SESSION['user']['leave_flow'] == '' && isset($_SESSION['approval_settings']) && $_SESSION['approval_settings'] == '1'){?>
               <p>You don't have flow, Create a flow for this request</p>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.all.min.js"></script>
<script>
    function getDaysMinusWeekend (start_date,end_date) {
    let counter = 0;
    var start_date = new Date(start_date);
    var end_date = new Date(end_date);
    //alert(start_date);
      while(start_date.getTime() <= end_date.getTime()) {
        if(start_date.getDay()<6 && start_date.getDay() > 0) {
          //console.log(start_date.getDay());
            counter++;
        }
        start_date.setDate(start_date.getDate()+1);
      }
       $("#leave_day").val(counter);
       if(counter == 0){
           Swal.fire({
              type: 'Error',
              title: 'Oops...',
              text: 'Kindly select the appropriate start Date and End Date',
              footer: 'Please ensure that Saturday and Sunday are not selected as start Date or End Date'
            });
       }
       return counter;
   }
   function resumptionDate(end_date) {
    let counter = 0;
    let resumptionDate = '';
    var end_date = new Date(end_date);
    end_date.setDate(end_date.getDate()+1);
    console.log(end_date);
      while(counter == 0) {
        if(end_date.getDay()<6 && end_date.getDay() > 0) {
          console.log(end_date.getDay());
            counter++;
            console.log(counter);
        }
        
        if(counter == 0) end_date.setDate(end_date.getDate()+1);
      }
      $("#resumptionDate").text(end_date.toString().slice(0, 15));
      // return counter;
   }
    $(function(){
        $('#start_date').focusout(function(e){
          let start_date = $('#start_date').val();
          let end_date = $('#end_date').val();
          if(start_date == '') return false;
          if(end_date == '') return false;
          start_date = new Date(start_date);
          var today = new Date();
          var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
          //alert('aaa');
          date = new Date(date);
          //alert(date.getTime()+"----"+start_date.getTime());
          if(Number(date.getTime()) > Number(start_date.getTime())){
              Swal.fire({
              type: 'Error',
              title: 'Oops...',
              text: 'Kindly select an appropriate start Date'
            });
            $(".msgtouser").addClass('hide').html('');
            $(".msgtouser").removeClass('hide').append('<p>Kindly select an appropriate start Date (Date started has Past)</p>');
            //return false;
          }
          if(start_date.getDay() == 0 || start_date.getDay() == 6){
              Swal.fire({
              type: 'Error',
              title: 'Oops...',
              text: 'Start Date cannot be Saturday or Sunday'
            });
            $(".msgtouser").addClass('hide').html('');
            $(".msgtouser").removeClass('hide').append('<p>Start Date cannot be Saturday or Sunday</p>');
            //return false;
          }
          $(".msgtouser").addClass('hide').html('');
          getDaysMinusWeekend(start_date,end_date);
          resumptionDate(end_date);
          //$("#leave_day").val(days);
        });
        $('#end_date').focusout(function(e){
          //alert("ass");
          let start_date = $('#start_date').val();
          let end_date = $('#end_date').val();
          if(start_date == '') return false;
          if(end_date == '') return false;
          //alert(start_date);
          //getDaysMinusWeekend(start_date,end_date);
          //resumptionDate(end_date);
          ////alert(end_date.getDay());
          end_date = new Date(end_date);
          //alert(end_date.getDay());
          if(end_date.getDay() == 0 || end_date.getDay() == 6){
              Swal.fire({
              type: 'Error',
              title: 'Oops...',
              text: 'End Date cannot be Saturday or Sunday'
            });
             $(".msgtouser").addClass('hide').html('');
             $(".msgtouser").removeClass('hide').append('<p>End Date cannot be Saturday or Sunday</p>');
            //return false;
          }
           $(".msgtouser").addClass('hide').html('');
           getDaysMinusWeekend(start_date,end_date);
           resumptionDate(end_date);
          //$("#leave_day").val(days);
        });
   });
</script>

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
      //alert($('#flow').val());
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
        

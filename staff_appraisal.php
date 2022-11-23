<?php 
include 'connection.php';
session_start();
$appraisal = [];
$apraisal_flow = [];
$is_filled = true;
//print_r($_SESSION['user']);
 if(!isset($_SESSION['appraisal_id']) && $_SESSION['appraisal_id'] == '') header("Location: appraisals.php");
  $query = "SELECT * FROM appraisal WHERE id = '".$_SESSION['appraisal_id']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $appraisal[] = $row;
      }
  }
  if(!isset($_SESSION['is_just_filled'])){
    $rep_query = "SELECT * FROM appraisal_replies WHERE appraisal_id = '".$_SESSION['appraisal_id']."'";
    $rep_result = mysqli_query($conn, $rep_query);
    if(mysqli_num_rows($rep_result)> 0){
        $_SESSION['msg'] = "You have already completed this appraisal";
        $is_filled = true;
    }
  }
   unset($_SESSION['is_just_filled']);
  //print_r($appraisal[0]['appraisal_data']);
$appraisal_flow = explode(";", $_SESSION['user']['appraisal_flow']);

 $q = "SELECT * FROM users WHERE companyId = '".$_SESSION['user']['companyId']."' AND id != '".$_SESSION['user']['id']."'";
   $res = mysqli_query($conn, $q);
   if(mysqli_num_rows($res) > 0){
   while($r = mysqli_fetch_assoc($res)) {
     $users[] = $r;
     }
     //print_r($data);
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
                <h3>Complete Appraisal</h3>
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
                <?php if(isset($_SESSION['user']) && $_SESSION['user']['appraisal_flow'] == '') {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            You can not process appraisal, because you don't have approvals. Kindly contact the admin
                        </div>
                  <?php } ?>   
              <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Appraisal (<?=$appraisal[0]['period']?>) <?=$appraisal[0]['year']?></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php if($appraisal[0]['document'] != '') {?>
                        <div style="text-align: center;">
                            <div class="btn-group" role="group" aria-label="Basic example">
                              <a href="downloadfile.php/?to=view_appraisal&filename=<?=$appraisal[0]['document']?>" class="btn btn-primary">Download Appraisal</a>
                               <a style="color:#fff;" id = 'uploadfilled' class="btn btn-info">Upload Filled Appraisal</a>
                            </div>
                            <?php if($_SESSION['user']['category'] == 'staff'){?>
                                <form action="process_staff_appraisal_doc.php" method="POST" enctype="multipart/form-data">
                                   <input type="file" name = 'appraisal' id = 'filledApp' style="display: none;">
                                   <input type="text" name = 'appraisal_id' id = '' value = '<?=$appraisal[0]['id']?>' style="display: none;">
                                   <input type="submit" class="btn btn-success" name = 'submit' id = 'submitApp' style="display: none;">
                                </form>
                               
                              <?php } ?>
                        </div>
                    <?php } else if($appraisal[0]['document_name'] == 'input Question') {?>
                        <div class="row" id = "all_reply">
                             <div class="col-md-8 col-sm-12 col-xs-12 col-md-offset-2 each_questions">
                               <div style="text-align: center;">
                                  <div style="width: 40px;height: 40px;border-radius: 20px;border: 1px solid #5A738E;margin-left: auto;margin-right: auto;color: #5A738E;"><span id = 'stage' style="position: relative;top:10px;">1/<?=count(json_decode($appraisal[0]['appraisal_data']))?></span></div>
                               </div>
                               <div id="questionrow" style="text-align: justify;">
                                 <h5>Question 1</h5><p style = 'text-align:justify'><?=json_decode($appraisal[0]['appraisal_data'])[0]->question?></p>
                                 <p style = 'text-align:justify'>Weight <?=json_decode($appraisal[0]['appraisal_data'])[0]->weight?>%</p>
                               </div>
                                <?php if ($_SESSION['user']['category'] == 'staff' || $_SESSION['user']['category'] == 'admin') {?>
                                <div class="form-group">
                                  <label for="remark">Score</label>
                                  <select name = 'remark' id = "remark" class="form-control">
                                    <option value = "0"></option>
                                    <option value = "1">1</option>
                                    <option value = "2">2</option>
                                    <option value = "3">3</option>
                                    <option value = "4">4</option>
                                    <option value = "5">5</option>
                                    <option value = "6">6</option>
                                    <option value = "7">7</option>
                                    <option value = "8">8</option>
                                    <option value = "9">9</option>
                                    <option value = "10">10</option>
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label for="remark">Justification</label>
                                  <textarea class="form-control" value ="" rows="3" id = "justification" name="justification"></textarea>
                                </div>
                                <?php } ?>
                                <div class="row">
                                 <div class="btn-group main_btn" role="group" aria-label="Basic example" style="margin-top: 10px;">
                                  <button type="button" class="btn btn-warning" appraisal_data = '<?=$appraisal[0]['appraisal_data']?>' style="margin: 4px;" id="previous">Previous</button>
                                  <button type="button" class="btn btn-primary" appraisal_data = '<?=$appraisal[0]['appraisal_data']?>' style="margin: 4px;" id="next">Next</button>
                                 </div> 
                        </div>
                        </div>
                        </div>
                        <div class="row" id = "review_replies">
                             <div class="col-md-8 col-sm-12 col-xs-12 col-md-offset-2 each_reply">
                               <div>
                                 
                               </div>
                             </div>
                        </div>
                        <form action="process_staff_appraisal.php" method="POST" style="display: none">
                           <input type="text" name="appraisal_id" value="<?=$appraisal[0]['id']?>">
                           <textarea id = "all_remark" name = "all_remark"></textarea>
                           <textarea id = "all_justification" name="all_justification"></textarea>
                           <textarea id="appraisallist" name="appraisallist"></textarea>
                           <input style="display: none" id="flow" name="flow" class="form-control col-md-7 col-xs-12" type="text">
                           <!-- <button type="submit" name="submit" id = "submit_data"></button> -->
                           <!-- <button  class="btn btn-success hide" id="submit_data" name="submit">Submit</button>
                           <button  class="btn btn-success" data-toggle="modal" data-target="#addModal" id="flowplan">Continue</button> -->

                           <?php if(isset($_SESSION['approval_settings']) && $_SESSION['approval_settings'] == '1') {?>
                               <button  class="btn btn-success" data-toggle="modal" data-target="#addModal" id="flowplan">Continue</button>
                               <button style="display: none" class="btn btn-success" id="submit_data" name="submit">Submit</button>
                             <?php }else { ?>
                              <button  class="btn btn-success" id="submit_data" name="submit">Submit</button>
                             <?php  } ?> 
                        </form>
                    <?php }  ?>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Rating Summary<small></small></h2>
                  
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table class="table table-striped">
                      <tbody>
                        <tr>
                          <th scope="row">1</th>
                          <td>Lowest </td>
                        </tr>
                        <tr>
                          <th scope="row">10</th>
                          <td>Highest </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Approvals<small></small></h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table class="table table-striped">
                      <tbody>
                        <?php for ($r = 0; $r < count($appraisal_flow); $r++) {?>
                        <tr>
                          <?php $p = explode(":", $appraisal_flow[$r])?>
                          <?php if(count($p) > 0) { ?>
                          <th scope="row"><?=$p[0]?></th>
                          <?php if(count($p) > 1) { ?>
                          <td><?=$p[0]?></td>
                          <?php } }?>
                        </tr>
                       <?php } ?>
                      </tbody>
                    </table>
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
              if($_SESSION['user']['appraisal_flow'] != '')
              {?>
               <h3>Your Current flow</h3> 
            <?php 
                
                $flow = explode(';',$_SESSION['user']['appraisal_flow']);
                $value = 'Staff';
                for($r = 0; $r < count($flow); $r++){
                  $eachflow = explode(':',$flow[$r]);
                  //echo $eachflow[1];
                  
                  $query = "SELECT * FROM users WHERE email = '".$eachflow[1]."' LIMIT 1";
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
              }else if($_SESSION['user']['appraisal_flow'] == '' && isset($_SESSION['approval_settings']) && $_SESSION['approval_settings'] == '1'){?>
               <p>You don't have Appraisal flow, Create a flow for this request</p>
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
                 <option value="<?=$users[$r]['position'] == '' ? $users[$r]['role'] : $users[$r]['position']?>:<?=$users[$r]['email']?>:<?=$users[$r]['name']?> <?=$users[$r]['fname']?>"><?=$users[$r]['name']?> <?=$users[$r]['fname']?></option>
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
<script type="text/javascript" src="js/appraisal.js?version=1.22"></script>
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
       $('#submit_data').trigger('click');
    });

    $('#usethisflow').click(function(e){
      e.preventDefault();
      $('#submit_data').trigger('click');

    });
    $('#flowplan').click(function(e){
      e.preventDefault();
    })
  })

</script>
        

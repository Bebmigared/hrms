<?php 
include 'connection.php';
session_start();
$dept = [];
$allflow = [];
$manager = [];
$requisition_level = [];
$leave_level = [];
$appraisal_level = [];
$cash_level = [];
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
 if($_SESSION['user']['category'] != 'admin' && $_SESSION['user']['add_employee_management'] != '1') header("Location: dashboard.php");
 $query = "SELECT * from company WHERE id = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
        //print_r($data);
        $dept = explode(";",$row['department']);
        $app = explode(";", $row['appraisal_flow']);
        $leavef = explode(";", $row['leave_flow']);
        $cashf = explode(";", $row['cash_flow']);
        $req = explode(";", $row['requisition_flow']);
        $requisition_level = explode(";", $row['requisition_flow']);
        $leave_level = explode(";", $row['leave_flow']);
        $appraisal_level = explode(";", $row['appraisal_flow']);
        $cash_level = explode(";", $row['cash_flow']);
        
        for($r = 0; $r < count($req); $r++)
        {
            if(!in_array($req[$r],$allflow)) $allflow[] = $req[$r];   
        }
        
        for($r = 0; $r < count($app); $r++)
        {
            if(!in_array($app[$r],$allflow)) $allflow[] = $app[$r];   
        }
        
        
        for($r = 0; $r < count($leavef); $r++)
        {
            if(!in_array($leavef[$r],$allflow)) $allflow[] = $leavef[$r];   
        }
        
        for($r = 0; $r < count($cashf); $r++)
        {
           if(!in_array($cashf[$r],$allflow)) $allflow[] = $cashf[$r];   
        }
        

      }
      //print_r($allflow);
  }

 $query = "SELECT * from users WHERE position != '' and admin_id = '".$_SESSION['user']['id']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $manager[] = $row;
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
                <h3>APPROVAL FLOW</h3>
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
                        <div class="alert alert-primary" style="background-color: #007bff;font-size:16px;color:#fff" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                  <?php } ?> 
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>ASSIGN APPROVAL FLOW</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form action="process_assign_manager.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
                              
                               <!-- <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Position <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="position" class="form-control" id = "position">
                                       <option value=""></option>
                                      <?php for($r = 0; $r < count($allflow); $r++){?>
                                       <?php if($allflow[$r] != '') { ?>
                                        <option value = "<?=$allflow[$r];?>"> <?=strtoupper($allflow[$r]);?></option>
                                      <?php } } ?>
                                    </select>
                                </div>
                              </div> -->
                               <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Flow <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="flow" class="form-control" id = "flow" required="true">
                                       <option value=""></option>
                                       <option value="Requisition flow">Requisition Approval flow</option>
                                       <option value="Cash flow">Cash Approval flow</option>
                                       <option value="Appraisal flow">Appraisal Approval flow</option>
                                       <option value="Leave flow">Leave Approval flow</option>
                                       <option value="ID Card flow">ID Card Approval flow</option>
                                    </select>
                                </div>
                              </div>
                              <input type="text" style="display: none" name="createleaveflow" id="createleaveflow" />
                              <input type="text" style="display: none" name="createreqflow" id="createreqflow" />
                              <input type="text" style="display: none" name="createappraisalflow" id="createappraisalflow" />
                              <input type="text" style="display: none" name="createcashflow" id="createcashflow" />

                               <?php for ($k = 0; $k < count($leave_level); $k++) {?>
                                  <div class="form-group leave all">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Approval (<span style="text-transform: capitalize;"><?=$leave_level[$k]?></span>) <?=$k+1?><!-- <?=$leave_level[$k]?> --> Name <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                      <select name = 'leave_manager' class='lmanager form-control ' id ='leave<?=$k?>'>
                                          <option value = ''></option>
                                          <?php for($r = 0; $r < count($manager); $r++) { ?>
                                          <?php if( strtolower($manager[$r]['position']) == strtolower($leave_level[$k])) {?>   
                                          <option value="<?=$leave_level[$k]?>:<?=$manager[$r]['email']?>"><?=$manager[$r]['name']?> <?=$manager[$r]['fname']?> <?=$manager[$r]['mname']?> (<?=ucfirst($manager[$r]['position'])?>)</option>
                                          <?php } } ?>
                                      </select>        
                                    </div>
                                  </div>
                                  <?php } ?>
                                   <?php for ($k = 0; $k < count($appraisal_level); $k++) {?>
                                    <div class="form-group app all">
                                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Approval (<span style="text-transform: capitalize;"><?=$appraisal_level[$k]?></span>) <?=$k+1?><!-- <?=$appraisal_level[$k]?> --> Name <span class="required">*</span>
                                      </label>
                                      <div class="col-md-6 col-sm-6 col-xs-12">
                                      <select name = 'appraisal_manager' class='form-control amanager' id ='app<?=$k?>'>
                                          <option value = ''></option>
                                          <?php for($r = 0; $r < count($manager); $r++) { ?>
                                          <?php if( strtolower($manager[$r]['position']) == strtolower($appraisal_level[$k])) {?> 
                                          <option value="<?=$appraisal_level[$k]?>:<?=$manager[$r]['email']?>"><?=$manager[$r]['name']?> <?=$manager[$r]['fname']?> <?=$manager[$r]['mname']?> (<?=ucfirst($manager[$r]['position'])?>)</option>
                                          <?php } } ?>
                                      </select>        
                                    </div>
                                    </div>
                                    <?php } ?>
                                     <?php for ($k = 0; $k < count($requisition_level); $k++) {?>
                                      <div class="form-group req all">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Approval (<span style="text-transform: capitalize;"><?=$requisition_level[$k]?></span>) <?=$k+1?> <!-- <?=$requisition_level[$k]?> --> Name <span class="required">*</span>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                        <select name = 'req_manager' class='form-control rmanager' id ='req<?=$k?>'>
                                            <option value = ''></option>
                                            <?php for($r = 0; $r < count($manager); $r++) { ?>
                                            <?php if( strtolower($manager[$r]['position']) == strtolower($requisition_level[$k])) {?>   
                                            <option value="<?=$requisition_level[$k]?>:<?=$manager[$r]['email']?>"><?=$manager[$r]['name']?> <?=$manager[$r]['fname']?> <?=$manager[$r]['mname']?> (<?=ucfirst($manager[$r]['position'])?>)</option>
                                            <?php } } ?>
                                        </select>        
                                      </div>
                                      </div>
                                      <?php } ?>

                                    <?php for ($k = 0; $k < count($cash_level); $k++) {?>
                                    <div class="form-group cash all">
                                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Approval (<span style="text-transform: capitalize;"><?=$cash_level[$k]?></span>) <?=$k+1?> <!-- <?=$cash_level[$k]?> --> Name <span class="required">*</span>
                                      </label>
                                      <div class="col-md-6 col-sm-6 col-xs-12">
                                      <select name = 'cash_manager' class='form-control cmanager' id ='cash<?=$k?>'>
                                          <option value = ''></option>
                                          <?php for($r = 0; $r < count($manager); $r++) { ?>
                                          <?php if( strtolower($manager[$r]['position']) == strtolower($cash_level[$k])) {?>   
                                          <option value="<?=$cash_level[$k]?>:<?=$manager[$r]['email']?>"><?=$manager[$r]['name']?> <?=$manager[$r]['fname']?> <?=$manager[$r]['mname']?> (<?=ucfirst($manager[$r]['position'])?>)</option>
                                          <?php } } ?>
                                      </select>        
                                    </div>
                                    </div>
                                    <?php } ?>        
                              <!-- <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Employee Name <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                     <select name = 'employee' class='form-control rmanager' id ="employee">
                                          <option value = ''></option>
                                          
                                      </select>    
                                </div>
                              </div> -->
                              
                              <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Department <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="department" class="form-control" required="true" id = "department">
                                       <option value=""></option>
                                      <?php for($r = 0; $r < count($dept); $r++){?>
                                        <option value = "<?=$dept[$r];?>"> <?=strtoupper($dept[$r]);?></option>
                                      <?php } ?>
                                    </select>
                                </div>
                              </div>

                              

                             
                              
                             
                              <div class="ln_solid"></div>
                              <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                  <button type="submit" name="submit" class="btn btn-success">Submit</button>
                                </div>
                              </div>
                            </form>
                  </div>
                </div>
              </div>
              <!--div class="col-md-4 col-sm-12 col-xs-12">
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
              </div-->
              <div class="col-md-4 col-sm-12 col-xs-12">
                
              </div>
            </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
<?php include "footer.php"?>
<!-- <script type="text/javascript" src="js/appraisal.js"></script> -->
<script type="text/javascript">
  
  $(function(e){
    var managers = <?php echo json_encode($manager); ?>;
    $(".all").css("display","none");
    let leavearray = [];
    let apparray = [];
    let casharray = [];
    let reqarray = [];
    $('#flow').change(function(e){
       let flow = $('#flow').val();
       $(".all").css("display","none");
       if(flow == 'Requisition flow')
       {
          $('.req').css("display","block");
       }else if(flow == 'Cash flow')
       {
          $('.cash').css("display","block");
       }else if(flow == 'Leave flow')
       {
          $('.leave').css("display","block");
       }else if(flow == 'Appraisal flow')
       {
          $('.app').css("display","block");
       }else if(flow == 'ID Card flow')
       {
          $('.card').css("display","block");
       }
    });
    $(".lmanager").change(function(e){
        let leavemanager = $('#'+this.id+'').val();

        let index = leavearray.indexOf(leavemanager.split(':')[0]);
         //alert(index);
        let createleaveflow = $('#createleaveflow').val();
        if(index == -1){
          if(createleaveflow == '') $('#createleaveflow').val(leavemanager);
          else $('#createleaveflow').val(createleaveflow+';'+leavemanager);
        } 

        else 
        {
            let flow = createleaveflow.split(';');
            flow[index] = leavemanager;
            $('#createleaveflow').val(flow.join(';'));
            //alert(flow.join(';'));
        }
        leavearray.push(leavemanager.split(':')[0]);
        //alert($('#createleaveflow').val());
    });
    $(".amanager").change(function(e){
        let appmanager = $('#'+this.id+'').val();

        let index = apparray.indexOf(appmanager.split(':')[0]);
         //alert(index);
        let createappflow = $('#createappraisalflow').val();
        if(index == -1){
          if(createappflow == '') $('#createappraisalflow').val(appmanager);
          else $('#createappraisalflow').val(createappflow+';'+appmanager);
        } 

        else 
        {
            let flow = createappflow.split(';');
            flow[index] = appmanager;
            $('#createappraisalflow').val(flow.join(';'));
            //alert(flow.join(';'));
        }
        apparray.push(appmanager.split(':')[0]);
        //alert($('#createleaveflow').val());
    });

    $(".rmanager").change(function(e){
        let reqmanager = $('#'+this.id+'').val();

        let index = reqarray.indexOf(reqmanager.split(':')[0]);
         //alert(index);
        let createreqflow = $('#createreqflow').val();
        if(index == -1){
          if(createreqflow == '') $('#createreqflow').val(reqmanager);
          else $('#createreqflow').val(createreqflow+';'+reqmanager);
        } 

        else 
        {
            let flow = createreqflow.split(';');
            flow[index] = reqmanager;
            $('#createreqflow').val(flow.join(';'));
           // alert(flow.join(';'));
        }
        reqarray.push(reqmanager.split(':')[0]);
        //alert($('#createleaveflow').val());
    });
    $(".cmanager").change(function(e){
        let cashmanager = $('#'+this.id+'').val();

        let index = casharray.indexOf(cashmanager.split(':')[0]);
        // alert(index);
        let createcashflow = $('#createcashflow').val();
        if(index == -1){
          if(createcashflow == '') $('#createcashflow').val(cashmanager);
          else $('#createcashflow').val(createcashflow+';'+cashmanager);
        } 

        else 
        {
            let flow = createcashflow.split(';');
            flow[index] = cashmanager;
            $('#createcashflow').val(flow.join(';'));
            //alert(flow.join(';'));
        }
        casharray.push(cashmanager.split(':')[0]);
        //alert($('#createleaveflow').val());
    });
    $('#position').change(function(e){
      e.preventDefault();
      let value = $(this).val();
      $('#employee').html('');
      $('#employee').append("<option value=''></option>");
      for(let r = 0; r < managers.length; r++)
      {
          if(managers[r].position != undefined && managers[r].position.toLowerCase() == value.toLowerCase())
          {
             $("#employee").append("<option value='"+managers[r].position.trim()+':'+managers[r].email.trim()+"'>"+managers[r].name+" "+managers[r].fname+"</option>");
          }
      }
     
    })
  });
</script>
        

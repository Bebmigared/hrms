<?php
include "connection.php";
session_start();
if(!isset($_SESSION['employee_payroll_data'])){
  header("Location: masterlist.php");
}
$month = ["JAN", "FEB", "MAR", "APR", "MAY", "JUNE", "JULY", "AUG","SEPT","OCT", "NOV", "DEC"];
$t = (int)date("m") - 1;
$this_month = $month[$t];
$branch = [];
$query = "SELECT * FROM branches WHERE id = '".$_SESSION['employee'][0]['branch_id']."'";
		  $result = mysqli_query($conn, $query);
		  if(mysqli_num_rows($result)> 0){
		      while($row = mysqli_fetch_assoc($result)) {
		        $branch[] = $row;
		      }
		  }
//print_r($branch);		  
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
  .btn-success a:hover{
      color:red;
  }
</style>
<div class="right_col" role="main">
<div class="">
        <div class="page-title">
          <div class="title_left">
              
            <h3><a href = 'masterlist'><i class="fa fa-arrow-left"></i></a> Payslip</h3>
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
                      <h2>Payslip (for <?=isset($_SESSION['employee'][0]['first_name']) ? $_SESSION['employee'][0]['last_name']." ".$_SESSION['employee'][0]['first_name'] : ''?>)</h2>
                    <ul class="nav navbar-right panel_toolbox">
                    </ul>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                   <?php if(isset($_SESSION['employee'])) {?>  
                    <div class="table-responsive">
                      
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th colspan="2" scope="col" style="">ICS OUTSOURCING LIMITED</th>
                            <th colspan="3" scope="col" style="">PAYSLIP FOR <?=$this_month?> <?=date('Y')?></th>
                            <th></th>
                          </tr>
                        </thead>
                           <tbody>
                                <tr>
                                  <th scope="row">EMP. CODE:</th>
                                  <td><?=isset($_SESSION['employee'][0]['employee_ID']) ? $_SESSION['employee'][0]['employee_ID'] : ''?></td>
                                  <th>PAYMODE</th>
                                  <td colspan="2"><?=isset($_SESSION['company']['company_name']) ? $_SESSION['company']['company_name'] : ''?></td>
                                  <td></td>
                                </tr>
                                <tr>
                                  <th scope="row">EMP.NAME</th>
                                  <td><?=isset($_SESSION['employee'][0]['first_name']) ? $_SESSION['employee'][0]['last_name']." ".$_SESSION['employee'][0]['first_name'] : ''?></td>
                                  <th>Grade</th>
                                  <td colspan="2">BANKING ASSOCIATE 3</td>
                                  <td></td>
                                </tr>
                                <tr>
                                  <th scope="row">DEPARTMENT</th>
                                  <td colspan="2">SUPPORT</td>
                                  <th>CATEGORY</th>
                                  <td colspan="2">Employee</td>
                                </tr>
                                <tr>
                                  <th scope="row">LOCATION</th>
                                  <td colspan="2"><?=isset($branch[0]['name']) ? $branch[0]['name'] : ''?></td>
                                  <th>POSITION</th>
                                  <td colspan="2">TELLER</td>
                                </tr>
                                <tr style="width: 100%;">
                                  <th style="width: 300px;">ALLOWANCES/EARNING</th>
                                  <th colspan="2">NGN</th>
                                   <th>DEDUCTIONS</th>
                                  <th colspan="2">NGN</th>
                                </tr>
              <tr>
              <td>BASIC SALARY</td>
              <td colspan="2"><?=number_format($_SESSION['employee_payroll_data'][0]['basic_salary'])?></td>
              <td>PENSION(EMPLOYER)</td>
              <td colspan="2"><?=number_format($_SESSION['employee_payroll_data'][0]['pension_company'])?></td>
              </tr>
              <tr>
              <td>HOUSING</td>
              <td colspan="2"><?=number_format($_SESSION['employee_payroll_data'][0]['housing'])?></td>
               <td>PENSION(EMPLOYEE)</td>
              <td colspan="2"><?=number_format($_SESSION['employee_payroll_data'][0]['tax'])?></td>
              
              </tr>
              <tr>
              <td>HOUSING</td>
              <td colspan="2"><?=number_format($_SESSION['employee_payroll_data'][0]['transport'])?></td>
              <td>NTF</td>
              <td colspan="2"><?=number_format($_SESSION['employee_payroll_data'][0]['NTF'])?></td>
              </tr>
              <tr>
              <td>LUNCH</td>
              <td colspan="2"><?=number_format($_SESSION['employee_payroll_data'][0]['lunch'])?></td>
              <td>ECA</td>
              <td colspan="2"><?=number_format($_SESSION['employee_payroll_data'][0]['ECA'])?></td>
              </tr>
              <tr>
              <td>UTILITY</td>
              <td colspan="2"><?=number_format($_SESSION['employee_payroll_data'][0]['utility'])?></td>
               <td>ITF</td>
              <td colspan="2"><?=number_format($_SESSION['employee_payroll_data'][0]['ITF'])?></td>
              </tr>
              <tr>
              <td>EDUCATION</td>
              <td colspan="2"><?=number_format($_SESSION['employee_payroll_data'][0]['education'])?></td>
              <td>GLI</td>
              <td colspan="2"><?=number_format($_SESSION['employee_payroll_data'][0]['GLI'])?></td>
              </tr>
              <tr>
              <td>ENTERTAINMENT</td>
              <td colspan="2"><?=number_format($_SESSION['employee_payroll_data'][0]['entertainment'])?></td>
              <td>TAX</td>
              <td colspan="2"><?=number_format($_SESSION['employee_payroll_data'][0]['tax'])?></td>
              
              </tr>
              <tr>
              <td>ITF</td>
              <td colspan="2"><?=number_format($_SESSION['employee_payroll_data'][0]['ITF'])?></td>
              <th>TOTAL DEDUCTION</th>
              <td colspan="2"></td>
              </tr>
              <tr>
              <td>FURNITURE</td>
              <td colspan="2"><?=number_format($_SESSION['employee_payroll_data'][0]['furniture'])?></td>
              <td></td>
              <td></td>
              </tr>
              <tr>
              <td>QUARTERLY ALLOWANCE</td>
              <td colspan="2"><?=number_format($_SESSION['employee_payroll_data'][0]['q_allowance'])?></td>
              </tr>
              <tr>
              <td>LEAVE</td>
              <td colspan="2"><?=number_format($_SESSION['employee_payroll_data'][0]['leave_bonus'])?></td>
              </tr>
              <tr>
              <th>GROSS SALARY</th>
              <td colspan="2"><?=$_SESSION['employee_payroll_data'][0]['gross']?></td>
              </tr>
              <tr>
              <th>NET SALARY</th>
              <td colspan="2"><?=$_SESSION['employee_payroll_data'][0]['NET']?></td>
              </tr>    
              <tr>
              <th>REMARK</th>
              <td colspan="3"></td>
              <td></td>
              <td></td>
              </tr>
                        </tbody>
                      </table>
                    </div>
                  <?php }else { ?>
                    <p>No Employee added</p>
                  <?php } ?> 
                  </div>
                </div>
              </div>    
             
        </div>
</div>
</div>
<?php include "footer.php"?>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<script>
    $(function(){
        $('#getbranch').on('change', function(e){
            if($('#getbranch').val() == '') return false;
            $('#branches').trigger('click');
        })
    });
    $('#getperiod').on('change', function(e){
        if($('#getperiod').val() == '') return false;
        $('#selectedperiod').trigger('click');
    })
</script>
        

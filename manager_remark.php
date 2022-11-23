<?php 
include 'connection.php';
 session_start();
 $msg = '';
 $data = [];
 $user = [];
 $to_remark = [];
 $cash_approval_details = [];
 $alldata_cash = [];
 $cash = [];
 $to_show_cash = [];
 $as_commented = [];
 $limit = (int)json_decode($json)->LIMIT;
 $totalpage = 0;
 $filterstatus = 'pending';
 unset($_SESSION['status']);
 $page = 1;
 $query = "SELECT * FROM users WHERE companyId = '".$_SESSION['user']['companyId']."'";
 $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result) > 0){
     while($row = mysqli_fetch_assoc($result)) {
          $user[] = $row;
     }
  }

 if(isset( $_GET['q']))
 {
    $page = $_GET['q'];
    $offset = ((int)$_GET['q'] - 1) * $limit;
    if(isset($_GET['status']))
    {
      $filterstatus = $_GET['status'];
      $_SESSION['status'] = $_GET['status'];
    } 

    else {
      $filterstatus = isset($_SESSION['status']) ? $_SESSION['status'] : 'pending';
    }
 }
 else 
 {
    $offset = 0;
 }

 //print_r($filterstatus);

 if($filterstatus == 'pending')
 {

  $query = "SELECT cash_request.id as cash_id, users.name, users.fname,users.department, cash_request.cash_id as cashid, cash_request.purpose,cash_request.flow,cash_request.justification,cash_request.date_created,cash_request.amount,cash_request.staff_id,users.id as user_id FROM cash_request INNER JOIN approval_flows ON approval_flows.requestId = cash_request.id INNER JOIN users ON users.id = cash_request.staff_id WHERE approval_flows.status IS NULL AND approval_flows.approvalId = '".$_SESSION['user']['id']."'";

   $query2 = "SELECT * FROM requesteditem INNER JOIN approval_flows ON approval_flows.requestId = requesteditem.id WHERE approval_flows.status IS null AND approval_flows.approvalId = '".$_SESSION['user']['id']."'";
 
 }else 
 {
     $query = "SELECT cash_request.id as cash_id, users.name, users.fname,users.department, cash_request.cash_id as cashid, cash_request.purpose,cash_request.flow,cash_request.justification,cash_request.date_created,cash_request.amount,cash_request.staff_id,users.id as user_id FROM cash_request INNER JOIN approval_flows ON approval_flows.requestId = cash_request.id INNER JOIN users ON users.id = cash_request.staff_id WHERE (approval_flows.status = 'approved' OR approval_flows.status = 'decline') AND approval_flows.approvalId = '".$_SESSION['user']['id']."'";

     $query2 = "SELECT * FROM requesteditem INNER JOIN approval_flows ON approval_flows.requestId = requesteditem.id WHERE approval_flows.status = 'approved' AND approval_flows.approvalId = '".$_SESSION['user']['id']."'";
 }

 // INNER JOIN users ON users.id = cash_request.staff_id WHERE cash_request.companyId = '".$_SESSION['user']['companyId']."' ORDER BY cash_request.id DESC";
 $app_result = mysqli_query($conn, $query);
  if(mysqli_num_rows($app_result) > 0){
     while($row = mysqli_fetch_assoc($app_result)) {
          $cash[] = $row;
     }
  }

  //print_r($cash);

   $result = mysqli_query($conn, $query2);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $alldata_cash[] = $row;
      }
      //echo count($alldata_item);
  }

  if(count($alldata_cash) > 0)
  {
       $totalpage = (count($alldata_cash) / (int)$limit);
       $totalpage = ceil($totalpage);
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
            <h3>Cash Requisition Remark</h3>
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
                    <h2>Remark</h2>
                      <ul class="nav navbar-right panel_toolbox">
                      <li>
                         <div class="input-group" style="">
                          <a  href="<?= $_SERVER['SERVER_NAME'] == 'localhost' ? "/newhrcore/manager_remark?q=1&l=sl&status=pending" : "/manager_remark?q=1&l=sl&status=pending" ?>"
                                class="btn btn-info">Pending</a>
                        
                           <a  href="<?= $_SERVER['SERVER_NAME'] == 'localhost' ? "/newhrcore/manager_remark?q=1&l=sl&status=treated" : "/manager_remark?q=1&l=sl&status=treated" ?>"
                                class="btn btn-success">Treated</a>   
                                </div>         
                      
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                   <?php if(count($cash) > 0 ){ ?>
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N</th>
                            <th class="column-title text-center" style='text-transform:capitalize'>Name</th>
                            <th class="column-title text-center" style='text-transform:capitalize'>Department </th>
                           <!--  <th class="column-title text-center" style='text-transform:capitalize'>Role </th> -->
                            <th class="column-title text-center">Cash ID </th>
                            <th class="column-title text-center">Amount </th>
                            <th class="column-title text-center">Category </th>
                            <th class="column-title text-center">Date Created </th>
                            <th class="column-title text-center">More </th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($cash); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class="text-center" style='text-transform:capitalize'><?=$cash[$h]['name']?> <?=$cash[$h]['fname']?></td>
                            <td class="text-center" style='text-transform:capitalize'>
                             <!--  <?=$to_remark[$h]['department']?> -->
                               <?php
                               $query = "SELECT * FROM departments WHERE id = '".$cash[$h]['department']."'";
                                $result = mysqli_query($conn, $query);
                                if(mysqli_num_rows($result)> 0){
                                    while($row = mysqli_fetch_assoc($result)) {
                                      echo $row['dept'];
                                    }
                                }else{
                                   echo $cash[$h]['department'];
                                }  
                                ?>    
                            </td>
                           <!--  <td class="text-center" style='text-transform:capitalize'><?=$cash[$h]['role']?></td> -->
                             <td class="text-center"><?=$cash[$h]['cashid']?></td>
                            <td class="text-center"><?=$cash[$h]['amount']?></td>
                            <td class="text-center" style='text-transform:capitalize'>
                             <!--  <?=$to_show_cash[$h]['purpose']?> -->
                                 <?php
                               $query = "SELECT * FROM cash_category WHERE id = '".$cash[$h]['purpose']."'";
                                $result = mysqli_query($conn, $query);
                                if(mysqli_num_rows($result)> 0){
                                    while($row = mysqli_fetch_assoc($result)) {
                                      echo $row['category'];
                                    }
                                }else{
                                   echo $cash[$h]['purpose'];
                                }
                                ?>
                              </td>
                            <td class="text-center" style='text-transform:capitalize'><?=$cash[$h]['date_created']?></td>
                            <td class="text-center"><a href="process_request_cash_details_for_remark.php/?cash_id=<?=base64_encode($cash[$h]['cash_id'])?>&staff_id=<?=base64_encode($cash[$h]['staff_id'])?>" class="btn btn-sm btn-success">View</a></td>
                           <?php }?>
                        </tbody>
                      </table>
                    </div>
                     <?php if($totalpage > 1) { ?>
                    <div id="">

                      <ul class="pagination">
                         <p>Page Showing <?=$page?> of <?=$totalpage?></p>
                        <p style="display: none"> <?=$ppage = $page > 1 ? $page - 1 : 1?></p>
                        <p style="display: none"> <?=$npage = $page < $totalpage ? $page + 1 : $totalpage?></p>
                        <li class="page-item <?= $page == 1 ? 'disabled' : ''?>"><a class="page-link" href="<?= $_SERVER['SERVER_NAME'] == 'localhost' ? "/newhrcore/manager_remark?q=$ppage&l=sl" : "/manager_remark?q=$ppage&l=sl" ?>">Previous</a></li>
                      
                        <li class="page-item <?= $page == $totalpage ? 'disabled' : ''?>"><a class="page-link" href="<?= $_SERVER['SERVER_NAME'] == 'localhost' ? "/newhrcore/manager_remark?q=$npage&l=sl" : "/manager_remark?q=$npage&l=sl" ?>">Next</a></li>
                      </ul>
                    </div>
                  <?php } ?> 
                    <?php } else { ?>
                       You have no requisition to approve
                    <?php } ?>
                  </div>
                </div>
            </div> 
        </div>
</div>
</div>
<?php include "footer.php"?>
<script type="text/javascript">
    $('.upload_qual_file').on('click', function(e){
     $('#qual_file').trigger('click');
    });
    function readURL(input) {
      if (input.files && input.files[0]) {
        $('#doc').text('1 doc added-'+input.files[0].name);
        $('.upload_qual_file')
            .attr('src', 'images/document.png')
            .width(100)
            .height(100);
      }
    }
</script>

        

<?php 
include 'connection.php';
include 'vendor/autoload.php';
$json = (string)((new josegonzalez\Dotenv\Loader(__DIR__.'/.env'))->parse());
 session_start();
 $msg = '';
 $data = [];
 $user = [];
 $alldata_item = [];
 $to_remark = [];
 $requistion_approval_details = [];
 $requistion = [];
 $to_show_requisition = [];
 $as_commented = [];
 $limit = (int)json_decode($json)->LIMIT;
 $totalpage = 0;
 $filterstatus = 'pending';
 unset($_SESSION['status']);
 $page = 1;
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
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

 if($filterstatus == 'pending')
 {
    $query = "SELECT approval_flows.id, approval_flows.status, requesteditem.id as request_id, requesteditem.item_id, requesteditem.item,requesteditem.flow,requesteditem.justification,requesteditem.staff_id, requesteditem.date_created FROM requesteditem INNER JOIN approval_flows ON approval_flows.requestId = requesteditem.id WHERE approval_flows.status IS null AND approval_flows.approvalId = '".$_SESSION['user']['id']."' ORDER BY id DESC LIMIT $limit OFFSET $offset";

     $query2 = "SELECT * FROM requesteditem INNER JOIN approval_flows ON approval_flows.requestId = requesteditem.id WHERE approval_flows.status IS null AND approval_flows.approvalId = '".$_SESSION['user']['id']."'";
 }
 else 
 {
    $query = "SELECT approval_flows.id, approval_flows.status, requesteditem.id as request_id, requesteditem.item_id, requesteditem.item,requesteditem.flow,requesteditem.justification,requesteditem.staff_id, requesteditem.date_created FROM requesteditem INNER JOIN approval_flows ON approval_flows.requestId = requesteditem.id WHERE approval_flows.status = 'approved' AND approval_flows.approvalId = '".$_SESSION['user']['id']."' ORDER BY id DESC LIMIT $limit OFFSET $offset";
    $query2 = "SELECT * FROM requesteditem INNER JOIN approval_flows ON approval_flows.requestId = requesteditem.id WHERE approval_flows.status = 'approved' AND approval_flows.approvalId = '".$_SESSION['user']['id']."'";
 }
 
 $app_result = mysqli_query($conn, $query);
  if(mysqli_num_rows($app_result) > 0){
     while($row = mysqli_fetch_assoc($app_result)) {
          $requistion[] = $row;
     }
  }

  $result = mysqli_query($conn, $query2);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $alldata_item[] = $row;
      }
      //echo count($alldata_item);
  }

  if(count($alldata_item) > 0)
  {
       $totalpage = (count($alldata_item) / (int)$limit);
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
            <h3>Requisition Remark</h3>
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
                    <h2>Requisition to Remark</h2>
                     <ul class="nav navbar-right panel_toolbox">
                      <li>
                         <div class="input-group" style="">
                          <a  href="<?= $_SERVER['SERVER_NAME'] == 'localhost' ? "/newhrcore/requisition_remark?q=1&l=sl&status=pending" : "/requisition_remark?q=1&l=sl&status=pending" ?>"
                                class="btn btn-info">Pending</a>
                        
                           <a  href="<?= $_SERVER['SERVER_NAME'] == 'localhost' ? "/newhrcore/requisition_remark?q=1&l=sl&status=treated" : "/requisition_remark?q=1&l=sl&status=treated" ?>"
                                class="btn btn-success">Treated</a>   
                                </div>         
                      
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                   <?php if(count($requistion) > 0 ){ ?>
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N</th>
                            <th class="column-title text-center">Name</th>
                            <th class="column-title text-center">Item ID </th>
                            <th class="column-title text-center">Department </th>
                            <!-- <th class="column-title text-center">Role </th> -->
                            <th class="column-title text-center">Item Name </th>
                            <th class="column-title text-center">Date Created </th>
                            <th class="column-title text-center">Status </th>
                            <th class="column-title text-center">More </th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($requistion); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class="text-center" style="text-transform: capitalize;">
                              <!-- <?=$requistion[$h]['staff_id']?> -->
                                <?php
                                $dept = '';
                               $query = "SELECT * FROM users WHERE id = '".$requistion[$h]['staff_id']."'";
                                $result = mysqli_query($conn, $query);
                                if(mysqli_num_rows($result)> 0){
                                    while($row = mysqli_fetch_assoc($result)) {
                                      echo $row['name'];
                                      $dept = $row['department'];
                                    }
                                }else{
                                   echo $requistion[$h]['staff_id'];
                                }  
                                ?>  
                              </td>
                            <td class="text-center"><?=$requistion[$h]['item_id']?></td>
                            <td class="text-center">
                             
                               <?php
                               $query = "SELECT * FROM departments WHERE id = '".$dept."'";
                                $result = mysqli_query($conn, $query);
                                if(mysqli_num_rows($result)> 0){
                                    while($row = mysqli_fetch_assoc($result)) {
                                      echo $row['dept'];
                                    }
                                }else{
                                   echo $dept;
                                }  
                                ?>   
                            </td>
                           <!--  <td class="text-center"><?=$requistion[$h]['role']?></td> -->
                            <td class="text-center" style="text-transform: capitalize;">
                              <!-- <?=$to_show_requisition[$h]['item']?> -->
                              <?php 
                              $query = "SELECT * FROM items WHERE id = '".$requistion[$h]['item']."'";
                              $result = mysqli_query($conn, $query);
                              if(mysqli_num_rows($result)> 0){
                                  while($row = mysqli_fetch_assoc($result)) {
                                    echo $row['item_name'];
                                  }
                              }else {
                                echo $requistion[$h]['item'];
                              }
                              ?>      
                            </td>
                             <td class="text-center"><?=$requistion[$h]['date_created']?></td>
                             <td class="text-center" style="text-transform: capitalize;"><?=$requistion[$h]['status'] == null ? 'Pending' : $requistion[$h]['status']?></td>
                            <?php if($_SERVER['SERVER_NAME'] != 'localhost') {?>
                            <td class="text-center"><a href="get_this_staff_requisition.php/?requestitem_id=<?=base64_encode($requistion[$h]['request_id'])?>&staff_id=<?=base64_encode($requistion[$h]['staff_id'])?>" class="btn btn-sm btn-success">View</a></td>
                          <?php }else { ?>
                            <td class="text-center"><a href="/newhrcore/get_this_staff_requisition.php/?requestitem_id=<?=base64_encode($requistion[$h]['request_id'])?>&staff_id=<?=base64_encode($requistion[$h]['staff_id'])?>" class="btn btn-sm btn-success">View</a></td>
                           <?php } }?>
                        </tbody>
                      </table>
                    </div>
                     <?php if($totalpage > 1) { ?>
                    <div id="">

                      <ul class="pagination">
                         <p>Page Showing <?=$page?> of <?=$totalpage?></p>
                        <p style="display: none"> <?=$ppage = $page > 1 ? $page - 1 : 1?></p>
                        <p style="display: none"> <?=$npage = $page < $totalpage ? $page + 1 : $totalpage?></p>
                        <li class="page-item <?= $page == 1 ? 'disabled' : ''?>"><a class="page-link" href="<?= $_SERVER['SERVER_NAME'] == 'localhost' ? "/newhrcore/requisition_remark?q=$ppage&l=sl" : "/requisition_remark?q=$ppage&l=sl" ?>">Previous</a></li>
                      
                        <li class="page-item <?= $page == $totalpage ? 'disabled' : ''?>"><a class="page-link" href="<?= $_SERVER['SERVER_NAME'] == 'localhost' ? "/newhrcore/requisition_remark?q=$npage&l=sl" : "/requisition_remark?q=$npage&l=sl" ?>">Next</a></li>
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

        

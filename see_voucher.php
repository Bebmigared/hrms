<?php
// error_reporting(E_ALL);ini_set('display_errors',1);
include 'connection.php';
include 'connectionpdo.php';
setlocale(LC_MONETARY,"en_NG");

session_start();
$depts = [];
$leave_type = [];
$users = [];

//print_r($_SESSION['user']);
/*$query = "SELECT * FROM company";
$result = mysqli_query($conn, $query);
//if(mysqli_num_rows($result) > 0){
while($row = mysqli_fetch_assoc($result)) {

   //$row= $depts[0]['dept'];
  // $all_dept = explode(";",$d[0]['dept']);
//}
}*/


  $voucher_nos = mysqli_real_escape_string($conn, $_POST['voucher_no']); 
  $queries = "SELECT * FROM voucher where voucher_no ='".$voucher_nos."' ";
  $res = mysqli_query($conn, $queries);
  $rowy = mysqli_fetch_array($res, MYSQLI_ASSOC);
//   echo $rowy['client_name'];
  $id = $rowy['voucher_no'];

  $voucher_no = mysqli_real_escape_string($conn, $_POST['voucher_no']); 
  $query = "SELECT voucher.voucher_no, voucher.username, voucher.client_name, voucher.paying_bank, 
  voucher.cheque_no, voucher.currency, voucher.description, voucher.amount, voucher.amount_words, voucher.ed, voucher.md, voucher.created_at, users.name, users.fname FROM voucher INNER JOIN users ON voucher.username = users.id";
  $result = mysqli_query($conn, $query);
 //$result = $results;
  //if(mysqli_num_rows($result) > 0){
  
    if(mysqli_num_rows($result) > 0){
      while($row = mysqli_fetch_assoc($result)) {
         if($row['voucher_no'] == $id)
         {
            $voucher[] = $row;
            $v = $rowy;
         }
      }
  }
  //print_r($voucher);
  $amount = $rowy['amount'];



?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="asset/img/hr.png" type="image/ico" />

    <title>HR CORE </title>

   <!-- Bootstrap -->
   <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="/vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.min.css" rel="stylesheet"/>
    <link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/4.11.4/standard/ckeditor.js"></script>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col footer_fixed">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a class="site_title"><i class="fa fa-box"></i> <span>HR CORE</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
             <div style="">
            <div class="profile clearfix">
              <div class="profile_pic">
                <img src="images/<?=$_SESSION['user']['profile_image']?>" alt="." class="img-circle profile_img">
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2><?=$_SESSION['user']['name']?></h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
                <?php include 'sidebar.php'?>
            </div>
           
            <!-- /sidebar menu -->

            <!-- /menu footer buttons -->
            <!--div class="sidebar-footer hidden-small">
              <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                <span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="permission" href = "permission">
                <span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
              </a>
              <a data-toggle="tooltip" data-placement="top" title="Logout" href="index">
                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
              </a>
            </div-->
            <!-- /menu footer buttons -->
          </div>
        </div>
        <!-- top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Voucher <small></small></h3>
                <h2><?php //echo $rowy['client_name'];  ?></h2>
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
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                      <img src="https://icsoutsourcing.com/discovery/ICSOutsourcing.png" alt="ICS LOGO" width="100" height="75"> Payment Voucher
                    <!--<h2>ICS OUTSOURCING LTD <small></small></h2>-->
                    <ul class="nav navbar-right panel_toolbox">
                      

                        </ul>
                      </li>
                      
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <section class="content invoice">
                      <!-- title row -->
                      <div class="row">
                        <div class="col-xs-12 invoice-header">
                          <h3>
                                          
                                          <small class="pull-right"><b>Date Raised:</b> <?= $voucher[0]['created_at']  ?> </small>
                                          
                                      </h3>
                            Voucher No: <b><?= $rowy['voucher_no']  ?></b>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- info row -->
                      <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                          <h4>Recipient<h4>
                          <address>
                                          <h4><strong><?= $rowy['client_name']?></strong></h4>
                                          <!--<br>795 Freedom Ave, Suite 600
                                          <br>New York, CA 94107
                                          <br>Phone: 1 (804) 123-9876
                                          <br>Email: ironadmin.com-->
                                      </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            
                         <!-- Paying Bank
                          <address>
                                          <h4><strong><?= $rowy['paying_bank'] ?></strong></h4>
                                          <!--<br>795 Freedom Ave, Suite 600
                                          <br>New York, CA 94107
                                          <br>Phone: 1 (804) 123-9876
                                          <br>Email: jon@ironadmin.com
                                      </address>-->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                          <!--<b>Currency:</b> <?= $voucher[0]['currency']  ?>-->
                          <br>
                          <!--<b>Amount:</b> <?= money_format("%i ", $amount)?>-->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->

                      <!-- Table row -->
                      <div class="row">
                        <div class="col-xs-12 table">
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th>Cheque No</th>
                                <th>Amount</th>
                                <th style="width: 40%">Amount in words #</th>
                                <th style="width: 40%">Description</th>
                                <th style="width: 30%">Paying Bank</th>
                                
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td><?php echo $rowy['cheque_no'] ?></td>
                                <td><?= money_format("%i ", $amount)?></td>
                                <td><?= ($rowy['amount_words'])?></td>
                                <td> <?= ($rowy['description'])?></td>
                                <td><?= $rowy['paying_bank'] ?></td>
                               
                              </tr>
                             
                            </tbody>
                          </table>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->

                    
                   
                          <p>Processed by: 
                            <?php 
                                if(empty($voucher[0]['fname'])){
                                    echo 'Omolade';
                                } else {
                                    echo $voucher[0]['fname'];
                                }
                            ?>
                            <?php 
                                if(empty($voucher[0]['name'])){
                                    echo 'Olabisi';
                                } else {
                                    echo $voucher[0]['name'];
                                }
                            ?>
                          </p>
                          <?php if($rowy['ed'] == 1) {?>
                          <p>Endorsed by: Femi Akeredolu</p>
                          <?php }?>
                           <?php if($rowy['md'] == 1) {?>
                          <p>Approved by: Peter Akindeju</p>
                          <?php }?>
                          <small class="pull-right"><?= date('d-M-Y') ?></small>
                      <!-- this row will not appear when printing -->
                      <form action="process_voucher_app.php" method="post">
                      <div class="row no-print">
                        <div class="col-xs-12">
                        <input type ="hidden" name="voucher_no" value="<?=$voucher[0]['voucher_no']?>">
                          <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
                          <?php if($_SESSION['user']['role']=='ed') {?>
                          <button type="submit" name="endorse" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Endorse</button>
                          <?php } elseif ($_SESSION['user']['role']== 'md') {?>
                            
                          <button type="submit" name="approve" class="btn btn-primary pull-right" style="margin-right: 5px;"><i class="fa fa-download"></i> Approve</button>
                        <?php } ?>
                        </div>
                      </div>
                      </form>
                    </section>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
        
    <!-- jQuery -->
    <script src="/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="/vendors/nprogress/nprogress.js"></script>
 <!-- jQuery custom content scroller -->
 <script src="vendors/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="/build/js/custom.min.js"></script>
  </body>
</html>
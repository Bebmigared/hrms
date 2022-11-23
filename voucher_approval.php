<?php
include 'connection.php';
include 'connectionpdo.php';

session_start();
$depts = [];
$leave_type = [];
$users = [];

  $query = "SELECT * FROM voucher ORDER BY created_at DESC
  ";
  $result = mysqli_query($conn, $query);
  while($row = mysqli_fetch_assoc($result)) {

     $voucher[] = $row;

}

if(isset($_POST['search'])){
$keyword = mysqli_real_escape_string($conn, $_POST['keyword']);
if($keyword = ""){
    exit();
}
$query = "SELECT * FROM voucher WHERE amount LIKE '%".$keyword."%' AND md = '0' ORDER BY status DESC";
$result = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($result)) {

   $voucher[] = $row;
  
}
}

//print_r($voucher)


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
        <div class="col-md-3 left_col">
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
        <?php include "top.php"?>
            <div class="right_col" role="main">
            <div class="">
            <div class="page-title">
            <div class="title_left">
                <h3>Voucher<small>Entries</small></h3>
            </div>
            <div class="title_right">
            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                <form action="voucher_search.php" method="post"> 
            <div class="input-group">
            <input type="text" name="keyword" class="form-control" placeholder="Search for amount">
            <span class="input-group-btn">
            <button type="submit" class="btn btn-secondary" name="search" type="button">Go!</button>  
            
                  </form>

            </span>
            </div>
            </div>
            </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>View vouchers<small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        <ul class="dropdown-menu" role="menu">
                          <li><a href="#">Settings 1</a>
                          </li>
                          <li><a href="#">Settings 2</a>
                          </li>
                        </ul>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <p class="text-muted font-13 m-b-30">
                    <form method="POST" action="process_voucher.php">
                    <?php if($_SESSION['user']['role'] == 'ed') {?>
                    <button type="submit" name="endorse_all" class="btn btn-danger btn-xx">Endorse All</button>
                    <?php }?>
                    <?php if ($_SESSION['user']['role'] == 'md'){?>
                    <button type="submit" name="approve_all" class="btn btn-danger btn-xx">Approve All</button>
                    <?php }?>
                    </p>
                <div class="x_content">
                <table class="table table-bordered">
                <thead>
                        <tr>
                          <th>Voucher No</th>
                          <th>Company</th>
                          <th>Paying Bank</th>
                          <th>Cheque No</th>
                          <th>Description</th>
                          <th>Amount</th>
                          <!--<th>amount_words</th>-->
                          <th>created_at</th>
                          <th>Action</th>
                          <th>Edt</th>
                          <th>View</th>

                        </tr>
                      </thead>


                      <tbody>
                      <?php for ($data = 0; $data < count($voucher); $data++) {?>
                        <?php if($_SESSION['user']['role']=='md' && $voucher[$data]['ed']=1 && $voucher[$data]['md']!=1) {?>
                        <tr>
                          <td><?=$voucher[$data]['voucher_no']?></td>
                          <td><?=$voucher[$data]['client_name']?></td>
                          <td><?=$voucher[$data]['paying_bank']?></td>
                          <td><?=$voucher[$data]['cheque_no']?></td>
                          <td><?=$voucher[$data]['description']?></td>
                          <!--<td><?=$voucher[$data]['currency'] .''. number_format($voucher[$data]['amount'])?></td>-->
                          <td><?=$voucher[$data]['currency'] .''.$voucher[$data]['amount']?></td>
                          <!--<td><?=$voucher[$data]['amount_words']?></td>-->
                          <td><?=$voucher[$data]['created_at']?></td>
                         
        
                          <td>
                            <?php if(($voucher[$data]['ed']==1)) {?>
<!--                            <button type="button" class="btn btn-warning btn-xs"><?= (empty($voucher[$data]['status'])) ? 'Awaiting Endorsement' : $voucher[$data]['status']?></button>-->
                             <form action="process_voucher_app.php" method="post">
                      <div class="row no-print">
                        <div class="col-xs-12">
                        <input type ="hidden" name="voucher_no" value="<?=$voucher[$data]['voucher_no']?>">
                          <?php if($_SESSION['user']['role']=='md') {?>
                          <button type="submit" name="endorse" class="btn btn-danger pull-right"><i class="fa fa-credit-card"></i> Awaiting Approval</button>
                          <?php }?>
                        </div>
                      </div>
                      </form><?php }?>
                          </td>
                           
                         
                        <td>
                            <?php if(($voucher[$data]['ed']==1)) {?>
<!--                            <button type="button" class="btn btn-warning btn-xs"><?= (empty($voucher[$data]['status'])) ? 'Awaiting Endorsement' : $voucher[$data]['status']?></button>-->
                              <form action="see_voucher.php" method="POST">
                          <input type ="hidden" name="voucher_no" value="<?=$voucher[$data]['voucher_no']?>">
                          <input type="submit" name="submit" id="submit" value="View" class="btn btn-info btn-xs" />
                           <!-- <a href="/hrenterprise/see_voucher.php?voucher_no=<?= $voucher[$data]['voucher_no'] ?>" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>-->
                      </form><?php }?>
                            <!--<a href="#" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                            <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>-->
                          </td>
                       
                       
                       
                       </td>

        
                        </tr>
                        
                         <?php }}?>
                      </tbody>
                      </table>
                      




        <script src="vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="vendors/nprogress/nprogress.js"></script>
    <!-- iCheck -->
    <script src="vendors/iCheck/icheck.min.js"></script>
    <!-- Datatables -->
    <script src="vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="vendors/jszip/dist/jszip.min.js"></script>
    <script src="vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="vendors/pdfmake/build/vfs_fonts.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="build/js/custom.min.js"></script>

  </body>
</html>
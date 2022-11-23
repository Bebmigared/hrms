<?php
include 'connection.php';
include 'connectionpdo.php';

session_start();
$depts = [];
$leave_type = [];
$users = [];

//print_r($_SESSION['user']);
  $query = "SELECT item, quantity FROM requesteditem
  ";
  $result = mysqli_query($conn, $query);
  //if(mysqli_num_rows($result) > 0){
  while($row = mysqli_fetch_assoc($result)) {

     $sumitem[] = $row;
     //$row= $depts[0]['dept'];
    // $all_dept = explode(";",$d[0]['dept']);
  //}
 // print_r($sumitem);
}




if($_SESSION['user']['category'] == 'admin'){
    $query = "SELECT * FROM items";
  }else {
    $query = "SELECT * FROM items";
  }
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $data_item[] = $row;
      }
  }
  //print_r($data_item);



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
                <h3>Store Ledger<small></small></h3>
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
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Ledgers<small></small></h2>
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

                  <?php if (count($data_item) > 0) {?>  
<div class="x_content">
                    <p class="text-muted font-13 m-b-30">
                      ...
                    </p>
                    <table id="datatable-buttons" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                          <th>Materials</th>
                          <th style="background-color:gray; color:white;">Opening Stock Quantity</th>
                          <th style="background-color:gray; color:white;">Opening Stock Amount</th>
                          <th style="background-color:yellow; color:black;">Supply Quantity </th>
                          <th style="background-color:yellow; color:black;">Supply Rate</th>
                          <th style="background-color:yellow; color:black;">Supply Amount</th>
                          <th style="background-color:Orange; color:black;">Quantity After Supply</th>
                          <th style="background-color:orange; color:black;">Amount After Supply</th>
                          <th style="background-color:Green; color:white;">Quantity Issued</th>
                          <!--<th style="background-color:green; color:white;">Rate Issued</th>-->
                          <!--<th style="background-color:green; color:white;">Amount Issued </th>-->
                          <th style="background-color:yellow; color:black;">Quantity After Issued</th>
                         <!-- <th style="background-color:yellow; color:black;">Amount After Issued</th>-->
                          <?php } ?>
                        </tr>
                      </thead>


                      <tbody>
                      <?php for ($h = 0; $h < count($data_item); $h++) {?>
                        <tr>
                        <td class=""><?=$data_item[$h]['item_name']?></td>
                            <td class=" "><?=$data_item[$h]['opening_quantity']?></td>
                            <td class=" "><?=$data_item[$h]['opening_amount']?></td>
                            <td class=" "><?=$data_item[$h]['quantity_supply']?></td>
                            <td class=""><?=$data_item[$h]['amount_supply']?></td>
                            <td class=" "><?=$data_item[$h]['quantity_supply']*$data_item[$h]['amount_supply']?></td>
                            <td class=" "><?=$data_item[$h]['quantity_aftersupply']?></td>
                            <td class=" "><?=$data_item[$h]['item_cost']?></td>
                            <td class=""> <?php  //$query = "SELECT item, quantity FROM requesteditem";
                            $itemno = $data_item[$h]['id'];
  $result = mysqli_query($conn, "SELECT SUM(quantity) AS value_sum FROM requesteditem where item = $itemno "); 
  $row = mysqli_fetch_assoc($result); 
  $sum = $row['value_sum'];
  print_r($sum);
  ?>
     </td>
                           <!-- <td class=" "><?=$data_item[$h]['item_category']?></td>
                            <td class=" "><?=$data_item[$h]['item_quantity']?></td>-->
                            <td class=" "><?=$data_item[$h]['item_quantity']?></td>
                            <!--<td class=" "><?=$data_item[$h]['item_cost']?></td>-->
                        </tr>
                        
                        <?php } ?>
                        </tbody>
                    </table>
                  </div>
                </div>
              </div>



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
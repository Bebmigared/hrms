<?php 
include 'connection.php';
session_start();

$date = [];

function one_year($start_date,$end_date){
    $counter = 0;
    while(strtotime($start_date) <= strtotime($end_date)){
           $counter++;
       $start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
   
     }
     return $counter;
   }
   if(isset($_POST['submit'])){
   $leave_start = mysqli_real_escape_string($conn, filter_var($_POST['start_date'], FILTER_SANITIZE_STRING));
   $leave_end = mysqli_real_escape_string($conn, filter_var($_POST['end_date'], FILTER_SANITIZE_STRING));
   $staff_id = $_SESSION['user']['id'];
   $year = date('Y');

   if(date("N",strtotime($leave_start))>5){
    $_SESSION['msg1'] = 'You can not start your leave on Saturday or Sunday, kindly select the appropriate start Date';
    header("Location: proposed_leave.php");
    return false;
    }
    if(date("N",strtotime($leave_end))>5){
    $_SESSION['msg2'] = 'You can not end your leave on Saturday or Sunday, kindly select the appropriate End Date';
    header("Location: proposed_leave.php");
    return false;
    }

    else{
/*$staff_id = 1;//mysqli_real_escape_string($conn, $_POST['staff_id']);
$year = 2020;//mysqli_real_escape_string($conn, $_POST['year']);
$leave_start = '20/01/2020'; //mysqli_real_escape_string($conn, $_POST['leave_start']);
$leave_end = '30/02/2020'; //mysqli_real_escape_string($conn, $_POST['leave_ends']);*/

$query = "INSERT INTO proposed_leave (staff_id, year, leave_start, leave_end) VALUES ('".$staff_id."', '".$year."', '".$leave_start."', '".$leave_end."')";

if(mysqli_query($conn, $query)){
    $msg = 'Successfully Inserted';
}
    else
    {
        echo "Error:-" . $query . "<br>" . mysqli_error($conn);
    }
   }
}


$query1 = "SELECT * FROM proposed_leave WHERE staff_id = '".$_SESSION['user']['id']."' AND year = '".date('Y')."' ORDER BY staff_id LIMIT 1";
$result = mysqli_query($conn, $query1);
    while($row = mysqli_fetch_assoc($result)) {
      $date[] = $row;
      $startdate = $row['leave_start'];
      $enddate = $row['leave_end'];
      $year = $row['year'];
    }

    //The function returns the no. of business days between two dates and it skips the holidays
function getWorkingDays($startDate,$endDate){
    // do strtotime calculations just once
    $endDate = strtotime($endDate);
    $startDate = strtotime($startDate);


    //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
    //We add one to inlude both dates in the interval.
    $days = ($endDate - $startDate) / 86400 + 1;

    $no_full_weeks = floor($days / 7);
    $no_remaining_days = fmod($days, 7);

    //It will return 1 if it's Monday,.. ,7 for Sunday
    $the_first_day_of_week = date("N", $startDate);
    $the_last_day_of_week = date("N", $endDate);

    //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
    //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
    if ($the_first_day_of_week <= $the_last_day_of_week) {
        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
    }
    else {
        // (edit by Tokes to fix an edge case where the start day was a Sunday
        // and the end day was NOT a Saturday)

        // the day of the week for start is later than the day of the week for end
        if ($the_first_day_of_week == 7) {
            // if the start date is a Sunday, then we definitely subtract 1 day
            $no_remaining_days--;

            if ($the_last_day_of_week == 6) {
                // if the end date is a Saturday, then we subtract another day
                $no_remaining_days--;
            }
        }
        else {
            // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
            // so we skip an entire weekend and subtract 2 days
            $no_remaining_days -= 2;
        }
    }

    //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
   $workingDays = $no_full_weeks * 5;
    if ($no_remaining_days > 0 )
    {
      $workingDays += $no_remaining_days;
    }

    //We subtract the holidays
    /*foreach($holidays as $holiday){
        $time_stamp=strtotime($holiday);
        //If the holiday doesn't fall in weekend
        if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
            $workingDays--;
    }

    return $workingDays;*/
}

//Example:

//$holidays=array("2008-12-29","2008-12-16","2009-01-01");



//echo getWorkingDays(".$startdate.",".$enddate.");
// => will return 7

//print_r($date);
mysqli_close($conn);
?>  
<?php ?>
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

    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- bootstrap-datetimepicker -->
    <link href="vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <!-- Ion.RangeSlider -->
    <link href="vendors/normalize-css/normalize.css" rel="stylesheet">
    <link href="vendors/ion.rangeSlider/css/ion.rangeSlider.css" rel="stylesheet">
    <link href="vendors/ion.rangeSlider/css/ion.rangeSlider.skinFlat.css" rel="stylesheet">
    <!-- Bootstrap Colorpicker -->
    <link href="vendors/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css" rel="stylesheet">

    <link href="vendors/cropper/dist/cropper.min.css" rel="stylesheet">

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
        <!-- /top navigation -->
<div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Leave Planning</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                   
                  </div>
                </div>
              </div>
            </div>
<div class="clearfix"></div>
<?php if(isset($_SESSION['msg1'])) {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            <?=$_SESSION['msg1']?></div>
<?php }?>

<?php if(isset($_SESSION['msg2'])) {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            <?=$_SESSION['msg2']?>
                        </div>
<?php }?>

<?php if(isset($year) != date('Y')) {?>
  <!--                      <div class="alert alert-info">-->
  <!--  <strong>Hello!  </strong>Your proposed Leave Start & End Date are <?= $startdate; ?> and <?= $enddate;  ?>   Thank you!-->
  <!--</div>-->
  <?php } 
  
  else{?>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
</br>
</br>
                      
                    
                     
                        <h2> Kindly Plan your Annual Leave For the year <?= date('Y'); ?></h2>
                      <?php }?>
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
                  <?php if(isset($year) != date('Y')) {?>
                  <div class="x_content">
                    <br />
<form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="proposed_leave.php" method="post">

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Start Date <span class="required">*</span>
                        </label>
                        <div class='col-sm-4'>
                    Enter Start Date
                    <div class="form-group">
                        
                        <input type="date" class="form-control" value="<?=isset($date['leave_start'])?>" name="start_date" data-inputmask="'mask': '99/99/9999'">
                        
                    </div>
                </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">End Date <span class="required">*</span>
                        </label>
                        <div class='col-sm-4'>
                    Enter End Date
                    <div class="form-group">
                        
                        <input type="date" class="form-control" name="end_date"></div>
                    
                </div>
                      </div>                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          <button class="btn btn-primary" type="button">Cancel</button>
						  <button class="btn btn-primary" type="reset">Reset</button>
                          <button type="submit" name="submit" class="btn btn-success">Submit</button>
                        </div>
                      </div>

                    </form>
                    <?php } else{?>
                    <h2></h2>
                      <?php }?>
                  </div>
                </div>
              </div>
            </div>
<!-- jQuery -->
    <script src="vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="vendors/nprogress/nprogress.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="vendors/moment/min/moment.min.js"></script>
    <script src="vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap-datetimepicker -->    
    <script src="vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <!-- Ion.RangeSlider -->
    <script src="vendors/ion.rangeSlider/js/ion.rangeSlider.min.js"></script>
    <!-- Bootstrap Colorpicker -->
    <script src="vendors/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
    <!-- jquery.inputmask -->
    <script src="vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <!-- jQuery Knob -->
    <script src="vendors/jquery-knob/dist/jquery.knob.min.js"></script>
    <!-- Cropper -->
    <script src="vendors/cropper/dist/cropper.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="build/js/custom.min.js"></script>
    
    <!-- Initialize datetimepicker -->
<script>
    $('#myDatepicker').datetimepicker();
    
    $('#myDatepicker2').datetimepicker({
        format: 'DD.MM.YYYY'
    });
    
    $('#myDatepicker3').datetimepicker({
        format: 'hh:mm A'
    });
    
    $('#myDatepicker4').datetimepicker({
        ignoreReadonly: true,
        allowInputToggle: true
    });

    $('#datetimepicker6').datetimepicker();
    
    $('#datetimepicker7').datetimepicker({
        useCurrent: false
    });
    
    $("#datetimepicker6").on("dp.change", function(e) {
        $('#datetimepicker7').data("DateTimePicker").minDate(e.date);
    });
    
    $("#datetimepicker7").on("dp.change", function(e) {
        $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
    });
</script>
  </body>
</html>
<?php ?>
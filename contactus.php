<?php 
  include "connection.php";
  include "process_email.php";
  session_start();
  if(isset($_POST['submit'])){
      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $name = mysqli_real_escape_string($conn, $_POST['name']);
      $message = mysqli_real_escape_string($conn, $_POST['message']);
      $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
      if($email != "" && filter_var($email, FILTER_VALIDATE_EMAIL) && $name != '' && $message != '' && is_numeric($phone_number)){
        $server_provider = explode("@", $email)[1];
        $provider = explode(".", $server_provider)[0];
          if($provider == 'gmail' || $provider == 'yahoo' || $provider == 'hotmail' || $provider == 'rocketmail' || $provider == 'googlemail' || $provider == 'aol' || $provider == 'outlook' || $provider == 'mail' || $provider == 'icloud'){
          $_SESSION['msg'] = 'Kindly use your company Email';
          $_SESSION['name'] = $name;
          $_SESSION['message'] = $message;
          $_SESSION['phone_number'] = $phone_number;
          }else {
          $send_email = 'povuche@icsoutsourcing.com';
          //$send_email = 'ogunrindeomotayo@gmail.com';
          $msg = "<div><p>Hello,</p><p> A potential Client has requested for a Demo on HRCORE.</p><p>Below are the information submitted:</p>
              <p>Name : $name</p>
              <p>Email: $email</p>
              <p>Phone Number: $phone_number</p>
              <p>Message: $message</p>";
           if (filter_var($send_email, FILTER_VALIDATE_EMAIL)) {
                process_data($conn,$send_email,$msg,'Request a Demo');
               $_SESSION['msg'] = "Thank you for your Interest in HRCORE, you will get a responses from us shortly";
                  $_SESSION['name'] = '';
                  $_SESSION['message'] = '';
                  $_SESSION['phone_number'] = '';
                  $_SESSION['email'] = '';
            }
      }
    }else {
      $_SESSION['msg'] = "Company Name, Company Email and Message fields are required";
      $_SESSION['name'] = $name;
      $_SESSION['message'] = $message;
      $_SESSION['phone_number'] = $phone_number;
      $_SESSION['email'] = $email;
      //echo $message;
    }      
            
    
  }
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

    <meta name="keywords" content="
core+hr,hrcore+login,hrcore+contact+us,branding+hr,automated+hr,core+hr+solutions,core+hr+software,digital+transformation+for+hr,automate+hr+processes,core+time+and+attendance+system,payroll+system+implementation,core+hr+system,best+core+hr+software,human+resources+systems+and+processes,hr+compensation+and+benefits,performance+management+softwares,hr+payroll+software+on+cloud,workforce+managment+software,hr+recruitment+softwares,workforce+management+system,hr+benefits+software,software+for+staff+management,hr+softwares+free+download,talent+management+solutions+company,workforce+management+gartner,technology+in+talent+management,HR+Service+Management,Human+Resource+Management,Core+Human+Resource+Management+Software,Human+Resource+Management+and+Payroll+Software,HR+Management+Software+Solutions,Online+HR+Payroll+Services,Easy+Online+Payroll+Services+for+Small+Business,Easy+Online+HR+Service+for+Small+Business">
    <meta name="description" content="HRcore is an online HR, Payroll Management Software that helps with the day-to-day activities of your employee. Leave management, Staff Attendance, Payroll Management, Appraisal Management, Requisition System and Audit System. HRCore offers fully integrated online payroll services that includes HR, benefits, and everything else you need for your business and employees.">
	<title>HR CORE -Online HR, Payroll Management Software Solution</title>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-143952669-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-143952669-1');
</script>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="vendors/iCheck/skins/flat/green.css" rel="stylesheet">

    <!-- bootstrap-progressbar -->
    <link href="vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
  </head>
    <style type="text/css">
  .overlay{
    position: absolute;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
    background-color: #000;
    opacity: 0.4;
  }
  .login_user{
    position: absolute;
    z-index: 3;
    top:13%;
    width: 60%;
    left: 20%;
    right: 20%;
  }
  .for_login{
      width:80%;margin-left:auto;margin-right:auto;
  }
  html, body {
    background-image: url("images/efp-header.jpg") !important;
     background-repeat: no-repeat;
     background-position: center;
     background-size: cover;
    height: 100%;
  }
  @media only screen and (max-width: 768px) {
   .for_login{
      width:98%;margin-left:auto;margin-right:auto;
  } 
  .login_user{
    position: absolute;
    z-index: 3;
    top:13%;
    width: 98%;
    left: 1%;
    right: 1%;
  }
 }
 </style>
  <body class="nav-md">
    <div class="overlay"></div>
    <div class="container body" style="background-color: #fff;overflow-x:hidden;">
      <div class="">
        <div class="sign_in" role="main" style="width:100%;margin-left:5px;">
            <div class="login_user">
                <div class="clearfix"></div>
                <div class="row for_login" style="">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                      <div class="x_title">
                        <h2>CONTACT US</h2>
                        <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                        <br />
                        <div class="text-center"><a href ="index.php"><img class="logo-alt" src="asset/img/hr.png" alt="logo" style="width: 100px;height: 50px;"></a></div>
                         <?php if(isset($_SESSION['msg'])) {?>
                        <div class="alert alert-primary" style="background-color: red;color:#fff" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                        <?php } ?>
                        <form action = "contactus.php" method="POST" id="demo-form2" data-parsley-validate class="form-horizontal">
                          <div class="form-group">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                              <input type="name" id="first-name" name = "name" placeholder = "Company Name" class="form-control col-md-9 col-xs-12" value ='<?=isset($_SESSION['name']) ? $_SESSION['name'] : ''?>'>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                              <input type="text" id="" name="phone_number" placeholder="Phone Number" class="form-control col-md-7 col-xs-12" value ='<?=isset($_SESSION['phone_number']) ? $_SESSION['phone_number'] : ''?>'>
                              <?php if(isset($_SESSION['phone_number']) && !is_numeric($_SESSION['phone_number']) && $_SESSION['phone_number'] != '') { ?>
                              <small class='text-danger'>Not a valid Number</small>
                              <?php } ?>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                              <input type="email"  name="email" placeholder="Company Email Address (Only)" class="form-control col-md-7 col-xs-12" value ='<?=isset($_SESSION['email']) ? $_SESSION['email'] : ''?>'>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-md-12 col-sm-6 col-xs-12">
                              <textarea rows="8" id="" name = 'message' placeholder="message" class="form-control col-md-7 col-xs-12"><?=isset($_SESSION['message']) ? $_SESSION['message'] : ''?></textarea>
                            </div>
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <button type="submit" name = "submit" class="btn btn-success">Contact US</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                </div>
          </div>
        <!-- /page content -->
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
    <!-- Chart.js -->
    <script src="vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="vendors/Flot/jquery.flot.js"></script>
    <script src="vendors/Flot/jquery.flot.pie.js"></script>
    <script src="vendors/Flot/jquery.flot.time.js"></script>
    <script src="vendors/Flot/jquery.flot.stack.js"></script>
    <script src="vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="vendors/DateJS/build/date.js"></script>
    <!-- JQVMap -->
    <script src="vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="vendors/moment/min/moment.min.js"></script>
    <script src="vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="build/js/custom.min.js"></script>
	
  </body>
</html>

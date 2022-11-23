 <?php
  session_start(); 
  
  htmlspecialchars($_GET["nsa"]);
//$db = mysqli_connect('127.0.0.1', 'root', '', 'emadb');
$PayPlan = htmlspecialchars($_GET["nsa"]);
$payData = [];
include 'db_conn.php';
$sql = "SELECT * FROM payment_data WHERE reg_code= '$PayPlan'";
$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
//print_r($sql);
//return false;

if (mysqli_num_rows($result) > 0) {
    
        while ($row = mysqli_fetch_assoc($result)) { 
        //print_r($row);
        
        $payData[] = $row;
        $_SESSION['payData'] = $row;
        $Email = $_SESSION['payData']['email'];
        //echo $_SESSION['PayPlan'][0];
        //$data = $row['company_name'];
        //echo $data;
        
        //$Email = $_SESSION['PayOther'][0]['email'];
//print_r($payData);
//return false;
        //header ('location: ../event1.php');
        }
        
}
else {
  echo 'Couldnt connect';
  //$error = 'Couldnt GET DATA!!';
}

/*
if (isset($_POST[''])) {
  # code...
}*/
  ?>
<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  
  <title>Features & Pricing of HRcore – HRCore Human Resources Management Solution</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">

  <!-- Favicons -->
  <link href="https://salesmanager.ng/landimg/favicon.png" rel="icon">
  <link href="https://salesmanager.ng/landimg/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Montserrat:300,400,500,700" rel="stylesheet">

  <!-- Bootstrap CSS File -->
  <link href="bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS Files -->
  <link href="font-awesome.min.css" rel="stylesheet">
  <link href="animate.min.css" rel="stylesheet">
  <link href="ionicons.min.css" rel="stylesheet">
  <link href="owl.carousel.min.css" rel="stylesheet">
  <link href="lightbox.min.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="style.css" rel="stylesheet">

  <!-- =======================================================
    Theme Name: NewBiz
    Theme URL: https://bootstrapmade.com/newbiz-bootstrap-business-template/
    Author: BootstrapMade.com
    License: https://bootstrapmade.com/license/
  ======================================================= -->
</head>
<style>
    #pricing {
  padding: 80px 0;
}
#testimonials{
    box-shadow:none !important;
}

#pricing .card {
  border: 0;
  border-radius: 0px;
  box-shadow: 0 3px 0px 0 rgba(65, 62, 102, 0.08);
  transition: all .3s ease-in-out;
  padding: 36px 0;
  position: relative;
  box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.08);
}

#pricing .card:after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 0%;
  height: 5px;
  background-color: #1bb1dc;
  transition: 0.5s;
}

#pricing .card:hover {
  -webkit-transform: scale(1.05);
  transform: scale(1.05);
  box-shadow: 0 20px 35px 0 rgba(0, 0, 0, 0.08);
}

#pricing .card:hover:after {
  width: 100%;
}

#pricing .card .card-header {
  background-color: white;
  border-bottom: 0px;
  -moz-text-align-last: center;
  text-align-last: center;
}

#pricing .card .card-title {
  margin-bottom: 16px;
  color: #535074;
}

#pricing .card .card-block {
  padding-top: 0;
  text-align: center;
}

#pricing .card .list-group-item {
  border: 0px;
  padding: 6px;
  color: #413e66;
  font-weight: 400;
}

#pricing .card h3 {
  font-size: 40px;
  margin-bottom: 0px;
  color: #535074;
}

#pricing .card h3 .currency {
  font-size: 30px;
  position: relative;
  font-weight: 400;
  top: -30px;
  letter-spacing: 0px;
}

#pricing .card h3 .period {
  font-size: 16px;
  color: #6c67a3;
  letter-spacing: 0px;
}

#pricing .card .list-group {
  margin-bottom: 15px;
}

#pricing .card .btn {
  text-transform: uppercase;
  font-size: 13px;
  font-weight: 500;
  color: #5f5b96;
  border-radius: 0;
  padding: 10px 24px;
  letter-spacing: 1px;
  border-radius: 3px;
  display: inline-block;
  background: #00428a;
  color: #fff;
}

#pricing .card .btn:hover {
  background: #0a98c0;
}
</style>
<body>

  <!--==========================
  Header
  ============================-->
  <header id="header" class="fixed-top">
    <div class="container">

      <div class="logo float-left">
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <h1 class="text-light"><a href="#header"><span>NewBiz</span></a></h1> -->
        <a href="/" class="scrollto"><img src="core.png" alt="" class="img-fluid" style='width:200px;height:auto'></a>
      </div>

      <nav class="main-nav float-right d-none d-lg-block">
        <ul>
          <li><a href="https://salesmanager.ng/">Home</a></li>
          <li><a href="https://salesmanager.ng/login">Login</a></li>
          </li>
        </ul>
      </nav><!-- .main-nav -->
      
    </div>
  </header><!-- #header -->

 
    <main id="main">
    
      <!--==========================-->
    <!--  Contact Section-->
    <!--============================-->-->
    <section id="contact">
      <div class="container-fluid">

        <div class="section-header">
          <h3><br>Confirm details and make Payment</h3>
        </div>

        <div class="row wow fadeInUp">

          <div class="col-lg-6">
            <div class="map mb-4 mb-lg-0">
              <img src="HRCore.png" class="img-fluid" alt=""></div>
          </div>
          
          <?php if($_SESSION['payData']['plan_type'] == 'Basic Plan'){?>
            <!--BEGIN OF BASIC PAYEMENT-->
            
          <div class="col-lg-6">
            <div class="row info">
              <!--<div class="col-md-5 info">-->
                <i class="ion-ios-arrow-forward"></i>
                <p style="font-size: 120%;">Confirm details and make Payment for <?php echo $_SESSION['payData']['plan_type']; ?> </p>
              <!--</div>-->
              
              
            </div>

            <div class="form">
              <form action="" method="post">
                           <script src="https://js.paystack.co/v1/inline.js"></script>
                <div class="form-row">
                  <div class="form-group col-lg-12">
                    <input type="text" name="company_name" class="form-control" id="name" value="<?php echo $_SESSION['payData']['company_name'];?>"  disabled>
                  </div>    
                   <div class="form-group col-lg-6">
                    <input type="text" name="product" class="form-control" value="<?php echo $_SESSION['payData']['plan_type'];?>" id="product"  disabled>
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="text" class="form-control" name="cost" id="cost" value="<?php echo $_SESSION['payData']['plan_cost'];?>"  disabled>
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="text" name="name" class="form-control" id="name" value="<?php echo $_SESSION['payData']['name'];?>" disabled>
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="email" class="form-control" name="email" id="email" value="<?php echo $_SESSION['payData']['email'];?>" disabled>
                    <input type="text" class="form-control" name="EMAIL" id="EMAIL" value="<?php echo $Email;?>"  style="display:none;">
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="number" class="form-control" name="phone" id="phone" value="<?php echo $_SESSION['payData']['phone_number'];?>" disabled>
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="text" class="form-control" name="where" id="where" value="<?php echo $_SESSION['payData']['how_did_you_hear'];?>"  disabled>
                    <input type="text" class="form-control" name="payment_status" value="paid" style="display: none;">
                    <input type="text" name="payment_date" value="<?php echo date('Y-m-d');?>" class="form-control" style="display: none;">
                  </div>
                </div>
                <div class="form-group">
                  <textarea class="form-control" name="message" value="<?php echo $_SESSION['payData']['other_details'];?>" rows="5" disabled></textarea>
                </div>
                <div class="text-center">
                  <!--<input class="btn btn-primary" name="submit_payment"  onclick="payWithPaystack()" id="paystack" type="button" value="Make Payment">-->
                  <input class="btn btn-primary" name="submit_pay"  id="submitdata" type="submit" value="Make Payment">
                </div>
              </form>
            </div>
          </div>
          
          <!--END OF BASIC PAYEMENT-->
          <?php } ?>
          
          
          <?php if($_SESSION['payData']['plan_type'] == 'Regular Plan'){?>
          <div class="col-lg-6">
            <div class="row info">
              <!--<div class="col-md-5 info">-->
                <i class="ion-ios-arrow-forward"></i>
                <p style="font-size: 120%;">Confirm details and make Payment for <?php echo $_SESSION['payData']['plan_type']; ?> </p>
              <!--</div>-->
              
              
            </div>

            <div class="form">
              <form action="" method="post">
                           <script src="https://js.paystack.co/v1/inline.js"></script>
                <div class="form-row">
                  <div class="form-group col-lg-12">
                    <input type="text" name="company_name" class="form-control" id="name" value="<?php echo $_SESSION['payData']['company_name'];?>"  disabled>
                  </div>    
                   <div class="form-group col-lg-6">
                    <input type="text" name="product" class="form-control" value="<?php echo $_SESSION['payData']['plan_type'];?>" id="product"  disabled>
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="text" class="form-control" name="cost" id="cost" value="<?php echo $_SESSION['payData']['plan_cost'];?>"  disabled>
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="text" name="name" class="form-control" id="name" value="<?php echo $_SESSION['payData']['name'];?>" disabled>
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="email" class="form-control" name="email" id="email" value="<?php echo $_SESSION['payData']['email'];?>" disabled>
                    <input type="text" class="form-control" name="EMAIL" id="EMAIL" value="<?php echo $Email;?>"  style="display:none;">
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="number" class="form-control" name="phone" id="phone" value="<?php echo $_SESSION['payData']['phone_number'];?>" disabled>
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="text" class="form-control" name="where" id="where" value="<?php echo $_SESSION['payData']['how_did_you_hear'];?>"  disabled>
                    <input type="text" class="form-control" name="payment_status" value="paid" style="display: none;">
                    <input type="text" name="payment_date" value="<?php echo date('Y-m-d');?>" class="form-control" style="display: none;">
                  </div>
                </div>
                <div class="form-group">
                  <textarea class="form-control" name="message" value="<?php echo $_SESSION['payData']['other_details'];?>" rows="5" disabled></textarea>
                </div>
                <div class="text-center">
                  <input class="btn btn-primary" name="submit_payment"  onclick="payWithPaystack1()" id="paystack" type="button" value="Make Payment">
                  <input class="btn btn-primary" name="submit_pay"  id="submitdata1" type="submit" >
                </div>
              </form>
            </div>
          </div>
          <?php } ?>
          
          <?php if($_SESSION['payData']['plan_type'] == 'Premium Plan'){?>
          <div class="col-lg-6">
            <div class="row info">
              <!--<div class="col-md-5 info">-->
                <i class="ion-ios-arrow-forward"></i>
                <p style="font-size: 120%;">Confirm details and make Payment for <?php echo $_SESSION['payData']['plan_type']; ?> </p>
              <!--</div>-->
              
              
            </div>

            <div class="form">
              <form action="" method="post">
                           <script src="https://js.paystack.co/v1/inline.js"></script>
                <div class="form-row">
                  <div class="form-group col-lg-12">
                    <input type="text" name="company_name" class="form-control" id="name" value="<?php echo $_SESSION['payData']['company_name'];?>"  disabled>
                  </div>    
                   <div class="form-group col-lg-6">
                    <input type="text" name="product" class="form-control" value="<?php echo $_SESSION['payData']['plan_type'];?>" id="product"  disabled>
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="text" class="form-control" name="cost" id="cost" value="<?php echo $_SESSION['payData']['plan_cost'];?>"  disabled>
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="text" name="name" class="form-control" id="name" value="<?php echo $_SESSION['payData']['name'];?>" disabled>
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="email" class="form-control" name="email" id="email" value="<?php echo $_SESSION['payData']['email'];?>" disabled>
                    <input type="text" class="form-control" name="EMAIL" id="EMAIL" value="<?php echo $Email;?>"  style="display:none;">
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="number" class="form-control" name="phone" id="phone" value="<?php echo $_SESSION['payData']['phone_number'];?>" disabled>
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="text" class="form-control" name="where" id="where" value="<?php echo $_SESSION['payData']['how_did_you_hear'];?>"  disabled>
                    <input type="text" class="form-control" name="payment_status" value="paid" style="display: none;">
                    <input type="text" name="payment_date" value="<?php echo date('Y-m-d');?>" class="form-control" style="display: none;">
                  </div>
                </div>
                <div class="form-group">
                  <textarea class="form-control" name="message" value="<?php echo $_SESSION['payData']['other_details'];?>" rows="5" disabled></textarea>
                </div>
                <div class="text-center">
                  <input class="btn btn-primary" name="submit_payment"  onclick="payWithPaystack2()" id="paystack" type="button" value="Make Payment">
                  <input class="btn btn-primary" name="submit_pay"  id="submitdata2" type="submit" >
                </div>
              </form>
            </div>
          </div>
          <?php } ?>
          
          <?php if($_SESSION['payData']['plan_type'] == 'Business Plan'){?>
          <div class="col-lg-6">
            <div class="row info">
              <!--<div class="col-md-5 info">-->
                <i class="ion-ios-arrow-forward"></i>
                <p style="font-size: 120%;">Confirm details and make Payment for <?php echo $_SESSION['payData']['plan_type']; ?> </p>
              <!--</div>-->
              
              
            </div>

            <div class="form">
              <form action="" method="post">
                           <script src="https://js.paystack.co/v1/inline.js"></script>
                <div class="form-row">
                  <div class="form-group col-lg-12">
                    <input type="text" name="company_name" class="form-control" id="name" value="<?php echo $_SESSION['payData']['company_name'];?>"  disabled>
                  </div>    
                   <div class="form-group col-lg-6">
                    <input type="text" name="product" class="form-control" value="<?php echo $_SESSION['payData']['plan_type'];?>" id="product"  disabled>
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="text" class="form-control" name="cost" id="cost" value="<?php echo $_SESSION['payData']['plan_cost'];?>"  disabled>
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="text" name="name" class="form-control" id="name" value="<?php echo $_SESSION['payData']['name'];?>" disabled>
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="email" class="form-control" name="email" id="email" value="<?php echo $_SESSION['payData']['email'];?>" disabled>
                    <input type="text" class="form-control" name="EMAIL" id="EMAIL" value="<?php echo $Email;?>"  style="display:none;">
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="number" class="form-control" name="phone" id="phone" value="<?php echo $_SESSION['payData']['phone_number'];?>" disabled>
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="text" class="form-control" name="where" id="where" value="<?php echo $_SESSION['payData']['how_did_you_hear'];?>"  disabled>
                    <input type="text" class="form-control" name="payment_status" value="paid" style="display: none;">
                    <input type="text" name="payment_date" value="<?php echo date('Y-m-d');?>" class="form-control" style="display: none;">
                  </div>
                </div>
                <div class="form-group">
                  <textarea class="form-control" name="message" value="<?php echo $_SESSION['payData']['other_details'];?>" rows="5" disabled></textarea>
                </div>
                <div class="text-center">
                  <input class="btn btn-primary" name="submit_payment"  onclick="payWithPaystack3()" id="paystack" type="button" value="Make Payment">
                  <input class="btn btn-primary" name="submit_pay"  id="submitdata3" type="submit" >
                </div>
              </form>
            </div>
          </div>
          <?php } ?>

        </div>

      </div>
    </section><!-- #contact -->
      <!--========================== 
      ===============================-->
  </main>
  <!--==========================
    Footer
  ============================-->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-4 col-md-6 footer-info">
            <h3>HRCORE</h3>
            <p>HRCORE is a flexible software that helps with the day-to-day activities of the company. It includes leave management, appraisal management, requisition management, cash request management, audit management, Employee payroll. All with customized approvals</p>
            <a href="https://play.google.com/store/apps/details?id=sale.manager.starter" class="scrollto"><img src="google-play.png" alt="" class="img-fluid"></a>
          </div>

          <div class="col-lg-2 col-md-6 footer-links">
            <h4>Useful Links</h4>
            <ul>
              <li><a href="#contact">Sign Up for free</a></li>
              <li><a href="https://www.salesmanager.ng/pricing">Compare Plan</a></li>
              <li><a href="#contact">Request a Demo</a></li>
            </ul>
          </div>
          <div class="col-lg-2 col-md-6 footer-links">
            <h4>Our Solutions</h4>
            <ul>
              <li><a href="https://hrcore.ng">HRCore</a></li>
              <li><a href="https://hotelanywhere.ng">HotelAnywhere</a></li>
              <li><a href="https://eventguru.ng">Eventguru</a></li>
              <li><a href="https://preventpro.ng">Preventpro</a></li>
              <li><a href="https://smoothride.ng">SmoothRide Ridesharing App for Business</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-contact">
            <h4>Contact Us</h4>
            <p>
              6, Olusoji Idowu Street <br>
              Ilupeju, Lagos State<br>
              Nigeria <br>
              <strong>Phone:</strong> 234-8090202323<br>
              <strong>Email:</strong> enquiries@icsoutsourcing.com<br>
            </p>

          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong>Sales Manager</strong>. All Rights Reserved
      </div>
      <div class="credits"> Designed by <a href="https://icsoutsourcing.com/digital-solutions/">ICS Digital</a>
      </div>
    </div>
  </footer><!-- #footer -->

  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
  <!-- Uncomment below i you want to use a preloader -->
  <!-- <div id="preloader"></div> -->
  
  
  
  <script type="text/javascript">
function payWithPaystack(){
    let email = $('#EMAIL').val();
    let amount = 100;
    var handler = PaystackPop.setup({
      //key: 'pk_live_61cbf727786a5c521a98990828f66b7e6dac6c97',
      //key: 'pk_test_152fc0d1d5b76e4291a4cadc0b7d0c969e04ce16';
      key: 'pk_test_152fc0d1d5b76e4291a4cadc0b7d0c969e04ce16',
      email: email,
      amount: amount,
      currency: "NGN",
      ref: ''+Math.floor((Math.random() * 000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
      metadata: {
         custom_fields: [
            {
                display_name: "Mobile Number",
                variable_name: "mobile_number",
                value: "+2348077392969"
            }
         ]
      },
      callback: function(response){
          //alert('success. transaction ref is ' + response.reference);
          $('#reference').val(response.reference);
          $('#message').val(response.message);
          $('#status').val(response.status);
          $('#transaction').val(response.transaction);
          $('#amount').val(response.amount);
          
           //$.post("verify.php", {reference:response.reference}, function(status){
                //if(status == "success")
                    //successful transaction
                    //alert('Transaction was successful');
                //else
                    //transaction failed
                    //alert(response);
                    //alert('Transaction UNSUCCESSFUL');
            //});
            
          $('#submitdata').trigger('click');
          console.log(response);
          
      },
      onClose: function(){
          alert('You have cancelled your online payment!');
          $('#submitdata').trigger('click');
          window.location.replace("https://www.hrcore.ng");
          //echo $_SERVER['HTTP_REFERER'];
          //alert('window closed');
          
      }
    });
    handler.openIframe();
  }
        </script>
        
        <script type="text/javascript">
function payWithPaystack1(){
    let email = $('#EMAIL').val();
    let amount = 4000000;
    var handler = PaystackPop.setup({
      //key: 'pk_live_61cbf727786a5c521a98990828f66b7e6dac6c97',
      //key: 'pk_test_152fc0d1d5b76e4291a4cadc0b7d0c969e04ce16';
      key: 'pk_test_152fc0d1d5b76e4291a4cadc0b7d0c969e04ce16',
      email: email,
      amount: amount,
      currency: "NGN",
      ref: ''+Math.floor((Math.random() * 4000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
      metadata: {
         custom_fields: [
            {
                display_name: "Mobile Number",
                variable_name: "mobile_number",
                value: "+2348077392969"
            }
         ]
      },
      callback: function(response){
          //alert('success. transaction ref is ' + response.reference);
          $('#reference').val(response.reference);
          $('#message').val(response.message);
          $('#status').val(response.status);
          $('#transaction').val(response.transaction);
          $('#amount').val(response.amount);
          
           //$.post("verify.php", {reference:response.reference}, function(status){
                //if(status == "success")
                    //successful transaction
                    //alert('Transaction was successful');
                //else
                    //transaction failed
                    //alert(response);
                    //alert('Transaction UNSUCCESSFUL');
            //});
            
          $('#submitdata1').trigger('click');
          console.log(response);
          
      },
      onClose: function(){
          alert('You have cancelled your online payment!');
          $('#submitdata').trigger('click');
          window.location.replace("https://www.hrcore.ng");
          //echo $_SERVER['HTTP_REFERER'];
          //alert('window closed');
          
      }
    });
    handler.openIframe();
  }
        </script>
        <script type="text/javascript">
function payWithPaystack2(){
    let email = $('#EMAIL').val();
    let amount = 7500000;
    var handler = PaystackPop.setup({
      //key: 'pk_live_61cbf727786a5c521a98990828f66b7e6dac6c97',
      //key: 'pk_test_152fc0d1d5b76e4291a4cadc0b7d0c969e04ce16';
      key: 'pk_test_152fc0d1d5b76e4291a4cadc0b7d0c969e04ce16',
      email: email,
      amount: amount,
      currency: "NGN",
      ref: ''+Math.floor((Math.random() * 7500000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
      metadata: {
         custom_fields: [
            {
                display_name: "Mobile Number",
                variable_name: "mobile_number",
                value: "+2348077392969"
            }
         ]
      },
      callback: function(response){
          //alert('success. transaction ref is ' + response.reference);
          $('#reference').val(response.reference);
          $('#message').val(response.message);
          $('#status').val(response.status);
          $('#transaction').val(response.transaction);
          $('#amount').val(response.amount);
          
           //$.post("verify.php", {reference:response.reference}, function(status){
                //if(status == "success")
                    //successful transaction
                    //alert('Transaction was successful');
                //else
                    //transaction failed
                    //alert(response);
                    //alert('Transaction UNSUCCESSFUL');
            //});
            
          $('#submitdata2').trigger('click');
          console.log(response);
          
      },
      onClose: function(){
          alert('You have cancelled your online payment!');
          $('#submitdata').trigger('click');
          window.location.replace("https://www.hrcore.ng");
          //echo $_SERVER['HTTP_REFERER'];
          //alert('window closed');
          
      }
    });
    handler.openIframe();
  }
        </script>
        <script type="text/javascript">
function payWithPaystack3(){
    let email = $('#EMAIL').val();
    let amount = 20000000;
    var handler = PaystackPop.setup({
      //key: 'pk_live_61cbf727786a5c521a98990828f66b7e6dac6c97',
      //key: 'pk_test_152fc0d1d5b76e4291a4cadc0b7d0c969e04ce16';
      key: 'pk_test_152fc0d1d5b76e4291a4cadc0b7d0c969e04ce16',
      email: email,
      amount: amount,
      currency: "NGN",
      ref: ''+Math.floor((Math.random() * 20000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
      metadata: {
         custom_fields: [
            {
                display_name: "Mobile Number",
                variable_name: "mobile_number",
                value: "+2348077392969"
            }
         ]
      },
      callback: function(response){
          //alert('success. transaction ref is ' + response.reference);
          $('#reference').val(response.reference);
          $('#message').val(response.message);
          $('#status').val(response.status);
          $('#transaction').val(response.transaction);
          $('#amount').val(response.amount);
          
           //$.post("verify.php", {reference:response.reference}, function(status){
                //if(status == "success")
                    //successful transaction
                    //alert('Transaction was successful');
                //else
                    //transaction failed
                    //alert(response);
                    //alert('Transaction UNSUCCESSFUL');
            //});
            
          $('#submitdata3').trigger('click');
          console.log(response);
          
      },
      onClose: function(){
          alert('You have cancelled your online payment!');
          $('#submitdata').trigger('click');
          window.location.replace("https://www.hrcore.ng");
          //echo $_SERVER['HTTP_REFERER'];
          //alert('window closed');
          
      }
    });
    handler.openIframe();
  }
        </script>

  <!-- JavaScript Libraries -->
  <script src="https://salesmanager.ng/landlib/jquery/jquery.min.js"></script>
  <script src="https://salesmanager.ng/landlib/jquery/jquery-migrate.min.js"></script>
  <script src="https://salesmanager.ng/landlib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="https://salesmanager.ng/landlib/easing/easing.min.js"></script>
  <script src="https://salesmanager.ng/landlib/mobile-nav/mobile-nav.js"></script>
  <script src="https://salesmanager.ng/landlib/wow/wow.min.js"></script>
  <script src="https://salesmanager.ng/landlib/waypoints/waypoints.min.js"></script>
  <script src="https://salesmanager.ng/landlib/counterup/counterup.min.js"></script>
  <script src="https://salesmanager.ng/landlib/owlcarousel/owl.carousel.min.js"></script>
  <script src="https://salesmanager.ng/landlib/isotope/isotope.pkgd.min.js"></script>
  <script src="https://salesmanager.ng/landlib/lightbox/js/lightbox.min.js"></script>
  <!-- Contact Form JavaScript File -->
  

  <!-- Template Main Javascript File -->
  <script src="https://salesmanager.ng/landjs/main.js"></script>

</body>
</html>

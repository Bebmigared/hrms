<?php 
 htmlspecialchars($_GET["plan"]);
$plan = $_GET["plan"];

include 'db_conn.php';
//echo "My Plan is ".$plan." ";
//return false;
//$plan = isset($_POST['taskOption']) ? $_POST['taskOption'] : false;
   //if ($option) {
      //echo htmlentities($_POST['taskOption'], ENT_QUOTES, "UTF-8");
   //}

if ($plan =='Basic') {
  # code...
  $plan_name = 'Basic Plan';
  $plan_cost = '₦0.00/Year';
}
elseif ($plan =='Regular') {
  # code...
  $plan_name = 'Regular Plan';
  $plan_cost = '₦40,000.00/Year';
}
elseif ($plan =='Premium') {
  # code...
  $plan_name = 'Premium Plan';
  $plan_cost = '₦75,000.00/Year';
}
elseif ($plan =='Business') {
  # code...
  $plan_name = 'Business Plan';
  $plan_cost = '₦200,000.00/Year';
}
//echo "My Plan Name is ".$plan_name." ";
//echo "My Plan Cost is ".$plan_cost." ";
//return false;


if (isset($_POST['submit_payment'])) {
  # code...
  function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

  $reg_code = generateRandomString();

  $company_name = $_POST['company_name'];
  $plan_type = $_POST['plan_name'];
  $plan_cost = $_POST['plan_cost'];
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $how = $_POST['where'];
  $message = $_POST['message'];
  $date = $_POST['date'];


  /*echo $company_name;
  echo $plan_type;
  echo $plan_cost;
  echo $name;
  echo $email;
  echo $phone;
  echo $how;
  echo $message;
  echo $date;
  echo " While code is ";
  echo $reg_code;
  return false;
  */


$query = "INSERT INTO payment_data (company_name, plan_type, plan_cost, name, email, phone_number, how_did_you_hear, other_details, registration_date, reg_code) VALUES ('$company_name','$plan_type','$plan_cost','$name','$email','$phone','$how','$message', '$date', '$reg_code')";

$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

if($result){
        $last_id = mysqli_insert_id($conn);
                    $order_id = $last_id;
                    $sql = "SELECT * FROM payment_data WHERE id= '$order_id'";
                    $result2 = mysqli_query($conn, $sql) or die(mysql_error($conn));

                    if (mysqli_num_rows($result2) != 0) {
    //while ($row = $resultdetail->fetch_assoc()) {
        while ($row = mysqli_fetch_assoc($result2)) {
        $code = $row['reg_code'];
        //header ('location: cand_data.php');
        //echo "<script language='javascript' type='text/javascript'> location.href='../cand_data.php' </script>";

        echo "<script type='text/javascript'>alert('Your Subscription details has been submitted successfully')</script>";
        echo "<script language='javascript' type='text/javascript'> location.href='confirm.php?nsa=$code&type=plan' </script>";
        }
}

        
        //header ('location: index.php');
    }
    else{
         echo "<script type='text/javascript'>alert('Your Subscription Details has NOT submitted!! Try Again!!')</script>";
    }
    
  //echo "<script type='text/javascript'>alert('You want to submit payment!!')</script>";

}


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
    <!--============================-->
    <section id="contact">
      <div class="container-fluid">

        <div class="section-header">
          <h3><br>Sign up for HR Core TODAY</h3>
        </div>

        <div class="row wow fadeInUp">

          <div class="col-lg-6">
            <div class="map mb-4 mb-lg-0">
              <img src="HRCore.png" class="img-fluid" alt=""></div>
          </div>

          <div class="col-lg-6">
            <div class="row info">
              <!--<div class="col-md-5 info">-->
                <i class="ion-ios-arrow-forward"></i>
                <p style="font-size: 120%;">You have chosen <?php echo $plan_name; ?> </p>
              <!--</div>-->
              
              
            </div>

            <div class="form">
              <!--<div id="sendmessage">Your demo request has been sent. Thank you!</div>-->
              <div id="errormessage"></div>
              <form action="" method="post">
                <!--<input type="hidden" name="_token" value="tAlwoizSUtBKcfPNe4bsMOXL1i756UtugCjTSvrb">-->           
                <div class="form-row">
                  <div class="form-group col-lg-12">
                    <input type="text" name="company_name" class="form-control" id="company_name" placeholder="Your Company Name"  required>
                    <!--<div class="validation"></div>-->
                  </div>    
                   <div class="form-group col-lg-6">
                    <input type="text" class="form-control" value="<?php echo $plan_name; ?>" required disabled>
                    <input type="text" name="plan_name" class="form-control" value="<?php echo $plan_name; ?>" id="plan_name"  required style="display: none;">
                    <!--<div class="validation"></div>-->
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="plan_cost" class="form-control" value="<?php echo $plan_cost; ?>" required disabled>
                    <input type="plan_cost" class="form-control" name="plan_cost" id="plan_cost" value="<?php echo $plan_cost; ?>" required style="display: none;">
                    <!--<div class="validation"></div>-->
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
                    <!--<div class="validation"></div>-->
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
                    <!--<div class="validation"></div>-->
                  </div>
                  <div class="form-group col-lg-6">
                    <input type="number" class="form-control" name="phone" id="phone" placeholder="Your Phone Number" required>
                    <!--<div class="validation"></div>-->
                  </div>
                  <div class="form-group col-lg-6">
                    <!--<input type="text" class="form-control" name="where" id="where" placeholder="Where did you hear about us?"  />-->
                    <select class="form-control" name="where" id="where" required>
                      <option>Where did you hear about us?</option>
                      <option>Online advert</option>
                      <option>Email advert</option>
                      <option>From a current user of HR Core</option>                      
                    </select>
                    <!--<div class="validation"></div>-->
                    <input type="text" name="date" value="<?php echo date('Y-m-d');?>" class="form-control" style="display: none;">
                  </div>
                </div>
                <div class="form-group">
                  <textarea class="form-control" name="message" rows="5" placeholder="Please write something that you expect from HR Core"required></textarea>
                  <!--<div class="validation"></div>-->
                </div>
                <div class="text-center">
                  <button name="submit_payment" type="submit" title="Send Message">Register</button>
                </div>
              </form>
            </div>
          </div>

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

          <!--div class="col-lg-3 col-md-6 footer-newsletter">
            <h4>Our Newsletter</h4>
            <p>Subscribe to receive bi-weekly updates from us.</p>
            <form action="" method="post">
              <input type="email" name="email"><input type="submit"  value="Subscribe">
            </form>
          </div-->

        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong>Sales Manager</strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!--
          All the links in the footer should remain intact.
          You can delete the links only if you purchased the pro version.
          Licensing information: https://bootstrapmade.com/license/
          Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=NewBiz
        -->
        Designed by <a href="https://icsoutsourcing.com/digital-solutions/">ICS Digital</a>
      </div>
    </div>
  </footer><!-- #footer -->

  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
  <!-- Uncomment below i you want to use a preloader -->
  <!-- <div id="preloader"></div> -->

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

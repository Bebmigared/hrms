<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  
  <title>HRCORE</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">

  <!-- Favicons -->
  <link href="asset/img/hr.png" rel="icon">
  <link href="asset/img/hr.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,500,600,700,700i|Montserrat:300,400,500,600,700" rel="stylesheet">

  <!-- Bootstrap CSS File -->
  <link href="homelib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- homelibraries CSS Files -->
  <link href="homelib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="homelib/animate/animate.min.css" rel="stylesheet">
  <link href="homelib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="homelib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="homelib/lightbox/css/lightbox.min.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="homecss/style.css" rel="stylesheet">

</head>
<style type="text/css">
  .ourform{
    width: 50%;margin-left: auto;margin-right: auto
  }
    @media only screen and (max-width: 768px) {
      .ourform {
        width:90%;margin-left: auto;margin-right: auto
      }
    }
</style>
<body>
  <!--==========================
  Header
  ============================-->
  <header id="header">

    <!--div id="topbar">
      <div class="container">
        <div class="social-links">
          <a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
          <a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
          <a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a>
          <a href="#" class="instagram"><i class="fa fa-instagram"></i></a>
        </div>
      </div>
    </div-->

    <div class="container">

      <div class="logo float-left">
        <!-- Uncomment below if you prefer to use an image logo -->
        <h1 class="text-light"><a href="#intro" class="scrollto"><span>HRCORE</span></a></h1>
        <!-- <a href="#header" class="scrollto"><img src="img/logo.png" alt="" class="img-fluid"></a> -->
      </div>

      <nav class="main-nav float-right d-none d-lg-block">
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="login.php">Login</a></li>
          <li><a href="#footer">Book A Demo</a></li>
          <li><a href="price.php">Pricing</a></li>
          
          <li><a href="#footer">Contact Us</a></li>
        </ul>
      </nav><!-- .main-nav -->
      
    </div>
  </header><!-- #header -->


  <main id="main">

    <!--==========================
      About Us Section
    ============================-->
   <section id="pricing" class="wow fadeInUp section-bg">

      <div class="container">


        <div class="row flex-items-xs-middle flex-items-xs-center" >
          
        <div class='ourform' style="">
          <?php if(isset($_SESSION['msg'])) {?>
          <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
              <?=$_SESSION['msg']?>
          </div>
          <?php unset($_SESSION['msg']); ?>
          <?php } ?> 
        </div>

         
      
        </div>
      </div>

    </section><!-- #pricing -->

  


    <section id="call-to-action" class="wow fadeInUp">
      <div class="container">
        <div class="row">
          <div class="col-lg-9 text-center text-lg-left">
            <h3 class="cta-title">Call To Action</h3>
            <p class="cta-text">Thinking about how to be more productive at work. Try HR CORE and manage your company day to day activities seamlessly. Why wait?</p>
          </div>
          <div class="col-lg-3 cta-btn-container text-center">
            <a class="cta-btn align-middle" href="#footer">Book A Demo</a>
          </div>
        </div>

      </div>
    </section><!-- #call-to-action -->


  </main>

  <!--==========================
    Footer
  ============================-->
  <footer id="footer" class="section-bg">
    <div class="footer-top">
      <div class="container">

        <div class="row">

          <div class="col-lg-6">

            <div class="row">

                <div class="col-sm-6">

                  <div class="footer-info">
                    <h3>HRCORE</h3>
                    <p>HRCORE is a flexible software that helps with the day-to-day activities of the company. 
                 It includes leave management, appraisal management, requisition management, cash request management, audit management, Employee payroll. All with customized approvals</p>
                  </div>

                </div>

                <div class="col-sm-6">
                  <div class="footer-links">
                    <h4>Useful Links</h4>
                    <ul>
                      <li><a href="price.php">Pricing</a></li>
                      <li><a href="login.php">Login</a></li>
                    </ul>
                  </div>

                  <div class="footer-links">
                    <h4>Contact Us</h4>
                    <p>
                      6 Olusoji Idowu Street <br>
                      Ilupeju<br>
                      Lagos <br>
                      <strong>Phone:</strong> +23490 - 8739 - 9360<br>
                      <strong>Email:</strong> info@hrcore.com<br>
                    </p>
                  </div>

                  <!--div class="social-links">
                    <a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
                    <a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
                    <a href="#" class="instagram"><i class="fa fa-instagram"></i></a>
                    <a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a>
                  </div-->

                </div>

            </div>

          </div>

          <div class="col-lg-6">

            <div class="form">
              
              <h4>Send us a message</h4>
              <p>Send us Message</p>
              <form action="process_contactus.php" method="post" role="form" class="oldcontactForm">
                <div class="form-group">
                  <input type="text" name="name" value=<?=$_SESSION['name']?> class="form-control" id="name" placeholder="Your Name" />
                  
                </div>
                <div class="form-group">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Your Email"  />
                  
                </div>
                <div class="form-group">
                  <input type="text" value=<?=$_SESSION['phone_number']?> class="form-control" name="phone_number" id="phone_number" placeholder="Phone Number"  />
                </div>
                <div class="form-group">
                  <textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="Please write something for us" placeholder="Message"><?=$_SESSION['message']?></textarea>
                 
                </div>

                <div class="text-center"><button type="submit" name = "submit" title="Send Message">Send Message</button></div>
              </form>
            </div>

          </div>

          

        </div>

      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; Copyright <strong>HRCORE</strong>. All Rights Reserved
      </div>
     
    </div>
  </footer><!-- #footer -->

  <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
  <!-- Uncomment below i you want to use a preloader -->
  <!-- <div id="preloader"></div> -->

  <!-- JavaScript homelibraries -->
  <script src="homelib/jquery/jquery.min.js"></script>
  <script src="homelib/jquery/jquery-migrate.min.js"></script>
  <script src="homelib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="homelib/easing/easing.min.js"></script>
  <script src="homelib/mobile-nav/mobile-nav.js"></script>
  <script src="homelib/wow/wow.min.js"></script>
  <script src="homelib/waypoints/waypoints.min.js"></script>
  <script src="homelib/counterup/counterup.min.js"></script>
  <script src="homelib/owlcarousel/owl.carousel.min.js"></script>
  <script src="homelib/isotope/isotope.pkgd.min.js"></script>
  <script src="homelib/lightbox/js/lightbox.min.js"></script>
  <!-- Contact Form JavaScript File -->
  <script src="homecontactform/contactform.js"></script>

  <!-- Template Main Javascript File -->
  <script src="homejs/main.js"></script>

</body>
</html>

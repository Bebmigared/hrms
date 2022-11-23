<?php 
 include "connection.php";
 session_start();
 //if(!isset($_SESSION['requestID'])) header("Location: login");
 $sql = "SELECT * from recruitment INNER JOIN users ON recruitment.admin_id = users.id where recruitment.requestID = '".$_SESSION['requestID']."'";
   $result = mysqli_query($conn,$sql);
   if(mysqli_num_rows($result) > 0){
     while($row = mysqli_fetch_assoc($result)){
       $recruitment[] = $row;
     }
   }
   //print_r($recruitment);
?>
<style>
    .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
      background-color: #fff;
    }
     
</style>
  
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
  
    <!-- bootstrap-progressbar -->
    <link href="vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>
    <!-- bootstrap-daterangepicker -->
    <link href="vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css">



    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
  </head>

  <body class="nav-md">
    <div class="container body" style="background-color: #f8fafc;overflow-x:hidden;">
      <div class="">
        <!-- top navigation -->
        <?php include 'top.php' ?>
        <!-- /top navigation -->
        <!-- page content -->
        <div class="right_col" role="main" style="width:100%;margin-left:0px;">
            <div class="">
                <div class="page-title">
                  <div class="title_left">
                    <h3>Upload Shortlisted Candidate</h3>
                  </div>
    
                  <div class="title_right">
                    <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                      <div class="input-group">
                        <!--input type="text" class="form-control" placeholder="Search for...">
                        <span class="input-group-btn">
                          <button class="btn btn-default" type="button">Go!</button>
                        </span-->
                      </div>
                    </div>
                  </div>
                </div>
                <div class="clearfix"></div> 
                <?php if(isset($_SESSION['msg'])) {?>
                        <div class="alert alert-primary" style="color:#fff;background-color: #007bff;" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                <?php } ?>
                <div class="row settings" style="">
                  <div class="alert alert-primary" style="background-color: blue;color:#fff" role="alert">
                      Kindly upload Excel file of the shortlisted candidates that meet the requirement listed below.
                  </div>
                  <div class="col-md-7 col-sm-12 col-xs-12">
                    <div class="x_panel">
                      <div class="x_title">
                        <h2>Job Request </h2>
                        <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                        <br />
                        <form id="" action="process_shortlisted.php" method="POST" enctype="multipart/form-data" data-parsley-validate class="form-horizontal form-label-left">
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_email">Job Title
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input name="job_title" disabled = 'true' class="form-control" value="<?=isset($recruitment[0]['job_title']) ? $recruitment[0]['job_title'] :''?>"/>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Job Description
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <textarea disabled="true" name = "job_description" class="form-control col-md-7 col-xs-12"><?=isset($recruitment[0]['job_description']) ? $recruitment[0]['job_description'] :''?></textarea>
                              
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Qualification Required
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <textarea disabled="true" name = "qualification" class="form-control col-md-7 col-xs-12"><?=isset($recruitment[0]['qualification']) ? $recruitment[0]['qualification'] :''?></textarea>
                              
                            </div>
                          </div> 
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Priority Level
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" disabled="true" id="fname" value="<?=isset($recruitment[0]['priority_level']) ? $recruitment[0]['priority_level'] :''?>" name = "priority_level" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Date Created
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" id="fname" disabled="true" value="<?=isset($recruitment[0]['request_date_created']) ? $recruitment[0]['request_date_created'] :''?>" name = "date_created" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Upload File (Excel sheet Only)
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="file" name ="file" id="upload" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div> 
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">RO Name</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name ="ro_name" id="" class="form-control col-md-7 col-xs-12">
                              <input type="text" style="display: none" value="<?=isset($recruitment[0]['id']) ? $recruitment[0]['id'] :''?>" name ="admin_id" id="" class="form-control col-md-7 col-xs-12">
                              <input type="text" style="display: none;" value="<?=isset($recruitment[0]['requestID']) ? $recruitment[0]['requestID'] :''?>" name ="requestID" id="" class="form-control col-md-7 col-xs-12">
                              <input type="text" style="display: none;" value="<?=isset($recruitment[0]['filename']) ? $recruitment[0]['filename'] :''?>" name ="filename" id="" class="form-control col-md-7 col-xs-12">
                            </div>
                          </div>  
                          <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                              <button type="submit" name="submit" class="btn btn-success">Submit</button>
                            </div>
                          </div>      
                        </form>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-5 col-sm-12 col-xs-12">
                    <div class="x_panel">
                      <div class="x_title">
                        <h2>Request By</h2>
                        <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                        <br />
                        <form data-parsley-validate class="form-horizontal form-label-left">
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="admin_email">People Manager Name<span class="required"></span>
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <input name="name" disabled = 'true' class="form-control" id = "user_company" value="<?=isset($recruitment[0]['name']) ? $recruitment[0]['name'] :''?>"/>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">People Manager Phone <span class="required"></span>
                            </label>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <input type="text" id="phone" disabled = 'true' name = "name" class="form-control col-md-7 col-xs-12" value="<?=isset($recruitment[0]['phone_number']) ? $recruitment[0]['phone_number'] :''?>">
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">People Manager Email <span class="required"></span>
                            </label>
                            <div class="col-md-9 col-sm-6 col-xs-12">
                              <input type="text" id="email" value="<?=isset($recruitment[0]['email']) ? $recruitment[0]['email'] :''?>" disabled = 'true' value="" name = "fname" class="form-control col-md-7 col-xs-12">
                            </div>
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
    <script src="/build/js/custom.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.all.min.js"></script>
    <script type="text/javascript" src="js/staff_settings.js"></script>
    <script type="text/javascript">
       $(function(){
        $(".loading").fadeOut("fast");
        $("#admin_id").on("change", function(e){
          let value = $(this).val();
          $(".loading_company").text("Loading Company details.....")
           $.post("specific_company.php",
            { admin_id: value },
            function(data, status){
               let company = atob(data);
               //alert(company);
               let companies = [];
                companies = company.split(";");
                companies.forEach(function(each_company){
                  $("#user_company").append("<option value = '"+each_company+"'> "+each_company+"</option>");
                  $(".loading_company").text('');
                })
            });
        });

       });
    </script>
  </body>
</html>

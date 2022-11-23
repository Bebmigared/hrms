<?php 
include 'connection.php';
session_start();
$kss = [];
$admin_id;
$users = [];
$limit = 3;
$allusers = [];
$totalpage = 0;
$showatonce = 4;
$_SESSION['stafflist_data'] = 1;
unset($_SESSION['Is_search']);
 if(isset( $_GET['q']))
 {
    $_SESSION['stafflist_data'] = $_GET['q'];
 }
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
 if($_SESSION['user']['category'] == 'staff') $admin_id = $_SESSION['user']['admin_id'];
 else if ($_SESSION['user']['category'] == 'admin') $admin_id = $_SESSION['user']['id'];
  $query = "SELECT kss.information,users.name,users.employee_ID FROM kss INNER JOIN users ON kss.staff_id = users.id WHERE kss.admin_id = '".$admin_id."'ORDER BY kss.id LIMIT 1";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $kss[] = $row;
      }
  }


 if(!isset($_SESSION['stafflist_data']))
 {
    $_SESSION['stafflist_data'] = 1;
    $offset = 0;
 }
 else 
 {
  $offset = ((int)$_SESSION['stafflist_data'] - 1) * $limit;
 } 
 if($_SESSION['user']['category'] == 'staff') $admin_id = $_SESSION['user']['admin_id'];
 else if ($_SESSION['user']['category'] == 'admin') $admin_id = $_SESSION['user']['id'];
  //$query = "SELECT * FROM users WHERE companyId = '".$_SESSION['user']['companyId']."' LIMIT $limit OFFSET $offset";
  $query = "SELECT * FROM users";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
      }
  }

  //$query = "SELECT * FROM users WHERE companyId = '".$_SESSION['user']['companyId']."'";
  $query = "SELECT * FROM users ORDER by name ASC ";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $allusers[] = $row;
        //print_r($allusers);
      }
  }

  $totalpage = (count($allusers) / count($users));
  $totalpage = ceil($totalpage);
  //print_r($totalpage);

  if(isset($_POST['submit']))
  {
        $_SESSION['Is_search'] = true;
        $users = [];
        $allusers = [];
        $query = "SELECT * FROM users WHERE name = '".trim($_POST['name'])."' AND companyId = '".$_SESSION['user']['companyId']."' LIMIT $limit OFFSET $offset";
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result)> 0){
            while($row = mysqli_fetch_assoc($result)) {
              $users[] = $row;
            }
        }

        $query = "SELECT * FROM users WHERE  name = '".$_POST['name']."' AND companyId = '".$_SESSION['user']['companyId']."'";
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result)> 0){
            while($row = mysqli_fetch_assoc($result)) {
              $allusers[] = $row;
            }
        }
        if(count($users) > 0)
        {
            $totalpage = (count($allusers) / count($users));
            $totalpage = ceil($totalpage);
        }
        else 
          $totalpage = 0;
        
  }
  
  function checkdept($conn, $id){
      
      $query = "SELECT dept from department WHERE id =$users[$h]['department']";
      $result = mysqli_query($conn, $query);
       while($row = mysqli_fetch_assoc($result)) {
              $cdept[] = $row;
              //print_r($query);
            }
      
  }

 if(isset( $_POST['resetpass']))
 {
   $user= mysqli_real_escape_string($conn, $_POST['userid']);
   $newpassword= 'selfservice';
   $newpass= password_hash($newpassword,PASSWORD_DEFAULT);
    $query22 = "UPDATE users SET password = '$newpass' AND cpassword = 'selfservice' WHERE id = '$user'";
    //echo var_dump ($query22);
    //exit();
    if (mysqli_query($conn, $query22)) {
    echo "<script type='text/javascript'>alert('User New Password is selfservice');
      window.location='staff_directory.php';
      </script>";
    } else {
  echo "Error updating record: " . mysqli_error($conn);
    }
        
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
                <h3>STAFF DIRECTORY</h3>
              </div>
               <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">

                <form method="post" action="staff_directory.php">
                  <div class="input-group">
                    <input type="text" name="name" class="form-control" required="true" placeholder="Search By Employee Name">
                    <span class="input-group-btn">
                      <button name="submit" class="btn btn-default" type="submit">Go!</button>
                    </span>
                  </div>
                </form>

                </div>
              </div>

              <!-- <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                  </div>
                </div>
              </div> -->
            </div>

            <div class="clearfix"></div>
             <a style="<?= (isset($_SESSION['Is_search']) && $_SESSION['Is_search'] == true) ? '' : 'display: none'?>" href="<?= $_SERVER['SERVER_NAME'] == 'localhost' ? "/newhrcore/staff_directory?q=1&l=sl" : "/staff_directory?q=1&l=sl" ?>" type="submit" class="btn btn-success">Return to List</a>
            <div class="row">
               <?php if(isset($_SESSION['msg'])) {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                  <?php } ?>   
              <div class="col-md-12 col-sm-12 col-xs-12">
                <?php if (count($users) > 0) {?>
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Staffs Information</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li>
                      <?php if($_SESSION['user']['category'] == 'admin') { ?>      
                      <form action="process_file.php" method="post">
                            <input type="text" name="which" value = "directory" style="display: none;">
                            <button type="submit" id="btnExport"
                                name='export' value="Export to Excel"
                                class="btn btn-info">Export to Excel</button>
                        </form>

                        <?php } ?>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <!--<div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">-->
                       <div class="table-responsive">
                      <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N</th>
                            <th class="column-title">Name</th>
                            <th class="column-title">Employee ID </th>
                            <th class="column-title">Email </th>
                            <th class="column-title">SBU</th>
                            <th class="column-title">Branch</th>
                            <th class="column-title">Phone Number</th>
                            <?php if($_SESSION['user']['category'] == 'admin' || $_SESSION['user']['leave_processing_permission'] == '1') { ?>
                            <th class="column-title">Reset Password</th>
                            <th class="column-title">Disable User</th>
                            <th class="column-title">Terminate User</th>
                            <?php } ?>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($users); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td style="text-Transform:capitalize" class=""><?=$users[$h]['name']?> <?=$users[$h]['fname']?> <?=$users[$h]['mname']?></td>
                            <td class=" "><?=$users[$h]['employee_ID']?></td>
                            <td class=" "><?=$users[$h]['email']?></td>
                            <td style="text-Transform:capitalize" class=" ">
                             <?=$users[$h]['department']?>
                              <?php
                              /* $query = "SELECT departments from user WHERE id = '".$users[$h]['department']."'";
                                $result = mysqli_query($conn, $query);
                                if(mysqli_num_rows($result)> 0){
                                    while($row = mysqli_fetch_assoc($result)) {
                                      echo $row['dept'];
                                    }
                                }*/
                                ?>
                            </td>
                            <td style="text-Transform:capitalize" class=" ">
                                
                             <?=$users[$h]['branch']?>
                              <?php
                               /*$query = "SELECT * FROM branches WHERE id = '".$users[$h]['branch']."'";
                                $result = mysqli_query($conn, $query);
                                if(mysqli_num_rows($result)> 0){
                                    while($row = mysqli_fetch_assoc($result)) {
                                      echo $row['name'];
                                    }
                                }*/
                                ?>
                            </td>
                            <td class=" "><?=$users[$h]['phone_number']?></td>
                            <?php if($_SESSION['user']['category'] == 'admin' || $_SESSION['user']['leave_processing_permission'] == '1') { ?>
                            
                            <td class=" ">
                                
                            <form action="staff_directory.php" method="POST">
                            <input type="text" value=' <?=$users[$h]['id']?>' name ='userid' hidden />
                                <input type="submit" name ="resetpass" class="btn btn-warning" value="Reset Password"  />    
                                </form>
                            </td>
                            
                            <td class=" ">
                           
                            <form action= "disable_user.php" method ="POST">
                           
                            <input type="text" value=' <?=$users[$h]['id']?>' name ='userid' hidden />
                            <?php if($users[$h]['active'] == 3) { ?>
                              <input type="submit" name ="enable" class="btn btn-info" value="enable"  />

<?php } else {?>
                           
                            <input type="submit" name ="disable" class="btn btn-warning" value="Disable"  />
                            </form>
                            </td>
                           <?php }?>
                           
                                                      <td class=" ">
                           
                            <form action= "disable_user.php" method ="POST">
                           
                            <input type="text" value=' <?=$users[$h]['id']?>' name ='userid' hidden />
                            <?php if($users[$h]['active'] == 4) { ?>
                              <input type="submit" name ="enable" class="btn btn-success" value="undo"  />

<?php } else {?>
                           
                            <input type="submit" name ="disable" class="btn btn-danger" value="Terminate"  />
                            </form>
                            </td>
                           <?php }}}?>
                        </tbody>
                      </table>

                    </div>
                  </div>
                  <?php if($totalpage > 1) { ?>
                  <div id="">

                  <!--  <ul class="pagination">
                       <p>Page Showing <?=$_SESSION['stafflist_data']?> of <?=$totalpage?></p>
                      <p style="display: none"> <?=$ppage = $_SESSION['stafflist_data'] > 1 ? $_SESSION['stafflist_data'] - 1 : 1?></p>
                      <p style="display: none"> <?=$npage = $_SESSION['stafflist_data'] < $totalpage ? $_SESSION['stafflist_data'] + 1 : $totalpage?></p>
                      <li class="page-item <?= $_SESSION['stafflist_data'] == 1 ? 'disabled' : ''?>"><a class="page-link" href="<?= $_SERVER['SERVER_NAME'] == 'localhost' ? "/newhrcore/staff_directory?q=$ppage&l=sl" : "/staff_directory?q=$ppage&l=sl" ?>">Previous</a></li>
                    
                      <li class="page-item <?= $_SESSION['stafflist_data'] == $totalpage ? 'disabled' : ''?>"><a class="page-link" href="<?= $_SERVER['SERVER_NAME'] == 'localhost' ? "/newhrcore/staff_directory?q=$npage&l=sl" : "/staff_directory?q=$npage&l=sl" ?>">Next</a></li>
                    </ul>-->
                  </div>
                <?php } ?> 
                </div>
                <?php } ?> 
                <?php if (count($users) == 0) {?>
                  <h3 style="text-align: center">No Data Found</h3>
                <?php  } ?>  
              </div>
              <!-- <div class="col-md-4 col-sm-12 col-xs-12">
                  <div class="x_panel">
                  <div class="x_title">
                    <h2>KSS shared by (<span style="font-size: 13px;"><?=isset($kss[0]['name']) ? $kss[0]['name'] :''?></span>)</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php if(count($kss) > 0) {?>
                    <p style="text-align: justify;"><?=$kss[0]['information']?></p>
                    <?php } else { ?>
                      <p style="text-align: justify;">No Information shared</p>
                    <?php } ?> 
                  </div>
                </div>
              </div> -->
              <div class="col-md-4 col-sm-12 col-xs-12">
                
              </div>
            </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
<?php include "footer.php"?>
<script type="text/javascript" src="js/appraisal.js"></script>
<script type="text/javascript" src="js/pagination.js"></script>
<script type="text/javascript">
  
</script>
        


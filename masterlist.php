<?php 
include 'connection.php';
session_start();
$employee = [];
$admin_id;
if(!isset($_SESSION['user']['id'])) header("Location: dashboard.php");
if($_SESSION['user']['category'] == 'staff') $admin_id = $_SESSION['user']['admin_id'];
if($_SESSION['user']['category'] == 'admin') $admin_id = $_SESSION['user']['id'];
//print_r($admin_id);
$query = "SELECT employee_info.id, employee_info.first_name, employee_info.last_name, employee_info.employee_ID FROM employee_info WHERE admin_id = '".$admin_id."'";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result)> 0){
  while($row = mysqli_fetch_assoc($result)) {
    $employee[] = $row;
  }
}
for ($o = 0; $o < count($employee); $o++){
    $ID[] = $employee[$o]['employee_ID'];
}
$query = "SELECT * FROM users WHERE admin_id = '".$admin_id."'";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result)> 0){
  while($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
  }
}
$p =0 ;
for($f =0;$f < count($users); $f++) {
    if(!in_array(strtolower($users[$f]['employee_ID']), $ID)) {
        $ID[] = $users[$f]['employee_ID'];
        //print_r($addbranch);
        $p = 1;
        $sql = "INSERT INTO employee_info (first_name,last_name,date_of_birth,status,gender,middle_name,employee_ID, admin_id,insert_by) VALUES ('".$users[$f]['fname']."','".$users[$f]['name']."','".$users[$f]['dob']."','".$users[$f]['marital_status']."','".$users[$f]['gender']."','".$users[$f]['mname']."' ,'".$users[$f]['employee_ID']."','".$admin_id."','".$admin_id."')";
        if (mysqli_query($conn,$sql ) === TRUE) {
              updateTable($conn,$users[$f]['employee_ID'],$users[$f]['branch'],$admin_id);
          } else {
              echo "Error: " . $sql . "<br>" . mysqli_error($conn);
          }
    }
}
if($p == 1){
    header("Location: masterlist.php");
}
function updateTable($conn,$employee_ID,$branch,$admin_id){
    $query = "SELECT * FROM branches WHERE name = '".$branch."' AND admin_id = '".$admin_id."'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $branch_data[] = $row;
      }
    }
    //print_r($branch_data);
    $sql = "UPDATE employee_info SET branch_id = '".$branch_data[0]['id']."' WHERE employee_ID = '".$employee_ID."'";
    if (mysqli_query($conn, $sql)) {
        $msg = '0';
    }else{
        $msg = '2';
    }
    return $msg;
}
//print_r($employee);
?>
<?php include "header.php"?>
<div class="right_col" role="main">
<div class="">
        <div class="page-title">
          <div class="title_left">
            <h3>Employee Master List</h3>
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
            <?php if(isset($_SESSION['msg'])) {?>
                        <div class="alert alert-primary" style="background-color: #d1ecf1;" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
            <?php } ?>        
                
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Employee Data</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a href ="employee" class="btn btn-success btn-sm" style="color: #fff;">Add Employee</a>
                      </li>
                      <li>
                      <form action = 'payslip_pdf.php' method = 'POST'>
                            <input type="text" name="slipbranch" value = "all" style="display: none;">
                            <button type="submit" name ='submit' class="btn btn-danger">Share Payslip</button>
                      </form>
                      </li>
                      <li>
                      <form action="process_file.php" method="post">
                            <input type="text" name="which" value = "masterlist" style="display: none;">
                            <button type="submit" id="btnExport"
                                name='export' value="Export to Excel"
                                class="btn btn-info">Export to Excel</button>
                        </form>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                   <?php if(count($employee) > 0) {?>  
                    <div class="table-responsive">
                      
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title text-center">S/N </th>
                            <th class="column-title text-center">Name </th>
                            <th class="column-title text-center">Employee ID </th>
                            <th class="column-title text-center">Option </th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php if(count($employee) > 0) {?>
                          <?php for ($h = 0; $h < count($employee); $h++) {?>
                            
                          <tr class="pointer">
                            <td class="a-center text-center">
                              <?=$h + 1?>
                            </td>
                            <td class="text-center"><?=$employee[$h]['first_name']?> <?=$employee[$h]['last_name']?></td>
                            <td class="text-center"><?=$employee[$h]['employee_ID']?></td>
                            <td class="text-center">
                              <div class="btn-group" role="group" aria-label="Basic example">
                                <a class="btn btn-primary btn-sm" href="editemployee.php/?id=<?=base64_encode($employee[$h]['id'])?>">Edit</a>
                                <a class="btn btn-success btn-sm" href="showemployee_payslip.php/?id=<?=base64_encode($employee[$h]['id'])?>">payslip</a>
                                <!--a class="btn btn-danger btn-sm" href="sendemployee_payslip.php/?id=<?=base64_encode($employee[$h]['id'])?>">Email</a-->
                              </div>
                            </td>
                          </tr>
                          <?php } } ?>
                        </tbody>
                      </table>
                    </div>
                  <?php }else { ?>
                    <p>No Employee added</p>
                  <?php } ?> 
                  </div>
                </div>
              </div>    
             
        </div>
</div>
</div>
<?php include "footer.php"?>
        

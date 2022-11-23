<?php 
include 'connection.php';
session_start();
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
$data_branch = [];
$admin_id;
if($_SESSION['user']['category'] == 'staff') $admin_id = $_SESSION['user']['admin_id'];
if($_SESSION['user']['category'] == 'admin') $admin_id = $_SESSION['user']['id'];
$sql = "SELECT * FROM users WHERE admin_id = '".$admin_id."' AND branch != ''";
$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result)> 0){
  while($row = mysqli_fetch_assoc($result)) {
    $user_branch[] = $row;
  }
}
$p=0;
$query = "SELECT * FROM branches WHERE company_id = '".$_SESSION['user']['companyId']."'";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result)> 0){
  while($row = mysqli_fetch_assoc($result)) {
    $data_branch[] = $row;
  }
}
?>
<?php include "header.php"?>
<div class="right_col" role="main">
<div class="">
        <div class="page-title">
          <div class="title_left">
            <h3>Manage Branch</h3>
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
                    <h2>Branch</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a href ="addbranch.php" class="btn btn-success btn-sm" style="color: #fff;">Add Branch</a>
                      </li>
                      <li>
                      <form action="process_file.php" method="post">
                            <input type="text" name="which" value = "branch" style="display: none;">
                            <button type="submit" id="btnExport"
                                name='export' value="Export to Excel"
                                class="btn btn-info">Export to Excel</button>
                        </form>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                   <?php if(count($data_branch) > 0) {?>  
                    <div class="table-responsive">
                      
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title text-center">S/N </th>
                            <th class="column-title text-center">Branch Name </th>
                            <th class="column-title text-center">Address </th>
                            <th class="column-title text-center">Phone Number </th>
                            <th class="column-title text-center">Email </th>
                            <th class="column-title text-center">Option </th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php if(count($data_branch) > 0) {?>
                          <?php for ($h = 0; $h < count($data_branch); $h++) {?>
                            
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class="text-center"><?=$data_branch[$h]['name']?></td>
                            <td class="text-center"><?=$data_branch[$h]['address']?></td>
                            <td class="text-center"><?=$data_branch[$h]['phone_number']?></td>
                            <td class="text-center"><?=$data_branch[$h]['email']?></td>
                            <td class="text-center">
                              <div class="btn-group" role="group" aria-label="Basic example">
                                <a class="btn btn-primary btn-sm edit" email ="<?=$data_branch[$h]['email']?>" address = "<?=$data_branch[$h]['address']?>" phone="<?=$data_branch[$h]['phone_number']?>" name="<?=$data_branch[$h]['name']?>" id="<?=$data_branch[$h]['id']?>" thisid = "<?=$data_branch[$h]['id']?>" data-toggle="modal" data-target="#exampleModal">Edit</a>
                              </div>
                            </td>
                          </tr>
                          <?php } } ?>
                        </tbody>
                      </table>
                    </div>
                  <?php }else { ?>
                    <p>No branch added</p>
                  <?php } ?> 
                  </div>
                </div>
              </div>    
             
        </div>
</div>
 <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Branch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                 <form action = 'process_addbranch.php' method = "POST">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Branch Name</label>
                    <input type="text" id="name" name ="name" class="form-control" aria-describedby="emailHelp" placeholder="">
                  </div>
                   <div class="form-group">
                                <label class="" for="first-name">Phone Number<span class="required">*</span>
                                </label>
                                    <input id="phone_number" name="phone_number" class="form-control" required="required" type="text" value = "">
                        </div>
                        <div class="form-group">
                                <label class="" for="first-name">Email<span class="required">*</span>
                                </label>
                                <input id="email" name="email" class="form-control" required="required" type="email" value = "">
                        </div>

                        <div class="form-group">
                                <label class="" for="first-name">Address<span class="required">*</span>
                                </label>
                                    <textarea id="address" name="address" class="form-control" required="required" type="text"></textarea>
                        </div>
                  <div class="form-group" style = "display: none">
                    <label for="exampleInputPassword1">Id</label>
                    <input type="text" name = "branch_id" id="id" class="form-control" placeholder="">
                  </div>
                  <input type="submit" name = "update" class="btn btn-primary"/>
                </form>
              </div>
            </div>
          </div>
        </div>
</div>
<?php include "footer.php"?>
<script type="text/javascript">
   $(function(){
       $(".edit").click(function(e){
        e.preventDefault();
        let id = $(this).attr('thisid');
        let name = $(this).attr('name');
        let email = $(this).attr('email');
        let address = $(this).attr('address');
        let phone_number = $(this).attr('phone');
      // alert(id);
        $("#id").val(id);
        $("#name").val(name);
        $("#phone_number").val(phone_number);
        $("#address").val(address);
        $("#email").val(email);

       })
   });
</script>

        

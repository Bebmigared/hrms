<?php 
include 'connection.php';
session_start();
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
$data_levels = [];

$query = "SELECT * FROM levels WHERE company_id = '".$_SESSION['user']['companyId']."'";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result)> 0){
  while($row = mysqli_fetch_assoc($result)) {
    $data_levels[] = $row;
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
                      <li><a href ="addlevel.php" class="btn btn-success btn-sm" style="color: #fff;">Add Level</a>
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
                   <?php if(count($data_levels) > 0) {?>  
                    <div class="table-responsive">
                      
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title text-center">S/N </th>
                            <th class="column-title text-center">Level Name </th>
                             <th class="column-title text-center">Date Created </th>
                            <th class="column-title text-center">Option </th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php if(count($data_levels) > 0) {?>
                          <?php for ($h = 0; $h < count($data_levels); $h++) {?>
                            
                          <tr class="pointer">
                            <td class="text-center ">
                              <?=$h + 1?>
                            </td>
                            <td class="text-center"><?=$data_levels[$h]['name']?></td>
                            <td class="text-center"><?=$data_levels[$h]['date_created']?></td>
                            <td class="text-center">
                              <div class="btn-group" role="group" aria-label="Basic example">
                                <a class="btn btn-primary btn-sm edit"  name="<?=$data_levels[$h]['name']?>" id="<?=$data_levels[$h]['id']?>" thisid = "<?=$data_levels[$h]['id']?>" data-toggle="modal" data-target="#exampleModal">Edit</a>
                              </div>
                            </td>
                          </tr>
                          <?php } } ?>
                        </tbody>
                      </table>
                    </div>
                  <?php }else { ?>
                    <p>No Level added</p>
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
                 <form action = 'process_addlevel.php' method = "POST">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Level Name</label>
                    <input type="text" id="name" name ="name" class="form-control" aria-describedby="emailHelp" placeholder="">
                  </div>
                  
                  <div class="form-group" style = "">
                    <label for="exampleInputPassword1">Id</label>
                    <input type="text" name = "level_id" id="id" class="form-control" placeholder="">
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
      // alert(id);
        $("#id").val(id);
        $("#name").val(name);

       })
   });
</script>

        

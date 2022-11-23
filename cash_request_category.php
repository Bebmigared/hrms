<?php 
include 'connection.php';
session_start();
 if(!isset($_SESSION['user']['id'])) header("Location: login.php");
$kss = [];
$categories = [];
 if(!isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == '') header("Location: login.php");
 if($_SESSION['user']['category'] == 'staff') $admin_id = $_SESSION['user']['admin_id'];
 else if ($_SESSION['user']['category'] == 'admin') $admin_id = $_SESSION['user']['id'];
  $query = "SELECT kss.information,users.name,users.employee_ID FROM kss INNER JOIN users ON kss.staff_id = users.id WHERE kss.admin_id = '".$admin_id."'ORDER BY kss.id DESC LIMIT 1";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $kss[] = $row;
      }
  }
  $query = "SELECT * FROM cash_category where admin_id ='".$_SESSION['user']['id']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
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
                <h3>CASH REQUEST CATEGORY</h3>
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
               <?php if(isset($_SESSION['msg']) && $_SESSION['msg'] != '') {?>
                        <div class="alert alert-primary" style="background-color: #337ab7;color:#fff" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                  <?php } ?>           
              <div class="col-md-8 col-sm-12 col-xs-12">
                 <div class="x_panel">
                    <div class="x_title">
                      <h2>Cash Category<small>
                          <button class='btn btn-primary btn-sm' data-toggle="modal" data-target="#categoryModal">Add Category</button>
                      </small></h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <br />
                       <?php if(count($categories) > 0) {?>
                       <div class="table-responsive">
                        <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N </th>
                            <th class="column-title">Category </th>
                            <th class="column-title">Date Created </th>
                            <th class="column-title">Delete </th>
                            <th class="column-title">Edit </th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($categories); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$categories[$h]['category']?></td>
                            <td class=" "><?=$categories[$h]['date_created']?></td>
                            <td class=" "><button class='btn btn-danger btn-sm' onclick="deleteData(<?=$categories[$h]['id']?>)">Delete</button></td>
                            <td class=" "><button data-toggle="modal" data-target="#editModal" thisid ="<?=$categories[$h]['id']?>" name = "<?=$categories[$h]['category']?>" class='btn btn-primary btn-sm edit' id="<?=$categories[$h]['id']?>">Edit</button></td>
                          </tr>
                           <?php }?>
                        </tbody>
                      </table>
                       </div>
                       <?php }else { ?>
                         <h3>No Category added</h3>
                       <?php } ?>
                    </div>
                  </div>
              </div>
              <div class="col-md-4 col-sm-12 col-xs-12">
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
              </div>
              <div class="col-md-4 col-sm-12 col-xs-12">
                
              </div>
            </div>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="process_cash_category.php" method="POST">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                      <div class="form-group">
                        <label for="category">Category Name</label>
                        <div class="">
                            <input type="text" class="form-control col-sm-12 col-md-12 col-lg-12" style ="width:100%" name = "category"/>
                        </div>
                      </div>
                      
                </div>
            </div>
              <div class="modal-footer">
              <button type="submit" name="submit" class="btn btn-primary">Add Category</button>
            </div>
            </form>
           
            
            
        
          </div>
          
        </div>
      </div>
    </div>
     <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="process_cash_category.php" method="POST">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                      <div class="form-group">
                        <label for="category">Category Name</label>
                        <div class="">
                            <input type="text" id="catename" class="form-control col-sm-12 col-md-12 col-lg-12" style ="width:100%" name = "category"/>
                             <input  type="text" id="cateid" class="form-control col-sm-12 col-md-12 col-lg-12" style ="width:100%;display: none" name = "id"/>
                        </div>
                      </div>
                      
                </div>
            </div>
              <div class="modal-footer">
              <button type="submit" name="update" class="btn btn-primary">Edit Category</button>
            </div>
            </form>
           
            
            
        
          </div>
          
        </div>
      </div>
    </div>
<?php include "footer.php"?>
<script type="text/javascript" src="js/appraisal.js"></script>
<script type="text/javascript">
   function deleteData(id)
   {
      var host = window.location.host;
      var url = '';
      if(host == 'localhost')
      {
         url = '/newhrcore/process_cash_category?id='+btoa(id);
      }else 
      {
        url = '/process_cash_category?id='+btoa(id);
      }
      if (confirm("Do you really want to delete this?")) {
        window.location.href = url;
       
      } else {
      }
   }

   $(function(e){
     $(".edit").click(function(e){
         let name = $(this).attr('name');
         let id = $(this).attr('thisid');
         $('#catename').val(name);
         $('#cateid').val(id);
     })
   })

</script>
        

<?php 
include 'connection.php';
session_start();
$data_item = [];
if(!isset($_SESSION['user']['id'])) header("Location: login.php");
 if(isset($_POST['edit'])){
     $name = mysqli_real_escape_string($conn, $_POST['name']);
     $category = mysqli_real_escape_string($conn, $_POST['category']);
     $cost = mysqli_real_escape_string($conn, $_POST['supply_cost']); //+ mysqli_real_escape_string($conn, $_POST['supply_cost']);
     $quantity = mysqli_real_escape_string($conn, $_POST['quantity']) + mysqli_real_escape_string($conn, $_POST['supply_quantity']);
     $q_aftersupply = mysqli_real_escape_string($conn, $_POST['quantity']) + mysqli_real_escape_string($conn, $_POST['supply_quantity']);
     //$s_cost = mysqli_real_escape_string($conn, $_POST['supply_cost']);
     $s_quantity = mysqli_real_escape_string($conn, $_POST['supply_quantity']);
     $id = mysqli_real_escape_string($conn, $_POST['itemid']);
     if($name == '' || $cost == '' || $quantity == ''){
         $_SESSION['msg'] = "Name, Cost and Quantity fields are Required";
     }else{
         $sql = "UPDATE items SET item_name = '".$name."', item_category = '".$category."',item_cost = '".$cost."', item_quantity = '".$quantity."', quantity_supply = '".$s_quantity."', quantity_aftersupply ='".$q_aftersupply."'
         WHERE id = '".$id."'";
         if (mysqli_query($conn, $sql)) {
            $_SESSION['msg'] = "Item Informations Updated";
         }else {
            $_SESSION['msg'] = mysqli_error($conn);
         }
     }
 }
 if(isset($_POST['delete'])){
     $id = mysqli_real_escape_string($conn, $_POST['itemid']);
     echo $id;
     echo 'aaaaa';
 }
 
  if($_SESSION['user']['category'] == 'admin'){
    $query = "SELECT * FROM items WHERE companyId = '".$_SESSION['user']['companyId']."'";
  }else {
    $query = "SELECT * FROM items WHERE companyId = '".$_SESSION['user']['companyId']."'";
  }
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $data_item[] = $row;
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
            <h3>Inventories</h3>
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
                        <div class="alert alert-primary" style="background-color: blue;font-size:14px;color:#fff;" role="alert">
                            <?=$_SESSION['msg']?>
                        </div>
                        <?php unset($_SESSION['msg']); ?>
                  <?php } ?>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Inventories</h2>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                   <?php if (count($data_item) > 0) {?>      
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">S/N </th>
                            <th class="column-title">Item Name </th>
                            <!--<th class="column-title">Item Catgeory </th>-->
                            <th class="column-title">Quantity </th>
                            <th class="column-title">Cost </th>
                            <?php if($_SESSION['user']['category'] == 'admin' || $_SESSION['user']['add_item_permission'] == '1'){?>
                            <th class="column-title">More </th>
                            <?php } ?>
                          </tr>
                        </thead>

                        <tbody>
                          <?php for ($h = 0; $h < count($data_item); $h++) {?>
                          <tr class="pointer">
                            <td class="a-center ">
                              <?=$h + 1?>
                            </td>
                            <td class=""><?=$data_item[$h]['item_name']?></td>
                           <!-- <td class=" "><?=$data_item[$h]['item_category']?></td>-->
                            <td class=" "><?=$data_item[$h]['item_quantity']?></td>
                            <td class=" "><?=$data_item[$h]['item_cost']?></td>
                            </td>
                            <?php if($_SESSION['user']['category'] == 'admin' || $_SESSION['user']['add_item_permission'] == '1'){?>
                            <td class=" ">
                                <a  href ="" items="<?=base64_encode($data_item)?>" val = "<?=$data_item[$h]['id']?>" class='btn btn-info edit' id='edit<?=$h?>' name="<?=$data_item[$h]['item_name']?>" category ="<?=$data_item[$h]['item_category']?>" quantity = "<?=$data_item[$h]['item_quantity']?>" cost ="<?=$data_item[$h]['item_cost']?>" item_id="<?=$data_item[$h]['id']?>" data-toggle="modal" data-target="#interModal">Update</a>
                                
                                
                            </td>
                            <?php } ?>
                          </tr>
                          <?php }?>
                        </tbody>
                      </table>
                    </div>
                    <?php } ?>
                    <?php if(count($data_item) == 0) {?>
                      <h3>No Item Added</h3>
                    <?php } ?>
                  </div>
                </div>
              </div>      
        </div>
</div>
</div>
<div class="modal fade" id="interModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Update Item</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form action="inventory.php" method="POST" enctype="multipart/form-data" id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
        
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Item Name<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="item_name" name="name" required="true" class="form-control col-md-7 col-xs-12" type="text">
              </div>
            </div>
            <div class="form-group" hidden>
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Category <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="category" name="category" required="true" class="form-control col-md-7 col-xs-12" type="text" >
                      
                    </div>
              </div>
              <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Opening Quantity <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="quantity" name="quantity" required="true" class="form-control col-md-7 col-xs-12" type="number" readonly>
                      
                    </div>
              </div>
              <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Opening Cost <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="cost" name="cost" required="true" class="form-control col-md-7 col-xs-12" type="number" readonly>
                      <input id="item_id" style="display:none;" name="itemid" required="true" class="form-control col-md-7 col-xs-12" type="text">
                    </div>
              </div>
               <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Quantity Supplied <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="squantity" name="supply_quantity" required="true" class="form-control col-md-7 col-xs-12" type="number">
                      
                    </div>
              </div>
              <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Amount Supplied <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <input id="scost" name="supply_cost" required="true" class="form-control col-md-7 col-xs-12" type="number">
                     
                    </div>
              </div>

              
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="submit" name="edit" class="btn btn-success">Submit</button>
                </div>
              </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php include "footer.php"?>
<script>
    $(function(e){
        $('.edit').on('click', function(e){
            e.preventDefault();
            let val = $('#'+this.id+'').attr("val");
            let name = $('#'+this.id+'').attr("name");
            let category = $('#'+this.id+'').attr("category");
            let quantity = $('#'+this.id+'').attr("quantity");
            let cost = $('#'+this.id+'').attr("cost");
            let id = $('#'+this.id+'').attr("item_id");
            $('#item_name').val(name);
            $('#category').val(category);
            $('#quantity').val(quantity);
            $('#cost').val(cost);
            $('#item_id').val(id);
                    
        });
        $('.delete').on('click', function(e){
            e.preventDefault();
            let id = $('#'+this.id+'').attr("item_id");
            $('#deleteitem_id').val(id);
            $('#deleteItem').trigger('click');
        })
    })
</script>
        

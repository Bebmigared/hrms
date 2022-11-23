<?php
include 'connection.php';
include 'connectionpdo.php';

session_start();
$depts = [];
$leave_type = [];
$users = [];

//print_r($_SESSION['user']);
  $query = "SELECT * FROM companies";
  $result = mysqli_query($conn, $query);
  //if(mysqli_num_rows($result) > 0){
  while($row = mysqli_fetch_assoc($result)) {

     $depts[] = $row;
     //$row= $depts[0]['dept'];
    // $all_dept = explode(";",$d[0]['dept']);
  //}
}

      //print_r ($depts);

      $query = "SELECT * FROM bank";
      $result = mysqli_query($conn, $query);
      //if(mysqli_num_rows($result) > 0){
      while($row = mysqli_fetch_assoc($result)) {
    
         $bank[] = $row;
         //$row= $depts[0]['dept'];
        // $all_dept = explode(";",$d[0]['dept']);
      //}
    }


  $query = "SELECT * FROM leave_type WHERE companyId = '".$_SESSION['user']['companyId']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $leave_type[] = $row;
      }
  }

   $q = "SELECT * FROM users WHERE companyId = '".$_SESSION['user']['companyId']."' AND id != '".$_SESSION['user']['id']."'";
   $res = mysqli_query($conn, $q);
   if(mysqli_num_rows($res) > 0){
   while($r = mysqli_fetch_assoc($res)) {
     $users[] = $r;
     }
     //print_r($data);
   }
   
$queryy = "SELECT voucher_no FROM voucher ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $queryy);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $last_id[] = $row;
      }
  }
  

$voucher_no = implode('',$last_id[0]);
//print $voucher_no +1;
//print_r($voucher_no);

?>
<?php include "header.php"?>
<div class="right_col" role="main">
<div class="">
        <!--<div class="page-title">
          <div class="title_left">
            <h3>Leave Request</h3>
          </div>-->

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
        <form action="process_voucher.php" method="post">
        <div class="clearfix"></div>
        <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Create Voucher</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="col-sm-10" style="background-color:#2A3F54";>
                  <div class="x_panel">
                    <div class="body">
                    <div class="form-group col-sm-4">
                    <label>Date Prepared</label>
                    <input type="text" name="created_at" id="inputName" value="<?= date('d/m/y') ?>" class="form-control" disabled/>
                    </div>

                    <div class="form-group col-sm-3">
                    <label>Name</label>
                    <input type="text" name="name" id="inputName" value="<?= $_SESSION['user']['name'] ?>" class="form-control" disabled/>
                    </div>

                    <div class="form-group col-sm-2">

            <label for="request">Voucher No</label>
            <input type="text" name="voucher_no" id="inputName" value="<? printf('%06d',$voucher_no+1)?>" class="form-control" readonly/>
           <!-- <select name="request" id="request" class="form-control" onchange="refreshCustomFields(this)" required>
                                    <option value="" selected="selected">
                       Select Department
                    </option>
                    <?php for ($h = 0; $h < count($depts); $h++) {?>
                      <option value="<?php echo $depts[$h]['company_name']?>"><?php echo $depts[$h]['company_name']?></option>

                                            <?php } ?>
                            </select>-->
        </div>

                    <div class="form-group col-sm-2">
                    <label for="request">Recipient</label>
                    
             <select name="client_name" id="request" class="form-control" onchange="refreshCustomFields(this)" required>-->
                                   
                    <?php for ($h = 0; $h < count($depts); $h++) {?>
                      <option value="<?php echo $depts[$h]['company_name']?>"><?php echo $depts[$h]['company_name']?></option>

                                            <?php } ?>
                            
        </select>
            </div>
            <div class="form-group col-sm-4">
                    <label for="request">Paying Bank</label>
             <select name="paying_bank" id="request" class="form-control" onchange="refreshCustomFields(this)" required>
                                    <option value="" selected="selected">
                       Select Bank
                    </option>
                    <?php for ($h = 0; $h < count($bank); $h++) {?>
                      <option value="<?php echo $bank[$h]['bank']?>"><?php echo $bank[$h]['bank']?></option>

                                            <?php } ?>
                            </select>
        
            </div>
            <div class="form-group col-sm-3">
                    <label for="request">Cheque No</label>
            <input type="text" name="cheque_no" class="form-control">
        
            </div>
            <div class="form-group col-sm-2">
            <label for="priority">Currency</label>
            <select name="currency" id="priority" class="form-control" onchange="refreshCustomFields(this)">
                                    <option value="NGN" selected="selected">
                        NGN
                    </option>
                                    <!--<option value="USD">
                        USD
                    </option>
                                    <option value="EUR">
                        EUR
                    </option>
                    <option value="YEN">
                        YEN
                    </option>-->

                            </select>
        </div>
        <div class="form-group col-sm-2">
                    <label for="request">Amount</label>
            <input type="text" name="amount" class="form-control">
        
            </div>
        <br>
        <div class="form-group col-sm-11">
        <label for="inputMessage">Message</label>
        <textarea name="description" id="message" rows="6" class="form-control markdown-editor" data-auto-save-name="client_ticket_open"></textarea>
    </div>

        </div>
        <br>
        <br>
        <div class="form-group col-sm-11">
        <p class="text-center">


        <input type="submit" name="submit" id="submit" value="Send" class="btn btn-primary"/>
        <a href="dashboard.php" class="btn btn-default">Cancel</a>
    </p>
    </form>
        </div>
    </div>


                    </div>
                    </div>
                    </div>


      </div>
    </div>
  </div>
</div>
<?php include "footer.php"?>



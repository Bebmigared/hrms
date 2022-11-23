<?php
include 'connection.php';
include 'connectionpdo.php';

session_start();
$depts = [];
$leave_type = [];
$users = [];

//print_r($_SESSION['user']);
  $query = "SELECT * FROM departments";
  $result = mysqli_query($conn, $query);
  //if(mysqli_num_rows($result) > 0){
  while($row = mysqli_fetch_assoc($result)) {

     $depts[] = $row;
     //$row= $depts[0]['dept'];
    // $all_dept = explode(";",$d[0]['dept']);
  //}
}

      //print_r ($depts);


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
        <form action="ticket_submit.php" method="post">
        <div class="clearfix"></div>
        <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Open Ticket</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="col-sm-10">
                  <div class="x_panel">
                    <div class="body">
                    <div class="form-group col-sm-4">
                    <label>Name</label>
                    <input type="text" name="name" id="inputName" value="<?= $_SESSION['user']['name'] ?>" class="form-control" disabled/>
                    </div>

                    <div class="form-group col-sm-5">
                    <label>Email</label>
                    <input type="email" name="email" id="staffEmail" value="<?= $_SESSION['user']['email'] ?>" class="form-control"/ disabled>
                    </div>

                    <div class="form-group col-sm-2">

            <label for="request">Select Department</label>
            <select name="request" id="request" class="form-control" onchange="refreshCustomFields(this)" required>
                                    <option value="" selected="selected">
                       Select Department
                    </option>
                    <?php for ($h = 0; $h < count($depts); $h++) {?>
                      <option value="<?php echo $depts[$h]['dept']?>"><?php echo $depts[$h]['dept']?></option>

                                            <?php } ?>
                            </select>
        </div>

                    <div class="form-group col-sm-9">
            <label for="inputSubject">Subject</label>
            <input type="text" name="subject" id="subject" value="" class="form-control" />
            </div>
            <div class="form-group col-sm-2">
            <label for="priority">Priority</label>
            <select name="priority" id="priority" class="form-control" onchange="refreshCustomFields(this)">
                                    <option value="Critical" selected="selected">
                        Critical
                    </option>
                                    <option value="High">
                        High
                    </option>
                                    <option value="Medium">
                        Medium
                    </option>
                    <option value="Low">
                        Low
                    </option>

                            </select>
        </div>
        <br>
        <div class="form-group col-sm-11">
        <label for="inputMessage">Message</label>
        <textarea name="message" id="message" rows="12" class="form-control markdown-editor" data-auto-save-name="client_ticket_open"></textarea>
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



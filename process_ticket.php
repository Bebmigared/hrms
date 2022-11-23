<?php
 include "connection.php";
 include "connectionPDO.php";
 
 session_start();
 $msgs = '';
 $ticket_id = '';

 $tickets = [];
 //echo $ticket_id;
 $ticket = [];
 $name = '';
 $ticket_id = $_SESSION['ticket_id'];
 $query = "SELECT tickets.id, tickets.ticket_id, tickets.user_id, tickets.request, tickets.subject, tickets.priority, tickets.message, tickets.dept, tickets.status, tickets.created_at, users.name, users.email FROM tickets INNER JOIN users ON tickets.user_id = users.id WHERE tickets.id  = '".$ticket_id."'";
 $result = mysqli_query($conn, $query);
 //if(mysqli_num_rows($result)>0){
     while($row = mysqli_fetch_assoc($result)) {
         $ticket[] = $row;
         $name = $row['name'];
         $id = $row['user_id'];
         $ticket_id = $id;
         $email = $row['email'];
         $subject = $row['subject'];
         $priority = $row['priority'];
         $message = $row['message'];
         $ticket_id = $row['ticket_id'];

       }
             //echo $id;

//echo $name;



  if(!isset($_SESSION['user']['id'])) header("Location: login.php");

  if(!isset($_SESSION['user'])) header("location: login");
  if(isset($_POST['submit'])){
      $id = mysqli_real_escape_string($conn, $_SESSION['user']['id']);
      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $request = mysqli_real_escape_string($conn, $_POST['request']);
      $subject = mysqli_real_escape_string($conn, $_POST['subject']);
      $priority = mysqli_real_escape_string($conn, $_POST['priority']);
      $message = mysqli_real_escape_string($conn, $_POST['message']);
      //$ticket_id = 3;
      
      //$status = mysqli_real_escape_string($conn, $_POST[''])

     // echo $priority;
     
  $sql = "INSERT INTO tickets (user_id , request, subject, priority, message, status)
   
    VALUES ('".$id."', '".$request."', '".$subject."','".$priority."','".$message."', 'open')";
    // VALUES ('1', 'a request', 'a subject','High', 'open')";
  
  /*if (mysqli_query($conn, $sql)) {
    //echo "Ticket Submitted Successfully";
    header("Location: tickets_home.php");
} else {
    //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
   //echo "Error submitting ticket kindly try again later";
   header("Location: tickets_home.php");
}
    }
 */
  if (mysqli_query($conn, $sql)) {
  echo "New record created successfully";
  } else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
  }
  
  elseif
  (isset($_GET['view_tickets']) || isset($_SESSION['ticketid'])){
    //
    if(isset($_GET['view_tickets'])){
      $id = mysqli_real_escape_string($conn, $_GET['id']);
      $_SESSION['ticketid'] = $id;
    }
    $query = "SELECT tickets.id, tickets.ticket_id, tickets.user_id,
     tickets.request, tickets.subject, tickets.priority, tickets.message, tickets.dept,
      tickets.status, tickets.created_at, users.name, users.email FROM tickets INNER JOIN users ON tickets.user_id = users.id WHERE tickets.id  = '".$_SESSION['ticketid']."'";
    $result = mysqli_query($conn, $query);
    //if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_assoc($result)) {
            $ticket[] = $row;
            $name = $row['name'];
            $id = $row['user_id'];
            $ticket_id = $id;
            $email = $row['email'];
            $subject = $row['subject'];
            $priority = $row['priority'];
            $message = $row['message'];
            $ticket_id = $row['ticket_id'];

          }
                //echo $id;
  
  //echo $name;
  }

  elseif(isset($_POST['reply_btn'])){
    $ticket_id = $_SESSION["ticket_id"];
    $user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
    $handler_id = mysqli_real_escape_string($conn, $_GET['handler_id']);
    $message = mysqli_real_escape_string($conn, $_GET['message']);
    $sql ="INSERT into tickets_message (user_id, ticket_id, handler_id, message) VALUES ('$user_id', '$ticket_id', '$handler_id', '$message')";
    if (mysqli_query($conn, $sql)) {
      echo "Reply sent";
      header('Location: process_ticket.php');
    } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
      }




 // print_r($ticket);

  //function all_tickets_message($conn, $query){
    $query ="SELECT * FROM tickets_message WHERE ticket_id ='".$_SESSION['ticketid']."' AND user_id=161 AND handler_id = 115";
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_assoc($result)) {
      //$message 
      //print_r($row);
      $tickets[] = $row;
  }
//}

  ?>

<?php include "header.php"?>
<div class="right_col" role="main">
<div class="clearfix"></div>

<div class="row">
  <div class="col-md-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Inbox Design<small>User Mail</small></h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#">Settings 1</a>
              </li>
              <li><a href="#">Settings 2</a>
              </li>
            </ul>
          </li>
          <li><a class="close-link"><i class="fa fa-close"></i></a>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>
   
          <!-- /MAIL LIST -->

          <!-- CONTENT MAIL -->
          <div class="col-sm-9 mail_view">
            <div class="inbox-body">
              <div class="mail_heading row">
                <div class="col-md-8">
                  
                <!--  <div class="btn-group">
                    <button class="btn btn-sm btn-primary" type="button"><i class="fa fa-reply"></i> Reply</button>
                    <button class="btn btn-sm btn-default" type="button"  data-placement="top" data-toggle="tooltip" data-original-title="Forward"><i class="fa fa-share"></i></button>
                    <button class="btn btn-sm btn-default" type="button" data-placement="top" data-toggle="tooltip" data-original-title="Print"><i class="fa fa-print"></i></button>
                    <button class="btn btn-sm btn-default" type="button" data-placement="top" data-toggle="tooltip" data-original-title="Trash"><i class="fa fa-trash-o"></i></button>
                  </div>-->
                </div>
                
                <div class="col-md-12">
                  <h4> Subject: <?= strtoupper($subject)?></h4><p class="date"> 8:02 PM 12 FEB 2014</p>
</br>
                </div>
              </div>
              <div class="sender-info">
                <div class="row">
                  <div class="col-md-12">
                  <div class="panel">
                        <a class="panel-heading collapsed" role="tab" id="headingTwo" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                          <h4 class="panel-title">Reply</h4>
                        </a>
                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                          <div class="panel-body">
                            <form action="process_ticket_reply.php" method="get">
                              <input type="text" name = "handler_id" value="<?=$_SESSION['user']['id']?>">
                              
                              <input type="text" name = "user_id" value="<?=$id?>">
                              <input type="text" name = "ticket_id" value="<?=$ticket_id?>">
                          <label for="inputMessage">Message</label>
                          <input type="submit" name="reply_btn" id="reply_btn" value="Reply" class="btn btn-primary"/>
                          <textarea name="message" id="message" rows="12" class="form-control markdown-editor" data-auto-save-name="client_ticket_open"></textarea>
        
</form>
                        </div>
                        </div>
                    <?php  for ($h = 0; $h < count($tickets); $h++)  {?>
                        <?= $tickets[$h]['messages'];?>
                      </div>
                      <strong><?= $name ?></strong>
                    <span>(<?= $email ?>)</span> to
                    <strong>me</strong>
                    <a class="sender-dropdown"><i class="fa fa-chevron-down"></i></a>
                  </div>
                </div>
              </div>
              
              <div class="view-mail">
                <p><?= $message ?></p>
              </div>
              <?php } ?>
                   
                  </div>
                </div>
              </div>
              
             
              
              
              
              </div>
              
            </div>

          </div>
          <!-- /CONTENT MAIL -->
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<!-- /page content -->


<?php include "footer.php"?>

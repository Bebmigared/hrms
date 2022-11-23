<?php
 include "connection.php";
 include "connectionPDO.php";
 include 'e_mail.php';
 
 session_start();
 //print isset($_SESSION['ticketid']);
 $msgs = '';
 //$ticket_id = '';

 $tickets = [];
 //echo $ticket_id;
 $ticket = [];
 $name = '';
 if(!isset($_GET['view_tickets'])){
 $tickets_id = $_REQUEST['ticket_id'];
 //echo $tickets_id;
 }

 if(isset($_POST['close_tickets'])){
     $query ="UPDATE tickets SET status ='closed' WHERE ticket_id = $tickets_id ";
     if(mysqli_query($conn, $query)){
        echo "<script type='text/javascript'>alert('Ticket Closed');
        window.location='view_ticket.php';
        </script>";
      } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      }
     }
 
 $query = "SELECT tickets.id, tickets.ticket_id, tickets.user_id,
 tickets.request, tickets.subject, tickets.priority, tickets.message, tickets.dept,
  tickets.status, tickets.created_at, users.name, users.email FROM tickets INNER JOIN users ON tickets.user_id = users.id WHERE tickets.ticket_id  = '$tickets_id'";
$result = mysqli_query($conn, $query);
 //if(mysqli_num_rows($result)>0){
     while($row = mysqli_fetch_assoc($result)) {
         $ticketx[] = $row;
         $name = $row['name'];
         $id = $row['user_id'];
         $ticket_id = $row['ticket_id'];
         $email = $row['email'];
         $subject = $row['subject'];
         $priority = $row['priority'];
         $messagex = $row['message'];
         $ticket_id = $row['ticket_id'];
         

       }
            
       //echo $subject;
       //print_r($row);
       //echo $id;

//echo $name;



  if(!isset($_SESSION['user']['id'])) header("Location: login.php");

  if(!isset($_SESSION['user'])) header("location: login");
  if(isset($_POST['submit'])){
      $ticket_id = date('dmis');
      $id = mysqli_real_escape_string($conn, $_SESSION['user']['id']);
      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $request = mysqli_real_escape_string($conn, $_POST['request']);
      $subject = mysqli_real_escape_string($conn, $_POST['subject']);
      $priority = mysqli_real_escape_string($conn, $_POST['priority']);
      $message = mysqli_real_escape_string($conn, $_POST['message']);
      $date = date('d-m-y');
      //$ticket_id = 3;
      
      //$status = mysqli_real_escape_string($conn, $_POST[''])

     // echo $priority;
     
  $sql = "INSERT INTO tickets (ticket_id, user_id , request, subject, priority, message, status, created_at)
   
    VALUES ('".$ticket_id."', '".$id."', '".$request."', '".$subject."','".$priority."','".$message."', 'open', '".$date."')";
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
    //mail("oluwasegunjimoh@gmail.com","My subject",sss);
    echo "<script type='text/javascript'>alert('Ticket Raised');
    window.location='view_myticket.php';
    </script>";
    sendmail('oluwasegunjimoh@gmail.com','new ticket','new ticket');
  } else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
  }
  
if(isset($_POST['view_tickets']) || isset($_SESSION['ticketid'])){
    //
    if(isset($_GET['view_tickets'])){
      $id = mysqli_real_escape_string($conn, $_POST['id']);
      $_SESSION['ticketid'] = $id;
    }
    $query = "SELECT tickets.id, tickets.ticket_id, tickets.user_id,
     tickets.request, tickets.subject, tickets.priority, tickets.message, tickets.dept,
      tickets.status, tickets.created_at, users.name, users.email FROM tickets INNER JOIN users ON tickets.user_id = users.id WHERE tickets.id  = '$id'";
    $result = mysqli_query($conn, $query);
    //if(mysqli_num_rows($result)>0){
        while($row = mysqli_fetch_assoc($result)) {
            $ticket[] = $row;
            $name = $row['name'];
            $id = $row['user_id'];
            $ticket_id = $row['ticket_id'];
            $email = $row['email'];
            $subject = $row['subject'];
            $priority = $row['priority'];
            $message = $row['message'];
            $ticket_id = $row['ticket_id'];

          }
                //echo $id;
  //print_r($row['ticket_id']);
  //echo $name;
  }

  /*elseif(isset($_POST['reply_btn'])){
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
      }*/




 // print_r($ticket);

  //function all_tickets_message($conn, $query){
    $query = "SELECT tickets_message.ticket_id, tickets_message.messages, tickets_message.created_at, users.name, users.email FROM tickets_message INNER JOIN users ON tickets_message.user_id = users.id WHERE tickets_message.ticket_id  = '$ticket_id'";
 
    $result = mysqli_query($conn, $query);
    while($row = mysqli_fetch_assoc($result)) {
      //$message 
      
      $tickets[] = $row;
      $name = $row['name'];
      //print_r($tickets);
  }
//}

if(isset($_POST['reply_btn'])){



    //$ticket_id = mysqli_real_escape_string($conn, $_POST['ticket_id']);
    $ticket_id = mysqli_real_escape_string($conn, $_POST['ticket_id']);
    $user_id = mysqli_real_escape_string($conn, $_SESSION['user']['id']);
    $handler_id = mysqli_real_escape_string($conn, $_POST['handler_id']);
    $user_email = $_SESSION['user']['email'];
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $created_at = date("Y-m-d");
   
    //$message = $row['message'];
    $sql ="INSERT into tickets_message (user_id, ticket_id, handler_id, messages, created_at) VALUES ('".$user_id."', '".$ticket_id."', '".$handler_id."', '".$message."', '".$created_at."')";
    if (mysqli_query($conn, $sql)) {
      sendmail('oluwasegunjimoh@gmail.com','a new reply on your ticket','new ticket');
     // echo "Reply sent";
      //header( "refresh:3;url=process_ticket2.php?ticket_id=".$ticket_id);
      header( "refresh:2;process_ticket2.php?ticket_id=".$ticket_id);
      
      
    } else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
      
    //$ticket_id = $_SESSION['ticket_id'];
    //header( "refresh:3;url=process_ticket2.php" );
  }

  ?>

<?php include "header.php"?>


<div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Ticketing System <small> </small></h3>
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
              <div class="col-md-12">
                <div class="x_panel">
                 
                <div class="sender-info">
                <div class="row">
                  <div class="col-md-12">
                  <div class="panel">
                        <a class="panel-heading collapsed" role="tab" id="headingTwo" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                          <h4 class="panel-title">Reply</h4><i class="glyphicon glyphicon-plane"></i>
                        </a>
                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                          <div class="panel-body">
                            <form action="process_ticket2.php" method="POST">
                              <input type="text" name = "handler_id" value="<?=$_SESSION['user']['id']?>" hidden>
                              
                              <input type="text" name = "user_id" value="<?=$id?>" hidden>
                              <input type="text" name = "ticket_id" value="<?=$ticket_id?>" hidden>
                          <label for="inputMessage">Message</label>
                          
                          <textarea name="message" id="message" rows="12" class="form-control markdown-editor" data-auto-save-name="client_ticket_open"></textarea>
</br> <input type="submit" name="reply_btn" id="reply_btn" value="Reply" class="btn btn-warning"/>        
</form>
                        </div>
                        </div>
                  <div class="x_content">

                    <div class="col-md-9 col-sm-9 col-xs-12">

                  

                      

                      <div>

                        <h4>Ticket Activity</h4>
                       <? if(count($tickets) < 1)?>
                            
                       <?php  ?>
                        <!-- end of user messages -->
                        <?php for ($h = 0; $h < count($tickets); $h++) {
                          
                            ?>
                           
                        <ul class="messages">
                          <li>
                            <img src="images/img.jpg" class="avatar" alt="Avatar">
                            <div class="message_date">
                              <h6 class="date text-info"><?php echo $tickets[$h]['created_at']?></h6>
                              <!--<p class="month">May</p>-->
                            </div>
                            <div class="message_wrapper">
                              <h4 class="heading"><?php echo $tickets[$h]['name']?></h4>
                              <blockquote class="message"><?php echo $tickets[$h]['messages'] ?></blockquote>
                              <br />
                              <!--<p class="url">
                                <span class="fs1 text-info" aria-hidden="true" data-icon=""></span>
                                <a href="#"><i class="fa fa-paperclip"></i> User Acceptance Test.doc </a>
                              </p>-->
                            </div>
                          </li>
                         
                        </ul>
                        <?php } ?>
                        
                        <!-- end of user messages -->


                      </div>


                    </div>

                    <!-- start project-detail sidebar -->
                    <div class="col-md-3 col-sm-3 col-xs-12">

                      <section class="panel">

                        <div class="x_title">
                          <h2>Ticket Information</h2>
                          <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                          <h3 class="green"><i class="glyphicon glyphicon-pushpin"></i> <?= strtoupper($subject)?></h3>

                          <p><?=$messagex ?></p>
                          <br />

                          <div class="project_detail">

                            <p class="title">Sender's Name</p>
                            <p><?=$name?></p>
                            <p class="title">Sender's Email</p>
                            <p>(<?= $email ?>)</p>
                            <p class="title">Priority</p>
                            <p>(<?= $priority ?>)</p>
                          </div>

                          <br />
                            <br />

                          <div class="text-center mtop20">
                            <a href="/hrenterprise/ticket_home.php" class="btn btn-sm btn-primary">Open Ticket</a>
                            <a href="/hrenterprise/dashboard.php" class="btn btn-sm btn-warning">Dashboard</a>
                            <form action="process_ticket2.php" method="post">
                            <div class="text-center mtop20">
                            <input type="text" name = "ticket_id" value="<?=$ticket_id?>" hidden>
                            <input type="submit" name="close_tickets" id="close_ticket" value="close" class="btn btn-danger" />
                        </div>
                        </form>
                            
                          </div>
                        </div>

                      </section>

                    </div>
                    <!-- end project-detail sidebar -->

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->


<?php include "footer.php"?>

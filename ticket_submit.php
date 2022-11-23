<?php
 include "connection.php";
 include "connectionPDO.php";
 include "e_mail.php";
 
 session_start();
if(isset($_POST['submit'])){
      $ticket_id = date('dmis');
      $id = mysqli_real_escape_string($conn, $_SESSION['user']['id']);
      //$email = mysqli_real_escape_string($conn, $_POST['email']);
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
  /*if (mysqli_query($conn, $sql)) {
    //mail("oluwasegunjimoh@gmail.com","My subject",sss);
    echo "<script type='text/javascript'>alert('Ticket Raised');
    window.location='view_myticket.php';
    </script>";
  } else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
  }*/
  if (mysqli_query($conn, $sql)) {
    echo "<script type='text/javascript'>alert('Ticket Raised');
    window.location='view_myticket.php';
    </script>";
    //mail("oluwasegunjimoh@gmail.com","My subject",sss);
    
    sendmail('oluwasegunjimoh@gmail.com','new ticket','new ticket');
  } else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
  }
  ?>
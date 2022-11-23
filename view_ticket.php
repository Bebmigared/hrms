<?php 
include 'connection.php';
include 'connectionpdo.php';
session_start();
$ticket = [];


$query = "SELECT tickets.id, tickets.ticket_id, tickets.user_id, tickets.request, tickets.subject, tickets.priority, tickets.message, tickets.dept, tickets.status, tickets.created_at, users.name FROM tickets INNER JOIN users ON tickets.user_id = users.id WHERE tickets.request  = '".$_SESSION['user']['department']."' AND status='open'";
$result = mysqli_query($conn, $query);
//if(mysqli_num_rows($result)>0){
    while($row = mysqli_fetch_assoc($result)) {
        $ticket[] = $row;
      }
  

/*$query = "SELECT * FROM tickets WHERE dept = '".$_SESSION['user']['department']."'";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)) {
      
       $ticket[] = $row;
      // $all_dept = explode(";",$d[0]['dept']);
    }
  }*/

//print_r($ticket);

?>

<?php include "header.php"?>
 <!-- page content -->
 <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Tickets <small>all tickets</small></h3>
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
                  <div class="x_title">
                    <h2>Tickets</h2>
                    <div class="title_right">
                    <div class="col-md-5 col-sm-5 col-xs-12">
                    <button type="button" class="btn btn-success btn-xl"><a href="ticket_home" style="color:white">OPEN TICKET</button>
</div>
</div>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <p style="color:blue">Tickets appear in order of new to old</p>

                    <!-- start project list -->
                    <table class="table table-striped projects" style="color:blue">
                      <thead>
                        <tr>
                          <th style="width: 1%">#</th>
                          <th style="width: 20%">Ticket ID</th>
                          <th style="width: 20%">Subject</th>
                          <th>User</th>
                          <th>Message</th>
                          <th>Priority</th>
                          <th>Status</th>
                          <th style="width: 20%">#Edit</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php for ($h = 0; $h < count($ticket); $h++) {?>
                        <tr>
                          <td> <?=$h + 1?></td>
                          <td> <?=$ticket[$h]['ticket_id']?></td>
                          <td>
                            <a><?=$ticket[$h]['subject']?></a>
                            <br />
                    
                            <small>Created 01.01.2015</small>
                          </td>
                          <td>
                          <?=$ticket[$h]['name']?>
                            <br />
                            
                          </td>
                          <td class="project_progress">
                          <?=$ticket[$h]['message']?>
                          
                          </td>
                          <td class="project_progress">
                          <?=$ticket[$h]['priority']?>
                            <br />
                            
                          </td>

                          <td>
                         
                            <button type="button" class="btn btn-success btn-xs"><?=$ticket[$h]['status']?></button>
                          </td>
                          <td>
                          <form action="process_ticket2.php" method="POST">
                          <input type ="hidden" name="id" value="<?=$ticket[$h]['ticket_id']?>">
                          
                          <!--<input type="submit" name="view_tickets" id="submit" value="view_tickets" class="btn btn-primary" />-->
                            <a href="/hrenterprise/process_ticket2.php?ticket_id=<?= $ticket[$h]['ticket_id'] ?>" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>
                      </form>
                            <!--<a href="#" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                            <a href="#" class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i> Delete </a>-->
                          </td>
                        </tr>
                        <?php }?>
                      </tbody>
                    </table>
                    <!-- end project list -->

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
        <?php include "footer.php"?>
<?php 
include 'connection.php';
session_start();
$appraisal = [];
$apraisal_flow = [];
$created_appraisal = [];
$is_filled = false;
if(!isset($_SESSION['appraisal_id']) && $_SESSION['appraisal_id'] == '') header("Location: appraisals.php");
  $query = "SELECT * FROM appraisal INNER JOIN appraisal_replies ON (appraisal_replies.appraisal_id = appraisal.id) INNER JOIN users ON users.id = appraisal_replies.staff_id WHERE appraisal_replies.appraisal_id = '".$_SESSION['appraisal_id']."' AND appraisal_replies.staff_id = '".$_SESSION['staff_id']."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      while($row = mysqli_fetch_assoc($result)) {
        $appraisal[] = $row;
      }
  }
//print_r($appraisal);
?>
<?php include "header.php"?>
<div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Appraisal</h3>
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
              <div class="col-md-8 col-sm-12 col-xs-12">
                <?php for($t = 0; $t < count($appraisal); $t++) {?>
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Appraisal (<?=$appraisal[$t]['period']?>) <?=$appraisal[$t]['year']?></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                        <?php if($appraisal[$t]['document'] != '') { ?>
                        <div style="text-align: center;">
                            <div class="btn-group" role="group" aria-label="Basic example">
                              <a href="downloadfile.php/?to=view_appraisal&filename=<?=$appraisal[$t]['document']?>" class="btn btn-primary">Download Appraisal</a>
                               <a style="color:#fff;" id = 'uploadfilled' class="btn btn-info">Upload Filled Appraisal</a>
                            </div>
                            <div style="text-align: left;">
                              <?php if ($_SESSION['user']['appraisal_flow'] != "") {?>
                              <?php $appraisal_flow = explode(";",$_SESSION['user']['appraisal_flow']) ?>
                              <form action="process_approval_comments.php" method="POST" process_approvals_comment.php >  
                              <?php $comment_flow = explode(";", $appraisal[$t]['comments_flow']); ?>  
                              <?php for ($r = 0; $r < count($comment_flow); $r++) {?>  
                              <?php $this_comment_flow = explode(":", $comment_flow[$r]); ?>
                              <div class="form-group" style="margin-top: 15px;"> 
                                <label style="text-transform: capitalize;" for="<?=$comment_flow[$r]?>"><?=$this_comment_flow[0]?>'s Comment</label> 
                               <?php if(strtolower($this_comment_flow[0]) == strtolower($_SESSION['user']['position'])) {?>
                                <textarea class="form-control textarea"  id = "textarea<?=$r?>"  appraisal_id = "<?=$appraisal[$t]['appraisal_id']?>" staff_id = "<?=$appraisal[$t]['staff_id']?>" name=""><?=count($this_comment_flow) > 1 ? $this_comment_flow[1] : ''?></textarea>
                              <?php }else { ?>
                                <textarea class="form-control textarea" disabled="true"  name=""><?=count($this_comment_flow) > 1 ? $this_comment_flow[1] : ''?></textarea>
                              <?php } ?>  
                              </div> 
                             
                               <?php }?> 
                              <input type="text" name="appraisal_id" id = "appraisal_id" style="display: none;">
                              <input type="text" name="staff_id" id = "staff_id" style="display: none;">
                              <textarea id = "comment" name = "comment" style="display: none;"></textarea>
                              <div class="form-group">
                                <button type="submit" class="btn btn-primary" name = "submit" style="">Add Comment</button>
                              </div> 
                              </form> 
                              <?php } ?>
                            </div>
                        </div>
                        <?php }else if($appraisal[$t]['document_name'] == 'input Question' && $appraisal[$t]['staff_remarks'] != '') {?>
                           <div class="row" id = "all_reply">
                             <div class="col-md-8 col-sm-12 col-xs-12 col-md-offset-2 each_questions">
                               <div style="text-align: center;">
                                  <div style="width: 40px;height: 40px;border-radius: 20px;border: 1px solid #5A738E;margin-left: auto;margin-right: auto;color: #5A738E;"><span id = 'stage' style="position: relative;top:10px;">1/<?=count(json_decode($appraisal[$t]['responses']))?></span></div>
                               </div>
                               <div style="text-align: justify;">
                                  <?php $m_remark = explode(";",$appraisal[$t]['manager_remark'])?> 
                                  <?php $m_just = explode("%%%",$appraisal[$t]['manager_justification'])?> 
                                  <?php $appraisal_data = explode("%%%",$appraisal[$t]['appraisal_data'])?>
                                   <?php $remark = explode(";",$appraisal[$t]['staff_remarks'])?>
                                   <?php $justification = explode(";",$appraisal[$t]['staff_justifications'])?>
                                   <?php $thisappraisal = json_decode($appraisal[$t]['responses'])?>
                                   <div id="questionrow">
                                      <h5>Question 1</h5><p><?=$thisappraisal[0]->question?></p>
                                      <h5 style = 'margin-top:10px;'>Remark</h5><p> <?=$thisappraisal[0]->remark?></p>
                                      <h5 style = 'margin-top:10px;'>Justification</h5>
                                      <p><?=$thisappraisal[0]->justification?></p>    
                                   </div>

                                  <?php $flow = explode(";",$appraisal[$t]['appraisal_flow']) ?> 
                                  <?php for($e = 0; $e < count($flow); $e++) { ?>
                                  <?php $eachflow = explode(":",$flow[$e]) ?>   
                                  <div class="form-group">
                                  <label for="remark"><?= $eachflow[0]?> Remark</label>

                                  <select name = 'remark' <?=$eachflow[1] != $_SESSION['user']['email'] ? 'disabled' : ''?>  id = "remark<?=$e?>" class="form-control manager_remark" appraisal_data = '<?=$appraisal[0]['responses']?>' val="<?=uniqid()?>" question = "0" who = "<?=$eachflow[0]?>" address = "<?=$eachflow[1]?>" approval_level = "<?=($e+1)?>">
                                   
                                    <option value = ""><?= (isset($thisappraisal[0]->manager_remark[$e]) && $thisappraisal[0]->manager_remark[$e]->who == $eachflow[0]) ? $thisappraisal[0]->manager_remark[$e]->remark :'' ?></option>
                                    <option value = "1">1</option>
                                    <option value = "2">2</option>
                                    <option value = "3">3</option>
                                    <option value = "4">4</option>
                                    <option value = "5">5</option>
                                    <option value = "6">6</option>
                                    <option value = "7">7</option>
                                    <option value = "8">8</option>
                                    <option value = "9">9</option>
                                    <option value = "10">10</option>
                                  </select>
                                </div>
                               
                                <div class="form-group">
                                  <label for="remark"><?= $eachflow[0]?> Justification</label>
                                  <textarea   <?=$eachflow[1] != $_SESSION['user']['email'] ? 'disabled' : ''?> class="form-control manager_justification" rows="3" id = "justification<?=$e?>" appraisal_data = '<?=$appraisal[0]['responses']?>' name="justification" val="<?=$r?>" question = "0"  who = "<?=$eachflow[0]?>" address = "<?=$eachflow[1]?>" approval_level = "<?=($e+1)?>"><?= (isset($thisappraisal[0]->manager_justification[$e]) && $thisappraisal[0]->manager_justification[$e]->who == $eachflow[0]) ? $thisappraisal[0]->manager_justification[$e]->justification :'' ?></textarea>
                                </div>
                                   <?php }  ?>
                               </div>
                               <div class="row">
                                 <div class="btn-group main_btn" role="group" aria-label="Basic example" style="margin-top: 10px;">
                                  <button type="button" class="btn btn-warning" question = "0" who = "<?=$eachflow[0]?>" appraisal_data = '<?=$appraisal[0]['responses']?>' style="margin: 4px;" id="sprevious" appraisal_flow = '<?=$appraisal[$t]['appraisal_flow']?>'>Previous</button>
                                  <button type="button" class="btn btn-primary" question = "0"  who = "<?=$eachflow[0]?>" appraisal_data = '<?=$appraisal[0]['responses']?>' style="margin: 4px;" id="snext" appraisal_flow = '<?=$appraisal[$t]['appraisal_flow']?>'>Next</button>
                                 </div> 
                              </div>
                               <form action="process_staff_appraisal.php" method="POST" style="display: none">
                                 <input type="text" name="appraisal_id" value="<?=$appraisal[0]['appraisal_id']?>">
                                 <input type="text" name="staff_id" value="<?=$appraisal[0]['staff_id']?>">
                                 <textarea id="managerappraisallist" name="managerappraisallist"></textarea>
                                 <button type="submit" name="submit_data_manager" id = "submit_data_manager"></button>
                              </form>
                               
                              <!--  <div style="text-align: left;">
                              <?php if ($_SESSION['user']['appraisal_flow'] != "") {?>
                              <?php $appraisal_flow = explode(";",$_SESSION['user']['appraisal_flow']) ?>
                              <form action="process_approval_comments.php" method="POST" process_approvals_comment.php >  
                              <?php $comment_flow = explode(";", $appraisal[$t]['comments_flow']); ?>  
                              <?php for ($r = 0; $r < count($comment_flow); $r++) {?>  
                              <?php $this_comment_flow = explode(":", $comment_flow[$r]); ?>
                              <div class="form-group" style="margin-top: 15px;"> 
                                <label style="text-transform: capitalize;" for="<?=$comment_flow[$r]?>"><?=$this_comment_flow[0]?>'s Comment</label> 
                               <?php if(strtolower($this_comment_flow[0]) == strtolower($_SESSION['user']['position'])) {?>  
                                <textarea class="form-control textarea"  id = "textarea<?=$r?>"  appraisal_id = "<?=$appraisal[$t]['appraisal_id']?>" staff_id = "<?=$appraisal[$t]['staff_id']?>"  name=""><?=count($this_comment_flow) > 1 ? $this_comment_flow[1] : ''?></textarea>
                              <?php }else { ?>
                                <textarea class="form-control textarea" disabled="true"  id = "textarea<?=$r?>"  appraisal_id = "<?=$appraisal[$t]['id']?>"  name=""><?=count($this_comment_flow) > 1 ? $this_comment_flow[1] : ''?></textarea>
                              <?php } ?>  
                              </div> 
                               <?php }?> 
                              <input type="text" name="appraisal_id" id = "appraisal_id" style="display: none;">
                              <input type="text" name="staff_id" id = "staff_id" style="display: none;">
                              <input type="text" name="flowby" id = "flowby" style="display:none" value="<?=$_SESSION['user']['position']?>">
                              <input type="text" class='lremark' name="remark" id = "manager_remark" style="display: none;">
                              <textarea class='ljustification' id = "manager_justification" name = "justification" style="display: none;"></textarea>
                              <textarea id = "comment" name = "comment" style="display:none"></textarea>
                              <div class="form-group">
                                <button type="submit" class="btn btn-primary" name = "submit" style="">Add Comment</button>
                              </div> 
                              </form> 
                              <?php } ?>
                            </div> -->
                        </div>
                        </div>
                        <?php } ?>  
                  </div>
                </div>
              <?php } ?>
              </div>
              <div class="col-md-4 col-sm-12 col-xs-12">
                 <div class="x_panel">
                  <div class="x_title">
                    <h2>Performance<small></small></h2>
                  
                    <div class="clearfix"></div>
                  </div>
                  <?php $thisappraisal = json_decode($appraisal[0]['responses'])?>
                  <?php 
                    $score = 0;
                    for($r = 0; $r < count($thisappraisal); $r++) { 
                      if(isset($thisappraisal[$r]->score))
                         $score = (float)$thisappraisal[$r]->score + $score;
                      //echo $score.'<br>';
                    }

                  ?>
                  <div class="x_content">
                    <table class="table table-striped">
                      <tbody>
                        <tr>
                          <th scope="row">1</th>
                          <td>Employee Score </td>
                          <td><?php echo $score / 10 ?>% </td>
                        </tr>
                         <?php $flow = explode(";",$appraisal[0]['appraisal_flow']) ?> 
                          <?php for($e = 0; $e < count($flow); $e++) { ?>
                          <?php $eachflow = explode(":",$flow[$e]) ?>   
                         <tr>
                          <th scope="row"><?=$e+2?></th>
                          <td><?=$eachflow[0]?> </td>
                          <td><?php 
                           $mscore = 0;
                            for($r = 0; $r < count($thisappraisal); $r++) { 
                             
                              //echo $score.'<br>';
                               
                                if(isset($thisappraisal[$r]->manager_remark))
                                {
                                  for($p = 0; $p < count($thisappraisal[$r]->manager_remark); $p++) { 
                                    if(isset($thisappraisal[$r]->manager_remark[$p]->score) && $thisappraisal[$r]->manager_remark[$p]->who == $eachflow[0])
                                    $mscore = $thisappraisal[$r]->manager_remark[$p]->score + $mscore;
                                  }
                                }
                              
                            }
                            echo $mscore / 10;

                          ?>%</td>
                          
                        </tr>
                      <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Rating Summary<small></small></h2>
                  
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table class="table table-striped">
                      <tbody>
                        <tr>
                          <th scope="row">1</th>
                          <td>Lowest </td>
                        </tr>
                        <tr>
                          <th scope="row">10</th>
                          <td>Highest </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Approvals<small></small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <table class="table table-striped">
                      <tbody>
                        <?php if ($_SESSION['user']['appraisal_flow'] != "") {?>
                        <?php $appraisal_flow = explode(";",$_SESSION['user']['appraisal_flow']) ?>
                        <?php for ($r = 0; $r < count($appraisal_flow); $r++) {?>
                        <tr>
                          <th scope="row"><?=explode(":", $appraisal_flow[$r])[0]?></th>
                          <td><?=explode(":", $appraisal_flow[$r])[0]?></td>
                        </tr>
                       <?php } }?>
                      </tbody>
                    </table>
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
<?php include "footer.php"?>
<script type="text/javascript" src="js/appraisal.js?version=1.32"></script>
        

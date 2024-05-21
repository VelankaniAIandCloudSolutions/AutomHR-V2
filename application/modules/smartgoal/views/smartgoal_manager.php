<?php
   $goal_id = $this->uri->segment(3);
   $goal_details = $this->db->get_where('smartgoal',array('id'=>$goal_id))->row_array();
   $user_role_type = $this->session->userdata("user_type");
   $score_required = '';
   // $score_required = "required";
   // if($user_role_type != 58 && $user_role_type!= 44)
   // {
   //         $score_required = '';
   // }
   
   ?>
<div class="content container-fluid">
   <div class="row">
      <div class="col-sm-8">
         <h4 class="page-title">Smart Goal</h4>
      </div>
   </div>
   <div class="row">
      <div class="col-md-12">
         <div class="row">
            <div class="col-sm-4">
               <table class="table table-border user-info-table">
                  <tbody>
                     <tr>
                        <td>Employee</td>
                        <td class="text-right"><?php echo $goal_details['emp_name']?></td>
                     </tr>
                     <tr>
                        <td>Position</td>
                        <td class="text-right"><?php echo $goal_details['position']?></td>
                     </tr>
                     <tr>
                        <td>Direct Manager</td>
                        <td class="text-right"><?php echo $goal_details['lead']?></td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
         <form action="<?php echo base_url()?>smartgoal/edit_smartgoal" method="post">
            <input type="hidden" name="user_id" value="<?php echo $goal_details['user_id']?>">
            <input type="hidden" name="id" value="<?php echo $goal_details['id']?>">
            <input type="hidden" name="position" value="<?php echo $goal_details['position']?>">  
            <input type="hidden" name="lead" value="<?php echo $goal_details['lead']?>">
            <input type="hidden" name="emp_name" value="<?php echo $goal_details['emp_name']?>">
            <div class="form-group">
               <div class="join-year">
                  <span>Year</span>
                  <select class="select form-control" name="goal_year">
                     <option value="2024" <?php if($goal_details['goal_year'] == '2024') {echo"selected";}?>>2023-2024</option>
                  </select>
               </div>
            </div>
            <div class="form-group">
               <label>Goal Duration</label>
               <div class="radio_input">
                  <label class="radio-inline custom_radio">
                  <input type="radio" name="goal_duration" value="90 days" <?php if($goal_details['goal_duration'] == '90 days'){echo"checked";} ?> >90 Days <span class="checkmark"></span>
                  </label>
                  <label class="radio-inline custom_radio">
                  <input type="radio" name="goal_duration" value="6 month" <?php if($goal_details['goal_duration'] == '6 month'){echo"checked";} ?> >6 Month <span class="checkmark"></span>
                  </label>
                  <label class="radio-inline custom_radio">
                  <input type="radio" name="goal_duration" <?php if($goal_details['goal_duration'] == '1 year'){echo"checked";} ?> value="1 year">1 Year <span class="checkmark"></span>
                  </label> 
               </div>
            </div>
            <div class="performance-wrap">
               <div class="performance-box">
                  <div class="table-responsive">
                     <table class="table performance-table">
                        <thead>
                           <tr>
                              <th></th>
                              <th class="text-center" style="min-width: 140px;">Status</th>
                              <th class="text-center" style="min-width: 140px;">Start</th>
                              <th class="text-center" style="min-width: 140px;">Completed</th>
                              <th class="text-center" style="min-width: 140px;">Rating</th>
                              <th class="text-center" style="min-width: 85px;">Feedback</th>
                              <?php if($this->session->userdata("user_type_name") !='employee'){?>
                               <th class="text-center" style="min-width: 140px;">Manager Rating</th>
                           <?php } ?>

                           <th class="text-center" style="min-width: 140px;">Weightage (in percent)</th>
                           <?php if($this->session->userdata("user_type_name") !='employee'){?>
                           <th class="text-center" style="min-width: 140px;">Score</th>
                           <?php } ?>

                           </tr>
                        </thead>
                        <tbody>
                           <?php 
                              $goal = json_decode($goal_details['goals']);

                              $tmp_goal_array = json_decode($goal_details['goals'], true);
                              $tmp_manager_ratings_array = array_column($tmp_goal_array, 'manager_rating');
                              $tmp_manager_ratting = 'null';
                              if($role ==1)
                              {
                                 if(in_array("",$tmp_manager_ratings_array) || in_array("null",  $tmp_manager_ratings_array))
                                 {
                                    $tmp_manager_ratting= '';
                                 }
                              }
	
                              foreach ($goal as $key => $goal) { 
                               
                              $actions = $goal->goal_action;
                              $goals = $goal->goal;
                              if($goal->created_date == '')
                              {
                                 $start_dt = date("2023-04-01");
                              }
                              else{
                                 $start_dt = $goal->created_date;
                              }
                              if($goal->completed_date == '')
                              {
                                 $complete_dt = date("2024-03-31");
                              }
                              else{
                                 $complete_dt = $goal->completed_date;
                              }
                              
                              $status = $goal->status;
                              $rating = $goal->rating;
                              $goal_progress = $goal->goal_progress;
                              if($tmp_manager_ratting == '')
                              {
                                  $manager_rating = $tmp_manager_ratting;
                              }
                              else{
                                  $manager_rating = $goal->manager_rating;
                              }
                              
                              if($role==1)
                              {
		                      if(!empty($rating))
		                      {
		                       $rating= $goal->rating;
		                      }
		                      else{
		                      	$rating= array(4);
		                      }
                              }
                              
                             
                              $tmp_Weightage = $goal->Weightage;
                              $Weightage = str_replace("%","", $tmp_Weightage);
                              
                              $score = $goal->score;
                              $score =  round(($Weightage * $manager_rating) / 100, 2);


                              ?>
                           <tr >
                              <td style="width: 600px;">
                                 <div class="form-group">
                                    <label>Goal <?php echo $key+1?></label>
                                    <?php 
                                       if (is_array($goals)) {
                                       
                                       for ($i = 0; $i < count($goals); $i++)  { ?>
                                    <input type="text" class="form-control" data-goalid="goal_1" name="goal[<?php echo $key?>][]" id="goal" value="<?php echo $goals[$i];?>" readonly>
                                    <?php } } else { ?>
                                    <input type="text" class="form-control" data-goalid="goal_1" name="goal[<?php echo $key?>][]" id="goal" value="<?php echo $goals;?>" readonly>
                                    <?php } ?>
                                 </div>
                                 <div class="progress m-b-0">
                                    <?php 
                                       if (is_array($goal_progress)) {
                                       
                                       for ($i = 0; $i < count($goal_progress); $i++)  { ?>
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $goal_progress[$i]?>">
                                       <span class="goal_prog" value=""><?php echo $goal_progress[$i];?></span> 
                                    </div>
                                    <input type="hidden" class="goal_progress" name="goal_progress[<?php echo $key?>][]"value="<?php echo $goal_progress[$i];?>">
                                    <?php } } else { ?>
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $goal->goal_progress?>">
                                       <span class="goal_prog" value=""><?php echo $goal->goal_progress;?></span> 
                                    </div>
                                    <input type="hidden" class="goal_progress" name="goal_progress[<?php echo $key?>][]"value="<?php echo $goal->goal_progress;?>">
                                    <?php } ?>
                                 </div>
                              </td>
                              <td class="text-center">
                                 <div class="dropdown">
                                    <?php 
                                       if (is_array($status)) {
                                       for ($i = 0; $i < count($status); $i++)  {  ?>
                                    <select class="form-control" name="status[<?php echo $key?>][]">
                                       <option value="Approved" <?php if($status[$i] == "Approved") echo selected;?>>Approved</option>
                                       <option value="Pending" <?php if($status[$i] == "Pending") echo selected;?>>Pending</option>
                                    </select>
                                    <?php } } else { ?>
                                    <select class="form-control" name="status[<?php echo $key?>][]">
                                       <option value="Approved">Approved</option>
                                       <option value="Pending">Pending</option>
                                    </select>
                                    <?php } ?>
                                 </div>
                              </td>
                              <td class="text-center">
                                 <div class="cal-icon no-border-cal">
                                    <?php 
                                       if (is_array($start_dt)) {
                                       for ($i = 0; $i < count($start_dt); $i++)  { ?>
                                    <input type="text" class="form-control datetimepicker" name="created_date[<?php echo $key?>][]" id="created_date" value="<?php echo $start_dt[$i]?>" readonly>
                                    <?php } } else { ?>
                                    <input type="text" class="form-control datetimepicker" name="created_date[<?php echo $key?>][]" id="created_date" value="<?php echo $start_dt?>" readonly>
                                    <?php } ?>
                                 </div>
                              </td>
                              <td class="text-center">
                                 <div class="cal-icon no-border-cal">
                                    <?php 
                                       if (is_array($complete_dt)) {
                                       for ($i = 0; $i < count($complete_dt); $i++)  { ?>
                                    <input type="text" class="form-control datetimepicker" name="completed_date[<?php echo $key?>][]" id="completed_date" value="<?php echo $complete_dt[$i]?>" readonly>
                                    <?php } } else { ?>
                                    <input type="text" class="form-control datetimepicker" name="completed_date[<?php echo $key?>][]" id="completed_date" value="<?php echo $complete_dt?>" readonly>
                                    <?php } ?>
                                 </div>
                              </td>
                              
                              <td >
                                 <?php $ratings = $this->db->get_where('smart_goal_configuration')->row_array() ; ?>
                                 <select class="form-control select" name="rating[<?php echo $key?>][]" required>
                                    <option value=""> No Rating</option>
                                    <option value="1" <?php if(trim($rating[0]) == 1){echo"selected";} ?> >1</option>
                                    <option value="2" <?php if(trim($rating[0]) == 2){echo"selected";} ?>>2</option>
                                    <option value="3" <?php if(trim($rating[0]) == 3){echo"selected";} ?>>3</option>
                                    <option value="4" <?php if(trim($rating[0]) == 4){echo"selected";} ?> >4</option>
                                    <option value="5" <?php if(trim($rating[0]) == 5){echo"selected";} ?> >5</option>
                                 </select>
                              </td>

                              <td class="text-center">
                                 <button type="button" class="btn btn-success obj_feedback" onclick="goal_feedback(<?php echo $key?>)" data-id="<?php echo $key ?>"><i class="fa fa-pencil"></i></button>
                              </td>

                              <?php if($this->session->userdata("user_type_name") !='employee'){?>
                              <td class="text-center">
                                  <div class="">
                                      <select name='manager_rating[]' id='manager_rating' class="form-control manager_rating_goal" required>
                                          <option value=""> No Rating</option>
                                          <option value="1" <?php if($manager_rating == 1){echo"selected";} ?> >1</option>
                                          <option value="2" <?php if($manager_rating == 2){echo"selected";} ?>>2</option>
                                          <option value="3" <?php if($manager_rating == 3){echo"selected";} ?>>3</option>
                                          <option value="4" <?php if($manager_rating == 4){echo"selected";} ?> >4</option>
                                          <option value="5" <?php if($manager_rating == 5){echo"selected";} ?> >5</option>
                                      </select>
                                  </div>
                              </td>
                              <?php } ?>
                              <td class="text-center">
                                  <div class="">
                                      <input type="text" value='<?php echo $Weightage; ?>'  class="form-control Weightage" name="Weightage[]" id="Weightage" required >
                                  </div>
                              </td>
                               <?php if($this->session->userdata("user_type_name") !='employee'){?>
                              <td class="text-center">
                                  <div class="">
                                    <input type="text" class="form-control score" value='<?php echo $score;?>' name="score[]" id="score" <?php echo  $score_required; ?>>
                                  </div>
                              </td>
                              <?php } ?>
                           </tr>
                        </tbody>
                        <tbody>
                           <tr>
                              <td>
                                 <!-- Goal Actions -->
                                 <div class="task-wrapper goal-wrapper">
                                    <div class="task-list-container">
                                       <div class="task-list-body">
                                          <ul class="task-list" id="tasklist">
                                             <?php for ($i = 0; $i < count($actions); $i++)  {
                                                ?>
                                             <li class="task">
                                                <div class="task-container">
                                                   <span class="task-action-btn task-check">
                                                   <span class="action-circle large" title="Mark Complete">
                                                   <i class="material-icons">check</i>
                                                   </span>
                                                   </span>
                                                   <input type="text" class="form-control task-input" name="goal_action[<?php echo $key?>][]" data-action="action_1" value="<?php echo $actions[$i] ?>" readonly>
                                                </div>
                                             </li>
                                             <?php } ?>
                                          </ul>
                                       </div>
                                       <div class="task-list-footer">
                                          <div class="new-task-wrapper">
                                             <textarea class="add-new-goal" placeholder="Enter new goal action here. . ."></textarea>
                                             <span class="error-message hidden">You need to enter a goal action first</span>
                                             <span class="add-new-task-btn btn add_goal">Add Goal Action</span>
                                             <span class="cancel-btn btn close-goal-panel">Close</span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="notification-popup hide">
                                    <p>
                                       <span class="task"></span>
                                       <span class="notification-text"></span>
                                    </p>
                                 </div>
                                 <!-- /Goal Actions -->
                              </td>
                           </tr>
                           <?php } ?>
                        </tbody>
                     </table>
                  </div>
               </div>
               <br><br>
                                <div>
                                    <label>Training Needs</label>
                                    <textarea class="form-control" name="training_need" id="training_need"><?php echo $goal_details['training_need'];?></textarea>
                                </div>
                                <br><br>
               <div>
                  <input type="submit" value="Submit" class="btn btn-primary submit-btn" style="display:block;margin:auto;margin-top:15px">
               </div>
            </div>
      </div>
   </div>
</div>
</div>
<input type="hidden" name="" id="count" value="2">
<input type="hidden" name="" id="task_count" value="0">
<script id="goal-template">
   <li class="task">
       <div class="task-container">
           <span class="task-action-btn task-check">
               <span class="action-circle large complete-btn" title="Mark Complete">
                   <i class="material-icons">check</i>
               </span>
           </span>
           <input type="text" class="task-label form-control"> 
           <input type="hidden" class="taskdetails" name="goal_action[0][]" data-action="" value="">
           <span class="task-action-btn task-btn-right">
               <span class="action-circle large delete-btn" title="Delete Goal Action">
                   <i class="material-icons">delete</i>
               </span>
           </span>
       </div>
   </li>
</script>
<!-- Add Feedback Modal -->
<div class="modal fade" id="goalfbk" role="dialog">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal">&times;</button>
<h4 class="modal-title">Write Feedback</h4>
</div>
<div class="modal-body">
<form>
<div class="form-group">
<label>Feedback</label>
<?php 
   $goal = json_decode($goal_details['goals']);
   
   foreach ($goal as $key => $goals) {
      $feedback = $goals->feedback;
    ?>
<?php 
   if($feedback != '') {
   for ($i = 0; $i < count($feedback); $i++)  {
                                         ?>
<textarea rows="4" class="form-control goal_feedback" name="feedback[<?php echo $key?>][]" id="feedback_<?php echo $key?>"><?php echo $feedback[$i]?></textarea>
<?php }  } else {?> 
<textarea rows="4" class="form-control goal_feedback" name="feedback[<?php echo $key?>][]" id="feedback_<?php echo $key?>"><?php echo $goals->feedback?></textarea>
<?php } }?>
</div>
</form>  
</div>
</div>
</div>
</div> 
</form>
<!-- /Add Feedback Modal -->

<?php
   $goal_id = $this->uri->segment(3);
   $goal_details = $this->db->get_where('smartgoal',array('id'=>$goal_id))->row_array();
   
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
                     <option value="2019">2019</option>
                     <option value="2020">2020</option>
                     <option va;ue="2021">2021</option>
                     <option value="2022">2022</option>
                     <option value="2023">2023</option>
                     <option value="2024">2024</option>
                  </select>
               </div>
            </div>
            <div class="form-group">
               <label>Goal Duration</label>
               <div class="radio_input">
                  <label class="radio-inline custom_radio">
                  <input type="radio" name="goal_duration" value="90 days" checked>90 Days <span class="checkmark"></span>
                  </label>
                  <label class="radio-inline custom_radio">
                  <input type="radio" name="goal_duration" value="6 month">6 Month <span class="checkmark"></span>
                  </label>
                  <label class="radio-inline custom_radio">
                  <input type="radio" name="goal_duration" value="1 year">1 Year <span class="checkmark"></span>
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
                           </tr>
                        </thead>
                        <tbody>
                           <?php 
                              $goal = json_decode($goal_details['goals']);
                              
                              foreach ($goal as $key => $goal) { 
                               
                              $actions = $goal->goal_action;
                              $goals = $goal->goal;
                              $start_dt = $goal->created_date;
                              $complete_dt = $goal->completed_date;
                              $status = $goal->status;
                              $rating = $goal->rating;
                              $goal_progress = $goal->goal_progress;
                              
                              ?>
                           <tr>
                              <td>
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
                                    <select class="form-control" name="status[<?php echo $key?>][]" disabled>
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
                                    <input type="text" class="form-control datetimepicker" name="created_date[<?php echo $key?>][]" id="created_date" value="<?php echo $start_dt[$i]?>" disabled>
                                    <?php } } else { ?>
                                    <input type="text" class="form-control datetimepicker" name="created_date[<?php echo $key?>][]" id="created_date" value="<?php echo $start_dt?>" disabled>
                                    <?php } ?>
                                 </div>
                              </td>
                              <td class="text-center">
                                 <div class="cal-icon no-border-cal">
                                    <?php 
                                       if (is_array($complete_dt)) {
                                       for ($i = 0; $i < count($complete_dt); $i++)  { ?>
                                    <input type="text" class="form-control datetimepicker" name="completed_date[<?php echo $key?>][]" id="completed_date" value="<?php echo $complete_dt[$i]?>" disabled>
                                    <?php } } else { ?>
                                    <input type="text" class="form-control datetimepicker" name="completed_date[<?php echo $key?>][]" id="completed_date" value="<?php echo $complete_dt?>" disabled>
                                    <?php } ?>
                                 </div>
                              </td>
                              <td>
                                 <?php $ratings = $this->db->get_where('smart_goal_configuration')->row_array() ; ?>
                                 <?php 
                                    if (is_array($rating)) {
                                    for ($i = 0; $i < count($rating); $i++)  {  ?>
                                 <select class="form-control select" name="rating[<?php echo $key?>][]" disabled>
                                    <option>Select</option>
                                    <!-- <option value="Achieved" <?php if($rating[$i] == "Achieved") echo selected;?>>Achieved</option>
                                    <option value="Not Achieved" <?php if($rating[$i] == "Not Achieved") echo selected;?>>Not Achieved</option>
                                    <option value="Over Achieved" <?php if($rating[$i] == "Over Achieved") echo selected;?>>Over Achieved</option> -->
                                    <?php if(isset($ratings) && !empty($ratings)){ 
                                             $rating_no = explode('|',$ratings['rating_no']);
                                             $rating_value = explode('|',$ratings['rating_value']);
                                             $definition = explode('|',$ratings['definition']);
                                             $a= 1;
                                             for ($j=0; $j <count($rating_no) ; $j++) {
                                                if(!empty($rating_no[$i])){
                                               ?>
                                             <option value="<?php echo $rating_no[$j];?>" <?php echo ($rating[$i] == $rating_no[$j])?"selected":"";?>><?php echo $rating_value[$j];?></option>
                                          <?php } } } else { ?>
                                                <option value="">Ratings Not Found</option>
                                          <?php } ?>
                                 </select>
                                 <?php } } else { ?>
                                 <select class="form-control select" name="rating[<?php echo $key?>][]">
                                    <option>Select</option>
                                    <?php if(isset($ratings) && !empty($ratings)){ 
                                             $rating_no = explode('|',$ratings['rating_no']);
                                             $rating_value = explode('|',$ratings['rating_value']);
                                             $definition = explode('|',$ratings['definition']);
                                             $a= 1;
                                             for ($i=0; $i <count($rating_no) ; $i++) {
                                                if(!empty($rating_no[$i])){
                                               ?>
                                             <option value="<?php echo $rating_no[$i];?>"><?php echo $rating_value[$i];?></option>
                                          <?php } } } else { ?>
                                                <option value="">Ratings Not Found</option>
                                          <?php } ?>
                                    <!-- <option value="Achieved">Achieved</option>
                                    <option value="Not Achieved">Not Achieved</option>
                                    <option value="Over Achieved">Over Achieved</option> -->
                                 </select>
                                 <?php } ?>
                              </td>
                              <td class="text-center">
                                 <button type="button" class="btn btn-success obj_feedback" onclick="goal_feedback(<?php echo $key?>)" data-id="<?php echo $key ?>"><i class="fa fa-commenting"></i></button>
                              </td>
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
               <div>
                 
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
<h4 class="modal-title">Feedback</h4>
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
<textarea readonly rows="4" class="form-control goal_feedback" name="feedback[<?php echo $key?>][]" id="feedback_<?php echo $key?>"><?php echo $feedback[$i]?></textarea>
<?php }  } else {?> 
<textarea readonly rows="4" class="form-control goal_feedback" name="feedback[<?php echo $key?>][]" id="feedback_<?php echo $key?>"><?php echo $goals->feedback?></textarea>
<?php } }?>
</div>
</form>  
</div>
</div>
</div>
</div> 
</form>
<!-- /Add Feedback Modal -->
<script type="text/javascript">
         // var ratings = <?php echo $ratings; ?>;
          var ratings_value = new Array();
          var definition_value = new Array();
         // var rating_value = <?php echo $rating_value; ?>;
   //          var definition = '<?php echo $definition; ?>';

      
    <?php foreach($rating_no as $val){ ?>
        ratings_value.push('<?php echo $val; ?>');
    <?php } ?>
    <?php foreach($rating_value as $val){ ?>
        definition_value.push('<?php echo $val; ?>');
    <?php } ?>
      console.log(ratings_value);
      console.log(definition_value);
      </script>
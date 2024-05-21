<?php

$user_details = $this->session->userdata();
$user_role_type = $this->session->userdata("user_type");
// $score_required = "required";
// if($user_role_type != 58 && $user_role_type!= 44)
// {
//         $score_required= '';
// }

$score_required = '';

$employee_details = $this->db->get_where('users',array('id'=>$user_details['user_id']))->row_array();
$designation = $this->db->get_where('designation',array('id'=>$employee_details['designation_id']))->row_array();
$account_details = $this->db->get_where('account_details',array('user_id'=>$user_details['user_id']))->row_array();
$team_lead = $this->db->get_where('account_details',array('user_id'=>$employee_details['teamlead_id']))->row_array();
$teamlead = $this->db->get_where('account_details',array('user_id'=>$team_lead['user_id']))->row_array();

$disable = "disabled";

if($employee_details['teamlead_id'] == $user_details['user_id'] && $user_details['role_id']=='1')
{
	$disable = '';
}
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
                                                <td class="text-right"><?php echo $account_details['fullname']?></td>
                                                
                                            </tr>
                                            <tr>
                                                <td>Position</td>
                                                <td class="text-right"><?php echo $designation['designation']?></td>    
                                                
                                            </tr>
                                            <tr>
                                                <td>Direct Manager</td>
                                                <td class="text-right"><?php echo $teamlead['fullname']?></td>
                                                    
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <form action="<?php echo base_url()?>smartgoal/add_smartgoal" method="post" id="goal_form">
                                <input type="hidden" name="user_id" value="<?php echo $account_details['user_id']?>">
                                <input type="hidden" name="position" value="<?php echo $designation['designation']?>">  
                                <input type="hidden" name="lead" value="<?php echo $teamlead['fullname']?>">
                                <input type="hidden" name="fullname" value="<?php echo $account_details['fullname']?>">
                                <input type="hidden" name="user_type_name" id='user_type_name' value="<?php echo $this->session->userdata('user_type_name');?>">

                                        <div class="form-group">                                    
                                        <div class="join-year">
                                        <span>Year</span>
                                        <select class="select form-control" name="goal_year" >
                                            <option value="2024" <?php if("2024" == date("Y")){echo"selected";} ?> >2023-2024</option>
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
                                  
                                    <table class="table performance-table" id='smart_goal_table'> 
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th class="text-center" style="min-width: 140px;">Start</th>
                                                <th class="text-center" style="min-width: 140px;">Completed</th>
                                                <th class="text-center" style="min-width: 140px;">Self Rating</th>
                                                
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
                                            <tr>
                                                <td style="width: 600px;">
                                                    <div class="form-group">
                                                        <label>Goal 1</label>
                                                        <input type="text" class="form-control" data-goalid="goal_1" name="goal[]" id="goal" required>
                                                    </div>
                                                    <div class="progress m-b-0">
                                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">
                                                            <span class="progress_percentage" data-progress="progress_1" value=""></span>

                                                        </div>
                                                         <input type="hidden" class="goal_progress" name="goal_progress[]"value="">
                                                    </div>
                                                </td>

                                                <td class="text-center">
                                                    <div class="cal-icon">
                                                        <input type="text" class="form-control datetimepicker" name="created_date[]" id="created_date" required>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="cal-icon">
                                                        <input type="text" class="form-control datetimepicker" name="completed_date[]" id="completed_date" required>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="">
                                                        <select name='rating[]' id='rating' class="form-control" required>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <?php if($this->session->userdata("user_type_name") !='employee'){?>
                                                <td class="text-center">
                                                    <div class="">
                                                        <select name='manager_rating[]' id='manager_rating' class="form-control manager_rating_goal" <?php echo $disable;?> required>
                                                            <option value="">No Rating</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <?php } ?>
                                                <td class="text-center">
                                                    <div class="">
                                                        <input type="text"  class="form-control Weightage" name="Weightage[]" id="Weightage" <?php echo  $score_required; ?> >
                                                    </div>
                                                </td>
                                                 <?php if($this->session->userdata("user_type_name") !='employee'){?>
                                                <td class="text-center">
                                                    <div class="">
                                                        <input type="text" class="form-control score" name="score[]" id="score" <?php echo  $score_required; ?>>
                                                    </div>
                                                </td>
                                                <?php } ?>
                                                
                                            </tr>
                                        </tbody>
                                        <tbody>
                                            <tr>
                                                <td colspan="6">
                                                    <div class="add-another">
                                                        <a href="javascript:void(0);" class="add_goal_action"><i class="fa fa-plus"></i> Actions</a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <!-- Goal Actions -->
                                                    <div class="task-wrapper goal-wrapper">
                                                        <div class="task-list-container">
                                                            <div class="task-list-body">
                                                                <ul class="task-list" id="tasklist">
                                                                    <li class="task">
                                                                        <div class="task-container">
                                                                            <span class="task-action-btn task-check">
                                                                                <span class="action-circle large complete-btn" onclick="progress_smartgoal(this)" title="Mark Complete">
                                                                                    <i class="material-icons">check</i>
                                                                                </span>
                                                                            </span>
                                                                            <input type="text" class="form-control" name="goal_action[0][]" data-action="action_1" value="Goal Action 1">

                                                                            <span class="task-action-btn task-btn-right">
                                                                                <span class="action-circle large delete-btn" title="Delete Goal Action">
                                                                                    <i class="material-icons">delete</i>
                                                                                </span>
                                                                            </span>
                                                                        </div>
                                                                    </li>
                                                                    <li class="task">
                                                                        <div class="task-container">
                                                                            <span class="task-action-btn task-check">
                                                                                <span class="action-circle large complete-btn" onclick="progress_smartgoal(this)" title="Mark Complete">
                                                                                    <i class="material-icons">check</i>
                                                                                </span>
                                                                            </span>
                                                                              <input type="text" class="form-control" name="goal_action[0][]" data-action="action_1" value="Goal Action 2">
                                                                            <span class="task-action-btn task-btn-right">
                                                                                <span class="action-circle large delete-btn" title="Delete Goal Action">
                                                                                    <i class="material-icons">delete</i>
                                                                                </span>
                                                                            </span>
                                                                        </div>
                                                                    </li>
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
                                        </tbody>
                                    </table>
                                    
                               
                                </div>
                            </div>
                                </form>
                                <div class="add-another-goal">
                                <a href="javascript:void(0);" id="add_smart_goal" ><i class="fa fa-plus"></i>Add Smart Goal</a>
                            </div>

                                <br><br>
                                <div>
                                    <label>Training Needs</label>
                                    <textarea class="form-control" name="training_need" id="training_need"></textarea>
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
                        <span class="action-circle large complete-btn" onclick="progress_smartgoal(this)" title="Mark Complete">
                            <i class="material-icons">check</i>
                        </span>
                    </span>
                    <input type="text" class="task-label form-control"> 
                 
                    <span class="task-action-btn task-btn-right">
                        <span class="action-circle large delete-btn" title="Delete Goal Action">
                            <i class="material-icons">delete</i>
                        </span>
                    </span>
                </div>
            </li>
        </script>
 

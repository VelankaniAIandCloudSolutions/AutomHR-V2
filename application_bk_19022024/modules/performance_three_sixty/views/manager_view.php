<?php

$user_details = $this->session->userdata();

$employee_details = $this->db->get_where('users',array('id'=>$user_id))->row_array();
$designation = $this->db->get_where('designation',array('id'=>$employee_details['designation_id']))->row_array();
$account_details = $this->db->get_where('account_details',array('user_id'=>$user_id))->row_array();
$team_lead = $this->db->get_where('account_details',array('user_id'=>$employee_details['teamlead_id']))->row_array();
$teamlead = $this->db->get_where('account_details',array('user_id'=>$team_lead['user_id']))->row_array();
?>

<!-- Content -->
                <div class="content container-fluid">
					<div class="row">
						<div class="col-sm-8">
							<h4 class="page-title m-b-5">360 Performance</h4>
							<ol class="breadcrumb page-breadcrumb">
								<li><a href="#">Offer Accepted</a></li>
								<li><a href="#">Completed Forms</a></li>
								<li class="active">360 Performance</li>
							</ol>
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
											<!-- <tr>
												<td>Direct Manager</td>
												<td class="text-right"><?php echo $teamlead['fullname']?></td>
													
											</tr>	 -->										
										</tbody>
									</table>
								</div>
							
								<div class="col-sm-8">
									<div class="join-year">
										<span>Year</span>
										<select class="select form-control">
											<option>2019</option>
										</select>
									</div>
								</div>
							</div>
							<div class="performance-wrap">
							<?php if(!empty($performances_360)){ 
								$total_count = count($performances_360)+1;
								$count =1 ;
								foreach ($performances_360 as $performance_360) {
								$actions = unserialize($performance_360['action']);	
								?>
								
								
								<?php if($count == 1){?>
								<h3>Goals</h3>
								<div class="form-group">
									<label>Goal Duration</label>
									<div class="radio_input">
										<label class="radio-inline custom_radio">
											<input type="radio" readonly name="goal_duration" <?php echo ($performance_360['goal_duration'] == 1)?"checked":"";?> value="1">90 Days <span class="checkmark"></span>
										</label>
										<label class="radio-inline custom_radio">
											<input type="radio" readonly name="goal_duration" <?php echo ($performance_360['goal_duration'] == 2)?"checked":"";?> value="2">6 Month <span class="checkmark"></span>
										</label>
										<label class="radio-inline custom_radio">
											<input type="radio"readonly name="goal_duration" <?php echo ($performance_360['goal_duration'] == 3)?"checked":"";?> value="3">1 Year <span class="checkmark"></span>
										</label> 
									</div>
								</div>
							<?php }?>

								<div class="performance-box">
									<div class="table-responsive">
										<table class="table performance-table">
											<thead>
												<tr>
													<th style="min-width: 600px;"></th>
													<th class="text-center">Status</th>
													<th class="text-center" style="width: 120px;">Self Rating</th>
													<th class="text-center" style="width: 120px;">Rating</th>
													<th class="text-center" style="width: 85px;">Feedback</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<div class="" style="margin-bottom: 15px;">
															<label>Goal <?php echo $count;?></label>
															<input type="hidden" name="id" class="form-control" value="<?php echo $performance_360['id']?>">
															<input type="hidden" name="teamlead_id" class="form-control" value="<?php echo $employee_details['teamlead_id']?>">
															<input type="text" readonly name="goals" class="form-control" value="<?php echo $performance_360['goals']?>">

														</div>
														<div class="progress m-b-0">
                                                        
                                                        
                                                        	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $performance_360['progress'];?>%">
                                                             <span class=""><?php echo $performance_360['progress'];?>%</span>

                                                        </div>
                                                         <input type="hidden" class="goal_progress" name="progress"value="<?php echo $performance_360['progress'];?>">
													</td>
													<td class="text-center">														
														<div class="dropdown">
															<a class="badge <?php echo ($performance_360['status'] == 0 )?"btn-danger":"btn-success";?> dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
																<?php echo ($performance_360['status'] == 0 )?"Not Approved":"Approved";?> <i class="caret"></i>
															</a>
															<ul class="dropdown-menu pull-right">
																<li><a href="<?=base_url()?>performance_three_sixty/three_sixty_status/<?php echo $performance_360['id'];?>/1/<?php echo $performance_360['user_id']; ?>">Approved</a></li>
																<li><a href="<?=base_url()?>performance_three_sixty/three_sixty_status/<?php echo $performance_360['id'];?>/0/<?php echo $performance_360['user_id']; ?>">Not Approved</a></li>
															</ul>
														</div>
														
													</td>
													<?php $ratings = $this->db->get_where('competency_ratings')->row_array() ; ?>
													<td>
														<select class="form-control select" name="self_rating" disabled="disabled">
															<option value="">Not rated</option>
															<?php if(isset($ratings) && !empty($ratings)){ 
															$rating_no = explode('|',$ratings['rating_no']);
															$rating_value = explode('|',$ratings['rating_value']);
															$definition = explode('|',$ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_no[$i])){
															  ?>
															<option value="<?php echo $rating_no[$i];?>" <?php echo ($performance_360['self_rating'] == $rating_no[$i])?"selected":"";?>><?php echo $rating_value[$i];?></option>
														<?php } } } else { ?>
																<option value="">Ratings Not Found</option>
														<?php } ?>
															
														</select>
													</td>
													<td>
														<select class="form-control select manager_rating" name="rating" data-id="<?php echo $performance_360['id'] ?>">
															<?php if(isset($ratings) && !empty($ratings)){ 
															$rating_no = explode('|',$ratings['rating_no']);
															$rating_value = explode('|',$ratings['rating_value']);
															$definition = explode('|',$ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_no[$i])){
															  ?>
															<option value="<?php echo $rating_no[$i];?>" <?php echo ($performance_360['rating'] == $rating_no[$i])?"selected":"";?>><?php echo $rating_value[$i];?></option>
														<?php } } } else { ?>
																<option value="">Ratings Not Found</option>
														<?php } ?>
														</select>
													</td>
													<td class="text-center">
														<button class="btn btn-success" type="button" data-toggle="modal" data-target="#add_opj_feedback<?php echo $performance_360['id'];?>"><i class="fa fa-commenting"></i></button>
													</td>
												</tr>
											</tbody>
											<tbody>
												<!-- <tr>
													<td colspan="6">
														<div class="add-another">
															<a href="javascript:void(0);" class="add_goal_action"><i class="fa fa-plus"></i> Actions</a>
														</div>
													</td>
												</tr> -->
												<tr>
													<td style="min-width: 600px;">
														<!-- Goal Actions -->
														<!-- <div class="task-wrapper goal-wrapper">
															<div class="task-list-container">
																<div class="task-list-body">
																	<div class="label-input">
																		<label>Action</label>
																		<textarea name="action" readonly class="form-control" rows="4" cols="50"><?php echo $performance_360['action']?></textarea>
																	</div>
																	
																</div>																
																
															</div>
														</div> -->
														<div class="task-wrapper goal-wrapper">
                                                        <div class="task-list-container">
                                                            <div class="task-list-body">
                                                                <ul class="task-list" id="tasklist">
                                                                	<?php for ($i = 0; $i < count($actions); $i++)  {
                                                					?>
                                                                    <li class="task">
                                                                        <div class="task-container">
                                                                            <span class="task-action-btn task-check">
                                                                                <span class="action-circle large " title="Mark Complete">
                                                                                    <i class="material-icons">check</i>
                                                                                </span>
                                                                            </span>
                                                                            <input type="text" class="form-control" name="action[]" data-action="action_1" value="<?php echo $actions[$i]?>" readonly>

                                                                            
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
											</tbody>
										</table>
									</div>
								</div>
							

							<?php $count++; } ?>

								
							</div>
							
							<?php } ?>
						
							
							<div class="performance-wrap">
								<h3 class="m-b-20">Competencies</h3>
								<div class="performance-box comp-box m-b-0">
									<div class="table-responsive">
										<table class="table performance-table">
											<thead>
												<tr>
													<th class="text-center">Competencies</th>
													<th class="text-center" style="width: 120px;">Self Rating</th>
													<th class="text-center" style="width: 120px;">Rating</th>
													<th class="text-center" style="width: 85px;">Feedback</th>
													<th class="text-center" style="width: 85px;"></th>
												</tr>
											</thead>
										<?php $performance_competency = $this->db->order_by('competency','ASC')->get('performance_competency')->result_array(); ?>
											<tbody>
												<?php if(! empty($performance_competency)){ 

																foreach ($performance_competency as $performance_competencies) {?>
												<tr>
													<td>
														<input type="text" class="form-control" readonly name="competencies[]" required="" value="<?php echo $performance_competencies['competency'];?>">
														<input type="hidden" class="form-control" name="teamlead_id[]" required="" value="<?php echo $employee_details['teamlead_id']?>>">
													</td>
													<td>
														<select class="form-control select " name="self_rating[]" disabled="disabled">
															<option value="">Not rated</option>

															<?php if(isset($ratings) && !empty($ratings)){ 															
															$rating_no = explode('|',$ratings['rating_no']);
															$rating_value = explode('|',$ratings['rating_value']);
															$definition = explode('|',$ratings['definition']);
															$a= 1;
															$selected='';
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_no[$i])){
																	$self_rating_id=$this->db->where('competencies',$performance_competencies['id'])->where('user_id',$employee_details['id'])->get('dgt_competencies')->row_array();
														             	
														             	if(!empty($self_rating_id['self_rating'])){
														             		if($rating_no[$i]==$self_rating_id['self_rating'])
																			{
																				$selected='selected';
																			}
																			else
																			{
																				$selected='';
																			}
														             	} else{
														             		$selected='';
														             	}
															  ?>
															<option value="<?php echo $rating_no[$i];?>" <?php echo $selected;?>><?php echo $rating_value[$i];?></option>
														<?php } } } else { ?>
																<option value="">Ratings Not Found</option>
														<?php } ?>

														</select>
													</td>
													<td>
														<select class="form-control select manager_competence_rating" name="rating[]" data-id="<?php echo $performance_competencies['id'] ?>" data-userid="<?php echo $employee_details['id'];?>">
															<option value="">Not rated</option>
															<?php if(isset($ratings) && !empty($ratings)){ 														
															$rating_no = explode('|',$ratings['rating_no']);
															$rating_value = explode('|',$ratings['rating_value']);
															$definition = explode('|',$ratings['definition']);
															$a= 1;
															$selected='';
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_no[$i])){
																	$self_rating_id=$this->db->where('competencies',$performance_competencies['id'])->where('user_id',$employee_details['id'])->get('dgt_competencies')->row_array();
														             	
														             	if(!empty($self_rating_id['rating'])){
														             		if($rating_no[$i]==$self_rating_id['rating'])
																			{
																				$selected='selected';
																			}
																			else
																			{
																				$selected='';
																			}
														             	} else{
														             		$selected='';
														             	}
															  ?>
															<option value="<?php echo $rating_no[$i];?>" <?php echo $selected;?>><?php echo $rating_value[$i];?></option>
														<?php } } } else { ?>
																<option value="">Ratings Not Found</option>
														<?php } ?>
														</select>
													</td>
													<?php $feedback=$this->db->where('competencies',$performance_competencies['id'])->where('user_id',$employee_details['id'])->get('dgt_competencies')->row_array(); ?>
													<td class="text-center">
														<button class="btn btn-success" type="button" data-toggle="modal" data-target="#add_com_feedback<?php echo $feedback['id'];?>"><i class="fa fa-commenting"></i></button>
													</td>
													<!-- <td>
														<button type="button" class="btn btn-white add_competency" data-toggle="tooltip" data-original-title="Add Competency"><i class="fa fa-plus-circle"></i></button>
													</td> -->
												</tr>
											</tbody>
										<?php } }?>
										</table>
									</div>
								</div>
							</div>
							
						</div>
					</div>
                </div>
				<!-- / Content -->
				<div id="opj_feedback" class="modal center-modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Feedback</h4>
					</div>
					<div class="modal-body">
						<ul class="review-list">
							<li>
								<div class="review">
									<div class="review-author">
										<img class="avatar" alt="User Image" src="assets/img/user.jpg">
									</div>
									<div class="review-block">
										<div class="review-by">
											<span class="review-author-name">Mark Boydston</span>
										</div>
										<p>With great power comes great capability</p>
										<span class="review-date">Feb 6, 2019</span>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- /View Feedback Modal -->
		 <?php $performances_360 = $this->db->select()
							->from('performance_360')
							->get()->result_array();
					
			foreach ($performances_360 as $performance_360) { ?>
				
		<!-- Add Feedback Modal -->
		<div id="add_opj_feedback<?php echo $performance_360['id']?>" class="modal center-modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Feedback</h4>
					</div>
					<form class="form-horizontal"  action="<?=base_url()?>performance_three_sixty/three_sixty_feedback" method="POST">
					<div class="modal-body">
						<?php $feed_backs = $this->db->select()
							->from('three_sixty_feedback')
							->where('goal_id',$performance_360['id'])
							->get()->result_array();  
							// echo ($this->db->last_query());
							if(!empty($feed_backs)){
							?>
						<ul >
							<?php foreach ($feed_backs as $feed_back) { ?>
								<li><?php echo $feed_back['feed_back'];?></li>
							<?php }?>						
						</ul>
						<?php }?>
						<div class="form-group">
							<label>Write Feedback</label>
							<textarea rows="4" class="form-control" name="feed_back"></textarea>
							<input type="hidden" name="goal_id" value="<?php echo $performance_360['id']?>">
							<input type="hidden" name="user_id" value="<?php echo $performance_360['user_id']?>">
						</div>
						<div class="submit-section">
							<input type="submit" value="Submit" class="btn btn-primary submit-btn">
						</div>
					</div>
				</form>
				</div>
			</div>
		</div>
	<?php } ?>
		<!-- /Add Feedback Modal -->
		<?php $competencies = $this->db->select()
							->from('competencies')
							->get()->result_array();
							// echo $competencies; exit
					if(!empty($competencies)){
			foreach ($competencies as $competence) { ?>
				
		<!-- Add Feedback Modal -->
		<div id="add_com_feedback<?php echo $competence['id'];?>" class="modal center-modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Feedback</h4>
					</div>
					<form class="form-horizontal"  action="<?=base_url()?>performance_three_sixty/competencies_feedback" method="POST">
					<div class="modal-body">
						<?php $feed_backs = $this->db->select()
							->from('competencies_feedback')
							->where('competencies_id',$competence['id'])
							->get()->result_array();  
							// echo ($this->db->last_query());
							if(!empty($feed_backs)){
							?>
						<ul >
							<?php foreach ($feed_backs as $feed_back) { ?>
								<li><?php echo $feed_back['feed_back'];?></li>
							<?php }?>						
						</ul>
						<?php }?>
						<div class="form-group">
							<label>Write Feedback</label>
							<textarea rows="4" class="form-control" name="feed_back"></textarea>
							<input type="hidden" name="competencies_id" value="<?php echo $competence['id']?>">
							<input type="hidden" name="user_id" value="<?php echo $competence['user_id']?>">
						</div>
						<div class="submit-section">
							<input type="submit" value="Submit" class="btn btn-primary submit-btn">
						</div>
					</div>
				</form>
				</div>
			</div>
		</div>
	<?php }
} ?>
		<!-- /Add Feedback Modal -->
		


		<div class="sidebar-overlay" data-reff="#sidebar"></div>


		
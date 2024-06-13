<?php

$user_details = $this->session->userdata();

$employee_details = $this->db->get_where('users',array('id'=>$user_details['user_id']))->row_array();
$designation = $this->db->get_where('designation',array('id'=>$employee_details['designation_id']))->row_array();
$account_details = $this->db->get_where('account_details',array('user_id'=>$user_details['user_id']))->row_array();
$team_lead = $this->db->get_where('account_details',array('user_id'=>$employee_details['teamlead_id']))->row_array();

$teamlead = $this->db->get_where('account_details',array('user_id'=>$team_lead['user_id']))->row_array();

?>
          
                <div class="content container-fluid">
					<div class="row">
						<div class="col-sm-8">
							<h4 class="page-title m-b-5">OKR Performance</h4>
							<!-- <ol class="breadcrumb page-breadcrumb">
								<li><a href="#">Offer Accepted</a></li>
								<li><a href="#">Completed Forms</a></li>
								<li class="active">Set Your Goal</li>
							</ol> -->
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
							<form action="<?php echo base_url()?>performance/add_goals" method="post">
								<input type="hidden" name="user_id" value="<?php echo $account_details['user_id']?>">
								<input type="hidden" name="position" value="<?php echo $designation['designation']?>">	
								<input type="hidden" name="lead" value="<?php echo $teamlead['user_id']?>">
								<input type="hidden" name="fullname" value="<?php echo $account_details['fullname']?>">

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
													<th class="text-center">Status</th>
													<th class="text-center">Progress</th>
													<th class="text-center">Grading</th>
													
												</tr>
											</thead>
											<tbody>
												<tr>
												<td>
													<div class="label-input">
														<label>Objective 1</label>
														<input type="text" class="form-control" name="objective[]">
													</div>
													</td>
													<td class="text-center">
													 <span class="badge btn-success" name="status">Pending</span>
													 <input type="hidden" class="okr_status" name="okr_status[]" value="Pending">
													</td>
													<td class="text-center">
														<!-- <button class="btn btn-warning demo" type="button" id="demo" 

														data-toggle="modal" data-target="#progress_bar" name="progress">
															

														</button> -->

														<button class="btn btn-warning demo" type="button" id="demo" 

														onclick="show_progress_bar(this)" name="progress[]"  >
															
															
														</button>

														<input type="hidden" class="progress_value" name="progress_value[]" id="progress_value" value="">
													</td>
													<!-- <td class="text-center"> -->
														<!-- <strong class="grade" name="grade"></strong>
														<input type="hidden" class="grade_val" name="grade_value[]" value=""> -->
														<!-- <select class="form-control select" name="grade_value[]" >
															<?php $ratings = $this->db->get_where('okr_ratings')->row_array() ; ?>
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

														</select>
													</td> -->
													
												</tr>
											</tbody>
											<tbody class="key_result_container">
												<tr>
													<td>
														<div class="label-input">
															<label>Key Result 1</label>
															<input type="text" class="form-control" name="key_result[]">
															<!-- <button type="button" class="btn btn-white add_key_result" data-toggle="tooltip" data-original-title="Add Key Result"><i class="fa fa-plus-circle"></i></button> -->

														</div>
													</td>
													<td class="text-center">
														<span class="badge btn-info">Pending</span>
														<input type="hidden" class="key_status" name="key_status[]" value="Pending">
													</td>
													<td class="text-center">
														<!-- <button class="btn btn-success keyres_progress" type="button" data-toggle="modal" data-target="#key_progress"></button> -->


													<button class="btn btn-success keyres_progress" type="button" id="keyres_progress" onclick="show_keyprogress_bar(this)" name="key_progress[]">
													</button>
													<input type="hidden" class="keyres_value" name="keyres_value[]" value="">
													</td>
													<!-- <td class="text-center"> -->
														<!-- <strong class="key_grade" name="key_grade"></strong>
														<input type="hidden" class="key_gradeval" name="key_gradeval[]" value=""> -->
														<!-- <select class="form-control select" name="key_gradeval[]" >
															<?php $ratings = $this->db->get_where('okr_ratings')->row_array() ; ?>
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

														</select>
													</td> -->
													
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							    </form>
								<div class="add-another-obj">
									<a href="javascript:void(0);" id="add_another_objective"><i class="fa fa-plus"></i> Add Another Objective</a>
								</div>
								<div>
								<input type="submit" value="Submit" class="btn btn-primary submit-btn" style="display:block;margin:auto;margin-top:15px">
								</div>
							</div>
						</div>
					</div>
                </div>

            </div>
        

		<!-- View Feedback Modal -->
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

		<!-- View progress Modal -->
		<div id="progress_bar" class="modal center-modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Objective Progress</h4>
					</div>
					<div class="modal-body">
						<input type="range" min="0" max="100" value="0" class="okr_progress" id="myRange">
					</div>
				</div>
			</div>
		</div>
		<!-- /View progress Modal -->


		<!-- View key progress Modal -->
		<div id="key_progress" class="modal center-modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Key Result Progress</h4>
					</div>
					<div class="modal-body">
						<input type="range" min="0" max="100" value="0" class="okr_key" id="key_range">
					</div>
				</div>
			</div>
		</div>
		<!-- /View key progress Modal -->

		<!-- Add Feedback Modal -->
		<div id="add_opj_feedback" class="modal center-modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Feedback</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label>Write Feedback</label>
							<textarea rows="4" class="form-control"></textarea>
						</div>
						<div class="submit-section">
							<input type="submit" value="Submit" class="btn btn-primary submit-btn">
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Add Feedback Modal -->

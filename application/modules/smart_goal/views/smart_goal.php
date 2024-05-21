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
												<td class="text-right">Joe Smith</td>
											</tr>
											<tr>
												<td>Position</td>
												<td class="text-right">Financial Analysist</td>
											</tr>
											<tr>
												<td>Direct Manager</td>
												<td class="text-right">Elon Musk</td>
											</tr>
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
					
							<h3>Goals</h3>
							<div class="form-group">
								<label>Goal Duration</label>
								<div class="radio_input">
										<label class="radio-inline custom_radio">
											<input type="radio" name="optradio" checked="">90 Days <span class="checkmark"></span>
										</label>
										<label class="radio-inline custom_radio">
											<input type="radio" name="optradio">6 Month <span class="checkmark"></span>
										</label>
										<label class="radio-inline custom_radio">
											<input type="radio" name="optradio">1 Year <span class="checkmark"></span>
										</label> 
									</div>
							</div>
							
							<!-- Performance Box -->
							<div class="performance-box">
								<div class="table-responsive">
									<form id="create_goal"  method="POST" >
									<table class="table performance-table">
										<thead>
											<tr>
												<th></th>
												<th class="text-center" style="min-width: 140px;">Status</th>
												<th class="text-center" style="min-width: 140px;">Start Date</th>
												<th class="text-center" style="min-width: 140px;">Completed Date</th>
												<th class="text-center" style="min-width: 85px;">Feedback</th>
												<th class="text-center" style="min-width: 140px;">Rating</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td style="width: 600px;">
													<div class="form-group">
														<label>Goal 1</label>
														<input type="text" class="form-control" name="goal" id="goal">
													</div>
													<div class="progress m-b-0">
														<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%">
															<span>90%</span>
														</div>
													</div>
												</td>
												<td class="text-center">
													<div class="dropdown">
														<a class="badge btn-danger dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
															Not Approved <i class="caret"></i>
														</a>
														<ul class="dropdown-menu pull-right">
															<li><a href="#">Approved</a></li>
															<li><a href="#">Not Approved</a></li>
														</ul>
													</div>
												</td>
												<td class="text-center">
													<div class="cal-icon">
														<input type="text" class="form-control datetimepicker" name="created_date" id="created_date">
													</div>
												</td>
												<td class="text-center">
													<div class="cal-icon">
														<input type="text" class="form-control datetimepicker" name="completed_date" id="completed_date">
													</div>
												</td>
												<td class="text-center">
													<button class="btn btn-success" type="button" data-toggle="modal" data-target="#opj_feedback"><i class="fa fa-commenting"></i></button>
												</td>
												<td>
													<select class="form-control select" name="rating" id="rating">
														<option>Select</option>
														<option value="achieved">Achieved</option>
														<option value="not_achieved">Not Achieved</option>
														<option value="over_achieved">Over Achieved</option>
													</select>
												</td>
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
												<td style="min-width: 600px;">
													<!-- Goal Actions -->
													<div class="task-wrapper goal-wrapper">
														<div class="task-list-container">
															<div class="task-list-body">
																<ul class="task-list">
																	<li class="task">
																		<div class="task-container">
																			<span class="task-action-btn task-check">
																				<span class="action-circle large complete-btn" title="Mark Complete">
																					<i class="material-icons">check</i>
																				</span>
																			</span>
																			<span class="task-label" contenteditable="true">Goal Action 1</span>
																			<span class="task-action-btn task-btn-right">
																				<span class="action-circle large delete-btn" title="Delete Goal Action">
																					<i class="material-icons">delete</i>
																				</span>
																			</span>
																		</div>
																	</li>
																	<li class="completed task">
																		<div class="task-container">
																			<span class="task-action-btn task-check">
																				<span class="action-circle large complete-btn" title="Mark Complete">
																					<i class="material-icons">check</i>
																				</span>
																			</span>
																			<span class="task-label" contenteditable="true">Goal Action 2</span>
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
									<div class="m-t-30 text-center">
										<button class="btn btn-primary create_goal_submit" type="submit" id="create_offers_submit" data-count ="">Create Goal</button>
									</div>
								</form>
								</div>
							</div>
							<!-- /Performance Box -->
							
							<!-- Performance Box -->
							<!-- <div class="performance-box">
								<div class="table-responsive">
									<table class="table performance-table">
										<thead>
											<tr>
												<th></th>
												<th class="text-center" style="width: 150px;">Status</th>
												<th class="text-center" style="width: 150px;">Start Date</th>
												<th class="text-center" style="width: 150px;">Completed Date</th>
												<th class="text-center" style="width: 85px;">Feedback</th>
												<th class="text-center" style="width: 140px;">Rating</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<div class="form-group">
														<label>Goal 2</label>
														<input type="text" class="form-control">
													</div>
													<div class="progress m-b-0">
														<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%">
															<span>30%</span>
														</div>
													</div>
												</td>
												<td class="text-center">
													<div class="dropdown">
														<a class="badge btn-danger dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
															Not Approved <i class="caret"></i>
														</a>
														<ul class="dropdown-menu pull-right">
															<li><a href="#">Approved</a></li>
															<li><a href="#">Not Approved</a></li>
														</ul>
													</div>
												</td>
												<td class="text-center">
													<div class="cal-icon">
														<input type="text" class="form-control datetimepicker">
													</div>
												</td>
												<td class="text-center">
													<div class="cal-icon">
														<input type="text" class="form-control datetimepicker">
													</div>
												</td>
												<td class="text-center">
													<button class="btn btn-success" type="button" data-toggle="modal" data-target="#opj_feedback"><i class="fa fa-commenting"></i></button>
												</td>
												<td>
													<select class="form-control select">
														<option>Select</option>
														<option>Achieved</option>
														<option>Not Achieved</option>
														<option>Over Achieved</option>
													</select>
												</td>
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
										</tbody>
									</table>
								</div>
							</div> -->
							<!-- /Performance Box -->
								
							<div class="add-another-goal">
								<a href="javascript:void(0);" id="add_smart_goal" ><i class="fa fa-plus"></i> Add Smart Goal</a>
							</div>
							<input type="hidden" name="" id="count" value="2">
						</div>
					</div>
                </div>

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

		<div class="sidebar-overlay" data-reff="#sidebar"></div>
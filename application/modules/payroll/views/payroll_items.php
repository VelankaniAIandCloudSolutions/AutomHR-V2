				<!-- Page Content -->
                <div class="content container-fluid">
				
					<!-- Page Title -->
					<div class="row">
						<div class="col-sm-12">
							<h4 class="page-title">Payroll Items</h4>
						</div>
					</div>
					<!-- /Page Title -->
					
					<!-- Page Tab -->
					<div class="page-menu">
						<div class="row">
							<div class="col-md-12">
								<ul class="nav nav-tabs nav-tabs-bottom">
									<li class="active">
										<a data-toggle="tab" href="#tab_additions">Additions</a>
									</li>
									<li>
										<a data-toggle="tab" href="#tab_overtime">Overtime</a>
									</li>
									<li>
										<a data-toggle="tab" href="#tab_deductions">Deductions</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /Page Tab -->
					
					<!-- Tab Content -->
					<div class="tab-content">
					
						<!-- Additions Tab -->
						<div class="tab-pane in active" id="tab_additions">
						
							<!-- Add Addition Button -->
							<div class="text-right m-b-30 clearfix">
								<button class="btn btn-primary add-btn" type="button" data-toggle="modal" data-target="#add_addition"><i class="fa fa-plus"></i> Add Addition</button>
							</div>
							<!-- /Add Addition Button -->

							<div class="payroll-table card">
								<table class="table table-hover table-radius">
									<thead>
										<tr>
											<th>Name</th>
											<th>Category</th>
											<th>Default/Unit Amount</th>
											<th class="text-right">Action</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th>Leave balance amount</th>
											<td>Monthly remuneration</td>
											<td>$5</td>
											<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
													<ul class="dropdown-menu pull-right">
														<li><a href="#" data-toggle="modal" data-target="#edit_addition"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
														<li><a href="#" data-toggle="modal" data-target="#delete_addition"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
										<tr>
											<th>Arrears of salary</th>
											<td>Additional remuneration</td>
											<td>$8</td>
											<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
													<ul class="dropdown-menu pull-right">
														<li><a href="#" data-toggle="modal" data-target="#edit_addition"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
														<li><a href="#" data-toggle="modal" data-target="#delete_addition"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<th>Gratuity</th>
											<td>Monthly remuneration</td>
											<td>$20</td>
											<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
													<ul class="dropdown-menu pull-right">
														<li><a href="#" data-toggle="modal" data-target="#edit_addition"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
														<li><a href="#" data-toggle="modal" data-target="#delete_addition"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<th>TDS</th>
											<td>Monthly remuneration</td>
											<td>$20</td>
											<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
													<ul class="dropdown-menu pull-right">
														<li><a href="#" data-toggle="modal" data-target="#edit_addition"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
														<li><a href="#" data-toggle="modal" data-target="#delete_addition"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
													</div>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<!-- Additions Tab -->
						
						<!-- Overtime Tab -->
						<div class="tab-pane" id="tab_overtime">
						
							<!-- Add Overtime Button -->
							<div class="text-right m-b-30 clearfix">
								<button class="btn btn-primary add-btn" type="button" data-toggle="modal" data-target="#add_overtime"><i class="fa fa-plus"></i> Add Overtime</button>
							</div>
							<!-- /Add Overtime Button -->

							<div class="payroll-table card">
								<table class="table table-hover table-radius">
									<thead>
										<tr>
											<th>Name</th>
											<th>Rate</th>
											<th class="text-right">Action</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th>Normal day OT 1.5x</th>
											<td>Hourly 1.5</td>
											<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
													<ul class="dropdown-menu pull-right">
														<li><a href="#" data-toggle="modal" data-target="#edit_overtime"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
														<li><a href="#" data-toggle="modal" data-target="#delete_overtime"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
										<tr>
											<th>Public holiday OT 3.0x</th>
											<td>Hourly 3</td>
											<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
													<ul class="dropdown-menu pull-right">
														<li><a href="#" data-toggle="modal" data-target="#edit_overtime"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
														<li><a href="#" data-toggle="modal" data-target="#delete_overtime"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
										<tr>
											<th>Rest day OT 2.0x</th>
											<td>Hourly 2</td>
											<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
													<ul class="dropdown-menu pull-right">
														<li><a href="#" data-toggle="modal" data-target="#edit_overtime"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
														<li><a href="#" data-toggle="modal" data-target="#delete_overtime"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							
						</div>
						<!-- /Overtime Tab -->
						
						<!-- Deductions Tab -->
						<div class="tab-pane" id="tab_deductions">
						
							<!-- Add Deductions Button -->
							<div class="text-right m-b-30 clearfix">
								<button class="btn btn-primary add-btn" type="button" data-toggle="modal" data-target="#add_deduction"><i class="fa fa-plus"></i> Add Deduction</button>
							</div>
							<!-- /Add Deductions Button -->

							<div class="payroll-table card">
								<table class="table table-hover table-radius">
									<thead>
										<tr>
											<th>Name</th>
											<th>Default/Unit Amount</th>
											<th class="text-right">Action</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th>Absent amount</th>
											<td>$12</td>
											<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
													<ul class="dropdown-menu pull-right">
														<li><a href="#" data-toggle="modal" data-target="#edit_deduction"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
														<li><a href="#" data-toggle="modal" data-target="#delete_deduction"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
										<tr>
											<th>Advance</th>
											<td>$7</td>
											<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
													<ul class="dropdown-menu pull-right">
														<li><a href="#" data-toggle="modal" data-target="#edit_deduction"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
														<li><a href="#" data-toggle="modal" data-target="#delete_deduction"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
										<tr>
											<th>Unpaid leave</th>
											<td>$3</td>
											<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
													<ul class="dropdown-menu pull-right">
														<li><a href="#" data-toggle="modal" data-target="#edit_deduction"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
														<li><a href="#" data-toggle="modal" data-target="#delete_deduction"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							
						</div>
						<!-- /Deductions Tab -->
						
					</div>
					<!-- Tab Content -->
					
                </div>
				<!-- /Page Content -->
				
				<!-- Add Addition Modal -->
				<div id="add_addition" class="modal custom-modal center-modal fade" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Add Addition</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form>
									<div class="form-group">
										<label>Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="form-group">
										<label>Category <span class="text-danger">*</span></label>
										<select class="select">
											<option>Select a category</option>
											<option>Monthly remuneration</option>
											<option>Additional remuneration</option>
										</select>
									</div>
									<div class="form-group">
										<label class="block">Unit calculation</label>
										<div class="status-toggle">
											<input type="checkbox" id="unit_calculation" class="check">
											<label for="unit_calculation" class="checktoggle">checkbox</label>
										</div>
									</div>
									<div class="form-group">
										<label>Unit Amount</label>
										<div class="input-group">
											<span class="input-group-addon">
												$
											</span>
											<input type="text" class="form-control">
											<span class="input-group-addon">
												.00
											</span>
										</div>
									</div>
									<div class="form-group">
										<label class="block">Assignee</label>
										<label class="radio-inline">
											<input type="radio" name="addition_assignee" id="addition_no_emp" value="option1"> No assignee
										</label>
										<label class="radio-inline">
											<input type="radio" name="addition_assignee" id="addition_all_emp" value="option2"> All employees
										</label>
										<label class="radio-inline">
											<input type="radio" name="addition_assignee" id="addition_single_emp" value="option3"> Select Employee
										</label>
										<div>
											<select class="select">
												<option>-</option>
												<option>Select All</option>
												<option>John Doe</option>
												<option>Richard Miles</option>
											</select>
										</div>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Add Addition Modal -->
				
				<!-- Edit Addition Modal -->
				<div id="edit_addition" class="modal custom-modal center-modal fade" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Edit Addition</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form>
									<div class="form-group">
										<label>Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="form-group">
										<label>Category <span class="text-danger">*</span></label>
										<select class="select">
											<option>Select a category</option>
											<option>Monthly remuneration</option>
											<option>Additional remuneration</option>
										</select>
									</div>
									<div class="form-group">
										<label class="block">Unit calculation</label>
										<div class="status-toggle">
											<input type="checkbox" id="edit_unit_calculation" class="check">
											<label for="edit_unit_calculation" class="checktoggle">checkbox</label>
										</div>
									</div>
									<div class="form-group">
										<label>Unit Amount</label>
										<div class="input-group">
											<span class="input-group-addon">
												$
											</span>
											<input type="text" class="form-control">
											<span class="input-group-addon">
												.00
											</span>
										</div>
									</div>
									<div class="form-group">
										<label class="block">Assignee</label>
										<label class="radio-inline">
											<input type="radio" name="edit_addition_assignee" id="edit_addition_no_emp" value="option1"> No assignee
										</label>
										<label class="radio-inline">
											<input type="radio" name="edit_addition_assignee" id="edit_addition_all_emp" value="option2"> All employees
										</label>
										<label class="radio-inline">
											<input type="radio" name="edit_addition_assignee" id="edit_addition_single_emp" value="option3"> Select Employee
										</label>
										<div>
											<select class="select">
												<option>-</option>
												<option>Select All</option>
												<option>John Doe</option>
												<option>Richard Miles</option>
											</select>
										</div>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Save</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Edit Addition Modal -->
				
				<!-- Delete Addition Modal -->
				<div class="modal custom-modal center-modal fade" id="delete_addition" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-head">
									<h3>Delete Addition</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-xs-6">
											<a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
										</div>
										<div class="col-xs-6">
											<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Delete Addition Modal -->
				
				<!-- Add Overtime Modal -->
				<div id="add_overtime" class="modal custom-modal center-modal fade" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Add Overtime</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form>
									<div class="form-group">
										<label>Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="form-group">
										<label>Rate Type <span class="text-danger">*</span></label>
										<select class="select">
											<option>-</option>
											<option>Daily Rate</option>
											<option>Hourly Rate</option>
										</select>
									</div>
									<div class="form-group">
										<label>Rate <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Add Overtime Modal -->
				
				<!-- Edit Overtime Modal -->
				<div id="edit_overtime" class="modal custom-modal center-modal fade" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Edit Overtime</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form>
									<div class="form-group">
										<label>Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="form-group">
										<label>Rate Type <span class="text-danger">*</span></label>
										<select class="select">
											<option>-</option>
											<option>Daily Rate</option>
											<option>Hourly Rate</option>
										</select>
									</div>
									<div class="form-group">
										<label>Rate <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Save</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Edit Overtime Modal -->
				
				<!-- Delete Overtime Modal -->
				<div class="modal custom-modal center-modal fade" id="delete_overtime" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-head">
									<h3>Delete Overtime</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-xs-6">
											<a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
										</div>
										<div class="col-xs-6">
											<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Delete Overtime Modal -->
				
				<!-- Add Deduction Modal -->
				<div id="add_deduction" class="modal custom-modal center-modal fade" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Add Deduction</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form>
									<div class="form-group">
										<label>Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="form-group">
										<label class="block">Unit calculation</label>
										<div class="status-toggle">
											<input type="checkbox" id="unit_calculation_deduction" class="check">
											<label for="unit_calculation_deduction" class="checktoggle">checkbox</label>
										</div>
									</div>
									<div class="form-group">
										<label>Unit Amount</label>
										<div class="input-group">
											<span class="input-group-addon">
												$
											</span>
											<input type="text" class="form-control">
											<span class="input-group-addon">
												.00
											</span>
										</div>
									</div>
									<div class="form-group">
										<label class="block">Assignee</label>
										<label class="radio-inline">
											<input type="radio" name="deduction_assignee" id="deduction_no_emp" value="option1"> No assignee
										</label>
										<label class="radio-inline">
											<input type="radio" name="deduction_assignee" id="deduction_all_emp" value="option2"> All employees
										</label>
										<label class="radio-inline">
											<input type="radio" name="deduction_assignee" id="deduction_single_emp" value="option3"> Select Employee
										</label>
										<div>
											<select class="select">
												<option>-</option>
												<option>Select All</option>
												<option>John Doe</option>
												<option>Richard Miles</option>
											</select>
										</div>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Add Deduction Modal -->
				
				<!-- Edit Deduction Modal -->
				<div id="edit_deduction" class="modal custom-modal center-modal fade" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Edit Deduction</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form>
									<div class="form-group">
										<label>Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="form-group">
										<label class="block">Unit calculation</label>
										<div class="status-toggle">
											<input type="checkbox" id="edit_unit_calculation_deduction" class="check">
											<label for="edit_unit_calculation_deduction" class="checktoggle">checkbox</label>
										</div>
									</div>
									<div class="form-group">
										<label>Unit Amount</label>
										<div class="input-group">
											<span class="input-group-addon">
												$
											</span>
											<input type="text" class="form-control">
											<span class="input-group-addon">
												.00
											</span>
										</div>
									</div>
									<div class="form-group">
										<label class="block">Assignee</label>
										<label class="radio-inline">
											<input type="radio" name="edit_deduction_assignee" id="edit_deduction_no_emp" value="option1"> No assignee
										</label>
										<label class="radio-inline">
											<input type="radio" name="edit_deduction_assignee" id="edit_deduction_all_emp" value="option2"> All employees
										</label>
										<label class="radio-inline">
											<input type="radio" name="edit_deduction_assignee" id="edit_deduction_single_emp" value="option3"> Select Employee
										</label>
										<div>
											<select class="select">
												<option>-</option>
												<option>Select All</option>
												<option>John Doe</option>
												<option>Richard Miles</option>
											</select>
										</div>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Save</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Edit Addition Modal -->
				
				<!-- Delete Deduction Modal -->
				<div class="modal custom-modal center-modal fade" id="delete_deduction" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-head">
									<h3>Delete Deduction</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-xs-6">
											<a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
										</div>
										<div class="col-xs-6">
											<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Delete Deduction Modal -->
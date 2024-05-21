`				<!-- Page Content -->
                <div class="content container-fluid">
				
					<!-- Page Title -->
					<div class="row">
						<div class="col-sm-5 col-5">
							<h4 class="page-title">Policies</h4>
						</div>
						<?php
						/*if($role!=3)
						{*/
						if(($this->session->userdata('role_id') == 1) || ($this->session->userdata('role_id') == 4) || ($this->session->userdata('user_type_name') == 'company_admin')){
							echo'<div class="col-sm-7 col-7 text-right m-b-30">
							<a href="#" class="btn add-btn"  onclick="add_policies()"  ><i class="fa fa-plus"></i> Add Policy</a>
						</div>';

						}
						
						?>
						
					</div>
					<!-- /Page Title -->
					
					<div class="row">
						<div class="col-md-12">
							<table class="table table-striped custom-table m-b-0" id="policies_table">
								<thead>
									<tr>
										<th style="width: 30px;"><b>#</b></th>
										<th><b>Policy Name </b></th>
										<th>  <?=lang('branch')?></th>
										<th><b>Department</b></th>
										<th><b>Description </b></th>
										<th><b>Created </b></th>
										<th class="text-right no-sort"><b>Action</b></th>
									</tr>
								</thead>
								<tbody>
								
									
								</tbody>
							</table>
						</div>
					</div>
                </div>
				<!-- /Page Content -->
				<div id="view_policy" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-titleview">Full Policy View</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<label id="full_view"></label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Add Policy Modal -->
				<div id="add_policy" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Add Policy</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form action="#"  id="add_policies" method="post" enctype="multipart/form-data" data-parsley-validate novalidate > 
									 <input type="hidden" value="" name="id"/> 
									 <div class="form-group">
										<label><?=lang('branch')?> <span class="text-danger">*</span></label>
										<select class="form-control" style="width:100%;"  name="branch_id" id="add_branch" >
											<option value="">Select</option>
											<?php
												if(!empty($branches))	{
													foreach ($branches as $branch1){ ?>
														<option value="<?=$branch1['branch_id']?>" ><?=$branch1['branch_name']?></option>
													<?php } ?>
												<?php } ?>
										</select>
									</div>
									<div class="form-group">
										<label>Policy Name <span class="text-danger">*</span></label>
										<input class="form-control" name="policyname" id="policyname" type="text">
									</div>
									<div class="form-group">
										<label>Description <span class="text-danger">*</span></label>
										<textarea class="form-control" name="description" id="description" rows="4"></textarea>
									</div>
									<div class="form-group">
										<label class="col-form-label">Department <span class="text-danger">*</span></label>
										<select class="select2-option" multiple style="width:100%;" name="department[]" id="department" >
										<option value="00">All Department</option> 
										<?php foreach (Client::get_all_departments() as $department) { if($department->branch_id == $this->session->userdata('branch_id')){ ?>
										<option value="<?php echo $department->deptid; ?>"><?php echo $department->deptname; ?></option>
										<?php } } ?>
									   </select>
									</div>
									<div class="form-group">
										<label>Upload Policy </label>
										<div class="custom-file">
											<input type="file" class="custom-file-input" name="policy_upload" id="policy_upload">
											<input type="hidden" name="policy_files" id="policy_files">
											<label class="custom-file-label" for="policy_upload">Choose file</label>
										</div>
									</div>
									<div class="form-group">
										 <div class="upload_details"></div>
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn" id="btnSave" type="submit">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Add Policy Modal -->
				
		
				
				<!-- Delete Policy Modal -->
				<div class="modal custom-modal fade" id="delete_policy" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-header">
									<h3>Delete Policy</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-6">
											<a href="javascript:void(0);" id="delete_policies" class="btn btn-primary continue-btn">Delete</a>
										</div>
										<div class="col-6">
											<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Delete Policy Modal -->
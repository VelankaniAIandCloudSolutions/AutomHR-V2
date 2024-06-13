<?php if(!empty($historys)){?>
	<div class="content">
	<!-- Start transfer entity -->
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-white">
					<div class="panel-heading font-bold">
						<h3 class="panel-title"><?=lang('prev_details')?></h3>
					</div>
					<?php foreach($historys as $history1){?>
					<div class="panel-body">
						<div class="col-md-12">
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-lg-5 control-label"><?=lang('prev_entity')?> </label>
										<div class="col-lg-7">
											<div class="m-b">
											<?php $branches = $this->db->where('branch_id',$history1['prev_branch_id'])->get('branches')->row_array();
											echo (!empty($branches['branch_name']))?$branches['branch_name']:'';?>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-lg-5 control-label"><?=lang('prev_bus_unit')?> </label>
										<div class="col-lg-7">
											<div class="m-b">
											<?php echo $history1['business_unit'];?>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-lg-5 control-label"><?=lang('prev_division')?> </label>
											<div class="col-lg-7">
											<?php echo $history1['division'];?>
										</div>
									</div> 
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-lg-5 control-label"><?=lang('prev_report_to')?> </label>
											<div class="col-lg-7">
											<?php $users = $this->db->where('user_id',$history1['report_to'])->get('account_details')->row_array();
											echo (!empty($users['fullname']))?$users['fullname']:'';
											?>
										</div>
									</div>   
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-lg-5 control-label"><?=lang('prev_department')?> </label>
											<div class="col-lg-7">
											<?php $departments = $this->db->where('deptid',$history1['department_id'])->get('departments')->row_array();
											echo (!empty($departments['deptname']))?$departments['deptname']:'';
											?>
										</div>
									</div> 
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-lg-5 control-label"><?=lang('prev_designation')?> </label>
											<div class="col-lg-7">
											<?php $designations = $this->db->where('id',$history1['designation_id'])->get('designation')->row_array();
											echo (!empty($designations['designation']))?$designations['designation']:'';
											?>
												
										</div>
									</div>   
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="col-lg-5 control-label"><?=lang('prev_employee_id')?> </label>
											<div class="col-lg-7">
											<?php echo $history1['prev_employee_id'];?>
										</div>
									</div>   
								</div>
						</div>
					</div>
					<?php }?>
				</div>
			</div>
		</div>
	</div>
<?php }?>
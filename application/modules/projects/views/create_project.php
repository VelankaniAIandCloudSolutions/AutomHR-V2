<style>
    .select2-results__options > li:first-child{
   display: none;
}
.select2-results__message{
    display:block !important;
}
    </style>
<div class="content">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4 class="page-title">Create Project</h4>
		</div>
	</div>
	<!-- Start Project Form -->
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<?php
			$attributes = array('class' => 'bs-example','id'=>'projectAddForm');
			echo form_open(base_url().'projects/add',$attributes); ?>
			<?=$this->session->flashdata('form_error') ?>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?=lang('project_code')?> <span class="text-danger">*</span></label>
							<?php $this->load->helper('string'); ?>
							<input type="text" class="form-control" value="<?=config_item('project_prefix')?><?=random_string('nozero', 5);?>" name="project_code" readonly style="cursor: not-allowed;">
							<input type="hidden" name="created_by" id="created_by" value="<?php echo $this->session->userdata('user_id'); ?>">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?=lang('project_title')?> <span class="text-danger">*</span></label>
							<input type="text" class="form-control" placeholder="<?=lang('project_title')?>" name="project_title" value="<?=set_value('project_title')?>">
						</div>	
					</div>	
				</div>	
				<?php // if (User::is_admin() || User::perm_allowed(User::get_id(),'add_projects')) { ?>
				<div class="row">
					<!--<div class="col-md-6">
						<div class="form-group">
							<label><?=lang('branch')?> <span class="text-danger">*</span></label>
							<select class="form-control" style="width:100%;"  name="branch_id" id="add_branch" required  onchange="entity_change(this.value)">
								<option value="">Select</option>
								<?php if(!empty($branches))	{
									foreach ($branches as $branch1){ ?>
										<option value="<?=$branch1['branch_id']?>" <?php if($branch1['branch_id']==$mail['branch_id']){ echo 'selected';}?>><?=$branch1['branch_name']?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>-->
					<div class="col-md-6">
						<div class="form-group">
							<label>Client<span class="text-danger">*</span> </label>
							<div class="row">
								<div class="col-md-12">
									<select class=" form-control" name="client" id="client"> 
										<option value="" selected disabled>Choose Client</option>
										
										<?php if(!empty($companies))	{
									foreach ($companies as $companies1){ ?>
										<option value="<?=$companies1['co_id']?>" ><?=$companies1['company_name']?></option>
									<?php } ?>
								<?php } ?>
									</select> 
								</div>
								<!-- <div class="col-md-4 new-client-btn">
									<a href="<?=base_url()?>companies/create" class="btn btn-success" data-toggle="ajaxModal" title="<?=lang('new_company')?>" data-placement="bottom"><i class="fa fa-plus"></i> <?=lang('new_client')?></a>
								</div> -->
							</div>	
						</div>	
					</div>
					
					<div class="col-md-6">
						<div class="form-group">
							<label><?=lang('fixed_rate')?></label>
							<div>
								<label class="switch">
									<input type="checkbox" id="fixed_rate" name="fixed_rate">
									<span></span>
								</label>
							</div>
						</div>
					</div>
					<!-- <div class="col-md-6">
						<div class="form-group"> 
							<label><?=lang('progress')?></label>
							<div class="pro-progress"> 
								<div id="progress-slider"></div>
								<input id="progress" type="hidden" value="0" name="progress"/>
							</div>
						</div> 
					</div> --> 

					</div> 
				<div class="row">

					<div class="col-md-6">
						<?php // if (User::is_admin() || User::perm_allowed(User::get_id(),'add_projects')) { ?>
						<?php // if (User::is_admin()) { ?>
						<div class="form-group">
							<label>Lead Name </label>
							<!-- Build your select: -->
							<select class="select2-option form-control assign_users" style="width:100%;" name="assign_lead" id="assign_lead"> 
							</select>
						</div>
						<?php // } ?>
						<?php // } ?>
					</div>
				
					
					<div class="col-md-6">
						<?php // if (User::is_admin() || User::perm_allowed(User::get_id(),'add_projects')) { ?>
						<?php // if (User::is_admin()) { ?>
						<div class="form-group">
							<label><?=lang('assigned_to')?></label>
							<!-- Build your select: -->
							<select class="select2-option assign_users" multiple="multiple" style="width:100%;" name="assign_to[]" > 
								
							</select>
						</div>
						<?php // } ?>
						<?php // } ?>
					</div>
					</div> 
				<div class="row">
					
				
					<div class="col-md-6">
						<div class="form-group">
							<label><?=lang('start_date')?> </label> 
							<input class="datepicker-input form-control" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="start_date" data-date-format="<?=config_item('date_picker_format');?>" >
						</div> 
					</div> 
					</div> 
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?=lang('due_date')?></label>
							<input class="datepicker-input form-control" type="text" value="" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
						</div> 
					</div> 
					<div id="hourly_rate" class="col-md-6">
						<div class="form-group">
							<label><?=lang('hourly_rate')?>  (<?=lang('eg')?> 50.00) </label>
							<input type="text" class="form-control" name="hourly_rate" value="<?=config_item('hourly_rate')?>">
						</div>
					</div>
					</div> 
				<div class="row">
					<div id="fixed_price" class="col-md-6" style="display:none">
						<div class="form-group">
							<label><?=lang('fixed_price')?> (<?=lang('eg')?> 300 )</label>
							<input type="text" class="form-control" placeholder="300" name="fixed_price" value="<?=set_value('fixed_price')?>">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?=lang('estimated_hours')?> </label>
							<input type="text" class="form-control" placeholder="300" name="estimate_hours" value="<?=set_value('estimate_hours')?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label><?=lang('description')?> </label>
							<textarea name="description" class="form-control foeditor-project-add" placeholder="<?=lang('description')?>" value="<?=set_value('description')?>"></textarea>
							<!-- <div class="row">
							<div class="col-md-6">
							<label id="addproject_description_error" class="error display-none" style="position:inherit;top:0">Description must not empty</label>
							</div>
							</div> -->
						</div>
					</div>
				</div>
				<div class="m-t-20 text-center">
					<button id="project_add_submit" class="btn btn-primary"><i class="fa fa-plus"></i> <?=lang('create_project')?></button>
				</div>
			</form>
		</div>
	</div>
	<!-- End Project Form -->
</div>
<script>
	function entity_change(a){
		$.ajax( {
			url:'<?php echo base_url('projects/get_companies/');?>'+a,
			success:function(data) {
				var reqdata = JSON.parse(data);
				$('#client').empty();
				$('#client').append(reqdata.companies);
				

				$('#assign_lead').empty();
				$('#assign_lead').append('<option value="">Select</option>'+reqdata.users);
				

				$('#assign_to').empty();
				$('#assign_to').append(reqdata.users);
				$('#client').refresh();
				$('#assign_lead').refresh();
				$('#assign_to').refresh();
				
			}
		});
	}
</script>	
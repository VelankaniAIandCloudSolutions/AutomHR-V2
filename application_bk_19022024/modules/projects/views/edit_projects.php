				<!-- Page Content -->
                <div class="content container-fluid">
<!-- Start create project -->
<div class="row">
<div class="col-sm-12">
	<section class="panel panel-white">
		<header class="panel-heading">
			<h3 class="panel-title"><?=lang('edit_project')?></h3>
		</header>
		<div class="panel-body">

			<?php if (User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_projects')){

				$project = Project::by_id($project_id);
						$attributes = array('class' => 'bs-example form-horizontal','id' => 'projectEditForm');
						echo form_open(base_url().'projects/edit',$attributes); ?>
						<?php echo validation_errors('<span style="color:red">', '</span><br>'); ?>
						<input type="hidden" name="project_id" value="<?=$project->project_id?>">

						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('status')?> </label>
							<div class="col-lg-5">
								<select class="form-control" name="status">
									<option value="Active"<?=($project->status == 'Active' ? ' selected="selected"':'')?>><?=lang('active')?></option>
									<option value="On Hold"<?=($project->status == 'On Hold' ? ' selected="selected"':'')?>><?=lang('on_hold')?></option>
									<option value="Done"<?=($project->status == 'Done' ? ' selected="selected"':'')?>><?=lang('done')?></option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('project_code')?> <span class="text-danger">*</span></label>
							<div class="col-lg-5">
								<input type="text" class="form-control" value="<?=$project->project_code?>" name="project_code" readonly>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('project_title')?> <span class="text-danger">*</span></label>
							<div class="col-lg-5">
								<input type="text" class="form-control" value="<?=$project->project_title?>" name="project_title">
							</div>
						</div>	
						
						<!--<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('branch')?> <span class="text-danger">*</span></label>
							<div class="col-lg-5">
								<select class="form-control" style="width:100%;"  name="branch_id" id="add_branch" required  onchange="entity_change(this.value)">
									<option value="">Select</option>
									<?php if(!empty($branches))	{
										foreach ($branches as $branch1){ ?>
											<option value="<?=$branch1['branch_id']?>" <?php if($branch1['branch_id']==$project->branch_id){ echo 'selected';}?>><?=$branch1['branch_name']?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>-->

						<div class="form-group">
							<label class="col-lg-3 control-label">Company<span class="text-danger">*</span> </label>
							<div class="col-lg-5">
								<div class="m-b"> 
									<select  style="width:100%;" class="form-control" name="client" id="client">
											<option value="" disabled >Choose Company</option>
										<?php 
											if(!empty($companies)){
										foreach ($companies as $key => $c) { 
											?>
											<option value="<?=$c['co_id']?>"  <?php if($c['co_id']==$project->client){ echo 'selected';}?> ><?=ucfirst($c['company_name'])?></option>
											<?php  } 
											}
											?>
										</select> 
									</div> 
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-3 control-label"><?=lang('start_date')?> <span class="text-danger">*</span></label> 
								<div class="col-lg-5">
									<input class="datepicker-input form-control" readonly type="text" value="<?=strftime(config_item('date_format'), strtotime($project->start_date));?>" name="start_date" data-date-format="<?=config_item('date_picker_format');?>" >
								</div> 
							</div> 
							<div class="form-group">
								<label class="col-lg-3 control-label"><?=lang('due_date')?> <span class="text-danger">*</span></label> 
								<div class="col-lg-5">
									<input class="datepicker-input form-control" readonly type="text" value="<?php if(valid_date($project->due_date)){ echo strftime(config_item('date_format'), strtotime($project->due_date)); } ?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
								</div> 
							</div> 
							<div class="form-group"> 
								<label class="col-lg-3 control-label"><?=lang('progress')?></label>
								<div class="col-lg-5"> 
									<div id="progress-slider"></div>
									<input id="progress" type="hidden" value="<?=$project->progress?>" name="progress"/>
								</div>
							</div> 

							<div class="form-group">
								<label class="col-lg-3 control-label">Lead Name <span class="text-danger">*</span></label>
								<div class="col-lg-5">

									<select class="select2-option1 form-control"   style="width:260px" name="assign_lead" id="assign_lead"> 
										<option value="<?= $project->assign_lead ?>" selected><?= User::displayName($project->assign_lead) ?></option>
										
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-3 control-label"><?=lang('assigned_to')?> <span class="text-danger">*</span></label>
								<div class="col-lg-5">

									<select class="select2-option form-control" multiple="multiple" style="width:260px" name="assign_to[]" id="assign_to"> 
										<optgroup label="Staff">
										<?php 
										 $assigns = unserialize($project->assign_to);
											if(!empty($users)){
											foreach ($users as $user1){
												
												?>
												<option value="<?=$user1['user_id'];?>" <?php if (in_array($user1['user_id'], $assigns)){ echo 'selected';}?>>
													<?=$user1['fullname'];?>
												</option>
											<?php  }
											}
											?>
										</optgroup> 
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-3 control-label"><?=lang('fixed_rate')?></label>
								<div class="col-lg-5">
									<label class="switch">
										<input type="checkbox" <?php if($project->fixed_rate == 'Yes'){ echo "checked=\"checked\""; } ?> name="fixed_rate" id="fixed_rate" >
										<span></span>
									</label>
								</div>
							</div>


							<div id="hourly_rate" <?php if($project->fixed_rate == 'Yes'){ echo "style=\"display:none\""; }?>>
								<div class="form-group">
									<label class="col-lg-3 control-label"><?=lang('hourly_rate')?>  (<?=lang('eg')?> 50 )<span class="text-danger">*</span></label>
									<div class="col-lg-5">
										<input type="text" class="form-control money" value="<?=$project->hourly_rate?>" name="hourly_rate">
									</div>
								</div>
							</div>
							<div id="fixed_price" <?php if($project->fixed_rate == 'No'){ echo "style=\"display:none\""; }?>>
								<div class="form-group">
									<label class="col-lg-3 control-label"><?=lang('fixed_price')?> (<?=lang('eg')?> 300 )<span class="text-danger">*</span></label>
									<div class="col-lg-5">
										<input type="text" class="form-control" value="<?=$project->fixed_price?>" name="fixed_price">
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-3 control-label"><?=lang('estimated_hours')?> <span class="text-danger">*</span></label>
								<div class="col-lg-5">
									<input type="text" class="form-control" value="<?=$project->estimate_hours?>" name="estimate_hours">
								</div>
							</div>	

							<div class="form-group">
								<label class="col-lg-3 control-label"><?=lang('description')?> <span class="text-danger">*</span></label>
								<div class="col-lg-9">
									<textarea name="description" class="form-control foeditor-project-edit" placeholder="<?=lang('about_the_project')?>" required><?=$project->description?></textarea>
									<div class="row">
									<div class="col-md-6">
									<label id="project_description_error" class="error display-none" style="position:inherit;top:0">Description must not empty</label>
									</div>
									</div>
								</div>
							</div>
							<div class="submit-section">
								<button id="project_edit_dashboard" class="btn btn-primary submit-btn"><?=lang('save_changes')?></button>
							</div>
						</form>
						<?php } ?>
					</div>
				</section>
			</div>
			</div>
			</div>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
			<script>
				$(document).ready(function () {
					$(".select2-option1").select2({
						minimumInputLength: 3,
						tags: [],
						ajax: {
							url: "<?php echo base_url('projects/getEmployees');?>",
							dataType: 'json',
							type: "GET",
							quietMillis: 2,
							data: function (term) {
								return {
									term: term
								};
							},
							processResults: function (data) {
								return {
									results: $.map(data, function (item) {
									return {
											text: item.fullname+' ('+item.email+')',
											slug: item.email,
											id: item.id
										}
									})
								};
							}
						}
					});
				});
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
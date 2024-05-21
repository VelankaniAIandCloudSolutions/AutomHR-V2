<!-- Start create project -->
<div class="row">
<div class="col-sm-12">
	<section class="panel panel-white">
		<header class="panel-heading">
			<h3 class="panel-title"><?=lang('project_details')?></h3>
		</header>
		<div class="panel-body">

			<?php if (User::is_admin() || User::perm_allowed(User::get_id(),'edit_all_projects')){

				$project = Project::by_id($project_id);
						$attributes = array('class' => 'bs-example form-horizontal');
						echo form_open(base_url().'projects/edit',$attributes); ?>
						<?php echo validation_errors('<span style="color:red">', '</span><br>'); ?>
						<input type="hidden" name="project_id" value="<?=$project->project_id?>">

						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('status')?> </label>
							<div class="col-lg-3">
								<select class="form-control" name="status">
									<option value="Active"<?=($project->status == 'Active' ? ' selected="selected"':'')?>><?=lang('active')?></option>
									<option value="On Hold"<?=($project->status == 'On Hold' ? ' selected="selected"':'')?>><?=lang('on_hold')?></option>
									<option value="Done"<?=($project->status == 'Done' ? ' selected="selected"':'')?>><?=lang('done')?></option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('project_code')?> <span class="text-danger">*</span></label>
							<div class="col-lg-3">
								<input type="text" class="form-control" value="<?=$project->project_code?>" name="project_code" readonly>
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('project_title')?> <span class="text-danger">*</span></label>
							<div class="col-lg-5">
								<input type="text" class="form-control" value="<?=$project->project_title?>" name="project_title">
							</div>
						</div>		

						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('client')?> <span class="text-danger">*</span> </label>
							<div class="col-lg-3">
								<div class="m-b"> 
									<select  style="width:100%;" class="form-control" name="client" required>
									<?php if($project->client > 0) { ?>
						<option value="<?=$project->client?>">
						<?=ucfirst(Client::view_by_id($project->client)->company_name)?>
						</option>
									<?php } ?>
										<?php foreach (Client::get_all_clients() as $key => $c) { ?>
											<option value="<?=$c->co_id?>"><?=ucfirst($c->company_name)?></option>
											<?php } ?>
										</select> 
									</div> 
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-3 control-label"><?=lang('start_date')?></label> 
								<div class="col-lg-3">
									<input class="datepicker-input form-control" type="text" value="<?=strftime(config_item('date_format'), strtotime($project->start_date));?>" name="start_date" data-date-format="<?=config_item('date_picker_format');?>" >
								</div> 
							</div> 
							<div class="form-group">
								<label class="col-lg-3 control-label"><?=lang('due_date')?></label> 
								<div class="col-lg-3">
									<input class="datepicker-input form-control" type="text" value="<?php if(valid_date($project->due_date)){ echo strftime(config_item('date_format'), strtotime($project->due_date)); } ?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
								</div> 
							</div> 
							<div class="form-group"> 
								<label class="col-lg-3 control-label"><?=lang('progress')?></label>
								<div class="col-lg-8 progress-box"> 
									<div id="progress-slider"></div>
									<input id="progress" type="hidden" value="<?=$project->progress?>" name="progress"/>
								</div>
							</div> 

							<div class="form-group">
								<label class="col-lg-3 control-label">Lead Name <span class="text-danger">*</span></label>
								<div class="col-lg-3">

									<select class="select2-option form-control"   style="width:260px" name="assign_lead" > 
										<optgroup label="Staff">
											<?php foreach (User::team() as $user): ?>
												<option value="<?=$user->id?>"  <?php 
													if ($user->id == $project->assign_lead) { ?> selected = "selected" <?php }   ?>>
													<?=ucfirst(User::displayName($user->id))?>
												</option>
											<?php endforeach ?>
										</optgroup> 
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-3 control-label"><?=lang('assigned_to')?> <span class="text-danger">*</span></label>
								<div class="col-lg-3">

									<select class="select2-option form-control" multiple="multiple" style="width:260px" name="assign_to[]" > 
										<optgroup label="Staff">
											<?php foreach (User::team() as $user): ?>
												<option value="<?=$user->id?>" <?php foreach (Project::project_team($project->project_id) as $value) {
													if ($user->id == $value->assigned_user) { ?> selected = "selected" <?php } } ?>>
													<?=ucfirst(User::displayName($user->id))?>
												</option>
											<?php endforeach ?>
										</optgroup> 
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-3 control-label"><?=lang('fixed_rate')?></label>
								<div class="col-lg-8">
									<label class="switch">
										<input type="checkbox" <?php if($project->fixed_rate == 'Yes'){ echo "checked=\"checked\""; } ?> name="fixed_rate" id="fixed_rate" >
										<span></span>
									</label>
								</div>
							</div>


							<div id="hourly_rate" <?php if($project->fixed_rate == 'Yes'){ echo "style=\"display:none\""; }?>>
								<div class="form-group">
									<label class="col-lg-3 control-label"><?=lang('hourly_rate')?>  (<?=lang('eg')?> 50 )</label>
									<div class="col-lg-3">
										<input type="text" class="form-control money" value="<?=$project->hourly_rate?>" name="hourly_rate">
									</div>
								</div>
							</div>
							<div id="fixed_price" <?php if($project->fixed_rate == 'No'){ echo "style=\"display:none\""; }?>>
								<div class="form-group">
									<label class="col-lg-3 control-label"><?=lang('fixed_price')?> (<?=lang('eg')?> 300 )</label>
									<div class="col-lg-3">
										<input type="text" class="form-control" value="<?=$project->fixed_price?>" name="fixed_price">
									</div>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-3 control-label"><?=lang('estimated_hours')?></label>
								<div class="col-lg-3">
									<input type="text" class="form-control" value="<?=$project->estimate_hours?>" name="estimate_hours">
								</div>
							</div>	

							<div class="form-group">
								<label class="col-lg-3 control-label"><?=lang('description')?> <span class="text-danger">*</span></label>
								<div class="col-lg-9">
									<textarea name="description" class="form-control foeditor" placeholder="<?=lang('about_the_project')?>" required><?=$project->description?></textarea>
								</div>
							</div>
							<div class="m-t-20 text-center">
								<button type="submit" class="btn btn-primary"><?=lang('save_changes')?></button>
							</div>
						</form>
						<?php } ?>
					</div>
				</section>
			</div>
			</div>
<!-- End create project -->
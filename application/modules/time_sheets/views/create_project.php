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
			$attributes = array('class' => 'bs-example');
			echo form_open(base_url().'projects/add',$attributes); ?>
			<?=$this->session->flashdata('form_error') ?>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?=lang('project_code')?> <span class="text-danger">*</span></label>
							<?php $this->load->helper('string'); ?>
							<input type="text" class="form-control" value="<?=config_item('project_prefix')?><?=random_string('nozero', 5);?>" name="project_code">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?=lang('project_title')?> <span class="text-danger">*</span></label>
							<input type="text" class="form-control" placeholder="<?=lang('project_title')?>" name="project_title" value="<?=set_value('project_title')?>" required>
						</div>	
					</div>	
				</div>	
				<?php if (User::is_admin() || User::perm_allowed(User::get_id(),'add_projects')) { ?>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?=lang('client')?> <span class="text-danger">*</span> </label>
							<div class="row">
								<div class="col-md-8">
									<select class="select2-option form-control" name="client"  required> 
										<?php foreach (Client::get_all_clients() as $key => $c) { ?>
										<option value="<?=$c->co_id?>"><?=ucfirst($c->company_name)?></option>
										<?php }?>
									</select> 
								</div>
								<div class="col-md-4 new-client-btn">
									<a href="<?=base_url()?>companies/create" class="btn btn-success" data-toggle="ajaxModal" title="<?=lang('new_company')?>" data-placement="bottom"><i class="fa fa-plus"></i> <?=lang('new_client')?></a>
								</div>
							</div>	
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group"> 
							<label><?=lang('progress')?></label>
							<div class="pro-progress"> 
								<div id="progress-slider"></div>
								<input id="progress" type="hidden" value="0" name="progress"/>
							</div>
						</div> 
					</div> 
				</div> 
				<?php } ?>
				<div class="row">
					<div class="col-md-6">
						<?php if (User::is_admin() || User::perm_allowed(User::get_id(),'add_projects')) { ?>
						<?php if (User::is_admin()) { ?>
						<div class="form-group">
							<label>Lead Name <span class="text-danger">*</span></label>
							<!-- Build your select: -->
							<select class="select2-option form-control" required style="width:100%;" name="assign_lead" > 
								<optgroup label="<?=lang('admin_staff')?>"> 
								<?php foreach (User::team() as $key => $user) { ?>
								<option value="<?=$user->id?>"><?=ucfirst(User::displayName($user->id))?></option>
								<?php } ?>	
								</optgroup> 
							</select>
						</div>
						<?php } ?>
						<?php } ?>
					</div>
					<div class="col-md-6">
						<?php if (User::is_admin() || User::perm_allowed(User::get_id(),'add_projects')) { ?>
						<?php if (User::is_admin()) { ?>
						<div class="form-group">
							<label><?=lang('assigned_to')?> <span class="text-danger">*</span></label>
							<!-- Build your select: -->
							<select class="select2-option form-control" multiple="multiple" required style="width:100%;" name="assign_to[]" > 
								<optgroup label="<?=lang('admin_staff')?>"> 
								<?php foreach (User::team() as $key => $user) { ?>
								<option value="<?=$user->id?>"><?=ucfirst(User::displayName($user->id))?></option>
								<?php } ?>	
								</optgroup> 
							</select>
						</div>
						<?php } ?>
						<?php } ?>
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
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><?=lang('start_date')?> <span class="text-danger">*</span></label> 
							<input class="datepicker-input form-control" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="start_date" data-date-format="<?=config_item('date_picker_format');?>" >
						</div> 
					</div> 
					<div class="col-md-6">
						<div class="form-group">
							<label><?=lang('due_date')?></label>
							<input class="datepicker-input form-control" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
						</div> 
					</div> 
				</div>
				<div class="row">
					<div id="hourly_rate" class="col-md-6">
						<div class="form-group">
							<label><?=lang('hourly_rate')?>  (<?=lang('eg')?> 50.00)</label>
							<input type="text" class="form-control" name="hourly_rate" value="<?=config_item('hourly_rate')?>">
						</div>
					</div>
					<div id="fixed_price" class="col-md-6" style="display:none">
						<div class="form-group">
							<label><?=lang('fixed_price')?> (<?=lang('eg')?> 300 )</label>
							<input type="text" class="form-control" placeholder="300" name="fixed_price" value="<?=set_value('fixed_price')?>">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><?=lang('estimated_hours')?></label>
							<input type="text" class="form-control" placeholder="300" name="estimate_hours" value="<?=set_value('estimate_hours')?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label><?=lang('description')?> <span class="text-danger">*</span></label>
							<textarea name="description" class="form-control foeditor-100" placeholder="<?=lang('description')?>" required value="<?=set_value('description')?>"></textarea>
						</div>
					</div>
				</div>
				<div class="m-t-20 text-center">
					<button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> <?=lang('create_project')?></button>
				</div>
			</form>
		</div>
	</div>
	<!-- End Project Form -->
</div>
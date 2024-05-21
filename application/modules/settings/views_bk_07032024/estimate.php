<div class="p-0">
	<!-- Start Form -->
	<div class="col-lg-12 p-0">
		<?php
		$attributes = array('class' => 'bs-example form-horizontal','id'=>'settingsEstimatesForm');
		echo form_open_multipart('settings/update', $attributes); ?>
			<div class="card panel-white">
				<div class="card-header">
					<h3 class="card-title p-5"><?=lang('estimate_settings')?></h3>
				</div>
				<div class="card-body">
					<input type="hidden" name="settings" value="<?=$load_setting?>">
					<div class="form-group form-row">
						<label class="col-lg-3 control-label"><?=lang('estimate_color')?> <span class="text-danger">*</span></label>
						<div class="col-lg-5">
							<input type="text" id="estimate_color" readonly name="estimate_color" class="form-control" value="<?=config_item('estimate_color')?>" required>
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-lg-3 control-label"><?=lang('estimate_prefix')?> <span class="text-danger">*</span></label>
						<div class="col-lg-5">
							<input type="text" name="estimate_prefix" class="form-control" value="<?=config_item('estimate_prefix')?>" required>
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-lg-3 control-label"><?=lang('estimate_start_number')?> <span class="text-danger">*</span></label>
						<div class="col-lg-5">
							<input type="text" name="estimate_start_no" class="form-control" value="<?=config_item('estimate_start_no')?>">
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-lg-3 control-label"><?=lang('display_estimate_badge')?></label>
						<div class="col-lg-5">
							<label class="switch">
								<input type="hidden" value="off" name="display_estimate_badge" />
								<input type="checkbox" <?php if(config_item('display_estimate_badge') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="display_estimate_badge">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-lg-3 control-label"><?=lang('show_item_tax')?></label>
						<div class="col-lg-5">
							<label class="switch">
								<input type="hidden" value="off" name="show_estimate_tax" />
								<input type="checkbox" <?php if(config_item('show_estimate_tax') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="show_estimate_tax">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-lg-3 control-label"><?=lang('estimate_to_project')?></label>
						<div class="col-lg-5">
							<label class="switch">
								<input type="hidden" value="off" name="estimate_to_project" />
								<input type="checkbox" <?php if(config_item('estimate_to_project') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="estimate_to_project">
								<span></span>
							</label>
						</div>
						<div class="col-lg-6">
							<span class="help-block m-b-0">Convert accepted estimate to project</span>
						</div>
					</div>
					<div class="form-group terms form-row">
						<label class="col-lg-3 control-label"><?=lang('estimate_footer')?></label>
						<div class="col-lg-9">
							<textarea class="form-control foeditor" name="estimate_footer"><?=config_item('estimate_footer')?></textarea>
						</div>
					</div>
					<div class="form-group terms form-row">
						<label class="col-lg-3 control-label"><?=lang('estimate_terms')?></label>
						<div class="col-lg-9">
							<textarea class="form-control foeditor" name="estimate_terms"><?=config_item('estimate_terms')?></textarea>
						</div>
					</div>
					<div class="text-center m-t-30">
                        <button id="settings_estimates_submit" class="btn btn-primary btn-lg"><?=lang('save_changes')?></button>
					</div>
				</div>
			</div>
		</form>
	</div>
	<!-- End Form -->
</div>
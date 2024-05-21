<div class="row">
    <!-- Start Form -->
	<div class="col-lg-12">
		<div class="panel panel-white">
			<div class="panel-heading">
				<h3 class="panel-title"><?=lang('client')?> <?=lang('project_settings')?></h3>
			</div>
			<div class="panel-body">
				<?php
				$attributes = array('class' => 'bs-example form-horizontal','data-validate'=>'parsley');
				echo form_open('settings/project_settings', $attributes); ?>
				<?php echo validation_errors(); $this->load->helper('inflector'); ?>
					<?php $json = json_decode(config_item('default_project_settings')); 
					foreach ($json as $key => $value) { ?>
					<div class="form-group">
						<label class="col-lg-5 control-label"><?=humanize($key);?></label>
						<div class="col-lg-7">
							<label class="switch">
								<input type="hidden" value="off" name="<?=$key?>" />
								<input type="checkbox" <?php if($value == 'on'){ echo "checked=\"checked\""; } ?> name="<?=$key?>">
								<span></span>
							</label>
						</div>
					</div>
					<?php } ?>
					<div class="text-center m-t-30">
						<button type="submit" class="btn btn-primary btn-lg"><?=lang('save_changes')?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
    <!-- End Form -->
</div>
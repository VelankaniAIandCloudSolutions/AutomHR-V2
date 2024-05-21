<h3 class="page-title">Salary Settings</h3>
<form action="<?php echo base_url(); ?>settings/update" id="HaDraForm" class="bs-example" enctype="multipart/form-data" method="post" accept-charset="utf-8">
	<?php 
		$settingsalray = array();
		if(!empty($salary_setting)){
			foreach ($salary_setting as  $value) {
				$settingsalray[$value->config_key] = $value->value;
			}
		}

	 ?>
	<div class="settings-widget">
		<input type="hidden" name="settings" value="setting_salary">
		<div class="h3 card-title with-switch">
			DA and HRA 											
			<div class="onoffswitch">
				<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="switch_hra" checked>
				<label class="onoffswitch-label" for="switch_hra">
					<span class="onoffswitch-inner"></span>
					<span class="onoffswitch-switch"></span>
				</label>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label>DA (%) <span class="text-danger">*</span></label>
					<input type="number" min="0" max="55" name="salary_da"  class="form-control" value="<?php echo (!empty($settingsalray['salary_da']))?$settingsalray['salary_da']:''; ?>" required="" maxlength="2">
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label>HRA (%) <span class="text-danger">*</span></label>
					<input type="number" min="0" max="25" name="salary_hra" class="form-control" value="<?php echo (!empty($settingsalray['salary_hra']))?$settingsalray['salary_hra']:''; ?>" required="" maxlength="2">
				</div>
			</div>
		</div>
	</div>

	
	
	<div class="submit-section">
		<button id="ha_dra_submit" class="btn btn-primary submit-btn">Save Changes</button>
	</div>
</form>



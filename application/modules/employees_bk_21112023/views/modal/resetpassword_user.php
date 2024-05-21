<?php $company_ref = config_item('company_id_prefix').$this->applib->generate_string();
while($this->db->where('company_ref', $company_ref)->get('companies')->num_rows() == 1) {
$company_ref = config_item('company_id_prefix').$this->applib->generate_string();
} ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">
				Reset password
			</h4>
        </div>
		<?php $attributes = array('id' => 'reset_newpassword_form'); echo form_open(base_url().'employees/reset_password/'.$user_id, $attributes); ?>
			<div class="modal-body">
				<div class="tab-content">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>New Password</label>
											<input type="password" class="form-control" name="new_password" id="reset_newpassword">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Confirm Password</label>
											<input type="password" class="form-control" name="new_confirm_password" id="reset_confirmpassword">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="submit-section text-right">
							<button class="btn btn-primary submit-btn" id="ResetNewPassword">Save & Continue</button>
						</div>
					
				</div>
				
			</div>
		</form>
	</div>
</div>
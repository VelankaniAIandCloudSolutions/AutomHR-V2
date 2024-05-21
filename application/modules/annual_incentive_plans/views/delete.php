<div class="modal-dialog  modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-body text-center">
			<?php echo form_open(base_url().'annual_incentive_plans/delete'); ?>
				<div class="form-head">
					<h3><?=lang('delete_plan')?></h3>
					<p>Are you sure want to delete?</p>
				</div>
				<p class="m-b-20"><?=lang('delete_plan_warning')?></p>				 
				<input type="hidden" name="plan" value="<?php echo isset($id)?$id:''; ?>">
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-6">
							<button type="submit" class="btn continue-btn">Delete</button>
						</div>
						<div class="col-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn continue-btn">Cancel</a>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
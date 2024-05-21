<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-body">
			<?php echo form_open(base_url().'shift_scheduling/delete_shift'); ?>
				<div class="form-head">
					<h3><?=lang('delete_shift')?></h3>
					<p>Are you sure want to delete?</p>
				</div>
				<p class="text-center mb-4"><?=lang('delete_user_warning')?></p>
				
				<input type="hidden" name="id" value="<?=$id?>">
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-6">
							<button type="submit" class="btn continue-btn">Delete</button>
						</div>
						<div class="col-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn cancel-btn">Cancel</a>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
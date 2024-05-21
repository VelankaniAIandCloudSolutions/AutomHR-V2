<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-body text-center">
			<div class="form-header">
				<h3><?=lang('delete_ticket')?></h3>
				<p><?=lang('delete_ticket_warning')?></p>
			</div>
			
			<div class="modal-btn delete-action">
				<?php echo form_open(base_url().'tickets/delete'); ?>
				<input type="hidden" name="ticket" value="<?=$ticket?>">
					<div class="row">
						<div class="col-6">
							<button type="submit" class="btn btn-primary continue-btn"><?=lang('delete_button')?></button>
						</div>
						<div class="col-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Delete Job Modal -->
<div class="modal custom-modal fade" id="delete_job" role="dialog">
	<div class="modal-dialog  modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<div class="form-header">
					<h3><?php echo lang('delete_job');?></h3>
					<p><?php echo lang('are_you_sure_want_to_delete');?></p>
				</div>
				<div class="modal-btn delete-action">
					<form action="<?php echo site_url('candidates/delete_job');?>" method="post">
					<div class="row">
						<input type="hidden" name="page_name" id="page_name" value="">
						<input type="hidden" name="delete_id" id="delete_id" value="">
						<div class="col-sm-6">
							<button type="submit" name="submit" value="delete" class="btn btn-primary continue-btn"><?php echo lang('delete');?></button>
						</div>
						<div class="col-sm-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn"><?php echo lang('cancel')?></a>
						</div>
					</div>
				</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Delete Job Modal -->
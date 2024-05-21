

<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		
		<?php echo form_open(base_url().'jobs/delete'); ?>
			<div class="modal-body">
				<div class="form-header">
					<h3><?php echo lang('delete')?></h3>
					<p><?php echo lang('are_you_sure_want_to_delete');?></p>
				</div>
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-sm-6">
							<input type="hidden" name="jobid" id="jobid" value="<?php echo $job_id;?>">
							<button type="submit" class="btn btn-primary continue-btn"><?php echo lang('delete');?></a>
						</div>
						<div class="col-sm-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn"><?php echo lang('cancel');?></a>
						</div>
					</div>
				</div>
			</div>
			</div>
		</form>
	</div>
</div>

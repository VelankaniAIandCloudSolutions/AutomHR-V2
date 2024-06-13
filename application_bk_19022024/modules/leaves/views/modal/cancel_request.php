<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-danger">
			<button type="button" class="close" data-dismiss="modal">&times;</button> 
			<h4 class="modal-title"> <?=lang('cancel_leave');?> </h4>
		</div>
		<?php echo form_open(base_url().'leaves/compen_cancel'); ?>
			<div class="modal-body">
				<p> Are you sure want cancel this leave Request ?</p>
				<input type="hidden" name="req_leave_tbl_id" value="<?=$req_leave_tbl_id?>"> 
				<input type="text" name="reason" id="reason" placeholder="Reason" class="form-control" required>
			</div>
			<div class="modal-footer"> 
				<button type="submit" class="btn btn-danger"> <?=lang('cancel');?> </button>
				<a href="#" class="btn btn-default" data-dismiss="modal"> <?=lang('close');?> </a>
			</div>
 		</form>
	</div>
</div>
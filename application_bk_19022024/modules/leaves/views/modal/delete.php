<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-danger">
			<button type="button" class="close" data-dismiss="modal">&times;</button> 
			<h4 class="modal-title"> Delete Leave </h4>
		</div>
		<?php echo form_open(base_url().'leaves/delete'); ?>
			<div class="modal-body">
				<p>Are you sure want delete this leave Request ?</p>
				<input type="hidden" name="req_leave_tbl_id" value="<?=$req_leave_tbl_id?>"> 
				<input type="text" name="reason" id="reason" placeholder="Reason" class="form-control" required>
			</div>
			<div class="modal-footer"> 
				<button type="submit" class="btn btn-danger"> Delete  </button>
				<a href="#" class="btn btn-default" data-dismiss="modal"> Close </a>
			</div>
 		</form>
	</div>
</div>
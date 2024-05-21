<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-success">
			<button type="button" class="close" data-dismiss="modal">&times;</button> 
			<h4 class="modal-title"> Approve Leave </h4>
		</div>
		<?php echo form_open(base_url().'leaves/approve'); ?>
			<div class="modal-body">
				<p> Are you sure want approve this leave Request ?</p>
				<input type="hidden" name="req_leave_tbl_id" value="<?=$req_leave_tbl_id?>"> 
			</div>
			<div class="modal-footer"> 
				 <button type="submit" class="btn btn-success"> Approve </button>
				 <a href="#" class="btn btn-danger" data-dismiss="modal"> Close </a>
			</div>
 		</form>
	</div>
</div>
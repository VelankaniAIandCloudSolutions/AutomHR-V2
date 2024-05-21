<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-danger">
			<h4 class="modal-title">Change Status</h4>
			<button type="button" class="close" data-dismiss="modal">&times;</button> 
		</div>
		<?php echo form_open(base_url().'forms/changeCategoryStatus/'.$category_id); ?>
			<div class="modal-body">
				<p>Are you sure want to change status?</p>
				<input type="hidden" name="category_id" value="<?=$category_id?>"> 
			</div>
			<div class="modal-footer"> 
				<button type="submit" class="btn btn-danger">Yes</button>
				<a href="#" class="btn btn-default" data-dismiss="modal">No</a>
			</div>
		</form>
	</div>
</div>
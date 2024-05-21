<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-danger">
			<h4 class="modal-title">Delete Category</h4>
			<button type="button" class="close" data-dismiss="modal">&times;</button> 
		</div>
		<?php echo form_open(base_url().'forms/delete_category/'.$category_id); ?>
			<div class="modal-body">
				<p>Are you sure want to delete this category?</p>
				<input type="hidden" name="category_id" value="<?=$category_id?>"> 
			</div>
			<div class="modal-footer"> 
				<button type="submit" class="btn btn-danger">Delete</button>
				<a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			</div>
		</form>
	</div>
</div>
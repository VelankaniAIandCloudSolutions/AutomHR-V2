<div class="modal-dialog">
	<div class="modal-content">
		
		<?php echo form_open(base_url().'file_manager/delete_folder/'.$folder_id); ?>
			<div class="modal-body">
				<div class="form-head">
				<h4 class="modal-title">Delete Folder</h4>
				<p>Are you sure want to delete this Folder? if you delete the folder, All files will be deleted in this folder.</p>
				<input type="hidden" name="folder_id" value="<?=$folder_id?>"> 
			</div>
			
			<div class="modal-btn delete-action"> 
				<div class="row">
						<div class="col-6">
				<button type="submit" class="btn btn-danger" style="margin-left: 200px;">Delete</button>
			</div>
			<div class="col-6">
				<a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			</div>
			</div>
			</div>
		</div>
		</form>
	</div>
</div>
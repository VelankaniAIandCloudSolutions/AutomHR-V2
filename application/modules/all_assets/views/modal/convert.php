<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-danger"> <button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('convert')?> - <?=Lead::find($id)->company_name?></h4>
		</div>
		<?php echo form_open(base_url().'leads/convert'); ?>
			<div class="modal-body">
				<p>A new client <strong><?=Lead::find($id)->company_name?></strong> will be created.</p>

				<input type="hidden" name="id" value="<?=$id?>">

			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-danger"><?=lang('convert')?></button>
			</div>
		</form>
	</div>
</div>
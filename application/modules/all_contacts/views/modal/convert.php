<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('convert')?> - <?=Lead::find($id)->company_name?></h4>
		</div>
		<?php echo form_open(base_url().'leads/convert'); ?>
			<div class="modal-body">
				<p>A new client <strong><?=Lead::find($id)->company_name?></strong> will be created.</p>

				<input type="hidden" name="id" value="<?=$id?>">
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary continue-btn"><?=lang('close')?></a>
						</div>
						<div class="col-6">
							<button type="submit" class="btn btn-primary cancel-btn"><?=lang('convert')?></button>
						</div>
					</div>
				</div>
			</div>
			<!--<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-danger"><?=lang('convert')?></button>
			</div>-->
		</form>
	</div>
</div>
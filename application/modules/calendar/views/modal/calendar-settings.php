<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button> 
			<h4 class="modal-title"><?=lang('calendar_settings')?></h4>
		</div>
		<?php $attributes = array('class' => 'bs-example'); echo form_open(base_url().'calendar/settings/save',$attributes); ?>
			<div class="modal-body">
				<div class="form-group">
					<label>Google Calendar API</label>
					<input type="text" class="form-control" value="<?=config_item('gcal_api_key')?>" name="gcal_api_key">
				</div>
				<div class="form-group">
					<label>Calendar ID</label>
					<input type="text" class="form-control" value="<?=config_item('gcal_id')?>" name="gcal_id">
				</div>
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-success"><?=lang('save')?></button>
			</div>
		</form>
	</div>
</div>
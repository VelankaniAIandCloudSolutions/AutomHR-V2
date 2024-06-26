<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('add_task')?></h4>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id'=>'itemsAddTask'); echo form_open(base_url().'items/save_task',$attributes); ?>
			<div class="modal-body">
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('task_name')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control" placeholder="<?=lang('task_name')?>" name="task_name" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('visible_to_client')?></label>
					<div class="col-lg-8">
						<div class="checkbox">
							<label class="checkbox-custom">
								<input name="visible" checked="checked" type="checkbox"> <?=lang('yes')?>
							</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('estimated_hours')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control" placeholder="50" name="estimate">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('description')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<textarea name="description" class="form-control ta" placeholder="<?=lang('description')?>"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-success" id="items_add_task"><?=lang('save_as_template')?></button>
			</div>
		</form>
	</div>
</div>
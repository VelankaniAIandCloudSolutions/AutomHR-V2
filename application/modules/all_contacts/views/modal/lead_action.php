<?php $action = (isset($action)) ? $action : ''; ?>
<?php if($action == 'add_todo'){ ?>
<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('create_list')?></h4>
		</div>
		<?php $attributes = array('class' => 'bs-example');  echo form_open(base_url().'leads/todo/add',$attributes); ?>
			<input type="hidden" name="lead_id" value="<?=$lead_id?>">
			<div class="modal-body">
				<div class="form-group">
					<label><?=lang('todo_item')?> <span class="text-danger">*</span></label>
					<textarea class="form-control ta" name="todo_item" required></textarea>
				</div>
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-success"><?=lang('save_changes')?></button>
			</div>
		</form>
	</div>
</div>
<?php } ?>

<?php if($action == 'edit_todo'){ ?>
<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('edit_item')?></h4>
		</div>
		<?php $todo = $this->db->where(array('id'=>$id))->get('todo')->row(); ?>
		<?php $attributes = array('class' => 'bs-example'); echo form_open(base_url().'leads/todo/edit',$attributes); ?>
			<input type="hidden" name="id" value="<?=$id?>">
			<div class="modal-body">
				<div class="form-group">
					<label><?=lang('todo_item')?> <span class="text-danger">*</span></label>
					<textarea class="form-control ta" name="todo_item" required><?=$todo->list_name;?> </textarea>
				</div>
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-success"><?=lang('save_changes')?></button>
			</div>
		</form>
	</div>
</div>
<?php } ?>

<?php if($action == 'delete_todo'){ ?>
<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('delete_list')?></h4>
		</div>
		<?php echo form_open(base_url().'leads/todo/delete'); ?>
			<div class="modal-body">
				<p><?=lang('delete_list_warning')?></p>
				<input type="hidden" name="id" value="<?=$id?>">
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary continue-btn"><?=lang('close')?></a>
						</div>
						<div class="col-6">
							<button type="submit" class="btn btn-primary cancel-btn"><?=lang('delete_button')?></button>
						</div>
					</div>
				</div>
			</div>
		<!--	<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-danger"><?=lang('delete_button')?></button>
			</div>-->
		</form>
	</div>
</div>
<?php } ?>
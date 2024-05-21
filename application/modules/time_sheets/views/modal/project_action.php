<?php $action = (isset($action)) ? $action : ''; ?>



<?php if($action == 'clone_project') { ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-success">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('clone_project')?></h4>
		</div>

		<?php $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/copy_project',$attributes); ?>
          <input type="hidden" name="project" value="<?=$project?>">
		<div class="modal-body">
		<p>A copy of <strong><?php echo Project::by_id($project)->project_title;?></strong> will be created.</p>




		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-success"><?=lang('clone_project')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->


<?php } ?>

<?php if($action == 'edit_team') { ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">

		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title">
		<?=lang('update_team')?></h4>
		</div>
		<?php if(User::is_admin()){ ?>

		<?php $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/team',$attributes); ?>
		<div class="modal-body">
			 <input type="hidden" name="project" value="<?=$project?>">



	<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('team_members')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="assigned_to[]" multiple="multiple" class="form-control">
				 <?php foreach (User::team() as $value) { ?>
				 	<option value="<?=$value->id?>" <?php foreach (Project::project_team($project) as $user) {
				 		if ($user->assigned_user == $value->id) { ?> selected = "selected" <?php } } ?>>
				 		<?php echo ucfirst(User::displayName($value->id)); ?></option>
                 <?php } ?>
				</select>
				<span class="help-block m-b-none"><?=lang('select_team_help')?></span>
				</div>

				</div>



		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-success"><?=lang('update_team')?></button>
		</form>
		<?php } ?>

		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->



<?php } ?>


<?php if($action == 'invoice_project'){ ?>



<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-primary" >
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('invoice_project')?></h4>
		</div>

	<?php $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/invoice',$attributes); ?>
          <input type="hidden" name="project" value="<?=$project?>">
		<div class="modal-body">
		<?php $p = Project::by_id($project); ?>
		<p>Project <strong> <?php echo $p->project_title; ?> </strong> will be converted to an Invoice.</p>

		<div class="form-group">
                      <label class="col-lg-4 control-label"><?=lang('unbill_invoiced_time')?></label>
                      <div class="col-lg-8">
                        <label class="switch">
                          <input type="checkbox" name="unbill_invoiced_time" checked="checked">
                          <span></span>
                        </label>
                      </div>
                    </div>

    <?php if(count(Project::has_billable_expenses($project)) > 0 ){ ?>
	<h3 class="small text-danger"><?=lang('include_expenses')?></h3>
    <?php foreach (Project::has_billable_expenses($project) as $key => $e) { ?>
	<?php
		$cur = ($p->client > 0) ? Client::get_currency_code($p->client)->code : $p->currency;
	?>
    <div class="form-group">
    <div class="col-lg-12 small">
    			<div class="col-md-1">
				<input type="checkbox" class="form-control" name="expense[<?=$e->id?>]" value="1">
				</div>

				<div class="col-md-6">
				<?=lang('expense_cost')?>:
				<strong>
				<?=Applib::format_currency($cur, $e->amount)?>
				</strong>
				</br>
				<?=lang('project')?>:
				<strong>
				<?=$p->project_title?>
				</strong><br>
				<?=lang('category')?>:
				<strong>
				<?=$this->db->where('id',$e->category)->get('categories')->row()->cat_name?>
				</strong><br>

				</div>

				<div class="col-md-5">
				<?=lang('expense_date')?>:
				<strong>
				<?=$e->expense_date?>
				</strong><br>
				<?=lang('notes')?>:
				<strong>
				<?=$e->notes?>
				</strong>

				</div>

				</div>

	</div>
	<div class="line line-dashed line-lg pull-in"></div>


    <?php } ?>
    <?php } ?>



		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-primary"><?=lang('invoice_project')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<?php } ?>


<?php if($action == 'comment_reply'){ ?>



<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('comment_reply')?></h4>
		</div>
					<?php
			 $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/replies',$attributes); ?>
		<div class="modal-body">
			 <input type="hidden" name="project" value="<?=$project?>">
			 <input type="hidden" name="comment" value="<?=$comment?>">



				<div class="form-group">
				<label class="col-lg-3 control-label"><?=lang('message')?> <span class="text-danger">*</span></label>
				<div class="col-lg-9">
				<textarea name="message" class="form-control ta" placeholder="<?=lang('type_message')?>"></textarea>
				</div>
				</div>

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-success"><?=lang('post_reply')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->



<?php } ?>


<?php if($action == 'delete_comment'){ ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('delete_comment')?></h4>
		</div><?php
			echo form_open(base_url().'projects/delete_comment'); ?>
		<div class="modal-body">
			<p><?=lang('delete_message_warning')?></p>

			<input type="hidden" name="id" value="<?=$info->comment_id?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-success"><?=lang('delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->



<?php } ?>


<?php if($action == 'delete_reply'){ ?>



<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('delete_comment')?></h4>
		</div><?php echo form_open(base_url().'projects/delete_reply'); ?>
		<div class="modal-body">
			<p><?=lang('delete_message_warning')?></p>

			<input type="hidden" name="reply_id" value="<?=$info->reply_id?>">
			<input type="hidden" name="parent_comment" value="<?=$info->parent_comment?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-primary"><?=lang('delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->



<?php } ?>


<?php if($action == 'mark_as_complete'){ ?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header ">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('mark_as_complete')?></h4>
		</div><?php echo form_open(base_url().'projects/mark_as_complete'); ?>
		<div class="modal-body">
			<p><?=lang('mark_as_complete_info')?></p>

			<input type="hidden" name="id" value="<?=$id?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-success"><?=lang('delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->


<?php } ?>


<?php if($action == 'delete_project'){ ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-danger"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('delete_project')?></h4>
		</div><?php echo form_open(base_url().'projects/delete'); ?>
		<div class="modal-body">
			<p><?=lang('delete_project_warning')?></p>

			<input type="hidden" name="project_id" value="<?=$project_id?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-danger"><?=lang('delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->



<?php } ?>


<?php if($action == 'add_todo'){ ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('create_list')?></h4>
		</div>

		<?php $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/todo/add',$attributes); ?>
          <input type="hidden" name="project" value="<?=$project_id?>">
		<div class="modal-body">

          	<div class="form-group">
			<label class="col-lg-4 control-label"><?=lang('todo_item')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" placeholder="Call James..." name="todo_item" required>
				</div>
			</div>

			<div class="form-group">
                      <label class="col-lg-4 control-label"><?=lang('visible')?></label>
                      <div class="col-lg-8">
                        <label class="switch">
                          <input type="checkbox" name="visible">
                          <span></span>
                        </label>
                        <span class="help-block m-b-none small text-danger">Visible to Everyone</span>
                      </div>


                    </div>


		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-success"><?=lang('save_changes')?></button>
		</form>
		</div>

	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->



<?php } ?>

<?php if($action == 'edit_todo'){ ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('edit_item')?></h4>
		</div>

		<?php $todo = $this->db->where(array('id'=>$id))->get('todo')->row(); ?>

		<?php $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/todo/edit',$attributes); ?>
          <input type="hidden" name="id" value="<?=$id?>">
          <input type="hidden" name="project" value="<?=$todo->project?>">
		<div class="modal-body">

          	<div class="form-group">
			<label class="col-lg-4 control-label"><?=lang('todo_item')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$todo->list_name;?>" name="todo_item" required>
				</div>
			</div>

			<div class="form-group">
                      <label class="col-lg-4 control-label"><?=lang('visible')?></label>
                      <div class="col-lg-8">
                        <label class="switch">
                          <input type="checkbox" name="visible" <?=($todo->visible == 'Yes') ? 'checked="checked"' : ''; ?>>
                          <span></span>
                        </label>
                        <span class="help-block m-b-none small text-danger">Visible to Everyone</span>
                      </div>


                    </div>


		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-success"><?=lang('save_changes')?></button>
		</form>
		</div>

	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->



<?php } ?>


<?php if($action == 'delete_todo'){ ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header bg-danger"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('delete_list')?></h4>
		</div><?php echo form_open(base_url().'projects/todo/delete'); ?>
		<div class="modal-body">

		<p><?=lang('delete_list_warning')?></p>

			<input type="hidden" name="id" value="<?=$id?>">

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
			<button type="submit" class="btn btn-danger"><?=lang('delete_button')?></button>
		</form>
	</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->



<?php } ?>

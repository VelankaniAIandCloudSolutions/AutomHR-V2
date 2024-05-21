<?php $action = (isset($action)) ? $action : ''; ?>

<?php if($action == 'add_bug') { ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title">
		<?=lang('new_bug')?></h4>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/bugs/add',$attributes); ?>
		<div class="modal-body">
			 <input type="hidden" name="project" value="<?=$project?>">
          		<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('issue_ref')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<?php $this->load->helper('string'); ?>
					<input type="text" class="form-control" value="<?=random_string('nozero', 6);?>" name="issue_ref" readonly>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('issue_title')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" required placeholder="<?=lang('issue_title')?>" name="issue_title">
				</div>
				</div>

				<?php if (User::is_admin() || Project::is_assigned(User::get_id(),$project)) { ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('reporter')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="reporter" class="form-control">
					<?php foreach (User::all_users() as $key => $user) { ?>
						<option value="<?=$user->id?>"><?=ucfirst(User::displayName($user->id))?></option>
					<?php } ?>
				</select>
				</div>
				</div>
				<?php } ?>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('priority')?> </label>
				<div class="col-lg-8">
				<select name="priority" class="form-control">
				 	<option value="low"><?=lang('low')?></option>
				 	<option value="medium"><?=lang('medium')?></option>
				 	<option value="high"><?=lang('high')?></option>
				</select>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('severity')?> </label>
				<div class="col-lg-8">
				<select name="severity" class="form-control">
						<option value="<?=lang('minor')?>"><?=lang('minor')?></option>
						<option value="<?=lang('major')?>"><?=lang('major')?></option>
						<option value="<?=lang('show_stopper')?>"><?=lang('show_stopper')?></option>
						<option value="<?=lang('must_be_fixed')?>"><?=lang('must_be_fixed')?></option>
				</select>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('description')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<textarea name="bug_description" class="form-control ta" placeholder="<?=lang('detailed_description')?>" required></textarea>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('reproducibility')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<textarea name="reproducibility" class="form-control ta" placeholder="<?=lang('steps_causing_bug')?>" required></textarea>
				</div>
				</div>


			<?php if (!User::is_client()) { ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('assigned_to')?> </label>
				<div class="col-lg-8">
				<select name="assigned_to" class="form-control">
				<option value="-"><?=lang('not_assigned')?></option>
				 <?php foreach (Project::project_team($project) as $u) { ?>
				 	<option value="<?=$u->assigned_user?>"><?=ucfirst(User::displayName($u->assigned_user))?></option>
                    <?php } ?>
				</select>
				</div>
				</div>

				<?php } ?>


		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-success"><?=lang('new_bug')?></button>
		</form>
		</div>
	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->



<?php } ?>

<?php if($action == 'edit_bug'){ ?>



<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('edit_bug')?></h4>
		</div>
		<?php $i = Project::view_bug($id);
		$attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/bugs/edit',$attributes); ?>
		<div class="modal-body">
			 <input type="hidden" name="bug_id" value="<?=$i->bug_id?>">
			 <input type="hidden" name="project" value="<?=$i->project?>">
          		<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('issue_ref')?></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$i->issue_ref?>" name="issue_ref" readonly>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('issue_title')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?=$i->issue_title?>" name="issue_title" required>
				</div>
				</div>

				<?php if (User::is_admin() || Project::is_assigned(User::get_id(),$i->project)) { ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('reporter')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<select name="reporter" class="form-control">
				<option value="<?=$i->reporter?>" selected="selected"><?=ucfirst(User::displayName($i->reporter))?>
				</option>
					<?php foreach (User::all_users() as $key => $user) { ?>
						<option value="<?=$user->id?>"><?=ucfirst(User::displayName($user->id))?></option>
					<?php } ?>
				</select>
				</div>
				</div>
				<?php } ?>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('priority')?> </label>
				<div class="col-lg-8">
				<select name="priority" class="form-control">
				<option value="<?=$i->priority?>"><?=ucfirst($i->priority)?></option>
				 	<option value="low"><?=lang('low')?></option>
				 	<option value="medium"><?=lang('medium')?></option>
				 	<option value="high"><?=lang('high')?></option>
				</select>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('severity')?> </label>
				<div class="col-lg-8">
				<select name="severity" class="form-control">
						<option value="<?=$i->severity?>"><?=$i->severity?></option>
						<option value="<?=lang('minor')?>"><?=lang('minor')?></option>
						<option value="<?=lang('major')?>"><?=lang('major')?></option>
						<option value="<?=lang('show_stopper')?>"><?=lang('show_stopper')?></option>
						<option value="<?=lang('must_be_fixed')?>"><?=lang('must_be_fixed')?></option>
				</select>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('description')?> <span class="text-danger">*</span></label>
				<div class="col-lg-8">
				<textarea name="bug_description" class="form-control ta" required><?=$i->bug_description?></textarea>
				</div>
				</div>

				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('reproducibility')?> </label>
				<div class="col-lg-8">
				<textarea name="reproducibility" class="form-control ta" required><?=$i->reproducibility?></textarea>
				</div>
				</div>

				<?php if (!User::is_client()){ ?>
				<div class="form-group">
				<label class="col-lg-4 control-label"><?=lang('assigned_to')?> </label>
				<div class="col-lg-8">
				<select name="assigned_to" class="form-control">
				<option value="-"><?=lang('not_assigned')?></option>
				 <?php foreach (Project::project_team($i->project) as $u) { ?>
				 	<option value="<?=$u->assigned_user?>"<?=($i->assigned_to == $u->assigned_user ? ' selected="selected"' : '')?>><?=ucfirst(User::displayName($u->assigned_user));?></option>
                            <?php } ?>
				</select>
				</div>
				</div>
				<?php } ?>

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



<?php if($action == 'add_bug_file') { ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title">
		<?=lang('upload_file')?></h4>
		</div>

		<?php $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open_multipart(base_url().'projects/bugs/file',$attributes); ?>
          <input type="hidden" name="project" value="<?=$project;?>">
           <input type="hidden" name="bug" value="<?=$bug?>">
		<div class="modal-body">

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=lang('file_title')?></label>
                    <div class="col-lg-9">
                    <input name="title" class="form-control" required placeholder="<?=lang('file_title')?>"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=lang('description')?></label>
                    <div class="col-lg-9">
                    <textarea name="description" class="form-control ta" placeholder="<?=lang('description')?>" ></textarea>
                    </div>
                </div>

                <div id="file_container">
                    <div class="form-group">
                        <div class="col-lg-offset-3 col-lg-9">
                        <div class="col-lg-8">
							<label class="btn btn-default btn-choose">Choose File</label>
                            <input type="file" class="form-control" name="bugfiles[]" required>
                        </div>
                    </div>
                </div>

		</div>
		<div class="modal-footer">
                    <a href="#" class="btn btn-success pull-left" id="add-new-file"><?=lang('upload_another_file')?></a>
                    <a href="#" class="btn btn-danger pull-left" id="clear-files" style="margin-left:6px;"><?=lang('clear_files')?></a>
                    <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-success"><?=lang('upload_file')?></button>
		</form>
		</div>
	</div>

    <script type="text/javascript">
        $('#clear-files').click(function(){
            $('#file_container').html(
                "<div class='form-group'>" +
                    "<div class='col-lg-offset-3 col-lg-9'>" +
                    "<label class='btn btn-default btn-choose'>Choose File</label><input type='file' class='form-control' name='bugfiles[]'>" +
                    "</div></div>"
            );
        });

        $('#add-new-file').click(function(){
            $('#file_container').append(
                "<div class='form-group'>" +
                "<div class='col-lg-offset-3 col-lg-9'>" +
                "<label class='btn btn-default btn-choose'>Choose File</label><input type='file' class='form-control' name='bugfiles[]'>" +
                "</div></div>"
            );
        });
    </script>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->


<?php } ?>


<?php if($action == 'bug_edit_file') { ?>



<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('edit_file')?></h4>
		</div>

		<?php
		$attributes = array('class' => 'bs-example form-horizontal');
          echo form_open_multipart(base_url().'projects/bugs/file/edit',$attributes); ?>
          <input type="hidden" name="project" value="<?=$project?>">
          <input type="hidden" name="bug" value="<?=$f->bug?>">
          <input type="hidden" name="file_id" value="<?=$file_id?>">
		<div class="modal-body">
                    <?php   $icon = $this->applib->file_icon($f->file_ext);
                            $real_url = base_url().'assets/bug-files/'.$f->file_name; ?>

                <div class="form-group">
                    <div class="col-lg-3">
                    <?php if ($f->is_image == 1) : ?>
                    <?php if ($f->image_width > $f->image_height) {
                        $ratio = round(((($f->image_width - $f->image_height) / 2) / $f->image_width) * 100);
                        $style = 'height:100%; margin-left: -'.$ratio.'%';
                    } else {
                        $ratio = round(((($f->image_height - $f->image_width) / 2) / $f->image_height) * 100);
                        $style = 'width:100%; margin-top: -'.$ratio.'%';
                    }  ?>
                        <div class="file-icon icon-large pull-right">
                            <a href="<?=base_url()?>projects/files/preview/<?=$f->file_id?>/<?=$project?>" data-toggle="ajaxModal">
                            <img style="<?=$style?>" src="<?=$real_url?>" /></a>
                        </div>
                    <?php else : ?>
                        <div class="file-icon icon-large pull-right"><i class="fa <?=$icon?> fa-5x"></i></div>
                    <?php endif; ?>
                    </div>
                    <div class="col-lg-9">
                    <table class="table table-striped table-small">
                        <tbody>
                            <tr><td class="col-lg-3"><?=lang('file_name');?></td><td><?=$f->file_name;?></td></tr>
                            <tr><td><?=lang('size');?></td><td><?=$f->size;?></td></tr>
                            <?php if($f->is_image == 1) : ?>
                            <tr><td><?=lang('dimensions');?></td><td><?=$f->image_width;?>x<?=$f->image_height;?></td></tr>
                            <?php endif; ?>
                            <tr><td><?=lang('date');?></td><td><?=strftime(config_item('date_format')." %H:%M", strtotime($f->date_posted));?></td></tr>
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=lang('file_title')?></label>
                    <div class="col-lg-9">
                    <input name="title" class="form-control" value="<?=$f->title;?>" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=lang('description')?></label>
                    <div class="col-lg-9">
                    <textarea name="description" class="form-control ta" ><?=$f->description;?></textarea>
                    </div>
                </div>		<div class="modal-footer">
                    <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
                    <button type="submit" class="btn btn-primary"><?=lang('save_file')?></button>
		</form>
		</div>
	        </div>
        </div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<?php } ?>

<?php if($action == 'bug_delete_file'){ ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('delete_file')?></h4>
		</div><?php
			echo form_open(base_url().'projects/bugs/file/delete'); ?>
		<div class="modal-body">
			<p><?=lang('delete_file_warning')?></p>

			<input type="hidden" name="file_id" value="<?=$file_id?>">
			<input type="hidden" name="bug" value="<?=Project::view_bug_file($file_id)->bug;?>">
			<input type="hidden" name="project" value="<?=$project?>">

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


<?php if($action == 'preview_bug_file') { ?>


<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title">
            <?=$file->title?></h4>
        </div>
        <div class="modal-body">
     <img width="538" src="<?=base_url()?>assets/bug-files/<?=$file->file_name?>" alt="<?=$file->file_name?>"/>
        </div>
    </div>
</div>


<?php } ?>

<?php if($action == 'delete_bug_comment') { ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('delete_comment')?></h4>
		</div><?php
			echo form_open(base_url().'projects/bugs/delete_comment'); ?>
		<div class="modal-body">
			<p><?=lang('delete_message_warning')?></p>

			<input type="hidden" name="comment_id" value="<?=$id?>">

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

<?php if($action == 'delete_bug') { ?>


<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title"><?=lang('delete_file')?></h4>
		</div><?php
			echo form_open(base_url().'projects/bugs/delete'); ?>
		<div class="modal-body">
			<p><?=lang('delete_file_warning')?></p>

			<input type="hidden" name="bug_id" value="<?=$bug_id?>">
			<input type="hidden" name="project" value="<?=$project?>">

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

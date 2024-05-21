<!-- Start Notebook -->
<?php if(User::is_admin() || (User::is_staff() && User::perm_allowed(User::get_id(),'view_project_notes'))){ ?>
<div class="panel panel-white">
	<div class="panel-heading">
		<h3 class="panel-title"><?=lang('project_notes')?></h3>
	</div>
	<?php echo form_open(base_url().'projects/notebook/savenote'); ?>
		<div class="panel-body">
			<input type="hidden" name="project" value="<?=$project_id?>">
			<div class="foeditor-noborder">
				<textarea type="text" class="form-control  foeditor-500" name="notes" placeholder="<?=lang('type_your_note_here')?>"><?php echo Project::by_id($project_id)->notes;?></textarea>
			</div>
			<div class="m-t-20">
				<button type="submit" class="btn btn-success"><?=lang('save_changes')?></button>
			</div>
		</div>
	</form>
</div>
<!-- End Notebook -->
<?php } ?>
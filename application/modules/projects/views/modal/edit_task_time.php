<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title">
			<?=lang('time_entry')?></h4>
		</div>
		
		<?php
		$attributes = array('class' => 'bs-example form-horizontal');
		echo form_open(base_url().'projects/tasks/timesheet',$attributes); ?>
		<input type="hidden" name="task_id" value="<?=$id?>">

		<div class="modal-body">
		<?php $timers = $this->db->where(array('task'=>$id,'status'=>'0'))->order_by('timer_id','DESC')->get('tasks_timer')->result(); 
		if(count($timers) > 0){
			foreach ($timers as $key => $t) { ?>
			<div class="form-group">
				<label class="col-lg-4 control-label small"><?=lang('date')?>: <?=strftime(config_item('date_format'), strtotime($t->date_timed))?></label>
				<div class="col-lg-3">
					<input type="text" class="form-control" value="<?=Applib::gm_sec(Project::logged_time('tasks',$t->timer_id));?>" name="<?=$t->timer_id?>">
				</div>
				<span class="help-block text-dark small">
				<a class="thumb-xs pull-left m-r-sm">
                       

                            <img src="<?php echo User::avatar_url($t->user); ?>" class="img-circle" data-toggle="tooltip" data-title="<?=User::displayName($t->user)?>" data-placement="left" class="img-rounded"> </a>
				<strong><?=User::displayName($t->user)?></strong> - <?=Applib::sec_to_hours(Project::logged_time('tasks',$t->timer_id));?></span>
			</div>
			<?php } ?>
		<?php } else { ?>
			<div class="alert alert-info small">
        		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        		<?=lang('nothing_to_display')?>
    		</div>
    	<?php } ?>

			
		</div>

		<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
		<?php if(count($timers) > 0){ ?>
		<button type="submit" class="btn btn-success"><?=lang('save_changes')?></button>
		<?php } ?>
	</form>
</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
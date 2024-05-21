<!-- Modal Dialog -->
<div class="modal-dialog  modal-dialog-centered">
	<!-- Modal Content -->
    <div class="modal-content">
        <div class="modal-header">
			<h4 class="modal-title"><?=lang('edit_schedule_group')?></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <?php
        if (!empty($group_info)) {
            foreach ($group_info as $key => $d) { ?>
                <?php
                $attributes = array('class' => 'bs-example');
                echo form_open(base_url().'shift_scheduling/edit_add_schedule_group',$attributes); ?>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?=$d->id?>">
                        <div class="form-group">
                            <label><?php echo lang('group_name'); ?> <span class="text-danger">*</span></label>
							<input type="text" class="form-control" value="<?php echo $d->group_name;?>" name="group_name">
                            <input type="hidden" class="form-control" value="<?php echo $this->session->userdata('user_id');?>" name="created_by">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('delete_schedule_group'); ?></label>
                            <div>
                                <label class="switch">
                                    <input type="checkbox" name="delete_group">
                                    <span></span>
                                </label>
                            </div>
                        </div>
						<div class="submit-section">
							<button type="submit" class="btn btn-primary submit-btn"><?=lang('save_changes')?></button>
						</div>
                    </div>
                </form>
        <?php } } ?>
    </div>
    <!-- /Modal Content -->
</div>
<!-- /Modal Dialog -->
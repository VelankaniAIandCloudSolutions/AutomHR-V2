<?php $action = (isset($action)) ? $action : ''; ?>



<?php if($action == 'add_link') { ?>

<?php if(User::is_admin() || Project::is_assigned(User::get_id(),$project_id)){ ?>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('add_link')?></h4>
		</div>
<style>
.datepicker{z-index:1151 !important;}
</style>
                <?php
                $attributes = array('class' => 'bs-example form-horizontal');
                echo form_open(base_url().'projects/links/add',$attributes); ?>
                <input type="hidden" name="project_id" value="<?=$project_id?>">
                <div class="modal-body">


                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('link_url')?> <span class="text-danger">*</span></label>
                    <div class="col-lg-8">
                            <input type="text" class="form-control" required placeholder="http://" name="link_url">

                    </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('link_title')?> </label>
                    <div class="col-lg-8">
                            <input type="text" class="form-control" placeholder="<?=lang('link_title')?>" name="link_title">
                            <small class="block small text-muted"><?=lang('add_link_auto')?></small>
                    </div>
                    </div>

                    <div class="form-group">
                    <label class="col-lg-4 control-label"><?=lang('description')?> </label>
                    <div class="col-lg-8">
                        <textarea name="description" class="form-control ta" placeholder="<?=lang('description')?>"></textarea>
                    </div>
                    </div>

		</div>
		<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
		<button type="submit" class="btn btn-success"><?=lang('add_link')?></button>
		</form>
		</div>
	</div>

	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
  <?php } ?>

<?php } ?>



<?php if($action == 'edit_link') { ?>


<?php if(User::is_admin() || Project::is_assigned(User::get_id(),$link->project_id)){ ?>

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('edit_link')?></h4>
        </div>


        <?php
        $attributes = array('class' => 'bs-example form-horizontal');
          echo form_open(base_url().'projects/links/edit',$attributes); ?>
          <input type="hidden" name="project_id" value="<?=$link->project_id?>">
          <input type="hidden" name="link_id" value="<?=$link->link_id?>">
        <div class="modal-body">

                <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('link_title')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-8">
                                <input type="text" class="form-control" value="<?=$link->link_title?>" name="link_title">
                        </div>
                        </div>
                <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('link_url')?> <span class="text-danger">*</span></label>
                        <div class="col-lg-8">
                                <input type="text" class="form-control" value="<?=$link->link_url?>" name="link_url">
                        </div>
                        </div>

                        <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('description')?> </label>
                        <div class="col-lg-8">
                        <textarea name="description" class="form-control ta"><?=$link->description?></textarea>
                        </div>
                        </div>

                        <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('username')?></label>
                        <div class="col-lg-8">
                                <input type="text" autocomplete="off" class="form-control" value="<?=$link->username?>" name="username">
                        </div>
                        </div>

                        <div class="form-group">
                        <label class="col-lg-4 control-label"><?=lang('password')?></label>
                        <div class="col-lg-8">
                                <input type="text" autocomplete="off" class="form-control" value="<?=$link->password?>" name="password">
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

<?php } ?>


<?php if($action == 'delete_link') { ?>


<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?=lang('delete_link')?></h4>
        </div><?php
            echo form_open(base_url().'projects/links/delete'); ?>
        <div class="modal-body">
            <p><?=lang('delete_link_warning')?></p>

            <input type="hidden" name="project_id" value="<?=$project_id?>">
            <input type="hidden" name="link_id" value="<?=$link_id?>">

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

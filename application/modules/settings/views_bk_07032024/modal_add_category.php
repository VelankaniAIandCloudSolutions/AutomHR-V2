<?php

$modules = array('expenses','leads','projects','invoices','estimates','tickets');
?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?=lang('add_category')?></h4>
        </div>

                <?php
                $attributes = array('class' => 'bs-example','id'=>'settingsAddCategory');
                echo form_open(base_url().'settings/add_category',$attributes); ?>
                    <div class="modal-body">

                        <div class="form-group">
                            <label><?=lang('cat_name')?> <span class="text-danger">*</span></label>
                               <input type="text" class="form-control" placeholder="e.g Accomodation" name="cat_name">
                        </div>

                        <div class="form-group">
                        <label><?=lang('module')?> <span class="text-danger">*</span></label>
						<select class="select2-option form-control" name="module" required>
							<?php foreach ($modules as $m) : ?>
								<option value="<?=$m?>"><?=ucfirst($m)?></option>
							<?php endforeach; ?>
						</select>
                    </div>

                    </div>
                    <div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
                        <button id="settings_add_category" class="btn btn-success"><?=lang('save_changes')?></button>
                    </div>
                </form>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->

<!-- Modal Dialog -->
<div class="modal-dialog  modal-dialog-centered">
	<!-- Modal Content -->
    <div class="modal-content">
        <div class="modal-header">
			<h4 class="modal-title"><?=lang('edit_category')?></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <?php
        if (!empty($category_info)) {
            foreach ($category_info as $key => $d) { ?>
                <?php
                $attributes = array('class' => 'bs-example');
                echo form_open(base_url().'categories/edit_categories',$attributes); ?>
                    <div class="modal-body">
                        <input type="hidden" name="cat_id" value="<?=$d->cat_id?>">
                        <div class="form-group">
                            <label>Category Name <span class="text-danger">*</span></label>
							<input type="text" class="form-control" value="<?=$d->category_name?>" name="category_name">
                        </div>
                        <div class="form-group">
                            <label>Delete Category</label>
                            <div>
                                <label class="switch">
                                    <input type="checkbox" name="delete_category">
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
<?php $action = (isset($action)) ? $action : ''; ?>
<?php if($action == 'add_file') { ?>
<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('upload_file')?></h4>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal'); echo form_open_multipart(base_url().'leads/file/add',$attributes); ?>
			<input type="hidden" name="company" value="<?=$company?>">
			<div class="modal-body">
                <div class="form-group">
                    <label class="col-lg-3 control-label"><?=lang('file_title')?> <span class="text-danger">*</span></label>
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
                            <label class="btn btn-default btn-choose">Choose File</label>
                            <input type="file" class="form-control" name="clientfiles[]" required="">
                        </div>
                    </div>
                </div>
				<div class="modal-footer">
                    <a href="#" class="btn btn-success float-left" id="add-new-file"><?=lang('upload_another_file')?></a>
                    <a href="#" class="btn btn-danger float-left" id="clear-files" style="margin-left:6px;"><?=lang('clear_files')?></a>
                    <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
                    <button type="submit" class="btn btn-success"><?=lang('upload_file')?></button>
				</div>
	        </div>
		</form>
	</div>
    <script type="text/javascript">
        $('#clear-files').click(function(){
            $('#file_container').html(
                "<div class='form-group'>" +
                    "<div class='col-lg-offset-3 col-lg-9'>" +
                    "<label class='btn btn-default btn-choose'>Choose File</label><input type='file' class='form-control' name='clientfiles[]'>" +
                    "</div></div>"
            );
        });

        $('#add-new-file').click(function(){
            $('#file_container').append(
                "<div class='form-group'>" +
                "<div class='col-lg-offset-3 col-lg-9'>" +
                "<label class='btn btn-default btn-choose'>Choose File</label><input type='file' class='form-control' name='clientfiles[]'>" +
                "</div></div>"
            );
        });
    </script>
</div>
<?php } ?>

<?php if($action == 'delete_file') { ?>
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('delete_file')?></h4>
        </div>
		<?php echo form_open(base_url().'leads/file/delete'); ?>
			<div class="modal-body">
				<p><?=lang('delete_file_warning')?></p>
				<input type="hidden" name="file" value="<?=$file_id?>">
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
			<!--<div class="modal-footer">
				<a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-danger"><?=lang('delete_button')?></button>
			</div>-->
        </form>
	</div>
</div>
<?php } ?>
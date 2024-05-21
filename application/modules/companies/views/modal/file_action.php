<?php $action = (isset($action)) ? $action : ''; ?>
<?php if($action == 'add_file') { ?>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('upload_file')?></h4>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id' => 'clientFilesForm'); echo form_open_multipart(base_url().'companies/file/add',$attributes); ?>
			<input type="hidden" name="company" value="<?=$company?>">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-lg-3 control-label"><?=lang('file_title')?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
						<input name="title" class="form-control" placeholder="<?=lang('file_title')?>"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"><?=lang('description')?> <span class="text-danger">*</span></label>
					<div class="col-lg-9">
					<textarea name="description" class="form-control ta" placeholder="<?=lang('description')?>" ></textarea>
					</div>
				</div>
				<div id="file_container">
					<div class="form-group">
						<div class="col-lg-offset-3 col-lg-9">
							<label class="btn btn-default btn-choose">Choose File</label>
							<input type="file" class="form-control" id="ipfile0" name="clientfiles[]">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<a href="#" class="btn btn-success pull-left" id="add-new-file"><?=lang('upload_another_file')?></a>
					<a href="#" class="btn btn-danger pull-left" id="clear-files" style="margin-left:6px;"><?=lang('clear_files')?></a>
					<a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
					<button class="btn btn-success" id="client_file_upload"><?=lang('upload_file')?></button>
				</div>
			</form>
		</div>
	</div>

    <script type="text/javascript">
	var fileLen = 0;
        $('#clear-files').click(function(){
			fileLen = 0;
            $('#file_container').html(
                "<div class='form-group'>" +
                    "<div class='col-lg-offset-3 col-lg-9'>" +
                    "<label class='btn btn-default btn-choose'>Choose File</label><input type='file' class='form-control ipfile0' name='clientfiles[]'>" +
                    "</div></div>"
            );
        });

        $('#add-new-file').click(function(){
			
			$('#file_container').append(
                "<div class='form-group'>" +
                "<div class='col-lg-offset-3 col-lg-9'>" +
                "<label class='btn btn-default btn-choose'>Choose File</label><input type='file' class='form-control' id='ipfile"+fileLen+"' name='clientfiles[]'>" +
                "</div></div>"
            );
        });
    </script>
</div>

<?php } ?>

<?php if($action == 'delete_file') { ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header"> <button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('delete_file')?></h4>
        </div>
		<?php echo form_open(base_url().'companies/file/delete'); ?>
			<div class="modal-body">
				<p><?=lang('delete_file_warning')?></p>
				<input type="hidden" name="file" value="<?=$file_id?>">
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-danger"><?=lang('delete_button')?></button>
			</div>
        </form>
	</div>
</div>
<?php } ?>
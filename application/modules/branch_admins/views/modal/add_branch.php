<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('add_branch')?></h4>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id'=>'branchlisttable'); echo form_open(base_url().'all_branches/add_branch',$attributes); ?>
			<div class="modal-body">
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('branch_name')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control" placeholder="<?=lang('branch_name')?>" name="branch_name">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('branch_status')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<select name="branch_status" class="form-control m-b">
							<option value="" selected disabled>Choose status</option>
							<option value="0">Active</option>
							<option value="1">InActive</option>
                        </select>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn btn-close" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-success" id="add_branches"><?=lang('add_branch')?></button>
			</div>
		</form>
	</div>
</div>
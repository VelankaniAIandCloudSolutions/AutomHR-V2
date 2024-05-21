<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">Add Category</h4>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		<?php $attributes = array('class' => 'bs-example','id'=> 'settingsDepartmentForm'); echo form_open_multipart('categories/categoiress', $attributes); ?>
			<div class="modal-body">
				<div class="form-group">
					<label>Category Name <span class="text-danger">*</span></label>
					<input type="text" name="category_name" class="form-control" required>
				</div>
				<div class="form-group">
					<label><?=lang('branch')?> <span class="text-danger">*</span></label>
					<select class="form-control" style="width:100%;"  name="branch_id" id="add_branch" required >
						<option value="">Select</option>
						<?php
						if(!empty($branches))	{
						foreach ($branches as $branch1){ ?>
						<option value="<?=$branch1['branch_id']?>" <?php echo ($entity_id==$branch1['branch_id'])?'selected':'';?>><?=$branch1['branch_name']?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>

				<div class="submit-section">
					<button class="btn btn-primary submit-btn">Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>
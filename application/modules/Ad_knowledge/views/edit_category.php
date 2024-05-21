<!-- Modal Dialog -->
<div class="modal-dialog modal-dialog-centered" role="dialog">
	<!-- Modal Content -->
    <div class="modal-content">
        <div class="modal-header">
			<h4 class="modal-title">Edit Category</h4>
			<button type="button" class="close" data-dismiss="modal">×</button>
		</div>

		<div class="modal-body">
				<?php $attributes = array('id' => 'category_edit_submit'); echo form_open(base_url().'ad_knowledge/addCategory',$attributes); ?>
					<div class="row">
						<input class="form-control" type="hidden" id="category_id" name="category_id" value="<?php echo $category_details->id; ?>">
						<div class="col-md-12">
						<div class="form-group">
							<label><?php echo lang('category_name'); ?><span class="text-danger">*</span></label>
							<input class="form-control" type="text" name="category_name" id="category_name" value="<?php echo $category_details->category_name; ?>" required="">
						</div>
						<div class="form-group">
							<label><?=lang('branch')?> <span class="text-danger">*</span></label>
							<select class="form-control" style="width:100%;"  name="branch_id" id="add_branch" required >
								<option value="">Select</option>
								<?php if(!empty($branches))	{
									foreach ($branches as $branch1){ ?>
										<option value="<?=$branch1['branch_id']?>" <?php if($category_details->branch_id ==$branch1['branch_id']){echo 'selected';} ?>><?=$branch1['branch_name']?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>	
						</div>
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn" id="submit_edit_category"><?php echo lang('submit'); ?></button>
					</div>
				</form>
			</div>
        	
    </div>
    <!-- /Modal Content -->
</div>
<!-- /Modal Dialog -->
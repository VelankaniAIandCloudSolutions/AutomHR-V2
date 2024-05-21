<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('create_revenue')?></h4>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id' => 'createRevenueForm'); echo form_open_multipart(base_url().'budget_revenues/create',$attributes); ?>
			<div class="modal-body">
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('amount')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control" placeholder="800.00" name="amount">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('notes')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<textarea class="form-control ta" name="notes"></textarea>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('revenue_date')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input class="datepicker-input form-control" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="revenue_date" data-date-format="<?=config_item('date_picker_format');?>" >
					</div>
				</div>
			 <div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('category')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<select name="category" class="form-control m-b" id="main_category">
							<option value="" disabled selected>Choose Category</option>
							<?php foreach ($categories as  $cat) { ?>
							<option value="<?php echo $cat['cat_id']?>"><?php echo $cat['category_name']?></option>
							<?php } ?>
						</select>
					</div>
					
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('sub_category')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<select name="sub_category" class="form-control m-b" id="sub_category">
							<option value="" >Choose Sub-Category</option>
						</select>
					</div>
					
				</div> 
				
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('attach_file')?></label>
					<div class="col-lg-8">
						<label class="btn btn-default btn-choose">Choose File</label>
						<input type="file" class="form-control" data-buttonText="<?=lang('choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="receipt">
					</div>
				</div>
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-success" id="accountsCreateRevenue"><?=lang('save_changes')?></button>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
    $('.datepicker-input').datepicker({ language: locale});
</script>

<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('edit_revenue')?></h4>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id' => 'editRevenueForm'); echo form_open_multipart(base_url().'budget_revenues/edit',$attributes); ?>
			<input type="hidden" name="revenue" value="<?=$inf['id'];?>">
			<div class="modal-body">
          		<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('amount')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control" value="<?php echo $inf['amount'];?>" name="amount">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('notes')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<textarea class="form-control ta" name="notes"><?php echo $inf['notes'];?></textarea>
					</div>
				</div>
								
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('category')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<select name="category" class="form-control" id="main_category">
							<option value="" disabled selected>Choose Category</option>
							<?php foreach ($categories as  $cat) { ?>
							<option value="<?php echo $cat['cat_id']?>" <?php echo($cat['cat_id'] == $inf['category_id'])?'selected':'';?>><?php echo $cat['category_name']?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<?php $subcat = $this->db->get_where('budget_subcategory',array('sub_id'=>$inf['s_category_id']))->row_array(); ?>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('category')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<select name="sub_category" class="form-control" id="sub_category">
							<option value="">Choose SubCategory</option>
							<option value="<?php echo $subcat['sub_id']; ?>" selected><?php echo $subcat['sub_category']; ?></option>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('revenue_date')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input class="datepicker-input form-control" type="text" value="<?=strftime(config_item('date_format'),strtotime($inf['revenue_date']))?>" name="revenue_date" data-date-format="<?=config_item('date_picker_format');?>" >
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('attach_file')?></label>
					<div class="col-lg-8">
						<label class="btn btn-default btn-choose">Choose File</label>
						<input type="file" class="form-control" data-buttonText="<?=lang('choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline" name="receipt" value="<?php echo $inf['receipt'];?>">
						<?php if(!empty($inf['receipt'])){ ?>
						<span><img src="<?php echo base_url().'./assets/uploads/'.$inf['receipt'];?>" alt="Smiley face" width="42" height="42"><?php echo $inf['receipt'];?> <span> <?php } ?>
					</div>
				</div>
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-success" id="accountsEditRevenue"><?=lang('save_changes')?></button>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
    $('.datepicker-input').datepicker({ language: locale});
</script>
<!-- <script type="text/javascript">
$('#selected_project').change(function() {
    if($("#selected_project").val()==="NULL"){
    	$("#client_select").show();
    }else{
    	$("#client_select").hide();
    }
});
</script> -->
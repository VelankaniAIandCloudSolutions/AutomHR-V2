<div class="content">
	
	<!-- Start create estimate -->
	<div class="row">
		<div class="col-sm-12">
			<div class="panel">
				<div class="panel-body">
				<div class="row">
					<div class="col-xs-12">
						<h3 class="page-title"><?=lang('create_estimate')?></h3>
					</div>
				</div>
					<?php $attributes = array('class' => 'bs-example form-horizontal', 'id' => 'createEstimateForm'); echo form_open(base_url().'estimates/add',$attributes); ?>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('reference_no')?> <span class="text-danger">*</span></label>
							<div class="col-lg-4">
								<?php $this->load->helper('string'); ?>
								<input type="text" class="form-control" readonly value="<?=config_item('estimate_prefix')?><?=Estimate::generate_estimate_number();?>" name="reference_no">
							</div>
						</div>
						<div class="form-group">
							<label  class="col-lg-3 control-label"><?=lang('branch')?> <span class="text-danger">*</span></label>
							<div class="col-lg-4">
							<select class="form-control" style="width:100%;"  name="branch_id" id="add_branch" required onchange="entity_change(this.value)">
							<option value="">Select</option>
							<?php
								if(!empty($branches))	{
								foreach ($branches as $branch1){ ?>
								<option value="<?=$branch1['branch_id']?>" ><?=$branch1['branch_name']?></option>
								<?php } ?>
								<?php } ?>
							</select>
								</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('client')?> <span class="text-danger">*</span> </label>
							<div class="col-lg-4">
								<select class="select2-option form-control" style="width:100%" id="create_estimate_client" name="client" >
									<optgroup label="<?=lang('clients')?>">
										
									</optgroup>
								</select>
								<label id="estimates_client_error" class="error display-none" style="top:40px;" for="client">Please Select a client</label>
							</div>
							<?php if(User::is_admin()) : ?>
							<div class="col-lg-5">
								<!-- <a href="<?=base_url()?>companies/create" class="btn btn-primary" data-toggle="ajaxModal" title="<?=lang('new_company')?>" data-placement="bottom">
									<i class="fa fa-plus"></i> <?=lang('new_client')?>
								</a> -->
							</div>
							<?php endif; ?>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('due_date')?></label>
							<div class="col-lg-4">
								<input class="datepicker-input form-control" readonly type="text" value="<?=strftime(config_item('date_format'), time());?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('tax')?> 1</label>
							<div class="col-lg-4">
								<div class="input-group">
									<span class="input-group-addon">%</span>
									<input class="form-control money" type="text" value="<?=config_item('default_tax')?>" id="estimate_create_tax1" name="tax">
								</div>
								<div class="row">
								<div class="col-md-12">
								<label id="create_estimate_tax1_error" class="error display-none" style="position:inherit;top:0;font-size: 15px;" for="tax1">Entered Tax is invalid</label>
								</div>
								</div>
								<!-- <label id="create_estimate_tax_error" class="error display-none" style="top:40px;" for="estimate_tax">Entered Tax1 is invalid</label> -->
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('tax')?> 2</label>
							<div class="col-lg-4">
								<div class="input-group">
									<span class="input-group-addon">%</span>
									<input class="form-control money" type="text" value="<?=config_item('default_tax2')?>" id="estimate_create_tax2" name="tax2">
								</div>
								<div class="row">
								<div class="col-md-12">
								<label id="create_estimate_tax2_error" class="error display-none" style="position:inherit;top:0;font-size: 15px;" for="tax2">Entered Tax is invalid</label>
								</div>
								</div>
								<!-- <label id="create_estimate_tax2_error" class="error display-none" style="top:40px;" for="estimate_tax2">Entered Tax2 is invalid</label> -->
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('discount')?></label>
							<div class="col-lg-4">
								<div class="input-group">
									<span class="input-group-addon">%</span>
									<input class="form-control money" type="text" value="0.00" id="estimate_create_discount" name="discount">
								</div>
								<div class="row">
								<div class="col-md-12">
								<label id="create_estimate_discount_error" class="error display-none" style="position:inherit;top:0;font-size: 15px;" for="discount">Entered Discount is invalid</label>
								</div>
								</div>
								<!-- <label id="create_estimate_discount_error" class="error display-none" style="top:40px;" for="estimate_tax2">Entered Discount is invalid</label> -->
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('notes')?> <span class="text-danger">*</span></label>
							<div class="col-lg-9">
								<textarea name="notes" class="form-control foeditor-estimate-cnote"><?=config_item('estimate_terms')?></textarea>
								<div class="row">
									<div class="col-md-6">
									<label id="estimates_notes_error" class="error display-none" style="position:inherit;top:0" for="notes">Notes field must not empty</label>
									</div>
								</div>
							</div>
						</div>
						<div class="text-center">
						    <button type="button" class="btn btn-primary" onClick="javascript:history.go(-1)">Close</button>
							<button type="submit" class="btn btn-primary m-l-5" id="createEstimate"><i class="fa fa-plus"></i> <?=lang('create_estimate')?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<!-- End create estimate -->
</div>
<script>
	function entity_change(a){
		$.ajax( {
			url:'<?php echo base_url('projects/get_companies/');?>'+a,
			success:function(data) {
				var reqdata = JSON.parse(data);
				$('#create_estimate_client').empty();
				$('#create_estimate_client').append(reqdata.companies);	
				
			}
		});
	}
</script>	
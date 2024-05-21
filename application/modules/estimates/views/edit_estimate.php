<!-- Start -->
<div class="content">
	<?php $est = Estimate::view_estimate($id); ?>
	<div class="row">
		<div class="col-xs-3">
			<h4 class="page-title">Edit Estimate</h4>
		</div>
		<div class="col-xs-9 text-right m-b-0">
			<a href="<?=base_url()?>fopdf/estimate/<?=$est->est_id?>" class="btn btn-primary pull-right"><i class="fa fa-file-pdf-o"></i> <?=lang('pdf')?></a>
			<a href="<?=base_url()?>estimates/view/<?=$est->est_id?>" data-original-title="<?=lang('view_details')?>" data-toggle="tooltip" data-placement="top" class="btn btn-success rounded m-r-2">
				<i class="fa fa-info-circle"></i> <?=lang('estimate_details')?>
			</a>
		</div>
	</div>
	<!-- Start create invoice -->
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-white">
				<div class="panel-heading">
					<h3 class="panel-title"><?=lang('estimate_details')?> - <?=$est->reference_no?></h3>
				</div>
				<div class="panel-body">
					<?php $attributes = array('class' => 'bs-example form-horizontal'); echo form_open_multipart(base_url().'estimates/edit',$attributes); ?>
						<input type="hidden" name="est_id" value="<?=$est->est_id?>">
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('reference_no')?> <span class="text-danger">*</span></label>
							<div class="col-lg-4">
								<input type="text" class="form-control" value="<?=$est->reference_no?>" name="reference_no" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('client')?> <span class="text-danger">*</span> </label>
							<div class="col-lg-4">
								<select class="select2-option form-control" style="width:100%" id="edit_estimate_client" name="client" >
									<optgroup label="<?=lang('clients')?>">
										<?php foreach (Client::get_all_clients() as $client): if($client->branch_id == $this->session->userdata('branch_id')){ ?>
										<option value="<?=$client->co_id?>"  <?php if($est->client == $client->co_id){ echo "selected"; } ?>   ><?=ucfirst($client->company_name)?>
										</option>
										<?php } endforeach; ?>
									</optgroup>
									<!-- <optgroup label="<?=lang('general_estimate')?>">
										<option value="0"><?=lang('unregistered_clients')?></option>
									</optgroup> -->
								</select>
								<label id="estimates_client_error" class="error display-none" style="top:40px;" for="client">Please Select a client</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('created')?></label>
							<div class="col-lg-4">
								<input class="datepicker-input form-control" type="text" readonly value="<?=strftime(config_item('date_format'), strtotime($est->date_saved));?>" name="date_saved" data-date-format="<?=config_item('date_picker_format');?>" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('due_date')?></label>
							<div class="col-lg-4">
								<input class="datepicker-input form-control" type="text" readonly value="<?=strftime(config_item('date_format'), strtotime($est->due_date));?>" name="due_date" data-date-format="<?=config_item('date_picker_format');?>" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('tax')?> 1</label>
							<div class="col-lg-4">
								<div class="input-group">
									<span class="input-group-addon">%</span>
									<input class="form-control money" type="text" value="<?=$est->tax?>" id="estimate_edit_tax1" name="tax">
								</div>
								<div class="row">
								<div class="col-md-12">
								<label id="edit_estimate_tax1_error" class="error display-none" style="position:inherit;top:0;font-size: 15px;" for="tax1">Entered Tax is invalid</label>
								</div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('tax')?> 2</label>
							<div class="col-lg-4">
								<div class="input-group">
									<span class="input-group-addon">%</span>
									<input class="form-control money" type="text" value="<?=$est->tax2?>" id="estimate_edit_tax2" name="tax">
								</div>
								<div class="row">
								<div class="col-md-12">
								<label id="edit_estimate_tax2_error" class="error display-none" style="position:inherit;top:0;font-size: 15px;" for="tax2">Entered Tax is invalid</label>
								</div>
								</div>
							</div>
						</div>
						<!-- Start discount fields -->
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('discount')?> </label>
							<div class="col-lg-4">
								<div class="input-group">
									<span class="input-group-addon">%</span>
									<input class="form-control money" type="text" value="<?=$est->discount?>" id="estimate_edit_discount" name="discount">
								</div>
								<div class="row">
								<div class="col-md-12">
								<label id="edit_estimate_discount_error" class="error display-none" style="position:inherit;top:0;font-size: 15px;" for="discount">Entered Discount is invalid</label>
								</div>
								</div>
							</div>
						</div>
						<!-- End discount Fields -->

						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('currency')?> </label>
							<div class="col-lg-4">
								<select name="currency" class="form-control">
									<option value="<?=$est->currency?>"><?=lang('use_current')?> - <?=$est->currency?></option>
									<?php foreach (App::currencies() as $currency) { ?>
									<option value="<?=$currency->code?>"><?=$currency->name?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?=lang('notes')?> <span class="text-danger">*</span></label>
							<div class="col-lg-9">
								<textarea name="notes" class="form-control foeditor-estimate-cnote"><?=$est->notes?></textarea>
								<div class="row">
									<div class="col-md-6">
									<label id="estimates_notes_error" class="error display-none" style="position:inherit;top:0;font-size:15px;" for="notes">Notes field must not empty</label>
									</div>
							</div>
							</div>
							
						</div>
						<div class="text-center">
							<button type="button" class="btn btn-primary" onClick="javascript:history.go(-1)">Close</button>
							<button type="submit" class="btn btn-primary m-l-5" id="editEstimate"> <?=lang('save_changes')?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- End create invoice -->
</div>
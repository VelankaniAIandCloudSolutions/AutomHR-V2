<?php $inf = Expense::view_by_id($id); ?>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('edit_expense')?></h4>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id' => 'editExpenseForm'); echo form_open_multipart(base_url().'expenses/edit',$attributes); ?>
			<input type="hidden" name="expense" value="<?=$inf->id?>">
			<div class="modal-body">
          		<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('amount')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control" value="<?=$inf->amount?>" name="amount">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('notes')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<textarea class="form-control ta" name="notes"><?=$inf->notes?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('project')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<select name="project" class="form-control m-b" id="selected_project">
							<option value="<?=$inf->project?>">
							<?php if($inf->project > 0 || $inf->project == NULL){
									echo Project::by_id($inf->project)->project_title;
								}else{ echo 'N/A'; }
							?>
							</option>
							<?php foreach ($projects as $key => $p) {
								if($p->project_id != $inf->project){ ?>
							<option value="<?=$p->project_id?>"><?=$p->project_title?></option>
							<?php } ?>
							<?php } ?>
							<option value="NULL">None</option>
						</select>
					</div>
				</div>
				<div id="client_select" style="display:none">
					<div class="form-group">
						<label class="col-lg-4 control-label"><?=lang('client')?></label>
						<div class="col-lg-8">
							<select name="client" class="form-control m-b">
								<option value="<?=$inf->client?>"><?php echo lang('use_current')?></option>
								<?php foreach (Client::get_all_clients() as $key => $c) { ?>
								<option value="<?=$c->co_id?>"><?=$c->company_name?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('expense_date')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input class="datepicker-input form-control" type="text" value="<?=strftime(config_item('date_format'),strtotime($inf->expense_date))?>" name="expense_date" data-date-format="<?=config_item('date_picker_format');?>" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('category')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<select name="category" class="form-control m-b">
							<option value="<?=$inf->category?>"><?php echo App::get_category_by_id($inf->category);?> </option>
							<?php foreach ($categories as $key => $cat) {
							if($cat->id != $inf->category){ ?>
							<option value="<?=$cat->id?>"><?=$cat->cat_name?></option>
							<?php } ?>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('billable')?></label>
					<div class="col-lg-8">
						<label class="switch">
							<input type="checkbox" name="billable" <?=($inf->billable == '1') ? 'checked="checked"': '';?>>
							<span></span>
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('show_to_client')?></label>
					<div class="col-lg-8">
						<label class="switch">
							<input type="checkbox" name="show_client" <?=($inf->show_client == 'Yes') ? 'checked="checked"':'';?>>
							<span></span>
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('invoiced')?></label>
					<div class="col-lg-8">
						<label class="switch">
							<input type="checkbox" name="invoiced" <?=($inf->invoiced == '1') ? 'checked="checked"': '';?>>
							<span></span>
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('attach_file')?></label>
					<div class="col-lg-8">
						<label class="btn btn-default btn-choose">Choose File</label>
						<input type="file" class="form-control" data-buttonText="<?=lang('choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline" name="receipt">
					</div>
				</div>
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-success" id="accountsEditExpense"><?=lang('save_changes')?></button>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
    $('.datepicker-input').datepicker({ language: locale});
</script>
<script type="text/javascript">
$('#selected_project').change(function() {
    if($("#selected_project").val()==="NULL"){
    	$("#client_select").show();
    }else{
    	$("#client_select").hide();
    }
});
</script>
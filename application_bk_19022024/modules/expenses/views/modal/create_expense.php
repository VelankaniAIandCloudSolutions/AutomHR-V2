
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('create_expense')?></h4>
			
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id' => 'createExpenseForm'); echo form_open_multipart(base_url().'expenses/create',$attributes); ?>
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
					<label class="col-lg-4 control-label"><?=lang('project')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<select name="project" class="form-control m-b" id="selected_project">
							<?php foreach ($projects as $key => $p) { ?>
							<option value="<?=$p->project_id?>" <?=($auto_select_project == $p->project_id) ? 'selected="selected"':''?>><?=$p->project_title?></option>
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
						<input class="datepicker-input form-control" type="text" value="<?=strftime(config_item('date_format'), time());?>" name="expense_date" data-date-format="<?=config_item('date_picker_format');?>" >
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('category')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<select name="category" class="form-control m-b">
							<?php foreach ($categories as $key => $cat) { ?>
							<option value="<?=$cat->id?>"><?=$cat->cat_name?></option>
							<?php } ?>
						</select>
					</div>
					<!-- <div class="col-lg-3">
						<a href="<?=base_url()?>settings/add_category" class="btn btn-success" data-toggle="ajaxModal" title="<?=lang('add_category')?>"><i class="fa fa-plus"></i> <?=lang('add_category')?></a>
					</div> -->
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('billable')?></label>
					<div class="col-lg-8">
						<label class="switch">
							<input type="checkbox" name="billable" checked="checked">
							<span></span>
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('show_to_client')?></label>
					<div class="col-lg-8">
						<label class="switch">
							<input type="checkbox" name="show_client" checked="checked">
							<span></span>
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('invoiced')?></label>
					<div class="col-lg-8">
						<label class="switch">
							<input type="checkbox" name="invoiced">
							<span></span>
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('attach_file')?></label>
					<div class="col-lg-8">
						<label class="btn btn-default btn-choose">Choose File</label>
						<input type="file" class="form-control" data-buttonText="<?=lang('choose_file')?>" data-icon="false" data-classButton="btn btn-default" data-classInput="form-control inline input-s" name="receipt">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label col-lg-4">Reports to</label>
					<div class="col-lg-8">													
						<select class="select2-option form-control"   style="width:260px" name="reports_to" id="reports_to"> 
							<optgroup>Select Report to</optgroup> 
							<optgroup label="Staff">
								<?php 

								$users = $this->db->where(array('role_id !='=>2,'role_id !='=>1,'activated'=>1,'banned'=>0,'id !=' => $this->session->userdata('user_id'))) -> get('users')->result();
								foreach ($users as $user): ?>
									<option value="<?=$user->id?>"  >
										<?=ucfirst(User::displayName($user->id))?>
									</option>
								<?php endforeach ?>
							</optgroup> 
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-lg-4">Default Expenses Approval</label>
					<div class="col-lg-8 approval-option">
						<label class="radio-inline">
							<input id="radio-single" value="seq-approver" name="default_expense_approval" type="radio">Sequence Approval (Chain) <sup> <span class="badge info-badge"><i class="fa fa-info" aria-hidden="true"></i></span></sup>
						</label>
						<label class="radio-inline" style="margin-left: 0px;">
							<input id="radio-multiple" value="sim-approver" name="default_expense_approval"type="radio">Simultaneous Approval <sup><span class="badge info-badge"><i class="fa fa-info" aria-hidden="true"></i></span></sup>
						</label>
					</div>
				</div>
				<div class="form-group row approver seq-approver">
					<label class="control-label col-lg-4">Expenses Approvers</label>
					
					<div class="col-lg-8 ">
						<label class="control-label m-b-10" style="padding-left:0">Approver 1</label>
						<div class="row">
							<div class="col-md-10">
								
								<select class="select2-option form-control"   style="width:260px" name="expense_approvers[]" id="offer_approvers"> 
							<optgroup>Select Approvers</optgroup> 
							<optgroup label="Staff">
								<?php 
						
								$users = $this->db->where(array('role_id !='=>2,'role_id !='=>1,'activated'=>1,'banned'=>0,'id !=' => $this->session->userdata('user_id'))) -> get('users')->result();
								foreach ($users as $user): ?>
									<option value="<?=$user->id?>"  >
										<?=ucfirst(User::displayName($user->id))?>
									</option>
								<?php endforeach ?>
							</optgroup> 
						</select>
							</div>
							
						</div>
						<div id="items1">
						</div>
					</div>
					<div class="row">
						<input type="hidden" id="count" value="1">
					<div class="col-sm-8 col-md-offset-4 m-t-10">
						<a id="add2" href="javascript:void(0)" class="add-more">+ Add Approver</a>
					</div>
					</div>
					</div>
					
					<div class="form-group row approver sim-approver">
						<label class="control-label col-lg-4">Expenses Approvers</label>
						<div class="col-lg-8 ">
							<label class="control-label" style="margin-bottom:10px;padding-left:0">Simultaneous Approval </label>
							<div class="row">
								<div class="col-md-10">
									<select class="select2-option form-control" id="select2_expense" multiple="multiple" style="width:260px" name="expense_approvers[]" > 
										<optgroup label="Staff">
											<?php 
											$users = $this->db->where(array('role_id !='=>2,'role_id !='=>1,'activated'=>1,'banned'=>0,'id !=' => $this->session->userdata('user_id'))) -> get('users')->result();
											foreach ($users as $user): ?>
												<option value="<?=$user->id?>">
													<?=ucfirst(User::displayName($user->id))?>
												</option>
											<?php endforeach ?>
										</optgroup> 
									</select>

								</div>
								
							</div>
						</div>
					</div>
					
					
					<div class="row m-t-20">
						<label class="control-label col-lg-4">Message to Approvers</label>
						<div class="col-lg-8 col-sm-offset-3">
							
							<div class="row">
								<div class="col-md-12">
									 <textarea class="form-control" rows="5" name="message_to_approvers" id="message_to_approvers"></textarea>
								</div>
							</div>
						</div>
					</div>


			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-success" id="accountsCreateExpense"><?=lang('save_changes')?></button>
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

$('#select2_expense').select2({dropdownParent: $('#ajaxModal')});

		$('#select2_expense').select2({
			minimumResultsForSearch: -1,
			width: '100%'
		});
	
	

</script>
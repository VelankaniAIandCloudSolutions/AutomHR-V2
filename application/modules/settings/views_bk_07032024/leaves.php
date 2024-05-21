<div class="p-0">
    <!-- Start Form -->
    <div class="col-lg-12 p-0">
		<div class="panel panel-table">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-6">
						<h3 class="panel-title m-t-6"><?='Leave Types'?></h3>
					</div>
					<div class="col-xs-6 text-right">
                    <a href="javascript:;" class="btn btn-sm btn-primary pull-right" 
                    onclick="$('#leave_type,#leave_type_tbl_id').val('');$('#leave_days').select2('val', '1');$('.l_type_save_btn').html('Save');$('.leave_type_add_form').show();">
                    	<i class="fa fa-plus"></i> <?='Add New Type';?>
                    </a>
					</div>
				</div>
			</div>
			<div class="panel-body"> 
				<div class="table-responsive">
				   <?php
					$leave_res = $this->db->query("select * from dgt_leave_types where status = 0")->result_array();
				   ?>
					<table id="table-holidays" class="table table-striped custom-table m-b-0">
						<thead>
							<tr>
								<th>No</th>
								<th>Type</th>
								<th>Days</th> 
								<th class="text-right">Action</th>  
							</tr>
						</thead>
						<tbody>
						<?php foreach($leave_res as $key => $vl){?>
							<tr>
								<td><?php echo $key+1; ?></td>
								<td><?php echo $vl['leave_type']; ?></td>
								<td><?php echo $vl['leave_days']; ?>  days</td>
								<td class="text-right"> 
									<a href="javascript:;" class="btn btn-success btn-xs leave_type_edit" data_type="<?=$vl['id'].'^'.$vl['leave_type'].'^'.$vl['leave_days']?>" title="<?=lang('edit')?>">
										<i class="fa fa-edit"></i> 
									</a>
									<a class="btn btn-danger btn-xs" title="Delete" data-toggle="ajaxModal" href="<?=base_url()?>settings/delete_leave_types/<?=$vl['id']?>" data-original-title="Delete">
										<i class="fa fa-trash-o"></i>
									</a>
								</td> 
							</tr>
							<?php } ?>
						</tbody>
					</table>
			   </div> 
			</div>
		</div>
		<?php
		$attributes = array('class' => 'bs-example form-horizontal','id'=>'addLeaveType');
		echo form_open_multipart('settings/leave_types', $attributes); ?>
			<div class="panel leave_type_add_form" style="display:none">
				<div class="panel-body">
					<div class="tab-pane fade in active" id="tab-english">
						<div class="form-group">
							<label class="col-lg-3 control-label"><?='Leave Type'?> <span class="text-danger">*</span></label>
							<div class="col-lg-5">
								<input type="text" name="leave_type" class="form-control" id="leave_type" value="">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"><?='Leave Days'?> <span class="text-danger">*</span></label>
							<div class="col-lg-5">
								<select class="select2-option form-control" style="width:210px;" id="leave_days" name="leave_days">
								<?php for($i = 1;$i <= 30; $i++ ){ ?>
									<option value="<?=$i?>"><?=$i.' Days'?></option>
								<?php } ?>       
								</select>
							</div>
						</div>  
						<div class="text-center m-t-30">
							<input type="hidden" name="leave_type_tbl_id" id="leave_type_tbl_id" value="">
							<button id="add_leave_type" class="btn btn-primary l_type_save_btn"><?='Save'?></button>
							<button type="button" class="btn btn-danger" onclick="$('.l_type_save_btn').html('Save');$('.leave_type_add_form').hide();"><?='Cancel'?></button>
						</div>
					</div>
				</div> 
			</div> 
		</form>
     </div>
    <!-- End Form -->
</div>
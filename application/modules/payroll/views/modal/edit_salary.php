<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button> 
			<h4 class="modal-title"> Edit Salary</h4>
		</div> 
		<div class="modal-body">
			<?php $attributes = array('class' => 'form-horizontal','id'=>'payrollSalaryEdit'); echo form_open(base_url().'payroll/save_salary',$attributes); ?>
				<div class="form-group">
					<label class="col-md-3 control-label"> New Salary Amount <span class="text-danger">*</span></label>
					<div class="col-md-6"> 
						<input type="hidden" name="salary_user_id" id="salary_user_id"  value="<?=$user_id?>"> 
						<input type="text" name="user_salary_amount" id="user_salary_amount" class="form-control"  value="" >
					</div>
					<div class="col-md-3"> 
						<button type="submit" class="btn btn-success btn-md" id="payroll_salary_edit"> Save New Salary </button>
					</div>
				</div> 
			</form>
			<hr>
			<h4 class="m-b-15"> Previous Salary Amounts </h4>
			<?php
			$previous_salary = $this->db->query("select * from dgt_salary where user_id = ".$user_id." order by id desc")->result_array();
			if(!empty($previous_salary)){
			foreach($previous_salary as $key => $slry){
			?> 
			<div class="row m-b-15">
				<div class="col-md-3"> Since &nbsp; <?php echo date('d-m-Y',strtotime($slry['date_created']))?> </div>
				<div class="col-md-3 slry_sw_<?=$slry['id']?>" style="display:none"> 
					<input type="text"  class="form-control user_salary_amnt_<?=$slry['id']?>"  value="<?php echo $slry['amount']; ?>"> 
				</div>
				<div class="col-md-3 slry_sw_<?=$slry['id']?>" style="display:none"> 
					<a href="javascript:;" class="btn btn-success" onClick="staff_salary_update(<?=$slry['id']?>);"> Update </a>
					<a href="javascript:;" class="btn btn-danger" onClick="$('.slry_sw_<?=$slry['id']?>').hide();$('.slry_cls_<?=$slry['id']?>').show();"> Close </a>
				</div>
				<div class="col-md-3 slry_cls_<?=$slry['id']?>"> 
					<?php echo $slry['amount']; ?> &nbsp; &nbsp; <a href="javascript:;" class="btn btn-warning" onClick="$('.slry_cls_<?=$slry['id']?>').hide();$('.slry_sw_<?=$slry['id']?>').show();$('.user_salary_amnt_<?=$slry['id']?>').focus();" > Edit </a> 
				</div>
			</div> 
			<?php }
			}else{?>
				<p style="color:red"> No Data Available </p>
			<?php } ?> 
  		</div>
		<div class="modal-footer">  
			<a href="#" class="btn btn-danger" data-dismiss="modal"> Close </a> 
		</div>
	</div>
</div>
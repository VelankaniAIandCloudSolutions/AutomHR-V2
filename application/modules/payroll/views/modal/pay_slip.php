<div class="modal-dialog" style="width:50%">

	<div class="modal-content">

		<div class="modal-header">

			<button type="button" class="close" data-dismiss="modal">&times;</button> 

			<h4 class="modal-title"> Create Pay Slip </h4>

		</div>

		<?php 



			$this->db->select('U.created');

			$this->db->from('users U');

			$this->db->where('U.id', $user_id);

			$employee_info = $this->db->get()->row();

			$joindate = $employee_info->created;

			$year  = date('Y',strtotime($joindate));

			$month = date('m',strtotime($joindate));



		 ?>

		<?php $attributes = array('id'=>'payrollPaySlip'); echo form_open(base_url().'payroll/view_salary_slip',$attributes); ?>

		<?php /*echo form_open(base_url().'fopdf/payslip_pdf');*/ ?>

			<div class="modal-body">

				<input type="hidden" name="payslip_user_id" value="<?=$user_id?>"> 

				<div class="row"> 

					<div class="col-md-6"> 

						<div class="form-group"> 

							<label>Year <span class="text-danger">*</span></label>

							<select class="select2-option form-control" style="width:100%;"  name="payslip_year" id="payslip_year" onchange="staff_salary_detail(<?=$user_id?>);"> 

								<option value="" selected="selected" disabled> -- Select Year -- </option>

								<?php for($i = $year ;$i <= date('Y'); $i++ ){ ?>

								<option value="<?=$i?>" <?php if($i == date('Y')){ echo "selected";}?>><?=$i?></option>

								<?php } ?>       

							</select>

						</div>

					</div>

					<div class="col-md-6"> 

						<div class="form-group"> 

							<label>Month <span class="text-danger">*</span></label>

							<?php

							$mons = array(1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 

							8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");

							?>

							<select class="select2-option form-control" style="width:100%; padding:5px"  name="payslip_month" id="payslip_month" required onchange="staff_salary_detail(<?=$user_id?>);"> 

								<option value="" selected="selected" disabled> -- Select month -- </option>

						<?php 

						if($year == date('Y')){

						foreach($mons as $key => $vl){ 

							if($key >= $month && $key<= (date('m') + 1)){ ?>

						<option value="<?=$key?>" <?php if($key == date('m')){ echo "selected";}?>><?=$vl?></option>

						<?php }

							}

						 }else{ 

					foreach($mons as $key => $vl){ ?>

					<option value="<?=$key?>" <?php if($key == date('m')){ echo "selected";}?>><?=$vl?></option>

					<?php 

							

							}

						} ?>       

							</select>

						</div>

					</div>

				</div>

				<hr>   

				<div class="row"> 

					<div class="col-md-6"> 

						<h4 class="text-primary">Earnigns</h4>

						<div class="form-group">

							<label> Basic Pay  </label>

							<?php

							$basic =  '';

							$year = date('Y');

							$month = date('m');

							 $all_statutory = $this->db->get_where('bank_statutory',array('user_id'=>$user_id))->row_array(); 

							 $addtional_ar = json_decode($all_statutory['pf_addtional'],TRUE);
							 $deduction_ar = json_decode($all_statutory['pf_deduction'],TRUE);
											
											// foreach ($addtional_ar as $add_ar) {
											


							?> 

							<input type="text" name="addtion||basic pay" id="basicpay"  class="form-control"  value="<?php echo $all_statutory['salary']; ?>" readonly="readonly">

						</div>

						<?php
						if(is_array($addtional_ar)){
							foreach ($addtional_ar as $key => $value) {

						?>

						<div class="form-group">

							<label><?php echo $value['addtion_name']; ?> </label>

							<input type="text" name="addtion||<?php echo $value['addtion_name']; ?>" id="<?php echo $value['addtion_name']; ?>" class="form-control"  value="<?php echo $value['unit_amount']; ?>" readonly="readonly">

						</div>
					<?php } } ?>
						 

					</div>  

					<div class="col-md-6">

						<h4 class="text-primary"> Deductions </h4>

						
						<?php $bank_details = $this->db->get_where('bank_statutory',array('user_id'=>$user_id))->row_array(); 

						$pf_details = json_decode($bank_details['bank_statutory'],TRUE);
						if($pf_details['pf_contribution'] == 'yes')
						{
							$pf_amount = $pf_details['pf_total_rate'];
						}else{
							$pf_amount = '';
						}

						if($pf_details['esi_contribution'] == 'yes')
						{
							$esi_amount = $pf_details['esi_total_rate'];
						}else{
							$esi_amount = '';
						}
						?>
						<div class="form-group">

							<label> ESI </label>

							<!-- <input type="text" name="payslip_ded_esi" id="payslip_ded_esi" class="form-control"  value="<?php //echo $ded_esi; ?>" > -->
							<input type="text" name="deduction||ESI" id="payslip_ded_esi" class="form-control"  readonly="readonly"  value="<?php echo $esi_amount; ?>" >

						</div>

						<div class="form-group">

							<label>PF</label>

							<!-- <input type="text" name="payslip_ded_pf" id="payslip_ded_pf" class="form-control"  value="<?php //echo $ded_pf; ?>" > -->
							<input type="text" name="deduction||PF" id="payslip_ded_pf" class="form-control"  readonly="readonly"  value="<?php echo $pf_amount; ?>" >

						</div>

						<div class="form-group">

							<?php $this->db->select_sum('leave_days');
							$total_leaves =  $this->db->get_where('user_leaves',array('user_id'=>$user_id,'status'=>1))->row_array();
							$lop = ($total_leaves['leave_days'] - 12);
							if($lop > 0)
							{
								$lop_leaves = $lop;
							}else{
								$lop_leaves = '';
							}

							$total_salary = $all_statutory['salary'];
							$one_day = ($total_salary / 22);
							$total_lop = ($one_day * $lop_leaves);

							?>

							<!-- <label>Leave <span style="color:red;">(<?php //echo $lop_leaves; ?>)</span></label> -->
							<label>Leave </label>
							<input type="text" name="deduction||leave" id="payslip_ded_leave" class="form-control"  readonly="readonly" value="<?php echo round($total_lop); ?>" >

						</div>


						<?php
							
							if(is_array($deduction_ar)){
							foreach ($deduction_ar as $key => $values) {

						?>

						<div class="form-group">

							<label><?php echo $values['model_name']; ?> </label>

							<input type="text" name="deduction||<?php echo $values['model_name']; ?>" id="deduction_<?php echo $values['model_name']; ?>" class="form-control"  value="<?php echo $values['unit_amount']; ?>" readonly="readonly">

						</div>
					<?php } } ?>

						

					</div>

				</div>

			</div>

			<div class="modal-footer text-center">

				<button type="submit" class="btn btn-success" id="payroll_create_payslip"> Create Pay Slip </button>

				<a href="#" class="btn btn-danger" data-dismiss="modal"> Close </a>

			</div>

		</form>

	</div>

</div>
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

							<label> Basic & DA (<?php echo $da_percentage; ?>%)  </label>

							<?php

							$basic = $da = $hra = $conveyance = $allowance = $medical_allowance = $others = $ded_tds = $ded_esi = $ded_pf = $ded_prof = $ded_leave = $ded_welfare = $ded_fund = $ded_others = '';

							$year = date('Y');

							$month = date('m');

							$this->db->where('user_id', $user_id);

							$this->db->where('p_year', $year);

							$this->db->where('p_month', $month);

							$details = $this->db->get('payslip')->row();



							if(!empty($details)){



								$details = json_decode($details->payslip_details,TRUE);

								$basic = $details['payslip_basic'];

								$da = $details['payslip_da'];

								$hra = $details['payslip_hra'];

								$conveyance = $details['payslip_conveyance'];

								$allowance = $details['payslip_allowance'];

								$medical_allowance	= $details['payslip_medical_allowance'];

								$others	= $details['payslip_others'];

								$ded_tds	= $details['payslip_ded_tds'];

								$ded_esi	= $details['payslip_ded_esi'];

								$ded_pf	= $details['payslip_ded_pf'];

								$ded_prof	= $details['payslip_ded_prof'];

								$ded_leave	= $details['payslip_ded_leave'];

								$ded_welfare	= $details['payslip_ded_welfare'];

								$ded_fund	= $details['payslip_ded_fund'];

								$ded_others	= $details['payslip_ded_others'];







							}else{

								$curr_slry_res = $this->db->query("SELECT amount from dgt_salary where user_id = ".$user_id." order by id desc limit 1")->result_array();

							if(!empty($curr_slry_res)){

							$da     = ($da_percentage*$curr_slry_res[0]['amount']/100);

							$hra    = ($hra_percentage*$curr_slry_res[0]['amount']/100);

							// $basic  = ($curr_slry_res[0]['amount']-($da+$hra));
							$basic  = $da;

							$balance = $curr_slry_res[0]['amount'] - ($da + $hra);

							}	

							}

							

							?> 

							<input type="text" name="payslip_basic" id="payslip_basic"  class="form-control"  value="<?=$basic?>" readonly="readonly">

						</div>

						<!-- <div class="form-group">

							<label> DA(<?php echo $da_percentage; ?>%) </label>

							<input type="text" name="payslip_da" id="payslip_da" class="form-control"  value="<?=$da?>" readonly="readonly">

						</div> -->

						<div class="form-group">

							<label>HRA(<?php echo $hra_percentage; ?>%) </label>

							<input type="text" name="payslip_hra" id="payslip_hra" class="form-control"  value="<?=$hra?>" readonly="readonly">

						</div>

						<div class="form-group">

							<label> Conveyance </label>

							<input type="text" name="payslip_conveyance" id="payslip_conveyance" class="form-control"  value="<?php echo $conveyance; ?>" >

						</div>

						<div class="form-group">

							<label> Allowance </label>

							<input type="text" name="payslip_allowance" id="payslip_allowance" class="form-control"  value="<?php echo $allowance; ?>" >

						</div>

						<div class="form-group">

							<label> Medical  Allowance </label>

							<input type="text" name="payslip_medical_allowance" id="payslip_medical_allowance" class="form-control"  value="<?php echo $medical_allowance; ?>" >

						</div>

						<div class="form-group">

							<label> Others </label>

							<input type="text" name="payslip_others" id="payslip_others" class="form-control"  value="<?php echo $balance; ?>" >

						</div>  

					</div>  

					<div class="col-md-6">

						<h4 class="text-primary"> Deductions </h4>

						<div class="form-group">

							<label> TDS </label>

							<input type="text" name="payslip_ded_tds" id="payslip_ded_tds" class="form-control"  value="<?php echo $ded_tds; ?>" >

						</div> 

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
							<input type="text" name="payslip_ded_esi" id="payslip_ded_esi" class="form-control"  value="<?php echo $esi_amount; ?>" >

						</div>

						<div class="form-group">

							<label>PF</label>

							<!-- <input type="text" name="payslip_ded_pf" id="payslip_ded_pf" class="form-control"  value="<?php //echo $ded_pf; ?>" > -->
							<input type="text" name="payslip_ded_pf" id="payslip_ded_pf" class="form-control"  value="<?php echo $pf_amount; ?>" >

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

							$total_salary = ($basic + $hra + $balance);
							$one_day = ($total_salary / 22);
							$total_lop = ($one_day * $lop_leaves);

							?>

							<!-- <label>Leave <span style="color:red;">(<?php //echo $lop_leaves; ?>)</span></label> -->
							<label>Leave </label>
							<input type="text" name="payslip_ded_leave" id="payslip_ded_leave" class="form-control"  value="<?php echo round($total_lop); ?>" >

						</div>

						<div class="form-group">

							<label>Prof. Tax </label>

							<input type="text" name="payslip_ded_prof" id="payslip_ded_prof" class="form-control"  value="<?php echo $ded_prof; ?>" >

						</div>

						<div class="form-group">

							<label>Overtime</label>

							<input type="text" name="payslip_ded_welfare" id="payslip_ded_welfare" class="form-control"  value="<?php echo $ded_welfare; ?>" >

						</div>

						<div class="form-group">

							<label> Fund </label>

							<input type="text" name="payslip_ded_fund" id="payslip_ded_fund" class="form-control"  value="<?php echo $ded_fund; ?>" >

						</div>

						<div class="form-group">

							<label> Others  </label>

							<input type="text" name="payslip_ded_others" id="payslip_ded_others" class="form-control"  value="<?php echo $ded_others; ?>" >

						</div>

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
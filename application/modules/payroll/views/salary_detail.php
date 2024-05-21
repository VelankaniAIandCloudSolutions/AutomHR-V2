		 <div class="content container-fluid">

					<div class="row">

						<div class="col-xs-8">

							<h4 class="page-title">Payslip</h4>

						</div>

						<div class="col-sm-4 text-right m-b-0">

							<div class="btn-group btn-group-sm">

								

								<button class="btn btn-default" onclick="document.querySelector('form').submit()">PDF</button>

								<a href="<?php echo base_url(); ?>payroll" class="btn btn-default">Back</a>

								<!-- <button class="btn btn-default"><i class="fa fa-print fa-lg"></i> Print</button> -->

							</div>

						</div>

					</div>

					<div class="row">

						<div class="col-md-12">

							<div class="card-box">

								<?php 

									$array = array();

	

	$array['user_id'] = $pay_slip_details['payslip_user_id'];

	$array['p_year'] = $pay_slip_details['payslip_year'];

	$array['p_month'] = $pay_slip_details['payslip_month'];



	$this->db->where($array);

	$payslip_count = $this->db->count_all_results('payslip');



	if($payslip_count == 0){

		$array['payslip_details'] = json_encode($pay_slip_details);

		$this->db->insert('payslip', $array);

		$id = $this->db->insert_id();



	}else{

	

		$array1['payslip_details'] = json_encode($pay_slip_details);

		$this->db->where($array);

		$this->db->update('payslip', $array1);

	}

		$this->db->where($array);

		$details = $this->db->get('payslip')->row_array();

		$pay_slip_details = json_decode($details['payslip_details'],TRUE);





								$employee_id = $pay_slip_details['payslip_user_id'];

								$this->db->select('U.id,AD.fullname as username,U.email,U.created,C.company_name, CONCAT(C.company_address,", ",C.city,", ",C.state,", ",C.country) as address');

								$this->db->from('users U');

								$this->db->join('account_details AD', 'AD.user_id = U.id', 'left');

								$this->db->join('companies C', 'C.co_id = AD.company', 'left');

								$this->db->where('U.id', $employee_id);

								$employee_info = $this->db->get()->row();

								

							 

								$earnings = 0;

								$deductions = 0;

								 ?>
								 <?php 

											$salary_moth = $pay_slip_details['payslip_year'].'-'.$pay_slip_details['payslip_month'].'-1';

											?>

								<h4 class="payslip-title" style="position: absolute;margin-left:-14px;">Payslip for the month of <?php echo date("F", strtotime($salary_moth)); ?> <?php echo $pay_slip_details['payslip_year']; ?></h4>

								<div class="row m-t-20">

									<div class="col-md-8 m-b-20">

										<img src="assets/img/logo2.png" class="m-b-20" alt="" style="width: 100px;">

										<ul class="list-unstyled m-b-0">

											<li><?php echo $employee_info->company_name; ?></li>

											<li><?php echo $employee_info->address; ?></li>

											

										</ul>

									</div>

									<div class="col-md-4 m-b-20">

									<div class="invoice-details pull-right">

										<h3 class="text-uppercase">Payslip #<?php echo $details['id']; ?></h3>

										<ul class="list-unstyled">



											<li>Salary Month: <?php 

											$salary_moth = $pay_slip_details['payslip_year'].'-'.$pay_slip_details['payslip_month'].'-1';

											?><span><?php echo date("F", strtotime($salary_moth)); ?>, <?php echo $pay_slip_details['payslip_year']; ?></span></li>

										</ul>

									</div>

									</div>

									
								</div>

								<div class="row">

								<?php if(!empty($pay_slip_details)){ 

									echo form_open(base_url().'fopdf/payslip_pdf'); 

									$pay_slip_details['payslipid'] = $details['id'];

								?>

							<?php foreach ($pay_slip_details as $key => $value): ?>

									<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">	

							<?php endforeach ?>

							</form>						

							<?php }  ?>

									<div class="col-md-6 m-b-20">

										<ul class="list-unstyled">

											<li><h5 class="m-b-0"><strong><?php echo $employee_info->username; ?></strong></h5></li>

											<!-- <li><span>Web Designer</span></li> -->

											<li>Employee ID: FT-00<?php echo $employee_info->id; ?></li>

											<li>Joining Date: <?php echo date('d M Y',strtotime($employee_info->created)); ?></li>

										</ul>

									</div>

									

								</div>

								<div class="row">

									<div class="col-md-6 col-sm-12">

										<div class="panel panel-default">
										    <div class="panel-heading"><h4 class="m-b-0 m-r-5"><strong>Earnings</strong></h4></div>
										    <div class="panel-body panel-body-earnings m-b-0">
											<table class="table">

											<tbody>

												<?php
												$i=1;
												foreach ($pay_slip_details as $key => $value) {

												  if(strpos($key, 'addtion||')!== false){  

												  	$earnings += $value;
												
												 ?>

												<tr>

													<td <?php if($i==1) { echo 'style="border:none;"'; } ?>><strong><?php echo ucwords(str_replace(array('addtion||','_'), ' ', $key));?></strong> <span class="pull-right">$<?php echo $value; ?></span></td>

												</tr>

											<?php $i++; } } ?>

												
											</tbody>

											</table>
											</div>
											<div class="panel-footer panel-footer-earnings">
											<table class="table">
											<tbody>
											<tr>

											<td style="border:none;"><strong>Total Earnings</strong> <span class="pull-right"><strong>$<?php echo $earnings; ?></strong></span></td>

											</tr>

											</tbody>

											</table>
											</div>
										

										</div>

									</div>

									<div class="col-md-6 col-sm-12">

										<div class="panel panel-default">
										    <div class="panel-heading"><h4 class="m-b-0 m-r-5"><strong>Deductions</strong></h4></div>
										    <div class="panel-body panel-body-deductions">
											<table class="table">

											<tbody>

												<?php
												$j=1;
												foreach ($pay_slip_details as $keys => $values) {

												  if(strpos($keys, 'deduction||')!== false){  

												  	$deductions += $values;
												
												 ?>

												<tr>

													<td <?php if($j==1) { echo 'style="border:none;"'; } ?>><strong><?php echo ucwords(str_replace(array('deduction||','_'), ' ', $keys));?></strong> <span class="pull-right">$<?php echo $values; ?></span></td>

												</tr>

											<?php $i++; } } ?>

												
											</tbody>
											</table>
												
											</div>
											<div class="panel-footer panel-footer-deductions">
											<table class="table">
											<tbody>
											<tr>

											<td style="border:none;"><strong>Total Deductions</strong> <span class="pull-right"><strong>$<?php echo $deductions;?></strong></span></td>

											</tr>

											</tbody>

											</table>
											</div>
										</div>
										</div>

											
									</div>

									<div class="row">
									<div class="col-md-12">

										<label type="button" class="label-netsalary pull-right"><strong>Net Salary : <span class="m-l-10">$<?php echo ($earnings - $deductions); ?></span></strong> <!-- (Fifty nine thousand six hundred and ninety eight only.) --></label>

									</div>
									</div>
								</div>

							</div>

						</div>

					</div>


<!-- <script type="text/javascript">

	printDiv('my_pay_slip')

function printDiv(divName) {

     var printContents = document.getElementById(divName).innerHTML;

     var originalContents = document.body.innerHTML;



     document.body.innerHTML = printContents;



     window.print();



     document.body.innerHTML = originalContents;

}

</script> -->
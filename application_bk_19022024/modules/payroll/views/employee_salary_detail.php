		 <div class="content container-fluid">

					<div class="row">

						<div class="col-xs-8">

							<h4 class="page-title">Payslip</h4>

						</div>

						<div class="col-sm-4 text-right m-b-0">

							<div class="btn-group btn-group-sm">

	
			<button class="btn btn-default" onclick="document.getElementById('pdf_employee').click()">PDF</button>

			<!--<a href="<?php echo base_url(); ?>payroll" class="btn btn-default">Back</a> -->

			

							</div>

						</div>

					</div>

					<div class="row">

						<div class="col-md-12">

							<div class="card-box">

				<?php 

								

						//$user_id  = $this->session->userdata('user_id');



						$array = array();



						

						$this->db->select('U.created');

						$this->db->from('users U');

						$this->db->where('U.id', $user_id);

						$employee_info = $this->db->get()->row();
						
						$this->db->select('doj,emp_code,branch_id');

						$this->db->from('account_details');

						$this->db->where('user_id', $user_id);

						$account_details = $this->db->get()->row();

						$this->db->select('branch_id,branch_name');

						$this->db->from('dgt_branches');

						$this->db->where('branch_id', $account_details->branch_id);

						$branch_details = $this->db->get()->row();



						$joindate = $account_details->doj;

						$year  = date('Y',strtotime($joindate));

						$month = date('m',strtotime($joindate));



						?>

					<div class="row"> 

						<form action="" method="post">

					<div class="col-md-4"> 

						<div class="form-group"> 

							<label>Year <span class="text-danger">*</span></label>

							<select class="select2-option form-control" style="width:100%;"  name="payslip_year" id="payslip_year" required onchange="staff_salary_detail(<?=$user_id?>);"> 

								<option value=""> -- Select Year -- </option>

								<?php for($i = $year ;$i <= date('Y'); $i++ ){ ?>

								<option value="<?=$i?>" <?php if($i == date('Y')){ echo "selected";}?>><?=$i?></option>

								<?php } ?>       

							</select>

						</div>

					</div>

					<div class="col-md-4"> 

						<div class="form-group"> 

							<label>Month <span class="text-danger">*</span></label>

							<?php

							$mons = array(1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 

							8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");

							?>

							<select class="select2-option form-control" style="width:100%; padding:5px"  name="payslip_month" id="payslip_month" required onchange="staff_salary_detail(<?=$user_id?>);"> 

								<option value=""> -- Select month -- </option>

						<?php 

						if($year == date('Y')){

						foreach($mons as $key => $vl){ 

							if($key >= $month && $key<=date('m')){ ?>

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

					<div class="col-md-4"> 

						<div class="form-group"> 

								<br>

							<input type="submit" name="submit" value="Get Pay Slip" class="btn btn-primary">

						</div>

					</div>	

					</form>

				</div>

				<hr>  

						<?php 



				if($this->input->post()){

					

					$array['user_id'] = $user_id;

					$array['p_year']  = $this->input->post('payslip_year');

					$array['p_month'] = $this->input->post('payslip_month');	

					

					$this->db->where($array);

					$details = $this->db->get('payslip')->row_array();



					if(!empty($details)){

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

									<div class="col-md-6 m-b-20">

										<img src="assets/img/logo2.png" class="m-b-20" alt="" style="width: 100px;">

										<ul class="list-unstyled m-b-0">

											<li><?php echo $employee_info->company_name; ?></li>

											<li><?php echo $employee_info->address; ?></li>

											

										</ul>

									</div>

									<div class="col-md-6 m-b-20">

										<div class="invoice-details">

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

									<div class="col-lg-12 m-b-20">

										<ul class="list-unstyled">

											<li><h5 class="m-b-0"><strong><?php echo $employee_info->username; ?></strong></h5></li>

											<!-- <li><span>Web Designer</span></li> -->

											<li>Employee ID:<?php echo $account_details->emp_code; ?></li>
											<li>Joining Date: <?php echo date('d M Y',strtotime($account_details->doj)); ?></li>
											<li>Entity: <?php echo $branch_details->branch_name; ?></li>

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

													<td <?php if($i==1) { echo 'style="border:none;"'; } ?>><strong><?php echo ucwords(str_replace(array('addtion||','_'), ' ', $key));?></strong> <span class="pull-right">₹<?php echo $value; ?></span></td>

												</tr>

											<?php $i++; } } ?>

												
											</tbody>

											</table>
											</div>
											<div class="panel-footer panel-footer-earnings" style="margin-top:18%">
											<table class="table">
											<tbody>
											<tr>

											<td style="border:none;"><strong>Total Earnings</strong> <span class="pull-right"><strong>₹<?php echo $earnings; ?></strong></span></td>

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

												  if(strpos($keys, 'deduction||')!== false && !empty($values)){  

												  	$deductions += $values;
												
												 ?>

												<tr>

													<td <?php if($j==1) { echo 'style="border:none;"'; } ?>><strong><?php echo ucwords(str_replace(array('deduction||','_'), ' ', $keys));?></strong> <span class="pull-right">₹<?php echo $values; ?></span></td>

												</tr>

											<?php $i++; } } ?>

												
											</tbody>
											</table>
												
											</div>
											<div class="panel-footer panel-footer-deductions">
											<table class="table">
											<tbody>
											<tr>

											<td style="border:none;"><strong>Total Deductions</strong> <span class="pull-right"><strong>₹<?php echo $deductions;?></strong></span></td>

											</tr>

											</tbody>

											</table>
											</div>
										</div>
										</div>

									

								</div>

								<div class="row">
									<div class="col-md-12">

										<label type="button" class="label-netsalary pull-right"><strong>Net Salary : <span class="m-l-10">₹<?php echo ($earnings - $deductions); ?></span></strong> <!-- (Fifty nine thousand six hundred and ninety eight only.) --></label>

									</div>
									</div>


						<?php if(!empty($pay_slip_details)){ 

						echo form_open(base_url().'fopdf/payslip_pdf'); 

						$pay_slip_details['payslipid'] = $details['id'];

						?>

						<?php foreach ($pay_slip_details as $key => $value): ?>

						<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">	

						<?php endforeach ?>

						<input type="submit" name="submit" value="submit" style="display: none;" id="pdf_employee">

						</form>						

<?php }  ?>



									<?php }else{ ?>

									<p class="alert alert-danger">No details were found</p>

									<?php }  ?>

								<?php }  ?>

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
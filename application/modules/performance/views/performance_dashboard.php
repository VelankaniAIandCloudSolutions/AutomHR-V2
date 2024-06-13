
<div class="content container-fluid">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="page-title">Performance Dashboard</h4>
		</div>
		<div class="col-md-6">
			<form name="performance_entity" id='performance_entity' action="<?php echo base_url('performance/performance_dashboard');?>" method="post">
				<div class="row">
					<div class="col-md-8">
						<lable class='lable'>Branch Name</lable>
						<select name="branch_id" id="branch_id" class="form-control">
							
							<?php 
							if(!empty($branch_list))
							{
								foreach($branch_list as $branch_list_val)
								{ ?>
									<option value="<?php echo $branch_list_val['branch_id']?>" <?php if($_POST['branch_id'] == $branch_list_val['branch_id']) { echo "selected";} ?> ><?php echo $branch_list_val['branch_name']?></option>
								<?php }
							}
							?>
						</select>
						
					</div>
					<div class="col-md-4" style="padding-top: 25px;">
						<input type="submit" name="Filter" class="btn btn-primary">
						<input type="button" value="Export" name="Export" id="Export" class="btn btn-primary" onclick="downloadTableData(); return false;">
					</div>
				</div>
				
			</form>
		</div>
	</div>

	<!-- <div class="row">
		<div class="col-sm-4">
			<div class="card-box text-center">
				<h4 class="card-title">Completed Performance Review</h4>
				<span class="perform-icon bg-success-light"><?php echo $completed_performance; ?></span>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="card-box text-center">
				<h4 class="card-title">Outstanding Reviews</h4>
				<span class="perform-icon bg-danger-light"><?php echo $outstanding_performance;?></span>
			</div>
		</div>
	</div> -->

	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table custom-table m-b-0" id='myTable'>
					<thead>
						<tr>
							<th style="width:30%;">Employee Name</th>
							<th style="width:30%;">Manager Name</th>
							<!-- <th>Self Review</th>
							<th>Peer Reviews</th>
							<th>Your Reviews</th>
							<th>Goals</th> -->
							<th style="width:20%;">Smart Goal (Score A = W * R) </th>
							<th style="width:20%;">Competency Goal (Score B = W * R)</th>
							<th style="width:20%;">Overall Score ((A+B)/2 )</th>

						</tr>
					</thead>
					<tbody>
						<?php 
						$rating_count= count($performances_360);
						$peer_rating = 0;
						foreach ($performances_360 as $value)
						{
							$employee_details = $this->db->get_where('users',array('id'=>$value['user_id']))->row_array();
							$designation = $this->db->get_where('designation',array('id'=>$employee_details['designation_id']))->row_array();
							$account_details = $this->db->get_where('account_details',array('user_id'=>$value['user_id']))->row_array();
							
							$teamlead_name = '';

							if($employee_details['teamlead_id'] > 0)
							{
								$team_lead_details = $this->db->get_where('account_details',array('user_id'=>$employee_details['teamlead_id']))->row_array();

								if(!empty($team_lead_details))
								{
									$teamlead_name = $team_lead_details['fullname'].' ('.$team_lead_details['emp_code'].')';
								}
							}?>
							<tr>
								<td>
									<a href="<?=base_url()?>employees/profile_view/<?php echo $value['user_id'];?>" class="avatar"><?php echo substr($account_details['fullname'],0,1);?></a>
									<h2><a href="<?=base_url()?>employees/profile_view/<?php echo $value['user_id'];?>"><?php echo $account_details['fullname'].' ('.$account_details['emp_code'].')'?> <span><?php echo $designation['designation']?></span></a></h2>
								</td>

								<td>
									<a href="<?=base_url()?>employees/profile_view/<?php echo $employee_details['teamlead_id'];?>" class="avatar"><?php echo substr($teamlead_name['fullname'],0,1);?></a>
									<h2><a href="<?=base_url()?>employees/profile_view/<?php echo $employee_details['teamlead_id'];?>"><?php echo $teamlead_name; ?> </a></h2>
								</td>

								<td>
									<?php 

									$smartgoal_details = $goal= array();
									$smartgoal_details = $this->db->select('*')
									->from('smartgoal')
									->where("user_id",$value['user_id'])->order_by("id",'asc')->limit(1)
									->get()->row_array();
									$goal = json_decode($smartgoal_details['goals']);
									$smart_goal_total_score = 0;
									foreach ($goal as $key => $goal) 
									{ 
										$score =0;
										$score = $goal->score;

										$smart_goal_total_score += $score;
									}

									echo round($smart_goal_total_score, 2);
									?>
								</td>

								<td>
									<?php 
									$performance_competency_details = array();
									$performance_competency_details = $this->db->select('*')
									->from('dgt_performance_competency')
									->where("status","0")
									->get()->result_array();

									$competency_total_score = $temp_competency_total_score = 0;

									if(!empty($performance_competency_details))
									{
										foreach($performance_competency_details as $value1)
										{
											$competency_details = array();
											$competency_details = $this->db->select('rating,competencies')
											->from('dgt_competencies')
											->where("competencies",$value1['id'])
											->where("user_id",$value['user_id'])->order_by("id",'desc')->limit(1)
											->get()->row_array();

											if(!empty($competency_details))
											{
													$manager_rating = 0;
													$manager_rating = $competency_details['rating'];
													$temp_competency_total_score += ($manager_rating * $value1['weighatage']) /100 ;
											}
										}
									}

									$competency_total_score = round($temp_competency_total_score, 2); 
									echo $competency_total_score;
									?>
								</td>
								<td>
									<?php 
										$score_a = $score_b= 0;
										$score_a = round($smart_goal_total_score, 2);
										$score_b = $competency_total_score;

										$overall_score = 0;
										$overall_score = ($score_a + $score_b)/2; 
										echo $overall_score;
									?>
								</td>
							</tr>
							<?php 

						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>


<!-- <?php 

						$rating_count= count($performances_360);
						$peer_rating = 0;

						foreach ($performances_360 as $value) { 

							$peer_rating = $value['peer_ratings']/2;

							$employee_details = $this->db->get_where('users',array('id'=>$value['user_id']))->row_array();
							$designation = $this->db->get_where('designation',array('id'=>$employee_details['designation_id']))->row_array();
							$account_details = $this->db->get_where('account_details',array('user_id'=>$value['user_id']))->row_array();
							
							$teamlead_name = '';

							if($employee_details['teamlead_id'] > 0)
							{
								$team_lead_details = $this->db->get_where('account_details',array('user_id'=>$employee_details['teamlead_id']))->row_array();

								if(!empty($team_lead_details))
								{
									$teamlead_name = $team_lead_details['fullname'].' ('.$team_lead_details['emp_code'].')';
								}
							}

							?>


							<tr>
								<td>
									<a href="<?=base_url()?>employees/profile_view/<?php echo $value['user_id'];?>" class="avatar"><?php echo substr($account_details['fullname'],0,1);?></a>
									<h2><a href="<?=base_url()?>employees/profile_view/<?php echo $value['user_id'];?>"><?php echo $account_details['fullname'].' ('.$account_details['emp_code'].')'?> <span><?php echo $designation['designation']?></span></a></h2>
								</td>

								<td>
									<a href="<?=base_url()?>employees/profile_view/<?php echo $employee_details['teamlead_id'];?>" class="avatar"><?php echo substr($teamlead_name['fullname'],0,1);?></a>
									<h2><a href="<?=base_url()?>employees/profile_view/<?php echo $employee_details['teamlead_id'];?>"><?php echo $teamlead_name; ?> </a></h2>
								</td>

								<td>
									<div class="rating">
										<?php for ($i=0; $i <5 ; $i++) {

											if($i < $value['self_ratings']){
												echo '<i class="fa fa-star rated"></i>';
											}else{
												echo '<i class="fa fa-star"></i>';
											} 
										}?> 
									</div>
								</td>
								<td>
									<div class="rating">
										<?php for ($i=0; $i <5 ; $i++) {

											if($i < $peer_rating){
												echo '<i class="fa fa-star rated"></i>';
											}else{
												echo '<i class="fa fa-star"></i>';
											} 
										}?> 
									</div>
								</td>
								<td>
									<div class="rating">
										<?php for ($i=0; $i <5 ; $i++) {

											if($i < $value['your_ratings']){
												echo '<i class="fa fa-star rated"></i>';
											}else{
												echo '<i class="fa fa-star"></i>';
											} 
										}?> 
									</div>
								</td>
								<td>
									<div class="progress-wrap">
										<div class="progress progress-xs">
											<div class="progress-bar" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:40%">
												40%
											</div>
										</div>
										<span>40%</span>
									</div>
								</td>
							</tr>
						<?php } ?> -->


  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

  <script>
    function downloadTableData() {
  const table = document.getElementById("myTable");

  // Store original cell content
  const originalContent = [];

  for (let i = 1; i < table.rows.length; i++) {
    originalContent[i] = [];
    for (let j = 0; j < 2; j++) {
      let cellContent = table.rows[i].cells[j].textContent.trim();
      originalContent[i][j] = cellContent;
      if (cellContent.length > 0) {
        table.rows[i].cells[j].textContent = cellContent.substring(1);
      }
    }
  }

  // Convert the modified table to Excel file
  const wb = XLSX.utils.table_to_book(table, { sheet: "SheetJS" });
  XLSX.writeFile(wb, "performance_report.xlsx");

  window.location.reload(true);

}
  </script>
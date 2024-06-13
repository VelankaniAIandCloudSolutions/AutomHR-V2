<?php 


$this->db->select("dc.user_id as user_id, dc.teamlead_id as teamlead_id");
$this->db->from("dgt_competencies as dc");
$this->db->join("dgt_account_details as dad","dad.user_id = dc.user_id","inner");
$this->db->where("dad.branch_id", $branch_id);
if ($role == '3') 
{
	$this->db->where("dc.teamlead_id", $user_id);
}
$this->db->group_by('dc.user_id');

$total_competencies_review = $this->db->get()->result_array();

$this->db->select("dea.employee_id as user_id");
$this->db->from("dgt_employee_appraisal as dea");
$this->db->join("dgt_account_details as dad","dea.employee_id = dad.user_id","inner");
$this->db->where("dad.branch_id", $branch_id);
// if ($role == '3') 
// {
// 	$this->db->where("dea.teamlead_id", $user_id);
// }

$this->db->group_by('dea.employee_id');
$total_annual_review = $this->db->get()->result_array();

$mergedArray = array_merge($total_competencies_review, $total_annual_review);

$merged_userIds = array_column($mergedArray, 'user_id');

$uniqueUserIds = array_unique($merged_userIds);



?>
<div class="content container-fluid">
	<div class="row">
		<div class="col-sm-6">
			<h4 class="page-title">Performance Dashboard</h4>
		</div>
		<div class="col-md-6">
			<form name="performance_entity" id='performance_entity' action="<?php echo base_url('performance/performance_dashboard');?>" method="post">
				<div class="row">
					<div class="col-md-8">
						<lable>Branch Name</lable>
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

	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table custom-table m-b-0" id='myTable'>
					<thead>
						<tr>
							<th style="width:30%;">Employee Name</th>
							<th style="width:30%;">Manager Name</th>
							
							<th style="width:20%;">Annual Review</th>
							<th style="width:20%;">Competency</th>

						</tr>
					</thead>
					<tbody>
						<?php 
						$rating_count= count($performances_360);
						$peer_rating = 0;
						foreach ($uniqueUserIds as $value)
						{
							$employee_details = $this->db->get_where('users',array('id'=>$value))->row_array();
							$designation = $this->db->get_where('designation',array('id'=>$employee_details['designation_id']))->row_array();
							$account_details = $this->db->get_where('account_details',array('user_id'=>$value))->row_array();
							
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
									<a href="<?=base_url()?>employees/profile_view/<?php echo $value;?>" class="avatar"><?php echo substr($account_details['fullname'],0,1);?></a>
									<h2><a href="<?=base_url()?>employees/profile_view/<?php echo $value;?>"><?php echo $account_details['fullname'].' ('.$account_details['emp_code'].')'?> <span><?php echo $designation['designation']?></span></a></h2>
								</td>

								<td>
									<a href="<?=base_url()?>employees/profile_view/<?php echo $employee_details['teamlead_id'];?>" class="avatar"><?php echo substr($teamlead_name['fullname'],0,1);?></a>
									<h2><a href="<?=base_url()?>employees/profile_view/<?php echo $employee_details['teamlead_id'];?>"><?php echo $teamlead_name; ?> </a></h2>
								</td>

								<td>
									<!-- <?php 

									$smartgoal_details = $goal= array();
									$smartgoal_details = $this->db->select('*')
									->from('smartgoal')
									->where("user_id",$value)->order_by("id",'desc')->limit(1)
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
									?> -->
									<?php 
										$kpo_total = 0;

										$this->db->select("*");
										$this->db->from("dgt_employee_appraisal");
										$this->db->where("employee_id",$value);
										$tmp_response = $this->db->get()->row_array();
										$avgg = $total_size_of_kpo = $kpo_total = 0;

										if(!empty($tmp_response))
										{
											$Teamwork_array = json_decode($tmp_response['Teamwork'], true);
											$Job_Knowledge_array = json_decode($tmp_response['Job_Knowledge'], true);
											$Work_Habits_array = json_decode($tmp_response['Work_Habits'], true);
											$Quality_Of_Work_array = json_decode($tmp_response['Quality_Of_Work'], true);
											$kpo_total = array_sum($Teamwork_array) +  array_sum($Job_Knowledge_array) + array_sum($Work_Habits_array) + array_sum($Quality_Of_Work_array);

											$total_size_of_kpo = sizeof($Teamwork_array) + sizeof($Job_Knowledge_array) + sizeof($Work_Habits_array) + sizeof($Quality_Of_Work_array);

											$avgg = round(($kpo_total / $total_size_of_kpo) , 2) ;
										}

										echo $avgg;
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
											$competency_details = $this->db->select('competencies, rating')
											->from('dgt_competencies')
											->where("competencies",$value1['id'])
											->where("user_id",$value)->order_by("id",'desc')->limit(1)
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

  <!-- <script>
    function downloadTableData() {
      const table = document.getElementById("myTable");
      const wb = XLSX.utils.table_to_book(table, {sheet: "SheetJS"});
      XLSX.writeFile(wb, "performance_reprot.xlsx");
    }
  </script> -->

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
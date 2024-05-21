<div class="content">
	<div class="row">
		<div class="col-md-4 col-xs-3">

			<h4 class="page-title">360 Performance</h4>
		</div>

		<div class="col-md-6">
			<form name="performance_entity" id='performance_entity' action="<?php echo base_url('performance_three_sixty');?>" method="post">
				<div class="row">
					<div class="col-md-5">
						<lable class='lable'>Branch Name</lable>
						<select name="branch_id" id="branch_id" class="form-control">
							<option value=""> Select Branch</option>
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
						<div class="col-md-7" style="padding-top: 25px;">
							<input type="submit" name="Filter" class="btn btn-primary">
							<input type="button" value="Export" name="Export" id="Export" class="btn btn-primary" onclick="downloadTableData(); return false;">
						</div>
					</div>

				</form>
			</div>

			<div class="col-md-2">
				<a href="<?php echo base_url('performance_three_sixty?add=1');?>" class="btn add-btn">Add</a>
			</div>

		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table id="table-holidays" class="table table-striped custom-table m-b-0 AppendDataTables">
						<thead>
							<tr class="table_heading">
								<th> No </th>
								<th> Name </th>
								<th> Designation</th>
								<th> Team lead </th>
								<th> Goal Duaration </th>
								<th> Score </th>
							</tr>
						</thead>
						<tbody id="admin_leave_tbl">
							<?php 
							if(!empty($performances_360)){
								foreach($performances_360 as $key => $details){
									$employee_details = $this->db->get_where('users',array('id'=>$details['user_id']))->row_array();
									$designation = $this->db->get_where('designation',array('id'=>$employee_details['designation_id']))->row_array();
									$account_details = $this->db->get_where('account_details',array('user_id'=>$details['user_id']))->row_array();
									$team_lead = $this->db->get_where('account_details',array('user_id'=>$employee_details['teamlead_id']))->row_array();
									$teamlead = $this->db->get_where('account_details',array('user_id'=>$team_lead['user_id']))->row_array();
									?>

									<tr>
										<td><?=$key+1?></td>

										<td><a class="text-info" href="<?php echo base_url()?>performance_three_sixty/show_performance_three_sixty/<?=$details['user_id']?>"><?=$account_details['fullname']." (".$account_details['emp_code'].")"?></a></td>
										<td><?php echo $designation['designation']?></td>
										<td><?=$teamlead['fullname']?></td>
										<td>
											<?php if($details['goal_duration'] == 1){
												echo '90 Days';
											} elseif($details['goal_duration'] == 2){
												echo "6 Months";
											} else {
												echo "1 Year";
											}?>
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
													->where("user_id",$details['user_id'])->order_by("id",'desc')->limit(1)
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
								<?php  } ?>  
							<?php  }else{ ?>
								<tr><td class="text-center" colspan="9">No details were found</td></tr>
							<?php } ?>  
						</tbody>
					</table>    
				</div>
			</div>
		</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

  <script>
    function downloadTableData() {
  const table = document.getElementById("table-holidays");

  // Store original cell content
  const originalContent = [];

  for (let i = 1; i < table.rows.length; i++) {
    originalContent[i] = [];
    for (let j = 0; j < 2; j++) {
      let cellContent = table.rows[i].cells[j].textContent.trim();
      originalContent[i][j] = cellContent;
    }
  }

  // Convert the modified table to Excel file
  const wb = XLSX.utils.table_to_book(table, { sheet: "SheetJS" });
  XLSX.writeFile(wb, "competency.xlsx");

  window.location.reload(true);

}
  </script>

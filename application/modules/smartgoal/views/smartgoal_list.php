<div class="content">
	<div class="row">
		<div class="col-sm-6 col-xs-3">
			<?php 
			$user_id = $this->uri->segment(3);
			$username = $this->db->get_where('dgt_account_details',array('user_id'=>$user_id))->row_array();
			
			?>
			<h4 class="page-title"><?php echo $username['fullname']?> Smartgoal Manager</h4>
		</div>
		<div class="col-md-6">
			<form name="entity" id='entity' action="<?php echo base_url('smartgoal');?>" method="post">
				<div class="row">
					<div class="col-md-6">
						<lable class='lable'>Branch Name</lable>
						<select name="branch_id" id="branch_id" class="form-control">
							<option value="">Select Entity</option>
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
						<div class="col-md-6" style="padding-top: 25px;">
							<input type="submit" name="Filter" class="btn btn-primary">
							<input type="button" value="Export" name="Export" id="Export" class="btn btn-primary" onclick="downloadTableData(); return false;">
						</div>
					</div>

				</form>
			</div>

		</div>

		<?php if (($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'admin') || ($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'superadmin')) { 

			?>

			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<?php 
						$user_id = $this->uri->segment(3);

						$this->db->select('smartgoal.*');
						$this->db->from('smartgoal');
						if($branch_id != "")
						{
							$this->db->join("account_details","account_details.user_id = smartgoal.user_id","inner");
							$this->db->where("account_details.branch_id", $branch_id);	
						}
						$smartgoal_details = $this->db->get()->result_array();

						?>
						<table id="table-holidays" class="table table-striped custom-table m-b-0 AppendDataTables">
							<thead>
								<tr class="table_heading">
									<th> No </th>
									<th> Name </th>
									<th> Designation</th>
									<th> Team lead </th>
									<th> Goal Duaration </th> 
									<th> Score</th>

								</tr>
							</thead>
							<tbody id="admin_leave_tbl">
								<?php 
								if(!empty($smartgoal_details)){
									foreach($smartgoal_details as $key => $details){  
					 	// $teamlead = $this->db->get_where('account_details',array('user_id'=>$details['lead']))->row_array();
										$account_details = $this->db->get_where('account_details',array('user_id'=>$details['user_id']))->row_array();
										
										?>

										<tr>
											<td><?=$key+1?></td>

											<td><a class="text-info" href="<?php echo base_url()?>smartgoal/show_smartgoal/<?=$details['id']?>"><?=$details['emp_name']." (".$account_details['emp_code'].")"?></a></td>
											<td><?=$details['position']?></td>
											<td><?=$details['lead']?></td>
											<td><?=$details['goal_duration']?></td>
											<td>
												<?php 
												$smartgoal_details = $goal= array();
												$smartgoal_details = $this->db->select('*')
												->from('smartgoal')
												->where("id",$details['id'])->order_by("id",'desc')->limit(1)
												->get()->row_array();
												$goal = json_decode($smartgoal_details['goals']);
												$smart_goal_total_score = 0;
												foreach ($goal as $key => $goal) 
												{ 
													$score =0;
													$tmp_Weightage = $goal->Weightage;
													$Weightage = str_replace("%","", $tmp_Weightage);
													$score =  round(($Weightage * ($goal->manager_rating)) / 100, 2);
													$smart_goal_total_score += $score;
												}

												if($smart_goal_total_score  < 1)
                        {
                           $smart_goal_total_score = 0;
                        }
                                    
												
												echo round($smart_goal_total_score, 2);
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
			<!-- user leave end -->
		<?php } ?>
		


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
  XLSX.writeFile(wb, "performance_report.xlsx");

  window.location.reload(true);

}
</script>
		



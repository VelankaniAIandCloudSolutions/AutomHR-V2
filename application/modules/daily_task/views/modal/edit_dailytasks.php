<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('edit_branch')?></h4>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id'=>'branchlisttable'); echo form_open(base_url().'daily_task/edit_dailytasks/',$attributes); ?>
			<div class="modal-body">
			<input type="hidden"  name="dailytask_id" class="form-control" value="<?php echo $task_details['dailytask_id'] ?>">
				<!--<div class="form-group">
					<label>Branch name<span class="text-danger">*</span></label>
						
					<select class="form-control" style="width:100%;" name="branch_id" id="dailybranch_id" > 
						<option value="" disabled>Choose Branch</option>
						<option value="0">All Branches</option>
						<?php 
							$all_branches = $this->db->get_where('branches',array('branch_status'=>'0'))->result_array();
							if(!empty($all_branches)){
								foreach($all_branches as $branch){

						?>
							<option value="<?php echo $branch['branch_id'];?>" <?php if($task_details['branch_id'] == $branch['branch_id']){ echo "selected"; } ?> ><?php echo $branch['branch_name']; ?></option>
						<?php } }else{ ?>
							<option value="" disabled>No Branches</option>
						<?php } ?>
				   </select>
				</div>-->
				<div class="form-group">
					<label>Employee Name<span class="text-danger">*</span></label>
					<select class="form-control" style="width:100%;" name="user_id" id="dailyemployee_id" > 
						<option value="" selected disabled >Choose Employee</option>
					<?php foreach (User::employee() as $key => $user) { 
						$emp_details = User::profile_info($user->id); 
						if($emp_details->branch_id == $this->session->userdata('branch_id')){
						?>
					<option value="<?php echo $user->id;?>" <?php if($task_details['user_id'] == $user->id){ echo "selected"; } ?>><?=ucfirst(User::displayName($user->id))?></option>
					<?php  } 
				}  ?>
				   </select>
				</div>
				<div class="form-group">
					<label>Task Name<span class="text-danger">*</span></label>
						<input type="text" id="dailytask_name" name="dailytask_name" class="form-control" value="<?php echo $task_details['dailytask_name'] ?>">
				</div>
				<div class="form-group">
					<label>Task Status<span class="text-danger">*</span></label>
					<select class="form-control" id="task_status" style="width:100%;" name="task_status" >
						<option value="" disabled>--Status--</option>
						<option value="0" <?php if($task_details['task_status'] == '0'){ echo "selected"; } ?>>Active</option>
						<option value="1" <?php if($task_details['task_status'] == '1'){ echo "selected"; } ?>>InActive</option>
					</select>
				</div>
				<div class="submit-section">
					<button class="btn btn-primary submit-btn" id="dailytask_btn">Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>
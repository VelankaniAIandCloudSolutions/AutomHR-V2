<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
            <?php 
            $form_type = 'Add';
            if(isset($job_type['id'])&&!empty($job_type['id'])) 
            {  
				$form_type = 'Edit'; ?> 
     <?php  }
            ?>
			<h4 class="modal-title"><?php echo $form_type; ?> Job Type</h4>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		<?php 
			$attributes = array('class' => 'bs-example'); echo form_open_multipart('all_jobtypes/add', $attributes); 
			if(isset($job_type['id'])&&!empty($job_type['id'])) 
            {    ?>
                <input type = "hidden" name="edit" value="true">
                <input type = "hidden" name="id" value="<?php echo $job_type['id']; ?>">
     <?php  } ?>
			<div class="modal-body">
				<div class="form-group">
					<label><?=lang('job_type_name')?> <span class="text-danger">*</span></label>
					<input type="text" name="job_type" class="form-control" value="<?php echo isset($job_type['job_type'])?$job_type['job_type']:''; ?>" required>
				</div>

				<div class="form-group">
					<?php 
						$active_selected = "selected";
						$inactive_selected = "";
							if(isset($job_type['status'])&&$job_type['status']==0)
							{
								$active_selected = "";
								$inactive_selected = "selected";
							}
					 ?>
					<select class="select2-option form-control" name="status" required> 								 
						<option value="1" <?php echo $active_selected;  ?> >Active</option>
						<option value="0" <?php echo $inactive_selected;  ?> >Inactive</option>
					</select>
				</div>

				<div class="submit-section">
					<button class="btn btn-primary submit-btn">Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>
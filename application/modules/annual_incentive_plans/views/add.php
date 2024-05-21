<div class="modal-dialog  modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
            <?php 
            $form_type = 'Add';
            if(isset($plan['id'])&&!empty($plan['id'])) 
            {  
				$form_type = 'Edit'; ?> 
     <?php  }
            ?>
			<!-- <h4 class="modal-title"><?php echo $plan; ?> Job Type</h4> -->
			<h4 class="modal-title">Annual Incentive Plans</h4>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		<?php 
			$attributes = array('class' => 'bs-example','id'=> 'addAnnualIncentivePlans'); echo form_open_multipart('annual_incentive_plans/add', $attributes); 
			if(isset($plan['id'])&&!empty($plan['id'])) 
            {    ?>
                <input type = "hidden" name="edit" value="true">
                <input type = "hidden" name="id" value="<?php echo $plan['id']; ?>">
     <?php  } ?>
			<div class="modal-body">
				<div class="form-group">
					<label>Annual Incentive Plans <span class="text-danger">*</span></label>
					<input type="text" name="plan" class="form-control" value="<?php echo isset($plan['plan'])?$plan['plan']:''; ?>" required>
				</div>

				<div class="form-group">
					<?php 
						$active_selected = "selected";
						$inactive_selected = "";
							if(isset($plan['status'])&&$plan['status']==0)
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
					<button class="btn btn-primary submit-btn submit_AnnualIncentivePlans">Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>
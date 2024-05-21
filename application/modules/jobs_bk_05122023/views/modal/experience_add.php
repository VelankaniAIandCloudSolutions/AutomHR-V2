<div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
		<div class="modal-header">
           
			<h4 class="modal-title"><?php echo $form_type; ?> <?php echo lang('experience_level');?></h4>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		<?php 
			$attributes = array('class' => 'bs-example','id'=>"add_experience_level"); echo form_open_multipart('jobs/add_experience', $attributes); 
			if(isset($exp_level['id']) && $exp_level['id']) 
            {    ?>
               <input type = "hidden" name="id" value="<?php echo $exp_level['id']; ?>">
     <?php  } ?>
			<div class="modal-body">
				<div class="form-group">
					<label><?=lang('experience_level')?> <span class="text-danger">*</span></label>
					<input type="text" name="experience" class="form-control" value="<?php echo isset($exp_level['experience_level']) ? $exp_level['experience_level'] : ''; ?>" required>
				</div>

				<div class="form-group">
					<?php 
						$active_selected = "selected";
						$inactive_selected = "";
							if(isset($exp_level['status'])&&$exp_level['status']==0)
							{
								$active_selected = "";
								$inactive_selected = "selected";
							}
					 ?>
					<select class="select2-option form-control" name="status" required> 
						<option value=""><?php echo lang('select_status')?></option>							 
						<option value="1" <?php echo $active_selected;  ?> ><?php echo lang('active');?></option>
						<option value="0" <?php echo $inactive_selected;  ?> ><?php echo lang('inactive');?></option>
					</select>
				</div>

				<div class="submit-section">
					<button class="btn btn-primary submit-btn" id="experience_level_btn" type="submit" name="submit" value="submit"><?php echo lang('submit'); ?></button>
				</div>
			</div>
		</form>
	</div>
</div>
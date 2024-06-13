<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
            <?php 
            $form_type = 'Add';
			$entity_id = '';
            if(isset($notice_board['id'])&&!empty($notice_board['id'])) 
            {  
				if(!empty($notice_board['branch_id'])){
					$entity_id = $notice_board['branch_id'];
				}
				$form_type = 'Edit'; ?> 
     <?php  }
            ?>
			<h4 class="modal-title"><?php echo $form_type; ?> Notice Board</h4>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		<?php 
			$attributes = array('class' => 'bs-example'); echo form_open_multipart('notice_board/add', $attributes); 
			if(isset($notice_board['id'])&&!empty($notice_board['id'])) 
            {    ?>
                <input type = "hidden" name="edit" value="true">
                <input type = "hidden" name="id" value="<?php echo $notice_board['id']; ?>">
     <?php  } ?>
			<div class="modal-body">
				<div class="form-group">
					<label><?=lang('title')?> <span class="text-danger">*</span></label>
					<input type="text" name="title" class="form-control" value="<?php echo isset($notice_board['title'])?$notice_board['title']:''; ?>" required>
				</div>
				<?php //if(($this->session->userdata('role_id') == 1) || ($this->session->userdata('role_id') == 4)){ ?>
				<div class="form-group">
					<label><?=lang('branch')?> <span class="text-danger">*</span></label>
					<select class="form-control" style="width:100%;"  name="branch_id" id="add_branch" required >
					<option value="">Select</option>
					<?php
						if(!empty($branches))	{
						foreach ($branches as $branch1){ ?>
						<option value="<?=$branch1['branch_id']?>" <?php echo ($entity_id==$branch1['branch_id'])?'selected':'';?>><?=$branch1['branch_name']?></option>
						<?php } ?>
						<?php } ?>
				</select>
				</div>
				<?php //}?>
				<div class="form-group">
					<label><?=lang('description')?> <span class="text-danger">*</span></label>
					<textarea name ="description" class="form-control" required><?php echo isset($notice_board['description'])?$notice_board['description']:''; ?></textarea>
				</div>

				

				<div class="submit-section">
					<button class="btn btn-primary submit-btn">Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">Edit Designation</h4>
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		<div class="modal-body">
			<?php $attributes = array('class' => 'bs-example','id'=>'settingsDesignationForm'); echo form_open_multipart('categories/edit_sub_category/'.$sub_details['sub_id'], $attributes); ?>
				<div class="form-group">
					<label>Departments <span class="text-danger">*</span></label>
					<!-- <input type="hidden" name="des_id" id="des_id" value="<?php //echo $sub_details['id']; ?>"> -->
					<!-- <input type="hidden" name="created" id="created" value="<?php echo date('Y-m-d H:i:s'); ?>"> -->
					<select name="cat_id" id="cat_id" required="required" class="form-control" >
						<option value="" selected disabled>Categories</option>
						<?php
						if($this->session->userdata('branch_id')==0){
							$categories = $this -> db -> get_where('budget_category',array()) -> result();
						}
						else{
							$branches = $this->db->select('*')->from('assigned_entities')->where('user_id',$this->session->userdata('user_id'))->get()->result_array();
							$branches = array_column($branches, 'branch_id');
							$categories = $this->db->where_in('branch_id',$branches)->get('budget_category')-> result();

						}
						if (!empty($categories)) { 
						foreach ($categories as $key => $d) { ?>
						<option value="<?php echo $d->cat_id; ?>" <?php if($d->cat_id == $sub_details['cat_id']){ echo "selected"; } ?>><?=$d->category_name?></option>
						<?php } }else{ ?>
						<option value="">No Results</option>
						<?php } ?>
					</select>
				</div>
				<div class="form-group">
					<label>Sub-Category Name <span class="text-danger">*</span></label>
					<input type="text" name="sub_category" class="form-control" value="<?php echo $sub_details['sub_category']; ?>" required>
				</div>
				<div class="submit-section"> 
					<button id="settings_designation_submit" class="btn btn-primary submit-btn">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>
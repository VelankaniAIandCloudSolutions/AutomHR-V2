<?php $company_ref = config_item('company_id_prefix').$this->applib->generate_string();
while($this->db->where('company_ref', $company_ref)->get('companies')->num_rows() == 1) {
$company_ref = config_item('company_id_prefix').$this->applib->generate_string();

} 

// $getusers = $this->db->get_where('dgt_users',array('id'=>$user_id))->row_array();
 $user_details = $this->db->select('*')
                                             ->from('users U')
                                             ->join('account_details AD','U.id = AD.user_id')
                                             ->where('U.id',$user_id)
                                             ->get()->row_array();


?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">
				Transfer Entity	
			</h4>
        </div>
		<?php $attributes = array('id' => 'transfer_branch'); echo form_open(base_url().'employees/get_profile_view/'.$user_id, $attributes); ?>
			<div class="modal-body">
				<div class="tab-content">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label>Email ID</label>
											<input type="text" readonly value="<?=$user_details['email']?>" class="form-control" name="emailid" id="emailid">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Branch</label>
											<select class="select2-option" style="width:100%;" required name="branch_id" id="branch_id">
													<?php

													$branch_lists = $this->db->get_where('branches',array('branch_status !='=>'1'))->result_array();
													// echo $this->db->last_query(); exit;


													 if(!empty($branch_lists))	{ ?>
															<option value="" selected disabled>Branch </option>

													 <?php foreach ($branch_lists as $branch_list){ 
													 
													$chk_branch = $this->db->get_where('dgt_account_details',array('branch_id'=>$branch_list['branch_id']))->result_array();
													// if(count($chk_branch)==0){
													 ?>

														<option <?php if($user_details['branch_id']==$branch_list['branch_id']){echo 'selected';} ?> value="<?php echo $branch_list['branch_id']; ?>"><?php echo ucfirst($branch_list['branch_name']); ?></option>

													<?php //}
													} ?>

														<?php }else{ ?>
																<option value="" selected disabled>No Branch</option>
														<?php } ?>

													

										</select>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="submit-section text-right">
							<button class="btn btn-primary submit-btn" id="ResetNewPassword">Transfer</button>
						</div>
					
				</div>
				
			</div>
		</form>
	</div>
</div>
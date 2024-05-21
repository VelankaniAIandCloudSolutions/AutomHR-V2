 <div id="edit_group" >
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title"><?php echo lang('edit_a_group');?></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
                        <div class="modal-body">
                            <p>Groups are where your team communicates. They’re best when organized around a topic — #leads, for example.</p>
                            <form id="create_group_form" method="post" action="<?php echo site_url('chats/edit_group')?>">
                                <input type="hidden" name="group_id" value="<?php  echo $this->uri->segment(3); ?>" >
                                <div class="form-group">
                                    <label><?php echo lang('group_name'); ?><span class="text-danger">*</span></label>
                                    <input class="form-control" id="group_name" name="group_name" required="" type="text" value="<?php if(isset($group_data['group_name'])){ echo $group_data['group_name'];}?>">
                                </div>
                                <div class="form-group">
                                    <label><?php echo lang('select_members')?> </label>
                                    
                                    <select name="members[]" class="form-control" multiple required="">
                                    	<option value=""><?php echo lang('select_members')?></option>
                                    	<?php if($all_users->num_rows()>0){
                                    		foreach ($all_users->result() as $users) {
                                    			?><option value="<?php echo $users->id;?>" <?php if(isset($group_data['members']) && in_array($users->id,$group_data['members'])){ echo "selected";}?>><?php echo $users->username;?></option><?php
                                    		}
                                    		?>
                                    		
                                    		<?php 
                                    	}?>
                                    	
                                    </select>
                                </div>
                                <div class="m-t-50 text-center">
                                    <input  class="btn btn-primary btn-lg" type="submit" name="create_groups" id="create_group_btn" value="Update Group">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
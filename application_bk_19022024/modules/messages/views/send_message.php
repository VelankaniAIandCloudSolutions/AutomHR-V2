<div class="content">
	<div class="row">
		<div class="col-sm-8">
			<h4 class="page-title"><?=lang('messages')?></h4>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4 col-md-4 col-lg-3 col-xs-12">
			<div class="card-box">
				<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
					<div>
						<ul class="mail-menu">
							<li class="<?php echo ($group == 'inbox') ? 'active' : '';?>">
								<a href="<?=base_url()?>messages?group=inbox"> <i class="fa fa-fw fa-envelope"></i>
									<?=lang('inbox')?>
								</a>
							</li>
							<li class="<?php echo ($group == 'sent') ? 'active' : '';?>">
								<a href="<?=base_url()?>messages?group=sent"> <i class="fa fa-fw fa-exchange"></i>
									<?=lang('sent')?>
								</a>
							</li>
							<li class="<?php echo ($group == 'favourites') ? 'active' : '';?>">
								<a href="<?=base_url()?>messages?group=favourites"> <i class="fa fa-fw fa-star"></i>
									<?=lang('favourites')?>
								</a>
							</li>
							<li class="<?php echo ($group == 'trash') ? 'active' : '';?>">
								<a href="<?=base_url()?>messages?group=trash"> <i class="fa fa-fw fa-trash-o"></i>
									<?=lang('trash')?>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-8 col-md-8 col-lg-9 col-xs-12">
				<div class="card-box">
						<div class="email-header">
							<div class="row">
								<div class="col-xs-12 col-sm-6 pull-right top-action-left">
									<?php echo form_open(base_url().'messages/search/'); ?>
										<div class="input-group">
											<input type="text" class="form-control" name="keyword" placeholder="<?=lang('keyword')?>">
												<span class="input-group-btn"> <button class="btn btn-md btn-default" type="submit">Go!</button>
												</span>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="email-content">
							<div>
								<?=$this->session->flashdata('form_error')?>
								<?php

								$attributes = array('class' => 'bs-example form-horizontal','id'=>'messageSendEmail');
								echo form_open(base_url().'messages/send',$attributes); ?>
									<div class="">
										<h4 class="m-b-20"><?=lang('message_notification')?></h4>
										<div class="form-group">
											<label class="col-lg-3 control-label"><?=lang('username')?> <span class="text-danger">*</span> </label>
											<div class="col-lg-9">
												<div class="m-b">
													<select class="select2-option form-control" id="username_select" multiple="multiple" style="width:260px" name="user_to[]" >
														<?php if(User::is_admin()){ 
														 ?>
														<optgroup label="<?=lang('administrators')?>">
															<?php foreach (User::admin_list() as $admin): 
															$user_details = User::profile_info($admin->id); 
															if($user_details->branch_id == $this->session->userdata('branch_id')){
																if($admin->role_id != 2){
															?>
																<option value="<?=$admin->id?>">
																<?=ucfirst(User::displayName($admin->id))?></option>
															<?php } } endforeach; ?>
														</optgroup>
														<optgroup label="<?=lang('staff')?>">
															<?php foreach (User::staff_list() as $s): 
																$staff_details = User::profile_info($s->id); 
																if($staff_details->branch_id == $this->session->userdata('branch_id')){
																?>
																<option value="<?=$s->id?>">
																<?=ucfirst(User::displayName($s->id))?></option>
															<?php } endforeach; ?>
														</optgroup>
														<optgroup label="<?=lang('clients')?>">
															<?php foreach (User::user_list() as $client): 
																	$client_details = User::profile_info($client->id); 
																	if($client_details->branch_id == $this->session->userdata('branch_id')){
																?>
																<option value="<?=$client->id?>">
																<?=ucfirst(User::displayName($client->id))?></option>
															<?php } endforeach; ?>
														</optgroup>
														<?php }else{ ?>
														<optgroup label="<?=lang('administrators')?>">
															<?php foreach (User::admin_list() as $admin): 
																	$admin_details = User::profile_info($admin->id); 
																	if($admin_details->branch_id == $this->session->userdata('branch_id')){
																?>
																<option value="<?=$admin->id?>">
																<?=ucfirst(User::displayName($admin->id))?></option>
															<?php } endforeach; ?>
														</optgroup>
														<?php } ?>
													</select>
												</div>
												<div class="row">
												<div class="col-md-6">
												<label id="username_message_error" class="error display-none" style="position:inherit;top:0;font-size:15px;">Please select a username</label>
												</div>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-3 control-label">Message <span class="text-danger">*</span></label>
											<div class="col-lg-9">
												<textarea name="message"  class="form-control foeditor-send-message"></textarea>
												<div class="row">
												<div class="col-md-6">
												<label id="send_message_error" class="error display-none" style="position:inherit;top:0;font-size:15px;">Message must not empty</label>
												</div>
												</div>
											</div>
										</div>
										<div class="row">
										<div class="col-md-12">
										<div class="pull-right m-r-14">
											<button type="submit" class="btn btn-success" id="message_send_email"><?=lang('send_message')?></button>
										</div>
										</div>
										</div>
						
									</div>
								</form>
							</div>
						</div>
					</div>
				
		</div>
	</div>
</div>
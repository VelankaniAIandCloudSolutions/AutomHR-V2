<style type="text/css">
.btn-default.btn-on.active{background-color: #c4dc78;color: white;}
.btn-default.btn-off.active{background-color: #2196F3;color: white;}
</style>

<div class="content">
	<div class="row">

		<?php if($this->session->userdata('subscription') == 'success'){ ?>
		<div class="alert alert-success alert-dismissible">
		    <button type="button" class="close" data-dismiss="alert">&times;</button>
		    <strong>Done!</strong> Subscripe Successfully. Your User Count is Increased...!
		 </div>

		<?php $this->session->set_userdata('subscription',''); } ?>
		<?php if($this->session->userdata('subscription') == 'error'){ ?>
		<div class="alert alert-danger alert-dismissible">
		    <button type="button" class="close" data-dismiss="alert">&times;</button>
		    <strong>Error!</strong> Please Try Again.
		</div>

		<?php $this->session->set_userdata('subscription',''); } ?>

		
		<div class="col-md-4">
			<h4 class="page-title"><?=lang('all_users')?></h4>
		</div>
		

<?php
		$attributes = array('id' => 'stripeFormSub');
          echo form_open(base_url().'stripepay/sub_authenticate',$attributes); ?>
                            <input type="hidden" id="stripeTokensub" name="stripeTokensub"/>
                            <input type="hidden" id="stripeEmailsub" name="stripeEmailsub"/>
                            <input type="hidden" id="stripeamountsub" name="stripeamountsub"/>
                        </form>

		<div class="col-md-8 text-right m-b-30">
			<a href="javascript:void(0)" class="btn btn-primary pull-right rounded m-l-5" data-toggle="modal" data-target="#add_new_user"><i class="fa fa-plus"></i> <?=lang('new_user')?></a>
			<!-- <a href="javascript:void(0)" class="btn btn-primary pull-right rounded" data-toggle="modal" data-target="#subscripe_user"><i class="fa fa-money icon" style="margin-right: 5px;"></i>Subscribe</a> -->
		</div>
	</div>				
					<div class="row filter-row">
						<div class="col-sm-3 col-xs-6">  
							<div class="form-group form-focus">
								<label class="control-label">User Name</label>
								<input type="text" class="form-control floating" id="username" name="username">
								<label id="username_error" class="error display-none" for="username">Username Shouldn't be empty</label>
							</div>
						</div>
						
						<div class="col-sm-3 col-xs-6"> 
							<div class="form-group form-focus select-focus">
								<label class="control-label">Company</label>
								<select class="select floating form-control" id="company" name="company"> 
									<option value="" selected="selected">All Companies</option>
									<?php if(!empty($companies)){ ?>
									<?php foreach ($companies as $company) { ?>
										<option value="<?php echo $company->co_id; ?>"><?php echo $company->company_name; ?></option>
									<?php  } ?>
									<?php } ?>
								</select>
								<label id="company_error" class="error display-none" for="company">Please Select a company</label>
							</div>
						</div>
						
						<div class="col-sm-3 col-xs-6"> 
							<div class="form-group form-focus select-focus">
								<label class="control-label">Role </label>
								<select class="select floating form-control" id="user_role" name="user_role"> 
									<option value="" selected="selected">All Roles</option>
									<?php
										$roles = User::get_roles();
									 if(!empty($roles)){ 
										foreach ($roles as $role) { 
											if ($role->r_id !=3) {
												?>
											<option value="<?php echo $role->r_id; ?>"><?php echo ucfirst($role->role); ?></option>		
										<?php
										 }
											}
									 } ?> 
								</select>
								<label id="user_role_error" class="error display-none" for="user_role">Please Select a role</label>
							</div>
						</div>

						<div class="col-sm-3 col-xs-6">  
							<a href="javascript:void(0)" id="users_search_btn" class="btn btn-success btn-block"> Search </a>  
						</div>  
						   
                    </div>

	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<table id="table-users" class="table table-striped custom-table">
					<thead>
						<tr>
							
							<th><?=lang('full_name')?></th>
							<th><?=lang('email')?> </th>
							<th><?=lang('company')?> </th>
							<th class="hidden-sm"><?=lang('date')?> </th>
							<th><?=lang('role')?> </th>
							<th class="col-options no-sort text-right"><?=lang('options')?></th>
						</tr>
					</thead>
					<tbody>

						<?php /* foreach (User::all_users() as $key => $user) { ?>
						<tr>
							<?php $info = User::profile_info($user->id); ?>
							<td>
								<a class="pull-left" data-toggle="tooltip" data-title="<?=User::login_info($user->id)->email?>" data-placement="right">
									<img src="<?php echo User::avatar_url($user->id); ?>" class="img-circle" width="32">
									<span class="label label-<?=($user->banned == '1') ? 'danger': 'success'?>"><?=$user->username?></span>
									<?php if($user->role_id == '3') { ?>
									<strong class=""><?=config_item('default_currency_symbol')?><?=User::profile_info($user->id)->hourly_rate;?>/<?=lang('hour')?></strong>
									<?php }?>
								</a>
							</td>
							<td class=""><?=$info->fullname?></td>
							<td class="">
								<?php if($info->company >0){ ?>
								<a href="<?=base_url()?>companies/view/<?=$info->company?>" class="text-info">
								<?=($info->company > 0) ? Client::view_by_id($info->company)->company_name : 'N/A'; ?></a>
								<?php }else{ ?>
								<a href="javascript:void(0)">N/A</a>
								<?php }  ?>
							</td>
							<td>
								<?php if (User::get_role($user->id) == 'admin') {
									  $span_badge = 'label label-danger';
								  }elseif (User::get_role($user->id) == 'staff') {
									  $span_badge = 'label label-info';
								  }elseif (User::get_role($user->id) == 'client') {
									  $span_badge = 'label label-default';
								  }else{
									  $span_badge = '';
								}
								?>
								<span class="<?=$span_badge?>"><?=lang(User::get_role($user->id))?></span>
							</td>
							<td class="hidden-sm">
								<?=strftime(config_item('date_format'), strtotime($user->created));?>
							</td>
							
							<td class="text-right">
								<a href="<?=base_url()?>users/account/auth/<?=$user->id?>" class="btn btn-info btn-xs" data-toggle="ajaxModal" title="<?=lang('user_edit_login')?>">
									<i class="fa fa-lock"></i>
								</a>
								<?php if($user->role_id == '3') { ?>
								<a href="<?=base_url()?>users/account/permissions/<?=$user->id?>" class="btn btn-danger btn-xs" data-toggle="ajaxModal" title="<?=lang('staff_permissions')?>">
									<i class="fa fa-shield"></i>
								</a>
								<?php } ?>

								<a href="<?=base_url()?>users/account/update/<?=$user->id?>" class="btn btn-success btn-xs" data-toggle="ajaxModal" title="<?=lang('edit')?>">
									<i class="fa fa-edit"></i>
								</a>
								<?php if ($user->id != User::get_id()) { ?>

								<a href="<?=base_url()?>users/account/ban/<?=$user->id?>" class="btn btn-warning btn-<?=($user->banned == '1') ? 'danger': 'default'?> btn-xs" data-toggle="ajaxModal" title="<?=lang('ban_user')?>">
									<i class="fa fa-times-circle-o"></i>
								</a>
								<a href="<?=base_url()?>users/account/delete/<?=$user->id?>" class="btn btn-primary btn-xs" data-toggle="ajaxModal" title="<?=lang('delete')?>">
									<i class="fa fa-trash-o"></i>
								</a>
								<?php } ?>
							</td>
						</tr>
						<?php }*/ ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div id="add_new_user" class="modal custom-modal fade" role="dialog" style="display: none;">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Add New User</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body m-b-0">
					<?php $attributes = array('id'=>'addNewUser');
					echo form_open(base_url().'auth/register_user',$attributes); ?>
						<p class="text-danger"><?php echo $this->session->flashdata('form_errors'); ?></p>
						<input type="hidden" name="r_url" value="<?=base_url()?>users/account">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label><?=lang('full_name')?> <span class="text-danger">*</span></label>
									<input type="text" class="form-control" value="<?=set_value('fullname')?>" placeholder="<?=lang('eg')?> <?=lang('user_placeholder_name')?>" name="fullname">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label><?=lang('username')?> <span class="text-danger">*</span></label>
									<input type="text" name="username" placeholder="<?=lang('eg')?> <?=lang('user_placeholder_username')?>" value="<?=set_value('username')?>" class="form-control">
								</div>
							</div>
							</div>
							<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label><?=lang('email')?> <span class="text-danger">*</span></label>
									<input type="email" placeholder="<?=lang('eg')?> <?=lang('user_placeholder_email')?>" name="email" value="<?=set_value('email')?>" class="form-control">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label><?=lang('phone')?> <span class="text-danger">*</span></label>
									<input type="text" class="form-control" value="<?=set_value('phone')?>" name="phone" placeholder="<?=lang('eg')?> <?=lang('user_placeholder_phone')?>">
								</div>
							</div>
							</div>
							<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label><?=lang('password')?> <span class="text-danger">*</span></label>
									<input type="password" placeholder="<?=lang('password')?>" value="<?=set_value('password')?>" name="password" id="password" class="form-control">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label><?=lang('confirm_password')?> <span class="text-danger">*</span></label>
									<input type="password" placeholder="<?=lang('confirm_password')?>" value="<?=set_value('confirm_password')?>" name="confirm_password"  class="form-control">
								</div>
							</div>
							</div>
							<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label><?=lang('company')?> <span class="text-danger">*</span></label>
									<select class="select2-option form-control" style="width:100%;" name="company" >
										<optgroup label="<?=lang('default_company')?>">
											<option value="-"><?=config_item('company_name')?></option>
										</optgroup>
										<optgroup label="<?=lang('other_companies')?>">
											<?php foreach (Client::get_all_clients() as $company){ ?>
											<option value="<?=$company->co_id?>"><?=$company->company_name?></option>
											<?php } ?>
										</optgroup>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label><?=lang('role')?> <span class="text-danger">*</span></label>
									<select name="role" class="form-control">
										<?php foreach (User::get_roles() as $r) {
											if (($r->r_id !=3) && ($r->r_id !=4) && ($r->r_id !=1)) {
													
												
										 ?>
										<option value="<?=$r->r_id?>" selected><?=ucfirst($r->role)?></option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="m-t-20 text-center">
							<button class="btn btn-primary" id="user_add_new"><?=lang('register_user')?></button>
						</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

		<div id="subscripe_user" class="modal custom-modal fade" role="dialog" style="display: none;">
		<div class="modal-dialog">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<div class="modal-content modal-lg">
				<div class="modal-header">
					<h4 class="modal-title">User Subscription</h4>
				</div>
				<div class="modal-body m-b-30">
						<p class="text-danger"><?php  echo $this->session->flashdata('form_errors'); ?></p>
						<input type="hidden" name="r_url" value="<?=base_url()?>users/account">
						<div class="row">
						<form method="post" id="paypal_form">
							<div class="col-md-12">
								<span id="error_sub" style="color: red;display: none;"> *Please fill the required fields</span>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Choose Number of Users<span class="text-danger">*</span></label>
									<input type="text" name="user_count" id="user_count" class="form-control" placeholder="Users count">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Subscription Amount<span class="text-danger">*</span></label>
									<input type="text" name="sub_amount" id="sub_amount" placeholder="$11" value="<?=set_value('sub_amount')?>" class="form-control" disabled>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Payment Type<span class="text-danger">*</span></label>
									<div class="btn-group" id="status" data-toggle="buttons">
						              <label class="btn btn-default btn-on btn-lg active">
						              <input type="radio" class="payment_type_option" value="stripe" name="payment_type" id="payment_type" checked="checked">Stripe</label>
						              <label class="btn btn-default btn-off btn-lg">
						              <input type="radio" class="payment_type_option" value="paypal" name="payment_type" id="payment_type">Paypal</label>
						            </div>
								</div>
							</div>
							<div class="m-t-20 text-center">
								<a href="javascript:void(0);" id="user_subscripe" class="btn btn-primary">Subscribe</a>
								<button id="paypal_subscription"  class="btn btn-primary" style="display: none;">Subscribe </button>
							</div>
					</form>
						</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>
<script type="text/javascript">
	var handler = StripeCheckout.configure({
            key: '<?=config_item('stripe_public_key')?>',
            image: '<?=base_url()?>assets/images/<?=config_item('company_logo')?>',
            token: function(token) {
                // Use the token to create the charge with a server-side script.
                // You can access the token ID with `token.id`
                $("#stripeTokensub").val(token.id);
                $("#stripeEmailsub").val(token.email);
                $("#stripeFormSub").submit();



            }
        });
	$(document).ready(function(){
		$('input[type=radio][name=payment_type]').change(function() {
		    if (this.value == 'stripe') {
		        $('#paypal_subscription').css('display','none');
	    		$('#user_subscripe').css('display','');
		    }else{
		        $('#user_subscripe').css('display','none');
	    		$('#paypal_subscription').css('display','');
		    }
		});
	});



	$('#user_subscripe').on('click', function(e) {
        // Open Checkout with further options
        var stripe_amount = $('#sub_amount').val();
        if((stripe_amount != '') || (stripe_amount != 0)){
	    	$('#error_sub').css('display','none');
	        handler.open({
	            name: 'Dreamguys Technologies',
	            description: 'User Subscribtion',
	            amount: stripe_amount * 100,
	            currency: 'USD'
	        });
	        e.preventDefault();
	    }else{
	    	// alert('please fill the required Fields');
	    	$('#error_sub').css('display','');
	    	return false;
	    }
    });

    $('#paypal_subscription').click(function(){
    	var paypal_amount = $('#stripeamountsub').val();
    	var user_count = $('#user_count').val();
    	if((user_count != '') & (user_count != 0))
    	{
    		$('#error_sub').css('display','none');
			var form_url = "<?php echo base_url().'paypal/subscription_pay/'.$this->session->userdata('user_id').'/'; ?>"+paypal_amount;
			$('#paypal_form').attr('action',form_url);
			$('#paypal_form').submit();
    	}else{
    		$('#error_sub').css('display','');
	    	return false;
    	}
    });
</script>
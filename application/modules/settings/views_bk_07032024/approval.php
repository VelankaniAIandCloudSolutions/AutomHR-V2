<?php  if(User::is_staff()) { ?>
<div class="content">
   <div class="row">
      <div class="col-xs-3">
         <h4 class="page-title m-b-20"><?=lang('settings')?></h4>
      </div>
      <div class="col-xs-9 m-b-0 text-right">
         <?php if($load_setting == 'templates'){  ?>
         <div class="btn-group">
            <button type="button" class="btn btn-sm btn-primary" title="Filter" data-toggle="dropdown"><i class="fa fa-cogs"></i> <?=lang('choose_template')?> <span class="caret"></span></button>
            <ul class="dropdown-menu">
               <li><a href="<?=base_url()?>settings/?settings=templates&group=user"><?=lang('account_emails')?></a></li>
               <li><a href="<?=base_url()?>settings/?settings=templates&group=bugs"><?=lang('bug_emails')?></a></li>
               <li><a href="<?=base_url()?>settings/?settings=templates&group=project"><?=lang('project_emails')?></a></li>
               <li><a href="<?=base_url()?>settings/?settings=templates&group=task"><?=lang('task_emails')?></a></li>
               <li><a href="<?=base_url()?>settings/?settings=templates&group=invoice"><?=lang('invoicing_emails')?></a></li>
               <li><a href="<?=base_url()?>settings/?settings=templates&group=ticket"><?=lang('ticketing_emails')?></a></li>
               <li class="divider"></li>
               <li><a href="<?=base_url()?>settings/?settings=templates&group=extra"><?=lang('extra_emails')?></a></li>
               <li><a href="<?=base_url()?>settings/?settings=templates&group=signature"><?=lang('email_signature')?></a></li>
            </ul>
         </div>
         <?php }
            $set = array('theme','customize');
            if( in_array($load_setting, $set)){  ?>
         <?php  ?>
         <?php } ?>
         <?php $set = array('payments');
            if(in_array($load_setting, $set)){ $views = $this->input->get('view'); if($views != 'currency'){ ?>
         <a href="<?=base_url()?>settings/?settings=payments&view=currency" class="btn btn-primary btn-sm">
         <?=lang('currencies')?></a>
         <?php } }
            $set = array('system', 'validate');
            if( in_array($load_setting, $set)){  ?>
         <a href="<?=base_url()?>settings/?settings=system&view=categories" class="btn btn-primary btn-sm"><?=lang('category')?>
         </a>
         <a href="<?=base_url()?>settings/?settings=system&view=slack" class="btn btn-warning btn-sm">Slack</a>
         <a href="<?=base_url()?>settings/?settings=system&view=project" class="btn btn-info btn-sm"><?=lang('project_settings')?>
         </a>
         <a href="<?=base_url()?>settings/database" class="btn btn-success btn-sm"><i class="fa fa-cloud-download text"></i>
         <span class="text"><?=lang('database_backup')?></span>
         </a>
         <?php } ?>
         <?php if($load_setting == 'email'){  ?>
         <a href="<?=base_url()?>settings/?settings=email&view=alerts" class="btn btn-success btn-sm"><i class="fa fa-inbox text"></i>
         <span class="text"><?=lang('alert_settings')?></span>
         </a>
         <?php } ?>
      </div>
   </div>
   <div class="row">
      <div class="col-sm-4 col-md-4 col-lg-3 col-xs-12">
         <a class="btn btn-default visible-xs-inline-block m-r-xs m-b-20" data-toggle="class:show" data-target="#setting-nav"><i class="fa fa-reorder"></i></a>
         <div id="setting-nav" class="card-box settings-menu hidden-xs">
            <ul>
               <?php                
                  $menus = $this->db->where('hook','settings_menu_admin')->where('visible',1)->order_by('order','ASC')->get('hooks')->result();
                  
                      $approval_menu = $this->db->get_where('hooks',array('name'=>approval_settings))->result();
                                
                                 if(User::is_admin()) {
                  foreach ($menus as $menu) { 
                                     ?>
               <li class="<?php echo ($load_setting == $menu->route) ? 'active' : '';?>">
                  <a href="<?=base_url()?>settings/?settings=<?=$menu->route?>">
                  <i class="fa fa-fw <?=$menu->icon?>"></i>
                  <?=lang($menu->name)?>
                  </a>
               </li>
               <?php } }
                  elseif(User::is_staff())
                  {
                     foreach ($approval_menu as $app_menu) { ?>
               <li class="<?php echo ($load_setting == $app_menu->route) ? 'active' : '';?>">
                  <a href="<?=base_url()?>settings/?settings=<?=$app_menu->route?>">
                  <i class="fa fa-fw <?=$app_menu->icon?>"></i>
                  <?=lang($app_menu->name)?>
                  </a>
               </li>
               <?php } }
                  ?>
            </ul>
         </div>
      </div>
      <?php } ?>
      <?php if(User::is_staff()) { ?>
      <div class="col-sm-8 col-md-8 col-lg-9 col-xs-12">
         <?php } ?>
         <?php
            $expense_approvers = $this->db->get('expense_approvers')->result_array();
            
            ?>
         <div class="p-0">
            <div class="col-lg-12 p-0">
               <div class="panel panel-white">
                  <div class="panel-heading">
                     <h3 class="panel-title p-5">Approval Setting</h3>
                  </div>
                  <div class="panel-body">
                     <ul class="nav nav-tabs nav-tabs-solid m-b-20">
                        <li class="active">
							<a href="<?php echo base_url()?>settings/?settings=approval" id='expense_approval'>Expenses Approval</a>
                        </li>
                        <li class="">
							<a href="#" id='offer_app_view' >Offer Approval</a>
                        </li>
						<li class="" >
							<a href="#" id='leave_approval'>Leave Approval</a>
                        </li>
						
						<li class="" >
							<a href="#" id='resignation_notice'>Resignation Notice</a>
                        </li>
						
						
                     </ul>
                     <div class="content">
	<div class="row div_table-expenses">
                           <div class="col-sm-4 col-xs-3">
                              <h4 class="page-title"><?=lang('expenses')?></h4>
                           </div>
                           <div class="col-sm-8 col-xs-9 text-right m-b-0">
                              <!-- <?php if(User::is_staff() ) { ?> 
                                 <a href="<?=base_url()?>expenses/create" data-toggle="ajaxModal" title="<?=lang('create_expense')?>" class="btn btn-primary rounded pull-right"><i class="fa fa-plus"></i> <?=lang('create_expense')?></a>
                                 <?php } ?> -->
                           </div>
                        </div>
	<div class="row div_table-expenses">
                           <div class="col-md-12">
                              <div class="table-responsive">
                                 <table id="table-expenses" class="table table-striped custom-table m-b-0">
                                    <thead>
                                       <tr>
                                          <th style="width:5px; display:none;"></th>
                                          <th class=""><?=lang('project')?></th>
                                          <th class="col-currency"><?=lang('amount')?></th>
                                          <th class=""><?=lang('staff_name')?></th>
                                          <th class=""><?=lang('category')?></th>
                                          <th style="width:5px; display:none;"></th>
                                          <th class=""><?=lang('expense_date')?></th>
                                          <th class=""><?=lang('status')?></th>
                                          <?php if(User::is_staff()) {?>
                                          <th class=""><?=lang('expense_action')?></th>
                                          <?php } ?>			
                                       </tr>
                                    </thead>
                                    <tbody>
                                       <?php foreach ($expenses as $key => $e) { 
                                          $exp_id = $e->id;
                                          $exp_status = $this->db->get_where('expense_approvers',array('approvers'=>$this->session->userdata('user_id'),'expense'=> $exp_id))->row();
                                          $expensestatus = $this->db->get_where('expense_approvers',array('expense'=> $exp_id))->result();
                                          $status = array();
                                          foreach($expensestatus as $value)
                                          {
                                          	if($status[$value->status]) 
                                          	{
                                              	$status[$value->status] += 1;
                                          	}
                                          	else
                                          	{
                                          		$status[$value->status] = 1;
                                          	}		
                                          }
                                          
                                          $overall_array_count = count(array_filter($expensestatus));
                                          
                                          $expense_status = 'Pending';
                                          if(isset($status[$value->status]) && $overall_array_count>0 )
                                          {
                                          	foreach ($status as $key => $value) 
                                          	{
                                          		 if($key==1)
                                          		 {
                                          		 	if($status[$key]==$overall_array_count)
                                          		 	{
                                          		 		$expense_status = 'Approved';
                                          		 	}
                                          		 }
                                          		 if($key==2)
                                          		 {
                                                                  if($status[$key]==$overall_array_count)
                                          		 	{
                                          		 		$expense_status = 'Declined';
                                          		 	}
                                          		 }
                                          }
                                          
                                          }
                                          
                                          if($e->project != '' || $e->project != 'NULL'){
                                          $p = Project::by_id($e->project);
                                          }else{ $p = NULL; } ?>
                                       <tr id="row_id_<?=$e->id?>" >
                                          <td style="display:none;"><?=$e->id?></td>
                                          <td>
                                             <?php if($e->show_client != 'Yes'){ ?>
                                             <a href="<?=base_url()?>expenses/show/<?=$e->id?>" data-toggle="tooltip" data-title="<?=lang('show_to_client')?>" data-placement="right">
                                             <i class="fa fa-circle-o text-danger"></i>
                                             </a>
                                             <?php } ?>
                                             <?php if($e->receipt != NULL){ ?>
                                             <a href="<?=base_url()?>assets/uploads/<?=$e->receipt?>" target="_blank" data-toggle="tooltip" data-title="<?=$e->receipt?>" data-placement="right">
                                             <i class="fa fa-paperclip"></i>
                                             </a>
                                             <?php } ?>
                                             <?=($p != NULL) ? $p->project_title : 'N/A'; ?>
                                          </td>
                                          <td class="col-currency">
                                             <strong>
                                             <?php
                                                $cur = ($p != NULL) ? $p->currency : 'USD'; 
                                                $cur = ($e->client > 0) ? Client::client_currency($e->client)->code : $cur;
                                                ?>
                                             <?=Applib::format_currency($cur, $e->amount)?>
                                             </strong>
                                          </td>
                                          <td>
                                             <?php // echo ($e->client > 0) ? Client::view_by_id($e->client)->company_name : 'N/A'; ?>
                                             <?php					 
                                                $user_id = isset($e->added_by)?$e->added_by:'';					 
                                                $user_details = User::profile_info($user_id);
                                                $user_name = isset($user_details->fullname)?$user_details->fullname:'No Name'; 
                                                echo $user_name;
                                                ?>
                                          </td>
                                          <td>
                                             <?php echo App::get_category_by_id($e->category); ?>
                                          </td>
                                          <th style="width:5px; display:none;"><?php echo date('m/d/Y',strtotime($e->expense_date)); ?></th>
                                          <td>
                                             <?=strftime(config_item('date_format'), strtotime($e->expense_date))?>
                                          </td>
                                          <td>
                                             <?php 
                                                $approved_status = 'Pending';
                                                $label_color = 'warning';
                                                if(isset($e->admin_approved)&&!empty($e->admin_approved))
                                                {
                                                	if($e->admin_approved==1)
                                                	{
                                                		$approved_status = 'Approved';
                                                		$label_color = 'success';
                                                	}
                                                	if($e->admin_approved==2)
                                                	{
                                                		$approved_status = 'Declined';
                                                		$label_color = 'danger';
                                                	}
                                                }					 
                                                ?>
                                             <?php
                                                if(User::is_staff()) {
                                                
                                                
                                                	if($exp_status->status==1)
                                                	{
                                                		$approver_status = 'Approved';
                                                		$label_color1 = 'success';
                                                	}
                                                	else if($exp_status->status==2)
                                                	{
                                                		$approver_status = 'Declined';
                                                		$label_color1 = 'danger';
                                                	}
                                                	else if($exp_status->status==0)
                                                	{
                                                		$approver_status = 'Pending';
                                                		$label_color1 = 'warning';
                                                	}
                                                					
                                                ?> 
                                             <span class="small label label-<?=$label_color1?>">
                                             <?=$approver_status;?>
                                             </span>
                                             <?php  } 
                                                else if(User::is_admin())
                                                {
                                                
                                                		$label_color = 'warning';
                                                	
                                                		if($expense_status == Approved)
                                                		{
                                                			$approved_status = 'Approved';
                                                			$label_color = 'success';
                                                		}
                                                		if($expense_status == Declined)
                                                		{
                                                			$approved_status = 'Declined';
                                                			$label_color = 'danger';
                                                		}
                                                	
                                                ?> 
                                             <span class="small label label-<?=$label_color?>">
                                             <?php echo $expense_status?>
                                             </span>
                                             <?php } ?>
                                          </td>
                                          <?php if(User::is_staff()) {?>
                                          <td>
                                             <button class="btn btn-success" data-toggle="tooltip" title="<?=lang('accept_expense')?>" onclick="accept_expense(<?=$e->id?>)" ><i class="fa fa-check"></i></button>
                                             <button class="btn btn-danger" data-toggle="tooltip" title="<?=lang('decline_expense')?>" onclick="decline_expense(<?=$e->id?>)" ><i class="fa fa-times"></i></button>				
                                          </td>
                                          <?php } ?>
                                          
                                       </tr>
                                       <?php  }  ?>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>

	<div class="row div_offer_app"  style="display: none;">
		<div class="col-sm-4 col-xs-3">
			<h4 class="page-title">Offer</h4>
		</div>
	</div>
	<div class="row div_offer_app" style="display: none;">
		<div class="col-md-12">
			<div class="table-responsive">
				<table id="table-offer_app" class="table table-striped custom-table m-b-0">
					<thead>
						<tr>
							<th style="display:none;">#</th>
							<th>Name</th>
							<th>Title</th>
							<th>Email</th>
							<th>Status</th>
							<th>Resume</th>
							<?php if(!User::is_admin()) {?>
								<th>Action</th>
							<?php }?>
						</tr>
					</thead>
					<tbody> 
						 <?php
						 $jtype=array();
							 foreach ($offer_jobtype as $key => $value) {
							   $jtype[$value->id] = $value->job_type;
							 }
					$is_admin =  User::is_admin();
                    
                     foreach ($candi_list as $ck => $cv) { 
                   
                          $s_label = 'Not Approved';$s_label2 = 'Approve'; $class='success'; $color='#b31109';
                        if($cv->app_status == 2) {$s_label = 'Approved'; $s_label2 = 'Stop'; $class='warning';$color='#056928';}

                        /*if($cv->status == 3) $s_label = 'Send offer';
                        if($cv->status == 4) $s_label = 'Offer accepted';
                        if($cv->status == 5) $s_label = 'Declined';
                        if($cv->status == 6) $s_label = 'Onboard';*/


                    ?>
                    <tr> 
                      <td style="display:none;"><?=$cv->casid?></td>
                      <td><?=ucfirst($cv->candidate) ?></td> 
                      <td><?=ucfirst($jtype[$cv->job_type]) ?></td>
                      <td><?=$cv->email?></td>
                      <td style="color: <?=$color?>"><?=ucfirst($s_label)?></td>
                      <td> <a href="<?= base_url().''.$cv->file_path.'/'.$cv->filename ?>" target='_blank' >Download</td>
                       <?php if(!$is_admin) {?>
                       <td title="Change the status to"><button data-status='<?=$cv->app_status?>' data-offid='<?=$cv->casid?>' type="button" class="btn btn-<?=$class?> status_changebuttons"><?=$s_label2?></button></td>

                      <?php } }?>
					</tbody>
				</table>
			</div>
		</div>
	</div>


</div>
<div class="row leave_approval_div"  style="display: none;">
		<div class="col-sm-4 col-xs-3">
			<h4 class="page-title">Leave</h4>
		</div>
	</div>
<div class='leave_approval_div'  style="display: none;">
	

		<div class="row">
		<div class="col-md-12">
		<div class="table-responsive">
			<?php if(User::is_admin()) { 
			$leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname
										FROM `dgt_user_leaves` ul
										left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
										left join dgt_account_details ad on ad.user_id = ul.user_id 
										where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." order by ul.id  DESC ")->result_array();
		} else{
			$leave_list = array();
			// print_r($leave_list); exit;
		}
	   ?>
			 <table id="table-holidays" class="table table-striped custom-table m-b-0 AppendDataTables">
				<thead>
					<tr class="table_heading">
						<th> No </th>
						<th> User </th>
						<th> Leave Type </th>
						<th> Date From </th>
						<th> Date To </th>
						<th> Reason </th> 
						<th> No.of Days </th>  
						<th> Status </th>  
						<th class="text-right"> Options </th>  
					</tr>
				</thead>
				<tbody id="admin_leave_tbl">
					<?php 
					if(!empty($leave_list)){
					 foreach($leave_list as $key => $levs){  ?>
					
					<tr>
						<td><?=$key+1?></td>
						<td><a class="text-info" href="<?php echo base_url()?>leaves/show_leave/<?=$levs['user_id']?>"><?=$levs['fullname']?></a></td>
						<td><?=$levs['l_type']?></td>
						<td><?=date('d-m-Y',strtotime($levs['leave_from']))?></td>
						<td><?=date('d-m-Y',strtotime($levs['leave_to']))?></td>
						<td width="30%"><?=$levs['leave_reason']?></td>
						<td>
							<?php 
							echo $levs['leave_days'];
							if($levs['leave_day_type'] == 1){
								echo ' ( Full Day )';
							}else if($levs['leave_day_type'] == 2){
								echo ' ( First Half )';
							}else if($levs['leave_day_type'] == 3){
								echo ' ( Second Half )';
							}?>
						  </td>
						<td>
						<?php
						if($levs['status'] == 4){
								echo '<span class="label label-info"> TL - Approved</span><br>';
								echo '<span class="label label-danger"> Management - Pending</span>';
							}else if($levs['status'] == 7){
										echo '<span class="label label-danger"> Deleted </span>';
									}
							if($levs['status'] == 0){
								echo ' <span class="label" style="background:#D2691E"> Pending </span>';
							}else if($levs['status'] == 1){
								echo '<span class="label label-success"> Approved </span> ';
							}else if($levs['status'] == 2){
								echo '<span class="label label-danger"> Rejected</span>';
							}else if($levs['status'] == 3){
								echo '<span class="label label-danger"> Cancelled</span>';
							}
							?>
						</td>
						<td class="text-right"> 
						<?php // if($levs['status'] == 4){ ?>
							 <a  class="btn btn-success btn-xs"  
							 data-toggle="ajaxModal" href="<?=base_url()?>leaves/approve/management/<?=$levs['id']?>" title="Approve" data-original-title="Approve" >
								<i class="fa fa-thumbs-o-up"></i> 
							 </a>
						 <?php // } 
						 // if($levs['status'] == 0 || $levs['status'] == 1){
						 // if($levs['status'] == 4 ){ ?>     
							 <a class="btn btn-danger btn-xs"  
							 data-toggle="ajaxModal" href="<?=base_url()?>leaves/reject/management/<?=$levs['id']?>" title="Reject" data-original-title="Reject">
								<i class="fa fa-thumbs-o-down"></i> 
							 </a>
						 <?php // } ?>
						 <!--<a class="btn btn-danger btn-xs"  
							 data-toggle="ajaxModal" href="<?=base_url()?>leaves/delete/<?=$levs['id']?>" title="Delete" data-original-title="Delete">
								<i class="fa fa-trash-o"></i> 
						 </a>-->
						</td>
					</tr>
				 <?php  } ?>  
				 <?php  }else{ ?>
						 <tr><td class="text-center" colspan="9">No details were found</td></tr>
						 <?php } ?>  
				</tbody>
		   </table>    
	    </div>
		</div>
		</div>
		<!-- user leave end -->
		
                     </div>
					 
					 
					 
	<!--<div class="row resignation_div"  style="display: none;">
		<div class="col-sm-4 col-xs-3">
			<h4 class="page-title">Resignation Notice</h4>
		</div>
	</div>-->
<div class='resignation_div'  style="display: none;">
	

		<div class="row">
		<div class="col-md-12">
		<?php 
		//if(User::is_admin()) {
        $resignation_notice = $this->db->get('resignation_notice')->row_array();
        if(!empty($resignation_notice)){          
          $email_notification = explode(',', $resignation_notice['email_notification']);
          $notice_days = $resignation_notice['notice_days'];  
        }else{
          $email_notification = array();
          $notice_days = '';
        }
       ?>
		<form action="<?php echo base_url(); ?>settings/resignation_notice" id="tokbox_form" class="bs-example form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
               <h3>Resignation Notice</h3>
                    <!-- <input type="hidden" name="settings" value="offer_approval_setting"> -->
                    <div class="form-group">
                        <label class="control-label col-md-6">Email Notification <span class="text-danger">*</span></label></label>
                        <div class="col-md-6 approval-option">
                           <select class="select2-option form-control email_notification" multiple="multiple" style="width:100%" name="email_notification[]" > 
                                <optgroup label="Staff">                                  
                                <?php foreach (User::team() as $user){ ?>
                                    <option value="<?=$user->id?>" <?php if(in_array($user->id, $email_notification)){ echo "selected";}?> >
                                        <?=ucfirst(User::displayName($user->id))?>
                                    </option>
                                <?php } ?>
                                </optgroup> 
                          </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-6">Notice Days <span class="text-danger">*</span></label></label>
                        <div class="col-md-6 approval-option">
                         <input type="number" name="notice_days" class="form-control notice_days" value="<?php echo $notice_days;?>">
                        </div>
                    </div>

                     <div class="m-t-30">
                                              <div class="col-md-12 submit-section">
                          <button id="resignation_notice_set_btn" type="submit" class="btn btn-primary submit-btn">Save Changes</button>
                        </div>
                    </div>
        </form>
		</div>
		</div>
		<!-- user leave end -->
		
                     </div>
					 
					 
					 
					 
	
  
                  </div>
               </div>
            </div>
         </div>
         </form>
      </div>
   </div>
</div>
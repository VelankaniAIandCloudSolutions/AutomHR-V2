<?php 
// echo $this->session->userdata('branch_id');exit;
// $this->db->select_sum('leave_days');
// $total_count = $this->db->get_where('common_leave_types',array('status'=>'0','leave_id !='=>'8','leave_id !='=>'9'))->row()->leave_days;
$annual_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_type_id '=>'1','branch_id'=>$this->session->userdata('branch_id')))->row_array();
// echo "<pre>"; print_r($annual_leaves); exit; 

$carry_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_type_id '=>'2','branch_id'=>$this->session->userdata('branch_id')))->row_array();
$earned_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_type_id '=>'3','branch_id'=>$this->session->userdata('branch_id')))->row_array();
$sck_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_type_id '=>'4','branch_id'=>$this->session->userdata('branch_id')))->row_array();
$hospiatality_leaves = $this->db->get_where('common_leave_types',array('status'=>'0','leave_type_id '=>'5','branch_id'=>$this->session->userdata('branch_id')))->row_array();

// $total_hosp_leave = $this->db->get_where('user_leaves',array('user_id'=>$this->session->userdata('user_id'),'leave_type'=>'5'))->result_array();

// $total_count = ($annual_leaves['leave_days'] + $carry_leaves['leave_days'] + $earned_leaves['leave_days']);
$last_yr = date("Y",strtotime("-1 year"));
// echo $last_yr; exit;
$carry_days = $this->db->select_sum('leave_days')
					   ->from('dgt_user_leaves')
					   ->where('user_id',$this->session->userdata('user_id'))
					   ->like('leave_from',$last_yr)
					   ->like('leave_to',$last_yr)
					   ->get()->row()->leave_days;
$total_hosp_leave = $this->db->select_sum('leave_days')
							   ->from('dgt_user_leaves')
							   ->where('user_id',$this->session->userdata('user_id'))
							   ->where('leave_type','5')
							   ->where('status','1')
							   ->get()->row()->leave_days;
					   // echo $this->db->last_query(); exit;

$last_yr_leaves = $this->db->get_where('yearly_leaves',array('years'=>$last_yr))->row_array();
if(count($last_yr_leaves) != 0 )
{
	$l = json_decode($last_yr_leaves['total_leaves'],TRUE);

	$lst_anu_leaves = $l['annual_leaves'];
	$lst_cr_leaves = $l['cr_leaves'];
	$last_total = $lst_anu_leaves +  $lst_cr_leaves;
	if($carry_days != '')
	{
		$bl_leaves = $carry_days - $last_total; 
	}else{
		$bl_leaves = 0; 
	}
	// echo $bl_leaves; exit;
	if($bl_leaves < 0){			
		$ext_leaves = abs($bl_leaves);
	}else{
		$ext_leaves = 0;
	}
	if($ext_leaves == $carry_leaves['leave_days'])
	{
		$total_count = ($annual_leaves['leave_days'] + $carry_leaves['leave_days']);
	}
	if($ext_leaves > $carry_leaves['leave_days'])
	{
		$total_count = ($annual_leaves['leave_days'] + $carry_leaves['leave_days']);
	}
	if($ext_leaves < $carry_leaves['leave_days']){
		$total_count = ($annual_leaves['leave_days'] + $ext_leaves);
	}
	if($ext_leaves == 0)
	{
		$total_count = $annual_leaves['leave_days'];
	}


}else{
	$total_count = $annual_leaves['leave_days'];
}

	// echo $total_count; exit;

?>
<div class="content">

	<div class="row">
		<div class="col-sm-6 col-xs-3">
			<h4 class="page-title">Leaves</h4>
		</div>
		<div class="col-sm-6 col-xs-9 text-right m-b-30">
			<?php if (($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'admin') && ($this->session->userdata('user_type_name') != 'company_admin') && ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'superadmin')) { ?>
			<a href="javascript:;" class="btn btn-primary rounded pull-right New-Leave" onclick="$('.new_leave_reqst').show();$('#date_alert_msg').hide();" data-loginid="<?php echo $this->session->userdata('user_id'); ?>" ><i class="fa fa-plus"></i> <?='New Leave Request';?></a>
			<a href="javascript:;" class="btn btn-primary rounded pull-right New-Leave" onclick="$('.new_compenstory_leave_reqst').show();$('#date_alert_msg').hide();" data-loginid="<?php echo $this->session->userdata('user_id'); ?>" style="margin-right:10px;"><i class="fa fa-plus"></i> <?='New Compensatory Leave Request';?></a>
			<?php } ?>
		</div>
	</div>
	<?php  if($this->session->flashdata('alert_message')){?>
	<div class="panel panel-default" id="date_alert_msg">
		<div class="panel-heading font-bold" style="color:white; background:#FF0000">
			<i class="fa fa-info-circle"></i> Alert Details 
			<i class="fa fa-times pull-right" style="cursor:pointer" onclick="$('#date_alert_msg').hide();"></i>
		</div>
		<div class="panel-body">
			<p style="color:red"> Already you have make request for now requested Dates! Please Check...</p>
		</div>
	</div>
	<?php  }  ?>  
	
	<?php //$leav_types =  $this->db->query("SELECT * FROM `dgt_common_leave_types` where status = 0")->result_array();  ?> 
	
	<?php
	//$leav_types =  $this->db->get_where('common_leave_types',array('status'=>'0'))->db->group_by('leave_type_id')->result_array(); 
	$condition['status']  = '0';
	$leav_types =  $this->db->from('common_leave_types')->where($condition)->group_by('leave_type_id')->get()->result_array(); 
	?> 

	
	<?php if (($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'admin') || ($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'superadmin')) { 



		// $total_employees = $this->db->get_where('users',array('role_id'=>3,'activated'=>1,'banned'=>0))->result_array();

		$total_employees = $this->db->select('*')
						 ->from('users U')
						 ->join('account_details AD','U.id = AD.user_id')
						 ->where('U.role_id',3)
						 ->where('U.activated',1)
						 ->where('U.banned',0)
						 ->where('AD.branch_id',$this->session->userdata('branch_id'))
						 ->get()->result_array();

		$today_leaves = $this->db->query("SELECT ul.*,ad.fullname
										FROM `dgt_user_leaves` ul
										left join dgt_account_details ad on ad.user_id = ul.user_id 
										where DATE_FORMAT(ul.leave_to,'%Y-%m-%d') = ".date('Y-m-d')."
										and DATE_FORMAT(ul.leave_from,'%Y-%m-%d') = ".date('Y-m-d')." and ul.status='1'
										and ad.branch_id='".$this->session->userdata('branch_id')."' order by ul.id  DESC ")->result_array();
		
		
		// $this->db->get_where('user_leaves',array('leave_to'=>date('Y-m-d'),'leave_from'=>date('Y-m-d'),'status'=>1))->result_array();
		
		$pending_leaves = $this->db->query("SELECT ul.*,ad.fullname
										FROM `dgt_user_leaves` ul
										left join dgt_account_details ad on ad.user_id = ul.user_id 
										where ul.status='0'
										and ad.branch_id='".$this->session->userdata('branch_id')."' order by ul.id  DESC ")->result_array();
		// $this->db->get_where('user_leaves',array('status'=>0))->result_array();

		$present_employees = (count($total_employees) - count($today_leaves));

		?>

		<!-- Leave Statistics -->
					<?php /*<div class="row">
						<div class="col-md-3">
							<div class="stats-info">
								<h6>Today Presents</h6>
								<h4> <?php echo $present_employees; ?> / <?php echo count($total_employees); ?></h4>
							</div>
						</div>
						<div class="col-md-3">
							<div class="stats-info">
								<h6>Planned Leaves</h6>
								<h4><?php echo count($today_leaves); ?> <span>Today</span></h4>
							</div>
						</div>
						<div class="col-md-3">
							<div class="stats-info">
								<h6>Unplanned Leaves</h6>
								<h4>0 <span>Today</span></h4>
							</div>
						</div>
						<div class="col-md-3">
							<div class="stats-info">
								<h6>Pending Requests</h6>
								<h4><?php echo count($pending_leaves); ?></h4>
							</div>
						</div>
					</div>*/?>
					<!-- /Leave Statistics -->






	<!-- user leaves -->
		<form method="POST" id="leave_search_form">
			<div class="row filter-row" id="search_form_div">
				<div class="col-xs-6 col-sm-4 col-md-4 col-lg-2">
					<div class="form-group form-focus select-focus">
						<label class="control-label">Leave Type</label>
						<select class="select2-option floating form-control" id="ser_leave_type" name="ser_leave_type" >
							<option value=""> All Type of Leaves </option>
							<?php for($i = 0;$i < count($leav_types); $i++ ){ ?>
							<option value="<?php echo $leav_types[$i]['leave_type_id']; ?>" <?php if(!empty($_POST['ser_leave_type']) && $_POST['ser_leave_type']==$leav_types[$i]['leave_type_id']){ echo 'selected';}?>><?=$leav_types[$i]['leave_type']?></option>
							<?php } ?> 
						</select>
						<label id="ser_leave_type_error" class="error display-none" for="ser_leave_type">Select a Leave Type</label>
					</div>
				</div>
				<div class="col-xs-6 col-sm-4 col-md-4 col-lg-2">
					<div class="form-group form-focus select-focus">
						<label class="control-label">Leave Status</label>
						<?php //echo '<pre>';print_r($_POST);exit;?>
						<select class="select2-option floating form-control" id="ser_leave_sts" name="ser_leave_sts" >
							<option value="" <?php if(empty($_POST['ser_leave_sts']) || $_POST['ser_leave_sts']==''){ echo 'selected';}?>> All Status </option>
							<option value="0" <?php if(isset($_POST['ser_leave_sts']) && $_POST['ser_leave_sts']!='' && $_POST['ser_leave_sts']==0){ echo 'selected';}?>> Pending </option>
							<option value="1" <?php if(!empty($_POST['ser_leave_sts']) && $_POST['ser_leave_sts']==1){ echo 'selected';}?>> Approved </option>
							<option value="2" <?php if(!empty($_POST['ser_leave_sts']) && $_POST['ser_leave_sts']==2){ echo 'selected';}?>> Rejected </option>
						</select>
						<label id="ser_leave_sts_error" class="error display-none" for="ser_leave_sts">Select a status</label>
					</div>
				</div>
				<div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
					<div class="form-group form-focus">
						<label class="control-label">Employee Name</label>
						<input type="text" class="form-control floating" id="ser_leave_user_name" name="ser_leave_user_name" value="<?php if(!empty($_POST['ser_leave_user_name'])){ echo $_POST['ser_leave_user_name'];}?>">
						<label id="ser_leave_user_name_error" class="error display-none" for="ser_leave_user_name">Username Shouldn't be empty</label>
					</div>
				</div>
				<div class="col-sm-8 col-md-8 col-xs-12 col-lg-4 search_date">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-0">
							<div class="form-group form-focus">
							<div class="ref-icon"> 
								<label class="control-label">From</label>
								<input class="form-control floating leaves-datepicker" readonly type="text" data-date-format="dd-mm-yyyy" id="ser_leave_date_from" value="<?php if(!empty($_POST['ser_leave_date_from'])){ echo $_POST['ser_leave_date_from'];}?>" size="16" name="ser_leave_date_from">
								<i class="fa fa-refresh fa-clearicon" title="Clear To Date" onclick="$('#ser_leave_date_from').val('');$(this).parent().parent().removeClass('focused');"></i>
							</div>	
							</div>
							<label id="ser_leave_date_from_error" class="error display-none" for="ser_leave_date_from">Choose From date</label>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 p-0">
							<div class="form-group form-focus">
							<div class="ref-icon">
								<label class="control-label">To</label>
								<input class="form-control floating leaves-datepicker leaves-to-datepicker" readonly type="text" data-date-format="dd-mm-yyyy" id="ser_leave_date_to" value="<?php if(!empty($_POST['ser_leave_date_to'])){ echo $_POST['ser_leave_date_to'];}?>" size="16"  name="ser_leave_date_to">
								<i class="fa fa-refresh fa-clearicon" title="Clear To Date" onclick="$('#ser_leave_date_to').val('');$(this).parent().parent().removeClass('focused');"></i>
								 <label id="ser_leave_date_to_error" class="error display-none" for="ser_leave_date_to">Choose To date</label>
								</div>
							</div>
						</div>
				</div>
				<div class="col-sm-4 col-md-4 col-xs-6 col-lg-2">
					<a href="javascript:void(0)" class="btn btn-success btn-block" id="admin_search_leave"> Search </a>
				</div>
			</div>
		</form>

		<ul class="nav nav-tabs" style="margin-bottom:22px;">
			<li role="presentation" class="active nav_li" id="all_leaves_nav"><a href="javascript:void('0')" onclick="leave_tab('panel','all_leaves')">Leaves</a></li>
			<li role="presentation" class=" nav_li" id="compensatory_leave_request_nav"><a href="javascript:void('0')"  onclick="leave_tab('panel','compensatory_leave_request')"><?php echo lang('compen_request');?></a></li>
		</ul>
		<div class="row panel" id="compensatory_leave_request" style="display:none">
			<div class="col-md-12">
				<div class="table-responsive">
					<?php 
					
						$leave_list = $this->db->select('cl.*,AD.fullname')->from('compensatory_leave cl')->join('users U','U.id = cl.user_id')->join('account_details AD','AD.user_id = cl.user_id')->where("DATE_FORMAT(cl.leave_from,'%Y')",date('Y'))->order_by('id','desc')->get()->result_array();
			?>
					<table id="table_leaves" class="table table-striped custom-table m-b-0">
						<thead>
							<tr class="table_heading">
								<th><b> No </b></th>
								<th><b> Employee </b> </th>
								<th><b> Leave Type </b></th>
								<th><b> From </b></th>
								<th><b> To </b></th>
								<th><b> Reason </b></th> 
								<th><b> No.of Days </b> </th>  
								<th><b> Status </b></th>  
								<th class="text-right no-sort"><b>Action </b></th>  
							</tr>
						</thead>
						<tbody id="admin_leave_tbl">
							<?php 
							if(!empty($leave_list)){
							foreach($leave_list as $key => $levs){  ?>
							
							<tr>
								<td><?=$key+1?></td>
								<td><?=$levs['fullname']?></td>
								<td><?=lang('compen_request')?></td>
								<td><?=(!empty($levs['leave_from']))?date('d-m-Y',strtotime($levs['leave_from'])):''?></td>
								<td><?=(!empty($levs['leave_to']))?date('d-m-Y',strtotime($levs['leave_to'])):''?></td>
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
									else if($levs['status'] == 5){
										echo '<span class="label label-danger"> TL - Rejected</span>';
									}
									?>
								</td>
								<td class="text-right"> 
									<a  class="btn btn-success btn-xs"  
									data-toggle="ajaxModal" href="<?=base_url()?>leaves/approve_request/management/<?=$levs['id']?>" title="Approve" data-original-title="Approve" >
										<i class="fa fa-thumbs-o-up"></i> 
									</a>
							
									<a class="btn btn-danger btn-xs"  
									data-toggle="ajaxModal" href="<?=base_url()?>leaves/reject_request/management/<?=$levs['id']?>" title="Reject" data-original-title="Reject">
										<i class="fa fa-thumbs-o-down"></i> 
									</a>
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

		<div class="row panel" id="all_leaves">
		<div class="col-md-12">
		<div class="table-responsive">
			<?php 
			// $leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname
										// FROM `dgt_user_leaves` ul
										// left join dgt_common_leave_types lt on lt.leave_id = ul.leave_type
										// left join dgt_account_details ad on ad.user_id = ul.user_id 
										// where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." and 
										// ad.branch_id='".$this->session->userdata('branch_id')."' order by ul.id  DESC ")->result_array();
										
			
			// print_r($leave_list); exit;
			if($this->session->userdata('user_type_name') == 'company_admin') {
				$leave_list =$this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname
				FROM `dgt_user_leaves` ul
				left join dgt_common_leave_types lt on lt.leave_type_id = ul.leave_type
				left join dgt_account_details ad on ad.user_id = ul.user_id 
				where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." and ul.branch_id IN (".$branchid.") order by ul.id  DESC ")->result_array();
			}
			else
			{
				$lsql="SELECT ul.*,lt.leave_type as l_type,ad.fullname
				FROM `dgt_user_leaves` ul
				left join dgt_common_leave_types lt on lt.leave_type_id = ul.leave_type and lt.branch_id = ul.branch_id
				left join dgt_account_details ad on ad.user_id = ul.user_id 
				where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')."  order by ul.id  DESC";
				
				$leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname
				FROM `dgt_user_leaves` ul
				left join dgt_common_leave_types lt on lt.leave_type_id = ul.leave_type and lt.branch_id = ul.branch_id
				left join dgt_account_details ad on ad.user_id = ul.user_id 
				where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')."  order by ul.id  DESC ")->result_array();
				// echo '<pre>';print_r($lsql); exit;
			}
	   ?>
			 <table id="table_leaves1" class="table table-striped custom-table m-b-0">
				<thead>
					<tr class="table_heading">
						<th><b> No </b></th>
						<th><b> Employee </b> </th>
						<th><b> Leave Type </b></th>
						<th><b> From </b></th>
						<th><b> To </b></th>
						<th><b> Reason </b></th> 
						<th><b> No.of Days </b> </th>  
						<th><b> Status </b></th>  
						<th class="text-right no-sort"><b>Action </b></th>  
					</tr>
				</thead>
				<tbody id="admin_leave_tbl">
					<?php 
					/*if(!empty($leave_list)){
					 foreach($leave_list as $key => $levs){  ?>
					
					<tr>
						<td><?=$key+1?></td>
						<td><a class="text-info" href="<?php echo base_url()?>leaves/show_leave/<?=$levs['user_id']?>"><?=$levs['fullname']?></a></td>
						<td><?=$levs['l_type']?></td>
						<td><?=(!empty($levs['leave_from']))?date('d-m-Y',strtotime($levs['leave_from'])):''?></td>
						<td><?=(!empty($levs['leave_to']))?date('d-m-Y',strtotime($levs['leave_to'])):''?></td>
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
						 <?php }*/ ?>  
				</tbody>
		   </table>    
	    </div>
		</div>
		</div>
		<!-- user leave end -->
		<?php } ?>
		<?php
		if($this->session->userdata('user_type_name') == 'company_admin') { ?>


		<ul class="nav nav-tabs" style="margin-bottom:22px;">
			<li role="presentation" class="active nav_li" id="all_leaves_nav"><a href="javascript:void('0')" onclick="leave_tab('panel','all_leaves')">Leaves</a></li>
			<li role="presentation" class=" nav_li" id="compensatory_leave_request_nav"><a href="javascript:void('0')"  onclick="leave_tab('panel','compensatory_leave_request')"><?php echo lang('compen_request');?></a></li>
		</ul>

		<div class="row panel" id="compensatory_leave_request" style="display:none">
			<div class="col-md-12">
				<div class="table-responsive">
				<?php 
					$branch_details = $this->db->get_where('dgt_assigned_entities',array('user_id'=>$this->session->userdata('user_id')))->result_array(); 
					$branches = array_column($branch_details, 'branch_id');

					$leave_list = $this->db->select('cl.*,AD.fullname')->from('compensatory_leave cl')->join('users U','U.id = cl.user_id')->join('account_details AD','AD.user_id = cl.user_id')->where("DATE_FORMAT(cl.leave_from,'%Y')",date('Y'))->where_in("cl.branch_id",$branches)->order_by('id','desc')->get()->result_array();
				?>
					<table id="table-holidays" class="table table-striped custom-table m-b-0">
						<thead>
							<tr class="table_heading">
								<th><b> No </b></th>
								<th><b> Employee </b> </th>
								<th><b> Leave Type </b></th>
								<th><b> From </b></th>
								<th><b> To </b></th>
								<th><b> Reason </b></th> 
								<th><b> No.of Days </b> </th>  
								<th><b> Status </b></th>  
								<th class="text-right no-sort"><b>Action </b></th>  
							</tr>
						</thead>
						<tbody id="admin_leave_tbl">
							<?php 
							if(!empty($leave_list)){
							foreach($leave_list as $key => $levs){  ?>
							
							<tr>
								<td><?=$key+1?></td>
								<td><?=$levs['fullname']?></td>
								<td><?=lang('compen_request')?></td>
								<td><?=(!empty($levs['leave_from']))?date('d-m-Y',strtotime($levs['leave_from'])):''?></td>
								<td><?=(!empty($levs['leave_to']))?date('d-m-Y',strtotime($levs['leave_to'])):''?></td>
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
									}
									?>
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
									else if($levs['status'] == 5){
										echo '<span class="label label-danger"> TL - Rejected</span>';
									}
									?>
								</td>
								<td class="text-right"> 
									<a  class="btn btn-success btn-xs"  
									data-toggle="ajaxModal" href="<?=base_url()?>leaves/approve_request/management/<?=$levs['id']?>" title="Approve" data-original-title="Approve" >
										<i class="fa fa-thumbs-o-up"></i> 
									</a>    
									<a class="btn btn-danger btn-xs"  
									data-toggle="ajaxModal" href="<?=base_url()?>leaves/reject_request/management/<?=$levs['id']?>" title="Reject" data-original-title="Reject">
										<i class="fa fa-thumbs-o-down"></i> 
									</a>
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

		<div class="row panel" id="all_leaves">
			<div class="col-md-12">
				<div class="table-responsive">
				<?php 
					$branch_details = $this->db->get_where('dgt_assigned_entities',array('user_id'=>$this->session->userdata('user_id')))->result_array(); 
					$branchid = implode(',',array_column($branch_details, 'branch_id'));
					$leave_list =$this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname
					FROM `dgt_user_leaves` ul
					left join dgt_common_leave_types lt on lt.leave_type_id = ul.leave_type
					left join dgt_account_details ad on ad.user_id = ul.user_id 
					where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." and ul.branch_id IN (".$branchid.") group by ul.id order by ul.id  DESC ")->result_array();
				?>
					<table id="table-holidays" class="table table-striped custom-table m-b-0">
						<thead>
							<tr class="table_heading">
								<th><b> No </b></th>
								<th><b> Employee </b> </th>
								<th><b> Leave Type </b></th>
								<th><b> From </b></th>
								<th><b> To </b></th>
								<th><b> Reason </b></th> 
								<th><b> No.of Days </b> </th>  
								<th><b> Status </b></th>  
								<th class="text-right no-sort"><b>Action </b></th>  
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
								<td><?=(!empty($levs['leave_from']))?date('d-m-Y',strtotime($levs['leave_from'])):''?></td>
								<td><?=(!empty($levs['leave_to']))?date('d-m-Y',strtotime($levs['leave_to'])):'';?></td>
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
									<a  class="btn btn-success btn-xs"  
									data-toggle="ajaxModal" href="<?=base_url()?>leaves/approve_request/management/<?=$levs['id']?>" title="Approve" data-original-title="Approve" >
										<i class="fa fa-thumbs-o-up"></i> 
									</a>    
									<a class="btn btn-danger btn-xs"  
									data-toggle="ajaxModal" href="<?=base_url()?>leaves/reject_request/management/<?=$levs['id']?>" title="Reject" data-original-title="Reject">
										<i class="fa fa-thumbs-o-down"></i> 
									</a>
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
		<?php }
		 ?>
		<?php if (($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'superadmin') && ($this->session->userdata('user_type_name') != 'company_admin') && ($this->tank_auth->user_role($this->tank_auth->get_role_id()) != 'admin')) { 

				$user_id = $this->session->userdata('user_id');
		  		$total_leaves = array();
		  		$normal_leaves = array();
		  		$medical_leaves = array();
		  		$sick_leaves = array();
		  		$leaves = $this->Leaves_model->check_leavesById($user_id);
		  		$nor_leaves = $this->Leaves_model->check_leavesBycat($user_id,'1');
		  		$med_leaves = $this->Leaves_model->check_leavesBycat($user_id,'2');
		  		$sick_leav = $this->Leaves_model->check_leavesBycat($user_id,'4');
		  		$sk_leaves = $this->Leaves_model->check_leavesBycat($user_id,'3');
		  		for($i=0;$i<count($leaves);$i++)
		  		{
		  			$total_leaves[] = $leaves[$i]['leave_days'];
		  		}
		  		foreach($nor_leaves as $n_leave)
		  		{
		  			$normal_leaves[] = $n_leave['leave_days'];
		  		}
		  		foreach($med_leaves as $md_leave)
		  		{
		  			$medical_leaves[] = $md_leave['leave_days'];
		  		}
		  		foreach($sk_leaves as $sk_leave)
		  		{
		  			$sick_leaves[] = $sk_leave['leave_days'];
		  		}
		  		foreach($sick_leav as $sick_lea)
		  		{
		  			$all_sick_leaves[] = $sick_lea['leave_days'];
		  		}

		  		$t_leaves = array_sum($total_leaves);
		  		$total_normal_leaves = $this->db->get_where('leave_types',array('id'=>1))->row_array();
		  		$lop = ($t_leaves - $total_normal_leaves['leave_days']);
		  		if($lop > 0 )
		  		{
		  			$lop_days = $lop;
		  		}else{
		  			$lop_days = 0;
		  		}

		  		$re_leaves = (12 - $t_leaves);

		  		$an_leaves        = array();
		  		$crfd_leaves      = array();
		  		$ernd_leaves      = array();
		  		$anu_leaves       = $this->Leaves_model->check_leavesBycat($user_id,'1');
		  		$cr_leaves 		  = $this->Leaves_model->check_leavesBycat($user_id,'2');
		  		$er_leaves 		  = $this->Leaves_model->check_leavesBycat($user_id,'3');
		  		foreach($anu_leaves as $anu_leave)
		  		{
		  			$an_leaves[] = $anu_leave['leave_days'];
		  		}
		  		foreach($cr_leaves as $cr_leave)
		  		{
		  			$crfd_leaves[] = $cr_leave['leave_days'];
		  		}
		  		foreach($er_leaves as $er_leave)
		  		{
		  			$ernd_leaves[] = $er_leave['leave_days'];
		  		}

		  		// $tot_leave_count = (array_sum($an_leaves) + array_sum($crfd_leaves) + array_sum($ernd_leaves));
		  		$tot_leave_count = (array_sum($an_leaves) + array_sum($crfd_leaves));

		  		$tot_sk_leaves = array_sum($all_sick_leaves)?array_sum($all_sick_leaves):'0';


		  		$extra_leaves = $this->db->get_where('assigned_policy_user',array('user_id'=>$user_id));

		  		$extra_policy_leaves = array();
		  		$all_extra_policy_leaves = array();

		  		foreach ($extra_leaves->result_array() as $extra) {
		  			$extra_days = $this->db->get_where('custom_policy',array('policy_id'=>$extra['policy_id']))->row_array();
		  			$extra_policy_leaves[] = $extra_days['policy_leave_days'];
		  		}

		  		$user_detail = $this->db->get_where('account_details',array('user_id'=>$user_id))->row_array();

		  		$maternity_leaves = $this->db->get_where('common_leave_types',array('leave_type_id'=>'6'))->row_array();
		  		$paternity_leaves = $this->db->get_where('common_leave_types',array('leave_type_id'=>'7'))->row_array();



		  		$total_maternity_leave = $this->db->select_sum('leave_days')
												  ->from('dgt_user_leaves')
												  ->where('user_id',$this->session->userdata('user_id'))
												  ->where('leave_type','6')
												  ->where('status','1')
												  ->get()->row()->leave_days;


		  		$total_paternity_leave = $this->db->select_sum('leave_days')
												  ->from('dgt_user_leaves')
												  ->where('user_id',$this->session->userdata('user_id'))
												  ->where('leave_type','7')
												  ->where('status','1')
												  ->get()->row()->leave_days;



		  		$cr_yr = date('Y');
		  		$total_user_leaves = $this->db->select_sum('leave_days')
									   ->from('dgt_user_leaves')
									   ->where('user_id',$this->session->userdata('user_id'))
									   ->where('status','1')
									   ->where('leave_type','1')
									   ->like('leave_from',$cr_yr)
									   ->like('leave_to',$cr_yr)
									   ->get()->row()->leave_days;

				if($extra_leaves->num_rows() != 0){
					$total_count = ($total_count + array_sum($extra_policy_leaves));
				}


				$sk_lops = ($sck_leaves['leave_days'] - $tot_sk_leaves);
				if($sk_lops < 0 )
				{
					$sick_lop = abs($sk_lops);
				}else{
					$sick_lop = 0;
				}
				$tot_anu_count = ($total_count - $total_user_leaves);
				if($tot_anu_count < 0 )
				{
					$anu_lop = abs($tot_anu_count);
				}else{
					$anu_lop = 0;
				}
				$tot_hosp_count = ($hospiatality_leaves['leave_days'] - $total_hosp_leave);
				if($tot_hosp_count < 0 )
				{
					$hosp_lop = abs($tot_hosp_count);
				}else{
					$hosp_lop = 0;
				}

				$total_lop = ($anu_lop + $sick_lop + $hosp_lop);


				// Maternity Leave Conditions..

			$doj = $user_detail['doj'];
			$cr_date = date('Y-m-d');

			$ts1 = strtotime($doj);
			$ts2 = strtotime($cr_date);
			$year1 = date('Y', $ts1);
			$year2 = date('Y', $ts2);
			$month1 = date('m', $ts1);
			$month2 = date('m', $ts2);
			$job_experience = (($year2 - $year1) * 12) + ($month2 - $month1);


		 ?>

			<!-- Leave Statistics -->
					<?php /*<div class="row">
						<div class="col-md-3">
							<div class="stats-info">
								<h6>Annual Leave</h6>
								<?php if($total_user_leaves != 0){ $t_anu_leaves = $total_user_leaves; }else{ $t_anu_leaves = 0; } ?>
								<h4><?php echo $t_anu_leaves.' / '.$total_count; ?></h4>
							</div>
						</div>
						<?php
								if($sck_leaves['status'] != 1){ ?>
							<div class="col-md-3">
								<div class="stats-info">
									<h6>Sick Leave</h6>
									<h4><?php echo $tot_sk_leaves.' / '.$sck_leaves['leave_days']; ?></h4>
								</div>
							</div>
						<?php } ?>
						<?php // if($user_detail['gender'] == 'female'){ 
								if($total_maternity_leave != 0){
							?>
							<div class="col-md-3">
								<div class="stats-info">
									<h6>Maternity</h6>
									<h4><?php echo $maternity_leaves['leave_days']; ?></h4>
								</div>
							</div>
						<?php } // } ?>
						<?php 
								// if($paternity_leaves['status'] != 1){
								// if($user_detail['gender'] == 'male'){ 
								if($total_paternity_leave != 0){
							?>
							<div class="col-md-3">
								<div class="stats-info">
									<h6>Paternity</h6>
									<h4><?php echo $paternity_leaves['leave_days']; ?></h4>
								</div>
							</div>
						<?php } // } ?>
						<?php  if($total_hosp_leave != 0){ ?>
							<div class="col-md-3">
								<div class="stats-info">
									<h6>Hospitalisation Leaves</h6>
									<h4><?php echo $total_hosp_leave.' / '.$hospiatality_leaves['leave_days']; ?></h4>
								</div>
							</div>
						<?php  } ?>

						<div class="col-md-3">
							<div class="stats-info">
								<h6>Total Leaves</h6>
								<h4><?php echo $t_leaves; ?></h4>
							</div>
						</div>
						<div class="col-md-3">
							<div class="stats-info">
								<h6>Loss of Pay</h6>
								<h4><?php echo $total_lop; ?></h4>
							</div>
						</div>
					</div>*/?>
					<!-- /Leave Statistics -->

		<!-- user leaves -->

		<div class="panel panel-white new_leave_reqst" style="display:none">
		<div class="panel-heading">
			<h3 class="panel-title">New Leave Request</h3>
		</div>
		
		<div class="panel-body"> 
			<?php $attributes = array('class' => 'bs-example form-horizontal','id'=> 'employeesAddLeave');
			echo form_open(base_url().'leaves/add',$attributes); ?> 	
				<div class="form-group">
					<label class="col-lg-2 control-label"> Leave Type <span class="text-danger">*</span></label>
					<div class="col-lg-4">
						<select class="select2-option form-control" onchange="Getholidays();" style="width:100%;" id="req_leave_type" name="req_leave_type"> 
							<option value=""> -- Select Leave Type -- </option>
							<?php 
							// $all_leave_types = $this->db->get_where('common_leave_types',array('status'=>'0','leave_type_id !='=>'8','leave_type_id !='=>'9','branch_id'=>$this->session->userdata('branch_id')))->result_array();
							$all_leave_types = $this->db->get_where('common_leave_types',array('status'=>'0','branch_id'=>$this->session->userdata('branch_id'),'leave_days >'=>0))->result_array();
							// echo '<pre>';print_r($all_leave_types);exit;
							foreach($all_leave_types as $all_leave){
							    	if($job_experience < 3){ // More than 3 months
							    // if(($all_leave['leave_type_id'] != 2) && ($all_leave['leave_type_id'] != 6) && ($all_leave['leave_type_id'] != 7) && ($all_leave['leave_type_id'] != 8) && ($all_leave['leave_type_id'] != 9)){
							    if(($all_leave['leave_type_id'] != 2)){
							?>
								<option value="<?=$all_leave['leave_type_id']?>"><?=$all_leave['leave_type']?></option>
							<?php } }
							
							// elseif(($all_leave['leave_type_id'] != 2) && ($all_leave['leave_type_id'] != 8) && ($all_leave['leave_type_id'] != 9)){ 
							elseif(($all_leave['leave_type_id'] != 2)){ 
							
							?>
							
							
								<option value="<?=$all_leave['leave_type_id']?>"><?=$all_leave['leave_type']?></option>
							<?php } } ?>       
							
						</select>
					</div>
				</div>
				
				<div id="holiday_div" style="display:none;"  class="form-group">
					<label class="col-lg-2 control-label"> Holidays <span class="text-danger">*</span></label>
					<div class="col-lg-4">
						<select onchange="Getholidaydates();" class="select2-option form-control" style="width:100%;" id="holiday_id" > 
							<option value=""> -- Select Holidays -- </option>
							<?php 
							// $all_leave_types = $this->db->get_where('common_leave_types',array('status'=>'0','leave_type_id !='=>'8','leave_type_id !='=>'9','branch_id'=>$this->session->userdata('branch_id')))->result_array();
							$yr=date('Y');
							$this->db->select('*');
							$this->db->from('holidays');
							$this->db->where('status',0);
							$this->db->where('branch_id',$this->session->userdata('branch_id'));
							$this->db->where('DATE_FORMAT(holiday_date,"%Y")',$yr);
							$this->db->where('holiday_national',0);
							$holiday  = $this->db->get()->result_array();
							
							// echo '<pre>';print_r($this->db->last_query());//exit;
							foreach($holiday as $holid){
							?>
								<option value="<?=$holid['id']?>"><?=$holid['title']?></option>
							<?php 
							}
							?>  
							
						</select>
					</div>
				</div>
				
				
				<div class="form-group">
					<label class="col-lg-2 control-label">From <span class="text-danger">*</span></label>
					<div class="col-lg-4">
						<input class="form-control " readonly size="16" type="text"
						onchange="leave_days_calc();"
						  value="" name="req_leave_date_from" id="req_leave_date_from" data-date-format="dd-mm-yyyy" > 
						  
						  <input class="form-control req_leave_date_from" readonly size="16" type="text"
						 name="req_leave_date_from" style="display: none;" > 
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">To <span class="text-danger">*</span></label>
					<div class="col-lg-4">
					<input class=" form-control " readonly size="16" type="text"  
					onchange="leave_days_calc();"
					value="" name="req_leave_date_to" id="req_leave_date_to" data-date-format="dd-mm-yyyy" > 
					<input class="req_leave_date_to form-control " readonly size="16" type="text"  
					 name="req_leave_date_to" style="display: none;" > 
					
					</div>
				</div>
				<div class="form-group" style="display:none" id="leave_day_type">
					<label class="col-lg-2 control-label">  &nbsp; </label>
					<div class="col-lg-4"> 
					 Full Day <input type="radio" class="relativ-radio" name="req_leave_day_type" value="1" checked="checked" onclick="leave_day_type();"> 
					 &nbsp; First Half <input type="radio" class="relativ-radio" name="req_leave_day_type" value="2" onclick="leave_day_type();"> 
					 &nbsp; Second Half <input type="radio" class="relativ-radio" name="req_leave_day_type" value="3" onclick="leave_day_type();">
					 </div>
				</div> 
				<div class="form-group">
					<label class="col-lg-2 control-label"> Number of days </label>
					<div class="col-lg-4">
						<input type="text" name="req_leave_count" class="form-control" id="req_leave_count" value="" readonly="readonly">
						<?php $teamlead_details = $this->db->get_where('dgt_users',array('id'=>$this->session->userdata('user_id')))->row_array(); ?>
						<input type="hidden" name="teamlead_id" id="teamlead_id" value="<?php echo $teamlead_details['teamlead_id']; ?>">
						<!-- <span style="color:red;display: none;" id="lop_call">LOP</span> -->
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label"> Leave reason <span class="text-danger">*</span></label>
					<div class="col-lg-4">
						<textarea class="form-control" name="req_leave_reason" id="staff_leave_reason"></textarea>
					</div>
				</div> 
				<div class="form-group">
					<label class="col-lg-2 control-label"> &nbsp; </label>
					<div class="col-lg-4">
						<button class="btn btn-success" type="submit" id="employee_add_leave"> Send Leave Request </button>
						<button class="btn btn-danger" type="button" onclick="$('.new_leave_reqst').hide();"> Cancel </button>
					 </div>
				</div> 
				
			</form> 
		</div>

		</div>

		<div class="panel panel-white new_compenstory_leave_reqst" style="display:none">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo lang('new_compen_req');?></h3>
		</div>
		
		<div class="panel-body"> 
			<?php $attributes = array('class' => 'bs-example form-horizontal','id'=> 'employeesAddCompenstoryLeave');
			echo form_open(base_url().'leaves/addCompenstory',$attributes); ?> 	
				<div class="form-group">
					<label class="col-lg-2 control-label"> Leave Type <span class="text-danger">*</span></label>
					<div class="col-lg-4">
						<select class="select2-option form-control" style="width:100%;" id="req_leave_type" name="req_leave_type" required> 
							<option value=""> -- Select Leave Type -- </option>
							<option value="47"><?=lang('compenstory_leave');?></option> 
							
						</select>
					</div>
				</div>
				<div   class="form-group">
					<label class="col-lg-2 control-label"> Leave date <span class="text-danger">*</span></label>
					<div class="col-lg-4">
						<select onchange="leave_dates(this.value);" class="select2-option form-control" style="width:100%;" id="leave_date" > 
							<option value=""> -- Select Leave Date -- </option>
							<?php 
							if(!empty($cmp_offs)){
								foreach($cmp_offs as $cmp_off1){
							?>
									<option value="<?php echo date('d-m-Y',strtotime($cmp_off1['dates'])).'_'.$cmp_off1['worked']; ?>"><?=date('d-m-Y',strtotime($cmp_off1['dates']))?></option>
							<?php 
								}
							}
							?>  
							
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">From <span class="text-danger">*</span></label>
					<div class="col-lg-4">
						<input class="form-control" readonly size="16" type="text" name="req_compen_leave_date_from" id="req_compen_leave_date_from1" data-date-format="dd-mm-yyyy" required> 
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label">To <span class="text-danger">*</span></label>
					<div class="col-lg-4">
						<input class=" form-control" readonly size="16" type="text"  name="req_compen_leave_date_to" id="req_compen_leave_date_to1"  required> 
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-2 control-label"> Number of days </label>
					<div class="col-lg-4">
						<input type="text" name="req_compen_leave_count" class="form-control" id="req_compen_leave_count" value="" readonly="readonly" required>
						<?php $teamlead_details = $this->db->get_where('dgt_users',array('id'=>$this->session->userdata('user_id')))->row_array(); ?>
						<input type="hidden" name="teamlead_id"  value="<?php echo $teamlead_details['teamlead_id']; ?>">
						<input type="hidden" id="user_id" value="<?php echo $this->session->userdata('user_id'); ?>">
						
					</div>
				</div>
							<?php /*
				<div class="form-group" style="display:none" id="compen_leave_day_type">
					<label class="col-lg-2 control-label">  &nbsp; </label>
					<div class="col-lg-4"> 
					 Full Day <input type="radio" class="relativ-radio" name="req_compen_leave_day_type" value="1" checked="checked" onclick="compen_leave_day_type();"> 
					 &nbsp; First Half <input type="radio" class="relativ-radio" name="req_compen_leave_day_type" value="2" onclick="compen_leave_day_type();"> 
					 &nbsp; Second Half <input type="radio" class="relativ-radio" name="req_compen_leave_day_type" value="3" onclick="compen_leave_day_type();">
					 </div>
				</div> */?>
				<div class="form-group">
					<label class="col-lg-2 control-label"> Leave reason <span class="text-danger">*</span></label>
					<div class="col-lg-4">
						<textarea class="form-control" name="req_leave_reason" id="staff_compen_leave_reason" required></textarea>
					</div>
				</div> 
				<div class="form-group">
					<label class="col-lg-2 control-label"> &nbsp; </label>
					<div class="col-lg-4">
						<button class="btn btn-success" type="submit" id="employee_add_compen_leave"> Send Leave Request </button>
						<button class="btn btn-danger" type="button" onclick="$('.new_compenstory_leave_reqst').hide();"> Cancel </button>
					 </div>
				</div> 
				
			</form> 
		</div>



	</div> 

	
	<ul class="nav nav-tabs" style="margin-bottom:22px;">
		<li role="presentation" class="active nav_li" id="team_leaves_nav"><a href="javascript:void('0')" onclick="leave_tab('panel','team_leaves')">Leaves</a></li>
		<li role="presentation" class=" nav_li" id="team_leave_request_nav"><a href="javascript:void('0')"  onclick="leave_tab('panel','team_leave_request')"><?php echo lang('compen_request');?></a></li>
	</ul>
	<?php 
			$check_teamlead = $this->db->get_where('dgt_users',array('id'=>$this->session->userdata('user_id')))->row_array(); 
			$user_role = $this->session->userdata('user_type_name');
			if($user_role =='employee')
			{
				$check_teamlead['is_teamlead'] = 'no';
			}
			else{
				$check_teamlead['is_teamlead'] == 'yes';
			}
			if($check_teamlead['is_teamlead'] == 'yes'){
			?>
			<input type="radio" name="leaves_type"  class="ChooseType" value="personal"> Personal
			<input type="radio" name="leaves_type"  class="ChooseType" value="team" checked> Team
			<input type="hidden" value="0" id="check_compensatory">
				<!-- <a href="#" class="p_link" data-typ="personal">Personal Leaves</a> -->
				<!-- <a href="#" class="p_link" data-typ="team" style="display: none;">Team Leaves</a> -->
			<?php } ?>
	<div class="panel panel-table" id="team_leave_request" style="display:none">
			
		<div class="panel-body">
		
			<div class="table-responsive">
				<?php 

					
					if($check_teamlead['is_teamlead'] == 'yes')
					{
						$leave_list = $this->db->select('*')->from('compensatory_leave')->where('status!=','7')->where('teamlead_id',$check_teamlead['id'])->where("DATE_FORMAT(leave_from,'%Y')",date('Y'))->order_by('id','desc')->get()->result_array();
					}
					else
					{
						$leave_list = $this->db->select('*')->from('compensatory_leave')->where('branch_id',$this->session->userdata('branch_id'))->where('status!=','6')->where('user_id',$this->tank_auth->get_user_id())->where("DATE_FORMAT(leave_from,'%Y')",date('Y'))->order_by('id','desc')->get()->result_array();
					}
					
				?>
				<table  class="table table-striped custom-table m-b-0">
					<thead>
						<tr>
							<th> No </th>
							<th> Leave Type </th>
							<th> From </th>
							<th> To </th>
							<th> Reason </th> 
							<th> No.of Days </th>  
							<th> Status </th>  
							<th class="no-sort"> Action </th>  
						</tr>
					</thead>
					<tbody>
						<?php 
						if(!empty($leave_list)){
						foreach($leave_list as $key => $levs){  ?>
						<tr>
							<td><?=$key+1?></td>
							<td><?=lang('compen_request')?></td>
							<td><?=(!empty($levs['leave_from']))?date('d-m-Y',strtotime($levs['leave_from'])):''?></td>
							<td><?=(!empty($levs['leave_to']))?date('d-m-Y',strtotime($levs['leave_to'])):''?></td>
							<td><?=$levs['leave_reason']?></td>
							<td>
								<?php 
								echo $levs['leave_days'];
								if($levs['leave_day_type'] == 1){
									echo ' ( Full Day )';
								}else if($levs['leave_day_type'] == 2){
									echo ' ( First Half )';
								}else if($levs['leave_day_type'] == 3){
									echo ' ( Second Half )';
								}
								?>
							</td>
							<td>
							<?php
								if($levs['status'] == 0){
									if($check_teamlead['is_teamlead'] == 'no')
									echo '<span class="label" style="background:#D2691E"> Pending </span>';
									if($check_teamlead['is_teamlead'] == 'yes')
									echo '<span class="label" style="background:#D2691E"> Requested </span>';
								}else if($levs['status'] == 1){
									echo '<span class="label label-success"> Approved </span> ';
								}else if($levs['status'] == 2){
									echo '<span class="label label-danger"> Rejected</span>';
								}else if($levs['status'] == 3){
									echo '<span class="label label-danger"> Cancelled</span>';
								}else if($levs['status'] == 4){
									echo '<span class="label label-info"> TL - Approved</span><br>';
									echo '<span class="label label-danger"> Management - Pending</span>';
								}else if($levs['status'] == 5){
									echo '<span class="label label-danger"> TL - Rejected</span>';
								}else if($levs['status'] == 7){
									echo '<span class="label label-danger"> Deleted </span>';
								}
							?>
							</td>
							<td> 
								<?php if($check_teamlead['is_teamlead'] == 'no'){ ?>
							<?php if($levs['status'] == 0){ ?> 
									<a class="btn btn-danger btn-xs"  
									data-toggle="ajaxModal" href="<?=base_url()?>leaves/compen_cancel/<?=$levs['id']?>" title="Cancel" data-original-title="Cancel">
									<i class="fa fa-times"></i> 
									</a>
							<?php } }
							if($check_teamlead['is_teamlead'] == 'yes'){ 
								if(($levs['status'] != 3) &&  ($levs['status'] != 1)){
								?>
									<a  class="btn btn-success btn-xs"  
									data-toggle="ajaxModal" href="<?=base_url()?>leaves/approve_request/teamlead/<?=$levs['id']?>" title="Approve" data-original-title="Approve" >
									<i class="fa fa-thumbs-o-up"></i> 
									</a> 
									<a class="btn btn-danger btn-xs"  
									data-toggle="ajaxModal" href="<?=base_url()?>leaves/reject_request/teamlead/<?=$levs['id']?>" title="Reject" data-original-title="Reject">
									<i class="fa fa-thumbs-o-down"></i> 
									</a>
								<?php } } ?>
									<!--<a class="btn btn-danger btn-xs"  
									data-toggle="ajaxModal" href="<?=base_url()?>leaves/delete/<?=$levs['id']?>" title="Delete" data-original-title="Delete">
									<i class="fa fa-trash-o"></i> 
									</a>-->
							</td>
						</tr>
						<?php  } ?>  
						<?php  }else{ ?>
						<tr><td colspan="9">No details were found</td></tr>
						<?php } ?>  
					</tbody>
				</table>
			</div>	
		</div>
	</div>

	<div class="panel panel-table" id="team_leaves">
			
			<div class="panel-body">
			
				<div class="table-responsive">
				   <?php 
				   // print_r($check_teamlead); exit;
				   if($check_teamlead['is_teamlead'] == 'yes')
				   {
						$leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type
													FROM `dgt_user_leaves` ul
													left join dgt_common_leave_types lt on lt.leave_type_id = ul.leave_type
													where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." and 
													ul.status != 7 and ul.teamlead_id =".$check_teamlead['id']." and lt.branch_id='".$this->session->userdata('branch_id')."' order by ul.id  ASC ")->result_array();
					}
				   else
				   {
					$leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type
													FROM `dgt_user_leaves` ul
													left join dgt_common_leave_types lt on lt.leave_type_id = ul.leave_type
													where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." and 
													ul.status != 6 and ul.user_id =".$this->tank_auth->get_user_id()." and lt.branch_id='".$this->session->userdata('branch_id')."' order by ul.id  ASC ")->result_array();
				}
				

					
				   ?>
					<table id="table-holidays" class="table table-striped custom-table m-b-0">
						<thead>
							<tr>
								<th> No </th>
								<?php if($check_teamlead['is_teamlead'] == 'yes') { ?>
								<th> Employee Name</th>
								<?php }?>
								<th> Leave Type </th>
								<th> From </th>
								<th> To </th>
								<th> Reason </th> 
								<th> No.of Days </th>  
								<th> Status </th>  
								<th class="no-sort"> Action </th>  
							</tr>
						</thead>
						<tbody>
						 <?php 
						 	if(!empty($leave_list)){
						  foreach($leave_list as $key => $levs){  ?>
							<tr>
								<td><?=$key+1?></td>
								<?php if($check_teamlead['is_teamlead'] == 'yes') { $user_details = $this->db->get_where('account_details',array('user_id'=>$levs['user_id']))->row_array(); ?>
								<td><?=$user_details['fullname']?></td> <?php } ?>
								<td><?=$levs['l_type']?></td>
								<td><?=(!empty($levs['leave_from']))?date('d-m-Y',strtotime($levs['leave_from'])):''?></td>
								<td><?=(!empty($levs['leave_to']))?date('d-m-Y',strtotime($levs['leave_to'])):''?></td>
								<td><?=$levs['leave_reason']?></td>
								<td>
									<?php 
									echo $levs['leave_days'];
									if($levs['leave_day_type'] == 1){
										echo ' ( Full Day )';
									}else if($levs['leave_day_type'] == 2){
										echo ' ( First Half )';
									}else if($levs['leave_day_type'] == 3){
										echo ' ( Second Half )';
									}
									?>
								</td>
								<td>
								<?php
									if($levs['status'] == 0){
										if($check_teamlead['is_teamlead'] == 'no')
										echo '<span class="label" style="background:#D2691E"> Pending </span>';
										if($check_teamlead['is_teamlead'] == 'yes')
										echo '<span class="label" style="background:#D2691E"> Requested </span>';
									}else if($levs['status'] == 1){
										echo '<span class="label label-success"> Approved </span> ';
									}else if($levs['status'] == 2){
										echo '<span class="label label-danger"> Rejected</span>';
									}else if($levs['status'] == 3){
										echo '<span class="label label-danger"> Cancelled</span>';
									}else if($levs['status'] == 4){
										echo '<span class="label label-info"> TL - Approved</span><br>';
										echo '<span class="label label-danger"> Management - Pending</span>';
									}else if($levs['status'] == 5){
										echo '<span class="label label-danger"> TL - Rejected</span>';
									}else if($levs['status'] == 7){
										echo '<span class="label label-danger"> Deleted </span>';
									}
								?>
								</td>
								<td> 
									<?php if(empty($check_teamlead['is_teamlead']) || $check_teamlead['is_teamlead'] != 'yes'){ ?>
								<?php if($levs['status'] == 0){ ?> 
									 <a class="btn btn-danger btn-xs"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/cancel/<?=$levs['id']?>" title="Cancel" data-original-title="Cancel">
										<i class="fa fa-times"></i> 
									 </a>
								<?php } }
								if($check_teamlead['is_teamlead'] == 'yes'){ 
									if(($levs['status'] != 3) &&  ($levs['status'] != 1)){
								 ?>
									 <a  class="btn btn-success btn-xs"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/approve/teamlead/<?=$levs['id']?>" title="Approve" data-original-title="Approve" >
										<i class="fa fa-thumbs-o-up"></i> 
									 </a> 
									 <a class="btn btn-danger btn-xs"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/reject/teamlead/<?=$levs['id']?>" title="Reject" data-original-title="Reject">
										<i class="fa fa-thumbs-o-down"></i> 
									 </a>
								 <?php } } ?>
									 <!--<a class="btn btn-danger btn-xs"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/delete/<?=$levs['id']?>" title="Delete" data-original-title="Delete">
										<i class="fa fa-trash-o"></i> 
									 </a>-->
								</td>
							</tr>
						 <?php  } ?>  
						 <?php  }else{ ?>
						 <tr><td colspan="9">No details were found</td></tr>
						 <?php } ?>  
						</tbody>
				   </table>    
				</div>
			</div>
		</div>
		
	 <!-- user leave end -->
	 <?php } ?>
</div> 


<div class="panel panel-table" id="Personal_leaves" style="display: none;">
			<div class="panel-heading">
				<h3 class="panel-title">Leaves Details</h3>
			</div>
			<div class="panel-body">
			
				<div class="table-responsive">
				   <?php 
				   $check_teamlead = $this->db->get_where('dgt_users',array('id'=>$this->session->userdata('user_id')))->row_array();
				   // print_r($check_teamlead); exit;
				   // if($check_teamlead['is_teamlead'] == 'no')
				   // {
					$leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type
													FROM `dgt_user_leaves` ul
													left join dgt_common_leave_types lt on lt.leave_type_id = ul.leave_type
													where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." and 
													ul.status != 6 and ul.user_id =".$this->tank_auth->get_user_id()." and lt.branch_id='".$this->session->userdata('branch_id')."' order by ul.id  ASC ")->result_array();
				// }
				// if($check_teamlead['is_teamlead'] == 'yes')
				//    {
				// 	$leave_list = $this->db->query("SELECT ul.*,lt.leave_type as l_type
				// 									FROM `dgt_user_leaves` ul
				// 									left join dgt_common_leave_types lt on lt.id = ul.leave_type
				// 									where DATE_FORMAT(ul.leave_from,'%Y') = ".date('Y')." and 
				// 									ul.status != 7 and ul.teamlead_id =".$check_teamlead['id']." order by ul.id  ASC ")->result_array();
				// }
					
				   ?>
					<table id="table-holidays" class="table table-striped custom-table m-b-0">
						<thead>
							<tr>
								<th> No </th>
								<th> Leave Type </th>
								<th> From </th>
								<th> To </th>
								<th> Reason </th> 
								<th> No.of Days </th>  
								<th> Status </th>  
								<th class="no-sort"> Action </th>  
							</tr>
						</thead>
						<tbody>
						 <?php 
						 	if(!empty($leave_list)){
						  foreach($leave_list as $key => $levs){  ?>
							<tr>
								<td><?=$key+1?></td>
								<td><?=$levs['l_type']?></td>
								<td><?=(!empty($levs['leave_from']))?date('d-m-Y',strtotime($levs['leave_from'])):''?></td>
								<td><?=(!empty($levs['leave_to']))?date('d-m-Y',strtotime($levs['leave_to'])):''?></td>
								<td><?=$levs['leave_reason']?></td>
								<td>
									<?php 
									echo $levs['leave_days'];
									if($levs['leave_day_type'] == 1){
										echo ' ( Full Day )';
									}else if($levs['leave_day_type'] == 2){
										echo ' ( First Half )';
									}else if($levs['leave_day_type'] == 3){
										echo ' ( Second Half )';
									}
									?>
								</td>
								<td>
								<?php
									if($levs['status'] == 0){
										if($check_teamlead['is_teamlead'] == 'no')
										echo '<span class="label" style="background:#D2691E"> Pending </span>';
										if($check_teamlead['is_teamlead'] == 'yes')
										echo '<span class="label" style="background:#D2691E"> Requested </span>';
									}else if($levs['status'] == 1){
										echo '<span class="label label-success"> Approved </span> ';
									}else if($levs['status'] == 2){
										echo '<span class="label label-danger"> Rejected</span>';
									}else if($levs['status'] == 3){
										echo '<span class="label label-danger"> Cancelled</span>';
									}else if($levs['status'] == 4){
										echo '<span class="label label-info"> TL - Approved</span><br>';
										echo '<span class="label label-danger"> Management - Pending</span>';
									}else if($levs['status'] == 5){
										echo '<span class="label label-danger"> TL - Rejected</span>';
									}else if($levs['status'] == 7){
										echo '<span class="label label-danger"> Deleted </span>';
									}
								?>
								</td>
								<td> 
									<?php if($check_teamlead['is_teamlead'] == 'no'){ ?>
								<?php if($levs['status'] == 0){ ?> 
									 <a class="btn btn-danger btn-xs"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/cancel/<?=$levs['id']?>" title="Cancel" data-original-title="Cancel">
										<i class="fa fa-times"></i> 
									 </a>
								<?php } }
								if($check_teamlead['is_teamlead'] == 'yes'){ 
									if($levs['status'] != 7){
									// if(($levs['status'] != 3) &&  ($levs['status'] != 1)){
								 ?>
									 <!-- <a  class="btn btn-success btn-xs"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/approve/teamlead/<?=$levs['id']?>" title="Approve" data-original-title="Approve" >
										<i class="fa fa-thumbs-o-up"></i> 
									 </a> 
									 <a class="btn btn-danger btn-xs"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/reject/teamlead/<?=$levs['id']?>" title="Reject" data-original-title="Reject">
										<i class="fa fa-thumbs-o-down"></i> 
									 </a> -->
									 <a class="btn btn-danger btn-xs" data-toggle="ajaxModal" href="<?=base_url()?>leaves/delete/<?=$levs['id']?>" title="Delete" data-original-title="Delete">
										<i class="fa fa-trash-o"></i> 
									 </a>
								 <?php } } ?>
								</td>
							</tr>
						 <?php  } ?>  
						 <?php  }else{ ?>
						 <tr><td colspan="9">No details were found</td></tr>
						 <?php } ?>  
						</tbody>
				   </table>    
				</div>
			</div>
		</div>


		<div class="panel panel-table" id="Personal_leave_request" style="display: none;">
			<div class="panel-heading">
				<h3 class="panel-title">Leaves Details</h3>
			</div>
			<div class="panel-body">
			
				<div class="table-responsive">
				   <?php 
				   $check_teamlead = $this->db->get_where('dgt_users',array('id'=>$this->session->userdata('user_id')))->row_array();

				   $leave_list = $this->db->select('*')->from('compensatory_leave')->where('branch_id',$this->session->userdata('branch_id'))->where('status!=','6')->where('user_id',$this->tank_auth->get_user_id())->where("DATE_FORMAT(leave_from,'%Y')",date('Y'))->order_by('id','desc')->get()->result_array();
				   ?>
					<table id="table_leaves_emp" class="table table-striped custom-table m-b-0">
						<thead>
							<tr>
								<th> No </th>
								<th> Leave Type </th>
								<th> From </th>
								<th> To </th>
								<th> Reason </th> 
								<th> No.of Days </th>  
								<th> Status </th>  
								<th class="no-sort"> Action </th>  
							</tr>
						</thead>
						<tbody>
						 <?php 
						 	if(!empty($leave_list)){
						  foreach($leave_list as $key => $levs){  ?>
							<tr>
								<td><?=$key+1?></td>
								<td><?=lang('compen_request')?></td>
								<td><?=(!empty($levs['leave_from']))?date('d-m-Y',strtotime($levs['leave_from'])):''?></td>
								<td><?=(!empty($levs['leave_to']))?date('d-m-Y',strtotime($levs['leave_to'])):''?></td>
								<td><?=$levs['leave_reason']?></td>
								<td>
									<?php 
									echo $levs['leave_days'];
									if($levs['leave_day_type'] == 1){
										echo ' ( Full Day )';
									}else if($levs['leave_day_type'] == 2){
										echo ' ( First Half )';
									}else if($levs['leave_day_type'] == 3){
										echo ' ( Second Half )';
									}
									?>
								</td>
								<td>
								<?php
									if($levs['status'] == 0){
										if($check_teamlead['is_teamlead'] == 'no')
										echo '<span class="label" style="background:#D2691E"> Pending </span>';
										if($check_teamlead['is_teamlead'] == 'yes')
										echo '<span class="label" style="background:#D2691E"> Requested </span>';
									}else if($levs['status'] == 1){
										echo '<span class="label label-success"> Approved </span> ';
									}else if($levs['status'] == 2){
										echo '<span class="label label-danger"> Rejected</span>';
									}else if($levs['status'] == 3){
										echo '<span class="label label-danger"> Cancelled</span>';
									}else if($levs['status'] == 4){
										echo '<span class="label label-info"> TL - Approved</span><br>';
										echo '<span class="label label-danger"> Management - Pending</span>';
									}else if($levs['status'] == 5){
										echo '<span class="label label-danger"> TL - Rejected</span>';
									}else if($levs['status'] == 7){
										echo '<span class="label label-danger"> Deleted </span>';
									}
								?>
								</td>
								<td> 
									<?php if($check_teamlead['is_teamlead'] == 'no'){ ?>
								<?php if($levs['status'] == 0){ ?> 
									 <a class="btn btn-danger btn-xs"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/cancel/<?=$levs['id']?>" title="Cancel" data-original-title="Cancel">
										<i class="fa fa-times"></i> 
									 </a>
								<?php } }
								if($check_teamlead['is_teamlead'] == 'yes'){ 
									if($levs['status'] != 7){
									// if(($levs['status'] != 3) &&  ($levs['status'] != 1)){
								 ?>
									 <!-- <a  class="btn btn-success btn-xs"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/approve/teamlead/<?=$levs['id']?>" title="Approve" data-original-title="Approve" >
										<i class="fa fa-thumbs-o-up"></i> 
									 </a> 
									 <a class="btn btn-danger btn-xs"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/reject/teamlead/<?=$levs['id']?>" title="Reject" data-original-title="Reject">
										<i class="fa fa-thumbs-o-down"></i> 
									 </a> -->
									 <a class="btn btn-danger btn-xs"  
									 data-toggle="ajaxModal" href="<?=base_url()?>leaves/delete/<?=$levs['id']?>" title="Delete" data-original-title="Delete">
										<i class="fa fa-trash-o"></i> 
									 </a>
								 <?php } } ?>
								</td>
							</tr>
						 <?php  } ?>  
						 <?php  }else{ ?>
						 <tr><td colspan="9">No details were found</td></tr>
						 <?php }?>  
						</tbody>
				   </table>    
				</div>
			</div>
		</div>
		<?php echo datatable_script();?>
<script>
	$(document).ready(function() {
	var leave_type		= $('#ser_leave_type').val();
	var leave_status	= $('#ser_leave_sts').val();
	var employee_name	= $('#ser_leave_user_name').val();
	var leave_from		= $('#ser_leave_date_from').val();
	var leave_to		= $('#ser_leave_date_to').val();
    $('#table_leaves1').DataTable( {
		 "columns": [
             { data: "s_no" },
             { data: "fullname" },
             { data: "leave_type" },
			 { data: "leave_from" },
             { data: "leave_to" },
             { data: "leave_reason" },
			 { data: "leave_days" },
			 { data: "status" },
			 { data: "action" }
          ],
        "processing": true,
        "serverSide": true,
       "aLengthMenu": [
		[10,25, 50, 100],
		[10,25, 50, 100]
        ],
        "ajax": "<?php echo base_url().'leaves/ajax_leaves?leave_type=';?>"+leave_type+"&leave_status="+leave_status+"&employee_name="+employee_name+"&leave_from="+leave_from+"&leave_to="+leave_to
    } );
} );
	function leave_tab(a,b){
		$('.'+a).hide();
		$('#'+b).show();
		$('.nav_li').removeClass('active');
		$('#'+b+'_nav').addClass('active');
		$('#check_compensatory').val(0);
		if(b=='team_leave_request'){
			$('#check_compensatory').val(1);
		}
		$('#leave_search_form').hide();
		if(b== 'all_leaves' || b =='team_leaves'){
			$('#leave_search_form').show();
		}
	}
	
	
	function Getholidays()
	{
		var leave_type_id=$('#req_leave_type').val();//alert(leave_type_id);
		if(leave_type_id==11)
		{
			$("#req_leave_date_from").removeAttr('name');
			$("#req_leave_date_to").removeAttr('name');
			$("#holiday_div").show();
		
		}
		else
		{
			$(".req_leave_date_from").removeAttr('name');
			$(".req_leave_date_to").removeAttr('name');
			$("#holiday_div").hide();
		}
	}
	
	function Getholidaydates()
	{
		var holiday_id=$('#holiday_id').val();//alert(holiday_id);
		var base_url ='<?php echo base_url(); ?>';
		$.post(base_url + 'leaves/Getholidaydates/', {holiday_id:holiday_id}, function (datas) {
			// console.log(datas); return false;
			
		$('#req_leave_date_from').hide();
		$('.req_leave_date_from').show();
		$('#req_leave_date_to').hide();
		$('.req_leave_date_to').show();
		
		$('.req_leave_date_from').val(datas);
		$('.req_leave_date_to').val(datas);
		$('#req_leave_count').val(1);
		

			
// $("#req_leave_date_from").datepicker("");
        // $("#req_leave_date_from").datepicker({ });

		   // toastr.success('Extra Leaves Updated');
		   // setTimeout(function () {
				// location.reload();
			// }, 1500);
		});
	}
</script>

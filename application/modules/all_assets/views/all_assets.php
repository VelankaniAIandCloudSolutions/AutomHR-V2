<div class="content container-fluid">
					<div class="row">
						<div class="col-xs-8">
							<h4 class="page-title">Assets</h4>
						</div>
						<div class="col-xs-4 text-right m-b-30">
							<?php if(($this->session->userdata('role_id') == 1) || ( ($this->session->userdata('user_type_name') == 'company_admin'))){ ?>
								<a href="#" class="btn btn-primary rounded pull-right" data-toggle="modal" data-target="#add_asset"><i class="fa fa-plus"></i> Add Asset</a>
							<?php }?>
						</div>
					</div>
					<!-- <div class="row filter-row">
						<div class="col-sm-3 col-xs-6">  
							<div class="form-group form-focus">
								<label class="control-label">Employee Name</label>
								<input type="text" class="form-control floating" />
							</div>
						</div>
						<div class="col-sm-3 col-xs-6"> 
							<div class="form-group form-focus select-focus">
								<label class="control-label">Status</label>
								<select class="select floating"> 
									<option value=""> -- Select -- </option>
									<option value="0"> Pending </option>
									<option value="1"> Approved </option>
									<option value="2"> Returned </option>
								</select>
							</div>
						</div>
						<div class="col-sm-4 col-xs-12">  
						   <div class="row">  
							   <div class="col-sm-6 col-xs-6">  
									<div class="form-group form-focus">
										<label class="control-label">From</label>
										<div class="cal-icon"><input class="form-control floating datetimepicker" type="text"></div>
									</div>
								</div>
							   <div class="col-sm-6 col-xs-6">  
									<div class="form-group form-focus">
										<label class="control-label">To</label>
										<div class="cal-icon"><input class="form-control floating datetimepicker" type="text"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-2 col-xs-6">  
							<a href="#" class="btn btn-success btn-block"> Search </a>  
						</div>     
                    </div> -->
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-striped custom-table m-b-0 datatable">
									<thead>
										<tr>
											<th>#</th>
											<th>Asset User</th>
											<th>Asset Name</th>
											<th>Asset Id</th>
											<th>Purchase Date</th>
											<th>Warrenty End</th>
											<th>Amount</th>
											<th class="text-center">Status</th>
											<th class="text-right">Actions</th>
										</tr>
									</thead>
									<tbody>
										<?php $i=1; foreach($all_assets as $assets){ 
											$user_det = $this->db->get_where('account_details',array('user_id'=>$assets['asset_user']))->row_array();
											?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $user_det['fullname']; ?></td>
											<td>
												<strong><?php echo $assets['asset_name']; ?></strong>
											</td>
											<td><?php echo $assets['reference_id']; ?></td>
											<td><?php echo date('d M Y',strtotime($assets['purchase_date'])); ?></td>
											<td><?php echo date('d M Y',strtotime($assets['warranty_date'])); ?></td>
											<td><?php echo $assets['assets_value']; ?></td>
											<td class="text-center">
												<div class="dropdown action-label">
													<a class="btn btn-white btn-sm rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
														<!-- <i class="fa fa-dot-circle-o text-success"></i> <?php echo ucfirst($assets['status']); ?> <i class="caret"></i> -->
														<?php if($assets['status']=="approved"){
															echo '<i class="fa fa-dot-circle-o text-success"></i> '. "Approved";
														}
														elseif($assets['status']=="pending"){
															echo '<i class="fa fa-dot-circle-o text-danger"></i> '. "Pending";
														}
														elseif($assets['status']=="deployed"){
															echo '<i class="fa fa-dot-circle-o text-info"></i> '. "Deployed";
														}
														elseif($assets['status']=="damaged"){
															echo '<i class="fa fa-dot-circle-o text-info"></i> '. "Damaged";
														}else{
															echo $assets['status'];
														} ?>
													</a>
													<ul class="dropdown-menu pull-right">
														<li><a href="<?php echo base_url(); ?>all_assets/assets_status_change/<?php echo $assets['assets_id']; ?>/pending"><i class="fa fa-dot-circle-o text-danger"></i> Pending</a></li>
														<li><a href="<?php echo base_url(); ?>all_assets/assets_status_change/<?php echo $assets['assets_id']; ?>/approved"><i class="fa fa-dot-circle-o text-success"></i> Approved</a></li>
														<li><a href="<?php echo base_url(); ?>all_assets/assets_status_change/<?php echo $assets['assets_id']; ?>/deployed"><i class="fa fa-dot-circle-o text-info"></i> Deployed</a></li>
														<li><a href="<?php echo base_url(); ?>all_assets/assets_status_change/<?php echo $assets['assets_id']; ?>/damaged"><i class="fa fa-dot-circle-o text-info"></i> Damaged</a></li>
													</ul>
												</div>
											</td>
											<td class="text-right">
												<div class="dropdown">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
													<ul class="dropdown-menu pull-right">
														<li><a href="#" title="Edit" data-toggle="modal" data-target="#edit_asset<?php echo $assets['assets_id']; ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
														<li><a href="#" title="Delete" data-toggle="modal" data-target="#delete_asset<?php echo $assets['assets_id']; ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
										<?php $i++; } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
                </div>
                
			<div id="add_asset" class="modal custom-modal fade" role="dialog">
				<div class="modal-dialog">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<div class="modal-content modal-md">
						<div class="modal-header">
							<h4 class="modal-title">Add Asset</h4>
						</div>
						<div class="modal-body">
							<form id="add_asset_form" method="post" action="<?php echo base_url(); ?>all_assets/add" >
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Asset Name<span class="text-danger">*</span></label>
											<input class="form-control" type="text" name="asset_name" id="asset_name" placeholder="Asset Name">
											<input class="form-control" type="hidden" name="branch_id" value="<?php echo $this->session->userdata('branch_id'); ?>" >
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Asset Id<span class="text-danger">*</span></label>
											<input class="form-control" type="text"  name="reference_id" id="reference_id" value="">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Purchase Date<span class="text-danger">*</span></label>
											<input class="form-control datetimepicker" type="text" id="purchase_date" name="purchase_date" placeholder="Purchase Date" data-date-format='yyyy-mm-dd'>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Purchase From<span class="text-danger">*</span></label>
											<input class="form-control" type="text" name="purchase_from" id="purchase_from" placeholder="Purchase From">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Manufacturer<span class="text-danger">*</span></label>
											<input class="form-control" type="text" id="manufacture" name="manufacture" placeholder="Manufacturer">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Model<span class="text-danger">*</span></label>
											<input class="form-control" type="text" name="model" id="model" placeholder="Model Name">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Serial Number<span class="text-danger">*</span></label>
											<input class="form-control" type="text" name="serial_number" id="serial_number" placeholder="Serial Number">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Supplier<span class="text-danger">*</span></label>
											<input class="form-control" type="text" name="supplier" id="supplier" placeholder="Supplier Name">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Condition<span class="text-danger">*</span></label>
											<input class="form-control" type="text" name="asset_condition" id="asset_condition" placeholder="Asset-Condition">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Warranty<span class="text-danger">*</span></label>
											<input class="form-control" type="text" placeholder="Warranty-Date" id="warranty_date" name="warranty_date" data-date-format='yyyy-mm-dd'>
										</div>
									</div>
								</div>
								<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label><?=lang('branch')?> <span class="text-danger">*</span></label>
										<select onchange="entity_change1(this.value)" class="form-control" style="width:100%;"  name="branch_id" id="add_branch" required >
											<option value="">Select</option>
											<?php if(!empty($branches))	{
												foreach ($branches as $branch1){ ?>
													<option value="<?=$branch1['branch_id']?>" ><?=$branch1['branch_name']?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>
									
									<div class="col-sm-6">
										<div class="form-group">
											<label>Asset User<span class="text-danger">*</span></label>
											<select class="select2-option21 form-control" style="width:100%;" name="asset_user" id="asset_user">
													<option value="" selected disabled>Users</option>
													
											</select>
											</div>
									</div>

									<div class="col-md-12">
										<div class="form-group">
											<label>Value<span class="text-danger">*</span></label>
											<input placeholder="$1800" class="form-control" type="text" name="assets_value" id="assets_value">
										</div>
									</div>

									<div class="col-md-12">
										<div class="form-group">
											<label>Description<span class="text-danger">*</span></label>
											<textarea class="form-control" name="description" id="description"></textarea>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Status<span class="text-danger">*</span></label>
											<select class="select2-option" style="width:100%;" name="status" id="status">
												<option value="" selected disabled>Status</option>
												<option value="pending">Pending</option>
												<option value="approved">Approved</option>
												<option value="deployed">Deployed</option>
												<option value="damaged">Damaged</option>
											</select>
										</div>
									</div>
									
								</div>
								
								<div class="m-t-20 text-center">
									<button class="btn btn-primary" id="add_btn_asset">Add Asset</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<?php foreach($all_assets as $asse){ ?>
			<div id="delete_asset<?php echo $asse['assets_id']; ?>" class="modal custom-modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content modal-md">
						<div class="modal-header">
							<h4 class="modal-title">Delete Asset</h4>
						</div>
							<div class="modal-body card-box">
								<p>Are you sure want to delete this asset?</p>
								<div class="m-t-20"> <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
									<a href="<?php echo base_url(); ?>all_assets/delete/<?php echo $asse['assets_id']; ?>"  class="btn btn-danger">Delete</a>
								</div>
							</div>
					</div>
				</div>
			</div>
			<div id="edit_asset<?php echo $asse['assets_id']; ?>" class="modal custom-modal fade" role="dialog">
				<div class="modal-dialog">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<div class="modal-content modal-md">
						<div class="modal-header">
							<h4 class="modal-title">Edit Asset</h4>
						</div>
						<div class="modal-body">
							<form id="edit_assets_form" method="post" action="<?php echo base_url(); ?>all_assets/edit/<?php echo $asse['assets_id']; ?>">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Asset Name<span class="text-danger">*</span></label>
											<input class="form-control" type="text" name="asset_name" id="asset_name" placeholder="Asset Name" value="<?php echo $asse['asset_name']; ?>">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Asset Id<span class="text-danger">*</span></label>
											<input class="form-control" type="text"  name="reference_id" id="reference_id" value="<?php echo $asse['reference_id']; ?>" >
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Purchase Date<span class="text-danger">*</span></label>
											<input class="form-control datetimepicker" type="text" id="purchase_date" name="purchase_date" placeholder="Purchase Date" data-date-format='yyyy-mm-dd' value="<?php echo $asse['purchase_date']; ?>">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Purchase From<span class="text-danger">*</span></label>
											<input class="form-control" type="text" name="purchase_from" id="purchase_from" placeholder="Purchase From" value="<?php echo $asse['purchase_from']; ?>">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Manufacturer<span class="text-danger">*</span></label>
											<input class="form-control" type="text" id="manufacture" name="manufacture" placeholder="Manufacturer" value="<?php echo $asse['manufacture']; ?>">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Model<span class="text-danger">*</span></label>
											<input class="form-control" type="text" name="model" id="model" placeholder="Model Name" value="<?php echo $asse['model']; ?>">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Serial Number<span class="text-danger">*</span></label>
											<input class="form-control" type="text" name="serial_number" id="serial_number" placeholder="Serial Number" value="<?php echo $asse['serial_number']; ?>">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Supplier<span class="text-danger">*</span></label>
											<input class="form-control" type="text" name="supplier" id="supplier" placeholder="Supplier Name" value="<?php echo $asse['supplier']; ?>">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Condition<span class="text-danger">*</span></label>
											<input class="form-control" type="text" name="asset_condition" id="asset_condition" placeholder="Asset-Condition" value="<?php echo $asse['asset_condition']; ?>">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Warranty<span class="text-danger">*</span></label>
											<input class="form-control" type="text" placeholder="Warranty-Date" id="warranty_date" name="warranty_date" data-date-format='yyyy-mm-dd' value="<?php echo $asse['warranty_date']; ?>">
										</div>
									</div>
								</div>
								<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label><?=lang('branch')?> <span class="text-danger">*</span></label>
										<select class="form-control" style="width:100%;"  name="branch_id" id="add_branch" required onchange="entity_change1(this.value)" >
											<option value="">Select</option>
											<?php if(!empty($branches))	{
												foreach ($branches as $branch1){ ?>
													<option value="<?=$branch1['branch_id']?>" <?php if($asse['branch_id'] == $branch1['branch_id']){ ?> selected <?php } ?>><?=$branch1['branch_name']?></option>
												<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>
									
									<div class="col-sm-6">
										<div class="form-group">
											<label>Asset User<span class="text-danger">*</span></label>
											<?php
											$users = $this->db->select('AD.*,U.email')->from('users U')->join('account_details AD','U.id = AD.user_id')->where('AD.branch_id',$asse['branch_id'])->get()->result_array();
											?>
											<select class="select2-option2 form-control" style="width:100%;" name="asset_user" id="asset_user_edit" >
													<option value="" selected disabled>Users</option>
												<?php
												if(!empty($users)){foreach($users as $user1){?>
													<option value='<?php echo $user1['user_id'];?>' <?php echo ( $user1['user_id']==$asse['asset_user'])?'selected':'';?>><?php echo $user1['fullname'].'('.$user1['email'].')';?></option>
												<?php }}?>
													
											</select>
											</div>
									</div>

									<div class="col-md-12">
										<div class="form-group">
											<label>Value<span class="text-danger">*</span></label>
											<input placeholder="1800" class="form-control" type="text" name="assets_value" id="assets_value" value="<?php echo $asse['assets_value']; ?>">
										</div>
									</div>

									<div class="col-md-12">
										<div class="form-group">
											<label>Description<span class="text-danger">*</span></label>
											<textarea class="form-control" name="description" id="description"><?php echo $asse['description']; ?></textarea>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Status<span class="text-danger">*</span></label>
											<select class="select2-option" style="width:100%;" name="status" id="status">
												<option value="" selected disabled>Status</option>
												<option value="pending" <?php if($asse['status'] == 'pending'){ ?> selected <?php } ?>>Pending</option>
												<option value="approved" <?php if($asse['status'] == 'approved'){ ?> selected <?php } ?>>Approved</option>
												<option value="deployed" <?php if($asse['status'] == 'deployed'){ ?> selected <?php } ?>>Deployed</option>
												<option value="damaged" <?php if($asse['status'] == 'damaged'){ ?> selected <?php } ?>>Damaged</option>
											</select>
										</div>
									</div>
									
								</div>
								<div class="m-t-20 text-center">
									<button class="btn btn-primary" id="edit_btn_asset">Update Asset</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
			<script>
	function entity_change1(a){
		$.ajax( {
			url:'<?php echo base_url('all_assets/get_companies/');?>'+a,
			success:function(data) {
				var reqdata = JSON.parse(data);
				$('#asset_user').empty();

				$('#asset_user_edit').empty();
				$('#asset_user').append('<option value="">Select</option>'+reqdata.users);
				
				$('#asset_user_edit').append('<option value="">Select</option>'+reqdata.users);
				$('#asset_user').refresh();
				$('#asset_user_edit').refresh();
				
			}
		});
	}
</script>	
			<script>
				$(document).ready(function () {
					$(".select2-option2").select2({
						minimumInputLength: 3,
						tags: [],
						ajax: {
							url: "<?php echo base_url('transfer_entity/getEmployees');?>",
							dataType: 'json',
							type: "GET",
							quietMillis: 2,
							data: function (term) {
								return {
									term: term
								};
							},
							processResults: function (data) {
								
								return {
									results: $.map(data, function (item) {
									return {
											text: item.fullname+' ('+item.email+')',
											slug: item.email,
											id: item.id
										}
									})
								};
							}
						}
					});
				});
				<?php if(!empty($asse['asset_user'])){

				$user_details = $this->db->select('AD.*,U.email')->from('users U')->join('account_details AD','U.id = AD.user_id')->where('U.id',$asse['asset_user'])->get()->result_array();
				?>
					$("#asset_user").empty();
					<?php foreach($users as $user1){?>
						$("#asset_user").append("<option value='<?php echo $user1['user_id'];?>' selected><?php echo $user1['fullname'].'('.$user1['email'].')';?></option>");

				<?php 
					}
				}?>
	</script>
	
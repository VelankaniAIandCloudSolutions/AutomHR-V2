<div class="content">
	<div class="row">
		<div class="col-sm-8 col-xs-4">
			<h4 class="page-title"><?php echo lang('all_branches'); ?></h4>
		</div>
		<div class="col-sm-4 col-xs-8 text-right m-b-20">
			<a href="<?=base_url()?>all_branches/add_branch" class="btn add-btn" data-toggle="ajaxModal"><i class="fa fa-plus"></i> <?php echo lang('add_branch') ;?></a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
		   <div class="table-responsive">
				<table id="table-branches" class="table table-striped custom-table m-b-0 AppendDataTables">
					<thead>
						<tr>
							<th>#</th>
							<th><?php echo lang('prefix'); ?></th>
							<th><?php echo lang('branch_name'); ?></th>
							<th><?php echo lang('entity_admin'); ?></th>
							<th><?php echo lang('branch_status'); ?></th>							
							<th><?php echo lang('action'); ?></th>							
						</tr>
					</thead>
					<tbody>
						<?php if(!empty($all_branches)){ 
							$row =1;
							foreach($all_branches as $branch){

								$users= $this->db->select('U.id,AD.fullname,U.email')->from('dgt_users U')->join('account_details AD','AD.user_id = U.id')->join('assigned_entities ae','ae.user_id = U.id')->where('U.status',1)->where('ae.branch_id',$branch['branch_id'])->order_by('AD.fullname','asc')->get()->result_array();

								if($branch['branch_status'] == 0){
									$label = 'success';
									$status = 'Active';
								}else{
									$label = 'danger';
									$status = 'InActive';
								}
						 ?>
						<tr>
							<td><?php echo $row; ?></td>
							<td><?php echo $branch['branch_prefix']; ?></td>
							<td><?php echo ucfirst($branch['branch_name']); ?></td>
							<td>
								<?php if(!empty($users)){
									$empname = '';
									foreach($users as $user1){
										$empname .= $user1['fullname'].',  ';
									}
									echo rtrim($empname,', ');
								}
								?>
							</td>
							<td><span class="label label-<?php echo $label; ?>"><?php echo $status; ?></span></td>
							<td>
								<a  href="<?=base_url()?>all_branches/edit_branch/<?php echo $branch['branch_id']; ?>" data-toggle="ajaxModal" class="btn btn-success" data-toggle="tooltip" title="<?=lang('edit_branch')?>" ><i class="fa fa-pencil"></i></a>
								<?php /*<a  href="<?=base_url()?>all_branches/delete_branch/<?php echo $branch['branch_id']; ?>" data-toggle="ajaxModal" class="btn btn-danger" data-toggle="tooltip" title="<?=lang('delete_branch')?>" ><i class="fa fa-times"></i></a>	*/?>
							</td>
						</tr>
					<?php $row++; } }else{ ?>
						<tr>
							<td colspan="4" class="text-center"> No Branches </td>
						</tr>
					<?php } ?>
					</tbody>
			   </table>    
			</div>
		</div>
	</div>
</div>
<style>
	.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #0253cc !important;
}

input:focus + .slider {
  box-shadow: 0 0 1px #0253cc !important;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
#map {
  height: 18%;
  width:58%;
  position:fixed !important;
}

#infowindow-content .title {
  font-weight: bold;
}

#infowindow-content {
  display: none;
}

#map #infowindow-content {
  display: inline;
}

.pac-card {
  background-color: #fff;
  border: 0;
  border-radius: 2px;
  box-shadow: 0 1px 4px -1px rgba(0, 0, 0, 0.3);
  margin: 10px;
  padding: 0 0.5em;
  font: 400 18px Roboto, Arial, sans-serif;
  overflow: hidden;
  font-family: Roboto;
  padding: 0;
}

#pac-container {
  padding-bottom: 12px;
  margin-right: 12px;
}
.pac-container {
  z-index: 9999;
}

.pac-controls {
  display: inline-block;
  padding: 5px 11px;
}

.pac-controls label {
  font-family: Roboto;
  font-size: 13px;
  font-weight: 300;
}

#pac-input {
  padding: 0 11px 0 13px;
  text-overflow: ellipsis;
}

#pac-input:focus {
  border-color: #4d90fe;
}
</style>
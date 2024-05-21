<div class="panel panel-white">
	<div class="panel-heading font-bold">
		<h3 class="panel-title"><?=lang('roles_and_privileges_settings')?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
		<div class="col-xs-12 text-right m-b-20">
			<a href="<?php echo base_url(); ?>settings/new_menu_role/"><span class="btn btn-info">New Role</span></a>
		</div>
	</div>
		<?php 
		//	$all_roles = $this->db->where('branch_id',$this->session->userdata('branch_id'))->get('roles')->result_array();
			$all_roles = $this->db->get('roles')->result_array();
		?>
		<div class="row">
			<div class="col-sm-12 col-xs-12 col-md-12"> 

				<div class="table-responsive">
			        <table id="table-rates" class="table table-striped custom-table m-b-0 AppendDataTables">
			            <thead>
			                <tr>
			                    <th style="width: 60px;">Role ID</th>
			                    <th>Role Name</th>
			                    <th>Modules Access</th>
			                    <th>Created</th>
			                    <th class="col-options no-sort text-center"><?=lang('action')?></th>
			                </tr>
			            </thead>
			            <tbody>
			            	<?php $e = 1; 
			            	foreach($all_roles as $roles){

			            	$date=date_create($roles['created']);
							$dis_date = date_format($date,"d M Y"); 
			            		if($roles['role'] == 'admin'){
			            			$des = 'Full Permissions';
			            		}else{
			            			$des = 'Custom Permissions';
			            		}
			            		?>
			            		<tr>
			            			<td><?php echo $e; ?></td>
			            			<td><a href="<?php echo base_url(); ?>settings/role_view/<?php echo $roles['role']; ?>"><?php echo ucfirst(str_replace('_',' ', $roles['role'])); ?></a></td>
			            			<td><?php echo $des; ?></td>
			            			<td><?php echo $dis_date; ?></td>
			            			<td style="text-align: center;">
										<a class="btn btn-danger btn-xs" title="<?=lang('cannot_delete')?>"><i class="fa fa-ban" aria-hidden="true"></i></a>
			            				<!-- <?php if($roles['role'] != 'admin'){ ?>
				            				<a class="btn btn-danger btn-xs" href="<?=base_url()?>settings/roles_delete/<?php echo $roles['r_id']; ?>" data-toggle="ajaxModal" title="<?=lang('delete_role')?>"><i class="fa fa-trash-o"></i></a>
				            			<?php }else{ ?>
				            				<a class="btn btn-danger btn-xs" title="<?=lang('cannot_delete')?>"><i class="fa fa-ban" aria-hidden="true"></i></a>
				            			<?php } ?> -->
			            			</td>
			            		</tr>
			            	<?php $e++; } ?>
			            </tbody>
			        </table>
			    </div>
			</div>
		</div>
	</div>
</div>
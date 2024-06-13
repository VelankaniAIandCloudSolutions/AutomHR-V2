<div class="panel panel-white">
	<div class="panel-heading font-bold d-inline-flex align-items-center justify-content-between">
		<h3 class="panel-title"><?=ucfirst(str_replace('_',' ', $role_name)).' - '.lang('mods_privileges')?></h3>
		<a class="btn add-btn" data-toggle="modal" data-target="#add_menus" title="Add Menus"><i class="fa fa-plus"></i> Add Menus</a><br>
		<a href="<?php echo base_url(); ?>settings/?settings=menu" title="Back to Menu List">Back</a>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-12 col-xs-12 col-md-12"> 
				<?php //echo "<pre>"; print_r($menus); exit; ?>
				<div class="table-responsive">
			        <table  class="table table-striped custom-table m-b-0 AppendDataTables">
			            <thead>
			                <tr>
			                    <th>#</th>
			                    <th>Menu Icon</th>
			                    <th>Module</th>
			                    <th class="col-options no-sort text-center"><?=lang('action')?></th>
			                </tr>
			            </thead>
			            <tbody>
			            	<?php $e = 1; 
			            	foreach($menus as $menu){ 
			            		$menu_names[] = $menu['name'];
			            		$menu_access = $menu['access'];
			            		if($menu['visible'] == 1)
			                    {
			                        $record_visible = "success";
			                    }else{
			                        $record_visible = "default";
			                    }
			            		?>
			            		<tr class="sortable" data-module="<?php echo $menu['module']; ?>" data-access="1">
		            				<td ><?php echo $e; ?></td>
		            				<td>
		            					<div class="btn-group"><button class="btn btn-default iconpicker-component" type="button"><i class="fa <?php echo $menu['icon']; ?> fa-fw"></i></button><button data-toggle="dropdown" data-selected="<?php echo $menu['icon']; ?>" class="menu-icon icp icp-dd btn btn-default dropdown-toggle" type="button" aria-expanded="false" data-role="<?php echo $menu['access']; ?>" data-href="<?php echo base_url(); ?>settings/hook/icon/<?php echo $menu['module']; ?>"><span class="caret"></span></button><div class="dropdown-menu iconpicker-container"></div></div>
		            				</td>
		            				<td><?php echo $menu['name']; ?></td>
		            				<td class="text-center">
		            					<a data-rel="tooltip" data-original-title="Toggle" class="menu-view-toggle btn btn-xs btn-<?php echo $record_visible; ?>" href="#" data-role="<?php echo $menu['access']; ?>" data-href="<?php echo base_url(); ?>settings/hook/visible/<?php echo $menu['module']; ?>"><i class="fa fa-eye"></i></a>
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


<!-- Updating menu modal -->
<div id="add_menus" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add <?=ucfirst(str_replace('_',' ', $role_name))?> Menu</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="RoleaddForm" method="post" action="<?php echo base_url(); ?>settings/update_menus">
            	<div class="form-group">
					<input type="hidden" class="form-control" value="<?php echo $role_name;?>" name="role_name" >
					<input type="hidden" class="form-control" value="<?php echo $menu_access;?>" name="menu_access" >
				</div>
				<div class="form-group leave-duallist">
					<label>Add Menu</label>
					<div class="row">
						<div class="col-lg-5 col-sm-5 col-12">
							<select name="role_menu_from[]" id="role_menu_select" class="form-control" size="5" multiple="multiple" >
								<?php 
									$all_menus = $this->db->get_where('hooks',array('hook'=>'main_menu_admin','route !='=>'#'))->result();
								  foreach($all_menus as $adm){	
								  if(!in_array($adm->name, $menu_names)){ ?>
							  			<option value="<?php echo $adm->name; ?>"><?=lang($adm->name)?></option>
							  		<?php }?>									
								<?php 
								 } ?>
							</select>
						</div>
						<div class="multiselect-controls col-lg-2 col-sm-2 col-12">
							<button type="button" id="role_menu_select_rightAll" class="btn btn-block btn-white"><i class="fa fa-forward"></i></button>
							<button type="button" id="role_menu_select_rightSelected" class="btn btn-block btn-white"><i class="fa fa-chevron-right"></i></button>
							<button type="button" id="role_menu_select_leftSelected" class="btn btn-block btn-white"><i class="fa fa-chevron-left"></i></button>
							<button type="button" id="role_menu_select_leftAll" class="btn btn-block btn-white"><i class="fa fa-backward"></i></button>
						</div>
						<div class="col-lg-5 col-sm-5 col-12">
							<select name="role_menu_to[]" id="role_menu_select_to" class="form-control" size="8" multiple="multiple" ></select>
						</div>
					</div>
				</div>

				<div class="submit-section">
					<button class="btn btn-primary submit-btn">Submit</button>
				</div>
			</form>
			</div>
		</div>
	</div>
</div>
<!--/ Updating menu modal -->
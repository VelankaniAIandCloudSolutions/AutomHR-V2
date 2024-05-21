<div class="panel panel-white">
	<div class="panel-heading font-bold">
		<h3 class="panel-title">Add New Role</h3>
	</div>
	<form id="NewRoleForm" method="post" >
		<div class="panel-body">
            <form id="RoleaddForm" method="post" action="<?php echo base_url(); ?>settings/new_menu_role">
            	<div class="form-group">
					<label>New Role <span class="text-danger">*</span></label>
					<input type="text" class="form-control" value="" name="role_name" placeholder="Role Name">
				</div>
				<div class="form-group leave-duallist">
					<label>Add Menu</label>
					<div class="row">
						<div class="col-lg-5 col-sm-5 col-xs-12">
							<select name="role_menu_from[]" id="role_menu_select" class="form-control" size="5" multiple="multiple">
								<?php 
									$all_menus = $this->db->get_where('hooks',array('hook'=>'main_menu_admin','route !='=>'#'))->result();
								  foreach($all_menus as $adm){ ?>
									<option value="<?php echo $adm->name; ?>"><?=lang($adm->name)?></option>
								<?php } ?>
							</select>
						</div>
						<div class="multiselect-controls col-lg-2 col-sm-2 col-xs-12">
							<button type="button" id="role_menu_select_rightAll" class="btn btn-block btn-white"><i class="fa fa-forward"></i></button>
							<button type="button" id="role_menu_select_rightSelected" class="btn btn-block btn-white"><i class="fa fa-chevron-right"></i></button>
							<button type="button" id="role_menu_select_leftSelected" class="btn btn-block btn-white"><i class="fa fa-chevron-left"></i></button>
							<button type="button" id="role_menu_select_leftAll" class="btn btn-block btn-white"><i class="fa fa-backward"></i></button>
						</div>
						<div class="col-lg-5 col-sm-5 col-xs-12">
							<select name="role_menu_to[]" id="role_menu_select_to" class="form-control" size="8" multiple="multiple"></select>
						</div>
					</div>
				</div>

				<div class="submit-section">
					<button class="btn btn-primary submit-btn">Submit</button>
				</div>
			</form>
</div>
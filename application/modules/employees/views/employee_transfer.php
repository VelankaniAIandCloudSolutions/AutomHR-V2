<!-- Start -->
<div class="content">
 <!-- Start transfer entity -->
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-white">
				<div class="panel-heading font-bold">
					<h3 class="panel-title"><?=lang('movement_from')?></h3>
				</div>
				<div class="panel-body">
					<?php
					$attributes = array('class' => 'bs-example form-horizontal','id'=>'ticketSelectDept');
          			echo form_open(base_url().'employees/transerEntity',$attributes); 
					?>
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">							
									<div class="col-lg-3">
										<label class="control-label"><?=lang('add_employee')?> <span class="text-danger">*</span></label>
									</div>
									<div class="col-lg-9">
										<div class="m-b">
										<select class="form-control select2-option1" name="employee" onchange="employee_change(this.value)" requied>
										<option value="" selected disabled><?=lang('employee')?></option>
											
										</select>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">							
									<div class="col-lg-3">
									<label class="control-label"><?=lang('branch')?><span class="text-danger">*</span> </label>
									
									</div>
									<div class="col-lg-9">

										<select class="form-control" style="width:100%;"  onchange="entity_change(this.value)" name="branch_id" id="add_branch" required>
											<option value="">Select</option>
											<?php
											if(!empty($entities))	{
												foreach ($entities as $entity){ ?>
													<option value="<?=$entity['branch_id']?>" ><?=$entity['branch_name']?></option>
												<?php } 
											} ?>
										</select>										
									</div>
									
									</div>
								</div>
							</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">								
									<div class="col-lg-3">
										<label class="control-label"><?=lang('prefix')?><span class="text-danger">*</span>  </label>
									</div>
										<div class="col-lg-9">

											<input type="text" class="form-control" id="prefix" name="prefix" required readonly>										
									</div>
								</div>   
							</div>
							
							<div class="col-md-6">
								<div class="form-group">									
									<div class="col-lg-3">
										<label class="control-label"><?=lang('new_employee_id')?><span class="text-danger">*</span>  </label>
									</div>
										<div class="col-lg-9">

											<input type="text" class="form-control" id="employee_id" name="employee_id" required>										
									</div>
								</div>   
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<div class="col-lg-3">
										<label class="control-label"><?=lang('type_change')?> <span class="text-danger">*</span></label>
									</div>
									<div class="col-lg-9">
										<div class="m-b">
											<select class=" form-control" style="width:100%" name="type_change" id="type_change" >
												<option value="Internal Transfer" >Internal Transfer</option>
												<option value="External Transfer" >External Transfer</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<div class="col-lg-3">
										<label class="control-label"><?=lang('department')?> </label>
									</div>
										<div class="col-lg-9">
											<select class=" form-control" style="width:100%" name="department_id" id="department_name" onchange="check_repot()" >
												<?php $departments = Client::get_all_departments();

												if(!empty($departments)){ ?>
												<?php foreach ($departments as $department) {
												?>
												<option  value="<?php echo $department->deptid; ?>"><?php echo $department->deptname; ?></option>
											<?php  } ?>
											<?php } ?>
											</select>
									</div>
								</div> 
							</div>
						</div>
						<div class="row">
						<div class="col-md-6">
							<div class="form-group">
									<div class="col-lg-3">
										<label class="control-label">Designations </label>
									</div>
									<div class="col-lg-9">
										<?php
										 $designations = '';
										 $designation_id = (!empty($employee_details['designation_id']))?$employee_details['designation_id']:'';
										 if(!empty($designation_id)){
											 $designations = $this->employees->get_department_and_designation($designation_id);	
										 }
											 
										$departmentid = (!empty($designations['departmentid']))?$designations['departmentid']:'';
										$designations = (!empty($designations['designations']))?$designations['designations']:array();
										?>
										<select class="form-control" style="width:100%;" name="designations" id="designations">
											
										</select>
								
								</div>
							</div>   
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-lg-3">
									<label class="control-label"><?=lang('report_to')?> </label>
								</div>
									<div class="col-lg-9">

										<select class="form-control" style="width:100%;" name="report_to" id="reporting_to">
											
										</select>
										
								</div>
							</div>   
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-lg-3">
									<label class="control-label"><?=lang('effective_date')?> <span class="text-danger">*</span> </label>
								</div>
									<div class="col-lg-9">

										<input type="text" class="form-control"  name="emp_doj" id="emp_doj" required>										
								</div>
							</div>   
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-lg-3">
									<label class="control-label"><?=lang('location')?> </label>
								</div>
									<div class="col-lg-9">

									<select class="form-control" style="width:100%;" name="location" id="location">
										<option value="46 Ounces" >46 Ounces</option>
										<option value="46 Ounces - Cafe Capriccio" >46 Ounces - Cafe Capriccio</option>
										<option value="Oterra" >Oterra</option>
										<option value="Otium Bangalore" >Otium Bangalore</option>
										<option value="Otium Goa" >Otium Goa</option>
										<option value="Velankani AI & Cloud Solutions LLP" >Velankani AI & Cloud Solutions LLP</option>
										<option value="VEPL">VEPL-Unit2</option>
										<option value="VISL">VISL</option>
										<option value="VEPL-B1">VEPL-B1</option>
										<option value="VEPL-JIGANI">VEPL-JIGANI</option>
									</select>									
								</div>
							</div>   
						</div> 
						</div>
						<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-lg-3">
									<label class="control-label"><?=lang('business_unit')?> </label>
								</div>
									<div class="col-lg-9">
									<select class="form-control" style="width:100%;" name="business_unit" id="business_unit">
										<option value="Manufacturing" >Manufacturing</option>
										<option value="Precast" >Precast</option>
										<option value="Tower Building" >Tower Building</option>
									</select>										
								</div>
							</div>   
						</div>
						
						<div class="col-md-6">
							<div class="form-group">
								<div class="col-lg-3">
									<label class="control-label"><?=lang('division')?> </label>
								</div>
									<div class="col-lg-9">
										<select class="form-control" style="width:100%;" name="division" id="division">
											<option value="Tower Building">Tower Building</option>
										</select>
								</div>
							</div>   
						</div>
						</div>
					</div>
					<?php echo $this->session->flashdata('form_error'); ?>

					<div class="modal-footer"> 
						<button class="btn btn-success" id="employee_edit_user"><?=lang('save_changes')?></button>
					</div>
					<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="emp_history" style="display:none"></div>
<!-- End transfer entity -->
<script src="<?=base_url()?>assets/js/jquery-2.2.4.min.js"></script>
<!-- end -->
<style>
	label.col-lg-3.control-label {
    	white-space: nowrap;
	}
	.col-md-6{
		padding-top:10px;
	}
</style>
<script>
	function check_repot(){
		$('#reporting_to').empty().append("<option selected='selected' value=''>Reporter's Name</option>");
	}

	$(document).ready(function () {
		$(".select2-option1").select2({
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
	function employee_change(a){
		$.ajax( {
			url:'<?php echo base_url('employees/get_details/');?>'+a,
			success:function(data) {
				var reqdata = JSON.parse(data);
				$('#add_branch').val(reqdata.branch_id);
				$('#prefix').val(reqdata.prefix);
				$('#employee_id').val(reqdata.employee_id);
				$('#type_change').val(reqdata.type_change);
				
				$('#location').val(reqdata.location1);
				$('#business_unit').val(reqdata.business_unit);
				$('#department_name').val(reqdata.department_id);
				
				$('#designations').append(reqdata.designation);
				$('#designations').val(reqdata.designation_id);
				check_repot();
				get_reporters(reqdata.teamlead_id);
				
				$('#emp_history').html('');
				$('#emp_history').hide();
				if(reqdata.historys !=''){
					$('#emp_history').html(reqdata.historys);
					$('#emp_history').show();
				}
				$('#type_change').refresh();;
				
			}
		});
	}
	function entity_change(val) {
			if(val != '') {
				$.ajax({
				type: "POST",
				url: '<?php echo base_url('employees/get_branch_prefix/')?>',
				data:  {id:val},
					success: function (datas) {
						var response = JSON.parse(datas);
						$('#prefix').val(response.branch_prefix);
					}
				});
			}
	}
	function get_reporters(a){
		var dept_id = $('#department_name').val();
        var des_id = $('#designations').val();
        $.post('<?php echo base_url('employees/teamlead_options/')?>',{des_id:des_id,dept_id:dept_id},function(res){
            var leads_name = JSON.parse(res);
            $('#reporting_to').empty();
            $('#reporting_to').append("<option value='' selected disabled='disabled'>Reporter's Name</option>");
            for(i=0; i<leads_name.length; i++) {
                $('#reporting_to').append("<option value="+leads_name[i].id+">"+leads_name[i].username+"</option>");                      
            }
				$('#reporting_to').val(a);
        });
	}
	</script>

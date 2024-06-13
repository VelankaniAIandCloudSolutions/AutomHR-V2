				<!-- Page Content -->
                <div class="content container-fluid">
				
					<!-- Page Title -->
					<div class="row">
						<div class="col-sm-5 col-5">
							<h4 class="page-title">Promotion</h4>
						</div>
						<div class="col-sm-7 col-7 text-right m-b-30">
						<?php if(($this->session->userdata('role_id') == 1) || ($this->session->userdata('role_id') == 4) || ($this->session->userdata('user_type_name') == 'company_admin')){ ?>
							<a href="#" class="btn add-btn" onclick="add_promotion()"><i class="fa fa-plus"></i> Add Promotion</a>
						<?php }?>
						</div>
					</div>
					<!-- /Page Title -->
					
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-striped custom-table mb-0 datatable" id="promotion_table">
									<thead>
										<tr>
											<th style="width: 30px;">#</th>
											<th>Promoted Employee </th>
											<th>Department</th>
											<th>Promotion From </th>
											<th>Promotion To </th>
											<th>Promotion Date </th>
											<?php if($this->session->userdata('role_id') != 1){
												?>
												<th>Status</th>
											<?php
											}?>
											
											<th class="text-right">Action</th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
                </div>
				<!-- /Page Content -->
				
				<!-- Add Promotion Modal -->
				<div id="add_promotion" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Add Promotion</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form action="#"  id="add_promotions" method="post" enctype="multipart/form-data" data-parsley-validate novalidate  > 
									<input type="hidden" name="id" id="edit_id">
									<div class="form-group">
										<label>Promotion For <span class="text-danger">*</span></label>
										<select class="select2-option1 form-control" style="width:100%;" name="employee" id="employee_id1"> 
											
									   </select>
									</div>
									
									<div class="form-group">
										<label>Promotion From </label>
										<input type="hidden" id="cur_prm_to">
										<input class="form-control" id="designation" name="designation" type="hidden" readonly>
										<input class="form-control" id="grade" name="grade" type="hidden"  readonly>
										<input class="form-control" id="grade_name" name="grade_name" type="text"  readonly>
									</div>
									<div class="form-group">
										<label>Department <span class="text-danger">*</span></label>
										<select class="form-control" id="department" style="width:100%;" name="department" onchange="promotion_department(this.value)">
											<option value="">--Select--</option>
										</select>
									</div>
									<div class="form-group">
										<label>Promotion To <span class="text-danger">*</span></label>
										<select class="select2-option form-control promotion" id="promotionto" style="width:100%;" name="promotionto" >
											<option value="">--Select--</option>
										</select>
									</div>
									<div class="form-group">
										<label>Promotion Date <span class="text-danger">*</span></label>
										<div class="cal-icon">
											<input type="text" id="promotiondate" name="promotiondate" class="form-control datetimepicker">
										</div>
									</div>

									<div class="form-group">
										<label>Current Salary <span class="text-danger">*</span></label>
										<input type="number" id="currentsalary" name="currentsalary" class="form-control">
									</div>
									<div class="form-group">
										<label>New Salary <span class="text-danger">*</span></label>
										<input type="number" id="newsalary" name="newsalary" class="form-control">
									</div>
									<div class="form-group">
										<label>Effective Date <span class="text-danger">*</span></label>
										<div class="cal-icon">
											<input type="text" id="effectivedate" name="effectivedate" class="form-control datetimepicker">
										</div>
									</div>
									<?php /*<div class="form-group">
										<label>Promotion Letter <span class="text-danger">*</span></label>
										<input type="file" id="promotionletter" name="promotionletter"  class="form-control">
									</div>*/?>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn" id="btnSave">Submit</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Add Promotion Modal -->
				
				
				<!-- Accept Promotion Modal -->
				<div class="modal custom-modal fade" id="send_promotion" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-head">
									<h3>Send Promotion</h3>
									<p>Are you sure want to send promotion?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-xs-6">
											<a href="javascript:void(0);" id="accept_promotion" class="btn btn-primary continue-btn" onclick="accept_promotion()">Confirm</a>
										</div>
										<div class="col-xs-6">
											<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Accepet Promotion Modal -->
				
				<!-- Delete Promotion Modal -->
				<div class="modal custom-modal fade" id="delete_promotion" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-head">
									<h3>Delete Promotion</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-xs-6">
											<a href="javascript:void(0);" id="delete_promotions" class="btn btn-primary continue-btn">Delete</a>
										</div>
										<div class="col-xs-6">
											<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Delete Promotion Modal -->
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
				<script>
				$(document).ready(function () {
					$(".select2-option1").select2({
						minimumInputLength: 3,
						// tags: [],
						ajax: {
							url: "<?php echo base_url('promotion/getEmployees');?>",
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
					}).on("change", function (e) {
						var employeeid=$(this).val();
						$('#department').html('');
						$('#department').find('option').remove().end();
						$.post('<?php echo base_url();?>promotion/get_designation',{employeeid:employeeid},function(data){
							
							var obj=jQuery.parseJSON(data);
							$('#designation').val(obj.designation_id);
							$('#grade').val(obj.designation);
							$('#grade_name').val(obj.designation);

						});
						let edit_id = $('#edit_id').val();
						

						$.post('<?php echo base_url();?>promotion/get_department',{employeeid:employeeid,edit_id:edit_id},function(data){
							
							var obj=jQuery.parseJSON(data);

							//$('#department').select2('destroy');
							//$("#department").select2();
							$(obj).each(function(){
								var option = $('<option />');
								option.attr('value', this.deptid).text(this.deptname);           
								$('#department').append(option);
								
							}); 
							$('#department').val(obj[0].department_id);
							//$('#department').select2('destroy');
							//$("#department").select2();
							//$('#department').val(obj.department_id);

						});


						$.post('<?php echo base_url();?>promotion/getemployeeAnnualctc',{employeeid:employeeid,edit_id:edit_id},function(data){
							
							// var obj=jQuery.parseJSON(data);
							$('#currentsalary').val(data);
						});


// if(edit_id!=''){
// 					let cur_dept = $('#department').val();
// 					promotion_department(cur_dept);
					
					
// 				}
								if(edit_id==''){

						$.ajax({
							type: "POST",
							url: "<?php echo base_url();?>promotion/get_grades",
							data:{employeeid:employeeid,edit_id:edit_id}, 
							beforeSend :function(){
							$("#promotionto option:gt(0)").remove(); 
							$('#promotionto').select2('destroy');
							$("#promotionto").select2();
							$('#promotionto').find("option:eq(0)").html("Please wait..");
							$('#promotionto').select2('destroy');
							$("#promotionto").select2();
							},                         
							success: function (data) {   
							var obj=jQuery.parseJSON(data);
							$('#promotionto').select2('destroy'); 
							$("#promotionto").select2();      
							$('#promotionto').find("option:eq(0)").html("--Select--");
							$('#promotionto').select2('destroy');
							$("#promotionto").select2();
							var obj=jQuery.parseJSON(data);       
							$('#promotionto').select2('destroy');
							$("#promotionto").select2();
							//$('#promotionto').empty();
							$(obj).each(function(){
								if(this.id!=''){
								var option = $('<option value="'+this.id+'">'+this.designation+'</option>');
								//option.attr('value', this.designation_id).text(this.designation);  
								
								$('#promotionto').append(option);
								}
							});  
							
							$('#promotionto').select2('destroy');
							$("#promotionto").select2();
							}
						});  
								}
					});
				});
				
				
				function promotion_department(deptid){
					$('#promotionto').empty().trigger("change");
					
					$.post('<?php echo base_url();?>promotion/get_designation_by_dept',{deptid:deptid},function(data){
							
						var obj=jQuery.parseJSON(data);
						
						$('#promotionto').select2('destroy'); 
							$("#promotionto").select2();      
							$('#promotionto').find("option:eq(0)").html("--Select--");

						$('#promotionto').select2('destroy');
						$("#promotionto").select2();
						$(obj).each(function(){
							var option = $('<option />');
							option.attr('value', this.id).text(this.designation);           
							$('#promotionto').append(option);
						});       
						let prm_to = $('#cur_prm_to').val();
					$("#promotionto").val(prm_to);

					});
				}
				</script>
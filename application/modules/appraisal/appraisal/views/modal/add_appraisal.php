<!--Add Performance Appraisal Modal -->
<style>
    .select2-results__options > li:first-child{
   display: none;
}
.select2-results__message{
    display:block !important;
}
    </style>
					<div class="modal-dialog modal-lg  modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title"><?php echo lang('give_performance_appraisal'); ?></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form action="<?php echo site_url('appraisal/add_appraisal')?>" method="post" id="add_appraisal" name="add_appraisal">
									<div class="row">
										<div class="col-sm-12">
											<div class="form-group">
												<label><?php echo lang('employee');?> <span class="text-danger">*</span></label>
												<select class="select2-option form-control" name="employee_id" style="width:100%;" required onchange="select_indicators(this.value)" >
													<!-- <option value=""><?php echo lang('select_employee'); ?></option>
													<?php foreach($employees as $emp) { ?>
													<option value="<?php echo $emp->id;?>"><?php echo $emp->username;?></option>
													<?php } ?> -->
												</select>
											</div>
											<div class="form-group">
												<label><?php echo lang('select_date');?> <span class="text-danger">*</span></label>
												<div class="cal-icon"><input class="form-control datetimepicker" type="text" name="appraisal_date" required data-date-format="dd/mm/yyyy"  data-date-start-date="0d"></div>
											</div>
										</div>
										<div class="col-sm-12">
											<div class="" id="card_details">
												
                          <div class="tab-box">
                            <div class="row user-tabs">
                              <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                                <ul class="nav nav-tabs nav-tabs-solid">
                                  <li class="nav-item"><a href="#appr_technical" data-toggle="tab" class="nav-link active"><?php echo lang('technical'); ?></a></li>
                                  <li class="nav-item"><a href="#appr_organizational" data-toggle="tab" class="nav-link"><?php echo lang('organizational');?></a></li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <div class="tab-content" >
                            <div id="appr_technical" class="pro-overview tab-pane fade show active">
                              <div class="row">
                                <div class="col-sm-12">
                                  <div class="bg-white table-responsive">
                                    <table class="table">
                                      <thead>
                                       <tr>
                                        <th colspan="5"><?php echo lang('technical_competencies');?></th> 
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                        <th colspan="2" style="font-weight: 500;"><?php echo lang('indicator');?></th>
                                        <th colspan="2" style="font-weight: 500;"><?php echo lang('expected_value');?></th>
                                        <th style="font-weight: 500;"><?php echo lang('set_value');?></th>
                                        </tr>
                                        <?php foreach($indicator_names['technical'] as $key => $value){
                                        ?>
                                        	<tr>
                                        <td scope="row" colspan="2"><?php echo $value;?></td>
                                        <td colspan="2" id="level_<?php echo $key;?>"></td>
                                        <td style="min-width:150px">
                                        	<select name="levels[<?php echo $key;?>]" class="form-control">
	                                          
	                                          <option value="1"> <?php echo lang('beginner')?></option>
	                                          <option value="2"> <?php echo lang('intermediate')?></option>
	                                          <option value="3"> <?php echo lang('advanced')?></option>
	                                          <option value="4"><?php echo lang('expert_leader')?> </option>
                                          </select>
                                      </td>
                                        </tr>
                                        <?php	
                                        }?>
                                        
                                       
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="tab-pane fade" id="appr_organizational">
                              <div class="row">
                                <div class="col-sm-12">
                                  <div class="bg-white table-responsive">
                                    <table class="table table-bordered">
                                      <thead>
                                        <tr>
                                          <th colspan="5" ><?php echo lang('organizational_competencies')?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                          <th colspan="2" style="font-weight: 500;"><?php echo lang('indicator');?></th>
                                        <th colspan="2" style="font-weight: 500;"><?php echo lang('expected_value');?></th>
                                        <th style="font-weight: 500;"><?php echo lang('set_value');?></th>
                                        </tr>

                                         <?php foreach($indicator_names['organization'] as $key1 => $value1){
                                        ?>
                                        	<tr>
                                        <td scope="row" colspan="2"><?php echo $value1;?></td>
                                        <td colspan="2" id="level_<?php echo $key1;?>"></td>
                                        <td style="min-width:150px">
                                        	<select name="levels[<?php echo $key1;?>]" class="form-control">
	                                        
	                                          <option value="1"> <?php echo lang('beginner')?></option>
	                                          <option value="2"> <?php echo lang('intermediate')?></option>
	                                          <option value="3"> <?php echo lang('advanced')?></option>
	                                          <option value="4"><?php echo lang('expert_leader')?> </option>
                                          </select>
                                      </td>
                                        </tr>
                                        <?php	
                                        }?>
                                        
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
											
										</div>
										<div class="col-sm-12">
											<div class="form-group">
												<label class=""><?php echo lang('status')?></label>
												<select class="select form-control" name="status" required>
													<option value="1"><?php echo lang('active') ?></option>
													<option value="0"><?php echo lang('inactive')?></option>
												</select>
											</div>
										</div>
									</div>
									
									<div class="submit-section">
										<button type="submit" name="submit" id="add_appraisal_btn"class="btn btn-primary submit-btn" onclick="appraisal_from();"><?php echo lang('submit')?></button>
									</div>
								</form>
							</div>
						</div>
					</div>
				
				<!-- /Add Performance Appraisal Modal-->
        <script>
           $(".select2-option").select2({
              minimumInputLength: 3,
              tags: [],
              ajax: {
                  url: "<?php echo base_url('appraisal/getEmployees');?>",
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
        </script>
			
				<!-- Page Content -->
                <div class="content">
                	<div class="page-header">
					<div class="row">
						<div class="col-sm-7 col-12">
							<h4 class="page-title"><?php echo lang('performance_appraisal');?></h4>
							<ul class="breadcrumb mb-3">
								<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
								<li class="breadcrumb-item active"><?php echo lang('performance_appraisal');?></li>
							</ul>
						</div>
				        <div class="col-sm-5 col-12 text-right m-b-20">
				           
						   <?php
						   if(App::is_permit('menu_appraisal','create') || $visiable=='1')
						   {
							?>
						   <a href="<?php echo site_url('appraisal/add_appraisal');?>" class="btn add-btn" data-toggle="ajaxModal"><i class="fa fa-plus"></i> <?php echo lang('add_new');?></a>
							<?php
						   }
							?>
							<button style="margin-right:10px;" id="export" name="export" onclick="export_appraisal(); return false;" class="btn add-btn" > Export </button>
						</div>
					</div>
					
					
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table id="table-templates-1" class="table table-striped custom-table m-b-0 AppendDataTables">
									<thead>
										<tr>
											<th style="width: 30px;">#</th>
											<th><?php echo lang('employee');?></th>
											<th><?php echo lang('designation');?></th>
											<th><?php echo lang('department')?></th>
											<th><?php echo lang('appraisal_date')?></th>
											<th><?php echo lang('status');?></th>
											<th><?php echo 'Score';?></th>
											<?php
											App::is_permit('menu_appraisal','write');
											if(App::is_permit('menu_appraisal','write')==true|| App::is_permit('menu_appraisal','delete')==true)
											{

											?>
											<th class="col-options no-sort text-right"><?php echo lang('action'); ?></th>
											<?php
											}
											?>
										</tr>
									</thead>
									<tbody>
										<?php 
										$i=1;
										
										foreach($appraisals as $appr) { ?>
										<tr>
											<td><?php echo $i++;?></td>
											<td>
												<a href="<?php if(App::is_permit('menu_appraisal','read')){?><?php echo site_url('appraisal/view_appraisal/').$appr->id;?>" data-toggle="ajaxModal"  <?php }else{ echo '#';}?>">  <?php echo ucfirst(User::displayName($appr->employee_id));?></a>
											</td>
											<td><?php echo ucfirst($appr->designation); ?> </td>
											<td><?php echo ucfirst($appr->deptname);?></td>
											<td>
												<?php echo date('d M Y',strtotime($appr->appraisal_date));?>
											</td>
											
											
											
											
											<td>
												<div class="dropdown action-label">
													<a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
														<?php if($appr->status==1){
															echo '<i class="fa fa-dot-circle-o text-success"></i> '.lang('active');
														}else{
															echo '<i class="fa fa-dot-circle-o text-danger"></i> '.lang('inactive');
														}?>
														
													</a>
													<div class="dropdown-menu">
														<a class="dropdown-item" href="<?php echo site_url('appraisal/appraisal_status/').$appr->id.'/1';?>"><i class="fa fa-dot-circle-o text-success"></i> <?php 
														echo lang('active');?></a>
														<a class="dropdown-item" href="<?php echo site_url('appraisal/appraisal_status/').$appr->id.'/0';?>"><i class="fa fa-dot-circle-o text-danger"></i> <?php 
														echo lang('inactive');
														?></a>
													</div>
												</div>
											</td>
											
											<td>
												<?php 
												$avgg = 0;
												$this->db->select("*");
												$this->db->from("dgt_employee_appraisal");
												$this->db->where("id",$appr->id);
												$tmp_response = $this->db->get()->row_array();
												
												$kpo_total = 0;

												if(!empty($tmp_response))
												{
												$Teamwork_array = json_decode($tmp_response['Teamwork'], true);
												$Job_Knowledge_array = json_decode($tmp_response['Job_Knowledge'], true);
												$Work_Habits_array = json_decode($tmp_response['Work_Habits'], true);
												$Quality_Of_Work_array = json_decode($tmp_response['Quality_Of_Work'], true);
												$kpo_total = array_sum($Teamwork_array) +  array_sum($Job_Knowledge_array) + 															 array_sum($Work_Habits_array) + array_sum($Quality_Of_Work_array);
												
												$total_size_of_kpo = sizeof($Teamwork_array) + sizeof($Job_Knowledge_array) + sizeof($Work_Habits_array) + sizeof($Quality_Of_Work_array);

$avgg = round(($kpo_total / $total_size_of_kpo) , 2) ;

	

												}
												echo $avgg;

												?>
											</td>
											
											<?php
											if(App::is_permit('menu_appraisal','write')==true|| App::is_permit('menu_appraisal','delete')==true)
											{

											?>
											<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
													<div class="dropdown-menu float-right">
														<?php if(App::is_permit('menu_appraisal','write')){?><a class="dropdown-item" href="<?php echo site_url('appraisal/edit_appraisal/').$appr->id;?>" data-toggle="ajaxModal" ><i class="fa fa-pencil m-r-5"></i> <?php echo lang('edit');?></a><?php }?>
											            <?php if(App::is_permit('menu_appraisal','delete')){?><a class="dropdown-item" href="<?php echo site_url('appraisal/delete_appraisal/').$appr->id;?>" data-toggle="ajaxModal"  ><i class="fa fa-trash-o m-r-5"></i> <?php echo lang('delete');?></a><?php }?>
													</div>
												</div>
											</td>
											<?php 
											}
											?>
										</tr>
									<?php  } ?>
										
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				
                	
        </div>
				<!-- /Page Content -->

				<?php //$this->load->view('modal/add_appraisal')?>
				
				
				
				 <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
				 
				<script>
    function export_appraisal() {
  const table = document.getElementById("table-templates-1");

  // Store original cell content
  const originalContent = [];

  for (let i = 0; i < table.rows.length; i++) {
    originalContent[i] = [];
    for (let j = 0; j < table.rows[i].cells.length; j++) {
      let cellContent = table.rows[i].cells[j].textContent.trim();
      originalContent[i][j] = cellContent;
      if (cellContent.length > 0 && j !== 0) {
        table.rows[i].cells[j].textContent = cellContent.substring(0);
      }
    }
  }

  // Convert the modified table to Excel file
  const wb = XLSX.utils.table_to_book(table, { sheet: "SheetJS" });
  XLSX.writeFile(wb, "appraisal.xlsx");

  // Restore original cell content
  for (let i = 0; i < originalContent.length; i++) {
    for (let j = 0; j < originalContent[i].length; j++) {
      table.rows[i].cells[j].textContent = originalContent[i][j];
    }
  }

  // Reload the page after exporting
  window.location.reload(true);
}
  </script>
			
           


<!-- Content -->
<div class="content container-fluid">
					
					<div class="page-header">
					<!-- Title -->
					<div class="row">
						<div class="col-12 col-md-8 col-sm-9">
							<h4 class="page-title">Offer Approval Dashboard</h4>
							<ul class="breadcrumb">
			                    <li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
			                    <li class="breadcrumb-item active">Offer Approval Dashboard</li>
			                </ul>
						</div>
						<!-- <div class="col-md-4 col-12 col-sm-3">
							<div class="float-right">
								<a href="<?=base_url()?>offers/create" class="btn add-btn text-center m-b-20">Create Offer</a>
							</div>
						</div> -->
					</div>
				</div>
					<!-- / Title -->
					<div class="row">
						<?php /*<div class="col-md-6 col-sm-6 col-lg-4">
							<a href="#" class="dash-widget-pro showTable1">
								<div class="dash-widget card-box">
									<div class="dash-widget-info text-center">
										<span class="offer-total dash-color-1"><?=count($inprogress) ?></span><br>
										<span class="text-center dash-text">Offer Approval In Progress</span>
									</div>
								</div>
							</a>
						</div>*/?>
						<div class="col-md-6 col-sm-6 col-lg-4">
							<a href="#" class="dash-widget-pro showTable2">
								<div class="dash-widget card-box">
									<div class="dash-widget-info text-center">
										<span class="offer-total dash-color-2"><?=count($ready) ?></span><br>
										<span class="text-center dash-text">Offer Approved & Ready to be Send</span>
									</div>
								</div>
							</a>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-4">
							<a href="#" class="dash-widget-pro showTable7">
								<div class="dash-widget card-box">
									<div class="dash-widget-info text-center">
										<span class="offer-total dash-color-6"><?=count($rejected) ?></span><br>
										<span class="text-center dash-text">Rejected Offer</span>
									</div>
								</div>
							</a>
						</div>
						
						<div class="col-md-6 col-sm-6 col-lg-4">
							<a href="#" class="dash-widget-pro showTable3">
								<div class="dash-widget card-box">
									<div class="dash-widget-info text-center">
										<span class="offer-total dash-color-3"><?=count($send) ?></span><br>
										<span class="text-center dash-text">Offers Send</span>
									</div>
								</div>
							</a>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-4">
							<a href="#" class="dash-widget-pro showTable4">
								<div class="dash-widget card-box">
									<div class="dash-widget-info text-center">
										<span class="offer-total dash-color-4"><?=count($accept) ?></span><br>
										<span class="text-center dash-text">Offers Accepted</span>
									</div>
								</div>
							</a>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-4">
							<a href="#" class="dash-widget-pro showTable5">
								<div class="dash-widget card-box">
									<div class="dash-widget-info text-center">
										<span class="offer-total dash-color-5"><?=count($declined) ?></span><br>
										<span class="text-center dash-text">Offers Declined</span>
									</div>
								</div>
							</a>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-4">
							<a href="#" class="dash-widget-pro showTable6">
								<div class="dash-widget card-box">
									<div class="dash-widget-info text-center">
										<span class="offer-total dash-color-6"><?=count($archived) ?></span><br>
										<span class="text-center dash-text">Archived Offers</span>
									</div>
								</div>
							</a>
						</div>
						
					</div>
					<div class="card">
						<div class="card-body">
					<div id="table-7" style="display:none">
					<div class="row">
						<div class="col-md-12">
							<h3 class="page-sub-title m-t-0">Rejected Offers (<?=count($rejected) ?>)</h3>
							<div class="table-responsive">
								<table class="table custom-table datatable table-bordered">
									<thead>
										<tr>
											<th style="width:20%;">Name</th>
											<th>Position</th>
											<th>Action</th>
											<th style="min-width:700px;">Status</th>
										</tr>
									</thead>
									<tbody>

										<?php 
										foreach ($rejected as $ipk => $ipv) {
											$query = "SELECT * from dgt_jobs where id = '".$ipv['title']."' ";
	            							$req_job = $this->db->query($query)->row_array();

											$query = "SELECT * from dgt_registered_candidates where id = '".$ipv['name']."' ";
	            							$req_candidate = $this->db->query($query)->row_array();
											
										?>

										<tr>
											<td>
												<a href="#" class="avatar"><img class="rounded-circle" src="assets/img/user.jpg" alt=""></a>
												<h2><a href="#"><?=ucfirst($req_candidate['first_name'].' '.$req_candidate['last_name'])?></a></h2>
											<td>
												<span><?=ucfirst($req_job['job_title'])?></span>
											</td>
											<td  class="text-center">
												<a href="#" class="like-icon m-r-10"><i class="fa fa-file-archive-o" title="Archive" aria-hidden="true" onclick="app_archive('1','<?=$ipv['id']?>')"></i></a>

												<?php /*<a href="#" class="like-icon text-danger" data-toggle="modal" data-target="#offer_decline" onclick="set_appval('<?=$ipv['id']?>')"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></a>*/?>
												<a href="#" class="like-icon  text-success m-r-10"><i class="fa fa-check" title="Accept" aria-hidden="true" onclick="app_accept('<?=$ipv['id']?>')"></i></a>
											</td>
											<td>
												<div class="tabbable">
													<ul class="nav navbar-nav wizard flex-row m-b-0">
														<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Created</a>
														</li>
														<?php /*<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>In Progress</a>
														</li>*/?>
														<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Rejected</a>
														</li>
														
														<li class=" nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Send</a>
														</li>
														<li class=" nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Accepted </a>
														</li>
														<li class=" nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Onboard</a>
														</li>
													</ul>
												</div>
											</td>
										</tr>
										<?php } ?> 
										 
									</tbody>
								</table>
							</div>
						</div>
					</div>
					</div>
					<?php /*<div id="table-1">
					<div class="row">
						<div class="col-md-12">
							<h3 class="page-sub-title m-t-0">Offer Approval In Process (<?=count($inprogress) ?>)</h3>
							<div class="table-responsive">
								<table class="table custom-table datatable table-bordered">
									<thead>
										<tr>
											<th style="width:20%;">Name</th>
											<th>Position</th>
											<th>Action</th>
											<th style="min-width:700px;">Status</th>
										</tr>
									</thead>
									<tbody>

										<?php 
										foreach ($inprogress as $ipk => $ipv) {
											$query = "SELECT * from dgt_jobs where id = '".$ipv['title']."' ";
	            							$req_job = $this->db->query($query)->row_array();

											$query = "SELECT * from dgt_registered_candidates where id = '".$ipv['name']."' ";
	            							$req_candidate = $this->db->query($query)->row_array();
										?>

										<tr>
											<td>
												<a href="#" class="avatar"><img class="rounded-circle" src="assets/img/user.jpg" alt=""></a>
												<h2><a href="#"><?=ucfirst($req_candidate['first_name'].' '.$req_candidate['last_name'])?></a></h2>
											<td>
												<span><?=ucfirst($req_job['job_title'])?></span>
											</td>
											<td  class="text-center">
												<a href="#" class="like-icon m-r-10"><i class="fa fa-file-archive-o" title="Archive" aria-hidden="true" onclick="app_archive('1','<?=$ipv['id']?>')"></i></a>

												<a href="#" class="like-icon text-danger" data-toggle="modal" data-target="#offer_decline"  onclick="set_appval('<?=$ipv['id']?>')"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></a>
												<a href="#" class="like-icon  text-success m-r-10"><i class="fa fa-check" title="Accept" aria-hidden="true" onclick="app_accept('<?=$ipv['id']?>')"></i></a>
											</td>
											<td>
												<div class="tabbable">
													<ul class="nav navbar-nav wizard flex-row m-b-0">
														<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Created</a>
														</li>
														<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>In Progress</a>
														</li>
														<li class=" nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Approved</a>
														</li>
														
														<li class=" nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Send Offer</a>
														</li>
														<li class=" nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Accepted </a>
														</li>
														<li class=" nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Onboard</a>
														</li>
													</ul>
												</div>
											</td>
										</tr>
										<?php } ?> 
										 
									</tbody>
								</table>
							</div>
						</div>
					</div>
					</div>*/?>
					<div id="table-2" style="display:block !important;">

					<div class="row">
						<div class="col-md-12">
							<h3 class="page-sub-title m-t-0">Offer Approval & Ready to be Send (<?=count($ready) ?>)</h3>
							<div class="table-responsive">
								<table class="table custom-table datatable table-bordered">
									<thead>
										<tr>
											<th style="width:20%;">Name</th>
											<th>Position</th>
											<th>Status</th>
											<th>Action</th>
											<th>Download Offer Letter </th>
										</tr>
									</thead>
									<tbody>
										<?php 
										foreach ($ready as $rk => $rv) {
											$query = "SELECT * from dgt_jobs where id = '".$rv['title']."' ";
	            							$req_job = $this->db->query($query)->row_array();
											$query = "SELECT * from dgt_registered_candidates where id = '".$rv['name']."' ";
	            							$req_candidate = $this->db->query($query)->row_array();
											$query = "SELECT * from dgt_offer_approvers where offer = '".$rv['offer_id']."' and status!='2' ";
	            							$check_offer_approver = $this->db->query($query)->row_array();
										?>
										<tr>
											<td>
												<a href="#" class="avatar"><img class="rounded-circle" src="assets/img/user.jpg" alt=""></a>
												<h2><a href="#"><?=ucfirst($req_candidate['first_name'].' '.$req_candidate['last_name'])?></a></h2>
											<td>
												<span><?=ucfirst($req_job['job_title'])?></span>
											</td>
											<td>
												<div class="tabbable">
													<ul class="nav navbar-nav flex-row wizard m-b-0">
														<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Created</a>
														</li>
														<?php /*<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>In Progress</a>
														</li>
														<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Approved</a>
														</li>*/?>
														
														<li class=" nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Send</a>
														</li>
														<li class=" nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Accepted</a>
														</li>
														<li class=" nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Onboard</a>
														</li>
													</ul>
												</div>
											</td>
											<td class="text-center">
												<a href="#" class="like-icon m-r-10"><i class="fa fa-file-archive-o" title="Archive" aria-hidden="true" onclick="app_archive('2','<?=$rv['id']?>')"></i></a>
												<?php if(empty($check_offer_approver)){?>
												<a href="#" onclick="send_Appmails('<?=$rv['id']?>')" class="btn btn-info">Offer Send</a>
												<?php }?>

											</td>
											<td class="text-center">
												<a href="<?php echo base_url('offers/offer_letter_download_pdf/').$req_job['id'].'/'.$req_candidate['id'];?>"><i class="fa fa-file-pdf-o" style="font-size:36px;color:red"></i></a> &nbsp;&nbsp;
												<a href="<?php echo base_url('offers/offer_letter_download_word/').$req_job['id'].'/'.$req_candidate['id'];?>"><i class="fa fa-file-word-o" style="font-size:36px;color:blue"></i></a>
											</td>
										</tr><?php } ?> 
									</tbody>
								</table>
							</div>
						</div>
					</div>
					</div>

					


					<div id="digital_signature_div" class="modal custom-modal fade center-modal" role="dialog">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title">Offer Send</h4>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>
								<div class="modal-body">
									<form enctype="multipart/form-data" id="prv_form">					
										<input id="ch_asso_id" type="hidden">
										<div class="form-group">
											<label>Signature Type</label>
											<input type="radio" name="signature_type" value="digital_signature" checked onchange="signature_div('signature_div')">Add Digital Signature
											<input type="radio" name="signature_type" value="upload_image" onchange="signature_div('sign_image')">Upload Signature
										</div>
										<div class="form-group sign_div" id="sign_image" style="display:none">
											<label>Upload Signature</label>
											<input type="file" id="sign_image_file" name="sign_image">
											<p style="color:red;display:none;" id="sign_img_error" class="eror_sign">Please Upload Signature</p>
											<p style="color:red;display:none;" id="sign_img_val_error" class="eror_sign">Please Upload Only Image</p>
										</div>					
										<div class="form-group sign_div" id="signature_div">
											<label>Digital Signature: <span class="text-muted-light"></span></label>
											<br>
											<div id="sig"></div>
											<textarea id="signature64" name="signature" style="display: none"></textarea>
											<p style="color:red;display:none;" id="sign_error">Please Sign Here</p>
										</div>
										<div class="col-md-12 sign_div" id="clear_sign">
											<button class="btn btn-sm btn-warning" id="clear">&#x232B;Clear Signature</button>
										</div>
										<div class="m-t-50 text-center">
											<button class="btn btn-primary btn-lg" type="button"  onclick="appmails_preview()">Next</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>


					<div id="digital_signature_preview" class="modal custom-modal fade center-modal" role="dialog">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title">Offer Preview</h4>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>
								<div class="modal-body">				
									<input id="ch_asso_id_preview" type="hidden">				
									<input id="ch_asso_id_img" type="hidden">				
									<div id="preview_div"></div>
									<div class="m-t-50 text-center">
										<button class="btn btn-primary btn-lg" type="button"  onclick="send_offer_letter_mail()">Send Offer</button>
									</div>
								</div>
							</div>
						</div>
					</div>

					<style>
					.kbw-signature {
						width: 500px;
						height: 300px;
					}

					#sig canvas {
						width: 100% !important;
						height: auto;
					}
				</style>



					
					<div id="table-3"><hidden value='' id='tab_hide' ></hidden>
					<div class="row">
						<div class="col-md-12">
							<h3 class="page-sub-title m-t-0">Offer Send (<?=count($send) ?>)</h3>
							<div class="table-responsive">
								<table class="table custom-table datatable table-bordered">
									<thead>
										<tr>
											<th style="width:20%;">Name</th>
											<th>Position</th>
											<th>Action</th>
											<th>Status</th>
											
										</tr>
									</thead>
									<tbody>
										<?php 
										foreach ($send as $ipk => $ipv) {
											$query = "SELECT * from dgt_jobs where id = '".$ipv['title']."' ";
	            							$req_job = $this->db->query($query)->row_array();

											$query = "SELECT * from dgt_registered_candidates where id = '".$ipv['name']."' ";
	            							$req_candidate = $this->db->query($query)->row_array();
										?>
										<tr>
											<td>
												<!-- <a href="profile.html" class="avatar"><img class="rounded-circle" src="assets/img/user.jpg" alt=""></a> -->
												<h2><a href="#"><?=ucfirst($req_candidate['first_name'].' '.$req_candidate['first_name'])?></a></h2>
											<td>
												<span><?=ucfirst($req_job['title'])?></span>
											</td>
											<td class="text-center">
												<!-- <a href="#" class="like-icon text-success m-r-10"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></a> -->
												<a href="#" class="like-icon text-danger" data-toggle="modal" data-target="#offer_decline"  onclick="set_appval('<?=$ipv['id']?>')"><i class="fa fa-thumbs-o-down" title='Decline' aria-hidden="true"></i></a>
												<a href="#" class="like-icon m-r-10"><i class="fa fa-file-archive-o" title="Archive" aria-hidden="true" onclick="app_archive('3','<?=$ipv['id']?>')"></i></a>
												<?php /*<a href="#" class="like-icon  text-success m-r-10"><i class="fa fa-check" title="Accept" aria-hidden="true" onclick="app_accept('<?=$ipv['id']?>')"></i></a>*/?>
											</td>
											<td>
												<div class="tabbable">
													<ul class="nav navbar-nav flex-row wizard m-b-0">
														<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Created</a>
														</li>
														<?php /*<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>In Progress</a>
														</li>
														<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Approved</a>
														</li>
														*/?>
														<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Send</a>
														</li>
														<li>
															<a class="nav-link nav-item" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Accepted</a>
														</li>
														<li>
															<a class="nav-link nav-item" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Onboard</a>
														</li>
													</ul>
												</div>
											</td>
										</tr>
										<?php } ?> 
									</tbody>
								</table>
							</div>
						</div>
					</div>
					</div>
					<div id="table-4">
					<div class="row">
						<div class="col-md-12">
							<h3 class="page-sub-title m-t-0">Offer Accepted (<?=count($accept) ?>)</h3>
							<div class="table-responsive">
								<table class="table custom-table datatable table-bordered">
									<thead>
										<tr>
											<th style="width:20%;">Name</th>
											<th>Position</th>
											<th>Action</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										foreach ($accept as $ipk => $ipv) {
											$query = "SELECT * from dgt_jobs where id = '".$ipv['title']."' ";
	            							$req_job = $this->db->query($query)->row_array();
											$query = "SELECT * from dgt_registered_candidates where id = '".$ipv['name']."' ";
	            							$req_candidate = $this->db->query($query)->row_array();
										?>
										<tr>
											<td>
												<a href="#" class="avatar"><img class="rounded-circle" src="assets/img/user.jpg" alt=""></a>
												<h2><a href="#"><?=ucfirst($req_candidate['first_name'].' '.$req_candidate['last_name'])?></a></h2>
											<td>
												<span><?=ucfirst($req_job['job_title'])?></span>
											</td>
											<td class="text-center">
												<!-- <a href="#" class="like-icon text-success m-r-10"><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></a> -->
												<a href="#" class="like-icon text-danger" data-toggle="modal" data-target="#offer_decline"  onclick="set_appval('<?=$ipv['id']?>')"><i class="fa fa-thumbs-o-down" title='Decline' aria-hidden="true"></i></a>
												<a href="#" class="like-icon m-r-10"><i class="fa fa-file-archive-o" title="Archive" aria-hidden="true" onclick="app_archive('4','<?=$ipv['id']?>')"></i></a>
												<a href="<?php echo base_url()?>offers/onboarding/<?=$ipv['id']?>" title="Onboarding" aria-hidden="true" class="m-r-10">Onboarding</a>
											</td>
											<td>
												<div class="tabbable">
													<ul class="nav navbar-nav flex-row wizard m-b-0">
														<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Created</a>
														</li>
														<?php /*<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>In Progress</a>
														</li>
														<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Approved</a>
														</li>*/?>
														
														<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Send</a>
														</li>
														<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Accepted</a>
														</li>
														<li class=" nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Onboard</a>
														</li>
													</ul>
												</div>
											</td>
											
										</tr>
										 <?php }?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					</div>
					<div id="table-5">
					<div class="row">
						<div class="col-md-12">
							<h3 class="page-sub-title m-t-0">Offer Declined (<?=count($declined) ?>)</h3>
							<div class="table-responsive">
								<table class="table custom-table datatable table-bordered">
									<thead>
										<tr>
											<th style="width:20%;">Name</th>
											<th>Position</th>
											<th>Action</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<?php 
										foreach ($declined as $ipk => $ipv) {
											$query = "SELECT * from dgt_jobs where id = '".$ipv['title']."' ";
	            							$req_job = $this->db->query($query)->row_array();
											$query = "SELECT * from dgt_registered_candidates where id = '".$ipv['name']."' ";
	            							$req_candidate = $this->db->query($query)->row_array();
										?>
										<tr>
											<td>
												<a href="#" class="avatar"><img class="rounded-circle" src="assets/img/user.jpg" alt=""></a>
												<h2><a href="#"><?=ucfirst($req_candidate['first_name'].' '.$req_candidate['last_name'])?></a></h2>
											<td>
												<span><?=ucfirst($req_job['job_title'])?></span>
											</td>
											<td class="text-center">
												<!-- <a href="#" class="like-icon text-success m-r-10"><i class="fa fa-thumbs-o-up" onclick="send_Appmails('<?=$ipv['caid']?>')" aria-hidden="true"></i></a> -->
												<!-- <a href="#" class="like-icon text-danger"><i class="fa fa-thumbs-down" aria-hidden="true"></i></a> -->
												<a href="#" class="like-icon m-r-10"><i class="fa fa-file-archive-o" title="Archive" aria-hidden="true" onclick="app_archive('5','<?=$ipv['id']?>')"></i></a>
											</td>
											<td>
												<div class="tabbable">
													<ul class="nav navbar-nav flex-row wizard m-b-0">
														<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Created</a>
														</li>
														<?php /*<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>In Progress</a>
														</li>
														<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Approved</a>
														</li>
														*/?>
														<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Send</a>
														</li>
														<li class="offer-declined nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Declined</a>
														</li>
														<li class="nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Onboard</a>
														</li>
													</ul>
												</div>
											</td>
										</tr>
										 <?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					</div>
					<div id="table-6">
					<div class="row">
						<div class="col-md-12">
							<h3 class="page-sub-title m-t-0">Archived Offers (<?=count($archived) ?>)</h3>
							<div class="table-responsive">
								<table class="table custom-table datatable table-bordered">
									<thead>
										<tr>
											<th style="width:20%;">Name</th>
											<th>Position</th>
											<th>Action</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody><?php 
										foreach ($archived as $ipk => $ipv) {
											$query = "SELECT * from dgt_jobs where id = '".$ipv['title']."' ";
	            							$req_job = $this->db->query($query)->row_array();
											$query = "SELECT * from dgt_registered_candidates where id = '".$ipv['name']."' ";
	            							$req_candidate = $this->db->query($query)->row_array();
										?>
										<tr>
											<td>
												<a href="#" class="avatar"><img class="rounded-circle" src="assets/img/user.jpg" alt=""></a>
												<h2><a href="#"><?=ucfirst($req_candidate['first_name'].' '.$req_candidate['last_name'])?></a></h2>
											<td>
												<span><?=ucfirst($req_job['job_title'])?></span>
											</td>
											<td class="text-center">
												<a href="#" class="like-icon text-success m-r-10"><i class="fa fa-thumbs-o-up" title='Accept' aria-hidden="true" onclick="app_accept('<?=$ipv['id']?>')"></i></a>
												<a href="#" class="like-icon text-danger" data-toggle="modal" data-target="#offer_decline" onclick="set_appval('<?=$ipv['id']?>')"><i class="fa fa-thumbs-o-down" aria-hidden="true"></i></a> 
											</td>
											<td>
												<div class="tabbable">
													<ul class="nav navbar-nav flex-row wizard m-b-0">
														<li class="active nav-item">
															<a href="javascript:void(0)" class="nav-link" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Created</a>
														</li>
														<?php /*<li class="active nav-item">
															<a href="javascript:void(0)" class="nav-link" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>In Progress</a>
														</li>*/?>
														<li class="active nav-item">
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Archived</a>
														</li>
														<?php /*<li >
															<a href="javascript:void(0)" class="nav-link" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Approved</a>
														</li>*/?>
														
														<li>
															<a href="javascript:void(0)" class="nav-link" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Offer Send</a>
														</li>
														
														<li>
															<a class="nav-link" href="javascript:void(0)" ><span class="nmbr"><i class="fa fa-check" aria-hidden="true"></i></span>Onboard</a>
														</li>
													</ul>
												</div>
											</td>
										</tr>
									<?php } ?>	 
									</tbody>
								</table>
							</div>
						</div>
					</div>
					</div>
                </div>
            </div>
        </div>
				<!-- / Content -->
				<!-- Offer Decline Modal -->
				<div class="modal fade" id="offer_decline" role="dialog">
					<div class="modal-dialog modal-sm modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-header">
									<h3>Offer Decline</h3>
									<p>Are you sure want to decline?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-6">
											<a href="javascript:void(0);" onclick='offer_declinejs()' class="btn btn-primary btn-block continue-btn">Decline</a>
										</div>
										<div class="col-6">
											<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-default btn-block continue-btn">Cancel</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Offer Decline Modal -->
				
			<!--	<div class="notification-box">
					<div class="msg-sidebar notifications msg-noti">
						<div class="topnav-dropdown-header">
							<span>Messages</span>
						</div>
						<div class="drop-scroll msg-list-scroll">
							<ul class="list-box">
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">R</span>
											</div>
											<div class="list-body">
												<span class="message-author">Richard Miles </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item new-message">
											<div class="list-left">
												<span class="avatar">J</span>
											</div>
											<div class="list-body">
												<span class="message-author">John Doe</span>
												<span class="message-time">1 Aug</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">T</span>
											</div>
											<div class="list-body">
												<span class="message-author"> Tarah Shropshire </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">M</span>
											</div>
											<div class="list-body">
												<span class="message-author">Mike Litorus</span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">C</span>
											</div>
											<div class="list-body">
												<span class="message-author"> Catherine Manseau </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">D</span>
											</div>
											<div class="list-body">
												<span class="message-author"> Domenic Houston </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">B</span>
											</div>
											<div class="list-body">
												<span class="message-author"> Buster Wigton </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">R</span>
											</div>
											<div class="list-body">
												<span class="message-author"> Rolland Webber </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">C</span>
											</div>
											<div class="list-body">
												<span class="message-author"> Claire Mapes </span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">M</span>
											</div>
											<div class="list-body">
												<span class="message-author">Melita Faucher</span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">J</span>
											</div>
											<div class="list-body">
												<span class="message-author">Jeffery Lalor</span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">L</span>
											</div>
											<div class="list-body">
												<span class="message-author">Loren Gatlin</span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
								<li>
									<a href="chat.html">
										<div class="list-item">
											<div class="list-left">
												<span class="avatar">T</span>
											</div>
											<div class="list-body">
												<span class="message-author">Tarah Shropshire</span>
												<span class="message-time">12:28 AM</span>
												<div class="clearfix"></div>
												<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
											</div>
										</div>
									</a>
								</li>
							</ul>
						</div>
						<div class="topnav-dropdown-footer">
							<a href="chat.html">See all messages</a>
						</div>
					</div>
				</div>
           -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<link rel="stylesheet" href="<?=base_url()?>assets/css/jquery.signature.css" type="text/css" />
<script src="<?=base_url()?>assets/js/jquery.signature.min.js"></script> 
<script type="text/javascript">
	function signature_div(cur_div){
		$('.eror_sign').hide();
		$('.sign_div').hide();
		$('#'+cur_div).show();
		if(cur_div == 'signature_div'){
			$('#clear_sign').show();
		}
	}
	var sig = $('#sig').signature({
		syncField: '#signature64',
		syncFormat: 'PNG'
	});
	$('#clear').click(function(e) {
		e.preventDefault();
		sig.signature('clear');
		$("#signature64").val('');
	});
</script>

                <div class="content container-fluid">
					<div class="row">
						<div class="col-sm-8">
							<h4 class="page-title">Performance Dashboard</h4>
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-4">
							<div class="card-box text-center">
								<h4 class="card-title">Completed Performance Review</h4>
								<span class="perform-icon bg-success-light"><?php echo $completed_performance; ?></span>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="card-box text-center">
								<h4 class="card-title">Outstanding Reviews</h4>
								<span class="perform-icon bg-danger-light"><?php echo $outstanding_performance;?></span>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table custom-table m-b-0">
									<thead>
										<tr>
											<th style="width:30%;">Team</th>
											<th>Self Review</th>
											<th>Peer Reviews</th>
											<th>Your Reviews</th>
											<th>Goals</th>
										</tr>
									</thead>
									<tbody>
										<?php 
											
										$rating_count= count($performances_360);
										$peer_rating = 0;

								                foreach ($performances_360 as $value) { 

								                	$peer_rating = $value['peer_ratings']/2;

								                	$employee_details = $this->db->get_where('users',array('id'=>$value['user_id']))->row_array();
$designation = $this->db->get_where('designation',array('id'=>$employee_details['designation_id']))->row_array();
$account_details = $this->db->get_where('account_details',array('user_id'=>$value['user_id']))->row_array();

								                	?>
								                    	
								                    
										<tr>
											<td>
												<a href="<?=base_url()?>employees/profile_view/<?php echo $value['user_id'];?>" class="avatar"><?php echo substr($account_details['fullname'],0,1);?></a>
												<h2><a href="<?=base_url()?>employees/profile_view/<?php echo $value['user_id'];?>"><?php echo $account_details['fullname']?> <span><?php echo $designation['designation']?></span></a></h2>
											</td>
											<td>
												<div class="rating">
													<?php for ($i=0; $i <5 ; $i++) {

														if($i < $value['self_ratings']){
														echo '<i class="fa fa-star rated"></i>';
														}else{
														echo '<i class="fa fa-star"></i>';
													} 
												}?> 
												</div>
											</td>
											<td>
												<div class="rating">
													<?php for ($i=0; $i <5 ; $i++) {

														if($i < $peer_rating){
														echo '<i class="fa fa-star rated"></i>';
														}else{
														echo '<i class="fa fa-star"></i>';
													} 
												}?> 
												</div>
											</td>
											<td>
												<div class="rating">
													<?php for ($i=0; $i <5 ; $i++) {

														if($i < $value['your_ratings']){
														echo '<i class="fa fa-star rated"></i>';
														}else{
														echo '<i class="fa fa-star"></i>';
													} 
												}?> 
												</div>
											</td>
											<td>
												<div class="progress-wrap">
													<div class="progress progress-xs">
														<div class="progress-bar" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:40%">
															40%
														</div>
													</div>
													<span>40%</span>
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
		 <div class="content container-fluid">

					<div class="row">

						<div class="col-xs-8">

							<h4 class="page-title">Overtime</h4>

						</div>

						

					</div>

					<div class="payroll-table card">
								<table class="table table-hover table-radius">
									<thead>
										<tr>
											<th>#</th>
											<th>Username</th>
											<th>RO</th>
											<th>Description</th>
											<th>Date</th>
											<th>Hours</th>
											<th>Status</th>
											
										</tr>
									</thead>
									<tbody>
										<?php

// $qry='SELECT o.*,u.id,ad.fullname
									// FROM `dgt_overtime` o  
									// left join dgt_users u on u.id = o.user_id 
									// left join dgt_account_details ad on ad.user_id = u.id 
									// where ad.branch_id='.$this->session->userdata("branch_id").' 
									// order by o.id desc';
									// echo $qry;exit;
										$overtime = $this->db->query('SELECT o.*,u.id,ad.fullname
									FROM `dgt_overtime` o  
									left join dgt_users u on u.id = o.user_id 
									left join dgt_account_details ad on ad.user_id = u.id 
									where ad.branch_id='.$this->session->userdata("branch_id").' 
									order by o.id desc')->result_array();
									
									
									
										// $this->db->get('overtime')->result_array();
										 
										if(!empty($overtime))
										{
											$o=1;
											foreach ($overtime as $o_row) {
												

										?>
										<tr>
											<th><?php echo $o++;?></th>
											<?php  $user_details = $this->db->get_where('account_details',array('user_id'=>$o_row['user_id']))->row_array(); ?>
								            <td><?=$user_details['fullname']?></td> 
								            <?php  $ro_details = $this->db->get_where('account_details',array('user_id'=>$o_row['teamlead_id']))->row_array(); ?>
								            <td><?=$ro_details['fullname']?></td>
											<th><?php echo $o_row['ot_description'];?></th>
											<td><?php echo date('d M Y',strtotime($o_row['ot_date']));?></td>
											<td><?php echo $o_row['ot_hours'];?></td>
											<td>
								<?php
									if($o_row['status'] == 0){
										
										echo '<span class="label" style="background:#D2691E"> Pending </span>';
										
									}else if($o_row['status'] == 1){
										echo '<span class="label label-success"> Approved </span> ';
									}else if($o_row['status'] == 2){
										echo '<span class="label label-danger"> Rejected</span>';
									}else if($o_row['status'] == 3){
										echo '<span class="label label-danger"> Cancelled</span>';
									}
								?>
								</td>
								
										</tr>

									<?php } } ?>
									</tbody>
								</table>
							</div>





					
                </div>



                



<?php 
  $user_id = $this->session->userdata('user_id'); 
  date_default_timezone_set('Asia/Kolkata');
  $punch_in_date = date('Y-m-d');
  $punch_in_time = date('H:i');
  $punch_in_date_time = date('Y-m-d H:i');


   $strtotime = strtotime($punch_in_date_time);
   $a_year    = date('Y',$strtotime);
   $a_month   = date('m',$strtotime);
   $a_day     = date('d',$strtotime);
   $a_days     = date('d',$strtotime);
   $a_dayss     = date('d',$strtotime);
   $a_cin     = date('H:i',$strtotime);
   $where     = array('user_id'=>$user_id,'a_month'=>$a_month,'a_year'=>$a_year);
   $this->db->select('month_days,month_days_in_out');
   $record  = $this->db->get_where('dgt_attendance_details',$where)->row_array();

 
   $punchin_id = 1;
   if(!empty($record['month_days'])){
     
    
      $month_days =  unserialize($record['month_days']);
      $month_days_in_out =  unserialize($record['month_days_in_out']);
     
     $a_day -=1;

     if(!empty($month_days[$a_day])  && !empty($month_days_in_out[$a_day])){  

      $day = $month_days[$a_day];
      $day_in_out = $month_days_in_out[$a_day];


      $latest_inout = end($day_in_out);

    
        if($day['day'] == '' || !empty($latest_inout['punch_out']) || empty($latest_inout['punch_in'])){ 
          $punch_in = $day['punch_in'];
          $punch_in_out = $latest_inout['punch_in'];
          $punch_out_in = $latest_inout['punch_out'];
          $punchin_id = 1;
        }else{
           $punch_in = $day['punch_in'];
          $punch_in_out = $latest_inout['punch_in'];
          $punch_out_in = $latest_inout['punch_out'];
          $punchin_id = 0;
        }
     }
         
            
     

     $punchin_time = date("g:i a", strtotime($day['punch_in']));
     $punchout_time = date("g:i a", strtotime($day['punch_out']));
   }

  ?>


  <?php
        $a_dayss -=1;
        $production_hour=0;
        $break_hour=0;

         if(!empty($record['month_days_in_out'])){

         $month_days_in_outss =  unserialize($record['month_days_in_out']);

                              
          foreach ($month_days_in_outss[$a_dayss] as $punch_detailss) 
          {

              if(!empty($punch_detailss['punch_in']) && !empty($punch_detailss['punch_out']))
              {
                
                  $production_hour += time_difference(date('H:i',strtotime($punch_detailss['punch_in'])),date('H:i',strtotime($punch_detailss['punch_out'])));
              }
                        
                                          
               
          }

           for ($i=0; $i <count($month_days_in_outss[$a_dayss]) ; $i++) { 

                      if(!empty($month_days_in_outss[$a_dayss][$i]['punch_out']) && $month_days_in_outss[$a_dayss][ $i+1 ]['punch_in'])
                      {
                          
                          $break_hour += time_difference(date('H:i',strtotime($month_days_in_outss[$a_dayss][$i]['punch_out'])),date('H:i',strtotime($month_days_in_outss[$a_dayss][ $i+1 ]['punch_in'])));
                      }

                      
            }
        }
    ?>


<div class="content container-fluid">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-lg-4">
							<div class="dash-widget clearfix card-box">
								<a href="<?php echo base_url()?>all_tasks">
									<span class="dash-widget-icon"><i class="fa fa-diamond"></i></span>
									<div class="dash-widget-info m-t-15">
										<span class="dash-widget-text">Tasks</span>
									</div>
								</a>
							</div>
						</div>
						<?php /*<div class="col-md-6 col-sm-6 col-lg-3">
							<div class="dash-widget clearfix card-box">
								<a href="salary-view.html">
									<span class="dash-widget-icon"><i class="fa fa-usd" aria-hidden="true"></i></span>
									<div class="dash-widget-info m-t-15">
										<span class="dash-widget-text">Payslip</span>
									</div>
								</a>
							</div>
						</div>*/?>
						<div class="col-md-6 col-sm-6 col-lg-4">
							<div class="dash-widget clearfix card-box">
								<a href="<?php echo base_url()?>leaves">
									<span class="dash-widget-icon"><i class="fa fa-calendar"></i></span>
									<div class="dash-widget-info m-t-15">
										<span class="dash-widget-text">Leaves</span>
									</div>
								</a>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-lg-4">
							<div class="dash-widget clearfix card-box">
								<a href="<?php echo base_url()?>tickets">
									<span class="dash-widget-icon"><i class="fa fa-ticket" aria-hidden="true"></i></span>
									<div class="dash-widget-info m-t-15">
										<span class="dash-widget-text">Tickets</span>
									</div>
								</a>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-lg-5">
							<div class="panel">
								<ul class="nav nav-tabs custom-nav-tab">
									<li class="active">
										<a data-toggle="tab" href="#mark-attendance">Mark <small>Attendance</small></a>
									</li>
									<li>
										<a data-toggle="tab" href="#attendance-overview">Attendance Overview</a>
									</li>
								</ul>
								<div class="panel-body">
									<div class="tab-content p-t-0">
										<div id="mark-attendance" class="tab-pane fade in active">
											<div class="card punch-status">
							                <div class="card-body">
							                  <h5 class="card-title">Timesheet <small class="text-muted"><?php echo date('d M Y'); ?></small></h5>
							                  <?php 
							                  $user_id = $this->session->userdata('user_id');      
							                  if($punchin_id == 1){ 
							                  
							                  	?>  
							                   <form action="<?php echo base_url(); ?>collaborator/save_punch_details" method="POST" class="form-horizontal">
							                      <div class="punch-det">
							                        <h6>Check In </h6>
							                        <p><?php echo date("D M j h:i:s A");?></p>
							                      </div>
							                      <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id;?>">
							                      <input type="hidden" name="punch_in_date_time" id="punch_in_date_time" value="<?php echo $punch_in_date_time; ?>">
							                      <div class="punch-info">
							                        <div class="punch-hours">
							                          <span><?php echo intdiv($production_hour, 60).'.'. ($production_hour % 60);?> hrs</span>
							                        </div>
							                      </div>
							                      <div class="punch-btn-section">
							                        <button type="submit" class="btn btn-primary punch-btn">Check in</button>
							                      </div>
							                    </form>
							                  <?php }else{ 
							                  	 
							                  	  ?>
							                    <form action="<?php echo base_url(); ?>collaborator/save_punch_details_out" method="POST" class="form-horizontal">
							                      <div class="punch-det">
							                        <h6>Check In at</h6>
							                        <p><?php echo date('l'); ?>, <?php echo date('d M Y',strtotime($punch_in_date)); ?> <?php echo (!empty($punch_in))?date("g:i a", strtotime($punch_in)):''?></p>
							                      </div>
							                      <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
							                      <input type="hidden" name="punch_out_date_time" id="punch_out_date_time" value="<?php echo $punch_in_date_time; ?>">
							                      <div class="punch-info">
							                        <div class="punch-hours">
							                          <span><?php echo intdiv($production_hour, 60).'.'. ($production_hour % 60);?> hrs</span>
							                        </div>
							                      </div>
							                      <div class="punch-btn-section">
							                        <button type="submit" class="btn btn-primary punch-btn">Check out</button>
							                      </div>
							                    </form>
							                  <?php } ?>
							                  <div class="statistics">



							                    <div class="row">
							                      <div class="col-md-6 text-center">
							                        <div class="stats-box">
							                          <p>Worked Hours</p>
							                          <h6><?php echo intdiv($production_hour, 60).'.'. ($production_hour % 60);?>  hrs</h6>
							                        </div>
							                      </div>
							                      <div class="col-md-6 text-center">
							                        <div class="stats-box">
							                          <p>Break</p>
							                          <h6><?php echo intdiv($break_hour, 60).'.'. ($break_hour % 60);?> hrs</h6>
							                        </div>
							                      </div>
							                      <?php /*<div class="col-md-4 text-center">
							                        <div class="stats-box">
							                          <p>Overtime</p>
							                          <?php
							                           $overtimes=($production_hour+$break_hour)-(9*60);
							                          if($overtimes > 0)
							                          {
							                            $overtime=$overtimes;
							                          }
							                          else
							                          {
							                            $overtime=0;
							                          }
							                          ?>
							                          <h6><?php echo intdiv($overtime, 60).'.'. ($overtime % 60);?> hrs</h6>
							                        </div>
							                      </div> */ ?>
							                    </div>
							                  </div>
							                </div>
							              </div>
										</div>
										<div id="attendance-overview" class="tab-pane fade">
											<div class="card att-statistics">
                <div class="card-body">
                  <h5 class="card-title">Statistics</h5>
                  <div class="stats-list">
                    <div class="stats-info">

                      <?php
                            $maxTime = (8*3600);
                           
                            $today_percentage = (($production_hour*60) / $maxTime) * 100;

                      ?>

                      <p>Today <strong><?php echo intdiv($production_hour, 60).'.'. ($production_hour % 60);?> <small>/ 8 hrs</small></strong></p>
                      <div class="progress">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $today_percentage;?>%" aria-valuenow="<?php echo $today_percentage;?>" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>

                    

                    <?php
                        $week_production_hour=0;
                        $month_production_hour=0;

                         if(!empty($record['month_days_in_out'])){

                             $month_days_in_out_week =  unserialize($record['month_days_in_out']);

                              $week_start_date = date("d",strtotime('monday this week'));
                              $week_end_date=date("d",strtotime("friday this week"));

                             for ($week=$week_start_date-1; $week <= $week_end_date-1 ; $week++) { 
                                                                          
                              foreach ($month_days_in_out_week[$week] as $punch_detail_week) 
                              {

                                  if(!empty($punch_detail_week['punch_in']) && !empty($punch_detail_week['punch_out']))
                                  {
                                    
                                      $week_production_hour += time_difference(date('H:i',strtotime($punch_detail_week['punch_in'])),date('H:i',strtotime($punch_detail_week['punch_out'])));


                                  }
                              }

                             }
      
                        }
                  
                                    
                         $week_maxTime = (40*3600);
                         $week_percentage = (($week_production_hour*60) / $week_maxTime) * 100;

                         $working_hours=working_days(date('n'), date('Y'))*8;
   

                     
                      if(!empty($record['month_days_in_out'])){

                           $month_days_in_out_month =  unserialize($record['month_days_in_out']);

                             $month_start_date = date('01', strtotime(date('Y-m-d')));
                             $month_end_date=date('t', strtotime(date('Y-m-d')));

                           for ($month=$month_start_date-1; $month <= $month_end_date-1 ; $month++) { 
                                                                        
                            foreach ($month_days_in_out_month[$month] as $punch_detail_month) 
                            {

                                if(!empty($punch_detail_month['punch_in']) && !empty($punch_detail_month['punch_out']))
                                {
                                  
                                    $month_production_hour += time_difference(date('H:i',strtotime($punch_detail_month['punch_in'])),date('H:i',strtotime($punch_detail_month['punch_out'])));


                                }
                            }

                           }
      
                        }
                  
                                    
                         $month_maxTime = ($working_hours*3600);
                         $month_percentage = (($month_production_hour*60) / $month_maxTime) * 100;


                         $remaining_hour=($working_hours*60)-$month_production_hour;



                           $month_overtimes=($month_production_hour)-($working_hours*60);
                          if($month_overtimes > 0)
                          {
                            $month_overtime=$month_overtimes;
                          }
                          else
                          {
                            $month_overtime=0;
                          }

                          $overtime_percentage = (($month_overtime*60) / $month_maxTime) * 100;
                          

                        

                      ?>
                    <div class="stats-info">
                      <p>This Week <strong><?php echo intdiv($week_production_hour, 60).'.'. ($week_production_hour % 60);?> <small>/ 40 hrs</small></strong></p>
                      <div class="progress">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo $week_percentage;?>%" aria-valuenow="<?php echo $week_percentage;?>" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="stats-info">
                      <p>This Month <strong><?php echo intdiv($month_production_hour, 60).'.'. ($month_production_hour % 60);?> <small>/ <?php echo $working_hours;?> hrs</small></strong></p>
                      <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $month_percentage;?>%" aria-valuenow="<?php echo $month_percentage;?>" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="stats-info">
                      <p>Remaining Hours <strong><?php echo intdiv($remaining_hour, 60).'.'. ($remaining_hour % 60);?> <small>/ <?php echo $working_hours;?> hrs</small></strong></p>
                      <div class="progress">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $month_percentage;?>%" aria-valuenow="<?php echo $month_percentage;?>" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="stats-info">
                      <p>Overtime <strong><?php echo intdiv($month_overtime, 60).'.'. ($month_overtime % 60);?> hrs</strong></p>
                      <div class="progress">
                        <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo $overtime_percentage;?>%" aria-valuenow="<?php echo $overtime_percentage;?>" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-7">
							<div class="panel panel-table equal-panel">
								<div class="panel-heading">
									<h3 class="panel-title">My Tasks</h3>
								</div>
								<div class="panel-body">
								<div class="drop-scroll">
									<div class="table-responsive">	
										
										<table class="table table-striped custom-table m-b-0">
											<thead>
												<tr>
													<th>Task</th>
													<th>End Date </th>
													<th>Status</th>
													<th>Progress</th>
													<th class="text-right">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php 

												$user_id = $this->session->userdata('user_id');
												$all_projects = $this->db->select('*')
											         ->from('dgt_assign_projects')
											         ->where('assigned_user', $user_id)
											         ->get()->result_array();


												
												foreach ($all_projects as $key => $projects) {
												$pro_id = $projects['project_assigned'];
												
												$task_details = $this->db->select('*')
											         			->from('dgt_tasks')
											         			->where('project', $pro_id)
											         			->get()->result_array();
											    
												foreach ($task_details as $key => $task) {
												$task_percentage = (100 / 100) * $task['task_progress'];


												?>
												<tr>
													<td>
														<h2><a href="tasks.html"><?php echo $task['task_name']?></a></h2>
														<small class="block text-ellipsis">
															<span class="text-muted"><?php echo $task['description']?></span>
														</small>
													</td>
													<td><?php echo $task['end_date']?></td>
													<td>
														<div class="btn btn-white btn-sm rounded dropdown-toggle">
																<?php if($task['task_progress'] == 100) { ?>
																<i class="fa fa-dot-circle-o text-success" style="color:#55ce63;"></i> Completed
																<?php } elseif($task['task_progress'] > 0 && $task['task_progress'] < 100) { ?>
																<i class="fa fa-dot-circle-o text-success" style="color:#ff9b44;"></i> In Progress
																<?php } elseif($task['task_progress'] == 0) { ?>
															    <i class="fa fa-dot-circle-o text-success" style="color:#f62d51;"></i> Not Started
																
																<?php } ?>
															</div>
													</td>
													<td>
														<div class="progress progress-xs progress-striped">
															<div class="progress-bar bg-success" role="progressbar" data-toggle="tooltip" title="<?php echo $task_percentage?>%" style="width: <?php echo $task_percentage?>%"></div>
														</div>
													</td>
													<td class="text-right">
														<div class="dropdown">
															<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
															<ul class="dropdown-menu pull-right">
																<li><a href="<?php echo base_url()?>all_tasks/view/<?php echo $task['project']?>" title="Edit"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
																<li><a href="#" title="Delete" data-toggle="modal" onclick="delete_task(<?php echo $task['t_id'];?>)"  ><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
															</ul>
														</div>
													</td>
												</tr>
												
											 <?php } } ?>
											</tbody>
										</table>
									</div>
									</div>
								</div>
								<div class="panel-footer">
									<a href="<?php echo base_url()?>all_tasks" class="text-primary">View all My Tasks</a>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-table">
								<div class="panel-heading">
									<h3 class="panel-title">My Projects</h3>
								</div>
								<div class="panel-body">
									<div class="table-responsive">	
										<table class="table table-striped custom-table m-b-0">
											<thead>
												<tr>
													<th>Project Name</th>
													<th>End Date </th>
													<th>Status</th>
													<th>Progress</th>
												</tr>
											</thead>
											<tbody>
												<?php 
												$user_id = $this->session->userdata('user_id');
												$all_projects = $this->db->select('*')
											         ->from('dgt_assign_projects')
											         ->where('assigned_user', $user_id)
											         ->get()->result_array();
												

												foreach ($all_projects as $key => $projects) {

													 $pro = $projects['project_assigned'];
													 $project_details = $this->db->select('*')
											         			->from('dgt_projects')
											         			->where('project_id', $pro)
											         			->get()->result_array();	
												foreach ($project_details as $key => $details) {
												$task_percentage = (100 / 100) * $details['progress'];

																																
												
												?>
												<tr>
													<td>
														<h2><a href="project-view.html"><?php echo $details['project_title']?></a></h2>
														<small class="block text-ellipsis">
															<?php 
															$completed_task_count = $this->db->get_where('tasks',array('project'=>$details['project_id'],'task_progress'=>'100'))->result_array();
															$open_task_count = $this->db->get_where('tasks',array('project'=>$details['project_id'],'task_progress !='=>'100'))->result_array(); 
															?>
															<span class="text-xs"><?php echo count($open_task_count)?></span> <span class="text-muted">open tasks, </span>
															<span class="text-xs"><?php echo count($completed_task_count)?></span> <span class="text-muted">tasks completed</span>
														</small>
													</td>
													<td><?php echo date('d M Y ', strtotime($details['due_date']))?></td>
													<td>
														<div class="btn btn-white btn-sm rounded dropdown-toggle">
																<?php if($details['progress'] == 100) { ?>
																<i class="fa fa-dot-circle-o text-success" style="color:#55ce63;"></i> Completed
																<?php } elseif($details['progress'] > 0 && $task['task_progress'] < 100) { ?>
																<i class="fa fa-dot-circle-o text-success" style="color:#ff9b44;"></i> In Progress
																<?php } elseif($details['progress'] == 0) { ?>
															    <i class="fa fa-dot-circle-o text-success" style="color:#f62d51;"></i> Not Started
																
																<?php } ?>
															</div>
													</td>
												
													<td>
														<div class="progress progress-xs progress-striped">
															<div class="progress-bar bg-success" role="progressbar" data-toggle="tooltip" title="<?php echo $task_percentage?>%" style="width: <?php echo $task_percentage?>%"></div>
														</div>
													</td>
												</tr>
												
												
												<?php } } ?>
												
											</tbody>
										</table>
									</div>
								</div>
								<div class="panel-footer">
									<a href="<?php echo base_url()?>projects" class="text-primary">View all projects</a>
								</div>
							</div>
						</div>
					</div>
					
					
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-table">
								<div class="panel-heading">
									<h3 class="panel-title">My Goals</h3>
								</div>
								<div class="panel-body">
									<div class="table-responsive">	
										<table class="table table-striped custom-table m-b-0">
											<thead>
												<tr>
													<th>Title</th>
													<th>Description </th>
													<th>Status</th>
													<th>Expected Completion Date</th>
												</tr>
											</thead>
											<tbody>
												<?php foreach($goals as $key => $goal) {	?>
												<tr>
													<td>
														<h2><a target="_blank" href="http://www.trainotel.com/career/goals"><?php echo $goal['title']?></a></h2>
													</td>
													<td><?=substr_replace($goal['description'], "...", 80);?></td>
													<td><?=$goal['status'];?></td>
													<td><?php echo $goal['expected_completion_date'];?></td>
												</tr>
												
												
												<?php } ?>
												
											</tbody>
										</table>
									</div>
								</div>
								<div class="panel-footer">
									<a target="_blank" href="http://www.trainotel.com/career/goals" class="text-primary">View all Goals</a>
								</div>
							</div>
						</div>
					</div>

            <div class="row">
            <div class="col-md-6">
              <div class="panel panel-table">
                <div class="panel-heading">
                  <h3 class="panel-title">Wiki</h3>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <?php 
                    $Wiki = $this->db->select("*")
                           ->from('dgt_wiki')
                           ->where('branch_id',$this->session->userdata('branch_id'))
                           ->order_by('id',desc)
                           ->limit(7)
                           ->get()->result_array();

                          
                    ?>
                    <table class="table table-striped custom-table m-b-0">
                      <thead>
                        <tr>                          
                          <th class="col-md-3"><b>S.No</b></th>
                          <th class="col-md-3"><b>Title</b></th>
                          <th class="col-md-3"><b>Description</b></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php $row =1; 
                        foreach($Wiki as $Wiki_details) { ?>
                        
                        <tr>
                          <td><?php echo $row; ?></td>
                          <td><?php echo $Wiki_details['title']; ?></td>
                          <td><?php echo wordwrap($Wiki_details['description'],60,"<br>\n"); ?></td>
                        </tr>
                        <?php $row++; } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="panel-footer">
                  <a href="<?php echo base_url()?>wiki" class="text-primary">View all Wiki</a>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="panel panel-table">
                <div class="panel-heading">
                  <h3 class="panel-title">Notice Board</h3>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table class="table table-striped custom-table m-b-0">
                      <thead>
                        <tr>
                          <th class="col-md-3"><b>Title</b></th>
                          <th class="col-md-3"><b>Description</b></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $this->db->limit(7);
                        $this->db->order_by('id',asc);
                        $notice_boards = $this->db->get('notice_board')->result_array(); 
                        foreach($notice_boards as $notice_board){
                        ?>
                        <tr>
                          <td><?php echo $notice_board['title']?></td>
                          <td><?php echo $notice_board['description']?></td>
                          
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="panel-footer">
                  <a href="<?php echo base_url()?>notice_board" class="text-primary">View all Notice Boards</a>
                </div>
              </div>
            </div>
          </div>
					<div class="row">
						<div class="col-sm-12">
							<div class="panel">
								<div class="panel-heading">
									<h3 class="panel-title">My Calender</h3>
								</div>
								<div class="panel-body">
									<div id="calendar"></div>
								</div>
							</div>
						</div>
					</div>
					<!-- <div class="themes">
						<div class="themes-icon"><i class="fa fa-cog"></i></div>
						<div class="themes-body">
							<ul id="theme-change" class="theme-colors">
								<li><a href="../orange/index.html"><span class="theme-orange"></span></a></li>
								<li><a href="../purple/index.html"><span class="theme-purple"></span></a></li> 
								<li><a href="../blue/index.html"><span class="theme-blue"></span></a></li>
								<li><a href="../maroon/index.html"><span class="theme-maroon"></span></a></li>
								<li><a href="../light/index.html"><span class="theme-light"></span></a></li> 
								<li><a href="../dark/index.html"><span class="theme-dark"></span></a></li> 
								<li><a href="../rtl/index.html"><span class="theme-rtl">RTL</span></a></li>
							</ul>
						</div>
					</div> -->
				</div>
				<div class="notification-box">
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
			

			<!---- Modal -->
			<div  class="modal custom-modal fade" role="dialog" id="delete_modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
				<form id="assigned_task" method="post" action="<?php echo base_url();?>all_tasks/delete_task">
					<div class="form-head">
						<h3><?=lang('delete_lead')?></h3>
						<p>Are you sure want to delete?</p>
					</div>
					<input type="text" name="delete_project" id="project" value="<?=$project_id['project_id']?>">
					<input type="text" name="delete_task" id="delete_task_id" >
					<div class="modal-btn delete-action">
						<div class="row">
							<div class="col-xs-6">
								<button type="submit" class="btn continue-btn">Delete</button>
							</div>
							<div class="col-xs-6">
								<a href="javascript:void(0);" data-dismiss="modal" class="btn cancel-btn">Cancel</a>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
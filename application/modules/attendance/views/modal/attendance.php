  <!-- Attendance Modal -->
				
					<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Attendance Info</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-md-6">
										<div class="card punch-status">
											<div class="card-body">
												<h5 class="card-title">Timesheet <small class="text-muted"><?php echo date('d M Y',strtotime($atten_year.'-'.$atten_month.'-'.$atten_day));?></small></h5>


												<?php

												   $a_day     = $atten_day;
												   $a_days     = $atten_day;
												   $a_dayss     = $atten_day;




												if(!empty($record['month_days'])){
     
    												  $month_days =  unserialize($record['month_days']);
												      $month_days_in_out =  unserialize($record['month_days_in_out']);
												     /*echo "<pre>";
												     print_r($month_days);
												     print_r($month_days_in_out);
												     exit;*/
												      $a_day -=1;

												     if(!empty($month_days[$a_day])  && !empty($month_days_in_out[$a_day])){  

												      $day = $month_days[$a_day];
												      $day_in_out = $month_days_in_out[$a_day];

												      $latest_inout = end($day_in_out);

												    
												        if($day['day'] == '' || !empty($latest_inout['punch_out'])){ 
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

											        if(sizeof($month_days_in_outss[$a_dayss]) > 1)
													{	
														$tmp_checkin_count = 0;
														$tmp_checkin_count = sizeof($month_days_in_outss[$a_dayss]);
														$tmp_checkin = $month_days_in_outss[$a_dayss][$tmp_checkin_count-1]['punch_in'];
														$tmp_checkout = $month_days_in_outss[$a_dayss][$tmp_checkin_count-1]['punch_out'];

														$month_days_in_outss[$a_dayss] = array();
														$month_days_in_outss[$a_dayss][0]['day'] = '1'; 
														$month_days_in_outss[$a_dayss][0]['punch_in'] = $tmp_checkin;
														$month_days_in_outss[$a_dayss][0]['punch_out'] = $tmp_checkout;
													}


											                              
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

												<div class="punch-det">
													<h6>Check In at</h6>
													<?php
													if(!empty($punch_in))
													{
														echo'<p>'.date('l',strtotime($atten_year.'-'.$atten_month.'-'.$atten_day)).', '.date('d M Y',strtotime($atten_year.'-'.$atten_month.'-'.$atten_day)).' '. date("g:i a", strtotime($punch_in)).'</p>';
													}
													?>
												</div>
												<div class="punch-info">
													<div class="punch-hours">
														<span><?php echo intdiv($production_hour, 60).'.'. ($production_hour % 60);?> hrs</span>
													</div>
												</div>
												<div class="punch-det">
													<h6>Check Out at</h6>
													<?php
													if(!empty($punch_out_in))
													{
														echo'<p>'.date('l',strtotime($atten_year.'-'.$atten_month.'-'.$atten_day)).', '.date('d M Y',strtotime($atten_year.'-'.$atten_month.'-'.$atten_day)).' '.date("g:i a", strtotime($punch_out_in)).'</p>';
													}
													?>
													
												</div>
												<?php if(!empty($reason)){?>	
													<div class="punch-det">
														<h6>Reason</h6>
														<?php 
														if($reason==1)
														{
															$reas='Forgot to Check In';
														}
														if($reason==2)
														{
															$reas='Forgot to Check Out';
														}
														if($reason==3)
														{
															$reas='Network Issues';
														}
														if($reason==4)
														{
															$reas='On Duty';
														}
														if($reason==5)
														{
															$reas='Permission';
														}
														
														?>
														<?php
														if(!empty($reason))
														{
															echo $reas;
														}
														?>
														
													</div>
												<?php }?>		
												
												<div class="statistics">
													<div class="row">
														<div class="col-md-6 text-center">
															<div class="stats-box">
																<p>Production</p>
																<h6><?php echo intdiv($production_hour, 60).'.'. ($production_hour % 60);?> hrs</h6>
															</div>
														</div>
														<div class="col-md-6 text-center">
															<div class="stats-box">
																<p>Break</p>
																<h6><?php echo intdiv($break_hour, 60).'.'. ($break_hour % 60);?> hrs</h6>
															</div>
														</div>
														
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="card recent-activity">
											<div class="card-body">
												<h5 class="card-title">Activity</h5>
												<ul class="res-activity-list">
													<?php
								                    $a_days -=1;

								                     if(!empty($record['month_days_in_out'])){

								                     $month_days_in_outs =  unserialize($record['month_days_in_out']);

								                    if(sizeof($month_days_in_outs[$a_dayss]) > 1)
													{	
														$tmp_checkin_count = 0;
														$tmp_checkin_count = sizeof($month_days_in_outs[$a_dayss]);
														$tmp_checkin = $month_days_in_outs[$a_dayss][$tmp_checkin_count-1]['punch_in'];
														$tmp_checkout = $month_days_in_outs[$a_dayss][$tmp_checkin_count-1]['punch_out'];

														$month_days_in_outs[$a_dayss] = array();
														$month_days_in_outs[$a_dayss][0]['day'] = '1'; 
														$month_days_in_outs[$a_dayss][0]['punch_in'] = $tmp_checkin;
														$month_days_in_outs[$a_dayss][0]['punch_out'] = $tmp_checkout;
													}

								                                          
								                      foreach ($month_days_in_outs[$a_days] as $punch_details) 
								                      {

								                        if(!empty($punch_details['punch_in']))
								                        {
								                          echo'<li>
								                                <p class="mb-0">Check In at</p>
								                                <p class="res-activity-time">
								                                  <i class="fa fa-clock-o"></i>
								                                  '.date("g:i a", strtotime($punch_details['punch_in'])).'
								                                </p>
								                              </li>';
								                        }
								                        if(!empty($punch_details['punch_out']))
								                        {
								                           echo'<li>
								                                <p class="mb-0">Check Out at</p>
								                                <p class="res-activity-time">
								                                  <i class="fa fa-clock-o"></i>
								                                   '.date("g:i a", strtotime($punch_details['punch_out'])).'
								                                </p>
								                              </li>';
								                        }


								                      }

								                    }

								                  
								                     ?>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
			
				<!-- /Attendance Modal -->
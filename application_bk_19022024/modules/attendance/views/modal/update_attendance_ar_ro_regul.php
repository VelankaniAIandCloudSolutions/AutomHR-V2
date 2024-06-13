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


 // echo '<pre>';print_r($record);exit;

												if(!empty($record['month_days'])){
     
    												  $month_days =  unserialize($record['month_days']);
												      $month_days_in_out =  unserialize($record['month_days_in_out']);
												      $a_day -=1;
// echo '<pre>';print_r($month_days);exit;
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
													 $reason=$record['reason'];
                                                     }
// 	echo '<pre>';print_r($punchin_time);exit;

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

												
												<div class="statistics">
													<div class="row">
														<div class="col-md-6 text-center">
															<div class="stats-box">
																<p>Worked Hours</p>
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
												<h5 class="card-title">Update Attendance</h5>
												<form method="post" action="<?php echo base_url();?>attendance/update_attendance_ar_ro_regul/<?php echo $user_id;?>/<?php echo $atten_day;?>/<?php echo $atten_month;?>/<?php echo $atten_year;?>">
												<div class="form-group">
												<label>Check In Time</label>
												<input type="time" class="form-control" value="<?php echo $punch_in;?>" name="punch_in_time" required>
												</div>
												<div class="form-group">
												<label>Check Out Time</label>
												<input type="time" class="form-control" value="<?php echo $punch_out_in;?>" name="punch_out_time" required>
												</div>
												<div class="form-group">
												<label>Reason</label>
												
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
												<input readonly type="text" class="form-control" value="<?php echo $reas;?>" >
												<input readonly type="hidden" name="reason" class="form-control" value="<?php echo $reason;?>" >
												</div>
												<div class="form-group">
												<label>Status</label>
												<select required onchange="rejectreasonenble();" class="select   form-control" id="ro_status" name="ro_status"> 
													<option value=""  selected="selected" disabled>Select Status</option>
													<option <?php if($record['ro_status']==1){echo 'selected';} ?> value="1" >Approved</option>
													<option <?php if($record['ro_status']==2){echo 'selected';} ?> value="2" >Rejected</option>
													
												</select>
												</div>
												<?php
												if($record['ro_status']==2)
												{
													$dis='';
												}
												else
												{
													$dis='display:none;';
												}
												?>
												<div id="rejectreason_div" style="<?=$dis?>" class="form-group">
												<label>Reject Reason</label>
												<textarea class="form-control" id="reject_reason" name="reject_reason" ><?=$record['reject_reason']?></textarea>
												</div>
												
												<div class="form-group">
												
												<input type="submit" class="btn btn-success" name="save" value="Save">
												</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
			
				<!-- /Attendance Modal -->
				
				
				<script>
				function rejectreasonenble()
				{
					var ro_status=$("#ro_status").val();
					if(ro_status==2)
					{
						$("#rejectreason_div").css('display','block');
					}
					else
					{
						$("#reject_reason").val('');
						$("#rejectreason_div").css('display','none');
					}
				}
				</script>
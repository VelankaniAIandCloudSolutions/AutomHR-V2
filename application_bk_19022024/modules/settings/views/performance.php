

	<?php if(isset($_GET['key']) && !empty($_GET['key']))
	{

    $active_performance = $_GET['key'];

} else {
  $active_performance = 'okr';
}
 // echo $active_performance; exit();
?>
	<?php $performance_status = $this->db->get('performance_status')->row_array(); ?>
		
                <div class="content">
					<div class="row">
						<div class="col-sm-12">
							<h4 class="page-title">Performance Configuration 							<?php if($performance_status['okr'] ==1 ) {?>
								<button class="btn btn-success m-t-5" type="submit">OKRs  Activated</button>
						<?php 	} else if($performance_status['competency'] ==1){?>
							<button class="btn btn-success m-t-5" type="submit">Competency Activated</button>
						<?php } else { ?>
							<button class="btn btn-success m-t-5" type="submit">SMART Goals Activated</button>
						<?php }?></h4> 

						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card-box">
								<p><b>Click the tabs below for more information on each Performance Management module.
Only one Performance module can be activated at a time.
</b></p>
								<ul class="nav nav-tabs nav-tabs-bottom">
									<li class="<?php echo ('okr' == $active_performance )?'active':'';?>"><a href="#okr_tab" data-toggle="tab">OKRs</a></li>									
									<li class="<?php echo ('competency' == $active_performance )?'active':'';?>"><a href="#compentency_tab" data-toggle="tab">Competency-based</a></li>
									<!-- <li><a href="#kpi_tab" data-toggle="tab">KPI</a></li> -->
									<li class="<?php echo ('smart_goals' == $active_performance )?'active':'';?>"><a href="#smart_goals_tab" data-toggle="tab">SMART Goals</a></li>
									<!-- <li><select class="form-control performance_status" required="required">
										<option value="">Please Select Performance</option>
										<option value="okr" <?php echo ($performance_status['okr'] == 1)?"selected":""?>>OKRs</option>
										<option value="competency" <?php echo ($performance_status['competency'] == 1)?"selected":""?>>Competency-based</option>
										<option value="smart_goals" <?php echo ($performance_status['smart_goals'] == 1)?"selected":""?>>SMART Goals</option>
									</select></li> -->
								</ul>

								<div class="tab-content">
								
									<!-- OKR Config -->
									<div class="tab-pane <?php echo ('okr' == $active_performance )?'active':'';?>" id="okr_tab">
										<div class="row">
											<div class="col-md-12 col-lg-8">
												<!-- <form id="okr_description" action="<?=base_url()?>settings/okr_description" method="POST"> -->
													<div class="form-group">
														<label>OKRs Description</label>
														<textarea rows="4" cols="5" class="form-control" name="description"><?php echo $okr_description['description'];?></textarea>
													</div>
											 	
											</div>
											<div class="col-md-12 col-lg-12">
													<div class="rating-list m-t-20">
													<span class="rating-bad">
														<span class="rating-count">
														<a href="">0.0</a>
														<a href="">0.1</a>
														<a href="">0.2</a>
														<a href="">0.3</a>
														</span><br>
														<span class="rating-text">We failed to make real progress</span>
													</span>
													<span class="rating-normal">
														<span class="rating-count">
														<a href="">0.4</a>
														<a href="">0.5</a>
														<a href="">0.6</a>
														</span><br>
														<span class="rating-text">We made progress, but fell short of completion</span>
													</span>
													<span class="rating-good">
														<span class="rating-count">
														<a href="">0.7</a>
														<a href="">0.8</a>
														<a href="">0.9</a>
														<a href="">1.0</a>
														</span><br>
														<span class="rating-text">We delivered</span>
													</span>
												</div>
											</div>
											<div class="col-md-12 col-lg-8">
													<div class="submit-section">
														<button class="btn btn-primary submit-btn performance_status <?php echo ($performance_status['okr'] ==1)?"red_circle":"";?>" data-status="okr" <?php echo ($performance_status['okr'] == 1)?'disabled':'';?> title="<?php echo ($performance_status['okr'] ==1)?"Admin can’t activate it again":"";?>">Activate OKR</button>
													</div>
												</div>
											<div class="col-md-12 col-lg-8">
												<hr>
											<div class="form-group">
												<label>Choose Your Rating Scale</label>
												<div class="radio_input" id="rating_scale_select_okr">
													<label class="radio-inline custom_radio">
														<input type="radio" name="rating_scale" value="rating_01_010" required class="rating_scale">0.1 - 1.0 <span class="checkmark"></span>
													</label>
													<label class="radio-inline custom_radio">
														<input type="radio" name="rating_scale" value="rating_1_5" required class="rating_scale">1 - 5 <span class="checkmark"></span>
													</label>
													<label class="radio-inline custom_radio">
														<input type="radio" name="rating_scale" value="rating_1_10" class="rating_scale">1 - 10 <span class="checkmark"></span>
													</label>
													<label class="radio-inline custom_radio">
														<input type="radio" name="rating_scale" value="custom_rating" class="rating_scale">Custom <span class="checkmark"></span>
													</label> 
												</div>
											</div>


											<!--0.1 to  1.0 Ratings Content -->
											<?php $zero_one_ratings = $this->db->get_where('okr_ratings',array('rating_scale'=>'rating_01_010'))->row_array() ;
        											    // echo print_r($five_ratings); exit;
        											    ?>
											<div class="form-group" id="01ratings_cont_okr" style="display: none;">
												<label>Rating Scale Definition</label>
													<div class="table-responsive">
														<form id="create_goal_configuration" action="<?=base_url()?>settings/create_okr_ratings" method="POST">
														<!-- <textarea rows="4" cols="5" class="form-control" name="description"><?php echo (isset($ten_ratings) && !empty($ten_ratings))?$ten_ratings['description'] :" ";?></textarea> -->

													<table class="table">
														<tbody>
															<?php

															if(isset($zero_one_ratings) && !empty($zero_one_ratings)){ 
															$rating_no = explode('|',$zero_one_ratings['rating_no']);	
															$rating_value = explode('|',$zero_one_ratings['rating_value']);
															$definition = explode('|',$zero_one_ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_value[$i])){
															  ?>
																<tr>
																<td> <?php echo $a++; ?> </td>
																<td>
																	<input type="hidden" name="rating_no[]" class="form-control" value="<?php echo $rating_no[$i];?>">
																	<input type="text" name="rating_value_ten[]" class="form-control" value="<?php echo $rating_value[$i];?>" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required><?php echo $definition[$i];?></textarea>
																</td>
															</tr>
																
															<?php }  } ?>
															<input type="hidden" class="five_rating_scale" name ="rating_scale" value="<?php echo $zero_one_ratings['rating_scale'];?>"> 

														<?php } else {
															?>
															<input type="hidden" class="five_rating_scale" name="rating_scale" class="form-control rating_scale">
															<tr>

																<td> 0.1 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.1">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.2 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.2">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.2" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.3 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.3">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.3" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.4 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.4">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.4" required>
																</td>
																<td>

																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.5 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.5">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.5" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.6 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.6">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.6" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.7 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.7">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.7" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.8 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.8">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.8" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.9 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.9">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.9" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 1.0 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="1.0">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 1.0" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>

														<?php } ?>
														</tbody>
													</table>
													<div class="submit-section">
												<button class="btn btn-primary submit-btn create_goal_configuration_submit" type="submit">Save</button>
											</div>
											</form>
												</div>
											</div>
											<!-- 0.1 to  1.0  Ratings Content -->



											<!-- 5 Ratings Content -->

											<?php $five_ratings = $this->db->get_where('okr_ratings',array('rating_scale'=>'rating_1_5'))->row_array() ;
        											    // echo print_r($five_ratings); exit;
        											    ?>
											<div class="form-group" id="5ratings_cont_okr" style="display: none;">
												<label>Rating Scale Definition</label>
													<div class="table-responsive">
														<form id="" action="<?=base_url()?>settings/create_okr_ratings" method="POST">
															<!-- <textarea rows="4" cols="5" class="form-control" name="description"><?php echo (isset($five_ratings) && !empty($five_ratings))?$five_ratings['description'] :" ";?></textarea> -->
													<table class="table">
														<tbody>
															<?php

															if(isset($five_ratings) && !empty($five_ratings)){ 
															$rating_no = explode('|',$five_ratings['rating_no']);
															$rating_value = explode('|',$five_ratings['rating_value']);
															$definition = explode('|',$five_ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																// if(!empty($rating_value[$i])){
															  ?>
																<tr>
																<td> <?php echo $a++; ?> </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="<?php echo $rating_no[$i];?>">
																	<input type="text" name="rating_value[]" class="form-control" value="<?php echo $rating_value[$i];?>" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required><?php echo $definition[$i];?></textarea>
																</td>

															</tr>
																
															<?php 
															  } ?>
															<input type="hidden" name ="rating_scale" class="five_rating_scale" value="<?php echo $five_ratings['rating_scale'];?>">
														<?php } else {
															?>
															
															<tr>
																<td> 1 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="1">
																	<input type="text" name="rating_value[]" class="form-control" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 2 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="2">
																	<input type="text" name="rating_value[]" class="form-control" placeholder="Short word to describe rating of 2" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 3 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="3">
																	<input type="text" name="rating_value[]" class="form-control" placeholder="Short word to describe rating of 3" required>
																</td>
																<td>
																	<textarea rows="3"  name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 4 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="4">
																	<input type="text" name="rating_value[]" class="form-control" placeholder="Short word to describe rating of 4" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 5 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="5">
																	<input type="text" name="rating_value[]" class="form-control" placeholder="Short word to describe rating of 5" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<input type="hidden" class="five_rating_scale" name="rating_scale" class="form-control rating_scale">
														
														<?php } ?>
														</tbody>
													</table>
													<div class="submit-section">
												<button class="btn btn-primary submit-btn create_goal_configuration_submit" type="submit">Save</button>
											</div>
											</form>
												</div>
											</div>
											<!-- /5 Ratings Content -->
											
											<!-- 10 Ratings Content -->
											<?php $ten_ratings = $this->db->get_where('okr_ratings',array('rating_scale'=>'rating_1_10'))->row_array() ;
        											    // echo print_r($five_ratings); exit;
        											    ?>
											<div class="form-group" id="10ratings_cont_okr" style="display: none;">
												<label>Rating Scale Definition</label>
													<div class="table-responsive">
														<form id="create_goal_configuration" action="<?=base_url()?>settings/create_okr_ratings" method="POST">
														<!-- <textarea rows="4" cols="5" class="form-control" name="description"><?php echo (isset($ten_ratings) && !empty($ten_ratings))?$ten_ratings['description'] :" ";?></textarea> -->

													<table class="table">
														<tbody>
															<?php

															if(isset($ten_ratings) && !empty($ten_ratings)){ 
															$rating_no = explode('|',$ten_ratings['rating_no']);	
															$rating_value = explode('|',$ten_ratings['rating_value']);
															$definition = explode('|',$ten_ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_value[$i])){
															  ?>
																<tr>
																<td> <?php echo $a++; ?> </td>
																<td>
																	<input type="hidden" name="rating_no[]" class="form-control" value="<?php echo $rating_no[$i];?>">
																	<input type="text" name="rating_value_ten[]" class="form-control" value="<?php echo $rating_value[$i];?>" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required><?php echo $definition[$i];?></textarea>
																</td>
															</tr>
																
															<?php }  } ?>
															<input type="hidden" class="five_rating_scale" name ="rating_scale" value="<?php echo $ten_ratings['rating_scale'];?>"> 

														<?php } else {
															?>
															<input type="hidden" class="five_rating_scale" name="rating_scale" class="form-control rating_scale">
															<tr>

																<td> 1 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="1">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 2 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="2">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 2" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 3 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="3">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 3" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 4 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="4">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 4" required>
																</td>
																<td>

																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 5 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="5">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 5" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 6 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="6">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 6" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 7 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="7">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 7" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 8 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="8">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 8" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 9 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="9">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 9" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 10 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="10">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 10" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>

														<?php } ?>
														</tbody>
													</table>
													<div class="submit-section">
												<button class="btn btn-primary submit-btn create_goal_configuration_submit" type="submit">Save</button>
											</div>
											</form>
												</div>
											</div>
											<!-- 10 Ratings Content -->
											
											<!-- Custom Ratings Content -->
											<?php $custom_rating = $this->db->get_where('okr_ratings',array('rating_scale'=>'custom_rating'))->row_array() ;
        											    // echo print_r($five_ratings); exit;
        											    ?>
											<div class="form-group" id="custom_rating_cont_okr" style="display: none;">
												<label>Custom Rating Count</label>
												<div class="form-group">
													<input type="text" class="form-control" id="custom_rating_input" name="custom_rating_count" value="" placeholder="20" style="width: 160px;">
												</div>
												<label>Rating Scale Definition</label>
												<div class="table-responsive">
													<form id="create_goal_configuration" action="<?=base_url()?>settings/create_okr_ratings" method="POST">
														<!-- <textarea rows="4" cols="5" class="form-control" name="description"><?php echo (isset($custom_rating) && !empty($custom_rating))?$custom_rating['description'] :" ";?></textarea> -->
														<input type="hidden" class="five_rating_scale" name="rating_scale" class="form-control rating_scale">
													<table class="table">
														<tbody class="custom-value">
															<?php if(isset($custom_rating) && !empty($custom_rating)){ 
															$rating_no = explode('|',$custom_rating['rating_no']);
															$rating_value = explode('|',$custom_rating['rating_value']);
															$definition = explode('|',$custom_rating['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_no[$i])){
															  ?>
																<tr>
																<td> <?php echo $a++; ?> </td>
																<td>
																	<input type="hidden" name="rating_no[]" class="form-control" value="<?php echo $rating_no[$i];?>">
																	<input type="text" name="rating_value_custom[]" class="form-control" value="<?php echo $rating_value[$i];?>" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_custom[]" class="form-control" placeholder="Descriptive Rating Definition" required><?php echo $definition[$i];?></textarea>
																</td>
																
															</tr>
																
															<?php }  }?>
															<input type="hidden" class="five_rating_scale" name ="rating_scale" value="<?php echo $custom_rating['rating_scale'];?>">
															<?php 
															 } 
															?>
														</tbody>
													</table>
													<div class="submit-section">
												<button class="btn btn-primary submit-btn create_goal_configuration_submit" type="submit">Save</button>
											</div>
											</form>
												</div>
											</div>
											<!-- /Custom Ratings Content -->
											</div>

												<!-- </form> -->
										</div>
									</div>

									<!-- Compentency Config -->
									<div class="tab-pane <?php echo ('competency' == $active_performance )?'active':'';?>" id="compentency_tab">
										<div class="row">
											<div class="col-md-6">
												<!-- <form id="compentency_tab" action="<?php echo base_url()?>settings/insert_competencies" method="post"> -->
													<div class="form-group">
														<label>Competency-based</label>
														<textarea rows="4" cols="5" class="form-control" name="competencies_desc"><?php echo $competencies_desc['description'];?></textarea>
													</div>

													<div class="submit-section">														
														<button style="margin: 23px 0px;" class="btn btn-primary submit-btn performance_status <?php echo ($performance_status['competency'] ==1)?"red_circle":"";?>" data-status="competency" <?php echo ($performance_status['competency'] ==1)?"disabled":"";?> title="<?php echo ($performance_status['competency'] ==1)?"Admin can’t activate it again":"";?>">Activate Competency-based</button>
													</div>
												<!-- </form> -->
											</div>

											<?php $performance_competency = $this->db->order_by('competency','ASC')->get('performance_competency')->result_array();
	  ?>									<div class="col-md-12" >
											<div class="form-group" >	

												
													<table class="table table-bordered table-center">		

													<thead style="background:#f2f2f2">
														<th>Competency</th>
														<th>Definition</th>
														<th style="width: 70px;">Action</th>
													</thead>										
													
														<tbody>
															<?php if(! empty($performance_competency)){ 

																foreach ($performance_competency as $performance_competencies) {?>
																<tr>	
																	<th>
																		<?php echo $performance_competencies['competency'];?>
																	</th>
																	<td>
																	 <!-- <textarea rows="4" cols="5" class="form-control" name="definition" placeholder="Definition" readonly> <?php echo $performance_competencies['definition'];?></textarea> -->
																	 <div class="task-textarea">
																		<textarea placeholder="Definition" id="competency_definition_<?php echo $performance_competencies['id'];?>" onkeyup="competency_definition(<?php echo $performance_competencies['id'];?>)" class="form-control definition_edit_<?php echo $performance_competencies['id'];?>" readonly><?php echo $performance_competencies['definition'];?></textarea>
																	</div>
																	</td>
																	<td>
																		<a href="javascript:void(0);" class="text-warning" title="Edit" id="definition_edit_<?php echo $performance_competencies['id'];?>" onclick="definition_edit(<?php echo $performance_competencies['id'];?>)"><i class="fa fa-pencil"></i></a>
																		<a href="<?=base_url()?>settings/delete_performance_competency/<?php echo $performance_competencies['id']; ?>" class="text-danger" title="Delete" data-toggle="ajaxModal"><i class="fa fa-times"></i></a>
																	</td>																	
																	
																</tr>

															<?php }  } else { ?> 
																<tr>
																	<td colspan="3"> No Competencies Found</td>
																</tr>

															<?php }?>
															
														</tbody>
													</table>
											</div>

											<!-- <div class="add-another-goal">
												<a href="javascript:void(0);" class="add_competency_performance"><i class="fa fa-plus"></i> Add New Competency</a>												
											</div> -->

											<div class="form-group" >	

												<form class="form-horizontal" action="<?=base_url()?>settings/create_performance_competency" method="POST">

													<table class="table performance-table">												
													
														<tbody>
															<tr>
																<td style="padding-left: 0;">
																	<input type="text" class="form-control" name="competency[]" required="" placeholder="Competency">
																	<!-- <input type="hidden" class="form-control" name="teamlead_id[]" value="<?php echo $employee_details['teamlead_id']?>" id="teamlead_id"> -->
																</td>
																<td>
																 <textarea style="height: 44px;" rows="4" cols="20" class="form-control" name="definition[]" placeholder="Definition" required=""></textarea>
																</td>
																
																<td style="padding-right: 0;">
																	<button type="button" class="btn btn-white add_competency_performance" data-toggle="tooltip" data-original-title="Add Competency"><i class="fa fa-plus-circle"></i></button>
																</td>
															</tr>
														</tbody>
													</table>
											
													<div class="m-t-30 text-center">
														<button class="btn btn-primary" type="submit" id="create_offers_submit">Create Competencies</button>
													</div>
												</form>
											</div>
											</div>

											<div class="col-md-12 col-lg-8">
												<hr>
											<div class="form-group">
												<label>Choose Your Rating Scale</label>
												<div class="radio_input" id="rating_scale_select_competency">
													<label class="radio-inline custom_radio">
														<input type="radio" name="rating_scale" value="rating_01_010" required class="rating_scale">0.1 - 1.0 <span class="checkmark"></span>
													</label>
													<label class="radio-inline custom_radio">
														<input type="radio" name="rating_scale" value="rating_1_5" required class="rating_scale">1 - 5 <span class="checkmark"></span>
													</label>
													<label class="radio-inline custom_radio">
														<input type="radio" name="rating_scale" value="rating_1_10" class="rating_scale">1 - 10 <span class="checkmark"></span>
													</label>
													<label class="radio-inline custom_radio">
														<input type="radio" name="rating_scale" value="custom_rating" class="rating_scale">Custom <span class="checkmark"></span>
													</label> 
												</div>
											</div>


											






											<!--0.1 to  1.0 Ratings Content -->
											<?php $zero_one_ratings = $this->db->get_where('competency_ratings',array('rating_scale'=>'rating_01_010'))->row_array() ;
        											    // echo print_r($five_ratings); exit;
        											    ?>
											<div class="form-group" id="01ratings_cont_competency" style="display: none;">
												<label>Rating Scale Definition</label>
													<div class="table-responsive">
														<form id="create_goal_configuration" action="<?=base_url()?>settings/create_competency_ratings" method="POST">
														<!-- <textarea rows="4" cols="5" class="form-control" name="description"><?php echo (isset($ten_ratings) && !empty($ten_ratings))?$ten_ratings['description'] :" ";?></textarea> -->

													<table class="table">
														<tbody>
															<?php

															if(isset($zero_one_ratings) && !empty($zero_one_ratings)){ 
															$rating_no = explode('|',$zero_one_ratings['rating_no']);	
															$rating_value = explode('|',$zero_one_ratings['rating_value']);
															$definition = explode('|',$zero_one_ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_value[$i])){
															  ?>
																<tr>
																<td> <?php echo $a++; ?> </td>
																<td>
																	<input type="hidden" name="rating_no[]" class="form-control" value="<?php echo $rating_no[$i];?>">
																	<input type="text" name="rating_value_ten[]" class="form-control" value="<?php echo $rating_value[$i];?>" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required><?php echo $definition[$i];?></textarea>
																</td>
															</tr>
																
															<?php }  } ?>
															<input type="hidden" class="five_rating_scale" name ="rating_scale" value="<?php echo $zero_one_ratings['rating_scale'];?>"> 

														<?php } else {
															?>
															<input type="hidden" class="five_rating_scale" name="rating_scale" class="form-control rating_scale">
															<tr>

																<td> 0.1 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.1">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.2 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.2">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.2" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.3 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.3">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.3" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.4 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.4">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.4" required>
																</td>
																<td>

																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.5 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.5">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.5" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.6 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.6">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.6" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.7 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.7">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.7" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.8 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.8">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.8" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.9 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.9">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.9" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 1.0 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="1.0">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 1.0" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>

														<?php } ?>
														</tbody>
													</table>
													<div class="submit-section">
												<button class="btn btn-primary submit-btn create_goal_configuration_submit" type="submit">Save</button>
											</div>
											</form>
												</div>
											</div>
											<!-- 0.1 to  1.0  Ratings Content -->


											<!-- 5 Ratings Content -->

											<?php $five_ratings = $this->db->get_where('competency_ratings',array('rating_scale'=>'rating_1_5'))->row_array() ;
        											    // echo print_r($five_ratings); exit;
        											    ?>
											<div class="form-group" id="5ratings_cont_competency" style="display: none;">
												<label>Rating Scale Definition</label>
													<div class="table-responsive">
														<form id="" action="<?=base_url()?>settings/create_competency_ratings" method="POST">
															<!-- <textarea rows="4" cols="5" class="form-control" name="description"><?php echo (isset($five_ratings) && !empty($five_ratings))?$five_ratings['description'] :" ";?></textarea> -->
													<table class="table">
														<tbody>
															<?php

															if(isset($five_ratings) && !empty($five_ratings)){ 
															$rating_no = explode('|',$five_ratings['rating_no']);
															$rating_value = explode('|',$five_ratings['rating_value']);
															$definition = explode('|',$five_ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																// if(!empty($rating_value[$i])){
															  ?>
																<tr>
																<td> <?php echo $a++; ?> </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="<?php echo $rating_no[$i];?>">
																	<input type="text" name="rating_value[]" class="form-control" value="<?php echo $rating_value[$i];?>" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required><?php echo $definition[$i];?></textarea>
																</td>

															</tr>
																
															<?php 
															  } ?>
															<input type="hidden" name ="rating_scale" class="five_rating_scale" value="<?php echo $five_ratings['rating_scale'];?>">
														<?php } else {
															?>
															
															<tr>
																<td> 1 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="1">
																	<input type="text" name="rating_value[]" class="form-control" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 2 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="2">
																	<input type="text" name="rating_value[]" class="form-control" placeholder="Short word to describe rating of 2" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 3 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="3">
																	<input type="text" name="rating_value[]" class="form-control" placeholder="Short word to describe rating of 3" required>
																</td>
																<td>
																	<textarea rows="3"  name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 4 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="4">
																	<input type="text" name="rating_value[]" class="form-control" placeholder="Short word to describe rating of 4" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 5 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="5">
																	<input type="text" name="rating_value[]" class="form-control" placeholder="Short word to describe rating of 5" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<input type="hidden" class="five_rating_scale" name="rating_scale" class="form-control rating_scale">
														
														<?php } ?>
														</tbody>
													</table>
													<div class="submit-section">
												<button class="btn btn-primary submit-btn create_goal_configuration_submit" type="submit">Save</button>
											</div>
											</form>
												</div>
											</div>
											<!-- /5 Ratings Content -->
											
											<!-- 10 Ratings Content -->
											<?php $ten_ratings = $this->db->get_where('competency_ratings',array('rating_scale'=>'rating_1_10'))->row_array() ;
        											    // echo print_r($five_ratings); exit;
        											    ?>
											<div class="form-group" id="10ratings_cont_competency" style="display: none;">
												<label>Rating Scale Definition</label>
													<div class="table-responsive">
														<form id="create_goal_configuration" action="<?=base_url()?>settings/create_competency_ratings" method="POST">
														<!-- <textarea rows="4" cols="5" class="form-control" name="description"><?php echo (isset($ten_ratings) && !empty($ten_ratings))?$ten_ratings['description'] :" ";?></textarea> -->

													<table class="table">
														<tbody>
															<?php

															if(isset($ten_ratings) && !empty($ten_ratings)){ 
															$rating_no = explode('|',$ten_ratings['rating_no']);	
															$rating_value = explode('|',$ten_ratings['rating_value']);
															$definition = explode('|',$ten_ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_value[$i])){
															  ?>
																<tr>
																<td> <?php echo $a++; ?> </td>
																<td>
																	<input type="hidden" name="rating_no[]" class="form-control" value="<?php echo $rating_no[$i];?>">
																	<input type="text" name="rating_value_ten[]" class="form-control" value="<?php echo $rating_value[$i];?>" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required><?php echo $definition[$i];?></textarea>
																</td>
															</tr>
																
															<?php }  } ?>
															<input type="hidden" class="five_rating_scale" name ="rating_scale" value="<?php echo $ten_ratings['rating_scale'];?>"> 

														<?php } else {
															?>
															<input type="hidden" class="five_rating_scale" name="rating_scale" class="form-control rating_scale">
															<tr>

																<td> 1 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="1">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 2 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="2">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 2" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 3 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="3">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 3" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 4 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="4">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 4" required>
																</td>
																<td>

																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 5 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="5">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 5" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 6 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="6">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 6" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 7 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="7">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 7" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 8 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="8">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 8" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 9 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="9">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 9" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 10 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="10">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 10" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>

														<?php } ?>
														</tbody>
													</table>
													<div class="submit-section">
												<button class="btn btn-primary submit-btn create_goal_configuration_submit" type="submit">Save</button>
											</div>
											</form>
												</div>
											</div>
											<!-- 10 Ratings Content -->
											
											<!-- Custom Ratings Content -->
											<?php $custom_rating = $this->db->get_where('competency_ratings',array('rating_scale'=>'custom_rating'))->row_array() ;
        											    // echo print_r($five_ratings); exit;
        											    ?>
											<div class="form-group" id="custom_rating_cont_competency" style="display: none;">
												<label>Custom Rating Count</label>
												<div class="form-group">
													<input type="text" class="form-control" id="custom_rating_input" name="custom_rating_count" value="" placeholder="20" style="width: 160px;">
												</div>
												<label>Rating Scale Definition</label>
												<div class="table-responsive">
													<form id="create_goal_configuration" action="<?=base_url()?>settings/create_competency_ratings" method="POST">
														<!-- <textarea rows="4" cols="5" class="form-control" name="description"><?php echo (isset($custom_rating) && !empty($custom_rating))?$custom_rating['description'] :" ";?></textarea> -->
														<input type="hidden" class="five_rating_scale" name="rating_scale" class="form-control rating_scale">
													<table class="table">
														<tbody class="custom-value">
															<?php if(isset($custom_rating) && !empty($custom_rating)){ 
															$rating_no = explode('|',$custom_rating['rating_no']);
															$rating_value = explode('|',$custom_rating['rating_value']);
															$definition = explode('|',$custom_rating['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_no[$i])){
															  ?>
																<tr>
																<td> <?php echo $a++; ?> </td>
																<td>
																	<input type="hidden" name="rating_no[]" class="form-control" value="<?php echo $rating_no[$i];?>">
																	<input type="text" name="rating_value_custom[]" class="form-control" value="<?php echo $rating_value[$i];?>" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_custom[]" class="form-control" placeholder="Descriptive Rating Definition" required><?php echo $definition[$i];?></textarea>
																</td>
																
															</tr>
																
															<?php }  }?>
															<input type="hidden" class="five_rating_scale" name ="rating_scale" value="<?php echo $custom_rating['rating_scale'];?>">
															<?php 
															 } 
															?>
														</tbody>
													</table>
													<div class="submit-section">
												<button class="btn btn-primary submit-btn create_goal_configuration_submit" type="submit">Save</button>
											</div>
											</form>
												</div>
											</div>
											<!-- /Custom Ratings Content -->
											</div>
										</div>	
									</div>
									<!-- /Compentency Config -->
									<!-- /OKR Config -->
									
									<!-- KPI Config -->
									<!-- <div class="tab-pane" id="kpi_tab">
										<div class="row">
											<div class="col-md-6">
												<form action="<?php echo base_url()?>settings/insert_kpi" method="post">
													<div class="form-group">
														<label>KPIs Description</label>
														<textarea rows="4" cols="5" class="form-control" name="kpi_desc"><?php echo $kpi_desc['description'];?></textarea>
													</div>

													<div class="submit-section">
														<button class="btn btn-primary submit-btn" type="submit">Save</button>
													</div>
												</form>
											</div>
										</div>	
									</div> -->
									<!-- /KPI Config -->
									
									<!-- Smart Goal Config -->
									<div class="tab-pane <?php echo ('smart_goals' == $active_performance )?'active':'';?>" id="smart_goals_tab">
										
										<div class="row">
											<div class="col-md-6">
												<!-- <form id="compentency_tab" action="<?php echo base_url()?>settings/insert_competencies" method="post"> -->
													<div class="form-group">
														<label>Smart Goals Configuration</label>
														<textarea rows="4" cols="5" class="form-control" name="smart_goals">A 360-degree assessment can also be added to capture ratings and feedback</textarea>
													</div>

													<div class="submit-section">														
														<button class="btn btn-primary submit-btn performance_status <?php echo ($performance_status['smart_goals'] ==1)?"red_circle":"";?>" data-status="smart_goals" <?php echo ($performance_status['smart_goals'] ==1)?"disabled":"";?> title="<?php echo ($performance_status['smart_goals'] ==1)?"Admin can’t activate it again":"";?>" <?php if($performance_status['smart_goals'] ==1){ ?> style="background-color: #c7c7c7;border: 1px solid #c7c7c7;" <?php } ?> >Activate SMART Goals</button>
													</div>
												<!-- </form> -->
											</div>
										</div>	
											<hr>
											<div class="form-group">
												<label>Choose Your Rating Scale</label>
												<div class="radio_input" id="rating_scale_select">
													<label class="radio-inline custom_radio">
														<input type="radio" name="rating_scale" value="rating_01_010" required class="rating_scale">0.1 - 1.0 <span class="checkmark"></span>
													</label>
													<label class="radio-inline custom_radio">
														<input type="radio" name="rating_scale" value="rating_1_5" required class="rating_scale">1 - 5 <span class="checkmark"></span>
													</label>
													<label class="radio-inline custom_radio">
														<input type="radio" name="rating_scale" value="rating_1_10" class="rating_scale">1 - 10 <span class="checkmark"></span>
													</label>
													<label class="radio-inline custom_radio">
														<input type="radio" name="rating_scale" value="custom_rating" class="rating_scale">Custom <span class="checkmark"></span>
													</label> 
												</div>
											</div>


											<?php $zero_one_ratings = $this->db->get_where('smart_goal_configuration',array('rating_scale'=>'rating_01_010'))->row_array() ;
        											    // echo print_r($five_ratings); exit;
        											    ?>
											<div class="form-group" id="01ratings_cont" style="display: none;">
												<label>Rating Scale Definition</label>
													<div class="table-responsive">
														<form id="create_goal_configuration" action="<?=base_url()?>settings/create_smart_goal" method="POST">
														<!-- <textarea rows="4" cols="5" class="form-control" name="description"><?php echo (isset($ten_ratings) && !empty($ten_ratings))?$ten_ratings['description'] :" ";?></textarea> -->

													<table class="table">
														<tbody>
															<?php

															if(isset($zero_one_ratings) && !empty($zero_one_ratings)){ 
															$rating_no = explode('|',$zero_one_ratings['rating_no']);	
															$rating_value = explode('|',$zero_one_ratings['rating_value']);
															$definition = explode('|',$zero_one_ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_value[$i])){
															  ?>
																<tr>
																<td> <?php echo $a++; ?> </td>
																<td>
																	<input type="hidden" name="rating_no[]" class="form-control" value="<?php echo $rating_no[$i];?>">
																	<input type="text" name="rating_value_ten[]" class="form-control" value="<?php echo $rating_value[$i];?>" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required><?php echo $definition[$i];?></textarea>
																</td>
															</tr>
																
															<?php }  } ?>
															<input type="hidden" class="five_rating_scale" name ="rating_scale" value="<?php echo $zero_one_ratings['rating_scale'];?>"> 

														<?php } else {
															?>
															<input type="hidden" class="five_rating_scale" name="rating_scale" class="form-control rating_scale">
															<tr>

																<td> 0.1 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.1">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.2 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.2">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.2" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.3 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.3">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.3" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.4 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.4">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.4" required>
																</td>
																<td>

																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.5 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.5">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.5" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.6 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.6">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.6" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.7 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.7">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.7" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.8 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.8">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.8" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 0.9 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="0.9">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 0.9" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 1.0 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="1.0">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 1.0" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>

														<?php } ?>
														</tbody>
													</table>
													<div class="submit-section">
												<button class="btn btn-primary submit-btn create_goal_configuration_submit" type="submit">Save</button>
											</div>
											</form>
												</div>
											</div>
											<!-- 0.1 to  1.0  Ratings Content -->
											
											<!-- 5 Ratings Content -->

											<?php $five_ratings = $this->db->get_where('smart_goal_configuration',array('rating_scale'=>'rating_1_5'))->row_array() ;
        											    // echo print_r($five_ratings); exit;
        											    ?>
											<div class="form-group" id="5ratings_cont" style="display: none;">
												<label>Rating Scale Definition</label>
													<div class="table-responsive">
														<form id="" action="<?=base_url()?>settings/create_smart_goal" method="POST">
															<!-- <textarea rows="4" cols="5" class="form-control" name="description"><?php echo (isset($five_ratings) && !empty($five_ratings))?$five_ratings['description'] :" ";?></textarea> -->
													<table class="table">
														<tbody>
															<?php

															if(isset($five_ratings) && !empty($five_ratings)){ 
															$rating_no = explode('|',$five_ratings['rating_no']);
															$rating_value = explode('|',$five_ratings['rating_value']);
															$definition = explode('|',$five_ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																// if(!empty($rating_value[$i])){
															  ?>
																<tr>
																<td> <?php echo $a++; ?> </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="<?php echo $rating_no[$i];?>">
																	<input type="text" name="rating_value[]" class="form-control" value="<?php echo $rating_value[$i];?>" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required><?php echo $definition[$i];?></textarea>
																</td>

															</tr>
																
															<?php 
															  } ?>
															<input type="hidden" name ="rating_scale" class="five_rating_scale" value="<?php echo $five_ratings['rating_scale'];?>">
														<?php } else {
															?>
															
															<tr>
																<td> 1 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="1">
																	<input type="text" name="rating_value[]" class="form-control" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 2 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="2">
																	<input type="text" name="rating_value[]" class="form-control" placeholder="Short word to describe rating of 2" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 3 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="3">
																	<input type="text" name="rating_value[]" class="form-control" placeholder="Short word to describe rating of 3" required>
																</td>
																<td>
																	<textarea rows="3"  name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 4 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="4">
																	<input type="text" name="rating_value[]" class="form-control" placeholder="Short word to describe rating of 4" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 5 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="5">
																	<input type="text" name="rating_value[]" class="form-control" placeholder="Short word to describe rating of 5" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<input type="hidden" class="five_rating_scale" name="rating_scale" class="form-control rating_scale">
														
														<?php } ?>
														</tbody>
													</table>
													<div class="submit-section">
												<button class="btn btn-primary submit-btn create_goal_configuration_submit" type="submit">Save</button>
											</div>
											</form>
												</div>
											</div>
											<!-- /5 Ratings Content -->
											
											<!-- 10 Ratings Content -->
											<?php $ten_ratings = $this->db->get_where('smart_goal_configuration',array('rating_scale'=>'rating_1_10'))->row_array() ;
        											    // echo print_r($five_ratings); exit;
        											    ?>
											<div class="form-group" id="10ratings_cont" style="display: none;">
												<label>Rating Scale Definition</label>
													<div class="table-responsive">
														<form id="create_goal_configuration" action="<?=base_url()?>settings/create_smart_goal" method="POST">
														<!-- <textarea rows="4" cols="5" class="form-control" name="description"><?php echo (isset($ten_ratings) && !empty($ten_ratings))?$ten_ratings['description'] :" ";?></textarea> -->

													<table class="table">
														<tbody>
															<?php

															if(isset($ten_ratings) && !empty($ten_ratings)){ 
															$rating_no = explode('|',$ten_ratings['rating_no']);	
															$rating_value = explode('|',$ten_ratings['rating_value']);
															$definition = explode('|',$ten_ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_value[$i])){
															  ?>
																<tr>
																<td> <?php echo $a++; ?> </td>
																<td>
																	<input type="hidden" name="rating_no[]" class="form-control" value="<?php echo $rating_no[$i];?>">
																	<input type="text" name="rating_value_ten[]" class="form-control" value="<?php echo $rating_value[$i];?>" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required><?php echo $definition[$i];?></textarea>
																</td>
															</tr>
																
															<?php }  } ?>
															<input type="hidden" class="five_rating_scale" name ="rating" value="<?php echo $ten_ratings['rating_scale'];?>"> 

														<?php } else {
															?>
															<input type="hidden" class="five_rating_scale" name="rating_scale" class="form-control rating_scale">
															<tr>

																<td> 1 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="1">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 2 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="2">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 2" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 3 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="3">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 3" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 4 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="4">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 4" required>
																</td>
																<td>

																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 5 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="5">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 5" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 6 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="6">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 6" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 7 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="7">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 7" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 8 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="8">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 8" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 9 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="9">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 9" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 10 </td>
																<td>
																	<input type="hidden" name="rating_no[]" value="10">
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 10" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>

														<?php } ?>
														</tbody>
													</table>
													<div class="submit-section">
												<button class="btn btn-primary submit-btn create_goal_configuration_submit" type="submit">Save</button>
											</div>
											</form>
												</div>
											</div>
											<!-- 10 Ratings Content -->
											
											<!-- Custom Ratings Content -->
											<?php $custom_rating = $this->db->get_where('smart_goal_configuration',array('rating_scale'=>'custom_rating'))->row_array() ;
        											    // echo print_r($five_ratings); exit;
        											    ?>
											<div class="form-group" id="custom_rating_cont" style="display: none;">
												<label>Custom Rating Count</label>
												<div class="form-group">
													<input type="text" class="form-control" id="custom_rating_input" name="custom_rating_count" value="" placeholder="20" style="width: 160px;">
												</div>
												<label>Rating Scale Definition</label>
												<div class="table-responsive">
													<form id="create_goal_configuration" action="<?=base_url()?>settings/create_smart_goal" method="POST">
														<!-- <textarea rows="4" cols="5" class="form-control" name="description"><?php echo (isset($custom_rating) && !empty($custom_rating))?$custom_rating['description'] :" ";?></textarea> -->
														<input type="hidden" class="five_rating_scale" name="rating_scale" class="form-control rating_scale">
													<table class="table">
														<tbody class="custom-value">
															<?php if(isset($custom_rating) && !empty($custom_rating)){ 
															$rating_no = explode('|',$custom_rating['rating_no']);
															$rating_value = explode('|',$custom_rating['rating_value']);
															$definition = explode('|',$custom_rating['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_no) ; $i++) {
																if(!empty($rating_no[$i])){
															  ?>
																<tr>
																<td> <?php echo $a++; ?> </td>
																<td>
																	<input type="hidden" name="rating_no[]" class="form-control" value="<?php echo $rating_no[$i];?>">
																	<input type="text" name="rating_value_custom[]" class="form-control" value="<?php echo $rating_value[$i];?>" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_custom[]" class="form-control" placeholder="Descriptive Rating Definition" required><?php echo $definition[$i];?></textarea>
																</td>
																
															</tr>
																
															<?php }  }?>
															<input type="hidden" class="five_rating_scale" name ="rating_scale" value="<?php echo $custom_rating['rating_scale'];?>">
															<?php 
															 } 
															?>
														</tbody>
													</table>
													<div class="submit-section">
												<button class="btn btn-primary submit-btn create_goal_configuration_submit" type="submit">Save</button>
											</div>
											</form>
												</div>
											</div>
											<!-- /Custom Ratings Content -->


											
											
											<!-- <div class="submit-section">
												<button class="btn btn-primary submit-btn create_goal_configuration_submit" type="submit">Save</button>
											</div> -->
										<!-- </form> -->
									</div>
									<!-- /Smart Goal Config -->
									
									
									
								</div>
							</div>
						</div>
					</div>
                </div>
				
         

		<div class="sidebar-overlay" data-reff="#sidebar"></div>
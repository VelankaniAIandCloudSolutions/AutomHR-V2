

		<div class="page-wrapper">
                <div class="content container">
					<div class="row">
						<div class="col-sm-8">
							<h4 class="page-title">Performance Configuration</h4>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card-box">
								<ul class="nav nav-tabs nav-tabs-bottom">
									<li class="active"><a href="#okr_tab" data-toggle="tab">OKRs</a></li>
									<li><a href="#kpi_tab" data-toggle="tab">KPI</a></li>
									<li><a href="#smart_goals_tab" data-toggle="tab">Smart Goals</a></li>
									<li><a href="#compentency_tab" data-toggle="tab">Compentencies or BARS</a></li>
								</ul>

								<div class="tab-content">
								
									<!-- OKR Config -->
									<div class="tab-pane active" id="okr_tab">
										<div class="row">
											<div class="col-md-6">
												<form id="okr_description" action="<?=base_url()?>performance_configuration/okr_description" method="POST">
													<div class="form-group">
														<label>OKRs Description</label>
														<textarea rows="4" cols="5" class="form-control" name="description"><?php echo $okr_description['description'];?></textarea>
													</div>
													<div class="submit-section">
														<button class="btn btn-primary submit-btn okr_description_submit" type="submit">Save</button>
													</div>
												</form>
											</div>
										</div>
									</div>
									<!-- /OKR Config -->
									
									<!-- KPI Config -->
									<div class="tab-pane" id="kpi_tab">
										KPI Content here
									</div>
									<!-- /KPI Config -->
									
									<!-- Smart Goal Config -->
									<div class="tab-pane" id="smart_goals_tab">
										<h4 class="m-b-20">Smart Goals Configuration</h4>
										<form id="" action="<?=base_url()?>performance_configuration/create_smart_goal" method="POST">
											<div class="form-group">
												<label>Choose Your Rating Scale</label>
												<div class="radio_input" id="rating_scale_select">
													<label class="radio-inline custom_radio">
														<input type="radio" name="rating_scale" value="rating_1_5" required>1 - 5 <span class="checkmark"></span>
													</label>
													<label class="radio-inline custom_radio">
														<input type="radio" name="rating_scale" value="rating_1_10">1 - 10 <span class="checkmark"></span>
													</label>
													<label class="radio-inline custom_radio">
														<input type="radio" name="rating_scale" value="custom_rating">Custom <span class="checkmark"></span>
													</label> 
												</div>
											</div>
											
											<!-- 5 Ratings Content -->

											<?php $five_ratings = $this->db->get_where('smart_goal_configuration',array('rating_scale'=>'rating_1_5'))->row_array() ;
        											    // echo print_r($five_ratings); exit;
        											    ?>
											<div class="form-group" id="5ratings_cont" style="display: none;">
												<label>Rating Scale Definition</label>
													<div class="table-responsive">
													<table class="table">
														<tbody>
															<?php

															if(isset($five_ratings) && !empty($five_ratings)){ 
															$rating_value = explode('|',$five_ratings['rating_value']);
															$definition = explode('|',$five_ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_value) ; $i++) {
																// if(!empty($rating_value[$i])){
															  ?>
																<tr>
																<td> <?php echo $a++; ?> </td>
																<td>
																	<input type="text" name="rating_value[]" class="form-control" value="<?php echo $rating_value[$i];?>" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required><?php echo $definition[$i];?></textarea>
																</td>
															</tr>
																
															<?php 
															  } ?>
															<!-- <input type="hidden" name ="rating" value="<?php echo $five_ratings['rating_scale'];?>"> -->
														<?php } else {
															?>
															<tr>
																<td> 1 </td>
																<td>
																	<input type="text" name="rating_value[]" class="form-control" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 2 </td>
																<td>
																	<input type="text" name="rating_value[]" class="form-control" placeholder="Short word to describe rating of 2" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 3 </td>
																<td>
																	<input type="text" name="rating_value[]" class="form-control" placeholder="Short word to describe rating of 3" required>
																</td>
																<td>
																	<textarea rows="3"  name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 4 </td>
																<td>
																	<input type="text" name="rating_value[]" class="form-control" placeholder="Short word to describe rating of 4" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 5 </td>
																<td>
																	<input type="text" name="rating_value[]" class="form-control" placeholder="Short word to describe rating of 5" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
														<?php } ?>
														</tbody>
													</table>
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
													<table class="table">
														<tbody>
															<?php

															if(isset($ten_ratings) && !empty($ten_ratings)){ 
															$rating_value = explode('|',$ten_ratings['rating_value']);
															$definition = explode('|',$ten_ratings['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_value) ; $i++) {
																if(!empty($rating_value[$i])){
															  ?>
																<tr>
																<td> <?php echo $a++; ?> </td>
																<td>
																	<input type="text" name="rating_value_ten[]" class="form-control" value="<?php echo $rating_value[$i];?>" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required><?php echo $definition[$i];?></textarea>
																</td>
															</tr>
																
															<?php }  } ?>
															<!-- <input type="hidden" name ="rating" value="<?php echo $ten_ratings['rating_scale'];?>"> -->

														<?php } else {
															?>
															<tr>
																<td> 1 </td>
																<td>
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 2 </td>
																<td>
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 2" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 3 </td>
																<td>
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 3" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 4 </td>
																<td>
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 4" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 5 </td>
																<td>
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 5" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 6 </td>
																<td>
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 6" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 7 </td>
																<td>
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 7" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 8 </td>
																<td>
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 8" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 9 </td>
																<td>
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 9" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
															<tr>
																<td> 10 </td>
																<td>
																	<input type="text" name="rating_value_ten[]" class="form-control" placeholder="Short word to describe rating of 10" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_ten[]" class="form-control" placeholder="Descriptive Rating Definition" required></textarea>
																</td>
															</tr>
														<?php } ?>
														</tbody>
													</table>
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
													<table class="table">
														<tbody class="custom-value">
															<?php if(isset($custom_rating) && !empty($custom_rating)){ 
															$rating_value = explode('|',$custom_rating['rating_value']);
															$definition = explode('|',$custom_rating['definition']);
															$a= 1;
															for ($i=0; $i <count($rating_value) ; $i++) {
																if(!empty($rating_value[$i])){
															  ?>
																<tr>
																<td> <?php echo $a++; ?> </td>
																<td>
																	<input type="text" name="rating_value_custom[]" class="form-control" value="<?php echo $rating_value[$i];?>" placeholder="Short word to describe rating of 1" required>
																</td>
																<td>
																	<textarea rows="3" name="definition_custom[]" class="form-control" placeholder="Descriptive Rating Definition" required><?php echo $definition[$i];?></textarea>
																</td>
																
															</tr>
																
															<?php }  }?>
															<!-- <input type="hidden" name ="rating" value="<?php echo $custom_rating['rating_scale'];?>"> -->
															<?php 
															 } 
															?>
														</tbody>
													</table>
												</div>
											</div>
											<!-- /Custom Ratings Content -->
											
											<div class="submit-section">
												<button class="btn btn-primary submit-btn create_goal_configuration_submit" type="submit">Save</button>
											</div>
										</form>
									</div>
									<!-- /Smart Goal Config -->
									
									<!-- Compentency Config -->
									<div class="tab-pane" id="compentency_tab">
										Compentency content here
									</div>
									<!-- /Compentency Config -->
									
								</div>
							</div>
						</div>
					</div>
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
            </div>

		<div class="sidebar-overlay" data-reff="#sidebar"></div>
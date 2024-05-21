<div class="content">

					<div class="row">

						<div class="col-sm-8">

							<h4 class="page-title"><?=lang('messages')?></h4>

						</div>

					</div>

					<div class="row">

						<div class="col-sm-4 col-md-4 col-lg-3 col-xs-12">

							<div class="card-box">

								<a class="btn btn-primary btn-block m-b-20" href="<?=base_url()?>messages/send?group=sent" title="<?=lang('send_message')?>" data-placement="top"><i class="fa fa-envelope"></i> <?=lang('send_message')?></a>

								<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">

									<div>

										<ul class="mail-menu">

											<li class="<?php echo ($group == 'inbox') ? 'active' : '';?>">

												<a href="<?=base_url()?>messages?group=inbox"> <i class="fa fa-fw fa-envelope"></i>

													<?=lang('inbox')?>

												</a>

											</li>

											<li class="<?php echo ($group == 'sent') ? 'active' : '';?>">

												<a href="<?=base_url()?>messages?group=sent"> <i class="fa fa-fw fa-exchange"></i>

													<?=lang('sent')?>

												</a>

											</li>

											<li class="<?php echo ($group == 'favourites') ? 'active' : '';?>">

												<a href="<?=base_url()?>messages?group=favourites"> <i class="fa fa-fw fa-star"></i>

													<?=lang('favourites')?>

												</a>

											</li>

											<li class="<?php echo ($group == 'trash') ? 'active' : '';?>">

												<a href="<?=base_url()?>messages?group=trash"> <i class="fa fa-fw fa-trash-o"></i>

													<?=lang('trash')?>

												</a>

											</li>

										</ul>

									</div>

								</div>

                            </div>

						</div>

						<div class="col-sm-8 col-md-8 col-lg-9 col-xs-12">

									<div class="card-box">

										<div class="email-header">

											<div class="row">

												<div class="col-xs-12 col-sm-6 pull-right top-action-left">

													<?php echo form_open(base_url().'messages/search/'); ?>

														<div class="input-group">

															<input type="text" class="form-control" name="keyword" placeholder="<?=lang('keyword')?>">

															<span class="input-group-btn"> <button class="btn btn-md btn-default" type="submit">Go!</button></span>

														</div>

													</form>

												</div>

											</div>

										</div>

										<div class="email-content">

											<div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">

												<div class="panel-body">

													<!-- message form -->

													<article class="comment-item media" id="comment-form">

														<a class="pull-left thumb-sm avatar">

															<img src="<?php echo User::avatar_url(User::get_id()); ?>" class="img-circle">

														</a>

														<section class="media-body">

															<?php

															$attributes = array('class' => 'class="m-b-none"','id'=>'messageConversation');

															echo form_open(base_url().'messages/send', $attributes); ?>

																<input type="hidden" name="user_to[]" value="<?=$user_from?>">

																<input type="hidden" name="r_url" value="<?=current_url()?>">

																<div class="message-cont">

																	<textarea class="form-control foeditor-send-conversation" name="message" placeholder="<?=lang('enter_message')?>"></textarea>
																	<div class="row">
																	<div class="col-md-6">
																	<label id="send_conversation_error" class="error display-none" style="position:inherit;top:0;font-size:15px;">Message must not empty</label>
																	</div>
																	</div>

																	<div class="message-send-btn">

																		<button class="btn btn-success pull-right btn-sm m-t-3" id="send_message_conversation" type="submit"><?=lang('send_message')?></button>

																		<ul class="nav nav-pills nav-sm">



																		</ul>

																	</div>

																</div>

															</form>

														</section>

													</article>

													<!-- Conversation Start -->

													<section class="comment-list block">

														<?php

														$this->load->helper('text');

														foreach (Message::conversation($user_from) as $key => $msg) { ?>

															<article class="comment-item">

																<a class="pull-left thumb-sm avatar">

																	<img src="<?php echo User::avatar_url($msg->user_from); ?>" class="img-circle">

																</a>

																<span class="arrow left"></span>

																<section class="comment-body panel panel-default">

																	<header class="panel-heading bg-white">

																		<a href="#">

																			<?=ucfirst(User::displayName($msg->user_from))?>

																		</a>

																		<span class="text-muted m-l-sm pull-right">

																			<?php if($msg->user_from != User::get_id()) { ?>

																			<a href="<?=base_url()?>messages/favourite/<?=$msg->msg_id?>" title="<?=lang('favourite')?>"><i class="fa <?=($msg->favourite) ? 'fa-heart text-danger' : 'fa-heart-o';?> text-danger"></i></a>

																			<?php } ?>

																			<i class="fa fa-clock-o"></i>

																			<?php echo Applib::time_elapsed_string(strtotime($msg->date_received),'UTC'); ?>

																			<?php

																			if ($msg->user_to == User::get_id()) { ?>

																			<a href="<?=base_url()?>messages/delete/<?=$msg->msg_id?>" data-toggle="ajaxModal" class="btn btn-danger btn-xs active"><i class="fa fa-trash-o text-white text-active"></i>

																			</a>

																			<?php } ?>

																		</span>

																	</header>

																	<div class="panel-body">

																		<div><?=nl2br($msg->message);?></div>

																	</div>

																</section>

															</article>

														<?php } ?>

														<!-- .message-end -->

													</section>

												</div>

											</div>

										</div>

									</div>

						</div>

					</div>

</div>
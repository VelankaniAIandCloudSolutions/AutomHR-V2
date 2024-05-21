<?php $info = Ticket::view_by_id($id); ?>
<!--Start -->
                    <div class="header-fixed hidden-print">
                        <div class="row">

                        <div class="col-md-12 d-flex">
        
        <a href="#t_info" class="btn btn-sm btn-danger text-white" id="info_btn" data-toggle="class:hide"><i class="fa fa-info-circle"></i></a>
        <?php if (User::is_admin()) { ?>
            <a href="<?=base_url()?>tickets/edit/<?=$info->id?>" class="btn btn-sm btn-success ms-2">
            <i class="fa fa-pencil"></i> <?=lang('edit_ticket')?></a>
        <?php } ?>
        <?php if (User::is_admin() || ($info->reporter == $this->session->userdata('user_id')) ||  ($info->assignee == $this->session->userdata('user_id')) ) { 
            ?>
        <div class="d-flex">

            <button class="btn btn-sm btn-info dropdown-toggle ms-2" data-toggle="dropdown">
                                    <?php 
                                    if($info->status!=""){ echo ucfirst($info->status );}else{
                                        echo  lang('change_status');
                                    }
                                   ?>
                                    <span class="caret"></span></button>
                                    <div class="dropdown-menu">
                                        <?php

                                       
                                        $statuses = $this->db->get('status')->result();
                                        foreach ($statuses as $key => $s) {
                                            if($info->reporter == $this->session->userdata('user_id') && $this->session->userdata('role_id')!=1){
                                                if($info->status == 'closed'){
                                                    if($s->status == 'open'){
                                                        $s->status = 'reopen';
                                                    }
                                                }
                                                if($s->status !='closed'){
                                         ?>
                                        <a class="dropdown-item <?php if($info->status == $s->status){ echo "active";}?>" style="display:block;" href="<?=base_url()?>tickets/status/<?=$info->id?>/?status=<?=$s->status?>"><?=ucfirst($s->status)?></a>
                                        <?php }
                                            }
                                            else if($info->assignee == $this->session->userdata('user_id')  && $this->session->userdata('role_id')!=1){
                                                if($info->status == 'closed'){
                                                    if($s->status == 'open'){
                                                        $s->status = 'reopen';
                                                    }
                                                }
                                                if($s->status !='closed'){
                                         ?>
                                       <a class="dropdown-item <?php if($info->status == $s->status){ echo "active";}?>" style="display:block;" href="<?=base_url()?>tickets/status/<?=$info->id?>/?status=<?=$s->status?>"><?=ucfirst($s->status)?></a>
                                        <?php }
                                            } else {
                                                if($info->status == 'closed'){
                                                    if($s->status == 'open'){
                                                        $s->status = 'reopen';
                                                    }
                                                }
                                                ?>
                                                <a class="<?php if($info->status == $s->status){ echo "active";}?>" style="display:block;" href="<?=base_url()?>tickets/status/<?=$info->id?>/?status=<?=$s->status?>"><?=ucfirst($s->status)?></a>
                                            <?php }

                                        } ?>
                                    </ul>
        </div>


    <?php } ?>
    <?php 
        $this->db->select('U.id,AD.fullname');
        $this->db->from('users U');
        $this->db->join('account_details AD', 'AD.user_id = U.id', 'left');
        $this->db->where('U.department_id',$info->department);
        $dept_user = $this->db->get()->result();
                                        // echo "<pre>"; print_r($dept_user);
    $cur_user_dept = User::login_info(User::get_id())->department_id;
    if($info->assignee == 0 &&  $info->department == $cur_user_dept){?>

        <div class="d-flex">
            <button class="btn btn-sm btn-warning dropdown-toggle" data-toggle="dropdown">Select Assignee </button>
			<div class="dropdown-menu">
				<?php foreach ($dept_user as $key => $dept) { ?>
				<a class="dropdown-item" href="<?=base_url()?>tickets/assignee/<?=$info->id?>/?assignee=<?=$dept->id?>"><?=ucfirst($dept->fullname)?></a>
				<?php } ?>
			</div>
        </div>

    <?php } ?>
    
        <?php if (User::is_admin()) { ?>
		<a href="<?=base_url()?>tickets/delete/<?=$info->id?>" class="btn btn-sm btn-danger float-end ms-2" data-toggle="ajaxModal">
			<i class="fa fa-trash-o"></i> <?=lang('delete_ticket')?>
		</a>
        <?php } ?>
   </div>


                            
                        </div>
                    </div>

<div class="content">
                    <?php
                    $rep = $this->db->where('ticketid',$info->id)->get('ticketreplies')->num_rows();
                    if($rep == 0 AND $info->status != 'closed'){ ?>

                <div class="alert alert-success hidden-print mt-3 d-flex align-items-center">
                        <button type="button" class="close me-2" data-dismiss="alert">×</button> <i class="fa fa-warning"></i>
                        <?= lang('ticket_not_replied') ?>
                    </div>
                <?php } ?>


                        <!-- Start ticket Details -->
                        <div class="row p-0">
                                <div class="col-sm-12 col-lg-12" id="t_info">
								
								
														<div class="card-box">
                              <?php //print_r($info);?>
															<!-- <div class="project-title">
																<div class="m-b-20">
																	<span class="h5 card-title "><?=$info->subject?></span>
																</div>
															</div>
                                                            <div class=""><?=nl2br_except_pre($info->body)?></div> -->
                                                            <div class="project-title">
                                                                <div class="m-b-20">
                                                                    <span class="h5 card-title "><?php echo lang('ticket_details'); ?></span>
                                                                </div>
                                                            </div>
														      <div class="tic-content">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <ul class="list-group">
                                                                          <li class="list-group-item">
                                                                             <span class="text-dark"><?php echo lang('ticket_number')?></span><span class="float-end text-primary"><?php echo $info->ticket_code;?></span>
                                                                          </li>
                                         <?php 
                                              $this->db->select('U.email,AD.fullname');
                                              $this->db->from('dgt_users as U');
                                              $this->db->join('dgt_account_details as AD','AD.user_id=U.id');
                                              $this->db->where('U.id',$info->created_by);
                                              $created_user = $this->db->get()->row_array();
                                          //   print_r($created_user);
                                            ?>
                                                                          <li class="list-group-item">
                                                                             <span class="text-dark"><?php echo lang('name');?></span><span class="pull-right text-primary"><?php echo $created_user['fullname'];?></span>
                                                                          </li>
                                                                          <li class="list-group-item">
                                                                               <span class="text-dark"><?php echo lang('email');?></span><span class="pull-right text-primary"><?php echo $created_user['email'];?></span>
                                                                          </li>
                                                                         
                                                                           <li class="list-group-item">
                                                                             <span class="text-dark"><?php echo lang('created_on');?></span><span class="pull-right text-primary"><?php echo date('d/m/Y',strtotime($info->created)); ?></span>
                                                                          </li>
                                                                           <li class="list-group-item">
                                                                             <span class="text-dark"><?php echo lang('subject');?></span><span class="pull-right text-primary"><?php echo $info->subject;?></span>
                                                                          </li>
                                                                      </ul>
                                                                      <ul class="list-group mt-4">
                                                                           <li class="list-group-item">
                                                                             <span class="text-dark">Description</span>
                                                                             <p  class="f-12 mt-3"><?php echo nl2br_except_pre($info->body); ?>.</p>
                                                                          </li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <ul class="list-group">
                                                                          <li class="list-group-item">
                                                                             <span class="text-dark">Assigned To</span><span class="pull-right text-primary"><?php echo ($info->assignee)?User::displayName($info->assignee):'-';?></span>
                                                                          </li>
                                                                           <li class="list-group-item">
                                                                             <span class="text-dark">Priority</span><span class="pull-right text-primary"><?php 
                                                                             $this->db->where('id',$info->priority);
                                                                            $priority =  $this->db->get('dgt_priorities')->row(); 

                                                                            echo ucfirst($priority->priority);?></span>
                                                                          </li>
                                                                          <li class="list-group-item">
                                                                             <span class="text-dark"><?php echo lang('status');?></span><span class="pull-right text-primary" style="color:<?php if($info->status=='resolved'){ echo 'green';} ?>"><?php echo ucfirst($info->status);?></span>
                                                                          </li>
                                                                          <li class="list-group-item">
                                                                               <span class="text-dark"><?php echo lang('stage'); ?></span><span class="pull-right text-primary"><?php echo ($info->assignee)?lang('assigned'):lang('not_assigned');?></span>
                                                                          </li>
                                                                          <li class="list-group-item">
                                                                             <span class="text-dark"><?php echo lang('responded_by')?></span><span class="pull-right text-primary"><?php echo ($info->assignee)?User::displayName($info->assignee):'-';?></span>
                                                                          </li>
                                                                      </ul>
                                                                     <!--  <ul class="list-group mt-4">
                                                                           <li class="list-group-item">
                                                                             <span class="text-dark">Resolution</span>
                                                                             <p  class="f-12 mt-3">Here are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.</p>
                                                                          </li>
                                                                        </ul> -->
                                                                    </div>
                                                                </div>
                                                              </div>
														</div>
													
													
                            </div>
                            <!-- End ticket details-->


<style>
img { max-width: 100%; height: auto; }
</style>


                            <!-- <div class="col-xl-5 col-md-12 task-chat-view ticket_body m-t-3"> -->
                            <div class="col-lg-5 col-md-12t ticket_body m-t-3">
                               
                                    <div class="card-box">
								
<div id="task_window">
							<div class="">
											<!-- 	<div class="chats">
												                               <?php 
                                    if(count(Ticket::view_replies($id)) > 0) {
                                    foreach (Ticket::view_replies($id) as $key => $r) {
                                    $role = User::get_role($r->replierid); 
                                    $role_label = ($role == 'admin') ? 'danger' : 'info';
                                    ?>
													<div class="chat chat-left">
														<div class="chat-avatar">
															<a class="avatar" href="javascript:void(0);">
																 <img src="<?php echo  User::avatar_url($r->replierid); ?>" class="rounded-circle" alt="<?=User::displayName($r->replierid)?>">
															</a>
														</div>
														<div class="chat-body">
															<div class="chat-bubble">
																<div class="task-contents">
																	<span class="task-chat-user"><?php echo User::displayName($r->replierid); ?>                                                 <span class="badge text-white badge-pill bg-<?=$role_label?> m-l-xs">
                                                <?php echo ucfirst(User::get_role($r->replierid))?></span></span> <span class="chat-time">                    <?php echo strftime(config_item('date_format')." %H:%M:%S", strtotime($r->time)); ?>
                          <?php
                        if(config_item('show_time_ago') == 'TRUE'){
                        echo ' - '.Applib::time_elapsed_string(strtotime($r->time));
                      }
                        ?></span><p class="activate_links">
                                                <?=$r->body?>
                                                </p>
												
												<ul class="attach-list">
                                                <?php if($r->attachment != NULL){
                                                $replyfiles = '';
                                                if (json_decode($r->attachment)) {
                                                $replyfiles = json_decode($r->attachment);
                                                foreach ($replyfiles as $rf) { ?>
												
												 <li class="img-file">
													<div class="attach-img-download"><a href="<?=base_url()?>assets/attachments/<?=$rf?>"><?=$rf?></a></div>
													<div class="task-attach-img"><img alt="" src="<?=base_url()?>assets/attachments/<?=$rf?>"></div>
												</li>
												
                                                <?php }
                                                }else{ ?>
                                                <a href="<?=base_url()?>assets/attachments/<?=$r->attachment?>" target="_blank"><?=$r->attachment?></a><br>
                                                <?php } ?>

                                                <?php } ?>
												</ul>

																</div>
															</div>
														</div>
													</div>
						<?php } } else { ?>
																
													<div class="chat chat-left">
														<div class="no-info"><?=lang('no_ticket_replies')?></div>
													</div>
                                    <?php } ?>			
													</div> -->
	
								<div class="chat-footer p-0" style="border-top:0px;">
									<div class="message-bar">
										<div class="message-inner">
											<div class="message-area">
											                                    <!-- comment form -->
                                    <div class="comment-item media" id="comment-form">
                                        <a class="avatar float-left mb-2">
                                           
            <img src="<?php echo User::avatar_url(User::get_id()); ?>" class="rounded-circle">
                                        
                                        </a>Reply<span style="color:red">*</span><br>
                                        <div class="media-body">
                                            <div class="foeditor-noborder">
                                                <?php $attributes = 'class="m-b-0" id="reply_ticket"'; echo form_open_multipart(base_url().'tickets/reply',$attributes); ?>
                                                <input type="hidden" name="ticketid" value="<?=$info->id?>">
                                                <input type="hidden" name="ticket_code" value="<?=$info->ticket_code?>">
                                                <input type="hidden" name="replierid" value="<?=User::get_id();?>">
                                                <textarea class="form-control textarea-trim" name="reply" id="reply_id" style="height: 90px;" placeholder="<?=lang('ticket')?> <?=$info->ticket_code?> <?=lang('reply')?>">
                                                </textarea>
                                                <br>
                                                <div class="row">
                                                    <div>
                                                    <label id="editTicket_message_error" class="error display-none" style="position:inherit;top:0;font-size:15px;">Message must not empty</label>
                                                    </div>
                                                </div>
                                                <div id="file_container">
                                                <div class="form-group">
                                                    <div class="row">
                                                    <div class="col-md-12">   
                                                        <div class="d-flex"><h5>Attach Image</h5> - <p> Image,docs</p></div>
                                                        <input type="file" class="filestyle form-control" name="ticketfiles[]">
                                                    </div>
                                                </div>
                                                </div>
                                                        
                                                </div>
                                                <div class="">
                                                    <div class="mb-3 text-end">
                                                    <a href="javascript:void(0);" class="btn btn-primary btn-sm text-white" id="add-new-file"><?=lang('upload_another_file')?></a>
                                                    <a href="javascript:void(0);" class="btn btn-danger btn-sm" id="clear-files" style="margin-left:6px;"><?=lang('clear_files')?></a>
                                                    <!-- <div class="line line-dashed line-lg"></div> -->
                                                    </div>
                                                    <hr>
                                                    <button class="btn btn-primary float-end" type="submit" id="reply_ticket_btn" ><?=lang('reply_ticket')?></button>
                                                    <ul class="nav nav-pills nav-sm">
                                                    </ul>
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
                        </div>
                   

                            
                                <div class="card-box">
                                    <h5 class="card-title m-b-20">Uploaded image files</h5>
                                    <div class="row">

                                        <?php if($info->attachment != NULL){
                                $files = '';
                                if (json_decode($info->attachment)) {
                                $files = json_decode($info->attachment);
                                foreach ($files as $f) { ?>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="uploaded-box">
                                            <div class="uploaded-img mb-3">
                                                <img alt="" class="img-fluid" src="<?=base_url()?>assets/attachments/<?=$f?>">
                                            </div>
                                            <div class="uploaded-img-name" style="font-size: 13px;">
                                                 <?=$f?>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                                }else{ ?>
                                <div class="col-md-3 col-6">
                                <a class="badge badge-pill bg-info" href="<?=base_url()?>assets/attachments/<?=$info->attachment?>" target="_blank"><?=$info->attachment?></a></div>
                                <?php } ?>

                                <?php } ?>
                                                            </div>
                                                        </div>
                                                   

						</div>
							
							
							<!--  <div class="col-xl-7 col-md-12 task-chat-view m-t-3"> -->
                 <div class="col-lg-7 col-md-12 m-t-3">
                                
                                    <div class="card-box" style="overflow-y:scroll; height:500px;">
                                        <div class="row">
                                            <div class="col-md-12">
                                               
                                                <ul class="tl">
                                                 
                                                    <?php 

                                          if(count(Ticket::view_replies($id)) > 0) {
                                            $i=1;
                                          foreach (Ticket::view_replies($id) as $key => $r) {
                                          $role = User::get_role($r->replierid); 
                                          $role_label = ($role == 'admin') ? 'danger' : 'info';
                                    ?>
                                     <?php if($this->session->userdata('user_id')==$r->replierid){?>
                                                <li>
                                                

                                                <div class="tl-badge success">
                                                </div>
                                                <div class="tl-panel">
                                                <div class="chats p-0">
                                                <div class="chat chat-left">
                                                        <div class="chat-avatar">
                                                            <a class="avatar" href="javascript:void(0);">
                                                                 <img src="<?php echo  User::avatar_url($r->replierid); ?>" class="rounded-circle" alt="<?php echo  User::displayName($r->replierid); ?>" onerror="this.onerror=null;this.src='<?php echo base_url();?>/assets/avatar/default_avatar.jpg';">
                                                            </a>
                                                        </div>
                                                        <div class="chat-body">
                                                            <div class="chat-bubble mb-0">
                                                                <div class="task-contents mb-0">
                                                                    <span class="task-chat-user"><?php echo User::displayName($r->replierid); ?><span class="badge text-white badge-pill bg-danger m-l-xs">
                                                                    <?php echo ucfirst(User::get_role($r->replierid)); ?></span>
                                                                    </span> 
                                                                    <span class="chat-time"> 
                                                                      <?php
                              echo strftime(config_item('date_format')." %I:%M:%S", strtotime($r->time));

                              if(config_item('show_time_ago') == 'TRUE'){
                              echo ' - '.Applib::time_elapsed_string(strtotime($r->time));
                              }
                        ?></span>
                                                                    <p class="activate_links mb-0">
                                                                    <?=$r->body?></p>

                                                                    <ul class="attach-list">
                                                                       <?php if($r->attachment != NULL){
                                                $replyfiles = '';
                                                if (json_decode($r->attachment)) {
                                                $replyfiles = json_decode($r->attachment);
                                                foreach ($replyfiles as $rf) { ?>
                        
                         <li class="img-file">
                          <div class="attach-img-download"><a href="<?=base_url()?>assets/attachments/<?=$rf?>"><?=$rf?></a></div>
                          <div class="task-attach-img"><img alt="" src="<?=base_url()?>assets/attachments/<?=$rf?>"></div>
                        </li>
                        
                                                <?php }
                                                }else{ ?>
                                                <a href="<?=base_url()?>assets/attachments/<?=$r->attachment?>" target="_blank"><?=$r->attachment?></a><br>
                                                <?php } ?>

                                                <?php } ?>
                                                                    </ul>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                </div>
                                                </li>
                                                <?php } if($this->session->userdata('user_id')!=$r->replierid){?>
                                                <li class="tl-inverted">
                                                <div class="tl-badge warning">
                                                </div>
                                                <div class="tl-panel">
                                                <div class="chat chat-left">
                                                        <div class="chat-avatar">
                                                            <a class="avatar" href="javascript:void(0);">
                                                                 <img src="<?php echo  User::avatar_url($r->replierid); ?>" class="rounded-circle" alt="<?=User::displayName($r->replierid)?>" onerror="this.onerror=null;this.src='<?php echo base_url();?>/assets/avatar/default_avatar.jpg';">
                                                            </a>
                                                        </div>
                                                        <div class="chat-body">
                                                            <div class="chat-bubble mb-0">
                                                                <div class="task-contents mb-0">
                                                                    <span class="task-chat-user"><?php echo User::displayName($r->replierid); ?><span class="badge text-white badge-pill bg-danger m-l-xs">
                                                                    <?php echo ucfirst(User::get_role($r->replierid))?></span>
                                                                    </span> 
                                                                    <span class="chat-time"><?php echo strftime(config_item('date_format')." %H:%M:%S", strtotime($r->time)); ?>
                          <?php
                        if(config_item('show_time_ago') == 'TRUE'){
                        echo ' - '.Applib::time_elapsed_string(strtotime($r->time));
                      }
                        ?></span>
                                                                    <p class="activate_links mb-0">
                                                                     <?=$r->body?></p>

                                                                    <ul class="attach-list">
                                                                       <?php if($r->attachment != NULL){
                                                $replyfiles = '';
                                                if (json_decode($r->attachment)) {
                                                $replyfiles = json_decode($r->attachment);
                                                foreach ($replyfiles as $rf) { ?>
                        
                         <li class="img-file">
                          <div class="attach-img-download"><a href="<?=base_url()?>assets/attachments/<?=$rf?>"><?=$rf?></a></div>
                          <div class="task-attach-img"><img alt="" src="<?=base_url()?>assets/attachments/<?=$rf?>"></div>
                        </li>
                        
                                                <?php }
                                                }else{ ?>
                                                <a href="<?=base_url()?>assets/attachments/<?=$r->attachment?>" target="_blank"><?=$r->attachment?></a><br>
                                                <?php } ?>

                                                <?php } ?>
                                                                    </ul>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                </li>

                                              <?php } $i++; } } ?>
                                                
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                
                            </div>
							
							
							
							

                        </div>
                        <!-- End details -->
                </div>
                <!-- End display details -->
                <!-- <label class='btn btn-default btn-filechoose'>Choose File</label> -->
            </section>
            <script src="<?=base_url()?>assets/js/jquery-2.2.4.min.js"></script>
            <script type="text/javascript">
            $('#clear-files').click(function(){
            $('#file_container').html(
            "<div class='form-group'><div class='row'><div class='col-md-12'><label class='btn btn-default btn-filechoose'>Choose File</label><input type='file' class='form-control' data-buttonText='<?=lang('choose_file')?>' data-icon='false' data-classButton='btn btn-default' data-classInput='form-control inline input-s' name='ticketfiles[]'></div></div></div>"
            );
            });
            $('#add-new-file').click(function(){
            $('#file_container').append(
            "<div class='form-group'><div class='row'><div class='col-md-12'><label class='btn btn-default btn-filechoose'>Choose File</label><input type='file' class='form-control' data-buttonText='<?=lang('choose_file')?>' data-icon='false' data-classButton='btn btn-default' data-classInput='form-control inline input-s' name='ticketfiles[]'></div></div></div>"
            );
            });
            $('#info_btn').click(function(){
                var st = $( ".ticket_body" ).attr( "class" );

                if (st == 'col-sm-5 task-chat-view ticket_body' || st == 'ticket_body task-chat-view col-sm-5') {
                    $('.ticket_body').removeClass("col-sm-5");
                    $('.ticket_body').addClass("col-sm-12");
                }else{
                    $('.ticket_body').addClass("col-sm-5");
                    $('.ticket_body').removeClass("col-sm-12");
                }

            });
            function reply_comment(parent_id){
                $.ajax({
                type: "POST",
                url: base_url+'tickets/set_parent_comment',
                dataType: 'json',                       
                data: {parent_id:parent_id},
                type: 'post',
                success: function(data){
                }
            });

            }
            </script>


			
            <!-- end -->
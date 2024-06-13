<?php //echo $this->uri->segment(1); exit; 
if($this->uri->segment(1) == 'chats'){
?>


<div class="sidebar sidebar-<?=config_item('sidebar_theme')?>" id="sidebar">
                <div class="sidebar-inner slimscroll">
                    <div class="sidebar-menu video-wrapper">
                        <?php //echo "<pre>"; print_r($group_chat_list); exit; ?>
                        <ul class="grp-chat-wrapper">
                            <li> 
                                <a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> <span>Back to Home</span></a>
                            </li>
                            <!-- <li class="menu-title grp-video-chat">
                                Chat Groups <a class="menu-title-icon" href="#" data-toggle="modal" data-target="#add_group" title="Create Group"><i class="fa fa-plus"></i></a>
                            </li> -->


                                <?php 
//                                     $user_id = $this->session->userdata('user_id');
//                                     $all_groups = $this->db->select('*')
//                                                            ->from('chat_group_members CGM')
//                                                            ->join('chat_group_details CGD','CGM.group_id = CGD.group_id')
//                                                            ->where('CGM.login_id',$user_id)
//                                                            ->group_by('CGM.group_id')
//                                                            ->get()->result_array();

// // echo "<pre>"; print_r($all_groups); exit; 

//                                 if(empty($all_groups)){ 

//                                     $log_id =  $this->session->userdata('user_id');
//                                     $groups = $this->db->select('*')
//                                              ->from('dgt_chat_group_members CG')
//                                              ->join('dgt_chat_group_details GD', 'CG.group_id = GD.group_id')
//                                              ->where('CG.login_id',$log_id)
//                                              ->order_by('CG.group_id',DESC)
//                                              ->get()->result_array();
//                                              if(empty($groups)){
                                    ?>
                                    <!-- <li class="no-chat-msg">
                                        <p>No Group</p>
                                    </li> -->
                                <?php // }else{
                                    
                                    // foreach($all_groups as $groupp){ ?>
                                    <!-- <li> 
                                        <a href="javascript:void(0);" class="GroupChatList" data-grpid="<?php //echo $groupp['group_id']; ?>" data-grpname="<?php //echo ucfirst($groupp['group_name']); ?>" data-baseurl="<?php //echo base_url(); ?>"><?php //echo ucfirst($groupp['group_name']); ?></a>
                                    </li> -->
                                    <?php
                                     // }
                                     //      } 
                                     //      }else{ 
                                    ?>
                                    <?php  
                                    // foreach($all_groups as $chat_list){ 


                                    //     $chat_users = $this->db->get_where('chat_group_members',array('group_id'=> $chat_list['group_id']))->result_array();
                                    //     if(count($chat_users) > 2 ){
                                        ?>
                                   <!--  <li>
                                        <a href="javascript:void(0);" class="GroupChatList" data-grpid="<?php //echo $chat_list['group_id']; ?>" data-grpname="<?php //echo ucfirst($chat_list['group_name']); ?>" data-baseurl="<?php //echo base_url(); ?>"><?php //echo ucfirst($chat_list['group_name']); ?></a>
                                    </li> -->
                                    <?php // } } ?>
                                <?php // } ?>

                        </ul>
                        <ul class="single-chat-wrapper">
                            <li class="menu-title">
                                Direct Chats <a href="#" data-toggle="modal" data-target="#add_chat_user" class="menu-title-icon" title="Open a direct message"><i class="fa fa-plus"></i></a>
                                <?php //echo "<pre>"; print_r($single_chat_list); exit; ?>
                            </li>
                        </ul> 
                                <?php if(empty($single_chat_list)){ ?>
                                <ul>
                                    <li> <span>No Chat</span></li> 
                                </ul> 
                                <?php }else{ ?>

                                <ul class="UseRLisT">
                                    <?php foreach($single_chat_list as $single_chat){ if(!empty($single_chat['avatar'])){
                                        $pro_pic = $single_chat['avatar'];
                                    }else{
                                        $pro_pic = 'default_avatar.jpg';
                                    }
                                    ?>
                                    <li> 
                                                <?php if($single_chat['online_status'] == 0){ 
                                                            $status = 'offline';
                                                        }else{
                                                            $status = 'online';
                                                        }



                                                        $destination = $this->db->get_where('designation',array('id'=>$single_chat['designation_id']))->row_array();
                                                        // print_r($destination); exit;
                                                    ?>
                                        <!--<a class="SingleChatList" data-name="<?php echo ucfirst($single_chat['fullname']); ?>" data-email="<?php echo $single_chat['email']; ?>" data-phone="<?php echo $single_chat['phone']; ?>" data-online="<?php echo $status; ?>" data-propic="<?php echo $pro_pic; ?>" data-username="<?php echo $single_chat['username']; ?>" data-baseurl="<?php echo base_url(); ?>" data-lastlogin="<?php echo $single_chat['last_login']; ?>" data-userid="<?php echo $single_chat['user_id']; ?>" data-destination ="<?php echo $destination['designation']; ?>" >
                                            <span class="user-img chat-avatar-sm">
                                                <img class="img-circle" src="<?php echo base_url(); ?>assets/avatar/<?php echo $pro_pic; ?>" width="32" alt="Admin">
                                                <span class="status <?php echo $status; ?>"></span>
                                            </span> 
                                            <span class="member-name-blk"><?php echo ucfirst($single_chat['fullname']); ?></span>
                                            <span class="badge bg-danger pull-right"></span>
                                        </a>-->
										
										 <a class="SingleChatList" data-name="<?php echo ucfirst($single_chat['fullname']); ?>" data-email="<?php echo $single_chat['email']; ?>" data-phone="<?php echo $single_chat['phone']; ?>" data-online="<?php echo $status; ?>" data-propic="<?php echo $pro_pic; ?>" data-username="<?php echo $single_chat['username']; ?>" data-baseurl="<?php echo base_url(); ?>" data-lastlogin="<?php echo $single_chat['last_login']; ?>" data-userid="<?php echo $single_chat['user_id']; ?>" data-destination ="<?php echo $destination['designation']; ?>" >
                                            <span class="user-img chat-avatar-sm">
                                                <img class="rounded-circle" src="<?php echo base_url(); ?>assets/avatar/<?php echo $pro_pic; ?>" width="32" alt="Admin">
                                                <span class="status <?php echo $status; ?>"></span>
                                            </span> 
                                            <span class="member-name-blk"><?php echo ucfirst($single_chat['fullname']); ?></span>
                                            <span class="badge bg-danger float-right"></span>
                                        </a>
                                    </li>
                                <?php } ?>
                                </ul>
                            <?php }?>
                    </div>
                </div>
            </div>



            


<?php } 
else if($this->uri->segment(1) =='all_tasks'){ 

    

    ?>


<div class="sidebar sidebar-<?=config_item('sidebar_theme')?>" id="sidebar">
                <div class="sidebar-inner slimscroll">
                    <div class="sidebar-menu">
                        <ul>
                            <li> 
                                <a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Back to Home</a>
                            </li>
                            <li class="menu-title"><a href="<?php echo base_url(); ?>projects">Projects</a> </li>

                            <?php $i=1; foreach($all_projects as $project){ ?>
                                <li <?php if($this->uri->segment(3) == ''){ if($i == count($all_projects)){ ?> class="active" <?php } }elseif($this->uri->segment(3) == $project['project_id']){ ?> class="active" <?php } ?> > 
                                    <a href="<?php echo base_url(); ?>all_tasks/view/<?php echo $project['project_id']; ?>"><?php echo $project['project_title']; ?></a>
                                </li>
                            <?php $i++; } ?>

                        </ul>
                    </div>
                </div>
            </div>


<?php } else{ 

    

    ?>
    <div class="sidebar-<?=config_item('sidebar_theme')?> sidebar" id="nav">
    <div class="slimscroll">
        <?php if(config_item('enable_languages') == 'TRUE'){/* ?>
        <div class="language-menu">
            <div class="btn-group dropdown">
                <button type="button" class="btn btn-sm dropdown-toggle btn-default" data-toggle="dropdown" btn-icon="" title="<?=lang('languages')?>"><i class="fa fa-globe"></i></button>
                <button type="button" class="btn btn-sm btn-default dropdown-toggle  hidden-nav-xs" data-toggle="dropdown"><?=lang('languages')?> <span class="caret"></span></button>
                <ul class="dropdown-menu text-left">
                    <?php foreach ($languages as $lang) : if ($lang->active == 1) : ?>
                    <li>
                    <a href="<?=base_url()?>set_language?lang=<?=$lang->name?>" title="<?=ucwords(str_replace("_"," ", $lang->name))?>">
                    <img src="<?=base_url()?>assets/images/flags/<?=$lang->icon?>.gif" alt="<?=ucwords(str_replace("_"," ", $lang->name))?>"  /> <?=ucwords(str_replace("_"," ", $lang->name))?>
                    </a>
                    </li>
                    <?php endif; endforeach; ?>
                </ul>
            </div>
        </div>
        <?php */} ?>
        <div id="sidebar-menu" class="sidebar-menu">
            <ul class="nav">
                <?php
                $user_id = User::get_id();
                $badge = array();
                $timer_on = App::counter('project_timer',array('status'=>'1'));
                if($timer_on > 0){ $badge['menu_projects'] = '<b class="badge bg-danger pull-right">'.$timer_on.'<i class="fa fa-refresh fa-spin"></i></b>'; }
                $unread = App::counter('messages',array('user_to'=>User::get_id(),'status' => 'Unread'));
                if($unread > 0){ $badge['menu_messages'] = '<b class="badge bg-primary pull-right">'.$unread.'</b>'; }
                $menus = $this->db->where('access',3)->where('visible',1)->where('parent','')->where('hook','main_menu_staff')->order_by('order','ASC')->get('hooks')->result();
                foreach ($menus as $menu) {
                $sub = $this->db->where('access',3)->where('visible',1)->where('parent',$menu->module)->where('hook','main_menu_staff')->order_by('order','ASC')->get('hooks');
                $perm = TRUE;
                ?>
                <?php if($menu->permission != '') { $perm = User::perm_allowed($user_id, $menu->permission); } ?>
                <?php if($perm){ ?>
                <?php if ($sub->num_rows() > 0) {
                $submenus = $sub->result(); ?>
                <li class="<?php
                    foreach ($submenus as $submenu) {
                        if($page == lang($submenu->name)){echo  "active"; }
                    }
                    ?>">
                    <a href="<?=base_url()?><?=$menu->route?>">
                        <i class="fa <?=$menu->icon?> icon"> <b class="bg-<?=config_item('theme_color');?>"></b></i>
                        <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i></span>
                        <span><?=lang($menu->name)?></span>
                    </a>
                    <ul class="nav lt">
                        <?php foreach ($submenus as $submenu) { ?>
                        <li class="<?php if($page == lang($submenu->name)){echo  "active"; }?>">
                            <a href="<?=base_url()?><?=$submenu->route?>">
                                <?php if (isset($badge[$submenu->module])) { echo $badge[$menu->module]; } ?>
                                <i class="fa <?=$submenu->icon?> icon"> <b class="bg-<?=config_item('theme_color');?>"></b></i>
                                <span><?=lang($submenu->name)?></span>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </li>
                <?php } else { ?>
                <li class="<?php if($page == lang($menu->name)){echo  "active"; }?>">
                    <a href="<?=base_url()?><?=$menu->route?>">
                        <?php if (isset($badge[$menu->module])) { echo $badge[$menu->module]; } ?>
                        <i class="fa <?=$menu->icon?> icon"> <b class="bg-<?=config_item('theme_color');?>"></b></i>
                        <span><?=lang($menu->name)?></span>
                    </a>
                </li>
                <?php } ?>
                <?php } ?>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
            <?php /*<div class="page-wrapper video-blk-right chat-call-placeholder">
                <div class="container">        
                    <div class="row">
                        <div class="col-md-12">
                            <div class="grp-chat-details-blk">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-box video-box text-center">
                                            <div id="profile-info-collapse">
                                                <div id="dismiss">
                                                    <i class="glyphicon glyphicon-arrow-left"></i>
                                                </div>                                                    
                                                <div class="single-profile-details">
                                                        <h3 class="m-b-20" id="fullname_chat">John Doe</h3>
                                                        <div class="user-status-blk">
                                                            <span class="user-img">
                                                                <img class="img-circle" id="user_img_chat" src="" width="104" alt="Admin">
                                                                <span class="status" id="user_status_chat"></span>
                                                            </span>
                                                        </div>
                                                        <hr/>
                                                        <div class="chat-profile-info">
                                                            <ul class="user-det-list text-left">
                                                                <li>
                                                                    <span class="text-left">Username:</span>
                                                                    <span class="pull-right text-muted" id="chat_username">johndoe</span>
                                                                </li>
                                                                <li>
                                                                    <span class="text-left">Email:</span>
                                                                    <span class="pull-right text-muted" id="user_email_chat">johndoe@example.com</span>
                                                                </li>
                                                                <li>
                                                                    <span class="text-left">Phone:</span>
                                                                    <span class="pull-right text-muted" id="user_phone_chat">9876543210</span>
                                                                </li>
                                                            </ul>
                                                        </div>                                                        
                                                    </div>
                                                    <div class="grp-profile-details">
                                                    <h3 id="chatgroup_name"></h3>
                                                    <img class="img img-responsive center-block group-lg-icon" width="140px" src="images/group.svg">
                                                    <hr>
                                                    <h4>Members</h4>
                                                    <div class="project-members m-b-15" >
                                                        <ul class="team-members" id="chatGroup_members">
                                                            <!-- <li>
                                                                <a href="#" data-toggle="tooltip" title="" data-original-title="John Doe"><img src="images/user.jpg" alt="John Doe"></a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-toggle="tooltip" title="" data-original-title="Richard Miles"><img src="images/user.jpg" alt="Richard Miles"></a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-toggle="tooltip" title="" data-original-title="John Smith"><img src="images/user.jpg" alt="John Smith"></a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-toggle="tooltip" title="" data-original-title="Mike Litorus"><img src="images/user.jpg" alt="Mike Litorus"></a>
                                                            </li>
                                                            <li>
                                                                <a href="#" data-toggle="tooltip" title="" data-original-title="John Doe, Richard Miles, John Smith, Mike Ltrorus, Shan lee" class="all-users">+1</a>
                                                            </li> -->
                                                        </ul>
                                                    </div>                                                        
                                                </div>
                                            </div>                                            
                                            <div class="row">
                                                <div class="col-lg-12 col-sm-11">
                                                    <div class="sticky-quicklinks">
                                                                <ul class="sticky list-unstyled m-b-0">
                                                                    <li>
                                                                        <a href="javascript:;" id="profileCollapse" class=""> 
                                                                            <img class="stick-icon effect-fadeout hover-animate img img-responsive" src="<?php echo base_url(); ?>images/man-user.svg" />
                                                                        </a>
                                                                    </li>                                                                    
                                                                    <li onclick="handle_video_panel(0)">
                                                                        <!-- <a href="#" data-toggle="modal" data-target="#live-video-chat" data-backdrop="static" data-keyboard="false"><img class="stick-icon effect-fadeout hover-animate img img-responsive" src="<?php echo base_url(); ?>images/call.svg" /></a> -->
                                                                        <a href="#" ><img class="stick-icon effect-fadeout hover-animate img img-responsive" src="<?php echo base_url(); ?>images/call.svg" /></a>
                                                                    </li>
                                                                    <li  onclick="handle_video_panel(1)">
                                                                        <!-- <a href="#" data-toggle="modal" data-target="#live-video-chat" data-backdrop="static" data-keyboard="false"><img class="stick-icon effect-fadeout hover-animate img img-responsive" src="<?php echo base_url(); ?>images/video-camera.svg" /></a> -->
                                                                        <a href="#"><img class="stick-icon effect-fadeout hover-animate img img-responsive" src="<?php echo base_url(); ?>images/video-camera.svg" /></a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                    <div class="single-profile-chat-conversation"> */ ?>
<?php } ?>
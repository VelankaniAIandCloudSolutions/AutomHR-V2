 <?php  $roles = $this->db->where('branch_id',$this->session->userdata('branch_id'))->get('roles')->result(); ?>
            <div class="chat-main-row">
                <div class="chat-main-wrapper">
                    <div class="col-12 message-view">
                        <div class="chat-window">
                            <div class="chat-header fixed-header">
                                <div class="row">
                                    <div class="col-6">
                                        <h4 class="page-title m-b-0 m-t-5">Contacts</h4>
                                    </div>
                                    <div class="col-6">
                                        <div class="navbar justify-content-end">
                                            <div class="search-box m-t-0" style="float:right;">
                                                    <div class="input-group input-group-sm">
                                                       <input type="text" class="form-control" id="contact_search" onkeyup="contact_search()" placeholder="Search" required="">
                                                        <span class="input-group-append">
                                                            <button class="btn" type="button"><i class="fa fa-search"></i></button>
                                                        </span>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="chat-contents">
                                <div class="chat-content-wrap">
                                    <div class="chat-wrap-inner">
                                        <div class="contact-box clearfix row">
                                            <div class="contact-cat col-sm-4 col-lg-3">
                                            
												<?php
												
												// if(App::is_permit('menu_contacts','create'))
												// {
												?>
                                                <a href="javascript:void(0);" class="btn btn-primary btn-block" data-toggle="modal" data-target="#add_contact"><i class="fa fa-plus"></i> Add Contact</a>
												<?php
												// }
												?>
												<div class="roles-menu">
                                                    <ul class="">
                                                        <li class="active check_status" id="ch_0"><a href="javascript:void(0);" class="role_search" data-id="<?=$r->r_id?>">All</a></li>
															
                                                        
                                                         <?php
                                                          if (!empty($roles)) {
                                                          foreach ($roles as $r) { ?>
                                                            <li class="check_status" id="ch_<?=$r->r_id?>"><a href="#" class="role_search" data-id="<?=$r->r_id?>"><?=ucfirst($r->role)?></a></li>
                                                          <?php } } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="contacts-list col-sm-8 col-lg-9">
                                                
                                            </div>
                                            <div class="contact-alphapets">
                                                <div class="alphapets-inner">
                                                    <a href="#" class="name_search">A</a>
                                                    <a href="#" class="name_search">B</a>
                                                    <a href="#" class="name_search">C</a>
                                                    <a href="#" class="name_search">D</a>
                                                    <a href="#" class="name_search">E</a>
                                                    <a href="#" class="name_search">F</a>
                                                    <a href="#" class="name_search">G</a>
                                                    <a href="#" class="name_search">H</a>
                                                    <a href="#" class="name_search">I</a>
                                                    <a href="#" class="name_search">J</a>
                                                    <a href="#" class="name_search">K</a>
                                                    <a href="#" class="name_search">L</a>
                                                    <a href="#" class="name_search">M</a>
                                                    <a href="#" class="name_search">N</a>
                                                    <a href="#" class="name_search">O</a>
                                                    <a href="#" class="name_search">P</a>
                                                    <a href="#" class="name_search">Q</a>
                                                    <a href="#" class="name_search">R</a>
                                                    <a href="#" class="name_search">S</a>
                                                    <a href="#" class="name_search">T</a>
                                                    <a href="#" class="name_search">U</a>
                                                    <a href="#" class="name_search">V</a>
                                                    <a href="#" class="name_search">W</a>
                                                    <a href="#" class="name_search">X</a>
                                                    <a href="#" class="name_search">Y</a>
                                                    <a href="#" class="name_search">Z</a>
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
           
            <!-- Add Contact Modal -->
            <div class="modal custom-modal fade" id="add_contact" role="dialog" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Contact</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="<?=base_url()?>all_contacts/create_contacts" id="AddContacForm" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label>Role Name <span class="text-danger">*</span></label>
                                        <input type="hidden" name="branch_id" value="<?php echo $this->session->userdata('branch_id');?>">
                                         <select name="roles" class="form-control" id="roles">
                                            <option value="">Select Role</option>
                                          <?php
                                          if (!empty($roles)) {
                                          foreach ($roles as $r) { ?>
                                             <option value="<?=$r->r_id?>"><?=ucfirst($r->role)?></option>
                                          <?php } } ?>
                                          </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="contact_name" id="contact_name">
                                    </div>
                                    <div class="form-group">
                                        <label>Email Address <span id="already_contactname" style="display: none;color:red;">Already Registered Contact Email</span></label>
                                        <input class="form-control" type="email" name="email" id="email">
                                    </div>
                                    <div class="form-group">
                                        <label>Contact Number <span class="text-danger">*</span><span id="already_contact_number" style="display: none;color:red;">Already Registered Contact Number</span></label>
                                        <input class="form-control" type="text" name="contact_number" id="contact_number">
                                    </div>
                                     <div class="form-group">
                                        <label>Image <span class="text-danger">*</span></label>
                                        <input class="form-control" type="file" name="file" id="file">
                                    </div>
                                    <div class="form-group">
                                        <label class="d-block">Status</label>
                                        <div class="status-toggle">
                                            <input type="checkbox" id="contact_status" name="status" class="check" value="1" checked>
                                            <label for="contact_status" class="checktoggle">checkbox</label>
                                        </div>
                                    </div>
                                    <div class="submit-section">
                                        <button class="btn btn-primary submit-btn" id="submit_contact_form" >Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Add Contact Modal -->
           
                <!-- Delete Contact Modal -->
                <?php $all_contacts = $this->db->order_by('id','DESC')->get_where('contacts',array('status'=>1))->result_array();
                foreach ($all_contacts as $key => $contacts) { ?>
                <div class="modal custom-modal fade" id="delete_contact_<?php echo $contacts['id']?>" role="dialog" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                             <!-- <form method="POST" action="<?php echo base_url();?>all_contacts/create_contacts/" id="AddContacForm" enctype="multipart/form-data"> -->
                                <div class="modal-body">
                                    <div class="form-header">
                                        <h3>Delete Contact</h3>
                                        <p>Are you sure want to delete?</p>
                                    </div>
                                    <div class="modal-btn delete-action">
                                        <div class="row">
                                            <div class="col-6">
                                                <a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
                                            </div>
                                            <div class="col-6">
                                                <a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary continue-btn">Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- </form> -->
                        </div>
                    </div>
                </div>
            <?php } ?>
                <!-- /Delete Contact Modal -->
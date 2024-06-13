
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
                                         <select name="roles" class="form-control" id="roles">
                                            <option value="">Select Role</option>
                                          <?php
										  $roles = $this->db->get('roles')->result();
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
                
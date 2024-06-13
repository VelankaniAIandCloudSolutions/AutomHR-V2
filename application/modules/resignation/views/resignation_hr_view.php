<div class="content">
    <div class="row">
        <h3>Employee Resignation Details</h3>
    </div>
    <hr>

    <div class="row ">
        <?php
        $manager_data = $resignation_data['resignation_manger_data'];
        $manager_status = 1;
        $manager_feedback = '';
        $manager_is_notice_period_change = 'no';
        $manager_last_woking_date = $resignation_data['requested_last_working_date'];
        if (!empty($manager_data)) {
            foreach ($manager_data as $manager_data_val) {
                $manager_status = $manager_data_val['status'];
                $manager_feedback = $manager_data_val['feedback'];
                $manager_is_notice_period_change = $manager_data_val['is_notice_period_change'];
                $manager_last_woking_date = $manager_data_val['last_woking_date'];
            }
        }

        $hr_data = $resignation_data['resignation_hr_data'];
        $hr_status = 1;
        $hr_feedback = '';
        $hr_is_notice_period_change = 'no';
        $all_clearance_completed = 'no';
        $hr_last_woking_date = $manager_last_woking_date;
        $assets = array();
        if (!empty($hr_data)) {
            foreach ($hr_data as $hr_data_val) {
                $hr_status = $hr_data_val['status'];
                $hr_feedback = $hr_data_val['feedback'];
                $hr_is_notice_period_change = $hr_data_val['is_agreed_with_manger_last_working_date'];
                if ($hr_data_val['last_woking_date_by_hr'] != "") {
                    $hr_last_woking_date = date("Y-m-d", strtotime($hr_data_val['last_woking_date_by_hr']));
                }
                $all_clearance_completed = $hr_data_val['is_clearence_completed'];
                $assets = json_decode($hr_data_val['assets']);
            }
        }
        ?>
        <div class="card">
            <div class="col-md-12">
                <form autocomplete="off" action="<?php echo base_url('resignation/hr_approval/') . $resignation_data['id']; ?>" id="manager_approval" method="post" enctype="multipart/form-data" data-parsley-validate novalidate>
                    <input type="hidden" name="id" value="<?php echo $resignation_data['id'] ?>">
                    <div class="row">
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label><?= lang('branch') ?> <span class="text-danger">*</span></label>
                                <select onchange="entity_change1(this.value);" class="form-control" style="width:100%;" name="branch_id" id="add_branch" disabled required>
                                    <option value="">Select</option>
                                    <?php
                                    if (!empty($branches)) {
                                        foreach ($branches as $branch1) { ?>
                                            <option value="<?= $branch1['branch_id'] ?>" <?php if ($branch1['branch_id'] == $resignation_data['branch_id']) {
                                                                                                echo "selected";
                                                                                            } ?>><?= $branch1['branch_name'] ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Resigning Employee <span class="text-danger">*</span></label><br>
                                <input type="text" disabled class="form-control" value="<?php echo $resignation_data['fullname'] . '(' . $resignation_data['emp_code'] . ')'; ?>">
                            </div>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Department <span class="text-danger">*</span></label>
                                <select class="form-control" style="width:100%;" name="department_id" id="department_id" disabled onchange="get_designation(this.value); return false;" required>
                                    <option value=""> Choose Department</option>
                                    <?php
                                    $this->load->model("department");
                                    $department_list = $this->department->department_list();
                                    foreach ($department_list as $key => $department_val) {
                                    ?>
                                        <option value="<?php echo $department_val['deptid']; ?>" <?php if ($department_val['deptid'] == $resignation_data['department_id']) {
                                                                                                        echo "selected";
                                                                                                    } ?>><?= ucfirst($department_val['deptname']) ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Designation <span class="text-danger">*</span></label>
                                <select class="form-control" style="width:100%;" name="designation_id" id="designation_id" disabled required>
                                    <option value=""> Choose Designation</option>
                                    <?php
                                    $designation_list = $this->department->designation_list();

                                    //print_r($user); die; 

                                    foreach ($designation_list as $key => $designation_val) {
                                    ?>
                                        <option value="<?php echo $designation_val['id']; ?>" <?php if ($designation_val['id'] == $resignation_data['designation_id']) {
                                                                                                    echo "selected";
                                                                                                } ?>><?= ucfirst($designation_val['designation']) ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Location <span class="text-danger">*</span></label>
                                <input type="text" name="location" id="location" class="form-control" value="<?php echo $resignation_data['location']; ?>" disabled required />
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Date o Joining <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input type="text" name="date_of_joining" id="date_of_joining" value="<?php echo $resignation_data['date_of_joining']; ?>" class="form-control" required disabled>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class='row'>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Exit Request Submitted Date <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input type="text" name="exit_request_submit_date" id="exit_request_submit_date" disabled value="<?php echo $resignation_data['exit_request_submit_date']; ?>" class="form-control datetimepicker" required>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Requested Last Working Date <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input type="text" name="requested_last_working_date" id="requested_last_working_date" value="<?php echo $resignation_data['requested_last_working_date']; ?>" class="form-control datetimepicker" disabled required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='col-md-12'>
                            <div class="form-group">
                                <label>Reason for Resignation <span class="text-danger">*</span></label>
                                <textarea disabled class="form-control" name="reason" id="reason" rows="4" required><?php echo $resignation_data['reason']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class='row'>

                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Notice Period End Date <span class="text-danger">*</span></label>
                                <div class="cal-icon">
                                    <input type="text" disabled value="<?php echo $resignation_data['notice_period_end_date']; ?>" name="notice_period_end_date" id="notice_period_end_date" class="form-control datetimepicker" required>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Personal Email</label>
                                <input type="text" value="<?php echo $resignation_data['personal_email']; ?>" name="personal_email" id="personal_email" class="form-control" readonly />
                            </div>
                        </div>

                    </div>

                    <div class='row'>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Elaborate Reason for Resignation</label>
                                <textarea class="form-control" disabled name="elaborate_reason" id="elaborate_reason"><?php echo $resignation_data['elaborate_reason']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Resignation Attachment</label>
                                <input type="file" name="resignation_attachment" id="resignation_attachment" class="form-control" />

                                <div id='attachment_files'>
                                    <?php
                                    $attachments = $response['attachments'];
                                    if (!empty($attachments)) {
                                        foreach ($attachments as $attachments_val) { ?>
                                            <a href="<?php echo base_url('uploads/resignation_attachment/') . $response['employee'] . '/' . $attachments_val['name']; ?>"> <?php echo $attachments_val['name']; ?>
                                        <?php }
                                    }
                                        ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <h3>Manager Details</h3>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Do you want to change the notice period date of employees?</label><br>
                                <input type="radio" name="change_last_working_date" id="change_last_working_date" value="yes" checked disabled> Yes
                                <input type="radio" name="change_last_working_date" id="change_last_working_date" value="no" disabled> No
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Updated Last Working Date by Manager</label>
                                <div class="cal-icon">
                                    <?php
                                    if ($manager_is_notice_period_change == 'yes') {
                                        $disabled = '';
                                    } else {
                                        $disabled = 'readonly';
                                    }
                                    ?>
                                    <input type="text" disabled <?php echo $disabled; ?> value="<?php echo $manager_last_woking_date; ?>" name="last_woking_date" id="last_woking_date" class="form-control datetimepicker" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Manager Feedback</label><br>
                                <textarea class="form-control" disabled name="feedback" id="feedback" rows="4"><?php echo $manager_feedback; ?></textarea>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Resignation Status</label>
                                <select class='form-control' name="resignation_status" id='resignation_status' disabled>
                                    <option value='1' <?php if ($manager_status == '1') {
                                                            echo "selected";
                                                        } ?>>Pending</option>
                                    <option value='2' <?php if ($manager_status == '2') {
                                                            echo "selected";
                                                        } ?>>Accepted</option>
                                    <option value='3' <?php if ($manager_status == '3') {
                                                            echo "selected";
                                                        } ?>>Rejected</option>
                                    <option value='4' <?php if ($manager_status == '4') {
                                                            echo "selected";
                                                        } ?>>Withdrawn</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!--HR Details  -->
                    <div class='row'>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <h3>HR Details</h3>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Do you agree with manager praposed last working date?</label><br>
                                <input type="radio" name="change_last_working_date_by_hr" id="change_last_working_date_by_hr" onchange="last_working_date(this.value);return false;" value="yes" <?php if($hr_is_notice_period_change=='yes'){ echo " checked";} ?> > Yes
                                <input type="radio" name="change_last_working_date_by_hr" id="change_last_working_date_by_hr" onchange="last_working_date(this.value);return false;" value="no" <?php if($hr_is_notice_period_change=='no'){ echo " checked";} ?>> No
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Last Working Date (As per HR)</label>
                                <div class="cal-icon">
                                    <input type="text" value="<?php echo date('d-m-Y', strtotime($hr_last_woking_date)); ?>" name="last_woking_date_by_hr" id="last_woking_date_by_hr" class="form-control datetimepicker" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>HR Feedback</label><br>
                                <textarea class="form-control" name="hr_feedback" id="hr_feedback" rows="4" required><?php echo $hr_feedback; ?></textarea>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Resignation Status By HR</label>
                                <select class='form-control' name="resignation_status_hr" id='resignation_status_hr'>
                                    <option value='1' <?php if ($hr_status == '1') {
                                                            echo "selected";
                                                        } ?>>Pending</option>
                                    <option value='2' <?php if ($hr_status == '2') {
                                                            echo "selected";
                                                        } ?>>Accepted</option>
                                    <option value='3' <?php if ($hr_status == '3') {
                                                            echo "selected";
                                                        } ?>>Rejected</option>
                                    <option value='4' <?php if ($hr_status == '4') {
                                                            echo "selected";
                                                        } ?>>Withdrawn</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>All the Clearance completed</label><br>
                                <input type="radio" name="all_clearance" id="all_clearance" onchange="last_working_date(this.value);return false;" value="yes" <?php if($all_clearance_completed == 'yes'){echo"checked";}?> > Yes
                                <input type="radio" name="all_clearance" id="all_clearance" onchange="last_working_date(this.value);return false;" value="no" <?php if($all_clearance_completed == 'no'){echo"checked";}?> > No
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Assets</label> <span>(Please check if submitted)</span><br>
                                <ul style="list-style-type: none;">
                                    <li><input type="checkbox" class="assets" name="assets[badge]" id="badge" <?php if($assets->badge =='on'){echo "checked";} ?>  ><label for="badge">Badge</label></li>
                                    <li><input type="checkbox" class="assets" name="assets[uniform]" id="uniform" <?php if($assets->uniform =='on'){echo "checked";} ?> ><label for="uniform">Uniform</label></li>
                                    <li><input type="checkbox" class="assets" name="assets[coat]" id="coat" <?php if($assets->coat =='on'){echo "checked";} ?>><label for="coat">Coat</label></li>
                                    <li><input type="checkbox" class="assets" name="assets[shoes]" id="shoes" <?php if($assets->shoes =='on'){echo "checked";} ?> ><label for="shoes">Shoes</label></li>
                                    <li><input type="checkbox" class="assets" name="assets[apron]" id="apron" <?php if($assets->apron =='on'){echo "checked";} ?> ><label for="apron">Apron</label></li>
                                    <li><input type="checkbox" class="assets" name="assets[knives]" id="knives" <?php if($assets->knives =='on'){echo "checked";} ?>><label for="knives">Knives</label></li>
                                    <li><input type="checkbox" class="assets" name="assets[mobile]" id="mobile" <?php if($assets->mobile =='on'){echo "checked";} ?>><label for="mobile">Mobile</label></li>
                                    <li><input type="checkbox" class="assets" name="assets[idcard]" id="idcard" <?php if($assets->idcard =='on'){echo "checked";} ?>><label for="idcard">ID Card</label></li>
                                    <li><input type="checkbox" class="assets" name="assets[personalemail]" id="personalemail" <?php if($assets->personalemail =='on'){echo "checked";} ?>><label for="personalemail">Personal Email</label></li>
                                    <li><input type="checkbox" class="assets" name="assets[laptop]" id="laptop" <?php if($assets->laptop =='on'){echo "checked";} ?>><label for="laptop">Laptop</label></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <?php
                        $form_disable= '';
                        if($hr_status == "2")
                        {
                            $form_disable = "disabled";
                        } 
                        ?>
                        <input type="submit" <?php echo $form_disable; ?> class="btn btn-primary submit-btn" id="btnHrSave" name="btnHrSave" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function last_working_date(checkbox_val) {
    if (checkbox_val == 'yes') {
        $("#last_woking_date_by_hr").prop("readonly", true);
        // $(".assets").prop("checked", true);
    } else if (checkbox_val == 'no') {
        $("#last_woking_date_by_hr").prop("readonly", false);
        // $(".assets").prop("checked", false);
    }
}


$(document).ready(function() {
    $(".datetimepicker").datepicker({
        dateFormat: 'dd-mm-yy',
        onSelect: function(dateText) {
            // Do something when a date is selected
        }
    });
});

</script>
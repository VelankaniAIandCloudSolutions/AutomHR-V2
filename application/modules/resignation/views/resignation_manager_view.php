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
        ?>
        <div class="card">
            <div class="col-md-12">
                <form autocomplete="off" action="<?php echo base_url('resignation/manager_approval/') . $resignation_data['id']; ?>" id="manager_approval" method="post" enctype="multipart/form-data" data-parsley-validate novalidate>
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
                                    <input type="text" name="date_of_joining" id="date_of_joining" value="<?php echo $resignation_data['date_of_joining']; ?>" class="form-control datetimepicker" required disabled>
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
                                <input type="radio" name="change_last_working_date" id="change_last_working_date" onchange="last_workign_date(this.value);return false;" value="yes" checked> Yes
                                <input type="radio" name="change_last_working_date" id="change_last_working_date" onchange="last_workign_date(this.value);return false;" value="no" > No
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Last Working Date by Manager</label>
                                <div class="cal-icon">
                                    <?php
                                    if ($manager_is_notice_period_change == 'yes') {
                                        $disabled = '';
                                    } else {
                                        $disabled = 'readonly';
                                    }
                                    ?>
                                    <input type="text" <?php echo $disabled; ?> value="<?php echo $manager_last_woking_date; ?>" name="last_woking_date" id="last_woking_date" class="form-control datetimepicker" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Manager Feedback</label><br>
                                <textarea class="form-control" name="feedback" id="feedback" rows="4" required><?php echo $manager_feedback; ?></textarea>
                            </div>
                        </div>
                        <div class='col-md-6'>
                            <div class="form-group">
                                <label>Resignation Status</label>
                                <select class='form-control' name="resignation_status" id='resignation_status'>
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

                    <div class="submit-section">
                    <?php
                        $form_disable= '';
                        if($manager_status == "2")
                        {
                            $form_disable = "disabled";
                        } 
                        ?>
                        <input type="submit" <?php echo $form_disable; ?> class="btn btn-primary submit-btn" id="btnmanagerSave" name="btnmanagerSave" value="Submit">
                    </div>
                    <br<br>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function last_workign_date(checkbox_val)
    {
        if(checkbox_val == 'yes')
        {
            $("#last_woking_date").removeAttr("readonly", "readonly");

        }
        else if(checkbox_val == 'no')
        {
            $("#last_woking_date").attr("readonly", "readonly");
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
<style>
    /* Style for the panel footer */
    .panel-footer {
        overflow-x: auto;
        /* Enable horizontal scrolling if necessary */
    }

    /* Style for the table */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    /* Style for table header cells */
    th {
        background-color: #f2f2f2;
        text-align: left;
    }

    /* Style for table data cells */
    td {
        padding: 8px;
        border: 1px solid #ddd;
    }

    /* Media query for responsiveness */
    @media screen and (max-width: 600px) {

        /* Hide table headers on small screens */
        th {
            display: none;
        }

        /* Adjust table data cells on small screens */
        td {
            display: block;
            width: 100%;
            text-align: left;
        }
    }
</style>

<?php

$branch_lists = $this->db->get_where('branches', array('branch_status !=' => '1'))->result_array();

$this->load->helper('app');
$template_group = isset($_GET['group']) ? $_GET['group'] : 'user';
switch ($template_group) {
    case "bugs":
        $default = "bug_assigned";
        break;
    case "extra":
        $default = "estimate_email";
        break;
    case "invoice":
        $default = "invoice_message";
        break;
    case "project":
        $default = "project_assigned";
        break;
    case "task":
        $default = "task_created";
        break;
    case "ticket":
        $default = "ticket_client_email";
        break;
    case "user":
        $default = "activate_account";
        break;
    case "signature":
        $default = "email_signature";
        break;
    case "attendance_regularization":
        $default = "attendance_regularization";
        break;
    case "attendance_regularization_approve":
        $default = "attendance_regularization_approve";
        break;
    case "offer_send":
        $default = "offer_send";
        break;
    case "birthday":
        $default = "birthday";
        break;
    case "anniversary":
        $default = "anniversary";
        break;
}
$setting_email = isset($_GET['email']) ? $_GET['email'] : $default;
$email['bugs'] = array("bug_assigned", "bug_status", "bug_comment", "bug_file", "bug_reported");
$email['extra'] = array("estimate_email", "message_received");
$email['invoice'] = array("invoice_message", "invoice_reminder", "payment_email");
$email['project'] = array("project_created", "project_assigned", "project_comment", "project_complete", "project_file", "project_updated");
$email['task'] = array("task_created", "task_assigned", "task_updated", "task_comment");

$email['ticket'] = array("ticket_client_email", "ticket_closed_email", "ticket_reply_email", "ticket_staff_email", "auto_close_ticket", "ticket_reopened_email");
$email['user'] = array("activate_account", "change_email", "forgot_password", "registration", "reset_password");
$email['signature'] = array("email_signature");
$email['birthday'] = array("birthday");
$email['anniversary'] = array("anniversary");

$attributes = array('class' => 'bs-example form-horizontal');
echo form_open('settings/templates?settings=templates&group=' . $template_group . '&email=' . $setting_email, $attributes);
?>
<div class="p-0">
    <div class="col-lg-12 p-0">
        <div class="panel panel-white">
            <div class="panel-heading">
                <h3 class="panel-title p-5"><?= lang('email_templates') ?></h3>
            </div>
            <div class="panel-body">
                <ul class="nav nav-tabs nav-tabs-solid m-b-20">
                    <?php foreach ($email[$template_group] as $temp) :
                        $lang = $temp;
                        switch ($temp) {
                            case "registration":
                                $lang = 'register_email';
                                break;
                            case "bug_comment":
                                $lang = 'bug_comments';
                                break;
                            case "project_file":
                                $lang = 'project_files';
                                break;
                            case "project_comment":
                                $lang = 'project_comments';
                                break;
                            case "project_assigned":
                                $lang = 'project_assignment';
                                break;
                            case "task_assigned":
                                $lang = 'task_assignment';
                                break;
                            case "email_signature":
                                $lang = 'email_signature';
                                break;
                            case "attendance_regularization":
                                $lang = "attendance_regularization";
                                break;
                            case "attendance_regularization_approve":
                                $lang = "attendance_regularization_approve";
                                break;
                            case "offer_send":
                                $lang = "offer_send";
                                break;
                            case "birthday":
                                $lang = "birthday";
                                break;
                            case "anniversary":
                                $lang = "anniversary";
                                break;
                        } ?>
                        <li class="<?php if ($setting_email == $temp) {
                                        echo "active";
                                    } ?> ">
                            <a href="<?= base_url() ?>settings/?settings=templates&group=<?= $template_group; ?>&email=<?= $temp; ?>"><?= lang($lang) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <input type="hidden" name="email_group" value="<?= $setting_email; ?>">
                <input type="hidden" name="return_url" value="<?= base_url() ?>settings/?settings=templates&group=<?= $template_group; ?>&email=<?= $setting_email; ?>">

                <?php if ($template_group === 'offer_send') : ?>
                    <div class="form-group">
                        <label class="col-lg-12"><?= lang('branch') ?></label>
                        <div class="col-lg-12">
                            <select name="branch_id" id="branch_id" class="form-control" onchange="get_offer_letter_body(this.value)">
                                <?php if (!empty($branch_lists)) {
                                    foreach ($branch_lists as $branch_lists_val) { ?>
                                        <option value="<?php echo $branch_lists_val['branch_id']; ?>"><?php echo $branch_lists_val['branch_name']; ?></option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($template_group != 'signature') : ?>
                    <div class="form-group">
                        <label class="col-lg-12"><?= lang('subject') ?></label>
                        <div class="col-lg-12">
                            <input class="form-control " name="subject" value="<?php echo App::email_template($setting_email, 'subject'); ?>" />
                        </div>
                    </div>
                <?php endif; ?>


                <div class="form-group">
                    <label class="col-lg-12"><?= lang('mail_content') ?></label>
                    <div class="col-lg-12">
                        <textarea class="form-control foeditor-550" id='email_template' name="email_template"></textarea>
                    </div>
                </div>

                <?php if ($template_group === 'offer_send') : ?>
                    <div class="form-group">
                        <label class="col-lg-12"><?= lang('offer_letter_attachment') ?></label>
                        <div class="col-lg-12">
                            <textarea class="form-control foeditor-500" id='offer_letter_attachment' name="offer_letter_attachment"></textarea>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="text-center m-t-30">
                    <button type="submit" class="btn btn-primary btn-lg"><?= lang('save_changes') ?></button>
                </div>
            </div>
            <div class="panel-footer">
                <!-- <strong><?= lang('template_tags') ?></strong> -->
                <div class="row">

                    <?php
                    $tags = get_tags($setting_email);
                    asort($tags);
                    $count = count($tags); ?>
                    <div class="col-md-12">
                        <th colspan='3'><?= lang('template_tags') . '( Total Tags = ' . $count . ')' ?></th>
                    </div>

                    <?php for ($i = 0; $i < $count; $i += 2) { // Increment by 3 for each row
                        echo '<div class="col-md-12">';
                        for ($j = $i; $j < $i + 2 && $j < $count; $j++) { // Loop for 3 cells or until the end of tags
                            echo '<div class="col-md-6"><strong>{' . $tags[$j] . '}</strong></div>';
                        }
                        echo '</div>';
                    }
                    ?>
                </div>


            </div>
        </div>
    </div>
</div>
</form>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Your function code here
        get_offer_letter_body($("#branch_id").val());
    });

    function get_offer_letter_body(branch_id = '') {
        $.ajax({
            url: '<?php echo base_url("settings/get_offer_letter_body/"); ?>' + branch_id,
            type: "POST",
            dataType: 'json',
            data: {
                branch_id: branch_id
            },
            success: function(response) {
                if (response != "") {
                    $('#branch_id').val(response.branch_id);
                    $('input[name="subject"]').val(response.subject);
                    $('#email_template').summernote('code', response.template_body);
                    $('#offer_letter_attachment').summernote('code', response.offer_letter_attachment);
                } else if (response == "0") {
                    $('#branch_id').val(branch_id);
                    $('input[name="subject"]').val('');
                    $('#email_template').summernote('code', '');
                    $('#offer_letter_attachment').summernote('code', '');
                } else {
                    console.log(response);
                }
            }
        });
    }
</script>
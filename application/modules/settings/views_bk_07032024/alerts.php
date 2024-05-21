<div class="row">
    <!-- Start Form -->
	<div class="col-lg-12">
		<div class="panel panel-white">
			<div class="panel-heading">
				<h3 class="panel-title"><?=lang('alert_settings')?></h3>
			</div>
			<div class="panel-body">
				<?php
				$attributes = array('class' => 'bs-example form-horizontal','data-validate'=>'parsley');
				echo form_open('settings/update', $attributes); ?>
				<?php echo validation_errors(); ?>
					<input type="hidden" name="settings" value="<?=$load_setting?>">
					<div class="form-group text-danger disable-emailbox">
						<label class="col-lg-5 control-label" 
						data-toggle="tooltip" data-title="DISABLE ALL EMAILS" data-placement="right"><?=lang('disable_emails')?></label>
						<div class="col-lg-7">
							<label class="switch">
								<input type="hidden" value="off" name="disable_emails" />
								<input type="checkbox" <?php if(config_item('disable_emails') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="disable_emails">
								<span></span>
							</label>
						</div>
					</div>
					<hr>
					<div class="form-group">
						<label class="col-lg-5 control-label" 
						data-toggle="tooltip" data-title="An email containing user login credentials will be sent to new users" data-placement="right"><?=lang('email_account_details')?></label>
						<div class="col-lg-7">
							<label class="switch">
								<input type="hidden" value="off" name="email_account_details" />
								<input type="checkbox" <?php if(config_item('email_account_details') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="email_account_details">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-5 control-label" data-toggle="tooltip" data-title="Send email to admins when a new payment is received" data-placement="right" ><?=lang('notify_payment_received')?></label>
						<div class="col-lg-7">
							<label class="switch">
								<input type="hidden" value="off" data-toggle="tooltip" name="notify_payment_received" />
								<input type="checkbox" <?php if(config_item('notify_payment_received') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_payment_received">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-5 control-label"  data-toggle="tooltip" data-title="Send email to admins/staff when a new ticket created" data-placement="right"><?=lang('email_staff_tickets')?></label>
						<div class="col-lg-7">
							<label class="switch">
								<input type="hidden" value="off" name="email_staff_tickets" />
								<input type="checkbox" <?php if(config_item('email_staff_tickets') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="email_staff_tickets">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-5 control-label"  data-toggle="tooltip" data-title="Notify a user when a bug is assigned to them." data-placement="right"><?=lang('notify_bug_assignment')?></label>
						<div class="col-lg-7">
							<label class="switch">
								<input type="hidden" value="off" name="notify_bug_assignment" />
								<input type="checkbox" <?php if(config_item('notify_bug_assignment') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_bug_assignment">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-5 control-label"  data-toggle="tooltip" data-title="Send email to reporter/staff when a new bug comment is received" data-placement="right"><?=lang('notify_bug_comments')?></label>
						<div class="col-lg-7">
							<label class="switch">
								<input type="hidden" value="off" name="notify_bug_comments" />
								<input type="checkbox" <?php if(config_item('notify_bug_comments') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_bug_comments">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-5 control-label"  data-toggle="tooltip" data-title="Send email to reporter/staff when bug status changed" data-placement="right"><?=lang('notify_bug_status')?></label>
						<div class="col-lg-7">
							<label class="switch">
								<input type="hidden" value="off" name="notify_bug_status" />
								<input type="checkbox" <?php if(config_item('notify_bug_status') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_bug_status">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-5 control-label"  data-toggle="tooltip" data-title="Send email to project team when assigned to a project" data-placement="right"><?=lang('notify_project_assignments')?></label>
						<div class="col-lg-7">
							<label class="switch">
								<input type="hidden" value="off" name="notify_project_assignments" />
								<input type="checkbox" <?php if(config_item('notify_project_assignments') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_project_assignments">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-5 control-label"  data-toggle="tooltip" data-title="Send email when project receives a new comment" data-placement="right"><?=lang('notify_project_comments')?></label>
						<div class="col-lg-7">
							<label class="switch">
								<input type="hidden" value="off" name="notify_project_comments" />
								<input type="checkbox" <?php if(config_item('notify_project_comments') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_project_comments">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-5 control-label"  data-toggle="tooltip" data-title="Send email to client primary contact/staff when a new file is uploaded" data-placement="right"><?=lang('notify_project_files')?></label>
						<div class="col-lg-7">
							<label class="switch">
								<input type="hidden" value="off" name="notify_project_files" />
								<input type="checkbox" <?php if(config_item('notify_project_files') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_project_files">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-5 control-label"  data-toggle="tooltip" data-title="Send email to task team when assigned a task" data-placement="right"><?=lang('notify_task_assignments')?></label>
						<div class="col-lg-7">
							<label class="switch">
								<input type="hidden" value="off" name="notify_task_assignments" />
								<input type="checkbox" <?php if(config_item('notify_task_assignments') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_task_assignments">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-5 control-label"  data-toggle="tooltip" data-title="Send email to client/task team when a new task is created" data-placement="right"><?=lang('notify_task_created')?></label>
						<div class="col-lg-7">
							<label class="switch">
								<input type="hidden" value="off" name="notify_task_created" />
								<input type="checkbox" <?php if(config_item('notify_task_created') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_task_created">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-5 control-label"  data-toggle="tooltip" data-title="Send email to recipient when a new message is received" data-placement="right"><?=lang('notify_message_received')?></label>
						<div class="col-lg-7">
							<label class="switch">
								<input type="hidden" value="off" name="notify_message_received" />
								<input type="checkbox" <?php if(config_item('notify_message_received') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_message_received">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-5 control-label"  data-toggle="tooltip" data-title="Send email to client email when a new project is opened" data-placement="right"><?=lang('notify_project_opened')?></label>
						<div class="col-lg-7">
							<label class="switch">
								<input type="hidden" value="off" name="notify_project_opened" />
								<input type="checkbox" <?php if(config_item('notify_project_opened') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_project_opened">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-5 control-label"  data-toggle="tooltip" data-title="Send email to bug reporter when a new bug is posted" data-placement="right"><?=lang('notify_bug_reporter')?></label>
						<div class="col-lg-7">
							<label class="switch">
								<input type="hidden" value="off" name="notify_bug_reporter" />
								<input type="checkbox" <?php if(config_item('notify_bug_reporter') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_bug_reporter">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-5 control-label"  data-toggle="tooltip" data-title="Send email to reporter/staff when a ticket is replied to" data-placement="right"><?=lang('notify_ticket_reply')?></label>
						<div class="col-lg-7">
							<label class="switch">
								<input type="hidden" value="off" name="notify_ticket_reply" />
								<input type="checkbox" <?php if(config_item('notify_ticket_reply') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_ticket_reply">
								<span></span>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-5 control-label"  data-toggle="tooltip" data-title="Send email to ticket reporter when ticket closed" data-placement="right"><?=lang('notify_ticket_closed')?></label>
						<div class="col-lg-7">
							<label class="switch">
								<input type="hidden" value="off" name="notify_ticket_closed" />
								<input type="checkbox" <?php if(config_item('notify_ticket_closed') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_ticket_closed">
								<span></span>
							</label>
						</div>
					</div>
					 <div class="form-group">
						<label class="col-lg-5 control-label"  data-toggle="tooltip" data-title="Send email to staff or client when ticket re-opened" data-placement="right"><?=lang('notify_ticket_reopened')?></label>
						<div class="col-lg-7">
							<label class="switch">
								<input type="hidden" value="off" name="notify_ticket_reopened" />
								<input type="checkbox" <?php if(config_item('notify_ticket_reopened') == 'TRUE'){ echo "checked=\"checked\""; } ?> name="notify_ticket_reopened">
								<span></span>
							</label>
						</div>
					</div>
					<div class="text-center m-t-30">
						<button type="submit" class="btn btn-primary btn-lg"><?=lang('save_changes')?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
    <!-- End Form -->
</div>
<?php $info = Ticket::view_by_id($id); ?>
<div class="content">

<div class="row">
	<div class="col-lg-8">

	<!-- Page Header -->
	<div class="page-header mt-0">
		<div class="row align-items-center">
			<div class="col-6">
				<h4 class="page-title"><?=lang('edit_ticket')?></h4>
				<ul class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
					<li class="breadcrumb-item"><a href="<?=base_url()?>tickets"><?=lang('tickets')?></a></li>
					<li class="breadcrumb-item active"><?=lang('edit_ticket')?></li>
				</ul>
			</div>
			<div class="col-6 text-right m-b-20">
				<a href="<?=base_url()?>tickets/view/<?=$info->id?>" data-original-title="<?=lang('view_details')?>" data-bs-toggle="tooltip" data-placement="bottom" class="btn add-btn"><i class="fa fa-info-circle"></i> <?=lang('ticket_details')?></a>
			</div>
		</div>
	</div>
	<!-- /Page Header -->
	
		<div class="card-box">
	<h4 class="mb-3"><?=lang('ticket_details')?> - <?=$info->ticket_code?></h4>
	
<!-- Start ticket form -->
<!-- <?php echo $this->session->flashdata('form_error'); ?> -->

	<?php 
			 $attributes = array('class' => 'bs-example <!--form-horizontal-->','id'=>'ticketEditForm');
          echo form_open_multipart(base_url().'tickets/edit/',$attributes);
           ?>
			 
			 <input type="hidden" name="id" value="<?=$info->id?>">

			    <div class="form-group">
					<div class="">
					<label class=""><?=lang('ticket_code')?> <span class="text-danger">*</span></label>
					<div class="">
						<input type="text" class="form-control" value="<?=$info->ticket_code?>" name="ticket_code">
					</div>
					</div>
				</div>

				<div class="form-group">
					<div class="">
						<label class=""><?=lang('subject')?> <span class="text-danger">*</span></label>
						<div class="">
							<input type="text" class="form-control" value="<?=$info->subject?>" name="subject">
						</div>
					</div>
				</div>
				
				<div class="form-group">
				<div class="">
				<label class=""><?=lang('priority')?> <span class="text-danger">*</span> </label>
				<div class="">
					<div class="m-b"> 
					<select name="priority" class="form-control" >
					<option value="<?=$info->priority?>"><?=lang('use_current')?></option>
					<?php 
					$priorities = $this->db->order_by('hour','DESC')->get('priorities')->result();
						foreach ($priorities as $p): ?>
					<option value="<?=$p->id?>" <?php echo($info->priority == $p->id)?"selected":"";?>><?=$p->priority;?></option>
					<?php endforeach; ?>
					</select> 
					</div> 
				</div>
			</div>
			</div>

			 <div class="form-group">
				 <div class="">
					<label class=""><?=lang('department')?> <span class="text-danger">*</span></label>
					<div class="">
						<div class="m-b"> 
						<select name="department" class="form-control" >
						<?php 
						$departments = App::get_by_where('departments',array('deptid >'=>'0'));
						// $departments = $this->db->where_in('deptid',[1,3,9,10])->from('departments')->get()->result();

							foreach ($departments as $d): ?>
						<option value="<?=$d->deptid?>"<?=($info->department == $d->deptid ? ' selected="selected"' : '')?>><?=strtoupper($d->deptname)?></option>
						<?php endforeach;  ?>
						</select> 
						</div> 
					</div>
				</div>
			</div>
<?php //if (User::is_admin()) { ?>

			<!-- <div class="form-group">
				<div class="">
					<label class=""><?=lang('reporter')?> <span class="text-danger">*</span> </label>
					<div class="">
						<div class="m-b"> 
						<select class="select2-option form-control" name="reporter" >
						<optgroup label="<?=lang('users')?>"> 
						<?php foreach (User::all_users() as $user): ?>
						<option value="<?=$user->id?>"<?=($info->reporter == $user->id ? ' selected="selected"' : '')?>><?php echo User::displayName($user->id); ?></option>
						<?php endforeach; ?>
						</optgroup> 
						</select> 
						</div> 
					</div>
				</div>
			</div> -->
		<?php //} ?>

			<div class="form-group">
			<div class="">
				<label class=""><?=lang('ticket_message')?> <span class="text-danger">*</span></label>
				<div class="">
				<textarea name="body" class="form-control  foeditor foeditor-ticket-message"><?=strip_tags($info->body)?></textarea>
				<div class="">
				<div class="col-md-6">
				<label id="editTicket_message_error" class="error display-none" style="position:inherit;top:0;font-size:15px;">Message must not empty</label>
				</div>
				</div>
				</div>
				</div>
				</div>

			<div id="file_container">
				<div class="form-group">
				<div class="">
				<label class=""><?=lang('attachment')?></label>
				<div class="">
					<input type="file" class="form-control" name="ticketfiles[]">
				</div>
				</div>
				</div>

			</div>
			<div class="row">
				<div class="col-lg-3">
				</div>
				<div class="col-lg-9">
					<a href="javascript:void(0);" class="btn btn-primary btn-sm" id="add-new-file"><?=lang('upload_another_file')?></a>
					<!-- <a href="javascript:void(0);" class="btn btn-danger btn-sm" id="clear-files" style="margin-left:6px;"><?=lang('clear_files')?></a> -->
				</div>
			</div>
			<div class="submit-section">
				<button type="submit" class="btn btn-primary submit-btn" id="tickets_edit_ticket"><?=lang('save_changes')?></button>
				<a href="<?=base_url().'tickets';?>"><button class="btn btn-danger submit-btn" type="button" id=""><?=lang('cancel')?></button></a>
			</div>
		</form>



		<!-- End ticket -->
		
</div>

</div>
</div>
</div>

<!-- End edit ticket -->




<script src="<?=base_url()?>assets/js/jquery-2.2.4.min.js"></script>
<script type="text/javascript">
        $('#clear-files').click(function(){
            $('#file_container').html(
                "<div class='form-group'>" +
                "<div class='row'>" +
                    "<label class='col-lg-3 control-label'></label>" +
                    "<div class='col-lg-9'>" +
                    "<input class='form-control' type='file' name='ticketfiles[]'>" +
                    "</div></div></div>"
            );
        });

        $('#add-new-file').click(function(){
            $('#file_container').append(
                "<div class='form-group inputFormRow'>" +
                 "<div class='row'>" +
                    "<label class='col-lg-12 control-label'><?=lang('attachment')?></label>" +
                    "<div class='col-lg-12'>" +
                    "<label class='btn btn-default btn-choose'>Choose File</label><input type='file' class='form-control' name='ticketfiles[]'>" +
                    "</div><button id='removeRow' type='button' class='btn btn-danger'>Remove</button></div>"
            );
        });
        $(document).on('click', '#removeRow', function () {
	        $(this).closest('.inputFormRow').remove();
	    });
    </script>


		</section>



<!-- end -->
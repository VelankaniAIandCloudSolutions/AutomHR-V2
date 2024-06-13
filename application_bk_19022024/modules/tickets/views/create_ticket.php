<!-- Start -->
<div class="content">
<div class="row">
<div class="col-sm-12">
		<!-- Page Header -->
	 <div class="page-header mt-0">
		<div class="row">
			<div class="col-md-8">
				<h4 class="page-title"><?=lang('create_ticket')?></h4>
				<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?=base_url()?>"><?=lang('dashboard')?></a></li>
						<li class="breadcrumb-item"><a href="<?=base_url()?>tickets"><?=lang('tickets')?></a></li>
					<li class="breadcrumb-item active"><?=lang('create_ticket')?></li>
				</ul>
			</div>
		</div>
	</div>
	<!-- /Page Header -->
	<div class="row">
		<div class="col-lg-8">
			<div class="card-box ">
			<h4 class="mb-3"><?=lang('ticket_details')?></h4>
			<?php echo $this->session->flashdata('form_error'); ?>

			<?php //if(isset($_GET['dept'])){
			 $attributes = array('class' => 'bs-example <!--form-horizontal-->','id'=>'ticketCreateForm');
          echo form_open_multipart(base_url().'tickets/add/?dept='.$_GET['dept'],$attributes);
           ?>           
			 <!-- <input type="hidden" name="department" value="<?=$_GET['dept']?>"> -->
			 <!--select department-->
				<div class="form-group">
				<label class=""><?=lang('department')?> <span class="text-danger">*</span></label>
					<!-- <input type="text" class="form-control" value="<?php echo Ticket::generate_code(); ?>" name="ticket_code" readonly="readonly"> -->
					<select onchange="GetAssignee();" id="department" name="department" class="form-control" required>
						<option value=""><?php echo lang('select_department');?></option>
					<?php
					// $departments = App::get_by_where('departments',array('deptid >'=>'0'));
					// $departments = $this->db->where_in('deptid',[1,3,9,10])->from('departments')->get()->result();
					$departments = App::get_by_where('departments',array('deptid >'=>'0'));

					$departments = $this->db->where('deptid >=',0)->from('departments')->get()->result();
					foreach ($departments as $d): ?>
					<option value="<?=$d->deptid?>"><?=strtoupper($d->deptname)?></option>
					<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group ">
				<label class="">Assignee<span class="text-danger">*</span></label>
					<!-- <input type="text" class="form-control" value="<?php echo Ticket::generate_code(); ?>" name="ticket_code" readonly="readonly"> -->
					<div class="assignee_div"> 
					<select class="select2-option form-control assignee" style="width:100%;" name="assignee" id="assignee" >
						<option value=""  disabled>Assignee</option>
					</select>
					</div>
				</div>
				
				<!--Select department-->
			    <div class="form-group">
			    <div class="">
				<label class=""><?=lang('ticket_code')?> <span class="text-danger">*</span></label>
				<div class="">
					<input type="text" class="form-control" value="<?php echo Ticket::generate_code(); ?>" name="ticket_code" readonly="readonly">
				</div>
				</div>
				</div>

				<div class="form-group">
				<div class="">
				<label class=""><?=lang('subject')?> <span class="text-danger">*</span></label>
				<div class="">
					<input type="text" class="form-control" placeholder="<?=lang('sample_ticket_subject')?>" name="subject">
				</div>
				</div>
				</div>
				<?php if (User::is_admin()) { ?>

				<!-- <div class="form-group">
				<div class="">
				<label class=""><?=lang('reporter')?> <span class="text-danger">*</span> </label>
				<div class="">
					<div class="m-b">
						<select class="form-control" name="reporter" >
							<optgroup label="<?=lang('users')?>">
							<?php foreach (User::all_users() as $u): ?>
							<option value="<?=$u->id?>"><?=User::displayName($u->id);?></option>
							<?php endforeach; ?>
							</optgroup>
						</select>
					</div>
				</div>
			</div>
			</div> -->
			<?php } ?>



				<div class="form-group">
				<div class="">
				<label class=""><?=lang('priority')?> <span class="text-danger">*</span> </label>
				<div class="">
					<div class="m-b">
					<select name="priority" class="form-control">
					<?php
					$priorities = $this->db->order_by('hour','DESC')->get('priorities')->result();
					foreach ($priorities as $p): ?>
					<option value="<?=$p->id?>"><?php echo $p->priority?></option>
					<?php endforeach; ?>
					</select>
					</div>
				</div>
			</div>
			</div>

			<div class="form-group">
			<div class="">
				<label class=""><?=lang('ticket_message')?> <span class="text-danger">*</span></label>
				<div class="">
				<textarea name="body" class="form-control foeditor foeditor-ticket-message" placeholder="<?=lang('message')?>"><?php echo set_value('body');?></textarea>
				<div class="">
				<div class="col-md-6">
				<label id="addticket_message_error" class="error display-none" style="position:inherit;top:0;font-size:15px;">Message must not empty</label>
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
				<div class="col-lg-12">
					<a href="javascript:void(0);" class="btn btn-primary btn-sm" id="add-new-file"><?=lang('upload_another_file')?></a>
					<!-- <a href="javascript:void(0);" class="btn btn-danger btn-sm" id="clear-files" style="margin-left:6px;"><?=lang('clear_files')?></a> -->
				</div>
			</div>



			<?php
			$dept = isset($_GET['dept']) ? $_GET['dept'] : 0;
		$additional = $this->db->where(array('deptid'=> $dept))->get("fields")->result_array();
if (is_array($additional) && !empty($additional))
{
	foreach ($additional as $item)
	{
		$label = ($item['label'] == NULL) ? $item['name'] : $item['label'];
		echo '<div class="form-group">';
		echo '<div class="row">';
		echo ' <label class="col-lg-3 control-label"> ' .$label. '</label>';
		echo ' <div class="col-lg-9">';
		if ($item['type'] == 'text')
		{
			echo ' <input type="text" class="form-control" name="' . $item['uniqid'] . '">  ';
		}
		echo ' </div>';
		echo ' </div>';
		echo ' </div>';
	}


}
?>

<div class="submit-section">
<button type="submit" class="btn btn-primary submit-btn" id="tickets_create_ticket">
<?=lang('create_ticket')?></button>
</div>

</div>

		</form>

		<?php /*}else{
			/*
			$attributes = array('class' => 'bs-example form-horizontal','id'=>'ticketSelectDept');
          echo form_open(base_url().'tickets/add',$attributes); ?>

          <div class="form-group">
          <div class="row">
				<label class="col-lg-2 control-label"><?=lang('department')?> <span class="text-danger">*</span> </label>
				<div class="col-lg-6">
					<div class="m-b">
					<select name="dept" class="form-control select" required>
						<option value=""><?php echo lang('select_department');?></option>
					<?php
					$departments = App::get_by_where('departments',array('deptid >'=>'0'));
					foreach ($departments as $d): ?>
					<option value="<?=$d->deptid?>"><?=strtoupper($d->deptname)?></option>
					<?php endforeach; ?>
					</select>
					</div>
				</div>

				<?php if (User::is_admin()) { ?>
				<div class="col-lg-4">
				<a href="<?=base_url()?>settings/?settings=departments" class="btn btn-info add-btn float-left" data-bs-toggle="tooltip" title="<?=lang('departments')?>"><i class="fa fa-plus"></i> <?=lang('departments')?></a>
				</div>
				<?php } ?>


			</div>
			</div>
	<div class="submit-section">
		<button type="submit" class="btn btn-primary submit-btn" id="ticket_select_dept"><?=lang('select_department')?></button>
	</div>
</form>
		<?php }*/ ?>
</div>
</div>
</div>
</div>

<!-- End create ticket -->

<script src="<?=base_url()?>assets/js/jquery-2.2.4.min.js"></script>
<script type="text/javascript">


function GetAssignee()
{
	var department=$("#department").val();
	$.ajax({
        type: "POST",
        url: base_url + 'tickets/GetAssignee/',
        data:  {department:department},
        success: function (data) { 
		 // $('#assignee_div').modal('show');
					// console.log(data);return false;
					$('.assignee').html(data);          
		}
	});
}
        $('#clear-files').click(function(){
            $('#file_container').html(
                "<div class='form-group'>" +
                "<div class='row'>" +
                    "<label class='col-lg-3 control-label'><?=lang('attachment')?></label>" +
                    "<div class='col-lg-9'>" +
                    "<input type='file' class='form-control' name='ticketfiles[]'>" +
                    "</div></div></div>"
            );
        });

        $('#add-new-file').click(function(){
            $('#file_container').append(
                "<div class='form-group inputFormRow'>" +
                "<div class='row'>" +
                    "<label class='col-lg-12 control-label'><?=lang('attachment')?></label>" +
                    "<div class='col-lg-12'>" +
                    "<input type='file' class='form-control' name='ticketfiles[]'>" +
                    "</div></div><button id='removeRow' type='button' class='btn btn-danger'>Remove</button></div>"
            );
        });
        $(document).on('click', '#removeRow', function () {
	        $(this).closest('.inputFormRow').remove();
	    });
		
		
		
		
    </script>


		</div>
		</div>
		</div>



<!-- end -->

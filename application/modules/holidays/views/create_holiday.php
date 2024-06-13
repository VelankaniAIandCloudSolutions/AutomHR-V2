<div class="content">
   <div class="row">
	   <div class="col-md-6 col-md-offset-3">
			<h4 class="page-title"><?='Create Holiday';?></h4>
			<?php $attributes = array('class' => 'bs-example','id'=> 'employeeCreateHoliday'); echo form_open(base_url().'holidays/add',$attributes); ?> 
				<div class="form-group">
					<label>Holiday Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control" value="" name="holiday_title">
				</div>
				<div class="form-group">
					<label>Holiday Date <span class="text-danger">*</span></label>
					<input class="datepicker-input form-control" readonly size="16" type="text"  value="" name="holiday_date" data-date-format="dd-mm-yyyy" > 
				</div>
				<div class="form-group">
					<label>Entity<span class="text-danger">*</span> </label>
					<?php $branches = $this->db->where('branch_status','0')->get('branches')->result();?>
					<select multiple class="select2-option form-control" style="width:100%;" name="branch[]" id="add_branch" required>
						<option value="" ></option>
							<?php
							if(!empty($branches))	{
								foreach ($branches as $branch1){ ?>
									<option value="<?=$branch1->branch_id?>"><?=$branch1->branch_name?></option>
								<?php } 
							} ?>
					</select>					
				</div>
				<div class="form-group">
					<input  type="checkbox"  value="1" name="holiday_national" > 
					<?php echo lang('is_national_holday')?>
				</div>
				<div class="form-group">
					<label>Holiday Description <span class="text-danger">*</span></label>
					<textarea class="form-control" name="holiday_description"></textarea>
				</div>
				<div class="m-t-20 text-center">
					<button class="btn btn-primary" id="employee_create_holiday">Create Holiday</button>
					<a href="<?php echo base_url().'holidays';?>" >
						<button class="btn btn-danger" type="button"> Cancel </button>
					</a>
				</div>
			</form>
	   </div>
   </div>
</div>
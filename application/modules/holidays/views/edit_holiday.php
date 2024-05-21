<div class="content">
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<h4 class="page-title"><?='Edit Holiday';?></h4>
			<?php  
			 if(!isset($holidays_det) || empty($holidays_det)){ redirect(base_url().'holidays');} 
			 $attributes = array('class' => 'bs-example','id'=> 'employeeEditHoliday');
			echo form_open(base_url().'holidays/edit',$attributes); ?> 
				<div class="form-group">
					<label>Holiday Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control" value="<?=$holidays_det[0]['title']?>" name="holiday_title">
				</div>
				<div class="form-group">
					<label>Holiday Date <span class="text-danger">*</span></label>
					<input class="datepicker-input form-control" readonly type="text"  value="<?=date('d-m-Y',strtotime($holidays_det[0]['holiday_date']))?>" name="holiday_date" data-date-format="dd-mm-yyyy" >
					
				</div>
				<div class="form-group">
					<label>Entity<span class="text-danger">*</span> </label>
					<?php $branches1 = $this->db->where('branch_status','0')->get('branches')->result();?>
					<select  class="select2-option form-control" style="width:100%;" name="branch[]" id="add_branch" required multiple>
							<?php
							if(!empty($branches1))	{
								foreach ($branches1 as $branch1){ ?>
									<option value="<?=$branch1->branch_id?>" <?=(in_array($branch1->branch_id,$branches))?'selected':''?>><?=$branch1->branch_name?></option>
								<?php } 
							} ?>
					</select>					
				</div>
				<div class="form-group">
					<input  type="checkbox"  value="1" name="holiday_national" <?=(!empty($holidays_det[0]['holiday_national']))?'checked':''?>> 
					<?php echo lang('is_national_holday')?>
				</div>
				<div class="form-group">
					<label>Holiday Description <span class="text-danger">*</span></label>
					<textarea class="form-control" name="holiday_description"> <?=$holidays_det[0]['description']?></textarea>
				</div>
				<div class="m-t-20 text-center">
					<input type="hidden" name="holiday_tbl_id" value="<?=$holidays_det[0]['id']?>">
					<button class="btn btn-primary" id="employee_edit_holiday"> Update Holiday</button>
					<a href="<?php echo base_url().'holidays';?>" >
						<button class="btn btn-danger" type="button"> Cancel </button>
					</a>
				</div>
			</form>
		</div>
	</div>
</div> 
<div class="content">
	<div class="row">
		<div class="col-sm-8 col-xs-4">
			<h4 class="page-title"><?='Holidays of '.date('Y');?></h4>
		</div>
		<div class="col-sm-4 col-xs-8 text-right m-b-20">
			<?php if (($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'admin') || ($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'superadmin')) { ?>
			<a class="btn add-btn" href="<?=base_url()?>holidays/add"><i class="fa fa-plus"></i> <?='Add New Holiday';?></a>
			<?php } ?>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-sm-3">
					<div class="form-group form-focus focused">
					<label class="control-label">Select Holidays</label>
					<select class="select2-option form-control m-b-0" onchange="get_year_holidays(this.value);"> 
						<option value="0" selected="selected" disabled>-- Filter Holidays -- </option>
						<?php for($i = 2015;$i <= date('Y')+1; $i++ ){ 
						$sel = '';
						if($i == date('Y')) { $sel = 'selected="selected"'; }
						?>
						<option value="<?=$i?>" <?=$sel?>><?=$i?></option>
						<?php } ?>       
					</select>
					</div>
				</div>
			</div> 
		   <div class="table-responsive">
				<table id="table-holidays" class="table table-striped custom-table m-b-0 AppendDataTables">
					<thead>
						<tr>
							<th><?='No';?></th>
							<th><?='Title';?> </th>
							<th><?='Holiday Date';?></th>
							<th><?='Description';?></th> 
							<?php if (($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'admin') || ($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'superadmin')) { ?>
							<th class="text-right"><?='Action';?></th> 
							<?php } ?>
						</tr>
					</thead>
					<tbody id="holiday_tbl_body">
					<?php  
					$i = 1;  foreach($holidays as $key => $hldays){
						 $curdate       = strtotime(date('d-m-Y'));
						 $hlidate       = strtotime($hldays['holiday_date']); 
						 ?>
						<tr  >
							<td><?=$i?></td>
							<td><?=$hldays['title']?></td>
							<td><?php
							 echo date('d-m-Y',strtotime($hldays['holiday_date']));
							 /*if($curdate > $hlidate){
								 echo '&nbsp; &nbsp;&nbsp; <span class="label label-danger"> End </span>';
							 }else if($curdate < $hlidate){ 
								 echo '&nbsp; &nbsp;&nbsp; <span class="label label-success"> Upcoming </span>';
							 }else{
								 echo '&nbsp; &nbsp;&nbsp; <span class="label label-info"> Today </span>';
							 }*/
							?></td>
							<td width="50%"><?=$hldays['description']?></td>
							<?php if (($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'admin') || ($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'superadmin')) { 

								/*if($curdate > $hlidate){

								?>

							<td class="text-right"> 
							 <a href="#" class="btn btn-success btn-xs"  disabled title="<?=lang('edit')?>">
								<i class="fa fa-edit"></i> 
							 </a>
							 <a class="btn btn-danger btn-xs" title="Delete" disabled href="#" data-original-title="Delete">
								<i class="fa fa-trash-o"></i>
							 </a>
							</td>

						<?php } else {*/

							?>

							<td class="text-right"> 
							 <a href="<?=base_url()?>holidays/edit/<?=$hldays['id']?>" class="btn btn-success btn-xs"  title="<?=lang('edit')?>">
								<i class="fa fa-edit"></i> 
							 </a>
							 <a class="btn btn-danger btn-xs" title="Delete" data-toggle="ajaxModal" href="<?=base_url()?>holidays/delete/<?=$hldays['id']?>" data-original-title="Delete">
								<i class="fa fa-trash-o"></i>
							 </a>
							</td>

							<?php //} 
						} ?>
					   </tr>
					 <?php $i++; } ?>  
					</tbody>
			   </table>    
			</div>
		</div>
	</div>
</div> 
<script language="javascript">
function get_year_holidays(year)
{ 	 if(year != "" || year != 0){   
      $('#holiday_tbl_body').html('<tr> <td class="text-center" colspan="5"><img src="<?=base_url()?>assets/images/loader-mini.gif" alt="Loading"></td> </tr>'); 
      $.ajax({
          url     : "<?=base_url()?>"+'holidays/year_holidays/',
          data    : { year : year,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },
          type    :"POST",
          success : function(res){ 
              $('#holiday_tbl_body').html(res); 
          }
      });
    }									
}
</script>
<div class="content">
	<div class="row">
		<div class="col-xs-4">
			<h4 class="page-title">Rules & Regulation</h4>
		</div>
		<div class="col-xs-8 text-right m-b-20">
		  <?php  if ($this->tank_auth->user_role($this->tank_auth->get_role_id()) == 'admin') { ?>
			<a href="<?=base_url()?>rules_regulation/add" class="btn btn-primary rounded pull-right"><i class="fa fa-plus"></i> <?='Add New Rule';?></a>
		  <?php } ?> 
		</div>
	</div>
	<div class="table-responsive">
		<table id="table-holidays" class="table table-striped custom-table m-b-0 AppendDataTables">
			<thead>
				<tr>
					<th><?='No';?></th> 
					<th><?='Description';?></th> 
					<th><?='Status';?></th> 
					<th><?='Created On';?></th>  
					<th class="text-right"><?='Option';?></th> 
				</tr>
			</thead>
			<tbody>
			 <?php $i = 1;  foreach($rules as $key => $rls){ ?>
				<tr >
					<td><?=$i?></td> 
					<td style="width:55%"><?=$rls['content']?></td>
					<td>
					<?php 
					 if($rls['status'] == 0){
						 echo '&nbsp; &nbsp;&nbsp; <span class="label label-success"> Active </span>';
					 } 
					?>
					</td>
					<td><?php echo date('d-m-Y h:i:s',strtotime($rls['date_created'])); ?></td> 
					<td class="text-right"> 
						 <a href="<?=base_url()?>rules_regulation/edit/<?=$rls['id']?>" class="btn btn-success btn-xs"  title="<?=lang('edit')?>">
							<i class="fa fa-edit"></i> 
						 </a>
						 <a class="btn btn-danger btn-xs" title="Delete" data-toggle="ajaxModal" href="<?=base_url()?>rules_regulation/delete/<?=$rls['id']?>" data-original-title="Delete">
							<i class="fa fa-trash-o"></i>
						 </a>
					</td> 
			   </tr>
			 <?php $i++; } ?>  
			</tbody>
	   </table>    
   </div>
</div> 
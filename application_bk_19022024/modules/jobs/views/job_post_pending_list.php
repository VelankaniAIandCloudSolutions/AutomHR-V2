<?php
   $jtype=array(0=>'unassigned');
       foreach ($offer_jobtype as $jkey => $jvalue) {
               $jtype[$jvalue->id]=$jvalue->job_type;                        
        }
   
   ?>
<div class="content">
   <div class="page-header">
      <div class="row">
         <div class="col-md-4 col-12">
            <h4 class="page-title m-b-0"><?php echo lang('raise_job_request');?></h4>
            <ul class="breadcrumb p-l-0" style="background:none; border:none;">
               <li class="breadcrumb-item"><?php echo lang('raise_job_request');?></li>
            </ul>
         </div>
         <div class="col-md-8 col-12 text-right m-b-30">
            <a class="btn add-btn m-r-5" href="<?php echo base_url(); ?>jobs/job_post_approval" class="btn add-btn"><i class="fa fa-plus"></i> <?php echo lang('job_request_approval');?></a>
            <!-- <a class="btn add-btn m-r-5" href="<?php echo base_url(); ?>jobs/pending_for_job_post_list" class="btn delete-btn"><i class="fa fa-undo"></i> <?php echo lang('back');?></a> -->
         </div>
      </div>
   </div>
   <?php //$this->load->view('sub_menus');?>
   <div class="row">
      <?php foreach ($jobs as $key => $value) {	//print_r($value);exit();	// foreach starts 
         ?>
      <div class="col-md-12">
         <!-- <a class="job-list" href="<?=base_url()?>jobs/jobview/<?=$value->id?>"> -->
         <div class="job-list">
            <div class="job-list-det">
               <div class="job-list-desc">
                  <h3 class="job-list-title"><?=ucfirst($value->title);?></h3>
                  <h4 class="job-department"><?=ucfirst($jtype[$value->job_type]);?></h4>
               </div>
               <div class="job-type-info">
                  <span >
                  <a class='job-types' href="<?=base_url()?>jobs/apply/<?=$value->id?>/<?=$value->job_type?>">Apply</a>
                  </span>
               </div>
            </div>
            <div class="job-list-footer">
               <ul>
                  <!-- <li><i class="fa fa-map-signs"></i> California</li> -->
                  <li><i class="fa fa-money"></i> <?=$value->salary;?></li>
                  <li><i class="fa fa-clock-o"></i> <?=Jobs::time_elapsed_string($value->created); ?></li>
               </ul>
            </div>
         </div>
         <!-- </a> -->
      </div>
      <?php } // foreach end ?>	 
   </div>
   <div class="row">
      <!-- <div class="col-sm-5 col-12">
         <h4 class="page-title"><?php echo lang('manage_jobs');?></h4>
         </div> -->
   </div>
   <div class="table-responsive">
      <table class="table table-striped custom-table mb-0 AppendDataTables">
         <thead>
            <tr>
               <th>#</th>
               <th><?php echo lang('Proposed_Designation');?></th>
               <th><?php echo lang('Number_of_years_of_experience');?></th>
               <th><?php echo lang('Qualification_required');?></th>
               <th><?php echo lang('Nature_of_work_experience');?></th>
               <th class="text-center"><?php echo lang('Any_targeted_industry_segment');?></th>
               <th class="text-center"><?php echo lang('Proposed_CTC');?></th>
               <th><?php echo lang('Timeline_for_Hiring');?></th>
               <th><?php echo lang('Names_of_People_who_will_conduct_the_interview');?></th>
               <th><?php echo lang('status');?></th>
               <th><?php echo lang('job_posted_status');?></th>
               <th><?php echo lang('action');?></th>
            </tr>
         </thead>
         <tbody>
            <?php 
               $i=1;
               $class_array = array('text-info','text-success','text-danger','text-warning');
               if(!empty($job_data))
               {
               	foreach($job_data as $key =>$list){
               		$json_decode_data = json_decode($list->json_data, true);
               		?>
            <tr>
               <td><?php echo $i++;?></td>
               <td><?php echo $json_decode_data['Proposed_Designation'];?></td>
               <td><?php echo $json_decode_data['Number_of_years_of_experience'];?></td>
               <td><?php echo $json_decode_data['Qualification_required'];?></td>
               <td><?php echo $json_decode_data['Nature_of_work_experience'];?></td>
               <td><?php echo $json_decode_data['Any_targeted_industry_segment'];?></td>
               <td><?php echo $json_decode_data['Proposed_CTC'];?></td>
               <td><?php echo $json_decode_data['Timeline_for_Hiring'];?></td>
               <td><?php echo $json_decode_data['Names_of_People_who_will_conduct_the_interview'];?></td>
               <td>
                  <?php 
                     if($list->status =='0')
                     {
                     	echo "<b style='color:orange;'>Pending for Approval</b>";
                     }
                     if($list->status =='1')
                     {
                     	echo "<b style='color:green;'>Approved : </b>".$list->comment_by_approval;
                     }
                     if($list->status =='2')
                     {
                     	echo "<b style='color:red;'>Rejected : </b>".$list->comment_by_approval;
                     }
                     ?>
               </td>
               <td>
                  <?php 
                     if($list->job_posted_status != '1')
                     {
                     	if($list->status == "0")
                     	{
                     		echo "<b style='color:orange;'>Pending for Job Posting Approval.</b>";
                     	}
                     	elseif($list->status == "2")
                     	{
                     		echo "<b style='color:red;'>Job Posting Rejected.</b>";
                     	}
                     	else{
                     		echo "<b style='color:orange;'>Pending for Job Posting Approval.</b>";
                     	}
                     }
                     else if($list->job_posted_status =='1')
                     {
                     	echo "<b style='color:green;'>Job Posted</b>";
                     }
                     else if($list->job_posted_status !='1' && $list->status == '1')
                     {
                     	echo "<b style='color:orange;'>Job Posting Request Approved but Pending for Job Posting.</b>";
                     }
                     else{
                     	echo "<b style='color:orange;'>Pending for Job Posting Approval.</b>";
                     }
                     ?>
               </td>
               <td>
                  <?php if($list->status == '1' && ($list->job_posted_status == '0' || $list->job_posted_status == ''))
                     { ?>
                  <a class="btn add-btn" href="<?php echo base_url(); ?>jobs/approved_job_post/<?php echo $list->id;?>" class="btn add-btn"><?php echo lang('add_jobs');?></a>
                  <?php } 
                     ?>
               </td>
            </tr>
            <?php }  } ?>
         </tbody>
      </table>
   </div>
</div>
<!-- all_job_header modal -->
<?php $all_job_header= $this->db->get('all_job_header')->row_array();?>
<div id="all_job_header" class="modal fade" role="dialog">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header pb-0">
            <h5 class="modal-title"><?php echo lang('header_image');?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form method="post" id="job_header" method="POST" action="<?php echo base_url(); ?>jobs/add_header" enctype="multipart/form-data">
               <?php if(!empty( $all_job_header)){?>
               <input class="form-control" type="hidden" name="id"  id= "image_edit" value="<?php echo $all_job_header['id'];?>">
               <?php }?>
               <div class="form-group">
                  <label><?php echo lang('description');?> <span class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="description" value="<?php echo (!empty($all_job_header['description']))?$all_job_header['description']:"";?>" required>
               </div>
               <div class="form-group">
                  <label><?php echo lang('header_image');?> <span class="text-danger">*</span></label>
                  <input class="form-control" type="file" name = "image" id="image"  >
                  <input class="form-control" type="hidden" name="avatar"  id= "image_edit" value="<?php echo $all_job_header['image'];?>" required>
                  <?php  if(!empty($all_job_header['image'])){ ?>
                  <img class="rounded-circle" alt="" style="width: 300px" src="<?php echo base_url()?>assets/uploads/<?php echo $all_job_header['image'];?>">
                  <?php  } ?>
               </div>
               <div class="submit-section">
                  <button class="btn btn-primary submit-btn" id="" type="submit"><?php echo lang('submit');?></button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!-- all_job_header modal -->
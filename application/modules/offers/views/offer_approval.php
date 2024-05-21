<?php
$jtype=array();
 foreach ($offer_jobtype as $key => $value) {
   $jtype[$value->id] = $value->job_type;
 }
?>
                <div class="content ">
          <div class="row">
            <div class="col-xs-8">
              <h4 class="page-title">Offers</h4>
            </div>
           <!--  <div class="col-xs-4 text-right m-b-30">
              <a href="#" class="btn btn-primary rounded pull-right" data-toggle="modal" data-target="#add_job"><i class="fa fa-plus"></i> Add New Job</a>
            </div> -->
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-striped custom-table m-b-0 datatable" id="approval_table">
                  <thead>
                   <tr>
                      <th >#</th>
                      <th>Name</th>
                      <th class="text-left">Title</th>
                      <th class="text-left">Job Type </th> 
                      <th class="text-left">Pay </th> 
                      <!-- <th>Vacation</th> -->
                      <th class="text-left">Status</th>
                      <!-- <th>Resume</th> -->
					  <th class="text-center">Action</th>
					   <?php
						if(App::is_permit('menu_offer_approval','read')==true||App::is_permit('menu_offer_approval','write')==true|| App::is_permit('menu_offer_approval','delete')==true)
						{
						?>
						
						<?php
						}
						?>
					<!--   <th>Applicants</th>
                      <th class="text-right">Actions</th> -->
                    </tr>
                  </thead>
                  <tbody>

                   <?php
                    $curren_type = array();
                     foreach ($currencies as $curren){ 
                      $curren_type[$curren->code] = $curren->symbol;
                     }
                   //  print_r($curren_type);
                      $plan_percent = array();
                      foreach (User::get_annual_incentive_plans() as $plans =>$plan){
                       $plan_percent[$plan['id']] = ucfirst(trim($plan['plan']));
                      } 
                      $vocation_name = array();
                       foreach (User::get_vocations() as $vocations =>$vocation){
                        $vocation_name[$vocation['id']] = ucfirst(trim($vocation['vocation']));
                       }
                    $i=1;
                    /* foreach ($candi_list as $ck => $cv) {

                         $s_label = 'Requested';$s_label2 = '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>'; $class='success'; $color='#b31109';
                          $clr_class='warning'; $title="Click to Approve";
                           if($cv->approver_status == 1) { 
							$s_label = 'Rejected'; $s_label2 = '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>'; $class='danger'; $color='#b31109';
                          $clr_class='danger'; $title="Click to Approve";
                           }
                        if($cv->approver_status == 2) { 
                          $s_label = 'Approved'; $s_label2 = '<i class="fa fa-thumbs-o-down" aria-hidden="true"></i>'; $clr_class='success';
                        $title="Click to Not Approve"; $class='warning';$color='#056928';}

                      if($this->session->userdata('role_id')==1)
						{
							$rejected = $this->db->where(array('status'=>1,'offer'=>$cv->id))->get('dgt_offer_approvers')->num_rows();
							if($rejected >0)
							{
							$s_label = 'Rejected'; $s_label2 = '<i class="fa fa-thumbs-o-up" aria-hidden="true"></i>'; $class='danger'; $color='#b31109';
							$clr_class='danger'; $title="Click to Approve";

							}
						}

                        /*if($cv->status == 3) $s_label = 'Send offer';
                        if($cv->status == 4) $s_label = 'Offer accepted';
                        if($cv->status == 5) $s_label = 'Declined';
                        if($cv->status == 6) $s_label = 'Onboard';*/
                 /*   ?>
                    <tr>

                       <td ><?=$i;?></td>
                      <td><a href="<?php if(App::is_permit('menu_offer_approval','read')){?><?php echo base_url('offers/offer_view/').$cv->id; ?><?php }else{ echo '#';}?>" class =""><?=ucfirst($cv->candidate) ?></a></td>

                     
                      
                      <td class="text-left"><?=$cv->title?></td>
                      <td class="text-left"><?=ucfirst($jtype[$cv->job_type]) ?></td>                      
                      <td class="text-right"><?=$cv->salary; ?>  <?php echo $curren_type[$cv->currency_type];?></td>
                       <td class="text-right"><?php if(isset($plan_percent[$cv->annual_incentive_plan])){
                        echo $plan_percent[$cv->annual_incentive_plan];
                      }else{ echo "No";};?></td>
                      <td class="text-right"><?=ucfirst(($cv->long_term_incentive_plan=='on')?'Yes':'No') ?></td>
                      <!-- <td><?php if(isset($vocation_name[$cv->vacation])) { echo $vocation_name[$cv->vacation];}else{ echo "-";}?></td> -->

                        <td ><label class="badge bg-inverse-<?php echo $clr_class;?>" style='display: inline-block;min-width: 90px;'><?=ucfirst($s_label)?></label></td>
                      <!-- <td> <a href="<?= base_url().''.$cv->file_path.'/'.$cv->filename ?>" target='_blank' download>Download</td> -->
                    <!--   <td title="<?php echo $title; ?>">
                        <button data-status='<?=$cv->approver_status?>' data-offerid ="<?=$cv->id?>" data-offid='<?=$cv->app_row_id?>' type="button" class="btn btn-<?=$class?> status_changebuttons"><?=$s_label2?></button></td> -->

                      
						<td class="text-center">
						<div class="dropdown">
						<a data-toggle="dropdown" class="action-icon" href="#"><i class="fa fa-ellipsis-v"></i></a>
						<div class="dropdown-menu float-right">

						<a href="javascript:void(0)" data-status='<?php echo 1;?>' data-offerid ="<?=$cv->id?>" data-offid='<?=$cv->app_row_id?>'class="status_changebuttons dropdown-item"  title="<?php echo "Approve offer"?>"><i class="fa fa-thumbs-o-up m-r-5" aria-hidden="true"></i>
						<?=lang('approve')?></a>


						<a href="javascript:void(0)" data-status='<?php echo 2;?>' data-offerid ="<?=$cv->id?>" data-offid='<?=$cv->app_row_id?>'class ="status_changebuttons dropdown-item"><i class="fa fa-ban m-r-5" aria-hidden="true"></i> <?=lang('reject')?></a>

						<a href="<?php echo base_url('offers/offer_view/').$cv->id; ?>" class =" dropdown-item"><i class="fa fa-eye m-r-5" aria-hidden="true"></i><?=lang('view_offer')?></a>


						</div>
						</div>


						</td>
						
                    </tr>
                      <?php }*/ ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
                </div>
        
                <?php echo datatable_script();?>
<script>
	$(document).ready(function() {
		$('#approval_table').DataTable( {
		"columns": [
				{ data: "s_no" },
				{ data: "name" },
        { data: "title" },
				{ data: "job_type" },
				{ data: "salary" },
        { data: "status" },
				{ data: "action" },
		],
		"bDestroy": true,
    "searching":false,
		"processing": true,
		"serverSide": true,
		"aLengthMenu": [
			//[10,25, 50, 100],
			//[10,25, 50, 100]
				[10,25, 50, 100, 200, -1],
				[10,25, 50, 100, 200, "All"]
		],
		"ajax": "<?php echo base_url().'offers/ajax_offer_approvals';?>",
    "language": {
        "zeroRecords": "No data available",
        "infoEmpty": "No records found",
    }

		} );
	} );
  
  function offer_approve(offerid,offid,status){
    $.ajax({
				url: base_url+'/offers/candidate_approve/',
        dataType:'json',
        type: 'POST',
        data: {'status':status,'offer_tab_id':offid,'offer_id':offerid},
        success: function (data) {
          // alert(data);
			 if(status == 1){
                     toastr.success('Approved successfully');
               setTimeout(function () {
                    location.reload();
                }, 1500);
                }else{
                     toastr.success('Rejected successfully');
                     setTimeout(function () {
                    location.reload();
                }, 1500);
                   

                }
        },
				
				});

  }
</script>
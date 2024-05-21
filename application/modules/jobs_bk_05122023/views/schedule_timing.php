<div class="content">
  <div class="page-header">
	<div class="row">
		<div class="col-12">
			<h4 class="page-title"><?=lang('schedule_timing')?></h4>
       <ul class="breadcrumb p-l-0" style="background:none; border:none;">
          <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">dashoard</a></li>
         
           <li class="breadcrumb-item"><?=lang('schedule_timing')?></li>
        
        </ul>
		</div>
	</div>
</div>
    <?php //$this->load->view('sub_menus');?>
    

	
    <div class="row">
        <!-- Project Tasks -->
        <div class="col-lg-12">
                <div class="table-responsive">
                    <table id="table-templates-1" class="table table-striped custom-table m-b-0 AppendDataTables">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?=lang('name')?></th>
                                <th><?=lang('job_title')?></th>    
                                <!-- <th><?=lang('schedule_timing')?></th> -->                         
                                <th><?php echo lang('user_available_timings')?></th>
                          <!--      <?php if(App::is_permit('menu_schedule_timing','read')){?> <th><?php echo lang('chats'); ?></th><?php }?> -->
                                <?php if(App::is_permit('menu_schedule_timing','write')){?><th class="col-options no-sort text-right"><?=lang('schedule_timing')?></th><?php }?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php  


                            $i=1;
                               foreach($candidates_list as $list){
                                        ?>
                                        <tr>
                                            <td> <?php echo $i++; ?> </td>
                                            <td class="text-capitalize"> <?php echo $list->first_name.' '.$list->last_name; ?> </td> 
                                            <td> <?php echo $list->job_title; ?> </td> 
                                             <td><?php
                                             $schedule_dates = json_decode($list->schedule_date,true);
                                             $available_timings = json_decode($list->user_selected_timing,true);
                                           //  print_r($available_timings);
                                             foreach ($schedule_dates as $key => $dates) {
                                                 echo '<b>'.date('d-m-Y',strtotime($dates)).'</b> - '. $time_list[$available_timings[$key]] .'</br>';
                        

                                             }
                                              ?> </td>        
                                            <!--   <?php if(App::is_permit('menu_schedule_timing','read')){?><td><a class="btn btn-success btn-sm" href="<?php echo base_url('candidate_chats').'?id='.$list->candidate_id;?>"><?php echo lang('chats')?></a></td> <?php }?>   
                                            
											<?php if(App::is_permit('menu_schedule_timing','write'))
											{?> -->
											<td class="text-right"> 
                                                <a href="<?=base_url()?>jobs/add_schedule_timing/<?php echo $list->candidate_id.'/'.$list->id;?>" class="btn btn-success btn-sm" data-toggle="ajaxModal">
                                                    <?=lang('schedule_time');?>
                                                </a>
                                                                     
                                            </td>
											<?php 
											}
											?>
                                            
                                        </tr>
                                        <?php 
                               }
                            
                                ?>
                        </tbody>
                    </table>
                </div>
        </div>
        <!-- End Project Tasks -->
    </div>
 
</div>

  
<?php
    $jtype=array(0=>'unassigned');
        foreach ($offer_jobtype as $jkey => $jvalue) {
                $jtype[$jvalue->id]=$jvalue->job_type;                        
         }
    
    ?>
<div class="content p-t-10">
    <div class="page-header">
    <div class="row">
        <div class="col-8">
            <h4 class="page-title m-b-0"><?php echo lang('view_job');?></h4>
            <ul class="breadcrumb p-l-0" style="background:none; border:none;">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>"><?php echo lang('home');?></a></li>
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>candidates/all_jobs"><?php echo lang('all_jobs');?></a></li>
                <li class="breadcrumb-item"><?php echo lang('view_job');?></li>
            </ul>
        </div>
            <div class="col-sm-4 text-right m-b-20">            
            <a class="btn back-btn" href="<?=base_url()?>candidates/all_jobs"><i class="fa fa-chevron-left"></i> <?php echo lang('back'); ?></a>
    </div>
</div>
</div>
    <div class="row">
        <?php foreach ($jobs as $key => $value) {   //print_r($value);exit();   // foreach starts 
            ?>
        <div class="col-md-6">
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
        <div class="col-md-8">
            <div class="job-info job-widget">
                <img src="<?php echo base_url('images/jobs/').$jobs_detail['job_image'];?>" alt="" width="100%" height="240">
                <h3 class="job-title text-capitalize"><?php echo $jobs_detail['job_title'];?></h3>
                <span class="job-dept"><?php echo $jobs_detail['deptname'];?></span>
                <ul class="job-post-det">
                    <li><i class="fa fa-calendar"></i> <?php echo lang('post_date');?>: <span class="text-blue"><?php echo date('M d, Y',strtotime($jobs_detail['start_date']));?></span></li>
                    <li><i class="fa fa-calendar"></i>  <?php echo lang('last_date');?>: <span class="text-blue"><?php echo date('M d, Y',strtotime($jobs_detail['expired_date']));?></span></li>
                    <li><i class="fa fa-user-o"></i> <?php echo lang('applications');?>: <span class="text-blue"><?php 
                    if(isset($applications['count'])) { echo $applications['count'];}else{ echo 0;} ?></span></li>
                    <li><i class="fa fa-eye"></i> <?php echo lang('views')?>: <span class="text-blue"><?php if(isset($total_views['count'])){ echo $total_views['count'];}else{ echo 0;}?></span></li>
                </ul>
            </div>
            <div class="job-content job-widget">
                <div class="job-desc-title">
                    <h4><?php echo lang('job_description');?></h4>
                </div>
                <div class="job-description">
                    <p><?php echo $jobs_detail['description'];?></p>
                     <!-- <ul class="square-list">
                        <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                        <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                        <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                        <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                        <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                    </ul> -->
                </div>
               
            </div>
        </div>
        <div class="col-md-4">
            <div class="job-det-info job-widget">
                <?php if($applied_status){?>
                <a class="btn job-btn" href="<?php echo site_url('candidates/job_apply').'/'.$jobs_detail['id'].'/apply';?>"><?php echo lang('apply_for_this_job');?></a>
                <div class="row">
                    <div class="col-lg-6"><a class="btn job-btn1" href="<?php echo site_url('candidates/job_apply').'/'.$jobs_detail['id'].'/save'; ?>"><?php echo lang('save');?></a></div>
                    <div class="col-lg-6"><a class="btn job-btn2" href="<?php echo site_url('candidates/job_apply').'/'.$jobs_detail['id'].'/archive'; ?>"><?php echo lang('archive');?></a></div>
                </div>
                <?php }else{
                    ?><p class="text-success">Applied successfully</p><?php
                }?>
                <div class="info-list">
                    <span><i class="fa fa-bar-chart"></i></span>
                    <h5><?php echo lang('job_type') ?></h5>
                    <p><?php echo $jobs_detail['job_type'];?></p>
                </div>
                <div class="info-list">
                    <span><i class="fa fa-money"></i></span>
                    <h5><?php echo lang('salary');?></h5>
                    <p><?php echo '$'.$jobs_detail['salary_from'].' - $'.$jobs_detail['salary_to'];?></p>
                </div>
                <div class="info-list">
                    <span><i class="fa fa-suitcase"></i></span>
                    <h5><?php echo lang('experience');?></h5>
                    <p><?php echo $jobs_detail['experience'];?></p>
                </div>
                <div class="info-list">
                    <span><i class="fa fa-ticket"></i></span>
                    <h5><?php echo lang('vacancy');?></h5>
                    <p><?php  echo $jobs_detail['no_of_vacancy']; ?></p>
                </div>
                <div class="info-list">
                    <span><i class="fa fa-map-signs"></i></span>
                    <h5><?php echo lang('location');?></h5>
                    <p><?php echo $jobs_detail['job_location'];?>
                    </p>
                </div>
                <div class="info-list">
                   <p class="text-truncate"> <?php echo PHONE_NUMBER;?>
                        <br> <a href="mailto:<?php echo MAIL_ID;?>" title="<?php echo MAIL_ID;?>"> <?php echo MAIL_ID;?></a>
                        <br> <a href="<?php echo WEBSITE_URL;?>" target="_blank" title="<?php echo WEBSITE_URL;?>"><?php echo WEBSITE_URL;?></a>
                    </p>
                </div>
                <div class="info-list text-center">
                    <a class="app-ends" href="#"><?php 
                        $current_date = date('Y-m-d');
                        if(strtotime($jobs_detail['expired_date']) < strtotime($current_date))
                        {
                            echo "<p class='text-danger'>".lang("expired")."</p>";
                        }else{
                            $datetime1 = new DateTime($current_date);
                            $datetime2 = new DateTime($jobs_detail['expired_date']);
                            $difference = $datetime1->diff($datetime2);
                            echo lang('application_ends_in').' ';
                            if( $difference->m > 0 ){
                                echo $difference->m.' months , '.$difference->d.' days';
                            }else{
                                echo $difference->d.' days '. $difference->h.' hours';
                            }
                        }?></a>
                </div>
            </div>
        </div>
    </div>
</div>
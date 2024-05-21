<?php
    $offer_jobtype =Jobs::_jobtypename();
    $offer_id=  $this->uri->segment(3);
    $job_role=  $this->uri->segment(4);
      $jtype=array(0=>'unassigned');
          foreach ($offer_jobtype as $jkey => $jvalue) {
                  $jtype[$jvalue->id]=$jvalue->job_type;                        
           }
    
    ?>
<div class="main-wrapper">
    <div class="header">
        <div class="header-left">
            <a href="<?=base_url()?>" class="logo">
            <img src="<?=base_url()?>assets/images/NewHrms_login_logo.png" width="40" height="40" alt="">
            </a>
        </div>
        <!-- <div class="page-title-box float-left">
            <h3>Phu HRMS</h3>
            </div> -->           
    </div>
    <div class="page-wrapper job-wrapper">
        <div class="content container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <h4 class="page-title"><?php echo lang('apply_job');?></h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <form id='applyjobfrm'  action='<?=base_url()?>auth/job_apply' name='applyjobfrm' method="post" enctype="multipart/form-data"  >
                        <div class="form-group">
                            <label><?php echo lang('name');?></label>
                            <input class="form-control" type="text" id='uname' name='uname' >
                            <div class="error_i" id="nameErr"><span></span></div>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('email_address');?></label>
                            <input class="form-control" type="text" id='email_id' name='email_id'  >
                            <div class="error_i" id="emailErr"></div>
                            <input class="form-control" type="hidden" id='job_role' name ='job_role' value="<?=$jtype[$job_role] ?> ">
                            <input class="form-control" type="hidden" id='job_roleid' name ='job_roleid' value="<?=$job_role ?> ">
                            <input class="form-control" type="hidden" id='offer_id' name ='offer_id' value="<?= $offer_id?>">
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('messages');?></label>
                            <textarea id='txtar' name='txtar' class="form-control" ></textarea>
                            <div class="error_i" id="txtErr"></div>
                        </div>
                        <div class="form-group">
                            <label><?php echo lang('upload_cv');?></label>
                            <input type="file" accept="application/pdf,application/msword,
                                application/vnd.openxmlformats-officedocument.wordprocessingml.document" name='cvfile' id='cvfile' class="form-control"> 
                            <div class="error_i" id="fileErr"></div>
                        </div>
                        <div class="m-t-20 text-center">
                            <button class="btn btn-primary btn-lg" id="job_apply"><?php echo lang('send_application');?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"></script>
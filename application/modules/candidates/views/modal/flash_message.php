 <?php
            if ($this->session->flashdata('success')) { $alert_type = 'success'; }else{ $alert_type = 'danger'; }
            if($this->session->flashdata('success')){ ?>
            <div class="alert alert-<?=$alert_type?>"> 
            <button type="button" class="close" data-dismiss="alert">×</button> <i class="fa fa-info-sign"></i>
            <?=$this->session->flashdata('success');?>
            </div>
            <?php }  if($this->session->flashdata('error')){ ?>
            <div class="alert alert-<?=$alert_type?>"> 
            <button type="button" class="close" data-dismiss="alert">×</button> <i class="fa fa-info-sign"></i>
            <?=$this->session->flashdata('error');?>
            </div>
            <?php } ?> 
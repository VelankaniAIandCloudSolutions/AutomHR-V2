<?php 
$cur = App::currencies(config_item('default_currency')); 
// $task = ($task_id > 0) ? $this->db->get_where('tasks',array('t_id'=>$data['task_id'])) : array();
// $project_id = (isset($task_id)) ? $task_id : '';
// $task_progress = (isset($task_progress)) ? $task_progress : '';
$user_id = (isset($user_id)) ? $user_id : '0';
$department_id = (isset($department_id)) ? $department_id : '0';
$designation_id = (isset($designation_id)) ? $designation_id : '0';
if($user_id != ''){
  $user_details = $this->db->select('*')
                           ->from('users U')
                           ->join('account_details AD','U.id = AD.user_id',LEFT)
                           ->where('AD.user_id',$user_id)
                           ->get()->row_array();
}
if($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1){
  $post_branch_id = $branch_id;
} else {
  $post_branch_id = $this->session->userdata('branch_id');
}
?>

<div class="content">
  <section class="panel panel-white">
            
    <div class="panel-heading">

            <?=$this->load->view('report_header');?>

            <?php if($this->uri->segment(3) && count($employees)> 0 ){ ?>
              <a href="<?=base_url()?>reports/employeepdf/<?=$user_id;?>/<?= $post_branch_id ?>/<?= $department_id ?>/<?= $designation_id ?>" class="btn btn-primary pull-right"><i class="fa fa-file-pdf-o"></i><?=lang('pdf')?>
              </a>              
            <?php } ?>
            <input type="hidden" class="form-control user_id_excel" name = "user_id" value="<?php echo (isset($_POST['user_id']) && !empty($_POST['user_id']))?$_POST['user_id']:'';?>">
            <?php  $report_name = lang('employee_report');?>
            <button class="btn  btn-primary pull-right" onclick="employee_report_excel('<?php echo $report_name;?>','employee_report_excel');" > <span ><i class="fa fa-file-excel-o m-r-5" aria-hidden="true"></i></span><span><?=lang('excel')?></span> </button>
             
    </div>

    <div class="panel-body">

            <!-- <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <i class="fa fa-info-sign"></i><?=lang('amount_displayed_in_your_cur')?>&nbsp;<span class="label label-success"><?=config_item('default_currency')?></span>
            </div> -->

      <div class="fill body reports-top rep-new-band">
        <div class="criteria-container fill-container hidden-print">
          <div class="criteria-band">
            <address class="row">
            <?php echo form_open(base_url().'reports/view/employee_report'); ?>

            <?php if($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1){?>
              <div class="col-md-2">
                <label><?=lang('branches')?> </label>
                <select <?=$dis?>  class="form-control" name="branch_id" id="branchreportemp"><!---->
              <option value="0"><?=lang('all_branches')?></option>
              <?php 
                // $all_branches = $this->db->get_where('branches',array('branch_status'=>'0'))->result_array();
                // echo "<pre>"; print_r($all_branches); exit;
                if(!empty($all_branches)){
                  foreach($all_branches as $branch){ ?>
                    <option value="<?php echo $branch['branch_id']; ?>" <?php if($branch_id == $branch['branch_id']){ echo "selected"; } ?> ><?php echo $branch['branch_name']; ?></option>
              <?php 
                  }
                }
              ?>
          </select>
              </div>
              <div class="col-md-3">
                <label><?=lang('name')?> </label>
                <?php
                if($this->session->userdata('user_id') !=1){
                  $all_employees = $this->db->select('U.*,AD.fullname')
                  ->from('users U')
                  ->join('account_details AD','U.id = AD.user_id',LEFT)
                  ->where('U.role_id',3)
                  ->where('AD.branch_id',$this->session->userdata('branch_id'))
                  ->get()->result_array();
                }
                ?>
                <select class="select2-option form-control" style="width:280px" name="user_id" id="all_userids"  >
                <option value="">Choose Employees</option>
                  <?php if(!empty($all_employees)){ 
                    foreach($all_employees as $emp) { ?>
                      <option value="<?php echo $emp['id']; ?>" <?php if($user_id == $emp['id']){ echo "selected"; } ?>><?php echo $emp['fullname']; ?></option>
                    <?php } } ?>
                </select>
              </div>
              <?php }
            else{
            ?>
              <input type="hidden" value="<?php echo $this->session->userdata('branch_id');?>" name="branch_id">
              <input type="hidden" value="<?php echo $this->session->userdata('user_id');?>" name="user_id">
          <?php }?>
              

              <div class="col-md-3">
                <label><?=lang('department')?> </label>
                <select class="select2-option form-control" style="width:280px" name="department_id" id="all_departmentids" >
                    <optgroup label="<?=lang('department')?>">
                      <option value="0">All</option>
                        <?php 
                        $department = $this->db->get_where('departments')->result();

                        foreach ($department as $c): ?>
                            <option value="<?=$c->deptid?>" <?=($department_id == $c->deptid) ? 'selected="selected"' : '';?>>
                            <?=ucfirst($c->deptname)?></option>
                        <?php endforeach;  ?>
                    </optgroup>
                </select>
              </div>

              <div class="col-md-3">
                <label><?=lang('designation')?> </label>
                <select class="select2-option form-control" style="width:280px" name="designation_id" id="all_designationids" >
                    <optgroup label="<?=lang('designation')?>">
                      <option value="0">All</option>
                        <?php 
                        $designation = $this->db->get_where('designation')->result();

                        foreach ($designation as $c): ?>
                            <option value="<?=$c->id?>" <?=($designation_id == $c->id) ? 'selected="selected"' : '';?>>
                            <?=ucfirst($c->designation)?></option>
                        <?php endforeach;  ?>
                    </optgroup>
                </select>
              </div>
                


              <div class="col-md-2">  
                <button class="btn btn-success" type="submit">
                  <?=lang('run_report')?>
                </button>
              </div>



            </address>
          </div>
        </div>


        <?php  form_close(); ?>

        <div class="rep-container">
          <div class="page-header text-center">
            <h3 class="reports-headerspacing"><?=lang('employee_report')?></h3>
            <!-- <?php if($task->t_id != NULL){ ?>
            <h5><span><?=lang('project_name')?>:</span>&nbsp;<?=$task->task_name?>&nbsp;</h5>
            <?php } ?> -->
        </div>

        <div class="fill-container">


          <div class="col-md-12">
                  
              <table id="task_report" class="table table-striped custom-table m-b-0">
                <thead>
                  <tr>
                    <th style="width:5px; display:none;"></th>
                    <th><b><?=lang('name')?></b></th>  
                    <th><b><?='Employee Code';?></b></th>  
                    <!-- <th><b><?=lang('company')?></b></th> -->
                    <th><b><?=lang('email')?></b></th>
                    <th><b><?=lang('department')?></b></th>
                    <th><b><?=lang('designation')?></b></th>
                    <th class="col-title "><b><?=lang('status')?></b></th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($employees as $key => $p) { 

                     // $company_name = $this->db->get_where('companies',array('co_id'=>$p['company']))->row_array(); 
                     // $users = $this->db->get_where('account_details',array('user_id'=>$p['user_id']))->row_array();
                     // $designation = $this->db->get_where('designation',array('id'=>$p['designation_id']))->row_array();
                     // $department = $this->db->get_where('departments',array('deptid'=>$p['department_id']))->row_array();

                   if($p['status'] == 1)
                    {
                      $cls = 'active';
                      $btn_actions='Active';
                    }else{
                      $cls = 'inactive';
                      $btn_actions='Inactive';
                    }
                   
                  
                                           
                  ?> 
                  <tr >
                    
                    <td>
                     
                      <a class="text-info" data-toggle="tooltip"  href="<?=base_url()?>employees/profile_view/<?=$p['id']?>">
                        <?=$p['fullname']?>
                      </a>
                    
                    </td>
                    <td>
                     
                      <a class="text-info" data-toggle="tooltip"  href="<?=base_url()?>employees/profile_view/<?=$p['id']?>">
                        <?=$p['emp_code']?>
                      </a>
                    
                    </td>
                    <!-- <td><?php echo $company_name['company_name']?></td> -->
                    <td><?php echo $p['email']?></td>
                    <td><?php echo $p['department']?></td>
                    <td><?php echo $p['designation']?></td>

                    
                    <?php 
                      switch ($p['status']) {
                        case '1': $label = 'success'; break;
                        case '0': $label = 'warning'; break;
                      }
                    ?>
                    <td>
                      <span class="label label-<?=$label?>"><?php echo $btn_actions ?></span>
                    </td>
                   
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>


     
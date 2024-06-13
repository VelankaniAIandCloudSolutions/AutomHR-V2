<div class="content">
   <div class="row">
      <div class="col-xs-12 message_notifcation" ></div>
      <div class="col-xs-4">
         <h4 class="page-title"><?php echo $title;?></h4>
      </div>
      <div class="col-sm-8 col-9 text-right m-b-20">
         <a href="javascript:void(0)" class="btn add-btn" data-toggle="modal" data-target="#add_new_user"><i class="fa fa-plus"></i> Add Employee</a>
         <?php  if($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1){?>
         <a href="<?php echo  base_url('employees/transerEntity');?>" class="btn add-btn" style="margin-right:10px;"><?=lang('movement_from')?></a>
         <?php }?>
         <div class="view-icons">
            <a href="<?php echo base_url().'employees/grid_employees'?>" onclick="changeviews(this,'grid')" class="viewby grid-view btn btn-link"><i class="fa fa-th"></i></a>
            <a href="<?php echo base_url().'employees/employees'?>" onclick="changeviews(this,'list')" class="viewby list-view btn btn-link active"><i class="fa fa-bars"></i></a>
         </div>
      </div>
   </div>
   <?php $attributes = array('class' => 'bs-example','id'=> 'importBulkImport', "enctype" => "multipart/form-data","method" => "post"); echo form_open_multipart('employees/employee_bulk_import', $attributes); ?>
   
   <div class="row">
      <div class="col-sm-6 col-xs-12 col-md-6">
         <label class="control-label"><?=lang('action')?> </label>
         <select class="select floating form-control" id="action_type"  name="action_type" style="padding: 14px 9px 0px;" required>
            <option value="" selected="selected">Action</option>
            <!-- <option value="insert">Insert</option> -->
            <option value="update">Update</option>
         </select>
      </div>

      <div class="col-sm-6 col-xs-12 col-md-6">
         <label class="control-label"><?=lang('branch')?> </label>
         
         <select class="select2 form-control" style="width:100%;" name="branch_id"  id="branch_id" onchange='return export_entity_data($(this).val());' required> 
            <option value="">Select Entity</option>
            <?php 
               if(!empty($entities) && is_array($entities))
               {
                  foreach($entities as $entities_val)
                  { ?>
                     <option value="<?php echo $entities_val->branch_id;?>" > <?php echo $entities_val->branch_name;?> </option>
                  <?php }
               }
            ?>
         </select>
      </div>
   </div>

   <div class="row">
      <div class="col-sm-6 col-xs-12 col-md-6">
         <label class="control-label">Action By </label>
         
         <select class="select2 form-control" style="width:100%;" name="action_by"  id="action_by" required> 
            <option value="">Action Perform on ID/Email</option>
            <option value="1">Employee ID</option>
            <option value="2">Employee Work Email </option>
         </select>
      </div>

      <div class="col-sm-6 col-xs-12 col-md-6">
         <label class="control-label">Information Update For</label>
         
         <select class="select2 form-control" style="width:100%;" name="info_update_for" onchange="button_display();  return false;"  id="info_update_for" required> 
            <option value="">Select</option>
            <option value="1">Employee Basic Information</option>
            <option value="2">Bank & Statutory / Payroll Addition/ Payroll Deduction</option>
            <option value="3">Zip File Upload</option>
         </select>
      </div>
      
    </div>

    
   <div class="row">

   <div class="col-sm-6 col-xs-12 col-md-6">
         <label class="control-label">Document Name</label>
         <input name="document_type" id="document_type" class="select2 form-control" style="width:100%;" placeholder="Enter Document Name">
      </div>
      <div class="col-sm-6 col-xs-12 col-md-6">
         <label><?='File Upload'?> </label>
         <input type="file" name="import_csv" class="form-control" id="import_csv" placeholder="Bulk Import" >
      </div>
   </div>
   <div class="row">
      <div class="col-sm-6 col-xs-12 col-md-3">
               <button class="btn btn-primary submit-btn" id="addBulkimport" type="submit">Submit</button>
      </div>
      <div class="col-sm-6 col-xs-12 col-md-3">
         <a href="<?php echo base_url('employees/employeeSampleCsv?file=basicsample');?>" class="btn btn-primary submit-btn employee_basic_info" style="display:none;"><?php echo lang("download_csv_sample");?></a>
         <a href="<?php echo base_url('employees/employeeSampleCsv?file=banksample');?>" class="btn btn-primary submit-btn employee_bank_info" style="display:none;"><?php echo lang("download_csv_sample");?></a>
         <button class="btn btn-primary submit-btn" id="export_btn" type="button" onclick="return export_employee_data();" style="display:none;">Export Data</button>
      </div>
   </div>
   
   </form>
</div>
</div>
</div>

<script>
   function button_display()
   {
      $("#export_btn").hide();
      
      var btn_value = $("#info_update_for").val();
      if(btn_value == 1)
      {
         $(".employee_basic_info").show();
         $(".employee_bank_info").hide();
      }
      else if(btn_value == 2)
      {
         $(".employee_bank_info").show();
         $(".employee_basic_info").hide();
      }
      else{
         $(".employee_bank_info").hide();
         $(".employee_basic_info").hide();
      }
   }

   function export_entity_data(entity_id )
   {
      if(entity_id > 0)
      {
         $("#export_btn").show();
      }
      else{
         $("#export_btn").hide();
      }
   }

</script>

<script>
    function export_employee_data() {
        // Create a hidden iframe
        var branch_id = $("#branch_id").val();
        var iframe = $('<iframe>', {
            id: 'downloadFrame',
            style: 'display:none',
            src: "<?php echo base_url('employees/export_employee_data?branch_id=');?>"+branch_id // Replace 'your_controller' with your actual controller name
        });

        // Append the iframe to the document body
        $('body').append(iframe);

        // Remove the iframe after a short delay (adjust as needed)
        // setTimeout(function() {
        //     $('#downloadFrame').remove();
        // }, 5000); // Remove after 5 seconds (adjust as needed)
    }
</script>
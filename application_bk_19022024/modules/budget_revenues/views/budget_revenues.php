<div class="content container-fluid">
          <div class="row">
            <div class="col-xs-8">
              <h4 class="page-title">Budget revenues</h4>
            </div>

            <div class="col-xs-4 text-right m-b-30">
             
              
      <a href="<?=base_url()?>budget_revenues/create" data-toggle="ajaxModal" title="<?=lang('create_revenue')?>" class="btn btn-primary rounded pull-right"><i class="fa fa-plus"></i> <?=lang('create_revenue')?></a>
     
            </div>
          </div>
          <div class="row filter-row">
              <div class="col-md-12 padding-2p search_date">
            <form id="timesheet_search" method="post" action="<?php echo base_url().'budget_revenues/'; ?>">
                  <div class="row">
                     <div class="col-sm-6 col-md-3 col-xs-6">  
                      <div class="form-group form-focus select-focus" style="width:100%;">

                        <label class="control-label">Category Name</label>
                      
                        <select class="select floating form-control" name="cat_id"  id="cat_id" style="padding: 14px 9px 0px;"> 

                            <option value="" selected="selected">All category</option>
                               
                            <?php  if(!empty($categories)){ ?>

                            <?php foreach($categories as $re) { ?>

                            <option value="<?php echo $re['cat_id']; ?>" <?php if($this->session->userdata('cat_id') !=''){ if($this->session->userdata('cat_id') == $re['cat_id']) { echo 'selected="selected"'; } }?>><?php echo $re['category_name']; ?></option>

                            <?php   } ?>

                            <?php  } ?>

                        </select>
                        <label id="employee_id_error" class="error display-none" for="employee_id">Please select an option</label>
                      </div>
                    </div>

                    <!-- <div class="col-sm-6 col-md-2 col-xs-6">  
                      <div class="form-group form-focus select-focus" style="width:100%;">

                        <label class="control-label">Projects Name</label>
                      
                        <select class="select floating form-control" name="project_id"  id="project_id" style="padding: 14px 9px 0px;"> 

                            <option value="" selected="selected">Projects</option>
                               
                            <?php  if(!empty($projects)){ ?>

                            <?php  foreach ($projects as $project) { ?>

                            <option value="<?php echo $project['id']; ?>" <?php if($this->session->userdata('search_employee') !=''){ if($this->session->userdata('search_employee') == $project['id']) { echo 'selected="selected"'; } }?>><?php echo $project['project_title']; ?></option>

                            <?php   } ?>

                            <?php  } ?>

                        </select>
                        <label id="employee_id_error" class="error display-none" for="employee_id">Please select an option</label>
                      </div>
                    </div>

                   
                    <div class="col-sm-6 col-md-2 col-xs-6">  
                      <div class="form-group form-focus select-focus" style="width:100%;">

                        <label class="control-label">Budget Category</label>
                      
                        <select class="select floating form-control" name="cat_id"  id="cat_id" style="padding: 14px 9px 0px;"> 

                            <option value="" selected="selected">Categories</option>
                               
                            <?php  if(!empty($budget_category)){ ?>

                            <?php foreach($budget_category as $category) { ?>

                            <option value="<?php echo $category['cat_id']; ?>" <?php if($this->session->userdata('search_employee') !=''){ if($this->session->userdata('search_employee') == $category['cat_id']) { echo 'selected="selected"'; } }?>><?php echo $category['category_name']; ?></option>

                            <?php   } ?>

                            <?php  } ?>

                        </select>
                        <label id="employee_id_error" class="error display-none" for="employee_id">Please select an option</label>
                      </div>
                    </div> -->
                    <div class="col-sm-6 col-md-3 col-xs-6">
                      <div class="form-group form-focus">
                        <label class="control-label">Date From</label>
                        <div class="cal-icon">
                          <input class="form-control floating" id="expenses_date_from" type="text" data-date-format="dd-mm-yyyy" name="budget_start_date"  value="<?php if($this->session->userdata('budget_start_date') !=''){ echo $this->session->userdata('budget_start_date');  } ?>" size="16">
                          <label id="timesheet_date_from_error" class="error display-none" for="expenses_date_from">From Date Shouldn't be empty</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-3 col-xs-6">
                      <div class="form-group form-focus">
                        <label class="control-label">Date To</label>
                        <div class="cal-icon">
                          <input class="form-control floating" id="expenses_date_to" type="text" data-date-format="dd-mm-yyyy" name="budget_end_date"  value="<?php if($this->session->userdata('budget_end_date') !=''){ echo $this->session->userdata('budget_end_date');  } ?>" size="16">
                          <label id="timesheet_date_to_error" class="error display-none" for="expenses_date_to">To Date Shouldn't be empty</label>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-6 col-md-3 col-xs-6">  
                    <div class="form-group">
                        <button id="budget-revenuet_search_btn" class="btn btn-success form-control" > Search </button>  <!-- 
                        <a href="javascript:void(0)" id="budget_revenue_search_btn" class="btn btn-success btn-block form-control" > Search </a>   -->
                    </div>
                    </div> 
                  </div>
               
            </form>
              </div> 
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-striped custom-table datatable" id="table-budget-revenue">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Notes</th>                    
                      <th>Category Name</th>
                      <th>SubCategory Name</th>
                      
                     <!-- <th>Total Revenue</th>
                      <th>Tax Amount</th> -->
                      <th>Amount</th>
                      <th>Revenue Date</th>
                      <th class="text-right">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php /*$i = 1; foreach($budget_revenues as $budget){ 
                      if($budget['project_id'] != 0){
                        $project = $this->db->get_where('projects',array('project_id'=>$budget['project_id']))->row_array();
                        $project_name = $project['project_title'];
                      }else{
                        $project_name = '-';
                      }

                      if(($budget['category_id'] != 0) || ($budget['s_category_id'] != 0)){
                        $category = $this->db->get_where('budget_category',array('cat_id'=>$budget['category_id']))->row_array();
                        $subcategory = $this->db->get_where('budget_subcategory',array('sub_id'=>$budget['s_category_id']))->row_array();
                        $category_name = $category['category_name'];
                        $subcategory_name = $subcategory['sub_category'];
                      }else{
                        $category_name = '-';
                        $subcategory_name = '-';
                      }

                     


                      ?>
                      <tr>
                        <td style="text-align: center;"><?php echo $i; ?></td>
                        <td style="text-align: center;"><?php echo $budget['notes']; ?></td>
                        <td><?php echo $category_name; ?></td>
                        <td><?php echo $subcategory_name; ?></td>
                       <!--  <td><?php echo ucfirst($budget['budget_type']); ?></td>
                        <td><?php echo ucfirst($project_name); ?></td>
                        <td><?php echo $budget_expenses_title; ?></td> -->
                        <td><?php echo $budget['amount']; ?></td>
                      
                        <td style="text-align: center;"><?php echo date('d M Y',strtotime($budget['revenue_date'])); ?></td>
                        <td class="text-right">
                          <div class="dropdown">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <ul class="dropdown-menu pull-right">
                              <li><a href="<?php echo base_url(); ?>budget_revenues/edit/<?php echo $budget['id']; ?>" data-toggle="ajaxModal"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
                              <li><a href="#" data-toggle="modal" data-target="#delete_budget<?php echo $budget['id']; ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
                            </ul>
                          </div>
                        </td> 
                      </tr>
                    <?php $i++; }*/ ?>

                  </tbody>
                </table>
              </div>
            </div>
          </div>
                </div>

                 <?php /*foreach($budget_revenues as $budget){ ?>
                    <div id="delete_budget<?php echo $budget['id']; ?>" class="modal custom-modal fade" role="dialog">
                      <div class="modal-dialog">
                        <div class="modal-content modal-md">
                          <div class="modal-header">
                            <h4 class="modal-title">Delete Revenues( <?php echo $budget['notes']; ?> )</h4>
                          </div>
                          <form method="post" action="<?php echo base_url(); ?>budget_revenues/delete_budget/<?php echo $budget['id']; ?>">
                            <div class="modal-body">
                              <input type="hidden" class="form-control" value="<?php echo $budget['id'];?>" name="amount">
                              <p>Are you sure want to delete this?</p>
                              <div class="m-t-20"> <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-danger">Delete</button>
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                <?php }*/ ?>

<?php echo datatable_script();?>
<script>
	$(document).ready(function() {
    var cat_id  = $('#cat_id').val();
    var expenses_date_from  = $('#expenses_date_from').val();
    var expenses_date_to  = $('#expenses_date_to').val();
		$('#table-budget-revenue').DataTable( {
		"columns": [
				{ data: "budget_no" },
				{ data: "notes" },
				{ data: "category" },
				{ data: "subcategory" },
				/*{ data: "project_name" },*/
				{ data: "amount" },
        { data: "revenue_date" },
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
		"ajax": "<?php echo base_url().'budget_revenues/ajax_revenues';?>?cat_id="+cat_id+"&budget_start_date="+expenses_date_from+"&budget_end_date"+expenses_date_to
		} );
	} );
</script>
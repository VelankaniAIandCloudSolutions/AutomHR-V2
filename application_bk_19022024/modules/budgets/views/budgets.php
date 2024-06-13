<div class="content container-fluid">
          <div class="row">
            <div class="col-xs-8">
              <h4 class="page-title">Budgets</h4>
            </div>
            <div class="col-xs-4 text-right m-b-30">
              <a class="btn btn-primary rounded pull-right" href="<?php echo base_url(); ?>budgets/add_budgets"><i class="fa fa-plus"></i> Add Budget</a>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-striped custom-table datatable" id="table_budget">
                  <thead>
                    <tr>
                      <th>Budget No</th>
                      <th>Budget Title</th>
                      <th>Budget Type</th>
                      <!-- <th>Project Name</th>
                      <th>Category Name</th>
                      <th>SubCategory Name</th> -->
                      <th>Start Date</th>
                      <th>End Date</th>
                      <th>Total Revenue</th>
                      <th>Total Expenses</th>
                      <th>Tax Amount</th>
                      <th>Budget Amount</th>
                      <th class="text-right">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php /*$i = 1; foreach($budgets as $budget){ 
                      if($budget['project_id'] != 0){
                        $project = $this->db->get_where('projects',array('project_id'=>$budget['project_id']))->row_array();
                        $project_name = $project['project_title'];
                      }else{
                        $project_name = '-';
                      }

                      if(($budget['category_id'] != 0) && ($budget['sub_cat_id'] != 0)){
                        $category = $this->db->get_where('budget_category',array('cat_id'=>$budget['category_id']))->row_array();
                        $subcategory = $this->db->get_where('budget_subcategory',array('sub_id'=>$budget['sub_cat_id']))->row_array();
                        $category_name = $category['category_name'];
                        $subcategory_name = $subcategory['sub_category'];
                      }else{
                        $category_name = '-';
                        $subcategory_name = '-';
                      }

                      ?>
                      <tr>
                        <td style="text-align: center;"><?php echo $i; ?></td>
                        <td style="text-align: center;"><?php echo $budget['budget_title']; ?></td>
                        <!-- <td><?php echo $project_name; ?></td>
                        <td><?php echo $category_name; ?></td>
                        <td><?php echo $subcategory_name; ?></td> -->
                        <td style="text-align: center;"><?php echo ucfirst($budget['budget_type']); ?></td>
                        <td style="text-align: center;"><?php echo date('d M Y',strtotime($budget['budget_start_date'])); ?></td>
                        <td style="text-align: center;"><?php echo date('d M Y',strtotime($budget['budget_end_date'])); ?></td>
                        <td style="text-align: center;"><?php echo $budget['overall_revenues']; ?></td>
                        <td style="text-align: center;"><?php echo $budget['overall_expenses']; ?></td>
                        <td style="text-align: center;"><?php echo $budget['tax_amount']; ?></td>
                        <td style="text-align: center;"><?php echo $budget['budget_amount']; ?></td>
                        <td class="text-right">
                          <div class="dropdown">
                            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <ul class="dropdown-menu pull-right">
                              <li><a href="<?php echo base_url(); ?>budgets/edit_budgets/<?php echo $budget['budget_id']; ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a></li>
                              <li><a href="#" data-toggle="modal" data-target="#delete_budget<?php echo $budget['budget_id']; ?>"><i class="fa fa-trash-o m-r-5"></i> Delete</a></li>
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

                 <?php /*foreach($budgets as $budget){ ?>
                    <div id="delete_budget<?php echo $budget['budget_id']; ?>" class="modal custom-modal fade" role="dialog">
                      <div class="modal-dialog">
                        <div class="modal-content modal-md">
                          <div class="modal-header">
                            <h4 class="modal-title">Delete Budget( <?php echo $budget['budget_title']; ?> )</h4>
                          </div>
                          <form method="post" action="<?php echo base_url(); ?>budgets/delete_budget/<?php echo $budget['budget_id']; ?>">
                            <div class="modal-body">
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
		$('#table_budget').DataTable( {
		"columns": [
				{ data: "budget_no" },
				{ data: "budget_title" },
				{ data: "budget_type" },
				{ data: "start_date" },
				{ data: "end_date" },
				{ data: "overall_revenues" },
				{ data: "overall_expenses" },
				{ data: "tax_amount" },
        { data: "budget_amount" },
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
		"ajax": "<?php echo base_url().'budgets/ajax_budget';?>"
		} );
	} );
</script>
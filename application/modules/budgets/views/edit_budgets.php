<?php //echo "<pre>"; print_r($budgets); exit; ?>
<div class="content container-fluid">
				<div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <h4 class="page-title">Edit Budget</h4>
                    </div>
                    <div class="col-sm-6 col-md-offset-3">
						<a href="<?php echo base_url(); ?>budgets" class="btn back-btn"><i class="fa fa-chevron-left"></i>Back</a>
			        </div>
                </div>
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<form method="post" action="<?php echo base_url(); ?>budgets/edit_budgets/<?php echo $budgets['budget_id']; ?>">
							<div class="form-group">
								<label>Budget Title</label>
								<input class="form-control" type="text" name="budget_title" placeholder="Budgets Title" value="<?php echo $budgets['budget_title']; ?>">
							</div>

								<label>Choose Budget Respect Type</label>
							<div class="form-group">
								<label class="radio-inline">
							      <input type="radio" name="budget_type" class="BudgetType" value="project" <?php if($budgets['budget_type'] == 'project'){ echo 'checked'; } ?> >Project
							    </label>
							    <label class="radio-inline">
							      <input type="radio" name="budget_type" class="BudgetType" value="category" <?php if($budgets['budget_type'] == 'category'){ echo 'checked'; } ?>>Category
							    </label>
							</div>

							<div class="form-group ProjecTS" <?php if($budgets['budget_type'] != 'project'){ ?> style="display: none;" <?php } ?>>
								<label><?=lang('projects')?></label>
								<select name="projects" class="form-control" id="projects">
									<option value="" disabled selected>Choose Project</option>

									<?php foreach($projects as $project){ ?>
										<option value="<?php echo $project['project_id']; ?>" <?php if($budgets['project_id'] == $project['project_id']){ echo 'selected'; } ?>><?php echo $project['project_title']; ?></option>
									<?php } ?>

								</select>
							</div>
							<div class="form-group CategorY" <?php if($budgets['budget_type'] != 'category'){ ?> style="display: none;" <?php } ?> >
								<label><?=lang('category')?></label>
								<select name="category" class="form-control" id="main_category">
									<option value="" disabled selected>Choose Category</option>
									<?php foreach($categories as $category){ ?>
										<option value="<?php echo $category['cat_id']; ?>" <?php if($budgets['category_id'] == $category['cat_id']){ echo 'selected'; } ?>><?php echo $category['category_name']; ?></option>
									<?php } ?>
									<!-- <option value="2">Category 2</option>
									<option value="3">Category 3</option> -->
								</select>
							</div>
							<?php $subcat = $this->db->get_where('budget_subcategory',array('sub_id'=>$budgets['sub_cat_id']))->row_array(); ?>
							<div class="form-group SUbCategorY" <?php if($budgets['budget_type'] != 'category'){ ?> style="display: none;" <?php } ?>>
								<label><?=lang('sub_category')?></label>
								<select name="sub_category" class="form-control" id="sub_category">
									<option value="<?php echo $subcat['sub_id']; ?>" selected><?php echo $subcat['sub_category']; ?></option>
								</select>
							</div>
							<div class="form-group">
								<label>Start Date</label>
								<div class="cal-icon"><input class="form-control datetimepicker" type="text" name="budget_start_date" placeholder="Start Date" data-date-format="dd-mm-yyyy" value="<?php echo date('d-m-Y',strtotime($budgets['budget_start_date'])); ?>"></div>
							</div>
							<div class="form-group">
								<label>End Date</label>
								<div class="cal-icon"><input class="form-control datetimepicker" type="text" name="budget_end_date" placeholder="End Date" data-date-format="dd-mm-yyyy" value="<?php echo date('d-m-Y',strtotime($budgets['budget_end_date'])); ?>"></div>
							</div>

							<div class="form-group">
								<label>Expected Revenues</label>
							</div>
							<div class="AllRevenuesClass">

								<?php 
									$all_revenue_titles = json_decode($budgets['revenue_title']); 
									$all_revenue_amount = json_decode($budgets['revenue_amount']); 
									for($i=0;$i<count($all_revenue_titles);$i++)
									{
								?>
								<div class="row AlLRevenues">
									<?php if($i != 0){ ?>
										<a class="remove_revenue_div" style="cursor: pointer;"><i class="fa fa-trash-o"></i></a>
									<?php } ?>

									<div class="col-sm-6">
										<div class="form-group">
											<label>Revenue Title <span class="text-danger">*</span></label>
											<input type="text" class="form-control RevenuETitle" value="<?php echo $all_revenue_titles[$i]; ?>" placeholder="Revenue Title" name="revenue_title[]" autocomplete="off">
										</div>
									</div>
									<div class="col-sm-5">
										<div class="form-group">
											<label>Revenue Amount <span class="text-danger">*</span></label>
											<input type="text" name="revenue_amount[]" placeholder="Amount" value="<?php echo $all_revenue_amount[$i]; ?>" class="form-control RevenuEAmount" autocomplete="off" >
										</div>
									</div>
									<?php if($i == 0){ ?>
										<div class="col-sm-1">
											<div class="add-more">
												<a class="add_more_revenue" title="Add Revenue" style="cursor: pointer;" ><i class="fa fa-plus-circle"></i></a>
											</div>
										</div>
									<?php } ?>
								</div>
							<?php } ?>

							</div>

							<div class="form-group">
								<label>Overall Revenues <span class="text-danger">(A)</span></label>
								<input class="form-control" type="text" name="overall_revenues" id="overall_revenues" placeholder="Overall Revenues" readonly style="cursor: not-allowed;" value="<?php echo $budgets['overall_revenues']; ?>">
							</div>

							<div class="form-group">
								<label>Expected Expenses</label>
							</div>
							<div class="AllExpensesClass">

								<?php 
									$all_expenses_titles = json_decode($budgets['expenses_title']); 
									$all_expenses_amount = json_decode($budgets['expenses_amount']); 
									for($i=0;$i<count($all_expenses_titles);$i++)
									{
								?>
								<div class="row AlLExpenses">
									<?php if($i != 0){ ?>
										<a class="remove_expenses_div" style="cursor: pointer;"><i class="fa fa-trash-o"></i></a>
									<?php } ?>
									<div class="col-sm-6">
										<div class="form-group">
											<label>Expenses Title <span class="text-danger">*</span></label>
											<input type="text" class="form-control EXpensesTItle" value="<?php echo $all_expenses_titles[$i]; ?>" placeholder="Expenses Title" name="expenses_title[]" autocomplete="off">
										</div>
									</div>
									<div class="col-sm-5">
										<div class="form-group">
											<label>Expenses Amount <span class="text-danger">*</span></label>
											<input type="text" name="expenses_amount[]" placeholder="Amount" value="<?php echo $all_expenses_amount[$i]; ?>" class="form-control EXpensesAmount" autocomplete="off">
										</div>
									</div>
									<?php if($i == 0){ ?>
										<div class="col-sm-1">
											<div class="add-more">
												<a class="add_more_expenses" title="Add Expenses" style="cursor: pointer;"><i class="fa fa-plus-circle"></i></a>
											</div>
										</div>
									<?php } ?>
								</div>
							<?php } ?>
							</div>

							<div class="form-group">
								<label>Overall Expense <span class="text-danger">(B)</span></label>
								<input class="form-control" type="text" name="overall_expenses" id="overall_expenses" placeholder="Overall Expenses" readonly style="cursor: not-allowed;" value="<?php echo $budgets['overall_expenses']; ?>">
							</div>


							<div class="form-group">
								<label>Expected Profit <span class="text-danger">( C = A - B )</span> </label>
								<input class="form-control" type="text" name="expected_profit" id="expected_profit" placeholder="Expected Profit" readonly style="cursor: not-allowed;" value="<?php echo $budgets['expected_profit']; ?>">
							</div>

							<div class="form-group">
								<label>Tax <span class="text-danger">(D)</span></label>
								<input class="form-control" type="text" name="tax_amount" id="tax_amount" placeholder="Tax Amount" value="<?php echo $budgets['tax_amount']; ?>">
							</div>

							<div class="form-group">
								<label>Budget Amount <span class="text-danger">( E = C - D )</span> </label>
								<input class="form-control" type="text" name="budget_amount" id="budget_amount" placeholder="Budget Amount" readonly style="cursor: not-allowed;" value="<?php echo $budgets['budget_amount']; ?>">
							</div>
							<div class="m-t-20 text-center">
								<button class="btn btn-primary btn-lg BudgetAddBtn">Submit</button>
							</div>
						</form>
					</div>
				</div>
            </div>
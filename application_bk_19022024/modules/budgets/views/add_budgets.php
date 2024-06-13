<div class="content container-fluid">
				<div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <h4 class="page-title">Add Budget</h4>
                    </div>
                    <div class="col-sm-6 col-md-offset-3">
						<a href="<?php echo base_url(); ?>budgets" class="btn back-btn"><i class="fa fa-chevron-left"></i>Back</a>
			        </div>
                </div>
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<form method="post" id="add_budget_form" action="<?php echo base_url(); ?>budgets/add_budgets">
							<div class="form-group">
								<label>Budget Title</label>
								<input class="form-control" type="text" name="budget_title" placeholder="Budgets Title">
							</div>

								<label>Choose Budget Respect Type</label>
							<div class="form-group">
								<label class="radio-inline">
							      <input type="radio" name="budget_type" class="BudgetType" value="project" required>Project
							    </label>
							    <label class="radio-inline">
							      <input type="radio" name="budget_type" class="BudgetType" value="category" required>Category
							    </label>
							</div>

							<div class="form-group ProjecTS" style="display: none;">
								<label><?=lang('projects')?></label>
								<select name="projects" class="form-control" id="projects">
									<option value="" disabled selected>Choose Project</option>
									<?php foreach($projects as $project){ ?>
										<option value="<?php echo $project['project_id']; ?>"><?php echo $project['project_title']; ?></option>
									<?php } ?>
									<!-- <option value="2">Category 2</option>
									<option value="3">Category 3</option> -->
								</select>
							</div>

							<div class="form-group CategorY" style="display: none;">
								<label><?=lang('category')?></label>
								<select name="category" class="form-control" id="main_category">
									<option value="" disabled selected>Choose Category</option>
									<?php foreach($categories as $category){ ?>
										<option value="<?php echo $category['cat_id']; ?>"><?php echo $category['category_name']; ?></option>
									<?php } ?>
									<!-- <option value="2">Category 2</option>
									<option value="3">Category 3</option> -->
								</select>
							</div>
							<div class="form-group SUbCategorY" style="display: none;">
								<label><?=lang('sub_category')?></label>
								<select name="sub_category" class="form-control" id="sub_category">
									<option value="" disabled selected>Choose Sub-Category</option>
								</select>
							</div>
							<div class="form-group">
								<label>Start Date</label>
								<div class="cal-icon"><input class="form-control datetimepicker" type="text" name="budget_start_date" placeholder="Start Date" data-date-format="dd-mm-yyyy" autocomplete="off"></div>
							</div>
							<div class="form-group">
								<label>End Date</label>
								<div class="cal-icon"><input class="form-control datetimepicker" type="text" name="budget_end_date" placeholder="End Date" data-date-format="dd-mm-yyyy" autocomplete="off"></div>
							</div>

							<div class="form-group">
								<label>Expected Revenues</label>
							</div>
							<div class="AllRevenuesClass">
								<div class="row AlLRevenues">
									<div class="col-sm-6">
										<div class="form-group">
											<label>Revenue Title <span class="text-danger">*</span></label>
											<input type="text" class="form-control RevenuETitle" value="" placeholder="Revenue Title" name="revenue_title[]" autocomplete="off" required>
										</div>
									</div>
									<div class="col-sm-5">
										<div class="form-group">
											<label>Revenue Amount <span class="text-danger">*</span></label>
											<input type="text" name="revenue_amount[]" placeholder="Amount" value="" class="form-control RevenuEAmount" autocomplete="off" required>
										</div>
									</div>
									<div class="col-sm-1">
										<div class="add-more">
											<a class="add_more_revenue" title="Add Revenue" style="cursor: pointer;" ><i class="fa fa-plus-circle"></i></a>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label>Overall Revenues <span class="text-danger">(A)</span></label>
								<input class="form-control" type="text" name="overall_revenues" id="overall_revenues" placeholder="Overall Revenues" readonly style="cursor: not-allowed;">
							</div>

							<div class="form-group">
								<label>Expected Expenses</label>
							</div>
							<div class="AllExpensesClass">
								<div class="row AlLExpenses">
									<div class="col-sm-6">
										<div class="form-group">
											<label>Expenses Title <span class="text-danger">*</span></label>
											<input type="text" class="form-control EXpensesTItle" value="" placeholder="Expenses Title" name="expenses_title[]" autocomplete="off" required>
										</div>
									</div>
									<div class="col-sm-5">
										<div class="form-group">
											<label>Expenses Amount <span class="text-danger">*</span></label>
											<input type="text" name="expenses_amount[]" placeholder="Amount" value="" class="form-control EXpensesAmount" autocomplete="off" required>
										</div>
									</div>
									<div class="col-sm-1">
										<div class="add-more">
											<a class="add_more_expenses" title="Add Expenses" style="cursor: pointer;"><i class="fa fa-plus-circle"></i></a>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
								<label>Overall Expense <span class="text-danger">(B)</span></label>
								<input class="form-control" type="text" name="overall_expenses" id="overall_expenses" placeholder="Overall Expenses" readonly style="cursor: not-allowed;">
							</div>


							<div class="form-group">
								<label>Expected Profit <span class="text-danger">( C = A - B )</span> </label>
								<input class="form-control" type="text" name="expected_profit" id="expected_profit" placeholder="Expected Profit" readonly style="cursor: not-allowed;">
							</div>

							<div class="form-group">
								<label>Tax <span class="text-danger">(D)</span></label>
								<input class="form-control" type="text" name="tax_amount" id="tax_amount" placeholder="Tax Amount" required>
							</div>

							<div class="form-group">
								<label>Budget Amount <span class="text-danger">( E = C - D )</span> </label>
								<input class="form-control" type="text" name="budget_amount" id="budget_amount" placeholder="Budget Amount" readonly style="cursor: not-allowed;">
							</div>
							<div class="m-t-20 text-center">
								<button class="btn btn-primary btn-lg BudgetAddBtn">Submit</button>
							</div>
						</form>
					</div>
				</div>
            </div>
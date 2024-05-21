<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button> 
			<h4 class="modal-title"><?=lang('add_expenses')?></h4>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal'); echo form_open(base_url().'invoices/add_expenses',$attributes); ?>
			<input type="hidden" name="invoice" value="<?=$invoice?>">
			<div class="modal-body">
				<?php foreach ($expenses as $key => $e) { 
					if($e->project > 0) $project = Project::by_id($e->project)->project_title;
					else $project = 'N/A';
				?>
				<div class="form-group">
					<div class="col-lg-12 small">
						<div class="col-md-1">
							<input type="checkbox" class="form-control" name="expense[<?=$e->id?>]" value="1">
						</div>
						<div class="col-md-6">
							<?=lang('expense_cost')?>: 
							<strong>
							<?php $cur = ($e->project > 0) 
							? Project::by_id($e->project)->currency 
							: Client::client_currency($e->client)->code;
							?>
							<?=Applib::format_currency($cur, $e->amount)?>
							</strong>
							</br>
							<?=lang('project')?>: 
							<strong>
							<?=$project?> 
							</strong><br>
							<?=lang('category')?>: 
							<strong>
							<?=App::get_category_by_id($e->category);?>
							</strong><br>
						</div>
						<div class="col-md-5">
							<?=lang('expense_date')?>: 
							<strong>
							<?=$e->expense_date?>
							</strong><br>
							<?=lang('notes')?>: 
							<strong>
							<?=$e->notes?>
							</strong>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a> 
				<button type="submit" class="btn btn-success"><?=lang('save_changes')?></button>
			</div>
		</form>
	</div>
</div>
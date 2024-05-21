<div class="content">
	<div class="row">
		<div class="col-xs-4">
			<h4 class="page-title"><?=lang('leads')?></h4>
		</div>
		<div class="col-xs-8 text-right m-b-0">
			<!-- <a href="<?=base_url()?>leads/create" class="btn btn-primary rounded pull-right" data-toggle="ajaxModal" title="<?=lang('new_lead')?>" data-placement="left"><i class="fa fa-plus"></i> <?=lang('new_lead')?></a> -->
			<div class="view-icons">
				<a href="<?=base_url()?>leads?list=table" class="list-view btn btn-link" data-toggle="tooltip" title="Table View" data-placement="bottom"><i class="fa fa-list"></i></a>
				<a href="<?=base_url()?>leads?list=kanban" class=" btn btn-link" data-toggle="tooltip" title="Kanban View" data-placement="bottom"><i class="fa fa-th"></i></a>
			</div>
		</div>
	</div>
	<div class="row">
		<?php if($list_view == 'table') : ?>
		<!-- Display leads as table -->
		<div class="col-lg-12">
			<div class="table-responsive">
				<table id="table-clients" class="table table-striped custom-table m-b-0 AppendDataTables">
					<thead>
						<tr>
							<th><?=lang('company')?> </th>
							<th><?=lang('lead_value')?></th>
						 
							<th><?=lang('lead_stage')?></th>
							<th class="hidden-sm"><?=lang('primary_contact')?></th>
							<th><?=lang('email')?> </th>
							<th class="col-options no-sort"></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($leads as $l) { ?>
						<tr>
							<td>
								<h2>
									<a href="<?=base_url()?>leads/view/<?=$l->co_id?>" class="text-info">
										<?=($l->company_name != NULL) ? $l->company_name : '...'; ?>
									</a>
								</h2>
							</td>
							<td>
								<strong><?=Applib::format_currency($l->currency, $l->transaction_value)?></strong>
							</td>
							 
							<td>
								<span class="label label-default"><?=App::get_category_by_id($l->lead_stage);?></span>
							</td>
							<td class="hidden-sm">
								<?php if ($l->individual == 0) {
									echo ($l->primary_contact) ? User::displayName($l->primary_contact) : 'N/A';
								} ?>
							</td>
							<td><?=$l->company_email?></td>
							<td>
								<a href="<?=base_url()?>leads/delete/<?=$l->co_id?>" class="btn btn-danger btn-xs" data-toggle="ajaxModal" title="<?=lang('delete')?>"><i class="fa fa-trash-o"></i></a>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
		<!-- End Table View -->
		<?php else: ?>
		<div class="col-lg-12" style="overflow-x: scroll;">
			<div class="alert alert-info" id="move_status" style="display:none">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			</div>
			<!-- Start Leads Kanban -->
			<div class="kanban-fluid">
				<div id="sortableKanbanBoards">
					<?php $cards = App::get_by_where('categories', array('module'=>'leads')); ?>
					<!--start list-->
					<?php foreach($cards as $card) : ?>
					<div class="panel kanban-col" style="border-color: #f2f5f7;">
						<div class="panel-heading" style="text-transform: uppercase;">
							<?=$card->cat_name?>
							<i class="fa fa-chevron-down pull-right"></i>
						</div>
						<div class="panel-body">
							<div id="<?=underscore($card->cat_name)?>" class="kanban-centered">
								<?php $leads = App::get_by_where('companies',array('is_lead' => 1,'lead_stage' => $card->id)); ?>
								<?php foreach($leads as $lead) : ?>
								<article class="kanban-entry grab" id="<?=$lead->co_id?>" draggable="true">
									<div class="kanban-entry-inner">
										<div class="kanban-label">
											<?php if($lead->primary_contact > 0) : ?>
											<span class="small pull-right"><?=User::displayName($lead->primary_contact)?></span>
											<?php endif; ?>
											<h2><a href="<?=base_url()?>leads/view/<?=$lead->co_id?>"><?=$lead->company_name?></a></h2>
											<span class="text-success">
												<label class="label label-success"><?=Lead::currency($lead->co_id)->symbol?><?=$lead->transaction_value?></label>
											</span>
											<span class="small pull-right text-muted"><?=count(Lead::tasks($lead->co_id,'done'));?> of <?=count(Lead::tasks($lead->co_id));?> <?=lang('tasks')?></span>
										</div>
									</div>
								</article>
								<?php endforeach; ?>
							</div>
						</div>
						<div class="panel-footer">
							<a href="<?=base_url()?>leads/create?stage=<?=$card->id?>" data-toggle="ajaxModal" class="btn btn-success btn-xs"><?=lang('d')?>Add New Lead</a>
						</div>
					</div>
					<?php endforeach; ?>
					<!-- end list-->
				</div>
			</div>
			<!-- Static Modal -->
			<div class="modal modal-static fade" id="processing-modal" role="dialog" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-body">
							<div class="text-center">
								<i class="fa fa-refresh fa-4x fa-spin"></i>
								<h4>Processing...</h4>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>

<?php if($list_view != 'table') : ?>
<script src="<?=base_url()?>assets/js/jquery-2.2.4.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
         var kanbanCol = $('.panel-body');
         kanbanCol.css('max-height', (window.innerHeight - 150) + 'px');

         var kanbanColCount = parseInt(kanbanCol.length);
         $('.kanban-fluid').css('min-width', (kanbanColCount * 350) + 'px');

         draggableInit();

         $('.panel-heading').click(function() {
             var $panelBody = $(this).parent().children('.panel-body');
             $panelBody.slideToggle();
         });
     });

     function draggableInit() {
         var sourceId;

         $('[draggable=true]').bind('dragstart', function (event) {
             sourceId = $(this).parent().attr('id');
             event.originalEvent.dataTransfer.setData("text/plain", event.target.getAttribute('id'));
         });

         $('.panel-body').bind('dragover', function (event) {
             event.preventDefault();
         });

         $('.panel-body').bind('drop', function (event) {
             var children = $(this).children();
             var targetId = children.attr('id');

             if (sourceId != targetId) {
                 var elementId = event.originalEvent.dataTransfer.getData("text/plain");

                 $('#processing-modal').modal('toggle'); //before post


                 // Post data
                 setTimeout(function () {
                     var element = document.getElementById(elementId);
                     lead_id = element.getAttribute('id');
                     var msgbox = $("#move_status");
                     $.ajax({
                        type: "POST",
                        url: "<?=base_url()?>leads/move_list",
                        data: { 'lead_id': lead_id, 'target' : targetId },
                        success: function(msg){
                            msgbox.html(msg).show().delay(5000).fadeOut();
                       }

                });

                     children.prepend(element);
                     $('#processing-modal').modal('toggle'); // after post
                 }, 1000);

             }

             event.preventDefault();
         });
     }
</script>
<!-- End Leads Kanban -->
<?php endif; ?>
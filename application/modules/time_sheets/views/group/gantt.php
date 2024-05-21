<link rel="stylesheet" href="<?=base_url()?>assets/css/gantt.css" type="text/css" />
 <?php if (!User::is_client() || Project::is_assigned(User::get_id(),$project_id) || Project::setting('show_project_gantt',$project_id)) { ?>

<!-- Gantt Chart Fix for IE -->
<!--[if IE]><!-->
    <style>
        .fn-gantt .day, .fn-gantt .date {
                box-sizing: border-box !important;
                width: 25px !important;
            }
    </style>
<!--<![endif]-->

<div class="contain">
	<div class="row">
		<div class="col-lg-12">
			<div class="card-box">
				<div class="gantt"></div>
			</div>
		</div>
	</div>
</div>

<?php } ?>
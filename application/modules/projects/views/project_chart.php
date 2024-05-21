<?php 
$project_details = $this->db->get_where('projects',array('project_id'=>$project_id))->row_array();
?>

<div class="content">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h4 class="page-title">Project Gannt Chart</h4>
		</div>
	</div>
	<!-- Start Project Form -->
	<div class="row">
		<div class="contain">

    	<h2>
	    		Project Name : <?php echo $project_details['project_title']; ?>
	    	</h2>
	    	<?php if(count($task_list) == 0){  ?><p>No Tasks</p> <?php }else{ ?>
				
				<!-- <div class="gantt"></div> -->
				<div id="chart_div"></div>
			<?php } ?>
		</div>
	</div>
	<!-- End Project Form -->
</div>
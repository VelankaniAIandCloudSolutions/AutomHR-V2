<!-- <style type="text/css">
.modal:nth-of-type(even) {
    /*z-index: 1053 !important;*/
}
.modal-backdrop.show:nth-of-type(even) {
    /*z-index: 1051 !important;*/
}
</style> -->

<div class="content container-fluid">
					<!-- Page Header -->
	<div class="page-header mt-0">
		<div class="row">
			<div class="col-md-6">
				<h3 class="page-title">Knowledgebase</h3>
				<ul class="breadcrumb">
					<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
					<li class="breadcrumb-item active">Knowledgebase</li>
				</ul>
			</div>
			<div class="col-auto float-right ml-auto">
				<?php if(App::is_permit('menu_knowledgebase','create')){ ?>
					<?php if(($this->session->userdata('role_id') == 1) || ( ($this->session->userdata('user_type_name') == 'company_admin'))){ ?>
				<a href="#"  data-toggle="modal" data-target="#add_know"  class="btn add-btn ml-2"><?php echo lang('add_knowledge'); ?>  
				</a>
			<?php }} ?>
				<a href="<?php echo base_url(); ?>ad_knowledge/category_list" class="btn add-btn"><?php echo lang('category'); ?> 
				</a>
			</div>
		</div>
	</div>
	<!-- /Page Header -->
	<div class="row">
		<?php if($knowledge_details) { 
				foreach($knowledge_details as $knowledge) { 
					$category_details = $this->db->get_where('category',array('id'=>$knowledge->category))->row_array();
					// echo "<pre>"; print_r($knowledge_details); exit;
					?>
					<div class="col-xl-4 col-md-6 col-sm-6">
							<!-- <div class="col-auto float-right ml-auto">
								<div class="dropdown dropdown-action position-absoulte d-inline-block">
									<a href="#" class="btn add-btn" data-toggle="dropdown" aria-expanded="false">Manage</a>
									<div class="dropdown-menu dropdown-menu-right">
									</div>
								</div>
							</div> -->
						<div class="topics">
							<div class="row">
							<div class="col">
							<h3 class="topic-title pr-0"><a href="#"><i class="fa fa-folder-o"></i> <?php echo $category_details['category_name'];?> <span><?php echo ($knowledge->views)?$knowledge->views:''; ?></span></a>
								<?php if(App::is_permit('menu_knowledgebase','write') || App::is_permit('menu_knowledgebase','delete')){?>
								<div class="dropdown profile-action" style="top:3px;color:#333;right:0">
									<a href="#" class="action-icon p-0" data-toggle="dropdown"><i class="material-icons text-dark m-0">more_vert</i></a>
									<div class="dropdown-menu dropdown-menu-right">
										<?php if(App::is_permit('menu_knowledgebase','write')){?>
										<a style="font-size:12px" class="dropdown-item text-dark" href="#" data-toggle="modal" data-target="#add_know_<?php echo $knowledge->id; ?>"><i class="fa fa-pencil m-r-5 text-dark"></i> Edit</a>
									<?php } ?>
									<?php if(App::is_permit('menu_knowledgebase','delete')){?>
										<a style="font-size:12px" class="dropdown-item text-dark" href="#" data-toggle="modal" data-target="#delete_know_<?php echo $knowledge->id; ?>"><i class="fa fa-trash-o m-r-5 text-dark"></i> Delete</a>
										<?php } ?>
									</div>
								</div>
							<?php } ?>
							</h3>
								
								
								</div>
							
								
								<!--<div class="col-auto">
							<a href="#" data-toggle="modal" data-target="#add_know_<?php echo $knowledge->id; ?>"><i class="fa fa-pencil m-r-5"></i></a>
								<a href="#" data-toggle="modal" data-target="#delete_know_<?php echo $knowledge->id; ?>"><i class="fa fa-trash-o m-r-5"></i></a>
							</div>-->
							</div>
							<ul class="topics-list">
								<?php $topics = $this->db->select('*')->get_where('ad_knowledge_topic', array('knowledge_id'=>$knowledge->id))->result();
									foreach ($topics as $key => $value) { ?>
										<?php /*<li><a href="<?php echo base_url();?>ad_knowledge/ad_knowledge_view/<?php echo $knowledge->id;?>"> <?php echo $value->topic;?></a>
										</li>*/?>
										<li><a href="javascript:void(0);"> <?php echo $value->topic;?></a>
										</li>
									<?php } ?>
							</ul>
						</div>
					</div>
			<?php } 
			} else { ?>
				<div class="col-md-12 col-sm-12">
					<div class="topics" style="text-align:center;">Details Not found</div>
				</div>
			<?php }	?>
	
	</div>
</div>
	
<div id="add_know" class="modal custom-modal fade" role="dialog">
		<div class="modal-dialog modal-dialog-centered"  role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><?php echo lang('add_knowledge'); ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<?php $attributes = array('id' => 'knowledge_submit'); echo form_open(base_url().'ad_knowledge/addKnowledge',$attributes); ?>
						<div class="form-group">
							<label><?php echo lang('category'); ?> <span class="text-danger">*</span></label>
							<select class="form-control" name="category" id="category">
								<option value="">Select Category</option>
								<?php foreach($categories as $category) { ?>
									<option value="<?php echo $category->id; ?>"><?php echo $category->category_name; ?></option>
								<?php } ?>
							</select>
							<!-- <input class="form-control" type="text" name="category" id="category"> -->
						</div>
						<div class="form-group">
							<label><?=lang('branch')?> <span class="text-danger">*</span></label>
							<select class="form-control" style="width:100%;"  name="branch_id" id="add_branch" required >
								<option value="">Select</option>
								<?php if(!empty($branches))	{
									foreach ($branches as $branch1){ ?>
										<option value="<?=$branch1['branch_id']?>" ><?=$branch1['branch_name']?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
							<?php /*<div class="add-more">
								<a href="#myModal2" data-toggle="modal" data-target="#myModal2"><i class="fa fa-plus-circle"></i> Add Category</a>
							</div>*/?>
						<!-- <div class="form-group">
							<label><?php echo lang('title'); ?> <span class="text-danger">*</span></label>
							<input class="form-control" type="text" name="title" id="title">
						</div> -->
						<div class="form-group topics1">
							<label><?php echo lang('topic'); ?> <span class="text-danger">*</span></label>
							<textarea class="form-control" name="topic[]" id="topic"></textarea>
						</div>
						<div class="form-group">
							<label><?php echo lang('description'); ?> </label>
							<textarea class="form-control foeditor-550" name="description" id="description"></textarea>
						</div>
						<div class="submit-section">
							<button class="btn btn-primary submit-btn" id="submit_knowledge"><?php echo lang('submit'); ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
</div>	

<!-- <div id="add_category" class="modal" role="dialog" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php //echo lang('add_category'); ?></h5>
				<button type="button" class="close" id="category_form" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php $attributes //= array('id' => 'category_submit'); echo form_open(base_url().'ad_knowledge/addCategory',$attributes); ?>
					<div class="form-group">
						<label><?php //echo lang('category_name'); ?> <span class="text-danger">*</span></label>
						<input class="form-control" type="text" name="category_name" id="category_name" required="">
					</div>
					<div class="submit-section">
						<button class="btn btn-primary submit-btn" id="submit_category"><?php //echo lang('submit'); ?></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
 -->
<?php if($knowledge_details) { 
				foreach($knowledge_details as $knowledge) { ?>

<div id="add_know_<?php echo $knowledge->id; ?>" class="modal" role="dialog">  <!-- custom-modal fade -->
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Update Knowledge</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<?php $attributes = array('id' => 'edit_knowledge_submit','class'=>'edit_knowledge_submit'); echo form_open(base_url().'ad_knowledge/addKnowledge',$attributes); ?>
						<div class="form-group">
							<input class="form-control" type="hidden" name="knowledge_id" id="knowledge_id" value="<?php echo $knowledge->id; ?>">
							<label><?php echo lang('category'); ?> <span class="text-danger">*</span></label>
							<select class="form-control" name="category" id="category" required="">
								<option value="">Select Category</option>
								<?php foreach($categories as $category) { ?>
									<option value="<?php echo $category->id; ?>" <?=!empty($knowledge->category==$category->id)?'selected':'';?>><?php echo $category->category_name; ?></option>
								<?php } ?>
							</select>
							<div class="add-more">
								<a href="#" data-toggle="modal" data-target="#myModal2"><i class="fa fa-plus-circle"></i> Add</a>
							</div>
						</div>
						<!-- <div class="form-group">
							<label><?php echo lang('title'); ?> <span class="text-danger">*</span></label>
							<input class="form-control" type="text" name="title" id="title" value="<?php echo $knowledge->title; ?>" required="">
						</div> -->
						<?php /* <div class="form-group topics1">
							<label><?php echo lang('topic'); ?> <span class="text-danger">*</span></label>
							<textarea class="form-control" name="topic[]" id="topic"><?php echo $knwldge_data->topic; ?></textarea>
							<div class="add-more">
								<a href="#" id="add_more_topic"><i class="fa fa-plus-circle"></i> Add More</a>
							</div>
						</div> */ ?>
						<div class="form-group topics1">
							<label><?php echo lang('topic'); ?> <span class="text-danger">*</span></label>
							<?php //$i = 1; foreach($topics as $topic) {
								$topicss = $this->db->get_where('ad_knowledge_topic',array('knowledge_id'=>$knowledge->id))->row_array();
							 ?>
								<textarea class="form-control" name="topic[]" id="topic_<?php echo $knowledge->id;?>" required=""><?php echo $topicss['topic']; ?></textarea>
							<?php //if($i != 1) { ?> 
								<!-- <a href="#" id="remove_topic_<?php echo $topic->id;?>" class="remove_topic" onclick="removeTopic('<?php echo $topic->id;?>')"><i class="fa fa-trash-o"></i></a> <?php // } $i++;   }?> -->
							<!-- <div class="add-more">
								<a href="#" id="add_more_topic"><i class="fa fa-plus-circle"></i> Add More</a>
							</div> -->
						</div>
						<div class="form-group">
							<label><?php echo lang('description'); ?> </label>
							<textarea class="form-control foeditor-550" name="description" id="description"><?php echo $knowledge->description; ?></textarea>
						</div>
						<div class="submit-section">
							<button class="btn btn-primary submit-btn edit_submit_knowledge" id="edit_submit_knowledge"><?php echo lang('submit'); ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
</div>	

<div id="delete_know_<?php echo $knowledge->id; ?>" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-danger">
				<button type="button" class="close" data-dismiss="modal">&times;</button> 
				<h4 class="modal-title">Delete Knowledge</h4>
			</div>
			<?php $attributes = array('id' => 'knowledge_submit'); echo form_open(base_url().'ad_knowledge/delete_knowledge',$attributes); ?>
				<div class="modal-body">
					<p>Are you sure want to delete?</p>
					<input type="hidden" name="knowledge_id" id="knowledge_id" value="<?=$knowledge->id?>"> 
				</div>
				<div class="modal-footer"> 
					<button type="submit" class="btn btn-danger"><?php echo lang('delete'); ?></button>
					<a href="#" class="btn btn-default" data-dismiss="modal"><?php echo lang('close')?></a>
				</div>
			</form>
		</div>
	</div>
</div>	


<?php } } ?>

<div class="modal next-modal" id="myModal2" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><?php echo lang('add_category'); ?></h4>
           <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
        </div><div class="container"></div>
        <div class="modal-body">
          <?php $attributes = array('id' => 'category_submit'); echo form_open(); ?>
					<div class="form-group">
						<label><?php echo lang('category_name'); ?> <span class="text-danger">*</span><span id="already_category_name" style="display: none;color:red;">Category name already exists</span></label>
						<input class="form-control" type="text" name="category_name" id="category_name" required value="">
					</div>
					<div class="submit-section">
						 <a href="#" data-dismiss="modal" class="btn btn-danger submit-btn">Close</a>
						<button class="btn btn-primary submit-btn" id="new_submit_category"><?php echo lang('submit'); ?></button>
					</div>
				</form>
        </div>
        <!-- <div class="modal-footer">
          <a href="#" data-dismiss="modal" class="btn">Close</a>
          <a href="#" class="btn btn-primary">Save changes</a>
        </div> -->
      </div>
    </div>
</div>

<script type="text/javascript">
	$('#category_form').click(function() {
		$('#add_category').modal('hide');
	})
</script>							
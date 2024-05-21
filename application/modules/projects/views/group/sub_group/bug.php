<?php
  $bug = isset($_GET['id']) ? $_GET['id'] : 0;
  $i = Project::view_bug($bug);
    ?>
        <div class="row m-b-0">
          <div class="col-sm-12">
            <a href="<?=base_url()?>projects/bugs/file/<?=$i->project?>/<?=$i->bug_id?>" data-toggle="ajaxModal" class="btn btn-sm btn-primary m-l-10"><?=lang('attach_file')?></a>
            <?php if (User::is_admin() || Project::is_assigned(User::get_id(),$i->project)) { ?>
              <a href="<?=base_url()?>projects/bugs/edit/<?=$i->project?>/?id=<?=$i->bug_id?>" data-toggle="ajaxModal" class="btn btn-sm btn-success"><?=lang('edit_bug')?></a> 
            <?php } ?>
          </div>
        </div>
      <div class="row padding-12p">
        <div class="col-lg-12">
          <section class="panel">
            <div class="panel-body">
              <div class="row">
                <div class="col-lg-6">
                  <ul class="list-group no-radius m-b-0">
                    <li class="list-group-item">
                      <span class="pull-right">

                      <strong>
                      <?php echo Project::by_id($i->project)->project_title; ?> 
                      </strong>
                      </span><?=lang('project')?>
                    </li>

                    <li class="list-group-item">
                      <span class="pull-right"><strong><?=$i->issue_title?></strong></span><?=lang('issue_title')?>
                    </li>

                    <li class="list-group-item">
                      <span class="pull-right"><strong><?=$i->issue_ref?></strong></span><?=lang('issue_ref')?>
                    </li>

                    <li class="list-group-item">
                      <span class="pull-right">
                      <strong><?=ucfirst(User::displayName($i->reporter))?></strong>
                      </span><?=lang('reporter')?>
                    </li>
                    <?php if (User::is_admin() || Project::is_assigned(User::get_id(),$i->project)) { ?>
                      <li class="list-group-item">
                        <span class="pull-right">
                        <strong>
                            <?php echo ($i->assigned_to > 0) ? User::displayName($i->assigned_to) : 'N/A'; ?>
                        </strong>
                        </span><?=lang('assigned_to')?>
                      </li>
                    <?php } ?>   
                  </ul>
                </div>
                <!-- End details C1-->
                <div class="col-lg-6">
                  <ul class="list-group no-radius m-b-0">
                    <li class="list-group-item">
                      <span class="pull-right"><strong><?=$i->severity?></strong></span><?=lang('severity')?>
                    </li>
                    <li class="list-group-item">
                      <span class="pull-right"><strong><?=lang(strtolower($i->bug_status))?></strong></span><?=lang('bug_status')?>
                    </li>
                    <li class="list-group-item">
                      <span class="pull-right"><strong><?=ucfirst($i->priority)?></strong> </span>
                      <?=lang('priority')?>
                    </li>


                    <li class="list-group-item">
                      <span class="pull-right"><strong><?=strftime(config_item('date_format'), strtotime($i->reported_on));?></strong>
                      </span><?=lang('reported_on')?>
                    </li>

                    <li class="list-group-item">
                      <span class="pull-right">
                      <span class="label label-success">
                        <strong><?=strftime(config_item('date_format'), strtotime($i->last_modified));?></strong>
                      </span>
                      </span><?=lang('last_modified')?>
                    </li>    
                  </ul>
                </div>
              </div>
              <!-- End details -->
            </div>
          </section>
						<div class="panel uploaded-files m-t-3">
                            <div class="panel-body">
                                <h5 class="panel-title m-b-20">Uploaded files</h5>
                                <ul class="files-list">
									<?php
									$this->load->helper('file');
									foreach (Project::bug_has_files($bug) as $key => $f) {
									$icon = $this->applib->file_icon($f->file_ext);
									$real_url = base_url().'assets/bug-files/'.$f->file_name;
									?>
									<li>
                                        <div class="files-cont">
                                            <div class="file-type">
											   <?php if ($f->is_image == 1) : ?>
												<?php if ($f->image_width > $f->image_height) {
													$ratio = round(((($f->image_width - $f->image_height) / 2) / $f->image_width) * 100);
													$style = 'height:100%; margin-left: -'.$ratio.'%';
												} else {
													$ratio = round(((($f->image_height - $f->image_width) / 2) / $f->image_height) * 100);
													$style = 'width:100%; margin-top: -'.$ratio.'%';
												}  ?>
													<a href="<?=base_url()?>projects/bugs/preview/<?=$f->file_id?>/<?=$i->project?>" data-toggle="ajaxModal"><img style="<?=$style?>" src="<?=$real_url?>" /></a>
												<?php else : ?>
													<div class="files-icon"><i class="fa <?=$icon?>"></i></div>
												<?php endif; ?>
											</div>
                                            <div class="files-info">
                                                <span class="file-name text-ellipsis">
													<a data-toggle="tooltip" data-placement="top" data-original-title="<?=$f->description?>" class="text-info" href="<?=base_url()?>projects/bugs/download/<?=$i->project?>/<?=$f->file_id?>">
														<?=(empty($f->title) ? $f->file_name : $f->title)?>
													</a>
												</span>
                                                <span class="file-author"><a href="javascript:void(0);"><?php echo User::displayName($f->uploaded_by)?></a></span> <span class="file-date"><?=strftime(config_item('date_format')." %H:%m",strtotime($f->date_posted));?></span>
                                                <div class="file-size">Size: <?=number_format($f->size,0,config_item('decimal_separator'),  config_item('thousand_separator'))?> KB</div>
                                            </div>
										<?php  if($f->uploaded_by == User::get_id() || User::is_admin()){ ?>
                        
                                            <ul class="files-action">
                                                <li class="dropdown">
                                                    <a href="" class="dropdown-toggle btn btn-default btn-xs" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="<?=base_url()?>projects/bugs/file/edit/<?=$f->file_id?>/<?=$i->project?>" data-toggle="ajaxModal"><i class="fa fa-edit"></i> Edit</a></li>
                                                        <li><a href="<?=base_url()?>projects/bugs/file/delete/<?=$f->file_id?>/<?=$i->project?>" data-toggle="ajaxModal"><i class="fa fa-edit"></i> Delete</a></li>
                                                    </ul>
                                                </li>
                                            </ul>
											<?php } ?>
										</div>
                                    </li>
								<?php } ?>
								</ul>
                            </div>
                        </div>
		<div class="card-box m-t-3">
			<h3 class="card-title">Bug Description</h3>
			<div class="bug-cont m-b-30"><?=nl2br_except_pre($i->reproducibility)?></div>
			<h3 class="card-title">Reproducibility</h3>
			 
			<div class="bug-desc"><?=nl2br_except_pre($i->bug_description)?></div>
		</div>
          <!-- Start Comments -->
          <div class="row padding-12p">
            <div class="col-lg-12">
              <section class="panel panel-body">
                <section class="comment-list block">
                  <article class="comment-item media" id="comment-form">
                  
                    <a class="pull-left thumb-sm avatar">
                    <img src="<?php echo User::avatar_url(User::get_id()); ?>" class="img-circle">
                    </a>


                     
                    <section class="media-body">
                      <section class="panel panel-default">
                        <?php 
                        $attributes = 'class="m-b-none"';
                        echo form_open(base_url().'projects/bugs/comment/',$attributes); ?>
                          <input type="hidden" name="bug_id" value="<?=$bug?>">
                          <input type="hidden" name="project" value="<?=$i->project?>">
                          <textarea class="form-control foeditor-100" name="comment" placeholder="<?=lang('issue_ref')?><?=$i->issue_ref?> <?=lang('comment')?>"></textarea>
                          <footer class="panel-footer bg-light lter">
                             <button class="btn btn-success pull-right btn-sm" type="submit"><?=lang('post_comment')?></button>
                            <ul class="nav nav-pills nav-sm"></ul>
                          </footer>
                        </form>
                      </section>
                    </section>
                  </article>
                  <?php foreach (Project::bug_has_comments($bug) as $key => $c) {
                    $role_label = (User::login_info($c->comment_by) == '1') ? 'danger' : 'info';
                  ?> 
                          <article class="comment-item">
                            <a class="pull-left thumb-sm avatar">
                      <img src="<?php echo User::avatar_url($c->comment_by); ?>" class="img-circle">
                            </a>
                            <span class="arrow left"></span>
                            <section class="comment-body panel panel-default">
                              <header class="panel-heading bg-white">
                                <a href="#"><?=ucfirst(User::displayName($c->comment_by))?></a>
                                <label class="label bg-<?=$role_label?> m-l-xs">
                                <?php echo User::get_role($c->comment_by); ?></label> 
                                <span class="text-muted m-l-sm pull-right">
<?php echo strftime(config_item('date_format')." %H:%M:%S", strtotime($c->date_commented)) ?>
                                <?php
                  if(config_item('show_time_ago') == 'TRUE'){
                    echo ' - '.Applib::time_elapsed_string(strtotime($c->date_commented));
                  } ?>


                  <?php if($c->comment_by == User::get_id()){ ?>

                     <a href="<?=base_url()?>projects/bugs/delete_comment/<?=$c->c_id?>" data-toggle="ajaxModal" title="<?=lang('comment_reply')?>"><i class="fa fa-trash-o text-danger"></i>
                     </a>
                  <?php } ?>
                                </span>
                              </header>

                              <div class="panel-body">
                                <div class="text-muted small activate_links"><?=nl2br_except_pre($c->comment)?></div>
                               
                              </div>

                            </section>
                          </article>
                        <?php } ?>

                        <?php if(count(Project::bug_has_comments($bug)) <= 0) { ?>
                          <article class="comment-item">
                            <section class="comment-body panel panel-default">
                              <div class="panel-body">
                                <p>No comments found</p>
                              </div>
                            </section>
                          </article>
                        <?php } ?>

                      </section>
                    </section>
                  </div>
                </div>
              <!-- END COMMENTS -->
                 </div> 
      </div>
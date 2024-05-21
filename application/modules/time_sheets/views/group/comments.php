<style>
img {max-width: 100%; height: auto;}
  .note-editor.note-frame {
    border: none;
}
</style>

<div class="row">
    <div class="col-lg-12">
      <section class="panel panel-body">
        <section class="comment-list block">
          <article class="comment-item media" id="comment-form">
            <?php
              if (!User::is_client() || Project::setting('show_project_comments',$project_id)) { ?>
              <a class="pull-left thumb-sm avatar">


          <img src="<?php echo User::avatar_url(User::get_id()); ?>" class="img-circle">

          </a>

              <section class="media-body">
                <section class="panel panel-default">
                  <?php
                  $attributes = 'class="m-b-none"';
                  echo form_open(base_url().'projects/comment/',$attributes); ?>
                    <input type="hidden" name="project" value="<?=$project_id?>">
              <textarea class="form-control foeditor-100" name="comment"
              placeholder="<?php echo Project::by_id($project_id)->project_title; ?> <?=lang('comment')?>" ></textarea>
                    <footer class="panel-footer bg-light lter">
                      <button class="btn btn-success pull-right btn-sm" type="submit">
                      <i class="fa fa-comments"></i> <?=lang('comment')?></button>
                      <ul class="nav nav-pills nav-sm">
                      </ul>
                    </footer>
                  </form>
                </section>
              </section>
          </article>

  <?php foreach (Project::has_comments($project_id) as $key => $c) { ?>
     <?php $this->db->where('comment_id',$c->comment_id)->update('comments',array('unread' => 0)); ?>

          <?php $role_label = (User::get_role($c->posted_by) == 'admin') ? 'danger' : 'info'; ?>
            <article class="comment-item">
              <a class="pull-left thumb-sm avatar">

  <img src="<?php echo User::avatar_url($c->posted_by); ?>" class="img-circle">

              </a>
              <span class="arrow left"></span>
              <section class="comment-body panel panel-default">
                <header class="panel-heading bg-white">
                  <a href="#">
                  <?=ucfirst(User::displayName($c->posted_by))?>
                  </a>
                  <label class="label bg-<?=$role_label?> m-l-xs"><?=ucfirst(User::get_role($c->posted_by))?> </label>
                  <span class="text-muted m-l-sm pull-right">
                  <i class="fa fa-clock-o"></i>
                  <?php echo strftime(config_item('date_format')." %H:%M:%S", strtotime($c->date_posted)) ?>
                  <?php
                  if(config_item('show_time_ago') == 'TRUE'){
                    echo ' - '.humanFormat(strtotime($c->date_posted)).' '.lang('ago');
                  }?>

                    <a href="<?=base_url()?>projects/replies?c=<?=$c->comment_id?>&p=<?=$project_id?>" data-toggle="ajaxModal" title="<?=lang('comment_reply')?>"><i class="fa fa-comment text-primary"></i>
                    </a>
                    <?php
                    if($c->posted_by == User::get_id()){ ?>

                     <a href="<?=base_url()?>projects/delete_comment/<?=$c->comment_id?>" data-toggle="ajaxModal" title="<?=lang('comment_reply')?>"><i class="fa fa-trash-o text-danger"></i>
                     </a>
                    <?php } ?>


                  </span>
                </header>
                <div class="panel-body">
                  <div class="text-dark small activate_links"><?php echo nl2br_except_pre($c->message)?></div>
                    <div class="comment-action m-t-sm">



                  </div>
                </div>




                <?php foreach (Project::has_replies($c->comment_id) as $key => $reply) { ?>
                      <article id="comment-id-2" class="comment-item comment-reply">
                          <?php if(User::check_user_exist($reply->replied_by)) { ?>

<a class="pull-left thumb-sm avatar">

  <img src="<?php echo User::avatar_url($reply->replied_by); ?>" class="img-circle">

</a>
<?php } else { echo lang('user_not_found'); } ?>

                          <span class="arrow left"></span>
                        <section class="comment-body panel panel-default text-sm">
                          <div class="panel-body">
                          <span class="text-muted m-l-sm pull-right">
              <?php echo strftime(config_item('date_format')." %H:%M:%S", strtotime($reply->date_posted)) ?>
                          <?php
                        if(config_item('show_time_ago') == 'TRUE'){
                        $ts = $reply->date_posted;
                        $convertedTime = (Applib::convert_datetime($ts));
                        echo ' - '.Applib::makeAgo($convertedTime);
                      }
                        ?>
                              <?php
                    if($reply->replied_by == User::get_id()){ ?>

                     <a href="<?=base_url()?>projects/delete_reply/<?php echo $reply->reply_id; ?>" data-toggle="ajaxModal" title="<?php echo lang('comment_reply'); ?>"><i class="fa fa-trash-o text-danger"></i>
                     </a>
                    <?php } ?>




                              </span>
                        <?php if (User::check_user_exist($reply->replied_by)) { ?>
                              <span class="text-danger">
                              <?php echo ucfirst(User::displayName($reply->replied_by)); ?></span>
                            <?php }?>
                          <p><span class="text-dark activate_links"><?php echo $reply->reply_msg; ?></span></p>

                          </div>
                         </section>
                      </article>
                      <?php } ?>
              </section>
            </article>
          <?php } ?>
          <?php if(count(Project::has_comments($project_id)) == 0){ ?>
            <article class="comment-item">
              <section class="comment-body panel panel-default">
                <div class="panel-body">
                  <p><?=lang('no_comments_found')?></p>
                </div>
              </section>
            </article>
            <?php } ?>
          <?php } ?>
        </section>
      </section>
    </div>
  </div>

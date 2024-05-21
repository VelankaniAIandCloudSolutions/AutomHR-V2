<style>
img {max-width: 100%; height: auto;}
  .note-editor.note-frame {
    border: none;
}
</style>
<div class="row">
<div class="col-lg-12">
  <section class="card card-body">
    <section class="comment-list block">
      <article class="comment-item media" id="comment-form">
          <a class="float-left thumb-sm avatar">
      <img src="<?php echo User::avatar_url(User::get_id()); ?>" class="rounded-circle">
      </a>

          <section class="media-body">
            <section class="">
              <?php
              $attributes = 'class="m-b-none"';
              echo form_open(base_url().'leads/comment',$attributes); ?>
                <input type="hidden" name="client_id" value="<?=$l->co_id?>">
          <textarea class="form-control foeditor-100" name="comment"
          placeholder="<?php echo $l->company_name; ?> <?=lang('comment')?>" required></textarea>
                <footer class="card-footer bg-light lter">
                  <button class="btn btn-success float-right btn-sm" type="submit">
                  <i class="fa fa-comments"></i> <?=lang('comment')?></button>
                  <ul class="nav nav-pills nav-sm">
                  </ul>
                </footer>
              </form>
            </section>
          </section>
      </article>

<?php foreach (Lead::comments($l->co_id) as $key => $c) {
    if($c->posted_by != User::get_id()) :
            $this->db->where('comment_id',$c->comment_id)->update('comments',array('unread' => 0));
    endif;
    ?>

      <?php $role_label = (User::get_role($c->posted_by) == 'admin') ? 'danger' : 'info'; ?>
        <article class="comment-item">
          <a class="float-left thumb-sm avatar">

<img src="<?php echo User::avatar_url($c->posted_by); ?>" class="rounded-circle">

          </a>
          <span class="arrow left"></span>
          <section class="comment-body card card-default">
            <header class="card-header bg-white">
              <a href="#">
              <?=ucfirst(User::displayName($c->posted_by))?>
              </a>
              <label class="badge bg-<?=$role_label?> m-l-xs"><?=ucfirst(User::get_role($c->posted_by))?> </label>
              <span class="text-muted m-l-sm float-right">
                  <?php echo humanFormat(strtotime($c->date_posted)).' '.lang('ago'); ?>
                <?php
                if($c->posted_by == User::get_id()){ ?>

                 <a href="<?=base_url()?>leads/comment/<?=$c->comment_id?>/delete" data-toggle="ajaxModal" title="<?=lang('comment_reply')?>"><i class="fa fa-trash-o text-danger"></i>
                 </a>
                <?php } ?>


              </span>
            </header>
            <div class="card-body">
              <div class="text-dark activate_links"><?php echo nl2br_except_pre($c->message)?></div>
                <div class="comment-action m-t-sm">



              </div>
            </div>

          </section>
        </article>
      <?php } ?>
      <?php if(count(Lead::comments($l->co_id)) == 0){ ?>
        <article class="comment-item">
          <section class="comment-body card card-default">
            <div class="card-body">
              <p><?=lang('no_comments_found')?></p>
            </div>
          </section>
        </article>
        <?php } ?>
    </section>
  </section>
</div>
</div>

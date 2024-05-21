<div class="panel panel-white">
  <?php
  $link_id = isset($_GET['id']) ? $_GET['id'] : 0;
  $link = Project::view_link($link_id);
  if($link->project_id == $project_id){ ?>
    <div class="panel-heading">
      <div class="row">
        <div class="col-sm-12 m-b-xs">
          <?php if(User::is_admin()){ ?>
            <a href="<?=base_url()?>projects/links/edit/<?=$link->link_id?>" data-toggle="ajaxModal" class="btn btn-success btn-sm"><?=lang('edit_link')?></a>
            <a href="<?=base_url()?>projects/links/delete/<?=$link->project_id?>/<?=$link->link_id?>" data-toggle="ajaxModal" title="<?=lang('delete_link')?>" class="btn btn-danger btn-sm"><i class="fa fa-trash-o text-white"></i> <?=lang('delete_link')?></a>
          <?php }?>
        </div>
      </div>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-lg-7">
          <ul class="list-group no-radius">
            <li class="list-group-item">
              <span class="pull-right"><?=$link->link_title?> </span><?=lang('link_title')?>
            </li>
            <li class="list-group-item">
                <span class="pull-right"><a href="<?=$link->link_url?>" target="_blank"><?=$link->link_url?> </a> </span><?=lang('link_url')?>
            </li>
            <li class="list-group-item">
              <span class="pull-right"><?php echo Project::by_id($link->project_id)->project_title; ?></span><?=lang('project')?>
            </li>
          </ul>
        </div>
        <!-- End details C1-->
        <div class="col-lg-5">
          <ul class="list-group no-radius">
            <li class="list-group-item">
              <span class="pull-right"><?=$link->username?></span><?=lang('username')?>
            </li>
            <li class="list-group-item">
              <span class="pull-right"><input id="link-password" class="discreet" type="password" value="<?=$link->password?>" /></span><?=lang('password')?>
            </li>
          </ul>
        </div>
      </div>
      <p><blockquote class="small text-muted"><?=nl2br_except_pre($link->description)?></blockquote></p>
    </div>
    <!-- End details -->
    <!-- End ROW 1 -->
  <?php } ?>
</div>

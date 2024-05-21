<div class="row">
	<div class="col-md-12">
		<div class="card files-panel">
			<div class="card-header">
				<div class="row">
					<div class="col-6">
						<h3 class="card-title"><?=lang('files')?></h3>
					</div>
					<div class="col-6">
						<a href="<?=base_url()?>leads/file/add/<?=$l->co_id?>" class="btn btn-success btn-sm float-right" data-toggle="ajaxModal" data-placement="left" title="<?=lang('upload_file')?>">
							<i class="fa fa-plus-circle"></i> <?=lang('upload_file')?>
						</a>
					</div>
				</div>
			</div>
			<div class="card-body">
				<ul class="files-list list-group no-radius">
					<?php $this->load->helper('file');
					foreach (Lead::files($l->co_id) as $key => $f) {
					$icon = $this->applib->file_icon($f->ext);
					$real_url = base_url().'assets/uploads/'.$f->file_name;
					?>
					<li class="list-group-item">
						<div class="files-cont">
							<?php if ($f->is_image == 1) : ?>
							<?php if ($f->image_width > $f->image_height) {
								$ratio = round(((($f->image_width - $f->image_height) / 2) / $f->image_width) * 100);
								$style = 'height:100%; margin-left: -'.$ratio.'%';
							} else {
								$ratio = round(((($f->image_height - $f->image_width) / 2) / $f->image_height) * 100);
								$style = 'width:100%; margin-top: -'.$ratio.'%';
							} ?>
							<div class="file-type">
								<a href="<?=base_url()?>leads/file/<?=$f->file_id?>"><img style="<?=$style?>" src="<?=$real_url?>" /></a>
							</div>
							<?php else : ?>
								<div class="file-type"><i class="fa <?=$icon?> fa-lg"></i></div>
							<?php endif; ?>
							<span class="file-name">
								<a data-toggle="tooltip" data-placement="right" data-original-title="<?=$f->description?>" class="text-muted" href="<?=base_url()?>leads/file/<?=$f->file_id?>">
									<?=(empty($f->title) ? $f->file_name : $f->title)?>
								</a>
							</span>
							
							<div class="files-action">
								<a href="<?=base_url()?>leads/file/delete/<?=$f->file_id?>" data-toggle="ajaxModal"><i class="fa fa-trash-o text-danger"></i></a>
							</div>
						</div>
					</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
</div>
<!-- End Files section -->
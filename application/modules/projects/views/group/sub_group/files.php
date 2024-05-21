<?php $this->load->helper('file'); ?>

<div class="row">
<div class="panel panel-white m-b-0">

	<div class="panel-heading panel-h-p1">

		<div class="row">

			<div class="col-xs-6">

				<h3 class="panel-title m-t-6"><?=lang('files')?></h3>

			</div>

			<div class="col-xs-6 text-right">

				<a href="<?=base_url()?>projects/files/add/<?=$project_id?>" data-toggle="ajaxModal" class="btn btn-sm btn-primary"><?=lang('upload_file')?></a>

			</div>

		</div>

		<div class="row">

			<div class="col-sm-12">

				

			</div>

		</div>
</div>

</div>

                <?=$this->session->flashdata('form_error')?>

    <div class="table-responsive">

                  <table id="table-files" class="table table-striped b-t b-light small">

                    <thead>

                      <tr>

                        <th class=""><?=lang('files')?></th>

                        <th class=""><?=lang('size')?></th>

                        <th class=""><?=lang('date')?></th>

                        <th class=""><?=lang('user')?></th>

                        <th class="col-options no-sort text-right"><?=lang('options')?></th>

                      </tr>

                    </thead>

                    <tbody>

        <?php foreach (Project::has_files($project_id) as $key => $f) {

                  $icon = $this->applib->file_icon($f->ext);

                  $path = $f->path;

                  $fullpath = base_url().'assets/project-files/'.$path.$f->file_name;

                    if($path == NULL){

                        $fullpath = base_url().'assets/project-files/'.$f->file_name;

                    }



                  $real_url = $fullpath;

                  ?>

                      <tr class="file-item">

                        <td>

                            <?php if ($f->is_image == 1) : ?>

                            <?php if ($f->image_width > $f->image_height) {

                                $ratio = round(((($f->image_width - $f->image_height) / 2) / $f->image_width) * 100);

                                $style = 'height:100%; margin-left: -'.$ratio.'%';

                            } else {

                                $ratio = round(((($f->image_height - $f->image_width) / 2) / $f->image_height) * 100);

                                $style = 'width:100%; margin-top: -'.$ratio.'%';

                            }  ?>

                        <div class="file-icon">

                        <a href="<?=base_url()?>projects/files/preview/<?=$f->file_id?>/<?=$project_id?>" data-toggle="ajaxModal"><img style="<?=$style?>" src="<?=$real_url?>" />

                        </a>

                        </div>

                            <?php else : ?>



                        <div class="file-icon"><i class="fa <?=$icon?>"></i>

                        </div>

                            <?php endif; ?>



                        <a data-toggle="tooltip" data-placement="top" data-original-title="<?=$this->applib->short_string($f->file_name,25,5,30)?>" class="text-info" href="<?=base_url()?>projects/download/<?=$f->project?>?group=files&id=<?=$f->file_id?>">

                        <?=$f->title?>

                        <?php if ($f->is_image == 1) : ?>

                        <em><?=$f->image_width."x".$f->image_height?></em>

                        <?php endif; ?>

                        </a>



                        <p class="file-text activate_links"><?=nl2br_except_pre($this->applib->short_string($f->description,100,0,100))?>

                        </p>

                        </td>



                        <td class=""><?=number_format($f->size,0,config_item('decimal_separator'),  config_item('thousand_separator'))?> KB</td>

                        <td class="col-date">

                           <?php 

                            $timezone = $this->session->userdata('timezone');

                            $date_posted = Applib::UTC_time_to_localtime($f->date_posted,$timezone);

                            $date_posted = date(str_replace('%','',config_item('date_format')).' H:i',strtotime($date_posted));

                         ?>

                        <?=$date_posted;?></td>

                        <td>

                        <a class="pull-left thumb-sm avatar">

                        

                          <img src="<?php echo User::avatar_url($f->uploaded_by); ?>" class="img-circle" data-toggle="tooltip" data-title="<?php echo User::displayName($f->uploaded_by)?>" data-placement="top">

                        

                          </a>

                        </td>

                        <td class="text-right">

                          <?php  if($f->uploaded_by == User::get_id() || User::is_admin()){ ?>

                          <a class="btn btn-xs btn-danger" href="<?=base_url()?>projects/files/delete/<?=$f->project?>?group=files&id=<?=$f->file_id?>" data-toggle="ajaxModal"><i class="fa fa-trash-o"></i></a>

                          <a class="btn btn-xs btn-default" href="<?=base_url()?>projects/files/edit/<?=$f->project?>?group=files&id=<?=$f->file_id?>" data-toggle="ajaxModal"><i class="fa fa-edit"></i></a>

                          <?php } ?>

                        </td>



                      </tr>

                      <?php } ?>

                    </tbody>

                  </table>

                </div>



<!-- End details -->

 </div>


<style>
  * {
    box-sizing: border-box;
  }

  .fb-main {
    min-height: 600px;
  }

  input[type=text] {

    margin-bottom: 3px;
  }

  select {
    margin-bottom: 5px;
    font-size: 40px;
  }

  </style>
<div class="content">
<div class="row">
                        <div class="col-xs-12">
							<h4 class="page-title m-b-30"><?=lang('settings')?></h4>
						</div>
                    </div>
					<div class="row">
						<div class="col-sm-4 col-md-4 col-lg-3 col-xs-12">
							<a class="btn btn-default visible-xs-inline-block m-b-20" data-toggle="class:show" data-target="#setting-nav"><i class="fa fa-reorder"></i></a>
                        <div id="setting-nav" class="card-box settings-menu hidden-xs">
                            <ul class="nav nav-pills nav-stacked no-radius">
                            <?php
                            $menus = $this->db->where('hook','settings_menu_admin')->where('visible',1)->order_by('order','ASC')->get('hooks')->result();
                            foreach ($menus as $menu) { ?>
                                <li class="<?php echo ($load_setting == $menu->route) ? 'active' : '';?>">
                                    <a href="<?=base_url()?>settings/?settings=<?=$menu->route?>">
                                        <i class="fa fa-fw <?=$menu->icon?>"></i>
                                        <?=lang($menu->name)?>
                                    </a>
                                </li>
                            <?php } ?>
                            </ul>
                        </div>
						</div>
						<div class="col-sm-8 col-md-8 col-lg-9 col-xs-12">
                    <?php
            		$attributes = array('id' => 'saveform');
            		echo form_open(base_url() . 'settings/fields/saveform', $attributes);
            		?>

 <div class="table-head"><?=ucfirst($module)?> custom fields
     <span class="pull-right">
    <span class="label label-warning changes">Unsaved</span>
  <input type="submit" class="btn btn-primary btn-sm save button-loader" value="Save" disabled="disabled"></span>
  <input type="hidden" name="module" value="<?=$module?>" />
  <input type="hidden" name="deptid" value="<?=isset($department) ? $department : 0; ?>" />
  <input type="hidden" name="uniqid" value="<?=Applib::generate_unique_value()?>" />
</div>
 <div class="table-div">
     <br>

           <textarea id="formcontent" class="hidden" name="formcontent"></textarea>

</form>
 <div class='fb-main'></div>
						</div>
					</div>
</div>

<?php if (isset($formbuilder)) { ?>
    <script src="<?=base_url()?>assets/js/apps/formbuilder_vendor.js"></script>
    <script src="<?=base_url()?>assets/js/apps/formbuilder.js"></script>
<?php } ?>
<script>
    $(function(){

      fb = new Formbuilder({
        selector: '.fb-main',
        bootstrapData: [
            <?php foreach($fields as $f) : ?>
            {"label":"<?=$f->label?>","field_type":"<?=$f->type?>","required":"<?=($f->required == 1) ? true : false;?>","cid":"<?=$f->cid?>",'uniqid':"<?=$f->uniqid?>",'module':"<?=$f->module?>","field_options":<?=$f->field_options?>},
            <?php endforeach; ?>
        ]
      });

      fb.on('save', function(payload){
        console.log(payload);
        $("#formcontent").text(payload);
      });


      switch ( window.orientation ) {

    case 0:
        alert('Please turn your phone sideways in order to use this page!');
    break;

}

    });
  </script>

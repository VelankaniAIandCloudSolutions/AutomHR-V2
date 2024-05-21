<section id="content">
  <section class="hbox stretch">
     <!-- .aside -->
    <aside>
      <section class="vbox">
        <header class="header bg-white b-b b-light"> 
          <p><?='Edit Holiday';?></p>
        </header>   
              <section class="scrollable wrapper"> 
               <div class="col-sm-12">
                  <section class="panel panel-default">
                    <header class="panel-heading font-bold"><i class="fa fa-info-circle"></i> Holyday Details</header>
                    <div class="panel-body">
                     <?php  
					 if(!isset($holidays_det) || empty($holidays_det)){ redirect(base_url().'holidays');} 
 					 $attributes = array('class' => 'bs-example form-horizontal');
                            echo form_open(base_url().'holidays/edit',$attributes); ?> 
                            <div class="form-group">
                                <label class="col-lg-2 control-label"> Holyday Title <span class="text-danger">*</span></label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" value="<?=$holidays_det[0]['title']?>" name="holiday_title" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label"> Holiday Date <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                <input class="input-sm input-s datepicker-input form-control" readonly size="16" type="text"  value="<?=date('d-m-Y',strtotime($holidays_det[0]['holiday_date']))?>" name="holiday_date" data-date-format="dd-mm-yyyy" required > 
                                </div>
                            </div>
        
                            <div class="form-group">
                                <label class="col-lg-2 control-label"> Holiday Description </label>
                                <div class="col-lg-4">
                                        <textarea class="form-control" name="holiday_description"> <?=$holidays_det[0]['description']?> </textarea>
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-lg-2 control-label"> &nbsp; </label>
                                <div class="col-lg-4">
                                        <input type="hidden" name="holiday_tbl_id" value="<?=$holidays_det[0]['id']?>">
                                        <button class="btn btn-sm btn-success" type="submit"> Update Holiday</button>
                                        <a href="<?php echo base_url().'holidays';?>" >
                                        	<button class="btn btn-sm btn-danger" type="button"> Cancel </button>
                                        </a>
                                </div>
                            </div> 
                     </form>
                  </div>
                </section>
               </div> 
            </section>  
    </section>
  </aside>
  <!-- /.aside -->
 </section>
</section> 
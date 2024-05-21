<div class="content container-fluid">
					<!-- Page Header -->
					<div class="page-header">
						<div class="row">
							<div class="col-sm-12">
								<h3 class="page-title">Forms</h3>
								<ul class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Dashboard</a></li>
									<li class="breadcrumb-item active"><a href="<?php echo base_url(); ?>forms">Forms</a></li>
								</ul>
							</div>
							<div class="col-auto float-right ml-auto">
								<a href="<?php echo base_url()?>forms" class="btn add-btn"><i class="fa fa-left-arrow"></i> Back</a>
							</div>
						</div>
					</div>
					<!-- /Page Header -->
					<!-- Content Starts -->
					
					
					<div class="row" id="search_content">
					<?php 
					foreach($all_forms as $form)
					{
					?>
						<div class="col-lg-12">
							<h4 class="text-capitalize"><?php echo $cat=$this->db->get_where( 'forms_category',array('id'=>$form['category']))->row()->category_name;?> / <?php echo $form['form_name'];?></h4>
							<div class="card">
								<div class="card-header text-center">
									<h4 class="card-title mb-0">
										<?php echo $form['form_name'];?>
									</h4>
								</div>
								<?php
								$file_t=explode('.',$form['file']);
								$file_type=$file_t[1];
								
								
								?>
								
								<div class="card-body">
									<div  style="width: 100%;">
									<div class="card-file-thumb">
																		<?php
																		if($file_type=='pdf')
																		{?>
																			<i class="fa fa-file-pdf-o"></i>

																		<?php
																		}
																		else if($file_type=='docx')
																		{?>
																			<i class="fa fa-file-word-o"></i>

																		<?php
																		}
																		else if($file_type=='png'||$file_type=='jpg'||$file_type=='psd')
																		{?>
																			<img src="<?php echo base_url().'uploads/files/'.$form['file']; ?>" style="height: 90%;width: 30%;">

																		<?php
																		}
																		else if($file_type=='xls')
																		{?>
																			<i class="fa fa-file-excel-o"></i>

																		<?php
																		}
																		else if($file_type=='ppt')
																		{?>
																			<i class="fa fa-file-powerpoint-o"></i>

																		<?php
																		}
																		else if($file_type=='mp3')
																		{?>
																			<i class="fa fa-file-audio-o"></i>

																		<?php
																		}
																		else if($file_type=='mp4')
																		{?>
																			<i class="fa fa-file-video-o"></i>

																		<?php
																		}
																		else if($file_type=='txt')
																		{?>
																			<i class="fa fa-file-text-o"></i>

																		<?php
																		}
																		else if($file_type=='html')
																		{?>
																			<i class="fa fa-file-code-o"></i>

																		<?php
																		}
																		?>
																		
																	</div>
																	<center><?php echo $form['file'];?><center>
																	<!--<iframe src="<?=base_url('assets/uploads/'.$form['file'])?>" width="100%" height="500">
									<p>Your browser does not support iframes.</p>
									</iframe>-->
									
									</div>
								</div>
								<?php if(App::is_permit('menu_forms','read')){?>
								<div class="card-footer text-center">
									<a href="<?php echo base_url();?>forms/download_file/<?php echo $form['form_id'];?>"  class="btn btn-primary submit-btn download">Download</a>
								</div>
							<?php } ?>
							</div>
						</div>
						<?php
					}
						?>
						
					</div>
					<!-- /Content End -->
				</div>
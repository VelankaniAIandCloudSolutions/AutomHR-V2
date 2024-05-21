<div class="content">
	<div class="row">
		<div class="col-sm-8">
			<h4 class="page-title"><?=lang('messages')?></h4>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4 col-md-4 col-lg-3 col-xs-12">
			<div class="card-box">
				<a class="btn btn-primary btn-block m-b-20" href="<?=base_url()?>messages/send/?group=sent" title="<?=lang('send_message')?>" data-placement="top">
					<i class="fa fa-envelope"></i> <?=lang('send_message')?>
				</a>
				<div class="left-menu">
					<ul class="mail-menu">
						<li class="<?php echo ($group == 'inbox') ? 'active' : '';?>">
							<a href="<?=base_url()?>messages?group=inbox"> <i class="fa fa-fw fa-envelope"></i>
								<?=lang('inbox')?>
							</a>
						</li>
						<li class="<?php echo ($group == 'sent') ? 'active' : '';?>">
							<a href="<?=base_url()?>messages?group=sent"> <i class="fa fa-fw fa-exchange"></i>
								<?=lang('sent')?>
							</a>
						</li>
						<li class="<?php echo ($group == 'favourites') ? 'active' : '';?>">
							<a href="<?=base_url()?>messages?group=favourites"> <i class="fa fa-fw fa-star"></i>
								<?=lang('favourites')?>
							</a>
						</li>
						<li class="<?php echo ($group == 'trash') ? 'active' : '';?>">
							<a href="<?=base_url()?>messages?group=trash"> <i class="fa fa-fw fa-trash-o"></i>
								<?=lang('trash')?>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-sm-8 col-md-8 col-lg-9 col-xs-12">
					<div class="card-box">
						<div class="email-header">
							<div class="row">
								<div class="col-xs-12 col-sm-6 pull-right top-action-left">
									<?php echo form_open(base_url().'messages/search/'); ?>
										<div class="input-group">
											<input type="text" class="form-control" name="keyword" placeholder="<?=lang('keyword')?>">
											<span class="input-group-btn"> <button class="btn btn-md btn-default" type="submit">Go!</button>
										</span>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="email-content">
							<div class="slim-scroll table-inbox">
								<?php $this->load->helper('text'); ?>
								<ul class="email-list">
								<?php
									$group = isset($_GET['group']) ? $_GET['group'] : FALSE;
									switch ($group) {
										case 'sent':
											$this->load->view('group/sent');
											break;
										case 'inbox':
											$this->load->view('group/inbox',$messages);
											break;
										case 'favourites':
											$this->load->view('group/favourites');
											break;
										case 'trash':
											$this->load->view('group/trash');
											break;
										default:
											$this->load->view('group/inbox');
											break;
									}
								?>
								</ul>
							</div>
						</div>
					</div>
				
		</div>
	</div>
</div>
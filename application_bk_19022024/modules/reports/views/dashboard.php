<?php
$chart_year = ($this->session->userdata('chart_year')) ? $this->session->userdata('chart_year') : date('Y');
$cur = App::currencies(config_item('default_currency'));
$this->lang->load('calendar',config_item('language'));
?>
<link rel="stylesheet" href="<?=base_url()?>assets/css/morris.css">
<div class="content">
	<div class="row">
		<div class="col-xs-4">
			<h4 class="page-title">Reports</h4>
		</div>
		<div class="col-xs-8 text-right m-b-0">
			<?=$this->load->view('report_header');?>
		</div>
	</div>
	<div class="row">
	<div class="col-xs-12">
	<div class="alert alert-info">
		<button type="button" class="close" data-dismiss="alert">×</button>
		<i class="fa fa-info-sign"></i><?=lang('amount_displayed_in_your_cur')?>&nbsp;<span class="label label-success"><?=config_item('default_currency')?></span>
	</div>
	</div>
	</div>
	<div class="row">
		<div class="col-sm-4">
			<div class="panel panel-info m-b-2">
				<div class="panel-body">
					<div class="clear">
						<span class="text-dark"><?=lang('total_sales')?></span>
						<small class="block text-danger pull-right m-l bt">
						<?=Applib::format_currency($cur->code,Report::total_paid());?>
						</small>
					</div>
				</div>
			</div>
			<div class="panel panel-info m-b-2">
				<div class="panel-body">
					<div class="clear">
						<span class="text-dark"><?=lang('collected_this_year')?></span>
						<small class="block text-danger pull-right m-l">
							<strong>
							<?=Applib::format_currency($cur->code,Report::year_amount(date('Y')));?>
							</strong>
						</small>
					</div>
				</div>
			</div>
			<div class="panel panel-info m-b-2">
				<div class="panel-body">
					<div class="clear">
						<span class="text-dark"><?=lang('paid_this_month')?></span>
						<small class="block text-danger pull-right m-l">
							<strong>
							<?=Applib::format_currency($cur->code,Report::month_amount(date('Y'),date('m')));?>
							</strong>
						</small>
					</div>
				</div>
			</div>
			<div class="panel panel-info m-b-2">
				<div class="panel-body">
					<div class="clear">
						<span class="text-dark"><?=lang('last_month')?></span>
						<small class="block text-muted pull-right m-l">
						<?=Applib::format_currency($cur->code,Report::month_amount(date('Y'),date('m')-1));?>
						</small>
					</div>
				</div>
			</div>
			<div class="panel panel-info">
				<div class="panel-body">
					<div class="clear">
						<span class="text-dark"><?=lang('payments_received')?></span>
						<small class="block text-muted pull-right m-l"><?=Report::num_payments()?></small>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="panel">
				<div class="panel-heading">
					<h3 class="panel-title"><?=lang('invoiced_monthly')?></h3>
				</div>
				<div id="bar-graph"></div>
			</div>
		</div>
	</div>
	<div class="row">
		<!-- 1st Quarter -->
		<div class="col-md-3 col-sm-6">
			<div class="panel">
				<div class="panel-heading">
					<h3 class="panel-title">1st <?=lang('quarter')?>, <?=$chart_year?></h3>
				</div>
				<div class="panel-body">
					<?php
					$total_jan = Report::month_amount($chart_year, '01');
					$total_feb = Report::month_amount($chart_year, '02');
					$total_mar = Report::month_amount($chart_year, '03');
					$sum = array($total_jan,$total_feb,$total_mar);
					?>
					<div class="clearfix m-b-md text-muted"><?=lang('cal_january')?><div class="pull-right ">
					<?=Applib::format_currency($cur->code,$total_jan);?></div>
					</div>
					<div class="clearfix m-b-md text-muted"><?=lang('cal_february')?><div class="pull-right ">
					<?=Applib::format_currency($cur->code,$total_feb);?>
					</div>
					</div>
					<div class="clearfix m-b-md text-muted"><?=lang('cal_march')?><div class="pull-right ">
						<?=Applib::format_currency($cur->code,$total_mar);?>
					</div>
					</div>
					<div class="clearfix m-b-md">
						<div class="pull-right text-dark">
							<strong><?=Applib::format_currency($cur->code,array_sum($sum));?></strong>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- 2nd Quarter -->
		<div class="col-md-3 col-sm-6">
			<div class="panel">
				<div class="panel-heading">
					<h3 class="panel-title">2nd <?=lang('quarter')?>, <?=$chart_year?></h3>
				</div>
				<div class="panel-body">
					<?php
					$total_apr = Report::month_amount($chart_year, '04');
					$total_may = Report::month_amount($chart_year, '05');
					$total_jun = Report::month_amount($chart_year, '06');
					$sum = array($total_apr,$total_may,$total_jun);
					?>
					<div class="clearfix m-b-md text-muted"><?=lang('cal_april')?><div class="pull-right">
					<?=Applib::format_currency($cur->code,$total_apr);?></div>
					</div>
					<div class="clearfix m-b-md text-muted"><?=lang('cal_may')?><div class="pull-right">
					<?=Applib::format_currency($cur->code,$total_may);?>
					</div>
					</div>
					<div class="clearfix m-b-md text-muted"><?=lang('cal_june')?><div class="pull-right">
					<?=Applib::format_currency($cur->code,$total_jun);?>
					</div>
					</div>
					<div class="clearfix m-b-md">
						<div class="pull-right text-dark">
							<strong><?=Applib::format_currency($cur->code,array_sum($sum));?></strong>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- 3rd Quarter -->
		<div class="col-md-3 col-sm-6">
			<div class="panel">
				<div class="panel-heading">
					<h3 class="panel-title">3rd <?=lang('quarter')?>, <?=$chart_year?></h3>
				</div>
				<div class="panel-body">
					<?php
					$total_jul = Report::month_amount($chart_year, '07');
					$total_aug = Report::month_amount($chart_year, '08');
					$total_sep = Report::month_amount($chart_year, '09');
					$sum = array($total_jul,$total_aug,$total_sep);
					?>
					<div class="clearfix m-b-md text-muted"><?=lang('cal_july')?><div class="pull-right">
					<?=Applib::format_currency($cur->code,$total_jul);?></div>
					</div>
					<div class="clearfix m-b-md text-muted"><?=lang('cal_august')?><div class="pull-right">
					<?=Applib::format_currency($cur->code,$total_aug);?>
					</div>
					</div>
					<div class="clearfix m-b-md text-muted"><?=lang('cal_september')?><div class="pull-right">
					<?=Applib::format_currency($cur->code,$total_sep);?>
					</div>
					</div>
					<div class="clearfix m-b-md">
						<div class="pull-right text-dark">
							<strong><?=Applib::format_currency($cur->code,array_sum($sum));?></strong>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- 4th Quarter -->
		<div class="col-md-3 col-sm-6">
			<div class="panel">
				<div class="panel-heading">
					<h3 class="panel-title">4th <?=lang('quarter')?>, <?=$chart_year?></h3>
				</div>
				<div class="panel-body">
					<?php
					$total_oct = Report::month_amount($chart_year, '10');
					$total_nov = Report::month_amount($chart_year, '11');
					$total_dec = Report::month_amount($chart_year, '12');
					$sum = array($total_oct,$total_nov,$total_dec);
					?>
					<div class="clearfix m-b-md text-muted"><?=lang('cal_october')?><div class="pull-right">
					<?=Applib::format_currency($cur->code,$total_oct);?></div>
					</div>
					<div class="clearfix m-b-md text-muted"><?=lang('cal_november')?><div class="pull-right">
					<?=Applib::format_currency($cur->code,$total_nov);?>
					</div>
					</div>
					<div class="clearfix m-b-md text-muted"><?=lang('cal_december')?><div class="pull-right">
					<?=Applib::format_currency($cur->code,$total_dec);?>
					</div>
					</div>
					<div class="clearfix m-b-md">
						<div class="pull-right text-dark">
							<strong><?=Applib::format_currency($cur->code,array_sum($sum));?></strong>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Quarters -->
	</div>
	<!-- End Row -->

	<div class="row">
		<div class="col-sm-4">
			<div class="card-box">
				<h3 class="card-title"><?=lang('top_clients')?></h3>
				<div class="slim-scroll" data-height="400" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
					<ul class="list-group alt">
						<?php foreach (Report::top_clients(20) as $key => $client) {
							// echo '<pre>';print_r($client);
							if($client->branch_id == $this->session->userdata('branch_id')){

							?>
						<li class="list-group-item">
							<div>
							<a href="<?=base_url()?>companies/view/<?=$client->co_id?>"><?=Client::view_by_id($client->co_id)->company_name?></a>
							<small class="text-muted pull-right">
								<?=Applib::format_currency($cur->code,Client::amount_paid($client->co_id));?>
							</small>
							</div>
						</li>
							<?php }} ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="card-box">
				<h3 class="card-title"><?=lang('outstanding')?></h3>
				<div class="slim-scroll" data-height="400" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
					<ul class="list-group alt">
						<?php foreach (Report::outstanding() as $key => $i) { 
						if($i->branch_id == $this->session->userdata('branch_id')){
						?>
						<li class="list-group-item">
							<div>
								<a href="<?=base_url()?>invoices/view/<?=$i->inv_id?>"><?=$i->reference_no;?></a>
								<small class="text-muted pull-right">
								<?php if ($i->currency != config_item('default_currency')) {
								echo Applib::format_currency($cur->code,Applib::convert_currency($i->currency, Invoice::get_invoice_due_amount($i->inv_id)));
								}else{
								echo Applib::format_currency($cur->code,Invoice::get_invoice_due_amount($i->inv_id));
								}
								?>
								</small>
							</div>
						</li>
						<?php }} ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<div class="card-box">
				<h3 class="card-title"><?=lang('unbilled_expenses')?></h3>
				<div class="slim-scroll" data-height="400" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
					<ul class="list-group alt">
						<?php
						foreach (Report::unbilled_expenses() as $key => $e) {
						$client = Client::view_by_id($e->client);
						if($e->branch_id == $this->session->userdata('branch_id')){
						?>
						<li class="list-group-item">
							<div>
								<a href="<?=base_url()?>companies/view/<?=$e->client?>"><?=$client->company_name?></a>
								<small class="text-muted pull-right">
								<?php if ($client->currency != config_item('default_currency')) {
								echo Applib::format_currency($cur->code,Applib::convert_currency($client->currency,Expense::total_by_client($e->client)));
								}else{
								echo Applib::format_currency($cur->code,Expense::total_by_client($e->client));
								}
								?>
								</small>
							</div>
						</li>
						<?php }} ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- End Row -->
</div>
<!-- end -->
		
<script src="<?=base_url()?>assets/js/jquery-2.2.4.min.js"></script>
<script src="<?=base_url()?>assets/js/raphael-min.js"></script>
<script src="<?=base_url()?>assets/js/morris.min.js"></script>
<script type="text/javascript">
  /*
 * Play with this code and it'll update in the panel opposite.
 *
 * Why not try some of the options above?
 */
Morris.Bar({
  element: 'bar-graph',
  data: [
    { y: 'Jan', a: <?=Report::invoiced($chart_year,'01')?> },
    { y: 'Feb', a: <?=Report::invoiced($chart_year,'02')?> },
    { y: 'Mar', a: <?=Report::invoiced($chart_year,'03')?> },
    { y: 'Apr', a: <?=Report::invoiced($chart_year,'04')?> },
    { y: 'May', a: <?=Report::invoiced($chart_year,'05')?> },
    { y: 'Jun', a: <?=Report::invoiced($chart_year,'06')?> },
    { y: 'Jul', a: <?=Report::invoiced($chart_year,'07')?> },
    { y: 'Aug', a: <?=Report::invoiced($chart_year,'08')?> },
    { y: 'Sep', a: <?=Report::invoiced($chart_year,'09')?> },
    { y: 'Oct', a: <?=Report::invoiced($chart_year,'10')?> },
    { y: 'Nov', a: <?=Report::invoiced($chart_year,'11')?> },
    { y: 'Dec', a: <?=Report::invoiced($chart_year,'12')?> }
  ],
  xkey: 'y',
  ykeys: ['a'],
  labels: ['Invoices'],
  preUnits:'<?=$cur->symbol?>',
  barColors: ["#38354a"]
});
</script>
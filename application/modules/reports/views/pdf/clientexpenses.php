<link rel="stylesheet" href="<?=base_url()?>assets/css/app.css" type="text/css" />
<style type="text/css">
  .pure-table td, .pure-table th {
    border-bottom: 1px solid #cbcbcb;
    border-width: 0 0 0 1px;
    margin: 0;
    overflow: visible;
    padding: .5em 1em;
}
.pure-table .table td {
    vertical-align: middle !important;
}
</style>
<?php 
ini_set('memory_limit', '-1');
$cur = App::currencies(config_item('default_currency')); 
$customer = ($client > 0) ? Client::view_by_id($client) : array();
$report_by = (isset($report_by)) ? $report_by : 'all';
?>


<div class="rep-container">
  <div class="page-header text-center">
  <h3 class="reports-headerspacing"><?=lang('expenses_report')?></h3>
  <?php if($client != NULL){ ?>
  <h5><span><?=lang('client_name')?>:</span>&nbsp;<?=$customer->company_name?>&nbsp;</h5>
  <?php } ?>
</div>

<table class="table pure-table"><thead>
<tr>
<th><?=lang('status')?></th>
<th><?=lang('date')?></th>
<th><?=lang('billable')?></th>
<th><?=lang('category')?></th>
<th class="text-right"><?=lang('amount')?></th>
</tr>
</thead>

<tbody>

<?php 
$total_expense = 0;
foreach ($expenses as $key => $ex) { ?>
        <tr>
        <td class="text-<?=($ex->invoiced == '1') ? 'success' : 'dark'; ?>">
        <?=($ex->invoiced == 0) ? lang('unbilled') : lang('billed'); ?>
        </td>
        <td><?=format_date($ex->expense_date);?></td>
        <td><?=($ex->billable == '1') ? lang('yes') : lang('no'); ?></td>
        <td><?=App::get_category_by_id($ex->category);?></td>
        <td class="text-right">
        <?php if (Client::view_by_id($ex->client)->currency != config_item('default_currency')) {
          $converted = Applib::convert_currency(Client::view_by_id($ex->client)->currency, $ex->amount);
          echo Applib::format_currency($cur->code,$converted);
          $total_expense += $converted;
        }else{
          $total_expense += $ex->amount;
          echo Applib::format_currency($cur->code,$ex->amount);
        }
        ?></td>
      </tr>
<?php } ?>

        <tr>
          <td colspan="4"><strong><?=lang('total')?></strong></td>
          <td class="text-right"><strong><?=Applib::format_currency($cur->code,$total_expense)?></strong></td>
        </tr>


<!----></tbody>
</table> </div>
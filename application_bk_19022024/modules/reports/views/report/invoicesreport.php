<!-- Include Required Prerequisites -->
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
 
<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<?php 
$cur = App::currencies(config_item('default_currency')); 
$start_date = date('F d, Y',strtotime($range[0]));
$end_date = date('F d, Y',strtotime($range[1]));
$report_by = (isset($report_by)) ? $report_by : 'InvoiceDate';
if($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1){
  $post_branch_id = $branch_id;
} else {
  $post_branch_id = $this->session->userdata('branch_id');
}
?>

<div class="content">
          <div class="panel panel-white">

          <div class="panel-heading">
          
            <?=$this->load->view('report_header');?>

            <?php if($this->uri->segment(3)){ ?>
              <a href="<?=base_url()?>reports/invoicespdf/<?=strtotime($start_date)?>/<?=strtotime($end_date)?>/<?=$report_by?>/<?= $branch_id ?>" class="btn btn-primary pull-right"><i class="fa fa-file-pdf-o"></i><?=lang('pdf')?>
              </a>
            <?php } ?>
            <button class="btn  btn-primary pull-right" onclick="export_report('invoice_report','invoice');" > <span ><i class="fa fa-file-excel-o m-r-5" aria-hidden="true"></i></span><span><?=lang('excel')?></span> </button>
             
            </div>

            <div class="panel-body">

            <div class="alert alert-info">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <i class="fa fa-info-sign"></i><?=lang('amount_displayed_in_your_cur')?>&nbsp;<span class="label label-success"><?=config_item('default_currency')?></span>
            </div>


<div class="fill body reports-top rep-new-band">
<div class="criteria-container fill-container hidden-print">
  <div class="criteria-band">
    <address class="row">
    <?php echo form_open(base_url().'reports/view/invoicesreport'); ?>
    <?php if($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1){?>
       <div class="col-md-2">
          <label><?=lang('branches')?></label>
          <select <?=$dis?> class="select2-option form-control" style="width:100%" name="branch_id">
              <option value="0"><?=lang('all_branches')?></option>
              <?php 
                // $all_branches = $this->db->get_where('branches',array('branch_status'=>'0'))->result_array();
                 if(!empty($all_branches)){
                  foreach($all_branches as $branch){ ?>
                    <option value="<?php echo $branch['branch_id']; ?>" <?php if($branch_id == $branch['branch_id']){ echo "selected"; } ?> ><?php echo $branch['branch_name']; ?></option>
              <?php 
                  }
                }
              ?>
          </select>
        </div>
    <?php } else { ?>
      <input type="hidden" value="<?php echo $this->session->userdata('branch_id');?>" name="branch_id">
    <?php } ?>
        <div class="col-md-2">
          <label><?=lang('report_by')?></label>
          <select class="form-control" name="report_by"><!---->
              <option value="InvoiceDate"><?=lang('invoice_date')?></option>
              <option value="InvoiceDueDate" <?=($report_by == 'InvoiceDueDate') ? 'selected="selected"': ''; ?>><?=lang('invoice')?> <?=lang('due_date')?></option>
          </select>
        </div>
      
        <div class="col-md-4">
          <label><?=lang('date_range')?></label>
          <input type="text" name="range" id="reportrange" class="pull-right form-control">
            <i class="fa fa-calendar"></i>&nbsp;
            <span></span> <b class="caret"></b>
        </div>


      <div class="col-md-2">  
  <button class="btn btn-success" type="submit">
    <?=lang('run_report')?>
  </button>
</div>



    </address>


  </div>
</div>


</form>

<div class="rep-container">
  <div class="page-header text-center">
  <h3 class="reports-headerspacing"><?=lang('invoices_report')?></h3>
  <h5><span>From</span>&nbsp;<?=$start_date?>&nbsp;<span>To</span>&nbsp;<?=$end_date?></h5>
</div>

  <div class="fill-container">
<table class="table table-bordered table-striped zi-table table-hover norow-action" id="invoice_report"><thead>
  <tr>
<th class="text-left">
  <div class="pull-left over-flow"><?=lang('status')?></div>
</th>
         <th class="text-left <?=($report_by == 'InvoiceDate') ? 'text-primary': ''; ?>">
  <div class="pull-left over-flow"> <?=lang('invoice_date')?></div>
  
</th>
         <th class="text-left <?=($report_by == 'InvoiceDueDate') ? 'text-primary': ''; ?>">
  <div class="pull-left over-flow"> <?=lang('due_date')?></div>
</th>
         <th class="text-left">
  <div class="pull-left over-flow"> <?=lang('invoice')?>#</div>
<!----></div>
</th>
         
         <th class="text-left">
  <div class="pull-left over-flow"> <?=lang('client_name')?></div>
</th>
         <th class="text-right">
  <div class=" over-flow"> <?=lang('invoice_amount')?></div>
</th>
         <th class="text-right">
  <div class=" over-flow"> <?=lang('balance_due')?></div>
</th>
  </tr>
</thead>

<tbody>

<?php 
$due_total = 0;
$invoice_total = 0;
$all_branch_invoices = $this->db->get_where('invoices',array('branch_id'=>$branch_id))->result_array();
if($branch_id != 0 ){
  $count_branch = count($all_branch_invoices);
}else{
  $count_branch = -1;
}
foreach ($invoices as $key => $invoice) {
  if($count_branch > 0){
  if(($invoice->branch_id == $branch_id) && ($invoice->branch_id != 0)){

  $status = Invoice::payment_status($invoice->inv_id);
  $text_color = 'info';
  switch ($status) {
    case 'fully_paid':
      $text_color = 'success';
      break;
    case 'not_paid':
      $text_color = 'danger';
      break;
  }
  ?>
        <tr>
        <td><div class="text-<?=$text_color?>"><?=lang($status)?></div></td>
        <td><?=format_date($invoice->date_saved);?></td>
        <td><?=format_date($invoice->due_date);?></td>
        <td><a href="<?=base_url()?>invoices/view/<?=$invoice->inv_id?>"><?=$invoice->reference_no?></a></td>
        <td><a href="<?=base_url()?>companies/view/<?=$invoice->client?>"><?=Client::view_by_id($invoice->client)->company_name?></a></td>

        <td class="text-right">
        <?php if ($invoice->currency != config_item('default_currency')) {
          $payable = Applib::convert_currency($invoice->currency, Invoice::payable($invoice->inv_id));
          echo Applib::format_currency($cur->code,$payable);
          $invoice_total += $payable;
        }else{
          $invoice_total += Invoice::payable($invoice->inv_id);
          echo Applib::format_currency($cur->code,Invoice::payable($invoice->inv_id));
        }
        ?></td>
        <td class="text-right">
        <?php if ($invoice->currency != config_item('default_currency')) {
          $due = Applib::convert_currency($invoice->currency, Invoice::get_invoice_due_amount($invoice->inv_id));
          $due_total += $due;
          echo Applib::format_currency($cur->code,$due);
          }else{
          $due_total += Invoice::get_invoice_due_amount($invoice->inv_id);
          echo Applib::format_currency($cur->code,Invoice::get_invoice_due_amount($invoice->inv_id));
          }
          ?></td>
      </tr>
<?php }else{
   $status = Invoice::payment_status($invoice->inv_id);
  $text_color = 'info';
  switch ($status) {
    case 'fully_paid':
      $text_color = 'success';
      break;
    case 'not_paid':
      $text_color = 'danger';
      break;
  }
  ?>
        <tr>
        <td><div class="text-<?=$text_color?>"><?=lang($status)?></div></td>
        <td><?=format_date($invoice->date_saved);?></td>
        <td><?=format_date($invoice->due_date);?></td>
        <td><a href="<?=base_url()?>invoices/view/<?=$invoice->inv_id?>"><?=$invoice->reference_no?></a></td>
        <td><a href="<?=base_url()?>companies/view/<?=$invoice->client?>"><?=Client::view_by_id($invoice->client)->company_name?></a></td>

        <td class="text-right">
        <?php if ($invoice->currency != config_item('default_currency')) {
          $payable = Applib::convert_currency($invoice->currency, Invoice::payable($invoice->inv_id));
          echo Applib::format_currency($cur->code,$payable);
          $invoice_total += $payable;
        }else{
          $invoice_total += Invoice::payable($invoice->inv_id);
          echo Applib::format_currency($cur->code,Invoice::payable($invoice->inv_id));
        }
        ?></td>
        <td class="text-right">
        <?php if ($invoice->currency != config_item('default_currency')) {
          $due = Applib::convert_currency($invoice->currency, Invoice::get_invoice_due_amount($invoice->inv_id));
          $due_total += $due;
          echo Applib::format_currency($cur->code,$due);
          }else{
          $due_total += Invoice::get_invoice_due_amount($invoice->inv_id);
          echo Applib::format_currency($cur->code,Invoice::get_invoice_due_amount($invoice->inv_id));
          }
          ?></td>
      </tr>
 <?php } }elseif($count_branch == -1){ 
        $status = Invoice::payment_status($invoice->inv_id);
  $text_color = 'info';
  switch ($status) {
    case 'fully_paid':
      $text_color = 'success';
      break;
    case 'not_paid':
      $text_color = 'danger';
      break;
  }
  ?>
       <tr>
        <td><div class="text-<?=$text_color?>"><?=lang($status)?></div></td>
        <td><?=format_date($invoice->date_saved);?></td>
        <td><?=format_date($invoice->due_date);?></td>
        <td><a href="<?=base_url()?>invoices/view/<?=$invoice->inv_id?>"><?=$invoice->reference_no?></a></td>
        <td><a href="<?=base_url()?>companies/view/<?=$invoice->client?>"><?=Client::view_by_id($invoice->client)->company_name?></a></td>

        <td class="text-right">
        <?php if ($invoice->currency != config_item('default_currency')) {
          $payable = Applib::convert_currency($invoice->currency, Invoice::payable($invoice->inv_id));
          echo Applib::format_currency($cur->code,$payable);
          $invoice_total += $payable;
        }else{
          $invoice_total += Invoice::payable($invoice->inv_id);
          echo Applib::format_currency($cur->code,Invoice::payable($invoice->inv_id));
        }
        ?></td>
        <td class="text-right">
        <?php if ($invoice->currency != config_item('default_currency')) {
          $due = Applib::convert_currency($invoice->currency, Invoice::get_invoice_due_amount($invoice->inv_id));
          $due_total += $due;
          echo Applib::format_currency($cur->code,$due);
          }else{
          $due_total += Invoice::get_invoice_due_amount($invoice->inv_id);
          echo Applib::format_currency($cur->code,Invoice::get_invoice_due_amount($invoice->inv_id));
          }
          ?></td>
      </tr>
 <?php } } ?>

        <tr class="hover-muted">
          <td colspan="5"><?=lang('total')?></td>
          <td class="text-right"><?=Applib::format_currency($cur->code,$invoice_total)?></td>
          <td class="text-right"><?=Applib::format_currency($cur->code,$due_total)?></td>
        </tr>


<!----></tbody>
</table>  </div>
    

</div>


</div>






            </div>
            </div>
            </div>

<script type="text/javascript">
    $('#reportrange').daterangepicker({
      locale: {
            format: 'MMMM D, YYYY'
        },
        startDate: '<?=$start_date?>',
        endDate: '<?=$end_date?>',
        "opens": "right",
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    });
</script>
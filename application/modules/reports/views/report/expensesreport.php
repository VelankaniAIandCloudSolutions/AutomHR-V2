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
$report_by = (isset($report_by)) ? $report_by : 'all';
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
              <a href="<?=base_url()?>reports/expensespdf/<?=strtotime($start_date)?>/<?=strtotime($end_date)?>/<?=$report_by?>/<?=$post_branch_id?>" class="btn btn-primary pull-right"><i class="fa fa-file-pdf-o"></i><?=lang('pdf')?>
              </a>
            <?php } ?>
            <button class="btn  btn-primary pull-right" onclick="export_report('expense_report','expenses');" > <span ><i class="fa fa-file-excel-o m-r-5" aria-hidden="true"></i></span><span><?=lang('excel')?></span> </button>
             
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
    <?php echo form_open(base_url().'reports/view/expensesreport'); ?>
    <?php if($this->session->userdata('user_type_name') =='company_admin' || $this->tank_auth->get_role_id()==1){?>
    <div class="col-md-4">
      <label><?=lang('branches')?> </label>

      <select <?=$dis?> class="select2-option form-control" style="width:100%" name="branch_id" id="branchreportexpenses" >
        <option value="" disabled selected>Choose Entity</option>
        <optgroup label="<?=lang('all_branches')?>">
          <?php 
          //  $all_branches = $this->db->get_where('branches',array('branch_status'=>'0'))->result_array();
           if(!empty($all_branches)){
          foreach ($all_branches as $c){ ?>
          <option value="<?=$c['branch_id']?>" <?php if($branch_id == $c['branch_id']){ echo "selected"; } ?> ><?=ucfirst($c['branch_name'])?></option>
          <?php } }  ?>
        </optgroup>
      </select>
    </div>
    <?php }
              else{
              ?>
                <input type="hidden" value="<?php echo $this->session->userdata('branch_id');?>" name="branch_id">
            <?php }?>
    <div class="col-md-2">
          <label><?=lang('report_by')?></label>
          <select class="form-control" name="report_by">
          <option value="all" <?=($report_by == 'all') ? 'selected="selected"': ''; ?>><?=lang('all')?></option>
          <option value="billable" <?=($report_by == 'billable') ? 'selected="selected"': ''; ?>><?=lang('billable')?></option>
          <option value="unbillable" <?=($report_by == 'unbillable') ? 'selected="selected"': ''; ?>><?=lang('unbillable')?></option>
          <option value="billed" <?=($report_by == 'billed') ? 'selected="selected"': ''; ?>><?=lang('billed')?></option>
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
  <h3 class="reports-headerspacing"><?=lang('expenses_report')?></h3>
  <h5><span>From</span>&nbsp;<?=$start_date?>&nbsp;<span>To</span>&nbsp;<?=$end_date?></h5>
</div>

  <div class="fill-container">
<table class="table zi-table table-hover norow-action table-bordered table-striped" id="expense_report"><thead>
  <tr>
<th class="text-left">
  <div class="pull-left over-flow"><?=lang('status')?></div>
</th>
         <th class="text-left">
  <div class="pull-left over-flow"> <?=lang('date')?></div>
  
</th>
         <th class="sortable text-left">
  <div class="pull-left over-flow"> <?=lang('client_name')?></div>
</th>
         <th class="sortable text-left">
  <div class="pull-left over-flow"> <?=lang('category')?></div>
<!----></div>
</th>
        
         <th class="sortable text-right">
  <div class=" over-flow"> <?=lang('amount')?></div>
</th>
  </tr>
</thead>

<tbody>

<?php 
$total_expense = 0;
foreach ($expenses as $key => $ex) { ?>
        <tr>
        <td>
        <a href="<?=base_url()?>expenses/view/<?=$ex->id?>">
        <?=($ex->invoiced == 0) ? lang('unbilled') : lang('billed'); ?>
        </a></td>
        <td><?=format_date($ex->expense_date);?></td>
        <td><a href="<?=base_url()?>companies/view/<?=$ex->client?>">
        <?=Client::view_by_id($ex->client)->company_name;?></a>
        </td>
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

        <tr class="hover-muted">
          <td colspan="4"><?=lang('total')?></td>
          <td class="text-right"><?=Applib::format_currency($cur->code,$total_expense)?></td>
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
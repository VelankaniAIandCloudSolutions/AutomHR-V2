<?php if(!empty($this->uri->segment(3))){

    $active_year = $this->uri->segment(3);

} else {
  $active_year = date("Y");
}?>
      <div class="content container-fluid">

          <div class="row">
            <div class="col-xs-12">
              <h4 class="page-title">Balance Sheet</h4>
            </div>
          </div>
          <div class="budget-tabs">           
            <ul class="nav nav-tabs tabs nav-tabs-bottom nav-justified">
              <?php   $start_years = Date('Y', strtotime("-3 Year"));
                 $current_year = date("Y");

                    for ($i=$start_years; $i <=$current_year ; $i++) { ?>
                     <li class="<?php echo ($i == $active_year )?'active':'';?>" id="tab_<?php echo $i;?>"><a data-toggle="tab" class="statement" data-year="<?php echo $i;?>" href="#statement_<?php echo $i;?>"><?php echo $i;?></a></li>
              <?php }?>
            </ul>
          </div>
        <div class="tab-content">           
            
           <?php  for ($i=$start_years; $i <=$current_year ; $i++) { ?>
            <div id="statement_<?php echo $i;?>" class="tab-pane <?php echo ($i == $active_year )?'active':'';?>">
              <div class="card-box">
                <h4 class="card-title">Balance Sheet <?php echo $i;?></h4>

                <input type="hidden" name="new_hidden_rows" id="new_hidden_rows" value="0">

                <table class="table table-hover accounts-table budget-table m-b-0 table-nowrap">
                  <thead>
                    <tr>

                      <th style="width: 250px;"></th>
                       <?php foreach($months as $month) {?>
                          <th><?php echo $month; ?></th>
                         <?php } ?>
                      <th>Overall</th>
                      <th></th>
                    </tr>
                  </thead>

                  <tbody id="asset_cont_<?php echo $i;?>">
                    <tr class="title-row">
                      <td colspan="10"><strong>Assets</strong></td>
                      <td class="text-right" colspan="5"><button type="button" class="btn btn-info btn-xs add-new" id="asset_add" data-year ="<?php echo $i;?>" onclick="asset_add(<?php echo $i;?>)"><i class="fa fa-plus"></i> Add Assets</button></td>
                    </tr>
                    <?php $this->db->select('*');
                          $this->db->where("year",$i);
                          $this->db->where("type","asset");
                          $this->db->where("branch_id",$this->session->userdata('branch_id'));
                          $assets = $this->db->get('balance_sheet')->result_array();
                          $count = 0;
                          if(!empty($assets)){
                            $count = 0;
                            foreach ($assets as $asset) {?>
                              <tr>
                                  <td data-name="title" data-id="title<?php echo $count.$i; ?>"><?php echo $asset['title'];?></td>
                                  <td data-name="jan" data-id="jan<?php echo $count.$i; ?>"><?php echo $asset['jan'];?></td>
                                  <td data-name="feb" data-id="feb<?php echo $count.$i; ?>"><?php echo $asset['feb'];?></td>
                                  <td data-name="mar" data-id="mar<?php echo $count.$i; ?>"><?php echo $asset['mar'];?></td>
                                  <td data-name="apr" data-id="apr<?php echo $count.$i; ?>"><?php echo $asset['apr'];?></td>
                                  <td data-name="may" data-id="may<?php echo $count.$i; ?>"><?php echo $asset['may'];?></td>
                                  <td data-name="jun" data-id="jun<?php echo $count.$i; ?>"><?php echo $asset['jun'];?></td>
                                  <td data-name="jul" data-id="jul<?php echo $count.$i; ?>"><?php echo $asset['jul'];?></td>
                                  <td data-name="aug" data-id="aug<?php echo $count.$i; ?>"><?php echo $asset['aug'];?></td>
                                  <td data-name="sep" data-id="sep<?php echo $count.$i; ?>"><?php echo $asset['sep'];?></td>
                                  <td data-name="oct" data-id="oct_?php echo $count.$i; ?>"><?php echo $asset['oct'];?></td>
                                  <td data-name="nov" data-id="nov<?php echo $count.$i; ?>"><?php echo $asset['nov'];?></td>
                                  <td data-name="dece" data-id="dece<?php echo $count.$i; ?>"><?php echo $asset['dece'];?></td>
                                  <td><strong><?php echo ($asset['jan']+$asset['feb']+$asset['mar']+$asset['apr']+$asset['may']+$asset['jun']+$asset['jul']+$asset['aug']+$asset['sep']+$asset['oct']+$asset['nov']+$asset['dece']);?></strong></td>
                                  <td class="text-right actionss">
                                    <a class="btn btn-info btn-xs add"  onclick="save_asset(<?php echo $count?>,<?php echo $i?>,this)" data-type ="asset" data-id ="<?php echo $asset['id'];?>" id="asset_save" title="Add" data-toggle="tooltip"><i class="fa fa-check"></i></a>
                                    <a class="btn btn-success btn-xs edit" id="asset_edit" title="Edit" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-danger btn-xs delete" data-toggle="modal" data-target="#asset_delete<?php echo $asset['id']?>" id="" title="Delete" data-toggle="tooltip"><i class="fa fa-trash-o"></i></a>
                                  </td>
                                <tr>
                                
                                <div id="asset_delete<?php echo $asset['id']?>" class="modal custom-modal fade" role="dialog">
                                <div class="modal-dialog">
                                  <div class="modal-content modal-md">
                                    <div class="modal-header">
                                      <h4 class="modal-title">Delete Balance Sheet Asset( <?php echo ucfirst($asset['title']);?> )</h4>
                                    </div>
                                    <form method="post" action="<?php echo base_url(); ?>balance_sheet/delete_balance_sheet/<?php echo $asset['id']; ?>">
                                      <div class="modal-body">
                                        <input type="hidden" class="form-control" value="<?php echo $asset['id'];?>" name="id">
                                        <input type="hidden" class="form-control" value="<?php echo $asset['title'];?>" name="title">
                                         <input type="hidden" class="form-control" value="<?php echo $i;?>" name="year">
                                        <p>Are you sure want to delete this?</p>
                                        <div class="m-t-20"> <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
                                          <button type="submit" class="btn btn-danger">Delete</button>
                                        </div>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                              </div>
                      <?php $counts = $count;
                         $count++;
                            }
                     }?>
                    <input type="hidden" name="new_hidden_rows" id="new_hidden_rows" value="<?php echo $counts;?>">                    
                  </tbody>

                  <tbody>
                    <tr class="total-row">
                      <td><strong>Total Assets</strong></td>
                      <?php $this->db->select('sum(jan) as jan,sum(feb) as feb,sum(mar) as mar,sum(apr) as apr,sum(may) as may,sum(jun) as jun,sum(jul) as jul,sum(aug) as aug,sum(sep) as sep,sum(oct) as oct,sum(nov) as nov,sum(dece) as dece');
                          $this->db->where("year",$i);
                          $this->db->where("type","asset");
                          $this->db->where("branch_id",$this->session->userdata('branch_id'));
                          $total_asset = $this->db->get('balance_sheet')->row_array();?>
                      <td><strong><?php echo $total_asset['jan']?></strong></td>
                      <td><strong><?php echo $total_asset['feb']?></strong></td>
                      <td><strong><?php echo $total_asset['mar']?></strong></td>
                      <td><strong><?php echo $total_asset['apr']?></strong></td>
                      <td><strong><?php echo $total_asset['may']?></strong></td>
                      <td><strong><?php echo $total_asset['jun']?></strong></td>
                      <td><strong><?php echo $total_asset['jul']?></strong></td>
                      <td><strong><?php echo $total_asset['aug']?></strong></td>
                      <td><strong><?php echo $total_asset['sep']?></strong></td>
                      <td><strong><?php echo $total_asset['oct']?></strong></td>
                      <td><strong><?php echo $total_asset['nov']?></strong></td>
                      <td><strong><?php echo $total_asset['dece']?></strong></td>
                      <?php $total_assets = ($total_asset['jan']+$total_asset['feb']+$total_asset['mar']+$total_asset['apr']+$total_asset['may']+$total_asset['jun']+$total_asset['jul']+$total_asset['aug']+$total_asset['sep']+$total_asset['oct']+$total_asset['nov']+$total_asset['dece']) ?>
                      <td><strong id="asset_color_<?php echo $i;?>" <?php echo ($total_asset_color['total_asset'] == $total_liabilitie_shareholder_color['total_liabilitie_shareholder'])?'style="
    color: #22e422;"':'style="
    color: red;"'?>><?php echo $total_assets; ?></strong></td>
                      <td></td>
                    </tr>
                  </tbody>

                  <tbody id="liabilitie_cont<?php echo $i;?>">
                    <tr class="title-row">
                      <td colspan="10"><strong>Liabilities</strong></td>
                      <td class="text-right" colspan="5"><button type="button" class="btn btn-info btn-xs add-new" id="liabilitie_add" data-year ="<?php echo $i;?>" onclick="liabilitie_add(<?php echo $i;?>)"><i class="fa fa-plus"></i> Add Liabilities</button></td>
                    </tr>
                    <?php $this->db->select('*');
                          $this->db->where("year",$i);
                          $this->db->where("type","liabilitie");
                          $this->db->where("branch_id",$this->session->userdata('branch_id'));
                          $liabilities = $this->db->get('balance_sheet')->result_array();
                          $liabilitie_count = 0;
                          if(!empty($liabilities)){
                           // $count = 0;
                            foreach ($liabilities as $liabilitie) {?>
                              <tr>
                                  <td data-name="title" data-id="liabilitie_title<?php echo $liabilitie_count.$i; ?>"><?php echo $liabilitie['title'];?></td>
                                  <td data-name="jan" data-id="liabilitie_jan<?php echo $liabilitie_count.$i; ?>"><?php echo $liabilitie['jan'];?></td>
                                  <td data-name="feb" data-id="liabilitie_feb<?php echo $liabilitie_count.$i; ?>"><?php echo $liabilitie['feb'];?></td>
                                  <td data-name="mar" data-id="liabilitie_mar<?php echo $liabilitie_count.$i; ?>"><?php echo $liabilitie['mar'];?></td>
                                  <td data-name="apr" data-id="liabilitie_apr<?php echo $liabilitie_count.$i; ?>"><?php echo $liabilitie['apr'];?></td>
                                  <td data-name="may" data-id="liabilitie_may<?php echo $liabilitie_count.$i; ?>"><?php echo $liabilitie['may'];?></td>
                                  <td data-name="jun" data-id="liabilitie_jun<?php echo $liabilitie_count.$i; ?>"><?php echo $liabilitie['jun'];?></td>
                                  <td data-name="jul" data-id="liabilitie_jul<?php echo $liabilitie_count.$i; ?>"><?php echo $liabilitie['jul'];?></td>
                                  <td data-name="aug" data-id="liabilitie_aug<?php echo $liabilitie_count.$i; ?>"><?php echo $liabilitie['aug'];?></td>
                                  <td data-name="sep" data-id="liabilitie_sep<?php echo $liabilitie_count.$i; ?>"><?php echo $liabilitie['sep'];?></td>
                                  <td data-name="oct" data-id="liabilitie_oct<?php echo $liabilitie_count.$i; ?>"><?php echo $liabilitie['oct'];?></td>
                                  <td data-name="nov" data-id="liabilitie_nov<?php echo $liabilitie_count.$i; ?>"><?php echo $liabilitie['nov'];?></td>
                                  <td data-name="dece" data-id="liabilitie_dece<?php echo $liabilitie_count.$i; ?>"><?php echo $liabilitie['dece'];?></td>
                                  <td><strong><?php echo ($liabilitie['jan']+$liabilitie['feb']+$liabilitie['mar']+$liabilitie['apr']+$liabilitie['may']+$liabilitie['jun']+$liabilitie['jul']+$liabilitie['aug']+$liabilitie['sep']+$liabilitie['oct']+$liabilitie['nov']+$liabilitie['dece']);?></strong></td>
                                  <td class="text-right actionss">
                                    <a class="btn btn-info btn-xs add"  onclick="save_liabilitie(<?php echo $liabilitie_count?>,<?php echo $i?>,this)" data-type ="liabilitie" data-id ="<?php echo $liabilitie['id'];?>" id="liabilitie_save" title="Add" data-toggle="tooltip"><i class="fa fa-check"></i></a>
                                    <a class="btn btn-success btn-xs edit" id="liabilitie_edit" title="Edit" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-danger btn-xs delete" data-toggle="modal" data-target="#liabilitie_delete<?php echo $liabilitie['id']?>" id="" title="Delete" data-toggle="tooltip"><i class="fa fa-trash-o"></i></a>
                                  </td>
                                <tr>
                                
                                <div id="liabilitie_delete<?php echo $liabilitie['id']?>" class="modal custom-modal fade" role="dialog">
                                <div class="modal-dialog">
                                  <div class="modal-content modal-md">
                                    <div class="modal-header">
                                      <h4 class="modal-title">Delete Balance Sheet Liabilities( <?php echo $liabilitie['title']?> )</h4>
                                    </div>
                                    <form method="post" action="<?php echo base_url(); ?>balance_sheet/delete_balance_sheet/<?php echo $liabilitie['id']; ?>">
                                      <div class="modal-body">
                                        <input type="hidden" class="form-control" value="<?php echo $liabilitie['id'];?>" name="id">
                                        <input type="hidden" class="form-control" value="<?php echo $liabilitie['title'];?>" name="title">
                                        <input type="hidden" class="form-control" value="<?php echo $i;?>" name="year">
                                        <p>Are you sure want to delete this?</p>
                                        <div class="m-t-20"> <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
                                          <button type="submit" class="btn btn-danger">Delete</button>
                                        </div>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                              </div>
                      <?php $liabilitie_counts = $liabilitie_count;
                         $liabilitie_count++;
                            }
                     }?>
                    <input type="hidden" name="liabilitie_new_hidden_rows" id="liabilitie_new_hidden_rows" value="<?php echo $liabilitie_counts;?>">
                  </tbody>
                  <tbody>
                    <tr class="total-row">
                      <td><strong>Total Liabilities</strong></td>
                      <?php $this->db->select('sum(jan) as jan,sum(feb) as feb,sum(mar) as mar,sum(apr) as apr,sum(may) as may,sum(jun) as jun,sum(jul) as jul,sum(aug) as aug,sum(sep) as sep,sum(oct) as oct,sum(nov) as nov,sum(dece) as dece');
                          $this->db->where("year",$i);
                          $this->db->where("type","liabilitie");
                          $this->db->where("branch_id",$this->session->userdata('branch_id'));
                          $total_liabilitie = $this->db->get('balance_sheet')->row_array();?>
                      <td><strong><?php echo $total_liabilitie['jan']?></strong></td>
                      <td><strong><?php echo $total_liabilitie['feb']?></strong></td>
                      <td><strong><?php echo $total_liabilitie['mar']?></strong></td>
                      <td><strong><?php echo $total_liabilitie['apr']?></strong></td>
                      <td><strong><?php echo $total_liabilitie['may']?></strong></td>
                      <td><strong><?php echo $total_liabilitie['jun']?></strong></td>
                      <td><strong><?php echo $total_liabilitie['jul']?></strong></td>
                      <td><strong><?php echo $total_liabilitie['aug']?></strong></td>
                      <td><strong><?php echo $total_liabilitie['sep']?></strong></td>
                      <td><strong><?php echo $total_liabilitie['oct']?></strong></td>
                      <td><strong><?php echo $total_liabilitie['nov']?></strong></td>
                      <td><strong><?php echo $total_liabilitie['dece']?></strong></td>
                      <?php $total_liabilities = ($total_liabilitie['jan']+$total_liabilitie['feb']+$total_liabilitie['mar']+$total_liabilitie['apr']+$total_liabilitie['may']+$total_liabilitie['jun']+$total_liabilitie['jul']+$total_liabilitie['aug']+$total_liabilitie['sep']+$total_liabilitie['oct']+$total_liabilitie['nov']+$total_liabilitie['dece']) ?>
                      <td><strong><?php echo $total_liabilities; ?></strong></td>
                      <td></td>
                    </tr>
                  </tbody>

                  <tbody id="shareholder_cont<?php echo $i;?>">
                    <tr class="title-row">
                      <td colspan="10"><strong>Shareholder's Equity</strong></td>
                      <td class="text-right" colspan="5"><button type="button" class="btn btn-info btn-xs add-new" id="shareholder_add" data-year ="<?php echo $i;?>" onclick="shareholder_add(<?php echo $i;?>)"><i class="fa fa-plus"></i> Add Shareholder's Equity</button></td>
                    </tr>
                    <?php $this->db->select('*');
                          $this->db->where("year",$i);
                          $this->db->where("type","shareholder");
                          $this->db->where("branch_id",$this->session->userdata('branch_id'));
                          $shareholders = $this->db->get('balance_sheet')->result_array();
                          $shareholder_count = 0;
                          if(!empty($shareholders)){
                           // $count = 0;
                            foreach ($shareholders as $shareholder) {?>
                              <tr>
                                  <td data-name="title" data-id="shareholder_title<?php echo $shareholder_count.$i; ?>"><?php echo $shareholder['title'];?></td>
                                  <td data-name="jan" data-id="shareholder_jan<?php echo $shareholder_count.$i; ?>"><?php echo $shareholder['jan'];?></td>
                                  <td data-name="feb" data-id="shareholder_feb<?php echo $shareholder_count.$i; ?>"><?php echo $shareholder['feb'];?></td>
                                  <td data-name="mar" data-id="shareholder_mar<?php echo $shareholder_count.$i; ?>"><?php echo $shareholder['mar'];?></td>
                                  <td data-name="apr" data-id="shareholder_apr<?php echo $shareholder_count.$i; ?>"><?php echo $shareholder['apr'];?></td>
                                  <td data-name="may" data-id="shareholder_may<?php echo $shareholder_count.$i; ?>"><?php echo $shareholder['may'];?></td>
                                  <td data-name="jun" data-id="shareholder_jun<?php echo $shareholder_count.$i; ?>"><?php echo $shareholder['jun'];?></td>
                                  <td data-name="jul" data-id="shareholder_jul<?php echo $shareholder_count.$i; ?>"><?php echo $shareholder['jul'];?></td>
                                  <td data-name="aug" data-id="shareholder_aug<?php echo $shareholder_count.$i; ?>"><?php echo $shareholder['aug'];?></td>
                                  <td data-name="sep" data-id="shareholder_sep<?php echo $shareholder_count.$i; ?>"><?php echo $shareholder['sep'];?></td>
                                  <td data-name="oct" data-id="shareholder_oct<?php echo $shareholder_count.$i; ?>"><?php echo $shareholder['oct'];?></td>
                                  <td data-name="nov" data-id="shareholder_nov<?php echo $shareholder_count.$i; ?>"><?php echo $shareholder['nov'];?></td>
                                  <td data-name="dece" data-id="shareholder_dece<?php echo $shareholder_count.$i; ?>"><?php echo $shareholder['dece'];?></td>
                                  <td><strong><?php echo ($shareholder['jan']+$shareholder['feb']+$shareholder['mar']+$shareholder['apr']+$shareholder['may']+$shareholder['jun']+$shareholder['jul']+$shareholder['aug']+$shareholder['sep']+$shareholder['oct']+$shareholder['nov']+$shareholder['dece']);?></strong></td>
                                  <td class="text-right actionss">
                                    <a class="btn btn-info btn-xs add"  onclick="save_shareholder(<?php echo $shareholder_count?>,<?php echo $i?>,this)" data-type ="shareholder" data-id ="<?php echo $shareholder['id'];?>" id="shareholder_save" title="Add" data-toggle="tooltip"><i class="fa fa-check"></i></a>
                                    <a class="btn btn-success btn-xs edit" id="shareholder_edit" title="Edit" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>
                                    <a class="btn btn-danger btn-xs delete" data-toggle="modal" data-target="#shareholder_delete<?php echo $shareholder['id']?>" id="" title="Delete" data-toggle="tooltip"><i class="fa fa-trash-o"></i></a>
                                  </td>
                                <tr>
                                
                                <div id="shareholder_delete<?php echo $shareholder['id']?>" class="modal custom-modal fade" role="dialog">
                                <div class="modal-dialog">
                                  <div class="modal-content modal-md">
                                    <div class="modal-header">
                                      <h4 class="modal-title">Delete Balance Sheet Shareholder's Equity( <?php echo $shareholder['title']?> )</h4>
                                    </div>
                                    <form method="post" action="<?php echo base_url(); ?>balance_sheet/delete_balance_sheet/<?php echo $shareholder['id']; ?>">
                                      <div class="modal-body">
                                        <input type="hidden" class="form-control" value="<?php echo $shareholder['id'];?>" name="id">
                                        <input type="hidden" class="form-control" value="<?php echo $shareholder['title'];?>" name="title">
                                        <input type="hidden" class="form-control" value="<?php echo $i;?>" name="year">
                                        <p>Are you sure want to delete this?</p>
                                        <div class="m-t-20"> <a href="#" class="btn btn-default" data-dismiss="modal">Close</a>
                                          <button type="submit" class="btn btn-danger">Delete</button>
                                        </div>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                              </div>
                      <?php $shareholder_counts = $shareholder_count;
                         $shareholder_count++;
                            }
                     }?>
                    <input type="hidden" name="shareholder_new_hidden_rows" id="shareholder_new_hidden_rows" value="<?php echo $shareholder_counts;?>">
                  </tbody>
                  <tbody>
                    <tr class="total-row">
                      <td><strong>Shareholder's Equity</strong></td>
                      <?php $this->db->select('sum(jan) as jan,sum(feb) as feb,sum(mar) as mar,sum(apr) as apr,sum(may) as may,sum(jun) as jun,sum(jul) as jul,sum(aug) as aug,sum(sep) as sep,sum(oct) as oct,sum(nov) as nov,sum(dece) as dece');
                          $this->db->where("year",$i);
                          $this->db->where("type","shareholder");
                          $this->db->where("branch_id",$this->session->userdata('branch_id'));
                          $total_shareholder = $this->db->get('balance_sheet')->row_array();?>
                      <td><strong><?php echo $total_shareholder['jan']?></strong></td>
                      <td><strong><?php echo $total_shareholder['feb']?></strong></td>
                      <td><strong><?php echo $total_shareholder['mar']?></strong></td>
                      <td><strong><?php echo $total_shareholder['apr']?></strong></td>
                      <td><strong><?php echo $total_shareholder['may']?></strong></td>
                      <td><strong><?php echo $total_shareholder['jun']?></strong></td>
                      <td><strong><?php echo $total_shareholder['jul']?></strong></td>
                      <td><strong><?php echo $total_shareholder['aug']?></strong></td>
                      <td><strong><?php echo $total_shareholder['sep']?></strong></td>
                      <td><strong><?php echo $total_shareholder['oct']?></strong></td>
                      <td><strong><?php echo $total_shareholder['nov']?></strong></td>
                      <td><strong><?php echo $total_shareholder['dece']?></strong></td>
                      <?php $total_liabilities = ($total_shareholder['jan']+$total_shareholder['feb']+$total_shareholder['mar']+$total_shareholder['apr']+$total_shareholder['may']+$total_shareholder['jun']+$total_shareholder['jul']+$total_shareholder['aug']+$total_shareholder['sep']+$total_shareholder['oct']+$total_shareholder['nov']+$total_shareholder['dece']) ?>
                      <td><strong><?php echo $total_liabilities; ?></strong></td>
                      <td></td>
                    </tr>
                  </tbody>
                  <tbody>
                    <tr>
                      <td><strong>Total Liabilities & Shareholder's Equity</strong></td>
                      <?php 
                        $liabilitie_shareholder = array();
                        foreach ($total_liabilitie as $key => $value) {
                          $liabilitie_shareholder[$key] = $total_liabilitie[$key] + $total_shareholder[$key];

                        }

                      ?>
                      <td><strong><?php echo $liabilitie_shareholder['jan']?></strong></td>
                      <td><strong><?php echo $liabilitie_shareholder['feb']?></strong></td>
                      <td><strong><?php echo $liabilitie_shareholder['mar']?></strong></td>
                      <td><strong><?php echo $liabilitie_shareholder['apr']?></strong></td>
                      <td><strong><?php echo $liabilitie_shareholder['may']?></strong></td>
                      <td><strong><?php echo $liabilitie_shareholder['jun']?></strong></td>
                      <td><strong><?php echo $liabilitie_shareholder['jul']?></strong></td>
                      <td><strong><?php echo $liabilitie_shareholder['aug']?></strong></td>
                      <td><strong><?php echo $liabilitie_shareholder['sep']?></strong></td>
                      <td><strong><?php echo $liabilitie_shareholder['oct']?></strong></td>
                      <td><strong><?php echo $liabilitie_shareholder['nov']?></strong></td>
                      <td><strong><?php echo $liabilitie_shareholder['dece']?></strong></td>
                      <?php $total_liabilitie_shareholder = ($liabilitie_shareholder['jan']+$liabilitie_shareholder['feb']+$liabilitie_shareholder['mar']+$liabilitie_shareholder['apr']+$liabilitie_shareholder['may']+$liabilitie_shareholder['jun']+$liabilitie_shareholder['jul']+$liabilitie_shareholder['aug']+$liabilitie_shareholder['sep']+$liabilitie_shareholder['oct']+$liabilitie_shareholder['nov']+$liabilitie_shareholder['dece']) ?>
                      <td ><strong id="shareholder_color_<?php echo $i;?>" <?php echo ($total_asset_color['total_asset'] == $total_liabilitie_shareholder_color['total_liabilitie_shareholder'])?'style="
    color: #22e422;"':'style="
    color: red;"'?>><?php echo $total_liabilitie_shareholder; ?></strong></td>
                      <td></td>
                    </tr>
                  </tbody>
                  
                </table>
              </div>
            </div>
          <?php }?>
          </div>
        </div>

<?php if (isset($datepicker)) { ?>
<script src="<?=base_url()?>assets/js/slider/bootstrap-slider.js"></script>
<script src="<?=base_url()?>assets/js/datepicker/bootstrap-datepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.0/locales/bootstrap-datepicker.<?=(lang('lang_code') == 'en' ? 'en-GB': lang('lang_code'))?>.min.js"></script>


<script type="text/javascript">
// $('.datepicker-input').datepicker({
//     todayHighlight: true,
//     todayBtn: "linked",
//     autoclose: true
// });

$('.TimeSheetDate').datepicker();
    $('.datepicker-input').datepicker({
    todayHighlight: true,
    todayBtn: "linked",
    //autoclose: true
 }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else  
        {
        $(this).parent().parent().removeClass('focused');
        }
    });
     
</script>

<?php } ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
<?php if (isset($form)) { ?>
<script src="<?=base_url()?>assets/js/libs/select2.min.js"></script>
<script src="<?=base_url()?>assets/js/file-input/bootstrap-filestyle.min.js"></script>
<script src="<?=base_url()?>assets/js/wysiwyg/jquery.hotkeys.js"></script>
<script src="<?=base_url()?>assets/js/wysiwyg/bootstrap-wysiwyg.js"></script>
<script src="<?=base_url()?>assets/js/wysiwyg/demo.js"></script>
<script src="<?=base_url()?>assets/js/parsley/parsley.min.js"></script>
<script src="<?=base_url()?>assets/js/parsley/parsley.extend.js"></script>
<?php } ?>

<?php if ($this->uri->segment(2) == 'help') { ?>
 <!-- App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/1.0.0/intro.min.js"> </script>
<script src="<?=base_url()?>assets/js/intro/demo.js"> </script>
<?php }  ?>


<?php if ($this->uri->segment(1) == 'chats') { ?>
        <!-- <script type="text/javascript">
            $(document).ready(function(){
            if ($('#chat_details_appnd').html().trim().length == 0) {
                $("ul.chat_user_lst li:first").click();
                $('#chat_details_appnd').html('<div class="row"><div class="col-md-12 text-center"><img src="<?php echo base_url(); ?>assets/images/loader-mini.gif" alt="Loading"></div></div>');
                
            }
        });
        </script> -->
<?php } ?>
<script src="<?=base_url()?>assets/js/jquery.validate.js"></script>
<script src="<?=base_url()?>assets/js/additional-methods.min.js"></script>
<script src="<?=base_url()?>assets/js/datepicker/bootstrap-datepicker.js"></script>
<?php
if (isset($datatables)) {
    $sort = strtoupper(config_item('date_picker_format'));
?>
<script src="<?=base_url()?>assets/js/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/js/datatables/dataTables.bootstrap.min.js"></script>

<script src="<?=base_url()?>assets/js/datatables/datetime-moment.js"></script>




<script type="text/javascript">
        jQuery.extend( jQuery.fn.dataTableExt.oSort, {
            "currency-pre": function (a) {
                a = (a==="-") ? 0 : a.replace( /[^\d\-\.]/g, "" );
                return parseFloat( a ); },
            "currency-asc": function (a,b) {
                return a - b; },
            "currency-desc": function (a,b) {
                return b - a; }
        });
        $.fn.dataTableExt.oApi.fnResetAllFilters = function (oSettings, bDraw/*default true*/) {
                for(iCol = 0; iCol < oSettings.aoPreSearchCols.length; iCol++) {
                        oSettings.aoPreSearchCols[ iCol ].sSearch = '';
                }
                oSettings.oPreviousSearch.sSearch = '';

                if(typeof bDraw === 'undefined') bDraw = true;
                if(bDraw) this.fnDraw();
        }

        $(document).ready(function() {

        // $.fn.dataTable.moment('<?=$sort?>');
        // $.fn.dataTable.moment('<?=$sort?> HH:mm');

        var oTable1 = $('.AppendDataTables').dataTable({
        "bProcessing": true,
        "sDom": "<'row'<'col-sm-4'l><'col-sm-8'f>r>t<'row'<'col-sm-4'i><'col-sm-8'p>>",
        "sPaginationType": "full_numbers",
        "iDisplayLength": <?=config_item('rows_per_table')?>,
        "oLanguage": {
                "sProcessing": "<?=lang('processing')?>",
                "sLoadingRecords": "<?=lang('loading')?>",
                "sLengthMenu": "<?=lang('show_entries')?>",
                "sEmptyTable": "<?=lang('empty_table')?>",
                "sInfo": "<?=lang('pagination_info')?>",
                "sInfoEmpty": "<?=lang('pagination_empty')?>",
                "sInfoFiltered": "<?=lang('pagination_filtered')?>",
                "sInfoPostFix":  "",
                "sSearch": "<?=lang('search')?>:",
                "sUrl": "",
                "oPaginate": {
                        "sFirst":"<?=lang('first')?>",
                        "sPrevious": "<?=lang('previous')?>",
                        "sNext": "<?=lang('next')?>",
                        "sLast": "<?=lang('last')?>"
                }
        },
        "tableTools": {
                    "sSwfPath": "<?=base_url()?>assets/js/datatables/tableTools/swf/copy_csv_xls_pdf.swf",
              "aButtons": [
                      {
                      "sExtends": "csv",
                      "sTitle": "<?=config_item('company_name').' - '.lang('invoices')?>"
                  },
                      {
                      "sExtends": "xls",
                      "sTitle": "<?=config_item('company_name').' - '.lang('invoices')?>"
                  },
                      {
                      "sExtends": "pdf",
                      "sTitle": "<?=config_item('company_name').' - '.lang('invoices')?>"
                  },
              ],
        },
        "aaSorting": [],
        "aoColumnDefs":[{
                    "aTargets": ["no-sort"]
                  , "bSortable": false
              },{
                    "aTargets": ["col-currency"]
                  , "sType": "currency"
              }]
        });
            $("#table-tickets").dataTable().fnSort([[0,'desc']]);
            $("#table-tickets-archive").dataTable().fnSort([[1,'desc']]);


            $("#table-projects-client").dataTable().fnSort([[4,'asc']]);
            $("#table-projects-archive").dataTable().fnSort([[5,'desc']]);
            $("#table-teams").dataTable().fnSort([[0,'asc']]);
            $("#table-milestones").dataTable().fnSort([[2,'desc']]);
            $("#table-milestone").dataTable().fnSort([[2,'desc']]);
            $("#table-tasks").dataTable().fnSort([[2,'desc']]);
            $("#table-files").dataTable().fnSort([[2,'desc']]);
            $("#table-links").dataTable().fnSort([[0,'asc']]);
            $("#table-project-timelog").dataTable().fnSort([[0,'desc']]);
            $("#table-tasks-timelog").dataTable().fnSort([[0,'desc']]);

            $("#table-clients").dataTable().fnSort([[0,'asc']]);

            /* client search Hide start */

            var tableclients = $('#table-clients-compaines').DataTable();

            $('#client_search').click(function(){
                var clientname = $('#client_name').val();
                var client_email = $('#client_email').val();
                
                tableclients
                .columns( 0 )
                .search(  clientname )
                .columns( 4 )
                .search(  client_email )
                .draw();
                
            });
            $('#table-clients-compaines_filter').hide();


            /* client search Hide end */
            /* Project Data table start  */

             var tableprojects = $("#table-projects").DataTable(); //dataTable().fnSort([[0,'desc']]);

               $('#project_search_btn').click(function(){

                var project_title = $('#project_title').val();
                var client_name = $('#client_name').val();

                
                tableprojects
                .columns( 1 )
                .search(  project_title )
                .columns( 2 )
                .search(  client_name )
                .draw();
                
            });
            $('#table-projects_filter').hide();
            /* Project Data table END  */
            /* User Data Table Start */

            //$("#table-users").dataTable().fnSort([[4,'desc']]);
          /*   var tableusers = $("#table-users").DataTable();

               $('#users_search_btn').click(function(){

                var username = $('#username').val();
                var company = $('#company').val();
                var user_role = $('#user_role').val();

                tableusers
                .columns( 0 )
                .search(  username )
                .columns( 2 )
                .search(  company )
                .columns( 3 )
                .search(  user_role )
                .draw();
            });
            $('#table-users_filter').hide();*/

             var tableemployee = $("#table-employee").DataTable();

               $('#employee_search_btn').click(function(){

                var employee_id = $('#employee_id').val();
                var employee_email = $('#employee_email').val();
                var username = $('#username').val();
                var company = $('#company').val();

                tableemployee
                .columns( 0 )
                .search(  username )
                .columns( 1 )
                .search(  company )
                .columns( 2 )
                .search(  employee_id )
                .columns( 3 )
                .search(  employee_email )
                .draw();
            });
            $('#table-employee_filter').hide();

            /* User Data Table End */
            
            /* Ticked  Data Table Start  */

            //$("#table-tickets").dataTable().fnSort([[0,'desc']]);
             var tabletickets = $("#table-tickets").DataTable();

               $('#ticket_search_btn').click(function(){

                var employee_name = $('#employee_name').val();
                var ticket_status = $('#ticket_status').val();
                var ticked_priority = $('#ticked_priority').val();
                var ticket_from = $('#ticket_from').val();
                var ticket_to = $('#ticket_to').val();

                tabletickets
                .columns(2 )
                .search(  employee_name )
                .columns( 6 )
                .search(  ticket_status )
                .columns( 4 )
                .search(  ticked_priority )
                .draw();
                 if(ticket_from !='' && ticket_to!=''){

                 tabletickets.draw();

                 }
            });

               <?php if($this->uri->segment(1) == 'tickets'){ ?>

                $.fn.dataTable.ext.search.push(
                function( settings, data, dataIndex ) {
                var min  = $('#ticket_from').val();
                var max  = $('#ticket_to').val();

                var createdAt = data[7] || 0; // Our date column in the table

                if  (
                ( min == "" || max == "" )
                ||
                ( moment(createdAt).isSameOrAfter(min) && moment(createdAt).isSameOrBefore(max) )
                )
                {
                return true;
                }
                return false;
                }
                );
            <?php }  ?>

             $('#table-tickets_filter').hide();
            /* Ticked  Data Table End */

               /* Invoice  Data Table Start */
            // $("#table-invoices").dataTable().fnSort([[0,'desc']]);
            var tableinvoices = $("#table-invoices").DataTable();
            $('#tableinvoices_btn').click(function(){

                var invoices_status = $('#invoices_status').val();

                var ticket_from = $('#invoice_date_from').val();
                var ticket_to = $('#invoice_date_to').val();

                tableinvoices
                .columns(2 )
                .search(  invoices_status )
                .draw();
                 if(ticket_from !='' && ticket_to!=''){

                 tableinvoices.draw();

                 }
            });
               <?php if($this->uri->segment(1) == 'invoices'){ ?>
               $.fn.dataTable.ext.search.push(
                function( settings, data, dataIndex ) {
                var min  = $('#invoice_date_from').val();
                var max  = $('#invoice_date_to').val();

                var createdAt = data[1] || 0; // Our date column in the table

                if  (
                ( min == "" || max == "" )
                ||
                ( moment(createdAt).isSameOrAfter(min) && moment(createdAt).isSameOrBefore(max) )
                )
                {
                return true;
                }
                return false;
                }
                );
            <?php } ?>

             $('#table-invoices_filter').hide();
            /* Invoice  Data Table End */
            /* Expenses  Data Table Start */
            //$("#table-expenses").dataTable().fnSort([[0,'desc']]);
             var tableexpenses = $("#table-expenses").DataTable();

              $('#search_expenses_btn').click(function(){

                var expenes_project = $('#expenes_project').val();
                var expenes_client = $('#expenes_client').val();
                var expenses_category = $('#expenses_category').val();

                var from = $('#expenses_date_from').val();
                var to = $('#expenses_date_to').val();

                tableexpenses
                .columns(1 )
                .search(  expenes_project )
                .columns( 3 )
                .search(  expenes_client )
                .columns( 5 )
                .search(  expenses_category )
                .draw();
                 if(from !='' && to!=''){

                 tableexpenses.draw();

                 }
            });

              <?php if($this->uri->segment(1) == 'expenses'){ ?>
               $.fn.dataTable.ext.search.push(
                function( settings, data, dataIndex ) {
                var min  = $('#expenses_date_from').val();
                var max  = $('#expenses_date_to').val();

                var createdAt = data[6] || 0; // Our date column in the table

                if  (
                ( min == "" || max == "" )
                ||
                ( moment(createdAt).isSameOrAfter(min) && moment(createdAt).isSameOrBefore(max) )
                )
                {
                return true;
                }
                return false;
                }
                );
            <?php } ?>

             $('#table-expenses_filter').hide();
            /* Expenses  Data Table End */

              /* estimates  Data Table Start */
            // $("#table-estimates").dataTable().fnSort([[0,'desc']]);
             var tableestimates = $("#table-estimates").DataTable();

              $('#search_estimates_btn').click(function(){

                var estimates_status = $('#estimates_status').val();
                var from = $('#estimates_from').val();
                var to = $('#estimates_to').val();

                tableestimates
                .columns( 4 )
                .search(  estimates_status )
                .draw();
                 if(from !='' && to!=''){

                 tableestimates.draw();

                 }
            });

              <?php if($this->uri->segment(1) == 'estimates'){ ?>
               $.fn.dataTable.ext.search.push(
                function( settings, data, dataIndex ) {
                var min  = $('#estimates_from').val();
                var max  = $('#estimates_to').val();

                var createdAt = data[1] || 0; // Our date column in the table

                if  (
                ( min == "" || max == "" )
                ||
                ( moment(createdAt).isSameOrAfter(min) && moment(createdAt).isSameOrBefore(max) )
                )
                {
                return true;
                }
                return false;
                }
                );
            <?php } ?>

             $('#table-estimates_filter').hide();
            /* estimates  Data Table End */

            $("#table-client-details-1").dataTable().fnSort([[1,'asc']]);
            $("#table-client-details-2").dataTable().fnSort([[2,'desc']]);
            $("#table-client-details-3").dataTable().fnSort([[0,'asc']]);
            $("#table-client-details-4").dataTable().fnSort([[1,'asc']]);
            $("#table-templates-1").dataTable().fnSort([[0,'asc']]);
            $("#table-templates-2").dataTable().fnSort([[0,'asc']]);


            //$("#table-payments").dataTable().fnSort([[0,'desc']]);

            // $("#table-rates").dataTable().fnSort([[0,'asc']]);
            $("#table-bugs").dataTable().fnSort([[1,'desc']]);
            $("#table-stuff").dataTable().fnSort([[0,'asc']]);
            $("#table-activities").dataTable().fnSort([[0,'desc']]);

            $("#table-strings").DataTable().page.len(-1).draw();
            if ($('#table-strings').length == 1) { $('#table-strings_length, #table-strings_paginate').remove(); $('#table-strings_filter input').css('width','200px'); }


        $('#save-translation').on('click', function (e) {
            e.preventDefault();
            oTable1.fnResetAllFilters();
            $.ajax({
                url: base_url+'settings/translations/save/?settings=translations',
                type: 'POST',
                data: { json : JSON.stringify($('#form-strings').serializeArray()) },
                success: function() {
                    toastr.success("<?=lang('translation_updated_successfully')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });
        $('#table-translations').on('click','.backup-translation', function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            $.ajax({
                url: target,
                type: 'GET',
                data: {},
                success: function() {
                    toastr.success("<?=lang('translation_backed_up_successfully')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });
        $("#table-translations").on('click', '.restore-translation', function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            $.ajax({
                url: target,
                type: 'GET',
                data: {},
                success: function() {
                    toastr.success("<?=lang('translation_restored_successfully')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });
        $('#table-translations').on('click','.submit-translation', function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            $.ajax({
                url: target,
                type: 'GET',
                data: {},
                success: function() {
                    toastr.success("<?=lang('translation_submitted_successfully')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });
        $("#table-translations").on('click','.active-translation',function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            var isActive = 0;
            if (!$(this).hasClass('btn-success')) { isActive = 1; }
            $(this).toggleClass('btn-success').toggleClass('btn-default');
            $.ajax({
                url: target,
                type: 'POST',
                data: { active: isActive },
                success: function() {
                    toastr.success("<?=lang('translation_updated_successfully')?>", "<?=lang('response_status')?>");
                },
                error: function(xhr) {
                    alert('Error: '+JSON.stringify(xhr));
                }
            });
        });

        // $(".menu-view-toggle").on('click',function (e) {
        $(document).on('click','.menu-view-toggle',function(e){
            e.preventDefault();
            var target = $(this).attr('data-href');
            var role = $(this).attr('data-role');
            var vis = 1;
            if ($(this).hasClass('btn-success')) { vis = 0; }
            $(this).toggleClass('btn-success').toggleClass('btn-default');
            $.ajax({
                url: target,
                type: 'POST',
                data: { visible: vis, access: role },
                success: function() {},
                error: function(xhr) {}
            });
        });

        $(".cron-enabled-toggle").on('click',function (e) {
            e.preventDefault();
            var target = $(this).attr('data-href');
            var role = $(this).attr('data-role');
            var ena = 1;
            if ($(this).hasClass('btn-success')) { ena = 0; }
            $(this).toggleClass('btn-success').toggleClass('btn-default');
            $.ajax({
                url: target,
                type: 'POST',
                data: { enabled: ena, access: role },
                success: function() {},
                error: function(xhr) {}
            });
        });


        $('[data-rel=tooltip]').tooltip();
});
</script>
<?php }  ?>

<?php if (isset($iconpicker)) { ?>
<script type="text/javascript" src="<?=base_url()?>assets/js/iconpicker/fontawesome-iconpicker.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
            $('#site-icon').iconpicker({hideOnSelect: true, placement: 'bottomLeft'});
            $('.menu-icon').iconpicker().on('iconpickerSelected',function(event){
                var role = $(this).attr('data-role');
                var target = $(this).attr('data-href');
                $(this).siblings('div.iconpicker-container').hide();
                $.ajax({
                    url: target,
                    type: 'POST',
                    data: { icon: event.iconpickerValue, access: role  },
                    success: function() {},
                    error: function(xhr) {}
                });
            });
    });
</script>
<?php } ?>

<?php if (isset($sortable)) { ?>
<script type="text/javascript" src="<?=base_url()?>assets/js/sortable/jquery-sortable.js"></script>
<script type="text/javascript">
    var t1, t2, t3, t4, t5;
    $('#inv-details, #est-details').sortable({
        cursorAt: { top: 20, left: 0 },
        containerSelector: 'table',
        handle: '.drag-handle',
        revert: false,
        itemPath: '> tbody',
        itemSelector: 'tr.sortable',
        placeholder: '<tr class="placeholder"/>',
        afterMove: function() { clearTimeout(t1); t1 = setTimeout('saveOrder()', 500); }
    });
    $('#menu-admin').sortable({
        cursorAt: { top: 20, right: 20 },
        containerSelector: 'table',
        handle: '.drag-handle',
        revert: true,
        itemPath: '> tbody',
        itemSelector: 'tr.sortable',
        placeholder: '<tr class="placeholder"/>',
        afterMove: function() { clearTimeout(t2); t2 = setTimeout('saveMenu(\'admin\',1)', 500); }
    });
    $('#menu-client').sortable({
        cursorAt: { top: 20, right: 20 },
        containerSelector: 'table',
        handle: '.drag-handle',
        revert: true,
        itemPath: '> tbody',
        itemSelector: 'tr.sortable',
        placeholder: '<tr class="placeholder"/>',
        afterMove: function() { clearTimeout(t3); t3 = setTimeout('saveMenu(\'client\',2)', 500); }
    });
    $('#menu-staff').sortable({
        cursorAt: { top: 20, right: 20 },
        containerSelector: 'table',
        handle: '.drag-handle',
        revert: true,
        itemPath: '> tbody',
        itemSelector: 'tr.sortable',
        placeholder: '<tr class="placeholder"/>',
        afterMove: function() { clearTimeout(t4); t4 = setTimeout('saveMenu(\'staff\',3)', 500); }
    });
    $('#cron-jobs').sortable({
        cursorAt: { top: 20, left: 20 },
        containerSelector: 'table',
        handle: '.drag-handle',
        revert: true,
        itemPath: '> tbody',
        itemSelector: 'tr.sortable',
        placeholder: '<tr class="placeholder"/>',
        afterMove: function() { clearTimeout(t5); t5 = setTimeout('setCron()', 500); }
    });

    function saveOrder() {
        var data = $('.sorted_table').sortable("serialize").get();
        var items = JSON.stringify(data);
        var table = $('.sorted_table').attr('type');
        $.ajax({
            url: "<?=base_url()?>"+table+"/items/reorder/",
            type: "POST",
            dataType:'json',
            data: { json: items },
            success: function() { }
        });

    }
    function saveMenu(table, access) {
        var data = $("#menu-"+table).sortable("serialize").get();
        var items = JSON.stringify(data);
        $.ajax({
            url: "<?=base_url()?>settings/hook/reorder/"+access,
            type: "POST",
            dataType:'json',
            data: { json: items },
            success: function() { }
        });
    }

    function setCron() {
        var data = $('#cron-jobs').sortable("serialize").get();
        var items = JSON.stringify(data);
        $.ajax({
            url: "<?=base_url()?>settings/hook/reorder/1",
            type: "POST",
            dataType:'json',
            data: { json: items },
            success: function() { }
        });
    }
</script>
<?php } ?>

<?php if (isset($nouislider)) { ?>
<script type="text/javascript" src="<?=base_url()?>assets/js/nouislider/jquery.nouislider.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    var progress = $('#progress').val();
    $('#progress-slider').noUiSlider({
            start: [ progress ],
            step: 10,
            connect: "lower",
            range: {
                'min': 0,
                'max': 100
            },
            format: {
                to: function ( value ) {
                    return Math.floor(value);
                },
                from: function ( value ) {
                    return Math.floor(value);
                }
            }
    });
    $('#progress-slider').on('slide', function() {
        var progress = $(this).val();
        $('#progress').val(progress);
        $('.noUi-handle').attr('title', progress+'%').tooltip('fixTitle').parent().find('.tooltip-inner').text(progress+'%');
    });

    $('#progress-slider').on('change', function() {
        var progress = $(this).val();
        $('#progress').val(progress);
    });

    $('#progress-slider').on('mouseover', function() {
        var progress = $(this).val();
        $('.noUi-handle').attr('title', progress+'%').tooltip('fixTitle').tooltip('show');
    });

    var invoiceHeight = $('#invoice-logo-height').val();
    $('#invoice-logo-slider').noUiSlider({
            start: [ invoiceHeight ],
            step: 1,
            connect: "lower",
            range: {
                'min': 30,
                'max': 150
            },
            format: {
                to: function ( value ) {
                    return Math.floor(value);
                },
                from: function ( value ) {
                    return Math.floor(value);
                }
            }
    });
    $('#invoice-logo-slider').on('slide', function() {
        var invoiceHeight = $(this).val();
        var invoiceWidth = $('.invoice_image img').width();
        $('#invoice-logo-height').val(invoiceHeight);
        $('#invoice-logo-width').val(invoiceWidth);
        $('.noUi-handle').attr('title', invoiceHeight+'px').tooltip('fixTitle').parent().find('.tooltip-inner').text(invoiceHeight+'px');
        $('.invoice_image img').css('height',invoiceHeight+'px');
        $('#invoice-logo-dimensions').html(invoiceHeight+'px x '+invoiceWidth+'px');
    });

    $('#invoice-logo-slider').on('change', function() {
        var invoiceHeight = $(this).val();
        var invoiceWidth = $('.invoice_image img').width();
        $('#invoice-logo-height').val(invoiceHeight);
        $('#invoice-logo-width').val(invoiceWidth);
        $('.invoice_image').css('height',invoiceHeight+'px');
        $('#invoice-logo-dimensions').html(invoiceHeight+'px x '+invoiceWidth+'px');
    });

    $('#invoice-logo-slider').on('mouseover', function() {
        var invoiceHeight = $(this).val();
        $('.noUi-handle').attr('title', invoiceHeight+'px').tooltip('fixTitle').tooltip('show');
    });

});

function edit_model(task_id){
   
     $.post(base_url + 'all_tasks/get_task_details/', { task_id: task_id}, function (datas) {

         var data = JSON.parse(datas);        
        
               // $("#t_id").val(data.t_id);
               // $("#task_name").val(data.task_name);
               // $("#status").val(data.status);
               // $("#priority").val(data.priority);
               $("#progress_task"+task_id).val(data.task_progress);
               // $("#due_date").val(data.due_date);
               // $("#assigned_to_edit").val(data.assigned_to); 

               var progress = data.task_progress;

                $('#progress-slider_task'+task_id).noUiSlider({
            start: [ progress ],
            step: 10,
            connect: "lower",
            range: {
                'min': 0,
                'max': 100
            },
            format: {
                to: function ( value ) {
                    return Math.floor(value);
                },
                from: function ( value ) {
                    return Math.floor(value);
                }
            }
    });
    $('#progress-slider_task'+task_id).on('slide', function() {
        var progress = $(this).val();
        $('#progress_task'+task_id).val(progress);
        $('.noUi-handle').attr('title', progress+'%').tooltip('fixTitle').parent().find('.tooltip-inner').text(progress+'%');
    });

    $('#progress-slider_task'+task_id).on('change', function() {
        var progress = $(this).val();
        $('#progress_task'+task_id).val(progress);
    });

    $('#progress-slider_task'+task_id).on('mouseover', function() {
        var progress = $(this).val();
        $('.noUi-handle').attr('title', progress+'%').tooltip('fixTitle').tooltip('show');
    });

              
            });
}
</script>
<?php } ?>

<?php if (isset($calendar) || isset($fullcalendar)) { ?>
<?php $lang = lang('lang_code'); if ($lang == 'en') { $lang = 'en-gb'; } ?>
<script src="<?=base_url()?>assets/js/moment.min.js"></script>
<script src="<?=base_url()?>assets/js/fullcalendar.min.js"></script>
<script src="<?=base_url()?>assets/js/calendar/gcal.js"></script>
<script src="<?=base_url()?>assets/js/calendar/lang/<?=$lang?>.js"></script>
<?php if (isset($calendar)) { ?>
 <?=$this->load->view('sub_group/calendarjs')?>
<?php } ?>


<?php
if(User::is_staff()) :
$tasks = $this->db->select('*, dgt_tasks.due_date as task_due, dgt_tasks.start_date as task_start',TRUE)->join('assign_tasks','task_assigned = t_id')->where('assigned_user',User::get_id())->get('tasks')->result();
$projects = $this->db->join('assign_projects','project_assigned = project_id')
                  ->where('assigned_user',User::get_id())->get('projects')->result();

$events = $this->db->where('added_by',User::get_id())->get('events')->result();
else:

$tasks = $this->db->select('*, dgt_tasks.due_date as task_due, dgt_tasks.start_date as task_start',TRUE)->join('projects','project = project_id')->get('tasks')->result();
$payments = $this->db->join('invoices','invoice = inv_id')->join('companies','paid_by = co_id')->get('payments')->result();
$invoices = $this->db->join('companies','client = co_id')->get('invoices')->result();
$estimates = $this->db->join('companies','client = co_id')->get('estimates')->result();
$projects = $this->db->join('companies','client = co_id')->get('projects')->result();
$events = $this->db->get('events')->result();
$leave_list   = $this->db->query("SELECT ul.*,lt.leave_type as l_type,ad.fullname
										FROM `dgt_user_leaves` ul
										left join dgt_leave_types lt on lt.id = ul.leave_type
										left join dgt_account_details ad on ad.user_id = ul.user_id
										where ul.status != 4 order by ul.id  ASC ")->result();
endif;
$gcal_api_key = config_item('gcal_api_key');
$gcal_id = config_item('gcal_id');
?>
<script type="text/javascript">

    $(document).ready(function () {

        $('#calendar').fullCalendar({
            googleCalendarApiKey: '<?=$gcal_api_key?>',
            header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay',
      },
      eventLimit: true,
            eventAfterRender: function(event, element, view) {
                if (event.type == 'fo') {
                    $(element).attr('data-toggle', 'ajaxModal').addClass('ajaxModal');
                }
            },
            eventSources: [
                <?php if(User::is_admin()) : ?>
                {
                    events: [
                    <?php foreach ($payments as $p) { ?>
                            {
                                title  : '<?=addslashes($p->company_name)."  (".Applib::format_currency($p->currency, $p->amount).")"?>',
                                start  : '<?= date('Y-m-d', strtotime($p->payment_date)) ?>',
                                end: '<?= date('Y-m-d', strtotime($p->payment_date)) ?>',
                                url: '<?= base_url('calendar/event/payments/' . $p->p_id) ?>',
                                type: 'fo'
                            },
                    <?php } ?>
                    ],
                    color: '#FBBE59',
                    textColor: 'black'
                },<?php endif; ?>
                <?php if(User::is_admin()) : ?>
                {
                    events: [
                    <?php foreach ($invoices as $i) { ?>
                            {
                                title  : '<?=$i->reference_no." ".addslashes($i->company_name)?>',
                                start  : '<?= date('Y-m-d', strtotime($i->due_date)) ?>',
                                end: '<?= date('Y-m-d', strtotime($i->due_date)) ?>',
                                url: '<?= base_url('calendar/event/invoices/' . $i->inv_id) ?>',
                                type: 'fo'
                            },
                    <?php } ?>
                    ],
                    color: '#E27777',
                    textColor: 'white'
                },<?php endif; ?>
                {
                    events: [
                    <?php foreach ($events as $e) { ?>
                            {
                                title  : '<?=addslashes($e->event_name)?>',
                                start  : '<?=date('Y-m-d', strtotime($e->start_date)) ?>',
                                end: '<?= date('Y-m-d', strtotime($e->end_date)) ?>',
                                url: '<?= base_url('calendar/event/events/' . $e->id) ?>',
                                type: 'fo',
                                color: '<?=$e->color?>'
                            },
                    <?php } ?>
                    ],
                    color: '#38354a',
                    textColor: 'white'
                },
                {
                    googleCalendarId: '<?=$gcal_id?>'
                }
            ]
        });
    });
    
</script>
<?php } ?>


<?php if (isset($set_fixed_rate)) { ?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#fixed_rate").click(function(){
            //if checked
            if($("#fixed_rate").is(":checked")){
                $("#fixed_price").show("fast");
                $("#hourly_rate").hide("fast");
                }else{
                    $("#fixed_price").hide("fast");
                    $("#hourly_rate").show("fast");
                }
        });
    });
</script>
<?php } ?>

<?php if (isset($postmark_config)) { ?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#use_postmark").click(function(){
            //if checked
            if($("#use_postmark").is(":checked")){
                $("#postmark_config").show("fast");
                }else{
                    $("#postmark_config").hide("fast");
                }
        });
        $("#use_alternate_emails").click(function(){
            //if checked
            if($("#use_alternate_emails").is(":checked")){
                $("#alternate_emails").show("fast");
                }else{
                    $("#alternate_emails").hide("fast");
                }
        });
    });
</script>
<?php } ?>

<?php if (isset($braintree_setup)) { ?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#use_braintree").click(function(){
            //if checked
            if($("#use_braintree").is(":checked")){
                $("#braintree_setup").show("fast");
                }else{
                    $("#braintree_setup").hide("fast");
                }
        });
    });
</script>
<?php } ?>

<?php if (isset($attach_slip)) { ?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#attach_slip").click(function(){
            //if checked
            if($("#attach_slip").is(":checked")){
                $("#attach_field").show("fast");
                }else{
                    $("#attach_field").hide("fast");
                }
        });
    });
</script>
<?php } ?>

<?php if (isset($task_checkbox)) { ?>
<script type="text/javascript">

$(document).ready(function() {

$('.task_complete input[type="checkbox"]').change(function() {

    var task_id = $(this).data().id;
    var task_complete = $(this).is(":checked");

    var formData = {
            'task_id'         : task_id,
            'task_complete'   : task_complete
        };
    $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : '<?=base_url()?>projects/tasks/progress', // the url where we want to POST
            data        : formData, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
            encode          : true
        })
            // using the done promise callback
            .done(function(data) {

                 if ( ! data.success) {
                        alert('There was a problem with AJAX');
                    }else{
                        location.reload();
                    }

                // here we will handle errors and validation messages
            });

  });



});
</script>
<?php } ?>


<?php if (isset($todo_list)) { ?>
<script type="text/javascript">

$(document).ready(function() {

$('.todo_complete input[type="checkbox"]').change(function() {

    var id = $(this).data().id;
    var todo_complete = $(this).is(":checked");

    var formData = {
            'id'         : id,
            'todo_complete'   : todo_complete
        };
    $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : '<?=base_url()?>projects/todo/status', // the url where we want to POST
            data        : formData, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
            encode      : true
        })
            // using the done promise callback
            .done(function(data) {

                 if ( ! data.success) {
                        alert('There was a problem with AJAX');
                    }else{
                        location.reload();
                    }

                // here we will handle errors and validation messages
            });

  });

});
</script>
<?php } ?>

 <?php
if($this->session->flashdata('message')){
$message = $this->session->flashdata('message');
$alert = $this->session->flashdata('response_status'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        swal({
            title: "<?=lang($alert)?>",
            text: "<?=$message?>",
            type: "<?=$alert?>",
            timer: 5000,
            confirmButtonColor: "#38354a"
        });
});
</script>
<?php } ?>

<?php if (isset($typeahead)) { ?>
<script type="text/javascript">
    $(document).ready(function(){

        var scope = $('#auto-item-name').attr('data-scope');
        if (scope == 'invoices' || scope == 'estimates') {

        var substringMatcher = function(strs) {
          return function findMatches(q, cb) {
            var substrRegex;
            var matches = [];
            substrRegex = new RegExp(q, 'i');
            $.each(strs, function(i, str) {
              if (substrRegex.test(str)) {
                matches.push(str);
              }
            });
            cb(matches);
          };
        };

        $('#auto-item-name').on('keyup',function(){ $('#hidden-item-name').val($(this).val()); });

        $.ajax({
            url: base_url + scope + '/autoitems/',
            type: "POST",
            data: {},
            success: function(response){
                $('.typeahead').typeahead({
                    hint: true,
                    highlight: true,
                    minLength: 2
                    },
                    {
                    name: "item_name",
                    limit: 10,
                    source: substringMatcher(response)
                });
                $('.typeahead').bind('typeahead:select', function(ev, suggestion) {
                    $.ajax({
                        url: base_url + scope + '/autoitem/',
                        type: "POST",
                        data: {name: suggestion},
                        success: function(response){
                            $('#hidden-item-name').val(response.item_name);
                            $('#auto-item-desc').val(response.item_desc).trigger('keyup');
                            $('#auto-quantity').val(response.quantity);
                            $('#auto-unit-cost').val(response.unit_cost);
                        }
                    });
                });
            }
        });
    }


    });
</script>
<?php } ?>
<!-- this is settings page leave type scripts -->
<script type="text/javascript">
function leave_day_type(){
  var val  = $("input[name='req_leave_day_type']:checked").val();
  if(val == 2 || val == 3){  val = 0.5;  }
  $('#req_leave_count').val(val);
}
function leave_days_calc(){
    $('#leave_day_type').hide();

    var new_cnt = 0;

    if($('#req_leave_date_from').val()!=''){
        var d1     = $('#req_leave_date_from').datepicker('getDate');
    var d2     = $('#req_leave_date_to').datepicker('getDate');

    if(d1  != null && d2 != null){
        while(d1 <= d2){
           if(d1.getDay() != 0){  new_cnt++; }
           var newDate = d1.setDate(d1.getDate() + 1);
           d1 = new Date(newDate);
        }
        //alert(new_cnt);
    }
    }

    /*var oneDay = 24*60*60*1000;
    var diff   = 0;
    if (d1 && d2) {
       diff = Math.round(Math.abs((d2.getTime() - d1.getTime())/(oneDay)));
    }*/
	if(new_cnt == 1){
		$("input[name=req_leave_day_type][value='1']").prop("checked",true);
		$('#leave_day_type').show();
 	}
    if(new_cnt > 18){
        $('#lop_call').css('display','');
    }
    $('#req_leave_count').val(new_cnt);

}
$(document).ready(function(){
		$("#emp_doj").datepicker({ });
    if($("#req_leave_date_from").length > 0){
        $("#req_leave_date_from").datepicker({ });
		$("#req_leave_date_to").datepicker({ });
	}
	$('.leave_datepicker').each(function() {
		var minDate = new Date();
		minDate.setHours(0);
		minDate.setMinutes(0);
		minDate.setSeconds(0,0);
		var $picker = $(this);
		$picker.datepicker();
		var pickerObject = $picker.data('datepicker');
		$picker.on('changeDate', function(ev){
 			if (ev.date.valueOf() < minDate.valueOf()){
 				$picker.datepicker('setDate', minDate);
 				ev.preventDefault();
				return false;
			}
		});
    });

	$('.leave_type_edit').on('click', function (e) {
  		var det = $(this).attr('data_type').split('^');
		 $('#leave_type_tbl_id').val(det[0]);
		 $('#leave_type').val(det[1]);
		 //$('#leave_days').val(det[2]);
		 $("#leave_days").select2("val", det[2]);
		 $('.l_type_save_btn').html('Update');
		 $('.leave_type_add_form').show();
 	});

$('#admin_search_leave').on('click', function (e) {
	    e.preventDefault();
		var scope  = 'leaves';
        var target = $(this).attr('href');
 		var l_type = $('#ser_leave_type').val();
		var l_sts  = $('#ser_leave_sts').val();
		var dfrom  = $('#ser_leave_date_from').val();
		var dto    = $('#ser_leave_date_to').val();
        var uname  = $.trim($('#ser_leave_user_name').val());
        if( l_type != '' && l_sts != '' && dfrom != '' && dto != '' && uname != '')
        {
  		$('#admin_leave_tbl').html('<tr> <td class="text-center" colspan="9"><img src="<?=base_url()?>assets/images/loader-mini.gif" alt="Loading"></td> </tr>');
		$.ajax({
            url    : "<?=base_url()?>"+ scope + '/search_leaves/',
            type   : "POST",
            data   : {l_type:l_type,l_sts:l_sts,uname:uname,dfrom:dfrom,dto:dto,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'},
            success: function(response) {
			               $('#admin_leave_tbl').html(response);
 			}
        });
        }
 	});
});
</script>

<?php if (isset($gantt)) { ?>

<script src="<?=base_url()?>assets/js/charts/gantt/jquery.fn.gantt.js"></script>

<script>
$(".gantt").gantt({
    source: [
    <?php
    if(!User::is_client()){
    $tasks = $this->db->order_by('t_id','desc')->where(array('project'=>$project))->get('tasks')->result();
    }else{
    $tasks = $this->db->order_by('t_id','desc')->where(array('project'=>$project,'visible'=>'Yes'))->get('tasks')->result();
    }
    foreach ($tasks as $key => $t) { $start_date = ($t->start_date == NULL) ? $t->date_added : $t->start_date; ?>
{
  "name": '<a href="<?=site_url()?>projects/view/<?=$project?>?group=tasks&view=task&id=<?=$t->t_id?>" class="text-info"><?=addslashes($t->task_name)?> </a>',
  "desc": "",
  "values": [
            {"from":  Date.parse("<?=date('Y/m/d',strtotime($start_date))?>"), "to": Date.parse("<?=date('Y/m/d',strtotime($t->due_date))?>"),
            "desc": "<b><?=$t->task_name?></b> - <em><?=$t->task_progress?>% <?=lang('done')?></em><br><div class=\"line line-dashed line-lg pull-in\"></div><em><?=lang('start_date')?>: <span class=\"text-success text-small\"><?=strftime(config_item('date_format'), strtotime($start_date));?></span> to <?=lang('due_date')?>: <span class=\"text-danger text-small\"><?=strftime(config_item('date_format'), strtotime($t->due_date));?></span></em>",
            "customClass": '<?php if($t->task_progress == '100'){ echo "ganttGreen"; }else{ echo "ganttRed"; } ?>', "label": "<?=$t->task_name?>"
            }
  ]
},
<?php } ?>
],

    maxScale: "months",
    itemsPerPage: 25,
});
</script>
<?php } ?>
<script type="text/javascript">
$(document).ready(function() {
 //    var param = 0; //this is for ie problem just 1 parametter

 //   // setInterval(function(){ get_all_new_chats(param); }, 5000); // this will run after every 5 seconds

 //    // get_all_new_chats(param); //for page load first time
	// //get_last_chat_user(param);
	// setInterval(function(){
	//    var param = 0; //this is for ie problem just 1 parametter
 //      //get_all_new_chats(param); // this will run after every 5 seconds
	//   get_oposit_new_chats(1,0);
	// }, 5000);

    $('.clickable tr').click(function() {
        var href = $(this).find("a").attr("href");
        if(href) {
            window.location = href;
        }
    });

});

function show_user_sidebar(){
    $('.chat_user_sidebar').toggleClass('open-msg-box')
}
function filter_chat_user(text)

{

    $("#chat-contacts_list > li").each(function() {

		if ($(this).find('.contact-name').text().search(new RegExp(text, "i")) > -1) {

			$(this).show();

		}else {

			$(this).hide();

		}

    });

}

function get_all_new_chats(param)

{

 	  $.ajax({

			  url      : "<?=base_url()?>"+'chats/all_new_chats/',

			  dataType : 'json',

			  success  : function(res){

				             var chat_id   = $('.chat_user_lst').find('.active_cls').attr('chat_id');

							 if(!chat_id) chat_id = 0;

 				             $.each(res.chats, function(key,value){

 								  if($('#chat-'+key).length == 0){

									  var chld = $('#chat-window-container').children().size();

						   			  if(chld < 5){

 									 	 get_users_chats(key,1);

									  }

 								  }else if($('#chat-'+key).length == 1){

									  var html2 = value.html2;

									  var d = $('#chat-'+key).find('.panel-body');

									  var ids = '';

 									  $.each(html2, function(key2,value2){

 										 if($('#c_wind_'+key2).length == 0){

											// $(d).append(value2).animate({ scrollTop: $(d)[0].scrollHeight}, 500);

										 }

 										 if(ids == ''){ ids += key2; }else { ids += ','+key2; }

									 });

									 $('#new_chat_tbl_ids_'+key).val(ids);

  								  }



								  if($('.chat_usr_'+key).length == 0){

 									new_side_user_window(key);

 								  }else{

									 $('.chat_usr_'+key).find('.usr_last_chat_date').html(value.time);

									 $('.chat_usr_'+key).find('.usr_last_chat_det').html(value.content+' .....');

								  }

								  var tot_new = 0; $.each(value.html2, function(key2,value2){  tot_new++;  });

								  $('#new_chat_cnt_'+key).html(tot_new).show();



								  if(chat_id == key){

									  var html1 = value.html1;

									  $.each(html1, function(key3,value3){

 										if($('#c_wind2_'+key3).length == 0){

											$("#chat_details_appnd").append(value3);

										}

									  });

  								  }

  				              });

 				 }

	  });

}

function change_chat_sts(id,user_id){

    var ids = $(id).val();

	if(ids != ''){

		$.ajax({

			url     : "<?=base_url()?>"+'chats/change_chat_sts/',

			type    : "POST",

			data    : { ids : ids,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

			success : function(response) {

				                            $(id).val('');

											$('#new_chat_cnt_'+user_id).html(0).hide();

										 }

		});

	}

}

function get_users_chats(user_id,type){

	$.ajax({

		url     : "<?=base_url()?>"+'chats/new_chat_window/',

		type    : "POST",

		data    : { user_id : user_id,type:type,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

		success : function(response) {

		               if($('#chat-'+user_id).length == 0){

 						   var chld = $('#chat-window-container').children().size();

						   if(chld == 5){

  							   $('#chat-window-container').find('div:first').remove();

						   }

 						   $('#chat-window-container').append(response);

 						   var d = $('#chat-'+user_id).find('.panel-body');

						   $(d).animate({ scrollTop: $(d)[0].scrollHeight});

						   if(type == 0){ $('#chat_txt_bx'+user_id).focus(); }

  					   }

 		}

	});

}

function chat_window_toggle(ele){

 	if($(ele).hasClass( "panel-heading" )){

 		$(ele).parent().find('.panel-body,.chat_text_bx').toggle();

	}

}

function email_list_active(ele)

{

    $(ele).parent().children().each(function(index, element) {  $(this).removeClass('active_cls');  });

	$(ele).addClass('active_cls');

}



function save_chat()

{

   $("#_error_").html('').hide();

   var chat_id   = $('.chat_user_lst').find('.active_cls').attr('chat_id');

   if(chat_id){

     var chat_content = $.trim($('#chat_message_content').val());

	 if(chat_content.length > 0){

			 $.ajax({

				url      : "<?=base_url()?>"+'chats/save_chat/',

				type     : "POST",

				dataType : 'json',

				data     : { chat_id : chat_id,chat_content : chat_content,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

				success  : function(response) {

				                $('.chat_user_sidebar').hide();

								if(response.html1 != '') {

 								   $('.chat_usr_'+chat_id).find('.usr_last_chat_date').html(response.time);

								   $('.chat_usr_'+chat_id).find('.usr_last_chat_det').html(response.content);

   								   $('#chat_message_content').val('');

								   $('#chat_details_appnd').append(response.html1);

							    }

								$('.chat-wrap-inner').animate({scrollTop: $('#chat_details_appnd').outerHeight()}, 1000);
								if($('#chat-'+chat_id).length == 1){

									var d = $('#chat-'+chat_id).find('.panel-body');

									$(d).append(response.html2).animate({ scrollTop: $(d)[0].scrollHeight}, 1000);

 							    }

				}

			 });

	 }else{

		 $("#_error_").html('Please Enter Some Content.....').show();

	 }

	 return false;

    }else{

		 $("#_error_").html('Please select Users.....').show();

		 return false;

    }

}

function save_chat2(chat_id)

{

     var chat_tbl_id  = $.trim($('#chat_tbl_id_bx'+chat_id).val());

     var chat_content = $.trim($('#chat_txt_bx'+chat_id).val());

	 if(chat_content.length > 0){

			 $.ajax({

				url      : "<?=base_url()?>"+'chats/save_chat2/',

				type     : "POST",

				dataType : 'json',

				data     : { chat_tbl_id: chat_tbl_id,chat_id : chat_id,chat_content : chat_content,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

				success  : function(response) {

 				                if(chat_tbl_id == 0){ $('#chat_tbl_id_bx'+chat_id).val(response.chat_tbl_id); }

								if($('#chat-'+chat_id).length == 1){

 									$('#chat_txt_bx'+chat_id).val('');

									var d = $('#chat-'+chat_id).find('.panel-body');

									$(d).append(response.html2).animate({ scrollTop: $(d)[0].scrollHeight}, 1000);

 							    }

								var active_id   = $('.chat_user_lst').find('.active_cls').attr('chat_id');

								if(!active_id) { active_id = 0; }

 								if(active_id != 0 && active_id == chat_id) {

								   $('#chat_details_appnd').append(response.html1);

							    }

 								if($('.chat_usr_'+chat_id).length == 0){

 									new_side_user_window(chat_id);

 								}else{

									$('.chat_usr_'+chat_id).find('.usr_last_chat_date').html(response.time);

									$('.chat_usr_'+chat_id).find('.usr_last_chat_det').html(response.content);

								}

 				}

			 });

	 }

	 return false;

 }

 function new_side_user_window(user_id)

{

 	 if(user_id != "" || user_id != 0){

		 $.ajax({

				  url     : "<?=base_url()?>"+'chats/new_sidebar_window/',

				  data    : { user_id : user_id,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

				  type    :"POST",

				  success : function(res){

					   if(res != '') {

						  $('.chat_user_lst').append(res);

					   }

				  }

		 });

	 }

}

function chat_details(user_id)

{

	 if(user_id != "" || user_id != 0){

		 $.ajax({

				  url     : "<?=base_url()?>"+'chats/chat_details/',

				  data    : { user_id : user_id,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

				  type    :"POST",

				  success : function(res){

					   if(res != '') {

						  $('.chat_user_sidebar').hide();

 						  // $("#new_chat_icon"+user_id).remove();

						  $('#new_chat_cnt_'+user_id).hide();

 						  $('#chat_details_appnd').html(res).hide();
						  $('.chat-wrap-inner').animate({scrollTop: $('#chat_details_appnd').outerHeight()}, 3000);
						  setTimeout(function(){$('#chat_details_appnd').show();},2000);
					   }

				  }

		 });

	 }

}

function get_oposit_new_chats(ty,user_id)

{

  if(ty == 1 && user_id == 0){

	  var last_chat = $("#chat_details_appnd").children(":first").attr('c_lst2');

	  var chat_id   = $('.chat_user_lst').find('.active_cls').attr('chat_id');

  }else if(ty == 2 && user_id != 0){

 	  var last_chat = $("#chat-"+user_id).find('.panel-body').children(":last-child").attr('c_lst');

	  var chat_id   = user_id;

  }

  if(last_chat){} else var last_chat = 0;

  if(chat_id){

		 $.ajax({

				  url      : "<?=base_url()?>"+'chats/oposit_new_chat/',

				  data     : { last_chat : last_chat,chat_id:chat_id,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

				  type     : "POST",

				  dataType : 'json',

				  success  : function(res){

 				        var active_id   = $('.chat_user_lst').find('.active_cls').attr('chat_id');

					    if(!active_id) { active_id = 0; }

						if(active_id == chat_id){

							$.each(res.html1, function(key, value){

								if($('#c_wind2_'+key).length == 0){

									$("#chat_details_appnd").append(value);

								}

							});

						}

						if($('#chat-'+chat_id).length == 1){

							var d = $('#chat-'+chat_id).find('.panel-body');

							$.each(res.html2, function(key, value){

								if($('#c_wind_'+key).length == 0){

									$(d).append(value).animate({ scrollTop: $(d)[0].scrollHeight}, 500);

								}

							});

 						}



						if(res.time != ''){

 							$('.chat_usr_'+chat_id).find('.usr_last_chat_date').html(res.time);

						}

						if(res.content != '' && res.content){

					    	$('.chat_usr_'+chat_id).find('.usr_last_chat_det').html('You : '+res.content+' .....');

						}

  				  }

		 });

  }

}



function get_year_holidays(year)

	{ 	 if(year != "" || year != 0){

			 $('#holiday_tbl_body').html('<tr> <td class="text-center" colspan="5"><img src="<?=base_url()?>assets/images/loader-mini.gif" alt="Loading"></td> </tr>');

			 $.ajax({

					  url     : "<?=base_url()?>"+'holidays/year_holidays/',

					  data    : { year : year,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

					  type    :"POST",

					  success : function(res){

							  $('#holiday_tbl_body').html(res);

					  }

			 });

		 }

	}



function staff_salary_update(ty)

	{

		 var user_id  = $.trim($('#salary_user_id').val());

		 var amount  = $.trim($('.user_salary_amnt_'+ty).val());

	     if(user_id != '' && amount != ''){

			 $.ajax({

				  url     : "<?=base_url()?>"+'payroll/update_salary/',

				  data    : { user_id : user_id,amount : amount,type : ty,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

				  type    :"POST",

				  success : function(res){

				             var r_url = "<?=base_url()?>"+'payroll/';

						     if(res == 1){

								 window.location = r_url;

							 }

				  }

			 });

		 }

	}

function staff_salary_detail(user_id)

	{

		 var year    = $.trim($('#payslip_year').val());

		 var month   = $.trim($('#payslip_month').val());

	     if(user_id != ''){

			 $.ajax({

				  url     : "<?=base_url()?>"+'payroll/salary_detail/',

				  data    : { user_id : user_id,year : year,month : month,'<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>' },

				  type    :"POST",

				  dataType:"json",

				  success : function(res){
                    console.log(res);

				              if(res){

 								     $('#payslip_basic').val(res.basic);
                                     $('#payslip_hra').val(res.hra);
                                     $('#payslip_others').val(res.other);


                                      var other_details = res.payment_details;

                                     if(typeof(other_details.payslip_conveyance) !=='undefined'){

                                         $('#payslip_conveyance').val(other_details.payslip_conveyance);
                                         $('#payslip_allowance').val(other_details.payslip_allowance);
                                         $('#payslip_medical_allowance').val(other_details.payslip_medical_allowance);
                                         $('#payslip_others').val(other_details.payslip_others);
                                         $('#payslip_ded_tds').val(other_details.payslip_ded_tds);
                                         $('#payslip_ded_esi').val(other_details.payslip_ded_esi);
                                         $('#payslip_ded_pf').val(other_details.payslip_ded_pf);
                                         $('#payslip_ded_leave').val(other_details.payslip_ded_leave);
                                         $('#payslip_ded_prof').val(other_details.payslip_ded_prof);
                                         $('#payslip_ded_welfare').val(other_details.payslip_ded_welfare);
                                         $('#payslip_ded_fund').val(other_details.payslip_ded_fund);
                                         $('#payslip_ded_others').val(other_details.payslip_ded_others);
                                     }else{
                                        $('#payslip_conveyance,#payslip_allowance,#payslip_medical_allowance,#payslip_others,#payslip_ded_tds,#payslip_ded_esi,#payslip_ded_pf,#payslip_ded_leave,#payslip_ded_prof,#payslip_ded_welfare,#payslip_ded_fund,#payslip_ded_others').val('');

                                     }



							  } else{
                                  $('#payslip_basic,#payslip_da,#payslip_hra,#payslip_conveyance,#payslip_allowance,#payslip_medical_allowance,#payslip_others,#payslip_ded_tds,#payslip_ded_esi,#payslip_ded_pf,#payslip_ded_leave,#payslip_ded_prof,#payslip_ded_welfare,#payslip_ded_fund,#payslip_ded_others').val('');
                              }

 				  }

			 });

		 }

	}

  $('#attendance_search_btn').click(function(){

          var user_id = $('#username').val();
          var attendance_date = $('#attendance_date').val();
          console.log(attendance_date);
          if(attendance_date != ''){
          $.post('<?=base_url()?>attendance/attendance/get_list',{user_id:user_id, date:attendance_date},function(data){
            if(data){
    		        var full_records = JSON.parse(data);
                var html = '';
                if(full_records.length > 0){
                  html = html + '<table class="table table-striped custom-table datatable m-b-0">'+
                  '<thead>'+
										'<tr>'+
											'<th>Employee Name</th>'+
											'<th>Punch In</th>'+
											'<th>Punch In Note</th>'+
											'<th>Punch In Address</th>'+
											'<th>Punch Out</th>'+
											'<th>Punch Out Note</th>'+
											'<th>Punch Out Address</th>'+
											'<th>Duration(Hours)</th>'+
											'<th>Total(Hours)</th>'+
										'</tr>'+
									'</thead>'+
									'<tbody>';
                  $(full_records).each(function(i,record){
                    html = html + '<tr>'+
											'<td>'+record.fullname+'</td>'+
											'<td>'+record.punch_in_date_time+
											'</td>'+
											'<td>'+record.punch_in_note+'</td>'+
											'<td>'+record.punch_in_address+'</td>'+
											'<td>'+record.punch_out_date_time+'</td>'+
											'<td>'+record.punch_out_note+'</td>'+
											'<td>'+record.punch_out_address+'</td>'+
											'<td>'+record.cal_hours+'</td>'+
											'<td>'+record.total_hours+'</td>'+
										'</tr>';
                  });
                  html = html + '</tbody>'+
                  '</table>';
                }
                else{
                  html = html + '<span> No records found! </span>';
                }
            }
            $('#attendance_date').css({'border-color': '#ccc'});
            $("#attendance_list").html(html);
        });
      }
      else{
        $('#attendance_date').css({'border-color': 'red'});
      }
    });

  $(document).on('change','#department_name,#editdepartment_name',function(){
     var department = $(this).val();
     alert(department);
     if(department!=''){
         $.post('<?=base_url()?>employees/designations',{department:department},function(data){
            if(data){
                var full_records = JSON.parse(data);
                var html = '';
                if(full_records.length > 0){
                        html += '<option value="" selected disabled>Position</option>';
                    $( full_records ).each(function( key,val ) {
                        
                        html += '<option value="'+val.id+'">'+val.designation+'</option>';
                    });
                }
                $('#designations,#editdesignations').html(html);
            }
        });
        $.post(base_url+'employees/teamlead_options/',{dept_id:department},function(res){
            // console.log(res); return false;
            var leads_name = JSON.parse(res);
            $('#reporting_to').empty();
            $('#reporting_to').append("<option value='' selected disabled='disabled'>Reporter's Name</option>");
            for(i=0; i<leads_name.length; i++) {
                $('#reporting_to').append("<option value="+leads_name[i].id+">"+leads_name[i].username+"</option>");                      
             }
            });
     }else{
        $('#designations').html('<option value="">No results</option>');
     }
     
  })



  $(document).on('change','#company_name',function(){
     var company = $(this).val();
    
     if(company!=''){
         $.post('<?=base_url()?>reports/employees',{company:company},function(data){
            if(data){
                var full_records = JSON.parse(data);
                var html = '';
                if(full_records.length > 0){
                        html += '<option value="" selected disabled>Employee</option>';
                    $( full_records ).each(function( key,val ) {
                        
                        html += '<option value="'+val.user_id+'">'+val.fullname+'</option>';
                    });
                }
                $('#employee_name').html(html);  
            }
        });
     }else{
        $('#employee_name').html('<option value="">No results</option>');
     }
     
  })




  $(document).on('change','#designations',function(){
  // $('#designations').change(function(e){
        var dept_id = $('#department_name').val();
        var des_id = $(this).val();
        // $.post(base_url+'employees/teamlead_options/',{des_id:des_id,dept_id:dept_id},function(res){
        //     // console.log(res); return false;
        //     var leads_name = JSON.parse(res);
        //     $('#reporting_to').empty();
        //     $('#reporting_to').append("<option value='' selected disabled='disabled'>Reporter's Name</option>");
        //     for(i=0; i<leads_name.length; i++) {
        //         $('#reporting_to').append("<option value="+leads_name[i].id+">"+leads_name[i].username+"</option>");                      
        //      }
        //     });
      });
  var uri_page = "<?php echo $this->uri->segment(1); ?>";
  var uri_page1 = "<?php echo $this->uri->segment(2); ?>";
  var uri_page2 = "<?php echo $this->uri->segment(3); ?>";
  var base_url = "<?php echo base_url(); ?>";

</script>
 <?php

    if($this->uri->segment(2) == 'account'){ ?>

        <script type="text/javascript">
            var tableusers;

        $(document).ready(function() {

            tableusers = $('#table-users').DataTable({
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.

            "order": [], //Initial no order.
            "ajax": {
                "url": "<?php echo site_url('users/account/account_list')?>",
                "type": "POST",
                "data": function ( data ) {
                    data.username = $('#username').val();
                    data.company = $('#company').val();
                    data.user_role = $('#user_role').val();
                }
        },
        "columnDefs": [
        {
            "targets": [ 3,4,5 ], //first column / numbering column [0,1]
            "orderable": false, //set not orderable
        },
        ],

        });
        
        $('#table-users_filter').hide();

        $('#users_search_btn').click(function(){
            tableusers.ajax.reload();
        });
        

    });

   </script>
 <?php } ?>

<!-- <script src="<?=base_url()?>assets/js/jquery-ui.min.js"></script> -->
<script src="<?=base_url()?>assets/js/hrms_common.js"></script>
<script src="<?=base_url()?>assets/js/jquery.minicolors.min.js"></script>

<!-- Accounts -->
<?php if ($this->uri->segment(1) == 'budget_statement') { ?> 
<script src="<?=base_url()?>assets/js/budget_statement.js"> </script>
<?php }  ?>
<?php if ($this->uri->segment(1) == 'balance_sheet') { ?>
<script src="<?=base_url()?>assets/js/balance_sheet.js"> </script>
<?php }  ?>
<?php if (isset($country_code)) { ?>
<script src="<?=base_url()?>assets/plugins/country_code/build/js/intlTelInput-jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

    $(".telephone").intlTelInput({
    nationalMode: false

});
});
</script>
<?php } ?>
<script type="text/javascript">
/* Home page */


$(document).ready(function() {
    
    var doc_width = $(document).width();
    var loc = window.location.href;
    var currentLoc = loc.split('/');

    console.log(currentLoc);
    fontReduce();

    function fontReduce(){

        if((doc_width > 1199 && currentLoc[3] == "newhrms" && currentLoc[4] == "projects") || (doc_width > 929 && currentLoc[4] == "projects"))
        {
        /* equal column height for project dashboard */

            // var card_heights = $(".card-projects").map(function() {
            //     return $(this).height();
            // }).get(),
            // card_maxHeight = Math.max.apply(null, card_heights);
            // //$(".card-projects").height(card_maxHeight);
            // $(".card-activities").height(card_maxHeight);

        }

        if((doc_width > 1199 && currentLoc[3] == "newhrms" && currentLoc[4] == "") || (doc_width > 929 && currentLoc[4] == ""))
        {

            /* equal column height for welcome */
            // console.log(doc_width);

            var pay_heights = $(".panel-payments").map(function() {
                return $(this).height();
            }).get(),
            pay_maxHeight = Math.max.apply(null, pay_heights);
            $(".panel-invoices").height(pay_maxHeight);
            $(".panel-payments").height(pay_maxHeight);


            var pro_heights = $(".panel-projects").map(function() {
                return $(this).height();
            }).get(),
            pro_maxHeight = Math.max.apply(null, pro_heights);
            $(".panel-revenue").height(pro_maxHeight);

            var task_heights = $(".panel-tickets").map(function() {
                return $(this).height();
            }).get(),
            task_maxHeight = Math.max.apply(null, task_heights);
            $(".panel-tasks").height(task_maxHeight);
            $(".panel-activities").height(task_maxHeight);

                       
            /* adjusting font size in top 4 grids  */

            console.log("working");
            console.log($('.card-outstanding').height());
            var card_outstanding = $('.card-outstanding').height();
            var card_expenses = $('.card-expenses').height();
            var card_lastmonth = $('.card-lastmonth').height();
            var card_thismonth = $('.card-thismonth').height();

            if(card_outstanding > 60 || card_expenses > 60 || card_lastmonth > 60 || card_thismonth > 60)
            {
                var h3_con = $('.dash-widget-info > h3').html();
                var len = h3_con.split('').length;
                console.log(h3_con);
                console.log(len);
                $('.dash-widget-info > span').css('font-size','15px');
                switch (len) { 
                    case 7: 
                        $('.dash-widget-info > h3').css('font-size','22px');
                        break;
                    case 8: 
                        $('.dash-widget-info > h3').css('font-size','21px');
                        break;
                    case 9: 
                        $('.dash-widget-info > h3').css('font-size','20px');
                        break;		
                    case 10: 
                        $('.dash-widget-info > h3').css('font-size','19px');
                        break;
                    case 11: 
                        $('.dash-widget-info > h3').css('font-size','18px');
                        break;
                    case 12: 
                        $('.dash-widget-info > h3').css('font-size','17px');
                        break;
                    case 13: 
                        $('.dash-widget-info > h3').css('font-size','16px');
                        break;		
                    case 14: 
                        $('.dash-widget-info > h3').css('font-size','15px');
                        break;
                    default:
                        $('.dash-widget-info > h3').css('font-size','28px');
                }
            }

        }

    }

    $(window).resize(function(){
        console.log('changes');
        fontReduce();
    });

    $('#ser_leave_date_from').datepicker({
    //autoclose: true
    }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#ser_leave_date_to').datepicker('setStartDate', minDate);
        if($('#ser_leave_date_from').val() > $('#ser_leave_date_to').val())
        $('#ser_leave_date_to').val('');
    });

    $('#ser_leave_date_to').datepicker({
    //autoclose: true
    }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    });

    $('#invoice_date_from').datepicker({
    //autoclose: true
    }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#invoice_date_to').datepicker('setStartDate', minDate);
        if($('#invoice_date_from').val() > $('#invoice_date_to').val())
        $('#invoice_date_to').val('');
    });

    $('#invoice_date_to').datepicker({
    //autoclose: true
    }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    });

    $('#estimates_from').datepicker({
    //autoclose: true
    }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#estimates_to').datepicker('setStartDate', minDate);
        if($('#estimates_from').val() > $('#estimates_to').val())
        $('#estimates_to').val('');
    });

    $('#estimates_to').datepicker({
    //autoclose: true
    }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    });

    $('#expenses_date_from').datepicker({
    //autoclose: true
    }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#expenses_date_to').datepicker('setStartDate', minDate);
        if($('#expenses_date_from').val() > $('#expenses_date_to').val())
        $('#expenses_date_to').val('');
    });

    $('#expenses_date_to').datepicker({
    //autoclose: true
    }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    });

    $('#ticket_from').datepicker({
    //autoclose: true
    }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#ticket_to').datepicker('setStartDate', minDate);
        if($('#ticket_from').val() > $('#ticket_to').val())
        $('#ticket_to').val('');
    });

    $('#ticket_to').datepicker({
    //autoclose: true
    }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    });

    $('#ser_activity_date_from').datepicker({
    //autoclose: true
    }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#ser_activity_date_to').datepicker('setStartDate', minDate);
        if($('#ser_activity_date_from').val() > $('#ser_activity_date_to').val())
        $('#ser_activity_date_to').val('');
    });

    $('#ser_activity_date_to').datepicker({
    //autoclose: true
    }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    });

    $('#req_leave_date_from').datepicker({
    //autoclose: true
    }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    }).on('changeDate', function (selected) {
        // var minDate = new Date(selected.date.valueOf());
        // $('#req_leave_date_to').datepicker('setStartDate', minDate);
        if($('#req_leave_date_from').val() > $('#req_leave_date_to').val())
        $('#req_leave_date_to').val('');
    });

    $('#req_leave_date_to').datepicker({
    //autoclose: true
    }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    });

    $('#timesheet_date_from').datepicker({
    //autoclose: true
    }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#timesheet_date_to').datepicker('setStartDate', minDate);
        if($('#timesheet_date_from').val() > $('#timesheet_date_to').val())
        $('#timesheet_date_to').val('');
    });

    $('#timesheet_date_to').datepicker({
    //autoclose: true
    }).on('hide', function(e) {
        console.log($(this).val());
        $(this).val($(this).val());
        if($(this).val() != '')
        {
        $(this).parent().parent().addClass('focused');
        }
        else
        {
        $(this).parent().parent().removeClass('focused');
        }
    });

	$('#invoice_color').minicolors({
          control: $(this).attr('data-control') || 'hue',
          defaultValue: $(this).attr('data-defaultValue') || '',
          format: $(this).attr('data-format') || 'hex',
          keywords: $(this).attr('data-keywords') || '',
          inline: $(this).attr('data-inline') === 'true',
          letterCase: $(this).attr('data-letterCase') || 'lowercase',
          opacity: $(this).attr('data-opacity'),
          position: $(this).attr('data-position') || 'bottom left',
          swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
          change: function(value, opacity) {
            if( !value ) return;
            if( opacity ) value += ', ' + opacity;
            if( typeof console === 'object' ) {
              console.log(value);
            }
          },
          theme: 'bootstrap'
        });

        $('#estimate_color').minicolors({
          control: $(this).attr('data-control') || 'hue',
          defaultValue: $(this).attr('data-defaultValue') || '',
          format: $(this).attr('data-format') || 'hex',
          keywords: $(this).attr('data-keywords') || '',
          inline: $(this).attr('data-inline') === 'true',
          letterCase: $(this).attr('data-letterCase') || 'lowercase',
          opacity: $(this).attr('data-opacity'),
          position: $(this).attr('data-position') || 'bottom left',
          swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
          change: function(value, opacity) {
            if( !value ) return;
            if( opacity ) value += ', ' + opacity;
            if( typeof console === 'object' ) {
              console.log(value);
            }
          },
          theme: 'bootstrap'
        });

    

});

</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.GroupChatList').on("click",function(){
                $('.video-blk-right').removeClass('chat-call-placeholder');
                var propic = 'images/user.jpg';
                var base_url = $(this).data('baseurl');
                var group_id = $(this).data('grpid');
                var group_name = $(this).data('grpname');
                $('#hid_group_id').val(group_id);
                $('#chatgroup_name').text(group_name);
                $('#chatMenuuser').text(group_name);
                $('#MsgviewUserChat').attr('src',base_url+propic);
                $('.grp-profile-details').css('display','');
                $('.single-profile-details').css('display','none');
                $('#user_detailss').css('display','');
                $.post(base_url+'chats/group_chat_userlist',{group_id:group_id},function(res){
                    // console.log(res);
                $.post(base_url+'chats/group_user_grouplist',{group_id:group_id},function(data){
                    $('#chatGroup_members').html(''); 
                    $('#chatGroup_members').append(data); 
                    $('.chat-user-lists').append(data); 
                    // console.log(data); return false;
                });
                $.post(base_url+'chats/grouplist_receiver',{group_id:group_id},function(result){
                    // console.log(result);
                    $('#receiver_ids').val(result);
                });
                        $('.ChatHistory').html('');
                        $('.ChatHistory').append(res);
                    });
            });


        $(document).on("click",'.SingleChatList',function(){
            $('.video-blk-right').removeClass('chat-call-placeholder');
            var user_id = $(this).data('userid');
            var full_name = $(this).data('name');
            var username = $(this).data('username');
            var email = $(this).data('email');
            var phone = $(this).data('phone');
            var status = $(this).data('online');
            var propic = $(this).data('propic');
            var base_url = $(this).data('baseurl');
            var lastlogin = $(this).data('lastlogin');
            var destination = $(this).data('destination');

            $('#side_profile_name').text(full_name);
            $('#outgoing_username').text(full_name);
            $('#side_user_name').text(username);
            $('#side_user_phone').text(phone);
            $('#side_user_email').text(email);
            $('#call_user_name').text(full_name);
            $('#user_detailss').css('display','none');
            if(destination != '')
            {
                var des = destination; 
            }else{
                var des = '-'; 
            }
            $('#side_profile_destination').text(des);
            $('#side_profileimg').attr('src',base_url+'assets/avatar/'+propic);
            $('#outgoing_call_image').attr('src',base_url+'assets/avatar/'+propic);
            $('#call_user_pic').attr('src',base_url+'assets/avatar/'+propic);


            $('#fullname_chat').text(full_name);
            $('#chatMenuuser').text(full_name);
            $('#user_img_chat').attr('src',base_url+'assets/avatar/'+propic);
            $('#MsgviewUserChat').attr('src',base_url+'assets/avatar/'+propic);
            $('#user_img_chat').attr('alt',full_name);
            if(status == 'online')
            {
                    $('#user_status_chat').removeClass('offline');
                    $('#MsgviewStatus').removeClass('offline');
                    $('#user_status_chat').addClass(status);
                    $('#MsgviewStatus').addClass(status);
            }else{
                    $('#user_status_chat').removeClass('online');
                    $('#MsgviewStatus').removeClass('online');
                    $('#user_status_chat').addClass(status);
                    $('#MsgviewStatus').addClass(status);
            }
            $('#chat_username').text(username);
            $('#user_email_chat').text(email);
            $('#user_phone_chat').text(phone);
            $('#LastseenUserChat').text('Last seen '+lastlogin);
            // alert(lastlogin);
            // var d = Date('yy-mm-dd',lastlogin);
            // d.setDate(d.getDate() - 1);
            $.post(base_url+'chats/chat_single_history',{user_id:user_id},function(res){
                $('.grp-profile-details').css('display','none');
                $('.single-profile-details').css('display','');
                // console.log(res);
                $('.ChatHistory').html('');
                $('.ChatHistory').append(res);
            });

            // var to_id = $(this).data('opid');
            // var to_name = $(this).data('uname');
            $('.chatHeader').css('display','');
            $.post(base_url+'chats/online_offline',{to_id:user_id},function(res){
                if(res == 'offline')
                {
                  $('#offlineStatus'+user_id).css('display','');
                  $('#onlineStatus'+user_id).css('display','none');
                }else{
                  $('#onlineStatus'+user_id).css('display','');
                  $('#offlineStatus'+user_id).css('display','none');
                }
            })
            // $('#chatter_name').text('');
            $('#chatter_name').text(full_name);
            $('#to_id').val(user_id);



        });

        $(document).on("click",'.DeleteChatMsgg',function(){
            var res = getConfirmation();
            if(res == true)
            {
                var msg_id = $(this).data('msgid');
                var user_id = $(this).data('userid');
                $.post(base_url+'chats/chat_single_delete',{msg_id:msg_id},function(res){
                    toastr.success('Message Deleted');
                    $.post(base_url+'chats/chat_single_history',{user_id:user_id},function(res){
                        $('.ChatHistory').html('');
                        $('.ChatHistory').append(res);
                    });
                });
            }else{
                return false;
            }
        });

        $(document).on("click",'.DeleteChatMsgggroup',function(){
            var res = getConfirmation();
            if(res == true)
            {
                var msg_id = $(this).data('msgid');
                var group_id = $(this).data('grpid');
                $.post(base_url+'chats/chat_single_delete',{msg_id:msg_id},function(res){
                    toastr.success('Message Deleted');
                    $.post(base_url+'chats/group_chat_userlist',{group_id:group_id},function(res){
                        $('.ChatHistory').html('');
                        $('.ChatHistory').append(res);
                    });
                });
            }else{
                return false;
            }
        });

        function getConfirmation() {
               var retVal = confirm("Are you sure to delete?");
               if( retVal == true ) {
                  return true;
               } else {
                  return false;
               }
            }
        
    });
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.clone_invoice_btn').click(function(){
        var invoice_id = $(this).data('invoice');
        $.post(base_url+'invoices/invoice_cloning/',{invoice_id:invoice_id},function(res){
            setInterval(function(){ toastr.success('Invoice Copied'); 
            window.location.href = base_url+'invoices/view/'+res ;
        }, 1500);


        });
        return false;
      });
    });
</script>

<?php if($this->uri->segment(1) == 'organisation'){ ?>
<script>
    $('#department').change(function(){
	  	// alert($(this).val());
	  	var dept_id = $(this).val();
	  	$.post(base_url+'auth/designation_option/',{dept_id:dept_id},function(res){
            var leads_name = JSON.parse(res);
            //console.log(leads_name); return false;
			$('#position').empty();
		    $('#position').append("<option value='' selected disabled='disabled'>Destination</option>");
			for(i=0; i<leads_name.length; i++) {
		    	$('#position').append("<option value="+leads_name[i].id+" dp-name='Position'>"+leads_name[i].designation+"</option>");                      
		     }
		  	});
	  });
</script>
<?php } ?>



<?php if($this->uri->segment(1) == 'settings'){ ?>
<script>
    $('#department_id').change(function(){
	  	// alert($(this).val());
	  	var dept_id = $(this).val();
	  	$.post(base_url+'auth/designation_option/',{dept_id:dept_id},function(res){
            var leads_name = JSON.parse(res);
            var nn =[];
            //console.log(leads_name); return false;
			for(i=0; i<leads_name.length; i++) {
		  //  	$('.AllDes').html('<label class="label label-danger"><a href="" data-toggle="ajaxModal" title = "">'+leads_name[i].designation+'</a></label>'); 
		    	var nnn = '<label class="label label-danger" style="margin:3px;">'+leads_name[i].designation+'</label>';
		    	nn.push(nnn);
		     }
		     $('.AllDes').html(nn);
		     
		  	});
	  });
</script>
<?php } ?>


<?php if ($this->session->flashdata('tokbox_success')  != ''){ ?>
<script type="text/javascript">
    toastr.success("<?php echo $this->session->flashdata('tokbox_success'); ?>");
</script>

<?php } ?>

<?php if ($this->session->flashdata('tokbox_error')  != ''){ ?>
<script type="text/javascript">
    toastr.error("<?php echo $this->session->flashdata('tokbox_error'); ?>");
</script>

<?php } ?>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/task.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        // $('#OpenImgUpload').click(function(){ $('#file_upload').trigger('click'); });
        $('.open_upso').click(function(){
            $('#file_upload').trigger('click'); 
            });
        $('.open_upso_load').click(function(){
            var zid = $(this).data('zid');
            $('#file_upload_'+zid).trigger('click'); 
            });
        $('#OpenImgUpload').click(function()
        { 
            $('#file_upload').trigger('click'); 
        });
        $('#file_upload').change(function(){ $('#post_comments').submit(); });


        $('#post_comments').submit(function(){

            var fileupload=$('#fileupload').val();
            var comments=$('#comments').val();

            if(fileupload=='' && comments=='')
            {
                return false;
            }
            else
            {
                $('#post_comments').submit();
            }

        });
    $('#file_upload').on('change',function(e){
               e.preventDefault();
                var file_data = $('#file_upload').prop('files')[0];   
                var project = $('#project_upl').val();   
                var task = $('#task_k').val();   
                var form_data = new FormData();      
                     
                form_data.append('projectfiles', file_data);
                 
            
                $.ajax({
                    url: '<?php echo base_url();?>all_tasks/post_task_comments/'+task+'/'+project,
                    dataType: 'json',  // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,                         
                    type: 'POST',
                    success: function(data){
                //      console.log('data'); // display response from the PHP script, if any
                         call_comments(task);
                    }
                 });
            });
            
         $('._file_updata').on('change',function(e){
             
               e.preventDefault();
               var data_id =$(this).data('fid');
                var file_data = $('#file_upload_'+data_id).prop('files')[0];   
                var project = $('#project_upl_'+data_id).val();   
                var task = $('#task_k_'+data_id).val();   
                var form_data = new FormData();      
                     
                form_data.append('projectfiles', file_data);
                 
            
                $.ajax({
                    url: '<?php echo base_url();?>all_tasks/post_task_comments/'+task+'/'+project,
                    dataType: 'json',  // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,                         
                    type: 'POST',
                    success: function(data){
                //      console.log('data'); // display response from the PHP script, if any
                         call_comments(task);
                    }
                 });
            });
            
            $('._comment_upload').on('click',function(){
                
                
                var data_id =$(this).data('taskid');
                
                    var comment_urls='<?php echo base_url();?>all_tasks/post_task_comments';   
                    
                    var fileupload=$('#fileupload').val();
                    
                        var project = $('#project_upl_'+data_id).val();   
                        var task = $('#task_k_'+data_id).val();
                        var description = $('#comments_'+data_id).val();
                           
                    var comments={'task':task,'project':project,'description':description};          
                     
                      if(description !='' ){
                      $.ajax({
                            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                            url         : comment_urls, // the url where we want to POST
                            data        : comments, // our data object
                            dataType    : 'json', // what type of data do we expect back from the server
                            encode          : true
                        })
                    // using the done promise callback
                    .done(function(data) {
                        $('#comments_'+task).val('');
                         call_comments(task);
                        });
                    }
                        
                });
          $('#_comment_upload').on('click', function() {
            
            var comment_urls='<?php echo base_url();?>all_tasks/post_task_comments';   
            
            var fileupload=$('#fileupload').val();
            
                var project = $('#project_upl').val();   
                var task = $('#task_k').val();
                var description = $('#comments').val();
                   
            var comments={'task':task,'project':project,'description':description}; 
            if(description !='' ){       
              $.ajax({
                    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url         : comment_urls, // the url where we want to POST
                    data        : comments, // our data object
                    dataType    : 'json', // what type of data do we expect back from the server
                    encode          : true
                })
            // using the done promise callback
            .done(function(data) {
                 
                 call_comments(task);
                
                });
                
                }
        });
        
    });
    
    function call_comments(data)
    {
        
        var comment_urls='<?php echo base_url();?>all_tasks/ajax_comments_update';   
        $.ajax({
                type : 'POST',
                url : comment_urls,
                data        : {'id':data}, // our data object
                dataType    : 'json', // what type of data do we expect back from the server
                encode          : true
            }) .done(function(data) {
                  
var content_er='';
                   $.each(data, function(i, item) {
 
var pro_pic_coms='';
                    if(item.avatar== '' )
                 {
                        pro_pic_coms = '<?php echo base_url();?>assets/avatar/default_avatar.jpg';
                   }else{
                            pro_pic_coms = '<?php echo base_url();?>assets/avatar/'+item.avatar;
                        }
 
                        if(item.activites)
                        {
                    content_er =        '<div class="task-information">'+
                            '<span class="task-info-line"><a class="task-user" href="#">'+item.fullname+'</a> <span class="task-info-subject">'+item.activites+'</span></span>'+'<div class="task-time">'+item.date_posted+'</div></div>';                          
                        }
                        else
                        {
                            content_er ='<div class="chat chat-left">'
                                        +'<div class="chat-avatar">'
                                        +'<a  title="'+item.fullname+'" data-placement="right" data-toggle="tooltip" class="avatar">'
                                        +'<img alt="'+item.fullname+'" src="'+pro_pic_coms+'" class="img-responsive img-circle"></a></div>'
                                        +'<div class="chat-body">'
                                        +'<div class="chat-bubble">'
                                        +'<div class="chat-content">'
                                        +'<span class="task-chat-user">'+' '+item.fullname+' '+'</span>';
                            
                            if(item.file_name){
                                
                            content_er = content_er + '<span class="file-attached">'
                            +'attached file <i class="fa fa-paperclip">'
                            +'</i></span> <span class="chat-time">'
                            +' few seconds ago '
                            +'</span>'
                            +'<ul class="attach-list">';
                                
                            
                            
                            if(item.file_ext== '.png'|| item.file_ext== '.jpg'|| item.file_ext== '.jpeg'|| item.file_ext== '.PNG'|| item.file_ext== '.JPG'|| item.file_ext== '.JPEG')
                                {
                                
                                content_er = content_er+'<li class="img-file">'+
                                    '<div class="attach-img-download"><a href="<?=base_url()?>all_tasks/download/'
                                    +item.path+'/'+item.file_name+'">'+item.file_name+'</a></div>'
                                    +'<div class="task-attach-img"><img src="<?=base_url()?>assets/project-files/'+item.path+'/'+item.file_name+'" alt=""></div></li>';
                                                        
                                }
                                else if (item.ext=='.pdf')
                                {
                                    content_er = content_er + '<ul class="attach-list">'
                                    +'<li class="pdf-file">'
                                    +'<i class="fa fa-file-pdf-o"></i> '
                                    +'<a href="<?=base_url()?>all_tasks/download/'+item.path+'/'+item.file_name+'">'+item.file_name+'</a></li></ul>';
                                }
                                else
                                {
                                    content_er = content_er + '<ul class="attach-list">'
                                    +'<li><i class="fa fa-file">'
                                    +'</i> <a href="<?=base_url()?>all_tasks/download/'+item.path+'/'+item.file_name+'">'+item.file_name+'</a></li></ul>';
                                }
                            }
                            if (item.message)
                            {
                                
                                //~ content_er = content_er + '<span class="chat-time">'+' '+item.date_posted 
                                content_er = content_er + '<span class="chat-time">'+' few seconds ago </span>'
                                +'<p>'+ item.message +'</p>';
                            }
                            content_er = content_er + '</div></div></div></div>';   
                        
                        }
                    
                   });
                   
                   
                   $('.chats').append(content_er);
                   $('#comments').val('');
                   
                  
                });
    }
    function date_calls(value){
    var vvv=  '';console.log(value);
        console.log("<?= config_item('show_time_ago')=='TRUE'?(strtolower(humanFormat(strtotime('"+value+"')).' '.lang(ago))):'no'; ?>");   
    }

</script>

<script type="text/javascript">
    $(document).ready(function(){


        // Shows panel for entering new tasks
        $('.add-task-btn').click(function(){
            var newTaskWrapperOffset = $('.new-task-wrapper').offset().top;
            $(this).toggleClass('visible');
            $('.new-task-wrapper').toggleClass('visible');
             $('.new-task-wrapper').show();
            // Focus on the text area for typing in new task
            $('#new_task').focus();
            // Smoothly scroll to the text area to bring the text are in view
            $('body').animate({ scrollTop: newTaskWrapperOffset}, 1000);
        });

        // Shows panel for entering new tasks

        $('#new_task').blur(function(){
            var task=$(this).val();
            var project_id=$('#project_id').val();
            if(task!='')
            {

                var formData = {
            'task_name'         : task,
            'project'   :project_id,
            };
            $.ajax({
                    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url         : '<?=base_url()?>all_tasks/add_tasks', // the url where we want to POST
                    data        : formData, // our data object
                    dataType    : 'json', // what type of data do we expect back from the server
                    encode          : true
                })
            // using the done promise callback
            .done(function(data) {

                 if ( ! data.success) {
                        alert('There was a problem with AJAX');
                    }else{

                        var html='<li class="task">'
                                    +'<div class="task-container">'
                                        +'<span class="task-action-btn task-check">'
                                            +'<span class="action-circle large complete-btn task_completes"  data-id="'+data.task_id+'" title="Mark Complete">'
                                                +'<i class="material-icons">check</i>'
                                            +'</span>'
                                        +'</span>'
                                        +'<a href="<?php echo base_url();?>all_tasks/task_view/'+project_id+'/'+data.task_id+'"><span class="task-label" contenteditable="true">'+task+' </span></a>'
                                    +'</div>'
                                +'</li>';
                        
                        $('#task-list').append(html);
                        $('#new_task').val('');
                        $('.new-task-wrapper').hide();
                    }

                // here we will handle errors and validation messages
            });

            }

        });


        $('#new_task').keypress(function(e) {
             if (e.keyCode == 13 && !e.shiftKey) {
              e.preventDefault();
            var task=$(this).val();
            var project_id=$('#project_id').val();
            if(task!='')
            {

                var formData = {
            'task_name'         : task,
            'project'   :project_id,
            };
            $.ajax({
                    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url         : '<?=base_url()?>all_tasks/add_tasks', // the url where we want to POST
                    data        : formData, // our data object
                    dataType    : 'json', // what type of data do we expect back from the server
                    encode          : true
                })
            // using the done promise callback
            .done(function(data) {

                 if ( ! data.success) {
                        alert('There was a problem with AJAX');
                    }else{

                        var html='<li class="task">'
                                    +'<div class="task-container">'
                                        +'<span class="task-action-btn task-check">'
                                            +'<span class="action-circle large complete-btn task_completes"  data-id="'+data.task_id+'" title="Mark Complete">'
                                                +'<i class="material-icons">check</i>'
                                            +'</span>'
                                        +'</span>'
                                        +'<a href="<?php echo base_url();?>all_tasks/task_view/'+project_id+'/'+data.task_id+'"><span class="task-label" contenteditable="true">'+task+' </span></a>'
                                    +'</div>'
                                +'</li>';
                        
                        $('#task-list').append(html);
                        $('#new_task').val('');
                        $('.new-task-wrapper').hide();
                    }

                // here we will handle errors and validation messages
            });

            }
        }

        });



        $('#project_assign_tasks').click(function(){
            alert();
            
        });
 


        $('.task_completes').click(function() {

    var task_id = $(this).data().id;
    var task_complete = 'true';

    var formData = {
            'task_id'         : task_id,
            'task_complete'   : task_complete
        };
    $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : '<?=base_url()?>projects/tasks/progress', // the url where we want to POST
            data        : formData, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
            encode          : true
        })
            // using the done promise callback
            .done(function(data) {

                 if ( ! data.success) {
                        alert('There was a problem with AJAX');
                    }else{
                        location.reload();
                    }

                // here we will handle errors and validation messages
            });

  });


         $('.task_uncompletes').click(function() {

    var task_id = $(this).data().id;
    var task_complete = '';

    var formData = {
            'task_id'         : task_id,
            'task_complete'   : task_complete
        };
    $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : '<?=base_url()?>projects/tasks/progress', // the url where we want to POST
            data        : formData, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
            encode          : true
        })
            // using the done promise callback
            .done(function(data) {

                 if ( ! data.success) {
                        alert('There was a problem with AJAX');
                    }else{
                        location.reload();
                    }

                // here we will handle errors and validation messages
            });

  });

    });


function assign_form_submit()
{
            // var assigned_to=$('#assigned_to').val();
            var assigned_to=[];

            $.each($(".AssigneDTo option:selected"), function(){            
                assigned_to.push($(this).val());
            });
            var project=$('#project').val();
            var task=$('#task').val();
            var type=$('#type').val();
            var due_date=$('#add_task_date_due').val();

            console.log(assigned_to);

            if(assigned_to==null &&  due_date==null)
            {
                return false;
            }
            else
            {
           

                var formData = {
            'assigned_to'         : assigned_to,
            'project'   :project,
            'task'         : task,
            'type'   :type,
            'due_date'         : due_date,
           
            };
            $.ajax({
                    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url         : '<?=base_url()?>all_tasks/assign_user', // the url where we want to POST
                    data        : formData, // our data object
                    dataType    : 'json', // what type of data do we expect back from the server
                    encode          : true
                })
            // using the done promise callback
            .done(function(data) {

                 if ( ! data.success) {
                        alert('There was a problem with AJAX');
                    }else{


                        if(type=='Assign')
                        {


                             $.ajax({
                    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url         : '<?=base_url()?>all_tasks/team_datajax_load', // the url where we want to POST
                    data        : {'assigned_to':assigned_to}, // our data object
                    dataType    : 'json', // what type of data do we expect back from the server
                    encode          : true
                })
            // using the done promise callback
            .done(function(data) { 
                
           var start_html='';
           var default_img = '<?=base_url()?>/assets/avatar/default_avatar.jpg';
           var heading_ing =' <div class="pro-teams"><div class="pro-team-members">'+'<div class="task-head-title">Assign to</div><div class="avatar-group">';              
                  var contents='';
                  var new_html='';
                  var hidden_user=0;
                        $.each(data, function(i, item) {
    
                    var prof_img='';
                if(item.avatar)
                {
                    prof_img = '<?=base_url() ?>assets/avatar/'+item.avatar;
                }
                else
                {
                    prof_img= default_img;
                }
                if(i<5) //to show only 5 users list to avoid users icon overflow
                {
                    contents += '<div class="avatar">'
                 +'<img class="avatar-imgrounded-circle border border-white" alt="UserImage" title="'+item.fullname+'"'+
                 'src = "'+prof_img+'"></div> ';
                }console.log(i);
                
            /*  if(i>4 && $('#assign_tasks'+task).hasClass('remove_hidden_user'))
                {
                    contents += '<div class="avatar">'
                 +'<img class="avatar-imgrounded-circle border border-white" alt="UserImage" title="'+item.fullname+'"'+
                 'src = "'+prof_img+'"></div> ';
                }*/
                
                hidden_user=i;
                });//console.log(hidden_user);
            new_html = contents+' <div class="avatar"><a class="avatar-title rounded-circle border border-white"  data-toggle="ajaxModal" href="<?=base_url()?>all_tasks/assign_user/'+project+'/'+task+'/Assign"><i class="fa fa-plus"></i></a></div>';
            hidden_user = hidden_user-4; // i in each loop has starts with 0. so here 4 is used instead of 5
            var end_html='</div></div>  </div></div>';
            if(hidden_user>0)// &&  $('#assign_tasks'+task).hasClass('remove_hidden_user')==false)
            {
                 start_html= '<div class="avatar"><span title="Additional users" class="badge bg-purple">+'+hidden_user+'</span></div>';
            }
            
            $('.assignee-info').html(heading_ing+ start_html+ new_html+end_html);
            $('#assign_tasks'+task).html(heading_ing+ start_html+new_html+end_html);
            //~ $('.task-head-title').html('');
            
});
                            // var html='<div class="avatar">'
                            //         +'<img src="'+data.profile_img+'" alt="">'
                            //      +'</div>'
                            //     +'<div class="assigned-info">'
                            //         +'<div class="task-head-title">Assigned To</div>'
                            //         +'<div class="task-assignee">'+data.profiler_name+'</div>'
                            //     +'</div>'
                            //     +'<span onclick="delete_assigne('+project+','+task+')" class="remove-icon">'
                            //         +'<i class="fa fa-close"></i>'
                            //     +'</span>';

                            // $('.assignee-info').html(html);
                            // var htmls='<div class="task-information"><span class="task-info-line"><a class="task-user" href="#">'+data.activity_username+'</a> <span class="task-info-subject">'+data.activity+'</span></span><div class="task-time">'+data.activity_date+'</div></div>'
                            // $('.chats').append(htmls);

                            // var htmlss='<span class="action-circle large" title="'+data.profiler_name+'">'
                            //             +'<div class="avatar">'
                            //             +'<img src="'+data.profile_img+'" alt="">'
                            //            +'</div>'
                            //            +'</span>'

                            //           $('#assign_tasks'+task).html(htmlss);

                            $('#assign_modl').modal('hide');
                        }

                        if(type=='Due')
                        {
                            var html='<div class="due-icon">'
                                        +'<span>'
                                                +'<i class="material-icons">date_range</i>'
                                        +'</span>'
                                        +'</div>'
                                        +'<div class="due-info">'
                                            +'<div class="task-head-title">Due Date </div>'
                                            +'<div class="due-date">'+data.date+'</div>'
                                        +'</div>'
                                        +'<span onclick="delete_due_date('+project+','+task+')" class="remove-icon">'
                                            +'<i class="fa fa-close"></i>'
                                        +'</span>';
                        
                            $('.task-due-date').html(html);
                            var htmls='<div class="task-information"><span class="task-info-line"><a class="task-user" href="#">'+data.activity_username+'</a> <span class="task-info-subject">'+data.activity+'</span></span><div class="task-time">'+data.activity_date+'</div></div>'
                            $('.chats').append(htmls);  
                            $('#assign_modl').modal('hide');
                        }

                        
                        
                    }

                // here we will handle errors and validation messages
            });

        }
           

}

function delete_assigne(project_id,task_id)
{
    $.post('<?php echo base_url();?>all_tasks/delete_assigne',{'task_id':task_id},function(data){

                    var obj = jQuery.parseJSON(data);

                         if (! obj.success) {
                        alert('There was a problem with AJAX');
                    }else{

                        var html='<a data-toggle="ajaxModal" href="<?php echo base_url(); ?>all_tasks/assign_user/'+project_id+'/'+task_id+'/Assign">'
                                    +'<div class="avatar">'
                                        +'<img src="<?php echo base_url();?>assets/avatar/default_avatar.jpg" alt="">'
                                    +'</div>'
                                    +'<div class="assigned-info">'
                                        +'<div class="task-head-title">Unassigned</div>'
                                        +'<div class="task-assignee"></div>'
                                    +'</div>'
                                   +'</a>';
                        
                            $('.assignee-info').html(html);


                            var htmls='<span class="action-circle large" title="Assign">'
                                        +'<a data-toggle="ajaxModal" href="<?php echo base_url(); ?>all_tasks/assign_user/'+project_id+'/'+task_id+'/Assign">'
                                        +'<div class="avatar">'
                                        +'<img src="<?php echo base_url();?>assets/avatar/default_avatar.jpg" alt="">'
                                       +'</div>'
                                       +'</a>'
                                      +'</span>'

                                      $('#assign_tasks'+task_id).html(htmls);
                            

                    }


    });
}
function delete_due_date(project_id,task_id)
{
    $.post('<?php echo base_url();?>all_tasks/delete_due_date',{'task_id':task_id},function(data){

                    var obj = jQuery.parseJSON(data);

                         if (! obj.success) {
                        alert('There was a problem with AJAX');
                    }else{

                        var html='<a data-toggle="ajaxModal" href="<?php echo base_url(); ?>all_tasks/assign_user/'+project_id+'/'+task_id+'/Due">'
                                    +'<div class="due-icon">'
                                        +'<span>'
                                            +'<i class="material-icons">date_range</i>'
                                        +'</span>'
                                    +'</div>'
                                    +'<div class="due-info">'
                                        +'<div class="task-head-title">Due Date </div>'
                                        +'<div class="due-date"></div>'
                                    +'</div>'
                                   +'</a>';
                        
                            $('.task-due-date').html(html);
                            

                    }


    });
}
function delete_task(task_id)
{
    $('#delete_modal').modal('show');
    $('#delete_task_id').val(task_id);
}

function task_description(task_id)
{
    var description =$('#task_description').val();
    if(description!='')
    {
        $.post('<?php echo base_url();?>all_tasks/description_update',{'description':description,'task_id':task_id},function(data){

        });
    }
}
function competency_definition(id)
{
    var definition =$('#competency_definition_'+id).val();
    if(definition!='')
    {
        $.post('<?php echo base_url();?>settings/competency_definition_update',{'definition':definition,'id':id},function(data){

        });
    }
}
function definition_edit(id)
{
   
    $(".definition_edit_"+id).prop('readonly', false);
}
</script>

<script src="<?=base_url()?>assets/js/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/js/datatables/dataTables.bootstrap.min.js"></script>

<?php if($this->uri->segment(1) == 'policies'){ ?>
  <script type="text/javascript">
    var save_method;
    var table;

    $(document).ready(function() {

//datatables
table = $('#policies_table').DataTable({ 

"processing": true, //Feature control the processing indicator.
'bnDestroy' :true,
"serverSide": false, //Feature control DataTables' server-side processing mode.
"order": [], //Initial no order.
"bFilter": false,
'aoColumnDefs': [{
   'bSortable': false,
   'aTargets': [-1] /* 1st one, start by the right */
}],

// Load data for the table's content from an Ajax source
"ajax": {
  "url": "<?php echo base_url();?>policies/policies_list",
  "type": "POST",
  "data": function ( data ) {
    
  }
},

//Set column definition initialisation properties.
"columnDefs": [
{ 
"targets": [ 0 ], //first column / numbering column
"orderable": false, //set not orderable
},
],

});


});

function add_policies()
{
       save_method = 'add';
        $('#add_policies')[0].reset(); // reset form on modals
        $('#add_policy').modal('show'); // show bootstrap modal
        $("#department").select2();
        $('#department').select2('destroy');
        $("#department").select2();
        $('.modal-title').text('Create Policies'); // Set Title to Bootstrap modal title
        $('.upload_details').hide();


}

function full_view_policy(id)
{
    $('#add_policies')[0].reset(); // reset form on modals

    //Ajax Load data from ajax
    $.ajax({
      url : "<?php echo base_url();?>policies/policies_edit/"+id,
      type: "GET",
      dataType: "JSON",
      success: function(data)
      {
       $('#full_view').text(data.description);
       $('#view_policy').modal('show'); //
       $('.modal-titleview').html(data.policyname);
      }

    });
}
function edit_policies(id)
{
  save_method = 'update';
$('#add_policies')[0].reset(); // reset form on modals

//Ajax Load data from ajax
$.ajax({
  url : "<?php echo base_url();?>policies/policies_edit/"+id,
  type: "GET",
  dataType: "JSON",
  success: function(data)
  {

    $('[name="id"]').val(data.id);
    $('[name="policyname"]').val(data.policyname);
    $('[name="description"]').val(data.description);
    $('[name="policy_files"]').val(data.policy_file);

      $("#department").select2();
      var departments=data.department.split(',');
      $('#department').val(departments);
      $('#department').select2('destroy');
      $("#department").select2();
    
    $.post('<?php echo base_url();?>policies/policies_details',{id:id},function(data){
      $('.upload_details').show();
       $('.upload_details').html(data);

    });
    
    
$('#add_policy').modal('show'); // show bootstrap modal when complete loaded
$('.modal-title').text('Edit Policies'); // Set title to Bootstrap modal title




},
error: function (jqXHR, textStatus, errorThrown)
{
  alert('Error get data from ajax');
}
});
}

function reload_table()
{
table.ajax.reload(null,false); //reload datatable ajax 
}



function delete_policies(id)
{
    $('#delete_policy').modal('show'); // show bootstrap modal when complete loaded
   // Set title to Bootstrap modal title

   $('#delete_policies').attr('onclick','delete_policy('+id+')');
 



}

function delete_policy(id)

{

        $.ajax({
          url : "<?php echo site_url('policies/policies_delete')?>/"+id,
          type: "POST",
          dataType: "JSON",
          success: function(data)
          {
        //if success reload ajax table
        $('#delete_policy').modal('hide');
        reload_table();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          alert('Error deleting data');
        }
        });

}





</script>



<script type="text/javascript">
  

  function reload_table()
  {
table.ajax.reload(null,false); //reload datatable ajax 
}



</script>

<script type="text/javascript">
    $(document).ready(function (e){
        $("#add_policies").on('submit',(function(e){
            e.preventDefault();


            var policyname = $('#policyname').val();
            var description = $('#description').val();
            var department = $('#department').val();
           
          

             if(policyname == '')
            {
                toastr.error('Policy Name Field is Required');
                return false;
            }

            if(description == '')
            {
                toastr.error('Description Field is Required');
                return false;
            }

            if(department == '')
            {
                toastr.error('Department Field is Required');
                return false;
            }

           
           
            if(save_method=='update')
            {
              var urls='<?php echo base_url();?>policies/update_policies';
            }
            else
            {
              var urls='<?php echo base_url();?>policies/add_policies';
            }

            $.ajax({
                url: urls,
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                beforeSend:function() { 

                    $('#btnSave').text('Processing.....');
                    $('#btnSave').attr('disabled',true);
                    

                },
                success: function(data){
                  //alert(data);
                   var obj=jQuery.parseJSON(data);
                    if(obj.result=='yes')
                    {
                          toastr.success(obj.status);
                          $('#add_policy').modal('hide');
                          $('#btnSave').text('Submit');
                          $('#btnSave').attr('disabled',false);
                          $('#add_policies')[0].reset();
                          
                          table.ajax.reload(null,false);
                    }
                    else if(obj.result=='no')
                    {
                       toastr.error(obj.status);
                        $('#btnSave').text('Submit');
                        $('#btnSave').attr('disabled',false);
                    }

                    else if(obj.result=='uplo')
                    {
                        toastr.error(obj.status);
                        $('#btnSave').text('Submit');
                        $('#btnSave').attr('disabled',false);
                    }

                   
                   else
                    {
                        window.location.reload();
                    }
                },

            });
        }));
    });
</script> 

<?php } ?>


<?php if($this->uri->segment(1) == 'resignation'){ ?> 
  <script type="text/javascript">
    var save_method;
    var table;

    $(document).ready(function() {

//datatables
table = $('#resignation_table').DataTable({ 

"processing": true, //Feature control the processing indicator.
'bnDestroy' :true,
"serverSide": true, //Feature control DataTables' server-side processing mode.
"order": [], //Initial no order.
"bFilter": false,

// Load data for the table's content from an Ajax source
"ajax": {
  "url": "<?php echo base_url();?>resignation/resignation_list",
  "type": "POST",
  "data": function ( data ) {
    
  }
},

//Set column definition initialisation properties.
"columnDefs": [
{ 
"targets": [ 0 ], //first column / numbering column
"orderable": false, //set not orderable
},
],

});


});

    function add_resignation()
    {
      save_method = 'add';
$('#add_resignations')[0].reset(); // reset form on modals
$('#add_resignation').modal('show'); // show bootstrap modal
$('.modal-title').text('Create Resignation'); // Set Title to Bootstrap modal title
$('.upload_details').hide();

  //$("#employee_id").select2();
  $('#employee_id').select2('destroy');


}


function edit_resignation(id)
{
  save_method = 'update';
$('#add_resignations')[0].reset(); // reset form on modals

//Ajax Load data from ajax
$.ajax({
  url : "<?php echo base_url();?>resignation/resignation_edit/"+id,
  type: "GET",
  dataType: "JSON",
  success: function(data)
  {

    $('[name="id"]').val(data.id);
    $('[name="noticedate"]').val(data.noticedate);
    $('[name="resignationdate"]').val(data.resignationdate);
    $('[name="reason"]').val(data.reason);

      $("#employee_id").select2();
      $('#employee_id').val(data.employee);
      $('#employee_id').select2('destroy');
      $("#employee_id").select2();
    
   
$('#add_resignation').modal('show'); // show bootstrap modal when complete loaded
$('.modal-title').text('Edit Resignation'); // Set title to Bootstrap modal title




},
error: function (jqXHR, textStatus, errorThrown)
{
  alert('Error get data from ajax');
}
});
}

function reload_table()
{
table.ajax.reload(null,false); //reload datatable ajax 
}



function delete_resignations(id)
{
    $('#delete_resignation').modal('show'); // show bootstrap modal when complete loaded
   // Set title to Bootstrap modal title

   $('#delete_resignations').attr('onclick','delete_resignation('+id+')');
 



}

function delete_resignation(id)

{

        $.ajax({
          url : "<?php echo site_url('resignation/resignation_delete')?>/"+id,
          type: "POST",
          dataType: "JSON",
          success: function(data)
          {
        //if success reload ajax table
        $('#delete_resignation').modal('hide');
        reload_table();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          alert('Error deleting data');
        }
        });

}





</script>



<script type="text/javascript">
  

  function reload_table()
  {
table.ajax.reload(null,false); //reload datatable ajax 
}



</script>

<script type="text/javascript">
    $(document).ready(function (e){
        $("#add_resignations").on('submit',(function(e){
            e.preventDefault();

            var employee_id = $('#employee_id').val();
            var noticedate = $('#noticedate').val();
            var resignationdate = $('#resignationdate').val();
            var reason = $('#reason').val();
          

             if(employee_id == '')
            {
                toastr.error('Resigning Employee Field is Required');
                return false;
            }

            if(noticedate == '')
            {
                toastr.error('Notice Date Field is Required');
                return false;
            }

            if(resignationdate == '')
            {
                toastr.error('Resignation Date Field is Required');
                return false;
            }

            if(reason == '')
            {
                toastr.error('Reason  Field is Required');
                return false;
            }

            
           
            if(save_method=='update')
            {
              var urls='<?php echo base_url();?>resignation/update_resignation';
            }
            else
            {
              var urls='<?php echo base_url();?>resignation/add_resignation';
            }

            $.ajax({
                url: urls,
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                beforeSend:function() { 

                    $('#btnSave').text('Processing.....');
                    $('#btnSave').attr('disabled',true);
                    

                },
                success: function(data){
                  //alert(data);
                   var obj=jQuery.parseJSON(data);
                    if(obj.result=='yes')
                    {
                          toastr.success(obj.status);
                          $('#add_resignation').modal('hide');
                          $('#btnSave').text('Submit');
                          $('#btnSave').attr('disabled',false);
                          $('#add_resignations')[0].reset();
                          
                          table.ajax.reload(null,false);
                    }
                    else if(obj.result=='no')
                    {
                       toastr.error(obj.status);
                        $('#btnSave').text('Submit');
                        $('#btnSave').attr('disabled',false);
                    }

                    else if(obj.result=='uplo')
                    {
                        toastr.error(obj.status);
                        $('#btnSave').text('Submit');
                        $('#btnSave').attr('disabled',false);
                    }

                   
                   else
                    {
                        window.location.reload();
                    }
                },

            });
        }));
    });
</script> 

<?php } ?>


<?php if($this->uri->segment(1) == 'termination'){ ?> 

    <script type="text/javascript">



       

    $(document).ready(function (e){
        $("#add_termination_type").on('submit',(function(e){
            e.preventDefault();

            var term_ination_type = $('#term_ination_type').val();
            
             if(term_ination_type == '')
            {
                toastr.error('Termination Type Field is Required');
                return false;
            }
           
           
              var urls='<?php echo base_url();?>termination/add_termination_type';
           

            $.ajax({
                url: urls,
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                beforeSend:function() { 

                    $('#btnSave_t').text('Processing.....');
                    $('#btnSave_t').attr('disabled',true);
                    

                },
                success: function(data){
                  //alert(data);
                   var obj=jQuery.parseJSON(data);
                    if(obj.result=='yes')
                    {
                          toastr.success(obj.status);
                          $('#add_termination_type_modal').modal('hide');
                          $('#btnSave_t').text('Submit');
                          $('#btnSave_t').attr('disabled',false);
                          $('#add_termination_type')[0].reset();

                          get_terminations();
                          
                         
                    }
                    else
                    {
                       toastr.error(obj.status);
                        $('#btnSave_t').text('Submit');
                        $('#btnSave_t').attr('disabled',false);
                    }

                    
                },

            });
        }));
    });


 get_terminations()

        function get_terminations()
        {
            
             $.ajax({
            type: "GET",
            url: "<?php echo base_url();?>termination/get_termination",
            data:{id:''}, 
            beforeSend :function(){
          $(".termination_type option:gt(0)").remove();
          //$('.termination_type').find("option:eq(0)").html("Please wait..");
            },                         
            success: function (data) {
              /*get response as json */
               $('.termination_type').find("option:eq(0)").html("Select Termination Type");
              var obj=jQuery.parseJSON(data);
              $(obj).each(function()
              {
               var option = $('<option />');
               option.attr('value', this.value).text(this.label);           
               $('.termination_type').append(option);
             });  
             
                          
            }
          });

        }



</script> 




  <script type="text/javascript">
    var save_method;
    var table;

    $(document).ready(function() {

//datatables
table = $('#termination_table').DataTable({ 

"processing": true, //Feature control the processing indicator.
'bnDestroy' :true,
"serverSide": true, //Feature control DataTables' server-side processing mode.
"order": [], //Initial no order.
"bFilter": false,

// Load data for the table's content from an Ajax source
"ajax": {
  "url": "<?php echo base_url();?>termination/termination_list",
  "type": "POST",
  "data": function ( data ) {
    
  }
},

//Set column definition initialisation properties.
"columnDefs": [
{ 
"targets": [ 0 ], //first column / numbering column
"orderable": false, //set not orderable
},
],

});


});

    function add_termination()
    {
      save_method = 'add';
$('#add_terminations')[0].reset(); // reset form on modals
$('#add_termination').modal('show'); // show bootstrap modal
$('.modal-title').text('Create Termination'); // Set Title to Bootstrap modal title
$('.upload_details').hide();
$('#termination_type').val('');

   $("#employee_id").select2();
  $('#employee_id').select2('destroy');
  $("#employee_id").select2();


}


function edit_termination(id)
{
  save_method = 'update';
$('#add_terminations')[0].reset(); // reset form on modals

//Ajax Load data from ajax
$.ajax({
  url : "<?php echo base_url();?>termination/termination_edit/"+id,
  type: "GET",
  dataType: "JSON",
  success: function(data)
  {

    $('[name="id"]').val(data.id);
    $('[name="lastdate"]').val(data.lastdate);
    $('[name="terminationdate"]').val(data.terminationdate);
    $('[name="reason"]').val(data.reason);
    $('#termination_type').val('');
    $('#termination_type').val(data.termination_type);

      $("#employee_id").select2();
      $('#employee_id').val(data.employee);
      $('#employee_id').select2('destroy');
      $("#employee_id").select2();


      
      
    
   
$('#add_termination').modal('show'); // show bootstrap modal when complete loaded
$('.modal-title').text('Edit Termination'); // Set title to Bootstrap modal title




},
error: function (jqXHR, textStatus, errorThrown)
{
  alert('Error get data from ajax');
}
});
}

function reload_table()
{
table.ajax.reload(null,false); //reload datatable ajax 
}



function delete_terminations(id)
{
    $('#delete_termination').modal('show'); // show bootstrap modal when complete loaded
   // Set title to Bootstrap modal title

   $('#delete_terminations').attr('onclick','delete_termination('+id+')');
 



}

function delete_termination(id)

{

        $.ajax({
          url : "<?php echo site_url('termination/termination_delete')?>/"+id,
          type: "POST",
          dataType: "JSON",
          success: function(data)
          {
        //if success reload ajax table
        $('#delete_termination').modal('hide');
        reload_table();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          alert('Error deleting data');
        }
        });

}





</script>



<script type="text/javascript">
  

  function reload_table()
  {
table.ajax.reload(null,false); //reload datatable ajax 
}



</script>

<script type="text/javascript">
    $(document).ready(function (e){
        $("#add_terminations").on('submit',(function(e){
            e.preventDefault();


            var employee_id = $('#employee_id').val();
            var termination_type = $('#termination_type').val();
            var terminationdate = $('#terminationdate').val();
            var reason = $('#reason').val();
            var lastdate = $('#lastdate').val();

             if(employee_id == '')
            {
                toastr.error('Terminated Employee Field is Required');
                return false;
            }

            if(termination_type == '')
            {
                toastr.error('Termination Type Field is Required');
                return false;
            }

            if(terminationdate == '')
            {
                toastr.error('Termination Date Field is Required');
                return false;
            }

            if(reason == '')
            {
                toastr.error('Reason  Field is Required');
                return false;
            }

            if(lastdate == '')
            {
                toastr.error('Last Date  Field is Required');
                return false;
            }

           
            if(save_method=='update')
            {
              var urls='<?php echo base_url();?>termination/update_termination';
            }
            else
            {
              var urls='<?php echo base_url();?>termination/add_termination';
            }

            $.ajax({
                url: urls,
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                beforeSend:function() { 

                    $('#btnSave').text('Processing.....');
                    $('#btnSave').attr('disabled',true);
                    

                },
                success: function(data){
                  //alert(data);
                   var obj=jQuery.parseJSON(data);
                    if(obj.result=='yes')
                    {
                          toastr.success(obj.status);
                          $('#add_termination').modal('hide');
                          $('#btnSave').text('Submit');
                          $('#btnSave').attr('disabled',false);
                          $('#add_terminations')[0].reset();
                          
                          table.ajax.reload(null,false);
                    }
                    else if(obj.result=='no')
                    {
                       toastr.error(obj.status);
                        $('#btnSave').text('Submit');
                        $('#btnSave').attr('disabled',false);
                    }

                    else if(obj.result=='uplo')
                    {
                        toastr.error(obj.status);
                        $('#btnSave').text('Submit');
                        $('#btnSave').attr('disabled',false);
                    }

                   
                   else
                    {
                        window.location.reload();
                    }
                },

            });
        }));
    });
</script> 

<?php } ?>


<?php if($this->uri->segment(1) == 'promotion'){ ?> 

    <script type="text/javascript">



       

    $(document).ready(function (){

    $('#employee_id').change(function(){
        var employeeid=$(this).val();

        $.post('<?php echo base_url();?>promotion/get_departments',{employeeid:employeeid},function(data){

             var obj=jQuery.parseJSON(data);
             $('#designation').val(obj.designation_id);
             $('#grade').val(obj.grade);
             $('#grade_name').val(obj.grade_name);

        });


        $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>promotion/get_grades",
        data:{employeeid:employeeid}, 
        beforeSend :function(){
          $("#promotionto option:gt(0)").remove(); 
          $('#promotionto').select2('destroy');
          $("#promotionto").select2();
          $('#promotionto').find("option:eq(0)").html("Please wait..");
          $('#promotionto').select2('destroy');
          $("#promotionto").select2();
        },                         
        success: function (data) {   
          $('#promotionto').select2('destroy'); 
          $("#promotionto").select2();      
          $('#promotionto').find("option:eq(0)").html("--Select--");
          $('#promotionto').select2('destroy');
          $("#promotionto").select2();
          var obj=jQuery.parseJSON(data);       
          $('#promotionto').select2('destroy');
          $("#promotionto").select2();
          $(obj).each(function(){
            var option = $('<option />');
            option.attr('value', this.value).text(this.label);           
            $('#promotionto').append(option);
          });       
          $('#promotionto').select2('destroy');
          $("#promotionto").select2();
        }
      });  




        
    });




        
    });





</script> 




  <script type="text/javascript">
    var save_method;
    var table;

    $(document).ready(function() {

//datatables
table = $('#promotion_table').DataTable({ 

"processing": true, //Feature control the processing indicator.
'bnDestroy' :true,
"serverSide": true, //Feature control DataTables' server-side processing mode.
"order": [], //Initial no order.
"bFilter": false,

// Load data for the table's content from an Ajax source
"ajax": {
  "url": "<?php echo base_url();?>promotion/promotion_list",
  "type": "POST",
  "data": function ( data ) {
    
  }
},

//Set column definition initialisation properties.
"columnDefs": [
{ 
"targets": [ 0 ], //first column / numbering column
"orderable": false, //set not orderable
},
],

});


});

    function add_promotion()
    {
      save_method = 'add';
$('#add_promotions')[0].reset(); // reset form on modals
$('#add_promotion').modal('show'); // show bootstrap modal
$('.modal-title').text('Create promotion'); // Set Title to Bootstrap modal title
$('.upload_details').hide();
$('#promotion_type').val('');

   $("#employee_id").select2();
  $('#employee_id').select2('destroy');
  $("#employee_id").select2();

      $("#promotionto").select2();
      $('#promotionto').select2('destroy');
      $("#promotionto").select2();


}


function edit_promotion(id)
{
  save_method = 'update';
$('#add_promotions')[0].reset(); // reset form on modals

//Ajax Load data from ajax
$.ajax({
  url : "<?php echo base_url();?>promotion/promotion_edit/"+id,
  type: "GET",
  dataType: "JSON",
  success: function(datas)
  {

    $('[name="id"]').val(datas.id);
    $('[name="designation"]').val(datas.designation);
    $('[name="grade"]').val(datas.grade);
    $('[name="grade_name"]').val(datas.grade_name);
    $('[name="promotiondate"]').val(datas.promotiondate);
    

      $("#employee_id").select2();
      $('#employee_id').val(datas.employee);
      $('#employee_id').select2('destroy');
      $("#employee_id").select2();


      $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>promotion/get_grades",
        data:{employeeid:datas.employee}, 
        beforeSend :function(){
          $("#promotionto option:gt(0)").remove(); 
          $('#promotionto').select2('destroy');
          $("#promotionto").select2();
          $('#promotionto').find("option:eq(0)").html("Please wait..");
          $('#promotionto').select2('destroy');
          $("#promotionto").select2();
        },                         
        success: function (data) {   
          $('#promotionto').select2('destroy'); 
          $("#promotionto").select2();      
          $('#promotionto').find("option:eq(0)").html("--Select--");
          $('#promotionto').select2('destroy');
          $("#promotionto").select2();
          var obj=jQuery.parseJSON(data);       
          $('#promotionto').select2('destroy');
          $("#promotionto").select2();
          $(obj).each(function(){
            var option = $('<option />');
            option.attr('value', this.value).text(this.label);           
            $('#promotionto').append(option);
          });       
          $("#promotionto").select2();
          $('#promotionto').val(datas.promotionto);
          $('#promotionto').select2('destroy');
          $("#promotionto").select2();

        }
      }); 


     


      
      
    
   
$('#add_promotion').modal('show'); // show bootstrap modal when complete loaded
$('.modal-title').text('Edit promotion'); // Set title to Bootstrap modal title




},
error: function (jqXHR, textStatus, errorThrown)
{
  alert('Error get data from ajax');
}
});
}

function reload_table()
{
table.ajax.reload(null,false); //reload datatable ajax 
}



function delete_promotions(id)
{
    $('#delete_promotion').modal('show'); // show bootstrap modal when complete loaded
   // Set title to Bootstrap modal title

   $('#delete_promotions').attr('onclick','delete_promotion('+id+')');
 



}

function delete_promotion(id)

{

        $.ajax({
          url : "<?php echo site_url('promotion/promotion_delete')?>/"+id,
          type: "POST",
          dataType: "JSON",
          success: function(data)
          {
        //if success reload ajax table
        $('#delete_promotion').modal('hide');
        reload_table();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          alert('Error deleting data');
        }
        });

}





</script>



<script type="text/javascript">
  

  function reload_table()
  {
table.ajax.reload(null,false); //reload datatable ajax 
}



</script>

<script type="text/javascript">
    $(document).ready(function (e){
        $("#add_promotions").on('submit',(function(e){
            e.preventDefault();

            var employee_id = $('#employee_id').val();
            var designation = $('#designation').val();
            var promotionto = $('#promotionto').val();
            var promotiondate = $('#promotiondate').val();

             if(employee_id == '')
            {
                toastr.error('Promotion For Field is Required');
                return false;
            }

            if(designation == '')
            {
                toastr.error('Promotion From Field is Required');
                return false;
            }

            if(promotionto == '')
            {
                toastr.error('Promotion To Field is Required');
                return false;
            }

            if(promotiondate == '')
            {
                toastr.error('Promotion Date  Field is Required');
                return false;
            }
           
            if(save_method=='update')
            {
              var urls='<?php echo base_url();?>promotion/update_promotion';
            }
            else
            {
              var urls='<?php echo base_url();?>promotion/add_promotion';
            }

            $.ajax({
                url: urls,
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                beforeSend:function() { 

                    $('#btnSave').text('Processing.....');
                    $('#btnSave').attr('disabled',true);
                    

                },
                success: function(data){
                  //alert(data);
                   var obj=jQuery.parseJSON(data);
                    if(obj.result=='yes')
                    {
                          toastr.success(obj.status);
                          $('#add_promotion').modal('hide');
                          $('#btnSave').text('Submit');
                          $('#btnSave').attr('disabled',false);
                          $('#add_promotions')[0].reset();
                          
                          table.ajax.reload(null,false);
                    }
                    else if(obj.result=='no')
                    {
                       toastr.error(obj.status);
                        $('#btnSave').text('Submit');
                        $('#btnSave').attr('disabled',false);
                    }

                    else if(obj.result=='uplo')
                    {
                        toastr.error(obj.status);
                        $('#btnSave').text('Submit');
                        $('#btnSave').attr('disabled',false);
                    }

                   
                   else
                    {
                        window.location.reload();
                    }
                },

            });
        }));
    });
</script> 

<?php } ?>



<script type="text/javascript">
   $(document).ready(function() {
       // $('.foeditor').summernote({ height: 200, codemirror: { theme: 'monokai' } });
        $('.datatable').DataTable(); 
        $('#wiki').DataTable(); 
        $('#notice_board').DataTable(); 
        $('#project_report').DataTable(); 
        $('#task_report').DataTable(); 


       var table =  $('#table-clients-compaines').DataTable(); 
        
         // $('#departmen_id').on('change', function () {
         $('#client_search').on('click', function () {
            var client_name = $('#client_name').val();
            var client_email = $('#client_email').val();
                if(client_name != '')
                {
                        table.columns(0).search( client_name ).draw();
                }
                if(client_email != '')
                {
                        table.columns(4).search( client_email ).draw();
                }
                if((client_name == '') && (client_email == '')){
                    table.search('').columns().search('').draw();
                }
        });


         $('#client_search_grid').click(function(){
           var client_name_edit = $('#client_name_edit').val();
           var client_email_edit = $('#client_email_edit').val();
           if(client_name_edit != '')
           {
            $('.AllGridCompanies').hide();
            $('.AllGridCompanies:contains("'+client_name_edit+'")').show();
           }
           // if(client_email_edit != '')
           // {
           //  $('.AllGridCompanies:contains("'+client_email_edit+'")').show();
           // }
           // if((client_name_edit == '') && (client_email_edit == '')){
           if(client_name_edit == ''){
                    $('.AllGridCompanies').show();
                }
        });
    });

</script>

<script type="text/javascript">
    function delete_custom_policy(id)
    {
         $('#delete_custom_policy').modal('show'); // show bootstrap modal when complete loaded
         $('#delete_custom_policys').attr('onclick','delete_custom_policys('+id+')');
    }

    function delete_custom_policys(id) 
    {
        $.ajax({
          url : "<?php echo site_url('leave_settings/policy_delete')?>/"+id,
          type: "POST",
          success: function(data)
          {
            window.location.reload();
        }
        });
    }
</script>

<script type="text/javascript">
    function user_status_change(id)
    {
        if ($('#payroll_user_status'+id).is(':checked')) {
            var status=1;
        }
        else
        {
            var status=0;
        }


         $.post('<?php echo base_url();?>payroll/status_change',{employeeid:id,status:status},function(data){
            if(data==1)
            {
              toastr.success('Status changed successfully');
            }
            else
            {
                toastr.success('Status changed failed');
            }
         
        });
    }

    //Bootbox prompt for 
        function send_Appmails(ascid)
        {
            console.log(ascid);
         bootbox.confirm({
            message: "Are you sure, send the mail?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                     
                    $.ajax({
                    url: 'offers/send_applicantmail/',
                    dataType:'json',
                    type: 'POST',
                    data: {'assoc_id':ascid},
                    success: function (data) {
                         
                       
                        if(data.response=='ok')
                        {
                           window.location.reload();
                        }
                    },
                  
                    
                });
                }
            }
            });
        }
        //Bootbox prompt for 
        function app_archive(current,ascid)
        {
            console.log(ascid);
         bootbox.confirm({
            message: "Are you sure, move it to archive?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                     
                    $.ajax({
                    url: 'offers/to_archive/',
                    dataType:'json',
                    type: 'POST',
                    data: {'assoc_id':ascid,'current':current},
                    success: function (data) {
                         
                       
                        if(data.response=='ok')
                        {
                           window.location.reload();
                        }
                        else window.location.reload();
                    },
                  
                    
                });
                }
            }
            });
        }
        //Bootbox prompt for 
        function app_retrieve(ascid)
        {
            console.log(ascid);
         bootbox.confirm({
            message: "Are you sure, move it to archive?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                     
                    $.ajax({
                    url: 'offers/app_retrieve/',
                    dataType:'json',
                    type: 'POST',
                    data: {'assoc_id':ascid},
                    success: function (data) {
                         
                       
                        if(data.response=='ok')
                        {
                           window.location.reload();
                        }
                        else window.location.reload();
                    },
                  
                    
                });
                }
            }
            });
        }
         //Bootbox prompt for 
        function app_accept(ascid)
        {
            console.log(ascid);
         bootbox.confirm({
            message: "Are you sure, move it to accept state?",
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                     
                    $.ajax({
                    url: 'offers/app_accepts/',
                    dataType:'json',
                    type: 'POST',
                    data: {'assoc_id':ascid},
                    success: function (data) {
                         
                       
                        if(data.response=='ok')
                        {
                           window.location.reload();
                        }
                        else window.location.reload();
                    },
                  
                    
                });
                }
            }
            });
        }
        function set_appval(id)
        {
            var tab_hide = $('#tab_hide').val(id);
            
         
        }
        function offer_declinejs()
        {
            var tab_hideval = $('#tab_hide').val();
              $.ajax({
                    url: 'offers/offer_decline/',
                    dataType:'json',
                    type: 'POST',
                    data: {'assoc_id':tab_hideval},
                    success: function (data) {
                         
                       
                        if(data.response=='ok')
                        {
                           window.location.reload();
                        }
                    },
                  
                    
                });
        }

$(document).ready(function(){
    $("#table-offer_app").DataTable();
    $('#offer_app_view').click(function(){
         $('.div_offer_app').show();
         $('.div_table-expenses').hide();
         
         $('.leave_approval_div').hide();
         $('#offer_app_view').parent('li').addClass('active');
         $('#expense_approval').parent('li').removeClass('active');
         $('#leave_approval').parent('li').removeClass('active');

    });
     $('#expense_approval').click(function(){
         $('.div_table-expenses').show();
         $('.div_table-expenses_cnt').show();
         $('.div_offer_app').hide();
         $('.leave_approval_div').hide();
         $('#offer_app_view').parent('li').removeClass('active');
         $('#expense_approval').parent('li').addClass('active');
         $('#leave_approval').parent('li').removeClass('active');

    });
      $('#leave_approval').click(function(){
         $('.div_table-expenses').hide();
         $('.leave_approval_div').show();
         
         $('.div_offer_app').hide();
         $('#offer_app_view').parent('li').removeClass('active');
         $('#expense_approval').parent('li').removeClass('active');
         $('#leave_approval').parent('li').addClass('active');


    });



});
</script>
    
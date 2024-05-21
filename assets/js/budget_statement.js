function revenue_add(year){

        var total_count = $('#new_hidden_rows').val();
        var count = Number(total_count)+1;

        document.getElementById('new_hidden_rows').value = count;

        var index = $("#revenue_cont_"+count+""+year+" tr:last-child").index();
        var revenueactions = $("#revenue_cont_"+year+" td.actionss:last-child").html();
        var row = '<tr>' +
           
            '<td><input type="text" id="budget_name'+count+''+year+'"  name="budget_name[]" class="form-control"></td>' +
            '<td><input type="text" id="jan'+count+''+year+'"  name="jan[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="feb'+count+''+year+'"  name="feb[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="mar'+count+''+year+'"  name="mar[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="apr'+count+''+year+'"  name="apr[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="may'+count+''+year+'"  name="may[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="jun'+count+''+year+'"  name="jun[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="jul'+count+''+year+'"  name="jul[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="aug'+count+''+year+'"  name="aug[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="sep'+count+''+year+'"  name="sep[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="oct'+count+''+year+'"  name="oct[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="nov'+count+''+year+'"  name="nov[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="dece'+count+''+year+'" name="dece[]" class="form-control num_val"></td>' +
            '<td><strong></strong></td>' +
            '<td class="text-right actionss">'+
              '<a class="btn btn-info btn-xs " onclick="save_revenue('+count+','+year+',this)" data-type ="revenue" data-id="" id="revenue_save" title="Add" data-toggle="tooltip"><i class="fa fa-check"></i></a>'+
               // '<a class="btn btn-success btn-xs edit" id="revenue_edit" title="Edit" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>'+
               ' <a class="btn btn-danger btn-xs delete" id="revenue_delete" title="Delete" data-toggle="tooltip"><i class="fa fa-trash-o"></i></a>'+
               '</td>' +
        '</tr>';
      $("#revenue_cont_"+year).append(row);   
    $("#revenue_cont_"+year+" tr").eq(index + 1).find("#revenue_save, #revenue_edit").toggle();
    };


    function save_revenue(count,year,element){
                var id = element.getAttribute('data-id');                
                var type = element.getAttribute('data-type');
                var budget_name = $('#budget_name'+count+year).val();
                if(budget_name ==''){
                     $('#budget_name'+count+year).addClass("error");
                    preventDefault();
                }
                var jan = $('#jan'+count+year).val();
                var feb = $('#feb'+count+year).val();
                var mar = $('#mar'+count+year).val();
                var apr = $('#apr'+count+year).val();
                var may = $('#may'+count+year).val();
                var jun = $('#jun'+count+year).val();
                var jul = $('#jul'+count+year).val();
                var aug = $('#aug'+count+year).val();
                var sep = $('#sep'+count+year).val();
                var oct = $('#oct'+count+year).val();
                var nov = $('#nov'+count+year).val();
                var dece = $('#dece'+count+year).val();
                console.log(dece); 
        var dataString  =   {id:id,type:type,budget_name:budget_name,jan:jan,feb:feb,mar:mar,apr:apr,may:may,jun:jun,jul:jul,aug:aug,sep:sep,oct:oct,nov:nov,dece:dece,year:year};
        
        $.ajax({
            type: "POST",
            url: base_url+'budget_statement/add_budget_statement',
            data: dataString,           
            success: function(data){
                window.location.href= base_url+"budget_statement/index/"+year;            
            },
            error: function (xhr, b, c) {
                //console.log(xhr);
            }
        });   
    }

  // Edit row on edit button click
  $(document).on("click", "#revenue_edit", function(){    
        $(this).parents("tr").find("td:not(:last-child)").each(function(){
            var budget_name = $(this).attr('data-name');
            var num_val = (budget_name == 'budget_name')?'':'num_val'; 
      $(this).html('<input type="text" class="form-control '+num_val+'" value="' + $(this).text() + '" name="'+$(this).attr('data-name')+'" id="'+$(this).attr('data-id')+'">');
    });   
    $(this).parents("tr").find("#revenue_save, #revenue_edit").toggle();
    });
  // Delete row on delete button click
  $(document).on("click", "#revenue_delete", function(){
        $(this).parents("tr").remove();
    });
  
  
  // Expense add edit delete

  function expense_add(year){

        var total_count = $('#expense_new_hidden_rows').val();
        var count = Number(total_count)+1;
//        $('#new_hidden_rows').val(count);

        document.getElementById('expense_new_hidden_rows').value = count;

        var index = $("#expense_cont"+count+""+year+" tr:last-child").index();
        var revenueactions = $("#expense_cont"+year+" td.actionss:last-child").html();
        var row = '<tr>' +
           
            '<td><input type="text" id="expense_budget_name'+count+''+year+'"  name="budget_name[]" class="form-control"></td>' +
            '<td><input type="text" id="expense_jan'+count+''+year+'"  name="jan[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="expense_feb'+count+''+year+'"  name="feb[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="expense_mar'+count+''+year+'"  name="mar[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="expense_apr'+count+''+year+'"  name="apr[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="expense_may'+count+''+year+'"  name="may[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="expense_jun'+count+''+year+'"  name="jun[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="expense_jul'+count+''+year+'"  name="jul[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="expense_aug'+count+''+year+'"  name="aug[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="expense_sep'+count+''+year+'"  name="sep[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="expense_oct'+count+''+year+'"  name="oct[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="expense_nov'+count+''+year+'"  name="nov[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="expense_dece'+count+''+year+'" name="dece[]" class="form-control num_val"></td>' +
            '<td><strong></strong></td>' +
            '<td class="text-right actionss">'+
              '<a class="btn btn-info btn-xs " onclick="save_expense('+count+','+year+',this)" data-type ="expense" data-id="" id="expense_save" title="Add" data-toggle="tooltip"><i class="fa fa-check"></i></a>'+
               // '<a class="btn btn-success btn-xs edit" id="expense_edit" title="Edit" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>'+
               ' <a class="btn btn-danger btn-xs delete" id="revenue_delete" title="Delete" data-toggle="tooltip"><i class="fa fa-trash-o"></i></a>'+
               '</td>' +
        '</tr>';
      $("#expense_cont"+year).append(row);   
    $("#expense_cont"+year+" tr").eq(index + 1).find("#expense_save, #expense_edit").toggle();
    };


    function save_expense(count,year,element){
                var id = element.getAttribute('data-id');                
                var type = element.getAttribute('data-type');
                var budget_name = $('#expense_budget_name'+count+year).val();
                 if(budget_name ==''){
                     $('#expense_budget_name'+count+year).addClass("error");
                    preventDefault();
                }
                var jan = $('#expense_jan'+count+year).val();
                var feb = $('#expense_feb'+count+year).val();
                var mar = $('#expense_mar'+count+year).val();
                var apr = $('#expense_apr'+count+year).val();
                var may = $('#expense_may'+count+year).val();
                var jun = $('#expense_jun'+count+year).val();
                var jul = $('#expense_jul'+count+year).val();
                var aug = $('#expense_aug'+count+year).val();
                var sep = $('#expense_sep'+count+year).val();
                var oct = $('#expense_oct'+count+year).val();
                var nov = $('#expense_nov'+count+year).val();
                var dece = $('#expense_dece'+count+year).val();
                console.log(dece); 
        var dataString  =   {id:id,type:type,budget_name:budget_name,jan:jan,feb:feb,mar:mar,apr:apr,may:may,jun:jun,jul:jul,aug:aug,sep:sep,oct:oct,nov:nov,dece:dece,year:year};
        
        $.ajax({
            type: "POST",
            url: base_url+'budget_statement/add_budget_statement',
            data: dataString,           
            success: function(data){
                window.location.href= base_url+"budget_statement/index/"+year;       
            },
            error: function (xhr, b, c) {
                //console.log(xhr);
            }
        });   
    }

  // Edit row on edit button click
  $(document).on("click", "#expense_edit", function(){    
        $(this).parents("tr").find("td:not(:last-child)").each(function(){
             var budget_name = $(this).attr('data-name');
            var num_val = (budget_name == 'budget_name')?'':'num_val'; 
      $(this).html('<input type="text" class="form-control '+num_val+'" value="' + $(this).text() + '" name="'+$(this).attr('data-name')+'" id="'+$(this).attr('data-id')+'">');
    });   
    $(this).parents("tr").find("#expense_save, #expense_edit").toggle();
    });
  // Delete row on delete button click
  $(document).on("click", "#expense_delete", function(){
        $(this).parents("tr").remove();
    });
  
  
  // Tax add edit delete

function tax_add(year){

        var total_count = $('#no_of_tax').val();
        var count = Number(total_count)+1;
       // alert(total_count);
//        $('#new_hidden_rows').val(count);

        document.getElementById('tax_new_hidden_rows').value = count;

        var index = $("#tax_cont"+count+""+year+" tr:last-child").index();
        var revenueactions = $("#tax_cont"+year+" td.actionss:last-child").html();
        var row = '<tr>' +
           
            '<td><div class="tax-input"><input type="text" class="form-control" placeholder="Tax Name" id="tax_budget_name'+count+''+year+'"  name="budget_name[]"><input type="text"  id="tax_percentage'+count+''+year+'"  name="tax_percentage" class="form-control tax-value-input num_val" placeholder="%"></div></td>' +
            '<td><input type="text" id="tax_jan'+count+''+year+'"  name="jan[]" class="form-control" disabled></td>' +
            '<td><input type="text" id="tax_feb'+count+''+year+'"  name="feb[]" class="form-control" disabled></td>' +
            '<td><input type="text" id="tax_mar'+count+''+year+'"  name="mar[]" class="form-control" disabled></td>' +
            '<td><input type="text" id="tax_apr'+count+''+year+'"  name="apr[]" class="form-control" disabled></td>' +
            '<td><input type="text" id="tax_may'+count+''+year+'"  name="may[]" class="form-control" disabled></td>' +
            '<td><input type="text" id="tax_jun'+count+''+year+'"  name="jun[]" class="form-control" disabled></td>' +
            '<td><input type="text" id="tax_jul'+count+''+year+'"  name="jul[]" class="form-control" disabled></td>' +
            '<td><input type="text" id="tax_aug'+count+''+year+'"  name="aug[]" class="form-control" disabled></td>' +
            '<td><input type="text" id="tax_sep'+count+''+year+'"  name="sep[]" class="form-control" disabled></td>' +
            '<td><input type="text" id="tax_oct'+count+''+year+'"  name="oct[]" class="form-control" disabled></td>' +
            '<td><input type="text" id="tax_nov'+count+''+year+'"  name="nov[]" class="form-control" disabled></td>' +
            '<td><input type="text" id="tax_dece'+count+''+year+'" name="dece[]" class="form-control" disabled></td>' +
            '<td><strong><input type="text" class="form-control" disabled></strong></td>' +
            '<td class="text-right actionss">'+
              '<a class="btn btn-info btn-xs " onclick="save_tax('+count+','+year+',this)" data-type ="tax" data-id="" id="tax_save" title="Add" data-toggle="tooltip"><i class="fa fa-check"></i></a>'+
               // '<a class="btn btn-success btn-xs edit" id="tax_edit" title="Edit" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>'+
               ' <a class="btn btn-danger btn-xs delete" id="tax_delete" title="Delete" data-toggle="tooltip"><i class="fa fa-trash-o"></i></a>'+
               '</td>' +
        '</tr>';
      $("#tax_cont"+year).append(row);   
    $("#tax_cont"+year+" tr").eq(index + 1).find("#tax_save, #tax_edit").toggle();
    };


    function save_tax(count,year,element){
                var id = element.getAttribute('data-id');                
                var type = element.getAttribute('data-type');
                var budget_name = $('#tax_budget_name'+count+year).val();
                var tax_percentage = $('#tax_percentage'+count+year).val();
                if(budget_name ==''){
                     $('#tax_budget_name'+count+year).addClass("error");
                      $('#tax_percentage'+count+year).removeClass("error");
                    preventDefault();
                }
                if(tax_percentage==''){
                    $('#tax_percentage'+count+year).addClass("error");
                    $('#tax_budget_name'+count+year).removeClass("error");
                    preventDefault();
                }
                // var jan = $('#expense_jan'+count+year).val();
                // var feb = $('#expense_feb'+count+year).val();
                // var mar = $('#expense_mar'+count+year).val();
                // var apr = $('#expense_apr'+count+year).val();
                // var may = $('#expense_may'+count+year).val();
                // var jun = $('#expense_jun'+count+year).val();
                // var jul = $('#expense_jul'+count+year).val();
                // var aug = $('#expense_aug'+count+year).val();
                // var sep = $('#expense_sep'+count+year).val();
                // var oct = $('#expense_oct'+count+year).val();
                // var nov = $('#expense_nov'+count+year).val();
                // var dece = $('#expense_dece'+count+year).val();
                // console.log(dece); 
        var dataString  =   {id:id,type:type,budget_name:budget_name,tax_percentage:tax_percentage,year:year};
        
        $.ajax({
            type: "POST",
            url: base_url+'budget_statement/add_budget_statement',
            data: dataString,           
            success: function(data){
                window.location.href= base_url+"budget_statement/index/"+year;               
            },
            error: function (xhr, b, c) {
                //console.log(xhr);
            }
        });   
    }
  
  
  $(document).on("click", "#tax_edit", function(){    
    //     $(this).parents("tr").find("td:not(:last-child)").each(function(){
    //          var budget_name = $(this).attr('data-name');
    //         var num_val = (budget_name == 'budget_name')?'':'num_val'; 
    //  $(this).html('<input type="text" class="form-control '+num_val+'" value="' + $(this).text() + '" name="'+$(this).attr('data-name')+'" id="'+$(this).attr('data-id')+'">');
    // });   
    var budget_name = $(this).data('budget_name');
    var tax= $(this).data('tax');
     $("#"+budget_name).attr("readonly", false); 
     $("#"+tax).attr("readonly", false); 
    $(this).parents("tr").find("#tax_save, #tax_edit").toggle();
    });
  // Delete row on delete button click
  $(document).on("click", "#tax_delete", function(){
        $(this).parents("tr").remove();
    });
  
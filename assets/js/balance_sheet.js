 function asset_add(year){

        var total_count = $('#new_hidden_rows').val();
        var count = Number(total_count)+1;
       // alert(count);
//      $('#new_hidden_rows').val(count);

        document.getElementById('new_hidden_rows').value = count;

        var index = $("#asset_cont_"+count+""+year+" tr:last-child").index();
        var revenueactions = $("#asset_cont_"+year+" td.actionss:last-child").html();
        var row = '<tr>' +
           
            '<td><input type="text" id="title'+count+''+year+'"  name="title[]" class="form-control"></td>' +
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
              '<a class="btn btn-info btn-xs " onclick="save_asset('+count+','+year+',this)" data-type ="asset" data-id="" id="asset_save" title="Add" data-toggle="tooltip"><i class="fa fa-check"></i></a>'+
               // '<a class="btn btn-success btn-xs edit" id="revenue_edit" title="Edit" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>'+
               ' <a class="btn btn-danger btn-xs delete" id="asset_delete" title="Delete" data-toggle="tooltip"><i class="fa fa-trash-o"></i></a>'+
               '</td>' +
        '</tr>';
      $("#asset_cont_"+year).append(row);   
    $("#asset_cont_"+year+" tr").eq(index + 1).find("#assset_save, #assset_edit").toggle();
    };


    function save_asset(count,year,element){
                var id = element.getAttribute('data-id');                
                var type = element.getAttribute('data-type');
                var title = $('#title'+count+year).val();
                if(title ==''){
                     $('#title'+count+year).addClass("error");
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
        var dataString  =   {id:id,type:type,title:title,jan:jan,feb:feb,mar:mar,apr:apr,may:may,jun:jun,jul:jul,aug:aug,sep:sep,oct:oct,nov:nov,dece:dece,year:year};
        
        $.ajax({
            type: "POST",
            url: base_url+'balance_sheet/add_balance_sheet',
            data: dataString,           
            success: function(data){
               window.location.href= base_url+"balance_sheet/index/"+year;                
            },
            error: function (xhr, b, c) {
                //console.log(xhr);
            }
        });   
    }
  
  // Edit row on edit button click
  $(document).on("click", "#asset_edit", function(){    
        $(this).parents("tr").find("td:not(:last-child)").each(function(){
            var title = $(this).attr('data-name');
            var num_val = (title == 'title')?'':'num_val'; 
      $(this).html('<input type="text" class="form-control '+num_val+'" value="' + $(this).text() + '" name="'+$(this).attr('data-name')+'" id="'+$(this).attr('data-id')+'">');
    });   
    $(this).parents("tr").find("#asset_save, #asset_edit").toggle();
    });
  // Delete row on delete button click
  $(document).on("click", "#asset_delete", function(){
        $(this).parents("tr").remove();
    });
  
  
  // Expense add edit delete

  function liabilitie_add(year){

        var total_count = $('#liabilitie_new_hidden_rows').val();
        var count = Number(total_count)+1;
//        $('#new_hidden_rows').val(count);

        document.getElementById('liabilitie_new_hidden_rows').value = count;

        var index = $("#liabilitie_cont"+count+""+year+" tr:last-child").index();
        var revenueactions = $("#liabilitie_cont"+year+" td.actionss:last-child").html();
        var row = '<tr>' +
           
            '<td><input type="text" id="liabilitie_title'+count+''+year+'"  name="title[]" class="form-control"></td>' +
            '<td><input type="text" id="liabilitie_jan'+count+''+year+'"  name="jan[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="liabilitie_feb'+count+''+year+'"  name="feb[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="liabilitie_mar'+count+''+year+'"  name="mar[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="liabilitie_apr'+count+''+year+'"  name="apr[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="liabilitie_may'+count+''+year+'"  name="may[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="liabilitie_jun'+count+''+year+'"  name="jun[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="liabilitie_jul'+count+''+year+'"  name="jul[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="liabilitie_aug'+count+''+year+'"  name="aug[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="liabilitie_sep'+count+''+year+'"  name="sep[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="liabilitie_oct'+count+''+year+'"  name="oct[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="liabilitie_nov'+count+''+year+'"  name="nov[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="liabilitie_dece'+count+''+year+'" name="dece[]" class="form-control num_val"></td>' +
            '<td><strong></strong></td>' +
            '<td class="text-right actionss">'+
              '<a class="btn btn-info btn-xs " onclick="save_liabilitie('+count+','+year+',this)" data-type ="liabilitie" data-id="" id="liabilitie_save" title="Add" data-toggle="tooltip"><i class="fa fa-check"></i></a>'+
               // '<a class="btn btn-success btn-xs edit" id="expense_edit" title="Edit" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>'+
               ' <a class="btn btn-danger btn-xs delete" id="liabilitie_delete" title="Delete" data-toggle="tooltip"><i class="fa fa-trash-o"></i></a>'+
               '</td>' +
        '</tr>';
      $("#liabilitie_cont"+year).append(row);   
    $("#liabilitie_cont"+year+" tr").eq(index + 1).find("#liabilitie_save, #liabilitie_edit").toggle();
    };


    function save_liabilitie(count,year,element){
                var id = element.getAttribute('data-id');                
                var type = element.getAttribute('data-type');
                var title = $('#liabilitie_title'+count+year).val();
                 if(title ==''){
                     $('#liabilitie_title'+count+year).addClass("error");
                    preventDefault();
                }
                var jan = $('#liabilitie_jan'+count+year).val();
                var feb = $('#liabilitie_feb'+count+year).val();
                var mar = $('#liabilitie_mar'+count+year).val();
                var apr = $('#liabilitie_apr'+count+year).val();
                var may = $('#liabilitie_may'+count+year).val();
                var jun = $('#liabilitie_jun'+count+year).val();
                var jul = $('#liabilitie_jul'+count+year).val();
                var aug = $('#liabilitie_aug'+count+year).val();
                var sep = $('#liabilitie_sep'+count+year).val();
                var oct = $('#liabilitie_oct'+count+year).val();
                var nov = $('#liabilitie_nov'+count+year).val();
                var dece = $('#liabilitie_dece'+count+year).val();
                console.log(dece); 
        var dataString  =   {id:id,type:type,title:title,jan:jan,feb:feb,mar:mar,apr:apr,may:may,jun:jun,jul:jul,aug:aug,sep:sep,oct:oct,nov:nov,dece:dece,year:year};
        
        $.ajax({
            type: "POST",
            url: base_url+'balance_sheet/add_balance_sheet',
            data: dataString,           
            success: function(data){
                window.location.href= base_url+"balance_sheet/index/"+year;            
            },
            error: function (xhr, b, c) {
                //console.log(xhr);
            }
        });   
    }

  // Edit row on edit button click
  $(document).on("click", "#liabilitie_edit", function(){    
        $(this).parents("tr").find("td:not(:last-child)").each(function(){
             var title = $(this).attr('data-name');
            var num_val = (title == 'title')?'':'num_val'; 
      $(this).html('<input type="text" class="form-control '+num_val+'" value="' + $(this).text() + '" name="'+$(this).attr('data-name')+'" id="'+$(this).attr('data-id')+'">');
    });   
    $(this).parents("tr").find("#liabilitie_save, #liabilitie_edit").toggle();
    });
  // Delete row on delete button click
  $(document).on("click", "#liabilitie_delete", function(){
        $(this).parents("tr").remove();
    });
  
  
  // Shareholder's Equity

  function shareholder_add(year){

        var total_count = $('#shareholder_new_hidden_rows').val();
        var count = Number(total_count)+1;
//        $('#new_hidden_rows').val(count);

        document.getElementById('shareholder_new_hidden_rows').value = count;

        var index = $("#shareholder_cont"+count+""+year+" tr:last-child").index();
        var revenueactions = $("#shareholder_cont"+year+" td.actionss:last-child").html();
        var row = '<tr>' +
           
            '<td><input type="text" id="shareholder_title'+count+''+year+'"  name="title[]" class="form-control"></td>' +
            '<td><input type="text" id="shareholder_jan'+count+''+year+'"  name="jan[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="shareholder_feb'+count+''+year+'"  name="feb[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="shareholder_mar'+count+''+year+'"  name="mar[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="shareholder_apr'+count+''+year+'"  name="apr[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="shareholder_may'+count+''+year+'"  name="may[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="shareholder_jun'+count+''+year+'"  name="jun[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="shareholder_jul'+count+''+year+'"  name="jul[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="shareholder_aug'+count+''+year+'"  name="aug[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="shareholder_sep'+count+''+year+'"  name="sep[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="shareholder_oct'+count+''+year+'"  name="oct[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="shareholder_nov'+count+''+year+'"  name="nov[]" class="form-control num_val"></td>' +
            '<td><input type="text" id="shareholder_dece'+count+''+year+'" name="dece[]" class="form-control num_val"></td>' +
            '<td><strong></strong></td>' +
            '<td class="text-right actionss">'+
              '<a class="btn btn-info btn-xs " onclick="save_shareholder('+count+','+year+',this)" data-type ="shareholder" data-id="" id="shareholder_save" title="Add" data-toggle="tooltip"><i class="fa fa-check"></i></a>'+
               // '<a class="btn btn-success btn-xs edit" id="expense_edit" title="Edit" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>'+
               ' <a class="btn btn-danger btn-xs delete" id="shareholder_delete" title="Delete" data-toggle="tooltip"><i class="fa fa-trash-o"></i></a>'+
               '</td>' +
        '</tr>';
      $("#shareholder_cont"+year).append(row);   
    $("#shareholder_cont"+year+" tr").eq(index + 1).find("#shareholder_save, #shareholder_edit").toggle();
    };


    function save_shareholder(count,year,element){
                var id = element.getAttribute('data-id');                
                var type = element.getAttribute('data-type');
                var title = $('#shareholder_title'+count+year).val();
                 if(title ==''){
                     $('#shareholder_title'+count+year).addClass("error");
                    preventDefault();
                }
                var jan = $('#shareholder_jan'+count+year).val();
                var feb = $('#shareholder_feb'+count+year).val();
                var mar = $('#shareholder_mar'+count+year).val();
                var apr = $('#shareholder_apr'+count+year).val();
                var may = $('#shareholder_may'+count+year).val();
                var jun = $('#shareholder_jun'+count+year).val();
                var jul = $('#shareholder_jul'+count+year).val();
                var aug = $('#shareholder_aug'+count+year).val();
                var sep = $('#shareholder_sep'+count+year).val();
                var oct = $('#shareholder_oct'+count+year).val();
                var nov = $('#shareholder_nov'+count+year).val();
                var dece = $('#shareholder_dece'+count+year).val();
                console.log(dece); 
        var dataString  =   {id:id,type:type,title:title,jan:jan,feb:feb,mar:mar,apr:apr,may:may,jun:jun,jul:jul,aug:aug,sep:sep,oct:oct,nov:nov,dece:dece,year:year};
        
        $.ajax({
            type: "POST",
            url: base_url+'balance_sheet/add_balance_sheet',
            data: dataString,           
            success: function(data){         
              
               window.location.href= base_url+"balance_sheet/index/"+year;             
             
        
            },
            error: function (xhr, b, c) {
                //console.log(xhr);
            }
        });   
    }

  // Edit row on edit button click
  $(document).on("click", "#shareholder_edit", function(){    
        $(this).parents("tr").find("td:not(:last-child)").each(function(){
             var title = $(this).attr('data-name');
            var num_val = (title == 'title')?'':'num_val'; 
      $(this).html('<input type="text" class="form-control '+num_val+'" value="' + $(this).text() + '" name="'+$(this).attr('data-name')+'" id="'+$(this).attr('data-id')+'">');
    });   
    $(this).parents("tr").find("#shareholder_save, #shareholder_edit").toggle();
    });
  // Delete row on delete button click
  $(document).on("click", "#shareholder_delete", function(){
        $(this).parents("tr").remove();
    });


  //color change based total asset and shareholder
   $(document).on("click", ".statement", function(){
      
      var year = $(this).data('year');
      var asset_color =  $("#asset_color_"+year).html();
      var shareholder_color = $("#shareholder_color_"+year).html();

      if(asset_color == shareholder_color){
        $("#asset_color_"+year) .css("color","#22e422");
        $("#shareholder_color_"+year) .css("color","#22e422");
      }else {
        $("#asset_color_"+year) .css("color","red");
        $("#shareholder_color_"+year) .css("color","red");
      }

    });
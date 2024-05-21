//Shift & Schedule
    
   $(document).on('change','#department',function(){
     var department = $(this).val();
     if(department!=''){
         $.post(base_url + 'shift_scheduling/dept_emp',{department:department},function(data){
            if(data){
                var full_records = JSON.parse(data);
                var html = '';
                if(full_records.length > 0){
                        
                    $( full_records ).each(function( key,val ) {
                        
                        html += '<option value="'+val.user_id+'">'+val.fullname+'</option>';
                    });
                }
                $('#employee').html(html);
            }
        });
     }else{
        $('#employee').html('<option value="">No results</option>');
     }
     
  });
   $('#total_cyclic_days').keyup(function(e) {
        var total_cyclic_days = $('#total_cyclic_days').val();
        var text = "";
        if(total_cyclic_days > 0){
        for (var i = 1; i <= total_cyclic_days; i++) {

         text += '<label class="checkbox-inline "><input type="checkbox" name="workdays[]" value="'+i+'" class="days recurring"><span class="checkmark">'+i+'</span></label>';
        
        }
        $(".cyclic_days").html(text);
        
    }
   });
  $(document).on('change','#shift_id',function(){
     var shift_id = $(this).val();
     if(shift_id!=''){
         $.post(base_url + 'shift_scheduling/get_shift_by_id',{id:shift_id},function(data){
            if(data){
                var shift_details = JSON.parse(data);
                     if(shift_details.min_start_time == '00:00:00'){
                        var min_start_time ='';
                    } else { 
                        if(shift_details.min_start_time !=''){
                            var min_start_time = shift_details.min_start_time;
                        } else{
                            var min_start_time ='';
                        }
                    }
                     if(shift_details.start_time == '00:00:00'){
                        var start_time ='';
                    } else { 
                        if(shift_details.start_time !=''){
                            var start_time = shift_details.start_time;
                        } else{
                            var start_time ='';
                        }
                    }
                     if(shift_details.max_start_time == '00:00:00'){
                        var max_start_time ='';
                    } else { 
                        if(shift_details.max_start_time !=''){
                            var max_start_time = shift_details.max_start_time;
                        } else{
                            var max_start_time ='';
                        }
                    }
                     if(shift_details.min_end_time == '00:00:00'){
                        var min_end_time ='';
                    } else { 
                        if(shift_details.min_end_time !=''){
                            var min_end_time = shift_details.min_end_time;
                        } else{
                            var min_end_time ='';
                        }
                    }
                     if(shift_details.end_time == '00:00:00'){
                        var end_time ='';
                    } else { 
                        if(shift_details.end_time !=''){
                            var end_time = shift_details.end_time;
                        } else{
                            var end_time ='';
                        }
                    }
                    if(shift_details.max_end_time == '00:00:00'){
                        var max_end_time ='';
                    } else { 
                        if(shift_details.max_end_time !=''){
                            var max_end_time = shift_details.max_end_time;
                        } else{
                            var max_end_time ='';
                        }
                    }
                    if(shift_details.break_time == '00:00:00'){
                        var break_time ='';
                    } else {
                        if(shift_details.break_time !=''){
                            var break_time = shift_details.break_time;
                        } else{
                            var break_time ='';
                        }
                    }
                   // var datepicker =  $.datepicker.formatDate('dd/mm/yy', new Date());

                   //  var today = new Date();
                   //  var dd = String(today.getDate()).padStart(2, '0');
                   //  var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                   //  var yyyy = today.getFullYear();

                   //  today = mm + '-' + dd + '-' + yyyy;
                    // document.write(today);
                  //alert($datepicker);
                    var recurrsive = (shift_details.recurring_shift == '1')?"checked":"";
                    var cyclic = (shift_details.cyclic_shift == '1')?"checked":"";
                    var indefinite = (shift_details.indefinite == '1')?"checked":"";
                    var end_date = (shift_details.end_date != '0000-00-00')?shift_details.end_date:"";
                    var end_date_edit_schedule = shift_details.end_date;
                    var start_date_edit_schedule = shift_details.start_date;

                     $('#min_start_time').val(min_start_time);   
                     $('#start_time').val(start_time);   
                     $('#max_start_time').val(max_start_time);   
                     $('#min_end_time').val(min_end_time);   
                     $('#end_time').val(end_time);   
                     $('#max_end_time').val(max_end_time);   
                     $('#break_time').val(break_time);   
                     $('.end_date').val(end_date_edit_schedule);   
                     // $('#schedule_date').val(start_date_edit_schedule);   
                     $('.shift_details').removeClass('hide');       
                     
                     if(shift_details.recurring_shift == '1' || shift_details.cyclic_shift == '1'){ 
                       $('.exist_data').remove();
                        $('.recurring').attr('disabled',false);
                        $('.checkbox').removeClass('hide');
                        if(shift_details.recurring_shift == '1'){
                            $('.checkbox').html('<label><input type="checkbox"  name="recurring_shift" id="" class="recurring mr-2" value="1" '+recurrsive+' onclick="return false;">Recurring Shift</label>');
                        } else{
                             $('.checkbox').html('<label><input type="checkbox"  name="cyclic_shift" id="" class="recurring mr-2" value="1" '+cyclic+' onclick="return false;">Cyclic Shift</label>');
                        }
                        if(shift_details.recurring_shift == '1'){
                            $('#repeat_week').val(shift_details.repeat_week);
                            
                            var wdays = shift_details.week_days;
                            var week_day = wdays.split(',');
                            var monday = '';
                            var tuesday = '';
                            var wednesday = '';
                            var thursday = '';
                            var friday = '';
                            var saturday  = '';
                            var sunday = '';
                            if($.inArray("monday", week_day) !== -1) {
                                 monday ="checked";
                            } 
                            if($.inArray("tuesday", week_day) !== -1) {
                                 tuesday ="checked";
                            } 
                            if($.inArray("wednesday", week_day)!== -1) {
                                 wednesday ="checked";
                            } 
                            if($.inArray("thursday", week_day)!== -1) {
                                 thursday ="checked";
                            } 
                            if($.inArray("friday", week_day)!== -1) {
                                 friday ="checked";
                            } 
                            if($.inArray("saturday", week_day)!== -1) {
                                 saturday ="checked";
                            } 
                            if($.inArray("sunday", week_day)!== -1) {
                                 sunday ="checked";
                            } 

                            $('.wday-box').removeClass('hide');
                            $('.wday-box').html('<label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="monday" class="days recurring" '+monday+'  onclick="return false;"><span class="checkmark">M</span></label><label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="tuesday" class="days recurring" '+tuesday+' onclick="return false;"><span class="checkmark">T</span></label><label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="wednesday" class="days recurring" '+wednesday+' onclick="return false;"><span class="checkmark">W</span></label><label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="thursday" class="days recurring" '+thursday+' onclick="return false;"><span class="checkmark">T</span></label><label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="friday" class="days recurring" '+friday+' onclick="return false;"><span class="checkmark">F</span></label><label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="saturday" class="days recurring" '+saturday+' onclick="return false;"><span class="checkmark">S</span></label><label class="checkbox-inline"><input type="checkbox" name="week_days[]" value="sunday" class="days recurring" '+sunday+' onclick="return false;"><span class="checkmark">S</span></label>');

                            $('.indefinite_checkbox').removeClass('hide');
                            $('.indefinite_checkbox').html('<label><input type="checkbox"  name="indefinite" id="indefinite" class="recurring mr-2" value="1" '+indefinite+' onclick="return false;">Indefinite Shift</label>');
                            $('.add_end_date').removeClass('hide');
                            $('.end_date').val(end_date);
                            $('#schedule_date').val(start_date_edit_schedule);
                            if(shift_details.indefinite == '1'){
                                $('.end_date').attr('disabled',true);
                            }
                             $('.total_cyclic_days').addClass('hide');
                             $('.cyclic_days').addClass('hide');
                        }else{
                             $('.repeat_week').addClass('hide');
                              $('.wday-box').addClass('hide');
                                $('.indefinite_checkbox').addClass('hide');
                                $('.add_end_date').addClass('hide');
                                $('.edit_end_date').addClass('hide');
                            
                            $('.total_cyclic_days').removeClass('hide');
                             $('.cyclic_days').removeClass('hide');
                            $('#total_cyclic_days').val(shift_details.no_of_days_in_cycle);
                            var text = "";
                            if(shift_details.no_of_days_in_cycle > 0){
                                for (var i = 1; i <= shift_details.no_of_days_in_cycle; i++) {
                                    if(shift_details.workday  >= i){
                                        var checked = "checked";
                                    }else{
                                        var checked = " ";
                                    }
                                 text += '<label class="checkbox-inline "><input type="checkbox" name="workdays[]" value="'+i+'" class="days recurring mr-2" '+checked+'><span class="checkmark"  onclick="return false;">'+i+'</span></label>';
                                
                                }
                                $(".cyclic_days").html(text);
                               
                            }
                        }
                    } else{
                        $('.total_cyclic_days').addClass('hide');
                        $('.cyclic_days').addClass('hide');
                        $('.checkbox').addClass('hide');
                        $('.repeat_week').addClass('hide');
                        $('#repeat_week').addClass('hide');
                        if(shift_details.indefinite == 1){
                            $('.indefinite_checkbox').removeClass('hide');
                            $('.indefinite_checkbox').html('<label><input type="checkbox"  name="indefinite" id="indefinite" class="mr-2" value="1" '+indefinite+' onclick="return false;">Indefinite Shift</label>');
                            $('.end_date').attr('disabled',true);
                            $('.add_end_date').addClass('hide');
                        }else{
                            $('.end_date').attr('disabled',false);
                            $('.add_end_date').removeClass('hide');
                        }

                         $('.end_date1').addClass('hide');

                        $('.wday-box').addClass('hide');
                        $('.recurring').attr('disabled',true);
                        // $('.end_date').attr('disabled',false);
                    }
                    
                
            }
        });
     }else{
        $('shift_details').addClass('hide');
     }
     
  });



  $(document).on('click','#recurring_shift,#cyclic_shift',function(){
    if($("#recurring_shift"). prop("checked") == true || $("#cyclic_shift"). prop("checked") == true){
        
        $(".recurring").attr("disabled", false);         
         if($("#recurring_shift"). prop("checked") == true){
            $("#cyclic_shift"). prop("checked",false);
            $(".recurring").attr("enabled", true);  
            $('#recurring').removeClass('hide');
            $('.recurring_end_date').removeClass('hide');            
            $('.total_cyclic_days').addClass('hide');
            $('.cyclic_days').addClass('hide');
            $('.indefinite').removeClass('hide');             
            $(".indefinite").attr("disabled", false);
         }
        else if($("#cyclic_shift"). prop("checked") == true){
            $("#recurring_shift"). prop("checked",false);
             $('#recurring').addClass('hide');            
             $('.recurring_end_date').addClass('hide');
             $('.total_cyclic_days').removeClass('hide');
             $('.cyclic_days').removeClass('hide');
           // $('.indefinite').addClass('hide');
            $('.indefinite').removeClass('hide');
           $('.recurring_end_date').removeClass('hide'); 
            $(".indefinite").attr("disabled", true);
         }
        }
        else {
            $('#recurring').addClass('hide');
            $(".recurring").attr("disabled", true);
            $('.total_cyclic_days').addClass('hide');            
             $('.cyclic_days').addClass('hide');
              $('.recurring_end_date').removeClass('hide');    
              $('.indefinite').removeClass('hide');
               $(".indefinite").attr("disabled", false);
        }
     
  });


  $(document).on('click','.days,#indefinite',function(){
        recurring_shift();
     
  });
  $(document).on('change','#end_date',function(){
     $("#indefinite").prop("checked", false);
        recurring_shift();
     
  });

  //  $(document).on('change','#end_date,#start_date',function(){

  //   var start_date = $('#start_date').val();
  //   var end_date = $('#end_date').val();
   
  //   var start = start_date.split('-');
  //   var start_date = start[1] + '/' + start[0] + '/' + start[2];
  //   var end = end_date.split('-');
  //   var end_date = end[1] + '/' + end[0] + '/' + end[2];



  //   var date1 = new Date(start_date); 
  //   var date2 = new Date(end_date); 
  //   // To calculate the time difference of two dates 
  //   var Difference_In_Time = date2.getTime() - date1.getTime(); 
      
  //   // To calculate the no. of days between two dates 
  //   var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24); 
  //     // alert(Difference_In_Time);
  //     $('#total_cyclic_days').val(Difference_In_Days);
  //   //To display the final no. of days (result) 
    
     
  // });

    
function recurring_shift(){
    var favorite = [];
    $. each($("input[name='week_days[]']:checked"), function(){
    favorite. push($(this). val());
   
    }); 

    var week_days = favorite.toString();
    week_days = week_days.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase();
    });
   //  var jsDate = $('#end_date').datepicker('getDate');
   // if (jsDate !== null) { // if any date selected in datepicker
   //      jsDate instanceof Date; // -> true
   //      var end_date = jsDate.getDate();
   //      jsDate.getMonth();
   //      jsDate.getFullYear();
   //  }
// alert(end_date);
    if($("#indefinite").is(":checked")){
        $('#end_date').attr('disabled',true);
        var end_date = '';
    }
    else {
        $('#end_date').attr('disabled',false);
        var end_date = 'until '+ $('#end_date').val();
        if(end_date ==''){
        end_date = $('#end_date').val();
    } 
    }  
    
     $('.week_days').html('<label>Summary </label> <span>Repeats every week on '+week_days+' '+end_date+'  </span>');
    console.log(favorite);

}
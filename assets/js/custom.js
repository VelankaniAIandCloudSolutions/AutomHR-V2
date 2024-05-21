$(document).ready(function () {

    // Initialize page timers
    update_timer();

    // Selects the first tab of a group (otherwise none is selected)
    // $('.nav-tabs li a').first().tab('show');

    // Resize current autoresizable textareas
    $('textarea.js-auto-size').textareaAutoSize();

    // Show/hide password on click
    $('#link-password').on('click', function () {
        if ($(this).attr('type') == 'password') {
            $(this).attr('type', 'text').blur();
        } else {
            $(this).attr('type', 'password').blur();
        }
    });
    
    // select2 
    if ($.fn.select2) {
        $(".select2-option").select2();
        $("#select2-tags").select2({
          tags:["red", "green", "blue"],
          tokenSeparators: [",", " "]}
        );
    }
    
    $('#add-translation').on('click', function () {
        var lang = $('#add-language').val();
        window.location.href = base_url+'settings/translations/add/'+lang+'/?settings=translations';
    });
    
    $('#progress').on('change',function(){ $('#progress-value').html($(this).val() + '%'); });
    $('#progress').on('input',function(){ $('#progress-value').html($(this).val() + '%'); });
    var progress_val = $('#progress').attr('value');
    $('#progress').val(progress_val);

    $('.span12').addClass('col-lg-12').removeClass('span12');
    if (!$('button').prop('type')) {
        $(this).attr('type', 'button');
    }
});

function textarea_resize(el) {
    var lines = $(el).val().split(/\r\n|\r|\n/).length;
    var height = ((lines * 34) - ((lines - 1) * 10));
    $(el).css('height', height + 'px');
}

function update_timer() {
    $('.timer').each(function () {
        var time_start = $(this).attr('start');
        var timestamp = Math.floor(Date.now() / 1000);
        var passed = timestamp - time_start;
        var seconds = "0" + passed % 60;
        var minutes = "0" + (Math.floor(passed / 60) % 60);
        var hours = Math.floor(passed / 3600);
        var formattedTime = hours + ':' + minutes.substr(minutes.length - 2);
        $(this).find('span').html(formattedTime);
    });
    if ($('.timer').length > 0) {
        setTimeout('update_timer()', 1000);
    }
}

/***************2018-05-29 *******************/ 
$(document).on('click','#new_chat',function(){
    $( ".new_chat_search" ).slideToggle( "slow", function() {});
});

$(document).on('keyup','#search_user',function(){
    var name = $(this).val();
    $.post(base_url+'chats/new_chat_user',{name:name},function(data,status){
       var newdata =  JSON.parse(data);
       var html = '<ul class="media-list media-list-linked chat-user-list">';
        if(newdata.length > 0){
            
             $.each(newdata, function(i, item) {

                html = html +'<li class="media" onclick="get_new_user('+item.user_id+');remove_current(this);email_list_active(this);chat_details('+item.user_id+')">'+
                                            '<a href="javascript:void(0)" class="media-link">'+
                                              '  <div class="media-left"><span class="avatar">'+item.fullname[0]+'</span></div>'+
                                               ' <div class="media-body media-middle text-nowrap">'+
                                                '    <div class="user-name">'+item.fullname+'</div>'+
                                                '</div>'+
                                                '<div class="media-right media-middle text-nowrap">'+
                                                '</div>'+
                                            '</a>'+
                                        '</li>';
            });
        }
        html = html +'</ul>';
       $('.new_user_list').html(html)
    });
    
});


function get_new_user(id) {
    $('#add_chat_user').modal('hide');
    $.post(base_url+'chats/new_chat_userdetails',{id:id},function(data,status){
          var newdata =  JSON.parse(data);

        $('.chat_user_lst li').removeClass('active_cls');
           $('.chat_user_lst').append('<li class="active_cls" chat_id="'+newdata.user_id+'" onclick="email_list_active(this);chat_details('+newdata.user_id+');">' +
                    '<div class="chat-user">' +
                     ' <img width="35" src="'+base_url+'assets/avatar/'+newdata.avatar+'" class="img-circle">' +
                      '                      </div>' +
                    '<div class="chat-user-info chat_usr_'+newdata.user_id+'">' +
                     '   <span class="chatter-name">'+newdata.fullname+'</span>' +
                      '  <span class="chat-last-time">' +
                       '     <i class="fa fa-clock-o"></i> <span class="usr_last_chat_date">'+newdata.lastdate+'</span>' +
                       ' </span>' +
                       ' <p class="msg-text"> ' +
                        '    <span class="chat-msg usr_last_chat_det">Chat not available</span>' +
                          '  <b class="badge bg-warning pull-right" style="display:none" id="new_chat_cnt_'+newdata.user_id+'"> 0 </b>  ' +
                        '</p>' +
                    '</div>' +
                '</li>');
    });
    
}

function remove_current(e) {
    $(e).remove();
}

function check_milestone_date(){
                var milestone = $('#mile_stone_name option:selected').val();
                // alert(milestone);
                $.post(base_url+'projects/checkmilestone_date/',{id:milestone},function(res){
                    var result = JSON.parse(res);
                    // console.log(result.start_date);
                    // console.log(JSON.parse(res));
                    $('#add_task_date_start').datepicker('setStartDate', result.start_date);
                    $('#add_task_date_start').datepicker('setEndDate', result.due_date);
                    $('#add_task_date_due').datepicker('setStartDate', result.start_date);
                    $('#add_task_date_due').datepicker('setEndDate', result.due_date);
                });
            }

/*********************************/
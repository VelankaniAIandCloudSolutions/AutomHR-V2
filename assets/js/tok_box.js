var token;
var subscribers = {};



function get_call_notification(){
  $.get(base_url+'chats/get_call_notification',function(res){
    // console.log(res); return false;
    var obj = jQuery.parseJSON(res);
    var status = obj.status;
    var obj = obj.data;
    // alert(obj.data);

    // if(obj.online_status == 0)
    // {
    //   $('.offlineStatus').css('display','');
    //   $('.onlineStatus').css('display','none');
    // }else{
    //   $('.onlineStatus').css('display','');
    //   $('.offlineStatus').css('display','none');
    // }

    if(status == true){
      /* Notification occurs */

      $('audio#ringtone').prop("currentTime", 0);
      $('audio#ringtone').trigger("play");

      $('#new_call_type').val(obj.type);
      $('#group_id').val(obj.group_id);
      $('#call_type').val(obj.call_type);
      $('.gr_tab,.group_vccontainer').removeClass('hidden');
      $('.incoming-box').css('display','block'); 


      /* Check Group call or one to one */

      if(obj.type == 'one'){ 



        if(obj.profile_pic!=''){
          var caller_img = base_url+'assets/avatar/'+obj.profile_pic;    
        }else{
          var caller_img = base_url+'assets/img/user.jpg';  
        }    

        $('#incoming_call_userpic').attr('src',caller_img);

        $('.caller_image').attr('src',caller_img);
        $('#incoming_call_username').text(obj.fullname);
        $('.caller_name').text(obj.username);
        $('.caller_login_id').val(obj.login_id);      
        $('#call_from_id').val(obj.login_id);      
        $('#call_to_id').val(obj.call_to_id);
        $('.caller_sinchusername').val(obj.username);      
        $('.caller_full_name').val(obj.username);      
        $('.caller_profile_img').val(caller_img);      
        $('#call_type').val(obj.call_type);    
        $('#group_id').val(obj.group_id);   
        $('#groupId').val(obj.group_id);   

        // $('#incoming_call').modal('show');         
                

        /* Remove old menu and prepend new user view */

        if( obj.online_status == 1){
          var online_status = 'online';
        }else{
          var online_status = 'offline';      
        }
        // $('#'+obj.sinch_username).remove();
        $('li').removeClass('active');
        var html = '<li class="active menu" id="'+obj.username+'" onclick="set_chat_user('+obj.id+')">'+
        '<a href="#"><span class="status '+online_status+'"></span>'+obj.username+ '<span class="badge bg-danger pull-right" id="'+obj.sinch_username+'danger"></span></a>'+
        '</li>';       
        $('#new_call_user').html(html);              
        // clearInterval(notify);


      }else if(obj.type == 'many'){

        var caller_img = base_url+'assets/img/user.jpg';  
        $('.caller_image').attr('src',caller_img);
        $('.caller_name').text(obj.group_name);

        $('#'+obj.group_name).remove();
        var data = '<li class="menu active"  id="'+obj.group_name+'" onclick="set_nav_bar_group_user('+obj.group_id+',this)">'+
        '<a href="#">#'+obj.group_name+ '<span class="badge bg-danger pull-right"  id="'+obj.group_name+'danger"></span></a>'+
        '</li>';
        $('#new_group_user').prepend(data);
        $('#'+obj.group_name).click();
        $('#incoming_call').modal('show'); 
        $('#groupId').val(obj.group_id);       
        clearInterval(notify);


      }         

    }else{
      $('audio#ringback').trigger("pause");
      $('audio#ringtone').trigger("pause");
      $('#incoming_call').modal('hide');

    }

  });
} 


// var notify = setInterval(get_call_notification, 5000);

function handleError(error) {
  if (error) {
    console.error(error);
  }
}


function initializeSession() {

  clearInterval(notify);

  var call_type = $('#new_call_type').val(); // one to one 0r group 
  var type_of_call = $('#call_type').val(); // audio or video 
  var to_id = $('#to_id').val(); 

  // alert(to_id);
  // return false;

  $.post(base_url+'chats/get_chat_token',{call_type:call_type,type_of_call:type_of_call,to_id:to_id},function(res){
    // alert(res); return false;
    // console.log(res); return false;
    var obj = jQuery.parseJSON(res);
    // alert(obj); exit;

    if(obj.error){
        updateNotification('','Group was deleted!','error');
        toastr.error('Group was deleted!')
        window.location.reload();
        return false;
      }   

    /* New Outgoing  Call Initiated */
    var apiKey = obj.apiKey;    
    var sessionId = obj.sessionId;   
    var token = obj.token;
    var group_id = obj.dummy_group_id;
    $('#group_id').val(obj.dummy_group_id);
    $('#groupId').val(group_id);

    var type = $('#call_type').val(); /* Audio Or Video call type */
    // alert(type);

    if(type == 'audio'){
      var publisherOptions = {      
        insertMode: 'append',
        width: '100%',
        height: '100%',      
        name: currentUserName,
        style: { nameDisplayMode: "on" },
        publishAudio:true,
        publishVideo:false
      };
      $('.phone').css('color','#55ce63'); 
      $('.vccam').addClass('active');

    }else{
      var publisherOptions = {      
        insertMode: 'append',
        width: '100%',
        height: '100%',      
        name: currentUserName,
        style: { nameDisplayMode: "on" }
      };
      $('.camera').css('color','#55ce63'); 
    }


    var session = OT.initSession(apiKey, sessionId);
    /*Initialize the publisher*/  
    var publisher = OT.initPublisher('member_tab', publisherOptions, handleError);
    $('#group_outgoing_caller_image').addClass('hidden');
    $('.vcend,.vccam').removeClass('hidden');  
    $('.menu').addClass('disabled');      
    // Connect to the session
    session.connect(token, function callback(error) {
      if (error) {
        handleError(error);
      } else {
        // If the connection is successful, publish the publisher to the session
        session.publish(publisher, handleError);
      }
    });



     // Subscribe to a newly created stream
     session.on('streamCreated', function streamCreated(event) {
      var subscriberOptions = {
        insertMode: 'append',
        width: '100%',
        height: '100%'

      };

   
      $('.test').addClass('hidden');


      var subscriber_id = 'subscriber_' + event.stream.connection.connectionId;
      subscriberHtmlBlock = '<div class="subscriber" id="' + subscriber_id + '" ></div>';
      $('#outgoing').append(subscriberHtmlBlock);
      var subscriber = session.subscribe(event.stream, subscriber_id, subscriberOptions, handleError);
      subscribers[subscriber_id] = subscriber;
       $('.outgoing-box').css('display','none');
   $('#chat_user_window').css('display','none');  
   $('#live_video_chat').css('display','table');  
     // console.log(subscribers);


     /* Video Swapping into large view */
     $('#'+subscriber_id).click(function(){  
     $('#sample').html('');       
      $('#inner_image').addClass('hidden');
      $(this).addClass('active');           
      var id = $(this).attr('id');
      var subscriberOptions = {
        insertMode: 'append',
        width: '100%',
        height: '100%',
        publishAudio:true,
        publishVideo:true 
      };
      var subscriber = session.subscribe(event.stream,'sample', subscriberOptions, handleError); 
      $('#sample').attr('data-id',id); 
      $('#sample').addClass('active'); 
    });   



        $('#sample').click(function(){
        if($(this).hasClass('active')){
        var id  = $(this).attr('data-id');                    
        $(this).removeClass('active').html('');
        $('#inner_image').removeClass('hidden');
        $(this).attr('data-id','');    
        }   
        });


   });




     session.on('sessionDisconnected', function sessionDisconnected(event) {
      console.log('You were disconnected from the session.', event.reason);
    });

     session.on("streamDestroyed", function(event) {
    window.location.reload();
});

     /* Mute the  Video */

     $('#group_video_mute').click(function(){         
      if($(this).hasClass('active')){
        $(this).removeClass('active');   
        $('.Vid-Icn-Active').css('display','');                 
        $('.Vid-Icn-InActive').css('display','none');                
        publisher.publishVideo(true);         
      }else{
        $(this).addClass('active');   
        $('.Vid-Icn-Active').css('display','none');                 
        $('.Vid-Icn-InActive').css('display','');                              
        publisher.publishVideo(false);
      }
        //console.log(stream);
      });      

     /* Reject the Call  */
     $('.vcend').click(function(event) {
      event.preventDefault();  
      session.unpublish(publisher);  
      var group_id  = $('#group_id').val();
      $.post(base_url+'chats/discard_notify',{group_id:group_id},function(res){
      window.location.reload();
    })
    });
     return false;
   });
}

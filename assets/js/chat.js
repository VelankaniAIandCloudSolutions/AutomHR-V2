// $('a#answer').click(function(event) {
$(document).on("click",'#answer',function(event){
	// alert();
	event.preventDefault();
	
	var group_id = $('#group_id').val();
	var groupid = $('#groupId').val();
	var caller_login_id = $('.caller_login_id').val();
	var caller_sinchusername = $('.caller_sinchusername').val();
	var caller_full_name = $('.caller_full_name').val();
	var caller_profile_img = $('.caller_profile_img').val();

	$('.to_name').text(caller_full_name);
	$('.receiver_title_image').attr('src',caller_profile_img);	
	$('#call_user_pic').attr('src',caller_profile_img);
	$('#call_user_name').text(caller_full_name);
	$('#receiver_sinchusername').val(caller_sinchusername);
	$('audio#ringback').trigger("pause");
	$('audio#ringtone').trigger("pause");
	// $('#incoming_call').modal('hide');
	$('.incoming-box').css('display','none');
	$('#chat_user_window').css('display','none');
	$('.outgoing-box').css('display','none');
	$('.video-blk-right').removeClass('chat-call-placeholder');
	console.log('remove class');
	$('.loading').hide();
	$('#live_video_chat').css('display','table');
	$.post(base_url+'chats/group_user_grouplist',{group_id:groupid},function(data){
        	$('.chat-user-lists').append(data);
    });

	$.post(base_url+'chats/get_dummy_datas',{group_id,group_id},function(res){

		/* New Incoming  Call  */
		var token;
		var subscribers = {};
		var obj = jQuery.parseJSON(res);
		var apiKey = obj.apiKey;    
		var sessionId = obj.sessionId;   
		var token = obj.token;
		var group_id = obj.dummy_group_id;
		var session = OT.initSession(apiKey, sessionId);

		var type = $('#call_type').val(); /* Audio Or Video call type */

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
		
		var publisher = OT.initPublisher('member_tab', publisherOptions, handleError);
		// $('.vcend,.vccam').removeClass('hidden');
		$('#group_outgoing_caller_image').addClass('hidden');
		// $('.menu').addClass('disabled');      
		clearInterval(notify);

		// var publisher = OT.initPublisher('live-video-chat', publisherOptions, handleError);
		// $('.vcend,.vccam').removeClass('hidden');
		// $('#group_outgoing_caller_image').addClass('hidden');
		// $('.menu').addClass('disabled');      
		// clearInterval(notify);

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
     	console.log(subscribers);

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
     /* Mute the  Video */

     $('#group_video_mute').click(function(){  
     // alert();       
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

     $('.vcend').click(function(event) {
     	event.preventDefault();  
     	session.unpublish(publisher);
     	// $('#incoming_call').modal('hide');  
     	$('.incoming-box').css('display','none');  
     	var group_id  = $('#group_id').val();
     	$.post(base_url+'chats/discard_notify',{group_id:group_id},function(res){     		
     		window.location.reload();
     	})
     });
 });  

});

/* candidate video chat*/

$(document).on("click",'#user_answer',function(event){
	// alert();
	event.preventDefault();
	
	var group_id = $('#group_id').val();
	var groupid = $('#groupId').val();
	var caller_login_id = $('.caller_login_id').val();
	var caller_sinchusername = $('.caller_sinchusername').val();
	var caller_full_name = $('.caller_full_name').val();
	var caller_profile_img = $('.caller_profile_img').val();

	$('.to_name').text(caller_full_name);
	$('.receiver_title_image').attr('src',caller_profile_img);	
	$('#call_user_pic').attr('src',caller_profile_img);
	$('#call_user_name').text(caller_full_name);
	$('#receiver_sinchusername').val(caller_sinchusername);
	$('audio#ringback').trigger("pause");
	$('audio#ringtone').trigger("pause");
	// $('#incoming_call').modal('hide');
	$('.incoming-box').css('display','none');
	$('#chat_user_window').css('display','none');
	$('.outgoing-box').css('display','none');
	$('.video-blk-right').removeClass('chat-call-placeholder');
	console.log('remove class');
	$('.loading').hide();
	$('#live_video_chat').css('display','table');
	$.post(base_url+'candidate_chats/group_user_grouplist',{group_id:groupid},function(data){
        	$('.chat-user-lists').append(data);
    });

	$.post(base_url+'candidate_chats/get_dummy_datas',{group_id,group_id},function(res){

		/* New Incoming  Call  */
		var token;
		var subscribers = {};
		var obj = jQuery.parseJSON(res);
		var apiKey = obj.apiKey;    
		var sessionId = obj.sessionId;   
		var token = obj.token;
		var group_id = obj.dummy_group_id;
		var session = OT.initSession(apiKey, sessionId);

		var type = $('#call_type').val(); /* Audio Or Video call type */

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
		
		var publisher = OT.initPublisher('member_tab', publisherOptions, handleError);
		// $('.vcend,.vccam').removeClass('hidden');
		$('#group_outgoing_caller_image').addClass('hidden');
		// $('.menu').addClass('disabled');      
		clearInterval(notify);
		// var publisher = OT.initPublisher('live-video-chat', publisherOptions, handleError);
		// $('.vcend,.vccam').removeClass('hidden');
		// $('#group_outgoing_caller_image').addClass('hidden');
		// $('.menu').addClass('disabled');      
		// clearInterval(notify);

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
     	console.log(subscribers);


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
     /* Mute the  Video */

     $('#group_video_mute').click(function(){  
     // alert();       
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
     $('.user_vcend').click(function(event) {
     	event.preventDefault();  
     	session.unpublish(publisher);
     	// $('#incoming_call').modal('hide');  
     	$('.incoming-box').css('display','none');  
     	var group_id  = $('#group_id').val();
     	$.post(base_url+'candidate_chats/discard_notify',{group_id:group_id},function(res){     		
     		window.location.reload();
     	})
     });

 });  

});

$('#hangup').click(function(event) {
	event.preventDefault();  	
	$('audio#ringback').trigger("pause");
	$('audio#ringtone').trigger("pause");
	$('.incoming-box').css('display','none');
	var group_id  = $('#group_id').val();
	$.post(base_url+'chats/discard_notify',{group_id:group_id},function(res){
		window.location.reload();
	});
});



$('#user_hangup').click(function(event) {
	event.preventDefault();  	
	$('audio#ringback').trigger("pause");
	$('audio#ringtone').trigger("pause");
	$('.incoming-box').css('display','none');
	var group_id  = $('#group_id').val();
	$.post(base_url+'candidate_chats/discard_notify',{group_id:group_id},function(res){
		window.location.reload();
	});
});





$('.sample').click(function(){
	$('#inner_image').removeClass('hidden');
	$(this).html('');
});


function handle_video_panel(status){
	// alert();
	if(status == 0){ /* Clicking Audio icon */		
		$('#call_type').val('audio');								

	}else{ /*Clicking Video icon */
		$('#call_type').val('video');								
	}		
	
	// if($('.group_vccontainer').hasClass('hidden')){
	// 	initializeSession();
	// 	$('.group_vccontainer,.vcend').removeClass('hidden');								
	// }else{
	// 	$('.group_vccontainer,.vcend').addClass('hidden');								
	// }

	// $('#live-video-chat').modal('show');
	$('.outgoing-box').css('display','block');
		initializeSession();
		
}


/* for candidate video call */
function handle_video_panel_job(status){
  
    if(status == 0){ /* Clicking Audio icon */      
        $('#call_type').val('audio');                               

    }else{ /*Clicking Video icon */
        $('#call_type').val('video');                               
    }       
    
    // if($('.group_vccontainer').hasClass('hidden')){
    //  initializeSession();
    //  $('.group_vccontainer,.vcend').removeClass('hidden');                               
    // }else{
    //  $('.group_vccontainer,.vcend').addClass('hidden');                              
    // }

    // $('#live-video-chat').modal('show');
    $('.outgoing-box').css('display','block');
        initializeSessionJob();
        
}

function search_user(){
	var user_name = $('#search_user').val();
	// alert('chat search');
	// return false;
	if(user_name=='')
		{
			toastr.error('Search Field is Required');
			return false;
		}else if(user_name.length < 3){
			toastr.error('Minimum 4 letters');
			return false;
		}else{
			$.post(base_url+'chats/get_users_by_name',{user_name:user_name},function(res){
			//	console.log(res); return false;
				// $('#user_list').html('');
				if(res=='[]'){
					var message = 'Invalid User';
					toastr.error(message);
				}else{
					var message= 'User Added';
					toastr.success(message);
				}
				var data = '<li>';
				if(res){
					var obj = jQuery.parseJSON(res);
					$(obj).each(function(){
						if(this.online_status == 0)
						{
							var status = 'offline';
						}else{
							var status = 'online';
						}


							data +='<a href="javascritp::void(0);" class="SingleChatList" data-name="'+this.fullname+'" data-email="'+this.email+'" data-phone="'+this.phone+'" data-online="'+status+'" data-propic="'+this.pro_pic+'" data-username="'+this.username+'" data-baseurl="'+this.base_url+'" data-lastlogin="'+this.last_login+'" data-userid="'+this.login_id+'" data-destination="'+this.designation_id+'"><span class="chat-avatar-sm user-img"><img class="rounded" src="'+this.base_url+'assets/avatar/'+this.pro_pic+'" width="32" alt="Admin"><span class="status '+status+'"></span></span><span class="member-name-blk">'+this.fullname+'</span><span class="badge bg-danger pull-right"></span></a>';

						// data +='<li class="media">'+
						// '<a href="#" class="media-link" type="text" onclick="set_chat_user('+this.login_id+', this)">'+
						// '<div class="media-left"><span class="avatar">'+this.first_letter+'</span></div>'+
						// '<div class="media-body media-middle text-nowrap">'+
						// '<div class="user-name">'+this.first_name+' '+this.last_name+'</div>'+
						// '<span class="designation">'+this.department_name+'</span>'+
						// '</div>'+
						// '<div class="media-right media-middle text-nowrap">'+			
						// '</div>'+
						// '</a>'+
						// '</li>';
					});
					data +='</li>';
					$('.UseRLisT').prepend(data);
					$('.no_chat').css('display','none');

					
					 $('.CloSE').trigger("click");
					 $('#add_chat_user').modal('toggle');
					// location.reload();

				}


			});
		}
}


$(document).ready(function(){



	$(".vcfullscreen").click(function(){
		$(".vcheader, .vcmsg, .vccolsmall, .message-bar").toggle();
		$(".vccollarge").toggleClass("vccollargefull");
		$(this).toggleClass("vcfullscreenalt");
		if($(".vccollarge").hasClass("vccollargefull")){
			$(".vccollarge").css('height',$(window).height());
		} else{
			$(".vccollarge").css('height','auto');
		}
	});

	$(".videoinner").click(function(){
		$(this).toggleClass("videoinneralt");
	});




					// $("#for_audio").hide();
					// $("#for_group_audio").hide();
					// $("#for_video").hide();
					// $("#for_group_video").hide();
					// $("#for_screen_share_group").hide();
					/* To make active group enable */
					$("#other_video_group li.active").click();
					$("#other_audio_group li.active").click();
					$("#session_video_user li.active").click();
					$("#session_audio_user li.active").click();
				});




function update_call_details(){
	var call_to_id = $('#call_to_id').val();
	var call_from_id = $('#call_from_id').val();
	var group_id = $('#group_id').val();
	var call_type = $('#call_type').val();
	var call_duration = $('#call_duration').val();
	var call_started_at = $('#call_started_at').val();
	var call_ended_at = $('#call_ended_at').val();
	var end_cause = $('#end_cause').val();



	$.post(base_url+'chats/update_call_details',
	{
		call_from_id :call_from_id,
		call_to_id :call_to_id,
		group_id :group_id,
		call_type :call_type,
		call_duration :call_duration,
		call_started_at :call_started_at,
		call_ended_at :call_ended_at,
		end_cause :end_cause,
		call_status:0
	},function(res){
		console.log(res);

		var obj = jQuery.parseJSON(res);
		/*Call History */

		var history ='';
		/*Call History for Audio */
		if(obj.call_history.length!=0){
			$(obj.call_history).each(function(){				

				var end_cause = this.end_cause;
				if(this.profile_img!=''){
					var caller_img = base_url+'uploads/'+this.profile_img;		
				}else{
					var caller_img = base_url+'assets/img/user.jpg';	
				}                     
				if(this.login_id != currentUserId){
					var caller_name = this.first_name+' '+this.last_name;	
					var receiver_name = 'You';
				}else{
					var receiver_name =  this.first_name+' '+this.last_name;
					var caller_name = 'You';                    						 		
				}
				var call_duration = this.call_duration;                    						 							 			
				var call_ended_at = this.call_ended_at;
				if(end_cause == 'HUNG_UP'){ 
					// Call from others and answered 

					history +='<div class="chat chat-left">'+
					'<div class="chat-avatar">'+
					'<a href="javascript.void(0);" class="avatar">'+
					'<img alt="'+caller_name+'" src="'+caller_img+'" class="img-fluid rounded-circle">'+
					'</a>'+
					'</div>'+
					'<div class="chat-body">'+
					'<div class="chat-bubble">'+
					'<div class="chat-content">'+
					'<span class="task-chat-user">'+caller_name+'</span>'+
					'<span class="chat-time">'+call_ended_at+'</span>'+
					'<div class="call-details">'+
					'<i class="material-icons">call_end</i>'+
					'<div class="call-info">'+
					'<div class="call-user-details">'+
					'<span class="call-description">This call has ended</span>'+
					'</div>'+
					'<div class="call-timing">Duration: <strong>'+call_duration+'</strong></div>'+
					'</div>'+
					'</div>'+
					'</div>'+
					'</div>'+
					'</div>'+
					'</div>';
				}else if(end_cause == 'DENIED'){

					history +='<div class="chat chat-left">'+
					'<div class="chat-avatar">'+
					'<a href="javascript:void(0);" class="avatar">'+
					'<img alt="'+caller_name+'" src="'+caller_img+'" class="img-fluid rounded-circle">'+
					'</a>'+
					'</div>'+
					'<div class="chat-body">'+
					'<div class="chat-bubble">'+
					'<div class="chat-content">'+
					'<span class="task-chat-user">'+caller_name+'</span>'+
					'<span class="chat-time">'+call_ended_at+'</span>'+
					'<div class="call-details">'+
					'<i class="material-icons">phone_missed</i>'+
					'<div class="call-info">'+
					'<div class="call-user-details">'+
					'<span class="call-description">'+receiver_name+' rejected call</span>'+
					'</div>'+						
					'</div>'+
					'</div>'+
					'</div>'+
					'</div>'+
					'</div>'+
					'</div>';
				}else{

					history +='<div class="chat chat-left">'+
					'<div class="chat-avatar">'+
					'<a href="javascript:void(0)" class="avatar">'+
					'<img alt="'+caller_name+'" src="'+caller_img+'" class="img-fluid rounded-circle">'+
					'</a>'+
					'</div>'+
					'<div class="chat-body">'+
					'<div class="chat-bubble">'+
					'<div class="chat-content">'+
					'<span class="task-chat-user">'+caller_name+'</span> <span class="chat-time">'+call_ended_at+'</span>'+
					'<div class="call-details">'+
					'<i class="material-icons">phone_missed</i>'+
					'<div class="call-info">'+
					'<div class="call-user-details">'+
					'<span class="call-description">'+receiver_name+'&nbsp;missed the call</span>'+
					'</div>'+						
					'</div>'+
					'</div>'+
					'</div>'+
					'</div>'+
					'</div>'+
					'</div>';
				}






			});				
			$('#call_history').prepend(history);
		}
		/*Call History */
	});
}
/*Set Current */
function set_nav_bar_chat_user(login_id,element){

	
	$('li').removeClass('active').removeClass('hidden');
	$('.edit_group_name,.delete_group_name').addClass('hidden');
	$(element).addClass('active');
	$(element).next('span').next('span').empty();
	var id = $(element).attr('id');
	$('#'+id).closest('bg-danger').empty();
	$('#'+id+'danger').empty();

	
	$('.audio_call_icon').show();
	$('#video_type').val('one');
	$('.chat_messages').html('');
	var type = $(element).attr('type');	
	// $('.group_vccontainer').addClass('hidden');

	$.post(base_url+'chats/set_chat_user',{login_id,login_id},function(res){
		// alert(res); return false;
		var obj = jQuery.parseJSON(res);
		if(obj.online_status == 1){
			var online_status = 'online';
			$('.title_status').removeClass('offline');
			$('.title_status').addClass('online');
		}else{
			var online_status = 'offline';
			$('.title_status').removeClass('online');
			$('.title_status').addClass('offline');
		}
		if(obj.profile_img != ''){
			var receiver_image = obj.profile_img;
		}else{
			var receiver_image = base_url+'assets/img/user.jpg';
		}
		$('.chat-main-row,#task_window,#chat_sidebar').removeClass('hidden');

		$('#user_list').html('');
		$('#add_chat_user').modal('hide');
		$('#search_user').val('');
		var session_type = $(element).parent().attr('id');

		$('.chat-main-row,#task_window,#chat_sidebar').removeClass('hidden');
		$("#for_screen_share_group").hide();				

		var group_type_name = type.replace(/_/g, ' ');
		var extra_add = 'Call';
		if(type == 'text_chat'){
			extra_add = '';					
			$('.to_name').text(obj.first_name+' '+obj.last_name);
		}

		$('.to_name').text(obj.first_name+' '+obj.last_name);
		$('#receiver_sinchusername').val(obj.sinch_username);
		$('#receiver_id').val(obj.login_id);
		$('#receiver_image').val(receiver_image);
		$('.receiver_title_image').attr('src',receiver_image);						
		$('.chat_messages').html(obj.messages);
		$('#type').val('text');
		// $('#group_id').val('');

		var contents = '<div class="test" >'+
		'<img src="'+receiver_image+'" title ="'+obj.first_name+' '+obj.last_name+'" class="img-fluid outgoing_image" alt="" id="image_'+obj.sinch_username+'" >'+
		'<video id="video_'+obj.sinch_username+'" autoplay unmute class="hidden"></video>'+
		'<span class="thumb-title">'+obj.first_name+' '+obj.last_name+'</span>'+
		'</div>';
		$('#member_tab').html(contents);


		$('.load-more-btn').click(function(){
			$('.load-more-btn').html('<button class="btn btn-default">Please wait . . </button>');
			var total = parseInt($(this).attr('total'));
			if(total>0){                        
				load_more(total);   
				var total = total - 1;
				$(this).attr('total',total); 
				if(total == 0){
					$('.load-more-btn').html('<button class="btn btn-default">Thats all!</button>');
				}
			}else{
				$('.load-more-btn').html('<button class="btn btn-default">Thats all!</button>');
			}

		});




	});

}


/*Set Current Active User in Chat */
function set_chat_user(login_id, element){

	var chat_user_type = $('#user_list').attr('data-type');	
	$('li').removeClass('active');	
	$('.chat_messages').html('');
	$('#video_type').val('one');
	$('.edit_group_name,.delete_group_name').addClass('hidden');
	
	$.post(base_url+'chats/set_chat_user',{login_id,login_id},function(res){
		var obj = jQuery.parseJSON(res);	

		if(obj.online_status == 1){
			var online_status = 'online';			
			$('.title_status').removeClass('offline');
			$('.title_status').addClass('online');
		}else{
			var online_status = 'offline';
			$('.title_status').addClass('offline');
			$('.title_status').removeClass('online');
		}

		if(obj.profile_img != ''){
			var receiver_image = obj.profile_img;
		}else{
			var receiver_image = base_url+'assets/img/user.jpg';
		}
		$('#'+obj.sinch_username).remove();
		var data = '<li class="active menu" id="'+obj.sinch_username+'" onclick="set_nav_bar_chat_user('+obj.login_id+',this)" type=' + chat_user_type +'>'+
		'<a href="#"><span class="status '+online_status+'"></span>'+obj.first_name+' '+obj.last_name+ '<span class="badge bg-danger pull-right" id="'+obj.sinch_username+'danger"></span></a>'+
		'</li>';
		var group_type_name = chat_user_type.replace(/_/g, ' ');
		var extra_add = 'Call';
		if(chat_user_type == 'text_chat'){
			$('#session_chat_user').prepend(data);
			$('.chat-main-row,#task_window,#chat_sidebar').removeClass('hidden');
			extra_add = '';							
		}		


		$('.to_name').text(obj.first_name+' '+obj.last_name);

		$('#user_list').html('');
		$('#add_chat_user').modal('hide');
		$('#search_user').val('');		
		$('.department').text(obj.department_name);
		$('#receiver_sinchusername').val(obj.sinch_username);
		$('#receiver_id').val(obj.login_id);
		$('#receiver_image').val(receiver_image);
		$('.receiver_title_image').attr('src',receiver_image);						
		$('.chat_messages').html(obj.messages);
		$('#type').val('text');
		// $('#group_id').val('');

		var contents = '<div class="test" >'+
		'<img src="'+receiver_image+'" title ="'+obj.first_name+' '+obj.last_name+'" class="img-fluid outgoing_image" alt="" id="image_'+obj.sinch_username+'" >'+
		'<video id="video_'+obj.sinch_username+'" autoplay unmute class="hidden"></video>'+
		'<span class="thumb-title">'+obj.first_name+' '+obj.last_name+'</span>'+
		'</div>';
		$('#member_tab').html(contents);



		$('.load-more-btn').click(function(){
			$('.load-more-btn').html('<button class="btn btn-default">Please wait . . </button>');
			var total = $(this).attr('total');
			if(total>0 || total == 0 ){                        
				load_more(total);   
				var total = total - 1;
				$(this).attr('total',total); 
			}else{
				$('.load-more-btn').html('<button class="btn btn-default">Thats all!</button>');
			}

		});




	});

}



function delete_conversation()
{

	if(confirm('Are you sure to delete this conversation?')){
		var sender_id = $('#receiver_id').val();
		var group_id = $('#group_id').val();
		$.post(base_url+'chats/delete_conversation',{sender_id:sender_id,group_id:group_id},function(response){
			if(response == 1){
				$('.chat_messages').html('<div class="no_message"></div><div class="ajax"></div><input type="hidden"  id="hidden_id">');
			}
		});
	}
}

$('.load-more-btn').click(function(){
	$('.load-more-btn').html('<button class="btn btn-default">Please wait . . </button>');
	var total = $(this).attr('total');
	if(total>0 || total == 0 ){                        
		load_more(total);   
		var total = total - 1;
		$(this).attr('total',total); 
	}else{
		$('.load-more-btn').html('<button class="btn btn-default">Thats all!</button>');
	}

});

/*Append message onclick send button */

$('#chat_form').submit(function(){
	$('.no_message').html('');
	var time = $('#time').val();
	var img = $('#img').val();
	var receiver_id = $('#receiver_id').val();

	var input_message = $.trim($('#input_message').val());
	if(input_message == ''){
		updateNotification('','Please enter message to send!','error');
		return false;
	}
	if(input_message!=''){
		var content ='<div class="chat chat-right">'+
		'<div class="chat-body">'+
		'<div class="chat-bubble">'+
		'<div class="chat-content">'+
		'<p>'+input_message+'</p>'+
		'<span class="chat-time">'+time+'</span>'+
		'</div>'+		
		'</div>'+
		'</div>'+
		'</div>';
		$('.ajax').append(content);     
		$('#input_message').val('');  
		var message_type = $('#type').val();

		var group_id = $('#group_id').val();
		message(input_message);
		$.post(base_url+'chats/insert_chat',{message:input_message,receiver_id:receiver_id,message_type:message_type,group_id:group_id},function(res){

		});                 


	}
	return false;
});




$('#audio_chat_form').submit(function(){
	$('.no_message').html('');
	var time = $('#time').val();
	var img = $('#img').val();
	var receiver_id = $('#receiver_id').val();

	var input_message = $.trim($('#input_messages').val());
	if(input_message == ''){
		updateNotification('','Please enter message to send!','error');
		return false;
	}
	if(input_message!=''){
		var content ='<div class="chat chat-right">'+
		'<div class="chat-body">'+
		'<div class="chat-bubble">'+
		'<div class="chat-content">'+
		'<p>'+input_message+'</p>'+
		'<span class="chat-time">'+time+'</span>'+
		'</div>'+		
		'</div>'+
		'</div>'+
		'</div>';
		$('.ajax').append(content);     
		$('#input_messages').val('');  
		var message_type = $('#type').val();

		var group_id = $('#group_id').val();
		message(input_message);
		$.post(base_url+'chats/insert_chat',{message:input_message,receiver_id:receiver_id,message_type:message_type,group_id:group_id},function(res){

		});                 


	}
	return false;
});



$('.attach-icon').click(function(){
	$('#user_file').click();
});


$('#user_file').change(function(e) {   
	e.preventDefault();   
					var oFile = document.getElementById("user_file").files[0]; // <input type="file" id="fileUpload" accept=".jpg,.png,.gif,.jpeg"/>
					if (oFile.size > 25097152){ // 25 mb for bytes.
						updateNotification('Warning!','File size must under 25MB!','error');
						return false;
					}
					var formData = new FormData($('#chat_form')[0]);
					$.ajax({
						url: base_url+'chats/upload_files',
						type: 'POST',
						data: formData,    
						beforeSend :function(){
							$('.progress').removeClass('hidden');
							$('.progress').css('display','block');
						},    
						success: function(res) { 
							$('.progress').addClass('hidden');               
							var obj = jQuery.parseJSON(res);
							if(obj.error){
								updateNotification('Warning!',obj.error,'error');            			
								$('#user_file').val('');
								return false;
							}      
							var to_username = $('#receiver_sinchusername').val();
							var img = $('#img').val();
							var time = $('#time').val();
							var up_file_name =obj.file_name;

							if(obj.type == 'image'){
								var file_src = '<div class="chat-img-group clearfix">'+
								'<a class="chat-img-attach" href="'+base_url+'/'+obj.img+'" target="_blank">'+
								'<img width="182" height="137" alt="" src="'+base_url+'/'+obj.img+'">'+
								'<div class="chat-placeholder">'+
								'<div class="chat-img-name">'+up_file_name+'</div>'+
								'</div>'+
								'</a>'+
								'</div>';

								var img_content = 'img-content';

							}else{
								var file_src = '<ul class="attach-list">'+
								'<li><i class="fa fa-file"></i><a href="'+base_url+'/'+obj.img+'">'+up_file_name+'</a></li>'+
								'</ul>';    	
								var img_content = '';
							}           		

							var content ='<div class="chat chat-right">'+
							'<div class="chat-body">'+
							'<div class="chat-bubble">'+
							'<div class="chat-content '+img_content+'">'+file_src+
							'<span class="chat-time">'+time+'</span>'+
							'</div>'+            		
							'</div>'+
							'</div>'+
							'</div>'+
							'</div>';            		
							$('.ajax').append(content); 
							$('#user_file').val('');

							$(".msg-list-scroll").slimscroll({ scrollBy: '400px' });

							message('file');
						},
						error: function(error){
							updateNotification('Warning!','Please try again','error'); 
						},        
						cache: false,
						contentType: false,
						processData: false

					}); 
					return false; 

				});



function load_more(total){   

	if(total==0){
		$('.load-more-btn').html('<button class="btn btn-default">Thats all!</button>');
		return false;
	}   

	var receiver_id = $('#receiver_id').val();                  

	$.post(base_url+'chats/get_old_messages',{total:total},function(res){  
		if(res){        
			$('.load-more-btn').html('<button class="btn btn-default" data-page="2"><i class="fa fa-refresh"></i> Load More</button>');               
			$('.ajax_old').prepend(res);
		}else{
			$('.load-more-btn').html('<button class="btn btn-default">Thats all!</button>');
		}
	}); 
}





/*setting Current time */
function clock() {
	var time = new Date();
	time = time.toLocaleString('en-US', { hour: 'numeric',minute:'numeric', hour12: true });
	$('#time').val(time);
	setTimeout('clock()',1000);
}
clock();

function modal_open(modal_type){
	$('#user_list').attr('data-type', modal_type);
	$('#add_chat_user').modal('show');
}


$('.userList').click(function(){
	// alert($(this).data('opid'));
	// var to_id = $(this).data('opid');
	// var to_name = $(this).data('uname');
	// $('.chatHeader').css('display','');
	// $.post(base_url+'chats/online_offline',{to_id:to_id},function(res){
	// 	if(res == 'offline')
	// 	{
	// 	  $('#offlineStatus'+to_id).css('display','');
	//       $('#onlineStatus'+to_id).css('display','none');
	//     }else{
	//       $('#onlineStatus'+to_id).css('display','');
	//       $('#offlineStatus'+to_id).css('display','none');
	//     }
	// })
	// // $('#chatter_name').text('');
	// $('#chatter_name').text(to_name);
	// $('#to_id').val(to_id);
});


// Group Call...

function add_user(){
	var group_name = $('.to_group_video').text();
	var group_id = $('#group_id').val();
	$('#add_group_user').modal('show');
	$('#group_name1').val(group_name);
	$('#group_id1').val(group_id);

}


isMobile = {
	Android: function() {
		return navigator.userAgent.match(/Android/i);
	},
	BlackBerry: function() {
		return navigator.userAgent.match(/BlackBerry/i);
	},
	iOS: function() {
		return navigator.userAgent.match(/iPhone|iPad|iPod/i);
	},
	Opera: function() {
		return navigator.userAgent.match(/Opera Mini/i);
	},
	Windows: function() {
		return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
	},
	any: function() {
		return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
	}
};

var screenShareData = null;

function set_group_type(type){
	switch(type){
		case 1:
		$('#group_type').val('text');
		break;
		case 2:
		$('#group_type').val('audio');
		break;
		case 3:
		$('#group_type').val('video');
		break;
		case 4:
		$('#group_type').val('screen_share');
		break;
	}
}


//Support functions
var getUuid = function() {
	return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
		var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
		return v.toString(16);
	});
};

var channel= getUuid();//random channel name


$('#group_user_form').submit(function(){

	var members = $('#members1').val();
	var group_id = $('#group_id').val();
	if(members == ''){
		updateNotification('Warning !','Select members!','error');
		return false
	}

	$.post(base_url+'chats/add_group_user',{group_id:group_id,members:members},function(res){
		// console.log(res); return false;
		var obj = jQuery.parseJSON(res);		
		$('#add_group_user').modal('hide');	
		$('#group_user_form')[0].reset();
		updateNotification('Success  !','New user added','success');	

		var data ='';
		var group_name = obj.group_name;
		var group_id = obj.group_id;
		if(group_name!=''){
		$('.to_name').text(group_name);
		$('#group_id').val(group_id);
		$('li').removeClass('active');
		// var content =  '<li class="active" id="'+group_name+'" onclick="set_nav_bar_group_user('+group_id+',this)" type="group_text_chat"><a href="javascript:void(0)" >#'+group_name+'<span class="badge bg-danger pull-right" id="'+group_name+'danger"></span></a></li>';
		// $('#session_group_text').prepend(content);
		}
		


		var receivers =[];
		receivers.push($('#receiver_sinchusername').val());

		$(obj.users).each(function(){
			receivers.push(this.sinch_username);
			// send_new_msg('NEW_GROUP_ADDED',this.sinch_username);

			if(this.profile_img != ''){
				var image = base_url+'assets/uploads/'+this.profile_img;
			}else{
				var image = base_url+'assets/img/user.jpg';
			}

			data +='<div class="test" >'+
			'<img src="'+image+'" title ="'+this.user_name+'" class="img-fluid outgoing_image" alt="" id="image_'+this.sinch_username+'" >'+
			'<video id="video_'+this.sinch_username+'" autoplay unmute class="hidden"></video>'+
			'<span class="thumb-title">'+this.user_name+'</span>'+
			'</div>';

		});

		$('#receiver_sinchusername').val(receivers);


		$('.group_members').prepend(data);
		// message('NEW_USER_ADDED');

	});
	return false;





});

$('#group_form').submit(function(){
	var group_name = $('#group_name').val();
	var members = $('#members').val();
	var group_type = $('#group_type').val();
	if(group_name == ''){
		updateNotification('Warning !','Enter group name to create!','error');
		return false
	}else if(members == ''){
		updateNotification('Warning !','Select members to create group!','error');
		return false
	}



	if(group_type == 'text'){
		$('#type').val('group');
	}

	$.post(base_url+'chats/create_group',{group_name:group_name,members:members,group_type:group_type,channel:channel},function(res){
		if(res){
			// console.log(res); return false; 
			var obj = jQuery.parseJSON(res);
			console.log(obj);
			if(obj.error){
				updateNotification('Warning !',obj.error,'error');			
			}else{
				$('#group_form')[0].reset();
				updateNotification('Success  !',obj.success,'success');		
				
				$('li').removeClass('active');
				$('.chat_messages').html('');
				// $('#task_window,#chat_sidebar').addClass('hidden');
				$('#add_group').modal('hide');	
				if(obj.group_type == 'text'){
					var gtype = obj.group_type;

				}else if(obj.group_type == 'audio'){
					var gtype = 'group_audio';

				}else if(obj.group_type == 'video'){
					var gtype = 'group_video';
				}
				var data = '<li class="active" id="'+obj.group_name+'" onclick="set_nav_bar_group_user('+obj.group_id+',this)" type="'+gtype+'">'+
				'<a href="#">#'+obj.group_name+ '<span class="badge bg-danger pull-right"></span></a>'+
				'</li>';
				
				if(obj.group_type == 'text'){
					$('#session_group_text').prepend(data);

				}else if(obj.group_type == 'audio'){
					$('#session_group_audio').prepend(data);

				}else if(obj.group_type == 'video'){
					$('#session_group_video').prepend(data);
				}
				var data ='';
				var receivers =[];		
				var receivers_ids =[];		
				$(obj.group_members).each(function(){
					receivers.push(this.username);
					receivers_ids.push(this.id);

					if(this.profile_img != ''){
						var image = base_url+'assets/uploads/'+this.profile_img;
					}else{
						var image = base_url+'assets/img/user.jpg';
					}

					data +='<div class="test" >'+
					'<img src="'+image+'" title ="'+this.first_name+' '+this.last_name+'" class="img-fluid outgoing_image" alt="" id="image_'+this.sinch_username+'" >'+
					'<video id="video_'+this.sinch_username+'" autoplay unmute class="hidden"></video>'+
					'<span class="thumb-title">'+this.first_name+' '+this.last_name+'</span>'+
					'</div>';

				});		
				console.log(receivers);
				console.log(receivers_ids);
				$('#receiver_chatusername').val(receivers);
				// $('#receiver_ids').val(receivers_ids);
				$('#group_members').prepend(data);
				$('.gr_tab,.add_user').removeClass('hidden');
				$('.add_user').show();
				$('.single_video,.vc_tab').addClass('hidden');
				$('.chat-main-row').removeClass('hidden');				
				$('.to_name').text(obj.group_name);
				$('.to_group_video').text(obj.group_name);
				$('.receiver_title_image').attr('src',base_url+'assets/img/user.jpg');
				$('#channel').val(channel);
				$('.chat_messages').html('<div class="ajax"></div>');
				// $('input[data-role=tagsinput]').tagsinput('removeAll');
				$('.single_video').addClass('hidden');
				// message('NEW_GROUP_ADDED');
			}
		}
	})
	return false;
});	



$('#screen_share_form').submit(function(){
	var members = $('#share_members').val();
	var group_name = $('#share_group_name').val();
	if(group_name == ''){
		updateNotification('Warning !','Enter group name to create!','error');
		return false
	}else if(members == ''){
		updateNotification('Warning !','Select members to create group!','error');
		return false
	}

	$.post(base_url+'chats/create_share',{group_name:group_name,members:members},function(res){
		if(res){
			console.log(res);
			var obj = jQuery.parseJSON(res);

			if(obj.error){
				updateNotification('Warning !',obj.error,'error');			
			}else{
				$('#group_form')[0].reset();
				updateNotification('Success  !',obj.success,'success');	

				var data = '<li class="active" data-id="'+obj.fromId+'" data-src="'+obj.url+'" data-name="'+obj.group_name+'" id="'+obj.group_name+'" onclick="set_nav_bar_share_group_user(this)">'+
				'<a href="#">#'+obj.group_name+ '<span class="badge bg-danger pull-right"></span></a>'+
				'</li>';	
				
				$('#session_screen_shrare_group').prepend(data);
				message('NEW_GROUP_ADDED');
				location.reload();

			}
			
		}
	});
	return false;
});	



function add_user(){
	var group_name = $('.to_group_video').text();
	var group_id = $('#group_id').val();
	$('#add_group_user').modal('show');
	$('#group_name1').val(group_name);
	$('#group_id1').val(group_id);

}




function set_nav_bar_group_user(group_id,element){

	$('.gr_tab').removeClass('hidden');
	$('.single_video,.vc_tab').addClass('hidden');
	$('li').removeClass('active').removeClass('hidden');
	$(element).addClass('active');
	var id = $(element).attr('id');	
	$('#'+id+'danger').empty();	
	var type = $(element).attr('type');	
	$('.add_user').show();
	$('#video_type').val('many');

	$("div[id^='for_']").hide();	
	$('#for_' + type).show();
	$('.chat_messages').html('');
	$('.chat-main-row').removeClass('hidden');
	$('#group_id').val(group_id);
	$('.receiver_title_image').attr('src',base_url+'assets/img/user.jpg');	
	$('.group_members').html('');
	$.post(base_url+'chats/get_group_datas',{group_id:group_id,type:type},function(res){
		if(res){
			var obj = jQuery.parseJSON(res);
			if(obj.group){
				var group = obj.group;
				var type = obj.group.type;
				var group_type_name =type.replace(/_/g, ' ');
				var extra_add = 'Call';
				var receivers = [];
				var receiver_id = [];					


				if(type == 'text'){
					extra_add = '';
					$('.to_name').text(group.group_name);
				}
				$('.to_' + type).text(group.group_name);
					// $('#task_window,#chat_sidebar').addClass('hidden');
					
					var group_members_thumbnail = '';;
					var i=0;			
					if( obj.group_members){
						$(obj.group_members).each(function(){
							receivers.push(this.sinch_username);
							receiver_id.push(this.login_id);					
							if(this.profile_img != ''){
								var receiver_image = imageBasePath + this.profile_img;
							}else{
								var receiver_image = defaultImageBasePath +'user.jpg';
							}
							group_members_thumbnail +='<div class="test" >'+
							'<img src="'+receiver_image+'" title ="'+this.first_name+' '+this.last_name+'" class="img-fluid outgoing_image" alt="" id="image_' + this.sinch_username +'" >'+
							'<video id="video_'+this.sinch_username+'" autoplay unmute class="hidden"></video>'+
							'<span class="thumb-title">'+this.first_name+' '+this.last_name+'</span>'+
							'</div>';
						});
						$('.group_members').html(group_members_thumbnail);
					}
				// console.log(receivers);
				$('.to_group_video_name').text(group.group_name);
				$('.to_group_video').text(group.group_name);
				$('#receiver_sinchusername').val(receivers);
				$('#receiver_id').val(receiver_id);
				$('#type').val('group');
				$('.no_message').html('');
				$('.chat_messages').html(obj.messages);

				$('.load-more-btn').click(function(){
					$('.load-more-btn').html('<button class="btn btn-default">Please wait . . </button>');
					var total = $(this).attr('total');
					if(total>0 || total == 0 ){                        
						load_more(total);   
						var total = total - 1;
						$(this).attr('total',total); 
					}else{
						$('.load-more-btn').html('<button class="btn btn-default">Thats all!</button>');
					}

				});			

			}		

			
		}
	});


}



function set_nav_bar_share_group_user(element){
	$("#group_screen_btn").attr("data-id", $(element).attr("data-id"));
	$("#group_screen_btn").attr("data-name", $(element).attr("data-name"));
	if( currentUserId != $(element).attr("data-id") ) {
		$("#group_screen_btn").attr("data-src", $(element).attr("data-src"));
		$("#group_screen_btn").html("Click to view the shared screen");
	}
	$(".screen-share-window").removeClass("hidden");
	$(".audio_call_icon").hide();
	$("#for_audio").hide();
	$("#for_group_audio").hide();
	$("#for_video").hide();
	$("#for_group_video").hide();
	$("#for_screen_share_group").show();

	$("#for_screen_share_group .chat-main-row").removeClass("hidden");
	
}


function set_screen_share_url(){
	$('.loading').show();
	var group_id = $('#group_id').val();
	var receiver_id = $('#receiver_id').val();
	$.post(base_url+'chats/request_share', function(res){
		if(res){
				// console.log(res);
				var obj = jQuery.parseJSON(res);

				screenShareData = jQuery.parseJSON(obj);

				if( screenleap.isBrowserSupportedForExtension() ) {
					console.log("Browser supported");

					screenleap.checkIsExtensionEnabled(function(){ // If Extension installed 						 
						updateNotification('','Extension enabled.', 'success');
						console.log(screenShareData);
						screenleap.startSharing('IN_BROWSER', screenShareData);

						screenleap.checkIsExtensionInstalled(
							function() {									
								$('.loading').hide();
								console.log("Extension already installed but not enabled.");
								updateNotification('',"Extension already installed but not enabled.", 'error');
							},
							function() {
								$('.loading').hide();
								console.log("Extension not installed");
								screenleap.installExtension(
									function(){
										$('.loading').hide();
										console.log('Extension installation success.');
										updateNotification('','Extension installation success.', 'success');
									}, 
									function(){
										$('.loading').hide();
										console.log('Extension installation failed.');
										updateNotification('','Extension installation failed.', 'error');
									}
									);
							}
							);



					},function(){ // If Extension not installed 	
						$('.loading').hide();									
						updateNotification('',"Screen leap Extension should be  installed to screenshare.", 'error');
					}					
					);
				}
				else {
					console.log('Browser not supported for screen leap extension.');
					updateNotification('','Browser not supported for screen leap extension.', 'error');

				}



				screenleap.onScreenShareStart = function() { 
					var code = screenleap.getScreenShareCode();
					var viewerUrl = screenleap.getViewerUrl();
					console.log('Screen is now shared using the code ' + code);
					console.log('Screen is now shared with the url ' + viewerUrl); 


					$.post(base_url+'chats/update_share',{'group_id': group_id,'receiver_id': receiver_id,'url':viewerUrl }, function(res){
						$('.loading').hide();
						updateNotification('','Screen Share started successfully!', 'success');
						var receiver_sinchusername = $('#receiver_sinchusername').val();
						var viewerUrl = screenleap.getViewerUrl();
						viewerUrl = viewerUrl.replace(/'/g, "\\'")
						var txt  = 'Click the link to see the Screen share <br> <a href="'+viewerUrl+'" data-src="'+viewerUrl+'"  target="blank">'+viewerUrl+'</a>';					
						txt = txt.link(viewerUrl);
						var time = $('#time').val();
						var content ='<div class="chat chat-right">'+
						'<div class="chat-body">'+
						'<div class="chat-bubble">'+
						'<div class="chat-content">'+
						'<p>'+txt+'</p>'+
						'<span class="chat-time">'+time+'</span>'+
						'</div>'+		
						'</div>'+
						'</div>'+
						'</div>';
						$('.ajax').append(content);
							// Create a new Message
							var receiver_sinchusername = $('#receiver_sinchusername').val();
							var receiver_sinchusername = receiver_sinchusername.split(",");
							var receiver_sinchusername = receiver_sinchusername;
							var message = messageClient.newMessage(receiver_sinchusername,txt);
						// Send it
						messageClient.send(message);
						return false;


					});


				} 

				screenleap.onScreenShareEnd = function() { 
					updateNotification('','Screen sharing has ended!', 'success');
					
				}

				screenleap.error = function(action, errorMessage, xhr) { 
					var msg = action + ': ' + errorMessage; 
					if (xhr) { 
						msg += ' (' + xhr.status + ')'; 
					} 
					console.log('Error in ' + msg); 
					updateNotification('','Error in ' + msg, 'error');

				}

				screenleap.onViewerConnect = function( screenShareData ) { 
					console.log('viewer ' + screenShareData.participantId + ' connected'); 
					updateNotification('','viewer ' + screenShareData.participantId + ' connected', 'success');
				}

				screenleap.onViewerDisconnect = function( screenShareData ) { 
					console.log('viewer ' + screenShareData.participantId + ' disconnected'); 
					updateNotification('','viewer ' + screenShareData.participantId + ' disconnected', 'success');
				} 

				screenleap.onPause = function() { 
					updateNotification('','Screen sharing paused', 'success');
					console.log('Screen sharing paused'); 
				}

				screenleap.onResume = function() { 
					updateNotification('','Screen sharing resumed', 'success');
					console.log('Screen sharing resumed'); 
				}

			}
		});
}

function redirect_url(element){
	console.log(element);
}


$('#new_timesheet_btn').click(function(){
	var project_name = $("#project_name option:selected").val();
	var user_id = $("#user_id option:selected").val();
	var timeline_hours = $('#timeline_hours').val();
	var timeline_desc = $('#timeline_desc').val();
	var timeline_date = $('#timeline_date').val();
	
	// $.validator.addMethod("hours",
 //            function(value, element) {
 //                    return /^[0-9\.\:\/]+$/.test(value);
 //            },
 //        "Please enter a valid Hours."
 //        );

	$("#add_timeline").validate({
		ignore: [],
		rules: {
			project_name: {
				required: true
			},
			timeline_date: {
				required: true
			},
			timeline_desc: {
				required: true,
				maxlength:120
			},
			timeline_hours: {
				required: true
				// hours:'hours'
			}
		},
		messages: {
			project_name: {
				required: "Project name must not empty"
			},
			timeline_date: {
				required: "Date is required"
			},
			timeline_hours: {
				required: "Hours field is required"
			},
			timeline_desc: {
				required: "Description must not empty"
			}
		},
		submitHandler: function(form) {
			$.post(base_url+'time_sheets/add_timesheet',{project_name : project_name,user_id : user_id, timeline_hours : timeline_hours, timeline_desc : timeline_desc ,timeline_date : timeline_date}, function(res){
			//	console.log(res); return false;
				if(res == 'success')
				{
						toastr.success('Timesheet Added');
					// setInterval(function(){ 
						// location.reload();
					// }, 1500);
					location.reload();
				}else if(res == 'hoursless'){ 
					$('.Error-Hours').css('display','');
					// console.log(res);
					return false;
				}else if(res == 'error_daily'){
					$('.Error-Hours').css('display','none');
					$('.Error-Hours-Exist').css('display','');
					// console.log(res); 
					return false;
				}
			});
			return false;
		}
		
	   });

	
});


$(document).on('keyup','.workTimelineHour',function(){
	if($(this).val() == '')
	{
		$('label#timeline_hours_error').removeClass('display-block').addClass('display-none');
		$('label#timeline_hours_required').removeClass('display-none').addClass('display-block');
		return false;
	}
	
	if($(this).val().trim() != '')
	{
		$('label#timeline_hours_required').removeClass('display-block').addClass('display-none');
		if(/^[0-9\:\/]+$/.test($(this).val()))
		{
			$('label#timeline_hours_error').removeClass('display-block').addClass('display-none');
			return true;
		}
		else
		{
			$('label#timeline_hours_error').removeClass('display-none').addClass('display-block');
			return false;
		}
	}
})

$(document).on('keyup','.workTimelineDesc',function(){
	if($(this).val().trim() == '')
	{
		console.log('false')
		$('label#timeline_desc_error').removeClass('display-none').addClass('display-block');
		return false;
		
	}
	else
	{
		$('label#timeline_desc_error').removeClass('display-block').addClass('display-none');
		return true;
	}
})

$('.edit_timesheet_btn').click(function(){
	var user_id = $(this).data('editid');
	var project_name = $("#project_name"+user_id+" option:selected").val();
	var timeline_hours = $('#timeline_hours'+user_id).val();
	// alert(timeline_hours); return false;
	var timeline_desc = $('#timeline_desc'+user_id).val();
	var timeline_date = $('#timeline_date'+user_id).val();

	// console.log(user_id);
	// console.log(project_name);
	// console.log(timeline_hours);
	// console.log(timeline_desc);
	// console.log(timeline_date);

	function projectWorkEdit() {
		if(project_name.trim() != '')
		{
			$('label#project_name_required').removeClass('display-block').addClass('display-none');
			return true;
		}
		else
		{
			$('label#project_name_required').removeClass('display-none').addClass('display-block');
			//return false;
		}
	}
	   
	function hoursWorkEdit() {
		if(timeline_hours == '')
		{
			$('label#timeline_hours_error').removeClass('display-block').addClass('display-none');
			$('label#timeline_hours_required').removeClass('display-none').addClass('display-block');
			return false;
		}
		
		if(timeline_hours.trim() != '')
		{
			$('label#timeline_hours_required').removeClass('display-block').addClass('display-none');
			if(/^[0-9\:\/]+$/.test(timeline_hours))
			{
				$('label#timeline_hours_error').removeClass('display-block').addClass('display-none');
				return true;
			}
			else
			{
				$('label#timeline_hours_error').removeClass('display-none').addClass('display-block');
				return false;
			}
		}
		
	}
	   
	function descriptionWorkEdit() {
		if(timeline_desc.trim() == '')
		{
			console.log('false')
			$('label#timeline_desc_error').removeClass('display-none').addClass('display-block');
			return false;
			
		}
		else
		{
			$('label#timeline_desc_error').removeClass('display-block').addClass('display-none');
			return true;
		}
	}
	
	function dateWorkEdit() {
		if(timeline_date.trim() != '')
		{
			$('label#timeline_date_required').removeClass('display-block').addClass('display-none');
			return true;
		}
		else
		{
			$('label#timeline_date_required').removeClass('display-none').addClass('display-block');
			return false;
		}		
	   }
	   
	if(projectWorkEdit() == true && hoursWorkEdit() == true && descriptionWorkEdit() == true && dateWorkEdit() == true)
	{
	$.post(base_url+'time_sheets/edit_timesheet',{edit_id: user_id, project_name : project_name, timeline_hours : timeline_hours, timeline_desc : timeline_desc ,timeline_date : timeline_date}, function(res){
		if(res == 'success')
		{
						toastr.success('Timesheet Updated');
			setInterval(function(){ 
						location.reload();
					}, 1500);
		}else if(res == 'hoursless'){
			$('.Error-Hours-edit').css('display','');
			// console.log(res);
			return false;
		}else if(res == 'error_daily'){
			$('.Error-Hours-edit').css('display','none');
			$('.Error-Hours-Exist-edit').css('display','');
			// console.log(res); 
			return false;
		}
	});
	}
	else
	{
		return false;
	}

});


$('.Delete-Timeline').click(function(){
	var time_id = $(this).data('timeid');
	$.post(base_url+'time_sheets/delete_timesheet',{time_id: time_id}, function(res){
		// alert("Deleted");
			toastr.success('Timesheet Deleted');
		setInterval(function(){ 
			location.reload();
		}, 1500);
	});
	return false;
});

// $(document).ready(function(){
// 	$('#example').DataTable();
// });
if ($('.datetimepicker').length > 0) {
	$('.datepicker').datepicker({
		'autoclose':true
	});
}



function date_search_timesheet()
{
	var start_date = $('#timesheet_date_from').val();
	var end_date = $('#timesheet_date_to').val();
	var search_timesheet = 'searching';
	// console.log(end_date);
	$.post(base_url+'time_sheets/',{start_date: start_date,end_date:end_date,search:search_timesheet}, function(res){
		alert("Deleted");
		// location.reload();
	});
}



$('.New-Leave').click(function(){
	var login_id = $(this).data('loginid');
	$.post(base_url+'leaves/leave_check',{login_id: login_id}, function(res){
		var obj = JSON.parse(res);
		console.log(obj);
		// console.log(obj.normal_leaves);

		$('#normal_leaves').text(obj.normal_leaves);
		$('#medical_leaves').text(obj.medical_leaves);
		$('#sick_leaves').text(obj.sick_leaves);
		$('#total_leaves').text(obj.total_leaves);
		$('#loss_pay').text(obj.loss_pay);
		// if(obj.total_leaves > 18){
	 //        $('#lop_call').css('display','');
	 //    }
	 //    var current_leave = $('#req_leave_count').val();

	 //    var leave = obj.total_leaves + current_leave;
	 //    if(leave > 18 ){
	 //        $('#lop_call').css('display','');
	 //    }

		// location.reload();
	});
});


		


    $(function() {
	  $('#user_count').on('keydown',function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110])||(/65|67|86|88/.test(e.keyCode)&&(e.ctrlKey===true||e.metaKey===true))&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
	  $('#user_count').keyup(function(){
	  	var user_count = $(this).val();
	  	if((user_count != '') & (user_count != 0))
	  	{	
		  	var sub_amount = parseInt(user_count) + parseInt(10);
		  	$('#sub_amount').val('$'+sub_amount);
		  	$('#stripeamountsub').val(sub_amount);
	  	}else{
		  	$('#sub_amount').val('');
		  	$('#stripeamountsub').val('');
	  	}
	  });


	  $('.Team-Leader').change(function(){
	  	var check_leader = $('.Team-Leader:checked').val();
	  	if(check_leader == 'yes')
	  	{
	  		$('.TeamDiv').css('display','none');
	  	}else{
	  		$('.TeamDiv').css('display','');
	  	}
	  });

	  $('#designations').change(function(e){
	  	var dept_id = $('#department_name').val();
	  	var des_id = $(this).val();
	  	$.post(base_url+'employees/teamlead_options/',{des_id:des_id,dept_id:dept_id},function(res){
	  		// console.log(res); return false;
	  		var leads_name = JSON.parse(res);
			$('#reporting_to').empty();
		    $('#reporting_to').append("<option value='' selected disabled='disabled'>Reporter's Name</option>");
			for(i=0; i<leads_name.length; i++) {
		    	$('#reporting_to').append("<option value="+leads_name[i].id+">"+leads_name[i].username+"</option>");                      
		     }
		  	});
	  });


	  // $('.clone_invoice_btn').click(function(){
	  // 	var invoice_id = $(this).data('invoice');
	  // 	// alert(invoice_id);
	  // 	$.post(base_url+'invoices/invoice_cloning/',{invoice_id:invoice_id},function(res){
	  // 		console.log(res); return false;
	  // 	});
	  // 	return false;
	  // });

	});
	// New chat Design Script...
	// $('.SingleChatList').click(function(){
	// Delete Msg from chat History....
		
	$(function(){           

	 	$('.overlay').on('click', function () {
	        $('#profile-info-collapse').removeClass('active');
	    });
        // $('#profileCollapse').on('click', function () {
        // $(document).on("click",'#profileCollapse',function(){
            // $('#profile-info-collapse').addClass('active');
            // $('.collapse.in').toggleClass('in');
            // $('a[aria-expanded=true]').attr('aria-expanded', 'false');
        // });
	});
	
    function profileinfo()
    {
        $('#profile-info-collapse').addClass('active');
    }

    function dismiss_close()
    {
        $('#profile-info-collapse').removeClass('active');
    }

 	$(document).ready(function () {
        $(".chat-box-img").click(function(){
			$(".chat-icon").hide(500);
		});
		$("#close-hide").click(function(){
			$(".chat-blk").collapse("hide").slow;
			$('.chat-icon').show(500);
		});
		$(".chat-show-hide").click(function(){
			$(".chat-content").show();
			$(".chat-show-hide").hide();
		});
		$(".btn-chat-close").click(function(){
			$(".chat-content").hide();
			$(".chat-show-hide").show();
		});
		$('.fa-angle-double-right').click(function () {
            $(this).toggleClass('fa-angle-double-right fa-angle-double-left');
        });
		$('.user-list-icon').on('click', function(event) {        
			 $('.chat-user-list').toggle('show');
		});

        // $('.fullscreen-fa').click(function(){
        $(document).on("click",'.fullscreen-fa',function(){
		  $('.modal-open').toggleClass('fullscreen-modal');
		});   
    });
  // Below code get from uniscript  
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
                $('.ChatHistory').css('display','');
                $('.ChatHistoryGroup').css('display','none');
                        $('.ChatHistory').html('');
                        $('.ChatHistory').append(res);
                    });
            });

        $('.GroupChatLists').on("click",function(){
                $('.video-blk-right').removeClass('chat-call-placeholder');
                $('.chats').removeClass('ChatHistory');
                $('.chats').addClass('ChatHistoryGroup');
                 $('#to_id').val('');
                
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


                    $('#side_profile_name').text(group_name);
                    $('#side_profile_destination').text('Group Name');
                    $('.user-det-list-grp').html('<b style="margin-bottom:10px;display:block;">Group Members <a href="'+base_url+'chats/edit_group/'+group_id+'" data-toggle="ajaxModal" ><i class="fa fa-plus"></i></a></b>');
                     $('.user-det-list-grp').css('display','');

                    $('.user-det-list-grp').append(data);
                    $('.user-det-list').css('display','none');
                   
          
            
            
                    //$('.chat-user-lists').append(data); 
                    // console.log(data); return false;
                });
                $.post(base_url+'chats/grouplist_receiver',{group_id:group_id},function(result){
                    // console.log(result);
                    $('#receiver_ids').val(result);
                });
                 $('#LastseenUserChat').css('display','none');
                 $('.ChatHistory').css('display','none');
                  

                  $('.ChatHistoryGroup').css('display','');
                        $('.ChatHistoryGroup').html('');
                        $('.ChatHistoryGroup').append(res);
                        $('#group_image').val(1);
                    });
            });

        $(document).on("click",'.SingleChatList',function(){
            $('.chats').addClass('ChatHistory');
             $('.chats').removeClass('ChatHistoryGroup');
            
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
                    $('#LastseenUserChat').text('Online');
            }else{
                    $('#user_status_chat').removeClass('online');
                    $('#MsgviewStatus').removeClass('online');
                    $('#user_status_chat').addClass(status);
                    $('#MsgviewStatus').addClass(status);
                    $('#LastseenUserChat').text('Last seen '+lastlogin);
            }
            $('#chat_username').text(username);
            $('#user_email_chat').text(email);
            $('#user_phone_chat').text(phone);
            
            // alert(lastlogin);
            // var d = Date('yy-mm-dd',lastlogin);
            // d.setDate(d.getDate() - 1);
            $.post(base_url+'chats/chat_single_history',{user_id:user_id},function(res){
                $('.grp-profile-details').css('display','none');
                $('.single-profile-details').css('display','');
                // console.log(res);
                $('.ChatHistory').css('display','');
                $('.ChatHistoryGroup').css('display','none');
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
             $('.user-det-list-grp').css('display','none');
                    $('.user-det-list').css('display','');



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
                         $('.ChatHistory').css('display','');
                         $('.ChatHistoryGroup').css('display','none');
                        $('.ChatHistory').append(res);
                    });
                });
            }else{
                return false;
            }
        });
        /* $(document).on("click",'.EditChatmsg',function(){
             var msg_content = $(this).data('message');
             var msg_id = $(this).data('msgid');
             var user_id = $(this).data('userid');
             var append = '<input type="text" name="e_message" value="'+msg_content+'"> <a href="#" class="edit_save"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>'; 
             $('.content_'+msg_id).html('');
             $('.content_'+msg_id).append(append);
           
        });*/

        $(document).on("click",'.DeleteChatMsgggroup',function(){
            var res = getConfirmation();
            if(res == true)
            {
                var msg_id = $(this).data('msgid');
                var group_id = $(this).data('grpid');
                $.post(base_url+'chats/chat_single_delete',{msg_id:msg_id,group_id:group_id},function(res){
                    toastr.success('Message Deleted');
                    $.post(base_url+'chats/group_chat_userlist',{group_id:group_id},function(res){
                        $('.ChatHistoryGroup').html('');
                        $('.ChatHistory').css('display','none');
                        $('.ChatHistoryGroup').css('display','');
                        $('.ChatHistoryGroup').append(res);
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
	
	
	$(document).ready(function() {

   
    $(".file-upload").on('change', function(){
    var i =0;   // readURL(this);
     if (i == 0) {
    i = 1;
    var elem = document.getElementById("progress-bar");
    var width = 1;
    var id = setInterval(frame, 10);
    function frame() {
      if (width >= 100) {
        clearInterval(id);
        i = 0;
      } else {
        width++;
        elem.style.width = width + "%";
      }
    }
  }
         
    });
    
    // $(".upload-button").on('click', function() {
    //    $(".file-upload").click();
    // });
});

/*images*/
$(document).on("click",'#sendImages12',function(){

       var to_id = $('#to_id').val();
  
  if(to_id != '')
  {    
    
    var msg_type = 'one';
    $.post(base_url+'chats/get_message_token',{to_id:to_id,msg_type:msg_type},function(res){
        // console.log(res); return false;

       var obj = jQuery.parseJSON(res);

        if(obj.error){
            console.log(obj.error);
            return false;
          }

        /* New Outgoing  message Initiated */
//         var file_data = $('#chatimageform').prop('files');
//        // var file_data = $('#chatimages')[0].files[0];

   // var ele = document.getElementById('chatimages');
   // var formData = ele.files;

   var form_data = new FormData();

   // Read selected files
   var totalfiles = document.getElementById('chatimages').files.length;
 //  alert(totalfiles);
 var images_meg = '';
   for (var index = 0; index < totalfiles; index++) {
   // alert(document.getElementById('chatimages').files[index]);
   var filename =document.getElementById('chatimages').files[index]['name'];
         var extension = filename.split('.').pop().toLowerCase();
         if(extension == 'png' || extension == 'jpeg' || extension == 'jpg' || extension == 'gif'){

    images_meg += '<img src="'+base_url+'assets/uploads/chats/'+document.getElementById('chatimages').files[index]['name']+'" width="100" height="100">';
    }else{
        images_meg += '<a href="'+base_url+'assets/uploads/chats/'+document.getElementById('chatimages').files[index]['name']+'">'+filename+'</a><br>';
        }
  
      form_data.append("files[]", document.getElementById('chatimages').files[index]);
   }
   // alert(images_meg);

    var msgHistory = document.querySelector('#history');
               
                  var msge = '';
                  var from_id = obj.login_id;
                 // alert(from_id);
                  var msg_typ = 'one';
                  var grp_id = $('#hid_group_id').val();
                  var connection_id = '';
                  form_data.append('msge',msge);
                  form_data.append('from_id',from_id);
                  form_data.append('msg_typ',msg_typ);
                  form_data.append('group_id',grp_id);
                  form_data.append('to_id',to_id);
                  form_data.append('connection_id',connection_id);
                //  console.log(form_data);
                 // alert(form_data);

                  $.ajax({
                        type: "POST",
                        url: base_url + 'chats/message_saving/',
                        data: form_data,
                        contentType: false,
                        processData: false,
                        success: function (res) {
                        
                        var cur_time = chattimeNow();
                        // console.log(res); return false;
                        // var msg = document.createElement('p');
                        if(from_id == obj.login_id){
                        var cls_name = 'chat-right';
                        }else{
                        var cls_name = 'chat-left';
                        }
                        var msg ='<div class="chat '+cls_name+'"><!--<div class="chat-avatar"><a title="John Doe" data-placement="right" data-toggle="tooltip" class="avatar"><img alt="John Doe" src="images/user.jpg" class="img-fluid rounded-circle"></a></div>--><div class="chat-body"><div class="chat-bubble"><div class="chat-content"><p>'+images_meg+'</p><span class="chat-time">'+cur_time+'</span></div><div class="chat-action-btns"><ul><li><a href="#" class="del-msg DeleteChatMsgggroup" data-msgid="'+res+'" data-grpid="'+grp_id+'" title="Delete"><i class="fa fa-trash-o"></i></a></li></ul></div></div></div></div>';
                           $('#drag_files').modal('toggle');

                        $('.ChatHistory').append(msg);
                        $('#msgTxt').val('');
							var elem = document.getElementById("progress-bar");
							elem.style.width = "0%";
							$('#chatimages').val('');
                        }
                    });



                 /* $.post(base_url+'chats/message_saving/',{from_id:from_id,to_id:to_id,msge:msge,connection_id:connection_id,msg_typ:msg_typ,form_data:form_data},function(res){
                    alert(2);
                    // console.log(res); return false;
                    var cur_time = chattimeNow();
                    // console.log(res); return false;
                    // var msg = document.createElement('p');
                    if(from_id == obj.login_id){
                      var cls_name = 'chat-right';
                    }else{
                      var cls_name = 'chat-left';
                    }
                    var msg ='<div class="chat '+cls_name+'"><!--<div class="chat-avatar"><a title="John Doe" data-placement="right" data-toggle="tooltip" class="avatar"><img alt="John Doe" src="images/user.jpg" class="img-fluid rounded-circle"></a></div>--><div class="chat-body"><div class="chat-bubble"><div class="chat-content"><p>'+msge+'</p><span class="chat-time">'+cur_time+'</span></div><div class="chat-action-btns"><ul><li><a href="#" class="del-msg DeleteChatMsgggroup" data-msgid="'+res+'" data-grpid="'+grp_id+'" title="Delete"><i class="fa fa-trash-o"></i></a></li></ul></div></div></div></div>';
                    $('.ChatHistory').append(msg);
                    $('#msgTxt').value('');
                  });*/
                  

            });
          
  }else{
    //alert(889965);

    var group_members_ids = $('#receiver_ids').val();
    if(group_members_ids != '')
    {
      var result = group_members_ids.split(',');
      var msg_type = 'group';
    
      

      // Group Chat...
      $.post(base_url+'chats/get_message_token',{to_id:to_id,msg_type:msg_type},function(res){
        // console.log(res); return false;
       var obj = jQuery.parseJSON(res);

        if(obj.error){
            // updateNotification('','Group was deleted!','error');
            // window.location.reload();
            console.log(obj.error);
            return false;
          }

        /* New  image message Initiated */


        var form_data = new FormData();

        // Read selected files
        var totalfiles = document.getElementById('chatimages').files.length;
        // alert(totalfiles);
        var images_meg = '';
        for (var index = 0; index < totalfiles; index++) {
         var filename =document.getElementById('chatimages').files[index]['name'];
         var extension = filename.split('.').pop().toLowerCase();
         if(extension == 'png' || extension == 'jpeg' || extension == 'jpg' || extension == 'gif'){

        // alert(document.getElementById('chatimages').files[index]);
        images_meg += '<img src="'+base_url+'assets/uploads/chats/'+document.getElementById('chatimages').files[index]['name']+'" width="100" height="100">';
        }else{
        images_meg += '<a href="'+base_url+'assets/uploads/chats/'+document.getElementById('chatimages').files[index]['name']+'">'+filename+'</a><br>';
        }

        form_data.append("files[]", document.getElementById('chatimages').files[index]);
        }
   //alert(images_meg);

        var msgHistory = document.querySelector('#history');
               
                  var msge = '';
                  var from_id = obj.login_id;
                  var msg_typ = 'group';
                  var grp_id = $('#hid_group_id').val();
                  var connection_id = '';
                  var to_id = '';

                  form_data.append('msge',msge);
                  form_data.append('from_id',from_id);
                  form_data.append('msg_typ',msg_typ);
                  form_data.append('group_id',grp_id);
                  form_data.append('to_id',to_id);
                  form_data.append('connection_id',connection_id);
                  form_data.append('group_members_ids',group_members_ids);
                //  console.log(form_data);,group_members_ids:group_members_ids
                 // alert(form_data);

                  $.ajax({
                        type: "POST",
                        url: base_url + 'chats/message_saving/',
                        data: form_data,
                        contentType: false,
                        processData: false,
                        success: function (res) {
                        
                        var cur_time = chattimeNow();
                        // console.log(res); return false;
                        // var msg = document.createElement('p');
                        if(from_id == obj.login_id){
                        var cls_name = 'chat-right';
                        }else{
                        var cls_name = 'chat-left';
                        }
                        var msg ='<div class="chat '+cls_name+'"><!--<div class="chat-avatar"><a title="John Doe" data-placement="right" data-toggle="tooltip" class="avatar"><img alt="John Doe" src="images/user.jpg" class="img-fluid rounded-circle"></a></div>--><div class="chat-body"><div class="chat-bubble"><div class="chat-content"><p>'+images_meg+'</p><span class="chat-time">'+cur_time+'</span></div><div class="chat-action-btns"><ul><li><a href="#" class="del-msg DeleteChatMsgggroup" data-msgid="'+res+'" data-grpid="'+grp_id+'" title="Delete"><i class="fa fa-trash-o"></i></a></li></ul></div></div></div></div>';
                           $('#drag_files').modal('toggle');

                        //$('.ChatHistory').append(msg);
                        $('.ChatHistory').css('display','none');
                         $('.ChatHistoryGroup').last().append(msg);
                        $('#msgTxt').val('');

                        }
                    });
            

   
              // If the connection is successful, initialize a publisher and publish to the session
              
                  /*var msge = msgTxt.value;
                  var from_id = obj.login_id;
                  var msg_typ = 'group';
                  var grp_id = $('#hid_group_id').val();
                  var connection_id = session.connection.connectionId;
                  $.post(base_url+'chats/message_saving/',{from_id:from_id,to_id:to_id,msge:msge,connection_id:connection_id,msg_typ:msg_typ,group_id:grp_id},function(res){
                    var cur_time = chattimeNow();
                    // console.log(res); return false;
                    // var msg = document.createElement('p');
                    if(event.from.connectionId == session.connection.connectionId){
                      var cls_name = 'chat-right';
                    }else{
                      var cls_name = 'chat-left';
                    }
                    var msg = '<div class="chat '+cls_name+'"><div class="chat-body"><div class="chat-bubble"><div class="chat-content"><p>'+event.data+'</p><span class="chat-time">'+cur_time+'</span></div><div class="chat-action-btns"><ul><li><a href="#" class="del-msg DeleteChatMsgggroup" data-msgid="'+res+'" data-grpid="'+grp_id+'" title="Delete"><i class="fa fa-trash-o"></i></a></li></ul></div></div></div></div>';


                    // msg.textContent = event.data;
                    // msg.className = event.from.connectionId === session.connection.connectionId ? 'mine' : 'theirs';
                    // msg.className = event.from.connectionId === session.connection.connectionId ? 'chat chat-right' : 'chat chat-left';
                    // $('.ChatHistory').appendChild(msg);
                    // $( "li" ).last()
                    $('.ChatHistory .chat').last().append(msg);
                    // msg.scrollIntoView();
                  });
                  console.log('from '+event.from.connectionId);
                  console.log('to '+session.connection.connectionId);*/
                });

              

      
      
    }


  }

   
});

/*text message*/
 $(document).on("click",'#sendMessage',function(){
    send_message();
});

 
 $(document).on("click",'#create_group_btn1',function(){
    $('#create_group_form1').submit();
});

 function send_message(){

    var msgTxt = document.querySelector('#msgTxt');
 event.preventDefault();
 // alert(msgTxt.value);
  if(msgTxt.value !=  '')
  {
       var to_id = $('#to_id').val();
  
  if(to_id != '')
  {
    var msg_type = 'one';
    $.post(base_url+'chats/get_message_token',{to_id:to_id,msg_type:msg_type},function(res){
        // console.log(res); return false;
       var obj = jQuery.parseJSON(res);

        if(obj.error){
            console.log(obj.error);
            return false;
          }

        /* New Outgoing  message Initiated */
        


                var msgHistory = document.querySelector('#history');
               
                  var msge = msgTxt.value;
                  var from_id = obj.login_id;
                  var msg_typ = 'one';
                  var grp_id = $('#hid_group_id').val();
                  var connection_id = '';
                  $.post(base_url+'chats/message_saving/',{from_id:from_id,to_id:to_id,msge:msge,connection_id:connection_id,msg_typ:msg_typ},function(res){
                    // console.log(res); return false;
                    var cur_time = chattimeNow();
                    // console.log(res); return false;
                    // var msg = document.createElement('p');
                    if(from_id == obj.login_id){
                      var cls_name = 'chat-right';
                    }else{
                      var cls_name = 'chat-left';
                    }
                    var msg ='<div class="chat '+cls_name+'"><!--<div class="chat-avatar"><a title="John Doe" data-placement="right" data-toggle="tooltip" class="avatar"><img alt="John Doe" src="images/user.jpg" class="img-fluid rounded-circle"></a></div>--><div class="chat-body"><div class="chat-bubble"><div class="chat-content"><p>'+msge+'</p><span class="chat-time">'+cur_time+'</span></div><div class="chat-action-btns"><ul><li><a href="#" class="del-msg DeleteChatMsgggroup" data-msgid="'+res+'" data-grpid="'+grp_id+'" title="Delete"><i class="fa fa-trash-o"></i></a></li></ul></div></div></div></div>';
                    $('.ChatHistory').css('display','');
                $('.ChatHistoryGroup').css('display','none');
                    $('.ChatHistory').append(msg);
                    $('#msgTxt').val('');
                  });
                  

            });
          
  }else{
    //alert(to_id);
    var group_members_ids = $('#receiver_ids').val();
   // alert(group_members_ids);
    if(group_members_ids != '')
    {
      var result = group_members_ids.split(',');
      var msg_type = 'group';
    //  $.each(result, function(propName, to_id) {
      //   console.log(propVal);
      // });
      // return false;

      // Group Chat...
      $.post(base_url+'chats/get_message_token',{to_id:to_id,msg_type:msg_type},function(res){
        // console.log(res); return false;
       var obj = jQuery.parseJSON(res);

        if(obj.error){
            // updateNotification('','Group was deleted!','error');
            // window.location.reload();
            console.log(obj.error);
            return false;
          }

        /* New Outgoing  message Initiated */
        
     // var msgHistory = document.querySelector('.ChatHistory');
                  var to_id = '';
                  var msge = msgTxt.value;
                  var from_id = obj.login_id;
                  var msg_typ = 'group';
                  var grp_id = $('#hid_group_id').val();
                  var connection_id = '';
                  $.post(base_url+'chats/message_saving/',{from_id:from_id,to_id:to_id,msge:msge,connection_id:connection_id,msg_typ:msg_typ,group_id:grp_id,group_members_ids:group_members_ids},function(res){
                    var cur_time = chattimeNow();
                    // console.log(res); return false;
                    // var msg = document.createElement('p');
                    if(obj.login_id == from_id){
                      var cls_name = 'chat-right';
                    }else{
                      var cls_name = 'chat-left';
                    }
                    var msg = '<div class="chat '+cls_name+'"><div class="chat-body"><div class="chat-bubble"><div class="chat-content"><p>'+msge+'</p><span class="chat-time text-info">'+obj.username+'</span><span class="chat-time">'+cur_time+'</span></div><div class="chat-action-btns"><ul><li><a href="#" class="del-msg DeleteChatMsgggroup" data-msgid="'+res+'" data-grpid="'+grp_id+'" title="Delete"><i class="fa fa-trash-o"></i></a></li></ul></div></div></div></div>';


                    // msg.textContent = event.data;
                    // msg.className = event.from.connectionId === session.connection.connectionId ? 'mine' : 'theirs';
                    // msg.className = event.from.connectionId === session.connection.connectionId ? 'chat chat-right' : 'chat chat-left';
                    // $('.ChatHistory').appendChild(msg);
                    // $( "li" ).last()
                    $('.ChatHistory').css('display','none');
                     $('.ChatHistoryGroup').css('display','');
                    $('.ChatHistoryGroup').last().append(msg);
                    $('#msgTxt').val('');
                    // msg.scrollIntoView();
                  });
                
              

      });
      
    

  

  }else{
    return false;
  }
 }
}
}

function check_chat_messages()
{
   $.get(base_url+'chats/get_chat_msg_notification',function(res){
      console.log(res);
   });
}


function chattimeNow() {
  var d = new Date(),
    h = (d.getHours()<10?'0':'') + d.getHours(),
    m = (d.getMinutes()<10?'0':'') + d.getMinutes();
    s = (d.getSeconds()<10?'0':'') + d.getSeconds();
  var dd = h + ':' + m + ':' + s;
  return dd;
}
        
});



	
/* global API_KEY TOKEN SESSION_ID SAMPLE_SERVER_BASE_URL OT */
/* eslint-disable no-alert */

// var SAMPLE_SERVER_BASE_URL = 'https://172.16.1.204/hrms/';

// OR, if you have not set up a web server that runs the learning-opentok-php code,
// set these values to OpenTok API key, a valid session ID, and a token for the session.
// For test purposes, you can obtain these from https://tokbox.com/account.

// var apiKey = '46183542';
// var sessionId = '1_MX40NjE4MzU0Mn5-MTUzOTMxNjQwMTIxMX4vS1FPQWdZc2ZGTlhicHFIdE45RE9Vb3l-fg';
// var token = 'T1==cGFydG5lcl9pZD00NjE4MzU0MiZzaWc9Y2E2NGY1NzZhOTZkNWIyYzA1OGMxNjBjZWM4NjI0ZWQ0MGY1YjYxYzpzZXNzaW9uX2lkPTFfTVg0ME5qRTRNelUwTW41LU1UVXpPVE14TmpRd01USXhNWDR2UzFGUFFXZFpjMlpHVGxoaWNIRklkRTQ1UkU5VmIzbC1mZyZjcmVhdGVfdGltZT0xNTM5MzE2NDMxJm5vbmNlPTAuNDA1MDc0MjU0NDgxODI2MiZyb2xlPXB1Ymxpc2hlciZleHBpcmVfdGltZT0xNTM5NDAyODMzJmluaXRpYWxfbGF5b3V0X2NsYXNzX2xpc3Q9';

function initializeSession1() {
  var to_id = $('#to_id').val();
  // console.log(to_id); return false;
  // alert(to_id);
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
        var apiKey = obj.apiKey;    
        var sessionId = obj.sessionId;   
        var token = obj.token;

      session = OT.initSession(apiKey, sessionId);
          console.log(session.isConnected(token)); 
          if(session.isConnected(token) == false){


              session.connect(token, function callback(error) {
              // If the connection is successful, initialize a publisher and publish to the session
              if (!error) {
                console.log('connect');
                console.log('connect = '+session.connection.connectionId);
                // Receive a message and append it to the history
               

                session.signal({
                  type: 'msg',
                  data: msgTxt.value
                }, function signalCallback(error) {
                  if (error) {
                    console.error('Error sending signal:', error.name, error.message);
                  } else {
                    
                    msgTxt.value = '';
                  }
                });


                var msgHistory = document.querySelector('#history');
                session.on('signal', function signalCallback(event) {
                  var msge = msgTxt.value;
                  var from_id = obj.login_id;
                  var msg_typ = 'one';
                  var grp_id = $('#hid_group_id').val();
                  var connection_id = session.connection.connectionId;
                  $.post(base_url+'chats/message_saving/',{from_id:from_id,to_id:to_id,msge:msge,connection_id:connection_id,msg_typ:msg_typ},function(res){
                    // console.log(res); return false;
                    var cur_time = chattimeNow();
                    // console.log(res); return false;
                    // var msg = document.createElement('p');
                    if(event.from.connectionId == session.connection.connectionId){
                      var cls_name = 'chat-right';
                    }else{
                      var cls_name = 'chat-left';
                    }
                    var msg ='<div class="chat '+cls_name+'"><div class="chat-avatar"><a title="John Doe" data-placement="right" data-toggle="tooltip" class="avatar"><img alt="John Doe" src="images/user.jpg" class="img-responsive img-circle"></a></div><div class="chat-body"><div class="chat-bubble"><div class="chat-content"><p>'+event.data+'</p><span class="chat-time">'+cur_time+'</span></div><div class="chat-action-btns"><ul><li><a href="#" class="del-msg DeleteChatMsgggroup" data-msgid="'+res+'" data-grpid="'+grp_id+'" title="Delete"><i class="fa fa-trash-o"></i></a></li></ul></div></div></div></div>';
                    $('.ChatHistory').append(msg);
                  });
                  console.log('from '+event.from.connectionId);
                  console.log('to '+session.connection.connectionId);
                });

              } else {
                console.error('There was an error connecting to the session: ', error.name, error.message);
              }
            });
          }else{
            session.signal({
                  type: 'msg',
                  data: msgTxt.value
                }, function signalCallback(error) {
                  if (error) {
                    console.error('Error sending signal:', error.name, error.message);
                  } else {
                    msgTxt.value = '';
                  }
                });
          }

      });
  }else{
    var group_members_ids = $('#receiver_ids').val();
    if(group_members_ids != '')
    {
      var result = group_members_ids.split(',');
      var msg_type = 'group';
      $.each(result, function(propName, to_id) {
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
        var apiKey = obj.apiKey;    
        var sessionId = obj.sessionId;   
        var token = obj.token;

      session = OT.initSession(apiKey, sessionId);
      // console.log(session); return false; 
      // Connect to the session
          console.log(session.isConnected(token)); 
          if(session.isConnected(token) == false){


              session.connect(token, function callback(error) {
              // If the connection is successful, initialize a publisher and publish to the session
              if (!error) {
                console.log('connect');
                console.log(session.connection.connectionId);
                // Receive a message and append it to the history
               

                session.signal({
                  type: 'msg',
                  data: msgTxt.value
                }, function signalCallback(error) {
                  if (error) {
                    console.error('Error sending signal:', error.name, error.message);
                  } else {
                    
                    msgTxt.value = '';
                  }
                });


                // var msgHistory = document.querySelector('.ChatHistory');
                session.on('signal', function signalCallback(event) {
                  var msge = msgTxt.value;
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
                  console.log('to '+session.connection.connectionId);
                });

              } else {
                console.error('There was an error connecting to the session: ', error.name, error.message);
              }
            });
          }else{
            session.signal({
                  type: 'msg',
                  data: msgTxt.value
                }, function signalCallback(error) {
                  if (error) {
                    console.error('Error sending signal:', error.name, error.message);
                  } else {
                    msgTxt.value = '';
                  }
                });
          }

      });
      });
    }


  }



}

// Text chat
var form = document.querySelector('form');
var msgTxt = document.querySelector('#msgTxt');

// Send a signal once the user enters data in the form
form.addEventListener('submit', function submit(event) {
  event.preventDefault();
  // alert(msgTxt.value);
  if(msgTxt.value !=  '')
  {
      initializeSession1();
  }else{
    return false;
  }
  
});


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


// setInterval(check_chat_messages, 3000);
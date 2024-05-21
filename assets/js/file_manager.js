function edit_folder(id) {
    $.ajax({
        url: base_url+'file_manager/edit_folder/'+id,
        type: 'GET',
        data: {},
        dataType: 'json',
        success: function(response) {
            if(response != '') {
                $('#folder_id').val(response.folder_id); 
                $('#folder_name').val(response.folder_name);
                $(".modal-title").text('Edit Folder');
                $('#add_folder').modal('show');
            } else {
                $('#add_folder').modal('show');
            }
        },
        error: function(xhr) {
            alert('Error: '+JSON.stringify(xhr));
        }
    });
}

$('#file_search').keyup(function(){

    var input_data = $('#file_search').val();
    var base=$('#base_url').val();
   // alert(input_data);
    $.ajax({
        type: "POST",
        url:base+"file_manager/file_search",
        data: {search_data:input_data},
        success: function(data) {
            $('#project_files').html(data);
        
        }
    });
});
$('#project_search').keyup(function(){
    
    var input_data = $('#project_search').val();
    var base=$('#base_url').val();
    $.ajax({
        type: "POST",
        url:base+"file_manager/project_search",
        data: {search_data:input_data},
        success: function(data) {
            $('#project_content').html(data);
        }
    });
});

$(document).ready(function () {
    $('.ShareBtnAtag').on('click',function(){
        // alert();
        var file_id = $(this).data('id');
        $('#user_file_id').val(file_id);
        // alert(file_id);
    });

    $('.ShareFileUsers').on('click',function(){
        $('.ShareFileUsers').removeClass('active');
        $(this).addClass('active');
        var share_type = $(this).data('share');
        $.post(base_url+'file_manager/share_files',{share_type:share_type},function(res){
            $('#project_files').html(res);
        });
        // alert(file_id);
    }); 
});

function ShareBtnAtag(file_id)
{
    $('#user_file_id').val(file_id);
}
$('.file-drag-upload').hover(
                
   function () {
      $(this).addClass('selected-div-hover');
   }, 
    
   function () {
      $(this).removeClass('selected-div-hover');
   }
);
$('#file_search').keyup(function(){
                
    var input_data = $('#file_search').val();
    var base=$('#base_url').val();
    // alert(input_data);
    $.ajax({
    type: "POST",
    url:base+"file_manager/file_search",
    data: {search_data:input_data},
    success: function(data) {
        $('#project_files').html(data);

    }
    });
});
$('#project_search').keyup(function(){

    var input_data = $('#project_search').val();
    var base=$('#base_url').val();

    $.ajax({
    type: "POST",
    url:base+"file_manager/project_search",
    data: {search_data:input_data},
    success: function(data) {
        $('#project_content').html(data);
    }
    });
});
function file_fetch(i)
{
    $('#drag_folder_id').val(i);
    
    $.ajax({
    type:"POST",
    url:"file_manager/project_files",
    data: {project_id:i},
    success: function(data) {
        
        $('#project_files').html(data);
    
    }
    });
}
$('.FolderLiClas').on('click',function(){
        $('.FolderLiClas').removeClass('active');
        $(this).addClass('active');
    });
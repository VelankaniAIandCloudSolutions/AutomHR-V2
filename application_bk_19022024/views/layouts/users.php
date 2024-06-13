<?php  if (config_item('timezone')) { date_default_timezone_set(config_item('timezone')); } ?>

<!DOCTYPE html>

<html lang="<?=lang('lang_code')?>" class="app">

<head>

	<meta charset="utf-8">

	<meta name="description" content="">

	<meta name="author" content="<?=config_item('site_author')?>">

	<meta name="keyword" content="<?=config_item('site_desc')?>">

	<?php $favicon = config_item('site_favicon'); $ext = substr($favicon, -4); ?>

	<?php if ( $ext == '.ico') : ?>

	<link rel="shortcut icon" href="<?=base_url()?>assets/images/<?=config_item('site_favicon')?>">

	<?php endif; ?>

	<?php if ($ext == '.png') : ?>

	<link rel="icon" type="image/png" href="<?=base_url()?>assets/images/<?=config_item('site_favicon')?>">

	<?php endif; ?>

	<?php if ($ext == '.jpg' || $ext == 'jpeg') : ?>

	<link rel="icon" type="image/jpeg" href="<?=base_url()?>assets/images/<?=config_item('site_favicon')?>">

	<?php endif; ?>

	<?php if (config_item('site_appleicon') != '') : ?>

	<link rel="apple-touch-icon" href="<?=base_url()?>assets/images/<?=config_item('site_appleicon')?>">

	<link rel="apple-touch-icon" sizes="72x72" href="<?=base_url()?>assets/images/<?=config_item('site_appleicon')?>">

	<link rel="apple-touch-icon" sizes="114x114" href="<?=base_url()?>assets/images/<?=config_item('site_appleicon')?>">

	<link rel="apple-touch-icon" sizes="144x144" href="<?=base_url()?>assets/images/<?=config_item('site_appleicon')?>">

	<?php endif; ?>

	<title><?php echo $template['title'];?></title>

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<link rel="stylesheet" href="<?=base_url()?>assets/css/font-awesome.min.css">

	<link rel="stylesheet" href="<?=base_url()?>assets/css/ionicons.min.css">

	<link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.min.css" type="text/css">

	<link rel="stylesheet" href="<?=base_url()?>assets/css/jquery.minicolors.css" type="text/css">

	<link rel="stylesheet" href="<?=base_url()?>assets/js/datepicker/datepicker.css" type="text/css"/>

	<link rel="stylesheet" href="<?=base_url()?>assets/css/plugins/toastr.min.css" type="text/css">

	<link rel="stylesheet" href="<?=base_url()?>assets/css/plugins/sweetalert.css" type="text/css">

	<link rel="stylesheet" href="<?=base_url()?>assets/css/typeahead.css" type="text/css">
	<link rel="stylesheet" href="<?=base_url()?>assets/css/fullcalendar.min.css" type="text/css">
	<link rel="stylesheet" href="<?=base_url()?>assets/css/line-awesome.min.css" type="text/css">

	<?php if (isset($fuelux)) { ?>

	<link rel="stylesheet" href="<?=base_url()?>assets/css/plugins/fuelux.min.css" type="text/css">

	<?php } ?>

	<?php if (isset($nouislider)) { ?>

	<link href="<?=base_url()?>assets/js/nouislider/jquery.nouislider.min.css" rel="stylesheet"  type="text/css">

	<?php } ?>

	<?php if (isset($editor)) { ?>

	<link href="<?=base_url()?>assets/css/plugins/summernote.css" rel="stylesheet" type="text/css">

	<?php } ?>

	<?php if (isset($country_code)) { ?>

	<link href="<?=base_url()?>assets/plugins/country_code/build/css/intlTelInput.css" rel="stylesheet" type="text/css">

	<?php } ?>

	<?php if (isset($datepicker)) { ?>

	<link rel="stylesheet" href="<?=base_url()?>assets/js/slider/slider.css" type="text/css" />

	

	<?php } ?>

	<?php if (isset($iconpicker)) { ?>

	<link rel="stylesheet" href="<?=base_url()?>assets/js/iconpicker/fontawesome-iconpicker.min.css" type="text/css">

	<?php } ?>

	<?php if (isset($calendar) || isset($fullcalendar)) { ?>

	<link rel="stylesheet" href="<?=base_url()?>assets/css/fullcalendar.min.css" type="text/css">

	<?php } ?>

	<?php if (isset($form)) { ?>

	<link rel="stylesheet" href="<?=base_url()?>assets/css/plugins/select2.min.css" type="text/css"/>

	<link rel="stylesheet" href="<?=base_url()?>assets/css/plugins/select2-bootstrap.min.css" type="text/css" />

	<?php } ?>

	<?php if ($this->uri->segment(2) == 'help') { ?>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/1.0.0/introjs.min.css" type="text/css" />

	<?php }  ?>

	<?php if (isset($datatables)) { ?>

	<link rel="stylesheet" href="<?=base_url()?>assets/css/plugins/dataTables.bootstrap.min.css" type="text/css"/>

	<?php }  ?>

	<!-- <link rel="stylesheet" href="<?=base_url()?>assets/css/custom.css" type="text/css" /> -->

	<link rel="stylesheet" href="<?=base_url()?>assets/css/style.css" type="text/css" />

	<link rel="stylesheet" href="<?=base_url()?>assets/css/chat.css" type="text/css" />

	<?php 

	$family = 'Lato';

	$font = config_item('system_font');

	switch ($font) {

		case "open_sans": $family="Open Sans";  echo "<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,latin-ext,greek-ext,cyrillic-ext' rel='stylesheet' type='text/css'>"; break;

		case "open_sans_condensed": $family="Open Sans Condensed";  echo "<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;

		case "roboto": $family="Roboto";  echo "<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;

		case "roboto_condensed": $family="Roboto Condensed";  echo "<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,300,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;

		case "ubuntu": $family="Ubuntu";  echo "<link href='https://fonts.googleapis.com/css?family=Ubuntu:400,300,500,700&subset=latin,greek-ext,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;

		case "lato": $family="Lato";  echo "<link href='https://fonts.googleapis.com/css?family=Lato:100,300,400,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>"; break;

		case "oxygen": $family="Oxygen";  echo "<link href='https://fonts.googleapis.com/css?family=Oxygen:400,300,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>"; break;

		case "pt_sans": $family="PT Sans";  echo "<link href='https://fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;

		case "source_sans": $family="Source Sans Pro";  echo "<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;

		case "montserrat": $family="Montserrat";  echo "<link href='https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;

		case "fira_sans": $family="Fira Sans";  echo "<link href='https://fonts.googleapis.com/css?family=Fira+Sans:400,500,600,700' rel='stylesheet' type='text/css'>"; break;
		case "circularstd": $family="CircularStd"; break;

	}

	?>

	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

	<link rel="stylesheet" href="<?=base_url()?>assets/css/app.css" type="text/css" />

	<style type="text/css">

		#pie-chart{

			min-height: 250px;

		}

		.morris-hover{position:absolute;z-index:1000;}

		.morris-hover.morris-default-style{border-radius:10px;padding:6px;color:#666;background:rgba(255, 255, 255, 0.8);border:solid 2px rgba(230, 230, 230, 0.8);font-family:sans-serif;font-size:12px;text-align:center;}

		.morris-hover.morris-default-style .morris-hover-row-label{font-weight:bold;margin:0.25em 0;}

		.morris-hover.morris-default-style .morris-hover-point{white-space:nowrap;margin:0.1em 0;}

		body { font-family: '<?=$family?>'; }

	</style>


	<!-- Global site tag (gtag.js) - Google Analytics -->
	<!-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-132757834-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-132757834-1');
	</script> -->

	<!--[if lt IE 9]>

	<script src="js/ie/html5shiv.js">

	</script>

	<script src="js/ie/respond.min.js">

	</script>

	<script src="js/ie/excanvas.js">

	</script> <![endif]-->

</head>

<body class="theme-<?=config_item('top_bar_color')?>">

	<div class="main-wrapper">

		<!--header start-->
		
		<?php  if($this->uri->segment(1) !='candidates' && !$this->session->userdata('candidate_id')){
			echo modules::run('sidebar/top_header');
		}
		if($this->uri->segment(1) =='candidates' || $this->session->userdata('candidate_id')  ){
			echo modules::run('sidebar/candidate_top_header');
		}?>

		<!--header end-->

		

		<!--sidebar start-->

			<?php

			// if (User::is_admin()) {

			// 	echo modules::run('sidebar/admin_menu');

				

			// }elseif (User::is_staff()) {

			// 	echo modules::run('sidebar/collaborator_menu');

				 

			// }elseif (User::is_client()) {

			// 	echo modules::run('sidebar/client_menu');

			// }else{

			// 	redirect('login');

			// }

			?>

			<?php
			// echo User::is_admin(); exit;
			// if (User::is_admin()) {

				echo modules::run('sidebar/admin_menu');

				

			// }elseif (User::is_staff()) {

			// 	echo modules::run('sidebar/collaborator_menu');

				 

			// }elseif (User::is_client()) {

			// 	echo modules::run('sidebar/client_menu');

			// }else{

			// 	redirect('login');

			// }

			?>

		<!--sidebar end-->



			<div class="page-wrapper">

				<!--main content start-->

				<?php  echo $template['body'];?>

				<!--main content end-->

				<aside class="bg-light lter b-l aside-md hide" id="notes">

					<div class="wrapper">Notification

					</div>

				</aside>

			</div>

	</div>

	<div class="sidebar-overlay" data-reff=""></div>

	<script>
		var locale = '<?=lang('lang_code')?>';
		var base_url = '<?=base_url()?>';
	</script>

	<!--<script src="<?=base_url()?>assets/js/jquery-2.2.4.min.js"></script> -->
	<script src="<?=base_url()?>assets/js/jquery-3.2.1.min.js"></script>	
	
	<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
	<script src="https://static.opentok.com/v2/js/opentok.min.js"></script>
	<!-- Polyfill for fetch API so that we can fetch the sessionId and token in IE11 -->
	<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@7/dist/polyfill.min.js" charset="utf-8"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/fetch/2.0.3/fetch.min.js" charset="utf-8"></script>
	<script src="<?=base_url()?>assets/js/tok_box.js"></script>
	<?php if ($this->uri->segment(1) == 'shift_scheduling') { ?>
	<script src="<?=base_url()?>assets/js/shift.js"></script>
	<?php } ?>
	<?php if ($this->uri->segment(1) == 'file_manager') { ?>
	<script src="<?=base_url()?>assets/js/file_manager.js"></script>
	<?php } ?>
	<script src="<?=base_url()?>assets/js/chat.js"></script>
	<script src="<?=base_url()?>assets/js/notify.js"></script>
	<script src="<?=base_url()?>assets/js/libs/moment.min.js"></script>
	<script src="<?=base_url()?>assets/js/app.js"></script>
	<script src="<?=base_url()?>assets/js/charts/easypiechart/jquery.easy-pie-chart.js"></script>
	<script src="<?=base_url()?>assets/js/libs/jquery.sparkline.min.js"></script>
	<script src="<?=base_url()?>assets/js/libs/toastr.min.js"></script>
	<script src="<?=base_url()?>assets/js/libs/sweetalert.min.js"></script>
	<script src="<?=base_url()?>assets/js/libs/typeahead.jquery.min.js"></script>
	<script src="<?=base_url()?>assets/js/libs/jquery.textarea_autosize.min.js"></script>
	<script src="<?=base_url()?>assets/js/multiselect.min.js"></script>
	<script src="<?=base_url()?>assets/js/jquery.slimscroll.js"></script>
	<script src="<?=base_url()?>assets/js/chart.min.js"></script>
	<script src="<?=base_url()?>assets/js/fullcalendar.min.js"></script>
	<script src="<?=base_url()?>assets/js/jquery.fullcalendar.js"></script>
	<?php if (isset($notes_app)) { ?>
	<script src="<?=base_url()?>assets/js/libs/underscore-min.js"></script>
	<script src="<?=base_url()?>assets/js/libs/backbone-min.js"></script>
	<script src="<?=base_url()?>assets/js/libs/backbone.localStorage-min.js"></script>
	<script src="<?=base_url()?>assets/js/apps/notes.js"></script>
	<?php } ?>
	<script src="<?=base_url()?>assets/js/custom.js"></script>
	<?php if ($this->uri->segment(2) == 'business_proposals') { ?>
		<script src="<?=base_url()?>assets/js/jquery-ui.min.js"></script>
	 <script>     
        $(document).ready(function() {     	 
   
        // $("#preloader").remove();
    
            $(".kanban-wrap").sortable({
                connectWith: ".kanban-wrap",
                handle: ".kanban-box",
                placeholder: "drag-placeholder",
                 receive: function(event, ui) {
                 	var status = this.id;
                 	// var status_name = this.status;
                 	 // alert('status_id='+status);
                 	 // var t_id = $(this).attr('data-id');
                 	 var user_id = ui.sender.attr("data-id");
                 	 // alert('task_id ='+t_id);

                 	   var data_cid = ui.sender.attr("id");
                 	   // alert(t_id);
                 	 // alert('2'+data_cid);
                 	 if(data_cid != status){
                 	 	$.post(base_url + 'crm/lead_status_edit/', { status: status, user_id:user_id}, function (datas) {
               				// $(".se-pre-con").fadeOut("slow");
               				$('.loader').append('<div class="se-pre-con" style="background: url('+base_url+'/assets/simple-pre-loader/images/loader-64x/Preloader_2.gif) center no-repeat #fff;"></div>')
			              setTimeout(function(){ toastr.success('Lead status Edited Successfully');  
                                location.reload();
                            }, 1500);   
			            });
                 	  }
                 	 // alert('2'+t_id);
       				
    },
            });
        });
    </script>
<?php }?>


	<?php if ($this->uri->segment(2) == 'candidates_board') { ?> 
 
	 <script>
     
        $(document).ready(function() {
        	 
   
        // $("#preloader").remove();
    
            $(".kanban-wrap").sortable({
                connectWith: ".kanban-wrap",
                handle: ".kanban-box",
                placeholder: "drag-placeholder",
                 receive: function(event, ui) {
                //var status =  ui.sender.attr("data-status");
                 	var status = this.id;
                 	
                 	// var status_name = this.status;
                 	 // alert('status_id='+status);
                 	 // var t_id = $(this).attr('data-id');
                 	 var user_id = ui.sender.attr("data-id");
                 	 var job_id = ui.sender.attr("data-jobid");
                 	 
                 	 // alert('task_id ='+t_id);

                 	  // var data_cid = ui.sender.attr("id");
                 	   // alert(t_id);
                 	 // alert('2'+data_cid);
                 	 //if(data_cid != status){
                 	 	$.post(base_url + 'jobs/user_status_update/', { status: status, user_id:user_id,job_id:job_id}, function (datas) {
               				// $(".se-pre-con").fadeOut("slow");
               				$('.loader').append('<div class="se-pre-con" style="background: url('+base_url+'/assets/simple-pre-loader/images/loader-64x/Preloader_2.gif) center no-repeat #fff;"></div>')
			              setTimeout(function(){ toastr.success('candidate status Edited Successfully');  
                                location.reload();
                            }, 1500);   
			            });
                 	 // }
                 	 // alert('2'+t_id);
       				
    },
            });
        });
    </script>
<?php }?>
	<?php if ($this->uri->segment(1) == 'all_tasks') { ?> 
	<!-- <script src="<?=base_url()?>assets/js/jquery-ui.min.js"></script> -->
	 <script>
       
        $(document).ready(function() {
            $(".kanban-wrap").sortable({
                connectWith: ".kanban-wrap",
                handle: ".kanban-box",
                placeholder: "drag-placeholder",
                 receive: function(event, ui) {
                 	var status = this.id;
                 	// var status_name = this.status;
                 	 // alert('status_id='+status);
                 	 // var t_id = $(this).attr('data-id');
                 	 var t_id = ui.sender.attr("data-id");
                 	 // alert('task_id ='+t_id);

                 	   var data_cid = ui.sender.attr("id");
                 	   // alert(t_id);
                 	 // alert('2'+data_cid);
                 	 if(data_cid != status){
                 	 	$.post(base_url + 'all_tasks/task_status_edit/', { status: status, t_id:t_id}, function (datas) {
               
			              setTimeout(function(){ toastr.success('Task status Edited Successfully');  
                                location.reload();
                            }, 1500);   
			            });
                 	  }
                 	 // alert('2'+t_id);
       				
    },
       //          update: function (event, ui) {
       //          	 var data = $(this).sortable('serialize');
       //          	 alert('2'+status); 
       // 				 // var status = $(this).attr('data-status');
       // 				 var t_id = $(this).attr('data-id');
       // 				$.post(base_url + 'all_tasks/task_status_edit/', { status: status, t_id:t_id}, function (datas) {
               
       //         setTimeout(function(){ toastr.success('Task status Edited Successfully'); 
       //                          location.reload();
       //                      }, 1500);   
       //      });

       //  // POST to server using $.post or $.ajax
			        
			    // }
            });
        });
    </script>
<?php }?>
	<script>
		toastr.options = {
			"closeButton": true,
			"debug": false,

			"positionClass": "toast-bottom-right",

			"onclick": null,

			"showDuration": "300",

			"hideDuration": "1000",

			"timeOut": "5000",

			"extendedTimeOut": "1000",

			"showEasing": "swing",

			"hideEasing": "linear",

			"showMethod": "fadeIn",

			"hideMethod": "fadeOut"

		}          

	</script>

	<?php if (isset($fuelux)) { ?>

	<script src="<?=base_url()?>assets/js/fuelux/fuelux.min.js"></script>

	<?php } ?>

	<?php if (isset($editor)) { ?>

	<script src="<?=base_url()?>assets/js/wysiwyg/summernote.min.js"></script>

	<script>

	$(document).ready(function() {

	$('.foeditor').summernote({ height: 200, codemirror: { theme: 'monokai' } });

	$('.foeditor-estimate-cnote').summernote({ height: 200, codemirror: { theme: 'monokai' } });

	$('.foeditor-550').summernote({ height: 550, codemirror: { theme: 'monokai' } });

	$('.foeditor-500').summernote({ height: 500, codemirror: { theme: 'monokai' } });

	$('.foeditor-400').summernote({ height: 400, codemirror: { theme: 'monokai' } });

	$('.foeditor-300').summernote({ height: 300, codemirror: { theme: 'monokai' } });

	$('.foeditor-100').summernote({ height: 100, codemirror: { theme: 'monokai' } });

	$('.foeditor-project-discussion').summernote({ height: 100, codemirror: { theme: 'monokai' } });

	$('.foeditor-client-comment').summernote({ height: 100, codemirror: { theme: 'monokai' } });

	$('.foeditor-project-add').summernote({ height: 100, codemirror: { theme: 'monokai' } });

	$('.foeditor-project-edit').summernote({ height: 100, codemirror: { theme: 'monokai' } });

	$('.foeditor-taskview-comment').summernote({ height: 100, codemirror: { theme: 'monokai' } });
	
	$('.foeditor-invoice-create').summernote({ height: 100, codemirror: { theme: 'monokai' } });

	$('.foeditor-invoice-edit').summernote({ height: 100, codemirror: { theme: 'monokai' } });

	$('.foeditor-send-message').summernote({ height: 100, codemirror: { theme: 'monokai' } });

	$('.foeditor-send-conversation').summernote({ height: 100, codemirror: { theme: 'monokai' } });
	
	
	

	});

	</script>

	<?php } ?>

	<!-- <script type="text/javascript">
		$('.foeditor').summernote({ height: 200, codemirror: { theme: 'monokai' } });
	</script> -->

	<?php if(isset($show_links)) { ?>

	<script>

	// Check the main container is ready

	$('.activate_links').ready(function(){

		// Get each div

		$('.activate_links').each(function(){

			// Get the content

			var str = $(this).html();

			// Set the regex string

			var regex = /(https?:\/\/([-\w\.]+)+(:\d+)?(\/([\w\/_\.]*(\?\S+)?)?)?)/ig

			// Replace plain text links by hyperlinks

			var replaced_text = str.replace(regex, "<a href='$1' target='_blank'>$1</a>");

			// Echo link

			$(this).html(replaced_text);

		});

	});

	</script>

	<?php } ?>

	<!-- Bootstrap -->

	<!-- js placed at the end of the document so the pages load faster -->

	<?php  echo modules::run('sidebar/scripts');?>

	<script src="<?=base_url()?>assets/js/libs/jquery.maskMoney.min.js"></script>

	<?php 

		if (empty($this->session->userdata('timezone'))) {

	 ?>

	<script src="<?=base_url()?>assets/js/jstz-1.0.4.min.js"></script>

	<script>

		$(function() {

			$('.money').maskMoney();

			var tz = jstz.determine();

        	var timezone = tz.name();

        	$.post(base_url+'auth/userzone',{timezone:timezone},function(status){});

        	//console.log(timezone);

		})

	</script>

	<?php }  ?>



	<?php // }  

	if($this->uri->segment(2) == 'project_chart'){
	?>

	<script src="<?=base_url()?>assets/js/jquery.fn.gantt.js"></script>
	<!-- <script src="<?=base_url()?>assets/js/prettify.js"></script> -->
	<script src="http://taitems.github.com/UX-Lab/core/js/prettify.js"></script>
	<?php } ?>





	<?php 
if($this->uri->segment(2) == 'project_chart'){ ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	$(document).ready(function(){

	google.charts.load('current', {'packages':['gantt']});
    google.charts.setOnLoadCallback(drawChart);

    function daysToMilliseconds(days) {
      return days * 24 * 60 * 60 * 1000;
    }


    function drawChart(){
    var project_id = '<?php echo $this->session->userdata('project_id'); ?>';

	$.post('<?=base_url()?>projects/chart_tasks',{project_id:project_id},function(result){
		// console.log(result); 
		if(result != 'empty')
		{
			var test_result = $.parseJSON(result);
			 console.log(test_result);

			 var ar=new Array();
			 $.each(test_result, function(key,obj) {
			 	var temp = new Array();
			 	temp[0] = obj[0];
			 	temp[1] = obj[1];
			 	temp[2] = new Date(obj[2]);
			 	temp[3] = new Date(obj[3]);
			 	temp[4] = obj[4];
			 	temp[5] = obj[5];
			 	temp[6] = obj[6];

			 	ar.push(temp); 

			});
			var data = new google.visualization.DataTable();
		      data.addColumn('string', 'Task ID');
		      data.addColumn('string', 'Task Name');
		      data.addColumn('date', 'Start Date');
		      data.addColumn('date', 'End Date');
		      data.addColumn('number', 'Duration');
		      data.addColumn('number', 'Percent Complete');
		      data.addColumn('string', 'Dependencies');

		      data.addRows(ar);

		      // console.log(data.wg);

		      var options = {
		          width: 1100,
		          height: 500,
		           gantt: {
		          criticalPathEnabled: false,
		          innerGridHorizLine: {
		            stroke: '#ffe0b2',
		            strokeWidth: 2
		          },
		          innerGridTrack: {fill: '#fff3e0'},
		          innerGridDarkTrack: {fill: '#ffcc80'}
		        }
		        };
		      var chart = new google.visualization.Gantt(document.getElementById('chart_div'));

		      chart.draw(data, options);
		      // google.charts.setOnLoadCallback(chart);
		}
	});
}


	});
  </script>
<?php } ?>


	<?php if($this->uri->segment(1) == 'chats'){ ?>
		<script src="<?=base_url()?>assets/js/signal.js"></script>
	<?php } ?>

	<script>
	var base_url = '<?php echo base_url(); ?>';	
	var currentUserId = '<?php echo $this->session->userdata("user_id"); ?>';
	var defaultImageBasePath = base_url + 'assets/img/';
	var imageBasePath = base_url + '/uploads/';
	var currentUserProfileImage = '<?php echo $this->session->userdata("profile_img"); ?>';
	var currentUserName = '<?php echo ucfirst($this->session->userdata("first_name")).' '.ucfirst($this->session->userdata("last_name")); ?>';
	var currentSinchUserName = '<?php echo $this->session->userdata('sinch_username'); ?>';

	if(currentUserProfileImage != ''){
		currentUserProfileImage = imageBasePath + currentUserProfileImage;
	}
	else{
		currentUserProfileImage = defaultImageBasePath +'user.jpg';
	}
</script>

<?php if ($this->session->flashdata('su_message')  != ''){ ?>
<script type="text/javascript">
    toastr.success("<?php echo $this->session->flashdata('su_message'); ?>");
</script>

<?php } ?>

<?php if ($this->session->flashdata('er_message')  != ''){ ?>
<script type="text/javascript">
    toastr.error("<?php echo $this->session->flashdata('er_message'); ?>");
</script>

<?php } ?>

<?php $projects_count = $this->db->get_where('projects',array('status'=>'Active','proj_deleted'=>'No','branch_id'=>$this->session->userdata('branch_id')))->result_array(); 
$current_yr = date("Y");
$clients_count = $this->db->get_where('companies',array('branch_id'=>$this->session->userdata('branch_id')))->result_array(); 
$tasks_count = $this->db->get_where('tasks',array('branch_id'=>$this->session->userdata('branch_id')))->result_array(); 
$users_count = $this->db->select('*')
						 ->from('users U')
						 ->join('account_details AD','U.id = AD.user_id')
						 ->where('U.role_id',3)
						 ->where('U.status',1)
						 ->where('AD.branch_id',$this->session->userdata('branch_id'))
						 ->get()->result_array();
// $users_count = $this->db->get_where('users',array('role_id '=>3,'status'=>1))->result_array(); 
$jan_invoice = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 1) 
			//    ->where('branch_id',$this->session->userdata('branch_id')) 
			   ->where("year(date_saved)", $current_yr)
			   ->get()->result_array();

$jan_pending = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 1) 
			//    ->where('branch_id',$this->session->userdata('branch_id')) 
			   ->where("year(date_saved)", $current_yr)
			   ->where("status", Unpaid) 
			   ->get()->result_array();

$feb_invoice = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 2) 
			//    ->where('branch_id',$this->session->userdata('branch_id')) 
			   ->where("year(date_saved)", $current_yr)
			   ->get()->result_array();

$feb_pending = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 2) 
			//    ->where('branch_id',$this->session->userdata('branch_id')) 
			   ->where("year(date_saved)", $current_yr)
			   ->where("status", Unpaid) 
			   ->get()->result_array();

$march_invoice = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 3) 
			//    ->where('branch_id',$this->session->userdata('branch_id')) 
			   ->where("year(date_saved)", $current_yr)
			   ->get()->result_array();

$march_pending = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 3) 
			//    ->where('branch_id',$this->session->userdata('branch_id')) 
			   ->where("year(date_saved)", $current_yr)
			   ->where("status", Unpaid) 
			   ->get()->result_array();

$april_invoice = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 4) 
			//    ->where('branch_id',$this->session->userdata('branch_id')) 
			   ->where("year(date_saved)", $current_yr)
			   ->get()->result_array();

$april_pending = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 4) 
			//    ->where('branch_id',$this->session->userdata('branch_id')) 
			   ->where("year(date_saved)", $current_yr)
			   ->where("status", Unpaid) 
			   ->get()->result_array();

$may_invoice = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 5) 
			//    ->where('branch_id',$this->session->userdata('branch_id')) 
			   ->where("year(date_saved)", $current_yr)
			   ->get()->result_array();

$may_pending = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 5) 
			//    ->where('branch_id',$this->session->userdata('branch_id')) 
			   ->where("year(date_saved)", $current_yr)
			   ->where("status", Unpaid) 
			   ->get()->result_array();

$june_invoice = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 6) 
			//    ->where('branch_id',$this->session->userdata('branch_id')) 
			   ->where("year(date_saved)", $current_yr)
			   ->get()->result_array();

$june_pending = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 6) 
			//    ->where('branch_id',$this->session->userdata('branch_id')) 
			   ->where("year(date_saved)", $current_yr)
			   ->where("status", Unpaid) 
			   ->get()->result_array();
$july_invoice = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 7) 
			   ->where("year(date_saved)", $current_yr)
			   ->get()->result_array();

$july_pending = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 7) 
			   ->where("year(date_saved)", $current_yr)
			   ->where("status", Unpaid) 
			   ->get()->result_array();

$aug_invoice = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 8) 
			   ->where("year(date_saved)", $current_yr)
			   ->get()->result_array();

$aug_pending = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 8) 
			   ->where("year(date_saved)", $current_yr)
			   ->where("status", Unpaid) 
			   ->get()->result_array();

$sep_invoice = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 9) 
			   ->where("year(date_saved)", $current_yr)
			   ->get()->result_array();

$sep_pending = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 9) 
			   ->where("year(date_saved)", $current_yr)
			   ->where("status", Unpaid) 
			   ->get()->result_array();

$oct_invoice = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 10) 
			   ->where("year(date_saved)", $current_yr)
			   ->get()->result_array();

$oct_pending = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 10) 
			   ->where("year(date_saved)", $current_yr)
			   ->where("status", Unpaid) 
			   ->get()->result_array();

$nov_invoice = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 11) 
			   ->where("year(date_saved)", $current_yr)
			   ->get()->result_array();

$nov_pending = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 11) 
			   ->where("year(date_saved)", $current_yr)
			   ->where("status", Unpaid) 
			   ->get()->result_array();

$dec_invoice = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 12) 
			   ->where("year(date_saved)", $current_yr)
			   ->get()->result_array();

$dec_pending = $this->db->select("*")
			   ->from('dgt_invoices')
			   ->where("month(date_saved)", 12) 
			   ->where("year(date_saved)", $current_yr)
			   ->where("status", Unpaid) 
			   ->get()->result_array();


?>
					

<script>
			var projects = "<?php echo count($projects_count); ?>";
			var clients = "<?php echo count($clients_count);?>";
			var tasks = "<?php echo count($tasks_count)?>";
			var employees = "<?php echo count($users_count)?>";
			var jan = "<?php echo count($jan_invoice)?>";
			var jan_pending = "<?php echo count($jan_pending)?>";
			var feb_invoice = "<?php echo count($feb_invoice)?>";
			var feb_pending = "<?php echo count($feb_pending)?>";
			var march_invoice = "<?php echo count($march_invoice)?>";
			var march_pending = "<?php echo count($march_pending)?>";
			var april_invoice = "<?php echo count($april_invoice)?>";
			var april_pending = "<?php echo count($april_pending)?>";
			var may_invoice = "<?php echo count($may_invoice)?>";
			var may_pending = "<?php echo count($may_pending)?>";
			var june_invoice = "<?php echo count($june_invoice)?>";
			var june_pending = "<?php echo count($june_pending)?>";
			var july_invoice = "<?php echo count($july_invoice)?>";
			var july_pending = "<?php echo count($july_pending)?>";
			var aug_invoice = "<?php echo count($aug_invoice)?>";
			var aug_pending = "<?php echo count($aug_pending)?>";
			var sep_invoice = "<?php echo count($sep_invoice)?>";
			var sep_pending = "<?php echo count($sep_pending)?>";
			var oct_invoice = "<?php echo count($oct_invoice)?>";
			var oct_pending = "<?php echo count($oct_pending)?>";
			var nov_invoice = "<?php echo count($nov_invoice)?>";
			var nov_pending = "<?php echo count($nov_pending)?>";
			var dec_invoice = "<?php echo count($dec_invoice)?>";
			var dec_pending = "<?php echo count($dec_pending)?>";

			var uri_val = "<?php echo $this->uri->segment(1); ?>";
			

			if(uri_val=='')
			{
			var pieData={

				labels: ['clients', 'tasks', 'employees', 'projects'],

				datasets: [{
				data: [clients, tasks, employees, projects],
				backgroundColor: [
					"#FC6075",
					"#FF9B44",
					"#FD9BA8",
					"#Ffc999"
				],
				hoverBackgroundColor: [
					"#FC6075",
					"#FF9B44",
					"#FD9BA8",
					"#Ffc999"
				]
				}]
			};
			var options={
				segmentShowStroke : false,
				animateScale : true
			}
			var gas2=document.getElementById('gas2').getContext('2d');
			new Chart(gas2,{
				type:'pie',
				data:pieData,
				options:options
			});
			}


			

		</script>
		<!-- <script>
	var lineChartData = {
		labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],

		datasets: [{
			label: 'Total Invoice ',
			backgroundColor: 'transparent',
			borderColor: '#ff9b44',
			pointBackgroundColor: '#ff9b44',
			borderWidth: 2,
			data: [jan, feb_invoice, march_invoice, april_invoice, may_invoice, june_invoice],
			
		}, {
			label: 'Pending Invoice',
			backgroundColor: 'transparent',
			borderColor: 'rgba(252, 96, 117, 1)',
			pointBackgroundColor: 'rgba(252, 96, 117, 1)',
			borderWidth: 2,
			data: [jan_pending, feb_pending, march_pending, april_pending, may_pending, june_pending],
		}]

	};

	if(uri_val=='')
	{

	var linectx = document.getElementById('canvas').getContext('2d');
	window.myLine = new Chart(linectx, {
	type: 'line',
	data: lineChartData,
	options: {
		responsive: true,
		legend: {
			display: false,
		},
		tooltips: {
			mode: 'index',
			intersect: false,
		}
	}
	});
	}
		</script> -->

		<script>
		// alert(june_invoice);
			var options = { 
				  chart: {
				    type: 'bar',

				  },
				  series: [{
				    name: 'Total',
				    data: [jan, feb_invoice, march_invoice, april_invoice, may_invoice, june_invoice, july_invoice, aug_invoice, sep_invoice, oct_invoice, nov_invoice, dec_invoice]
				  },{
				    name: 'Pending',
				    data: [jan_pending, feb_pending, march_pending, april_pending, may_pending, june_pending, july_pending, aug_pending, sep_pending, oct_pending, nov_pending, dec_pending]
				  }],
				  xaxis: {
				    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul','Aug','Sep','Oct','Nov','Dec']
				  },
				  colors: ['#ff9b44', '#fc6075']
				}

				// var chart = new ApexCharts(document.querySelector("#chart"), options);

				// chart.render();
		</script>

		<script>
	var lineChartData = {
		labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],

		datasets: [{
			label: 'Total Invoice ',
			backgroundColor: 'transparent',
			borderColor: '#ff9b44',
			pointBackgroundColor: '#ff9b44',
			borderWidth: 2,
			data: [jan, feb_invoice, march_invoice, april_invoice, may_invoice, june_invoice],
			
		}, {
			label: 'Pending Invoice',
			backgroundColor: 'transparent',
			borderColor: 'rgba(252, 96, 117, 1)',
			pointBackgroundColor: 'rgba(252, 96, 117, 1)',
			borderWidth: 2,
			data: [jan_pending, feb_pending, march_pending, april_pending, may_pending, june_pending],
		}]

	};
	if(document.getElementById('canvas')){
	var linectx = document.getElementById('canvas').getContext('2d');
	window.myLine = new Chart(linectx, {
	type: 'line',
	data: lineChartData,
	options: {
		responsive: true,
		legend: {
			display: false,
		},
		tooltips: {
			mode: 'index',
			intersect: false,
		}
	}
	});
	}
	</script>


		<script>
		$(document).ready(function() {

			$('#rating_scale_select input[name="rating_scale"]').on('click', function() {
			if ($(this).val() == 'rating_01_010') {
				$('#01ratings_cont').show();
				$('#5ratings_cont').hide();
				$('#10ratings_cont').hide();
				$('#custom_rating_cont').hide();
			}	
			if ($(this).val() == 'rating_1_5') {
				$('#5ratings_cont').show();
				$('#10ratings_cont').hide();
				$('#custom_rating_cont').hide();
				$('#01ratings_cont').hide();
			}
			if ($(this).val() == 'rating_1_10') {
				$('#10ratings_cont').show();
				$('#5ratings_cont').hide();
				$('#custom_rating_cont').hide();
				$('#01ratings_cont').hide();
			}
			if ($(this).val() == 'custom_rating') {
				$('#custom_rating_cont').show();
				$('#5ratings_cont').hide();
				$('#10ratings_cont').hide();
				$('#01ratings_cont').hide();
			}
			else {
			}
		});
			
		    $('#rating_scale_select_okr input[name="rating_scale"]').on('click', function() {
		    if ($(this).val() == 'rating_01_010') {
				$('#01ratings_cont_okr').show();
				$('#5ratings_cont_okr').hide();
				$('#10ratings_cont_okr').hide();
				$('#custom_rating_cont_okr').hide();
			}	
			if ($(this).val() == 'rating_1_5') {
				$('#5ratings_cont_okr').show();
				$('#10ratings_cont_okr').hide();
				$('#custom_rating_cont_okr').hide();
				$('#01ratings_cont_okr').hide();
			}
			if ($(this).val() == 'rating_1_10') {
				$('#10ratings_cont_okr').show();
				$('#5ratings_cont_okr').hide();
				$('#custom_rating_cont_okr').hide();
				$('#01ratings_cont_okr').hide();
			}
			if ($(this).val() == 'custom_rating') {
				$('#custom_rating_cont_okr').show();
				$('#5ratings_cont_okr').hide();
				$('#10ratings_cont_okr').hide();
				$('#01ratings_cont_okr').hide();
			}
			else {
			}
		});
		    $('#rating_scale_select_competency input[name="rating_scale"]').on('click', function() {
		    if ($(this).val() == 'rating_01_010') {
				$('#01ratings_cont_competency').show();
				$('#5ratings_cont_competency').hide();
				$('#10ratings_cont_competency').hide();
				$('#custom_rating_cont_competency').hide();
			}
			if ($(this).val() == 'rating_1_5') {
				$('#5ratings_cont_competency').show();
				$('#10ratings_cont_competency').hide();
				$('#custom_rating_cont_competency').hide();
				$('#01ratings_cont_competency').hide();
			}
			if ($(this).val() == 'rating_1_10') {
				$('#10ratings_cont_competency').show();
				$('#5ratings_cont_competency').hide();
				$('#custom_rating_cont_competency').hide();
				$('#01ratings_cont_competency').hide();
			}
			if ($(this).val() == 'custom_rating') {
				$('#custom_rating_cont_competency').show();
				$('#5ratings_cont_competency').hide();
				$('#10ratings_cont_competency').hide();
				$('#01ratings_cont_competency').hide();
			}
			else {
			}
		});
			$("#custom_rating_input").keyup(function () {
				var value = $(this).val();				
				$(".custom-value").text(value);	
				
				var custom_input_html = ''; 
				for (var i = 1; i <= value; i++) {
				  custom_input_html += '<tr>' +
					'<td> '+ i +' </td>' +
					'<td>' +
						'<input type="hidden" class="form-control" name="rating_no[]" value="'+i+'">' +
						'<input type="text" class="form-control" name="rating_value_custom[]" placeholder="Short word to describe rating of 1" required>' +
					'</td>' +
					'<td>' +
						'<textarea rows="3" class="form-control" name="definition_custom[]" placeholder="Descriptive Rating Definition" required></textarea>' +
					'</td>' +
				'</tr>'; 
				}
				$('.custom-value').html(custom_input_html);
				
			});
		});
		</script>

		<script type="text/javascript">
				// var images = document.querySelectorAll('img');
				// alert(images);
				// for (var i = 0; i < images.length; i++) {
				//   images[i].onerror = function() {
				//     // this.style.display='none';
				//     this.src="little_girl.jpg";
				//     // alert("fds")
				//   }
				// }

				 // $("img").each(function(){
				 //        var img = $(this);
				 //        var image = new Image();
				 //        image.src = $(img).attr("src");
				 //        var no_image = "little_girl.jpg";
				 //        if (image.naturalWidth == 0 || image.readyState == 'uninitialized'){
				 //            $(img).unbind("error").attr("src", no_image).css({
				 //                height: $(img).css("height"),
				 //                width: $(img).css("width"),
				 //            });
				 //        }
				 //  });

				//  (function() {
				//     var allimgs = document.images;
				//     for (var i = 0; i < allimgs.length; i++) {
				//         allimgs[i].onerror = function() {
				//             this.style.visibility = "hidden"; //if you want hide
				//             this.remove(); //if you want to remove img tag
				//             console.clear(); //clear error from console
				//         }
				//     }
				// })();
		
		</script>

</body>

</html>
<!DOCTYPE html>
<html lang="<?=lang('lang_code')?>">
	<head>
            <meta charset="utf-8">
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
            <meta name="description" content="<?=config_item('site_desc')?>">
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
			<link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.min.css">
			<link rel="stylesheet" href="<?=base_url()?>assets/css/font-awesome.min.css">
            <link rel="stylesheet" href="<?=base_url()?>assets/css/app.css" type="text/css">
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
            <style type="text/css">
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
            <script src="js/ie/html5shiv.js" cache="false">
            </script>
            <script src="js/ie/respond.min.js" cache="false">
            </script>
            <script src="js/ie/excanvas.js" cache="false">
            </script> <![endif]-->
	</head>
	<body class="theme-<?=config_item('top_bar_color')?> account-page">


<div class="main-wrapper">
	<!--main content start-->
      <?php  echo $template['body'];?>
      <!--main content end-->
	  </div>
	  <script>
	  var base_url = "<?php echo base_url(); ?>";
	  </script>
        <script src="<?=base_url()?>assets/js/jquery-2.2.4.min.js"></script>
        <script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
	    <script src="<?=base_url()?>assets/js/app.js"></script>

        <script type="text/javascript">
        $(document).ready(function(){

          

         $(".dropdown-toggle").click(function(){
            $(".dropdown-menu").toggle();
        });


         $('#login_user_type').change(function(){
            var user_type = $('#login_user_type option:selected').val();
            $.post(base_url + 'auth/loginuser_check/', { user_type: user_type }, function (datas) {
                var result = JSON.parse(datas);
                // console.log(result);
                $('.UserTypeName').val(result.username);
                $('.UserTypePassword').val(result.password);
            });
         });


        });
        </script>
</body>
</html>

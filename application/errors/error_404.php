<!DOCTYPE html>
<html lang="en" class="app">
<head>
  <meta charset="utf-8">
  <title>Not found - 404 Error</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="<?=base_url()?>assets/css/font-awesome.min.css">
  
    <?php 
    $family = 'Open Sans';
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
            case "source_sans": $family="Source Sans Pro";  echo "<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
			case "montserrat": $family="Montserrat";  echo "<link href='https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>"; break;
			case "fira_sans": $family="Fira Sans";  echo "<link href='https://fonts.googleapis.com/css?family=Fira+Sans:400,500,600,700' rel='stylesheet' type='text/css'>"; break;
    }
    ?>
    
  <!--[if lt IE 9]>
    <script src="js/ie/html5shiv.js"></script>
    <script src="js/ie/respond.min.js"></script>
    <script src="js/ie/excanvas.js"></script>
  <![endif]-->
</head>
<body class="">
    <div class="content">
    <div class="row m-n">
      <div class="col-sm-4 col-sm-offset-4">
        <div class="text-center m-b-lg">
          <h1 class="h text-white">404</h1>
        </div>
        <div class="list-group m-b-sm bg-white m-b-lg">
          <a href="javascript:history.back();" class="list-group-item">
            <i class="fa fa-chevron-right icon-muted"></i>
            <i class="fa fa-fw fa-home icon-muted"></i> Back to Homepage
          </a>
          <a href="#" class="list-group-item">
            <i class="fa fa-chevron-right icon-muted"></i>
            <span class="badge bg-success"><?=config_item('company_phone')?></span>
            <i class="fa fa-fw fa-phone icon-muted"></i> Call us
          </a>
          <a href="#" class="list-group-item">
            <i class="fa fa-chevron-right icon-muted"></i>
            <span class="badge bg-primary"><?=config_item('company_domain')?></span>
            <i class="fa fa-fw fa-phone icon-muted"></i> Main Website
          </a>
        </div>
      </div>
    </div>
  </div>
  <script src="<?=base_url()?>assets/js/jquery-2.2.4.min.js"></script>
</body>
</html>
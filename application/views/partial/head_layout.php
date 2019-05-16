<title><?php echo isset($html_title)? $html_title: "Login"; ?></title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<base href="<?php echo base_url();?>" />
<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon"/>

<!-- BEGIN CSS -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>	<!-- IMPORTANTE -->
<link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css"/>	<!-- IMPORTANTE -->
<link href="css/confirm/jquery-confirm.css" rel="stylesheet">
<link href="js/bootstrap-validator/bootstrapValidator.min.css" rel="stylesheet">

<?php //listado de archivos css extraidos del archivo bin original y colocados en archivos css separados?>
<!-- <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<link href="css/datetimepicker.min.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
<link href="css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
<link href="css/tabdrop.min.css" rel="stylesheet" type="text/css" />
<link href="css/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="css/file-input.min.css" rel="stylesheet" type="text/css" />
<link href="css/flags.min.css" rel="stylesheet" type="text/css" />
<link href="css/fancybox.min.css" rel="stylesheet" type="text/css" />
<link href="css/checkbox_style.css" rel="stylesheet" type="text/css" />

<?php  /*
//estos css deberian llamarse solo donde sean necesarios
<link href="css/pricing.min.css" rel="stylesheet" type="text/css" />
<link href="css/subscription_cancelled.min.css" rel="stylesheet" type="text/css" />
*/
?>
<link href="css/layouts/<?php echo $theme_layout; ?>/css/layout.min.css" rel="stylesheet" type="text/css" />
<link href="css/layouts/<?php echo $theme_layout; ?>/css/themes/<?php echo ($theme_color != '')? $theme_color : 'default'; ?>.min.css" rel="stylesheet" type="text/css" id="style_color" />
<link href="css/layouts/<?php echo $theme_layout; ?>/css/custom.min.css" rel="stylesheet" type="text/css" />
<link href="css/checkbox_style.css" rel="stylesheet" type="text/css" />


<!-- END CSS -->
 
<!-- BEGIN JAVASCRIPT-->

<script src="<?php echo base_url();?>bin/bin.min.js" type="text/javascript"></script>

<!-- JAVASCRIPT -->
<?php $this->load->view("partial/metadata_offLine");?>

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
<link href="<?php echo base_url();?>bin/bin.min.css" rel="stylesheet" type="text/css"/>	<!-- IMPORTANTE -->
<script src="<?php echo base_url();?>js/vue.js"></script>


<!-- END CSS -->
 
<!-- BEGIN JAVASCRIPT-->
<script src="<?php echo base_url();?>bin/bin.min.js" type="text/javascript"></script>

<!-- JAVASCRIPT OffLine-->
<?php $this->load->view("partial/metadata_offLine");?>

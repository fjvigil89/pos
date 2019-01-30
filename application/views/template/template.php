<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="es"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="es"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="es"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo  $title ?></title>
        <meta name="description" content="<?php echo  $description ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shorcout icon" type="<?php echo  $data['base_url'] ?>/assets/template/image/x-icon" href="images/favicon.ico">
        <link rel="apple-touch-icon" href="<?php echo  $data['base_url'] ?>/assets/template/images/apple-touch-icon.png">

	<!-- ====================== Estilos ======================= -->
	<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet">
    <?php echo  $_styles ?>

	<!--[if lt IE 9]>
		<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
	<![endif]-->

</head>

<body class="stretched">
<?php echo  $header;  ?>
<?php echo  $slider;  ?>
<?php echo  $content;  ?>
<!-- Productos -->
	<section id="content">
		<div class="content-wrap">
			<div class="container clearfix">
                <?php echo  $product ?>
                <div class="sidebar nobottommargin col_last">
                <?php echo  $sidebar ?>
                </div>
            </div>
        </div>
    </section>
<!-- #Productos -->
<?php echo  $footer;  ?>

</div>
<!-- #wrapper-->


<!-- Scripts-->
	<!-- ==================== Ir al inicio ====================== -->
	<div id="gotoTop" class="icon-angle-up"></div>

	<!-- ============= External JavaScripts ==================== -->
    <?php echo  $_scripts; ?>
	<script>
		/*---- Star rating ------*/
		$("#input-11").rating({
			starCaptions: {0: "Sin clasificar",1: "Muy malo", 2: "Malo", 3: "Esta bien", 4: "Bueno", 5: "Muy bueno"},
			starCaptionClasses: {0: "text-danger", 1: "text-danger", 2: "text-warning", 3: "text-info", 4: "text-primary", 5: "text-success"},
		});
	</script>


	<!-- Google Analytics: cambiar UA-XXXXX-X por el ID de la cuenta. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');
        </script>

</body>
</html>


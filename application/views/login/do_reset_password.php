<html>
	<head>
		<meta http-equiv="Refresh" content="5;url=<?php echo base_url(); ?>">
		<?php $this->load->view("partial/head"); ?>
	</head>
	<body class="page-md login">

		<!-- BEGIN LOGO -->
        <div class="logo">
            <a href="pos.overall.ve">
                <?php echo img(
                array(
                    'src' => $this->Appconfig->get_logo_image(),
            )); ?>
            </a>
        </div>
        <!-- END LOGO -->

		<div class="content">					           
			<div class="alert alert-success">				
				<strong><?php echo lang('login_password_has_been_reset'); ?></strong><br />
				<?php echo anchor('login', lang('login_login')); ?>				
			</div>
		</div>

		<!-- BEGIN COPYRIGHT -->
        <div class="copyright">
             2015 &copy; Ingeniando Web - POS. Versión <span class="label label-info"> <?php echo APPLICATION_VERSION; ?></span>
        </div>
        <!-- END COPYRIGHT -->
        
        <script>
        jQuery(document).ready(function() {     
          	Metronic.init(); // init metronic core components
          	Layout.init(); // init current layout
          	Login.init();
          	Demo.init();
               // init background slide images
               $.backstretch([
                //"<?php echo base_url();?>assets/admin/pages/media/bg/1.jpg",
                //"<?php echo base_url();?>assets/admin/pages/media/bg/2.jpg",
                //"<?php echo base_url();?>assets/admin/pages/media/bg/3.jpg",
                //"<?php echo base_url();?>assets/admin/pages/media/bg/4.jpg",
                //"<?php echo base_url();?>assets/admin/pages/media/bg/7.jpg",
                "<?php echo base_url();?>assets/admin/pages/media/bg/8.jpg",
                //"<?php echo base_url();?>assets/admin/pages/media/bg/9.jpg"
                ], {
                  fade: 1000,
                  duration: 8000
            }
            );

        });
        </script>
        <!-- END JAVASCRIPTS -->
	</body>
</html>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php $this->load->view("partial/head"); ?>

		<script type="text/javascript">
			$(document).ready(function()
			{
				$(".login-form input:first").focus();
			});
		</script>
	</head>

	<body class="page-md login">

		<!-- BEGIN LOGO -->
        <div class="logo">
            <a href="http://www.ingeniandoweb.com/pos">
                <?php echo img(
                array(
                    'src' => $this->Appconfig->get_logo_image(),
            )); ?>
            </a>
        </div>
        <!-- END LOGO -->

		<?php if (is_on_demo_host()) { ?>				
			<div class="alert alert-success text-center">
				<h2><?php echo lang('login_press_login_to_continue'); ?></h2>
			</div>
		<?php } ?>

		<div class="content">					           
			<?php echo form_open('login/do_reset_password/'.$key,array('class'=>'login-form')); ?>
				<h4 class="form-title"><?php echo lang('login_restart_password'); ?></h4>
				<div class="form-group">
					<div class="input-icon">
						<i class="fa fa-lock"></i>
						<?php echo form_password(array(
						'name'=>'password', 
						'class'=>'form-control placeholder-no-fix', 
						'placeholder'=>lang('login_password'), 
						'size'=>'20')); ?>
					</div>
				</div>	
				<div class="form-group">
					<div class="input-icon">
						<i class="fa fa-check"></i>
						<?php echo form_password(array(
						'name'=>'confirm_password', 
						'class'=>'form-control placeholder-no-fix', 
						'placeholder'=> lang('login_confirm_password'), 
						'size'=>'20')); ?>
					</div>
				</div>

				<div class="form-actions">
					<div class="pull-left">
						<?php echo anchor('login', lang('login_login')); ?><br />					
					</div>
					<button type="submit" class="btn green-meadow btn-block " >
			            <?php echo lang('login_restart_password'); ?>
			        </button>
					
				</div>
			</form>

			<?php if (isset($error_message)) {?>
				<div class="alert alert-danger">
					<strong><?php echo json_encode(lang('common_error')); ?></strong>
					<?php echo $error_message; ?>
				</div>
			<?php } ?>
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
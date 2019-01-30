<!DOCTYPE html>
<html lang="en">
	<head>
		<?php $this->load->view("partial/head"); ?>

		<script type="text/javascript">
			$(document).ready(function()
			{
					//If we have an empty username focus
					if ($("#login_form input:first").val() == '')
					{
						$("#login_form input:first").focus();					
					}
					else
					{
						$("#login_form input:last").focus();
					}
				});
		</script>
	</head>

	<body class="page-md login">
        <!-- BEGIN LOGO -->
        <div class="logo">
            <a href="http://www.ingeniandoweb.com">
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
			<!-- BEGIN FORGOT PASSWORD FORM -->
            <?php echo form_open('login/do_reset_password_notify',array('class'=>'forget-form', 'id'=>'form-submit')); ?>
                
                <h4 class="form-title"><?php echo lang('login_info_reset_password3'); ?></h4>
                <div class="form-group">
                    <div class="input-icon">
                        <i class="fa fa-envelope"></i>
                        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="<?php echo lang('login_username');?>" name="username_or_email"/>
                    </div>
                </div>
                <div class="form-actions">
                	<div class="btn-group btn-group btn-group-justified">
						<?php echo anchor('login', lang('login_login'), 'class="btn green"'); ?>
						<a href="javascript:void(0)" class="btn blue" onclick="document.getElementById('form-submit').submit();"><?php echo lang('login_reset_password')?></a>						
						
					</div>
                </div>
            </form>
            <!-- END FORGOT PASSWORD FORM -->
            
            <?php if (isset($error)) {?>
			    <div class="alert alert-danger">
			        <strong><?php echo lang('common_error'); ?></strong>
			        <?php echo $error; ?>
			    </div>
			<?php } else if(isset($success)){ ?>
			    <div class="alert alert-success">
			        <strong><?php echo lang('common_success'); ?></strong> 
			        <?php echo $success; ?>
			    </div>
		    <?php } ?>

		</div>

		<!-- BEGIN COPYRIGHT -->
        <div class="copyright">
             2016 &copy; Overall Web - POS. Versión <span class="label label-info"> <?php echo APPLICATION_VERSION; ?></span>
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

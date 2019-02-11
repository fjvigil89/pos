<?php $this->load->view("partial/header"); ?>
	
		<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>
				<i class='fa fa-pencil'></i>
				<?php echo lang('config_backup_overview'); ?>
			</h1>
		</div>
		<!-- END PAGE TITLE -->		
	</div>
	<!-- END PAGE HEAD -->
	<!-- BEGIN PAGE BREADCRUMB -->
	<div id="breadcrumb" class="hidden-print">
		<?php echo create_breadcrumb(); ?>
	</div>
	<!-- END PAGE BREADCRUMB -->

	<div class="clear"></div>

	<!-- BEGIN  -->
	<div class="portlet light">		
		<div class="portlet-body">
			<p><?php echo lang('config_backup_overview_desc'); ?></p>
			<p><?php echo lang('config_backup_options'); ?></p>

		 	<ol>
				<li>
					<p><?php echo lang('config_backup_mysqldump');?> <a class="letter-space" href="http://dev.mysql.com/doc/refman/5.1/en/mysqldump.html" target="_blank">http://dev.mysql.com/</a></p>
					<p><?php echo anchor('config/do_mysqldump_backup', lang('config_backup_database'), array('class' => 'btn btn-primary letter-space')); ?></p>
				</li>
				<li>
					<p><?php echo lang('config_backup_simple_option'); ?></p>
					<p><?php echo anchor('config/do_backup', lang('config_backup_database'), array('class' => 'btn btn-primary letter-space')); ?></p>
				</li>
				<li>
					<p><?php echo lang('config_backup_phpmyadmin_1'); ?> <a href="http://127.0.0.1/phpmyadmin/" target="_blank">http://127.0.0.1/phpmyadmin/</a>. <?php echo lang('config_backup_phpmyadmin_2'); ?></p>
				</li>
				<li>
					<p><?php echo lang('config_backup_control_panel');?></p>
				</li>				
			</ol>

		</div>
	</div>
	<!-- END -->

	<script type='text/javascript'>

	</script>

<?php $this->load->view("partial/footer"); ?>



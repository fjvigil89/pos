<?php
function get_css_files()
{
   $CI =& get_instance();
	$return = array(
		array('path' =>'css/bootstrap-3.min.css', 'media' => 'all'),
		array('path' =>'css/jquery.gritter.css', 'media' => 'all'),
		array('path' =>'css/jquery-ui.css', 'media' => 'all'),
		array('path' =>'css/unicorn.css', 'media' => 'all'),
		array('path' =>'css/custom.css', 'media' => 'all'),
		array('path' =>'css/datepicker.css', 'media' => 'all'),
		array('path' =>'css/bootstrap-select.css', 'media' => 'all'),
		array('path' =>'css/select2.css', 'media' => 'all'),
		array('path' =>'css/font-awesome.min.css', 'media' => 'all'),
		array('path' =>'css/jquery.loadmask.css', 'media' => 'all'), // Pronto puede ELIMINARSE
		array('path' =>'css/token-input-facebook.css', 'media' => 'all'),
		array('path' =>'css/KeyTips.min.css', 'media' => 'all'),
	);	
	if(is_rtl_lang())
	{
		$return[] = array('path' =>'css/rtl.css', 'media' => 'all');
	}
	return $return;
}

function get_js_files()
{
	if(!defined("ENVIRONMENT") or ENVIRONMENT == 'development')
	{
		return array(
			array('path' =>'js/jquery.min.js'),
			array('path' =>'js/jquery.clicktoggle.js'),
			array('path' =>'js/jquery-ui.custom.min.js'),
			
			array('path' =>'js/jquery.gritter.min.js'),
			array('path' =>'js/jquery.peity.min.js'), // Do we use this?
			array('path' =>'js/unicorn.js'),
			array('path' =>'js/jquery.dataTables.min.js'),
			array('path' =>'js/bootstrap-datatables.js'),
			array('path' =>'js/bootstrap-datepicker.js'),
			array('path' =>'js/bootstrap-select.min.js'),  // Do we use this?
			array('path' =>'js/select2.min.js'),
			array('path' =>'js/jquery.interface.js'),  // Do we use this?
			array('path' =>'js/jquery.jpanelmenu.min.js'),  // Do we use this?
			array('path' =>'js/imagePreview.js'),
			array('path' =>'js/jquery.tablesorter.min.js'),

			array('path' =>'js/common.js'), // IMPORTANTE //

			array('path' =>'js/manage_tables.js'),   // IMPORTANTE //
			array('path' =>'js/jquery.tokeninput.js'),
			array('path' =>'js/jquery.jpanelmenu.min.js'),
			array('path' =>'js/jquery.loadmask.min.js'), // Pronto puede ELIMINARSE
			array('path' =>'js/jquery.imagerollover.js'),
			array('path' => 'js/jquery.KeyTips.js'),
		);
	}

	return array(
		array('path' =>'js/all.js'),
	);



}
?>
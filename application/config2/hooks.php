<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/
$hook['post_controller_constructor'][] = array(
                                'class'    => '',
                                'function' => 'load_config',
                                'filename' => 'load_config.php',
                                'filepath' => 'hooks'
                                );

$hook['post_controller_constructor'][] = array(
                                'class'    => 'ProfilerEnabler',
                                'function' => 'EnableProfiler',
                                'filename' => 'profiler_enabler.php',
                                'filepath' => 'hooks',
                                'params'   => array()
                                );
                                
$hook['pre_system'][] = array(
                                'class'    => '',
                                'function' => 'db_select',
                                'filename' => 'db_select.php',
                                'filepath' => 'hooks',
                                'params'   => array()
                                );
                                
$hook['post_controller_constructor'][] = array(
                                'class'    => '',
                                'function' => 'subscription_check',
                                'filename' => 'subscription_check.php',
                                'filepath' => 'hooks',
                                'params'   => array()
                                );


/* End of file hooks.php */
/* Location: ./application/config/hooks.php */
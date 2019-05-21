<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| AUTO-LOADER
| -------------------------------------------------------------------
| This file specifies which systems should be loaded by default.
|
| In order to keep the framework as light-weight as possible only the
| absolute minimal resources are loaded by default. For example,
| the database is not connected to automatically since no assumption
| is made regarding whether you intend to use it.  This file lets
| you globally define which systems you would like loaded with every
| request.
|
| -------------------------------------------------------------------
| Instructions
| -------------------------------------------------------------------
|
| These are the things you can load automatically:
|
| 1. Packages
| 2. Libraries
| 3. Helper files
| 4. Custom config files
| 5. Language files
| 6. Models
|
*/

/*
| -------------------------------------------------------------------
|  Auto-load Packges
| -------------------------------------------------------------------
| Prototype:
|
|  $autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
|
*/

$autoload['packages'] = array();


/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
| These are the classes located in the system/libraries folder
| or in your application/libraries folder.
|
| Prototype:
|
|	$autoload['libraries'] = array('database', 'session', 'xmlrpc');
*/

$autoload['libraries'] = array('database','form_validation','session','user_agent', 'pagination','curl','template');


/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['helper'] = array('url', 'file');
*/

$autoload['helper'] = array('form','url','table','text','currency', 'html', 'download', 'base64', 'mailchimp', 'language', 'file', 'assets', 'sale','items','item_kits','breadcrumb','demo', 'spreadsheet');

/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['config'] = array('config1', 'config2');
|
| NOTE: This item is intended for use ONLY if you have created custom
| config files.  Otherwise, leave it blank.
|
*/

$autoload['config'] = array();


/*
| -------------------------------------------------------------------
|  Auto-load Language files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['language'] = array('lang1', 'lang2');
|
| NOTE: Do not include the "_lang" part of your file.  For example
| "codeigniter_lang.php" would be referenced as array('codeigniter');
|
*/

$autoload['language'] = array('common', 'config', 'customers', 'datetime', 'employees', 'error', 'items', 'login', 'module', 'reports', 'sales','suppliers','receivings','giftcards', 'item_kits','locations','technical_supports','warehouse','exchange',"offline","viewer");


/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['model'] = array('model1', 'model2');
|
*/

$autoload['model'] = array('Appconfig','Person','Customer','Employee','Module','Item', 'Item_taxes', 'Sale', 'Supplier','Inventory','Receiving','Giftcard', 'Item_kit', 'Item_kit_taxes', 'Item_kit_items', 'Appfile','Item_location', 'Module_action','Location','Item_location_taxes', 'Tier', 'Item_kit_location','Item_kit_location_taxes', 'Item_taxes_finder', 'Item_kit_taxes_finder', 'Register', 'Additional_item_numbers','Additional_item_seriales','Template_model','items_subcategory','technical_support','Hour_access');

/* End of file autoload.php */
/* Location: ./application/config/autoload.php */
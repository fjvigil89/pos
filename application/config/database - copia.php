<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = '127.0.0.1';
$db['default']['username'] = 'root';
$db['default']['password'] = '';
$db['default']['database'] = 'inpos_demo';
$db['default']['dbdriver'] = 'mysqli';
$db['default']['dbprefix'] = 'phppos_';
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = FALSE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_unicode_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

$db['login']['hostname'] = '127.0.0.1';
$db['login']['username'] = 'root';
$db['login']['password'] = '';
$db['login']['database'] = 'login';
$db['login']['dbdriver'] = 'mysqli';
$db['login']['dbprefix'] = 'phppos_';
$db['login']['pconnect'] = FALSE;
$db['login']['db_debug'] = TRUE;
$db['login']['cache_on'] = FALSE;
$db['login']['cachedir'] = '';
$db['login']['char_set'] = 'utf8';
$db['login']['dbcollat'] = 'utf8_unicode_ci';
$db['login']['swap_pre'] = '';
$db['login']['autoinit'] = TRUE;
$db['login']['stricton'] = FALSE;


$db['shop']['hostname'] = '127.0.0.1';
$db['shop']['username'] = 'root';
$db['shop']['password'] = '';
$db['shop']['database'] = 'inpos_demo';
$db['shop']['dbdriver'] = 'mysqli';
$db['shop']['dbprefix'] = 'phppos_';
$db['shop']['pconnect'] = FALSE;
$db['shop']['db_debug'] = TRUE;
$db['shop']['cache_on'] = FALSE;
$db['shop']['cachedir'] = '';
$db['shop']['char_set'] = 'utf8';
$db['shop']['dbcollat'] = 'utf8_unicode_ci';
$db['shop']['swap_pre'] = '';
$db['shop']['autoinit'] = TRUE;
$db['shop']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/database.php */
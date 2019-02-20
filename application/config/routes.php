<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/


$route['default_controller'] = "login";
$route['404_override'] = '';
$route['no_access/(:any)'] = "no_access/index/$1";

//Summary reports inputs
$route['reports/summary_sales'] = "reports/date_input_excel_export";
$route['reports/summary_categories'] = "reports/summary_categories_input";
$route['reports/summary_customers'] = "reports/date_input_excel_export";
$route['reports/summary_suppliers'] = "reports/date_input_excel_export";
$route['reports/summary_items'] = "reports/date_input_excel_export";
$route['reports/summary_item_kits'] = "reports/date_input_excel_export";
$route['reports/summary_employees'] = "reports/employees_date_input_excel_export";
$route['reports/summary_taxes'] = "reports/date_input_excel_export";
$route['reports/summary_discounts'] = "reports/date_input_excel_export";
$route['reports/summary_payments'] = "reports/date_input_excel_export";
$route['reports/summary_giftcards'] = "reports/excel_export";
$route['reports/summary_store_accounts'] = "reports/excel_export";
$route['reports/summary_store_payments'] = "reports/excel_export";
$route['reports/summary_profit_and_loss'] = "reports/date_input_profit_and_loss";
$route['reports/summary_commissions'] = "reports/employees_date_input_excel_export";

//Graphical reports inputs
$route['reports/graphical_summary_sales'] = "reports/date_input";
$route['reports/graphical_summary_items'] = "reports/date_input";
$route['reports/graphical_summary_item_kits'] = "reports/date_input";
$route['reports/graphical_summary_categories'] = "reports/date_input";
$route['reports/graphical_summary_suppliers'] = "reports/date_input";
$route['reports/graphical_summary_employees'] = "reports/employees_date_input";
$route['reports/graphical_summary_taxes'] = "reports/date_input";
$route['reports/graphical_summary_customers'] = "reports/date_input";
$route['reports/graphical_summary_discounts'] = "reports/date_input";
$route['reports/graphical_summary_payments'] = "reports/date_input";
$route['reports/graphical_summary_commissions'] = "reports/employees_date_input";

//Inventory report inputs
$route['reports/inventory_low'] = "reports/inventory_input";
$route['reports/inventory_summary'] = "reports/inventory_input";

//Detailed report inputs 
$route['reports/detailed_daily_cut'] = 'reports/input_daily_cut';
$route['reports/detailed_payments_cash'] = 'reports/input_detailed_payments_cash';
$route['reports/detailed_register_log'] = 'reports/date_input_excel_export_register_log';
$route['reports/defective_items_log'] = 'reports/defective_items_input';
$route['reports/sales_consolidation'] = 'reports/sales_consolidation_input';
$route['reports/detailed_sales'] = "reports/custom_input_excel_export";
$route['reports/detailed_sales2']= "reports/custom_input_excel_export";
$route['reports/detailed_sales_rate']= "reports/custom_input_excel_export";
$route['reports/detailed_receivings'] = "reports/date_input_excel_export";
$route['reports/suppliers_credit'] = "reports/suppliers_credit_specific_input";
$route['reports/detailed_giftcards'] = "reports/detailed_giftcards_input";
$route['reports/specific_customer'] = "reports/specific_customer_input";
$route['reports/specific_customer_store_account'] = "reports/specific_customer_store_account_input";
$route['reports/specific_supplier_store_payment'] = "reports/specific_supplier_store_payment_input";
$route['reports/store_account_statements'] = "reports/store_account_statements_input";
$route['reports/specific_employee'] = "reports/specific_employee_input";
$route['reports/specific_supplier'] = "reports/specific_supplier_input";
$route['reports/deleted_sales'] = "reports/custom_input_excel_export";
$route['reports/detailed_profit_and_loss'] = "reports/date_input_profit_and_loss";
$route['reports/detailed_inventory'] = "reports/detailed_inventory_input";
$route['reports/detailed_suspended_sales'] = "reports/suspended_date_input_excel_export";
$route['reports/detailed_payments'] = "reports/date_input_excel_export";
$route['reports/detailed_commissions'] = "reports/specific_employee_input";

$route['reports/specific_movement_cash'] = "reports/specific_movement_cash_input";
$route['reports/only_cash'] = "reports/specific_movement_cash_input";
$route['reports/detailed_of_payment'] = "reports/detailed_of_payment_input";

$route['reports/summary_movement_cash'] = "reports/movement_cash_date_input_excel_export";
$route['reports/specific_transfer_location'] = "reports/specific_transfer_location_date_input_excel_export";
$route['reports/depostos_salidas'] = "reports/depostos_salidas_input_excel_export";
$route['reports/movement_balance'] = "reports/movement_balance_data_input_excel_export";
$route['reports/detailed_sales_serial'] = "reports/custom_serial_input_excel_export";
$route['reports/purchase_provider']="reports/purchase_provider_input";


//$route['reports/detailed_report_table'] = "reports/tables_report";

/* End of file routes.php */
/* Location: ./application/config/routes.php */
//inventory_summary

$route['tienda'] = "template/template";
$route['tienda/category'] = "template/category";
$route['tienda/shop-item'] = "template/shop_item";
$route['tienda/contact'] = "template/contact";
$route['tienda/img/view/(:any)'] = "img/view/$1";


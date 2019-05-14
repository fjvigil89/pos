<?php

require_once ("secure_area.php");

class Reports extends Secure_area {

    function __construct() {
        parent::__construct('reports');
        $this->load->library('Pdf');
        $this->load->model("Register_movement");        
        $this->load->model("Movement_balance");
        $this->load->helper('report');
        $this->has_profit_permission = $this->Employee->has_module_action_permission('reports', 'show_profit', $this->Employee->get_logged_in_employee_info()->person_id);
        $this->has_cost_price_permission = $this->Employee->has_module_action_permission('reports', 'show_cost_price', $this->Employee->get_logged_in_employee_info()->person_id);
    }

    //Initial report listing screen
    function index() {
        $this->load->view("reports/listing", array());
    }

    // Sales Generator Reports
    function sales_generator() {
        $this->check_action_permission('view_sales_generator');

        if ($this->input->get('act') == 'autocomplete') { // Must return a json string
            if ($this->input->get('w') != '') { // From where should we return data
                if ($this->input->get('term') != '') { // What exactly are we searchin
                    //allow parallel searchs to improve performance.
                    session_write_close();

                    switch ($this->input->get('w')) {
                        case 'customers':
                            $t = $this->Customer->search($this->input->get('term'), 100, 0, 'last_name', 'asc')->result_object();
                            $tmp = array();
                            foreach ($t as $k => $v) {
                                $tmp[$k] = array('id' => $v->person_id, 'name' => $v->last_name . ", " . $v->first_name . " - " . $v->email);
                            }
                            die(json_encode($tmp));
                            break;
                        case 'employees':
                        case 'salesPerson':
                            $t = $this->Employee->search($this->input->get('term'), 100, 0, 'last_name', 'asc')->result_object();
                            $tmp = array();
                            foreach ($t as $k => $v) {
                                $tmp[$k] = array('id' => $v->person_id, 'name' => $v->last_name . ", " . $v->first_name . " - " . $v->email);
                            }
                            die(json_encode($tmp));
                            break;
                        case 'itemsCategory':
                            $t = $this->Item->get_category_suggestions($this->input->get('term'));
                            $tmp = array();
                            foreach ($t as $k => $v) {
                                $tmp[$k] = array('id' => $v['label'], 'name' => $v['label']);
                            }
                            die(json_encode($tmp));
                            break;
                        case 'suppliers':
                            $t = $this->Supplier->search($this->input->get('term'), 100, 0, 'last_name', 'asc')->result_object();
                            $tmp = array();
                            foreach ($t as $k => $v) {
                                $tmp[$k] = array('id' => $v->person_id, 'name' => $v->last_name . ", " . $v->first_name . " - " . $v->company_name . " - " . $v->email);
                            }
                            die(json_encode($tmp));
                            break;
                        case 'itemsKitName':
                            $t = $this->Item_kit->search($this->input->get('term'), 100, 0, 'name', 'asc')->result_object();
                            $tmp = array();
                            foreach ($t as $k => $v) {
                                $tmp[$k] = array('id' => $v->item_kit_id, 'name' => $v->name . " / #" . $v->item_kit_number);
                            }
                            die(json_encode($tmp));
                            break;
                        case 'itemsName':
                            $t = $this->Item->search($this->input->get('term'), FALSE, 100, 0, 'name', 'asc')->result_object();
                            $tmp = array();
                            foreach ($t as $k => $v) {
                                $tmp[$k] = array('id' => $v->item_id, 'name' => $v->name);
                            }
                            die(json_encode($tmp));
                            break;
                        case 'paymentType':
                            $t = array(lang('sales_cash'), lang('sales_check'), lang('sales_giftcard'), lang('sales_debit'), lang('sales_credit'));

                            if ($this->config->item('customers_store_accounts')) {
                                $t[] = lang('sales_store_account');
                            }

                            foreach ($this->Appconfig->get_additional_payment_types() as $additional_payment_type) {
                                $t[] = $additional_payment_type;
                            }

                            $tmp = array();
                            foreach ($t as $k => $v) {
                                $tmp[$k] = array('id' => $v, 'name' => $v);
                            }
                            die(json_encode($tmp));
                            break;
                    }
                } else {
                    die;
                }
            } else {
                die(json_encode(array('value' => 'No such data found!')));
            }
        }
 
        $data = $this->_get_common_report_data();
        $data["title"] = lang('reports_sales_generator');
        $data["subtitle"] = lang('reports_sales_report_generator');
        $setValues = array('report_type' => '', 'sreport_date_range_simple' => '',
            'start_month' => date("m"), 'start_day' => date('d'), 'start_year' => date("Y"),
            'end_month' => date("m"), 'end_day' => date('d'), 'end_year' => date("Y"),
            'matchType' => '',
            'matched_items_only' => FALSE
        );
        foreach ($setValues as $k => $v) {
            if (empty($v) && !isset($data[$k])) {
                $data[$k] = '';
            } else {
                $data[$k] = $v;
            }
        }
        if ($this->input->get('generate_report')) { // Generate Custom Raport
            $data['report_type'] = $this->input->get('report_type');
            $data['sreport_date_range_simple'] = $this->input->get('report_date_range_simple');
            $data['start_month'] = $this->input->get('start_month');
            $data['start_day'] = $this->input->get('start_day');
            $data['start_year'] = $this->input->get('start_year');
            $data['end_month'] = $this->input->get('end_month');
            $data['end_day'] = $this->input->get('end_day');
            $data['end_year'] = $this->input->get('end_year');
            if ($data['report_type'] == 'simple') {
                $q = explode("/", $data['sreport_date_range_simple']);
                list($data['start_year'], $data['start_month'], $data['start_day']) = explode("-", $q[0]);
                list($data['end_year'], $data['end_month'], $data['end_day']) = explode("-", $q[1]);
                $interval = array(
                    'start_date' => $q[0],
                    'end_date' => $q[1]
                );
            } else {

                $interval = array(
                    'start_date' => $this->input->get('start_date'),
                    'end_date' => $this->input->get('end_date') . ' 23:59:59'
                );
            }



            $data['matchType'] = $this->input->get('matchType');
            $data['matched_items_only'] = $this->input->get('matched_items_only') ? TRUE : FALSE;

            $data['field'] = $this->input->get('field');
            $data['condition'] = $this->input->get('condition');
            $data['value'] = $this->input->get('value');

            $data['prepopulate'] = array();

            $field = $this->input->get('field');
            $condition = $this->input->get('condition');
            $value = $this->input->get('value');

            $tmpData = array();
            foreach ($field as $a => $b) {
                $uData = explode(",", $value[$a]);
                $tmp = $tmpID = array();
                switch ($b) {
                    case '1': // Customer
                        $t = $this->Customer->get_multiple_info($uData)->result_object();
                        foreach ($t as $k => $v) {
                            $tmpID[] = $v->person_id;
                            $tmp[$k] = array('id' => $v->person_id, 'name' => $v->last_name . ", " . $v->first_name . " - " . $v->email);
                        }
                        break;
                    case '2': // Item Serial Number
                        $tmpID[] = $value[$a];
                        break;
                    case '3': // Employees
                        $t = $this->Employee->get_multiple_info($uData)->result_object();
                        foreach ($t as $k => $v) {
                            $tmpID[] = $v->person_id;
                            $tmp[$k] = array('id' => $v->person_id, 'name' => $v->last_name . ", " . $v->first_name . " - " . $v->email);
                        }
                        break;
                    case '4': // Items Category
                        foreach ($uData as $k => $v) {
                            $tmpID[] = $v;
                            $tmp[$k] = array('id' => $v, 'name' => $v);
                        }
                        break;
                    case '5': // Suppliers
                        $t = $this->Supplier->get_multiple_info($uData)->result_object();
                        foreach ($t as $k => $v) {
                            $tmpID[] = $v->person_id;
                            $tmp[$k] = array('id' => $v->person_id, 'name' => $v->last_name . ", " . $v->first_name . " - " . $v->company_name . " - " . $v->email);
                        }
                        break;
                    case '6': // Sale Type
                        $tmpID[] = $condition[$a];
                        break;
                    case '7': // Sale Amount
                        $tmpID[] = $value[$a];
                        break;
                    case '8': // Item Kits
                        $t = $this->Item_kit->get_multiple_info($uData)->result_object();
                        foreach ($t as $k => $v) {
                            $tmpID[] = $v->item_kit_id;
                            $tmp[$k] = array('id' => $v->item_kit_id, 'name' => $v->name . " / #" . $v->item_kit_number);
                        }
                        break;
                    case '9': // Items Name
                        $t = $this->Item->get_multiple_info($uData)->result_object();
                        foreach ($t as $k => $v) {
                            $tmpID[] = $v->item_id;
                            $tmp[$k] = array('id' => $v->item_id, 'name' => $v->name);
                        }
                        break;
                    case '10': // SaleID
                        if (strpos(strtolower($value[$a]), strtolower($this->config->item('sale_prefix'))) !== FALSE) {
                            $value[$a] = (int) substr(strtolower($value[$a]), strpos(strtolower($value[$a]), $this->config->item('sale_prefix') . ' ') + strlen(strtolower($this->config->item('sale_prefix')) . ' '));
                        }
                        $tmpID[] = $value[$a];
                        break;
                    case '11': // Payment type
                        foreach ($uData as $k => $v) {
                            $tmpID[] = $v;
                            $tmp[$k] = array('id' => $v, 'name' => $v);
                        }
                        break;

                    case '12': // Sale Item Description
                        $tmpID[] = $value[$a];
                        break;
                    case '13': // Employees
                        $t = $this->Employee->get_multiple_info($uData)->result_object();
                        foreach ($t as $k => $v) {
                            $tmpID[] = $v->person_id;
                            $tmp[$k] = array('id' => $v->person_id, 'name' => $v->last_name . ", " . $v->first_name . " - " . $v->email);
                        }
                        break;
                }
                $data['prepopulate']['field'][$a][$b] = $tmp;
                $tmpData[] = array('f' => $b, 'o' => $condition[$a], 'i' => $tmpID);
            }

            $params['matchType'] = $data['matchType'];
            $params['matched_items_only'] = $data['matched_items_only'];
            $params['ops'] = array(
                1 => " = 'xx'",
                2 => " != 'xx'",
                5 => " IN ('xx')",
                6 => " NOT IN ('xx')",
                7 => " > xx",
                8 => " < xx",
                9 => " = xx",
                10 => '', // Sales
                11 => '', // Returns
            );

            $params['tables'] = array(
                1 => 'sales_items_temp.customer_id', // Customers
                2 => 'sales_items_temp.serialnumber', // Item Sale Serial number
                3 => 'sales_items_temp.employee_id', // Employees
                4 => 'sales_items_temp.category', // Item Category
                5 => 'sales_items_temp.supplier_id', // Suppliers
                6 => '', // Sale Type
                7 => '', // Sale Amount
                8 => 'sales_items_temp.item_kit_id', // Item Kit Name
                9 => 'sales_items_temp.item_id', // Item Name
                10 => 'sales_items_temp.sale_id', // Sale ID
                11 => 'sales_items_temp.payment_type', // Payment Type
                12 => 'sales_items_temp.description', // Item Sale Serial number
                13 => 'sales_items_temp.sold_by_employee_id', // Item Sale Serial number
            );
            $params['values'] = $tmpData;
            $params['offset'] = $this->input->get('per_page') ? $this->input->get('per_page') : 0;
            $params['export_excel'] = $this->input->get('export_excel');

            $this->load->model('reports/Sales_generator');
            $model = $this->Sales_generator;
            $model->setParams($params);
            $this->Sale->create_sales_items_temp_table($interval);
            $config = array();

            //Remove per_page from url so we don't have it duplicated
            $config['base_url'] = preg_replace('/&per_page=[0-9]*/', '', current_url());
            $config['total_rows'] = $model->getTotalRows();
            $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
            $config['page_query_string'] = TRUE;
            $this->pagination->initialize($config);

            $tabular_data = array();
            $report_data = $model->getData();

            $summary_data = array();
            $details_data = array();

            foreach ($report_data['summary'] as $key => $row) {
                $summary_data_row = array();
                $summary_data_row[] = array('data' => anchor('sales/receipt/' . $row['sale_id'], '<i class="fa fa-print fa fa-2x vertical-align"></i>', array('target' => '_blank')) . ' ' . anchor('sales/edit/' . $row['sale_id'], '<i class="fa fa-file-alt fa fa-2x"></i>', array('target' => '_blank')) . ' ' . anchor('sales/edit/' . $row['sale_id'], "<i class='fa fa-pencil'></i>" . lang('common_edit') . ' ' . $row['sale_id'], array('target' => '_blank', 'class' => 'btn btn-xs default')), 'align' => 'left');
                $summary_data_row[] = array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['sale_time'])), 'align' => 'left');
                $summary_data_row[] = array('data' => $row['register_name'], 'align' => 'left');
                $summary_data_row[] = array('data' => to_quantity($row['items_purchased']), 'align' => 'left');
                $summary_data_row[] = array('data' => $row['employee_name'] . ($row['sold_by_employee'] && $row['sold_by_employee'] != $row['employee_name'] ? '/' . $row['sold_by_employee'] : ''), 'align' => 'left');
                $summary_data_row[] = array('data' => $row['customer_name'], 'align' => 'left');
                $summary_data_row[] = array('data' => to_currency($row['subtotal']), 'align' => 'right');
                $summary_data_row[] = array('data' => to_currency($row['total']), 'align' => 'right');
                $summary_data_row[] = array('data' => to_currency($row['tax']), 'align' => 'right');

                if ($this->has_profit_permission) {
                    $summary_data_row[] = array('data' => to_currency($row['profit']), 'align' => 'right');
                }
                $summary_data_row[] = array('data' => $row['payment_type'], 'align' => 'right');
                $summary_data_row[] = array('data' => $row['comment'], 'align' => 'right');


                $summary_data[$key] = $summary_data_row;

                foreach ($report_data['details'][$key] as $drow) {
                    $details_data_row = array();

                    $details_data_row[] = array('data' => isset($drow['item_number']) ? $drow['item_number'] : $drow['item_kit_number'], 'align' => 'left');
                    $details_data_row[] = array('data' => isset($drow['item_product_id']) ? $drow['item_product_id'] : $drow['item_kit_product_id'], 'align' => 'left');
                    $details_data_row[] = array('data' => isset($drow['item_name']) ? anchor('items/view/' . $drow['item_id'], $drow['item_name']) : anchor('item_kits/view/' . $drow['item_kit_id'], $drow['item_kit_name']), 'align' => 'left');
                    $details_data_row[] = array('data' => $drow['category'], 'align' => 'left');
                    $details_data_row[] = array('data' => $drow['size'], 'align' => 'left');
                    $details_data_row[] = array('data' => $drow['serialnumber'], 'align' => 'left');
                    $details_data_row[] = array('data' => $drow['description'], 'align' => 'left');
                    $details_data_row[] = array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left');
                    $details_data_row[] = array('data' => to_currency($drow['subtotal']), 'align' => 'right');
                    $details_data_row[] = array('data' => to_currency($drow['total']), 'align' => 'right');
                    $details_data_row[] = array('data' => to_currency($drow['tax']), 'align' => 'right');

                    if ($this->has_profit_permission) {
                        $details_data_row[] = array('data' => to_currency($drow['profit']), 'align' => 'right');
                    }

                    $details_data_row[] = array('data' => $drow['discount_percent'] . '%', 'align' => 'left');
                    $details_data[$key][] = $details_data_row;
                }
            }

            $reportdata = array(
                "title" => lang('reports_sales_generator'),
                "subtitle" => lang('reports_sales_report_generator') . " - " . date(get_date_format(), strtotime($interval['start_date'])) . '-' . date(get_date_format(), strtotime($interval['end_date'])) . " - " . $config['total_rows'] . ' ' . lang('reports_sales_report_generator_results_found'),
                "headers" => $model->getDataColumns(),
                "summary_data" => $summary_data,
                "details_data" => $details_data,
                "overall_summary_data" => $model->getSummaryData(),
                'pagination' => $this->pagination->create_links(),
                'export_excel' => $this->input->get('export_excel'),
            );

            // Fetch & Output Data

            if (!$this->input->get('export_excel')) {
                $data['results'] = $this->load->view("reports/sales_generator_tabular_details", $reportdata, true);
            }
        }

        if (!$this->input->get('export_excel')) {
            $this->load->view("reports/sales_generator", $data);
        } else { //Excel export use regular tabular_details
            $this->load->view("reports/tabular_details", $reportdata);
        }
    }

    function _get_common_report_data($time = false) {
        $data = array();
        $data['report_date_range_simple'] = get_simple_date_ranges($time);
        $data['months'] = get_months();
        $data['days'] = get_days();
        $data['years'] = get_years();
        $data['hours'] = get_hours($this->config->item('time_format'));
        $data['minutes'] = get_minutes();
        $data['selected_month'] = date('m');
        $data['selected_day'] = date('d');
        $data['selected_year'] = date('Y');
        $data['reports_select'] = array('Entradas', 'Salidas', 'Entradas y Salidas');
        //$data['reports_select']=array('Efectivo');

        return $data;
    }
    function detailed_payments_cash($start_date, $end_date, $customer_id,  $export_excel = 0, $export_pdf = 0, $offset = 0){

        $this->check_action_permission('view_customers');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);
        $this->load->model('reports/Payments_cash');
        $model = $this->Payments_cash;
        $location_id=$this->Employee->get_logged_in_employee_current_location_id();
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date,"customer_id"=>$customer_id,"export_excel"=>$export_excel,"export_pdf"=>$export_pdf,'offset' => $offset,"location_id"=>$location_id));
        $config = array();
        $config['base_url'] = site_url("reports/detailed_payments_cash/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$customer_id/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 8;

        $this->pagination->initialize($config);
        
        $tabular_data = array();
        $report_data = $model->getData();

        foreach ($report_data["details"] as $row) {
            $data_row = array();
            $data_row[] = array('data' => anchor('payments/receipt/' . $row['petty_cash_id'], '<i class="fa fa-print fa fa-2x vertical-align"></i>', array('target' => '_blank', 'class' => 'hidden-print')) ,'align' => 'left' );
            
            $data_row[] = array('data' => $row['petty_cash_id'], 'align' => 'right');

            $data_row[] = array('data' => date(get_date_format() . ' ' . get_time_format(), strtotime($row['petty_cash_time'])), 'align' => 'right');
            //$data_row[] = array('data' => "", 'align' => 'right');

            $data_row[] = array('data' => $row['payment_type'], 'align' => 'right');

            
            $data_row[] = array('data' =>to_currency($row['monton_total']), 'align' => 'right');
            
            $tabular_data[] = $data_row;
        }
        

        $data = array(
            "title" => "Abonos  detallado",
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular", $data);
    
    }
    function input_detailed_payments_cash() {
        $data = $this->_get_common_report_data(TRUE);
        $this->load->view("reports/detailed_input_payments_cash",$data);
    }
    //--- corte diario- tabular
    function input_daily_cut() {
        $data = $this->_get_common_report_data(TRUE);
        $this->load->view("reports/detailed_input_daily_cut",$data);
    }

    function detailed_daily_cut($start_date, $end_date, $export_excel = 0, $export_pdf = 0, $offset = 0){
        $this->check_action_permission('daily_cut');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Detailed_daily_cut');
        $model = $this->Detailed_daily_cut;
        $end_date = date('Y-m-d 23:59:59', strtotime($end_date)); 
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date));
        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date));
        $this->Receiving->create_receivings_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date));
        $this->Register_movement->create_movement_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date));

        $data = array(
            "title" => lang('reports_daily_cut'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "details_data" => $model->getData(),
            "overall_summary_data" => $model->getSummaryData(),
        );
       // $this->load->view("reports/profit_and_loss_details", $data);
    
        $this->load->view("reports/tabular_cut",$data);
    }
    //Input for reports that require only a date range and an export to excel. (see routes.php to see that all summary reports route here)
    function date_input_excel_export() {
        $data = $this->_get_common_report_data(TRUE);
        $this->load->view("reports/date_input_excel_export", $data);
    }

    function detailed_receivings_input(){
        $data = $this->_get_common_report_data(TRUE);
        $data['specific_input_name'] = lang('reports_supplier');
        $data['search_suggestion_url'] = site_url('reports/supplier_search');
        $this->load->view("reports/detailed_receivings_input", $data);
    }

    function suppliers_credit_specific_input() {
        $data = $this->_get_common_report_data(TRUE);
        $data['specific_input_name'] = lang('reports_supplier');
        $data['search_suggestion_url'] = site_url('reports/supplier_search');
        $this->load->view("reports/suppliers_credit_specific_input", $data);
    }
   
    function custom_input_excel_export() {
        $data = $this->_get_common_report_data(TRUE);
        $this->load->view("reports/custom_input_excel_export", $data);
    }
    
    function custom_serial_input_excel_export() {
        $data = $this->_get_common_report_data(TRUE);
        $this->load->view("reports/custom_serial_input_excel_export", $data);
    }
    function purchase_provider_input() {
        $data = $this->_get_common_report_data(TRUE);
        $this->load->view("reports/purchase_provider_input", $data);
    }
    

    function summary_categories_input() {
        $data = $this->_get_common_report_data(TRUE);
        $this->load->view("reports/summary_categories_input", $data);
    }

    function suspended_date_input_excel_export() {
        $data = $this->_get_common_report_data(TRUE);
        $this->load->view("reports/suspended_date_input_excel_export", $data);
    }

    function employees_date_input_excel_export() {
        $data = $this->_get_common_report_data(TRUE);
        $this->load->view("reports/employees_date_input_excel_export", $data);
    }

    /** added for register log */
    function date_input_excel_export_register_log() {
        $data = $this->_get_common_report_data();
        $this->load->view("reports/date_input_excel_register_log.php", $data);
    }
   
    function detailed_payment() {
        $data = $this->_get_common_report_data(TRUE);
        $data['payment_options'] = array(
            lang('sales_all') => lang('sales_all'),
            lang('sales_cash') => lang('sales_cash'),
            lang('sales_check') => lang('sales_check'),
            lang('sales_giftcard') => lang('sales_giftcard'),
            lang('sales_debit') => lang('sales_debit'),
            lang('sales_credit') => lang('sales_credit')
        );

        if ($this->config->item('customers_store_accounts')) {

            $data['payment_options'] = array_merge($data['payment_options'], array(lang('sales_store_account') => lang('sales_store_account')));
        }
        foreach ($this->Appconfig->get_additional_payment_types() as $additional_payment_type) {
            $data['payment_options'][$additional_payment_type] = $additional_payment_type;
        }

        $this->load->view("reports/detailed_payment.php", $data);
    }

    /** also added for register log */
    function purchase_provider($start_date, $end_date, $sale_type, $sale_ticket, $export_pdf = 0,$export_excel = 0, $offset = 0) 
    {

        $this->check_action_permission('view_receivings');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);
        $sale_ticket = rawurldecode(-1);
        $this->load->model('reports/Detailed_purchase_provider');
        $model = $this->Detailed_purchase_provider;
        $id_employee_login= $this->Employee->get_logged_in_employee_info()->person_id;
        if($this->Employee->has_module_action_permission('sales', 'see_sales_uniqued',$id_employee_login)){
           $id_employee_login=FALSE;
        }
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel,
            'export_pdf' => $export_pdf,
            'sale_ticket' => $sale_ticket,
            "id_employee_login"=>$id_employee_login
        ));
       


        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'sale_ticket' => $sale_ticket, "id_employee_login"=>$id_employee_login));
        $config = array();
        $config['base_url'] = site_url("reports/purchase_provider/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$export_excel/$export_pdf/$sale_ticket");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 9;
        $this->pagination->initialize($config);

        $headers = $model->getDataColumns();

        $CI = & get_instance();
        $flag = $CI->Employee->has_module_action_permission('sales', 'see_sales_uniqued', $CI->Employee->get_logged_in_employee_info()->person_id);
        $report_data = $model->getData($CI->Employee->get_logged_in_employee_info()->person_id, $flag);

        $summary_data = array();

        
        $details_data = array();

        foreach ($report_data['summary'] as $key => $row) {

            $summary_data_row = array();
            if ($export_excel == 1 || $export_pdf == 1) {
                $edit = '';
            } else {
                $edit = lang('common_edit');
            }
            $link = site_url('reports/specific_customer/' . $start_date . '/' . $end_date . '/' . $row['customer_id'] . '/all/0');

            $summary_data_row[] = array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['sale_time'])), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['is_invoice'] ? ucfirst(strtolower($this->config->item('sale_prefix'))) : 'Boleta', 'align' => 'left');
            $summary_data_row[] = array('data' => $row['is_invoice'] ? $row['invoice_number'] : $row['ticket_number'], 'align' => 'left');
            $summary_data_row[] = array('data' => $row['number_orden'], 'align' => 'left');
            $summary_data_row[] = array('data' => to_quantity($row['items_purchased']), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['employee_name'] . ($row['sold_by_employee'] && $row['sold_by_employee'] != $row['employee_name'] ? '/' . $row['sold_by_employee'] : ''), 'align' => 'left');
            $summary_data_row[] = array('data' => '<a href="' . $link . '" target="_blank">' . $row['customer_name'] . '</a>', 'align' => 'left');
            $summary_data_row[] = array('data' => to_currency($row['item_cost_price']), 'align' => 'right');
            $summary_data_row[] = array('data' => to_currency($row['item_unit_price']), 'align' => 'right');


            $summary_data[$key] = $summary_data_row;


            foreach ($report_data['details'][$key] as $drow) {
                
                $details_data_row = array();

                $details_data_row[] = array('data' => isset($drow['item_number']) ? $drow['item_number'] : $drow['item_kit_number'], 'align' => 'left');
                
                $details_data_row[] = array('data' => isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align' => 'left');
                $details_data_row[] = array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left');
                $details_data_row[] = array('data' => $drow["company_name_proveedor"], 'align' => 'left');

                $details_data_row[] = array('data' => to_currency($drow["item_cost_price"]), 'align' => 'left');
                $details_data_row[] = array('data' => to_currency($drow["item_unit_price"]), 'align' => 'right');

                

                $details_data[$key][] = $details_data_row;
            }
        }

        $data = array(
            "title" => "Compra proveedor",
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "summary_data" => $summary_data,
            "details_data" => $details_data,
            "overall_summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular_details", $data);
    }
    function defective_items_input() {
        $data = $this->_get_common_report_data(TRUE);
        $this->load->view("reports/defective_items_input", $data);
    }
    function detailed_register_log($start_date, $end_date, $export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_register_log');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Detailed_register_log');
        $model = $this->Detailed_register_log;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'offset' => $offset, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf));

        $config = array();
        $config['base_url'] = site_url("reports/detailed_register_log/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 6;
        $this->pagination->initialize($config);

        $headers = $model->getDataColumns();
        $report_data = $model->getData();

        $summary_data = array();
        $details_data = array();

        foreach ($report_data as $row) {
            $receipt="";
            if ($row['shift_end'] == '0000-00-00 00:00:00') {
                $shift_end = lang('reports_register_log_open');
                $delete = anchor('reports/delete_register_log/' . $row['register_log_id'] . '/' . $start_date . '/' . $end_date, lang('common_delete'), "onclick='return confirm(" . json_encode(lang('reports_confirm_register_log_delete')) . ")'");
                $receipt="<br><strong>Abierto</strong>";
            } else {
                $shift_end = date(get_date_format(), strtotime($row['shift_end'])) . ' ' . date(get_time_format(), strtotime($row['shift_end']));
                $delete = anchor('reports/delete_register_log/' . $row['register_log_id'] . '/' . $start_date . '/' . $end_date, lang('common_delete'), "onclick='return confirm(" . json_encode(lang('reports_confirm_register_log_delete')) . ")'");
                $receipt="<br>" .anchor('registers_movement/receipt_register/' . $row['register_log_id'], '<i class="fa fa-print fa fa-2x vertical-align"></i>', array('target' => '_blank', 'class' => 'hidden-print'));

            }
            $summary_data[] = array(
                array('data' => $delete. $receipt, 'align' => 'left'),
                array('data' => $row['register_name'], 'align' => 'left'),
                array('data' => $row['open_first_name'] . ' ' . $row['open_last_name'], 'align' => 'left'),
                array('data' => $row['close_first_name'] . ' ' . $row['close_last_name'], 'align' => 'left'),
                array('data' => date(get_date_format(), strtotime($row['shift_start'])) . ' ' . date(get_time_format(), strtotime($row['shift_start'])), 'align' => 'left'),
                array('data' => $shift_end, 'align' => 'left'),
                array('data' => to_currency($row['open_amount']), 'align' => 'right'),
                array('data' => to_currency($row['close_amount']), 'align' => 'right'),
                array('data' => to_currency($row['cash_sales_amount']), 'align' => 'right'),
                array('data' => to_currency($row['difference']), 'align' => 'right')
            );
        }

        $data = array(
            "title" => lang('reports_register_log_title'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $summary_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular", $data);
    }

    function defective_items_log($start_date, $end_date, $export_excel = 0, $export_pdf = 0, $offset = 0) {
        //verificar
        $this->check_action_permission('view_inventory_reports');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Defective_item_audit');
        $model = $this->Defective_item_audit;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'offset' => $offset, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf));

        $config = array();
        $config['base_url'] = site_url("reports/defective_items_log/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 6;
        $this->pagination->initialize($config);

        $headers = $model->getDataColumns();
        $report_data = $model->getData();

        $summary_data = array();
        $details_data = array();

        foreach ($report_data as $row) {

            $summary_data[] = array(
                array('data' => $row['name'], 'align' => 'left'),
                array('data' => $row['quantity'], 'align' => 'left'),
                array('data' => $row['description'], 'align' => 'left'),
                array('data' => $row['timestamp'], 'align' => 'left'),
                array('data' => $row['username'], 'align' => 'left'),
            );
        }

        $data = array(
            "title" => lang('reports_defective_items_log_title'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $summary_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );
        $this->load->view("reports/tabular", $data);
    }

    function delete_register_log($register_log_id, $start_date, $end_date) {
        $this->load->model('reports/Detailed_register_log');
        if ($this->Detailed_register_log->delete_register_log($register_log_id)) {
            redirect('reports/detailed_register_log/' . $start_date . '/' . $end_date);
        }
    }

    //Summary sales report
    function summary_sales($start_date, $end_date, $sale_type, $export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_sales');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Summary_sales');
        $model = $this->Summary_sales;
        $id_employee_login= $this->Employee->get_logged_in_employee_info()->person_id;
        if($this->Employee->has_module_action_permission('sales', 'see_sales_uniqued',$id_employee_login)){
           $id_employee_login=FALSE;
        }
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf,"id_employee_login"=>$id_employee_login));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type,"id_employee_login"=>$id_employee_login));

        $config = array();
        $config['base_url'] = site_url("reports/summary_sales/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 8;
        $this->pagination->initialize($config);

        $tabular_data = array();
        $report_data = $model->getData();

        foreach ($report_data as $row) {
            $data_row = array();

            $data_row[] = array('data' => date(get_date_format(), strtotime($row['sale_date'])), 'align' => 'left');
            $data_row[] = array('data' => to_currency($row['subtotal']), 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['total']), 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['tax']), 'align' => 'right');

            if ($this->has_profit_permission) {
                $data_row[] = array('data' => to_currency($row['profit']), 'align' => 'right');
            }
            $tabular_data[] = $data_row;
        }
        $data = array(
            "title" => lang('reports_sales_summary_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links()
        );

        $this->load->view("reports/tabular", $data);
    }

    //Summary categories report
    function summary_categories($start_date, $end_date, $sale_type, $export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_categories');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Summary_categories');
        $model = $this->Summary_categories;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $config = array();
        $config['base_url'] = site_url("reports/summary_categories/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 7;

        $this->pagination->initialize($config);

        $tabular_data = array();
        $report_data = $model->getData();

        foreach ($report_data as $row) {
            $data_row = array();

            $data_row[] = array('data' => $row['category'], 'align' => 'left');
            $data_row[] = array('data' => to_currency($row['subtotal']), 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['total']), 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['tax']), 'align' => 'right');
            if ($this->has_profit_permission) {
                $data_row[] = array('data' => to_currency($row['profit']), 'align' => 'right');
            }
            $data_row[] = array('data' => floatval($row['item_sold']), 'align' => 'center');
            $tabular_data[] = $data_row;
        }

        $data = array(
            "title" => lang('reports_categories_summary_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular", $data);
    }

    //Summary customers report
    function summary_customers($start_date, $end_date, $sale_type, $export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_customers');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Summary_customers');
        $model = $this->Summary_customers;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $config = array();
        $config['base_url'] = site_url("reports/summary_customers/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 7;
        $this->pagination->initialize($config);

        $tabular_data = array();
        $report_data = $model->getData();
        $no_customer = $model->getNoCustomerData();
        $report_data = array_merge($no_customer, $report_data);

        foreach ($report_data as $row) {
            $data_row = array();

            $data_row[] = array('data' => $row['customer'], 'align' => 'left');
            $data_row[] = array('data' => to_currency($row['subtotal']), 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['total']), 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['tax']), 'align' => 'right');
            if ($this->has_profit_permission) {
                $data_row[] = array('data' => to_currency($row['profit']), 'align' => 'right');
            }

            $tabular_data[] = $data_row;
        }

        $data = array(
            "title" => lang('reports_customers_summary_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular", $data);
    }

    //Summary suppliers report
    function summary_suppliers($start_date, $end_date, $sale_type, $export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_suppliers');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Summary_suppliers');
        $model = $this->Summary_suppliers;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
        $config = array();
        $config['base_url'] = site_url("reports/summary_suppliers/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 7;
        $this->pagination->initialize($config);

        $tabular_data = array();
        $report_data = $model->getData();

        foreach ($report_data as $row) {
            $data_row = array();

            $data_row[] = array('data' => $row['supplier'], 'align' => 'left');
            $data_row[] = array('data' => to_currency($row['subtotal']), 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['total']), 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['tax']), 'align' => 'right');

            if ($this->has_profit_permission) {
                $data_row[] = array('data' => to_currency($row['profit']), 'align' => 'right');
            }
            $tabular_data[] = $data_row;
        }

        $data = array(
            "title" => lang('reports_suppliers_summary_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular", $data);
    }

    //Summary items report
    function summary_items($start_date, $end_date, $sale_type, $export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_items');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Summary_items');
        $model = $this->Summary_items;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $config = array();
        $config['base_url'] = site_url("reports/summary_items/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 7;

        $this->pagination->initialize($config);

        $tabular_data = array();
        $report_data = $model->getData();

        foreach ($report_data as $row) {
            $url = $row['image_id'] ? site_url('app_files/view/' . $row['image_id']) : (base_url() . '/img/icons/32/no-image-small-square.png');
            $image = array(
                'src' => $url,
                'id' => 'avatar',
                'class' => 'rollover',
                'width' => '45'
            );
            $data_row = array();
            if ($this->config->item('show_image') == 1) {
                $data_row[] = array('data' => "<a class='rollover' href='$url'>" . img($image) . "</a>", 'align' => 'left');
            }
            $data_row[] = array('data' => $row['name'], 'align' => 'left');
            $data_row[] = array('data' => $row['colour'], 'align' => 'left');
            $data_row[] = array('data' => $row['model'], 'align' => 'left');
            $data_row[] = array('data' => $row['marca'], 'align' => 'left');
            $data_row[] = array('data' => $row['item_number'], 'align' => 'left');
            $data_row[] = array('data' => $row['product_id'], 'align' => 'left');
            $data_row[] = array('data' => $row['category'], 'align' => 'left');
            $data_row[] = array('data' => to_quantity($row['quantity']), 'align' => 'left');
            $data_row[] = array('data' => to_quantity($row['quantity_purchased']), 'align' => 'left');
            $data_row[] = array('data' => to_currency($row['subtotal']), 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['total']), 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['tax']), 'align' => 'right');
            if ($this->has_profit_permission) {
                $data_row[] = array('data' => to_currency($row['profit']), 'align' => 'right');
            }
            $tabular_data[] = $data_row;
        }

        $data = array(
            "title" => lang('reports_items_summary_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links()
        );

        $this->load->view("reports/tabular", $data);
    }

    //Summary item kits report
    function summary_item_kits($start_date, $end_date, $sale_type, $export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_item_kits');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Summary_item_kits');
        $model = $this->Summary_item_kits;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
        $config = array();
        $config['base_url'] = site_url("reports/summary_item_kits/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 7;
        $this->pagination->initialize($config);

        $tabular_data = array();
        $report_data = $model->getData();

        foreach ($report_data as $row) {
            $data_row = array();

            $data_row[] = array('data' => $row['name'], 'align' => 'left');
            $data_row[] = array('data' => to_quantity($row['quantity_purchased']), 'align' => 'left');
            $data_row[] = array('data' => to_currency($row['subtotal']), 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['total']), 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['tax']), 'align' => 'right');
            if ($this->has_profit_permission) {
                $data_row[] = array('data' => to_currency($row['profit']), 'align' => 'right');
            }
            $tabular_data[] = $data_row;
        }

        $data = array(
            "title" => lang('reports_item_kits_summary_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular", $data);
    }

    //Summary employees report
    function summary_employees($start_date, $end_date, $sale_type, $employee_type, $export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_employees');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Summary_employees');
        $model = $this->Summary_employees;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'employee_type' => $employee_type, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
        $config = array();
        $config['base_url'] = site_url("reports/summary_employees/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$employee_type/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 8;
        $this->pagination->initialize($config);

        $tabular_data = array();
        $report_data = $model->getData();

        foreach ($report_data as $row) {
            $data_row = array();

            $data_row[] = array('data' => $row['employee'], 'align' => 'left');
            $data_row[] = array('data' => to_currency($row['subtotal']), 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['total']), 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['tax']), 'align' => 'right');
            if ($this->has_profit_permission) {
                $data_row[] = array('data' => to_currency($row['profit']), 'align' => 'right');
            }

            $tabular_data[] = $data_row;
        }

        $data = array(
            "title" => lang('reports_employees_summary_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular", $data);
    }

    //Summary taxes report
    function summary_taxes($start_date, $end_date, $sale_type, $export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_taxes');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Summary_taxes');
        $model = $this->Summary_taxes;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $config = array();
        $config['base_url'] = site_url("reports/summary_taxes/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 7;
        $this->pagination->initialize($config);

        $tabular_data = array();
        $report_data = $model->getData();

        foreach ($report_data as $row) {
            $tabular_data[] = array(array('data' => $row['name'], 'align' => 'left'), array('data' => to_currency($row['subtotal']), 'align' => 'left'), array('data' => to_currency($row['tax']), 'align' => 'left'), array('data' => to_currency($row['total']), 'align' => 'left'));
        }

        $data = array(
            "title" => lang('reports_taxes_summary_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links()
        );

        $this->load->view("reports/tabular", $data);
    }

    //Summary discounts report
    function summary_discounts($start_date, $end_date, $sale_type, $export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_discounts');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Summary_discounts');
        $model = $this->Summary_discounts;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $config = array();
        $config['base_url'] = site_url("reports/summary_discounts/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 7;
        $this->pagination->initialize($config);

        $tabular_data = array();
        $report_data = $model->getData();

        foreach ($report_data as $row) {
            $tabular_data[] = array(array('data' => $row['discount_percent'], 'align' => 'left'), array('data' => $row['count'], 'align' => 'left'));
        }

        $data = array(
            "title" => lang('reports_discounts_summary_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links()
        );

        $this->load->view("reports/tabular", $data);
    }

    function summary_payments($start_date, $end_date, $sale_type, $export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_payments');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Summary_payments');
        $model = $this->Summary_payments;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf));
        $sale_ids = $model->get_sale_ids_for_payments();
        $this->Sale->create_sales_items_temp_table(array('sale_ids' => $sale_ids, 'sale_type' => $sale_type));

        $config = array();
        $config['base_url'] = site_url("reports/summary_payments/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 7;

        $this->pagination->initialize($config);

        $tabular_data = array();
        $report_data = $model->getData();

        foreach ($report_data as $row) {
            $tabular_data[] = array(array('data' => $row['payment_type'], 'align' => 'left'), array('data' => to_currency($row['payment_amount']), 'align' => 'right'));
        }

        $data = array(
            "title" => lang('reports_payments_summary_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular", $data);
    }

    //Input for reports that require only a date range. (see routes.php to see that all graphical summary reports route here)
    function date_input() {
        $data = $this->_get_common_report_data();
        $this->load->view("reports/date_input", $data);
    }

    function employees_date_input() {
        $data = $this->_get_common_report_data();
        $this->load->view("reports/employees_date_input", $data);
    }

    function tables_report() {
        $this->check_action_permission('view_tables');
        $data = $this->_get_common_report_data();
        $this->load->view("reports/tables_reports", $data);
    }

    function detailed_report_table($start_date, $end_date, $export_pdf=0) {
        $this->check_action_permission('view_tables');
        $data["ntable"] = $this->config->item("table_acount");
        $mesas_ = $this->Sale->get_tables_n_occupied($start_date, $end_date);
        $data["title"] = lang("table_input");
        $data["export_pdf"] = $export_pdf;
        $data["subtitle"] = lang("chart_rotation_table") . ": " . date(get_date_format(), strtotime($start_date)) . ' - ' . date(get_date_format(), strtotime($end_date));
        $config['base_url'] = site_url("reports/table_input/" . rawurlencode($start_date) . '/' . rawurlencode($end_date));
        $array_mesa = array();
        foreach ($mesas_ as $mesa) {
            $array_mesa[$mesa->ntable] = $mesa->count;
        }
        // array("1"=>3,"2"=>0,"10"=>20)
        // el indice del array indica el numero de la masa 
        $data["tables"] = $array_mesa;
        $this->load->view("reports/table_input", $data);
    }

    //Graphical summary sales report
    function graphical_summary_sales($start_date, $end_date, $sale_type) {
        $this->check_action_permission('view_sales');
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_sales');
        $model = $this->Summary_sales;
        $id_employee_login= $this->Employee->get_logged_in_employee_info()->person_id;
        if($this->Employee->has_module_action_permission('sales', 'see_sales_uniqued',$id_employee_login)){
           $id_employee_login=FALSE;
        }
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type,"id_employee_login"=>$id_employee_login));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type,"id_employee_login"=>$id_employee_login));

        $data = array(
            "title" => lang('reports_sales_summary_report'),
            "graph_file" => site_url("reports/graphical_summary_sales_graph/$start_date/$end_date/$sale_type"),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "summary_data" => $model->getSummaryData()
        );

        $this->load->view("reports/graphical", $data);
    }

    //The actual graph data
    function graphical_summary_sales_graph($start_date, $end_date, $sale_type) {
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_sales');
        $model = $this->Summary_sales;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
        $report_data = $model->getData();

        $graph_data = array();
        foreach ($report_data as $row) {
            $graph_data[strtotime($row['sale_date'])] = $row['total'];
        }

        $data = array(
            "title" => lang('reports_sales_summary_report'),
            "data" => $graph_data
        );

        $this->load->view("reports/graphs/line", $data);
    }

    //Graphical summary items report
    function graphical_summary_items($start_date, $end_date, $sale_type) {
        $this->check_action_permission('view_items');
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_items');
        $model = $this->Summary_items;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $data = array(
            "title" => lang('reports_items_summary_report'),
            "graph_file" => site_url("reports/graphical_summary_items_graph/$start_date/$end_date/$sale_type"),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "summary_data" => $model->getSummaryData()
        );

        $this->load->view("reports/graphical", $data);
    }

    //The actual graph data
    function graphical_summary_items_graph($start_date, $end_date, $sale_type) {
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_items');
        $model = $this->Summary_items;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
        $report_data = $model->getData();

        $graph_data = array();
        foreach ($report_data as $row) {
            $graph_data[$row['name']] = $row['total'];
        }

        $data = array(
            "title" => lang('reports_items_summary_report'),
            "data" => $graph_data
        );

        $this->load->view("reports/graphs/pie", $data);
    }

    //Graphical summary item kits report
    function graphical_summary_item_kits($start_date, $end_date, $sale_type) {
        $this->check_action_permission('view_item_kits');
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_item_kits');
        $model = $this->Summary_item_kits;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $data = array(
            "title" => lang('reports_item_kits_summary_report'),
            "graph_file" => site_url("reports/graphical_summary_item_kits_graph/$start_date/$end_date/$sale_type"),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "summary_data" => $model->getSummaryData()
        );

        $this->load->view("reports/graphical", $data);
    }

    //The actual graph data
    function graphical_summary_item_kits_graph($start_date, $end_date, $sale_type) {
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_item_kits');
        $model = $this->Summary_item_kits;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
        $report_data = $model->getData();

        $graph_data = array();
        foreach ($report_data as $row) {
            $graph_data[$row['name']] = $row['total'];
        }

        $data = array(
            "title" => lang('reports_item_kits_summary_report'),
            "data" => $graph_data
        );

        $this->load->view("reports/graphs/pie", $data);
    }

    //Graphical summary customers report
    function graphical_summary_categories($start_date, $end_date, $sale_type) {
        $this->check_action_permission('view_categories');
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_categories');
        $model = $this->Summary_categories;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $data = array(
            "title" => lang('reports_categories_summary_report'),
            "graph_file" => site_url("reports/graphical_summary_categories_graph/$start_date/$end_date/$sale_type"),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "summary_data" => $model->getSummaryData()
        );

        $this->load->view("reports/graphical", $data);
    }

    //The actual graph data
    function graphical_summary_categories_graph($start_date, $end_date, $sale_type) {
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_categories');
        $model = $this->Summary_categories;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $report_data = $model->getData();

        $graph_data = array();
        foreach ($report_data as $row) {
            $graph_data[$row['category']] = $row['total'];
        }

        $data = array(
            "title" => lang('reports_categories_summary_report'),
            "data" => $graph_data
        );

        $this->load->view("reports/graphs/pie", $data);
    }

    function graphical_summary_suppliers($start_date, $end_date, $sale_type) {
        $this->check_action_permission('view_suppliers');

        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_suppliers');
        $model = $this->Summary_suppliers;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $data = array(
            "title" => lang('reports_suppliers_summary_report'),
            "graph_file" => site_url("reports/graphical_summary_suppliers_graph/$start_date/$end_date/$sale_type"),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "summary_data" => $model->getSummaryData()
        );

        $this->load->view("reports/graphical", $data);
    }

    //The actual graph data
    function graphical_summary_suppliers_graph($start_date, $end_date, $sale_type) {
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_suppliers');
        $model = $this->Summary_suppliers;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $report_data = $model->getData();

        $graph_data = array();
        foreach ($report_data as $row) {
            $graph_data[$row['supplier']] = $row['total'];
        }

        $data = array(
            "title" => lang('reports_suppliers_summary_report'),
            "data" => $graph_data
        );

        $this->load->view("reports/graphs/pie", $data);
    }

    function graphical_summary_employees($start_date, $end_date, $sale_type, $employee_type) {
        $this->check_action_permission('view_employees');
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_employees');
        $model = $this->Summary_employees;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'employee_type' => $employee_type));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $data = array(
            "title" => lang('reports_employees_summary_report'),
            "graph_file" => site_url("reports/graphical_summary_employees_graph/$start_date/$end_date/$sale_type/$employee_type"),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "summary_data" => $model->getSummaryData()
        );

        $this->load->view("reports/graphical", $data);
    }

    //The actual graph data
    function graphical_summary_employees_graph($start_date, $end_date, $sale_type, $employee_type) {
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_employees');
        $model = $this->Summary_employees;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'employee_type' => $employee_type));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
        $report_data = $model->getData();

        $graph_data = array();
        foreach ($report_data as $row) {
            $graph_data[$row['employee']] = $row['total'];
        }

        $data = array(
            "title" => lang('reports_employees_summary_report'),
            "data" => $graph_data
        );

        $this->load->view("reports/graphs/bar", $data);
    }

    function graphical_summary_taxes($start_date, $end_date, $sale_type) {
        $this->check_action_permission('view_taxes');
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_taxes');
        $model = $this->Summary_taxes;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $data = array(
            "title" => lang('reports_taxes_summary_report'),
            "graph_file" => site_url("reports/graphical_summary_taxes_graph/$start_date/$end_date/$sale_type"),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "summary_data" => $model->getSummaryData()
        );

        $this->load->view("reports/graphical", $data);
    }

    //The actual graph data
    function graphical_summary_taxes_graph($start_date, $end_date, $sale_type) {
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_taxes');
        $model = $this->Summary_taxes;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
        $report_data = $model->getData();

        $graph_data = array();
        foreach ($report_data as $row) {
            $graph_data[$row['name']] = $row['tax'];
        }

        $data = array(
            "title" => lang('reports_taxes_summary_report'),
            "data" => $graph_data
        );

        $this->load->view("reports/graphs/bar", $data);
    }

    //Graphical summary customers report
    function graphical_summary_customers($start_date, $end_date, $sale_type) {
        $this->check_action_permission('view_customers');
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_customers');
        $model = $this->Summary_customers;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $data = array(
            "title" => lang('reports_customers_summary_report'),
            "graph_file" => site_url("reports/graphical_summary_customers_graph/$start_date/$end_date/$sale_type"),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "summary_data" => $model->getSummaryData()
        );

        $this->load->view("reports/graphical", $data);
    }

    //The actual graph data
    function graphical_summary_customers_graph($start_date, $end_date, $sale_type) {
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));
        $this->load->model('reports/Summary_customers');
        $model = $this->Summary_customers;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $report_data = $model->getData();

        $graph_data = array();
        foreach ($report_data as $row) {
            $graph_data[$row['customer']] = $row['total'];
        }

        $data = array(
            "title" => lang('reports_customers_summary_report'),
            "data" => $graph_data
        );

        $this->load->view("reports/graphs/pie", $data);
    }

    //Graphical summary discounts report
    function graphical_summary_discounts($start_date, $end_date, $sale_type) {
        $this->check_action_permission('view_discounts');
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_discounts');
        $model = $this->Summary_discounts;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $data = array(
            "title" => lang('reports_discounts_summary_report'),
            "graph_file" => site_url("reports/graphical_summary_discounts_graph/$start_date/$end_date/$sale_type"),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "summary_data" => $model->getSummaryData()
        );

        $this->load->view("reports/graphical", $data);
    }

    //The actual graph data
    function graphical_summary_discounts_graph($start_date, $end_date, $sale_type) {
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_discounts');
        $model = $this->Summary_discounts;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
        $report_data = $model->getData();

        $graph_data = array();
        foreach ($report_data as $row) {
            $graph_data[$row['discount_percent']] = $row['count'];
        }

        $data = array(
            "title" => lang('reports_discounts_summary_report'),
            "data" => $graph_data
        );

        $this->load->view("reports/graphs/bar", $data);
    }

    function graphical_summary_payments($start_date, $end_date, $sale_type) {
        $this->check_action_permission('view_payments');
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_payments');
        $model = $this->Summary_payments;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
        $sale_ids = $model->get_sale_ids_for_payments();
        $this->Sale->create_sales_items_temp_table(array('sale_ids' => $sale_ids, 'sale_type' => $sale_type));

        $data = array(
            "title" => lang('reports_payments_summary_report'),
            "graph_file" => site_url("reports/graphical_summary_payments_graph/$start_date/$end_date/$sale_type"),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "summary_data" => $model->getSummaryData()
        );

        $this->load->view("reports/graphical", $data);
    }

    //The actual graph data
    function graphical_summary_payments_graph($start_date, $end_date, $sale_type) {
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_payments');
        $model = $this->Summary_payments;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $sale_ids = $model->get_sale_ids_for_payments();
        $this->Sale->create_sales_items_temp_table(array('sale_ids' => $sale_ids, 'sale_type' => $sale_type));
        $report_data = $model->getData();

        $graph_data = array();
        foreach ($report_data as $row) {
            $graph_data[$row['payment_type']] = $row['payment_amount'];
        }

        $data = array(
            "title" => lang('reports_payments_summary_report'),
            "data" => $graph_data
        );

        $this->load->view("reports/graphs/bar", $data);
    }

    function specific_customer_input() {
        $data = $this->_get_common_report_data(TRUE);
        $data['specific_input_name'] = lang('reports_customer');
        $data['search_suggestion_url'] = site_url('reports/customer_search');
        $this->load->view("reports/specific_input", $data);
    }

    function specific_customer_store_account_input() {
        $data = $this->_get_common_report_data(TRUE);
        $data['specific_input_name'] = lang('reports_customer');
        $data['search_suggestion_url'] = site_url('reports/customer_search');
        $this->load->view("reports/specific_input", $data);
    }
	
	function specific_supplier_store_payment_input() {
        $data = $this->_get_common_report_data(TRUE);
        $data['specific_input_name'] = lang('reports_supplier');
        $data['search_suggestion_url'] = site_url('reports/supplier_search');
        $this->load->view("reports/specific_supplier_input", $data);
    }

    function specific_customer($start_date, $end_date, $customer_id, $sale_type, $export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_customers');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Specific_customer');
        $model = $this->Specific_customer;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'customer_id' => $customer_id, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel
            , 'export_pdf' => $export_pdf));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'customer_id' => $customer_id, 'sale_type' => $sale_type));

        $config = array();
        $config['base_url'] = site_url("reports/specific_customer/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$customer_id/$sale_type/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 8;
        $this->pagination->initialize($config);


        $headers = $model->getDataColumns();
        $report_data = $model->getData();

        $summary_data = array();
        $details_data = array();


        foreach ($report_data['summary'] as $key => $row) {
            $summary_data_row = array();

            $summary_data_row[] = array('data' => anchor('sales/edit/' . $row['sale_id'], lang('common_edit'), array('target' => '_blank')), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['is_invoice'] ? $row['invoice_number'] : $row['ticket_number'], 'align' => 'left');
            $summary_data_row[] = array('data' => $row['is_invoice'] ? 'Factura' : 'Boleta', 'align' => 'left');
            $summary_data_row[] = array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['sale_time'])), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['register_name'], 'align' => 'left');
            $summary_data_row[] = array('data' => to_quantity($row['items_purchased']), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['employee_name'] . ($row['sold_by_employee'] && $row['sold_by_employee'] != $row['employee_name'] ? '/' . $row['sold_by_employee'] : ''), 'align' => 'left');
            $summary_data_row[] = array('data' => to_currency($row['subtotal']), 'align' => 'right');
            $summary_data_row[] = array('data' => to_currency($row['total']), 'align' => 'right');
            $summary_data_row[] = array('data' => to_currency($row['tax']), 'align' => 'right');
            if ($this->has_profit_permission) {
                $summary_data_row[] = array('data' => to_currency($row['profit']), 'align' => 'right');
            }

            $summary_data_row[] = array('data' => $row['payment_type'], 'align' => 'right');
            $summary_data_row[] = array('data' => $row['comment'], 'align' => 'right');
            $summary_data[$key] = $summary_data_row;


            foreach ($report_data['details'][$key] as $drow) {
                $details_data_row = array();
                $details_data_row[] = array('data' => isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['category'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['serialnumber'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['description'], 'align' => 'left');
                $details_data_row[] = array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left');

                $details_data_row[] = array('data' => to_currency($drow['subtotal']), 'align' => 'right');
                $details_data_row[] = array('data' => to_currency($drow['total']), 'align' => 'right');
                $details_data_row[] = array('data' => to_currency($drow['tax']), 'align' => 'right');

                if ($this->has_profit_permission) {
                    $details_data_row[] = array('data' => to_currency($drow['profit']), 'align' => 'right');
                }
                $details_data_row[] = array('data' => $drow['discount_percent'] . '%', 'align' => 'left');

                $details_data[$key][] = $details_data_row;
            }
        }

        $customer_info = $this->Customer->get_info($customer_id);
        $data = array(
            "title" => $customer_info->first_name . ' ' . $customer_info->last_name . ' ' . lang('reports_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "summary_data" => $summary_data,
            "details_data" => $details_data,
            "overall_summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular_details", $data);
    }

    function specific_customer_store_account($start_date, $end_date, $customer_id, $sale_type, $export_excel = 0,$export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_store_account');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Specific_customer_store_account');
        $model = $this->Specific_customer_store_account;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'customer_id' => $customer_id, 
        'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel,'export_pdf' => $export_pdf));
        $config = array();
        $config['base_url'] = site_url("reports/specific_customer_store_account/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$customer_id/$sale_type/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 8;
        $this->pagination->initialize($config);



        $headers = $model->getDataColumns();
        $report_data = $model->getData();

        $tabular_data = array();

        foreach ($report_data as $row) {
            $tabular_data[] = array(array('data' => $row['sno'], 'align' => 'left'),
                array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['date'])), 'align' => 'left'),
                array('data' => $row['transaction_amount'] > 0 ? to_currency($row['transaction_amount']) : to_currency(0), 'align' => 'right'),
                array('data' => $row['transaction_amount'] < 0 ? to_currency($row['transaction_amount'] * -1) : to_currency(0), 'align' => 'right'),
                array('data' => to_currency($row['balance']), 'align' => 'right'),
                array('data' => $row['comment'], 'align' => 'left'));
        }


        $customer_info = $this->Customer->get_info($customer_id);

        if ($customer_info->company_name) {
            $customer_title = $customer_info->company_name . ' (' . $customer_info->first_name . ' ' . $customer_info->last_name . ')';
        } else {
            $customer_title = $customer_info->first_name . ' ' . $customer_info->last_name;
        }
        $data = array(
            "title" => lang('reports_detailed_store_account_report') . $customer_title,
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $headers,
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular", $data);
    }

	 function specific_supplier_store_payment($start_date, $end_date, $supplier_id, $receiving_type, $export_excel = 0,$export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_store_payment');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Specific_supplier_store_payment');
        $model = $this->Specific_supplier_store_payment;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'supplier_id' => $supplier_id, 
        'receiving_type' => $receiving_type, 'offset' => $offset, 'export_excel' => $export_excel,'export_pdf' => $export_pdf));
        $config = array();
        $config['base_url'] = site_url("reports/specific_supplier_store_payment/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$supplier_id/$receiving_type/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 8;
        $this->pagination->initialize($config);



        $headers = $model->getDataColumns();
        $report_data = $model->getData();

        $tabular_data = array();
		$balance=0;
        foreach ($report_data as &$row) {
			 $row     = get_object_vars($row);
			 /* var_export($row); */
			if($row['monto'] !=0){
            $tabular_data[] = array(array('data' => ($row['monto']>0?"FAC: ":"ABO: ").str_pad($row['num'],6,'0', STR_PAD_LEFT), 'align' => 'left'),
                array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['fecha'])), 'align' => 'left'),
                array('data' => $row['monto'] < 0 ? to_currency($row['monto']) : to_currency(0), 'align' => 'right'),
                array('data' => $row['monto'] > 0 ? to_currency($row['monto'] ) :to_currency(0), 'align' => 'right'),
                array('data' => to_currency($balance+$row['monto']), 'align' => 'right'),
                array('data' => $row['comment'], 'align' => 'left'));
				$balance=$balance+$row['monto'];
			}
        }


        $supplier_info = $this->Supplier->get_info($supplier_id);
		$this->actualiza_balance_proveedor($supplier_id);
        if ($supplier_info->company_name) {
            $supplier_title = $supplier_info->company_name . ' (' . $supplier_info->first_name . ' ' . $supplier_info->last_name . ')';
        } else {
            $supplier_title = $supplier_info->first_name . ' ' . $supplier_info->last_name;
        }
        $data = array(
            "title" => lang('reports_detailed_store_payment_report'). $supplier_title ,
			"supplier" =>  $supplier_title,
            "subtitle" =>  date("j-m-Y  g:i a"),
            "headers" => $headers,
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular_payments", $data);
    }
	
	function store_account_statements_input() {
        $data = $this->_get_common_report_data();

        $data['search_suggestion_url'] = site_url('reports/customer_search');
        $this->load->view('reports/store_account_statements_input', $data);
    }
	
    function store_payment_statements_input() {
        $data = $this->_get_common_report_data();

        $data['search_suggestion_url'] = site_url('reports/customer_search');
        $this->load->view('reports/store_payment_statements_input', $data);
    }

    function store_account_statements($customer_id = -1, $start_date, $end_date, $hide_items = 0, $pull_payments_by = 'payment_date', $offset = 0) {
        $this->check_action_permission('view_store_account');
        $this->load->model('reports/Store_account_statements');
        $model = $this->Store_account_statements;
        $model->setParams(array('customer_id' => $customer_id, 'offset' => $offset, 'start_date' => $start_date, 'end_date' => $end_date, 'pull_payments_by' => $pull_payments_by));
        $config = array();
        $config['base_url'] = site_url("reports/store_account_statements/$customer_id/$start_date/$end_date/$hide_items/$pull_payments_by");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 8;
        $this->pagination->initialize($config);

        $report_data = $model->getData();

        $customer_info = $this->Customer->get_info($customer_id);
        $data = array(
            "title" => lang('reports_store_account_statements'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            'report_data' => $report_data,
            'hide_items' => $hide_items,
            "pagination" => $this->pagination->create_links(),
            'date_column' => $pull_payments_by == 'payment_date' ? 'date' : 'sale_time',
        );

        $this->load->view("reports/store_account_statements", $data);
    }
	
	 function store_payment_statements($supplier_id = -1, $start_date, $end_date, $hide_items = 0, $pull_payments_by = 'payment_date', $offset = 0) {
        $this->check_action_permission('view_store_payment');
        $this->load->model('reports/Store_payment_statements');
        $model = $this->Store_payment_statements;
        $model->setParams(array('supplier_id' => $supplier_id, 'offset' => $offset, 'start_date' => $start_date, 'end_date' => $end_date, 'pull_payments_by' => $pull_payments_by));
        $config = array();
        $config['base_url'] = site_url("reports/store_payment_statements/$supplier_id/$start_date/$end_date/$hide_items/$pull_payments_by");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 8;
        $this->pagination->initialize($config);

        $report_data = $model->getData();

        $supplier_info = $this->Supplier->get_info($supplier_id);
        $data = array(
            "title" => lang('reports_store_payment_statements'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            'report_data' => $report_data,
            'hide_items' => $hide_items,
            "pagination" => $this->pagination->create_links(),
            'date_column' => $pull_payments_by == 'payment_date' ? 'date' : 'sale_time',
        );

        $this->load->view("reports/store_payment_statements", $data);
    }

    function specific_employee_input() {
        $data = $this->_get_common_report_data(TRUE);
        $data['specific_input_name'] = lang('reports_employee');

        $employees = array();
        foreach ($this->Employee->get_all()->result() as $employee) {
            $employees[$employee->person_id] = $employee->first_name . ' ' . $employee->last_name;
        }
        $data['specific_input_data'] = $employees;
        $this->load->view("reports/specific_employee_input", $data);
    }

    function summary_movement_cash($start_date, $end_date,$register_id,$categoria,$id_empleado, $export_excel = 0, $export_pdf = 0, $offset = 0)
    {

        
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);
        $this->check_action_permission('view_movement_cash');

        $this->load->model('reports/summary_movement');
        $model = $this->summary_movement;
         $params=array('start_date' => $start_date, 'end_date' => $end_date, "register_id"=>$register_id, 'categoris'=>$categoria,'id_empleado'=>$id_empleado,'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset);

        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date,"register_id"=>$register_id, 'categoris'=>$categoria,'id_empleado'=>$id_empleado, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset));

        $this->Register_movement->create_movement_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, "register_id"=>$register_id, 'categoris'=>$categoria,'id_empleado'=>$id_empleado,));

        $config = array();
        $config['base_url'] = site_url("reports/summary_movement_cash/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$register_id/$categoria/$id_empleado/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 10;
        
        $this->pagination->initialize($config);
        
        $tabular_data = array();
        $report_data = $model->getData();

        foreach ($report_data["details"] as $row) {
            $data_row = array();
            $data_row[] = array('data' => date(get_date_format() , strtotime($row['register_date'])), 'align' => 'left');
            $data_row[] = array('data' => to_currency($row['ingreso']), 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['egreso']), 'align' => 'right');
            $data_row[] = array('data' =>$row['categorias_gastos'], 'align' => 'right');
            $tabular_data[] = $data_row;
        }
        
        $as=$model->getSummaryData();
        $data = array(
            "title" => "Movimiento de caja resumen",
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => array(),//$model->getSummaryData(),
            "summary_data_date"=>$model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular_movement", $data);

    }
    //
    function movement_cash_date_input_excel_export() {
        $data = $this->_get_common_report_data(TRUE);
        $cajas=array("all"=>"Todo");
        $location_id=$this->Employee->get_logged_in_employee_current_location_id();
        foreach($this->Register-> get_all_store() as $caja){
            if($location_id==$caja->location_id){
                $cajas[$caja->register_id]= $caja->name;
            }
        }
        $categorias_gastos=array("no establecido"=>"no establecido");
            foreach($this->Appconfig->get_categorias_gastos() as $categoria){
                $categorias_gastos[$categoria]=$categoria;
            }
            /*$categorias_gastos["Venta"]="Venta";
            $categorias_gastos["Devolución"]="Devolución";
            $categorias_gastos[lang("sales_store_account_payment")]=lang("sales_store_account_payment");
            $categorias_gastos["Venta eliminada"]="Venta eliminada";
            $categorias_gastos["Cierre de caja"]="Cierre de caja	";
            $categorias_gastos["Apertura de caja"]="Apertura de caja";
            $categorias_gastos["Venta"]="Venta";*/
            $categorias_gastos["all"]="Todo";
            $data["categorias_gastos"]=$categorias_gastos;
            $empleados=array("all"=>"Todo");
        $employees= $this->Employee->get_all()->result();
        foreach($employees as $empleado){
            $empleados[$empleado->person_id]=$empleado->first_name." ".$empleado->last_name;
        }
        $data["empleados"]=$empleados;
        $data["cajas"]=$cajas;
        $this->load->view("reports/summary_movement_input_excel_export", $data);
    }
    function specific_transfer_location_date_input_excel_export(){
        $data = $this->_get_common_report_data(TRUE);
        $data['specific_input_name'] = "Transferencias";

       
        $this->load->view("reports/specific_product_transfer", $data);
    }
    function consolidated_shop_location_date(){
        $data = $this->_get_common_report_data(TRUE);
        $data['specific_input_name'] = "Consolidado";

       
        $this->load->view("reports/consolidated_shop_location_date", $data);
    }
    function specific_transfer_location($start_date, $end_date,$store_id, $export_excel = 0, $export_pdf = 0, $offset = 0){
        $this->check_action_permission('view_transfer_location');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Detailed_transfer');
        $model = $this->Detailed_transfer;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'store_id' => $store_id, 'offset' => $offset, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf));

        $this->Receiving->create_receivings_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'store_id' => $store_id));
        $config = array();
        $config['base_url'] = site_url("reports/specific_transfer_location/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$store_id/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 8;
        $this->pagination->initialize($config);


        $headers = $model->getDataColumns();
        $report_data = $model->getData();

      
        $summary_data = array();
        $details_data = array();
        foreach ($report_data['summary'] as $key => $row) {
            $summary_data[$key] = array(array('data' => anchor('receivings/edit/' . $row['receiving_id'], 'RECV ' . $row['receiving_id'], array('target' => '_blank')), 'align' => 'left'), array('data' => date(get_date_format(), strtotime($row['receiving_date'])), 'align' => 'left'), array('data' => to_quantity($row['items_purchased']), 'align' => 'left'), array('data' => $row['name_location_envia'], 'align' => 'left'), array('data' => $row['name_location_resive'], 'align' => 'left'), array('data' => to_currency($row['total'], 10), 'align' => 'right'),  array('data' => $row['comment'], 'align' => 'left'));

            foreach ($report_data['details'][$key] as $drow) {
                $report_taxes = $model->getTaxesForItems($row['receiving_id'], $drow['item_id']);
                $details_data[$key][] = array(array('data' => $drow['name'], 'align' => 'left'),
                    array('data' => $drow['product_id'], 'align' => 'left'),
                    array('data' => $drow['category'], 'align' => 'left'),
                    array('data' => $drow['size'], 'align' => 'left'),
                    array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left'),
                    array('data' => to_currency($drow['total'], 10), 'align' => 'left'),
                    array('data' => to_currency($report_taxes[$drow['item_id']]['tax'], 10), 'align' => 'left'),
                    array('data' => to_currency($report_taxes[$drow['item_id']]['total'], 10), 'align' => 'left'),
                    array('data' => $drow['discount_percent'] . '%', 'align' => 'left'));
            }
        }




        $data = array(
            "title" => "Transferencias",
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "summary_data" => $summary_data,
            "details_data" => $details_data,
            "overall_summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular_details", $data);
    }

    function report_consolidated_shop($start_date, $end_date,$store_id){
     
        
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Consolidated_shop');
        $model = $this->Consolidated_shop;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'store_id' => $store_id));
        $this->Location->get_all()->result();
        //$this->Receiving->create_receivings_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'store_id' => $store_id));
        $config = array();
        $config['base_url'] = site_url("reports/report_consolidated_shop/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$store_id");
        $config['total_rows'] = $model->getTotalRows();
        $config['uri_segment'] = 8;
        $this->pagination->initialize($config);


        $headers = $model->getDataColumns();
        $report_data = $model->getData(array('start_date' => $start_date, 'end_date' => $end_date, 'store_id' => $store_id));
        $efectivo=0;
        $datafonos=0;
        $otros=0;
        $credito=0;
        $gastos=0;
        $total=0;
      
        $summary_data = array();
        foreach ($report_data['summary'] as $key => $row) {
                $summary_data[$key] = array(array('data' => $row['name'], 'align' => 'left'),
                    array('data' => to_currency($row['efectivo'], 10), 'align' => 'left'),
                    array('data' => to_currency($row['datafono'], 10), 'align' => 'left'),
                    array('data' => to_currency($row['otros'], 10), 'align' => 'left'),
                    array('data' => to_currency($row['credito'], 10), 'align' => 'left'),
                    array('data' => to_currency($row['gastos'], 10), 'align' => 'left'),
                    array('data' => to_currency($row['efectivo']+$row['datafono']+$row['credito']+$row['otros']-$row['gastos']), 'align' => 'right'),
                                
                );
                $efectivo+=$row['efectivo'];
                $datafonos+=$row['datafono'];
                $otros+=$row['otros'];
                $credito+=$row['credito'];
                $gastos+=$row['gastos'];
                $total+=$row['efectivo']+$row['datafono']+$row['credito']+$row['otros']-$row['gastos'];
            
        }
        $total_data=array('efectivo'=>to_currency($efectivo),'datafonos'=>to_currency($datafonos),'otros'=>to_currency($otros),'credito'=>to_currency($credito),'gastos'=>to_currency($gastos),'total'=>to_currency($total));
            



        $data = array(
            "title" => "Consolidado",
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "summary_data" => $summary_data,
            "total_data" => $total_data,
           // "overall_summary_data" => $model->getSummaryData(),
            "export_excel" => 0,
            "export_pdf" => 0,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular_consolidated", $data);
    }
    function movement_balance_data_input_excel_export() {
        $data = $this->_get_common_report_data(TRUE);
        
        $employees= $this->Employee->get_all()->result();
       
        $id_employee= $this->Employee->get_logged_in_employee_info()->person_id;
       if( $this->Employee->has_module_action_permission('changes_house', 'include_other_employees_report',$id_employee)){
            $empleados=array("all"=>"Todos");       
                foreach($employees as $empleado){
                    $employees= $this->Employee->get_all()->result();
                    $empleados[$empleado->person_id]=$empleado->first_name." ".$empleado->last_name;
                }
       }else{
           $empleado = $this->Employee->get_info( $id_employee);
           $empleados[$id_employee]= $empleado->first_name." ".$empleado->last_name;

       }
		
        $data["empleados"]=$empleados;
        $this->load->view("reports/depostos_salidas_input_excel_export", $data);
    }
    function depostos_salidas_input_excel_export() {
        $data = $this->_get_common_report_data(TRUE);
        
        $employees= $this->Employee->get_all()->result();
       
        $id_employee= $this->Employee->get_logged_in_employee_info()->person_id;
       if( $this->Employee->has_module_action_permission('changes_house', 'include_other_employees_report',$id_employee)){
            $empleados=array("all"=>"Todos");       
                foreach($employees as $empleado){
                    $employees= $this->Employee->get_all()->result();
                    $empleados[$empleado->person_id]=$empleado->first_name." ".$empleado->last_name;
                }
       }else{
           $empleado = $this->Employee->get_info( $id_employee);
           $empleados[$id_employee]= $empleado->first_name." ".$empleado->last_name;

       }
		
        $data["empleados"]=$empleados;
        $this->load->view("reports/input_data_input_excel_export", $data);
    }
    function movement_balance($start_date, $end_date,$id_employee, $export_excel = 0, $export_pdf = 0, $offset = 0)
    {
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);
        $this->check_action_permission('view_change_house');

        $this->load->model('reports/movement_balance_specific_employee');
        $model = $this->movement_balance_specific_employee;
        $id_empleado_logueado= $this->Employee->get_logged_in_employee_info()->person_id;
        if(! $this->Employee->has_module_action_permission('changes_house', 'include_other_employees_report',$id_empleado_logueado)){
            $id_employee=$id_empleado_logueado;
       }
         $params=array('start_date' => $start_date, 'end_date' => $end_date, "id_employee"=>$id_employee, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset);

        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date,"id_employee"=>$id_employee, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset));

        $this->Movement_balance->create_movement_balance_employees_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, "id_employee"=>$id_employee));

        $config = array();
        $config['base_url'] = site_url("reports/depostos_salidas/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$id_employee/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 8;

        $this->pagination->initialize($config);
        
        $tabular_data = array();
        $report_data = $model->getData();
        foreach ($report_data["details"] as $row) {
            $data_row = array();

            $data_row[] = array('data' => $row['id_movement'], 'align' => 'left');

            $data_row[] = array('data' => date(get_date_format() . ' ' . get_time_format(), strtotime($row['register_date'])), 'align' => 'right');
            $data_row[] = array('data' => $row['first_name']." " . $row['last_name'], 'align' => 'right');

            $data_row[] = array('data' => $row['category'], 'align' => 'right');
            $data_row[] = array('data' => $row['description'], 'align' => 'right');

           
            $data_row[] = array('data' => to_currency($row['amount']), 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['new_balance']), 'align' => 'right');

            
            $tabular_data[] = $data_row;
        }
        

        $data = array(
            "title" => "Movimiento de saldo",
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular", $data);
    }
    function depostos_salidas($start_date, $end_date,$id_employee, $export_excel = 0, $export_pdf = 0, $offset = 0)
    {
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);
        $this->check_action_permission('view_change_house');

        $this->load->model('reports/movement_balance_employee');
        $model = $this->movement_balance_employee;
        $id_empleado_logueado= $this->Employee->get_logged_in_employee_info()->person_id;
        if(! $this->Employee->has_module_action_permission('changes_house', 'include_other_employees_report',$id_empleado_logueado)){
            $id_employee=$id_empleado_logueado;
       }
         $params=array('start_date' => $start_date, 'end_date' => $end_date, "id_employee"=>$id_employee, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset);

        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date,"id_employee"=>$id_employee, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset));

        $this->Movement_balance->create_movement_balance_employees_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, "id_employee"=>$id_employee));

        $config = array();
        $config['base_url'] = site_url("reports/depostos_salidas/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$id_employee/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 8;

        $this->pagination->initialize($config);
        
        $tabular_data = array();
        $report_data = $model->getData();
        foreach ($report_data["details"] as $row) {
            $data_row = array();

            $data_row[] = array('data' => $row['id_movement'], 'align' => 'left');

            $data_row[] = array('data' => date(get_date_format() . ' ' . get_time_format(), strtotime($row['register_date'])), 'align' => 'right');
            $data_row[] = array('data' => $row['first_name']." " . $row['last_name'], 'align' => 'right');

            $data_row[] = array('data' => $row['category'], 'align' => 'right');
            $data_row[] = array('data' => $row['description'], 'align' => 'right');

           
            $data_row[] = array('data' => to_currency($row['amount']), 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['new_balance']), 'align' => 'right');

            
            $tabular_data[] = $data_row;
        }
        

        $data = array(
            "title" => "Depositos y retiros",
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular", $data);
    }
    function specific_movement_cash_input() {
        $data = $this->_get_common_report_data(TRUE);
        $cajas=array("all"=>"Todo");
        $location_id=$this->Employee->get_logged_in_employee_current_location_id();
        foreach($this->Register-> get_all_store() as $caja){
            if($location_id==$caja->location_id){
                $cajas[$caja->register_id]= $caja->name;
            }
        }
        $data["cajas"]=$cajas;

        $this->load->view("reports/movement_input_excel_export", $data);
    }
    
    function specific_movement_cash_input_move_money() {
        $data = $this->_get_common_report_data(TRUE);

        $this->load->view("reports/movement_input_excel_export_move_money", $data);
    }
    
    function only_cash($start_date, $end_date,$register_id, $export_excel = 0, $export_pdf = 0, $offset = 0)
    {
       
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);
        $this->check_action_permission('view_movement_cash');

        $this->load->model('reports/specific_movement');
        $model = $this->specific_movement;
        $params=array('start_date' => $start_date, 'end_date' => $end_date, "register_id"=>$register_id, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset);

        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date,"register_id"=>$register_id, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset));

        $this->Register_movement->create_movement_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, "register_id"=>$register_id));

        $config = array();
        $config['base_url'] = site_url("reports/specific_movement_cash/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$register_id/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 8;

        $this->pagination->initialize($config);
        
        $tabular_data = array();
        $report_data = $model->getData();
       
        foreach ($report_data["details"] as $row) {
            $data_row = array();
            $data_row[] = array('data' => anchor('registers_movement/receipt/' . $row['register_movement_id'], '<i class="fa fa-print fa fa-2x vertical-align"></i>', array('target' => '_blank', 'class' => 'hidden-print')) ,'align' => 'left' );
            
             $data_row[] = array('data' => $row['register_movement_id'], 'align' => 'right');

            $data_row[] = array('data' => date(get_date_format() . ' ' . get_time_format(), strtotime($row['register_date'])), 'align' => 'right');

            $data_row[] = array('data' => $row['description'], 'align' => 'right');

            if($row['type_movement']==1){
                $data_row[] = array('data' => 'INGRESO', 'align' => 'right');
                $data_row[] = array('data' => to_currency($row['mount']), 'align' => 'right');
                $data_row[] = array('data' => "", 'align' => 'right');
            }
           else{
                $data_row[] = array('data' => 'SALIDA', 'align' => 'right');
                $data_row[] = array('data' => "", 'align' => 'right');
                $data_row[] = array('data' => to_currency($row['mount']), 'align' => 'right');                
               
           }
            $data_row[] = array('data' =>$row['categorias_gastos'], 'align' => 'right');
            $data_row[] = array('data' =>$row['first_name']." ".$row['last_name'], 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['mount_cash']), 'align' => 'right');
            
            $tabular_data[] = $data_row;
        }


        $data = array(
            "title" => "Movimiento de caja detallado",
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        
       
        $this->load->view("reports/tabular", $data);
    }
    function detailed_of_move_money($start_date, $end_date, $export_excel = 0, $export_pdf = 0, $offset = 0){
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);
        $this->check_action_permission('view_movement_cash');

        $this->load->model('reports/specific_movement');
        $model = $this->specific_movement;
        $params=array('type_movement'=>2,'start_date' => $start_date, 'end_date' => $end_date, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset);

        $model->setParams(array('type_movement'=>2,'start_date' => $start_date, 'end_date' => $end_date, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset));

        $this->Register_movement->create_movement_items_temp_table(array('type_movement'=>2,'start_date' => $start_date, 'end_date' => $end_date,));

        $config = array();
        $config['base_url'] = site_url("reports/specific_movement_cash/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 8;

        $this->pagination->initialize($config);
        
        $tabular_data = array();
        $report_data = $model->getData();
       
        foreach ($report_data["details"] as $row) {
            $data_row = array();
            $data_row[] = array('data' => anchor('registers_movement/receipt/' . $row['register_movement_id'], '<i class="fa fa-print fa fa-2x vertical-align"></i>', array('target' => '_blank', 'class' => 'hidden-print')) ,'align' => 'left' );
            
             $data_row[] = array('data' => $row['register_movement_id'], 'align' => 'right');

            $data_row[] = array('data' => date(get_date_format() . ' ' . get_time_format(), strtotime($row['register_date'])), 'align' => 'right');

            $data_row[] = array('data' => $row['description'], 'align' => 'right');

            $data_row[] = array('data' =>$row['first_name']." ".$row['last_name'], 'align' => 'right');
            $data_row[] = array('data' => $row['entregado_a'], 'align' => 'right');
            $data_row[] = array('data' => $row['name_caja'], 'align' => 'right');
            $data_row[] = array('data' => $row['name_tienda'], 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['mount']), 'align' => 'right');
            
            $tabular_data[] = $data_row;
        }
        $d=$model->getSummaryData_move_money();
        $data = array(
            "title" => "Movimiento de traslados",
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns_move_money(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData_move_money(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        
       
        $this->load->view("reports/tabular", $data);
        
    }
    
    function specific_movement_cash($start_date, $end_date,$register_id, $export_excel = 0, $export_pdf = 0, $offset = 0)
    {
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);
        $this->check_action_permission('view_movement_cash');

        $this->load->model('reports/specific_movement');
        $model = $this->specific_movement;
         $params=array('start_date' => $start_date, 'end_date' => $end_date, "register_id"=>$register_id, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset);

        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date,"register_id"=>$register_id, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset));

        $this->Register_movement->create_movement_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, "register_id"=>$register_id));

        $config = array();
        $config['base_url'] = site_url("reports/specific_movement_cash/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$register_id/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 8;

        $this->pagination->initialize($config);
        
        $tabular_data = array();
        $report_data = $model->getData();
       
        foreach ($report_data["details"] as $row) {
            $data_row = array();
            $data_row[] = array('data' => anchor('registers_movement/receipt/' . $row['register_movement_id'], '<i class="fa fa-print fa fa-2x vertical-align"></i>', array('target' => '_blank', 'class' => 'hidden-print')) ,'align' => 'left' );
            
             $data_row[] = array('data' => $row['register_movement_id'], 'align' => 'right');

            $data_row[] = array('data' => date(get_date_format() . ' ' . get_time_format(), strtotime($row['register_date'])), 'align' => 'right');

            $data_row[] = array('data' => $row['description'], 'align' => 'right');

            if($row['type_movement']==1){
                $data_row[] = array('data' => 'INGRESO', 'align' => 'right');
                $data_row[] = array('data' => to_currency($row['mount']), 'align' => 'right');
                $data_row[] = array('data' => "", 'align' => 'right');
            }
           else{
                $data_row[] = array('data' => 'GASTOS', 'align' => 'right');
                $data_row[] = array('data' => "", 'align' => 'right');
                $data_row[] = array('data' => to_currency($row['mount']), 'align' => 'right');                
               
           }
            $data_row[] = array('data' =>$row['categorias_gastos'], 'align' => 'right');
            $data_row[] = array('data' =>$row['first_name']." ".$row['last_name'], 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['mount_cash']), 'align' => 'right');
            
            $tabular_data[] = $data_row;
        }


        $data = array(
            "title" => "Movimiento de caja detallado",
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        
       
        $this->load->view("reports/tabular", $data);
    }

    function detailed_of_payment_input() {

        $data = $this->_get_common_report_data(TRUE);
        $cajas=array("all"=>"Todo");
        $location_id=$this->Employee->get_logged_in_employee_current_location_id();
        foreach($this->Register-> get_all_store() as $caja){
            if($location_id==$caja->location_id){
                $cajas[$caja->register_id]= $caja->name;
            }
        }
        $empleados=array("all"=>"Todo");
        $employees= $this->Employee->get_all()->result();
        foreach($employees as $empleado){
            $empleados[$empleado->person_id]=$empleado->first_name." ".$empleado->last_name;
        }
        $data["empleados"]=$empleados;
       $data["cajas"]=$cajas;

        $this->load->view("reports/detaile_of_payment_excel_export", $data);
    }

    function detailed_of_payment($start_date, $end_date,$register_id,$empleado_id, $export_excel = 0, $export_pdf = 0, $offset = 0)
    {
       
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);
        $this->check_action_permission('view_movement_cash');

        $this->load->model('reports/specific_movement');
        $model = $this->specific_movement;
        $location_id=$this->Employee->get_logged_in_employee_current_location_id();

        $params=array('start_date' => $start_date, 'end_date' => $end_date, "register_id"=>$register_id, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset);

        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date,"register_id"=>$register_id, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset));

        $this->Register_movement->create_movement_all_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, "register_id"=>$register_id, "empleado_id" => $empleado_id));

        $config = array();
        $config['base_url'] = site_url("reports/specific_movement_cash/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$register_id/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 8;

        $this->pagination->initialize($config);
        
        $tabular_data = array();
        $report_data = $model->getData();
        $pagos = $this->Register_movement->get_metodos_pago_no_efectivo_ventas($start_date,$end_date, $location_id);
        $summary_data=$model->getSummaryData();
       foreach ($pagos as  $pago) {
           $es_devolucion= (
                ($pago["items_quantity"]==null?0:$pago["items_quantity"])+
                ($pago["items_k_quantity"]==null?0:$pago["items_k_quantity"])
            )<=0;
            if(!$es_devolucion){
                $summary_data["entrada"]+=$pago["monto"];
            }else{
                $summary_data["salida"]-=$pago["monto"];
            }
          $tem=array(
            "register_movement_id"=>$pago["id"],
            "register_log_id"=>"0",
            "register_date"=>$pago["fecha_pago"],
            "mount"=>$pago["monto"],
            "description"=>($es_devolucion?lang("sales_return"):lang("sales_sale"))." - ".$pago["payment_type"]." : Este tipo de movimiento no afecta el efectivo en caja" ,
            "detailed_description"=>$es_devolucion?lang("sales_return"):lang("sales_sale"),
            "type_movement"=>$es_devolucion?0:1,
            "mount_cash"=>"0",
            "categorias_gastos"=>$es_devolucion?lang("sales_return"):lang("sales_sale"),
            "id_employee"=>$pago["person_id"],
            "first_name"=>$pago["first_name"],
            "last_name"=>$pago["last_name"],
            "no_aplica"=>1
          );
          $report_data["details"][]= $tem;
       }
       $pagos = $this->Register_movement->get_metodos_pago_no_efectivo_credito($start_date,$end_date, $location_id);
       foreach ($pagos as  $pago) {
        
         if(!$es_devolucion){
             $summary_data["entrada"]+=$pago["monto"];
         }
       $tem=array(
         "register_movement_id"=>$pago["id"],
         "register_log_id"=>"0",
         "register_date"=>$pago["fecha_pago"],
         "mount"=>$pago["monto"],
         "description"=>lang("sales_store_account_payment")." - ".$pago["payment_type"]." : Este tipo de movimiento no afecta el efectivo en caja" ,
         "detailed_description"=>lang("sales_store_account_payment"),
         "type_movement"=>1,
         "mount_cash"=>"0",
         "categorias_gastos"=>lang("sales_store_account_payment"),
         "id_employee"=>$pago["person_id"],
         "first_name"=>$pago["first_name"],
         "last_name"=>$pago["last_name"],         
         "no_aplica"=>1
       );
       $report_data["details"][]= $tem;
    }
        foreach ($report_data["details"] as $row) {
            $data_row = array();
            if(isset($row["no_aplica"])){
                $data_row[] = array('data' => "" ,'align' => 'left' );

            }else{
                $data_row[] = array('data' => anchor('registers_movement/receipt/' . $row['register_movement_id'], '<i class="fa fa-print fa fa-2x vertical-align"></i>', array('target' => '_blank', 'class' => 'hidden-print')) ,'align' => 'left' );
            }            
             $data_row[] = array('data' => $row['register_movement_id'], 'align' => 'right');

            $data_row[] = array('data' => date(get_date_format() . ' ' . get_time_format(), strtotime($row['register_date'])), 'align' => 'right');

            $data_row[] = array('data' => $row['description'], 'align' => 'right');

            if($row['type_movement']==1){
                $data_row[] = array('data' => 'INGRESO', 'align' => 'right');
                $data_row[] = array('data' => to_currency($row['mount']), 'align' => 'right');
                $data_row[] = array('data' => "", 'align' => 'right');
            }
           else{
                $data_row[] = array('data' => 'SALIDA', 'align' => 'right');
                $data_row[] = array('data' => "", 'align' => 'right');
                $data_row[] = array('data' => to_currency($row['mount']), 'align' => 'right');                
               
           }
            $data_row[] = array('data' =>$row['categorias_gastos'], 'align' => 'right');
            $data_row[] = array('data' =>$row['first_name']." ".$row['last_name'], 'align' => 'right');
            if(isset($row["no_aplica"])){
                $data_row[] = array('data' => "<strong>no aplica</strong>" ,'align' => 'left' );
            }else{
                $data_row[] = array('data' => to_currency($row['mount_cash']), 'align' => 'right');
            }
            $tabular_data[] = $data_row;
        }
       
        $data = array(
            "title" => "Movimiento de caja detallado",
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $summary_data,
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );       
       
        $this->load->view("reports/tabular_detailed_of_payment", $data);
    }





    function specific_employee($start_date, $end_date, $employee_id, $sale_type, $payment_types = null, $employee_type, $export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_employees');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Specific_employee');
        $model = $this->Specific_employee;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'employee_id' => $employee_id, 'sale_type' => $sale_type, 'payment_type' => $payment_types, 'employee_type' => $employee_type, 'offset' => $offset, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'employee_id' => $employee_id, 'sale_type' => $sale_type));
        $config = array();
        $config['base_url'] = site_url("reports/specific_employee/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$employee_id/$sale_type/$employee_type/$export_excel/export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 9;
        $this->pagination->initialize($config);

        $headers = $model->getDataColumns();
        $report_data = $model->getData();

        $summary_data = array();
        $details_data = array();

        foreach ($report_data['summary'] as $key => $row) {
            $summary_data_row = array();

            $summary_data_row[] = array('data' => anchor('sales/edit/' . $row['sale_id'], lang('common_edit'), array('target' => '_blank')), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['is_invoice'] ? $row['invoice_number'] : $row['ticket_number'], 'align' => 'left');
            $summary_data_row[] = array('data' => $row['is_invoice'] ? 'Factura' : 'Boleta', 'align' => 'left');
            $summary_data_row[] = array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['sale_time'])), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['register_name'], 'align' => 'left');
            $summary_data_row[] = array('data' => to_quantity($row['items_purchased']), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['customer_name'], 'align' => 'left');
            $summary_data_row[] = array('data' => to_currency($row['subtotal']), 'align' => 'right');
            $summary_data_row[] = array('data' => to_currency($row['total']), 'align' => 'right');
            $summary_data_row[] = array('data' => to_currency($row['tax']), 'align' => 'right');
            if ($this->has_profit_permission) {
                $summary_data_row[] = array('data' => to_currency($row['profit']), 'align' => 'right');
            }

            $summary_data_row[] = array('data' => $row['payment_type'], 'align' => 'right');
            $summary_data_row[] = array('data' => $row['comment'], 'align' => 'right');
            $summary_data[$key] = $summary_data_row;


            foreach ($report_data['details'][$key] as $drow) {
                $details_data_row = array();
                $details_data_row[] = array('data' => isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['category'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['serialnumber'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['description'], 'align' => 'left');
                $details_data_row[] = array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left');

                $details_data_row[] = array('data' => to_currency($drow['subtotal']), 'align' => 'right');
                $details_data_row[] = array('data' => to_currency($drow['total']), 'align' => 'right');
                $details_data_row[] = array('data' => to_currency($drow['tax']), 'align' => 'right');

                if ($this->has_profit_permission) {
                    $details_data_row[] = array('data' => to_currency($drow['profit']), 'align' => 'right');
                }
                $details_data_row[] = array('data' => $drow['discount_percent'] . '%', 'align' => 'left');

                $details_data[$key][] = $details_data_row;
            }
        }
        $employee_info = $this->Employee->get_info($employee_id);
        $data = array(
            "title" => $employee_info->first_name . ' ' . $employee_info->last_name . ' ' . lang('reports_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "summary_data" => $summary_data,
            "details_data" => $details_data,
            "overall_summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular_details", $data);
    }

    function sales_consolidation_input() {

        $data = $this->_get_common_report_data(TRUE);
        $data['specific_input_name'] = lang('reports_employee');

        $employees = array();
        $data["id_employee_login"]=$this->Employee->get_logged_in_employee_info()->person_id;
        foreach ($this->Employee->get_all()->result() as $employee) {
            $employees[$employee->person_id] = $employee->first_name . ' ' . $employee->last_name;
        }
        $data['specific_input_data'] = $employees;
        $this->load->view("reports/sales_consolidation_input", $data);
    }

    function sales_consolidation($start_date, $end_date, $employee_id, $export_excel = 0,$export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_consolidated');

        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Sales_consolidation');
        $model = $this->Sales_consolidation; 

        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'employee_id' => $employee_id, 'offset' => $offset, 'export_excel' => $export_excel,'export_pdf' => $export_pdf));

        $config = array();
        $config['base_url'] = site_url("reports/sales_consolidation/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$employee_id/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 7;
        $this->pagination->initialize($config);

        $headers = $model->getDataColumns();
        $report_data = $model->getData();
        if (is_array($report_data)) {
            $summary_data = array();

            foreach ($report_data as $row) {

                $summary_data[] = array(
                    array('data' => $row[lang('reports_sales_base')], 'align' => 'center'),
                    array('data' => $row[lang('sales_cash')], 'align' => 'center'),
                    array('data' => $row[lang('sales_check')], 'align' => 'center'),
                    array('data' => $row[lang('sales_giftcard')], 'align' => 'center'),
                    array('data' => $row[lang('sales_debit')], 'align' => 'center'),
                    array('data' => $row[lang('sales_credit')], 'align' => 'center'),
                    array('data' => $row[lang('sales_store_account')], 'align' => 'center'),
                    array('data' => $row[lang('sales_store_account_payment')], 'align' => 'center'),
                    array('data' => $row[lang('cash_flows_deposit_money')], 'align' => 'center'),
                    array('data' => $row[lang('cash_flows_xtract_money')], 'align' => 'center'),
                    array('data' => $row[lang('reports_total')], 'align' => 'center'),
                );
            }

            $data = array(
                "title" => lang('reports_consolidated'),
                "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
                "headers" => $model->getDataColumns(),
                "data" => $summary_data,
                "summary_data" => $model->getSummaryData(),
                "export_excel" => $export_excel,
                "export_pdf" => $export_pdf,
                "pagination" => $this->pagination->create_links(),
            );

            $this->load->view("reports/tabular", $data);
        } else {
           $employee= $this->Employee->get_logged_in_employee_info();
            $data = array(
                "title" => lang('reports_consolidated'),
                "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
                "errors" => lang('error_sales_consolidation')."<br> <strong>Caja de: ". $employee->first_name . ' ' . $employee->last_name."</strong>",
                
            );
            $this->load->view("reports/errors", $data);
        }
    }
    function detailed_sales_rate($start_date, $end_date, $sale_type, $export_excel = 0, $export_pdf = 0, $sale_ticket, $offset = 0) {        
    $this->check_action_permission('view_sales');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);
        $sale_ticket = rawurldecode($sale_ticket);
        $this->load->model('reports/Detailed_sales_rate');
        $model = $this->Detailed_sales_rate;
        $id_employee_login= $this->Employee->get_logged_in_employee_info()->person_id;
        $CI = & get_instance();
        $flag = $CI->Employee->has_module_action_permission('sales', 'see_sales_uniqued', $id_employee_login);

        if($flag){
           $id_employee_login=FALSE;
        }
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel,
            'export_pdf' => $export_pdf,
            'sale_ticket' => $sale_ticket,
            "id_employee_login"=>$id_employee_login
        ));
       


        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'sale_ticket' => $sale_ticket, "id_employee_login"=>$id_employee_login));
        $config = array();
        $config['base_url'] = site_url("reports/detailed_sales/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$export_excel/$export_pdf/$sale_ticket");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 9;
        $this->pagination->initialize($config);

        $headers = $model->getDataColumns();

        
        $report_data = $model->getData($CI->Employee->get_logged_in_employee_info()->person_id, $flag);

        $summary_data = array();
        $details_data = array();

        foreach ($report_data['summary'] as $key => $row) {

            $summary_data_row = array();
            if ($export_excel == 1 || $export_pdf == 1) {
                $edit = '';
            } else {
                $edit = lang('common_edit');
            }
            $link = site_url('reports/specific_customer/' . $start_date . '/' . $end_date . '/' . $row['customer_id'] . '/all/0');

            $summary_data_row[] = array('data' => anchor('sales/receipt/' . $row['sale_id'], '<i class="fa fa-print fa fa-2x vertical-align"></i>', array('target' => '_blank', 'class' => 'hidden-print')) . '<span class="visible-print">' . $row['sale_id'] . '</span>' . anchor('sales/edit/' . $row['sale_id'], '<i class="fa fa-file-alt fa fa-2x"></i>', array('target' => '_blank')) . ' ' . anchor('sales/edit/' . $row['sale_id'], "<i class='fa fa-pencil'></i>" . $edit, array('target' => '_blank', 'class' => 'btn btn-xs default hidden-print')), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['is_invoice'] ? $row['invoice_number'] : $row['ticket_number'], 'align' => 'left');
            $summary_data_row[] = array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['sale_time'])), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['register_name'], 'align' => 'left');
            $summary_data_row[] = array('data' => to_quantity($row['items_purchased']), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['employee_name'] . ($row['sold_by_employee'] && $row['sold_by_employee'] != $row['employee_name'] ? '/' . $row['sold_by_employee'] : ''), 'align' => 'left');
            $summary_data_row[] = array('data' => '<a href="' . $link . '" target="_blank">' . $row['customer_name'] . '</a>', 'align' => 'left');
            $summary_data_row[] = array('data' => to_currency($row['total']), 'align' => 'right');
            $summary_data_row[] = array('data' =>lang('sales_'.$row["divisa"])." ". to_currency_no_money($row['cambio']), 'align' => 'right');


           // if ($this->has_profit_permission) {
                $summary_data_row[] = array('data' => to_currency($row['profit']), 'align' => 'right');
           // }

            $summary_data_row[] = array('data' => $row['payment_type'], 'align' => 'right');
            $summary_data_row[] = array('data' => $row['comment'], 'align' => 'right');
            $summary_data[$key] = $summary_data_row;


            foreach ($report_data['details'][$key] as $drow) {
                
                $details_data_row = array();

               
                $details_data_row[] = array('data' => isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align' => 'left');
                $details_data_row[] = array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'center');
                $details_data_row[] = array('data' => to_currency($drow['total']), 'align' => 'center');
                $details_data_row[] = array('data' => to_currency($drow['costo']), 'align' => 'center');

                $details_data_row[] = array('data' =>lang('sales_'.$drow["divisa"])." ". to_currency_no_money($drow['cambio']), 'align' => 'center');

                //if ($this->has_profit_permission) {
                    $details_data_row[] = array('data' => to_currency($drow['profit']), 'align' => 'right');
                //}

                $details_data[$key][] = $details_data_row;
            }
        }

        $data = array(
            "title" => lang('reports_detailed_sales_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "summary_data" => $summary_data,
            "details_data" => $details_data,
            "overall_summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular_details", $data);
    }

    function detailed_sales($start_date, $end_date, $sale_type, $export_excel = 0, $export_pdf = 0, $sale_ticket, $offset = 0) {

        $this->check_action_permission('view_sales');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);
        $sale_ticket = rawurldecode($sale_ticket);
        $this->load->model('reports/Detailed_sales');
        $model = $this->Detailed_sales;
        $CI = & get_instance();
        $id_employee_login= $this->Employee->get_logged_in_employee_info()->person_id;
        $flag = $CI->Employee->has_module_action_permission('sales', 'see_sales_uniqued', $id_employee_login);

        if($flag){
           $id_employee_login=FALSE;
        }
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel,
            'export_pdf' => $export_pdf,
            'sale_ticket' => $sale_ticket,
            "id_employee_login"=>$id_employee_login
        ));
       


        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'sale_ticket' => $sale_ticket, "id_employee_login"=>$id_employee_login));
        $config = array();
        $config['base_url'] = site_url("reports/detailed_sales_rate/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$export_excel/$export_pdf/$sale_ticket");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 9;
        $this->pagination->initialize($config);

        $headers = $model->getDataColumns();
        $report_data = $model->getData($CI->Employee->get_logged_in_employee_info()->person_id, $flag);

        $summary_data = array();

        
        $details_data = array();

        foreach ($report_data['summary'] as $key => $row) {

            $summary_data_row = array();
            if ($export_excel == 1 || $export_pdf == 1) {
                $edit = '';
            } else {
                $edit = lang('common_edit');
            }
            $link = site_url('reports/specific_customer/' . $start_date . '/' . $end_date . '/' . $row['customer_id'] . '/all/0');

            $summary_data_row[] = array('data' => anchor('sales/receipt/' . $row['sale_id'], '<i class="fa fa-print fa fa-2x vertical-align"></i>', array('target' => '_blank', 'class' => 'hidden-print')) . '<span class="visible-print">' . $row['sale_id'] . '</span>' . anchor('sales/edit/' . $row['sale_id'], '<i class="fa fa-file-alt fa fa-2x"></i>', array('target' => '_blank')) . ' ' . anchor('sales/edit/' . $row['sale_id'], "<i class='fa fa-pencil'></i>" . $edit, array('target' => '_blank', 'class' => 'btn btn-xs default hidden-print')), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['is_invoice'] ? ucfirst(strtolower($this->config->item('sale_prefix'))) : 'Boleta', 'align' => 'left');
            $summary_data_row[] = array('data' => $row['is_invoice'] ? $row['invoice_number'] : $row['ticket_number'], 'align' => 'left');
            $summary_data_row[] = array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['sale_time'])), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['register_name'], 'align' => 'left');
            $summary_data_row[] = array('data' => to_quantity($row['items_purchased']), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['employee_name'] . ($row['sold_by_employee'] && $row['sold_by_employee'] != $row['employee_name'] ? '/' . $row['sold_by_employee'] : ''), 'align' => 'left');
            $summary_data_row[] = array('data' => '<a href="' . $link . '" target="_blank">' . $row['customer_name'] . '</a>', 'align' => 'left');
            $summary_data_row[] = array('data' => to_currency($row['subtotal']), 'align' => 'right');
            $summary_data_row[] = array('data' => to_currency($row['total']), 'align' => 'right');
            $summary_data_row[] = array('data' => to_currency($row['tax']), 'align' => 'right');

            if ($this->has_profit_permission) {
                $summary_data_row[] = array('data' => to_currency($row['profit']), 'align' => 'right');
            }

            $summary_data_row[] = array('data' => $row['payment_type'], 'align' => 'right');
            $summary_data_row[] = array('data' => $row['comment'], 'align' => 'right');
            $summary_data[$key] = $summary_data_row;


            foreach ($report_data['details'][$key] as $drow) {
                $url = $drow['item_image_id'] ? site_url('app_files/view/' . $drow['item_image_id']) : (base_url() . '/img/icons/32/no-image-small-square.png');
                $image = array(
                    'src' => $url,
                    'id' => 'avatar',
                    'class' => 'rollover',
                    'width' => '45'
                );
                $details_data_row = array();

                $details_data_row[] = array('data' => isset($drow['item_number']) ? $drow['item_number'] : $drow['item_kit_number'], 'align' => 'left');
                $details_data_row[] = array('data' => isset($drow['item_product_id']) ? $drow['item_product_id'] : $drow['item_kit_product_id'], 'align' => 'left');
                if ($export_excel == 0 &&  $export_pdf==0  && $this->config->item('show_image') == 1) {
                    $details_data_row[] = array('data' => "<a class='rollover' href='$url'>" . img($image) . "</a>", 'align' => 'left');
                }
                $details_data_row[] = array('data' => isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['category'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['size'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['serialnumber'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['description'], 'align' => 'left');
                $details_data_row[] = array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left');
                $details_data_row[] = array('data' => to_currency($drow['subtotal']), 'align' => 'right');
                $details_data_row[] = array('data' => to_currency($drow['total']), 'align' => 'right');
                $details_data_row[] = array('data' => to_currency($drow['tax']), 'align' => 'right');

                if ($this->has_profit_permission) {
                    $details_data_row[] = array('data' => to_currency($drow['profit']), 'align' => 'right');
                }

                $details_data_row[] = array('data' => $drow['discount_percent'] . '%', 'align' => 'left');
                $details_data[$key][] = $details_data_row;
            }
        }
        $data = array(
            "title" => lang('reports_detailed_sales_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "summary_data" => $summary_data,
            "details_data" => $details_data,
            "overall_summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular_details", $data);
    }
    function detailed_sales2($start_date, $end_date, $sale_type, $export_excel = 0, $export_pdf = 0, $sale_ticket, $offset = 0) {

        $this->check_action_permission('view_sales');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);
        $sale_ticket = rawurldecode($sale_ticket);
        $this->load->model('reports/Detailed_sales');
        $model = $this->Detailed_sales;
        $id_employee_login= $this->Employee->get_logged_in_employee_info()->person_id;
        if($this->Employee->has_module_action_permission('sales', 'see_sales_uniqued',$id_employee_login)){
           $id_employee_login=FALSE;
        }
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel,
            'export_pdf' => $export_pdf,
            'sale_ticket' => $sale_ticket,
            "id_employee_login"=>$id_employee_login
        ));
       


        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'sale_ticket' => $sale_ticket, "id_employee_login"=>$id_employee_login));
        $config = array();
        $config['base_url'] = site_url("reports/detailed_sales/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$export_excel/$export_pdf/$sale_ticket");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 9;
        $this->pagination->initialize($config);

        $headers = $model->getDataColumns2();

        $CI = & get_instance();
        $flag = $CI->Employee->has_module_action_permission('sales', 'see_sales_uniqued', $CI->Employee->get_logged_in_employee_info()->person_id);
        $report_data = $model->getData($CI->Employee->get_logged_in_employee_info()->person_id, $flag);

        $summary_data = array();
        $details_data = array();

        foreach ($report_data['summary'] as $key => $row) {

            $summary_data_row = array();
            if ($export_excel == 1 || $export_pdf == 1) {
                $edit = '';
            } else {
                $edit = lang('common_edit');
            }
            $link = site_url('reports/specific_customer/' . $start_date . '/' . $end_date . '/' . $row['customer_id'] . '/all/0');
            
            $summary_data_row[] = array('data' => anchor('sales/receipt/' . $row['sale_id'], '<i class="fa fa-print fa fa-2x vertical-align"></i>', array('target' => '_blank', 'class' => 'hidden-print')) . '<span class="visible-print">' . $row['sale_id'] . '</span>' . anchor('sales/edit/' . $row['sale_id'], '<i class="fa fa-file-alt fa fa-2x"></i>', array('target' => '_blank')) . ' ' . anchor('sales/edit/' . $row['sale_id'], "<i class='fa fa-pencil'></i>" . $edit, array('target' => '_blank', 'class' => 'btn btn-xs default hidden-print')), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['is_invoice'] ? $row['invoice_number'] : $row['ticket_number'], 'align' => 'left');
            $summary_data_row[] = array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['sale_time'])), 'align' => 'left');
            $summary_data_row[] = array('data' => to_quantity($row['items_purchased']), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['employee_name'] . ($row['sold_by_employee'] && $row['sold_by_employee'] != $row['employee_name'] ? '/' . $row['sold_by_employee'] : ''), 'align' => 'left');
            $summary_data_row[] = array('data' => to_currency($row['subtotal']), 'align' => 'right');
            $summary_data_row[] = array('data' => to_currency($row['total']), 'align' => 'right');


            $summary_data[$key] = $summary_data_row;


            foreach ($report_data['details'][$key] as $drow) {
                $url = $drow['item_image_id'] ? site_url('app_files/view/' . $drow['item_image_id']) : (base_url() . '/img/icons/32/no-image-small-square.png');
                $image = array(
                    'src' => $url,
                    'id' => 'avatar',
                    'class' => 'rollover',
                    'width' => '45'
                );
                $details_data_row = array();

                $details_data_row[] = array('data' => isset($drow['item_number']) ? $drow['item_number'] : $drow['item_kit_number'], 'align' => 'left');
                $details_data_row[] = array('data' => isset($drow['item_product_id']) ? $drow['item_product_id'] : $drow['item_kit_product_id'], 'align' => 'center');
                
                $details_data_row[] = array('data' => isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align' => 'center');
                $details_data_row[] = array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'center');
                $details_data_row[] = array('data' => to_currency($drow['subtotal']), 'align' => 'right');
                $details_data_row[] = array('data' => $drow['discount_percent'] . '%', 'align' => 'left');
                $details_data_row[] = array('data' => to_currency($drow['total']), 'align' => 'right');
                $details_data[$key][] = $details_data_row;
            }
        }

        $data = array(
            "title" => lang('reports_detailed_sales_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns2(),
            "summary_data" => $summary_data,
            "details_data" => $details_data,
            "overall_summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular_details", $data);
    }
    function detailed_sales_serial($start_date, $end_date, $sale_type, $export_excel = 0, $export_pdf = 0,$number_serial="", $sale_ticket, $offset = 0) {

        $this->check_action_permission('view_sales');
        $number_serial =trim(rawurldecode($number_serial));
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);
        $sale_ticket = rawurldecode($sale_ticket);
        $this->load->model('reports/Detailed_sales_serial');
        $model = $this->Detailed_sales_serial;
        $id_employee_login= $this->Employee->get_logged_in_employee_info()->person_id;
        if($this->Employee->has_module_action_permission('sales', 'see_sales_uniqued',$id_employee_login)){
           $id_employee_login=FALSE;
        }
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel,
            'export_pdf' => $export_pdf,
            'sale_ticket' => $sale_ticket,
            "id_employee_login"=>$id_employee_login,
            "number_serial"=>$number_serial
        ));      


        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'sale_ticket' => $sale_ticket, "id_employee_login"=>$id_employee_login,"number_serial"=>$number_serial));
        $config = array();
        $config['base_url'] = site_url("reports/detailed_sales_serial/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$export_excel/$export_pdf/$number_serial/$sale_ticket");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 10;
        $this->pagination->initialize($config);

        $headers = $model->getDataColumns();

        $CI = & get_instance();
        $flag = $CI->Employee->has_module_action_permission('sales', 'see_sales_uniqued', $CI->Employee->get_logged_in_employee_info()->person_id);
        $report_data = $model->getData($CI->Employee->get_logged_in_employee_info()->person_id, $flag);

        $summary_data = array();
        $details_data = array();

        foreach ($report_data['summary'] as $key => $row) {

            $summary_data_row = array();
            if ($export_excel == 1 || $export_pdf == 1) {
                $edit = '';
            } else {
                $edit = lang('common_edit');
            }
            $link = site_url('reports/specific_customer/' . $start_date . '/' . $end_date . '/' . $row['customer_id'] . '/all/0');

            $summary_data_row[] = array('data' => anchor('sales/receipt/' . $row['sale_id'], '<i class="fa fa-print fa fa-2x vertical-align"></i>', array('target' => '_blank', 'class' => 'hidden-print')) . '<span class="visible-print">' . $row['sale_id'] . '</span>' . anchor('sales/edit/' . $row['sale_id'], '<i class="fa fa-file-alt fa fa-2x"></i>', array('target' => '_blank')) . ' ' . anchor('sales/edit/' . $row['sale_id'], "<i class='fa fa-pencil'></i>" . $edit, array('target' => '_blank', 'class' => 'btn btn-xs default hidden-print')), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['is_invoice'] ? ucfirst(strtolower($this->config->item('sale_prefix'))) : 'Boleta', 'align' => 'left');
            $summary_data_row[] = array('data' => $row['is_invoice'] ? $row['invoice_number'] : $row['ticket_number'], 'align' => 'left');
            $summary_data_row[] = array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['sale_time'])), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['register_name'], 'align' => 'left');
            $summary_data_row[] = array('data' => to_quantity($row['items_purchased']), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['employee_name'] . ($row['sold_by_employee'] && $row['sold_by_employee'] != $row['employee_name'] ? '/' . $row['sold_by_employee'] : ''), 'align' => 'left');
            $summary_data_row[] = array('data' => '<a href="' . $link . '" target="_blank">' . $row['customer_name'] . '</a>', 'align' => 'left');
            $summary_data_row[] = array('data' => to_currency($row['subtotal']), 'align' => 'right');
            $summary_data_row[] = array('data' => to_currency($row['total']), 'align' => 'right');
            $summary_data_row[] = array('data' => to_currency($row['tax']), 'align' => 'right');

            if ($this->has_profit_permission) {
                $summary_data_row[] = array('data' => to_currency($row['profit']), 'align' => 'right');
            }

            $summary_data_row[] = array('data' => $row['payment_type'], 'align' => 'right');
            $summary_data_row[] = array('data' => $row['comment'], 'align' => 'right');
            $summary_data[$key] = $summary_data_row;


            foreach ($report_data['details'][$key] as $drow) {
                $url = $drow['item_image_id'] ? site_url('app_files/view/' . $drow['item_image_id']) : (base_url() . '/img/icons/32/no-image-small-square.png');
                $image = array(
                    'src' => $url,
                    'id' => 'avatar',
                    'class' => 'rollover',
                    'width' => '45'
                );
                $details_data_row = array();

                $details_data_row[] = array('data' => isset($drow['item_number']) ? $drow['item_number'] : $drow['item_kit_number'], 'align' => 'left');
                $details_data_row[] = array('data' => isset($drow['item_product_id']) ? $drow['item_product_id'] : $drow['item_kit_product_id'], 'align' => 'left');
                if ($export_excel == 0 && /* $export_pdf==0 agredo cuanso se agrego la generacion de pdf */ $export_pdf == 0 && $this->config->item('show_image') == 1) {
                    $details_data_row[] = array('data' => "<a class='rollover' href='$url'>" . img($image) . "</a>", 'align' => 'left');
                }
                $details_data_row[] = array('data' => isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['category'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['size'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['serialnumber'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['description'], 'align' => 'left');
                $details_data_row[] = array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left');
                $details_data_row[] = array('data' => to_currency($drow['subtotal']), 'align' => 'right');
                $details_data_row[] = array('data' => to_currency($drow['total']), 'align' => 'right');
                $details_data_row[] = array('data' => to_currency($drow['tax']), 'align' => 'right');

                if ($this->has_profit_permission) {
                    $details_data_row[] = array('data' => to_currency($drow['profit']), 'align' => 'right');
                }

                $details_data_row[] = array('data' => $drow['discount_percent'] . '%', 'align' => 'left');
                $details_data[$key][] = $details_data_row;
            }
        }

        $data = array(
            "title" => lang('reports_detailed_sales_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "summary_data" => $summary_data,
            "details_data" => $details_data,
            "overall_summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular_details", $data);
    }

    function detailed_payments($start_date, $end_date, $sale_type, $payment_type = 0, $export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_payments');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Detailed_payments');
        $model = $this->Detailed_payments;


        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'payment_type' => $payment_type, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf));
        $sale_ids = $model->get_sale_ids_for_payments();
        $this->Sale->create_sales_items_temp_table(array('sale_ids' => $sale_ids, 'sale_type' => $sale_type));

        $config = array();
        $config['base_url'] = site_url("reports/detailed_payments/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 7;
        $this->pagination->initialize($config);

        $headers = $model->getDataColumns();
        $report_data = $model->getData();

        $summary_data = array();
        $details_data = array();


        foreach ($report_data['summary'] as $sale_id => $row) {
            foreach ($row as $payment_type => $payment_data_row) {
                $summary_data_row = array();
                $summary_data_row[] = array('data' => anchor('sales/receipt/' . $payment_data_row['sale_id'], '<i class="fa fa-print fa fa-2x vertical-align"></i>', array('target' => '_blank', 'class' => 'hidden-print')) . '<span class="visible-print">' . $payment_data_row['sale_id'] . '</span>' . anchor('sales/edit/' . $payment_data_row['sale_id'], '<i class="fa fa-file-alt fa fa-2x"></i>', array('target' => '_blank')) . ' ' . anchor('sales/edit/' . $payment_data_row['sale_id'], "<i class='fa fa-pencil'></i>" . lang('common_edit'), array('target' => '_blank', 'class' => 'btn btn-xs default hidden-print')), 'align' => 'center');
                $summary_data_row[] = array('data' => $payment_data_row['is_invoice'] ? 'Factura' : 'Boleta', 'align' => 'left');
                $summary_data_row[] = array('data' => $payment_data_row['is_invoice'] ? $payment_data_row['invoice_number'] : $payment_data_row['ticket_number'], 'align' => 'left');
                $summary_data_row[] = array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($payment_data_row['sale_time'])), 'align' => 'left');
                $summary_data_row[] = array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($payment_data_row['payment_date'])), 'align' => 'left');
                $summary_data_row[] = array('data' => $payment_data_row['payment_type'], 'align' => 'left');
                $summary_data_row[] = array('data' => to_currency($payment_data_row['payment_amount']), 'align' => 'right');

                $summary_data[$sale_id . '|' . $payment_type] = $summary_data_row;
            }
        }

        $temp_details_data = array();

        foreach ($report_data['details']['sale_ids'] as $sale_id => $drows) {
            $payment_types = array();
            foreach ($drows as $drow) {
                $payment_types[$drow['payment_type']] = TRUE;
            }

            foreach (array_keys($payment_types) as $payment_type) {
                foreach ($drows as $drow) {
                    $details_data_row = array();

                    $details_data_row[] = array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($drow['payment_date'])), 'align' => 'left');
                    $details_data_row[] = array('data' => $drow['payment_type'], 'align' => 'left');
                    $details_data_row[] = array('data' => to_currency($drow['payment_amount']), 'align' => 'right');

                    $details_data[$sale_id . '|' . $payment_type][] = $details_data_row;
                }
            }
        }

        $data = array(
            "title" => lang('reports_detailed_payments_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "summary_data" => $summary_data,
            "details_data" => $details_data,
            "overall_summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular_details", $data);
    }

    function detailed_suspended_sales($start_date, $end_date, $sale_type, $export_excel = 0, $export_pdf = 0, $sale_ticket, $offset = 0) {
        $this->check_action_permission('view_suspended_sales');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);
        $sale_ticket = rawurldecode($sale_ticket);

        $this->load->model('reports/Detailed_suspended_sales');

        $model = $this->Detailed_suspended_sales;

        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'sale_ticket' => $sale_ticket, 'force_suspended' => true));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'sale_ticket' => $sale_ticket, 'force_suspended' => true));
        $config = array();
        $config['base_url'] = site_url("reports/detailed_suspended_sales/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$export_excel/$export_pdf/$sale_ticket");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 7;
        $this->pagination->initialize($config);

        $headers = $model->getDataColumns();
        $report_data = $model->getData();

        $summary_data = array();
        $details_data = array();

        foreach ($report_data['summary'] as $key => $row) {
            $summary_data_row = array();

            $link = site_url('reports/specific_customer/' . $start_date . '/' . $end_date . '/' . $row['customer_id'] . '/all/0');

            $summary_data_row[] = array('data' => anchor('sales/receipt/' . $row['sale_id'], '<i class="fa fa-print fa fa-2x vertical-align"></i>', array('target' => '_blank', 'class' => 'hidden-print')) . '<span class="visible-print">' . $row['sale_id'] . '</span>' . anchor('sales/edit/' . $row['sale_id'], '<i class="fa fa-file-alt fa fa-2x"></i>', array('target' => '_blank')) . ' ' . anchor('sales/edit/' . $row['sale_id'], "<i class='fa fa-pencil'></i>" . lang('common_edit') . ' ' . $row['sale_id'], array('target' => '_blank', 'class' => 'btn btn-xs default hidden-print')), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['is_invoice'] ? 'Factura' : 'Boleta', 'align' => 'left');
            $summary_data_row[] = array('data' => $row['is_invoice'] ? $row['invoice_number'] : $row['ticket_number'], 'align' => 'left');
            $summary_data_row[] = array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['sale_time'])), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['register_name'], 'align' => 'left');
            $summary_data_row[] = array('data' => to_quantity($row['items_purchased']), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['employee_name'] . ($row['sold_by_employee'] && $row['sold_by_employee'] != $row['employee_name'] ? '/' . $row['sold_by_employee'] : ''), 'align' => 'left');
            $summary_data_row[] = array('data' => '<a href="' . $link . '" target="_blank">' . $row['customer_name'] . '</a>', 'align' => 'left');
            $summary_data_row[] = array('data' => to_currency($row['subtotal']), 'align' => 'right');
            $summary_data_row[] = array('data' => to_currency($row['total']), 'align' => 'right');
            $summary_data_row[] = array('data' => to_currency($row['tax']), 'align' => 'right');

            if ($this->has_profit_permission) {
                $summary_data_row[] = array('data' => to_currency($row['profit']), 'align' => 'right');
            }

            $summary_data_row[] = array('data' => $row['payment_type'], 'align' => 'right');
            $summary_data_row[] = array('data' => $row['comment'], 'align' => 'right');
            $summary_data_row[] = array('data' => $row['suspended'] == 1 ? lang('sales_layaway') : lang('sales_estimate'), 'align' => 'right');

            $summary_data[$key] = $summary_data_row;


            foreach ($report_data['details'][$key] as $drow) {
                $details_data_row = array();

                $details_data_row[] = array('data' => isset($drow['item_number']) ? $drow['item_number'] : $drow['item_kit_number'], 'align' => 'left');
                $details_data_row[] = array('data' => isset($drow['item_product_id']) ? $drow['item_product_id'] : $drow['item_kit_product_id'], 'align' => 'left');
                $details_data_row[] = array('data' => isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['category'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['size'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['serialnumber'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['description'], 'align' => 'left');
                $details_data_row[] = array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left');
                $details_data_row[] = array('data' => to_currency($drow['subtotal']), 'align' => 'right');
                $details_data_row[] = array('data' => to_currency($drow['total']), 'align' => 'right');
                $details_data_row[] = array('data' => to_currency($drow['tax']), 'align' => 'right');

                if ($this->has_profit_permission) {
                    $details_data_row[] = array('data' => to_currency($drow['profit']), 'align' => 'right');
                }

                $details_data_row[] = array('data' => $drow['discount_percent'] . '%', 'align' => 'left');
                $details_data[$key][] = $details_data_row;
            }
        }

        $data = array(
            "title" => lang('reports_detailed_suspended_sales_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "summary_data" => $summary_data,
            "details_data" => $details_data,
            "overall_summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular_details", $data);
    }

    function shop() {
        $data = $this->_get_common_report_data(TRUE);
        $data['specific_input_name'] = lang('reports_supplier');
        $data['shop_select'] = $this->Location->get_all()->result();
        $this->load->view("reports/shop", $data);
    }

    function detailed_shop($id, $page = 1) {
        $this->load->model('reports/Detailed_shop');
        $model = $this->Detailed_shop;
        $model->setParams(array('id' => $id + 1));

        $this->Sale->create_sales_items_temp_table(array('id' => $id));

        $start = 1;
        $limit = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;

        if ($page) {
            $start = ($page - 1) * $limit;
        }

        $this->load->library('pagination');
        $report_data = $model->getData($start, $limit);
        $config['use_page_numbers'] = TRUE;
        $config['uri_segment'] = 4;
        $config['first_url'] = site_url() . ('/reports/Detailed_shop/' . $id . '/1');
        $config['base_url'] = site_url() . ('/reports/Detailed_shop/' . $id);
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $limit;
        $config['num_links'] = 2;
        $this->pagination->initialize($config);

        $headers = $model->getDataColumns();

        foreach ($report_data as $row) {
            $data_row = array();
            $data_row[] = array('data' => $row['name'], 'align' => 'left');
            $data_row[] = array('data' => to_quantity($row['quantity']), 'align' => 'left');
            $data_row[] = array('data' => to_quantity($row['quantity_warehouse']), 'align' => 'left');
            $data_row[] = array('data' => to_quantity($row['quantity_defect']), 'align' => 'left');
            $data_row[] = array('data' => to_quantity($row['quantity_warehouse'] + $row['quantity']), 'align' => 'left');
            $tabular_data[] = $data_row;
        }
        $data = array(
            "title" => lang('reports_inventory_summary_report'),
            "subtitle" => '',
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel = 0,
            "export_pdf" => $export_pdf = 0,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular", $data);
    }

    function specific_supplier_input() {
        $data = $this->_get_common_report_data(TRUE);
        $data['specific_input_name'] = lang('reports_supplier');
        $data['search_suggestion_url'] = site_url('reports/supplier_search');
        $this->load->view("reports/specific_input", $data);
    }

    function specific_supplier($start_date, $end_date, $supplier_id, $sale_type, $export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_suppliers');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Specific_supplier');
        $model = $this->Specific_supplier;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'supplier_id' => $supplier_id, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'supplier_id' => $supplier_id, 'sale_type' => $sale_type));
        $config = array();
        $config['base_url'] = site_url("reports/specific_supplier/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$supplier_id/$sale_type/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 8;
        $this->pagination->initialize($config);


        $headers = $model->getDataColumns();
        $report_data = $model->getData();

        $summary_data = array();
        $details_data = array();

        foreach ($report_data['summary'] as $key => $row) {
            $summary_data_row = array();

            $summary_data_row[] = array('data' => anchor('sales/edit/' . $row['sale_id'], lang('common_edit') . ' ' . $row['sale_id'], array('target' => '_blank')), 'align' => 'left');
            $summary_data_row[] = array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['sale_time'])), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['register_name'], 'align' => 'left');
            $summary_data_row[] = array('data' => to_quantity($row['items_purchased']), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['customer_name'], 'align' => 'left');
            $summary_data_row[] = array('data' => to_currency($row['subtotal']), 'align' => 'right');
            $summary_data_row[] = array('data' => to_currency($row['total']), 'align' => 'right');
            $summary_data_row[] = array('data' => to_currency($row['tax']), 'align' => 'right');
            if ($this->has_profit_permission) {
                $summary_data_row[] = array('data' => to_currency($row['profit']), 'align' => 'right');
            }

            $summary_data_row[] = array('data' => $row['payment_type'], 'align' => 'right');
            $summary_data_row[] = array('data' => $row['comment'], 'align' => 'right');
            $summary_data[$key] = $summary_data_row;
            foreach ($report_data['details'][$key] as $drow) {
                $details_data_row = array();
                $details_data_row[] = array('data' => isset($drow['item_number']) ? $drow['item_number'] : $drow['item_kit_number'], 'align' => 'left');
                $details_data_row[] = array('data' => isset($drow['item_product_id']) ? $drow['item_product_id'] : $drow['item_kit_product_id'], 'align' => 'left');
                $details_data_row[] = array('data' => isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['category'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['serialnumber'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['description'], 'align' => 'left');
                $details_data_row[] = array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left');
                $details_data_row[] = array('data' => to_currency($drow['subtotal']), 'align' => 'right');
                $details_data_row[] = array('data' => to_currency($drow['total']), 'align' => 'right');
                $details_data_row[] = array('data' => to_currency($drow['tax']), 'align' => 'right');

                if ($this->has_profit_permission) {
                    $details_data_row[] = array('data' => to_currency($drow['profit']), 'align' => 'right');
                }
                $details_data_row[] = array('data' => $drow['discount_percent'] . '%', 'align' => 'left');

                $details_data[$key][] = $details_data_row;
            }
        }

        $supplier_info = $this->Supplier->get_info($supplier_id);
        $data = array(
            "title" => $supplier_info->first_name . ' ' . $supplier_info->last_name . ' ' . lang('reports_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "summary_data" => $summary_data,
            "details_data" => $details_data,
            "overall_summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular_details", $data);
    }

    function deleted_sales($start_date, $end_date, $sale_type, $export_excel, $export_pdf = 0, $sale_ticket, $offset = 0) {
        $this->check_action_permission('view_deleted_sales');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);
        $sale_ticket = rawurldecode($sale_ticket);

        $this->load->model('reports/Deleted_sales');
        $model = $this->Deleted_sales;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'offset' => $offset, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'sale_ticket' => $sale_ticket));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'sale_ticket' => $sale_ticket));
        $config = array();
        $config['base_url'] = site_url("reports/deleted_sales/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$export_excel/$export_pdf/$sale_ticket");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 7;
        $this->pagination->initialize($config);

        $headers = $model->getDataColumns();
        $report_data = $model->getData();

        $summary_data = array();
        $details_data = array();

        foreach ($report_data['summary'] as $key => $row) {

            $summary_data_row = array();

            $summary_data_row[] = array('data' => anchor('sales/edit/' . $row['sale_id'], lang('common_edit'), array('target' => '_blank')), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['is_invoice'] ? $row['invoice_number'] : $row['ticket_number'], 'align' => 'left');
            $summary_data_row[] = array('data' => $row['is_invoice'] ? 'Factura' : 'Boleta', 'align' => 'left');
            $summary_data_row[] = array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['sale_time'])), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['register_name'], 'align' => 'left');
            $summary_data_row[] = array('data' => to_quantity($row['items_purchased']), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['deleted_by'], 'align' => 'left');
            $summary_data_row[] = array('data' => $row['employee_name'] . ($row['sold_by_employee'] && $row['sold_by_employee'] != $row['employee_name'] ? '/' . $row['sold_by_employee'] : ''), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['customer_name'], 'align' => 'left');
            $summary_data_row[] = array('data' => to_currency($row['subtotal']), 'align' => 'right');
            $summary_data_row[] = array('data' => to_currency($row['total']), 'align' => 'right');
            $summary_data_row[] = array('data' => to_currency($row['tax']), 'align' => 'right');
            if ($this->has_profit_permission) {
                $summary_data_row[] = array('data' => to_currency($row['profit']), 'align' => 'right');
            }
            $summary_data_row[] = array('data' => $row['payment_type'], 'align' => 'left');
            $summary_data_row[] = array('data' => $row['comment'], 'align' => 'left');


            $summary_data[$key] = $summary_data_row;


            foreach ($report_data['details'][$key] as $drow) {
                $details_data_row = array();
                $details_data_row[] = array('data' => isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['category'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['serialnumber'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['description'], 'align' => 'left');
                $details_data_row[] = array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left');
                $details_data_row[] = array('data' => to_currency($drow['subtotal']), 'align' => 'right');
                $details_data_row[] = array('data' => to_currency($drow['total']), 'align' => 'right');
                $details_data_row[] = array('data' => to_currency($drow['tax']), 'align' => 'right');
                if ($this->has_profit_permission) {
                    $details_data_row[] = array('data' => to_currency($drow['profit']), 'align' => 'right');
                }

                $details_data_row[] = array('data' => $drow['discount_percent'] . '%', 'align' => 'left');

                $details_data[$key][] = $details_data_row;
            }
        }

        $data = array(
            "title" => lang('reports_deleted_sales_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "summary_data" => $summary_data,
            "details_data" => $details_data,
            "overall_summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            'pagination' => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular_details", $data);
    }

    function detailed_receivings($start_date, $end_date,$supplier_id,$sale_type, $export_excel = 0, $export_pdf = 0, $offset = 0) 
    {
        $this->check_action_permission('view_receivings');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Detailed_receivings');
        $model = $this->Detailed_receivings;
        $model->setParams(array(
            'start_date' => $start_date,
            'end_date' => $end_date,
            'supplier_id' => $supplier_id,
            'sale_type' => $sale_type,
            'offset' => $offset,
            'export_excel' => $export_excel,
            'export_pdf' => $export_pdf
            ));

        $this->Receiving->create_receivings_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
        $config = array();
        $config['base_url'] = site_url("reports/detailed_receivings/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 7;
        $this->pagination->initialize($config);


        $headers = $model->getDataColumns();
        $report_data = $model->getData();


        $summary_data = array();
        $details_data = array();

        foreach ($report_data['summary'] as $key => $row) {
            $type_buy=($row['items_purchased']<0)?'DEVC':'RECV';
            $summary_data[$key] = array(array('data' => anchor('receivings/edit/' . $row['receiving_id'], $type_buy . $row['receiving_id'], array('target' => '_blank')), 'align' => 'left'), array('data' => date(get_date_format(), strtotime($row['receiving_date'])), 'align' => 'left'), array('data' => to_quantity($row['items_purchased']), 'align' => 'left'), array('data' => $row['employee_name'], 'align' => 'left'), array('data' => $row['supplier_name'], 'align' => 'left'), array('data' => to_currency($row['total'], 10), 'align' => 'right'), array('data' => $row['payment_type'], 'align' => 'left'), array('data' => $row['comment'], 'align' => 'left'));

            foreach ($report_data['details'][$key] as $drow) {
                $report_taxes = $model->getTaxesForItems($row['receiving_id'], $drow['item_id']);
                $details_data[$key][] = array(array('data' => $drow['name'], 'align' => 'left'),
                    array('data' => $drow['product_id'], 'align' => 'left'),
                    array('data' => $drow['category'], 'align' => 'left'),
                    array('data' => $drow['size'], 'align' => 'left'),
                    array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left'),
                    array('data' => to_currency($drow['total'], 10), 'align' => 'left'),
                    array('data' => to_currency($report_taxes[$drow['item_id']]['tax'], 10), 'align' => 'left'),
                    array('data' => to_currency($report_taxes[$drow['item_id']]['total'], 10), 'align' => 'left'),
                    array('data' => $drow['discount_percent'] . '%', 'align' => 'left'));
            }
        }




        $data = array(
            "title" => lang('reports_detailed_receivings_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "summary_data" => $summary_data,
            "details_data" => $details_data,
            "overall_summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular_details", $data);
    }

    function suppliers_credit($start_date, $end_date, $supplier_id, $sale_type, $export_pdf=0, $export_excel = 0, $offset = 0) {
        $this->check_action_permission('view_receivings');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Suppliers_credit');
        $model = $this->Suppliers_credit;
        $model->setParams(array(
            'start_date' => $start_date,
            'end_date' => $end_date,
            'supplier_id' => $supplier_id,
            'sale_type' => $sale_type,
            'offset' => $offset,
            'export_excel' => $export_excel
        ));

        $this->Receiving->create_receivings_items_temp_table(array(
            'start_date' => $start_date,
            'end_date' => $end_date,
            'sale_type' => $sale_type
        ));

        $config = array();

        $config['base_url'] = site_url("reports/detailed_receivings/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$export_excel");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 7;

        $this->pagination->initialize($config);

        $headers = $model->getDataColumns();
        $report_data = $model->getData();

        $summary_data = array();
        $details_data = array();

        foreach ($report_data['summary'] as $key => $row) {
            $summary_data[$key] = array(array('data' => anchor('receivings/edit/' . $row['receiving_id'], 'RECV ' . $row['receiving_id'], array('target' => '_blank')), 'align' => 'left'), array('data' => date(get_date_format(), strtotime($row['receiving_date'])), 'align' => 'left'), array('data' => to_quantity($row['items_purchased']), 'align' => 'left'), array('data' => $row['employee_name'], 'align' => 'left'), array('data' => $row['supplier_name'], 'align' => 'left'), array('data' => to_currency($row['total'], 10), 'align' => 'right'), array('data' => $row['payment_type'], 'align' => 'left'), array('data' => $row['comment'], 'align' => 'left'));

            foreach ($report_data['details'][$key] as $drow) {
                $details_data[$key][] = array(
                    array('data' => $drow['name'], 'align' => 'left'),
                    array('data' => $drow['product_id'], 'align' => 'left'),
                    array('data' => $drow['category'], 'align' => 'left'),
                    array('data' => $drow['size'], 'align' => 'left'),
                    array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left'),
                    array('data' => to_currency($drow['total'], 10), 'align' => 'right'),
                    array('data' => $drow['discount_percent'] . '%', 'align' => 'left')
                );
            }
        }
        $fdf=$model->getSummaryData();
        $data = array(
            "title" => lang('reports_detailed_receivings_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "summary_data" => $summary_data,
            "details_data" => $details_data,
            "overall_summary_data" => $model->getSummaryData(),
            "export_pdf" => $export_pdf,
            "export_excel" => $export_excel,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular_details", $data);
    }

    function excel_export() {
        $data = $this->_get_common_report_data(TRUE);
        $this->load->view("reports/excel_export",$data);
    }

    function inventory_input() {
        $data = $this->_get_common_report_data(TRUE);
        $data['specific_input_name'] = lang('reports_supplier');

        $suppliers = array();

        $suppliers[-1] = lang('common_all');
        foreach ($this->Supplier->get_all()->result() as $supplier) {
            $suppliers[$supplier->person_id] = $supplier->company_name . ' (' . $supplier->first_name . ' ' . $supplier->last_name . ')';
        }
        $data['specific_input_data'] = $suppliers;
        $this->load->view("reports/inventory_input", $data);
    }

    function inventory_low($supplier = -1, $export_excel = 0, $offset = 0) {
        $this->check_action_permission('view_inventory_reports');
        $this->load->model('reports/Inventory_low');
        $model = $this->Inventory_low;
        $model->setParams(array('supplier' => $supplier, 'export_excel' => $export_excel, 'offset' => $offset));

        $config = array();
        $config['base_url'] = site_url("reports/inventory_low/$supplier/$export_excel");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 5;
        $this->pagination->initialize($config);

        $tabular_data = array();
        $report_data = $model->getData();
        foreach ($report_data as $row) {
            $data_row = array();

            $data_row[] = array('data' => $row['name'], 'align' => 'left');
            $data_row[] = array('data' => $row['category'], 'align' => 'left');
            $data_row[] = array('data' => $row['company_name'], 'align' => 'left');
            $data_row[] = array('data' => $row['item_number'], 'align' => 'left');
            $data_row[] = array('data' => $row['product_id'], 'align' => 'left');
            $data_row[] = array('data' => $row['description'], 'align' => 'left');
            if ($this->has_cost_price_permission) {
                $data_row[] = array('data' => to_currency($row['cost_price']), 'align' => 'right');
            }
            $data_row[] = array('data' => to_currency($row['unit_price']), 'align' => 'right');
            $data_row[] = array('data' => to_quantity($row['quantity']), 'align' => 'left');
            $data_row[] = array('data' => to_quantity($row['reorder_level']), 'align' => 'left');

            $tabular_data[] = $data_row;
        }

        $data = array(
            "title" => lang('reports_low_inventory_report'),
            "subtitle" => '',
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular", $data);
    }

    function inventory_summary($supplier = -1, $export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_inventory_reports');
        $this->load->model('reports/Inventory_summary');
        $model = $this->Inventory_summary;
        $model->setParams(array('supplier' => $supplier, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset));

        $config = array();
        $config['base_url'] = site_url("reports/inventory_summary/$supplier/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 5;
        $this->pagination->initialize($config);


        $tabular_data = array();
        $report_data = $model->getData();
        foreach ($report_data as $row) {
            $data_row = array();

            $data_row[] = array('data' => $row['name'], 'align' => 'left');
            $data_row[] = array('data' => $row['category'], 'align' => 'left');
            $data_row[] = array('data' => $row['company_name'], 'align' => 'left');
            $data_row[] = array('data' => $row['item_number'], 'align' => 'left');
            $data_row[] = array('data' => $row['product_id'], 'align' => 'left');
            $data_row[] = array('data' => $row['description'], 'align' => 'left');
            if ($this->has_cost_price_permission) {
                $data_row[] = array('data' => to_currency($row['cost_price']), 'align' => 'right');
            }
            $data_row[] = array('data' => to_currency($row['unit_price']), 'align' => 'right');
            $data_row[] = array('data' => to_quantity($row['quantity']), 'align' => 'left');
            $data_row[] = array('data' => to_quantity($row['reorder_level']), 'align' => 'left');

            $tabular_data[] = $data_row;
        }

        $data = array(
            "title" => lang('reports_inventory_summary_report'),
            "subtitle" => '',
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular", $data);
    }

    function summary_giftcards($start_date, $end_date,$export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_giftcards');
        $this->load->model('reports/Summary_giftcards');
        $model = $this->Summary_giftcards;
        $model->setParams(array('export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset));
        $config = array();
        $config['base_url'] = site_url("reports/summary_giftcards/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 4;
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);
        $this->pagination->initialize($config);

        $tabular_data = array();
        $report_data = $model->getData($start_date,$end_date);
        foreach ($report_data as $row) {
            $tabular_data[] = array(array('data' => $row['giftcard_number'], 'align' => 'left'), array('data' => date(get_date_format(), strtotime($row['update_giftcard'])), 'align' => 'left'), array('data' => to_currency($row['value']), 'align' => 'left'), array('data' => $row['customer_name'], 'align' => 'left'));
        }

        $data = array(
            "title" => lang('reports_giftcard_summary_report'),
            "subtitle" => '',
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular", $data);
    }

    function summary_store_accounts($export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_store_account');
        $this->load->model('reports/Summary_store_accounts');
        $model = $this->Summary_store_accounts;
        $model->setParams(array('export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset));

        $config = array();
        $config['base_url'] = site_url("reports/summary_store_accounts/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 4;
        $this->pagination->initialize($config);

        $tabular_data = array();
        $report_data = $model->getData();
        $sno = 1;
        foreach ($report_data as $row) {
            $tabular_data[] = array(array('data' => $sno, 'align' => 'left'), array('data' => $row['customer'], 'align' => 'left'), array('data' => to_currency($row['balance']), 'align' => 'right'), array('data' => anchor("customers/pay_now/" . $row['person_id'], lang('customers_pay'), array('title' => lang('customers_update'))), 'align' => 'right'));
            $sno++;
        }

        $data = array(
            "title" => lang('reports_store_account_summary_report'),
            "subtitle" => '',
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            'pagination' => $this->pagination->create_links()
        );

			$this->load->view("reports/tabular", $data);
    }
	
	function actualiza_balance_proveedor($supplier_id){
		$total_balance= $this->Receiving->total_balance($supplier_id);
		$this->db->set('balance', $total_balance);
		$this->db->where('person_id', $supplier_id);
        $result=$this->db->update('suppliers');
	}
	
	function summary_store_payments($export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_store_payment');
        $this->load->model('reports/Summary_store_payments');
        $model = $this->Summary_store_payments;
        $model->setParams(array('export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset));

        $config = array();
        $config['base_url'] = site_url("reports/summary_store_payments/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 4;
        $this->pagination->initialize($config);

        $tabular_data = array();
        $vieja_data = $model->getData();
		foreach ($vieja_data as $row) {
			$this->actualiza_balance_proveedor( $row['person_id']);
		}
		$report_data = $model->getData();
        $sno = 1;
        foreach ($report_data as $row) {
		   $tabular_data[] = array(array('data' => $sno, 'align' => 'left'), array('data' => $row['supplier'], 'align' => 'left'), array('data' => to_currency($row['balance']), 'align' => 'right'), array('data' => anchor("suppliers/pay_now/" . $row['person_id'], lang('suppliers_pay'), array('title' => lang('suppliers_update'))), 'align' => 'right'));
            $sno++;
        }

        $data = array(
            "title" => lang('reports_store_payment_summary_report'),
            "subtitle" => '',
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            'pagination' => $this->pagination->create_links()
        );

        $this->load->view("reports/tabular", $data);
    }

    function detailed_giftcards_input() {
        $data = $this->_get_common_report_data(TRUE);
        $data['specific_input_name'] = lang('reports_customer');
        $data['search_suggestion_url'] = site_url('reports/customer_search');
        $this->load->view("reports/detailed_giftcards_input", $data);
    }

    function detailed_giftcards($start_date, $end_date,$customer_id, $export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_giftcards');
        $this->load->model('reports/Detailed_giftcards');
        $model = $this->Detailed_giftcards;
        $model->setParams(array('customer_id' => $customer_id, 'offset' => $offset, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf));

        $this->Sale->create_sales_items_temp_table(array('customer_id' => $customer_id));

        $config = array();
        $config['base_url'] = site_url("reports/detailed_giftcards/$customer_id/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 5;
        $this->pagination->initialize($config);
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $headers = $model->getDataColumns();
        $report_data = $model->getData($start_date,$end_date );

        $summary_data = array();
        $details_data = array();

        foreach ($report_data['summary'] as $key => $row) {
            $summary_data_row = array();

            $summary_data_row[] = array('data' => anchor('sales/edit/' . $row['sale_id'], lang('common_edit') . ' ' . $row['sale_id'], array('target' => '_blank')), 'align' => 'left');
            $summary_data_row[] = array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['sale_time'])), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['register_name'], 'align' => 'left');
            $summary_data_row[] = array('data' => to_quantity($row['items_purchased']), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['customer_name'], 'align' => 'left');
            $summary_data_row[] = array('data' => to_currency($row['subtotal']), 'align' => 'right');
            $summary_data_row[] = array('data' => to_currency($row['total']), 'align' => 'right');
            $summary_data_row[] = array('data' => to_currency($row['tax']), 'align' => 'right');

            if ($this->has_profit_permission) {
                $summary_data_row[] = array('data' => to_currency($row['profit']), 'align' => 'right');
            }

            $summary_data_row[] = array('data' => $row['payment_type'], 'align' => 'right');
            $summary_data_row[] = array('data' => $row['comment'], 'align' => 'right');
            $summary_data[$key] = $summary_data_row;

            foreach ($report_data['details'][$key] as $drow) {
                $details_data_row = array();

                $details_data_row[] = array('data' => isset($drow['item_number']) ? $drow['item_number'] : $drow['item_kit_number'], 'align' => 'left');
                $details_data_row[] = array('data' => isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['category'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['serialnumber'], 'align' => 'left');
                ;
                $details_data_row[] = array('data' => $drow['description'], 'align' => 'left');
                $details_data_row[] = array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left');
                $details_data_row[] = array('data' => to_currency($drow['subtotal']), 'align' => 'right');
                $details_data_row[] = array('data' => to_currency($drow['total']), 'align' => 'right');
                $details_data_row[] = array('data' => to_currency($drow['tax']), 'align' => 'right');

                if ($this->has_profit_permission) {
                    $details_data_row[] = array('data' => to_currency($drow['profit']), 'align' => 'right');
                }

                $details_data_row[] = array('data' => $drow['discount_percent'] . '%', 'align' => 'left');
                $details_data[$key][] = $details_data_row;
            }
        }
        $customer_info = $this->Customer->get_info($customer_id);
        $data = array(
            "title" => $customer_info->first_name . ' ' . $customer_info->last_name . ' ' . lang('giftcards_giftcard') . ' ' . lang('reports_report'),
            "subtitle" => '',
            "headers" => $model->getDataColumns(),
            "summary_data" => $summary_data,
            "details_data" => $details_data,
            "overall_summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular_details", $data);
    }

    function date_input_profit_and_loss() {
        $data = $this->_get_common_report_data();
        $this->load->view("reports/date_input_profit_and_loss", $data);
    }

    function detailed_profit_and_loss($start_date, $end_date) {
        $this->check_action_permission('view_profit_and_loss'); 
        $this->load->model('reports/Detailed_profit_and_loss');
        $model = $this->Detailed_profit_and_loss; 
        $end_date = date('Y-m-d 23:59:59', strtotime($end_date)); 
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date));
        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date));
        $this->Receiving->create_receivings_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date,'listar_contado'=>true));
		$this->Receiving->create_store_payments_temp_table(array('start_date' => $start_date, 'end_date' => $end_date));
        $this->Register_movement->create_movement_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date));
        
        $data = array(
            "title" => lang('reports_detailed_profit_and_loss'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "details_data" => $model->getData(),
            "overall_summary_data" => $model->getSummaryData(),
        );
        $this->load->view("reports/profit_and_loss_details", $data);
    }

    function summary_profit_and_loss($start_date, $end_date) {
        $this->check_action_permission('view_profit_and_loss');
        $this->load->model('reports/Summary_profit_and_loss');
        $model = $this->Summary_profit_and_loss;
        $end_date = date('Y-m-d 23:59:59', strtotime($end_date));

        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date));
        $this->Receiving->create_receivings_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date,'listar_contado'=>true));
		$this->Receiving->create_store_payments_temp_table(array('start_date' => $start_date, 'end_date' => $end_date));

        $data = array(
            "title" => lang('reports_detailed_profit_and_loss'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "details_data" => $model->getData(),
            "overall_summary_data" => $model->getSummaryData(),
        );

        $this->load->view("reports/profit_and_loss_summary", $data);
    }

    function detailed_inventory_input() {
        $data = $this->_get_common_report_data(TRUE);
        $this->load->view("reports/detailed_inventory_input", $data);
    }

    function detailed_inventory($start_date, $end_date, $show_manual_adjustments_only, $export_excel = 0, $export_pdf = 0, $reports_select = 0, $offset = 0) {
        $this->check_action_permission('view_inventory_reports');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);
        $this->load->model('reports/Detailed_inventory');
        $model = $this->Detailed_inventory;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'show_manual_adjustments_only' => $show_manual_adjustments_only, 'reports_select' => $reports_select, 'offset' => $offset, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf));

        $config = array();
        $config['base_url'] = site_url("reports/detailed_inventory/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$show_manual_adjustments_only/$export_excel/$export_pdf/$reports_select");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 8;
        $this->pagination->initialize($config);

        $tabular_data = array();
        $report_data = $model->getData();
        foreach ($report_data as $row) {
            $url = $row['image_id'] ? site_url('app_files/view/' . $row['image_id']) : (base_url() . '/img/icons/32/no-image-small-square.png');
            $image = array(
                'src' => $url,
                'id' => 'avatar',
                'class' => 'rollover',
                'width' => '45'
            );

            $quantity_warehouse = $row['quantity_warehouse'] == NULL ? 0 : $row['quantity_warehouse'];

            $row['trans_comment'] = preg_replace('/' . $this->config->item('sale_prefix') . ' ([0-9]+)/', anchor('sales/receipt/$1', $row['trans_comment']), $row['trans_comment']);

            if ($this->config->item('show_image') == 1 && $export_excel == 0) {
                $tabular_data[] = array(array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['trans_date'])), 'align' => 'left'),
                    array('data' => ("<a href='$url'  class='rollover'>" . img($image) . "</a>"), 'align' => 'left'),
                    array('data' => $row['name'], 'align' => 'left'),
                    array('data' => $row['category'], 'align' => 'left'),
                    array('data' => $row['item_number'], 'align' => 'left'),
                    array('data' => $row['product_id'], 'align' => 'left'),
                    array('data' => to_quantity($row['trans_inventory']), 'align' => 'left'),
                    array('data' => to_quantity($row['quantity']), 'align' => 'left'),
                    array('data' => to_quantity($quantity_warehouse), 'align' => 'left'),
                    array('data' => $row['trans_comment'], 'align' => 'left'),
                );
            } else {
                $tabular_data[] = array(array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['trans_date'])), 'align' => 'left'),
                    array('data' => $row['name'], 'align' => 'left'),
                    array('data' => $row['category'], 'align' => 'left'),
                    array('data' => $row['item_number'], 'align' => 'left'),
                    array('data' => $row['product_id'], 'align' => 'left'),
                    array('data' => to_quantity($row['trans_inventory']), 'align' => 'left'),
                    array('data' => to_quantity($row['quantity']), 'align' => 'left'),
                    array('data' => to_quantity($quantity_warehouse), 'align' => 'left'),
                    array('data' => $row['trans_comment'], 'align' => 'left'),
                );
            }
        }

        $data = array(
            "title" => lang('reports_detailed_inventory_report'),
            "subtitle" => lang('reports_detailed_inventory_report') . " - " . date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)) . " - " . $config['total_rows'] . ' ' . lang('reports_sales_report_generator_results_found'),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular", $data);
    }

    //Summary employees report
    function summary_commissions($start_date, $end_date, $sale_type, $employee_type, $export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_commissions');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Summary_commissions');
        $model = $this->Summary_commissions;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'employee_type' => $employee_type, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf, 'offset' => $offset));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
        $config = array();
        $config['base_url'] = site_url("reports/summary_commissions/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$sale_type/$employee_type/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 8;
        $this->pagination->initialize($config);

        $tabular_data = array();
        $report_data = $model->getData();

        foreach ($report_data as $row) {
            $data_row = array();

            $data_row[] = array('data' => $row['employee'], 'align' => 'left');
            $data_row[] = array('data' => to_currency($row['subtotal']), 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['total']), 'align' => 'right');
            $data_row[] = array('data' => to_currency($row['tax']), 'align' => 'right');
            if ($this->has_profit_permission) {
                $data_row[] = array('data' => to_currency($row['profit']), 'align' => 'right');
            }
            $data_row[] = array('data' => to_currency($row['commission']), 'align' => 'right');
            $tabular_data[] = $data_row;
        }

        $data = array(
            "title" => lang('reports_comissions_summary_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "data" => $tabular_data,
            "summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular", $data);
    }

    function graphical_summary_commissions($start_date, $end_date, $sale_type, $employee_type) {
        $this->check_action_permission('view_commissions');
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_commissions');
        $model = $this->Summary_commissions;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'employee_type' => $employee_type));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));

        $data = array(
            "title" => lang('reports_comissions_summary_report'),
            "graph_file" => site_url("reports/graphical_summary_commissions_graph/$start_date/$end_date/$sale_type/$employee_type"),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "summary_data" => $model->getSummaryData()
        );

        $this->load->view("reports/graphical", $data);
    }

    //The actual graph data
    function graphical_summary_commissions_graph($start_date, $end_date, $sale_type, $employee_type) {
        $start_date = rawurldecode($start_date);
        $end_date = date('Y-m-d 23:59:59', strtotime(rawurldecode($end_date)));

        $this->load->model('reports/Summary_commissions');
        $model = $this->Summary_commissions;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type, 'employee_type' => $employee_type));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'sale_type' => $sale_type));
        $report_data = $model->getData();

        $graph_data = array();
        foreach ($report_data as $row) {
            $graph_data[$row['employee']] = $row['commission'];
        }

        $data = array(
            "title" => lang('reports_comissions_summary_report'),
            "data" => $graph_data
        );

        $this->load->view("reports/graphs/bar", $data);
    }

    function detailed_commissions($start_date, $end_date, $employee_id, $sale_type,$sale_person, $employee_type, $export_excel = 0, $export_pdf = 0, $offset = 0) {
        $this->check_action_permission('view_employees');
        $start_date = rawurldecode($start_date);
        $end_date = rawurldecode($end_date);

        $this->load->model('reports/Detailed_commissions');
        $model = $this->Detailed_commissions;
        $model->setParams(array('start_date' => $start_date, 'end_date' => $end_date, 'employee_id' => $employee_id, 'sale_type' => $sale_type, 'employee_type' => $employee_type, 'offset' => $offset, 'export_excel' => $export_excel, 'export_pdf' => $export_pdf));

        $this->Sale->create_sales_items_temp_table(array('start_date' => $start_date, 'end_date' => $end_date, 'employee_id' => $employee_id, 'sale_type' => $sale_type));
        $config = array();
        $config['base_url'] = site_url("reports/detailed_commissions/" . rawurlencode($start_date) . '/' . rawurlencode($end_date) . "/$employee_id/$sale_type/$employee_type/$export_excel/$export_pdf");
        $config['total_rows'] = $model->getTotalRows();
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $config['uri_segment'] = 9;
        $this->pagination->initialize($config);

        $headers = $model->getDataColumns();
        $report_data = $model->getData();

        $summary_data = array();
        $details_data = array();

        foreach ($report_data['summary'] as $key => $row) {
            $summary_data_row = array();

            $summary_data_row[] = array('data' => anchor('sales/edit/' . $row['sale_id'], lang('common_edit') . ' ' . $row['sale_id'], array('target' => '_blank')), 'align' => 'left');

            $summary_data_row[] = array('data' => $row['is_invoice'] ? $row['invoice_number'] : $row['ticket_number'], 'align' => 'right');
            $summary_data_row[] = array('data' => $row['is_invoice'] ? 'Factura' : 'Boleta', 'align' => 'left');
            $summary_data_row[] = array('data' => date(get_date_format() . '-' . get_time_format(), strtotime($row['sale_time'])), 'align' => 'right');

            $summary_data_row[] = array('data' => to_quantity($row['items_purchased']), 'align' => 'left');
            $summary_data_row[] = array('data' => $row['customer_name'], 'align' => 'left');
            $summary_data_row[] = array('data' => to_currency($row['subtotal']), 'align' => 'right');
            $summary_data_row[] = array('data' => to_currency($row['total']), 'align' => 'right');
            $summary_data_row[] = array('data' => to_currency($row['tax']), 'align' => 'right');
            if ($this->has_profit_permission) {
                $summary_data_row[] = array('data' => to_currency($row['profit']), 'align' => 'right');
            }
            $summary_data_row[] = array('data' => to_currency($row['commission']), 'align' => 'right');
            $summary_data_row[] = array('data' => $row['payment_type'], 'align' => 'right');
            $summary_data_row[] = array('data' => $row['comment'], 'align' => 'right');
            $summary_data[$key] = $summary_data_row;


            foreach ($report_data['details'][$key] as $drow) {
                $details_data_row = array();
                $details_data_row[] = array('data' => isset($drow['item_name']) ? $drow['item_name'] : $drow['item_kit_name'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['category'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['serialnumber'], 'align' => 'left');
                $details_data_row[] = array('data' => $drow['description'], 'align' => 'left');
                $details_data_row[] = array('data' => to_quantity($drow['quantity_purchased']), 'align' => 'left');

                $details_data_row[] = array('data' => to_currency($drow['subtotal']), 'align' => 'right');
                $details_data_row[] = array('data' => to_currency($drow['total']), 'align' => 'right');
                $details_data_row[] = array('data' => to_currency($drow['tax']), 'align' => 'right');

                if ($this->has_profit_permission) {
                    $details_data_row[] = array('data' => to_currency($drow['profit']), 'align' => 'right');
                }
                $details_data_row[] = array('data' => to_currency($drow['commission']), 'align' => 'right');

                $details_data_row[] = array('data' => $drow['discount_percent'] . '%', 'align' => 'left');

                $details_data[$key][] = $details_data_row;
            }
        }
        $employee_info = $this->Employee->get_info($employee_id);
        $data = array(
            "title" => $employee_info->first_name . ' ' . $employee_info->last_name . ' ' . lang('reports_report'),
            "subtitle" => date(get_date_format(), strtotime($start_date)) . '-' . date(get_date_format(), strtotime($end_date)),
            "headers" => $model->getDataColumns(),
            "summary_data" => $summary_data,
            "details_data" => $details_data,
            "overall_summary_data" => $model->getSummaryData(),
            "export_excel" => $export_excel,
            "export_pdf" => $export_pdf,
            "pagination" => $this->pagination->create_links(),
        );

        $this->load->view("reports/tabular_details", $data);
    }

    function detailed_consolidated() {

        //$start_date=rawurldecode($start_date);
        //$end_date=rawurldecode($end_date);
        //$this->load->model('reports/detailed_consolidated');
        //$model = $this->Detailed_commissions;
        //  $model->setParams(array('start_date'=>$start_date, 'end_date'=>$end_date, 'employee_id' =>$employee_id));
        //$this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date, 'employee_id' =>$employee_id));
        //$config = array();
        //$config['base_url'] = site_url("reports/detailed_consolidated/".rawurlencode($start_date).'/'.rawurlencode($end_date)."/$employee_id/");
        //$config['total_rows'] = $model->getTotalRows();
        //$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
        //$config['uri_segment'] = 9;
        //$this->pagination->initialize($config);
        //$headers = $model->getDataColumns();
        //$report_data = $model->getData();
        //$summary_data = array();
        //$details_data = array();
        //$employee_info = $this->Employee->get_info($employee_id);
        /* $data = array(
          "title" => $employee_info->first_name .' '. $employee_info->last_name.' '.lang('reports_report'),
          "subtitle" => date(get_date_format(), strtotime($start_date)) .'-'.date(get_date_format(), strtotime($end_date)),
          "headers" => $model->getDataColumns(),
          "summary_data" => $summary_data,
          "details_data" => $details_data,
          "overall_summary_data" => $model->getSummaryData(),
          "export_excel" => $export_excel,
          "pagination" => $this->pagination->create_links(),
          );
         */
        $this->load->view("reports/detailed_consolidated");
    }

    function customer_search() {
        //allow parallel searchs to improve performance.
        session_write_close();
        $suggestions = $this->Customer->get_customer_search_suggestions($this->input->get('term'), 100);
        echo json_encode($suggestions);
    }

    function supplier_search() {
        //allow parallel searchs to improve performance.
        session_write_close();
        $suggestions = $this->Supplier->get_suppliers_search_suggestions($this->input->get('term'), 100);
        echo json_encode($suggestions);
    }

    
}

?>

<?php
require_once "secure_area.php";
require_once "interfaces/idata_controller.php";
class Items extends Secure_area implements iData_controller
{
    public function __construct()
    {
        parent::__construct('items');
        $this->load->model("Item_unit_sell");
        $this->load->model('Categories');
        
    }

//change text to check line endings
    //new line endings

    public function index($offset = 0)
    {
        $params = $this->session->userdata('item_search_data') ? $this->session->userdata('item_search_data') : array('offset' => 0, 'order_col' => 'item_id', 'order_dir' => 'asc', 'search' => false, 'category' => false);

        if ($offset != $params['offset']) {
            redirect('items/index/' . $params['offset']);
        }

        $this->check_action_permission('search');
        $config['base_url'] = site_url('items/sorting');
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $data['controller_name'] = strtolower(get_class());
        $data['per_page'] = $config['per_page'];
        $data['search'] = $params['search'] ? $params['search'] : "";
        $data['category'] = $params['category'] ? $params['category'] : "";

        if ($data['search'] || $data['category']) {

         
            $config['total_rows'] = $this->Item->search_count_all($data['search'], $data['category']);
            $table_data = $this->Item->search($data['search'], $data['category'], $data['per_page'], $params['offset'], $params['order_col'], $params['order_dir']);
        } else {
            $config['total_rows'] = $this->Item->count_all();
            $table_data = $this->Item->get_all($data['per_page'], $params['offset'], $params['order_col'], $params['order_dir']);
        }

        $data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['order_col'] = $params['order_col'];
        $data['order_dir'] = $params['order_dir'];

        $data['manage_table'] = get_items_manage_table($table_data, $this);
       
        foreach ($this->Item->get_all_categories()->result() as $category) {
            $category = $category->category;
            $data['categories'][$category] = $category;
        }
        $data['categories'][''] = '--' . lang('items_select_category_or_all') . '--';

        $this->load->view('items/manage', $data);
    }

    //esto es solo para que salga la pag de los consultantes
	//porque no tengo el modulo de home
	function consultant($offset = 0)
	{
		$params = $this->session->userdata('item_search_data') ? $this->session->userdata('item_search_data') : array('offset' => 0, 'order_col' => 'item_id', 'order_dir' => 'asc', 'search' => false, 'category' => false);

        if ($offset != $params['offset']) {
            redirect('items/consultant/' . $params['offset']);
        }

        $this->check_action_permission('search');
        $config['base_url'] = site_url('items/sorting_consult');
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $data['controller_name'] = strtolower(get_class());
        $data['per_page'] = $config['per_page'];
        $data['search'] = $params['search'] ? $params['search'] : "";
        $data['category'] = $params['category'] ? $params['category'] : "";

        if ($data['search'] || $data['category']) {

         
            $config['total_rows'] = $this->Item->search_count_all($data['search'], $data['category']);
            $table_data = $this->Item->search($data['search'], $data['category'], $data['per_page'], $params['offset'], $params['order_col'], $params['order_dir']);
        } else {
            $config['total_rows'] = $this->Item->count_all();
            $table_data = $this->Item->get_all($data['per_page'], $params['offset'], $params['order_col'], $params['order_dir']);
        }

        $data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['order_col'] = $params['order_col'];
        $data['order_dir'] = $params['order_dir'];

        $data['manage_table'] = get_items_manage_consultant_table($table_data, $this);
        $data['categories'][''] = '--' . lang('items_select_category_or_all') . '--';
        foreach ($this->Item->get_all_categories()->result() as $category) {
            $category = $category->category;
            $data['categories'][$category] = $category;
        }

        //$this->load->view('items/manage', $data);
		$this->load->view("items/items_consultant", $data);
    }
    
    public function upload_items_shop_online(){
        if($this->Item->set_upload_shop_online()){
            echo json_encode(array('success' => true, 'message' => lang('items_upload_items_shop')));
        }else{
            echo json_encode(array('success' => false, 'message' => lang('items_not_upload_items_shop')));
        }
    }

    public function sorting()
    {
        $this->check_action_permission('search');
        $search = $this->input->post('search') ? $this->input->post('search') : "";
        $category = $this->input->post('category');

        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
        $order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'name';
        $order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc';

        $item_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search, 'category' => $category);
        $this->session->set_userdata("item_search_data", $item_search_data);
        if ($search || $category) {
            $config['total_rows'] = $this->Item->search_count_all($search, $category);
            $table_data = $this->Item->search($search, $category, $per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc');
        } else {
            $config['total_rows'] = $this->Item->count_all();
            $table_data = $this->Item->get_all($per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc');
        }
        $config['base_url'] = site_url('items/sorting');
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_items_manage_table_data_rows($table_data, $this);
        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }

    public function sorting_consult()
    {
        $this->check_action_permission('search');
        $search = $this->input->post('search') ? $this->input->post('search') : "";
        $category = $this->input->post('category');

        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
        $order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'name';
        $order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc';

        $item_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search, 'category' => $category);
        $this->session->set_userdata("item_search_data", $item_search_data);
        if ($search || $category) {
            $config['total_rows'] = $this->Item->search_count_all($search, $category);
            $table_data = $this->Item->search($search, $category, $per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc');
        } else {
            $config['total_rows'] = $this->Item->count_all();
            $table_data = $this->Item->get_all($per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'name', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc');
        }
        $config['base_url'] = site_url('items/sorting_consult');
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_items_manage_consultant_table_data_rows($table_data, $this);
        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }

    public function find_item_info()
    {
        $item_number = $this->input->post('scan_item_number');
        echo json_encode($this->Item->find_item_info($item_number));
    }

    public function item_number_exists()
    {
        if ($this->Item->account_number_exists($this->input->post('item_number'))) {
            echo 'false';
        } else {
            echo 'true';
        }

    }

    public function product_id_exists()
    {
        if ($this->Item->product_id_exists($this->input->post('product_id'))) {
            echo 'false';
        } else {
            echo 'true';
        }

    }
    public function  category_exists()
    {
        if ($this->Categories->category_exists($this->input->post('category'))) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    public function check_duplicate()
    {
        echo json_encode(array('duplicate' => $this->Item->check_duplicate($this->input->post('term'))));
    }

    public function item_exist()
    {
        if ($this->Item->check_duplicate($_GET['name'])) {
            echo "false";
        } else {
            echo "true";
        }
    }

    public function search()
    {
        $this->check_action_permission('search');
        $userdata = $this->session->userdata('item_search_data');
        $search = $this->input->post('search');
        $category = $this->input->post('category');

        $offset = $this->input->post('offset') ? $this->input->post('offset') : 0;

        if (empty($userdata['order_col'])) {
            $order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'name';
        } else {
            $order_col = 'unit_price';
        }

        if (isset($_POST['search_flag']) and !empty($_POST['search_flag'])) {
            $order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir') : 'asc';
            $order_col = 'unit_price';
        } else {
            $order_dir = $userdata['order_dir'];
        }

        $item_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search, 'category' => $category);
        $this->session->set_userdata("item_search_data", $item_search_data);
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $search_data = $this->Item->search($search, $category, $per_page, $offset, $order_col, $order_dir);
        $config['base_url'] = site_url('items/search');
        $config['total_rows'] = $this->Item->search_count_all($search, $category);
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        if ($_POST['consultant'] == FALSE) {
            $data['manage_table'] = get_items_manage_table_data_rows($search_data, $this);
        }
        else{
            //consultant
            $data['manage_table'] = get_items_manage_consultant_table_data_rows($search_data, $this);

        }
        
        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }

    /*
    Gives search suggestions based on what is being searched for
     */
    public function suggest()
    {
        //allow parallel searchs to improve performance.
        session_write_close();
        $suggestions = $this->Item->get_search_suggestions($this->input->get('term'), 100);
        echo json_encode($suggestions);
    }

    public function item_search()
    {
        //allow parallel searchs to improve performance.
        session_write_close();
        $suggestions = $this->Item->get_item_search_suggestions($this->input->get('term'), 100);
        echo json_encode($suggestions);
    }

    /*
    Gives search suggestions based on what is being searched for
     */
    public function suggest_category()
    {
        //allow parallel searchs to improve performance.
        session_write_close();
        $suggestions = $this->Item->get_category_suggestions($this->input->get('term'));
        echo json_encode($suggestions);
    }
    public function suggest_category_subcategory($custom="custom1",$item_id= false)
    {
        //allow parallel searchs to improve performance.
        session_write_close();
        $suggestions = $this->items_subcategory->get_category_suggestions_custom($this->input->get('term'),$custom,$item_id);
        echo json_encode($suggestions);
    }

    public function get_info($item_id = -1)
    {
        echo json_encode($this->Item->get_info($item_id));
    }

    public function view($item_id = -1, $redirect = 0, $sale_or_receiving = 'sale')
    {
        $this->check_action_permission('add_update');
        $this->load->helper('report');
        $data = array();
        $data['controller_name'] = strtolower(get_class());
        $data["units"] = $this->Appconfig->get_all_units();
        $data['item_info'] = $this->Item->get_info($item_id);
        $data['item_tax_info'] = $this->Item_taxes->get_info($item_id);
        $data['tiers'] = $this->Tier->get_all()->result();
        $data['locations'] = array();
        $data['location_tier_prices'] = array();
        $data['additional_item_numbers'] = $this->Additional_item_numbers->get_item_numbers($item_id);
        $data['seriales_item'] = $this->Additional_item_seriales->get_item_serales_unsold($item_id);
        
        $categories_tem = $this->Categories->get_all();
        $categories[""]="Seleccione";

        foreach ($categories_tem as $category)  
            $categories[$category["name"]] = $category["name"];
        //$item_categories_items_result = $this->Item->get_all_categories()->result();
        
        // categorias viejas, borrar cuando esté todo 100% implementado
        /*foreach ($item_categories_items_result as $category) 
            $categories[$category->category] = $category->category;*/
        
        $data["categories"] =  $categories;

        if ($item_id != -1) {
            $data['next_item_id'] = $this->Item->get_next_id($item_id);
            $data['prev_item_id'] = $this->Item->get_prev_id($item_id);
        }
        $data["unit_sale"]= $this->Item_unit_sell->get_all_by_item($item_id);

        foreach ($this->Location->get_all()->result() as $location) {
            if ($this->Employee->is_location_authenticated($location->location_id)) {
                $data['locations'][] = $location;
                $data['location_items'][$location->location_id] = $this->Item_location->get_info($item_id, $location->location_id);
                $data['location_taxes'][$location->location_id] = $this->Item_location_taxes->get_info($item_id, $location->location_id);

                foreach ($data['tiers'] as $tier) {
                    $tier_prices = $this->Item_location->get_tier_price_row($tier->id, $data['item_info']->item_id, $location->location_id);
                    if (!empty($tier_prices)) {
                        $data['location_tier_prices'][$location->location_id][$tier->id] = $tier_prices;
                    } else {
                        $data['location_tier_prices'][$location->location_id][$tier->id] = false;
                    }
                }
            }
            //if ($this->config->item('subcategory_of_items') && $this->Item->get_info($item_id)->subcategory){
            
               $subcategory_location= $this->items_subcategory->get_all_by_id($location->location_id, $item_id);
               $data['locations_subcategory'][$location->location_id]= $subcategory_location;


            //}

        }
        

        $suppliers = array('' => lang('items_none'));
        foreach ($this->Supplier->get_all()->result_array() as $row) {
            $suppliers[$row['person_id']] = $row['company_name'] . ' (' . $row['first_name'] . ' ' . $row['last_name'] . ')';
        }
        $data['redirect'] = $redirect;
        $data['sale_or_receiving'] = $sale_or_receiving;

        $data['tier_prices'] = array();
        $data['tier_type_options'] = array('unit_price' => lang('items_fixed_price'), 'percent_off' => lang('items_percent_off'));
        foreach ($data['tiers'] as $tier) {
            $tier_prices = $this->Item->get_tier_price_row($tier->id, $data['item_info']->item_id);

            if (!empty($tier_prices)) {
                $data['tier_prices'][$tier->id] = $tier_prices;
            } else {
                $data['tier_prices'][$tier->id] = false;
            }
        }
        $data['type_field_value'] = array();

        $data['suppliers'] = $suppliers;
        $data['selected_supplier'] = $this->Supplier->suppliers_info($item_id);
        $data["is_new_item"]=$item_id <=0 ? true:false;
        $this->load->view("items/form", $data);
    }
    public function clone_item($item_id)
    {
        $this->check_action_permission('add_update');
        $this->load->helper('report');
        $data = array();
        $data['controller_name'] = strtolower(get_class());
        $data['redirect'] = 2;
        $data['item_info'] = $this->Item->get_info($item_id);
        $data["is_new_item"]=false;
        //Unset unique identifiers
        $data['item_info']->item_number = '';
        $data['item_info']->product_id = '';

        $categories_tem = $this->Categories->get_all();
        $categories[""]="Seleccione";
        $data["units"] = $this->Appconfig->get_all_units();
        foreach ($categories_tem as $category) 
            $categories[$category["name"]] = $category["name"];

        $data['categories'] = $categories;

        $data['item_tax_info'] = $this->Item_taxes->get_info($item_id);
        $data['tiers'] = $this->Tier->get_all()->result();
        $data['locations'] = array();
        $data['location_tier_prices'] = array();
        $data['additional_item_numbers'] = false;
        $data["additional_item_seriales"]=false;

        foreach ($this->Location->get_all()->result() as $location) {
            if ($this->Employee->is_location_authenticated($location->location_id)) {
                $data['locations'][] = $location;
                $data['location_items'][$location->location_id] = $this->Item_location->get_info($item_id, $location->location_id);
                $data['location_taxes'][$location->location_id] = $this->Item_location_taxes->get_info($item_id, $location->location_id);

                foreach ($data['tiers'] as $tier) {
                    $tier_prices = $this->Item_location->get_tier_price_row($tier->id, $data['item_info']->item_id, $location->location_id);
                    if (!empty($tier_prices)) {
                        $data['location_tier_prices'][$location->location_id][$tier->id] = $tier_prices;
                    } else {
                        $data['location_tier_prices'][$location->location_id][$tier->id] = false;
                    }
                }
            }
            $subcategory_location= $this->items_subcategory->get_all_by_id($location->location_id, $item_id);
            $data['locations_subcategory'][$location->location_id]= $subcategory_location;

        }

        $suppliers = array('' => lang('items_none'));
        foreach ($this->Supplier->get_all()->result_array() as $row) {
            $suppliers[$row['person_id']] = $row['company_name'] . ' (' . $row['first_name'] . ' ' . $row['last_name'] . ')';
        }
        $data['tier_prices'] = array();
        $data['tier_type_options'] = array('unit_price' => lang('items_fixed_price'), 'percent_off' => lang('items_percent_off'));
        foreach ($data['tiers'] as $tier) {
            $tier_prices = $this->Item->get_tier_price_row($tier->id, $data['item_info']->item_id);

            if (!empty($tier_prices)) {
                $data['tier_prices'][$tier->id] = $tier_prices;
            } else {
                $data['tier_prices'][$tier->id] = false;
            }
        }
        $data['type_field_value'] = array();

        $data['suppliers'] = $suppliers;
        $data['selected_supplier'] = $this->Supplier->suppliers_info($item_id);
        $data['is_clone'] = true;
        $this->load->view("items/form", $data);
    }

    public function inventory($item_id = -1)
    {
        $this->check_action_permission('add_update');
        $data['item_info'] = $this->Item->get_info($item_id);
        $data['item_location_info'] = $this->Item_location->get_info($item_id);
        $this->load->view("items/inventory", $data);
    }

    public function generate_barcodes($item_ids)
    {
        $data['items'] = get_items_barcode_data($item_ids);
        $data['scale'] = 2;
        $this->load->view("barcode_sheet", $data);
    }

    public function generate_barcode_labels($item_ids)
    {
        $data['items'] = get_items_barcode_data($item_ids);
        $data['scale'] = 1;
        $this->load->view("barcode_labels", $data);
    }

    public function bulk_edit()
    {
        $this->check_action_permission('add_update');
        $this->load->helper('report');
        $data = array();

        $suppliers = array('' => lang('items_do_nothing'), '-1' => lang('items_none'));
        foreach ($this->Supplier->get_all()->result_array() as $row) {
            $suppliers[$row['person_id']] = $row['company_name'] . ' (' . $row['first_name'] . ' ' . $row['last_name'] . ')';
        }
        $data['suppliers'] = $suppliers;

        $data['override_default_tax_choices'] = array(
            '' => lang('items_do_nothing'),
            '0' => lang('common_no'),
            '1' => lang('common_yes'));

        $data['allow_alt_desciption_choices'] = array(
            '' => lang('items_do_nothing'),
            1 => lang('items_change_all_to_allow_alt_desc'),
            0 => lang('items_change_all_to_not_allow_allow_desc'));

        $data['serialization_choices'] = array(
            '' => lang('items_do_nothing'),
            1 => lang('items_change_all_to_serialized'),
            0 => lang('items_change_all_to_unserialized'));

        $data['tax_included_choices'] = array(
            '' => lang('items_do_nothing'),
            '0' => lang('common_no'),
            '1' => lang('common_yes'));

        $data['is_service_choices'] = array(
            '' => lang('items_do_nothing'),
            '0' => lang('common_no'),
            '1' => lang('common_yes'));

        $this->load->view("items/form_bulk", $data);
    }
    public function deleted_suplliers($item_id = -1)
    {
        $supplier_id = $this->input->post('id_supplier');
        $item_id = $this->input->post('item_id');
        if ($item_id && $supplier_id) {
            $this->Supplier->delete_suppliers($item_id, $supplier_id);
            echo json_encode(array('success' => true, 'message' => lang('items_delete_suppliers') . ' ' .
                $supplier_id, 'supplier_id' => $supplier_id));
        }
    }

    public function defective_item($item_id = false)
    {

        $data['item_info'] = $this->Item->get_info($item_id);
        $data['item_location_info'] = $this->Item_location->get_info($item_id);

        if (isset($_POST['quantity_defect'], $_POST['item_id'])) {

            $item_id = $this->input->post('item_id');
            $location_id = $this->input->post('location_id');
            $location_info = $this->Item_location->get_info($item_id);
            $current_defective_quantity = $location_info->quantity_defect;

            $quantity_defect = $this->input->post('quantity_defect');
            $total_defective_quantity = $this->input->post('quantity_defect') + $current_defective_quantity;

            $quantity_warehouse = $location_info->quantity_warehouse - $quantity_defect;
            $description = $this->input->post('defect_description');
            $defective_user_id = $this->Employee->get_logged_in_employee_info()->id;

            $this->form_validation->set_rules('quantity_defect', 'Cantidad defectuosa', 'required|numeric|greater_than[0]');
            $this->form_validation->set_rules('quantity_warehouse', 'Cantidad en bodega', 'numeric|greater_than[0]');

            if ($this->form_validation->run() === false) {
                echo json_encode(array('success' => false, 'message' => validation_errors()));
            } elseif ($this->Item_location->save_quantity_defect($quantity_warehouse, $quantity_defect, $total_defective_quantity, $description, $defective_user_id, $location_id, $item_id)) {
                echo json_encode(array('success' => true, 'message' => lang('items_successful_updating'), 'quantity_warehouse' => $quantity_warehouse));
            }
        } else {
            $this->load->view('items/defective_item', $data);
        }

    }

    public function save($item_id = -1)
    {
        $this->check_action_permission('add_update');
        $item_data = array(
            'name' => ucwords(strtolower($this->input->post('name'))),
            'description' => $this->input->post('description'),
            'tax_included' => $this->input->post('tax_included') ? $this->input->post('tax_included') : 0,
            'category' => ucwords(strtolower($this->input->post('category'))),
            'size' => $this->input->post('size'),
            'colour' => $this->input->post('colour'),
            'model' => $this->input->post('model'),
            'marca' => $this->input->post('marca'),
            'custom1' => $this->input->post('custom1'),
            'custom2' => $this->input->post('custom2'),
            'custom3' => $this->input->post('custom3'),
            'unit' => $this->input->post('unit'),
            //'supplier_id'=>$this->input->post('supplier_id')=='' ? null:$this->input->post('supplier_id'),
            'item_number' => $this->input->post('item_number') == '' ? null : $this->input->post('item_number'),
            'product_id' => $this->input->post('product_id') == '' ? null : $this->input->post('product_id'),
            'cost_price' => $this->input->post('cost_price'),
            'unit_price' => $this->input->post('unit_price'),
            'items_discount' => $this->input->post('items_discount') ? $this->input->post('items_discount') : 0,
            'promo_price' => $this->input->post('promo_price') ? $this->input->post('promo_price') : null,
            'promo_quantity' => $this->input->post('promo_quantity') == '' ? 0.0 : $this->input->post('promo_quantity'),
            'start_date' => $this->input->post('start_date') ? date('Y-m-d', strtotime($this->input->post('start_date'))) : null,
            'end_date' => $this->input->post('end_date') ? date('Y-m-d', strtotime($this->input->post('end_date'))) : null,
            'expiration_date' => $this->input->post('expiration_date') ? date('Y-m-d', strtotime($this->input->post('expiration_date'))) : '00/00/0000',
            'expiration_day' => $this->input->post('expiration_day'),
            'reorder_level' => $this->input->post('reorder_level') != '' ? $this->input->post('reorder_level') : null,
            'is_service' => $this->input->post('is_service') ? $this->input->post('is_service') : 0,
            'allow_alt_description' => $this->input->post('allow_alt_description') ? $this->input->post('allow_alt_description') : 0,
            'is_serialized' => $this->input->post('is_serialized') ? $this->input->post('is_serialized') : 0,
            'override_default_tax' => $this->input->post('override_default_tax') ? $this->input->post('override_default_tax') : 0,
            'costo_tax' => 0,
            'subcategory' => $this->input->post('subcategory') ? $this->input->post('subcategory') : 0,
            'activate_range'=>(int) $this->input->post('activate_range'),
            "has_sales_units"=>(int) $this->input->post('has_sales_units'),
            "quantity_unit_sale" => (double) $this->input->post('quantity_unit_sale') ?  $this->input->post('quantity_unit_sale'): 1,
            'shop_online'=>$this->input->post('shop_online') ? $this->input->post('shop_online') : 0
        );
        $unit_sale =$this->input->post('unit_sale');

        if($this->input->post('has_sales_units') == 1)
        {       

            if(!is_array($unit_sale) or count($unit_sale) < 1)
            {
                echo json_encode(array('success' => false, 'message' => "Debe creaar una unidad de salida"));
                exit();
            }
        }
        

        if ($this->input->post('override_default_commission')) {
            if ($this->input->post('commission_type') == 'fixed') {
                $item_data['commission_fixed'] = (float) $this->input->post('commission_value');
                $item_data['commission_percent'] = null;
            } else {
                $item_data['commission_percent'] = (float) $this->input->post('commission_value');
                $item_data['commission_fixed'] = null;
            }
        } else {
            $item_data['commission_percent'] = null;
            $item_data['commission_fixed'] = null;
        }

        $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
        $cur_item_info = $this->Item->get_info($item_id);
        $error_datos_custom=false;
        $redirect = $this->input->post('redirect');
        $sale_or_receiving = $this->input->post('sale_or_receiving');
       
        //valida entrada se debe pasar para un archivo heper       
        if($this->config->item('subcategory_of_items')==1 && $this->input->post('subcategory') ){
            if ($this->input->post('locations')) {
                foreach ($this->input->post('locations') as $location_id => $item_location_data) {
                         $subcategory_data_custom1=$item_location_data['subcategory_data_custom1'];
                         $subcategory_data_custom2=$item_location_data['subcategory_data_custom2'];
                         $subcategory_data_quantity=$item_location_data['subcategory_data_quantity'];
                         $subcategory_data_date= isset($item_location_data['subcategory_data_date']) ? $item_location_data['subcategory_data_date']: [] ;

                        if(count($subcategory_data_custom1) < 0|| count($subcategory_data_custom2)<0 || count($subcategory_data_quantity)<0 )
                            $error_datos_custom =true;
                        foreach($subcategory_data_custom1 as $custom){
                            if( $custom=="" || $custom==null){
                                    $error_datos_custom =true;
                            }
                        }
                        foreach($subcategory_data_custom2 as $custom){
                            if($custom=="" || $custom==null){
                                $error_datos_custom =true;
                            }
                        }
                        foreach($subcategory_data_quantity as $quantity){
                            if($quantity=="" || $quantity == null){
                                $error_datos_custom = true;
                            }
                        }
                        foreach($subcategory_data_date as $date){
                            if($date=="" || $date == null){
                                $error_datos_custom = true;
                            }
                        }
                        for ($i = 0; $i < count($subcategory_data_custom1); $i++) {
                            for ($j = 0; $j < count($subcategory_data_custom1); $j++) {
                                if($i!=$j){
                                    if($subcategory_data_custom1[$j]== $subcategory_data_custom1[$i] && $subcategory_data_custom2[$i]== $subcategory_data_custom2[$j]){
                                        $error_datos_custom= true;
                                    }
                                }
                            }
                
                        }
                        foreach($subcategory_data_quantity as $numero){
                            if(!is_numeric($numero))
                                $error_datos_custom=true;
                        }
                        if($error_datos_custom) break;
                    
                }
            }
        }            
      

        if (!$error_datos_custom && $this->Item->save($item_data, $item_id)) {
            $tier_type = $this->input->post('tier_type');

            if ($this->input->post('item_tier')) {
                foreach ($this->input->post('item_tier') as $tier_id => $price_or_percent) {
                    if ($price_or_percent) {
                        $tier_data = array('tier_id' => $tier_id);
                        $tier_data['item_id'] = isset($item_data['item_id']) ? $item_data['item_id'] : $item_id;

                        if ($tier_type[$tier_id] == 'unit_price') {
                            $tier_data['unit_price'] = $price_or_percent;
                            $tier_data['percent_off'] = null;
                        } else {
                            $tier_data['percent_off'] = (int) $price_or_percent;
                            $tier_data['unit_price'] = null;
                        }

                        $this->Item->item_round(array($tier_data['unit_price']));
                        $round = $this->Item->item_round(array($tier_data['unit_price']));
                        $tier_data['unit_price'] = $round;

                        $this->Item->save_item_tiers($tier_data, $item_id);
                    } else {
                        $this->Item->delete_tier_price($tier_id, $item_id);
                    }

                }
            }

            if ($this->input->post('supplier_id') && is_array($this->input->post('supplier_id'))) {

                $supplier['price_suppliers'] = $this->input->post('price_suppliers');

                $i = 0;
                foreach ($this->input->post('supplier_id') as $supplier_id) {
                    $item_data_supliers = array(
                        'supplier_id' => $supplier_id,
                        'price_suppliers' => $supplier['price_suppliers'][$i],
                        'item_id' => isset($item_data['item_id']) ? $item_data['item_id'] : $item_id,
                    );

                    $this->Supplier->save_suppliers($item_data_supliers, $supplier_id);
                    $i++;
                }
            }

            $success_message = '';

            //New item
            if ($item_id == -1) {

                $success_message = lang('items_successful_adding') . ' ' . $item_data['name'];
                $this->session->set_flashdata('manage_success_message', $success_message);
                echo json_encode(array('success' => true, 'message' => $success_message, 'item_id' => $item_data['item_id'], 'redirect' => $redirect, 'sale_or_receiving' => $sale_or_receiving));
                $item_id = $item_data['item_id'];

                if ($item_data['shop_online']==1) {
                    $key = $this->config->item('token_api');
                    $dominio = $this->config->item('dominioapi');                                                
                    $url= $dominio."/api/products/sicrono";
                    $datos=array('id' => $item_id, 'keyapi' => $key);
                    $resul=postcurl($url,$datos);
                }



            } else //previous item
            {

                $success_message = lang('items_successful_updating') . ' ' . $item_data['name'];
                $this->session->set_flashdata('manage_success_message', $success_message);
                echo json_encode(array('success' => true, 'message' => $success_message, 'item_id' => $item_id, 'redirect' => $redirect, 'sale_or_receiving' => $sale_or_receiving));


                if ($item_data['shop_online']==1) {
                    $key = $this->config->item('token_api');
                    $dominio = $this->config->item('dominioapi');                                                
                    $url= $dominio."/api/products/sicrono";
                    $datos=array('id' => $item_id, 'keyapi' => $key);
                    $resul=postcurl($url,$datos);
                }
            }

            if ($this->input->post('additional_item_numbers') && is_array($this->input->post('additional_item_numbers'))) {
                $this->Additional_item_numbers->save($item_id, $this->input->post('additional_item_numbers'));
            } else {
                $this->Additional_item_numbers->delete($item_id);
            }
            $additional_item_seriales= is_array($this->input->post('additional_item_seriales'))?$this->input->post('additional_item_seriales'):array();
            if ($this->input->post('is_serialized')==1 ){               
                if (!$this->Employee->has_module_action_permission('items','delete_serial', $this->Employee->get_logged_in_employee_info()->person_id) ) {
                    $seriales_old=$this->Additional_item_seriales->get_item_serales_unsold($item_id)->result_array();;
                    foreach ($seriales_old as $value) {
                        $additional_item_seriales[] = $value["item_serial"];
                    }                   
                }
                $this->Additional_item_seriales->save($item_id, $additional_item_seriales);
            } else {
                $this->Additional_item_seriales->delete($item_id);
            }

            $data_unit_sale_item = array();
            $location_id = $this->Employee->get_logged_in_employee_current_location_id();
            if($this->input->post('has_sales_units') == 1)
            {  
                //$canti_select = 0;
                for ($i= 0; $i < count( $unit_sale) ; $i = $i + 6) 
                { 
                    $data_unit_sale_item[] =array(
                        "id" =>$unit_sale[$i] < 1 ? null : $unit_sale[$i] ,
                        "item_id" => $item_id,
                        "unit_measurement" => empty($unit_sale[$i + 1]) ? "NOT DEFINED" : $unit_sale[$i + 1],
                        "name" =>empty($unit_sale[$i + 2]) ? "NOT DEFINED" : $unit_sale[$i + 2],                    
                        "quatity" =>is_numeric($unit_sale[$i + 3]) ? $unit_sale[$i + 3] :1,
                        "price" =>is_numeric($unit_sale[$i + 4] ) ? $unit_sale[$i + 4] :0,
                        "default_select" =>$unit_sale[$i + 5],
                        "location_id" => $location_id,
                        "deleted" => 0
                    );
                    //$canti_select = $canti_select + $unit_sale[$i + 5];
                }
                //if($canti_select == 0)
                 //   $data_unit_sale_item[0]["default_select"] = 1;

                $this->Item_unit_sell->save( $data_unit_sale_item,$item_id);
            
                
            }
            if ($this->input->post('locations')) {
                foreach ($this->input->post('locations') as $location_id => $item_location_data) {

                    $subcategory_data_custom1=$item_location_data['subcategory_data_custom1'];
                    $subcategory_data_custom2=$item_location_data['subcategory_data_custom2'];
                    $subcategory_data_quantity=$item_location_data['subcategory_data_quantity'];
                    $subcategory_data_expiration_date = isset($item_location_data['subcategory_data_date']) ? $item_location_data['subcategory_data_date']:[];
                    $override_prices = isset($item_location_data['override_prices']) && $item_location_data['override_prices'];
                    $override_defect = isset($item_location_data['override_defect']) && $item_location_data['override_defect'];
                    $item_location_before_save = $this->Item_location->get_info($item_id, $location_id);
                    $data = array(
                        'location_id' => $location_id,
                        'item_id' => $item_id,
                        'location' => $item_location_data['location'],
                        'cost_price' => $override_prices && $item_location_data['cost_price'] != '' ? $item_location_data['cost_price'] : null,
                        'unit_price' => $override_prices && $item_location_data['unit_price'] != '' ? $item_location_data['unit_price'] : null,
                        'items_discount' => $override_prices && $item_location_data['items_discounts'] != '' ? $item_location_data['items_discounts'] : 0,
                        'promo_price' => $override_prices && $item_location_data['promo_price'] != '' ? $item_location_data['promo_price'] : null,
                        'quantity_defect' => $override_defect && $item_location_data['quantity_defect'] != '' ? $item_location_data['quantity_defect'] : null,
                        'start_date' => $override_prices && $item_location_data['promo_price'] != '' && $item_location_data['start_date'] != '' ? date('Y-m-d', strtotime($item_location_data['start_date'])) : null,
                        'end_date' => $override_prices && $item_location_data['promo_price'] != '' && $item_location_data['end_date'] != '' ? date('Y-m-d', strtotime($item_location_data['end_date'])) : null,
                        'quantity' => $item_location_data['quantity'] != '' && !$this->input->post('is_service') ? $item_location_data['quantity'] : null,
                        'quantity_warehouse' => $item_location_data['quantity_warehouse'] != '' && !$this->input->post('is_service') ? $item_location_data['quantity_warehouse'] : null,
                        'reorder_level' => isset($item_location_data['reorder_level']) && $item_location_data['reorder_level'] != '' && $item_location_data['reorder_level'] != $this->input->post('reorder_level') ? $item_location_data['reorder_level'] : null,
                        'override_default_tax' => isset($item_location_data['override_default_tax']) && $item_location_data['override_default_tax'] != '' ? $item_location_data['override_default_tax'] : 0,
                        'override_defect' => isset($item_location_data['override_defect']) && $item_location_data['override_defect'] != '' ? $item_location_data['override_defect'] : 0,
                    );
                    if($item_id>0 and !$this->Employee->has_module_action_permission('items','edit_quantity', $this->Employee->get_logged_in_employee_info()->person_id)){
                        $tem_info=  $this->Item_location->get_info($item_id, $location_id);
                        $data['quantity'] =!$this->input->post('is_service') ? $tem_info->quantity: null;
                        $data['quantity_warehouse'] = !$this->input->post('is_service') ?   $tem_info->quantity_warehouse : null;                        
                    }
                    $this->Item_location->save($data, $item_id, $location_id);
                   
                    if($this->config->item('subcategory_of_items')== 1 && $this->input->post('subcategory') )
                    {
                        $data_subcategory = array();
                        for ($i = 0; $i < count($subcategory_data_quantity); $i++) 
                        {
                            $data_aux = array(
                                "item_id"=>$item_id,
                                "location_id"=>$location_id,
                                "custom1"=>strtoupper ($subcategory_data_custom1[$i]),
                                "custom2"=>strtoupper($subcategory_data_custom2[$i]),
                                "quantity"=>$subcategory_data_quantity[$i],
                                "expiration_date"=>null
                            );
                            if( $this->config->item("activate_pharmacy_mode")){
                                $data_aux["expiration_date"] =  $subcategory_data_expiration_date[$i] ? date('Y-m-d 00:00:00', strtotime($subcategory_data_expiration_date[$i])) : null;
                            }
                            $data_subcategory[]=$data_aux;

                        }
                        if(count($subcategory_data_quantity) > 0)
                        {
                            $result=$this->items_subcategory->save($data_subcategory, $item_id, $location_id);
                            if(!$result)
                            echo json_encode(array('success' => false, 'message' =>  'Error en agregar las categoría(s), no puede duplicar el '.
                            $this->config->item('custom_subcategory1_name').' y '.$this->config->item('custom_subcategory2_name').' de un ítem en la misma tienda' ));

                        }
                        
                    } else if(!$this->input->post('subcategory')){
                        // elminamos las subcategoria
                       $this->items_subcategory-> delete_all_by_id($location_id, $item_id)   ;  
                    }            

                    if (isset($item_location_data['item_tier'])) {
                        $tier_type = $item_location_data['tier_type'];

                        foreach ($item_location_data['item_tier'] as $tier_id => $price_or_percent) {
                            //If we are overriding prices and we have a price/percent, add..otherwise delete
                            if ($override_prices && $price_or_percent) {
                                $tier_data = array('tier_id' => $tier_id);
                                $tier_data['item_id'] = isset($item_data['item_id']) ? $item_data['item_id'] : $item_id;
                                $tier_data['location_id'] = $location_id;

                                if ($tier_type[$tier_id] == 'unit_price') {
                                    $tier_data['unit_price'] = $price_or_percent;
                                    $tier_data['percent_off'] = null;
                                } else {
                                    $tier_data['percent_off'] = (int) $price_or_percent;
                                    $tier_data['unit_price'] = null;
                                }

                                $this->Item_location->save_item_tiers($tier_data, $item_id, $location_id);
                            } else {
                                $this->Item_location->delete_tier_price($tier_id, $item_id, $location_id);
                            }
                        }
                    }

                    if (isset($item_location_data['tax_names'])) {
                        $location_items_taxes_data = array();
                        $tax_names = $item_location_data['tax_names'];
                        $tax_percents = $item_location_data['tax_percents'];
                        $tax_cumulatives = $item_location_data['tax_cumulatives'];
                        for ($k = 0; $k < count($tax_percents); $k++) {
                            if (is_numeric($tax_percents[$k])) {
                                $location_items_taxes_data[] = array('name' => $tax_names[$k], 'percent' => $tax_percents[$k], 'cumulative' => isset($tax_cumulatives[$k]) ? $tax_cumulatives[$k] : '0');
                            }
                        }
                        $this->Item_location_taxes->save($location_items_taxes_data, $item_id, $location_id);
                    }

                    if ($item_location_data['quantity'] != '' && !$this->input->post('is_service') && $item_location_data['quantity'] != $item_location_before_save->quantity) {
                        $inv_data = array
                            (
                            'trans_date' => date('Y-m-d H:i:s'),
                            'trans_items' => $item_id,
                            'trans_user' => $employee_id,
                            'trans_comment' => lang('items_manually_editing_of_quantity'),
                            'trans_inventory' => $item_location_data['quantity'] - $item_location_before_save->quantity,
                            'location_id' => $location_id,
                        );
                        $this->Inventory->insert($inv_data);
                    }
                }
            }
            $items_taxes_data = array();
            $tax_names = $this->input->post('tax_names');
            $tax_percents = $this->input->post('tax_percents');
            $tax_cumulatives = $this->input->post('tax_cumulatives');
            for ($k = 0; $k < count($tax_percents); $k++) {
                if (is_numeric($tax_percents[$k])) {
                    $items_taxes_data[] = array('name' => $tax_names[$k], 'percent' => $tax_percents[$k], 'cumulative' => isset($tax_cumulatives[$k]) ? $tax_cumulatives[$k] : '0');
                }
            }

            $this->Item_taxes->save($items_taxes_data, $item_id);

            //Delete Image
            if ($this->input->post('del_image') && $item_id != -1) {
                if ($cur_item_info->image_id != null) {
                    $this->Item->update_image(null, $item_id);
                    $this->Appfile->delete($cur_item_info->image_id);
                }
            }

            //Save Image File
            if (!empty($_FILES["image_id"]) && $_FILES["image_id"]["error"] == UPLOAD_ERR_OK) {
                $allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
                $extension = strtolower(pathinfo($_FILES["image_id"]["name"], PATHINFO_EXTENSION));

                if (in_array($extension, $allowed_extensions)) {
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $_FILES["image_id"]["tmp_name"];
                    $config['create_thumb'] = false;
                    $config['maintain_ratio'] = true;
                    $config['width'] = 400;
                    $config['height'] = 300;
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
                    $image_file_id = $this->Appfile->save($_FILES["image_id"]["name"], file_get_contents($_FILES["image_id"]["tmp_name"]));
                }

                $this->Item->update_image($image_file_id, $item_id);
            }
        } else //failure
        {
           if( $error_datos_custom){
            echo json_encode(array('success' => false, 'message' => lang('items_error_adding_updating') . '(subcategoría) ' .
            $item_data['name'], 'item_id' => -1));
           }else{
            echo json_encode(array('success' => false, 'message' => lang('items_error_adding_updating') . ' ' .
            $item_data['name'], 'item_id' => -1));
           }
           
        }
    }

    public function transferent()
    {
        $item_id = $this->input->post('item_id');

        if ($this->input->post('item_id') && $this->input->post('type_transferent') == "Stock a Bodega" && $this->input->post('quantity_transfer') > 0 && $this->input->post('quantity_transfer') <= $this->input->post('quantity')) {
            $location_id = $this->input->post('location_id');
            $item_id = $this->input->post('item_id');
            $data['quantity_stock'] = $this->input->post('quantity');
            $data['quantity_warehouse'] = $this->input->post('quantity_warehouse');
            $data['quantity_transfer'] = $this->input->post('quantity_transfer');
            $new_quantity = $data['quantity_stock'] - $data['quantity_transfer'];
            $new_warehouse = $data['quantity_warehouse'] + $data['quantity_transfer'];

            $data = array(
                'location_id' => $this->input->post('location_id'),
                'item_id' => $this->input->post('item_id'),
                'quantity' => $new_quantity,
                'quantity_warehouse' => $new_warehouse,
            );

            $this->Item_location->save($data, $item_id, $location_id);

            echo json_encode(array('success' => true, 'message' => lang('items_stock_adding_updating') . ' ' .
                $item_id, 'item_id' => $item_id));
        } elseif ($this->input->post('item_id') && $this->input->post('type_transferent') == "Bodega a Stock" && $this->input->post('quantity_transfer') > 0 && $this->input->post('quantity_transfer') <= $this->input->post('quantity_warehouse')) {
            $location_id = $this->input->post('location_id');
            $item_id = $this->input->post('item_id');
            $data['quantity_stock'] = $this->input->post('quantity');
            $data['quantity_warehouse'] = $this->input->post('quantity_warehouse');
            $data['quantity_transfer'] = $this->input->post('quantity_transfer');
            $new_quantity = $data['quantity_stock'] + $data['quantity_transfer'];
            $new_warehouse = $data['quantity_warehouse'] - $data['quantity_transfer'];

            $data = array(
                'location_id' => $this->input->post('location_id'),
                'item_id' => $this->input->post('item_id'),
                'quantity' => $new_quantity,
                'quantity_warehouse' => $new_warehouse,
            );

            $this->Item_location->save($data, $item_id, $location_id);

            echo json_encode(array('success' => true, 'message' => lang('items_stock_warehouse_updating') . ' ' .
                $item_id, 'item_id' => $item_id));
        } else //failure
        {
            switch ($item_id > 0) {
                case $this->input->post('type_transferent') == "Stock a Bodega" && $this->input->post('quantity_transfer') >= $this->input->post('quantity'):
                    echo json_encode(array('success' => false, 'message' => lang('items_error_transfer_stock')));
                    break;

                case $this->input->post('type_transferent') == "Bodega a Stock" && $this->input->post('quantity_transfer') >= $this->input->post('quantity_warehouse'):
                    echo json_encode(array('success' => false, 'message' => lang('items_error_transfer_warehouse')));
                    break;

                case $this->input->post('quantity_transfer') <= 0:
                    echo json_encode(array('success' => false, 'message' => lang('items_error_transfer_negative')));
                    break;
            }
        }
    }

    public function save_inventory($item_id = -1)
    {
        $this->check_action_permission('agregar_o_sustraer');
        $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
        $cur_item_info = $this->Item->get_info($item_id);
        $cur_item_location_info = $this->Item_location->get_info($item_id);

        $inv_data = array
            (
            'trans_date' => date('Y-m-d H:i:s'),
            'trans_items' => $item_id,
            'trans_user' => $employee_id,
            'trans_comment' => $this->input->post('trans_comment'),
            'trans_inventory' => $this->input->post('newquantity'),
            'location_id' => $this->Employee->get_logged_in_employee_current_location_id(),
        );
        $this->Inventory->insert($inv_data);

        if($this->config->item('subcategory_of_items') )
        {
            $custom1_subcategory = $this->input->post('custom1_subcategory');
            $custom2_subcategory = $this->input->post('custom2_subcategory');
            $subcategory = $this->items_subcategory->get_info($item_id, false, $custom1_subcategory, $custom2_subcategory);
            $quantity_subcategory = $subcategory->quantity;
            $result = $this->items_subcategory->save_quantity(($quantity_subcategory-$this->input->post('newquantity')), 
                $item_id, false, $custom1_subcategory,$custom2_subcategory);                            
        }
        //Update stock quantity
        if ($this->Item_location->save_quantity($cur_item_location_info->quantity + $this->input->post('newquantity'), $item_id)) {
            echo json_encode(array('success' => true, 'message' => lang('items_successful_updating') . ' ' .
                $cur_item_info->name, 'item_id' => $item_id));
        } else //failure
        {
            echo json_encode(array('success' => false, 'message' => lang('items_error_adding_updating') . ' ' .
                $cur_item_info->name, 'item_id' => -1));
        }
    }

    public function clear_state()
    {
        $this->session->unset_userdata('item_search_data');        
        redirect('items');
    }

    public function clear_state_consultant()
    {
        $this->session->unset_userdata('item_search_data');
        redirect('items/consultant');
        
    }

    public function bulk_update()
    {
        $this->db->trans_start();

        $this->check_action_permission('add_update');
        $items_to_update = $this->input->post('item_ids');
        $shop_online = $this->input->post('shop_online')?1:0;
        $select_inventory = $this->get_select_inventory();
        //clears the total inventory selection
        $this->clear_select_inventory();
        $item_data = array();
        $valor = $this->Item->get_multiple_info($items_to_update);
        if ($valor->num_rows() > 0) {
            $i = 0;
            foreach ($valor->result() as $row) {

                $tax[$i] = $this->input->post('costo_tax') / 100;
                $price[$i] = $row->unit_price;
                $precio_total[$i] = $price[$i] * $tax[$i];
                $item[$i] = ($precio_total[$i] < 0) ? $price[$i] + $precio_total[$i] : $price[$i] + $precio_total[$i];
                $i++;
            }
            $this->Item->update($item, $items_to_update,$shop_online);
        }

        foreach ($_POST as $key => $value) {
            if ($key == 'submit') {
                continue;
            }

            //This field is nullable, so treat it differently
            if ($key == 'supplier_id') {
                if ($value != '') {
                    $item_data["$key"] = $value == '-1' ? null : $value;
                }
            }
            if ($key == 'price_suppliers') {
                if ($value != '') {
                    $item_data["$key"] = $value == '-1' ? '' : $value;
                }
            }
            if ($key == 'unit_price') {
                if ($value != '') {

                    $price = $_POST['unit_price'];
                    $tax = $_POST['costo_tax'] / 100;
                    $precio_total = $price * $tax;
                    $precio = ($precio_total < 0) ? $price + $precio_total : $price + $precio_total;
                    $item_data["$key"] = $precio;
                }
            } elseif ($value != '' && ($key == 'start_date' || $key == 'end_date')) {
                $item_data["$key"] = date('Y-m-d', strtotime($value));
            } elseif ($value != '' && $key == 'quantity') {
                $this->Item_location->update_multiple(array('quantity' => $value), $items_to_update, $select_inventory);

            } elseif ($value != '' and !(in_array($key, array('item_ids', 'tax_names', 'tax_percents', 'tax_cumulatives', 'select_inventory')))) {
                $item_data["$key"] = $value;
            }
        }

        //Item data could be empty if tax information is being updated
        if (empty($item_data) || $this->Item->update_multiple($item_data, $items_to_update, $select_inventory)) {
            //Only update tax data of we are override taxes
            if (isset($item_data['override_default_tax']) && $item_data['override_default_tax']) {
                $items_taxes_data = array();
                $tax_names = $this->input->post('tax_names');
                $tax_percents = $this->input->post('tax_percents');
                $tax_cumulatives = $this->input->post('tax_cumulatives');

                for ($k = 0; $k < count($tax_percents); $k++) {
                    if (is_numeric($tax_percents[$k])) {
                        $items_taxes_data[] = array('name' => $tax_names[$k], 'percent' => $tax_percents[$k], 'cumulative' => isset($tax_cumulatives[$k]) ? $tax_cumulatives[$k] : '0');
                    }
                }

                if (!empty($items_taxes_data)) {
                    $this->Item_taxes->save_multiple($items_taxes_data, $items_to_update, $select_inventory);
                }
            }
            echo json_encode(array('success' => true, 'message' => lang('items_successful_bulk_edit')));
        } else {
            echo json_encode(array('success' => false, 'message' => lang('items_error_updating_multiple')));
        }

        $this->db->trans_complete();
    }

    public function delete()
    {
        $this->check_action_permission('delete');
        $items_to_delete = $this->input->post('ids');
        $select_inventory = $this->get_select_inventory();
        $total_rows = $select_inventory ? $this->Item->count_all() : count($items_to_delete);
        //clears the total inventory selection
        $this->clear_select_inventory();
        if ($this->Item->delete_list($items_to_delete, $select_inventory)) {
            echo json_encode(array('success' => true, 'message' => lang('items_successful_deleted') . ' ' .
                $total_rows . ' ' . lang('items_one_or_multiple')));
        } else {
            echo json_encode(array('success' => false, 'message' => lang('items_cannot_be_deleted')));
        }
    }

    public function _excel_get_header_row()
    {
        $header_row = array();

        $header_row[] = lang('items_item_number');
        $header_row[] = lang('items_product_id');
        $header_row[] = lang('items_name');
        $header_row[] = lang('items_category');
        $header_row[] = lang('items_cost_price');
        $header_row[] = lang('items_unit_price');
        $header_row[] = lang('reports_profit');
        foreach ($this->Tier->get_all()->result() as $tier) {
            $header_row[] = $tier->name;
            $header_row[] = $tier->name.' porcentaje';
        }
        $header_row[] = lang('items_override_default_tax');
        for ($i = 1; $i < 6; $i++) {
            $header_row[] = 'Impuesto' . $i;
        }
        $header_row[] = lang('items_price_includes_tax');
        $header_row[] = lang('items_is_service');
        $header_row[] = lang('items_quantity');
        $header_row[] = lang('items_reorder_level');
        $header_row[] = lang('items_description');
        $header_row[] = lang('items_allow_alt_desciption');
        $header_row[] = lang('items_is_serialized');
        $header_row[] = lang('items_size');
        $header_row[] = lang('items_marca');
        $header_row[] = lang('items_model');
        $header_row[] = lang('items_colour');
        $header_row[] = lang('reports_commission');
        $header_row[] = lang('item_id');
        $header_row[] = lang('suppliers_supplier');
        $header_row[] = lang('suppliers_price');
       
        //$header_row[] = lang('items_images');
        if ($this->config->item('subcategory_of_items')==1){
            for($i=1;$i<=(int)$this->config->item('quantity_subcategory_of_items');$i++){
                $header_row[] = lang('items_subcategory').$i."_". ($this->config->item('custom_subcategory1_name') ?  $this->config->item('custom_subcategory1_name'):'Personalizado1');
                $header_row[] = lang('items_subcategory').$i."_". ($this->config->item('custom_subcategory2_name') ?  $this->config->item('custom_subcategory2_name'):'Personalizado2');
                $header_row[] = lang('items_subcategory').$i."_". lang('items_quantity');
                if($this->config->item('activate_pharmacy_mode')){
                    $header_row[] = lang('items_expiration_date');
                }
            }
           
        }
        return $header_row;
    }

    public function excel()
    {
        $this->load->helper('report');
        $header_row = $this->_excel_get_header_row();

        $content = array_to_spreadsheet(array($header_row));
        force_download('items_import.' . ($this->config->item('spreadsheet_format') == 'XLSX' ? 'xlsx' : 'csv'), $content);
    }

    public function excel_compare()
    {
        $data = $this->Item->get_all($this->Item->count_all())->result_object();
        $this->load->helper('report');
        $header_row = $this->_excel_get_header_row_compare_material();
        $rows[] = $header_row;
        foreach ($data as $r) {
            $row = array();
            $row[] = $r->item_number;
            $row[] = $r->product_id;
            $row[] = $r->item_id;
            $row[] = $r->name;
            $row[] = '';
            $row[] = $r->category;
            $rows[] = $row;
        }
        $content = array_to_spreadsheet($rows);
        force_download('compare_inventory.' . ($this->config->item('spreadsheet_format') == 'XLSX' ? 'xlsx' : 'csv'), $content);
        exit;
    }

    public function options_excel_export()
    {
        $this->load->view("items/options_excel_export");
    }

    public function excel_export($start = false, $end = false)
    {

        if ($start && $end) {
            $limit = ($end - $start) + 1;
            $start = $start - 1;
            $data = $this->Item->get_all($limit, $start)->result_object();
        } else {
            $data = $this->Item->get_all($this->Item->count_all())->result_object();
        }

        $this->load->helper('report');
        $header_row = $this->_excel_get_header_row();
        $rows[] = $header_row;

        $supplier = $this->Supplier->get_all()->result();
        $count = count($supplier);

        $j = 0;
        foreach ($data as $r) {
            $row = array();
            $row[] = $r->item_number;
            $row[] = $r->product_id;
            $row[] = $r->name;
            $row[] = $r->category;
            $row[] = to_currency_no_money($r->cost_price, 10);
            $row[] = to_currency_no_money($r->unit_price);
            $row[] = $r->items_discount;

            foreach ($this->Tier->get_all()->result() as $tier) {
                $tier_id = $tier->id;
                $tier_row = $this->Item->get_tier_price_row($tier_id, $r->item_id);
                $value = '';
                $value_porc = '';

                if (is_object($tier_row) && property_exists($tier_row, 'tier_id')) {
                    //$value = $tier_row->unit_price !== null ? to_currency_no_money($tier_row->unit_price) : $tier_row->percent_off . '%';
                    if($tier_row->unit_price>=0 && $tier_row->percent_off<=0 ){
                        $value=to_currency_no_money($tier_row->unit_price);
                    }else{
                        $value_porc = $tier_row->percent_off . '%'; 
                    }
                }

                $row[] = $value;
                $row[] = $value_porc;
            }

            $row[] = $r->override_default_tax ? 'y' : '';

            for ($i = 0; $i < 5; $i++) {
                $taxes_id = $this->Item_taxes->get_info($r->item_id);
                $id = $taxes_id;

                if (isset($taxes_id[$i]['id'])) {
                    $id = $taxes_id[$i]['id'];
                    $taxes_row = $this->Item_taxes->get_taxes_tax_row($id, $r->item_id);
                    $value = 1;
                } else {
                    $taxes_row = '';
                    $value = '';
                }
                if (is_object($taxes_row) && property_exists($taxes_row, 'id') && $value == 1) {
                    $value = $taxes_row->percent;
                }

                $row[] = $value;
            }

            $row[] = $r->tax_included ? 'y' : '';
            $row[] = $r->is_service ? 'y' : '';
            $row[] = to_quantity($r->quantity, false);
            $row[] = to_quantity($r->reorder_level, false);
            $row[] = $r->description;
            $row[] = $r->allow_alt_description ? 'y' : '';
            $row[] = $r->is_serialized ? 'y' : '';
            $row[] = $r->size;
            $row[] = $r->marca;
            $row[] = $r->model;
            $row[] = $r->colour;
            $commission = '';

            if ($r->commission_fixed) {
                $commission = to_currency_no_money($r->commission_fixed);
            } elseif ($r->commission_percent) {
                $commission = to_currency_no_money($r->commission_percent) . '%';
            }
            $row[] = $commission;
            $row[] = $r->item_id;

            $i = 0;
            foreach ($this->Supplier->get_all()->result() as $supplier) {
                $supplier_row = $this->Supplier->suppliers_info_id($r->item_id, $supplier->person_id);
                $count_supplier_row[$r->item_id][] = count($supplier_row);

                if (isset($supplier_row[0]) && is_object($supplier_row[0]) && property_exists($supplier_row[0], 'supplier_id')) {
                    $id_supplier[$r->item_id][$i] = $supplier_row[0]->supplier_id;
                    $costo_suplier[$r->item_id][$i] = $supplier_row[0]->price_suppliers !== null ? to_currency_no_money($supplier_row[0]->price_suppliers) : '';
                    $i++;
                } else {
                    $value = '';
                    if (isset($supplier_row[0]) && is_object($supplier_row[0]) && property_exists($supplier_row[0], 'supplier_id')) {
                        $value = $supplier_row[0]->price_suppliers !== null ? to_currency_no_money($supplier_row[0]->price_suppliers) : '';
                    }
                    $row[] = $value;
                }
            }
            
            /**
             * [Description]
             * Solo se descomenta para obtener los IDs de la imaganes asociadas al producto al momento de exportar a excel
             */
            //$price_count_tiers=count($this->Tier->get_all()->result());
            //$row[27 + $price_count_tiers]= $r->image_id;
            if($this->config->item('subcategory_of_items')==1 )
            {
                $rows_subcategory=array();
                $index_=0;
                $canti_col=   count($header_row)-$this->config->item('quantity_subcategory_of_items')*3;  // se * por 3 cantidad de datos de las subcategoria 
                //$price_tiers_count + $price_taxes_count
                
                foreach($this->items_subcategory->get_all_by_id(false,  $r->item_id)as $subcategory)
                {
                    if($subcategory->custom1!="" && $subcategory->custom2!="")
                    {                 
                        $row[$canti_col+$index_]=$subcategory->custom1;
                        $row[$canti_col+$index_+1]=$subcategory->custom2;
                        $row[$canti_col+$index_+2]=$subcategory->quantity; 
                        $index_=$index_+3;    
                    }          
                }               
            }
            if (isset($count_supplier_row[$r->item_id]) && !empty($count_supplier_row[$r->item_id])) {
                $count_row = array_sum($count_supplier_row[$r->item_id]);
                if ($count_row > 0) {
                    $count_row = array_sum($count_supplier_row[$r->item_id]) - 1;
                }

                for ($i = 0; $i <= $count_row; $i++) {
                    if (isset($id_supplier[$r->item_id])) {
                        $price_count_tiers = count($this->Tier->get_all()->result());
                        $row[28 + $price_count_tiers] = $id_supplier[$r->item_id][$i];
                        $row[29 + $price_count_tiers] = to_currency_no_money($costo_suplier[$r->item_id][$i]);
                    }

                    $fila[$i] = $row;
                    $rows[] = $fila[$i];
                    $j++;
                }
            } else {
                $rows[] = $row;
            }
            
        }
        $content = array_to_spreadsheet($rows);
        force_download('items_export.' . ($this->config->item('spreadsheet_format') == 'XLSX' ? 'xlsx' : 'csv'), $content);
        exit;
    }

    public function excel_import()
    {
        $this->check_action_permission('add_update');
        $this->load->view("items/excel_import", null);
    }

    public function excel_import_compare()
    {
        $this->check_action_permission('add_update');
        $this->load->view("items/excel_import_compare", null);
    }

    public function do_excel_import()
    {
        if (is_on_demo_host()) {
            $msg = lang('items_excel_import_disabled_on_demo');
            echo json_encode(array('success' => false, 'message' => $msg));
            return;
        }

        set_time_limit(0);
        $this->check_action_permission('add_update');
        $this->db->trans_start();
        $msg = 'do_excel_import';
        $failCodes = array();
        if ($_FILES['file_path']['error'] != UPLOAD_ERR_OK) {
            $msg = lang('items_excel_import_failed');
            echo json_encode(array('success' => false, 'message' => $msg));
            return;
        } else {
            if (($handle = fopen($_FILES['file_path']['tmp_name'], "r")) !== false) {
                $objPHPExcel = file_to_obj_php_excel($_FILES['file_path']['tmp_name']);
                $sheet = $objPHPExcel->getActiveSheet();
                $num_rows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
                $price_tiers_count = $this->Tier->count_all()*2; // por 2, porque se agrego una nueva columna de porcentaje

                $price_taxes_count = 5;
                $tax_included_prices = 0;
                //Loop through rows, skip header row
                for ($k = 2; $k <= $num_rows; $k++) {
                    $name = $sheet->getCellByColumnAndRow(2, $k)->getValue();
                    if (!$name) {
                        $name = '';
                    }

                    $description = $sheet->getCellByColumnAndRow(12 + $price_tiers_count + $price_taxes_count, $k)->getValue();

                    if (!$description) {
                        $description = '';
                    }

                    $category = $sheet->getCellByColumnAndRow(3, $k)->getValue();

                    if (!$category) {
                        $category = '';
                    }

                    $cost_price = $sheet->getCellByColumnAndRow(4, $k)->getValue();

                    if ($cost_price == null) {
                        $cost_price = 0;
                    }

                    $unit_price = $sheet->getCellByColumnAndRow(5, $k)->getValue();

                    if ($unit_price == null) {
                        $unit_price = 0;
                    }

                    $items_discount = $sheet->getCellByColumnAndRow(6, $k)->getValue();

                    if ($items_discount == null) {
                        $items_discount = 0;
                    }

                    $override_default_tax = $sheet->getCellByColumnAndRow(7 + $price_tiers_count, $k)->getValue();
                    $override_default_tax = ($override_default_tax != null && $override_default_tax != '' and $override_default_tax != '0' and strtolower($override_default_tax) != 'n') ? '1' : '0';

                    $tax_included = $sheet->getCellByColumnAndRow(8 + $price_tiers_count + $price_taxes_count, $k)->getValue();
                    $tax_included = ($tax_included != null && $tax_included != '' and $tax_included != '0' and strtolower($tax_included) != 'n') ? '1' : '0';

                    $is_service = $sheet->getCellByColumnAndRow(9 + $price_tiers_count + $price_taxes_count, $k)->getValue();
                    $is_service = ($is_service != null && $is_service != '' and $is_service != '0' and strtolower($is_service) != 'n') ? '1' : '0';

                    $quantity = $sheet->getCellByColumnAndRow(10 + $price_tiers_count + $price_taxes_count, $k)->getValue();
                    $reorder_level = $sheet->getCellByColumnAndRow(11 + $price_tiers_count + $price_taxes_count, $k)->getValue();

                    $cost_supplier = $sheet->getCellByColumnAndRow(22 + $price_tiers_count + $price_taxes_count, $k)->getValue();
                    $cost_supplier = ($cost_supplier == null) ? '' : $cost_supplier;
                    $allow_alt_description = $sheet->getCellByColumnAndRow(13 + $price_tiers_count + $price_taxes_count, $k)->getValue();
                    $allow_alt_description = ($allow_alt_description != null && $allow_alt_description != '' and $allow_alt_description != '0' and strtolower($allow_alt_description) != 'n') ? '1' : '0';

                    $is_serialized = $sheet->getCellByColumnAndRow(14 + $price_tiers_count + $price_taxes_count, $k)->getValue();
                    $is_serialized = ($is_serialized != null && $is_serialized != '' and $is_serialized != '0' and strtolower($is_serialized) != 'n') ? '1' : '0';

                    $size = $sheet->getCellByColumnAndRow(15 + $price_tiers_count + $price_taxes_count, $k)->getValue();
                    $marca = $sheet->getCellByColumnAndRow(16 + $price_tiers_count + $price_taxes_count, $k)->getValue();
                    $model = $sheet->getCellByColumnAndRow(17 + $price_tiers_count + $price_taxes_count, $k)->getValue();
                    $colour = $sheet->getCellByColumnAndRow(18 + $price_tiers_count + $price_taxes_count, $k)->getValue();

                    if (!$size) {
                        $size = '';
                    }

                    if (!$marca) {
                        $marca = '';
                    }

                    if (!$model) {
                        $model = '';
                    }

                    if (!$colour) {
                        $colour = '';
                    }

                    $item_number = $sheet->getCellByColumnAndRow(0, $k)->getValue();
                    $product_id = $sheet->getCellByColumnAndRow(1, $k)->getValue();
                    $item_id = $sheet->getCellByColumnAndRow(20 + $price_tiers_count + $price_taxes_count, $k)->getValue();
                    //$image_id = $sheet->getCellByColumnAndRow(22+$price_tiers_count+$price_taxes_count, $k)->getValue();

                    if (!$item_id) {
                        $item_id = false;
                    }
                   
                    $item_data = array(
                        'name' => $name,
                        'description' => $description,
                        'category' => $category,
                        'cost_price' => to_currency_no_money($cost_price),
                        'unit_price' => to_currency_no_money($unit_price),
                        'items_discount' => $items_discount,
                        'tax_included' => $tax_included,
                        'override_default_tax' => $override_default_tax,
                        'is_service' => $is_service,
                        'reorder_level' => $reorder_level,
                        'allow_alt_description' => $allow_alt_description,
                        'is_serialized' => $is_serialized,
                        'size' => $size,
                        'marca' => $marca,
                        'model' => $model,
                        'colour' => $colour,
                        'subcategory'=>0
                        //'image_id'    =>  $image_id,
                    );

                    $supplier_id = $sheet->getCellByColumnAndRow(21 + $price_tiers_count + $price_taxes_count, $k)->getValue();

                    if ($supplier_id != "") {
                        $item_data['supplier_id'] = $supplier_id;
                    } else {
                        $item_data['supplier_id'] = null;
                    }

                    if ($item_number != "") {
                        $item_data['item_number'] = $item_number;
                    } else {
                        $item_data['item_number'] = null;
                    }

                    if ($product_id != "") {
                        $item_data['product_id'] = $product_id;
                    } else {
                        $item_data['product_id'] = null;
                    }

                    $commission = $sheet->getCellByColumnAndRow(19 + $price_tiers_count + $price_taxes_count, $k)->getValue();

                    if ($commission) {
                        if (strpos($commission, '%') === false) {
                            $item_data['commission_fixed'] = (float) $commission;
                            $item_data['commission_percent'] = null;
                        } else {
                            $item_data['commission_percent'] = (float) $commission;
                            $item_data['commission_fixed'] = null;
                        }
                    } else {
                        $item_data['commission_percent'] = null;
                        $item_data['commission_fixed'] = null;
                    }
                    $subcategory=false;
                   // se mira si tiene alguna subcategoría el producto
                    $data_subcategory = array();
                    if($this->config->item('subcategory_of_items')==1 )
                    {
                       
                        // si en un futuro se quiere agragr nuevas columnas se debe de agregar antes de esta y 
                        //luego sumar la cantida de columa agregada la nuemro 23
                        // 5*3 =15
                        $increment = $this->config->item("activate_pharmacy_mode") ? 4: 3; // cantidad de datos de la subcategoría 
                        $cantidad =(int)$this->config->item('quantity_subcategory_of_items') *  $increment;

                        for($ij = 0;$ij < $cantidad; $ij = $ij + $increment)
                        {
                            if($this->config->item('inhabilitar_subcategory1')==1){
                                $custom1_subcategory ="»";

                            }else{
                                $custom1_subcategory = $sheet->getCellByColumnAndRow((23+$ij)+ $price_tiers_count + $price_taxes_count, $k)->getValue();

                            }
                            $custom2_subcategory = $sheet->getCellByColumnAndRow((23+$ij+1)+ $price_tiers_count + $price_taxes_count, $k)->getValue();
                            $cantidad_subcategory = $sheet->getCellByColumnAndRow((23+$ij+2 )+ $price_tiers_count + $price_taxes_count, $k)->getValue();
                            $date_subcategory = $sheet->getCellByColumnAndRow((23+$ij+3 )+ $price_tiers_count + $price_taxes_count, $k)->getValue();
                            
                            if(!empty( $date_subcategory) and $this->config->item("activate_pharmacy_mode"))
							{
								$timestamp  = PHPExcel_Shared_Date::ExcelToPHP($date_subcategory + 1);
								$date_subcategory = date("Y-m-d", $timestamp);
							}
							else  
								$date_subcategory = null;
                            
                            if($custom1_subcategory!="" &&  $custom2_subcategory!="" && $cantidad_subcategory!="" ) {
                                $subcategory=true;
                                $data_aux = array(
                                    "item_id" => $item_id,
                                    "location_id" => $this->Employee->get_logged_in_employee_current_location_id(),
                                    "custom1" => strtoupper ($custom1_subcategory),
                                    "custom2" => strtoupper($custom2_subcategory),
                                    "expiration_date" => $date_subcategory,
                                    "of_low" => 0,
                                    "quantity" => is_numeric($cantidad_subcategory) ? $cantidad_subcategory : 0,
                                );
                                 $data_subcategory[]=$data_aux;
                            }
                        }
                      
                        if($subcategory){
                            $item_data["subcategory"]=1;
                        }
                    }
                    if ($this->Item->save($item_data, $item_id)) {
                        $this->Categories->save(-1,array(
                            "name" => $category,
                            "img" => "",
                            "name_img_original"=> "",
                            "deleted"=> 0,
                        ));
                        if (!empty($item_data['supplier_id']) && $item_data['supplier_id'] != null) {
                            $item_data_supliers = array(
                                'item_id' => isset($item_data['item_id']) ? $item_data['item_id'] : $item_id,
                                'supplier_id' => $item_data['supplier_id'],
                                'price_suppliers' => $cost_supplier,
                            );

                            $this->Supplier->save_suppliers($item_data_supliers, $item_data['supplier_id']);
                        }

                        $item_unit_price_col_index = 5;

                        $counter = 1;                      

                        foreach ($this->Tier->get_all()->result() as $tier) {
                            $tier_id = $tier->id;
                            $tier_data = array('tier_id' => $tier_id);
                            $tier_data['item_id'] = isset($item_data['item_id']) ? $item_data['item_id'] : $item_id;
                            $tier_value = $sheet->getCellByColumnAndRow($item_unit_price_col_index + ($counter + 1), $k)->getValue();
                            $tier_value_porc = $sheet->getCellByColumnAndRow($item_unit_price_col_index + ($counter + 2), $k)->getValue();
                            if ($tier_value || $tier_value_porc) {
                                if ($tier_value) {
                                    $tier_data['unit_price'] = $tier_value;
                                    $tier_data['percent_off'] = null;
                                } else {
                                    $tier_data['percent_off'] = (int) $tier_value_porc;
                                    $tier_data['unit_price'] = null;
                                }

                                $this->Item->save_item_tiers($tier_data, isset($item_data['item_id']) ? $item_data['item_id'] : $item_id);
                            } else {
                                $this->Item->delete_tier_price($tier_id, isset($item_data['item_id']) ? $item_data['item_id'] : $item_id);
                            }
                            $counter++;
                        }
                        $i = 0;
                        $j = 1;
                        $item_tax_price_col_index = 7;
                        $fila = $sheet->getCellByColumnAndRow(11 + $tax_included_prices, $k)->getFormattedValue();
                        $valor = count($fila);
                        $tax_data['item_id'] = isset($item_data['item_id']) ? $item_data['item_id'] : $item_id;

                        for ($i = 0; $i < 5; $i++) {
                            $tax_value[$i] = (array('name' => $sheet->getCellByColumnAndRow($item_tax_price_col_index + $price_tiers_count + $i, 1)->getValue(), 'percent' => $sheet->getCellByColumnAndRow($item_tax_price_col_index + $price_tiers_count + $i, $k)->getValue() == '0.000' ? '' : $sheet->getCellByColumnAndRow($item_tax_price_col_index + $price_tiers_count + $i, $k)->getValue(), 'item_id' => $tax_data['item_id']));
                            $j++;
                        }

                        if (!empty($tax_value)) {
                            $this->Item_taxes->save_multiple($tax_value, $tax_data);
                        }

                        $item_location_before_save = $this->Item_location->get_info($item_id, $this->Employee->get_logged_in_employee_current_location_id());

                        $this->Item_location->save_quantity($quantity != null ? $quantity : null, isset($item_data['item_id']) ? $item_data['item_id'] : $item_id);
                        
                        //actualiza id item antes de guardar subcategory
                        if($this->config->item('subcategory_of_items') ==1)
                        {
                            for($j=0;$j<count($data_subcategory);$j++)
                            {
                                $data_subcategory[$j]["item_id"]=isset($item_data['item_id']) ? $item_data['item_id'] : $item_id;
                            }
                        }
                        $this->items_subcategory->save($data_subcategory, (isset($item_data['item_id']) ? $item_data['item_id'] : $item_id),  $this->Employee->get_logged_in_employee_current_location_id());
                        $employee_id = $this->Employee->get_logged_in_employee_info()->person_id;
                        $emp_info = $this->Employee->get_info($employee_id);
                        $comment = lang('items_csv_import');

                        //Only log inventory if quantity changes
                        if (!$item_data['is_service'] && $quantity != $item_location_before_save->quantity) {
                            $inv_data = array
                                (
                                'trans_date' => date('Y-m-d H:i:s'),
                                'trans_items' => isset($item_data['item_id']) ? $item_data['item_id'] : $item_id,
                                'trans_user' => $employee_id,
                                'trans_comment' => $comment,
                                'trans_inventory' => $quantity - $item_location_before_save->quantity,
                                'location_id' => $this->Employee->get_logged_in_employee_current_location_id(),
                            );
                            $this->Inventory->insert($inv_data);
                        }
                    } else //insert or update item failure
                    {
                        $error_mysql= $this->db->_error_message();
                        echo json_encode(array('success' => false, 'message' => lang('items_duplicate_item_ids'),"erro_mysql"=>$error_mysql));
                        return;
                    }
                }
            } else {
                $error_mysql= $this->db->_error_message();
                echo json_encode(array('success' => false, 'message' => lang('common_upload_file_not_supported_format'),"erro_mysql"=>$error_mysql));
                return;
            }
        }

        $this->db->trans_complete();
        echo json_encode(array('success' => true, 'message' => lang('items_import_successful')));
    }
    public function _excel_get_header_row_compare()
    {
        $header_row = array();
        $header_row[] = lang('items_item_number');
        $header_row[] = lang('items_product_id');
        $header_row[] = lang('items_item_id');
        $header_row[] = lang('items_name');
        $header_row[] = lang('items_compare');
        $header_row[] = lang('items_compare_quantity');
        $header_row[] = lang('items_compare_real');
        return $header_row;
    }
    public function _excel_get_header_row_compare_material()
    {
        $header_row = array();
        $header_row[] = lang('items_item_number');
        $header_row[] = lang('items_product_id');
        $header_row[] = lang('items_item_id');
        $header_row[] = lang('items_name');
        $header_row[] = lang('items_compare');
        $header_row[] = lang('items_category');
        return $header_row;
    }
    public function do_excel_import_compare($flag = false)
    {
        if ($flag) {
            $spreadsheet = $this->session->all_userdata();
            $content = array_to_spreadsheet($spreadsheet['spreadsheet']);
            force_download('items_export.' . ($this->config->item('spreadsheet_format') == 'XLSX' ? 'xlsx' : 'csv'), $content);
            exit;
        } else {
            $this->load->helper('report');
            $header_row = $this->_excel_get_header_row_compare();
            $rows[] = $header_row;

            if (($handle = fopen($_FILES['file_path']['tmp_name'], "r")) !== false) {
                $objPHPExcel = file_to_obj_php_excel($_FILES['file_path']['tmp_name']);
                $sheet = $objPHPExcel->getActiveSheet();
                $num_rows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

                $price_taxes_count = 5;
                $tax_included_prices = 0;
                //Loop through rows, skip header row
                $counter = 0;
                for ($k = 2; $k <= $num_rows; $k++) {
                    $row = array();
                    $item_id = $sheet->getCellByColumnAndRow(2, $k)->getValue();
                    $quantity = $this->Item->get_all_id($item_id);

                    $row[] = $sheet->getCellByColumnAndRow(0, $k)->getValue();
                    $row[] = $sheet->getCellByColumnAndRow(1, $k)->getValue();
                    $row[] = $sheet->getCellByColumnAndRow(2, $k)->getValue();

                    if ($quantity->num_rows() > 0) {
                        $id_item = $quantity->row()->item_id;
                        $id = intval($id_item);
                        $name = $this->Item->get_id($id)->row()->name;
                        $row[] = $sheet->getCellByColumnAndRow(3, $k)->getValue();
                        $row[] = $sheet->getCellByColumnAndRow(4, $k)->getValue();
                        $quant = $quantity->row()->quantity == null ? 0 : $quantity->row()->quantity;
                        $row[] = to_quantity($quant);
                        $quantity_compare = $sheet->getCellByColumnAndRow(4, $k)->getValue();
                        $real = to_quantity($quantity->row()->quantity) - $quantity_compare;
                    } else {
                        echo json_encode(array('success' => false, 'message' => lang('items_duplicate_item_ids')));
                        return;
                    }
                    $row[] = abs($real);
                    $rows[] = $row;
                }
                $this->session->set_userdata('spreadsheet', $rows); // variable 'rows' added to the sessions
                echo json_encode(array('success' => true, 'message' => lang('items_import_successful')));
                exit;
            } else {
                echo json_encode(array('success' => false, 'message' => lang('common_upload_file_not_supported_format')));
                return;
            }
        }
    }
    public function cleanup()
    {
        $this->Item->cleanup();
        echo json_encode(array('success' => true, 'message' => lang('items_cleanup_sucessful')));
    }
    public function select_inventory()
    {
        $this->session->set_userdata('select_inventory', 1);
    }

    public function get_select_inventory()
    {
        return $this->session->userdata('select_inventory') ? $this->session->userdata('select_inventory') : 0;
    }

    public function clear_select_inventory()
    {
        $this->session->unset_userdata('select_inventory');

    }
    function does_have_subcategory(){
        $is_subcataery=false;
        $item_id= $this->input->post('item_id') ? $this->input->post('item_id') : 0;
        $item=$this->Item-> get_info($item_id);
        if( $this->config->item('subcategory_of_items')==1 && $item->subcategory){
            $is_subcataery=true;          
        }       
       echo json_encode( $is_subcataery);
    }
}

//END OF FILE

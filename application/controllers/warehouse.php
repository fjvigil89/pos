<?php  if (!defined('BASEPATH')) 
exit('No direct script access allowed');
require_once ("secure_area.php");
class Warehouse extends  Secure_area
{
    function __construct() {
        parent::__construct('warehouse');    
        $this->load->helper('report');
        $this->load->model('Orders_sales');

    }
    public function last_orders($start_date){
        $start_date = rawurldecode($start_date);
        $table_data = $this->Orders_sales->get_all2(10000, 0,"number","DESC",$start_date,"2150-12-31");
        $manage_table = get_warehouse_home_manage_table_data_rows($table_data, $this);
        echo json_encode(array("data"=>$manage_table, "rows"=>$table_data->num_rows(),"date"=>date('Y-m-d H:i:s')));

    }
    public function receipt($order_sale_id){
        $data=array();
        $orden_info= $this->Orders_sales->get_info($order_sale_id);
        $items=  $this->Orders_sales->get_items($orden_info->sale_id);
        $sale_info= $this->Sale->get_info($orden_info->sale_id)->row();
        $data = array();

        $data["orden_info"] = $orden_info;  
        $data["items"]   =$items;  
        $data["vendedor"]= $this->Employee->get_info($sale_info->sold_by_employee_id) ;
		$data['transaction_time']= date(get_date_format().' '.get_time_format(), strtotime($orden_info->date));

        $this->load->view("warehouse/receipt", $data);
    }
    public function update_state($order_sale_id){
        $description = $this->input->post('description');
        $state = $this->input->post('state');
        $result =$this->Orders_sales->update($order_sale_id,array("state"=>$state, "description"=>$description));
        echo json_encode(
            array('success' => $result,'message' => "")
        );


    }
    public function suggest()
    {
        //allow parallel searchs to improve performance.
       
        session_write_close();
        $suggestions = $this->Orders_sales->get_search_suggestions($this->input->get('term'), 100);
        echo json_encode($suggestions);
    }
    public function clear_state()
    {
        $this->session->unset_userdata('warehouse_search_data');
        redirect('warehouse');
    }
    public function view_modal($order_sale_id)
    {
       
        $orden_info= $this->Orders_sales->get_info($order_sale_id);
        $items=  $this->Orders_sales->get_items($orden_info->sale_id);
        $sale_info= $this->Sale->get_info($orden_info->sale_id)->row();
        $data = array();

        $data["orden_info"] = $orden_info;  
        $data["items"]   =$items;  
        $data["vendedor"]= $this->Employee->get_info($sale_info->sold_by_employee_id) ;
        $data["options"]=array(lang("warehouse_pendiente")=>lang("warehouse_pendiente"),lang("warehouse_rechazado")=>lang("warehouse_rechazado"),lang("warehouse_entregado")=>lang("warehouse_entregado"));

        $this->load->view("warehouse/items_modal", $data);

    }

    public function sorting()
    {

        $search = $this->input->post('search') ? $this->input->post('search') : "";
        $userdata = $this->session->userdata('warehouse_search_data') ?  $this->session->userdata('warehouse_search_data') : array();
        $estado = isset($userdata["estado"]) ? $userdata["estado"] : false;
        $start_date =  isset($userdata["start_date"]) ? $userdata["start_date"]:date('Y-m-d');
        $end_date = isset($userdata["end_date"]) ? $userdata["end_date"]:date('Y-m-d');
    
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
        $order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'number';
        $order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir') : 'DESC';

        $search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, "estado" => $estado, "search" => $search,"start_date"=>$start_date,"end_date"=>$end_date);
        $this->session->set_userdata("warehouse_search_data", $search_data);
        if ($search || $estado) {
            $config['total_rows'] = $this->Orders_sales->search_count_all($search, $estado,$start_date , $end_date);
            $table_data = $this->Orders_sales->search($search, $estado, $per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'number', $this->input->post('order_dir') ? $this->input->post('order_dir') : ' DESC',
             $start_date,$end_date);
        } else {
            $config['total_rows'] = $this->Orders_sales->count_all($start_date , $end_date);
            $table_data = $this->Orders_sales->get_all($per_page, $this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'number', $this->input->post('order_dir') ? $this->input->post('order_dir') : 'DESC', 
            $start_date,$end_date);
        }

        $config['per_page'] = $per_page;
        $config['base_url'] = site_url('warehouse/sorting');
        $config['total_rows'] = $this->Orders_sales->count_all($start_date , $end_date);
        //$table_data = $this->Change_house->get_all($per_page, $offset, $order_col, $order_dir, $employee_id);
        $data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_warehouse_home_manage_table_data_rows($table_data, $this);

        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }
    function index($offset=0){
        $data=array();
        
        $params = $this->session->userdata('warehouse_search_data') ? $this->session->userdata('warehouse_search_data') : array('offset' => 0, 'order_col' => 'number', 'order_dir' => 'DESC', 'search' => false, 'estado' => false, "start_date"=>date('Y-m-d'),"end_date"=>date('Y-m-d'));
        if ($offset != $params['offset']) {
            redirect('warehouse/index/' . $params['offset']);
        }

        $data['controller_name'] = strtolower(get_class());
        
        $data["options"]=array(null=>"",lang("warehouse_pendiente")=>lang("warehouse_pendiente"),lang("warehouse_rechazado")=>lang("warehouse_rechazado"),lang("warehouse_entregado")=>lang("warehouse_entregado"));
      
        $data["start_date"]=$params["start_date"];
        $data["end_date"]=$params["end_date"];
        $fechas =get_simple_date_ranges(false);
        $fechas["0000-00-00/0000-00-00"]=lang("warehouse_rango_fecha");      
        $data["report_date_range_simple"]=$fechas;
        $data['estado'] = (isset($params['estado']) && $params['estado'] != false) ? $params['estado'] : "";
        $config['base_url'] = site_url('warehouse/sorting');
        $config['per_page'] = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $data['controller_name'] = strtolower(get_class());
        $data['search'] = $params['search'] ? $params['search'] : "";
        $data['per_page'] = $config['per_page'];

        if ($data['search'] || $data['estado']) {

            $config['total_rows'] = $this->Orders_sales->search_count_all($data['search'], $data['estado'],$params["start_date"],$params["end_date"],false);
            $table_data = $this->Orders_sales->search($data['search'], $data['estado'], $data['per_page'], $params['offset'], $params['order_col'], $params['order_dir'],$params["start_date"],$params["end_date"]);
        } else {
            $config['total_rows'] = $this->Orders_sales->count_all($params["start_date"],$params["end_date"]);
            $table_data = $this->Orders_sales->get_all($data['per_page'], $params['offset'], $params['order_col'], $params['order_dir'],$params["start_date"],$params["end_date"]);
        }

        $data['total_rows'] = $config['total_rows'];
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['order_col'] = $params['order_col'];
        $data['order_dir'] = $params['order_dir'];
        $data['manage_table'] = get_warehouse_home_manage_table($table_data, $this);
     
        $data["report_date_range_simple"]=$fechas;
        $this->load->view('warehouse/manage',$data);

    }
    public function search()
    {

        $userdata = $this->session->userdata('warehouse_search_data');
        $search = $this->input->post('search');
        $estado = $this->input->post('estado');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
       
        $offset = $this->input->post('offset') ? $this->input->post('offset') : 0;     

        if (empty($userdata['order_col'])) {
            $order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'number';
        } else {
            $order_col = 'number';
        }
        if (isset($_POST['search_flag']) and !empty($_POST['search_flag'])) {
            $order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir') : 'DESC';
            $order_col = 'number';
        } else {
            $order_dir = $userdata['order_dir'];
        }

        $search_data_ = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search, 'estado' => $estado, "start_date"=> $start_date,"end_date"=>$end_date);
        $this->session->set_userdata("warehouse_search_data", $search_data_);
        $per_page = $this->config->item('number_of_items_per_page') ? (int) $this->config->item('number_of_items_per_page') : 20;
        $search_data = $this->Orders_sales->search($search, $estado, $per_page, $offset, $order_col, $order_dir,
         $start_date,$end_date);
        $config['base_url'] = site_url('warehouse/sorting');

        $config['total_rows'] = $this->Orders_sales->search_count_all($search, $estado,$start_date , $end_date);
        $config['per_page'] = $per_page;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['manage_table'] = get_warehouse_home_manage_table_data_rows($search_data, $this);
        echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
    }
}
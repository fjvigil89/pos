<?php
require_once ("secure_area.php");
class Home extends Secure_area 
{
	function __construct()
	{
        parent::__construct();
        $this->load->model('Statistics');
        $this->load->model('reports/Summary_profit_and_loss');	
        $this->load->model('Profile');

	}
	
	function index($start_date=false,$end_date=false)
	{
        
        $profit_and_loss = $this->Summary_profit_and_loss;
        $date=date('Y');
        $start_date=$date.'-01-01';
        $end_date =$date.'-12-31';
        $this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date));
        $data['sales_monsth'] = $this->Statistics->get_sales_monsth(); 
		//check the current day
		if ($start_date == false) 
		{

			if(date('D')!='Mon')
			{    
			  	$start_date = date('Y-m-d H:i:s',strtotime('last Monday'));  			  	
			}
			else
			{
		    	$start_date = date('Y-m-d H:i:s', strtotime('Monday'));		    	
			}
		}
		else
		{
			$start_date;
		}

		//always next saturday
		if ($end_date == false) 
		{
			if(date('D')!='Sun')
			{
			    $end_date = date('Y-m-d H:i:s',strtotime('next Sunday'));
			}
			else
			{
			    $end_date = date('Y-m-d H:i:s', strtotime('Sunday'));			    
			}
		}	
		else
		{
			$end_date;
		}
 
       // ventas totales por tienda
       $data['sales_total_by_store'] = $this->Statistics->get_all_sales_by_store();
       $data['sales_total_by_store_money'] = $this->Statistics->get_sales_store_money();

        //Variable que me trae un array con los 10 productos mas vendidos
    	$data['best_sellers_items'] = $this->Statistics->get_best_seller_items();
    	//Obtener solo el producto y cantidad mas vendido
    	$data['best_seller_item_name'] = $data['best_sellers_items'][0]['name'];
    	$data['best_seller_item_quantity'] = $data['best_sellers_items'][0]['quantity_purchased'];
       
        $data['items_scarce']=$this->Statistics->get_items_scarce($this->Employee->get_logged_in_employee_current_location_id());

    	//Variable que me trae los dias en los que se hicieron ventas
    	if($start_date!=false && $end_date!=false)
    	{
    		$data['weekly_sales'] = $this->Statistics->get_weekly_sales($start_date,$end_date);
    	    //$html=$this->load->view("charts/get_weekly_sales",$data); 
    		//json_encode(array('success'=>'true','content'=>$html));
    	}
    
        //Variable que obtiene el numero de las ventas por empleado
    	$data['sales_by_employees'] = $this->Statistics->get_all_sale_payments_by_employeer();
    	
        //Profit and loss statistic
        $pnl_start_date = date("Y-m-d H:i:s",strtotime("-1 day"));
        $pnl_end_date   = date("Y-m-d H:i:s");
        
        $profile = $this->Profile->get_profiles_admin();
        $profles_array = array_chunk($profile, 6, true);

        $colors = [ 0 => "red-thunderbird",
                    1 => "purple-seance",
                    2 => "yellow-lemon",
                    3 => "purple-medium",
                    4 => "blue",
                    5 => "red-pink",
                    6 => "yellow",
                    7 => "blue-hoki",
                    8 => "red-intense",
                    9 => "green-jungle",
                    10 => "yellow-casablanca",
                    11 => "purple-wisteria",
                    12 => "blue-steel",
                    13 => "green-meadow",
                    14 => "grey-salsa",
                    15 => "red-mint",
                    16 => "yellow-soft",
                    17 => "purple-seanse",
                    18 => "blue-chambray",
                    19 => "green-dark",];

        $data['colors_modal'] = $colors;

        $data['profiles'] = $profles_array;
        $profit_and_loss->setParams(array('start_date'=>$pnl_start_date, 'end_date'=>$pnl_end_date));
        $this->Sale->create_sales_items_temp_table(array('start_date'=>$pnl_start_date, 'end_date'=>$pnl_end_date));
        $this->Receiving->create_receivings_items_temp_table(array('start_date'=>$pnl_start_date, 'end_date'=>$pnl_end_date));
		$this->Receiving->create_store_payments_temp_table(array('start_date' => $start_date, 'end_date' => $end_date));

		$this->Receiving->create_store_payments_temp_table(array('start_date' => $pnl_start_date, 'end_date' => $pnl_end_date));

        
        $data['earnings_monsth_day'] = $this->Statistics->get_sales_earnings_monsth_day(); 

        //Cantidad total por modulos
		$data['total_items']=$this->Item->count_all();
		$data['total_item_kits']=$this->Item_kit->count_all();
		$data['total_suppliers']=$this->Supplier->count_all();
		$data['total_customers']=$this->Customer->count_all();
		$data['total_employees']=$this->Employee->count_all();
		$data['total_locations']=$this->Location->count_all();
		$data['total_giftcards']=$this->Giftcard->count_all();
        $data['total_sales']=$this->Sale->count_all();
        
        $this->load->view("home",$data);
        if($this->config->item("activate_pharmacy_mode"))
		{
            $this->load->helper('alert_email');
            generate_expiration_lerta_subcategory();
		}
    }
    function get_sales_store($start_date,$end_date){
        $data = $this->Statistics->get_all_sales_by_store($start_date,$end_date);
        echo json_encode ($data);  
    }
    // ganancias anuales por meses
    function get_sales_earnings_monsth($start_date){
        $data = $this->Statistics->get_sales_earnings_monsth_day($start_date);
        echo json_encode ($data);  
    }
    // ventas anuales por meses
    function get_sales_monsth($year){
        $year=$year!=''?$year:date('Y');
        $start_date=$year.'-01-01';
        $end_date =$year!=date('Y')?($year.'-12-31'):date("Y-m-d",strtotime("1 month"));
       
        $this->Sale->create_sales_items_temp_table(array('start_date'=>$start_date, 'end_date'=>$end_date));
        $data = $this->Statistics->get_sales_monsth($start_date);
        echo json_encode ($data);  
    }
    //ventas totales por tienda en dinero
    function get_sales_store_money($start_date,$end_date){
        $data = $this->Statistics->get_sales_store_money($start_date,$end_date);
        echo json_encode ($data);  
    }	

	function logout()
	{
        try 
        {
            $this->load->library('viewer_lib');
            $this->load->model('Viewer');
            $this->viewer_lib->update_viewer_cart($this->Employee->person_id_logged_in(),array(),10,array(),0,array());
        }
        catch(Exception $e) {}

        $this->Employee->logout();
	}
    
    function subscription_renewal_redirect()
    {
        $_SESSION['extra1'] = md5('ingeniando'.$this->db->database);
        redirect('login/subscription_cancelled');
    }
	
	function set_employee_current_location_id()
	{
		$this->Employee->set_employee_current_location_id($this->input->post('employee_current_location_id'));
		
		//Clear out logged in register when we switch locations
		$this->Employee->set_employee_current_register_id(false);
	}
	
	function keep_alive()
	{
		//Set keep alive session to prevent logging out
		$this->session->set_userdata("keep_alive",time());
		echo $this->session->userdata('keep_alive');
    }

	function view_item_modal($item_id)
	{

        $this->load->library('sale_lib');
		$data['item_info']=$this->Item->get_info($item_id);
		$data['item_location_info']=$this->Item_location->get_info($item_id);
		$data['item_tax_info']=$this->Item_taxes_finder->get_info($item_id);
		$data['reorder_level'] = ($data['item_location_info'] && $data['item_location_info']->reorder_level) ? $data['item_location_info']->reorder_level : $data['item_info']->reorder_level;
       
        $tiers=array();
        foreach ($this->Tier->get_all()->result() as $tier) {
           $tiers[]=array(
                "name"=> $tier->name,
                "precio"=>to_currency($this->sale_lib->get_price_by_tier_and_item($item_id,$tier->id), 2)
            );
        }
       
        $data["tiers"]= $tiers;
		//Obtener precio con impuesto
		$sum_tax=0;
		$tax_item_info = $data['item_tax_info'];
		
        foreach($tax_item_info as $key=>$tax)
		{
			$sum_tax+=$tax['percent']/100;
		}
       
		//$sum_tax=array_sum($prev_tax[$data['item_info']->item_id]);									
		$value_tax=$data['item_info']->unit_price*$sum_tax;	
		$data['item_price_with_tax'] = $value_tax + $data['item_info']->unit_price;		
		//
		
		$suppliers = array('' => lang('items_none'));
		foreach($this->Supplier->get_all()->result_array() as $row)
		{
			$suppliers[$row['person_id']] = $row['company_name'] .' ('.$row['first_name'] .' '. $row['last_name'].')';
		}

		if ($supplier_id = $this->Item->get_info($item_id)->supplier_id)
		{
			$supplier = $this->Supplier->get_info($supplier_id);
			$data['supplier'] = $supplier->company_name . ' ('.$supplier->first_name.' '.$supplier->last_name.')';
		} 
		
        $data['suspended_receivings'] = $this->Receiving->get_suspended_receivings_for_item($item_id);
        
        if ($this->config->item('subcategory_of_items') &&  $this->Item->get_info($item_id)->subcategory){
            $data['subcategory_info']= $this->items_subcategory->get_all_by_id(false, $item_id);
        }
		$this->load->view("items/items_modal",$data);
	}
	
	// Function to show the modal window when clicked on kit name
	function view_item_kit_modal($item_kit_id)
	{
		// Fetching Kit information using kit_id
		$data['item_kit_info']=$this->Item_kit->get_info($item_kit_id);
		
		$this->load->view("item_kits/items_modal",$data);
	}

    	// Function que muestra los impuesto y la moneda por defecto
	function view_language_currency_symbol_modal()
	{
		$language=$this->input->post('language');

        if(!empty($language))
        {
            $batch_save_data['language'] = $language;
        
            switch ($language) 
            {
                case 'spanish_argentina':
                    $batch_save_data['currency_symbol'] = '$';			
                    $batch_save_data['default_tax_1_rate'] = 21;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;
                case 'spanish_bolivia':
                    $batch_save_data['currency_symbol'] = 'Bs.';			
                    $batch_save_data['default_tax_1_rate'] = 13;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;
                case 'portugues_brasil':
                    $batch_save_data['currency_symbol'] = 'R$';			
                    $batch_save_data['default_tax_1_rate'] = 5;
                    $batch_save_data['default_tax_1_name'] = 'ISS';
                    break;			    
                case 'spanish_chile':
                    $batch_save_data['currency_symbol'] = '$';			
                    $batch_save_data['default_tax_1_rate'] = 19;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;	
                case 'spanish':
                    $batch_save_data['currency_symbol'] = '$';			
                    $batch_save_data['default_tax_1_rate'] = 16;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;	
                case 'spanish_costarica':
                    $batch_save_data['currency_symbol'] = '₡';			
                    $batch_save_data['default_tax_1_rate'] = 13;
                    $batch_save_data['default_tax_1_name'] = 'IV';
                    break;
                case 'spanish_ecuador':
                    $batch_save_data['currency_symbol'] = '$';			
                    $batch_save_data['default_tax_1_rate'] = 12;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;	
                case 'spanish_elsalvador':
                    $batch_save_data['currency_symbol'] = '$';			
                    $batch_save_data['default_tax_1_rate'] = 13;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;
                case 'spanish_spain':
                    $batch_save_data['currency_symbol'] = '€';			
                    $batch_save_data['default_tax_1_rate'] = 21;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;	
                case 'english':
                    $batch_save_data['currency_symbol'] = '$';			
                    $batch_save_data['default_tax_1_rate'] = '';
                    $batch_save_data['default_tax_1_name'] = '';
                    break;	
                case 'spanish_guatemala':
                    $batch_save_data['currency_symbol'] = 'Q';			
                    $batch_save_data['default_tax_1_rate'] = 12;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;	
                case 'spanish_honduras':
                    $batch_save_data['currency_symbol'] = 'L';			
                    $batch_save_data['default_tax_1_rate'] = 15;
                    $batch_save_data['default_tax_1_name'] = 'ISV';
                    break;
                case 'spanish_mexico':
                    $batch_save_data['currency_symbol'] = '$';			
                    $batch_save_data['default_tax_1_rate'] = 16;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;
                case 'spanish_nicaragua':
                    $batch_save_data['currency_symbol'] = 'C$';			
                    $batch_save_data['default_tax_1_rate'] = 15;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;
                case 'spanish_panama':
                    $batch_save_data['currency_symbol'] = '$';			
                    $batch_save_data['default_tax_1_rate'] = 7;
                    $batch_save_data['default_tax_1_name'] = 'ITBMS';
                    break;
                case 'spanish_paraguay':
                    $batch_save_data['currency_symbol'] = '₲';			
                    $batch_save_data['default_tax_1_rate'] = 10;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;
                case 'spanish_peru':
                    $batch_save_data['currency_symbol'] = 'S/.';			
                    $batch_save_data['default_tax_1_rate'] = 18;
                    $batch_save_data['default_tax_1_name'] = 'IGV';
                    break;
                case 'spanish_puertorico':
                    $batch_save_data['currency_symbol'] = '$';			
                    $batch_save_data['default_tax_1_rate'] = 7;
                    $batch_save_data['default_tax_1_name'] = 'IVU';
                    break;
                case 'spanish_republicadominicana':
                    $batch_save_data['currency_symbol'] = '$';			
                    $batch_save_data['default_tax_1_rate'] = 18;
                    $batch_save_data['default_tax_1_name'] = 'ITBIS';
                    break;
                case 'spanish_uruguay':
                    $batch_save_data['currency_symbol'] = '$';			
                    $batch_save_data['default_tax_1_rate'] = 22;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;
                case 'spanish_venezuela':
                    $batch_save_data['currency_symbol'] = 'Bs.F.';			
                    $batch_save_data['default_tax_1_rate'] = 12;
                    $batch_save_data['default_tax_1_name'] = 'IVA';
                    break;
            }

              
        
        }



         echo json_encode ($batch_save_data) ;   

}

function invertir_modal() {
    $this->load->model('Employee');
    $hide_modal = $this->input->get('modal');
    if($hide_modal == 0) {
        $aux_hide_modal =1;
    } else {
        $aux_hide_modal =0;
    }
    $data = array('hide_modal'=>$aux_hide_modal);
    $batch_save_data = $this->Employee->initial_config($data);
    echo $batch_save_data;  
}

function initial_config(){

	$data = array(
	'language'=>$this->input->post('language'),
	'currency_symbol'=>$this->input->post('moneda'),
	'default_tax_1_name'=>$this->input->post('nombre_impuesto'),
	'default_tax_1_rate'=>$this->input->post('impuesto'),
    'initial_config'=>$this->input->post('initial_config'),
    'profile_store'=>$this->input->post('perfil'),
    );

    $batch_save_data = $this->Employee->initial_config($data);

    echo json_encode ($batch_save_data);  
}


function cargar_perfil() {

    $this->load->model('Profile');
    $perfil = $this->input->post('perfil');
    $exito = $this->Profile->load_profile($perfil);
    $this->Appconfig->batch_save(array('Hide_panel_type_business'=>1));
    print_r($exito);

}

}
?>
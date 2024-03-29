<?php  if (!defined('BASEPATH')) 
exit('No direct script access allowed');
require_once ("secure_area.php");
class All extends  Secure_area
{
    function __construct() {
        parent::__construct();

        $this->load->library('viewer_lib');
        $this->load->model('Viewer');
        $this->load->model('Viewer_file');
    }
    function hide_modal_scale()
    {       
		$this->load->model('Viewer');	
		$this->Viewer->update_viewer($this->Employee->get_logged_in_employee_info()->person_id,
		array(				
				"is_cart" => 1,
				"updated" => date('Y-m-d H:i:s'),				
				"is_scale" => false,
				"data_scale" => json_encode([])
			)
		);
    }
    function get_item()
    {

        $item_code = $this->input->post("item");
        $item_kit_id =  $this->Item_kit->get_item_kit_id($item_code);
        $no_found = false;
        if($item_kit_id !== false)
        {
            $item_info = $this->Item_kit->get_info($item_kit_id);
            $no_found = !is_numeric($item_info->item_kit_id) ? true : false ;
            $kit = true;
        }
        else
        {  
            $item_id = $this->Item->get_item_id($item_code);
            $item_info = $this->Item->get_info($item_id);            
            $no_found = !is_numeric($item_info->item_id) ? true : false;
            $kit = false;
        }    

      
        $this->load->library('sale_lib');

        $data = array(
            "name"=>  character_limiter(H($item_info->name), 22),
            "price"=> to_currency($this->viewer_lib->get_price_item_expe($item_info)),
            "image_id" =>isset($item_info->image_id)?$item_info->image_id:null,
            "no_found" => $no_found,
            "kit" => $kit
        );
        echo json_encode( $data);
    }

    function set_item_viewer_scale()
    {
        $this->load->library('sale_lib');
        $data = $this->input->post("data");
        $this->viewer_lib->update_viewer_cart($this->Employee->person_id_logged_in(),
					$this->sale_lib->get_cart(),1,$this->sale_lib->get_payments(),
                    $this->sale_lib->get_overwrite_tax(),$this->sale_lib->get_new_tax(),
                    true, $data
                );
    }

    function checker()
    {
        $location_id = $this->Employee->get_logged_in_employee_current_location_id();
        $data_list = $this->Viewer_file->get_list_by_location($location_id)->result();
        $path_long =  PATH_RECUSE."/".$this->Employee->get_store()."/img";
        $data = array(
            
            "data_list" => $data_list,
            "path_long" => $path_long,
            "show_carrousel" => (int) $this->config->item("show_carrousel"),
            
        );       

        $this->load->view("viewer/checker",$data);
    }

    function get_rate($employee_id)
    {
        $employee_info = $this->Employee->get_info($employee_id);			
        $rate = $this->Employee->get_rate($employee_info->id_rate);
            
        if($employee_info->id_rate == 2)        
            $rate = $rate->sale_rate + ( $rate->sale_rate * ($this->config->item("ganancia_distribuidor") / 100));
        else
             $rate = $rate->sale_rate;

        echo json_encode((double)$rate);
    }
    function viewer($id)
    {
        $location_id = $this->Employee->get_logged_in_employee_current_location_id();
        $data_list = $this->Viewer_file->get_list_by_location($location_id)->result();
        $path_long =  PATH_RECUSE."/".$this->Employee->get_store()."/img";
        $rate = 0;

        if($this->config->item('activar_casa_cambio'))
        {
            $employee_info = $this->Employee->get_info($id);			
            $rate = $this->Employee->get_rate($employee_info->id_rate);
            
            if($employee_info->id_rate == 2)        
                $rate = $rate->sale_rate + ( $rate->sale_rate * ($this->config->item("ganancia_distribuidor") / 100));
            else
             $rate = $rate->sale_rate;
        }

        $data = array(
            "id"=>$id,
            "data_list" => $data_list,
            "path_long" => $path_long,
            "show_carrousel" => (int) $this->config->item("show_carrousel"),
            "show_viewer" => (int) $this->config->item("show_viewer"),
            "rate" =>(double) $rate
        );       

        $this->load->view("viewer/viewer", $data );  
    }
    function get_data($employee_id, $date = "0000-00-00 00:00:00")
    {
        $now = date('Y-m-d H:i:s');
        $date = rawurldecode($date);
        $_data = $this->Viewer->get_viewer_by_employee($employee_id);
       
        $data = array("is_updated" => true);
        $data["show_viewer"] = $this->config->item("show_viewer");
        $data["show_carrousel"] = $this->config->item("show_carrousel");

        if(is_array($_data)  and !empty($_data) )
        {            
            $cart = $this->viewer_lib->create_cart($_data);
            $arrayTotal = $this->viewer_lib->get_total_tax_and_subtotal($cart);           
            
            if($_data["updated"] != $date )
            {
                foreach ($cart as $key => $item) 
                {
                    $cart[$key]["price"] = to_currency($item["price"]);
                    $cart[$key]["total_tax"] = to_currency($item["total_tax"]);
                }

                $payment_total = $_data["is_cart"] == 2 ?  $this->viewer_lib->get_payments_totals(json_decode($_data["payments"],true)) : 0;

                $data["is_cart"] = $_data["is_cart"];
                $data["cart_data"] = $cart;
                $data["total"] = to_currency($arrayTotal["total"]);
                $data["total"] = to_currency($arrayTotal["total"]);
                $data["is_scale"] = $_data["is_scale"];
                $data["data_scale"] = json_decode($_data["data_scale"]) ;

                //$data["subtotal"] = to_currency($arrayTotal["subtotal"]);
                $data["updated"] = $_data["updated"];
               
            } 
            else
            {
                $data["is_updated"] = false;
                $data["updated"] = $_data["updated"];
                $new_date = date('Y-m-d H:i:s',strtotime ( '+6 second' , strtotime ( $_data["updated"]) ));

                if($_data["is_cart"] == 3 and  $new_date <= $now )
                {
                    $this->Viewer->update_viewer($employee_id,
                                        array(
                                                "cart_data" => "{}",
                                                "is_cart" => 1,
                                                "updated" => date('Y-m-d H:i:s'),
                                                "overwrite_tax" => 0,
                                                "new_tax" => "{}",
                                                "payments" => "{}",
                                                "is_scale" => 0,
                                                "data_scale" => "{}"
                                            ),3
                            );   
                    
                }
            }         
        }                
        else
            $data = array("is_cart" => 404,"updated" => $now );
        
        echo json_encode( $data);
    }
}
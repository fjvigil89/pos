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
            "show_carrousel" => $this->config->item("show_carrousel"),
            "show_viewer" => $this->config->item("show_viewer"),
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
                foreach ($cart as $key => $item) {
                    $cart[$key]["price"] = to_currency($item["price"]);
                    $cart[$key]["total_tax"] = to_currency($item["total_tax"]);
                }

                $payment_total = $_data["is_cart"] == 2 ?  $this->viewer_lib->get_payments_totals(json_decode($_data["payments"],true)) : 0;

                $data["is_cart"] = $_data["is_cart"];
                $data["cart_data"] = $cart;
                $data["total"] = to_currency($arrayTotal["total"]);
                $data["change"] =  to_currency(($arrayTotal["total"]-$payment_total)*-1) ;
                //$data["subtotal"] = to_currency($arrayTotal["subtotal"]);
                $data["updated"] = $_data["updated"];
               
            } 
            else
            {
                $data["is_updated"] = false;
                $data["updated"] = $_data["updated"];
            }         
        }                
        else
            $data = array("is_cart" => 404,"updated" => $now );
        
        echo json_encode( $data);
    }
}
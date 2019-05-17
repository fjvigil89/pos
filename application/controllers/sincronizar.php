<?php
require_once "secure_area.php";
class Sincronizar extends Secure_area
{

    public function __construct() 
    {
        parent::__construct();
        
        $this->load->model('Synchronization_offline');
        $this->load->model('Cajas_empleados');
        $this->load->model('Register_movement');

    }
    function get_data_cache(){
        $this->load->view("offline_data/cargar_archivos");
    }
    
    public function backup_items($date="0000-00-00 00:00:00")
    {
       $date= rawurldecode($date);
        $items = $this->Item->get_all_for_indexedDb(100000,0,$date)->result_array();
        echo json_encode($items);
    }
    public function backup_location_items($date="0000-00-00 00:00:00")
    {
        $date= rawurldecode($date);
        $items = $this->Item_location->get_all_for_indexeDb(100000,0,$date)->result_array();
        echo json_encode($items);
    }
    // decarga la congiguracion del sistema, las ubicacion de la tienda y permisos y ubicacion de empleados, y categorias de producto-kit
    public function backup_appconfig_location()
    {
        $this->load->model('appconfig');
        $this->load->model('location');
        $this->load->model('Module_action');
        $employees_locations = $this->Employee->get_all_location_employees()->result_array();
        $appconfig = $this->appconfig->get_all()->result_array();
        $arrayDataConfig = array("id_fila" => 1);
        foreach ($appconfig as $item) {
            $arrayDataConfig[$item["key"]] = $item["value"];
        }
        $locations = $this->location->get_all()->result_array();
        // $modules_actions= $this->Module_action->get_all()->result_array();
        $permissions_actions = $this->Module_action->get_permissions_actions_all()->result_array();
        $permissions = $this->Module->get_all_permissions()->result_array();
        $data["categories"] = $this->categories_all_array();
        $data["categories"]["id_fila"] = 1;
        $data["appconfig"] = $arrayDataConfig;
        $data["locations"] = $locations;
        $data["employees_locations"] = $employees_locations;
        //$data["modules_actions"]= $modules_actions;
        $data["permissions_actions"] = $permissions_actions;
        $data["permissions"] = $permissions;
        // estos son los lenguages
        $data["language"] = get_array_leng()->language;
        $data["language"]["id_fila"] = 1;
        

        echo json_encode($data);
    }
    public function get_register(){
        $data = array();
        $data["register"]=$this->Register->get_all_indexdb()->result_array();
        $data["cajas_empleados"]=$this->Cajas_empleados->get_cajas()->result_array();
        $aux= $this->Sale->get_registers_log_open()->result_array();
        for($i=0; $i< count($aux) ;$i++){
            //$aux[$i]["register_log_id"]= (int) $aux[$i]["register_log_id"];          
            $aux[$i]["register_log_id_origen"]= (int) $aux[$i]["register_log_id"];
            $aux[$i]["created_online"]= (int) $aux[$i]["created_online"];
            unset($aux[$i]["register_log_id"]);
            
           
        }
        $data["register_log"]=$aux;
        echo json_encode($data);
        
    }
    public function datos_sesion()
    {
        $ip = $this->input->ip_address();
        $tem = $this->Synchronization_offline->save($ip); 
        $data = array();
        if ($tem != null) {
            $data[] = array("key" => "person_id", "value" => $tem["person_id"]);
            $data[] = array("key" => "ubicacion_id", "value" => $this->Employee->get_logged_in_employee_current_location_id());
            $data[] = array("key" => "store", "value" => $this->Employee->get_store());
            $data[] = array("key" => "last_invoice", "value" => $tem["last_invoice"]); // # ultima factura
            $data[] = array("key" => "date_synchronization", "value" => $tem["date_mysql"]);
            $data[] = array("key" => "token", "value" => $tem["token"]);
            $data[] = array("key" => "id_synchronization", "value" => $tem["id"]);

            //$data[]=array("key"=>"last_date_synchronization","value"=>$now);// se cosulta de la table synchronization
            echo json_encode($data);
        } else {
          echo  json_encode("error"); // se envia este data para generar una excepción en javascript
        }

    }
    private function synchronize_register($register_log){  
        $respustas=array();
        foreach($register_log as $register){
            $respueta=array("error"=>false,"mensaje"=>"Sincronización exitosa.","register_log_id"=>$register["register_log_id"]);
            if($register["created_online"] == 1){
                // verificamos si la caja se cerró offline
                if($register["shift_end"] != "0000-00-00 00:00:00"){
                   $aux_register= $this->Sale->get_registers_log_by_id($register["register_log_id_origen"]);
                   if($aux_register->shift_end=="0000-00-00 00:00:00"){
                       $data_=array(
                           "shift_end"=>$register["shift_end"],
                           "close_amount"=>$register["close_amount"],
                           "employee_id_close"=>$register["employee_id_close"],
                           "closed_manual"=>$register["closed_manual"],
                        );
                        if(!$this->Sale->update_register_log_by_id($data_,$register["register_log_id_origen"])){
                            $respueta["error"]=true;
                            $respueta["mensaje"]="Error, no se puedo cerrar la caja abierta online";
                        }
                   }
                   else{
                       $data_ =array("descripcion"=>
                    "Cerrado offline * empleado ID: ".$register["employee_id_close"]." * Fecha: ".$register["shift_end"]);
                    $this->Sale->update_register_log_by_id($data_,$register["register_log_id_origen"]);
                   }
                }
            }else{
                $aux_register=$this->Sale->get_registers_log_by_id_synchronized(
                    $register["id_synchronization"],$register["register_log_id"]);
                if(!$aux_register){
                    $data_=array(
                        "employee_id_open"=>$register["employee_id_open"],
                        "employee_id_close"=>$register["employee_id_close"]==""?null:$register["employee_id_close"],
                        "register_id"=>$register["register_id"],
                        "shift_start"=>$register["shift_start"],
                        "shift_end"=>$register["shift_end"],
                        "open_amount"=>$register["open_amount"],
                        "close_amount"=>$register["close_amount"],  
                        "deleted"=>0,
                        "created_online"=>$register["created_online"],
                        "closed_manual"=>$register["closed_manual"],
                        "synchronizations_offline_id"=>$register["id_synchronization"],
                        "register_log_id_offline"=>$register["register_log_id"],
                        "descripcion"=>"Esta caja se creó  en modo offline."
                    ); 
                    if($register["shift_end"]=="0000-00-00 00:00:00" and $this->Sale->is_register_log_open($register["register_id"])){
                        $data_["shift_end"]=date('Y-m-d H:i:s');
                        $data_["descripcion"]="Caja cerrada automáticamente porque ya se encontraba abierta.";
                        $data_["closed_manual"]=0;
                    }   
                    if(!$this->Sale-> insert_register($data_)){
                        $respueta["error"]=true;
                        $respueta["mensaje"]="Error, no se puedo abrir la caja en el servidor";                    
                    }
                }else{
                    if( $aux_register->closed_manual==0){

                        $data_=array(); 
                        $actulizado=false;
                        if($register["shift_end"]!="0000-00-00 00:00:00"){
                            $data_["shift_end"]=$register["shift_end"];
                            $data_["closed_manual"]=$register["closed_manual"];
                            $data_["shift_end"]=$register["shift_start"];
                            $data_["employee_id_close"]=$register["employee_id_close"];
                            $actulizado=true;
                            if(!$this->Sale->update_register_log_by_id($data_,$register["register_log_id"])){
                                $respueta["error"]=true;
                                $respueta["mensaje"]="Error, no se puedo cerrar la caja";
                            }
                        } if($aux_register->shift_end!="0000-00-00 00:00:00" and !$actulizado){
                            $data_["shift_end"]=date('Y-m-d H:i:s')  ;
                            $this->Sale->update_register_log_by_id($data_,$register["register_log_id"]);
                        }
                    }
                }
            }
            $respustas[]=$respueta;
        }
        return $respustas;
    }      
    function synchronize_customers()
    {
        $data = $this->input->post('data');
        $data =  json_decode(stripslashes($data),true);
        $result = $this->Customer->save_multiple_offline($data);

        if(!$result)        
            echo json_encode(array("succes"=>false));
        else
            echo json_encode(array("succes"=>true,"data"=>$result));
    }
    public function synchronize_sales(){        
        $data =$this->input->post('data');
        $data =  json_decode(stripslashes($data),true);        
        if($this->Appconfig->is_offline_sales() and is_array($data) and !empty($data)){
           		
            $sales= isset($data["sales"])?$data["sales"]:array();
            $register_log=  isset($data["register"])? $data["register"]: array();
            // agregamos los reg istres           
            $respustas["register"]= $this->synchronize_register($register_log);
            $respustas["sales"]= $this->Sale->save_offline($sales) ;
            echo json_encode($respustas);
        }
    }
    // descargarmos los kits y subcategorias de productos y numeros
    public function backup_kits_and_mumber_serial($date="0000-00-00 00:00:00")
    {
        $date= rawurldecode($date);
        $item_kits = $this->Item_kit->get_all_indexddb($date)->result_array();
        $item_kits_item = $this->Item_kit_items->get_all($date)->result_array();
        $location_item_kits = $this->Item_kit_location->get_all($date)->result_array();
        $items_subcategory = $this->items_subcategory->get_all_indexed($date)->result_array();
        $data["item_kits"] = $item_kits;
        $data["item_kits_item"] = $item_kits_item;
        $data["location_item_kits"] = $location_item_kits;
        $data["items_subcategory"] = $items_subcategory;
        $data["additional_item_numbers"]=  $this->Additional_item_numbers->get_all($date)->result_array();
        $data["additional_item_seriales"]=  $this->Additional_item_seriales->get_all()->result_array();
        echo json_encode($data);
    }
  /*  public function backup_additional_item_numbers()
    {
        $this->load->model('Additional_item_numbers');
        $Additional_item_numbers = $this->Additional_item_numbers->get_all()->result_array();
        echo json_encode($Additional_item_numbers);
    }*/

    public function backup_taxes_and_tier($date="0000-00-00 00:00:00")
    {
        $date= rawurldecode($date);
        $item_kit_taxes = $this->Item_kit_taxes->get_all()->result_array();
        $item_taxes = $this->Item_taxes->get_all_offline($date)->result_array();

        $item_kit_location_taxes = $this->Item_kit_location_taxes->get_all()->result_array();
        $item_location_taxes = $this->Item_location_taxes->get_all_offline($date)->result_array();
        $items_tier_prices = $this->Item->get_tier_price_all($date)->result_array();
        $location_items_tier_prices = $this->Item_location->get_tier_price_all()->result_array();

        $location_item_kits_tier_prices = $this->Item_kit_location->get_tier_prices_all()->result_array();
        $item_kits_tier_prices = $this->Item_kit->get_tier_price_row_all()->result_array();
        $price_tiers = $this->Tier->get_all()->result_array();

        $data["item_kit_taxes"] = $item_kit_taxes;
        $data["item_taxes"] = $item_taxes;
        $data["item_kit_location_taxes"] = $item_kit_location_taxes;
        $data["item_location_taxes"] = $item_location_taxes;
        $data["items_tier_prices"] = $items_tier_prices;
        $data["location_items_tier_prices"] = $location_items_tier_prices;
        $data["location_item_kits_tier_prices"] = $location_item_kits_tier_prices;
        $data["item_kits_tier_prices"] = $item_kits_tier_prices;
        $data["price_tiers"] = $price_tiers;

        echo json_encode($data);
    }
    /**
     * categorias para el panel del cart
     */
    public function categories_all_array()
    {
        $categories = array();

        $item_categories = array();
        $item_categories_items_result = $this->Item->get_all_categories()->result();

        foreach ($item_categories_items_result as $category) {
            if ($category->category != lang('sales_giftcard') && $category->category != lang('sales_store_account_payment')) {
                $item_categories[] = $category->category;
            }
        }
        $item_kit_categories = array();
        $item_kit_categories_items_result = $this->Item_kit->get_all_categories()->result();
        foreach ($item_kit_categories_items_result as $category) {
            $item_kit_categories[] = $category->category;
        }
        $categories = array_unique(array_merge($item_categories, $item_kit_categories));

        return $categories;
    }
    public function backup_giftcard_and_points($date="0000-00-00 00:00:00")
    {
        $date= rawurldecode($date);
        $giftcards = $this->Giftcard->get_all_by_indexd($date)->result_array();        
        $points = $this->Customer->get_all_points($date)->result_array();
        $data["points"] = $points;
        $data["giftcards"] = $giftcards;
        echo json_encode($data);
    }
    
    public function backup_employee_and_customers($date="0000-00-00 00:00:00")
    {
        $date= rawurldecode($date);
        $employee = $this->Employee->get_all_by_indexd($date)->result_array();
        $customer = $this->Customer->get_all_by_indexd($date)->result_array();
        $data["employee"] = $employee;
        $data["customer"] = $customer;
        echo json_encode($data);
    }

}

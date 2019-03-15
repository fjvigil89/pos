<?php
class technical_support extends CI_Model
{
	/////////////////DANIEL////////////////////////////////
	function lista_servicios($status)
	{
		$current_location = $this->Employee->get_logged_in_employee_current_location_id();
                if($status==""){
                    $query = $this->db->query("SELECT su.Id_support, p.first_name, p.last_name, su.model, su.type_team, su.state, su.ubi_equipo,su.date_register FROM phppos_technical_supports su 
                    INNER JOIN phppos_customers c ON su.id_customer=c.person_id 
                    INNER JOIN phppos_people p ON c.person_id=p.person_id 
                    WHERE su.state<>'ENTREGADO' AND su.state<>'RETIRADO' and id_location='$current_location'");
                }
                if($status!=""){
                    $query = $this->db->query("SELECT su.Id_support, p.first_name, p.last_name, su.model, su.type_team, su.state, su.ubi_equipo,su.date_register FROM phppos_technical_supports su 
                    INNER JOIN phppos_customers c ON su.id_customer=c.person_id 
                    INNER JOIN phppos_people p ON c.person_id=p.person_id 
                    WHERE su.state<>'ENTREGADO' AND su.state<>'RETIRADO' and su.state=$status and id_location='$current_location'");
                }
		return $query->result();
	}
        function lista_servicios_total_entregado($status)
	{
		$current_location = $this->Employee->get_logged_in_employee_current_location_id(); 
                    $query = $this->db->query("SELECT su.Id_support, p.first_name, p.last_name, su.model, su.type_team, su.state,su.date_register FROM phppos_technical_supports su 
                    INNER JOIN phppos_customers c ON su.id_customer=c.person_id 
                    INNER JOIN phppos_people p ON c.person_id=p.person_id 
                    WHERE (su.state='$status' OR su.state='RETIRADO') and id_location='$current_location'");
                
		return $query->num_rows();
	}

    public function getClienteEquipo($id_support)
    {
//        INNER JOIN phppos_technical_supports_tservicios serv ON ts.type_team = serv.tservicios
//        INNER JOIN phppos_technical_supports_tfallas falla ON ts.damage_failure = falla.tfallas
        $resultado = $this->db->query("SELECT *, ts.state estados FROM phppos_technical_supports ts
            INNER JOIN phppos_customers c ON ts.id_customer = c.person_id
            INNER JOIN phppos_people p ON c.person_id = p.person_id            
            WHERE Id_support = $id_support");
        
        if($resultado->num_rows() == 1) {
            return $resultado->row();
        } else {
            return false;
        }

    }
     function actualizarServicioCliente($id_support, $data)
    {
       return $this->db->update("phppos_technical_supports", $data, "Id_support = $id_support");
    }   
	/////////////////DANIEL////////////////////////////////

    function search($search, $state = false, $limit=50,$offset=0,$column='order_support',$orderby='asc')
	{
		
		$current_location=$this->Employee->get_logged_in_employee_current_location_id();
		
			$search_terms_array=explode(" ", $this->db->escape_like_str($search));
	
			
			// se arma la condicion ----------------------------------------
			    $cadena="(order_support LIKE '%".$this->db->escape_like_str($search)."%' or ";
			
		    	$cadena=$cadena."model LIKE '%".$this->db->escape_like_str($search)."%' or ";			
				$cadena=$cadena."custom1_support_name LIKE '%".$this->db->escape_like_str($search)."%' or ";			
                $cadena=$cadena."custom2_support_name LIKE '".$this->db->escape_like_str($search)."%' or ";
                $cadena=$cadena."color LIKE '".$this->db->escape_like_str($search)."%' or ";
                $cadena=$cadena.$this->db->dbprefix('technical_supports').".state LIKE '".$this->db->escape_like_str($search)."%' or ";
                $cadena=$cadena.$this->db->dbprefix('customers').".account_number LIKE '".$this->db->escape_like_str($search)."%' or ";
                $cadena=$cadena.$this->db->dbprefix('people').".first_name LIKE '".$this->db->escape_like_str($search)."%' or ";
                $cadena=$cadena.$this->db->dbprefix('people').".last_name LIKE '".$this->db->escape_like_str($search)."%' or ";	
                $cadena=$cadena."CONCAT(".$this->db->dbprefix('people').".first_name,' ',". $this->db->dbprefix('people').".last_name) LIKE '".$this->db->escape_like_str($search)."%' ";			
		    	$cadena=$cadena.") and ".$this->db->dbprefix('technical_supports').".deleted=0";

			//----------------------------------------------------------------
            $this->db->select('technical_supports.*,
			people.first_name as first_name, 
			people.last_name as last_name,
			customers.account_number as account_number');
			$this->db->from('technical_supports');
            $this->db->join('customers', 'customers.person_id = technical_supports.id_customer');
            $this->db->join('people', 'people.person_id = customers.person_id');
			
			
			
            $this->db->where($cadena);
			$this->db->where("id_location",$current_location);
			
			if ($state)
			{
				$this->db->where($this->db->dbprefix('technical_supports').'.state', $state);
			}
				
			$this->db->order_by($column, $orderby);
			$this->db->limit($limit);
		    $this->db->offset($offset);
			$query=$this->db->get();
		    return  $query;
	}


	public function get_info_equipo($id_equipo)
	{
		$query = $this->db->query("SELECT su.Id_support, p.first_name, p.last_name, p.image_id, p.country, p.email, p.phone_number, su.model, su.type_team, su.state, su.marca, su.color, su.repair_cost FROM phppos_technical_supports su INNER JOIN phppos_customers c ON su.id_customer=c.person_id INNER JOIN
phppos_people p ON c.person_id=p.person_id WHERE Id_support='$id_equipo'");
		return $query;
	}
        public function get_info_abono_orden($id_equipo)
	{
		$queryAbono = $this->db->query("SELECT payment FROM phppos_support_payments WHERE id_support='$id_equipo'"); 
		return $queryAbono; 
	}

    function save(&$data_support,$id_support=-1, $payments_data)
    {
        if (!$this->Customer->exists($data_support["id_customer"]))
		{
            echo("cliente no existe");
            return -1;
        }
        $this->db->query("SET autocommit=0");
        $this->db->query('LOCK TABLES ' . $this->db->dbprefix('technical_supports') . ' WRITE '. $this->db->dbprefix('technical_supports') .
         ' READ '.$this->db->dbprefix('support_payments') . ' WRITE ');        
       // si es nueva se agrega si es nueva
        if($id_support==-1){
           $oreder= $this->get_last_order(false);
            $data_support["order_support"]=$oreder;
            if (!$this->db->insert('technical_supports', $data_support)) {             
                $this->db->query("ROLLBACK");
                $this->db->query('UNLOCK TABLES');
                return -1;            
            }
            $id_support = $this->db->insert_id();
           
            
        }  
        else{
            if($id_support!=-1){
                // se consulta el soporte antes de eliminar y se toman los datos que no debeb cambiar
                $info_support=$this->technical_support->get_info_by_id($id_support,false); 
                if( $info_support->Id_support=="") {
                    return -1; 
                }  
                            
               // $data_support["date_register"]= $info_support->date_register;
                $data_support["order_support"]=$info_support->order_support;
                if(!$this->delete_by_id($id_support)){
                    $this->db->query("ROLLBACK");
                    $this->db->query('UNLOCK TABLES');
                    return -1;
                }
                 $info_support=$this->technical_support->get_info_by_id($id_support,false); 
                $this->db->where('Id_support', $id_support);
                if(!$this->db->update('technical_supports',  $data_support)){
                    $this->db->query("ROLLBACK");
                    $this->db->query('UNLOCK TABLES');
                    return -1;
                }

            }
        }
         /// se agregan los abonos
        foreach($payments_data as $payments){
            $payments["id_support"]=$id_support;
            if(!$this->support_payment->save($payments, $id_support))
            {
                $this->db->query("ROLLBACK");
                $this->db->query('UNLOCK TABLES');
                return -1; 
            }
        }

        $this->db->query("COMMIT");
        $this->db->query('UNLOCK TABLES');
        return $id_support;
    }   

    function get_all($limit=10000, $offset=0,$col='Id_support',$order='', $state="RECIBIDO")
	{
        $current_location=$this->Employee->get_logged_in_employee_current_location_id();	
        
        
        $this->db->select('technical_supports.*,
        people.first_name as first_name, 
        people.last_name as last_name,
        customers.account_number as account_number');
        $this->db->from('technical_supports');
        $this->db->join('customers', 'customers.person_id = technical_supports.id_customer');
        $this->db->join('people', 'people.person_id = customers.person_id');

        
        $this->db->where($this->db->dbprefix('technical_supports').'.deleted',0);
       // $this->db->where($this->db->dbprefix('technical_supports').'.state',$state);
        $this->db->where('id_location',$current_location);        
		$this->db->order_by($col, $order);
		$this->db->limit($limit);
        $this->db->offset($offset);
        $query=$this->db->get();
		return  $query;
    }
    function get_last_order($id_location=false)
	{
        if(!$id_location){
            $id_location=$this->Employee->get_logged_in_employee_current_location_id();		
        }
        $this->db->select("MAX(order_support) as order_support");
		$this->db->from('technical_supports');	      
        $this->db->where('id_location',$id_location);
        $query=$this->db->get();
        if($query->num_rows() ==1) {
            $row= $query->row();
            if($row->order_support==0){
                return  $this->config->item("order_star")==false? 1 : ($this->config->item("order_star")+1);
            }
            return $row->order_support+1;
        }
        return $this->config->item("order_star")==false? 1 :($this->config->item("order_star")+1);
	
    }

    function det_spare_part_all_by_id_order($id_support)
	{       
       
		$this->db->from('spare_parts');	      
        $this->db->where('id_support',$id_support);
        $query=$this->db->get();
        return  $query;
    }
    function det_spare_part_all_by_id_order_respuesto($id_support)
	{       
            $query = $this->db->query("SELECT it.name,it.item_number, resp.repuesto_total, resp.respuesto_cantidad FROM phppos_technical_support_repuestos_persona resp, phppos_items it WHERE resp.repuesto_support='$id_support' AND it.item_id=resp.repuesto_item");   
            return  $query;
    }
    function delete_by_id($id_support)
	{
      	
     if(! $this->support_payment->delete_by_id_support($id_support) ){
         return false;
     }
    $this->db->where('Id_support', $id_support);
    return $this->db->update('technical_supports', array('deleted' => 1));
       
    }

    function set_state_by_id($id_support,$data)
	{
      
    $this->db->where('Id_support', $id_support);
    $query= $this->db->update('technical_supports', $data);
    return $query;
       
    }
    function get_search_suggestions($search,$limit=25)
	{
		$suggestions = array();
        $this->db->from('technical_supports');
		$this->db->like('color', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_name = $this->db->get();
		$temp_suggestions = array();
		foreach($by_name->result() as $row)
		{
			$temp_suggestions[] = $row->color;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
        }
		$this->db->from('technical_supports');
		$this->db->like('custom1_support_name', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_name = $this->db->get();
		$temp_suggestions = array();
		foreach($by_name->result() as $row)
		{
			$temp_suggestions[] = $row->custom1_support_name;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
        }
        $this->db->from('technical_supports');
		$this->db->like('custom2_support_name', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_name = $this->db->get();
		$temp_suggestions = array();
		foreach($by_name->result() as $row)
		{
			$temp_suggestions[] = $row->custom2_support_name;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
		}
		$this->db->from('technical_supports');
		$this->db->like('order_support', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_name = $this->db->get();
		$temp_suggestions = array();
		foreach($by_name->result() as $row)
		{
			$temp_suggestions[] = $row->order_support;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
        }

        $this->db->from('technical_supports');
		$this->db->like('model', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_name = $this->db->get();
		$temp_suggestions = array();
		foreach($by_name->result() as $row)
		{
			$temp_suggestions[] = $row->model;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
        }
        $this->db->from('technical_supports');
		$this->db->like('state', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_name = $this->db->get();
		$temp_suggestions = array();
		foreach($by_name->result() as $row)
		{
			$temp_suggestions[] = $row->state;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
        }
        $this->db->select('people.first_name as first_name, 
        people.last_name as last_name');
        $this->db->join('people', 'customers.person_id = people.person_id');
        $this->db->from('customers');
		$this->db->like('first_name', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_name = $this->db->get();
		$temp_suggestions = array();
		foreach($by_name->result() as $row)
		{
			$temp_suggestions[] = $row->first_name." ".$row->last_name;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
        }
       
        $this->db->from('customers');
		$this->db->like('account_number', $search);
		$this->db->where('deleted',0);
		$this->db->limit($limit);
		$by_name = $this->db->get();
		$temp_suggestions = array();
		foreach($by_name->result() as $row)
		{
			$temp_suggestions[] = $row->account_number;
		}
		
		sort($temp_suggestions);
		foreach($temp_suggestions as $temp_suggestion)
		{
			$suggestions[]=array('label'=> $temp_suggestion);		
        }
		return $suggestions;

	}
    function get_info_by_id($id_support, $id_location=false)
	{
        if(!$id_location){
        $id_location=$this->Employee->get_logged_in_employee_current_location_id();		
        }
		$this->db->from('technical_supports');		
        $this->db->where('deleted',0);
        $this->db->where('id_location',$id_location);       
        $this->db->where('Id_support',$id_support);          
		
        $query=$this->db->get();


        if($query->num_rows() ==1) {
            return $query->row();
        } else {          
            $item_subcategory_obj = new stdClass();
            //Get all the fields from technical_supports table
            $fields = $this->db->list_fields('technical_supports');

            foreach ($fields as $field) {             
                $item_subcategory_obj->$field = '';
            }
            return $item_subcategory_obj;
        }
	
	}
	   
    function exite_spare_part($id_support, $serie){
        $this->db->from('spare_parts');		
        $this->db->where('id_support',$id_support);
        $this->db->where('serie',$serie);
        $query=$this->db->get();
        if($query->num_rows() >0) {
            return true;
        }
        return false;
     }
    function add_spare_part($data){
        if(!$this->exite_spare_part($data["id_support"], $data["serie"])){
            return  $this->db->insert('spare_parts', $data);
        }else{
            $this->db->set('quantity', 'quantity+' . $data["quantity"], false);
           
            $this->db->where('id_support', $data["id_support"]);
            return $this->db->update('spare_parts');           
       
        }
    }
    function get_spare_part_by_support($id_support){
        $this->db->from('spare_parts');		
        $this->db->where('id_support',$id_support);
        $query=$this->db->get();
        return $query;
    }
    function delete_spare_part_by_id($id){        
        $this->db->where('id', $id);       
        return  $this->db->delete('spare_parts');   
    }
    
    ///////////////////////////// Modulo de Soporte Tecnico
    function get_historial_serv_tec_cliente($cliente) {         
        $this->db->from('phppos_technical_supports');		
        $this->db->where('id_customer',$cliente);
        $this->db->order_by('Id_support');
        $reportHServicios=$this->db->get();
        return $reportHServicios;
    } 
    function get_datos_support($idSupport) {         
        $this->db->from('phppos_technical_supports');		
        $this->db->where('id_support',$idSupport); 
        $reportServicioss=$this->db->get();
        return $reportServicioss;
    }
    function add_diagnostico($id_support,$diagnostico){ 
        $this->db->query("SET autocommit=0");      
        if(!$this->db->insert('phppos_technical_supports_diag_tec', $diagnostico)){
            $this->db->query("ROLLBACK");
            return false;
        }
        $id =$this->db->insert_id();
        $this->db->where('Id_support', $id_support);
        if(!$this->db->update('phppos_technical_supports',
         array('state' => lang("technical_supports_diagnosticado")))){
            $this->db->query("ROLLBACK");
            return false;
        }
        $this->db->query("COMMIT");
        return $id;
    }
    function get_diagnosticos($idSupport,$id=''){ 
        $this->db->from('phppos_technical_supports_diag_tec');	
        if($id==''){ 
            $this->db->where('id_support',$idSupport); 
        }
        else{
             $this->db->where('id',$id);
        }
        $this->db->order_by('diagnostico');
        $reportServiciosl=$this->db->get();
         return $reportServiciosl;        
    }
    function updat__diagnosticos($id,$dataDiag){
        $this->db->where('id', $id);
        return $this->db->update('phppos_technical_supports_diag_tec', array('diagnostico' => $dataDiag));
    }
    function cantidad_diagnosticos($idSupport){
        $this->db->from('phppos_technical_supports_diag_tec');		
        $this->db->where('id_support',$idSupport);         
        return $this->db->get()->num_rows()  ;          
    }
    function delete_diagnostico($idSup,$idSupport){
        
            $this->db->where('id', $idSup);       
            $eliminado = $this->db->delete('phppos_technical_supports_diag_tec');  
            
          
            if($this->cantidad_diagnosticos($idSupport) == 0) {  
                $this->db->where('Id_support', $idSupport);
                $this->db->update('phppos_technical_supports', array('state' => lang("technical_supports_recibido")));             
            } 
            return $eliminado;
       
    }
    
    function updat_status_serv_tecnico($idSupport,$dataResp,$dataST){
        $this->db->where('Id_support', $idSupport);
        return $this->db->update('phppos_technical_supports', array('state' => $dataResp,'repair_cost' => $dataST['costo'],'observaciones_entrega' => $dataST['comentarios'],'do_have_guarantee' => $dataST['garantia'],'date_garantia' => $dataST['fecha_garantia']));
    }
    
    function get_sev_tec_cliente($idSupport,$aq=""){ 
        $reportServTec=$this->db->query("SELECT ts.*, client.phone_number, client.email, client.country, client.first_name, client.last_name, client.image_id
        FROM phppos_technical_supports ts, phppos_people client
        WHERE 	ts.id_support='$idSupport' and ts.id_customer=client.person_id Order By ts.Id_support");
        if($aq=='') {
         return $reportServTec;
        }
        if($aq=='1') {
         return $reportServTec->row();
        }
        
    }
    function get_abonos_serv_tecnico($idSupport){ 
        $reportServAbon=$this->db->query("SELECT SUM(payment) Abonado FROM phppos_support_payments WHERE id_support='$idSupport'"); 
         return $reportServAbon;
    }
    function updat_status_serv_tec_cliente($idSupport,$data){ 
        $now= date('Y-m-d H:i:s');
        $this->db->where('Id_support', $idSupport);
        $this->db->update('phppos_technical_supports', array('state' => 'ENTREGADO','retirado_por'=>$data["nota"],'date_entregado' => $now));
    }
    public function buscar_servicios($searchword,$q) { 
        $current_location = $this->Employee->get_logged_in_employee_current_location_id();
        $where="";
        $bq= strtoupper("$searchword"); 
            $arr = explode(' ',$bq);
            if (is_array($arr)) {
                foreach($arr as $var){
                    $where.=" AND (UPPER(ts.model) Like '%$var%' Or UPPER(ts.marca) Like '%$var%' OR ts.type_team Like '%$var%' Or ts.color Like '%$var%' Or client.first_name Like '%$var%' Or client.last_name Like '%$var%' Or ts.Id_support Like '%$var%') ";
                }
            } else {
                $where=" AND (UPPER(ts.model) Like '%$bq%' Or UPPER(ts.marca) Like '%$bq%' OR ts.type_team Like '%$bq%' Or ts.color Like '%$bq%' Or client.first_name Like '%$bq%' Or client.last_name Like '%$bq%' Or ts.Id_support Like '%$bq%')";
            }
            if($q=="") {
                $reportList=$this->db->query("SELECT DISTINCT client.first_name, client.last_name, ts.id_customer, ts.type_team, ts.Id_support,ts.marca, ts.model, ts.color FROM phppos_technical_supports ts, phppos_people client
                WHERE  1=1 $where and ts.id_customer=client.person_id and id_location='$current_location' ORDER BY ts.marca Limit 0,10"); 
            }
            if($q=="1") {
                $reportList=$this->db->query("SELECT DISTINCT client.first_name, client.last_name, ts.id_customer, ts.marca FROM phppos_technical_supports ts, phppos_people client
                WHERE  1=1 $where and ts.id_customer=client.person_id and id_location='$current_location' ORDER BY ts.marca Limit 0,10"); 
            }
            if($q=="2") {
                $reportList=$this->db->query("SELECT DISTINCT client.first_name, client.last_name, ts.Id_support, ts.marca,ts.marca, ts.model, ts.type_team,ts.marca FROM phppos_technical_supports ts, phppos_people client
                WHERE  1=1 $where and ts.state<>'ENTREGADO' and ts.state<>'REPARADO' and ts.state<>'RECHAZADO' and ts.id_customer=client.person_id and id_location='$current_location' ORDER BY ts.marca Limit 0,10"); 
            }
        return $reportList;
     }
     public function buscar_servicios_total($searchword) {  
         $current_location = $this->Employee->get_logged_in_employee_current_location_id();
         $where="";
         $bq= strtoupper("$searchword"); 
            $arr = explode(' ',$bq);
            if (is_array($arr)) {
                foreach($arr as $var){
                    $where.=" AND (UPPER(ts.model) Like '%$var%' Or UPPER(ts.marca) Like '%$var%' OR ts.type_team Like '%$var%' Or ts.color Like '%$var%' Or client.first_name Like '%$var%' Or client.last_name Like '%$var%') ";
                }
            } else {
                $where=" AND (UPPER(ts.model) Like '%$bq%' Or UPPER(ts.marca) Like '%$bq%' OR ts.type_team Like '%$bq%' Or ts.color Like '%$bq%' Or client.first_name Like '%$bq%' Or client.last_name Like '%$bq%')";
            }
        $reportListt=$this->db->query("SELECT DISTINCT client.first_name, client.last_name, ts.type_team, ts.Id_support,ts.marca, ts.model, ts.color FROM phppos_technical_supports ts, phppos_people client
        WHERE  1=1 $where and ts.id_customer=client.person_id and id_location='$current_location' ORDER BY client.last_name"); 
        return $reportListt;
     }
    function hCliente($cliente) {
        $reportCliente=$this->db->query("SELECT id_customer
        FROM phppos_technical_supports
        WHERE id_customer='$cliente' Order By id_customer Limit 0,1"); 
         return $reportCliente;
        } 
        /*
	Gets information about a particular customer
	*/
	function get_info($customer_id)
	{   
		$this->db->from('customers');	
		$this->db->join('people', 'people.person_id = customers.person_id');
		$this->db->where('customers.person_id',$customer_id);
		$query = $this->db->get();
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $customer_id is NOT an customer
			$person_obj=parent::get_info(-1);
			
			//Get all the fields from customer table
			$fields = $this->db->list_fields('customers');
			
			//append those fields to base parent object, we we have a complete empty object
			foreach ($fields as $field)
			{
				$person_obj->$field='';
			}
			
			return $person_obj;
		}
	}
    function get_fallas($tipo) {
        $reportFalla=$this->db->query("SELECT Distinct damage_failure 
        FROM phppos_technical_supports
        WHERE state='$tipo' Order By damage_failure"); 
         return $reportFalla;
    } 
    function get_servicio_tipo($tipo) {
        $reportServ=$this->db->query("SELECT Distinct type_team 
        FROM phppos_technical_supports
        WHERE state='$tipo' Order By type_team"); 
         return $reportServ;
    } 
    function get_tecnicos($tipo) { 
       $reportTecnico=$this->db->query("SELECT Distinct peo.last_name, peo.first_name, tec.id_technical
        FROM phppos_technical_supports tec, phppos_people peo 
        WHERE tec.state='$tipo' and peo.person_id=tec.id_technical Order By peo.last_name"); 
         return $reportTecnico;
    } 
    function get_tecnicos_total($tipo) { 
        $reportFallat=$this->db->query("SELECT *
        FROM phppos_technical_supports
        WHERE state='$tipo'"); 
         return $reportFallat;
    } 
        
   
}
?>
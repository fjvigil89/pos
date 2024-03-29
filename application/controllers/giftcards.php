<?php
require_once ("secure_area.php");
require_once ("interfaces/idata_controller.php");
class Giftcards extends Secure_area implements iData_controller
{
	function __construct() 
	{
		parent::__construct('giftcards');
	}

	function index($offset=0)
	{
		$params = $this->session->userdata('giftcard_search_data') ? $this->session->userdata('giftcard_search_data') : array('offset' => 0, 'order_col' => 'giftcard_number', 'order_dir' => 'asc', 'search' => FALSE);
		if ($offset!=$params['offset'])
		{
		   redirect('giftcards/index/'.$params['offset']);
		}
		$this->check_action_permission('search');
		$config['base_url'] = site_url('giftcards/sorting');
		$config['per_page'] = $this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20; 
		$data['controller_name']=strtolower(get_class());
		$data['per_page'] = $config['per_page'];
		$data['search'] = $params['search'] ? $params['search'] : "";
		if ($data['search'])
		{
			$config['total_rows'] = $this->Giftcard->search_count_all($data['search']);
			$table_data = $this->Giftcard->search($data['search'],$data['per_page'],$params['offset'],$params['order_col'],$params['order_dir']);
		}
		else
		{
			$config['total_rows'] = $this->Giftcard->count_all();
			$table_data = $this->Giftcard->get_all($data['per_page'],$params['offset'],$params['order_col'],$params['order_dir']);
		}
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['order_col'] = $params['order_col'];
		$data['order_dir'] = $params['order_dir'];
		$data['total_rows'] = $config['total_rows'];		
		$data['manage_table']=get_giftcards_manage_table($table_data,$this);
		$this->load->view('giftcards/manage',$data);
	}
	
	function sorting()
	{
		$this->check_action_permission('search');
		$search=$this->input->post('search') ? $this->input->post('search') : "";
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;

		$offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
		$order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'giftcard_number';
		$order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc';

		$giftcard_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search);
		$this->session->set_userdata("giftcard_search_data",$giftcard_search_data);

		if ($search)
		{
			$config['total_rows'] = $this->Giftcard->search_count_all($search);
			$table_data = $this->Giftcard->search($search,$per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'giftcard_number' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		}
		else
		{
			$config['total_rows'] = $this->Giftcard->count_all();
			$table_data = $this->Giftcard->get_all($per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'giftcard_number' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		}
		$config['base_url'] = site_url('giftcards/sorting');
		$config['per_page'] = $per_page; 
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['manage_table']=get_Giftcards_manage_table_data_rows($table_data,$this);
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));	
	}
	function check_duplicate()
	{
		echo json_encode(array('duplicate'=>$this->Giftcard->check_duplicate($this->input->post('term'))));
	}

	function clear_state()
	{
		$this->session->unset_userdata('giftcard_search_data');
		redirect('giftcards');
	}

	/* added for excel expert */
	function excel_export() {
		$data = $this->Giftcard->get_all()->result_object();
		$this->load->helper('report');
		$rows = array();
		$row = array(lang('giftcards_giftcard_number'),lang('giftcards_card_value'));
		$rows[] = $row;
		foreach ($data as $r) {
			$row = array(
				$r->giftcard_number,
				$r->value
			);
			$rows[] = $row;
		}
		
		$content = array_to_spreadsheet($rows);
		force_download('giftcards_export.'.($this->config->item('spreadsheet_format') == 'XLSX' ? 'xlsx' : 'csv'), $content);
		exit;
	}

	function search()
	{
		$this->check_action_permission('search');
		$search=$this->input->post('search');
		$offset = $this->input->post('offset') ? $this->input->post('offset') : 0;
		$order_col = $this->input->post('order_col') ? $this->input->post('order_col') : 'giftcard_number';
		$order_dir = $this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc';

		$giftcard_search_data = array('offset' => $offset, 'order_col' => $order_col, 'order_dir' => $order_dir, 'search' => $search);
		$this->session->set_userdata("giftcard_search_data",$giftcard_search_data);
		$per_page=$this->config->item('number_of_items_per_page') ? (int)$this->config->item('number_of_items_per_page') : 20;
		$search_data=$this->Giftcard->search($search,$per_page,$this->input->post('offset') ? $this->input->post('offset') : 0, $this->input->post('order_col') ? $this->input->post('order_col') : 'giftcard_number' ,$this->input->post('order_dir') ? $this->input->post('order_dir'): 'asc');
		$config['base_url'] = site_url('giftcards/search');
		$config['total_rows'] = $this->Giftcard->search_count_all($search);
		$config['per_page'] = $per_page ;
		$this->pagination->initialize($config);				
		$data['pagination'] = $this->pagination->create_links();
		$data['manage_table']=get_giftcards_manage_table_data_rows($search_data,$this);
		echo json_encode(array('manage_table' => $data['manage_table'], 'pagination' => $data['pagination']));
		
	}
	
	

	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		//allow parallel searchs to improve performance.
		session_write_close();
		$suggestions = $this->Giftcard->get_search_suggestions($this->input->get('term'),100);
		echo json_encode($suggestions);
	}

	function view($giftcard_id=-1,$redirect=0)
	{
		$this->check_action_permission('add_update');
		
		$data = array();

		$data['customers'] = array('' => 'No Customer');
		foreach ($this->Customer->get_all()->result() as $customer)
		{
			$data['customers'][$customer->person_id] = $customer->first_name . ' '. $customer->last_name;
		}
		
		$data['giftcard_info']=$this->Giftcard->get_info($giftcard_id);
		$data['redirect']= $redirect;
		$this->load->view("giftcards/form",$data);
	}
	
	function giftcard_exists()
	{
		if($this->Giftcard->get_giftcard_id($this->input->post('description') ? $this->input->post('description') : $this->input->post('giftcard_number')))
		echo 'false';
		else
		echo 'true';
		
	}


	function point_reedempoints($customer_id=-1)
	{

		$redirect=$this->input->post('redirect_code');
		$this->load->model('points');
		$value_point_reedempoints=$this->input->post('point_reedempoints');
		$value_point=$this->input->post('value_point');
		$value_point_real=$this->config->item('value_point');
		$percent_point=$this->config->item('value_point');
		$percent_point=$this->config->item('percent_point')/100;
		if($value_point_reedempoints<=$value_point && $value_point_reedempoints>0)
		{
        $value=$value_point_real*$value_point*$percent_point;
        $point_update=$value_point-$value_point_reedempoints;
        
        $this->points->update_point($point_update,$customer_id);
		$giftcard_data = array(
		'giftcard_number'=>$this->input->post('giftcard_number'),
		'value'=>$value,
		'customer_id'=>$customer_id,
		);
		$this->Giftcard->save( $giftcard_data,$giftcard_id=-1);

        echo json_encode(array('success'=>true,'message'=> lang('giftcards_add'),'person_id'=>$customer_id,'redirect_code'=>$redirect));
	
		
       	}
     
	    	else
       	{
          		 echo json_encode(array('success'=>false,'message'=>lang('giftcards_error_points')));
         
       }


     

}
       
	
	function save($giftcard_id=-1)
	{
		$giftcard_data = array(
		'giftcard_number'=>$this->input->post('giftcard_number'),
		'value'=>$this->input->post('value'),
		'customer_id'=>$this->input->post('customer_id')=='' ? null:$this->input->post('customer_id'),
		);

		$redirect=$this->input->post('redirect');

		if( $this->Giftcard->save( $giftcard_data, $giftcard_id ) )
		{
			$success_message = '';
			
			//New giftcard
			if($giftcard_id==-1)
			{
				$success_message = lang('giftcards_successful_adding').' '.$giftcard_data['giftcard_number'];
				echo json_encode(array('success'=>true,'message'=>$success_message,'giftcard_id'=>$giftcard_data['giftcard_id'],'redirect' => 2));
				$giftcard_id = $giftcard_data['giftcard_id'];
			}
			else //previous giftcard
			{
				$success_message = lang('giftcards_successful_updating').' '.$giftcard_data['giftcard_number'];
				$this->session->set_flashdata('manage_success_message', $success_message);
				echo json_encode(array('success'=>true,'message'=>$success_message,'giftcard_id'=>$giftcard_id,'redirect' => $redirect));
			}
			
		}
		else//failure
		{
			echo json_encode(array('success'=>false,'message'=>lang('giftcards_error_adding_updating').' '.
			$giftcard_data['giftcard_number'],'giftcard_id'=>-1));
		}

	}

	function delete()
	{
		$this->check_action_permission('delete');		
		$giftcards_to_delete=$this->input->post('ids');

		if($this->Giftcard->delete_list($giftcards_to_delete))
		{
			echo json_encode(array('success'=>true,'message'=>lang('giftcards_successful_deleted').' '.
			count($giftcards_to_delete).' '.lang('giftcards_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>lang('giftcards_cannot_be_deleted')));
		}
	}

	function generate_barcodes($giftcard_ids)
	{
		$result = array();

		$giftcard_ids = explode('~', $giftcard_ids);
		foreach ($giftcard_ids as $giftcard_id)
		{
			$giftcard_info = $this->Giftcard->get_info($giftcard_id);
			$result[] = array('name' =>$giftcard_info->giftcard_number. ': '.to_currency($giftcard_info->value, 10), 'id'=> $giftcard_info->giftcard_number);
		}

		$data['items'] = $result;
		$data['scale'] = 1;
		$this->load->view("barcode_sheet", $data);
	}
	
	function generate_barcode_labels($giftcard_ids)
	{
		$result = array();

		$giftcard_ids = explode('~', $giftcard_ids);
		foreach ($giftcard_ids as $giftcard_id)
		{
			$giftcard_info = $this->Giftcard->get_info($giftcard_id);
			$result[] = array('name' =>$giftcard_info->giftcard_number. ': '.to_currency($giftcard_info->value, 10), 'id'=> $giftcard_info->giftcard_number);
		}

		$data['items'] = $result;
		$data['scale'] = 1;
		$this->load->view("barcode_labels", $data);
	}
	
	function save_item($item_id=-1)
	{
		$this->check_action_permission('add_update');		
		$item_data = array(
		'deleted' => 0,
		'name'=>$this->input->post('name'),
		'description'=>$this->input->post('description'),
		'tax_included'=>$this->input->post('tax_included') ? $this->input->post('tax_included') : 0,
		'category'=>$this->input->post('category'),
		'size'=>$this->input->post('size'),
		'supplier_id'=>$this->input->post('supplier_id')=='' ? null:$this->input->post('supplier_id'),
		'item_number'=>$this->input->post('item_number')=='' ? null:$this->input->post('item_number'),
		'product_id'=>$this->input->post('product_id')=='' ? null:$this->input->post('product_id'),
		'cost_price'=>$this->input->post('cost_price'),
		'unit_price'=>$this->input->post('unit_price'),
		'promo_price'=>$this->input->post('promo_price') ? $this->input->post('promo_price') : NULL,
		'start_date'=>$this->input->post('start_date') ? date('Y-m-d', strtotime($this->input->post('start_date'))) : NULL,
		'end_date'=>$this->input->post('end_date') ?date('Y-m-d', strtotime($this->input->post('end_date'))) : NULL,
		'reorder_level'=>$this->input->post('reorder_level')!='' ? $this->input->post('reorder_level') : NULL,
		'is_service'=>$this->input->post('is_service') ? $this->input->post('is_service') : 0 ,
		'allow_alt_description'=>$this->input->post('allow_alt_description') ? $this->input->post('allow_alt_description') : 0 ,
		'is_serialized'=>$this->input->post('is_serialized') ? $this->input->post('is_serialized') : 0,
		'override_default_tax'=> $this->input->post('override_default_tax') ? $this->input->post('override_default_tax') : 0,
		);
		

		$redirect=$this->input->post('redirect');
		$sale_or_receiving=$this->input->post('sale_or_receiving');

		if($this->Item->save($item_data,$item_id))
		{
			//New item
			if($item_id==-1)
			{				
				echo json_encode(array('success'=>true,'message'=>lang('items_successful_adding').' '.
				$item_data['name'],'item_id'=>$item_data['item_id'],'redirect' => $redirect, 'sale_or_receiving'=>$sale_or_receiving));
				$item_id = $item_data['item_id'];
			}
			else //previous item
			{
				echo json_encode(array('success'=>true,'message'=>lang('items_successful_updating').' '.
				$item_data['name'],'item_id'=>$item_id,'redirect' => $redirect, 'sale_or_receiving'=>$sale_or_receiving));
			}			
		}
		else //failure
		{
			echo json_encode(array('success'=>false,'message'=>lang('items_error_adding_updating').' '.
			$item_data['name'],'item_id'=>-1));
		}

	}

	function giftcard_print($giftcard_id=-1,$redirect=0)
	{
		$this->check_action_permission('add_update');
		
		$data = array();

		$data['customers'] = "No Customer";

		

		$data['giftcard_info']=$this->Giftcard->get_info($giftcard_id);
		$customer=$this->Customer->get_info($data['giftcard_info']->customer_id);

		if(!$data['giftcard_info']->customer_id==NULL)
		{
			$data['customers'] = $customer->first_name . ' '. $customer->last_name;
		}
		
		$data['redirect']= $redirect;
		$this->load->view("giftcards/print",$data);
	}

}
?>
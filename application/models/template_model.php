<?php
class Template_model extends CI_Model
{
    protected $bd;
    
    function __construct() {
        parent::__Construct();
        $this->bd = $this->load->database("shop",TRUE);
        //$res = $bd->get('noticias');
    }
	
	/*
	Returns all the locations online
	*/
    public function config_shop()
    {
        $this->bd->select('key, value');
        $this->bd->from('shop_config');   
        return $this->bd->get()->result();  
    }
    
	public function get_all()
	{
        
        $this->bd->select('*');
        $this->bd->from('items');
        $this->bd->join('app_files', 'items.image_id = app_files.file_id', 'left');
        return $this->bd->get()->result(); 
        
	}
    
    public function category(){
        //SELECT DISTINCT `category` FROM phppos_items
        
        $this->bd->distinct();
        $this->bd->select('category');
        $this->bd->from('items');
        return $this->bd->get()->result();  
    }
    
    public function shop_item($id){
        //SELECT * `category` FROM phppos_items
        
        $this->bd->select('*');
        $this->bd->from('items');
        $this->bd->where('item_id',$id);
        $this->bd->join('app_files', 'items.image_id = app_files.file_id', 'left');
        return $this->bd->get()->result(); 
    }
    
    function get_logo_image()
    {
        $this->bd->select('*');
        $this->bd->from('app_config');
        $this->bd->where('key','company_logo');
        $this->bd->join('app_files', 'app_config.value = app_files.file_id');
        return $this->bd->get()->row(); 

    }
    //manda la img segun su id
    function get($file_id)
	{
		$query = $this->bd->get_where('app_files', array('file_id' => $file_id), 1);
		
		if($query->num_rows()==1)
		{
			return $query->row();
		}
		
		return "";
		
	}
	

    

}

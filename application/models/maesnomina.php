
<?php
class Maesnomina extends CI_Model 
{

	public function __construct()
	{
		parent::__construct();
	}

	public function save($data){
		$query = $this->db->insert('maesnomina', $data);
		return $query;
	}
	public function update($id,$data){
		$this->db->where("id", $id);
		$query = $this->db->update("maesnomina", $data);
		return $query;
	}
	public function delete($id)
	{		
		$this->db->where('id', $id);
		$this->db->from('maesnomina');
		$query = $this->db->delete();
		return $query;
	}
	public function getId($id)
	{
		$this->db->where("id", $id);
		$this->db->from('maesnomina');
		$query = $this->db->get();
		$resultado=array();
		if($query->num_rows()>=1)		
			$resultado=$query->result();		
			
			return $resultado ;
		
	}
	public function getAll(){
		
		$this->db->from('maesnomina');
		$query =$this->db->get();		
			
			return $query->result();
	}
}


?>

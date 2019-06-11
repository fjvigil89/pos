<?php 
 
class Add_colum_viewer extends CI_Migration
{
    
    public function up()
	{		
		$this->db->query("ALTER TABLE `phppos_viewer_cart` ADD `is_scale` TINYINT(2) NOT NULL DEFAULT '0' AFTER `new_tax`, ADD `data_scale` MEDIUMTEXT NULL DEFAULT NULL AFTER `is_scale`");
	}


	public function down()
	{		
		$this->db->query("ALTER TABLE phppos_viewer_cart DROP data_scale");	
		$this->db->query("ALTER TABLE phppos_viewer_cart DROP is_scale");		

	}
    
}
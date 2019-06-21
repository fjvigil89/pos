<?php 
 
class Migration_Farmacia extends CI_Migration
{
    
    public function up()
	{		
		$this->db->query("ALTER TABLE `phppos_items_subcategory` ADD `of_low` TINYINT(2) NOT NULL DEFAULT '0' AFTER `quantity`, 
			ADD `expiration_date` DATETIME NULL DEFAULT NULL AFTER `of_low`;"); 
	}


	public function down()
	{		
		$this->db->query("ALTER TABLE `phppos_items_subcategory`
		DROP `expiration_date`");
		$this->db->query("ALTER TABLE `phppos_items_subcategory`
		DROP `of_low`");
	}
    
}
<?php 
 
class Add_colum_viewer extends CI_Migration
{
    
    public function up()
	{		
		$this->db->query("ALTER TABLE `phppos_viewer_cart` ADD `is_scale` TINYINT(2) NOT NULL DEFAULT '0' AFTER `new_tax`, ADD `data_scale` MEDIUMTEXT NULL DEFAULT NULL AFTER `is_scale`");
		$this->db->query("INSERT INTO `phppos_modules_actions` (`action_id`, `module_id`, `action_name_key`, `sort`) VALUES ('consultant_price_item', 'items', 'employee_consultant_price_item', '20')");
		$this->db->query("INSERT INTO `phppos_permissions_actions` (`module_id`, `person_id`, `action_id`) VALUES ('items', '1', 'consultant_price_item')");
	}


	public function down()
	{		
		$this->db->query("ALTER TABLE phppos_viewer_cart DROP data_scale");	
		$this->db->query("ALTER TABLE phppos_viewer_cart DROP is_scale");	
		$this->db->query("DELETE FROM `phppos_modules_actions` WHERE `phppos_modules_actions`.`action_id` ='consultant_price_item' AND `phppos_modules_actions`.`module_id` = 'items'");	
		$this->db->query("DELETE FROM `phppos_permissions_actions` WHERE `phppos_permissions_actions`.`module_id` = 'items' AND `phppos_permissions_actions`.`person_id` = 1 AND `phppos_permissions_actions`.`action_id` = 'consultant_price_item'");	

	}
    
}
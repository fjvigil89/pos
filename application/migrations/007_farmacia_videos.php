<?php 
 
class Migration_Farmacia_videos extends CI_Migration
{
    
    public function up()
	{		
		$this->db->query("ALTER TABLE `phppos_items_subcategory` ADD `of_low` TINYINT(2) NOT NULL DEFAULT '0' AFTER `quantity`, 
			ADD `expiration_date` DATETIME NULL DEFAULT NULL AFTER `of_low`;"); 

		$this->db->query("INSERT INTO `phppos_app_config` (`key`, `value`) VALUES ('profile_id', '0')");
		$this->db->query("CREATE TABLE `phppos_hide_video` (
			`employee_id` int(11) NOT NULL,
			`module_id` varchar(100) NOT NULL) ");

		$this->db->query("ALTER TABLE `phppos_hide_video` ADD UNIQUE KEY `employee_id` (`employee_id`,`module_id`)");

		$this->db->query("ALTER TABLE `phppos_hide_video` ADD CONSTRAINT `phppos_hide_video_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`)");
		$this->db->query("ALTER TABLE `phppos_items_subcategory` ADD FOREIGN KEY (`item_id`) REFERENCES `phppos_location_items`(`item_id`) ON DELETE RESTRICT ON UPDATE RESTRICT");
		$this->db->query("ALTER TABLE `phppos_locations` ADD `receive_expired_alert` TINYINT(2) NOT NULL DEFAULT '0' AFTER `deleted`");

		$this->db->query("ALTER TABLE `phppos_items` ADD `shop_online` TINYINT(4) NOT NULL AFTER `update_item`");
		
	}


	public function down()
	{		
		$this->db->query("ALTER TABLE `phppos_items` DROP `shop_online`");  
		$this->db->query("ALTER TABLE `phppos_locations` DROP `receive_expired_alert`");  

		$this->db->query("ALTER TABLE `phppos_items_subcategory`
		DROP `expiration_date`");
		$this->db->query("ALTER TABLE `phppos_items_subcategory`
		DROP `of_low`");
		$this->db->query("DROP TABLE phppos_hide_video");	
		$this->db->query("ALTER TABLE phppos_items_subcategory DROP FOREIGN KEY phppos_items_subcategory_ibfk_2");
		
	}
    
}
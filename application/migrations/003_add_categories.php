<?php 
 
class Migration_Add_categories extends CI_Migration
{
    
    public function up()
	{

		$this->db->query("CREATE TABLE `phppos_categories` (
			`id` int(10) UNSIGNED NOT NULL,
			`name` varchar(100) NOT NULL,
			`img` text,
			`name_img_original` text,
			`ordering` int(11) DEFAULT NULL,
			`deleted` tinyint(2) NOT NULL DEFAULT '0'
		  )");

		$this->db->query("ALTER TABLE `phppos_categories`
		ADD PRIMARY KEY (`id`),
		ADD UNIQUE KEY `name` (`name`)");

		$this->db->query("ALTER TABLE `phppos_categories`
		MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;");
	}


	public function down()
	{		
		$this->db->query("DROP TABLE phppos_categories");		

	}
    
}
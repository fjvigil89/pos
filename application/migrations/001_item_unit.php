<?php 
 
class Migration_Item_unit extends CI_Migration
{
    
    public function up()
	{

		$this->db->query("ALTER TABLE `phppos_items` ADD `has_sales_units` TINYINT NOT NULL DEFAULT '0' AFTER `deleted`");
		$this->db->query("ALTER TABLE `phppos_items` ADD `quantity_unit_sale` DECIMAL(23,10)  NULl AFTER `deleted`");

		$this->db->query("ALTER TABLE `phppos_sales_items` ADD `has_sales_units` TINYINT NOT NULL DEFAULT '0' AFTER `commission`");
		$this->db->query("ALTER TABLE `phppos_sales_items` ADD `name_unit` VARCHAR(60) NULL DEFAULT NULL AFTER `commission`");

		$this->db->query("ALTER TABLE `phppos_sales_items` ADD `has_selected_unit` TINYINT NULL DEFAULT '0' AFTER `commission`");
		$this->db->query("ALTER TABLE `phppos_sales_items` ADD `unit_quantity_presentation` DECIMAL(23,10) NULL DEFAULT NULL AFTER `commission`");
		$this->db->query("ALTER TABLE `phppos_sales_items` ADD `unit_quantity_item` DECIMAL(23,10) NULL DEFAULT NULL AFTER `commission`");

		$this->db->query("ALTER TABLE `phppos_sales_items` ADD `unit_quantity` DECIMAL(23,10) NULL DEFAULT NULL AFTER `commission`");
		$this->db->query("ALTER TABLE `phppos_sales_items` ADD `price_presentation` DECIMAL NULL DEFAULT NULL AFTER `commission`");

		
		$this->db->query("CREATE TABLE `phppos_item_unit_sell` (
			`id` int(11) UNSIGNED NOT  NULL,
			`item_id` int(11) NOT NULL,
			`name` varchar(100) NOT NULL,
			`price` DECIMAL(23,10) NOT NULL ,
			`quatity`  DECIMAL(23,10) NOT NULL DEFAULT '1',
			`default_select` tinyint(1) NOT NULL DEFAULT '0',
			`location_id` int(11) DEFAULT NULL,
			`deleted` tinyint(1) NOT NULL DEFAULT '0'
		  )");


		$this->db->query("ALTER TABLE `phppos_item_unit_sell`
			ADD PRIMARY KEY (`id`),
			ADD KEY `item_id` (`item_id`),
			ADD KEY `location_id` (`location_id`)");

		$this->db->query("ALTER TABLE `phppos_item_unit_sell`
			MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1");
		
		$this->db->query("ALTER TABLE `phppos_item_unit_sell`
			ADD CONSTRAINT `phppos_item_unit_sell_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`),
			ADD CONSTRAINT `phppos_item_unit_sell_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`)");
	
	
		//ejemplos de migraciones 
		
        /*$this->dbforge->add_field(array(
			'blog_id' => array(
				'type' => 'INT',
				'constraint' => 5,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'blog_title' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
			),
			'blog_description' => array(
				'type' => 'TEXT',
				'null' => TRUE,
			),
		));
        
        $this->dbforge->add_key('blog_id', TRUE);
		$this->dbforge->create_table('blog');

		$this->db->query("ALTER TABLE `phppos_blog` ADD `fredy2` INT NOT NULL AFTER `blog_description`");

		$this->dbforge->add_field('id INT NOT NULL AUTO_INCREMENT PRIMARY KEY');

		$this->dbforge->add_field('CONSTRAINT FOREIGN KEY (id) REFERENCES table(id)');

		$this->dbforge->add_field('INDEX (deleted)');

		$this->dbforge->add_column('table',[
			'COLUMN id INT NULL AFTER field',
			'CONSTRAINT fk_id FOREIGN KEY(id) REFERENCES table(id)',
		]);*/

}
	public function down()
	{		
		$this->db->query("DROP TABLE phppos_item_unit_sell");

		$this->db->query("ALTER TABLE phppos_items DROP has_sales_units");
		$this->db->query("ALTER TABLE phppos_items DROP quantity_unit_sale");

		$this->db->query("ALTER TABLE phppos_sales_items DROP has_sales_units");
		$this->db->query("ALTER TABLE phppos_sales_items DROP name_unit");
		$this->db->query("ALTER TABLE phppos_sales_items DROP has_selected_unit");
		$this->db->query("ALTER TABLE phppos_sales_items DROP unit_quantity_presentation");
		$this->db->query("ALTER TABLE phppos_sales_items DROP unit_quantity_item");
		$this->db->query("ALTER TABLE phppos_sales_items DROP unit_quantity");
		$this->db->query("ALTER TABLE phppos_sales_items DROP price_presentation");

	}
    
}
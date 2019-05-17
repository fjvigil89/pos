<?php 
 
class Migration_Add_table extends CI_Migration
{
    
    public function up()
	{

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
		//$this->db->query("ALTER TABLE `phppos_blog` ADD `fredy3` INT NOT NULL AFTER `blog_description`");
	
	}
	public function down()
	{		
		//$this->db->query("ALTER TABLE `phppos_blog` DROP `fredy3`");
	}
    
}
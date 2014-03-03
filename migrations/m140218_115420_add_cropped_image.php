<?php

class m140218_115420_add_cropped_image extends CDbMigration
{
	public function safeUp() {
		$this->addColumn('ophinvisualfields_humphrey_xml', 'cropped_image_id', 'int(10) unsigned NULL');
	
		$this->createIndex('ophinvisualfields_humphrey_xml_protected_file_id_fk', 'protected_file', 'id');
		$this->addForeignKey('ophinvisualfields_humphrey_xml_protected_file_id_fk', 'ophinvisualfields_humphrey_xml', 'cropped_image_id', 'protected_file', 'id');
	}

	public function safeDown() {
		return false;
	}
}
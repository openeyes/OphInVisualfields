<?php

class m140206_104653_add_protected_file_ids extends CDbMigration {

	public function safeUp() {
		$this->addColumn('ophinvisualfields_humphrey_xml', 'xml_file_id', 'int(10) unsigned NOT NULL');
		$this->addColumn('ophinvisualfields_humphrey_xml', 'humphrey_image_id', 'int(10) unsigned NULL');
		$this->addColumn('ophinvisualfields_humphrey_image', 'protected_file_id', 'int(10) unsigned NULL');
		
		$this->createIndex('ophinvisualfields_humphrey_xml_protected_file_xml_id_fk', 'ophinvisualfields_humphrey_xml', 'xml_file_id');
		$this->addForeignKey('ophinvisualfields_humphrey_xml_protected_file_xml_id_fk', 'ophinvisualfields_humphrey_xml', 'xml_file_id', 'protected_file', 'id');
		
		$this->createIndex('ophinvisualfields_humphrey_xml_image_id_fk', 'ophinvisualfields_humphrey_xml', 'humphrey_image_id');
		$this->addForeignKey('ophinvisualfields_humphrey_xml_image_id_fk', 'ophinvisualfields_humphrey_xml', 'humphrey_image_id', 'ophinvisualfields_humphrey_image', 'id');

		$this->createIndex('ophinvisualfields_humphrey_image_protected_file_id_fk', 'protected_file', 'id');
		$this->addForeignKey('ophinvisualfields_humphrey_image_protected_file_id_fk', 'ophinvisualfields_humphrey_image', 'protected_file_id', 'protected_file', 'id');
	}

	public function safeDown() {
		return false;
	}

}
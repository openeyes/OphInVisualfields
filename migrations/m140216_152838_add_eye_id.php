<?php

class m140216_152838_add_eye_id extends CDbMigration
{
	public function safeUp() {
		$this->addColumn('ophinvisualfields_humphrey_xml', 'eye_id', 'int(10) unsigned NOT NULL');
		
		$this->createIndex('ophinvisualfields_humphrey_eye_id_fk', 'ophinvisualfields_humphrey_xml', 'eye_id');
		$this->addForeignKey('ophinvisualfields_humphrey_eye_id_fk', 'ophinvisualfields_humphrey_xml', 'eye_id', 'eye', 'id');
	}

	public function safeDown() {
		return false;
	}
}
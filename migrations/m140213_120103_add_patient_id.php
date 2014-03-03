<?php

class m140213_120103_add_patient_id extends CDbMigration
{
	public function safeUp() {
		$this->addColumn('ophinvisualfields_humphrey_xml', 'patient_id', 'int(10) unsigned NOT NULL');
		
		$this->createIndex('ophinvisualfields_humphrey_patient_id_fk', 'ophinvisualfields_humphrey_xml', 'patient_id');
		$this->addForeignKey('ophinvisualfields_humphrey_patient_id_fk', 'ophinvisualfields_humphrey_xml', 'patient_id', 'patient', 'id');
	}

	public function safeDown() {
		return false;
	}
}
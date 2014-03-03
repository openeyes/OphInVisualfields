<?php

class m140226_135350_add_field_measurement extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp() {
		$this->dbConnection->createCommand()->createTable('ophinvisualfields_field_measurement', array('id INT(10) UNSIGNED NOT NULL primary key AUTO_INCREMENT',
			'patient_measurement_id INT(10) UNSIGNED NOT NULL',
			'humphrey_xml_id INT(10) UNSIGNED NOT NULL',
			'deleted tinyint default 0 NOT NULL'
		));
		$this->dbConnection->createCommand()->addForeignKey('ophinvisualfields_humphrey_xml_id_fk', 'ophinvisualfields_field_measurement', 'humphrey_xml_id', 'ophinvisualfields_humphrey_xml', 'id');
	}

	public function safeDown() {
		$this->dbConnection->createCommand()->dropTable('ophinvisualfields_field_measurement');
	}
}
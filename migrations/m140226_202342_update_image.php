<?php

class m140226_202342_update_image extends CDbMigration {

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp() {

		$this->dbConnection->createCommand()->renameColumn('et_ophinvisualfields_image', 'left_image', 'left_field_id');
		$this->dbConnection->createCommand()->renameColumn('et_ophinvisualfields_image', 'right_image', 'right_field_id');
//		$this->createIndex('et_ophinvisualfields_image_left_field_id_fk', 'et_ophinvisualfields_image', 'left_field_id');
//		$this->createIndex('et_ophinvisualfields_image_right_field_id_fk', 'et_ophinvisualfields_image', 'right_field_id');
//		$this->addForeignKey('et_ophinvisualfields_image_left_field_id_fk', 'et_ophinvisualfields_image', 'left_field_id', 'ophinvisualfields_humphrey_image', 'id');
//		$this->addForeignKey('et_ophinvisualfields_image_right_field_id_fk', 'et_ophinvisualfields_image', 'right_field_id', 'ophinvisualfields_humphrey_image', 'id');
//		$this->dbConnection->createCommand()->addForeignKey('ophinvisualfields_humphrey_xml_id_fk', 'ophinvisualfields_field_measurement', 'humphrey_xml_id', 'ophinvisualfields_humphrey_xml', 'id');		
	}

	public function safeDown() {
		
		$this->dbConnection->createCommand()->renameColumn('et_ophinvisualfields_image', 'right_field_id', 'right_image');
		$this->dbConnection->createCommand()->renameColumn('et_ophinvisualfields_image', 'left_field_id', 'left_image');
	}

}
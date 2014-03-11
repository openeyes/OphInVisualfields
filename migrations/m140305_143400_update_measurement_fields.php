<?php

class m140305_143400_update_measurement_fields extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp() {
    $this->addColumn('ophinvisualfields_field_measurement', 'patient_id', "INT(10) UNSIGNED NOT NULL");
    $this->addColumn('ophinvisualfields_field_measurement', 'eye_id', "INT(10) UNSIGNED NOT NULL");
    $this->addColumn('ophinvisualfields_field_measurement', 'image_id', "INT(10) UNSIGNED NOT NULL");
    $this->addColumn('ophinvisualfields_field_measurement', 'cropped_image_id', "INT(10) UNSIGNED NOT NULL");
    $this->addColumn('ophinvisualfields_field_measurement', 'strategy_id', "INT(10) UNSIGNED NOT NULL");
    $this->addColumn('ophinvisualfields_field_measurement', 'pattern_id', "INT(10) UNSIGNED NOT NULL");
    $this->addColumn('ophinvisualfields_field_measurement', 'study_datetime', "DATETIME NOT NULL");
    
		$this->dbConnection->createCommand()->addForeignKey('ophinvisualfields_field_measurement_pmid_fk', 'ophinvisualfields_field_measurement', 'patient_measurement_id', 'patient_measurement', 'id');
		$this->dbConnection->createCommand()->addForeignKey('ophinvisualfields_field_measurement_patternid_fk', 'ophinvisualfields_field_measurement', 'pattern_id', 'ophinvisualfields_pattern', 'id');
		$this->dbConnection->createCommand()->addForeignKey('ophinvisualfields_field_measurement_sid_fk', 'ophinvisualfields_field_measurement', 'strategy_id', 'ophinvisualfields_strategy', 'id');
		$this->dbConnection->createCommand()->addForeignKey('ophinvisualfields_field_measurement_pid_fk', 'ophinvisualfields_field_measurement', 'patient_id', 'patient', 'id');
		$this->dbConnection->createCommand()->addForeignKey('ophinvisualfields_field_measurement_eye_id_fk', 'ophinvisualfields_field_measurement', 'eye_id', 'eye', 'id');
		$this->dbConnection->createCommand()->addForeignKey('ophinvisualfields_field_measurement_image_id_fk', 'ophinvisualfields_field_measurement', 'image_id', 'protected_file', 'id');
		$this->dbConnection->createCommand()->addForeignKey('ophinvisualfields_field_measurement_cropped_id_fk', 'ophinvisualfields_field_measurement', 'cropped_image_id', 'protected_file', 'id');
	}

	public function safeDown() {
		$this->dbConnection->createCommand()->dropTable('ophinvisualfields_field_measurement');
	}
}
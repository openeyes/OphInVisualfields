<?php

class m140319_161227_add_measurement_type extends CDbMigration {

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp() {
		$this->insert('measurement_type', array('class_name' => 'MeasurementVisualFieldHumphrey'));
	}

	public function safeDown() {
		$this->delete('measurement_type', 'class_name=?', array('MeasurementVisualFieldHumphrey'));
	}

}
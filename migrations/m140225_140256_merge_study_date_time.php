<?php

class m140225_140256_merge_study_date_time extends CDbMigration {

	public function safeUp() {
		$this->dbConnection->createCommand()->addColumn('ophinvisualfields_humphrey_xml', 'study_datetime', 'datetime NOT NULL DEFAULT "1900-01-01 00:00:00"');
		$fields = $this->dbConnection->createCommand()->select('id, study_date, study_time')->from('ophinvisualfields_humphrey_xml')->queryAll();
		foreach ($fields as $field) {
			$this->dbConnection->createCommand()->update('ophinvisualfields_humphrey_xml', array('study_datetime' => $field['study_date'] . " " . $field['study_time']), 'id=' . $field['id']);
		}
	}

	public function safeDown() {
		return false;
	}

}
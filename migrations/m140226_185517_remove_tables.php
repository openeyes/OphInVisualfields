<?php

class m140226_185517_remove_tables extends CDbMigration {

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp() {
		$this->getDbConnection()->createCommand()->dropTable('ophinvisualfields_testtype_version');
		$this->getDbConnection()->createCommand()->dropTable('ophinvisualfields_testtype');
	}

	public function safeDown() {
		
	}

}
<?php

class m140207_114402_add_soft_deleted_fields extends CDbMigration {

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp() {
		$prefix = 'ophinvisualfields';
		$tables = array('ability', 'assessment', 'pattern', 'strategy', 'testtype',
			'humphrey_image', 'humphrey_xml');
		foreach($tables as $table) {
			// standard table:
			$table_name = $prefix . "_" . $table;
			$this->addColumn($table_name, "deleted", "tinyint(1) unsigned NOT NULL");
			// version table:
			$table_name = $prefix . "_" . $table . "_version";
			$this->addColumn($table_name, "deleted", "tinyint(1) unsigned NOT NULL");
		}
	}

	public function safeDown() {
		return false;
	}

}
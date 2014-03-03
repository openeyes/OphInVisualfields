<?php

class m140226_194620_add_more_soft_delete_fields extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp() {
		$prefix = 'et_ophinvisualfields';
		$tables = array('comments', 'condition', 'details', 'image', 'result');
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
		$prefix = 'et_ophinvisualfields';
		$tables = array('comments', 'condition', 'details', 'image', 'result');
		foreach($tables as $table) {
			// standard table:
			$table_name = $prefix . "_" . $table;
			$this->dropColumn($table_name, "deleted");
			// version table:
			$table_name = $prefix . "_" . $table . "_version";
			$this->dropColumn($table_name, "deleted");
		}
	}
}
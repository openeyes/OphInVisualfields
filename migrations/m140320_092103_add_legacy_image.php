<?php

class m140320_092103_add_legacy_image extends CDbMigration {

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp() {
		$this->addColumn('ophinvisualfields_humphrey_image', 'legacy', 'TINYINT(1) DEFAULT 0 NOT NULL');
		$this->addColumn('ophinvisualfields_humphrey_image_version', 'legacy', 'TINYINT(1) DEFAULT 0 NOT NULL');
	}

	public function safeDown() {
		$this->dropColumn('ophinvisualfields_humphrey_image', 'legacy');
		$this->dropColumn('ophinvisualfields_humphrey_image_version', 'legacy');
	}

}
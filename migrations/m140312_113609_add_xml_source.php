<?php

class m140312_113609_add_xml_source extends CDbMigration {

  // Use safeUp/safeDown to do migration with transaction
  public function safeUp() {
	$this->addColumn('ophinvisualfields_field_measurement', 'source', 'TEXT DEFAULT NULL');
  }

  public function safeDown() {
	$this->dropColumn('ophinvisualfields_field_measurement', 'source');
  }

}
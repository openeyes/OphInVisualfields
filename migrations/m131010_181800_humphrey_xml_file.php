<?php

require_once 'MigrationHelper.php';
class m131010_181800_humphrey_xml_file extends MigrationHelper
{
  private $suffix_xml = 'humphrey_xml';
  private $suffix_image = 'humphrey_image';

  private function getTableName($suffix) {
    return "ophinvisualfields_" . $suffix;
  }

// Use safeUp/safeDown to do migration with transaction
  public function safeUp() {
    $this->createTable($this->getTableName($this->suffix_xml), array_merge(array(
                'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
                'pid' => 'varchar(40) NOT NULL',
                'given_name' => 'varchar(50)',
                'middle_name' => 'varchar(50)',
                'family_name' => 'varchar(50)',
                'birth_date' => 'varchar(10)',
                'study_date' => 'varchar(10)',
                'study_time' => 'varchar(12)',
                'gender' => 'char',
                'eye' => 'char',
                'file_name' => 'varchar(100) NOT NULL',
                'test_strategy' => 'varchar(100) NOT NULL',
                    ), $this->getDefaults($this->suffix_xml)), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');
	
    $this->addColumn($this->getTableName($this->suffix_xml), 'test_name', 'varchar(100) NOT NULL');
  }

  public function safeDown() {
    $this->dropColumn($this->getTableName($this->suffix_xml), 'test_name');

    $this->deleteTableAndData($this->getTableName($this->suffix_xml));
  }
}
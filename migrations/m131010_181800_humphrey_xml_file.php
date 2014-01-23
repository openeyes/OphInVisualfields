<?php

class m131010_181800_humphrey_xml_file extends OEMigration
{
  private $suffix_xml = 'humphrey_xml';
  private $suffix_image = 'humphrey_image';
  private $fileTable = 'fs_file';

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

    $fileLinkTables = array($this->suffix_xml);
    foreach ($fileLinkTables as $tableName) {
      $this->addColumn($this->getTableName($tableName), 'file_id', 'int(10) unsigned NOT NULL');
      $this->createIndex($this->getTableName($tableName) . '_file_id_fk', $this->getTableName($tableName), 'file_id');
      $this->addForeignKey($this->getTableName($tableName) . '_file_id_fk', $this->getTableName($tableName), 'file_id', $this->fileTable, 'id');
    }

    $this->addColumn($this->getTableName($this->suffix_xml), 'tif_file_id', "int(10) unsigned default NULL");
    $this->addForeignKey($this->getTableName($this->suffix_xml) . '_tif_file_id_fk', $this->getTableName($this->suffix_xml), 'tif_file_id', $this->getTableName($this->suffix_image), 'file_id');
    
    $this->addColumn($this->getTableName($this->suffix_xml), 'test_name', 'varchar(100) NOT NULL');
  }

  public function safeDown() {
    $this->dropColumn($this->getTableName($this->suffix_xml), 'test_name');
    
    $this->dropForeignKey($this->getTableName($this->suffix_xml) . '_tif_file_id_fk', $this->getTableName($this->suffix_xml));
    $this->dropColumn($this->getTableName($this->suffix_xml), 'tif_file_id');

    $fileLinkTables = array($this->suffix_image);
    foreach ($fileLinkTables as $tableName) {
      $this->dropForeignKey($this->getTableName($tableName) . '_file_id_fk', $this->getTableName($tableName));
      $this->dropIndex($this->getTableName($tableName) . '_file_id_fk', $this->getTableName($tableName));
      $this->dropColumn($this->getTableName($tableName), 'file_id');
    }
    $this->deleteTableAndData($this->getTableName($this->suffix_xml));
  }
}
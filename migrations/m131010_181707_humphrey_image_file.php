<?php

class m131010_181707_humphrey_image_file extends OEMigration
{
  private $suffix_image = 'humphrey_image';
  private $fileTable = 'fs_file';

  private function getTableName($suffix) {
    return "ophinvisualfields_" . $suffix;
  }

// Use safeUp/safeDown to do migration with transaction
  public function safeUp() {
    $this->createTable($this->getTableName($this->suffix_image), array_merge(array(
                'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',), $this->getDefaults($this->suffix_image)), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');

    $fileLinkTables = array($this->suffix_image);
    foreach ($fileLinkTables as $tableName) {
      $this->addColumn($this->getTableName($tableName), 'file_id', 'int(10) unsigned NOT NULL');
      $this->createIndex($this->getTableName($tableName) . '_file_id_fk', $this->getTableName($tableName), 'file_id');
      $this->addForeignKey($this->getTableName($tableName) . '_file_id_fk', $this->getTableName($tableName), 'file_id', $this->fileTable, 'id');
    }
  }

  public function safeDown() {
    
    $fileLinkTables = array($this->suffix_image);
    foreach ($fileLinkTables as $tableName) {
      $this->dropForeignKey($this->getTableName($tableName) . '_file_id_fk', $this->getTableName($tableName));
      $this->dropIndex($this->getTableName($tableName) . '_file_id_fk', $this->getTableName($tableName));
      $this->dropColumn($this->getTableName($tableName), 'file_id');
    }
    $this->deleteTableAndData($this->getTableName($this->suffix_image));
  }
}
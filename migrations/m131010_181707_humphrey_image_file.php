<?php

require_once 'MigrationHelper.php';
class m131010_181707_humphrey_image_file extends MigrationHelper
{
  private $suffix_image = 'humphrey_image';

  private function getTableName($suffix) {
    return "ophinvisualfields_" . $suffix;
  }

// Use safeUp/safeDown to do migration with transaction
  public function safeUp() {
    $this->createTable($this->getTableName($this->suffix_image), array_merge(array(
                'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',), $this->getDefaults($this->suffix_image)), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');
  }

  public function safeDown() {
    $this->deleteTableAndData($this->getTableName($this->suffix_image));
  }
}
<?php

class m131010_185616_add_associated_field extends CDbMigration
{
  // Use safeUp/safeDown to do migration with transaction
  public function safeUp() {

    $this->addColumn('ophinvisualfields_humphrey_image', 'associated', "tinyint(1) DEFAULT 0");
    $this->addColumn('ophinvisualfields_humphrey_xml', 'associated', "tinyint(1) DEFAULT 0");
  }

  public function safeDown() {
    $this->dropColumn('ophinvisualfields_humphrey_image', 'associated');
    $this->dropColumn('ophinvisualfields_humphrey_xml', 'associated');
  }
}
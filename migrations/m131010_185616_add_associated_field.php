<?php

class m131010_185616_add_associated_field extends CDbMigration
{
  // Use safeUp/safeDown to do migration with transaction
  public function safeUp() {

    $this->addColumn('ophinvisualfields_humphrey_image', 'associated', "tinyint(1) DEFAULT 0");
//    $this->addColumn('ophinvisualfields_humphrey_image', 'xml_id', "int(10) unsigned DEFAULT NULL");
//    $this->createIndex('ophinvisualfields_humphrey_image_xml_id_fk', 'ophinvisualfields_humphrey_image', 'xml_id');
//    $this->addForeignKey('ophinvisualfields_humphrey_image_xml_id_fk', 'ophinvisualfields_humphrey_image', 'xml_id', 'ophinvisualfields_humphrey_xml', 'id');
    $this->addColumn('ophinvisualfields_humphrey_xml', 'associated', "tinyint(1) DEFAULT 0");
  }

  public function safeDown() {
//    $this->dropForeignKey('ophinvisualfields_humphrey_image_xml_id_fk', 'ophinvisualfields_humphrey_image');
//    $this->dropIndex('ophinvisualfields_humphrey_image_xml_id_fk', 'ophinvisualfields_humphrey_image');
    $this->dropColumn('ophinvisualfields_humphrey_image', 'associated');
//    $this->dropColumn('ophinvisualfields_humphrey_image', 'xml_id');
    $this->dropColumn('ophinvisualfields_humphrey_xml', 'associated');
  }
}
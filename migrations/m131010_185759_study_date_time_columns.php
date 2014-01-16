<?php

class m131010_185759_study_date_time_columns extends CDbMigration
{
  // Use safeUp/safeDown to do migration with transaction
  public function safeUp() {
    $this->alterColumn('ophinvisualfields_humphrey_xml', 'study_date', 'date');
    $this->alterColumn('ophinvisualfields_humphrey_xml', 'study_time', 'time');
  }

  public function safeDown() {
    $this->alterColumn('ophinvisualfields_humphrey_xml', 'study_date', 'varchar(10)');
    $this->alterColumn('ophinvisualfields_humphrey_xml', 'study_time', 'varchar(12)');
  }
}

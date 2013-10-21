<?php

class m131010_185759_study_date_time_columns extends CDbMigration
{
  // Use safeUp/safeDown to do migration with transaction
  public function safeUp() {
    $this->alterColumn('ophscimagehumphreys_scan_humphrey_xml', 'study_date', 'date');
    $this->alterColumn('ophscimagehumphreys_scan_humphrey_xml', 'study_time', 'time');
  }

  public function safeDown() {
    $this->alterColumn('ophscimagehumphreys_scan_humphrey_xml', 'study_date', 'varchar(10)');
    $this->alterColumn('ophscimagehumphreys_scan_humphrey_xml', 'study_time', 'varchar(12)');
  }
}
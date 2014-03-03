<?php

class m140226_140703_table_versioning extends CDbMigration
{
	public function up()
	{
		$this->execute("
CREATE TABLE `ophinvisualfields_field_measurement_version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `patient_measurement_id` int(10) unsigned NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `acv_ophinvisualfields_field_measurement_pat_measurement_id_fk` (`patient_measurement_id`),
  CONSTRAINT `acv_ophinvisualfields_field_measurement_pat_measurement_id_fk` FOREIGN KEY (`patient_measurement_id`) REFERENCES `patient_measurement` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
		");

		$this->alterColumn('ophinvisualfields_field_measurement_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophinvisualfields_field_measurement_version');

		$this->createIndex('ophinvisualfields_field_measurement_aid_fk','ophinvisualfields_field_measurement_version','id');
		$this->addForeignKey('ophinvisualfields_field_measurement_aid_fk','ophinvisualfields_field_measurement_version','id','ophinvisualfields_field_measurement','id');

		$this->addColumn('ophinvisualfields_field_measurement_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophinvisualfields_field_measurement_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophinvisualfields_field_measurement_version','version_id');
		$this->alterColumn('ophinvisualfields_field_measurement_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

	}

	public function down()
	{
		$this->dropTable('ophinvisualfields_field_measurement_version');
	}
}

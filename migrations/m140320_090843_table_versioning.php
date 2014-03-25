<?php

class m140320_090843_table_versioning extends CDbMigration
{
	public function up()
	{

		$this->execute("
CREATE TABLE `ophinvisualfields_field_measurement_version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `patient_measurement_id` int(10) unsigned NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `event_id` int(10) unsigned DEFAULT NULL,
  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
  `patient_id` int(10) unsigned NOT NULL,
  `eye_id` int(10) unsigned NOT NULL,
  `image_id` int(10) unsigned NOT NULL,
  `cropped_image_id` int(10) unsigned NOT NULL,
  `strategy_id` int(10) unsigned NOT NULL,
  `pattern_id` int(10) unsigned NOT NULL,
  `study_datetime` datetime NOT NULL,
  `source` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `acv_ophinvisualfields_field_measurement_last_modified_user_id_fk` (`last_modified_user_id`),
  KEY `acv_ophinvisualfields_field_measurement_created_user_id_fk` (`created_user_id`),
  KEY `acv_ophinvisualfields_field_measurement_event_id_fk` (`event_id`),
  KEY `acv_ophinvisualfields_field_measurement_pmid_fk` (`patient_measurement_id`),
  KEY `acv_ophinvisualfields_field_measurement_patternid_fk` (`pattern_id`),
  KEY `acv_ophinvisualfields_field_measurement_sid_fk` (`strategy_id`),
  KEY `acv_ophinvisualfields_field_measurement_pid_fk` (`patient_id`),
  KEY `acv_ophinvisualfields_field_measurement_eye_id_fk` (`eye_id`),
  KEY `acv_ophinvisualfields_field_measurement_image_id_fk` (`image_id`),
  KEY `acv_ophinvisualfields_field_measurement_cropped_id_fk` (`cropped_image_id`),
  CONSTRAINT `acv_ophinvisualfields_field_measurement_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `acv_ophinvisualfields_field_measurement_cropped_id_fk` FOREIGN KEY (`cropped_image_id`) REFERENCES `protected_file` (`id`),
  CONSTRAINT `acv_ophinvisualfields_field_measurement_event_id_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `acv_ophinvisualfields_field_measurement_eye_id_fk` FOREIGN KEY (`eye_id`) REFERENCES `eye` (`id`),
  CONSTRAINT `acv_ophinvisualfields_field_measurement_image_id_fk` FOREIGN KEY (`image_id`) REFERENCES `protected_file` (`id`),
  CONSTRAINT `acv_ophinvisualfields_field_measurement_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `acv_ophinvisualfields_field_measurement_patternid_fk` FOREIGN KEY (`pattern_id`) REFERENCES `ophinvisualfields_pattern` (`id`),
  CONSTRAINT `acv_ophinvisualfields_field_measurement_pid_fk` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`id`),
  CONSTRAINT `acv_ophinvisualfields_field_measurement_pmid_fk` FOREIGN KEY (`patient_measurement_id`) REFERENCES `patient_measurement` (`id`),
  CONSTRAINT `acv_ophinvisualfields_field_measurement_sid_fk` FOREIGN KEY (`strategy_id`) REFERENCES `ophinvisualfields_strategy` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
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

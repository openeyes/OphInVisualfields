<?php

class m140207_113653_table_versioning extends CDbMigration
{
	public function up()
	{
		$this->execute("
CREATE TABLE `et_ophinvisualfields_condition_version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL,
  `ability_id` int(10) unsigned NOT NULL DEFAULT '1',
  `other` text COLLATE utf8_bin,
  `glasses` tinyint(1) unsigned NOT NULL,
  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
  `last_modified_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
  `created_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `acv_et_ophinvisualfields_condition_lmui_fk` (`last_modified_user_id`),
  KEY `acv_et_ophinvisualfields_condition_cui_fk` (`created_user_id`),
  KEY `acv_et_ophinvisualfields_condition_ev_fk` (`event_id`),
  KEY `acv_et_ophinvisualfields_condition_ability_id_fk` (`ability_id`),
  CONSTRAINT `acv_et_ophinvisualfields_condition_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `acv_et_ophinvisualfields_condition_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `acv_et_ophinvisualfields_condition_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `acv_et_ophinvisualfields_condition_ability_id_fk` FOREIGN KEY (`ability_id`) REFERENCES `ophinvisualfields_ability` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('et_ophinvisualfields_condition_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophinvisualfields_condition_version');

		$this->createIndex('et_ophinvisualfields_condition_aid_fk','et_ophinvisualfields_condition_version','id');
		$this->addForeignKey('et_ophinvisualfields_condition_aid_fk','et_ophinvisualfields_condition_version','id','et_ophinvisualfields_condition','id');

		$this->addColumn('et_ophinvisualfields_condition_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophinvisualfields_condition_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophinvisualfields_condition_version','version_id');
		$this->alterColumn('et_ophinvisualfields_condition_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophinvisualfields_details_version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL,
  `pattern_id` int(10) unsigned NOT NULL DEFAULT '1',
  `strategy_id` int(10) unsigned NOT NULL DEFAULT '1',
  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
  `last_modified_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
  `created_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `acv_et_ophinvisualfields_details_lmui_fk` (`last_modified_user_id`),
  KEY `acv_et_ophinvisualfields_details_cui_fk` (`created_user_id`),
  KEY `acv_et_ophinvisualfields_details_ev_fk` (`event_id`),
  KEY `acv_et_ophinvisualfields_details_pattern_id_fk` (`pattern_id`),
  KEY `acv_et_ophinvisualfields_details_strategy_id_fk` (`strategy_id`),
  CONSTRAINT `acv_et_ophinvisualfields_details_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `acv_et_ophinvisualfields_details_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `acv_et_ophinvisualfields_details_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `acv_et_ophinvisualfields_details_pattern_id_fk` FOREIGN KEY (`pattern_id`) REFERENCES `ophinvisualfields_pattern` (`id`),
  CONSTRAINT `acv_et_ophinvisualfields_details_strategy_id_fk` FOREIGN KEY (`strategy_id`) REFERENCES `ophinvisualfields_strategy` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('et_ophinvisualfields_details_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophinvisualfields_details_version');

		$this->createIndex('et_ophinvisualfields_details_aid_fk','et_ophinvisualfields_details_version','id');
		$this->addForeignKey('et_ophinvisualfields_details_aid_fk','et_ophinvisualfields_details_version','id','et_ophinvisualfields_details','id');

		$this->addColumn('et_ophinvisualfields_details_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophinvisualfields_details_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophinvisualfields_details_version','version_id');
		$this->alterColumn('et_ophinvisualfields_details_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophinvisualfields_image_version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL,
  `left_image` int(10) unsigned NOT NULL,
  `right_image` int(10) unsigned NOT NULL,
  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
  `last_modified_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
  `created_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `acv_et_ophinvisualfields_image_lmui_fk` (`last_modified_user_id`),
  KEY `acv_et_ophinvisualfields_image_cui_fk` (`created_user_id`),
  KEY `acv_et_ophinvisualfields_image_ev_fk` (`event_id`),
  CONSTRAINT `acv_et_ophinvisualfields_image_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `acv_et_ophinvisualfields_image_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `acv_et_ophinvisualfields_image_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('et_ophinvisualfields_image_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophinvisualfields_image_version');

		$this->createIndex('et_ophinvisualfields_image_aid_fk','et_ophinvisualfields_image_version','id');
		$this->addForeignKey('et_ophinvisualfields_image_aid_fk','et_ophinvisualfields_image_version','id','et_ophinvisualfields_image','id');

		$this->addColumn('et_ophinvisualfields_image_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophinvisualfields_image_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophinvisualfields_image_version','version_id');
		$this->alterColumn('et_ophinvisualfields_image_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophinvisualfields_result_version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL,
  `reliability` tinyint(1) unsigned NOT NULL,
  `assessment` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `assessment_id` int(10) unsigned NOT NULL DEFAULT '1',
  `other` text COLLATE utf8_bin,
  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
  `last_modified_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
  `created_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `acv_et_ophinvisualfields_result_lmui_fk` (`last_modified_user_id`),
  KEY `acv_et_ophinvisualfields_result_cui_fk` (`created_user_id`),
  KEY `acv_et_ophinvisualfields_result_ev_fk` (`event_id`),
  KEY `acv_et_ophinvisualfields_result_assessment_id_fk` (`assessment_id`),
  CONSTRAINT `acv_et_ophinvisualfields_result_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `acv_et_ophinvisualfields_result_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `acv_et_ophinvisualfields_result_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `acv_et_ophinvisualfields_result_assessment_id_fk` FOREIGN KEY (`assessment_id`) REFERENCES `ophinvisualfields_assessment` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('et_ophinvisualfields_result_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophinvisualfields_result_version');

		$this->createIndex('et_ophinvisualfields_result_aid_fk','et_ophinvisualfields_result_version','id');
		$this->addForeignKey('et_ophinvisualfields_result_aid_fk','et_ophinvisualfields_result_version','id','et_ophinvisualfields_result','id');

		$this->addColumn('et_ophinvisualfields_result_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophinvisualfields_result_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophinvisualfields_result_version','version_id');
		$this->alterColumn('et_ophinvisualfields_result_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophinvisualfields_test_type_version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL,
  `test_type_id` int(10) unsigned NOT NULL,
  `comments` text COLLATE utf8_bin,
  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
  `last_modified_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
  `created_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
  PRIMARY KEY (`id`),
  KEY `acv_et_ophinvisualfields_test_type_lmui_fk` (`last_modified_user_id`),
  KEY `acv_et_ophinvisualfields_test_type_cui_fk` (`created_user_id`),
  KEY `acv_et_ophinvisualfields_test_type_ev_fk` (`event_id`),
  KEY `acv_et_ophinvisualfields_test_type_test_type_id_fk` (`test_type_id`),
  CONSTRAINT `acv_et_ophinvisualfields_test_type_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `acv_et_ophinvisualfields_test_type_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `acv_et_ophinvisualfields_test_type_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `acv_et_ophinvisualfields_test_type_test_type_id_fk` FOREIGN KEY (`test_type_id`) REFERENCES `ophinvisualfields_testtype` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('et_ophinvisualfields_test_type_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophinvisualfields_test_type_version');

		$this->createIndex('et_ophinvisualfields_test_type_aid_fk','et_ophinvisualfields_test_type_version','id');
		$this->addForeignKey('et_ophinvisualfields_test_type_aid_fk','et_ophinvisualfields_test_type_version','id','et_ophinvisualfields_test_type','id');

		$this->addColumn('et_ophinvisualfields_test_type_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophinvisualfields_test_type_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophinvisualfields_test_type_version','version_id');
		$this->alterColumn('et_ophinvisualfields_test_type_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophinvisualfields_ability_version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('ophinvisualfields_ability_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophinvisualfields_ability_version');

		$this->createIndex('ophinvisualfields_ability_aid_fk','ophinvisualfields_ability_version','id');
		$this->addForeignKey('ophinvisualfields_ability_aid_fk','ophinvisualfields_ability_version','id','ophinvisualfields_ability','id');

		$this->addColumn('ophinvisualfields_ability_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophinvisualfields_ability_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophinvisualfields_ability_version','version_id');
		$this->alterColumn('ophinvisualfields_ability_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophinvisualfields_assessment_version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('ophinvisualfields_assessment_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophinvisualfields_assessment_version');

		$this->createIndex('ophinvisualfields_assessment_aid_fk','ophinvisualfields_assessment_version','id');
		$this->addForeignKey('ophinvisualfields_assessment_aid_fk','ophinvisualfields_assessment_version','id','ophinvisualfields_assessment','id');

		$this->addColumn('ophinvisualfields_assessment_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophinvisualfields_assessment_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophinvisualfields_assessment_version','version_id');
		$this->alterColumn('ophinvisualfields_assessment_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophinvisualfields_humphrey_image_version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
  `last_modified_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
  `created_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
  `associated` tinyint(1) DEFAULT '0',
  `protected_file_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `acv_humphrey_image_lmuid_fk` (`last_modified_user_id`),
  KEY `acv_humphrey_image_cuid_fk` (`created_user_id`),
  KEY `acv_ophinvisualfields_humphrey_image_protected_file_id_fk` (`protected_file_id`),
  CONSTRAINT `acv_ophinvisualfields_humphrey_image_protected_file_id_fk` FOREIGN KEY (`protected_file_id`) REFERENCES `protected_file` (`id`),
  CONSTRAINT `acv_humphrey_image_cuid_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `acv_humphrey_image_lmuid_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('ophinvisualfields_humphrey_image_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophinvisualfields_humphrey_image_version');

		$this->createIndex('ophinvisualfields_humphrey_image_aid_fk','ophinvisualfields_humphrey_image_version','id');
		$this->addForeignKey('ophinvisualfields_humphrey_image_aid_fk','ophinvisualfields_humphrey_image_version','id','ophinvisualfields_humphrey_image','id');

		$this->addColumn('ophinvisualfields_humphrey_image_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophinvisualfields_humphrey_image_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophinvisualfields_humphrey_image_version','version_id');
		$this->alterColumn('ophinvisualfields_humphrey_image_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophinvisualfields_humphrey_xml_version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` varchar(40) COLLATE utf8_bin NOT NULL,
  `given_name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `middle_name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `family_name` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `birth_date` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `study_date` date DEFAULT NULL,
  `study_time` time DEFAULT NULL,
  `gender` char(1) COLLATE utf8_bin DEFAULT NULL,
  `eye` char(1) COLLATE utf8_bin DEFAULT NULL,
  `file_name` varchar(100) COLLATE utf8_bin NOT NULL,
  `test_strategy` varchar(100) COLLATE utf8_bin NOT NULL,
  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
  `last_modified_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
  `created_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
  `test_name` varchar(100) COLLATE utf8_bin NOT NULL,
  `associated` tinyint(1) DEFAULT '0',
  `xml_file_id` int(10) unsigned NOT NULL,
  `humphrey_image_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `acv_humphrey_xml_lmuid_fk` (`last_modified_user_id`),
  KEY `acv_humphrey_xml_cuid_fk` (`created_user_id`),
  KEY `acv_ophinvisualfields_humphrey_xml_protected_file_xml_id_fk` (`xml_file_id`),
  KEY `acv_ophinvisualfields_humphrey_xml_image_id_fk` (`humphrey_image_id`),
  CONSTRAINT `acv_ophinvisualfields_humphrey_xml_image_id_fk` FOREIGN KEY (`humphrey_image_id`) REFERENCES `ophinvisualfields_humphrey_image` (`id`),
  CONSTRAINT `acv_humphrey_xml_cuid_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `acv_humphrey_xml_lmuid_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `acv_ophinvisualfields_humphrey_xml_protected_file_xml_id_fk` FOREIGN KEY (`xml_file_id`) REFERENCES `protected_file` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('ophinvisualfields_humphrey_xml_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophinvisualfields_humphrey_xml_version');

		$this->createIndex('ophinvisualfields_humphrey_xml_aid_fk','ophinvisualfields_humphrey_xml_version','id');
		$this->addForeignKey('ophinvisualfields_humphrey_xml_aid_fk','ophinvisualfields_humphrey_xml_version','id','ophinvisualfields_humphrey_xml','id');

		$this->addColumn('ophinvisualfields_humphrey_xml_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophinvisualfields_humphrey_xml_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophinvisualfields_humphrey_xml_version','version_id');
		$this->alterColumn('ophinvisualfields_humphrey_xml_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophinvisualfields_pattern_version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('ophinvisualfields_pattern_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophinvisualfields_pattern_version');

		$this->createIndex('ophinvisualfields_pattern_aid_fk','ophinvisualfields_pattern_version','id');
		$this->addForeignKey('ophinvisualfields_pattern_aid_fk','ophinvisualfields_pattern_version','id','ophinvisualfields_pattern','id');

		$this->addColumn('ophinvisualfields_pattern_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophinvisualfields_pattern_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophinvisualfields_pattern_version','version_id');
		$this->alterColumn('ophinvisualfields_pattern_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophinvisualfields_strategy_version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('ophinvisualfields_strategy_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophinvisualfields_strategy_version');

		$this->createIndex('ophinvisualfields_strategy_aid_fk','ophinvisualfields_strategy_version','id');
		$this->addForeignKey('ophinvisualfields_strategy_aid_fk','ophinvisualfields_strategy_version','id','ophinvisualfields_strategy','id');

		$this->addColumn('ophinvisualfields_strategy_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophinvisualfields_strategy_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophinvisualfields_strategy_version','version_id');
		$this->alterColumn('ophinvisualfields_strategy_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophinvisualfields_testtype_version` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('ophinvisualfields_testtype_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophinvisualfields_testtype_version');

		$this->createIndex('ophinvisualfields_testtype_aid_fk','ophinvisualfields_testtype_version','id');
		$this->addForeignKey('ophinvisualfields_testtype_aid_fk','ophinvisualfields_testtype_version','id','ophinvisualfields_testtype','id');

		$this->addColumn('ophinvisualfields_testtype_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophinvisualfields_testtype_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophinvisualfields_testtype_version','version_id');
		$this->alterColumn('ophinvisualfields_testtype_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');
	}

	public function down()
	{
		$this->dropTable('et_ophinvisualfields_condition_version');
		$this->dropTable('et_ophinvisualfields_details_version');
		$this->dropTable('et_ophinvisualfields_image_version');
		$this->dropTable('et_ophinvisualfields_result_version');
		$this->dropTable('et_ophinvisualfields_test_type_version');
		$this->dropTable('ophinvisualfields_ability_version');
		$this->dropTable('ophinvisualfields_assessment_version');
		$this->dropTable('ophinvisualfields_humphrey_image_version');
		$this->dropTable('ophinvisualfields_humphrey_xml_version');
		$this->dropTable('ophinvisualfields_pattern_version');
		$this->dropTable('ophinvisualfields_strategy_version');
		$this->dropTable('ophinvisualfields_testtype_version');
	}
}

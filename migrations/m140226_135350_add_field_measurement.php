<?php

class m140226_135350_add_field_measurement extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp() {
		$this->execute("create table `ophinvisualfields_field_measurement` (`id` INT(10) UNSIGNED NOT NULL primary key AUTO_INCREMENT,
			`patient_measurement_id` INT(10) UNSIGNED NOT NULL,
			`deleted` tinyint default 0 NOT NULL,
			`event_id` int(10) unsigned DEFAULT NULL,
			`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			KEY `ophinvisualfields_field_measurement_last_modified_user_id_fk` (`last_modified_user_id`),
			KEY `ophinvisualfields_field_measurement_created_user_id_fk` (`created_user_id`),
			KEY `ophinvisualfields_field_measurement_event_id_fk` (`event_id`),
			CONSTRAINT `ophinvisualfields_field_measurement_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			CONSTRAINT `ophinvisualfields_field_measurement_event_id_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
			CONSTRAINT `ophinvisualfields_field_measurement_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`))
                                                        ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
		//`deleted` tinyint default 0 NOT NULL,
//		$this->dbConnection->createCommand()->addForeignKey('ophinvisualfields_humphrey_xml_id_fk', 'ophinvisualfields_field_measurement', 'humphrey_xml_id', 'ophinvisualfields_humphrey_xml', 'id');
	}

	public function safeDown() {
		$this->dbConnection->createCommand()->dropTable('ophinvisualfields_field_measurement');
	}
}
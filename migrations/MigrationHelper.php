<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MigrationHelper
 *
 * @author rich
 */
class MigrationHelper extends OEMigration {
	//put your code here

/**
	 * Returns all the default table array elements that all tables share.
	 * This is a convenience method for all table creation.
	 *
	 * @param $tableName the table name to use.
	 *
	 * @return an array of defaults to merge in to the table array data required.
	 */

	public function getDefaults($tableName) {
		$defaults = array('last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
			'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01
00:00:00\'',
			'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
			'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
			'PRIMARY KEY (`id`)',
			'KEY `' . $tableName . '_lmuid_fk' . '`
(`last_modified_user_id`)',
			'CONSTRAINT `' . $tableName . '_cuid_fk' . '`
FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
			'CONSTRAINT
`' . $tableName . '_lmuid_fk' . '` FOREIGN KEY
(`last_modified_user_id`) REFERENCES `user` (`id`)');
		return $defaults;
	}

}

?>

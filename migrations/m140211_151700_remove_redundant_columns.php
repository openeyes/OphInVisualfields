<?php

class m140211_151700_remove_redundant_columns extends CDbMigration {

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp() {
		// delete unused references:
		$this->dropForeignKey("ophinvisualfields_humphrey_xml_image_id_fk", "ophinvisualfields_humphrey_xml");
		$this->dropIndex("ophinvisualfields_humphrey_xml_image_id_fk", "ophinvisualfields_humphrey_xml");
		$this->dropForeignKey("ophinvisualfields_humphrey_xml_protected_file_xml_id_fk", "ophinvisualfields_humphrey_xml");
		$this->dropIndex("ophinvisualfields_humphrey_xml_protected_file_xml_id_fk", "ophinvisualfields_humphrey_xml");
		
		$columns = array(
			'pid', 'given_name', 'middle_name', 'family_name',
			'birth_date', 'gender',
			'file_name','xml_file_id');
		foreach($columns as $column) {
			$this->dropColumn("ophinvisualfields_humphrey_xml", $column);
		}
	}

	public function safeDown() {
		return false;
	}

}
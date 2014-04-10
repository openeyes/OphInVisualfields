<?php

class m140410_091201_protected_file_fks extends CDbMigration {

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp() {
        
		$this->createIndex('et_ophinvisualfields_image_left_id_fk', 'et_ophinvisualfields_image', 'left_field_id');
		$this->addForeignKey('et_ophinvisualfields_image_left_field_id_fk', 'et_ophinvisualfields_image', 'left_field_id', 'protected_file', 'id');
		$this->createIndex('et_ophinvisualfields_image_right_id_fk', 'et_ophinvisualfields_image', 'right_field_id');
		$this->addForeignKey('et_ophinvisualfields_image_right_field_id_fk', 'et_ophinvisualfields_image', 'right_field_id', 'protected_file', 'id');
    }

    public function safeDown() {
        
    }

}
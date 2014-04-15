<?php

class m140410_091201_protected_file_fks extends CDbMigration {

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp() {
        // need to allow nulls so that (e.g.) a legacy event can have just one image associated with it until the next image is bound:
        $this->alterColumn('et_ophinvisualfields_image', 'left_field_id', 'int(10) unsigned DEFAULT NULL');
        $this->alterColumn('et_ophinvisualfields_image', 'right_field_id', 'int(10) unsigned DEFAULT NULL');
        
        $this->createIndex('et_ophinvisualfields_image_left_field_id_fk', 'et_ophinvisualfields_image', 'left_field_id');
        $this->addForeignKey('et_ophinvisualfields_image_left_field_id', 'et_ophinvisualfields_image', 'left_field_id', 'protected_file', 'id');
        
        $this->createIndex('et_ophinvisualfields_image_right_field_id', 'et_ophinvisualfields_image', 'right_field_id');
        $this->addForeignKey('et_ophinvisualfields_image_right_id_field_fk', 'et_ophinvisualfields_image', 'right_field_id', 'protected_file', 'id');
    }

    public function safeDown() {
        $this->dropForeignKey('et_ophinvisualfields_image_left_field_id_fk', 'et_ophinvisualfields_image');
        $this->dropIndex('et_ophinvisualfields_image_left_field_id', 'et_ophinvisualfields_image');#
        
        $this->dropForeignKey('et_ophinvisualfields_image_right_field_id_fk', 'et_ophinvisualfields_image');
        $this->dropIndex('et_ophinvisualfields_image_right_field_id', 'et_ophinvisualfields_image');
        
        $this->alterColumn('et_ophinvisualfields_image', 'left_field_id', 'int(10) unsigned DEFAULT NOT NULL');
        $this->alterColumn('et_ophinvisualfields_image', 'right_field_id', 'int(10) unsigned DEFAULT NOT NULL');
        
        return true;
    }

}
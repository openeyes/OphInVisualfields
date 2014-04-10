<?php

class m140410_094713_remove_xml extends CDbMigration {

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp() {
        // TODO for Cardiff migration will need to check this one:
        $this->dropTable('ophinvisualfields_humphrey_xml_version');
        $this->dropTable('ophinvisualfields_humphrey_xml');
    }

    public function safeDown() {
        
    }

}